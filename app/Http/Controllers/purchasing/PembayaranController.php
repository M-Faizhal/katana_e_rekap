<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;
use App\Models\Vendor;
use App\Models\KalkulasiHps;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Display a listing of projects that need payment processing
     */
    public function index()
    {
        // Ambil proyek yang statusnya 'Pembayaran', 'Pengiriman', 'Selesai', atau 'Gagal' dan sudah ada penawaran yang di-ACC
        // Dengan vendor yang terlibat
        $proyekPerluBayar = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminMarketing', 'pembayaran.vendor'])
            ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai']) // Hapus 'Gagal' dari filter
            ->whereHas('penawaranAktif', function ($query) {
                $query->where('status', 'ACC');
            })
            ->get()
            ->map(function ($proyek) {
                // Ambil vendor yang terlibat dalam proyek ini
                $vendors = $proyek->penawaranAktif->penawaranDetail
                    ->pluck('barang.vendor')
                    ->unique('id_vendor')
                    ->filter(); // Remove null values

                $proyek->vendors_data = $vendors->map(function ($vendor) use ($proyek) {
                    $totalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
                        ->where('id_vendor', $vendor->id_vendor)
                        ->sum('total_harga_hpp');

                    $totalDibayarApproved = $proyek->pembayaran
                        ->where('id_vendor', $vendor->id_vendor)
                        ->where('status_verifikasi', 'Approved')
                        ->sum('nominal_bayar');

                    $sisaBayar = $totalVendor - $totalDibayarApproved;

                    // Jika totalVendor = 0, tampilkan warning dan status_lunas = false
                    $warning_hps = $totalVendor == 0 ? 'Data kalkulasi HPS belum diisi' : null;

                    return (object) [
                        'vendor' => $vendor,
                        'total_vendor' => $totalVendor,
                        'total_dibayar_approved' => $totalDibayarApproved,
                        'sisa_bayar' => $sisaBayar,
                        'persen_bayar' => $totalVendor > 0 ? ($totalDibayarApproved / $totalVendor) * 100 : 0,
                        'status_lunas' => $totalVendor > 0 ? $sisaBayar <= 0 : false,
                        'warning_hps' => $warning_hps
                    ];
                })
                // Filter: hanya vendor yang belum lunas atau data HPS belum diisi
                ->filter(function ($vendorData) {
                    return $vendorData->sisa_bayar > 0 || $vendorData->warning_hps;
                });

                return $proyek;
            })
            ->filter(function ($proyek) {
                return $proyek->vendors_data->count() > 0; // Hanya proyek yang ada vendor belum lunas
            })
            ->sortBy('nama_barang')
            ->values();
            
        // Convert collection to paginated result
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $currentPageItems = $proyekPerluBayar->slice(($currentPage - 1) * $perPage, $perPage);
        $proyekPerluBayar = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $proyekPerluBayar->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        // Ambil parameter filter dan search
        $search = request()->get('search');
        $statusFilter = request()->get('status_filter'); // untuk pembayaran
        $proyekStatusFilter = request()->get('proyek_status_filter'); // untuk status proyek lunas/belum
        $sortBy = request()->get('sort_by', 'created_at');
        $sortOrder = request()->get('sort_order', 'desc');
        $activeTab = request()->get('tab', 'perlu-bayar'); // untuk tab navigation

        // Ambil semua proyek dengan status Pembayaran, Pengiriman, Selesai, atau Gagal untuk history
        $semuaProyekQuery = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminMarketing', 'pembayaran.vendor'])
            ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai', 'Gagal'])
            ->whereHas('penawaranAktif', function ($query) {
                $query->where('status', 'ACC');
            });

        // Filter berdasarkan search
        if ($search) {
            $semuaProyekQuery->where(function ($query) use ($search) {
                $query->where('nama_barang', 'like', "%{$search}%")
                      ->orWhere('instansi', 'like', "%{$search}%")
                      ->orWhere('nama_klien', 'like', "%{$search}%")
                      ->orWhere('kota_kab', 'like', "%{$search}%")
                      ->orWhereHas('penawaranAktif', function ($subQuery) use ($search) {
                          $subQuery->where('no_penawaran', 'like', "%{$search}%");
                      });
            });
        }

        // Sorting
        if ($sortBy === 'nama_barang') {
            $semuaProyekQuery->orderBy('nama_barang', $sortOrder);
        } elseif ($sortBy === 'instansi') {
            $semuaProyekQuery->orderBy('instansi', $sortOrder);
        } elseif ($sortBy === 'nama_klien') {
            $semuaProyekQuery->orderBy('nama_klien', $sortOrder);
        } else {
            $semuaProyekQuery->orderBy('created_at', $sortOrder);
        }

        $semuaProyek = $semuaProyekQuery->paginate(10, ['*'], 'proyek_page');

        // Hitung statistik untuk setiap proyek (per vendor)
        $semuaProyek->getCollection()->transform(function ($proyek) {
            // Ambil vendor yang terlibat dalam proyek ini
            $vendors = $proyek->penawaranAktif->penawaranDetail
                ->pluck('barang.vendor')
                ->unique('id_vendor')
                ->filter(); // Remove null values

            $proyek->vendors_data = $vendors->map(function ($vendor) use ($proyek) {
                // Hitung total untuk vendor ini (menggunakan total_harga_hpp dari kalkulasi_hps)
                $totalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
                    ->where('id_vendor', $vendor->id_vendor)
                    ->sum('total_harga_hpp');

                // Hitung yang sudah dibayar untuk vendor ini
                $totalDibayarApproved = $proyek->pembayaran
                    ->where('id_vendor', $vendor->id_vendor)
                    ->where('status_verifikasi', 'Approved')
                    ->sum('nominal_bayar');

                $sisaBayar = $totalVendor - $totalDibayarApproved;

                return (object) [
                    'vendor' => $vendor,
                    'total_vendor' => $totalVendor,
                    'total_dibayar_approved' => $totalDibayarApproved,
                    'sisa_bayar' => $sisaBayar,
                    'persen_bayar' => $totalVendor > 0 ? ($totalDibayarApproved / $totalVendor) * 100 : 0,
                    'status_lunas' => $totalVendor > 0 ? $sisaBayar <= 0 : false,
                    'warning_hps' => $totalVendor == 0 ? 'Data kalkulasi HPS belum diisi' : null
                ];
            });

            // Hitung total keseluruhan proyek berdasarkan modal vendor
            $totalKeseluruhanModal = $proyek->vendors_data->sum('total_vendor');
            $totalKeseluruhanDibayar = $proyek->vendors_data->sum('total_dibayar_approved');
            $totalKeseluruhanSisa = $proyek->vendors_data->sum('sisa_bayar');
            
            $proyek->total_modal_vendor = $totalKeseluruhanModal;
            $proyek->total_dibayar_approved = $totalKeseluruhanDibayar;
            $proyek->sisa_bayar = $totalKeseluruhanSisa;
            $proyek->persen_bayar = $totalKeseluruhanModal > 0 ? 
                ($totalKeseluruhanDibayar / $totalKeseluruhanModal) * 100 : 0;
            $proyek->status_lunas = $totalKeseluruhanSisa <= 0;

            return $proyek;
        });

        // Filter berdasarkan status proyek (lunas/belum lunas) setelah perhitungan
        if ($proyekStatusFilter && $proyekStatusFilter !== 'all') {
            $semuaProyek = $semuaProyek->filter(function ($proyek) use ($proyekStatusFilter) {
                if ($proyekStatusFilter === 'lunas') {
                    return $proyek->status_lunas;
                } elseif ($proyekStatusFilter === 'belum_lunas') {
                    return !$proyek->status_lunas;
                }
                return true;
            });

            // Re-paginate setelah filter
            $currentPage = request()->get('proyek_page', 1);
            $perPage = 10;
            $currentPageItems = $semuaProyek->slice(($currentPage - 1) * $perPage, $perPage);
            $semuaProyek = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $semuaProyek->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'proyek_page']
            );
        }

        // Ambil semua pembayaran dengan filter
        $semuaPembayaranQuery = Pembayaran::with(['penawaran.proyek.adminMarketing', 'vendor'])
            ->whereHas('penawaran.proyek', function ($query) {
                $query->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai', 'Gagal'])
                      ->whereHas('penawaranAktif', function ($subQuery) {
                          $subQuery->where('status', 'ACC');
                      });
            });

        // Filter pembayaran berdasarkan status
        if ($statusFilter && $statusFilter !== 'all') {
            $semuaPembayaranQuery->where('status_verifikasi', $statusFilter);
        }

        // Filter pembayaran berdasarkan search
        if ($search) {
            $semuaPembayaranQuery->where(function ($query) use ($search) {
                $query->whereHas('penawaran.proyek', function ($subQuery) use ($search) {
                    $subQuery->where('nama_barang', 'like', "%{$search}%")
                             ->orWhere('instansi', 'like', "%{$search}%")
                             ->orWhere('nama_klien', 'like', "%{$search}%");
                })->orWhereHas('vendor', function ($subQuery) use ($search) {
                    $subQuery->where('nama_vendor', 'like', "%{$search}%");
                });
            });
        }

        $semuaPembayaran = $semuaPembayaranQuery->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pembayaran_page');

        return view('pages.purchasing.pembayaran', compact(
            'proyekPerluBayar', 
            'semuaPembayaran', 
            'semuaProyek',
            'search',
            'statusFilter',
            'proyekStatusFilter',
            'sortBy',
            'sortOrder',
            'activeTab'
        ));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create($id_proyek, $id_vendor = null)
    {
        // --- SUPERADMIN ACCESS FOR PEMBAYARAN ---
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Hanya admin purchasing/superadmin yang dapat menginput pembayaran.');
        }

        $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminMarketing', 'pembayaran'])
            ->findOrFail($id_proyek);

        if ($user->role !== 'superadmin' && $proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Anda tidak memiliki akses untuk menginput pembayaran pada proyek ini.');
        }

        // Pastikan proyek memiliki penawaran yang sudah di-ACC
        if (!$proyek->penawaranAktif || $proyek->penawaranAktif->status !== 'ACC') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Proyek ini belum memiliki penawaran yang di-ACC');
        }

        // Ambil vendor yang terlibat dalam proyek ini
        $vendors = $proyek->penawaranAktif->penawaranDetail
            ->pluck('barang.vendor')
            ->unique('id_vendor')
            ->filter()
            ->map(function ($vendor) use ($proyek) {
                // Hitung total untuk vendor ini (menggunakan total_harga_hpp dari kalkulasi_hps)
                $totalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
                    ->where('id_vendor', $vendor->id_vendor)
                    ->sum('total_harga_hpp');

                // Hitung yang sudah dibayar untuk vendor ini (approved saja)
                $totalDibayar = $proyek->pembayaran
                    ->where('id_vendor', $vendor->id_vendor)
                    ->where('status_verifikasi', 'Approved')
                    ->sum('nominal_bayar');

                $sisaBayar = $totalVendor - $totalDibayar;

                $vendor->total_vendor = $totalVendor;
                $vendor->total_dibayar = $totalDibayar;
                $vendor->sisa_bayar = $sisaBayar;

                return $vendor;
            })
            ->filter(function ($vendor) {
                return $vendor->sisa_bayar > 0; // Hanya vendor yang belum lunas
            });

        if ($vendors->isEmpty()) {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Semua vendor dalam proyek ini sudah lunas');
        }

        // Jika vendor specific dipilih
        $selectedVendor = null;
        if ($id_vendor) {
            $selectedVendor = $vendors->firstWhere('id_vendor', $id_vendor);
            if (!$selectedVendor) {
                return redirect()->route('purchasing.pembayaran')
                    ->with('error', 'Vendor tidak ditemukan atau sudah lunas');
            }
        }

        // Calculate total dibayar untuk vendor yang dipilih atau semua vendor (hanya yang approved)
        if ($selectedVendor) {
            $totalDibayar = $proyek->pembayaran
                ->where('id_vendor', $selectedVendor->id_vendor)
                ->where('status_verifikasi', 'Approved')
                ->sum('nominal_bayar');
                
            // Untuk vendor yang dipilih, hitung sisa bayar berdasarkan total vendor tersebut
            $sisaBayar = $selectedVendor->total_vendor - $totalDibayar;
            
            // Total modal vendor = modal vendor yang dipilih
            $totalModalVendor = $selectedVendor->total_vendor;
        } else {
            // Untuk semua vendor combined
            $totalDibayar = $proyek->pembayaran
                ->where('status_verifikasi', 'Approved')
                ->sum('nominal_bayar');
                
            // Calculate total vendor modal (total_harga_hpp dari kalkulasi_hps)
            $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
                ->sum('total_harga_hpp');
                
            // Calculate sisa bayar berdasarkan total modal vendor
            $sisaBayar = $totalModalVendor - $totalDibayar;
        }

        // Ambil breakdown modal per barang jika vendor dipilih
        $breakdownBarang = null;
        if ($selectedVendor) {
            $breakdownBarang = KalkulasiHps::with(['barang'])
                ->where('id_proyek', $proyek->id_proyek)
                ->where('id_vendor', $selectedVendor->id_vendor)
                ->get()
                ->map(function ($kalkulasi) {
                    return (object) [
                        'nama_barang' => $kalkulasi->barang->nama_barang ?? 'N/A',
                        'satuan' => $kalkulasi->barang->satuan ?? 'N/A',
                        'qty' => $kalkulasi->qty,
                        'harga_vendor' => $kalkulasi->harga_vendor,
                        'total_harga_hpp' => $kalkulasi->total_harga_hpp,
                        'harga_akhir' => $kalkulasi->harga_akhir,
                    ];
                });
        }

        return view('pages.purchasing.pembayaran-components.pembayaran-form', compact('proyek', 'vendors', 'selectedVendor', 'totalDibayar', 'sisaBayar', 'totalModalVendor', 'breakdownBarang'));
    }

    /**
     * Store a newly created payment in storage
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Hanya admin purchasing/superadmin yang dapat menginput pembayaran.');
        }

        $proyek = Proyek::find($request->id_proyek);
        if (!$proyek || ($user->role !== 'superadmin' && $proyek->id_admin_purchasing != $user->id_user)) {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Anda tidak memiliki akses untuk menginput pembayaran pada proyek ini.');
        }

        $request->validate([
            'id_proyek' => 'required|exists:proyek,id_proyek',
            'id_vendor' => 'required|exists:vendor,id_vendor',
            'jenis_bayar' => 'required|in:Lunas,DP,Cicilan',
            'nominal_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB
            'catatan' => 'nullable|string'
        ]);

        $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor'])->findOrFail($request->id_proyek);

        // Hitung total untuk vendor yang dipilih (menggunakan total_harga_hpp dari kalkulasi_hps)
        $totalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $request->id_vendor)
            ->sum('total_harga_hpp');

        // Validasi nominal pembayaran untuk vendor ini (hanya yang sudah approved)
        $totalDibayar = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
            ->where('id_vendor', $request->id_vendor)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        $sisaBayar = $totalVendor - $totalDibayar;

        if ($request->nominal_bayar > $sisaBayar) {
            return back()->with('error', 'Nominal pembayaran melebihi sisa tagihan untuk vendor ini');
        }

        DB::beginTransaction();
        try {
            // Upload bukti pembayaran dulu
            $buktiPath = null;
            if ($request->hasFile('bukti_bayar')) {
                $buktiFile = $request->file('bukti_bayar');
                $fileName = time() . '_bukti_' . $buktiFile->getClientOriginalName();
                $buktiFile->storeAs('', $fileName, 'public');
                $buktiPath = $fileName;
            }

            // Simpan pembayaran
            $pembayaran = Pembayaran::create([
                'id_penawaran' => $proyek->penawaranAktif->id_penawaran,
                'id_vendor' => $request->id_vendor,
                'jenis_bayar' => $request->jenis_bayar,
                'nominal_bayar' => $request->nominal_bayar,
                'tanggal_bayar' => now()->toDateString(),
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar' => $buktiPath,
                'catatan' => $request->catatan,
                'status_verifikasi' => 'Pending', // Menunggu verifikasi admin keuangan
            ]);

            // Check apakah semua vendor sudah lunas untuk update status proyek
            $allVendorsData = $proyek->penawaranAktif->penawaranDetail
                ->groupBy('barang.id_vendor')
                ->map(function ($details, $vendorId) use ($proyek, $request) {
                    // Hitung total vendor menggunakan harga modal
                    $totalVendor = $details->sum(function($detail) {
                        return $detail->qty * $detail->barang->harga_vendor; // harga modal
                    });
                    $totalDibayarVendor = $proyek->pembayaran
                        ->where('id_vendor', $vendorId)
                        ->where('status_verifikasi', 'Approved')
                        ->sum('nominal_bayar');
                    
                    // Tambahkan pembayaran yang baru dibuat jika untuk vendor ini
                    if ($vendorId == $request->id_vendor) {
                        $totalDibayarVendor += $request->nominal_bayar;
                    }

                    return $totalVendor <= $totalDibayarVendor;
                });

            // Update status proyek jika semua vendor sudah lunas
            if ($allVendorsData->every(function($isLunas) {
                return $isLunas;
            })) {
                $proyek->update(['status' => 'Pengiriman']);
            }

            DB::commit();
            
            return redirect()->route('purchasing.pembayaran')
                ->with('success', 'Pembayaran berhasil disimpan dan menunggu verifikasi admin keuangan');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang sudah diupload jika terjadi error
            if ($buktiPath) {
                $this->deleteFileIfExists($buktiPath);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan pembayaran');
        }
    }

    /**
     * Display the specified payment details
     */
    public function show($id_pembayaran)
    {
        $pembayaran = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor', 'vendor'])->findOrFail($id_pembayaran);
        
        // Hitung total modal untuk vendor ini saja (menggunakan total_harga_hpp dari kalkulasi_hps)
        $totalModalVendor = KalkulasiHps::where('id_proyek', $pembayaran->penawaran->proyek->id_proyek)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->sum('total_harga_hpp');

        // Hitung total yang sudah dibayar untuk vendor ini saja (hanya approved)
        $totalDibayarVendor = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        // Ambil breakdown modal per barang untuk vendor ini
        $breakdownBarang = KalkulasiHps::with(['barang'])
            ->where('id_proyek', $pembayaran->penawaran->proyek->id_proyek)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->get()
            ->map(function ($kalkulasi) {
                return (object) [
                    'nama_barang' => $kalkulasi->barang->nama_barang ?? 'N/A',
                    'satuan' => $kalkulasi->barang->satuan ?? 'N/A',
                    'qty' => $kalkulasi->qty,
                    'harga_vendor' => $kalkulasi->harga_vendor,
                    'total_harga_hpp' => $kalkulasi->total_harga_hpp,
                    'harga_akhir' => $kalkulasi->harga_akhir,
                ];
            });

        return view('pages.purchasing.pembayaran-components.pembayaran-detail', compact('pembayaran', 'totalModalVendor', 'totalDibayarVendor', 'breakdownBarang'));
    }

    /**
     * Show payment history for a project
     */
    public function history($id_proyek)
    {
        $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminMarketing'])->findOrFail($id_proyek);
        
        $riwayatPembayaran = Pembayaran::with(['penawaran', 'vendor'])
            ->where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total modal vendor untuk proyek ini
        $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
            ->sum('total_harga_hpp');

        return view('pages.purchasing.pembayaran-components.pembayaran-history', compact('proyek', 'riwayatPembayaran', 'totalModalVendor'));
    }

    /**
     * Calculate payment suggestions for quick input based on vendor modal
     */
    public function calculateSuggestion(Request $request)
    {
        // Role-based access control: Only allow assigned admin_purchasing
        if (Auth::user()->role !== 'admin_purchasing' && Auth::user()->role !== 'superadmin') {
            return response()->json([
                'error' => 'Tidak memiliki akses untuk fitur ini. Hanya admin purchasing/superadmin yang dapat menggunakan kalkulator saran pembayaran.'
            ], 403);
        }

        $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor'])->findOrFail($request->id_proyek);
        
        // Check if current user is assigned to this project
        if ($proyek->id_admin_purchasing != Auth::user()->id_user) {
            return response()->json([
                'error' => 'Tidak memiliki akses untuk proyek ini. Hanya admin purchasing yang ditugaskan yang dapat menggunakan kalkulator saran pembayaran.'
            ], 403);
        }

        $id_vendor = $request->id_vendor;
        
        if ($id_vendor) {
            // Hitung total modal untuk vendor yang dipilih
            $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
                ->where('id_vendor', $id_vendor)
                ->sum('total_harga_hpp');
        } else {
            // Jika tidak ada vendor dipilih, gunakan total semua vendor
            $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
                ->sum('total_harga_hpp');
        }

        $suggestions = [
            'lunas' => $totalModalVendor,
            'dp_30' => $totalModalVendor * 0.3,
            'dp_50' => $totalModalVendor * 0.5,
            'dp_70' => $totalModalVendor * 0.7,
        ];

        return response()->json($suggestions);
    }

    /**
     * Show the form for editing payment
     */
    public function edit($id_pembayaran)
    {
        // Check if user is admin_purchasing and assigned to this project
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Hanya admin purchasing/superadmin yang dapat mengedit pembayaran.');
        }

        $pembayaran = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor', 'vendor'])->findOrFail($id_pembayaran);
        if ($user->role !== 'superadmin' && $pembayaran->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Anda tidak memiliki akses untuk mengedit pembayaran pada proyek ini.');
        }
        
        // Hanya bisa edit jika status masih pending
        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat diubah');
        }

        $proyek = $pembayaran->penawaran->proyek;
        
        // Hitung total modal untuk vendor ini (menggunakan total_harga_hpp dari kalkulasi_hps)
        $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->sum('total_harga_hpp');
        
        // Hitung total yang sudah dibayar untuk vendor ini (exclude pembayaran ini, hanya approved)
        $totalDibayar = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->where('id_pembayaran', '!=', $id_pembayaran)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        $sisaBayar = $totalModalVendor - $totalDibayar;

        // Ambil breakdown modal per barang untuk vendor ini
        $breakdownBarang = KalkulasiHps::with(['barang'])
            ->where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->get()
            ->map(function ($kalkulasi) {
                return (object) [
                    'nama_barang' => $kalkulasi->barang->nama_barang ?? 'N/A',
                    'satuan' => $kalkulasi->barang->satuan ?? 'N/A',
                    'qty' => $kalkulasi->qty,
                    'harga_vendor' => $kalkulasi->harga_vendor,
                    'total_harga_hpp' => $kalkulasi->total_harga_hpp,
                    'harga_akhir' => $kalkulasi->harga_akhir,
                ];
            });

        return view('pages.purchasing.pembayaran-components.pembayaran-edit', compact('pembayaran', 'proyek', 'totalDibayar', 'sisaBayar', 'totalModalVendor', 'breakdownBarang'));
    }

    /**
     * Update payment with proper file management
     */
    public function update(Request $request, $id_pembayaran)
    {
        // Check if user is admin_purchasing and assigned to this project
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Hanya admin purchasing/superadmin yang dapat mengupdate pembayaran.');
        }

        $pembayaran = Pembayaran::with(['penawaran.proyek'])->findOrFail($id_pembayaran);
        if ($user->role !== 'superadmin' && $pembayaran->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Anda tidak memiliki akses untuk mengupdate pembayaran pada proyek ini.');
        }
        
        // Hanya bisa update jika status masih pending
        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat diubah');
        }

        $request->validate([
            'jenis_bayar' => 'required|in:Lunas,DP,Cicilan',
            'nominal_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // optional untuk update
            'catatan' => 'nullable|string'
        ]);

        $proyek = $pembayaran->penawaran->proyek;
        
        // Hitung total modal untuk vendor ini (menggunakan total_harga_hpp dari kalkulasi_hps)
        $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->sum('total_harga_hpp');

        // Validasi nominal pembayaran untuk vendor ini (hanya hitung yang Approved, exclude current)
        $totalDibayar = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->where('id_pembayaran', '!=', $id_pembayaran)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        $sisaBayar = $totalModalVendor - $totalDibayar;

        if ($request->nominal_bayar > $sisaBayar) {
            return back()->with('error', 'Nominal pembayaran melebihi sisa tagihan');
        }

        DB::beginTransaction();
        try {
            $oldBuktiPath = $pembayaran->bukti_bayar;
            $newBuktiPath = $oldBuktiPath; // Default keep old file

            // Handle file upload jika ada file baru
            if ($request->hasFile('bukti_bayar')) {
                // Upload file baru
                $buktiFile = $request->file('bukti_bayar');
                $fileName = time() . '_bukti_' . $buktiFile->getClientOriginalName();
                $buktiFile->storeAs('', $fileName, 'public');
                $newBuktiPath = $fileName;
                
                // Hapus file lama jika ada dan berbeda
                if ($oldBuktiPath && $oldBuktiPath !== $newBuktiPath) {
                    $this->deleteFileIfExists($oldBuktiPath);
                }
            }

            // Update pembayaran
            $pembayaran->update([
                'jenis_bayar' => $request->jenis_bayar,
                'nominal_bayar' => $request->nominal_bayar,
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar' => $newBuktiPath,
                'catatan' => $request->catatan,
            ]);

            DB::commit();

            return redirect()->route('purchasing.pembayaran')
                ->with('success', 'Pembayaran berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file baru jika upload gagal
            if (isset($newBuktiPath) && $newBuktiPath !== $oldBuktiPath) {
                $this->deleteFileIfExists($newBuktiPath);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat mengupdate pembayaran');
        }
    }

    /**
     * Delete payment and clean up files
     */
    public function destroy($id_pembayaran)
    {
        // Check if user is admin_purchasing and assigned to this project
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Hanya admin purchasing/superadmin yang dapat menghapus pembayaran.');
        }

        $pembayaran = Pembayaran::with(['penawaran.proyek'])->findOrFail($id_pembayaran);
        if ($user->role !== 'superadmin' && $pembayaran->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Anda tidak memiliki akses untuk menghapus pembayaran pada proyek ini.');
        }
        
        // Hanya bisa hapus jika status masih pending
        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat dihapus');
        }

        DB::beginTransaction();
        try {
            $buktiPath = $pembayaran->bukti_bayar;
            
            // Hapus record dari database
            $pembayaran->delete();
            
            // Hapus file bukti pembayaran
            if ($buktiPath) {
                $this->deleteFileIfExists($buktiPath);
            }

            DB::commit();

            return redirect()->route('purchasing.pembayaran')
                ->with('success', 'Pembayaran berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus pembayaran');
        }
    }

    /**
     * Helper method to safely delete file from storage
     */
    private function deleteFileIfExists($filePath)
    {
        try {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        } catch (\Exception $e) {
            // Silent fail untuk cleanup
        }
    }

    /**
     * Clean up orphaned files (untuk maintenance)
     */
    public function cleanupOrphanedFiles()
    {
        // Ambil semua file di root storage/app/public yang berkaitan dengan pembayaran (berdasarkan prefix)
        $allFiles = collect(Storage::disk('public')->files(''))->filter(function($file) {
            return strpos($file, '_bukti_') !== false;
        });
        
        // Ambil semua path file yang masih digunakan di database
        $usedFiles = Pembayaran::whereNotNull('bukti_bayar')
            ->pluck('bukti_bayar')
            ->toArray();

        $orphanedFiles = $allFiles->diff($usedFiles);
        $deletedCount = 0;

        foreach ($orphanedFiles as $file) {
            try {
                Storage::disk('public')->delete($file);
                $deletedCount++;
            } catch (\Exception $e) {
                // Silent fail untuk file yang tidak bisa dihapus
            }
        }

        return response()->json([
            'message' => "Cleanup completed. {$deletedCount} orphaned files deleted.",
            'deleted_count' => $deletedCount
        ]);
    }
}
