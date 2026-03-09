<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKost;
use App\Models\PengajuanKostBukti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PengajuanKostController extends Controller
{
    /**
     * Daftar semua pengajuan kost
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = PengajuanKost::with(['picMarketing', 'buktiBayar'])
            ->orderBy('created_at', 'desc');

        

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%")
                  ->orWhereHas('picMarketing', fn($u) => $u->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($status && in_array($status, ['menunggu', 'disetujui', 'revisi'])) {
            $query->where('status', $status);
        }

        $pengajuanList = $query->paginate(10)->withQueryString();

        $stats = [
            'menunggu'  => PengajuanKost::where('status', 'menunggu')->count(),
            'disetujui' => PengajuanKost::where('status', 'disetujui')->count(),
            'revisi'    => PengajuanKost::where('status', 'revisi')->count(),
        ];

        // Semua user aktif bisa menjadi PIC
        $allUsers = User::orderBy('nama')->get(['id_user', 'nama', 'jabatan', 'role']);

        return view('pages.marketing.pengajuan-kost.index', compact(
            'pengajuanList',
            'stats',
            'allUsers',
        ));
    }

    /**
     * Simpan pengajuan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_kegiatan'        => 'nullable|date',
            'tanggal_kegiatan_sampai' => 'nullable|date|after_or_equal:tanggal_kegiatan',
            'tanggal_pengajuan'       => 'nullable|date',
            'pic_marketing_id'        => 'required|exists:users,id_user',
            'lokasi'                  => 'nullable|string|max:255',
            'kota'                    => 'nullable|string|max:100',
            'keterangan_kegiatan'     => 'nullable|string|max:1000',
            'nominal'                 => 'nullable|numeric|min:0',
            'catatan'                 => 'nullable|string|max:1000',
            'bukti_files.*'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'tanggal_kegiatan_sampai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        DB::beginTransaction();
        try {
            $kode = PengajuanKost::generateKode();

            $pengajuan = PengajuanKost::create([
                'kode_pengajuan'          => $kode,
                'tanggal_kegiatan'        => $validated['tanggal_kegiatan'],
                'tanggal_kegiatan_sampai' => $validated['tanggal_kegiatan_sampai'] ?? null,
                'tanggal_pengajuan'       => $validated['tanggal_pengajuan'],
                'pic_marketing_id'        => $validated['pic_marketing_id'],
                'lokasi'                  => $validated['lokasi'],
                'kota'                    => $validated['kota'] ?? null,
                'keterangan_kegiatan'     => $validated['keterangan_kegiatan'] ?? null,
                'nominal'                 => $validated['nominal'],
                'catatan'                 => $validated['catatan'] ?? null,
                'status'                  => 'menunggu',
                'created_by'              => Auth::id(),
            ]);

            if ($request->hasFile('bukti_files')) {
                foreach ($request->file('bukti_files') as $file) {
                    $path = $file->store('pengajuan-kost', 'public');
                    PengajuanKostBukti::create([
                        'pengajuan_kost_id' => $pengajuan->id,
                        'file_path'         => $path,
                        'file_name'         => $file->getClientOriginalName(),
                        'file_type'         => strtolower($file->getClientOriginalExtension()),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Pengajuan kost {$kode} berhasil disimpan.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PengajuanKost store error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
        }
    }

    /**
     * Detail satu pengajuan (JSON untuk modal)
     */
    public function show(int $id)
    {
        $pengajuan = PengajuanKost::with(['picMarketing', 'createdBy', 'verifiedBy', 'buktiBayar'])
            ->findOrFail($id);

        $pengajuan->buktiBayar->transform(function ($bukti) {
            $bukti->url = asset('storage/' . $bukti->file_path);
            return $bukti;
        });

        return response()->json(['success' => true, 'data' => $pengajuan]);
    }

    /**
     * Update pengajuan (hanya boleh jika status 'menunggu' atau 'revisi')
     * Jika status revisi → otomatis kembali ke 'menunggu' setelah diupdate
     */
    public function update(Request $request, int $id)
    {
        $pengajuan = PengajuanKost::findOrFail($id);

        if (!$pengajuan->canEdit()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan yang sudah disetujui tidak dapat diubah.',
            ], 422);
        }

        $validated = $request->validate([
            'tanggal_kegiatan'        => 'required|date',
            'tanggal_kegiatan_sampai' => 'nullable|date|after_or_equal:tanggal_kegiatan',
            'tanggal_pengajuan'       => 'required|date',
            'pic_marketing_id'        => 'required|exists:users,id_user',
            'lokasi'                  => 'required|string|max:255',
            'kota'                    => 'nullable|string|max:100',
            'keterangan_kegiatan'     => 'nullable|string|max:1000',
            'nominal'                 => 'required|numeric|min:0',
            'catatan'                 => 'nullable|string|max:1000',
            'bukti_files.*'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'tanggal_kegiatan_sampai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        DB::beginTransaction();
        try {
            // Jika sedang direvisi → reset ke menunggu setelah diperbaiki
            $newStatus = $pengajuan->status === 'revisi' ? 'menunggu' : $pengajuan->status;

            $pengajuan->update(array_merge($validated, [
                'status'             => $newStatus,
                // Reset data verifikasi agar keuangan review ulang
                'verified_by'        => $pengajuan->status === 'revisi' ? null : $pengajuan->verified_by,
                'tanggal_verifikasi' => $pengajuan->status === 'revisi' ? null : $pengajuan->tanggal_verifikasi,
                'catatan_keuangan'   => $pengajuan->status === 'revisi' ? null : $pengajuan->catatan_keuangan,
            ]));

            if ($request->hasFile('bukti_files')) {
                foreach ($request->file('bukti_files') as $file) {
                    $path = $file->store('pengajuan-kost', 'public');
                    PengajuanKostBukti::create([
                        'pengajuan_kost_id' => $pengajuan->id,
                        'file_path'         => $path,
                        'file_name'         => $file->getClientOriginalName(),
                        'file_type'         => strtolower($file->getClientOriginalExtension()),
                    ]);
                }
            }

            DB::commit();

            $msg = $newStatus === 'menunggu' && $pengajuan->getOriginal('status') === 'revisi'
                ? 'Pengajuan berhasil diperbaiki dan diajukan ulang.'
                : 'Pengajuan berhasil diperbarui.';

            return response()->json(['success' => true, 'message' => $msg]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PengajuanKost update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }

    /**
     * Hapus pengajuan (hanya jika status 'menunggu' atau 'revisi')
     */
    public function destroy(int $id)
    {
        $pengajuan = PengajuanKost::with('buktiBayar')->findOrFail($id);

        if (!$pengajuan->canEdit()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan yang sudah disetujui tidak dapat dihapus.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($pengajuan->buktiBayar as $bukti) {
                Storage::disk('public')->delete($bukti->file_path);
            }

            $pengajuan->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dihapus.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PengajuanKost destroy error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }

    /**
     * Hapus satu file bukti
     */
    public function deleteBukti(int $buktiId)
    {
        $bukti = PengajuanKostBukti::with('pengajuanKost')->findOrFail($buktiId);

        if (!$bukti->pengajuanKost->canEdit()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus bukti pengajuan yang sudah disetujui.'], 422);
        }

        Storage::disk('public')->delete($bukti->file_path);
        $bukti->delete();

        return response()->json(['success' => true, 'message' => 'Bukti berhasil dihapus.']);
    }

    /**
     * Preview / download file bukti
     */
    public function previewBukti(int $buktiId)
    {
        $bukti = PengajuanKostBukti::findOrFail($buktiId);

        if (!Storage::disk('public')->exists($bukti->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($bukti->file_path));
    }
}
