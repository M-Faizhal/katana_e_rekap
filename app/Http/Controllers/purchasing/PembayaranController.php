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
use App\Models\Vendor;
use App\Models\SuratPo;
use App\Models\SuratPoItem;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;

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

        $penawaranIds = [];
        foreach ($penawaranByProyek as $pid => $penawaranId) {
            if ($penawaranId) $penawaranIds[] = $penawaranId;
        }
        if (empty($penawaranIds)) return [];

        $rows = Pembayaran::whereIn('id_penawaran', $penawaranIds)
            ->where('status_verifikasi', 'Approved')
            ->select('id_penawaran', 'id_vendor', DB::raw('SUM(nominal_bayar) as total'))
            ->groupBy('id_penawaran', 'id_vendor')
            ->get();

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
            $totalVendor          = $hpsMap[$proyek->id_proyek][$vendor->id_vendor] ?? 0;
            $totalDibayarApproved = $bayarMap[$proyek->id_proyek][$vendor->id_vendor] ?? 0;
            $sisaBayar            = $totalVendor - $totalDibayarApproved;
            $warningHps           = $totalVendor == 0 ? 'Data kalkulasi HPS belum diisi' : null;

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

        $proyek->total_modal_vendor     = $proyek->vendors_data->sum('total_vendor');
        $proyek->total_dibayar_approved = $proyek->vendors_data->sum('total_dibayar_approved');
        $proyek->sisa_bayar             = $proyek->vendors_data->sum('sisa_bayar');
        $proyek->persen_bayar           = $proyek->total_modal_vendor > 0
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
        $search             = request()->get('search');
        $statusFilter       = request()->get('status_filter');
        $proyekStatusFilter = request()->get('proyek_status_filter');
        $sortBy             = request()->get('sort_by', 'desc');
        $activeTab          = request()->get('tab', 'perlu-bayar');
        $poSearch           = request()->get('po_search');

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

        $proyekIds1         = $proyekPerluBayarAll->pluck('id_proyek')->all();
        $penawaranByProyek1 = $proyekPerluBayarAll->pluck('penawaranAktif.id_penawaran', 'id_proyek')->all();
        $hpsMap1            = $this->batchHpsMap($proyekIds1);
        $bayarMap1          = $this->batchBayarMap($proyekIds1, $penawaranByProyek1);

        $proyekPerluBayarCollection = $proyekPerluBayarAll
            ->map(fn($p) => $this->attachVendorsData($p, $hpsMap1, $bayarMap1, filterUnpaid: true))
            ->filter(fn($p) => $p->vendors_data->count() > 0)
            ->sortByDesc('created_at')
            ->values();

        $currentPage      = (int) request()->get('page', 1);
        $perPage          = 10;
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

        $semuaProyek        = $semuaProyekQuery->paginate(10, ['*'], 'proyek_page');
        $proyekIds2         = $semuaProyek->pluck('id_proyek')->all();
        $penawaranByProyek2 = $semuaProyek->pluck('penawaranAktif.id_penawaran', 'id_proyek')->all();
        $hpsMap2            = $this->batchHpsMap($proyekIds2);
        $bayarMap2          = $this->batchBayarMap($proyekIds2, $penawaranByProyek2);

        $semuaProyek->getCollection()->transform(
            fn($p) => $this->attachVendorsData($p, $hpsMap2, $bayarMap2, filterUnpaid: false)
        );

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
        // TAB "Pembuatan Surat PO"
        // ----------------------------------------------------------------
        $proyekPoQuery = Proyek::with([
            'penawaranAktif.penawaranDetail.barang.vendor',
            'adminMarketing',
            'pembayaran',
        ])
            ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai', 'Gagal'])
            ->whereHas('penawaranAktif', fn($q) => $q->where('status', 'ACC'));

        if ($poSearch) {
            $proyekPoQuery->where(function ($q) use ($poSearch) {
                $q->where('instansi', 'like', "%{$poSearch}%")
                  ->orWhere('kode_proyek', 'like', "%{$poSearch}%")
                  ->orWhereHas('proyekBarang', fn($sq) => $sq->where('nama_barang', 'like', "%{$poSearch}%"));
            });
        }

        $proyekPoQuery->orderBy('created_at', 'desc');

        $proyekPo            = $proyekPoQuery->paginate(10, ['*'], 'po_page');
        $proyekIdsPo         = $proyekPo->pluck('id_proyek')->all();
        $penawaranByProyekPo = $proyekPo->pluck('penawaranAktif.id_penawaran', 'id_proyek')->all();
        $hpsMapPo            = $this->batchHpsMap($proyekIdsPo);
        $bayarMapPo          = $this->batchBayarMap($proyekIdsPo, $penawaranByProyekPo);

        $proyekPo->getCollection()->transform(
            fn($p) => $this->attachVendorsData($p, $hpsMapPo, $bayarMapPo, filterUnpaid: false)
        );

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
            'activeTab',
            'proyekPo',
            'poSearch'
        ))->with('currentUser', Auth::user());
    }

    /**
     * Show the form for creating a new payment
     */
    public function create($id_proyek, $id_vendor = null)
    {
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

        if (!$proyek->penawaranAktif || $proyek->penawaranAktif->status !== 'ACC') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Proyek ini belum memiliki penawaran yang di-ACC');
        }

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

        $selectedVendor = null;
        if ($id_vendor) {
            $selectedVendor = $vendors->firstWhere('id_vendor', $id_vendor);
            if (!$selectedVendor) {
                return redirect()->route('purchasing.pembayaran')
                    ->with('error', 'Vendor tidak ditemukan atau sudah lunas');
            }
        }

        if ($selectedVendor) {
            $totalDibayar     = $bayarMapCreate[$proyek->id_proyek][$selectedVendor->id_vendor] ?? 0;
            $totalModalVendor = $selectedVendor->total_vendor;
            $sisaBayar        = $totalModalVendor - $totalDibayar;
        } else {
            $totalModalVendor = array_sum($hpsMapCreate[$proyek->id_proyek] ?? []);
            $totalDibayar     = array_sum($bayarMapCreate[$proyek->id_proyek] ?? []);
            $sisaBayar        = $totalModalVendor - $totalDibayar;
        }

        $breakdownBarang = null;
        $ppnDataTerakhir  = [];
        if ($selectedVendor) {
            $breakdownBarang = $this->buildBreakdownBarang($proyek->id_proyek, $selectedVendor->id_vendor);

            $pembayaranTerakhir = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                ->where('id_vendor', $selectedVendor->id_vendor)
                ->whereNotNull('ppn_data')
                ->latest('id_pembayaran')
                ->first();

            if ($pembayaranTerakhir && !empty($pembayaranTerakhir->ppn_data['items'])) {
                foreach ($pembayaranTerakhir->ppn_data['items'] as $item) {
                    $ppnDataTerakhir[$item['id_kalkulasi_hps']] = $item;
                }
            }
        }

        return view('pages.purchasing.pembayaran-components.pembayaran-form', compact(
            'proyek', 'vendors', 'selectedVendor', 'totalDibayar',
            'sisaBayar', 'totalModalVendor', 'breakdownBarang', 'ppnDataTerakhir'
        ));
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
            'id_proyek'                  => 'required|exists:proyek,id_proyek',
            'id_vendor'                  => 'required|exists:vendor,id_vendor',
            'jenis_bayar'                => 'required|in:Lunas,DP,Cicilan',
            'nominal_bayar'              => 'required|numeric|min:0.01',
            'metode_bayar'               => 'required|string',
            'bukti_bayar'                => 'required|array|min:1',
            'bukti_bayar.*'              => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan'                    => 'nullable|string',
            'ppn_items'                  => 'nullable|array',
            'ppn_items.*.id_barang'      => 'nullable|string',
            'ppn_items.*.nama_barang'    => 'nullable|string',
            'ppn_items.*.harga'          => 'nullable|numeric|min:0',
            'ppn_items.*.ada_ppn'        => 'nullable|in:1',
            'ppn_items.*.persen_ppn'     => 'nullable|numeric|min:0|max:100',
        ]);

        $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor'])->findOrFail($request->id_proyek);

        $totalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $request->id_vendor)
            ->sum('total_harga_hpp');

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
            $buktiPaths = [];
            if ($request->hasFile('bukti_bayar')) {
                foreach ($request->file('bukti_bayar') as $buktiFile) {
                    $fileName = time() . '_' . uniqid() . '_bukti_' . $buktiFile->getClientOriginalName();
                    $buktiFile->storeAs('', $fileName, 'public');
                    $buktiPaths[] = $fileName;
                }
            }

            $ppnData = null;
            if ($request->has('ppn_items') && is_array($request->ppn_items)) {
                $ppnItems        = [];
                $totalPpn        = 0;
                $totalSebelumPpn = 0;

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
                        $totalPpn        += $nominalPpn;
                        $totalSebelumPpn += $hargaSebelumPpn;
                    }
                }

                $ppnData = [
                    'items'             => $ppnItems,
                    'total_ppn'         => round($totalPpn, 2),
                    'total_sebelum_ppn' => round($totalSebelumPpn, 2),
                    'ada_ppn'           => $totalPpn > 0,
                ];
            }

            $pembayaran = Pembayaran::create([
                'id_penawaran'      => $proyek->penawaranAktif->id_penawaran,
                'id_vendor'         => $request->id_vendor,
                'jenis_bayar'       => $request->jenis_bayar,
                'nominal_bayar'     => $request->nominal_bayar,
                'tanggal_bayar'     => now()->toDateString(),
                'metode_bayar'      => $request->metode_bayar,
                'bukti_bayar'       => $buktiPaths,
                'catatan'           => $request->catatan,
                'status_verifikasi' => 'Pending',
                'ppn_data'          => $ppnData,
            ]);

            $allVendorsData = $proyek->penawaranAktif->penawaranDetail
                ->groupBy('barang.id_vendor')
                ->map(function ($details, $vendorId) use ($proyek, $request) {
                    $totalVendor = $details->sum(function ($detail) {
                        return $detail->qty * $detail->barang->harga_vendor;
                    });
                    $totalDibayarVendor = $proyek->pembayaran
                        ->where('id_vendor', $vendorId)
                        ->where('status_verifikasi', 'Approved')
                        ->sum('nominal_bayar');

                    if ($vendorId == $request->id_vendor) {
                        $totalDibayarVendor += $request->nominal_bayar;
                    }

                    return $totalVendor <= $totalDibayarVendor;
                });

            if ($allVendorsData->every(fn($isLunas) => $isLunas)) {
                $proyek->update(['status' => 'Pengiriman']);
            }

            DB::commit();

            return redirect()->route('purchasing.pembayaran')
                ->with('success', 'Pembayaran berhasil disimpan dan menunggu verifikasi admin keuangan');

        } catch (\Exception $e) {
            DB::rollback();

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
        $pembayaran = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor', 'vendor'])
            ->findOrFail($id_pembayaran);

        $totalModalVendor = KalkulasiHps::where('id_proyek', $pembayaran->penawaran->proyek->id_proyek)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->sum('total_harga_hpp');

        $totalDibayarVendor = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        $breakdownBarang = $this->buildBreakdownBarang(
            $pembayaran->penawaran->proyek->id_proyek,
            $pembayaran->id_vendor
        );

        return view('pages.purchasing.pembayaran-components.pembayaran-detail', compact(
            'pembayaran', 'totalModalVendor', 'totalDibayarVendor', 'breakdownBarang'
        ));
    }

    /**
     * Show payment history for a project
     */
    public function history($id_proyek)
    {
        $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminMarketing'])
            ->findOrFail($id_proyek);

        $riwayatPembayaran = Pembayaran::with(['penawaran', 'vendor'])
            ->where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
            ->sum('total_harga_hpp');

        $ppnRekap = [];

        $kalkulasiPerVendor = KalkulasiHps::with('barang')
            ->where('id_proyek', $proyek->id_proyek)
            ->get()
            ->groupBy('id_vendor');

        $vendorIds       = $riwayatPembayaran->pluck('id_vendor')->merge($kalkulasiPerVendor->keys())->unique();
        $riwayatByVendor = $riwayatPembayaran->groupBy('id_vendor');

        foreach ($vendorIds as $vendorId) {
            $payments      = $riwayatByVendor->get($vendorId, collect());
            $latestWithPpn = $payments->filter(fn($p) => !empty($p->ppn_data['items']))->sortByDesc('id_pembayaran')->first();

            $vendorNama = $payments->first()?->vendor?->nama_vendor
                ?? optional($kalkulasiPerVendor->get($vendorId)?->first()?->barang?->vendor)->nama_vendor
                ?? "Vendor #{$vendorId}";

            $approvedPayments        = $payments->where('status_verifikasi', 'Approved');
            $totalPpnApproved        = $approvedPayments->sum(fn($p) => floatval($p->ppn_data['total_ppn'] ?? 0));
            $totalSebelumPpnApproved = $approvedPayments->sum(fn($p) => floatval($p->ppn_data['total_sebelum_ppn'] ?? 0));

            if ($latestWithPpn) {
                $ppnItems        = $latestWithPpn->ppn_data['items'];
                $snapshotTanggal = $latestWithPpn->tanggal_bayar;
                $snapshotId      = $latestWithPpn->id_pembayaran;
            } else {
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
                'vendor_nama'        => $vendorNama,
                'items'              => $ppnItems,
                'total_ppn_approved' => round($totalPpnApproved, 2),
                'total_sebelum_ppn'  => round($totalSebelumPpnApproved, 2),
                'ada_ppn'            => ($totalPpnApproved > 0),
                'has_snapshot'       => $latestWithPpn !== null,
                'snapshot_tanggal'   => $snapshotTanggal,
                'snapshot_id'        => $snapshotId,
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
        if (Auth::user()->role !== 'admin_purchasing' && Auth::user()->role !== 'superadmin') {
            return response()->json([
                'error' => 'Tidak memiliki akses untuk fitur ini.'
            ], 403);
        }

        $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor'])->findOrFail($request->id_proyek);

        if (Auth::user()->role !== 'superadmin' && $proyek->id_admin_purchasing != Auth::user()->id_user) {
            return response()->json([
                'error' => 'Tidak memiliki akses untuk proyek ini.'
            ], 403);
        }

        $id_vendor = $request->id_vendor;

        if ($id_vendor) {
            $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
                ->where('id_vendor', $id_vendor)
                ->sum('total_harga_hpp');
        } else {
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
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Hanya admin purchasing/superadmin yang dapat mengedit pembayaran.');
        }

        $pembayaran = Pembayaran::with(['penawaran.proyek.penawaranAktif.penawaranDetail.barang.vendor', 'vendor'])
            ->findOrFail($id_pembayaran);

        if ($user->role !== 'superadmin' && $pembayaran->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Anda tidak memiliki akses untuk mengedit pembayaran pada proyek ini.');
        }

        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat diubah');
        }

        $proyek = $pembayaran->penawaran->proyek;

        $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->sum('total_harga_hpp');

        $totalDibayar = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->where('id_pembayaran', '!=', $id_pembayaran)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        $sisaBayar = $totalModalVendor - $totalDibayar;

        $breakdownBarang = $this->buildBreakdownBarang($proyek->id_proyek, $pembayaran->id_vendor);

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

        $ppnMapExisting = [];
        if (!empty($ppnDataExisting['items'])) {
            foreach ($ppnDataExisting['items'] as $item) {
                $ppnMapExisting[$item['id_kalkulasi_hps']] = $item;
            }
        }

        return view('pages.purchasing.pembayaran-components.pembayaran-edit', compact(
            'pembayaran', 'proyek', 'totalDibayar', 'sisaBayar',
            'totalModalVendor', 'breakdownBarang', 'ppnDataExisting', 'ppnMapExisting'
        ));
    }

    /**
     * Update payment with proper file management
     */
    public function update(Request $request, $id_pembayaran)
    {
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

        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat diubah');
        }

        $request->validate([
            'jenis_bayar'            => 'required|in:Lunas,DP,Cicilan',
            'nominal_bayar'          => 'required|numeric|min:0.01',
            'metode_bayar'           => 'required|string',
            'bukti_bayar'            => 'nullable|array',
            'bukti_bayar.*'          => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'delete_files'           => 'nullable|array',
            'delete_files.*'         => 'string',
            'catatan'                => 'nullable|string',
            'ppn_items'              => 'nullable|array',
            'ppn_items.*.id_barang'  => 'nullable|string',
            'ppn_items.*.nama_barang'=> 'nullable|string',
            'ppn_items.*.harga'      => 'nullable|numeric|min:0',
            'ppn_items.*.ada_ppn'    => 'nullable|in:1',
            'ppn_items.*.persen_ppn' => 'nullable|numeric|min:0|max:100',
        ]);

        $proyek = $pembayaran->penawaran->proyek;

        $totalModalVendor = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $pembayaran->id_vendor)
            ->sum('total_harga_hpp');

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
            $existingFiles = $pembayaran->bukti_bayar_array;
            $filesToDelete = $request->input('delete_files', []);

            foreach ($filesToDelete as $fileToDelete) {
                if (in_array($fileToDelete, $existingFiles)) {
                    $this->deleteFileIfExists($fileToDelete);
                }
            }

            $remainingFiles     = array_values(array_diff($existingFiles, $filesToDelete));
            $newlyUploadedFiles = [];

            if ($request->hasFile('bukti_bayar')) {
                foreach ($request->file('bukti_bayar') as $buktiFile) {
                    $fileName = time() . '_' . uniqid() . '_bukti_' . $buktiFile->getClientOriginalName();
                    $buktiFile->storeAs('', $fileName, 'public');
                    $newlyUploadedFiles[] = $fileName;
                }
            }

            $finalFiles = array_merge($remainingFiles, $newlyUploadedFiles);

            $ppnData = null;
            if ($request->has('ppn_items') && is_array($request->ppn_items)) {
                $ppnItems     = [];
                $totalPpn     = 0;
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
                        $totalPpn     += $nominalPpn;
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

            $pembayaran->update([
                'jenis_bayar'   => $request->jenis_bayar,
                'nominal_bayar' => $request->nominal_bayar,
                'metode_bayar'  => $request->metode_bayar,
                'bukti_bayar'   => $finalFiles,
                'catatan'       => $request->catatan,
                'ppn_data'      => $ppnData,
            ]);

            DB::commit();

            return redirect()->route('purchasing.pembayaran')
                ->with('success', 'Pembayaran berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();

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

        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat dihapus');
        }

        DB::beginTransaction();
        try {
            $buktiPaths = $pembayaran->bukti_bayar_array;
            $pembayaran->delete();

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
            // Silent fail
        }
    }

    /**
     * Clean up orphaned files (untuk maintenance)
     */
    public function cleanupOrphanedFiles()
    {
        $allFiles = collect(Storage::disk('public')->files(''))->filter(function ($file) {
            return strpos($file, '_bukti_') !== false;
        });

        $usedFiles = Pembayaran::whereNotNull('bukti_bayar')
            ->pluck('bukti_bayar')
            ->toArray();

        $orphanedFiles = $allFiles->diff($usedFiles);
        $deletedCount  = 0;

        foreach ($orphanedFiles as $file) {
            try {
                Storage::disk('public')->delete($file);
                $deletedCount++;
            } catch (\Exception $e) {
                // Silent fail
            }
        }

        return response()->json([
            'message'       => "Cleanup completed. {$deletedCount} orphaned files deleted.",
            'deleted_count' => $deletedCount,
        ]);
    }

    /**
     * Placeholder page: Pembuatan Surat PO (per proyek + vendor)
     */
    public function pembuatanSuratPo($id_proyek, $id_vendor)
    {
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak. Hanya admin purchasing/superadmin yang dapat membuat Surat PO.');
        }

        $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminPurchasing'])
            ->findOrFail($id_proyek);
        $vendor = Vendor::findOrFail($id_vendor);

        if ($user->role !== 'superadmin' && $proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pembayaran', ['tab' => 'pembuatan-surat-po'])
                ->with('error', 'Akses ditolak. Anda tidak memiliki akses untuk proyek ini.');
        }

        $kalkulasiItems = KalkulasiHps::with('barang')
            ->where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $vendor->id_vendor)
            ->orderBy('id_barang')
            ->get();

        $suratPo = SuratPo::with('items')
            ->where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $vendor->id_vendor)
            ->first();

        if (!$suratPo) {
            $suratPo = SuratPo::create([
                'id_proyek'           => $proyek->id_proyek,
                'id_vendor'           => $vendor->id_vendor,
                'id_user_purchasing'  => $proyek->id_admin_purchasing,
                'tanggal_surat'       => now()->toDateString(),
                'po_number'           => $proyek->kode_proyek,
                'ship_to_instansi'    => $proyek->instansi ?? '',
                'ship_to_alamat'      => $proyek->kab_kota ?? '',
                'comments_html'       => null,
                'tax'                 => 0,
                'shipping'            => 0,
                'other'               => 0,
                'dp_percent'          => 30,
                'termin2_percent'     => 30,
                'pelunasan_percent'   => 40,
            ]);
        }

        $existingByKalkulasi = $suratPo->items->keyBy('id_kalkulasi_hps');
        foreach ($kalkulasiItems as $k) {
            if (!$existingByKalkulasi->has($k->id_kalkulasi)) {
                SuratPoItem::create([
                    'id_surat_po'      => $suratPo->id_surat_po,
                    'id_barang'        => $k->id_barang,
                    'id_kalkulasi_hps' => $k->id_kalkulasi,
                    'qty'              => (int) $k->qty,
                    'unit_price'       => (float) ($k->harga_akhir ?? 0),
                    'spec_html'        => null,
                ]);
            }
        }

        $suratPo->load('items.barang');

        $dpp             = (float) $suratPo->dpp;
        $total           = (float) $suratPo->total;
        $dpAmount        = (float) $suratPo->dp_amount;
        $termin2Amount   = (float) $suratPo->termin2_amount;
        $pelunasanAmount = (float) $suratPo->pelunasan_amount;

        return view('pages.purchasing.pembayaran-components.pembuatan-surat-po', compact(
            'proyek', 'vendor', 'suratPo', 'kalkulasiItems',
            'dpp', 'total', 'dpAmount', 'termin2Amount', 'pelunasanAmount'
        ));
    }

    /**
     * Simpan / update draft Surat PO beserta lampiran PDF.
     */
    public function simpanSuratPo(Request $request, $id_proyek, $id_vendor)
    {
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak.');
        }

        $proyek = Proyek::with('adminPurchasing')->findOrFail($id_proyek);
        $vendor = Vendor::findOrFail($id_vendor);

        if ($user->role !== 'superadmin' && $proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pembayaran', ['tab' => 'pembuatan-surat-po'])
                ->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'tanggal_surat'                => ['nullable', 'date'],
            'ship_to_instansi'             => ['nullable', 'string', 'max:255'],
            'ship_to_alamat'               => ['nullable', 'string'],
            'comments_html'                => ['nullable', 'string'],
            'tax'                          => ['nullable', 'numeric', 'min:0'],
            'shipping'                     => ['nullable', 'numeric', 'min:0'],
            'other'                        => ['nullable', 'numeric', 'min:0'],
            'dp_percent'                   => ['required', 'numeric', 'min:0', 'max:100'],
            'termin2_percent'              => ['required', 'numeric', 'min:0', 'max:100'],
            'pelunasan_percent'            => ['required', 'numeric', 'min:0', 'max:100'],
            'items'                        => ['required', 'array'],
            'items.*.id_surat_po_item'     => ['required', 'integer'],
            'items.*.spec_html'            => ['nullable', 'string'],
            'lampiran_pdfs'                => ['nullable'],
            'lampiran_pdfs.*'              => ['file', 'mimes:pdf', 'max:10240'],
        ]);

        DB::transaction(function () use ($request, $proyek, $vendor) {
            $suratPo = SuratPo::firstOrCreate(
                ['id_proyek' => $proyek->id_proyek, 'id_vendor' => $vendor->id_vendor],
                [
                    'id_user_purchasing' => $proyek->id_admin_purchasing,
                    'tanggal_surat'      => now()->toDateString(),
                    'po_number'          => $proyek->kode_proyek,
                    'ship_to_instansi'   => $proyek->instansi ?? '',
                    'ship_to_alamat'     => $proyek->kab_kota ?? '',
                ]
            );

            $suratPo->update([
                'tanggal_surat'    => $request->tanggal_surat,
                'po_number'        => $proyek->kode_proyek,
                'ship_to_instansi' => $request->ship_to_instansi,
                'ship_to_alamat'   => $request->ship_to_alamat,
                'comments_html'    => $request->comments_html,
                'tax'              => $request->tax ?? 0,
                'shipping'         => $request->shipping ?? 0,
                'other'            => $request->other ?? 0,
                'dp_percent'       => $request->dp_percent,
                'termin2_percent'  => $request->termin2_percent,
                'pelunasan_percent'=> $request->pelunasan_percent,
            ]);

            foreach ($request->items as $itemPayload) {
                SuratPoItem::where('id_surat_po_item', $itemPayload['id_surat_po_item'])
                    ->where('id_surat_po', $suratPo->id_surat_po)
                    ->update([
                        'spec_html' => $itemPayload['spec_html'] ?? null,
                    ]);
            }

            // Upload lampiran PDF (append ke lampiran_files JSON)
            if ($request->hasFile('lampiran_pdfs')) {
                $rawRow   = DB::table('surat_po')->where('id_surat_po', $suratPo->id_surat_po)->value('lampiran_files');
                $existing = [];
                if ($rawRow) {
                    $decoded  = json_decode($rawRow, true);
                    $existing = is_array($decoded) ? $decoded : [];
                }

                foreach ($request->file('lampiran_pdfs') as $file) {
                    if (!$file || !$file->isValid()) continue;

                    $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
                    $filename = time() . '_' . uniqid() . '_' . $safeName;
                    $path     = $file->storeAs('surat-po/lampiran', $filename, 'public');

                    $existing[] = [
                        'path'          => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at'   => now()->toDateTimeString(),
                        'size'          => $file->getSize(),
                    ];
                }

                DB::table('surat_po')
                    ->where('id_surat_po', $suratPo->id_surat_po)
                    ->update(['lampiran_files' => json_encode(array_values($existing))]);
            }
        });

        return redirect()->route('purchasing.pembayaran.pembuatan-surat-po', [$proyek->id_proyek, $vendor->id_vendor])
            ->with('success', 'Draft Surat PO berhasil disimpan.');
    }

    /**
     * Hapus 1 lampiran dari Surat PO.
     */
    public function deleteSuratPoLampiran(Request $request, $id_proyek, $id_vendor)
    {
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate(['path' => 'required|string']);

        $proyek = Proyek::findOrFail($id_proyek);
        if ($user->role !== 'superadmin' && $proyek->id_admin_purchasing != $user->id_user) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $suratPo = SuratPo::where('id_proyek', $id_proyek)->where('id_vendor', $id_vendor)->firstOrFail();
        $path    = $request->input('path');

        $rawRow = DB::table('surat_po')->where('id_surat_po', $suratPo->id_surat_po)->value('lampiran_files');
        $files  = [];
        if ($rawRow) {
            $decoded = json_decode($rawRow, true);
            $files   = is_array($decoded) ? $decoded : [];
        }

        $newFiles = [];
        foreach ($files as $f) {
            if (($f['path'] ?? null) === $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
                continue;
            }
            $newFiles[] = $f;
        }

        DB::table('surat_po')
            ->where('id_surat_po', $suratPo->id_surat_po)
            ->update(['lampiran_files' => json_encode(array_values($newFiles))]);

        $suratPo = SuratPo::find($suratPo->id_surat_po);

        return response()->json([
            'success'        => true,
            'message'        => 'Lampiran berhasil dihapus.',
            'lampiran_files' => $suratPo->lampiran_files_list,
        ]);
    }

    /**
     * Merge generated Surat PO PDF with any uploaded lampiran PDFs.
     */
    private function mergeSuratPoWithLampiran(string $poPdfContent, ?SuratPo $suratPo = null): string
    {
        $lampiranList = [];

        try {
            if ($suratPo) {
                $rawRow = DB::table('surat_po')
                    ->where('id_surat_po', $suratPo->id_surat_po)
                    ->value('lampiran_files');

                if ($rawRow) {
                    $decoded      = json_decode($rawRow, true);
                    $lampiranList = is_array($decoded) ? $decoded : [];
                }
            }
        } catch (\Throwable $e) {
            // silent
        }

        if (empty($lampiranList)) {
            return $poPdfContent;
        }

        try {
            $merger = new Merger();
            $merger->addRaw($poPdfContent);

            foreach ($lampiranList as $f) {
                $path = $f['path'] ?? null;
                if (!$path) continue;

                $absolutePath = storage_path('app/public/' . ltrim($path, '/\\'));
                if (!file_exists($absolutePath)) continue;

                $raw = file_get_contents($absolutePath);
                if (!$raw || strlen($raw) === 0) continue;

                $merger->addRaw($raw);
            }

            return $merger->merge();
        } catch (\Throwable $e) {
            return $poPdfContent;
        }
    }

    /**
     * Preview Surat PO as PDF (merged with lampiran if any).
     */
    public function previewSuratPoPdf($id_proyek, $id_vendor)
    {
        $user = Auth::user();
        if ($user->role !== 'admin_purchasing' && $user->role !== 'superadmin') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Akses ditolak.');
        }

        $proyek = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminPurchasing'])
            ->findOrFail($id_proyek);
        $vendor = Vendor::findOrFail($id_vendor);

        if ($user->role !== 'superadmin' && $proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pembayaran', ['tab' => 'pembuatan-surat-po'])
                ->with('error', 'Akses ditolak.');
        }

        $suratPo = SuratPo::with(['items.barang', 'purchasing'])
            ->where('id_proyek', $proyek->id_proyek)
            ->where('id_vendor', $vendor->id_vendor)
            ->firstOrFail();

        // Jika DomPDF tersedia, preview sebagai PDF + merge lampiran
        try {
            $pdf     = Pdf::loadView('pages.files.surat-po', [
                'proyek'  => $proyek,
                'vendor'  => $vendor,
                'suratPo' => $suratPo,
            ]);
            $content = $this->mergeSuratPoWithLampiran($pdf->output(), $suratPo);

            return response($content, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="surat-po-preview.pdf"',
            ]);
        } catch (\Throwable $e) {
            // fallback: HTML preview
            return view('pages.files.surat-po', [
                'proyek'  => $proyek,
                'vendor'  => $vendor,
                'suratPo' => $suratPo,
            ]);
        }
    }
}