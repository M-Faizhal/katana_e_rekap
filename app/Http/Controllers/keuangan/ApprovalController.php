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
     * ALUR KERJA OTOMATIS PERUBAHAN STATUS PROYEK:
     * 
     * 1. Admin Keuangan approve pembayaran
     * 2. System cek apakah semua vendor sudah lunas:
     *    - Hitung total modal untuk setiap vendor
     *    - Hitung total yang sudah dibayar untuk setiap vendor
     *    - Bandingkan: jika total dibayar >= total modal, vendor dianggap lunas
     * 3. Jika SEMUA vendor sudah lunas DAN status proyek masih "Penawaran" atau "Pembayaran":
     *    - Otomatis ubah status proyek menjadi "Pengiriman"
     *    - Log perubahan untuk audit trail
     * 4. Berikan feedback kepada user tentang perubahan status
     */

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
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.approval')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat melakukan approve pembayaran.');
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

            // Hitung ulang status lunas untuk semua vendor dalam proyek
            $proyek = $pembayaran->penawaran->proyek;
            
            // Log untuk debugging
            Log::info('Checking project payment status after approval', [
                'project_id' => $proyek->id_proyek,
                'payment_id' => $pembayaran->id_pembayaran,
                'vendor_id' => $pembayaran->id_vendor,
                'current_project_status' => $proyek->status
            ]);

            // Check apakah semua vendor sudah lunas
            $allVendorsLunas = $this->checkAllVendorsPaid($proyek);
            $vendorSummary = $this->getVendorPaymentSummary($proyek);

            // Log vendor payment summary
            Log::info('Vendor payment summary', [
                'project_id' => $proyek->id_proyek,
                'vendor_summary' => $vendorSummary,
                'all_vendors_lunas' => $allVendorsLunas
            ]);

            // Update status proyek jika semua vendor sudah lunas DAN proyek dalam status yang tepat
            $statusChanged = false;
            if ($allVendorsLunas && in_array($proyek->status, ['Penawaran', 'Pembayaran'])) {
                $oldStatus = $proyek->status;
                $proyek->update(['status' => 'Pengiriman']);
                $statusChanged = true;
                
                Log::info('Project status updated to Pengiriman', [
                    'project_id' => $proyek->id_proyek,
                    'old_status' => $oldStatus,
                    'new_status' => 'Pengiriman',
                    'triggered_by_payment' => $pembayaran->id_pembayaran,
                    'approved_by' => Auth::user()->id_user
                ]);
            }

            DB::commit();

            // Prepare success message
            $successMessage = 'Pembayaran berhasil disetujui';
            if ($statusChanged) {
                $successMessage .= '. Status proyek otomatis berubah menjadi "Pengiriman" karena semua vendor sudah lunas.';
            }

            return redirect()->route('keuangan.approval')
                ->with('success', $successMessage);

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
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.approval')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat melakukan reject pembayaran.');
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

    /**
     * Helper method: Check if all vendors in a project are fully paid
     */
    private function checkAllVendorsPaid($proyek)
    {
        $vendorGroups = $proyek->penawaranAktif->penawaranDetail
            ->filter(function($detail) {
                return $detail->barang && $detail->barang->id_vendor;
            })
            ->groupBy('barang.id_vendor');

        foreach ($vendorGroups as $vendorId => $details) {
            // Hitung total modal untuk vendor ini
            $totalVendor = $details->sum(function($detail) {
                return $detail->qty * ($detail->barang->harga_vendor ?? 0);
            });

            // Hitung total yang sudah dibayar untuk vendor ini
            $totalDibayarVendor = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                ->where('id_vendor', $vendorId)
                ->where('status_verifikasi', 'Approved')
                ->sum('nominal_bayar');

            // Jika ada vendor yang belum lunas, return false
            if ($totalDibayarVendor < $totalVendor) {
                return false;
            }
        }

        return true;
    }

    /**
     * Helper method: Get vendor payment summary for a project
     */
    private function getVendorPaymentSummary($proyek)
    {
        $vendorSummary = [];
        $vendorGroups = $proyek->penawaranAktif->penawaranDetail
            ->filter(function($detail) {
                return $detail->barang && $detail->barang->id_vendor;
            })
            ->groupBy('barang.id_vendor');

        foreach ($vendorGroups as $vendorId => $details) {
            $totalVendor = $details->sum(function($detail) {
                return $detail->qty * ($detail->barang->harga_vendor ?? 0);
            });

            $totalDibayarVendor = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                ->where('id_vendor', $vendorId)
                ->where('status_verifikasi', 'Approved')
                ->sum('nominal_bayar');

            $vendorSummary[$vendorId] = [
                'total_modal' => $totalVendor,
                'total_dibayar' => $totalDibayarVendor,
                'sisa' => $totalVendor - $totalDibayarVendor,
                'lunas' => $totalDibayarVendor >= $totalVendor,
                'vendor_name' => $details->first()->barang->vendor->nama_vendor ?? 'Unknown'
            ];
        }

        return $vendorSummary;
    }

    /**
     * API endpoint: Get project payment status
     */
    public function getProjectPaymentStatus($proyekId)
    {
        try {
            $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor'])->findOrFail($proyekId);
            
            if (!$proyek->penawaranAktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyek belum memiliki penawaran aktif'
                ], 404);
            }

            $allVendorsLunas = $this->checkAllVendorsPaid($proyek);
            $vendorSummary = $this->getVendorPaymentSummary($proyek);

            return response()->json([
                'success' => true,
                'data' => [
                    'project_id' => $proyek->id_proyek,
                    'project_status' => $proyek->status,
                    'all_vendors_paid' => $allVendorsLunas,
                    'vendor_summary' => $vendorSummary,
                    'can_change_to_pengiriman' => $allVendorsLunas && in_array($proyek->status, ['Penawaran', 'Pembayaran'])
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
