<?php

namespace App\Http\Controllers;

use App\Models\ProjectChat;
use App\Models\ProjectChatFile;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProjectChatController extends Controller
{
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

    /**
     * Kirim pesan baru
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

        Proyek::where('id_proyek', $idProyek)->firstOrFail();

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

        return redirect()->route('chat.proyek', $idProyek)->with('scrollToBottom', true);
    }

    /**
     * Download file chat
     */
    public function downloadFile(int $fileId)
    {
        $file = ProjectChatFile::findOrFail($fileId);
        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

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
