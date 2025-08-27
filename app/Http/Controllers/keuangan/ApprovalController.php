<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Penawaran;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApprovalController extends Controller
{
    /**
     * Display pending payments for approval
     */
    public function index()
    {
        $pendingPayments = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor', 'vendor', 'verifikator'])
            ->where('status_verifikasi', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $totalPending = Pembayaran::where('status_verifikasi', 'Pending')->count();
        $totalApproved = Pembayaran::where('status_verifikasi', 'Approved')->count();
        $totalRejected = Pembayaran::where('status_verifikasi', 'Ditolak')->count();
        $totalAll = Pembayaran::count();

        return view('pages.keuangan.approval', compact(
            'pendingPayments',
            'totalPending',
            'totalApproved', 
            'totalRejected',
            'totalAll'
        ));
    }

    /**
     * Display approved payments
     */
    public function approved()
    {
        $approvedPayments = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor', 'vendor', 'verifikator'])
            ->where('status_verifikasi', 'Approved')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('pages.keuangan.approval-components.approved', compact('approvedPayments'));
    }

    /**
     * Display rejected payments
     */
    public function rejected()
    {
        $rejectedPayments = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor', 'vendor', 'verifikator'])
            ->where('status_verifikasi', 'Ditolak')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('pages.keuangan.approval-components.rejected', compact('rejectedPayments'));
    }

    /**
     * Show payment detail for approval
     */
    public function detail($id_pembayaran)
    {
        $pembayaran = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor', 'vendor', 'verifikator'])
            ->findOrFail($id_pembayaran);

        $proyek = $pembayaran->penawaran->proyek;
        $vendorId = $pembayaran->id_vendor;

        // Hitung total modal untuk vendor ini dengan lebih hati-hati
        $detailsForVendor = $proyek->penawaranAktif->penawaranDetail
            ->filter(function($detail) use ($vendorId) {
                return $detail->barang && $detail->barang->id_vendor == $vendorId;
            });

        $totalModalVendor = $detailsForVendor->sum(function($detail) {
            return $detail->qty * ($detail->barang->harga_vendor ?? 0);
        });

        // Hitung total yang sudah dibayar untuk vendor ini (hanya approved)
        $totalApproved = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
            ->where('id_vendor', $vendorId)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        // Hitung total pembayaran keseluruhan proyek (semua vendor)
        $totalPenawaran = $pembayaran->penawaran->total_penawaran;

        // Hitung sisa bayar untuk vendor ini (modal vendor - yang sudah dibayar)
        $sisaBayar = $totalModalVendor - $totalApproved;

        return view('pages.keuangan.approval-components.detail', compact(
            'pembayaran', 
            'totalModalVendor', 
            'totalApproved', 
            'totalPenawaran',
            'sisaBayar'
        ));
    }

    /**
     * Approve payment
     */
    public function approve(Request $request, $id_pembayaran)
    {
        // Role-based access control
        if (Auth::user()->role !== 'admin_keuangan') {
            return redirect()->route('keuangan.approval')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan yang dapat melakukan approve pembayaran.');
        }

        $pembayaran = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor'])
            ->findOrFail($id_pembayaran);

        // Pastikan status masih pending
        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('keuangan.approval')
                ->with('error', 'Pembayaran sudah diproses sebelumnya');
        }

        DB::beginTransaction();
        try {
            // Update status pembayaran
            $pembayaran->update([
                'status_verifikasi' => 'Approved',
                'diverifikasi_oleh' => Auth::user()->id_user,
                'tanggal_verifikasi' => now(),
                'catatan' => $request->catatan
            ]);

            // Hitung ulang status lunas untuk vendor ini
            $proyek = $pembayaran->penawaran->proyek;
            
            // Hitung total modal untuk vendor ini
            $totalModalVendor = $proyek->penawaranAktif->penawaranDetail
                ->where('barang.id_vendor', $pembayaran->id_vendor)
                ->sum(function($detail) {
                    return $detail->qty * $detail->barang->harga_vendor;
                });

            // Hitung total yang sudah dibayar untuk vendor ini (termasuk yang baru di-approve)
            $totalDibayarVendor = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
                ->where('id_vendor', $pembayaran->id_vendor)
                ->where('status_verifikasi', 'Approved')
                ->sum('nominal_bayar');

            // Check apakah semua vendor sudah lunas
            $allVendorsData = $proyek->penawaranAktif->penawaranDetail
                ->groupBy('barang.id_vendor')
                ->map(function($details, $vendorId) use ($proyek) {
                    $totalVendor = $details->sum(function($detail) {
                        return $detail->qty * $detail->barang->harga_vendor;
                    });

                    $totalDibayarVendor = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                        ->where('id_vendor', $vendorId)
                        ->where('status_verifikasi', 'Approved')
                        ->sum('nominal_bayar');

                    return $totalVendor <= $totalDibayarVendor;
                });

            // Update status proyek jika semua vendor sudah lunas
            if ($allVendorsData->every(function($isLunas) {
                return $isLunas;
            })) {
                $proyek->update(['status' => 'Pengiriman']);
            }

            DB::commit();

            return redirect()->route('keuangan.approval')
                ->with('success', 'Pembayaran berhasil disetujui');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menyetujui pembayaran');
        }
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, $id_pembayaran)
    {
        // Role-based access control
        if (Auth::user()->role !== 'admin_keuangan') {
            return redirect()->route('keuangan.approval')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan yang dapat melakukan reject pembayaran.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|min:10'
        ]);

        $pembayaran = Pembayaran::findOrFail($id_pembayaran);

        // Pastikan status masih pending
        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('keuangan.approval')
                ->with('error', 'Pembayaran sudah diproses sebelumnya');
        }

        DB::beginTransaction();
        try {
            // Update status pembayaran
            $pembayaran->update([
                'status_verifikasi' => 'Ditolak',
                'diverifikasi_oleh' => Auth::user()->id_user,
                'tanggal_verifikasi' => now(),
                'catatan' => $request->alasan_penolakan
            ]);

            DB::commit();

            return redirect()->route('keuangan.approval')
                ->with('success', 'Pembayaran berhasil ditolak');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menolak pembayaran');
        }
    }
}
