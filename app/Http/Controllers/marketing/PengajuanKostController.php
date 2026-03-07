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
     * Daftar semua pengajuan kost milik marketing yang login
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = PengajuanKost::with(['picMarketing', 'buktiBayar'])
            ->orderBy('created_at', 'desc');

        // Marketing biasa hanya lihat milik sendiri; superadmin & manager bisa lihat semua
        $user = Auth::user();
        if (!in_array($user->role, ['superadmin']) && $user->jabatan !== 'manager_marketing') {
            $query->where('created_by', $user->id_user);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%")
                  ->orWhereHas('picMarketing', fn($u) => $u->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($status && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        $pengajuanList = $query->paginate(10)->withQueryString();

        $stats = [
            'menunggu'  => PengajuanKost::where('status', 'menunggu')->count(),
            'disetujui' => PengajuanKost::where('status', 'disetujui')->count(),
            'ditolak'   => PengajuanKost::where('status', 'ditolak')->count(),
        ];

        // Daftar user marketing untuk form PIC
        $marketingUsers = User::whereIn('role', ['admin_marketing', 'superadmin'])
            ->orderBy('nama')
            ->get(['id_user', 'nama', 'jabatan']);

        return view('pages.marketing.pengajuan-kost.index', compact(
            'pengajuanList',
            'stats',
            'marketingUsers',
        ));
    }

    /**
     * Simpan pengajuan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_kegiatan'    => 'nullable|date',
            'tanggal_pengajuan'   => 'nullable|date',
            'pic_marketing_id'    => 'required|exists:users,id_user',
            'lokasi'              => 'nullable|string|max:255',
            'kota'                => 'nullable|string|max:100',
            'keterangan_kegiatan' => 'nullable|string|max:1000',
            'nominal'             => 'nullable|numeric|min:0',
            'catatan'             => 'nullable|string|max:1000',
            'bukti_files.*'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $kode = PengajuanKost::generateKode();

            $pengajuan = PengajuanKost::create([
                'kode_pengajuan'      => $kode,
                'tanggal_kegiatan'    => $validated['tanggal_kegiatan'],
                'tanggal_pengajuan'   => $validated['tanggal_pengajuan'],
                'pic_marketing_id'    => $validated['pic_marketing_id'],
                'lokasi'              => $validated['lokasi'],
                'kota'                => $validated['kota'] ?? null,
                'keterangan_kegiatan' => $validated['keterangan_kegiatan'] ?? null,
                'nominal'             => $validated['nominal'],
                'catatan'             => $validated['catatan'] ?? null,
                'status'              => 'menunggu',
                'created_by'          => Auth::id(),
            ]);

            // Upload multiple bukti
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
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Detail satu pengajuan (JSON untuk modal)
     */
    public function show(int $id)
    {
        $pengajuan = PengajuanKost::with(['picMarketing', 'createdBy', 'verifiedBy', 'buktiBayar'])
            ->findOrFail($id);

        // Tambahkan URL untuk setiap bukti
        $pengajuan->buktiBayar->transform(function ($bukti) {
            $bukti->url = asset('storage/' . $bukti->file_path);
            return $bukti;
        });

        return response()->json(['success' => true, 'data' => $pengajuan]);
    }

    /**
     * Update pengajuan (hanya boleh jika masih status 'menunggu')
     */
    public function update(Request $request, int $id)
    {
        $pengajuan = PengajuanKost::findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan yang sudah diverifikasi tidak dapat diubah.',
            ], 422);
        }

        $validated = $request->validate([
            'tanggal_kegiatan'    => 'required|date',
            'tanggal_pengajuan'   => 'required|date',
            'pic_marketing_id'    => 'required|exists:users,id_user',
            'lokasi'              => 'required|string|max:255',
            'kota'                => 'nullable|string|max:100',
            'keterangan_kegiatan' => 'nullable|string|max:1000',
            'nominal'             => 'required|numeric|min:0',
            'catatan'             => 'nullable|string|max:1000',
            'bukti_files.*'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $pengajuan->update($validated);

            // Upload bukti baru jika ada
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
            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil diperbarui.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PengajuanKost update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }

    /**
     * Hapus pengajuan (hanya jika masih 'menunggu')
     */
    public function destroy(int $id)
    {
        $pengajuan = PengajuanKost::with('buktiBayar')->findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan yang sudah diverifikasi tidak dapat dihapus.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Hapus file dari storage
            foreach ($pengajuan->buktiBayar as $bukti) {
                Storage::disk('public')->delete($bukti->file_path);
            }

            $pengajuan->delete(); // cascade hapus buktiBayar juga
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

        if ($bukti->pengajuanKost->status !== 'menunggu') {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus bukti pengajuan yang sudah diverifikasi.'], 422);
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
