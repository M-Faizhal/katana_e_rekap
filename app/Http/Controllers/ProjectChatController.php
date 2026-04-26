<?php

namespace App\Http\Controllers;

use App\Models\ProjectChat;
use App\Models\ProjectChatFile;
use App\Models\ProjectChatMention;
use App\Models\Proyek;
use App\Models\User;
use App\Notifications\ProjectChatMessageNotification;
use App\Notifications\ProjectChatMentionedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProjectChatController extends Controller
{
    // ─── Pages ───────────────────────────────────────────────────────────────

    /**
     * Tampilkan halaman chat proyek
     */
    public function index(int $idProyek)
    {
        $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'wilayah'])
            ->where('id_proyek', $idProyek)
            ->firstOrFail();

        $chats = ProjectChat::with(['user', 'files', 'replyTo.user', 'replyTo.files'])
            ->where('id_proyek', $idProyek)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($chat) => $this->formatChat($chat));

        $jumlahPesan = ProjectChat::where('id_proyek', $idProyek)->count();

        return view('pages.chat.index', compact('proyek', 'chats', 'jumlahPesan'));
    }

    // ─── Actions ─────────────────────────────────────────────────────────────

    /**
     * Kirim pesan baru + notifikasi + mention parsing
     */
    public function send(Request $request, int $idProyek)
    {
        $request->validate([
            'message'     => 'nullable|string|max:5000',
            'reply_to_id' => 'nullable|integer|exists:project_chats,id',
            'files.*'     => 'nullable|file|max:20480',
        ]);

        if (empty(trim($request->message ?? '')) && !$request->hasFile('files')) {
            return back()->withErrors(['message' => 'Pesan atau file harus diisi.'])->withInput();
        }

        $proyek = Proyek::where('id_proyek', $idProyek)->firstOrFail();

        // ── 1. Simpan chat ────────────────────────────────────────────────────
        try {
            $chat = ProjectChat::create([
                'id_proyek'   => $idProyek,
                'user_id'     => Auth::user()->id_user,
                'message'     => $request->message ? trim($request->message) : null,
                'reply_to_id' => $request->reply_to_id ?: null,
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('chat-files/' . $idProyek, 'public');
                    ProjectChatFile::create([
                        'chat_id'   => $chat->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('ProjectChat send error: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Gagal mengirim pesan.'])->withInput();
        }

        // Eager-load files agar snippet/notif benar
        $chat->load(['user', 'files']);

        // ── 2. Notifikasi pesan baru ke semua user kecuali pengirim ──────────
        try {
            $senderId = Auth::user()->id_user;
            $allUsers = User::where('id_user', '!=', $senderId)->get();

            foreach ($allUsers as $user) {
                $user->notify(new ProjectChatMessageNotification($chat, $proyek));
            }
        } catch (\Throwable $e) {
            Log::warning('ProjectChat message notification error: ' . $e->getMessage());
        }

        // ── 3. Parse mention & simpan ke pivot ───────────────────────────────
        try {
            $mentionedUserIds = $this->parseMentions($chat->message ?? '');

            foreach ($mentionedUserIds as $userId) {
                // Jangan mention diri sendiri
                if ($userId === Auth::user()->id_user) {
                    continue;
                }

                // Upsert ke pivot (unique constraint mencegah duplikat)
                ProjectChatMention::firstOrCreate([
                    'chat_id'            => $chat->id,
                    'mentioned_user_id'  => $userId,
                ]);

                // Kirim mention notification
                $mentionedUser = User::find($userId);
                if ($mentionedUser) {
                    $mentionedUser->notify(new ProjectChatMentionedNotification($chat, $proyek));
                }
            }
        } catch (\Throwable $e) {
            Log::warning('ProjectChat mention error: ' . $e->getMessage());
        }

        return redirect()->route('chat.proyek', $idProyek);
    }

    /**
     * Download file chat
     */
    public function downloadFile(int $fileId)
    {
        $file = ProjectChatFile::findOrFail($fileId);
        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }

    // ─── JSON Endpoints ───────────────────────────────────────────────────────

    /**
     * GET /chat/users?q=...
     * Kembalikan daftar user untuk autocomplete mention.
     */
    public function searchUsers(Request $request)
    {
        $q = trim($request->get('q', ''));

        $users = User::query()
            ->when($q !== '', fn($query) =>
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('username', 'like', "%{$q}%")
            )
            ->orderBy('nama')
            ->limit(10)
            ->get(['id_user', 'nama', 'username', 'foto']);

        return response()->json(
            $users->map(fn($u) => [
                'id_user'  => $u->id_user,
                'nama'     => $u->nama,
                'username' => $u->username,
                'foto'     => $u->foto ? asset('storage/' . $u->foto) : null,
            ])
        );
    }

    /**
     * GET /notifications/unread-count
     * Dipakai polling badge di header.
     */
    public function unreadCount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return response()->json([
            'unread' => $user->unreadNotifications()->count(),
        ]);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Parse semua @Nama Lengkap yang valid dari message.
     * Aturan: @ harus di awal token (awal string atau setelah spasi/newline).
     * Tidak memicu untuk email seperti test@domain.com.
     *
     * @return array<int> id_user yang ditemukan
     */
    private function parseMentions(string $message): array
    {
        if (blank($message)) {
            return [];
        }

        // Regex: @ di awal string atau setelah whitespace, diikuti nama (huruf, spasi, titik, tanda hubung)
        // Minimal 2 karakter setelah @
        preg_match_all('/(?:^|(?<=\s))@([A-Za-z][A-Za-z\s.\-]{1,60}?)(?=\s|$)/u', $message, $matches);

        $foundNames  = array_unique($matches[1] ?? []);
        $userIds     = [];

        foreach ($foundNames as $name) {
            $name = trim($name);
            if (empty($name)) {
                continue;
            }

            $user = User::where('nama', $name)->first();
            if ($user) {
                $userIds[] = $user->id_user;
            }
        }

        return array_unique($userIds);
    }

    private function formatChat(ProjectChat $chat): array
    {
        $user = $chat->user;

        return [
            'id'         => $chat->id,
            'message'    => $chat->message,
            'created_at' => $chat->created_at,
            'is_mine'    => $chat->user_id === Auth::user()->id_user,
            'user'       => $user ? [
                'id'       => $user->id_user,
                'nama'     => $user->nama,
                'username' => $user->username,
                'foto'     => $user->foto ? asset('storage/' . $user->foto) : null,
                'inisial'  => $this->getInisial($user->nama),
                'warna'    => $this->getWarna($user->id_user),
            ] : [
                'id' => 0, 'nama' => 'Unknown', 'username' => '-',
                'foto' => null, 'inisial' => '??', 'warna' => 'bg-gray-500',
            ],
            'reply_to'   => $chat->replyTo ? [
                'id'      => $chat->replyTo->id,
                'message' => $chat->replyTo->message,
                'user'    => $chat->replyTo->user?->nama ?? 'Unknown',
                'file'    => $chat->replyTo->files->first()?->file_name,
            ] : null,
            'files'      => $chat->files->map(fn($f) => [
                'id'        => $f->id,
                'file_name' => $f->file_name,
                'file_type' => $f->file_type,
                'file_size' => $f->formatted_size,
                'url'       => $f->url,
                'is_image'  => $f->is_image,
                'ext'       => strtolower(pathinfo($f->file_name, PATHINFO_EXTENSION)),
            ])->values()->toArray(),
        ];
    }

    private function getInisial(string $nama): string
    {
        $words = explode(' ', trim($nama));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($nama, 0, 2));
    }

    private function getWarna(int $userId): string
    {
        $colors = [
            'bg-red-500', 'bg-blue-500', 'bg-emerald-500', 'bg-amber-500',
            'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-orange-500',
            'bg-teal-500', 'bg-cyan-500',
        ];
        return $colors[$userId % count($colors)];
    }
}