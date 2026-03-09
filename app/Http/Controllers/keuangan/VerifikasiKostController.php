<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifikasiKostController extends Controller
{
    /**
     * List semua pengajuan kost
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'menunggu');

        $query = PengajuanKost::with(['picMarketing', 'createdBy', 'buktiBayar'])
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
            'total'     => PengajuanKost::count(),
        ];

        return view('pages.keuangan.verifikasi-kost.index', compact(
            'pengajuanList',
            'stats',
            'status',
        ));
    }

    /**
     * Detail pengajuan (JSON untuk modal)
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
     * Setujui pengajuan kost
     */
    public function approve(Request $request, int $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (! $user->hasAnyRole(['superadmin', 'admin_keuangan'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menyetujui pengajuan.',
            ], 403);
        }

        $pengajuan = PengajuanKost::findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajuan berstatus menunggu yang dapat disetujui.',
            ], 422);
        }

        $validated = $request->validate([
            'catatan_keuangan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $pengajuan->update([
                'status'             => 'disetujui',
                'verified_by'        => Auth::id(),
                'tanggal_verifikasi' => now(),
                'catatan_keuangan'   => $validated['catatan_keuangan'] ?? null,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Pengajuan {$pengajuan->kode_pengajuan} berhasil disetujui.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('VerifikasiKost approve error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }

    /**
     * Minta revisi pengajuan kost — pengaju harus memperbaiki dan mengajukan ulang
     */
    public function revision(Request $request, int $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (! $user->hasAnyRole(['superadmin', 'admin_keuangan'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk meminta revisi pengajuan.',
            ], 403);
        }

        $pengajuan = PengajuanKost::findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajuan berstatus menunggu yang dapat diminta revisi.',
            ], 422);
        }

        $validated = $request->validate([
            'catatan_keuangan' => 'required|string|max:1000',
        ], [
            'catatan_keuangan.required' => 'Catatan/alasan revisi wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $pengajuan->update([
                'status'             => 'revisi',
                'verified_by'        => Auth::id(),
                'tanggal_verifikasi' => now(),
                'catatan_keuangan'   => $validated['catatan_keuangan'],
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Pengajuan {$pengajuan->kode_pengajuan} dikembalikan untuk direvisi.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('VerifikasiKost revision error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }
}
