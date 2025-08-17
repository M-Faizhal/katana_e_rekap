<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Penawaran;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApprovalController extends Controller
{
    /**
     * Display approval dashboard
     */
    public function index()
    {
        try {
            // Ambil semua pembayaran yang statusnya pending
            $pendingPayments = Pembayaran::with(['penawaran.proyek'])
                ->where('status_verifikasi', 'Pending')
                ->orderBy('created_at', 'desc')
                ->get();

            // Statistik untuk dashboard
            $stats = [
                'pending' => Pembayaran::where('status_verifikasi', 'Pending')->count(),
                'approved' => Pembayaran::where('status_verifikasi', 'Approved')->count(),
                'rejected' => Pembayaran::where('status_verifikasi', 'Ditolak')->count(),
                'total_amount_pending' => Pembayaran::where('status_verifikasi', 'Pending')->sum('nominal_bayar')
            ];

            // Log untuk debugging
            Log::info('Approval Controller - Index', [
                'pending_count' => $pendingPayments->count(),
                'stats' => $stats
            ]);

            return view('pages.keuangan.approval', compact('pendingPayments', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Error in ApprovalController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data approval.');
        }
    }

    /**
     * Show detailed view of a payment for approval
     */
    public function show($id)
    {
        try {
            $pembayaran = Pembayaran::with(['penawaran.proyek'])
                ->findOrFail($id);

            // Hitung total pembayaran yang sudah approved untuk proyek ini
            $totalApproved = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
                ->where('status_verifikasi', 'Approved')
                ->where('id_pembayaran', '!=', $id) // Exclude current payment
                ->sum('nominal_bayar');

            $totalPenawaran = $pembayaran->penawaran->total_penawaran;
            $sisaBayar = $totalPenawaran - $totalApproved;

            return view('pages.keuangan.approval-components.detail', compact(
                'pembayaran', 
                'totalApproved', 
                'totalPenawaran', 
                'sisaBayar'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in ApprovalController@show: ' . $e->getMessage());
            return back()->with('error', 'Pembayaran tidak ditemukan.');
        }
    }

    /**
     * Approve a payment
     */
    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $pembayaran = Pembayaran::findOrFail($id);

            // Validasi status
            if ($pembayaran->status_verifikasi !== 'Pending') {
                return back()->with('error', 'Pembayaran sudah diproses sebelumnya.');
            }

            // Update status menjadi approved
            $pembayaran->update([
                'status_verifikasi' => 'Approved',
                'catatan' => $request->input('catatan_approval', $pembayaran->catatan)
            ]);

            DB::commit();

            Log::info('Payment approved', [
                'payment_id' => $id,
                'amount' => $pembayaran->nominal_bayar,
                'approved_by' => auth()->user()->nama
            ]);

            return redirect()->route('keuangan.approval')
                ->with('success', 'Pembayaran berhasil disetujui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving payment: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyetujui pembayaran.');
        }
    }

    /**
     * Reject a payment
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 500 karakter.'
        ]);

        try {
            DB::beginTransaction();

            $pembayaran = Pembayaran::findOrFail($id);

            // Validasi status
            if ($pembayaran->status_verifikasi !== 'Pending') {
                return back()->with('error', 'Pembayaran sudah diproses sebelumnya.');
            }

            // Update status menjadi ditolak
            $pembayaran->update([
                'status_verifikasi' => 'Ditolak',
                'catatan' => $request->input('alasan_penolakan')
            ]);

            DB::commit();

            Log::info('Payment rejected', [
                'payment_id' => $id,
                'amount' => $pembayaran->nominal_bayar,
                'rejected_by' => auth()->user()->nama,
                'reason' => $request->input('alasan_penolakan')
            ]);

            return redirect()->route('keuangan.approval')
                ->with('success', 'Pembayaran berhasil ditolak.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting payment: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menolak pembayaran.');
        }
    }

    /**
     * Get all approved payments
     */
    public function approved()
    {
        try {
            $approvedPayments = Pembayaran::with(['penawaran.proyek'])
                ->where('status_verifikasi', 'Approved')
                ->orderBy('updated_at', 'desc')
                ->paginate(20);

            return view('pages.keuangan.approval-components.approved', compact('approvedPayments'));
            
        } catch (\Exception $e) {
            Log::error('Error in ApprovalController@approved: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data pembayaran yang disetujui.');
        }
    }

    /**
     * Get all rejected payments
     */
    public function rejected()
    {
        try {
            $rejectedPayments = Pembayaran::with(['penawaran.proyek'])
                ->where('status_verifikasi', 'Ditolak')
                ->orderBy('updated_at', 'desc')
                ->paginate(20);

            return view('pages.keuangan.approval-components.rejected', compact('rejectedPayments'));
            
        } catch (\Exception $e) {
            Log::error('Error in ApprovalController@rejected: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data pembayaran yang ditolak.');
        }
    }
}
