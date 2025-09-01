<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\User;    
use App\Models\Proyek;
use App\Models\Vendor;
use App\Models\Penawaran;
use App\Models\PenawaranDetail;
use App\Models\Pembayaran;
use App\Models\PenagihanDinas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display the main laporan page (Laporan Proyek)
     */
    public function index(Request $request)
    {
        // Get basic statistics
        $stats = $this->getStatistics();

        // Get projects with filters applied
        $projects = $this->getFilteredProjects($request);

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        // Get chart data
        $chartData = $this->getChartData($request);

        return view('pages.laporan.proyek', compact('stats', 'projects', 'filterOptions', 'chartData'));
    }

    /**
     * Display Laporan Omset
     */
    public function omset(Request $request)
    {
        // Get omset statistics
        $stats = $this->getOmsetStatistics();

        // Get monthly omset data
        $monthlyOmset = $this->getMonthlyOmset($request);

        // Get vendor omset data
        $vendorOmset = $this->getVendorOmset($request);

        return view('pages.laporan.omset', compact('stats', 'monthlyOmset', 'vendorOmset'));
    }

    /**
     * Display Laporan Hutang Vendor
     */
    public function hutangVendor(Request $request)
    {
        // Get vendor debt statistics
        $stats = $this->getHutangVendorStatistics();

        // Get vendor debt list
        $hutangVendor = $this->getHutangVendorList($request);

        return view('pages.laporan.hutang-vendor', compact('stats', 'hutangVendor'));
    }

    /**
     * Display Laporan Piutang Dinas
     */
    public function piutangDinas(Request $request)
    {
        // Get piutang dinas statistics
        $stats = $this->getPiutangDinasStatistics();

        // Get piutang dinas list
        $piutangDinas = $this->getPiutangDinasList($request);

        return view('pages.laporan.piutang-dinas', compact('stats', 'piutangDinas'));
    }

    /**
     * Get basic statistics for the dashboard
     */
    private function getStatistics()
    {
        // Hitung total nilai HANYA dari penawaran yang status ACC dan proyek tidak gagal
        $totalNilai = Penawaran::where('status', 'ACC')
            ->whereHas('proyek', function($query) {
                $query->where('status', '!=', 'Gagal');
            })
            ->sum('total_penawaran');

        $stats = [
            'total_proyek' => Proyek::where('status', '!=', 'Gagal')->count(),
            'proyek_selesai' => Proyek::where('status', 'selesai')->count(),
            'proyek_berjalan' => Proyek::whereNotIn('status', ['selesai', 'gagal'])->count(),
            'total_nilai_proyek' => $totalNilai ?? 0,
            'vendor_aktif' => Vendor::whereHas('barang')->count(),
            'jenis_produk' => Barang::distinct('nama_barang')->count(),
        ];

        return $stats;
    }

    /**
     * Get projects with applied filters
     */
    private function getFilteredProjects(Request $request)
    {
        $query = Proyek::with([
            'adminMarketing',
            'adminPurchasing',
            'penawaran',
            'semuaPenawaran'
        ]); // Show all projects

        // Apply filters
        if ($request->filled('periode')) {
            $this->applyPeriodeFilter($query, $request->periode, $request);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('nilai')) {
            $this->applyNilaiFilter($query, $request->nilai);
        }

        if ($request->filled('produk')) {
            $query->whereHas('penawaran.penawaranDetail.barang', function($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->produk . '%');
            });
        }

        if ($request->filled('departemen')) {
            $query->whereHas('adminMarketing', function($q) use ($request) {
                $q->where('role', 'like', '%' . $request->departemen . '%');
            });
        }

        $projects = $query->orderBy('tanggal', 'desc')->paginate(10);

        // Hitung total_nilai untuk setiap proyek HANYA dari penawaran ACC
        foreach ($projects as $project) {
            $penawaranAcc = Penawaran::where('id_proyek', $project->id_proyek)
                ->where('status', 'ACC')
                ->first();
            
            // Hanya ambil nilai dari penawaran ACC, jika tidak ada maka 0
            $project->total_nilai = $penawaranAcc ? ($penawaranAcc->total_penawaran ?? 0) : 0;
        }

        return $projects;
    }

    /**
     * Apply periode filter to query
     */
    private function applyPeriodeFilter($query, $periode, $request)
    {
        $now = Carbon::now();

        switch ($periode) {
            case 'bulan-ini':
                $query->whereMonth('tanggal', $now->month)
                      ->whereYear('tanggal', $now->year);
                break;
            case '3-bulan':
                $query->where('tanggal', '>=', $now->copy()->subMonths(3));
                break;
            case '6-bulan':
                $query->where('tanggal', '>=', $now->copy()->subMonths(6));
                break;
            case 'tahun-ini':
                $query->whereYear('tanggal', $now->year);
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
                }
                break;
        }
    }

    /**
     * Apply nilai (value range) filter
     */
    private function applyNilaiFilter($query, $nilai)
    {
        // Filter berdasarkan total nilai dari penawaran ACC saja
        switch ($nilai) {
            case '0-10jt':
                $query->whereHas('penawaran', function($subQ) {
                    $subQ->where('status', 'ACC')->where('total_penawaran', '<=', 10000000);
                });
                break;
            case '10-50jt':
                $query->whereHas('penawaran', function($subQ) {
                    $subQ->where('status', 'ACC')->whereBetween('total_penawaran', [10000001, 50000000]);
                });
                break;
            case '50-100jt':
                $query->whereHas('penawaran', function($subQ) {
                    $subQ->where('status', 'ACC')->whereBetween('total_penawaran', [50000001, 100000000]);
                });
                break;
            case '100-500jt':
                $query->whereHas('penawaran', function($subQ) {
                    $subQ->where('status', 'ACC')->whereBetween('total_penawaran', [100000001, 500000000]);
                });
                break;
            case '500jt-1m':
                $query->whereHas('penawaran', function($subQ) {
                    $subQ->where('status', 'ACC')->whereBetween('total_penawaran', [500000001, 1000000000]);
                });
                break;
            case '1m+':
                $query->whereHas('penawaran', function($subQ) {
                    $subQ->where('status', 'ACC')->where('total_penawaran', '>', 1000000000);
                });
                break;
        }
    }

    /**
     * Get filter options for dropdowns
     */
    private function getFilterOptions()
    {
        return [
            'statuses' => [
                ['value' => 'menunggu', 'label' => 'Menunggu'],
                ['value' => 'verifikasi', 'label' => 'Verifikasi'],
                ['value' => 'purchasing', 'label' => 'Purchasing'],
                ['value' => 'penawaran', 'label' => 'Penawaran'],
                ['value' => 'pembayaran', 'label' => 'Pembayaran'],
                ['value' => 'pengiriman', 'label' => 'Pengiriman'],
                ['value' => 'selesai', 'label' => 'Selesai'],
            ],
            'products' => Barang::select('nama_barang')
                ->whereHas('penawaranDetail')
                ->distinct()
                ->orderBy('nama_barang')
                ->get(),
        ];
    }

    /**
     * Export report to Excel
     */
    public function export(Request $request)
    {
        // Get filtered projects for export
        $projects = $this->getFilteredProjects($request);

        // Create CSV response
        $filename = 'laporan-proyek-' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($projects) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Kode Proyek',
                'Nama Proyek',
                'Instansi',
                'Tanggal',
                'Status',
                'Admin Marketing',
                'Admin Purchasing',
                'Total Nilai',
                'Vendor',
                'Produk',
                'Kategori'
            ]);

            // Add data rows
            foreach ($projects as $project) {
                $vendor = $project->penawaran && $project->penawaran->penawaranDetail->first()
                    ? $project->penawaran->penawaranDetail->first()->barang->vendor->nama_vendor
                    : '-';

                $produk = $project->penawaran && $project->penawaran->penawaranDetail->first()
                    ? $project->penawaran->penawaranDetail->first()->barang->nama_barang
                    : '-';

                $kategori = $project->penawaran && $project->penawaran->penawaranDetail->first()
                    ? $project->penawaran->penawaranDetail->first()->barang->kategori
                    : '-';

                fputcsv($file, [
                    $project->kode_proyek,
                    $project->nama_klien . ' - ' . $project->jenis_pengadaan,
                    $project->instansi,
                    $project->tanggal->format('d/m/Y'),
                    ucfirst($project->status),
                    $project->adminMarketing->nama ?? '-',
                    $project->adminPurchasing->nama ?? '-',
                    number_format($project->total_nilai ?? 0, 0, ',', '.'),
                    $vendor,
                    $produk,
                    $kategori
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get project detail for modal
     */
    public function getProjectDetail($id)
    {
        $project = Proyek::with([
            'adminMarketing',
            'adminPurchasing',
            'penawaran.penawaranDetail.barang.vendor',
            'semuaPenawaran'
        ])->findOrFail($id);

        // Hitung total nilai HANYA dari penawaran ACC
        $penawaranAcc = Penawaran::where('id_proyek', $project->id_proyek)
            ->where('status', 'ACC')
            ->first();
        
        $totalNilai = $penawaranAcc ? ($penawaranAcc->total_penawaran ?? 0) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'kode_proyek' => $project->kode_proyek,
                'nama_klien' => $project->nama_klien,
                'instansi' => $project->instansi,
                'jenis_pengadaan' => $project->jenis_pengadaan,
                'tanggal' => Carbon::parse($project->tanggal)->format('d M Y'),
                'deadline' => $project->deadline ? Carbon::parse($project->deadline)->format('d M Y') : '-',
                'status' => ucfirst($project->status),
                'admin_marketing' => $project->adminMarketing->nama ?? '-',
                'admin_purchasing' => $project->adminPurchasing->nama ?? '-',
                'total_nilai' => $totalNilai,
                'catatan' => $project->catatan ?? '-',
                'penawaran' => $project->penawaran ? [
                    'no_penawaran' => $project->penawaran->no_penawaran,
                    'tanggal_penawaran' => Carbon::parse($project->penawaran->tanggal_penawaran)->format('d M Y'),
                    'total_nilai' => number_format(floatval($project->penawaran->total_nilai ?? 0), 0, ',', '.'),
                    'detail_barang' => $project->penawaran->penawaranDetail->map(function($detail) {
                        return [
                            'nama_barang' => $detail->barang->nama_barang,
                            'vendor' => $detail->barang->vendor->nama_vendor,
                            'kategori' => $detail->barang->kategori,
                            'jumlah' => $detail->jumlah,
                            'satuan' => $detail->barang->satuan,
                            'harga_satuan' => number_format(floatval($detail->harga_satuan ?? 0), 0, ',', '.'),
                            'subtotal' => number_format(floatval($detail->subtotal ?? 0), 0, ',', '.')
                        ];
                    })
                ] : null
            ]
        ]);
    }

    /**
     * API endpoint to get filtered data (for AJAX)
     */
    public function getFilteredData(Request $request)
    {
        $projects = $this->getFilteredProjects($request);
        $stats = $this->getStatistics();

        return response()->json([
            'success' => true,
            'data' => [
                'projects' => $projects,
                'stats' => $stats
            ]
        ]);
    }

    /**
     * Get omset statistics
     */
    private function getOmsetStatistics()
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        
        $currentMonthOmset = Proyek::where('status', 'selesai')
            ->whereMonth('updated_at', $currentMonth->month)
            ->whereYear('updated_at', $currentMonth->year)
            ->sum('harga_total');

        $lastMonthOmset = Proyek::where('status', 'selesai')
            ->whereMonth('updated_at', $lastMonth->month)
            ->whereYear('updated_at', $lastMonth->year)
            ->sum('harga_total');

        $yearlyOmset = Proyek::where('status', 'selesai')
            ->whereYear('updated_at', $currentMonth->year)
            ->sum('harga_total');

        $avgMonthlyOmset = $yearlyOmset / 12;

        return [
            'omset_bulan_ini' => $currentMonthOmset,
            'omset_bulan_lalu' => $lastMonthOmset,
            'omset_tahun_ini' => $yearlyOmset,
            'rata_rata_bulanan' => $avgMonthlyOmset,
            'pertumbuhan' => $lastMonthOmset > 0 ? (($currentMonthOmset - $lastMonthOmset) / $lastMonthOmset) * 100 : 0
        ];
    }

    /**
     * Get monthly omset data
     */
    private function getMonthlyOmset(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        
        return Proyek::select(
                DB::raw('MONTH(updated_at) as month'),
                DB::raw('SUM(harga_total) as total_omset'),
                DB::raw('COUNT(*) as jumlah_proyek')
            )
            ->where('status', 'selesai')
            ->whereYear('updated_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get vendor omset data
     */
    private function getVendorOmset(Request $request)
    {
        return Vendor::select('vendors.*')
            ->join('barangs', 'vendors.id_vendor', '=', 'barangs.id_vendor')
            ->join('penawaran_details', 'barangs.id_barang', '=', 'penawaran_details.id_barang')
            ->join('penawarans', 'penawaran_details.id_penawaran', '=', 'penawarans.id_penawaran')
            ->join('proyeks', 'penawarans.id_proyek', '=', 'proyeks.id_proyek')
            ->where('proyeks.status', 'selesai')
            ->select(
                'vendors.nama_vendor',
                DB::raw('SUM(penawaran_details.subtotal) as total_omset'),
                DB::raw('COUNT(DISTINCT proyeks.id_proyek) as jumlah_proyek')
            )
            ->groupBy('vendors.id_vendor', 'vendors.nama_vendor')
            ->orderBy('total_omset', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get hutang vendor statistics
     */
    private function getHutangVendorStatistics()
    {
        // Implementasi logika hutang vendor berdasarkan pembayaran yang belum lunas
        $totalHutang = DB::table('pembayarans')
            ->join('proyeks', 'pembayarans.id_proyek', '=', 'proyeks.id_proyek')
            ->where('pembayarans.status_pembayaran', '!=', 'lunas')
            ->sum('pembayarans.nominal_pembayaran');

        $hutangJatuhTempo = DB::table('pembayarans')
            ->join('proyeks', 'pembayarans.id_proyek', '=', 'proyeks.id_proyek')
            ->where('pembayarans.status_pembayaran', '!=', 'lunas')
            ->where('pembayarans.jatuh_tempo', '<', Carbon::now())
            ->sum('pembayarans.nominal_pembayaran');

        $jumlahVendorBerhutang = DB::table('pembayarans')
            ->join('proyeks', 'pembayarans.id_proyek', '=', 'proyeks.id_proyek')
            ->join('penawarans', 'proyeks.id_proyek', '=', 'penawarans.id_proyek')
            ->join('penawaran_details', 'penawarans.id_penawaran', '=', 'penawaran_details.id_penawaran')
            ->join('barangs', 'penawaran_details.id_barang', '=', 'barangs.id_barang')
            ->where('pembayarans.status_pembayaran', '!=', 'lunas')
            ->distinct('barangs.id_vendor')
            ->count();

        return [
            'total_hutang' => $totalHutang,
            'hutang_jatuh_tempo' => $hutangJatuhTempo,
            'jumlah_vendor' => $jumlahVendorBerhutang,
            'rata_rata_hutang' => $jumlahVendorBerhutang > 0 ? $totalHutang / $jumlahVendorBerhutang : 0
        ];
    }

    /**
     * Get hutang vendor list
     */
    private function getHutangVendorList(Request $request)
    {
        return DB::table('vendors')
            ->join('barangs', 'vendors.id_vendor', '=', 'barangs.id_vendor')
            ->join('penawaran_details', 'barangs.id_barang', '=', 'penawaran_details.id_barang')
            ->join('penawarans', 'penawaran_details.id_penawaran', '=', 'penawarans.id_penawaran')
            ->join('proyeks', 'penawarans.id_proyek', '=', 'proyeks.id_proyek')
            ->join('pembayarans', 'proyeks.id_proyek', '=', 'pembayarans.id_proyek')
            ->where('pembayarans.status_pembayaran', '!=', 'lunas')
            ->select(
                'vendors.nama_vendor',
                'vendors.kontak_vendor',
                'proyeks.kode_proyek',
                'proyeks.nama_klien',
                'pembayarans.nominal_pembayaran',
                'pembayarans.jatuh_tempo',
                'pembayarans.status_pembayaran',
                DB::raw('DATEDIFF(NOW(), pembayarans.jatuh_tempo) as hari_telat')
            )
            ->orderBy('pembayarans.jatuh_tempo', 'asc')
            ->paginate(15);
    }

    /**
     * Get piutang dinas statistics
     */
    private function getPiutangDinasStatistics()
    {
        $totalPiutang = PenagihanDinas::where('status_pembayaran', '!=', 'lunas')
            ->sum('sisa_pembayaran');

        $piutangJatuhTempo = PenagihanDinas::where('status_pembayaran', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<', Carbon::now())
            ->sum('sisa_pembayaran');

        $jumlahProyekBelumLunas = PenagihanDinas::where('status_pembayaran', '!=', 'lunas')
            ->distinct('proyek_id')
            ->count();

        return [
            'total_piutang' => $totalPiutang ?? 0,
            'piutang_jatuh_tempo' => $piutangJatuhTempo ?? 0,
            'jumlah_proyek' => $jumlahProyekBelumLunas ?? 0,
            'rata_rata_piutang' => $jumlahProyekBelumLunas > 0 ? ($totalPiutang / $jumlahProyekBelumLunas) : 0
        ];
    }

    /**
     * Get piutang dinas list
     */
    private function getPiutangDinasList(Request $request)
    {
        return PenagihanDinas::with(['proyek'])
            ->where('status_pembayaran', '!=', 'lunas')
            ->select(
                'penagihan_dinas.*',
                DB::raw('DATEDIFF(NOW(), tanggal_jatuh_tempo) as hari_telat')
            )
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->paginate(15);
    }

    /**
     * Get chart data for visualization
     */
    private function getChartData($request)
    {
        // Status distribution chart - hitung berdasarkan proyek yang ada sekarang (tidak termasuk gagal)
        $statusData = Proyek::select('status', DB::raw('count(*) as total'))
            ->where('status', '!=', 'gagal')
            ->groupBy('status')
            ->orderBy('total', 'desc')
            ->get();

        // Monthly project count chart (last 6 months) - berdasarkan data yang ada (tidak termasuk gagal)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->copy()->subMonths($i);
            $count = Proyek::whereMonth('tanggal', $month->month)
                ->whereYear('tanggal', $month->year)
                ->where('status', '!=', 'gagal')
                ->count();
            
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        // Monthly value chart (last 6 months) - HANYA dari penawaran ACC
        $monthlyValueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->copy()->subMonths($i);
            
            // Ambil total nilai dari penawaran ACC dalam bulan tersebut
            $totalValue = Penawaran::where('status', 'ACC')
                ->whereHas('proyek', function($query) use ($month) {
                    $query->whereMonth('tanggal', $month->month)
                          ->whereYear('tanggal', $month->year)
                          ->where('status', '!=', 'gagal');
                })
                ->sum('total_penawaran');
            
            $monthlyValueData[] = [
                'month' => $month->format('M Y'),
                'value' => $totalValue ?? 0
            ];
        }

        // Top 5 instansi by project count (tidak termasuk gagal)
        $instansiData = Proyek::select('instansi', DB::raw('count(*) as total'))
            ->whereNotNull('instansi')
            ->where('instansi', '!=', '')
            ->where('status', '!=', 'gagal')
            ->groupBy('instansi')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return [
            'status_distribution' => $statusData,
            'monthly_projects' => $monthlyData,
            'monthly_values' => $monthlyValueData,
            'top_instansi' => $instansiData
        ];
    }
}
