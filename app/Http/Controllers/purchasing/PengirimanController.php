<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Vendor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends Controller
{
    /**
     * Display a listing of projects ready for shipping
     */
    public function index()
    {
        // Ambil proyek yang statusnya 'Pengiriman' atau 'Selesai'
        // Dan vendor yang sudah lunas pembayarannya
        $proyekReady = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminMarketing', 'pembayaran', 'pengiriman'])
            ->whereIn('status', ['Pengiriman', 'Selesai'])
            ->whereHas('penawaranAktif', function ($query) {
                $query->where('status', 'ACC');
            })
            ->get()
            ->map(function ($proyek) {
                // Ambil vendor yang terlibat dalam proyek ini
                $vendors = $proyek->penawaranAktif->penawaranDetail
                    ->pluck('barang.vendor')
                    ->unique('id_vendor')
                    ->filter();

                $proyek->vendors_ready = $vendors->map(function ($vendor) use ($proyek) {
                    // Hitung total untuk vendor ini (menggunakan total_harga_hpp)
                    $totalVendor = $proyek->penawaranAktif->penawaranDetail
                        ->where('barang.id_vendor', $vendor->id_vendor)
                        ->sum('total_harga_hpp');

                    // Hitung yang sudah dibayar untuk vendor ini (approved saja)
                    $totalDibayarApproved = $proyek->pembayaran
                        ->where('id_vendor', $vendor->id_vendor)
                        ->where('status_verifikasi', 'Approved')
                        ->sum('nominal_bayar');

                    $isLunas = $totalVendor <= $totalDibayarApproved;
                    $hasPembayaranApproved = $totalDibayarApproved > 0; // Ada pembayaran yang sudah approved

                    // Cek apakah sudah ada pengiriman untuk vendor ini di penawaran ini
                    $pengiriman = Pengiriman::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                        ->where('id_vendor', $vendor->id_vendor)
                        ->first();

                    return [
                        'vendor' => $vendor->toArray(),
                        'total_vendor' => $totalVendor,
                        'total_dibayar_approved' => $totalDibayarApproved,
                        'status_lunas' => $isLunas,
                        'has_approved_payment' => $hasPembayaranApproved,
                        'pengiriman' => $pengiriman ? $pengiriman->toArray() : null,
                        'ready_to_ship' => $hasPembayaranApproved && !$pengiriman // Bisa kirim jika ada pembayaran approved
                    ];
                })->filter(function ($vendorData) {
                    return $vendorData['has_approved_payment']; // Hanya vendor yang ada pembayaran approved
                })->values()->toArray(); // Konversi ke array PHP biasa

                return $proyek;
            })
            ->filter(function ($proyek) {
                return count($proyek->vendors_ready) > 0; // Hanya proyek yang ada vendor lunas
            })
            ->values();

        // Ambil pengiriman yang sedang berjalan (per vendor)
        $pengirimanBerjalan = Pengiriman::with(['penawaran.proyek', 'vendor'])
            ->whereIn('status_verifikasi', ['Pending', 'Dalam_Proses'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil pengiriman yang sudah selesai (per vendor) 
        // Kriteria selesai: 
        // 1. Status Verified (untuk proyek yang sudah Selesai)
        // 2. Atau dokumen lengkap (foto_sampai + tanda_terima) tapi proyek belum Selesai
        $pengirimanSelesai = Pengiriman::with(['penawaran.proyek', 'vendor', 'verifiedBy'])
            ->where(function($query) {
                // Yang sudah verified dan proyeknya selesai
                $query->where('status_verifikasi', 'Verified')
                      ->whereHas('penawaran.proyek', function($proyekQuery) {
                          $proyekQuery->where('status', 'Selesai');
                      });
            })
            ->orWhere(function($query) {
                // Atau yang dokumennya sudah lengkap (sampai + tanda terima) tapi belum verified
                $query->whereNotNull('foto_sampai')
                      ->whereNotNull('tanda_terima')
                      ->where('foto_sampai', '!=', '')
                      ->where('tanda_terima', '!=', '')
                      ->where('status_verifikasi', '!=', 'Verified');
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        // Debug log untuk memastikan struktur data benar
        Log::info('Proyek Ready Data Structure:', [
            'count' => $proyekReady->count(),
            'sample' => $proyekReady->first() ? [
                'vendors_ready_count' => count($proyekReady->first()->vendors_ready),
                'vendors_ready_sample' => $proyekReady->first()->vendors_ready[0] ?? 'No vendors ready'
            ] : 'No projects'
        ]);

        return view('pages.purchasing.pengiriman', compact(
            'proyekReady',
            'pengirimanBerjalan', 
            'pengirimanSelesai'
        ));
    }

    /**
     * Store a newly created shipping record
     */
    public function store(Request $request)
    {
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk membuat pengiriman. Hanya admin purchasing/superadmin yang dapat melakukan aksi ini.');
        }

        $request->validate([
            'id_penawaran' => 'required|exists:penawaran,id_penawaran',
            'id_vendor' => 'required|exists:vendor,id_vendor',
            'no_surat_jalan' => 'required|string|max:50',
            'tanggal_kirim' => 'required|date',
            'alamat_kirim' => 'nullable|string',
            'file_surat_jalan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $penawaran = Penawaran::with(['proyek.pembayaran'])->findOrFail($request->id_penawaran);
        // Check if current user is assigned to this project, unless superadmin
        if ($user->role === 'admin_purchasing' && $penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk proyek ini. Hanya admin purchasing yang ditugaskan/superadmin yang dapat membuat pengiriman untuk proyek ini.');
        }
        
        $totalVendor = $penawaran->penawaranDetail
            ->where('barang.id_vendor', $request->id_vendor)
            ->sum('total_harga_hpp');

        $totalDibayar = $penawaran->proyek->pembayaran
            ->where('id_vendor', $request->id_vendor)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        if ($totalDibayar <= 0) {
            return back()->with('error', 'Vendor belum memiliki pembayaran yang di-approve, tidak bisa membuat pengiriman');
        }

        // Cek apakah sudah ada pengiriman untuk vendor ini
        $existingPengiriman = Pengiriman::where('id_penawaran', $request->id_penawaran)
            ->where('id_vendor', $request->id_vendor)
            ->exists();

        if ($existingPengiriman) {
            return back()->with('error', 'Pengiriman untuk vendor ini sudah dibuat');
        }

        DB::beginTransaction();
        try {
            // Upload file surat jalan jika ada
            $filePath = null;
            if ($request->hasFile('file_surat_jalan')) {
                $filePath = $request->file('file_surat_jalan')->store('pengiriman/surat_jalan', 'public');
            }

            // Buat pengiriman
            $pengiriman = Pengiriman::create([
                'id_penawaran' => $request->id_penawaran,
                'id_vendor' => $request->id_vendor,
                'no_surat_jalan' => $request->no_surat_jalan,
                'tanggal_kirim' => $request->tanggal_kirim,
                'alamat_kirim' => $request->alamat_kirim,
                'file_surat_jalan' => $filePath,
                'status_verifikasi' => 'Pending'
            ]);

            // Update status proyek berdasarkan kondisi vendor
            $this->updateProjectStatusOnShipping($penawaran->proyek);

            DB::commit();

            return redirect()->route('purchasing.pengiriman')
                ->with('success', 'Pengiriman berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat membuat pengiriman');
        }
    }

    /**
     * Update dokumentasi pengiriman
     */
    public function updateDokumentasi(Request $request, $id)
    {
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk mengupdate dokumentasi pengiriman. Hanya admin purchasing/superadmin yang dapat melakukan aksi ini.');
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek'])->findOrFail($id);
        // Check if current user is assigned to this project, unless superadmin
        if ($user->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk proyek ini. Hanya admin purchasing yang ditugaskan/superadmin yang dapat mengupdate dokumentasi pengiriman untuk proyek ini.');
        }

        $request->validate([
            'foto_berangkat' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto_perjalanan' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto_sampai' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'tanda_terima' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $updateData = [];

            // Handle file uploads
            $fileFields = ['foto_berangkat', 'foto_perjalanan', 'foto_sampai', 'tanda_terima'];
            
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Hapus file lama jika ada
                    if ($pengiriman->$field) {
                        Storage::disk('public')->delete($pengiriman->$field);
                    }
                    
                    // Upload file baru
                    $updateData[$field] = $request->file($field)->store('pengiriman/dokumentasi', 'public');
                }
            }

            // Update status berdasarkan kelengkapan dokumentasi
            $pengiriman->update($updateData);
            $pengiriman->refresh();

            // Auto update status berdasarkan dokumentasi yang ada
            if ($pengiriman->foto_berangkat && !$pengiriman->foto_perjalanan) {
                $pengiriman->update(['status_verifikasi' => 'Dalam_Proses']);
            } elseif ($pengiriman->foto_berangkat && $pengiriman->foto_perjalanan && $pengiriman->foto_sampai && $pengiriman->tanda_terima) {
                $pengiriman->update(['status_verifikasi' => 'Sampai_Tujuan']);
            }

            DB::commit();

            return redirect()->route('purchasing.pengiriman')
                ->with('success', 'Dokumentasi pengiriman berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat mengupdate dokumentasi');
        }
    }

    /**
     * Verify shipping completion (superadmin only)
     */
    public function verify(Request $request, $id)
    {
        // Role-based access control: Only allow superadmin
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk memverifikasi pengiriman. Hanya superadmin yang dapat melakukan aksi ini.');
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek'])->findOrFail($id);

        // Pastikan dokumentasi lengkap
        if (!$pengiriman->dokumentasi_lengkap) {
            return back()->with('error', 'Dokumentasi pengiriman belum lengkap');
        }

        DB::beginTransaction();
        try {
            // Update status pengiriman
            $pengiriman->update([
                'status_verifikasi' => 'Verified',
                'verified_by' => Auth::user()->id_user,
                'verified_at' => now(),
                'catatan_verifikasi' => $request->catatan_verifikasi
            ]);

            // Cek apakah semua vendor dalam proyek ini sudah selesai pengiriman
            $this->checkAndUpdateProjectStatus($pengiriman->penawaran->proyek);

            DB::commit();

            return redirect()->route('purchasing.pengiriman')
                ->with('success', 'Pengiriman berhasil diverifikasi');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat verifikasi pengiriman');
        }
    }

    /**
     * Check and update project status based on all vendor shipping status
     */
    private function checkAndUpdateProjectStatus($proyek)
    {
        // Ambil semua vendor yang terlibat dalam proyek
        $allVendorIds = $proyek->penawaranAktif->penawaranDetail
            ->pluck('barang.id_vendor')
            ->unique()
            ->filter();

        // Cek berapa vendor yang sudah verified pengirimannya
        $verifiedVendorIds = Pengiriman::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
            ->where('status_verifikasi', 'Verified')
            ->pluck('id_vendor')
            ->unique();

        // Jika semua vendor sudah verified, update status proyek ke Selesai
        if ($allVendorIds->count() === $verifiedVendorIds->count() && 
            $allVendorIds->diff($verifiedVendorIds)->isEmpty()) {
            $proyek->update(['status' => 'Selesai']);
        }
    }

    /**
     * Update project status when vendor starts shipping
     */
    private function updateProjectStatusOnShipping($proyek)
    {
        // Jika status proyek masih Pembayaran, dan ada vendor yang sudah mulai pengiriman
        // maka update status ke Pengiriman
        if ($proyek->status === 'Pembayaran') {
            // Cek apakah ada vendor yang sudah lunas dan sudah mulai pengiriman
            $hasShippingVendor = Pengiriman::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                ->exists();
                
            if ($hasShippingVendor) {
                $proyek->update(['status' => 'Pengiriman']);
            }
        }
    }

    /**
     * Get detail with files for modal
     */
    public function getDetailWithFiles($id)
    {
        $pengiriman = Pengiriman::with(['penawaran.proyek', 'vendor', 'verifiedBy'])
            ->findOrFail($id);

        return response()->json([
            'pengiriman' => $pengiriman,
            'files' => [
                'file_surat_jalan' => $pengiriman->file_surat_jalan ? Storage::url($pengiriman->file_surat_jalan) : null,
                'foto_berangkat' => $pengiriman->foto_berangkat ? Storage::url($pengiriman->foto_berangkat) : null,
                'foto_perjalanan' => $pengiriman->foto_perjalanan ? Storage::url($pengiriman->foto_perjalanan) : null,
                'foto_sampai' => $pengiriman->foto_sampai ? Storage::url($pengiriman->foto_sampai) : null,
                'tanda_terima' => $pengiriman->tanda_terima ? Storage::url($pengiriman->tanda_terima) : null,
            ]
        ]);
    }

    /**
     * Delete shipping record
     */
    public function destroy($id)
    {
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk menghapus pengiriman. Hanya admin purchasing/superadmin yang dapat melakukan aksi ini.');
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek'])->findOrFail($id);
        // Check if current user is assigned to this project, unless superadmin
        if ($user->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk proyek ini. Hanya admin purchasing yang ditugaskan/superadmin yang dapat menghapus pengiriman untuk proyek ini.');
        }

        // Hanya bisa hapus jika status masih Pending
        if ($pengiriman->status_verifikasi !== 'Pending') {
            return back()->with('error', 'Pengiriman yang sudah berjalan tidak dapat dihapus');
        }

        DB::beginTransaction();
        try {
            // Hapus file-file yang terkait
            $files = [
                $pengiriman->file_surat_jalan,
                $pengiriman->foto_berangkat,
                $pengiriman->foto_perjalanan,
                $pengiriman->foto_sampai,
                $pengiriman->tanda_terima
            ];

            foreach ($files as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }

            $pengiriman->delete();

            DB::commit();

            return redirect()->route('purchasing.pengiriman')
                ->with('success', 'Pengiriman berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengiriman');
        }
    }

    /**
     * Clean up orphaned files
     */
    public function cleanupOrphanedFiles()
    {
        // Role-based access control: Only allow superadmin for maintenance
        if (Auth::user()->role !== 'superadmin') {
            return response()->json([
                'error' => 'Tidak memiliki akses untuk maintenance. Hanya superadmin yang dapat melakukan aksi ini.'
            ], 403);
        }

        $folders = ['pengiriman/surat_jalan', 'pengiriman/dokumentasi'];
        $deletedCount = 0;

        foreach ($folders as $folder) {
            $allFiles = Storage::disk('public')->files($folder);
            
            $usedFiles = Pengiriman::whereNotNull('file_surat_jalan')
                ->orWhereNotNull('foto_berangkat')
                ->orWhereNotNull('foto_perjalanan')
                ->orWhereNotNull('foto_sampai')
                ->orWhereNotNull('tanda_terima')
                ->get()
                ->flatMap(function ($pengiriman) {
                    return collect([
                        $pengiriman->file_surat_jalan,
                        $pengiriman->foto_berangkat,
                        $pengiriman->foto_perjalanan,
                        $pengiriman->foto_sampai,
                        $pengiriman->tanda_terima
                    ])->filter();
                })
                ->toArray();

            $orphanedFiles = array_diff($allFiles, $usedFiles);

            foreach ($orphanedFiles as $file) {
                try {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                } catch (\Exception $e) {
                    // Silent fail
                }
            }
        }

        return response()->json([
            'message' => "Cleanup completed. {$deletedCount} orphaned files deleted.",
            'deleted_count' => $deletedCount
        ]);
    }
}
