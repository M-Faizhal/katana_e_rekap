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
        $proyekReady = Proyek::with([
                'penawaranAktif.penawaranDetail.barang.vendor', 
                'adminMarketing', 
                'pembayaran', 
                'pengiriman',
                'kalkulasiHps.barang',
                'kalkulasiHps.vendor'
            ])
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
                    $pembayaranApproved = $proyek->pembayaran
                        ->where('id_vendor', $vendor->id_vendor)
                        ->where('status_verifikasi', 'Approved');
                    
                    $totalDibayarApproved = $pembayaranApproved->sum('nominal_bayar');

                    $isLunas = $totalVendor <= $totalDibayarApproved;
                    $hasPembayaranApproved = $totalDibayarApproved > 0; // Ada pembayaran yang sudah approved

                    // Debug log untuk setiap vendor
                    Log::info('Vendor Payment Check:', [
                        'proyek_id' => $proyek->id_proyek,
                        'vendor_id' => $vendor->id_vendor,
                        'vendor_name' => $vendor->nama_vendor,
                        'total_vendor' => $totalVendor,
                        'pembayaran_approved_count' => $pembayaranApproved->count(),
                        'pembayaran_approved_data' => $pembayaranApproved->map(function($p) {
                            return [
                                'id' => $p->id_pembayaran,
                                'nominal' => $p->nominal_bayar,
                                'status' => $p->status_verifikasi,
                                'tanggal' => $p->tanggal_pembayaran
                            ];
                        })->toArray(),
                        'total_dibayar_approved' => $totalDibayarApproved,
                        'has_approved_payment' => $hasPembayaranApproved,
                        'is_lunas' => $isLunas
                    ]);

                    // Cek apakah sudah ada pengiriman untuk vendor ini di penawaran ini
                    $pengiriman = Pengiriman::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                        ->where('id_vendor', $vendor->id_vendor)
                        ->get();

                    // Ambil daftar barang untuk vendor ini dari kalkulasi HPS
                    $barangVendor = [];
                    $kalkulasiCount = 0;
                    $barangFoundCount = 0;
                    
                    if ($proyek->kalkulasiHps) {
                        $kalkulasiCount = $proyek->kalkulasiHps->count();
                        foreach ($proyek->kalkulasiHps as $kalkulasi) {
                            if ($kalkulasi->id_vendor == $vendor->id_vendor) {
                                if ($kalkulasi->barang && $kalkulasi->barang->nama_barang) {
                                    $barangVendor[] = $kalkulasi->barang->nama_barang;
                                    $barangFoundCount++;
                                }
                            }
                        }
                        $barangVendor = array_unique(array_filter($barangVendor));
                    }

                    // Fallback: jika tidak ada di kalkulasi HPS, ambil dari penawaran detail
                    if (empty($barangVendor) && $proyek->penawaranAktif && $proyek->penawaranAktif->penawaranDetail) {
                        foreach ($proyek->penawaranAktif->penawaranDetail as $detail) {
                            if ($detail->barang && $detail->barang->id_vendor == $vendor->id_vendor && $detail->barang->nama_barang) {
                                $barangVendor[] = $detail->barang->nama_barang;
                            }
                        }
                        $barangVendor = array_unique(array_filter($barangVendor));
                    }

                    // Log debug information
                    Log::info('Barang Vendor Debug:', [
                        'proyek_id' => $proyek->id_proyek,
                        'vendor_id' => $vendor->id_vendor,
                        'vendor_name' => $vendor->nama_vendor,
                        'kalkulasi_total_count' => $kalkulasiCount,
                        'barang_found_count' => $barangFoundCount,
                        'barang_vendor_array' => $barangVendor,
                        'has_penawaran_aktif' => $proyek->penawaranAktif ? true : false,
                        'penawaran_detail_count' => $proyek->penawaranAktif && $proyek->penawaranAktif->penawaranDetail ? 
                            $proyek->penawaranAktif->penawaranDetail->count() : 0
                    ]);

                    return [
                        'vendor' => $vendor->toArray(),
                        'total_vendor' => $totalVendor,
                        'total_dibayar_approved' => $totalDibayarApproved,
                        'status_lunas' => $isLunas,
                        'has_approved_payment' => $hasPembayaranApproved,
                        'pengiriman' => $pengiriman ? $pengiriman->toArray() : null,
                        'ready_to_ship' => $hasPembayaranApproved && $pengiriman->isEmpty(), // Bisa kirim jika ada pembayaran approved dan belum ada pengiriman
                        'barang_vendor' => $barangVendor // Daftar nama barang untuk vendor ini
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

        // Convert proyekReady collection to paginated result
        $currentPageReady = request()->get('ready_page', 1);
        $perPageReady = 10;
        $readyItems = collect($proyekReady)->flatMap(function($proyek) {
            return collect($proyek->vendors_ready)->filter(function($vendor) {
                return $vendor['ready_to_ship'];
            })->map(function($vendor) use ($proyek) {
                return (object) array_merge($vendor, ['proyek' => $proyek]);
            });
        });
        $currentPageReadyItems = $readyItems->slice(($currentPageReady - 1) * $perPageReady, $perPageReady);
        $proyekReadyPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageReadyItems,
            $readyItems->count(),
            $perPageReady,
            $currentPageReady,
            ['path' => request()->url(), 'pageName' => 'ready_page']
        );

        // Ambil pengiriman yang sedang berjalan (per vendor) dengan pagination
        $pengirimanBerjalan = Pengiriman::with([
                'penawaran.proyek.kalkulasiHps.barang', 
                'penawaran.proyek.kalkulasiHps.vendor',
                'vendor'
            ])
            ->whereIn('status_verifikasi', ['Pending', 'Dalam_Proses'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'proses_page');

        // Tambahkan data barang untuk setiap pengiriman yang sedang berjalan
        $pengirimanBerjalan->getCollection()->transform(function ($pengiriman) {
            // Ambil daftar barang untuk vendor ini dari kalkulasi HPS
            $barangList = [];
            if ($pengiriman->penawaran && $pengiriman->penawaran->proyek && $pengiriman->penawaran->proyek->kalkulasiHps) {
                foreach ($pengiriman->penawaran->proyek->kalkulasiHps as $kalkulasi) {
                    if ($kalkulasi->id_vendor == $pengiriman->id_vendor && $kalkulasi->barang) {
                        $barangList[] = $kalkulasi->barang->nama_barang;
                    }
                }
                $barangList = array_unique(array_filter($barangList));
            }

            // Jika tidak ada barang_list di field, set dari kalkulasi HPS
            if (empty($pengiriman->barang_list) && !empty($barangList)) {
                $pengiriman->barang_list = $barangList;
            }

            return $pengiriman;
        });

        // Ambil pengiriman yang sudah selesai (per vendor) dengan pagination
        // Kriteria selesai: 
        // 1. Status Verified (untuk proyek yang sudah Selesai)
        // 2. Atau dokumen lengkap (foto_sampai + tanda_terima) tapi proyek belum Selesai
        $pengirimanSelesai = Pengiriman::with([
                'penawaran.proyek.kalkulasiHps.barang', 
                'penawaran.proyek.kalkulasiHps.vendor',
                'vendor', 
                'verifiedBy'
            ])
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
            ->paginate(10, ['*'], 'selesai_page');

        // Tambahkan data barang untuk setiap pengiriman yang selesai
        $pengirimanSelesai->getCollection()->transform(function ($pengiriman) {
            // Ambil daftar barang untuk vendor ini dari kalkulasi HPS
            $barangList = [];
            if ($pengiriman->penawaran && $pengiriman->penawaran->proyek && $pengiriman->penawaran->proyek->kalkulasiHps) {
                foreach ($pengiriman->penawaran->proyek->kalkulasiHps as $kalkulasi) {
                    if ($kalkulasi->id_vendor == $pengiriman->id_vendor && $kalkulasi->barang) {
                        $barangList[] = $kalkulasi->barang->nama_barang;
                    }
                }
                $barangList = array_unique(array_filter($barangList));
            }

            // Jika tidak ada barang_list di field, set dari kalkulasi HPS
            if (empty($pengiriman->barang_list) && !empty($barangList)) {
                $pengiriman->barang_list = $barangList;
            }

            return $pengiriman;
        });

        // Debug log untuk memastikan struktur data benar
        Log::info('Proyek Ready Data Structure:', [
            'count' => $proyekReady->count(),
            'sample' => $proyekReady->count() > 0 ? [
                'proyek_id' => $proyekReady->first()->id_proyek,
                'proyek_status' => $proyekReady->first()->status,
                'vendors_ready_count' => count($proyekReady->first()->vendors_ready),
                'vendors_ready_sample' => $proyekReady->first()->vendors_ready[0] ?? 'No vendors ready',
                'barang_vendor_sample' => isset($proyekReady->first()->vendors_ready[0]['barang_vendor']) ? 
                    $proyekReady->first()->vendors_ready[0]['barang_vendor'] : 'No barang data',
                'kalkulasi_hps_count' => $proyekReady->first()->kalkulasiHps ? $proyekReady->first()->kalkulasiHps->count() : 0,
                'kalkulasi_hps_sample' => $proyekReady->first()->kalkulasiHps ? 
                    $proyekReady->first()->kalkulasiHps->first() : 'No kalkulasi HPS'
            ] : 'No projects'
        ]);

        return view('pages.purchasing.pengiriman', compact(
            'proyekReady',
            'proyekReadyPaginated',
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
                $file = $request->file('file_surat_jalan');
                $fileName = time() . '_surat_jalan_' . $file->getClientOriginalName();
                $file->storeAs('pengiriman/surat_jalan', $fileName, 'public');
                $filePath = $fileName; // Simpan hanya nama file
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
                Storage::disk('public')->delete('pengiriman/surat_jalan/' . $filePath);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat membuat pengiriman');
        }
    }

    /**
     * Show the form for editing pengiriman
     */
    public function edit($id)
    {
        Log::info('PengirimanController::edit called', ['id' => $id, 'user' => Auth::id()]);
        
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            Log::warning('Access denied for pengiriman edit', ['user_role' => $user->role, 'user_id' => $user->id_user]);
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit pengiriman'
            ], 403);
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek', 'vendor'])->findOrFail($id);

        // Additional check: Only admin_purchasing assigned to the project or superadmin can edit
        if ($user->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat mengedit pengiriman untuk proyek yang ditugaskan kepada Anda'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $pengiriman
        ]);
    }

    /**
     * Update pengiriman data
     */
    public function update(Request $request, $id)
    {
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengupdate pengiriman'
            ], 403);
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek', 'vendor'])->findOrFail($id);

        // Additional check: Only admin_purchasing assigned to the project or superadmin can update
        if ($user->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat mengupdate pengiriman untuk proyek yang ditugaskan kepada Anda'
            ], 403);
        }

        // Validate input
        $request->validate([
            'no_surat_jalan' => 'required|string|max:255|unique:pengiriman,no_surat_jalan,' . $id . ',id_pengiriman',
            'tanggal_kirim' => 'required|date',
            'alamat_kirim' => 'required|string',
            'file_surat_jalan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        DB::beginTransaction();

        try {
            $updateData = [
                'no_surat_jalan' => $request->no_surat_jalan,
                'tanggal_kirim' => $request->tanggal_kirim,
                'alamat_kirim' => $request->alamat_kirim
            ];

            // Handle file surat jalan update
            if ($request->hasFile('file_surat_jalan')) {
                // Delete old file if exists
                if ($pengiriman->file_surat_jalan) {
                    Storage::disk('public')->delete('pengiriman/surat_jalan/' . $pengiriman->file_surat_jalan);
                }

                // Upload new file
                $file = $request->file('file_surat_jalan');
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
                $file->storeAs('pengiriman/surat_jalan', $fileName, 'public');
                $updateData['file_surat_jalan'] = $fileName;
            }

            $pengiriman->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data pengiriman berhasil diperbarui',
                'data' => $pengiriman->fresh(['penawaran.proyek', 'vendor'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pengiriman: ' . $e->getMessage()
            ], 500);
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
                        Storage::disk('public')->delete('pengiriman/dokumentasi/' . $pengiriman->$field);
                    }
                    
                    // Upload file baru
                    $file = $request->file($field);
                    $fileName = time() . '_' . $field . '_' . $file->getClientOriginalName();
                    $file->storeAs('pengiriman/dokumentasi', $fileName, 'public');
                    $updateData[$field] = $fileName; // Simpan hanya nama file
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
                'file_surat_jalan' => $pengiriman->file_surat_jalan ? Storage::url('pengiriman/surat_jalan/' . $pengiriman->file_surat_jalan) : null,
                'foto_berangkat' => $pengiriman->foto_berangkat ? Storage::url('pengiriman/dokumentasi/' . $pengiriman->foto_berangkat) : null,
                'foto_perjalanan' => $pengiriman->foto_perjalanan ? Storage::url('pengiriman/dokumentasi/' . $pengiriman->foto_perjalanan) : null,
                'foto_sampai' => $pengiriman->foto_sampai ? Storage::url('pengiriman/dokumentasi/' . $pengiriman->foto_sampai) : null,
                'tanda_terima' => $pengiriman->tanda_terima ? Storage::url('pengiriman/dokumentasi/' . $pengiriman->tanda_terima) : null,
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
                ['file' => $pengiriman->file_surat_jalan, 'folder' => 'pengiriman/surat_jalan'],
                ['file' => $pengiriman->foto_berangkat, 'folder' => 'pengiriman/dokumentasi'],
                ['file' => $pengiriman->foto_perjalanan, 'folder' => 'pengiriman/dokumentasi'],
                ['file' => $pengiriman->foto_sampai, 'folder' => 'pengiriman/dokumentasi'],
                ['file' => $pengiriman->tanda_terima, 'folder' => 'pengiriman/dokumentasi']
            ];

            foreach ($files as $fileData) {
                if ($fileData['file']) {
                    $fullPath = $fileData['folder'] . '/' . $fileData['file'];
                    if (Storage::disk('public')->exists($fullPath)) {
                        Storage::disk('public')->delete($fullPath);
                    }
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
            
            // Ambil nama file saja (tanpa path)
            $allFileNames = array_map(function($file) {
                return basename($file);
            }, $allFiles);

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

            $orphanedFileNames = array_diff($allFileNames, $usedFiles);

            foreach ($orphanedFileNames as $fileName) {
                try {
                    Storage::disk('public')->delete($folder . '/' . $fileName);
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
