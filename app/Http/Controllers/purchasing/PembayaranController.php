<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Pembayaran;
use App\Models\KalkulasiHps;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Build the breakdown-barang list for a given proyek+vendor (reused in create/show/edit).
     */
    private function buildBreakdownBarang(int $proyekId, int $vendorId): \Illuminate\Support\Collection
    {
        return KalkulasiHps::with(['barang'])
            ->where('id_proyek', $proyekId)
            ->where('id_vendor', $vendorId)
            ->get()
            ->map(fn($k) => (object) [
                'id_kalkulasi_hps' => $k->id_kalkulasi,
                'id_barang'        => $k->id_barang,
                'nama_barang'      => $k->barang->nama_barang ?? 'N/A',
                'satuan'           => $k->barang->satuan ?? 'N/A',
                'qty'              => $k->qty,
                'harga_vendor'     => $k->harga_vendor,
                'total_harga_hpp'  => $k->total_harga_hpp,
                'harga_akhir'      => $k->harga_akhir,
            ]);
    }

    /**
     * Batch-load KalkulasiHps totals for a list of proyek IDs.
     * Returns: [ id_proyek => [ id_vendor => total_harga_hpp ] ]
     */
    private function batchHpsMap(array $proyekIds): array
    {
        if (empty($proyekIds)) return [];

        $rows = KalkulasiHps::whereIn('id_proyek', $proyekIds)
            ->select('id_proyek', 'id_vendor', DB::raw('SUM(total_harga_hpp) as total'))
            ->groupBy('id_proyek', 'id_vendor')
            ->get();

        $map = [];
        foreach ($rows as $row) {
            $map[$row->id_proyek][$row->id_vendor] = (float) $row->total;
        }
        return $map;
    }

    /**
     * Batch-load approved Pembayaran totals for a list of proyek IDs (via penawaran).
     * Returns: [ id_proyek => [ id_vendor => total_dibayar_approved ] ]
     * Requires proyek collection to be already loaded with penawaranAktif.
     */
    private function batchBayarMap(array $proyekIds, array $penawaranByProyek): array
    {
        if (empty($proyekIds)) return [];

        // Flatten all penawaran IDs we need
        $penawaranIds = [];
        foreach ($penawaranByProyek as $pid => $penawaranId) {
            if ($penawaranId) $penawaranIds[] = $penawaranId;
        }
        if (empty($penawaranIds)) return [];

        // Single query: total approved per (penawaran, vendor)
        $rows = Pembayaran::whereIn('id_penawaran', $penawaranIds)
            ->where('status_verifikasi', 'Approved')
            ->select('id_penawaran', 'id_vendor', DB::raw('SUM(nominal_bayar) as total'))
            ->groupBy('id_penawaran', 'id_vendor')
            ->get();

        // Build reverse map penawaran_id -> proyek_id
        $penawaranToProyek = array_flip($penawaranByProyek);

        $map = [];
        foreach ($rows as $row) {
            $proyekId = $penawaranToProyek[$row->id_penawaran] ?? null;
            if ($proyekId !== null) {
                $map[$proyekId][$row->id_vendor] = (float) $row->total;
            }
        }
        return $map;
    }

    /**
     * Build vendors_data for a proyek using pre-loaded HPS and Bayar maps.
     * @param bool $filterUnpaid  When true, only vendors with sisa_bayar > 0 or missing HPS are included.
     */
    private function attachVendorsData($proyek, array $hpsMap, array $bayarMap, bool $filterUnpaid = false)
    {
        $vendors = $proyek->penawaranAktif->penawaranDetail
            ->pluck('barang.vendor')
            ->unique('id_vendor')
            ->filter();

        $proyek->vendors_data = $vendors->map(function ($vendor) use ($proyek, $hpsMap, $bayarMap) {
            $totalVendor         = $hpsMap[$proyek->id_proyek][$vendor->id_vendor] ?? 0;
            $totalDibayarApproved = $bayarMap[$proyek->id_proyek][$vendor->id_vendor] ?? 0;
            $sisaBayar           = $totalVendor - $totalDibayarApproved;
            $warningHps          = $totalVendor == 0 ? 'Data kalkulasi HPS belum diisi' : null;

            return (object) [
                'vendor'                 => $vendor,
                'total_vendor'           => $totalVendor,
                'total_dibayar_approved' => $totalDibayarApproved,
                'sisa_bayar'             => $sisaBayar,
                'persen_bayar'           => $totalVendor > 0 ? ($totalDibayarApproved / $totalVendor) * 100 : 0,
                'status_lunas'           => $totalVendor > 0 ? $sisaBayar <= 0 : false,
                'warning_hps'            => $warningHps,
            ];
        });

        if ($filterUnpaid) {
            $proyek->vendors_data = $proyek->vendors_data->filter(
                fn($v) => $v->sisa_bayar > 0 || $v->warning_hps
            );
        }

        // Aggregate totals on the proyek object
        $proyek->total_modal_vendor      = $proyek->vendors_data->sum('total_vendor');
        $proyek->total_dibayar_approved  = $proyek->vendors_data->sum('total_dibayar_approved');
        $proyek->sisa_bayar              = $proyek->vendors_data->sum('sisa_bayar');
        $proyek->persen_bayar            = $proyek->total_modal_vendor > 0
            ? ($proyek->total_dibayar_approved / $proyek->total_modal_vendor) * 100
            : 0;
        $proyek->status_lunas = $proyek->sisa_bayar <= 0;

        return $proyek;
    }

    /**
     * Display a listing of projects that need payment processing
     */
    public function index()
    {
        // Ambil parameter filter dan search
        $search             = request()->get('search');
        $statusFilter       = request()->get('status_filter');
        $proyekStatusFilter = request()->get('proyek_status_filter');
        $sortBy             = request()->get('sort_by', 'desc');
        $activeTab          = request()->get('tab', 'perlu-bayar');

        // ----------------------------------------------------------------
        // TAB "Perlu Bayar"
        // ----------------------------------------------------------------
        $proyekPerluBayarQuery = Proyek::with([
            'penawaranAktif.penawaranDetail.barang.vendor',
            'adminMarketing',
            'pembayaran',
        ])
        ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai'])
        ->whereHas('penawaranAktif', fn($q) => $q->where('status', 'ACC'));

        if ($search) {
            $proyekPerluBayarQuery->where(function ($q) use ($search) {
                $q->where('instansi', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%")
                  ->orWhereHas('proyekBarang', fn($sq) => $sq->where('nama_barang', 'like', "%{$search}%"));
            });
        }

        $proyekPerluBayarAll = $proyekPerluBayarQuery->get();

        // Batch KalkulasiHps + approved Pembayaran for all relevant proyek (single query each)
        $proyekIds1          = $proyekPerluBayarAll->pluck('id_proyek')->all();
        $penawaranByProyek1  = $proyekPerluBayarAll->pluck('penawaranAktif.id_penawaran', 'id_proyek')->all();
        $hpsMap1             = $this->batchHpsMap($proyekIds1);
        $bayarMap1           = $this->batchBayarMap($proyekIds1, $penawaranByProyek1);

        $proyekPerluBayarCollection = $proyekPerluBayarAll
            ->map(fn($p) => $this->attachVendorsData($p, $hpsMap1, $bayarMap1, filterUnpaid: true))
            ->filter(fn($p) => $p->vendors_data->count() > 0)
            ->sortByDesc('created_at')
            ->values();

        // Manual paginate
        $currentPage     = (int) request()->get('page', 1);
        $perPage         = 10;
        $proyekPerluBayar = new \Illuminate\Pagination\LengthAwarePaginator(
            $proyekPerluBayarCollection->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $proyekPerluBayarCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        // ----------------------------------------------------------------
        // TAB "Semua Proyek"
        // ----------------------------------------------------------------
        $semuaProyekQuery = Proyek::with([
            'penawaranAktif.penawaranDetail.barang.vendor',
            'adminMarketing',
            'pembayaran',
        ])
        ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai', 'Gagal'])
        ->whereHas('penawaranAktif', fn($q) => $q->where('status', 'ACC'));

        if ($search) {
            $semuaProyekQuery->where(function ($q) use ($search) {
                $q->where('instansi', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%")
                  ->orWhereHas('proyekBarang', fn($sq) => $sq->where('nama_barang', 'like', "%{$search}%"));
            });
        }

        $sortBy === 'asc'
            ? $semuaProyekQuery->orderBy('created_at', 'asc')
            : $semuaProyekQuery->orderBy('created_at', 'desc');

        // Paginate DB query first, then batch only for current-page IDs
        $semuaProyek        = $semuaProyekQuery->paginate(10, ['*'], 'proyek_page');
        $proyekIds2         = $semuaProyek->pluck('id_proyek')->all();
        $penawaranByProyek2 = $semuaProyek->pluck('penawaranAktif.id_penawaran', 'id_proyek')->all();
        $hpsMap2            = $this->batchHpsMap($proyekIds2);
        $bayarMap2          = $this->batchBayarMap($proyekIds2, $penawaranByProyek2);

        $semuaProyek->getCollection()->transform(
            fn($p) => $this->attachVendorsData($p, $hpsMap2, $bayarMap2, filterUnpaid: false)
        );

        // In-memory filter by lunas/belum_lunas after transform
        if ($proyekStatusFilter && $proyekStatusFilter !== 'all') {
            $filtered = $semuaProyek->getCollection()->filter(function ($proyek) use ($proyekStatusFilter) {
                return $proyekStatusFilter === 'lunas' ? $proyek->status_lunas : !$proyek->status_lunas;
            })->values();

            $currentPageProyek = (int) request()->get('proyek_page', 1);
            $semuaProyek = new \Illuminate\Pagination\LengthAwarePaginator(
                $filtered->slice(($currentPageProyek - 1) * $perPage, $perPage)->values(),
                $filtered->count(),
                $perPage,
                $currentPageProyek,
                ['path' => request()->url(), 'pageName' => 'proyek_page']
            );
        }

        // ----------------------------------------------------------------
        // TAB "Semua Pembayaran"
        // ----------------------------------------------------------------
        $semuaPembayaranQuery = Pembayaran::with(['penawaran.proyek.adminMarketing', 'vendor'])
            ->whereHas('penawaran.proyek', function ($q) {
                $q->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai', 'Gagal'])
                  ->whereHas('penawaranAktif', fn($sq) => $sq->where('status', 'ACC'));
            });

        if ($statusFilter && $statusFilter !== 'all') {
            $semuaPembayaranQuery->where('status_verifikasi', $statusFilter);
        }

        if ($search) {
            $semuaPembayaranQuery->where(function ($q) use ($search) {
                $q->whereHas('penawaran.proyek', function ($sq) use ($search) {
                    $sq->where('instansi', 'like', "%{$search}%")
                       ->orWhere('kode_proyek', 'like', "%{$search}%")
                       ->orWhereHas('proyekBarang', fn($ssq) => $ssq->where('nama_barang', 'like', "%{$search}%"));
                })->orWhereHas('vendor', fn($sq) => $sq->where('nama_vendor', 'like', "%{$search}%"));
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
            'activeTab'
        ))->with('currentUser', Auth::user());
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
        $hpsMapCreate  = $this->batchHpsMap([$proyek->id_proyek]);
        $bayarMapCreate = $this->batchBayarMap(
            [$proyek->id_proyek],
            [$proyek->id_proyek => $proyek->penawaranAktif->id_penawaran]
        );

        $vendors = $proyek->penawaranAktif->penawaranDetail
            ->pluck('barang.vendor')
            ->unique('id_vendor')
            ->filter()
            ->map(function ($vendor) use ($proyek, $hpsMapCreate, $bayarMapCreate) {
                $totalVendor  = $hpsMapCreate[$proyek->id_proyek][$vendor->id_vendor] ?? 0;
                $totalDibayar = $bayarMapCreate[$proyek->id_proyek][$vendor->id_vendor] ?? 0;
                $sisaBayar    = $totalVendor - $totalDibayar;

                $vendor->total_vendor  = $totalVendor;
                $vendor->total_dibayar = $totalDibayar;
                $vendor->sisa_bayar    = $sisaBayar;

                return $vendor;
            })
            ->filter(fn($vendor) => $vendor->sisa_bayar > 0);

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
            $totalDibayar     = $bayarMapCreate[$proyek->id_proyek][$selectedVendor->id_vendor] ?? 0;
            $totalModalVendor = $selectedVendor->total_vendor;
            $sisaBayar        = $totalModalVendor - $totalDibayar;
        } else {
            // Untuk semua vendor combined (use pre-loaded maps)
            $totalModalVendor = array_sum($hpsMapCreate[$proyek->id_proyek] ?? []);
            $totalDibayar     = array_sum($bayarMapCreate[$proyek->id_proyek] ?? []);
            $sisaBayar        = $totalModalVendor - $totalDibayar;
        }

        // Ambil breakdown modal per barang jika vendor dipilih
        $breakdownBarang = null;
        $ppnDataTerakhir  = [];   // map: id_kalkulasi → item ppn dari pembayaran terakhir
        if ($selectedVendor) {
            $breakdownBarang = $this->buildBreakdownBarang($proyek->id_proyek, $selectedVendor->id_vendor);

            // Ambil ppn_data dari pembayaran terakhir untuk vendor+penawaran ini
            $pembayaranTerakhir = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                ->where('id_vendor', $selectedVendor->id_vendor)
                ->whereNotNull('ppn_data')
                ->latest('id_pembayaran')
                ->first();

            if ($pembayaranTerakhir && !empty($pembayaranTerakhir->ppn_data['items'])) {
                // Buat map id_kalkulasi_hps → item untuk lookup cepat di blade
                foreach ($pembayaranTerakhir->ppn_data['items'] as $item) {
                    $ppnDataTerakhir[$item['id_kalkulasi_hps']] = $item;
                }
            }
        }

        return view('pages.purchasing.pembayaran-components.pembayaran-form', compact('proyek', 'vendors', 'selectedVendor', 'totalDibayar', 'sisaBayar', 'totalModalVendor', 'breakdownBarang', 'ppnDataTerakhir'));
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
            'nominal_bayar' => 'required|numeric|min:0.01',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'required|array|min:1',
            'bukti_bayar.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB per file
            'catatan' => 'nullable|string',
            'ppn_items' => 'nullable|array',
            'ppn_items.*.id_barang' => 'nullable|string',
            'ppn_items.*.nama_barang' => 'nullable|string',
            'ppn_items.*.harga' => 'nullable|numeric|min:0',
            'ppn_items.*.ada_ppn' => 'nullable|in:1',
            'ppn_items.*.persen_ppn' => 'nullable|numeric|min:0|max:100',
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
            // Upload satu atau lebih bukti pembayaran
            $buktiPaths = [];
            if ($request->hasFile('bukti_bayar')) {
                foreach ($request->file('bukti_bayar') as $buktiFile) {
                    $fileName = time() . '_' . uniqid() . '_bukti_' . $buktiFile->getClientOriginalName();
                    $buktiFile->storeAs('', $fileName, 'public');
                    $buktiPaths[] = $fileName;
                }
            }

            // Proses data PPN per item
            $ppnData = null;
            if ($request->has('ppn_items') && is_array($request->ppn_items)) {
                $ppnItems = [];
                $totalPpn = 0;
                $totalSebelumPpn = 0;

                foreach ($request->ppn_items as $idKalkulasi => $item) {
                    $harga      = floatval($item['harga'] ?? 0);
                    $adaPpn     = isset($item['ada_ppn']) && $item['ada_ppn'] == '1';
                    $persenPpn  = $adaPpn ? floatval($item['persen_ppn'] ?? 11) : 0;

                    // Ekstrak PPN dari harga yang sudah include PPN
                    // harga_include_ppn = harga_sebelum_ppn * (1 + persen/100)
                    // harga_sebelum_ppn = harga / (1 + persen/100)
                    $hargaSebelumPpn = $adaPpn && $persenPpn > 0
                        ? $harga / (1 + $persenPpn / 100)
                        : $harga;
                    $nominalPpn = $harga - $hargaSebelumPpn;

                    $ppnItems[] = [
                        'id_kalkulasi_hps' => $idKalkulasi,
                        'id_barang'        => $item['id_barang'] ?? $idKalkulasi,
                        'nama_barang'      => $item['nama_barang'] ?? '',
                        'harga_total'      => $harga,
                        'ada_ppn'          => $adaPpn,
                        'persen_ppn'       => $adaPpn ? $persenPpn : null,
                        'harga_sebelum_ppn'=> $adaPpn ? round($hargaSebelumPpn, 2) : null,
                        'nominal_ppn'      => $adaPpn ? round($nominalPpn, 2) : null,
                    ];

                    if ($adaPpn) {
                        $totalPpn       += $nominalPpn;
                        $totalSebelumPpn += $hargaSebelumPpn;
                    }
                }

                $ppnData = [
                    'items'            => $ppnItems,
                    'total_ppn'        => round($totalPpn, 2),
                    'total_sebelum_ppn'=> round($totalSebelumPpn, 2),
                    'ada_ppn'          => $totalPpn > 0,
                ];
            }

            // Simpan pembayaran
            $pembayaran = Pembayaran::create([
                'id_penawaran' => $proyek->penawaranAktif->id_penawaran,
                'id_vendor' => $request->id_vendor,
                'jenis_bayar' => $request->jenis_bayar,
                'nominal_bayar' => $request->nominal_bayar,
                'tanggal_bayar' => now()->toDateString(),
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar' => $buktiPaths, // Model accessor will JSON-encode this
                'catatan' => $request->catatan,
                'status_verifikasi' => 'Pending', // Menunggu verifikasi admin keuangan
                'ppn_data' => $ppnData,
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
            foreach ($buktiPaths ?? [] as $uploadedFile) {
                $this->deleteFileIfExists($uploadedFile);
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
        $breakdownBarang = $this->buildBreakdownBarang(
            $pembayaran->penawaran->proyek->id_proyek,
            $pembayaran->id_vendor
        );

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

        // --- PPN Rekap per vendor ---
        // Semua vendor yang ada di proyek ini ditampilkan, baik yang ada PPN maupun tidak.
        $ppnRekap = [];  // [ id_vendor => [...] ]

        // Pre-load semua barang per vendor dari KalkulasiHps (untuk fallback vendor tanpa ppn_data)
        $kalkulasiPerVendor = KalkulasiHps::with('barang')
            ->where('id_proyek', $proyek->id_proyek)
            ->get()
            ->groupBy('id_vendor');

        // Gabungkan vendor dari riwayat pembayaran + vendor dari KalkulasiHps
        $vendorIds = $riwayatPembayaran->pluck('id_vendor')->merge($kalkulasiPerVendor->keys())->unique();

        // Kelompokkan pembayaran per vendor
        $riwayatByVendor = $riwayatPembayaran->groupBy('id_vendor');

        foreach ($vendorIds as $vendorId) {
            $payments   = $riwayatByVendor->get($vendorId, collect());

            // Cari pembayaran terbaru yang punya ppn_data
            $latestWithPpn = $payments->filter(fn($p) => !empty($p->ppn_data['items']))->sortByDesc('id_pembayaran')->first();

            // Nama vendor: dari pembayaran atau dari kalkulasi
            $vendorNama = $payments->first()?->vendor?->nama_vendor
                ?? optional($kalkulasiPerVendor->get($vendorId)?->first()?->barang?->vendor)->nama_vendor
                ?? "Vendor #{$vendorId}";

            // Kalkulasi akumulasi PPN hanya dari pembayaran Approved per vendor
            $approvedPayments        = $payments->where('status_verifikasi', 'Approved');
            $totalPpnApproved        = $approvedPayments->sum(fn($p) => floatval($p->ppn_data['total_ppn'] ?? 0));
            $totalSebelumPpnApproved = $approvedPayments->sum(fn($p) => floatval($p->ppn_data['total_sebelum_ppn'] ?? 0));

            if ($latestWithPpn) {
                // Vendor punya data PPN dari pembayaran terakhir
                $ppnItems = $latestWithPpn->ppn_data['items'];
                $snapshotTanggal = $latestWithPpn->tanggal_bayar;
                $snapshotId      = $latestWithPpn->id_pembayaran;
            } else {
                // Vendor belum pernah menyimpan ppn_data — fallback ke data barang dari KalkulasiHps
                $kalkRows = $kalkulasiPerVendor->get($vendorId, collect());
                $ppnItems = $kalkRows->map(fn($k) => [
                    'id_kalkulasi_hps'  => $k->id_kalkulasi,
                    'id_barang'         => $k->id_barang,
                    'nama_barang'       => $k->barang->nama_barang ?? 'N/A',
                    'harga_total'       => floatval($k->harga_akhir ?? $k->total_harga_hpp),
                    'ada_ppn'           => false,
                    'persen_ppn'        => null,
                    'harga_sebelum_ppn' => null,
                    'nominal_ppn'       => null,
                ])->values()->all();

                $snapshotTanggal = null;
                $snapshotId      = null;
            }

            $ppnRekap[$vendorId] = [
                'vendor_nama'          => $vendorNama,
                'items'                => $ppnItems,
                'total_ppn_approved'   => round($totalPpnApproved, 2),
                'total_sebelum_ppn'    => round($totalSebelumPpnApproved, 2),
                'ada_ppn'              => ($totalPpnApproved > 0),
                'has_snapshot'         => $latestWithPpn !== null,
                'snapshot_tanggal'     => $snapshotTanggal,
                'snapshot_id'          => $snapshotId,
            ];
        }

        return view('pages.purchasing.pembayaran-components.pembayaran-history', compact(
            'proyek', 'riwayatPembayaran', 'totalModalVendor', 'ppnRekap'
        ));
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
        $breakdownBarang = $this->buildBreakdownBarang($proyek->id_proyek, $pembayaran->id_vendor);

        // Ambil ppn_data: dari pembayaran ini sendiri, atau fallback dari pembayaran lain
        $ppnDataExisting = $pembayaran->ppn_data;
        if (empty($ppnDataExisting)) {
            $pembayaranDenganPpn = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
                ->where('id_vendor', $pembayaran->id_vendor)
                ->where('id_pembayaran', '!=', $id_pembayaran)
                ->whereNotNull('ppn_data')
                ->latest('id_pembayaran')
                ->first();
            $ppnDataExisting = $pembayaranDenganPpn?->ppn_data;
        }

        // Buat map id_kalkulasi_hps => item ppn untuk lookup di blade
        $ppnMapExisting = [];
        if (!empty($ppnDataExisting['items'])) {
            foreach ($ppnDataExisting['items'] as $item) {
                $ppnMapExisting[$item['id_kalkulasi_hps']] = $item;
            }
        }

        return view('pages.purchasing.pembayaran-components.pembayaran-edit', compact('pembayaran', 'proyek', 'totalDibayar', 'sisaBayar', 'totalModalVendor', 'breakdownBarang', 'ppnDataExisting', 'ppnMapExisting'));
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
            'nominal_bayar' => 'required|numeric|min:0.01',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'nullable|array',
            'bukti_bayar.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'delete_files' => 'nullable|array',
            'delete_files.*' => 'string',
            'catatan' => 'nullable|string',
            'ppn_items' => 'nullable|array',
            'ppn_items.*.id_barang' => 'nullable|string',
            'ppn_items.*.nama_barang' => 'nullable|string',
            'ppn_items.*.harga' => 'nullable|numeric|min:0',
            'ppn_items.*.ada_ppn' => 'nullable|in:1',
            'ppn_items.*.persen_ppn' => 'nullable|numeric|min:0|max:100',
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
            $existingFiles = $pembayaran->bukti_bayar_array; // semua file lama
            $filesToDelete = $request->input('delete_files', []); // file lama yang ingin dihapus

            // Hapus file yang dipilih untuk dihapus
            foreach ($filesToDelete as $fileToDelete) {
                // Pastikan hanya menghapus file milik pembayaran ini (security check)
                if (in_array($fileToDelete, $existingFiles)) {
                    $this->deleteFileIfExists($fileToDelete);
                }
            }

            // File lama yang masih tersisa (tidak dihapus)
            $remainingFiles = array_values(array_diff($existingFiles, $filesToDelete));

            // Upload file baru jika ada
            $newlyUploadedFiles = [];
            if ($request->hasFile('bukti_bayar')) {
                foreach ($request->file('bukti_bayar') as $buktiFile) {
                    $fileName = time() . '_' . uniqid() . '_bukti_' . $buktiFile->getClientOriginalName();
                    $buktiFile->storeAs('', $fileName, 'public');
                    $newlyUploadedFiles[] = $fileName;
                }
            }

            // Gabungkan file lama yang tersisa + file baru
            $finalFiles = array_merge($remainingFiles, $newlyUploadedFiles);

            // Proses data PPN per item
            $ppnData = null;
            if ($request->has('ppn_items') && is_array($request->ppn_items)) {
                $ppnItems    = [];
                $totalPpn    = 0;
                $totalSebelum = 0;

                foreach ($request->ppn_items as $idKalkulasi => $item) {
                    $harga     = floatval($item['harga'] ?? 0);
                    $adaPpn    = isset($item['ada_ppn']) && $item['ada_ppn'] == '1';
                    $persenPpn = $adaPpn ? floatval($item['persen_ppn'] ?? 11) : 0;

                    $hargaSebelumPpn = $adaPpn && $persenPpn > 0
                        ? $harga / (1 + $persenPpn / 100)
                        : $harga;
                    $nominalPpn = $harga - $hargaSebelumPpn;

                    $ppnItems[] = [
                        'id_kalkulasi_hps'  => $idKalkulasi,
                        'id_barang'         => $item['id_barang'] ?? $idKalkulasi,
                        'nama_barang'       => $item['nama_barang'] ?? '',
                        'harga_total'       => $harga,
                        'ada_ppn'           => $adaPpn,
                        'persen_ppn'        => $adaPpn ? $persenPpn : null,
                        'harga_sebelum_ppn' => $adaPpn ? round($hargaSebelumPpn, 2) : null,
                        'nominal_ppn'       => $adaPpn ? round($nominalPpn, 2) : null,
                    ];

                    if ($adaPpn) {
                        $totalPpn    += $nominalPpn;
                        $totalSebelum += $hargaSebelumPpn;
                    }
                }

                $ppnData = [
                    'items'             => $ppnItems,
                    'total_ppn'         => round($totalPpn, 2),
                    'total_sebelum_ppn' => round($totalSebelum, 2),
                    'ada_ppn'           => $totalPpn > 0,
                ];
            }

            // Update pembayaran
            $pembayaran->update([
                'jenis_bayar'  => $request->jenis_bayar,
                'nominal_bayar'=> $request->nominal_bayar,
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar'  => $finalFiles,
                'catatan'      => $request->catatan,
                'ppn_data'     => $ppnData,
            ]);

            DB::commit();

            return redirect()->route('purchasing.pembayaran')
                ->with('success', 'Pembayaran berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file baru yang sudah terupload jika terjadi error
            foreach ($newlyUploadedFiles ?? [] as $newFile) {
                $this->deleteFileIfExists($newFile);
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
            $buktiPaths = $pembayaran->bukti_bayar_array; // get all files as array
            
            // Hapus record dari database
            $pembayaran->delete();
            
            // Hapus semua file bukti pembayaran
            foreach ($buktiPaths as $filePath) {
                $this->deleteFileIfExists($filePath);
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
