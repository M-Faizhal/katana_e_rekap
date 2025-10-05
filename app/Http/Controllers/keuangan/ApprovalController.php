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
     * ALUR KERJA OTOMATIS PERUBAHAN STATUS PROYEK (DIMODIFIKASI):
     * 
     * 1. Admin Keuangan approve pembayaran
     * 2. System langsung mengubah status proyek menjadi "Pengiriman" jika:
     *    - Status proyek saat ini adalah "Penawaran" atau "Pembayaran"
     *    - Ada pembayaran yang di-approve (tidak perlu menunggu lunas)
     * 3. Log perubahan untuk audit trail
     * 4. Berikan feedback kepada user tentang perubahan status
     * 
     * CATATAN PERUBAHAN:
     * - Tidak lagi mengecek apakah vendor sudah lunas
     * - Status berubah langsung ketika ada pembayaran yang di-approve
     * - Pengiriman bisa dimulai meskipun pembayaran belum lunas (sesuai praktik bisnis)
     */

    /**
     * Display pending payments for approval
     */
    public function index()
    {
        $pendingPayments = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor', 'vendor', 'verifikator'])
            ->where('status_verifikasi', 'Pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
            ->paginate(10);

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
            ->paginate(10);

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
     * Approve payment (LOGIKA DIMODIFIKASI - STATUS BERUBAH SAAT APPROVAL)
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

            // Ambil data proyek
            $proyek = $pembayaran->penawaran->proyek;
            
            // Log untuk debugging
            Log::info('=== PAYMENT APPROVAL PROCESS START ===', [
                'project_id' => $proyek->id_proyek,
                'project_name' => $proyek->nama_proyek ?? 'Unknown',
                'payment_id' => $pembayaran->id_pembayaran,
                'vendor_id' => $pembayaran->id_vendor,
                'payment_amount' => $pembayaran->nominal_bayar,
                'current_project_status' => $proyek->status,
                'approved_by_user_id' => Auth::user()->id_user,
                'approved_by_user_name' => Auth::user()->nama ?? 'Unknown'
            ]);

            // LOGIKA BARU: Ubah status proyek ke "Pengiriman" jika kondisi terpenuhi
            $statusChanged = false;
            $canChangeStatus = in_array($proyek->status, ['Penawaran', 'Pembayaran']);
            
            Log::info('=== STATUS CHANGE EVALUATION ===', [
                'project_id' => $proyek->id_proyek,
                'current_status' => $proyek->status,
                'status_allows_change' => $canChangeStatus,
                'logic' => 'Status akan berubah ke Pengiriman jika proyek dalam status Penawaran atau Pembayaran'
            ]);

            if ($canChangeStatus) {
                $oldStatus = $proyek->status;
                $proyek->update(['status' => 'Pengiriman']);
                $statusChanged = true;
                
                Log::info('=== PROJECT STATUS CHANGED ===', [
                    'project_id' => $proyek->id_proyek,
                    'old_status' => $oldStatus,
                    'new_status' => 'Pengiriman',
                    'triggered_by_payment' => $pembayaran->id_pembayaran,
                    'approved_by' => Auth::user()->id_user,
                    'change_timestamp' => now(),
                    'reason' => 'Payment approved - status automatically changed to allow shipment preparation'
                ]);
            } else {
                Log::info('=== PROJECT STATUS NOT CHANGED ===', [
                    'project_id' => $proyek->id_proyek,
                    'reason' => 'Project status does not allow automatic change',
                    'current_status' => $proyek->status,
                    'allowed_statuses' => ['Penawaran', 'Pembayaran']
                ]);
            }

            // Dapatkan informasi vendor untuk pesan
            $vendorName = 'Unknown';
            if ($pembayaran->vendor) {
                $vendorName = $pembayaran->vendor->nama_vendor;
            }

            DB::commit();

            Log::info('=== PAYMENT APPROVAL PROCESS END ===', [
                'project_id' => $proyek->id_proyek,
                'payment_id' => $pembayaran->id_pembayaran,
                'status_changed' => $statusChanged,
                'final_project_status' => $proyek->fresh()->status,
                'vendor_name' => $vendorName
            ]);

            // Prepare success message
            $successMessage = "Pembayaran dari vendor \"{$vendorName}\" berhasil disetujui";
            if ($statusChanged) {
                $successMessage .= ". Status proyek otomatis berubah menjadi \"Pengiriman\" dan pengiriman dapat segera dipersiapkan.";
            }

            return redirect()->route('keuangan.approval')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('=== PAYMENT APPROVAL ERROR ===', [
                'project_id' => $proyek->id_proyek ?? 'Unknown',
                'payment_id' => $id_pembayaran,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat menyetujui pembayaran: ' . $e->getMessage());
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

            Log::info('Payment rejected', [
                'payment_id' => $id_pembayaran,
                'rejected_by' => Auth::user()->id_user,
                'reason' => $request->alasan_penolakan
            ]);

            return redirect()->route('keuangan.approval')
                ->with('success', 'Pembayaran berhasil ditolak');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Payment rejection error', [
                'payment_id' => $id_pembayaran,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat menolak pembayaran');
        }
    }

    /**
     * Helper method: Check if ANY vendor in a project has made payment (DIPERBAHARUI UNTUK INFORMASI)
     */
    private function checkAnyVendorPaid($proyek)
    {
        Log::info('=== CHECKING ANY VENDOR PAYMENT - START ===', [
            'project_id' => $proyek->id_proyek,
            'project_status' => $proyek->status
        ]);

        // Validasi penawaran aktif
        if (!$proyek->penawaranAktif) {
            Log::warning('No active penawaran found', ['project_id' => $proyek->id_proyek]);
            return false;
        }

        // Check apakah ada pembayaran yang sudah approved untuk proyek ini
        $hasApprovedPayment = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
            ->where('status_verifikasi', 'Approved')
            ->exists();

        Log::info('=== ANY VENDOR PAYMENT CHECK COMPLETE ===', [
            'project_id' => $proyek->id_proyek,
            'has_approved_payment' => $hasApprovedPayment
        ]);

        return $hasApprovedPayment;
    }

    /**
     * Helper method: Check if all vendors in a project are fully paid (TETAP DIPERTAHANKAN untuk keperluan lain)
     */
    private function checkAllVendorsPaid($proyek)
    {
        Log::info('=== CHECKING ALL VENDORS PAID - START ===', [
            'project_id' => $proyek->id_proyek,
            'project_status' => $proyek->status
        ]);

        // Validasi penawaran aktif
        if (!$proyek->penawaranAktif) {
            Log::warning('No active penawaran found', ['project_id' => $proyek->id_proyek]);
            return false;
        }

        // Validasi detail penawaran
        if (!$proyek->penawaranAktif->penawaranDetail || $proyek->penawaranAktif->penawaranDetail->isEmpty()) {
            Log::warning('No penawaran detail found', [
                'project_id' => $proyek->id_proyek,
                'penawaran_id' => $proyek->penawaranAktif->id_penawaran
            ]);
            return false;
        }

        Log::info('Penawaran data validation passed', [
            'project_id' => $proyek->id_proyek,
            'penawaran_id' => $proyek->penawaranAktif->id_penawaran,
            'total_detail_count' => $proyek->penawaranAktif->penawaranDetail->count()
        ]);

        // Filter detail yang valid (memiliki barang dan vendor)
        $validDetails = $proyek->penawaranAktif->penawaranDetail->filter(function($detail) {
            $isValid = $detail->barang && $detail->barang->id_vendor;
            if (!$isValid) {
                Log::warning('Invalid detail found', [
                    'detail_id' => $detail->id_penawaran_detail ?? 'Unknown',
                    'has_barang' => !is_null($detail->barang),
                    'has_vendor_id' => $detail->barang ? !is_null($detail->barang->id_vendor) : false
                ]);
            }
            return $isValid;
        });

        if ($validDetails->isEmpty()) {
            Log::warning('No valid details with vendor found', ['project_id' => $proyek->id_proyek]);
            return false;
        }

        Log::info('Valid details found', [
            'project_id' => $proyek->id_proyek,
            'valid_detail_count' => $validDetails->count(),
            'total_detail_count' => $proyek->penawaranAktif->penawaranDetail->count()
        ]);

        // Group by vendor
        $vendorGroups = $validDetails->groupBy('barang.id_vendor');

        Log::info('Vendor groups created', [
            'project_id' => $proyek->id_proyek,
            'vendor_count' => $vendorGroups->count(),
            'vendor_ids' => $vendorGroups->keys()->toArray()
        ]);

        foreach ($vendorGroups as $vendorId => $details) {
            // Hitung total modal untuk vendor ini
            $totalVendor = 0;
            $detailBreakdown = [];
            
            foreach ($details as $detail) {
                $itemTotal = $detail->qty * ($detail->barang->harga_vendor ?? 0);
                $totalVendor += $itemTotal;
                
                $detailBreakdown[] = [
                    'detail_id' => $detail->id_penawaran_detail ?? 'Unknown',
                    'barang_name' => $detail->barang->nama_barang ?? 'Unknown',
                    'qty' => $detail->qty,
                    'harga_vendor' => $detail->barang->harga_vendor ?? 0,
                    'subtotal' => $itemTotal
                ];
            }

            // Hitung total yang sudah dibayar untuk vendor ini
            $pembayaranQuery = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                ->where('id_vendor', $vendorId)
                ->where('status_verifikasi', 'Approved');
            
            $totalDibayarVendor = $pembayaranQuery->sum('nominal_bayar');
            $pembayaranCount = $pembayaranQuery->count();

            // Gunakan perbandingan yang lebih aman untuk floating point
            $isVendorPaid = bccomp($totalDibayarVendor, $totalVendor, 2) >= 0;
            $sisaBayar = bcsub($totalVendor, $totalDibayarVendor, 2);

            Log::info("=== VENDOR PAYMENT CHECK ===", [
                'project_id' => $proyek->id_proyek,
                'vendor_id' => $vendorId,
                'vendor_name' => $details->first()->barang->vendor->nama_vendor ?? 'Unknown',
                'total_modal_vendor' => $totalVendor,
                'total_dibayar_vendor' => $totalDibayarVendor,
                'sisa_bayar' => $sisaBayar,
                'pembayaran_count' => $pembayaranCount,
                'is_vendor_paid' => $isVendorPaid,
                'detail_breakdown' => $detailBreakdown
            ]);

            // Jika ada vendor yang belum lunas, return false
            if (!$isVendorPaid) {
                Log::info("=== VENDOR NOT FULLY PAID ===", [
                    'project_id' => $proyek->id_proyek,
                    'vendor_id' => $vendorId,
                    'vendor_name' => $details->first()->barang->vendor->nama_vendor ?? 'Unknown',
                    'total_needed' => $totalVendor,
                    'total_paid' => $totalDibayarVendor,
                    'remaining' => $sisaBayar
                ]);
                return false;
            }
        }

        Log::info('=== ALL VENDORS PAID CHECK COMPLETE ===', [
            'project_id' => $proyek->id_proyek,
            'result' => true,
            'total_vendors_checked' => $vendorGroups->count()
        ]);

        return true;
    }

    /**
     * Helper method: Get vendor payment summary for a project
     */
    private function getVendorPaymentSummary($proyek)
    {
        $vendorSummary = [];
        
        if (!$proyek->penawaranAktif || !$proyek->penawaranAktif->penawaranDetail) {
            return $vendorSummary;
        }

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

            $sisa = bcsub($totalVendor, $totalDibayarVendor, 2);
            $lunas = bccomp($totalDibayarVendor, $totalVendor, 2) >= 0;

            $vendorSummary[$vendorId] = [
                'total_modal' => $totalVendor,
                'total_dibayar' => $totalDibayarVendor,
                'sisa' => $sisa,
                'lunas' => $lunas,
                'vendor_name' => $details->first()->barang->vendor->nama_vendor ?? 'Unknown',
                'percentage_paid' => $totalVendor > 0 ? round(($totalDibayarVendor / $totalVendor) * 100, 2) : 0
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

            $hasAnyPayment = $this->checkAnyVendorPaid($proyek);
            $allVendorsLunas = $this->checkAllVendorsPaid($proyek);
            $vendorSummary = $this->getVendorPaymentSummary($proyek);

            return response()->json([
                'success' => true,
                'data' => [
                    'project_id' => $proyek->id_proyek,
                    'project_name' => $proyek->nama_proyek,
                    'project_status' => $proyek->status,
                    'has_any_payment' => $hasAnyPayment,
                    'all_vendors_paid' => $allVendorsLunas,
                    'vendor_summary' => $vendorSummary,
                    'can_change_to_pengiriman' => in_array($proyek->status, ['Penawaran', 'Pembayaran']),
                    'total_vendors' => count($vendorSummary),
                    'paid_vendors' => collect($vendorSummary)->where('lunas', true)->count(),
                    'unpaid_vendors' => collect($vendorSummary)->where('lunas', false)->count(),
                    'logic_info' => [
                        'status_change_trigger' => 'Payment approval (tidak perlu menunggu lunas)',
                        'allowed_statuses_for_change' => ['Penawaran', 'Pembayaran']
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('API getProjectPaymentStatus error', [
                'project_id' => $proyekId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug method: Manual check project payment status (for testing)
     */
    public function debugProjectPaymentStatus($proyekId)
    {
        if (!app()->environment(['local', 'staging'])) {
            abort(404);
        }

        try {
            $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor'])->findOrFail($proyekId);
            
            $hasAnyPayment = $this->checkAnyVendorPaid($proyek);
            $allVendorsLunas = $this->checkAllVendorsPaid($proyek);
            $vendorSummary = $this->getVendorPaymentSummary($proyek);
            
            return response()->json([
                'project_id' => $proyek->id_proyek,
                'project_name' => $proyek->nama_proyek,
                'current_status' => $proyek->status,
                'has_any_payment' => $hasAnyPayment,
                'all_vendors_paid' => $allVendorsLunas,
                'can_change_status_new_logic' => in_array($proyek->status, ['Penawaran', 'Pembayaran']),
                'can_change_status_old_logic' => $allVendorsLunas && in_array($proyek->status, ['Penawaran', 'Pembayaran']),
                'vendor_summary' => $vendorSummary,
                'total_vendors' => count($vendorSummary),
                'paid_vendors' => collect($vendorSummary)->where('lunas', true)->count(),
                'unpaid_vendors' => collect($vendorSummary)->where('lunas', false)->count(),
                'penawaran_aktif' => $proyek->penawaranAktif ? [
                    'id' => $proyek->penawaranAktif->id_penawaran,
                    'total_detail' => $proyek->penawaranAktif->penawaranDetail->count(),
                    'valid_detail' => $proyek->penawaranAktif->penawaranDetail->filter(function($detail) {
                        return $detail->barang && $detail->barang->id_vendor;
                    })->count()
                ] : null,
                'debug_timestamp' => now(),
                'logic_explanation' => [
                    'old_logic' => 'Status berubah ke Pengiriman hanya jika SEMUA vendor sudah lunas',
                    'new_logic' => 'Status berubah ke Pengiriman SETIAP KALI ada pembayaran yang di-approve',
                    'reasoning' => 'Pengiriman dapat dimulai segera setelah ada pembayaran, tidak perlu menunggu lunas. Ini memungkinkan persiapan pengiriman lebih cepat.',
                    'trigger_condition' => 'Pembayaran di-approve + Status proyek dalam [Penawaran, Pembayaran]'
                ]
            ], 200, [], JSON_PRETTY_PRINT);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Method untuk mendapatkan informasi detail pembayaran per vendor
     */
    public function getVendorPaymentDetails($proyekId, $vendorId = null)
    {
        try {
            $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor'])->findOrFail($proyekId);
            
            if (!$proyek->penawaranAktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyek belum memiliki penawaran aktif'
                ], 404);
            }

            $vendorSummary = $this->getVendorPaymentSummary($proyek);
            
            if ($vendorId) {
                // Return detail untuk vendor specific
                if (!isset($vendorSummary[$vendorId])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vendor tidak ditemukan dalam proyek ini'
                    ], 404);
                }

                // Get payment history untuk vendor ini
                $paymentHistory = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                    ->where('id_vendor', $vendorId)
                    ->with(['verifikator:id_user,nama'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function($payment) {
                        return [
                            'id' => $payment->id_pembayaran,
                            'nominal' => $payment->nominal_bayar,
                            'jenis_pembayaran' => $payment->jenis_pembayaran,
                            'status' => $payment->status_verifikasi,
                            'tanggal_bayar' => $payment->tanggal_bayar,
                            'tanggal_verifikasi' => $payment->tanggal_verifikasi,
                            'verifikator' => $payment->verifikator ? $payment->verifikator->nama : null,
                            'catatan' => $payment->catatan
                        ];
                    });

                return response()->json([
                    'success' => true,
                    'data' => [
                        'vendor_id' => $vendorId,
                        'vendor_info' => $vendorSummary[$vendorId],
                        'payment_history' => $paymentHistory,
                        'project_info' => [
                            'id' => $proyek->id_proyek,
                            'name' => $proyek->nama_proyek,
                            'status' => $proyek->status
                        ]
                    ]
                ]);
            } else {
                // Return summary semua vendor
                return response()->json([
                    'success' => true,
                    'data' => [
                        'project_info' => [
                            'id' => $proyek->id_proyek,
                            'name' => $proyek->nama_proyek,
                            'status' => $proyek->status
                        ],
                        'vendor_summary' => $vendorSummary,
                        'statistics' => [
                            'total_vendors' => count($vendorSummary),
                            'paid_vendors' => collect($vendorSummary)->where('lunas', true)->count(),
                            'unpaid_vendors' => collect($vendorSummary)->where('lunas', false)->count(),
                            'total_project_value' => collect($vendorSummary)->sum('total_modal'),
                            'total_paid' => collect($vendorSummary)->sum('total_dibayar'),
                            'total_remaining' => collect($vendorSummary)->sum('sisa')
                        ],
                        'logic_info' => [
                            'status_change_note' => 'Status proyek berubah ke Pengiriman setiap kali ada pembayaran yang di-approve, tidak perlu menunggu vendor lunas'
                        ]
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::error('API getVendorPaymentDetails error', [
                'project_id' => $proyekId,
                'vendor_id' => $vendorId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method tambahan: Force change project status ke Pengiriman (untuk keperluan manual/admin)
     */
    public function forceChangeToShipment($proyekId)
    {
        try {
            // Role-based access control
            $user = Auth::user();
            if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat mengubah status proyek.'
                ], 403);
            }

            $proyek = Proyek::findOrFail($proyekId);

            // Check apakah status dapat diubah
            if (!in_array($proyek->status, ['Penawaran', 'Pembayaran'])) {
                return response()->json([
                    'success' => false,
                    'message' => "Status proyek saat ini ({$proyek->status}) tidak dapat diubah ke Pengiriman."
                ], 400);
            }

            DB::beginTransaction();
            try {
                $oldStatus = $proyek->status;
                $proyek->update(['status' => 'Pengiriman']);

                Log::info('=== MANUAL PROJECT STATUS CHANGE ===', [
                    'project_id' => $proyek->id_proyek,
                    'project_name' => $proyek->nama_proyek,
                    'old_status' => $oldStatus,
                    'new_status' => 'Pengiriman',
                    'changed_by' => $user->id_user,
                    'changed_by_name' => $user->nama ?? 'Unknown',
                    'change_type' => 'Manual Force Change',
                    'timestamp' => now()
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => "Status proyek berhasil diubah dari \"{$oldStatus}\" menjadi \"Pengiriman\"",
                    'data' => [
                        'project_id' => $proyek->id_proyek,
                        'project_name' => $proyek->nama_proyek,
                        'old_status' => $oldStatus,
                        'new_status' => 'Pengiriman',
                        'changed_by' => $user->nama ?? 'Unknown',
                        'changed_at' => now()
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Force change to shipment error', [
                'project_id' => $proyekId,
                'error' => $e->getMessage(),
                'user_id' => Auth::user()->id_user ?? 'Unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}