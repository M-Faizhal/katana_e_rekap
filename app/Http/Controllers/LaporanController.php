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
     * Format number to Indonesian format (Rupiah)
     * Juta = 1,000,000 (6 zeros) -> jt
     * Miliar = 1,000,000,000 (9 zeros) -> M
     * Triliun = 1,000,000,000,000 (12 zeros) -> T
     */
    private function formatRupiah($amount)
    {
        if ($amount >= 1000000000000) {
            // Triliun
            return number_format($amount / 1000000000000, 1, ',', '.') . ' T';
        } elseif ($amount >= 1000000000) {
            // Miliar
            return number_format($amount / 1000000000, 1, ',', '.') . ' M';
        } elseif ($amount >= 1000000) {
            // Juta
            return number_format($amount / 1000000, 1, ',', '.') . ' jt';
        } elseif ($amount >= 1000) {
            // Ribu
            return number_format($amount / 1000, 1, ',', '.') . ' rb';
        } else {
            return number_format($amount, 0, ',', '.');
        }
    }
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

        // Add formatted versions for display
        $stats['omset_bulan_ini_formatted'] = $this->formatRupiah($stats['omset_bulan_ini']);
        $stats['omset_tahun_ini_formatted'] = $this->formatRupiah($stats['omset_tahun_ini']);
        $stats['rata_rata_bulanan_formatted'] = $this->formatRupiah($stats['rata_rata_bulanan']);

        // Get monthly omset data
        $monthlyOmset = $this->getMonthlyOmset($request);

        // Get vendor omset data
        $vendorOmset = $this->getVendorOmset($request);

        // Handle AJAX requests for year filter
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'monthlyOmset' => $monthlyOmset,
                'stats' => $stats
            ]);
        }

        return view('pages.laporan.omset', compact('stats', 'monthlyOmset', 'vendorOmset'));
    }

    /**
     * Display Laporan Hutang Vendor
     */
    public function hutangVendor(Request $request)
    {
        // Get vendor debt statistics
        $stats = $this->getHutangVendorStatistics();

        // Add formatted versions for display
        $stats['total_hutang_formatted'] = $this->formatRupiah($stats['total_hutang']);
        $stats['hutang_jatuh_tempo_formatted'] = $this->formatRupiah($stats['hutang_jatuh_tempo']);
        $stats['rata_rata_hutang_formatted'] = $this->formatRupiah($stats['rata_rata_hutang']);

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

        // Add formatted versions for display
        $stats['total_piutang_formatted'] = $this->formatRupiah($stats['total_piutang']);
        $stats['piutang_jatuh_tempo_formatted'] = $this->formatRupiah($stats['piutang_jatuh_tempo']);
        $stats['rata_rata_piutang_formatted'] = $this->formatRupiah($stats['rata_rata_piutang']);

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
        
        // Calculate omset from ACC proposals only
        $currentMonthOmset = Penawaran::where('status', 'ACC')
            ->whereHas('proyek', function($query) use ($currentMonth) {
                $query->where('status', 'selesai')
                      ->whereMonth('updated_at', $currentMonth->month)
                      ->whereYear('updated_at', $currentMonth->year);
            })
            ->sum('total_penawaran');

        $lastMonthOmset = Penawaran::where('status', 'ACC')
            ->whereHas('proyek', function($query) use ($lastMonth) {
                $query->where('status', 'selesai')
                      ->whereMonth('updated_at', $lastMonth->month)
                      ->whereYear('updated_at', $lastMonth->year);
            })
            ->sum('total_penawaran');

        $yearlyOmset = Penawaran::where('status', 'ACC')
            ->whereHas('proyek', function($query) use ($currentMonth) {
                $query->where('status', 'selesai')
                      ->whereYear('updated_at', $currentMonth->year);
            })
            ->sum('total_penawaran');

        $avgMonthlyOmset = $yearlyOmset / 12;

        return [
            'omset_bulan_ini' => $currentMonthOmset ?? 0,
            'omset_bulan_lalu' => $lastMonthOmset ?? 0,
            'omset_tahun_ini' => $yearlyOmset ?? 0,
            'rata_rata_bulanan' => $avgMonthlyOmset ?? 0,
            'pertumbuhan' => $lastMonthOmset > 0 ? (($currentMonthOmset - $lastMonthOmset) / $lastMonthOmset) * 100 : 0
        ];
    }

    /**
     * Get monthly omset data
     */
    private function getMonthlyOmset(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        
        return DB::table('penawaran')
            ->join('proyek', 'penawaran.id_proyek', '=', 'proyek.id_proyek')
            ->where('penawaran.status', 'ACC')
            ->where('proyek.status', 'selesai')
            ->whereYear('proyek.updated_at', $year)
            ->select(
                DB::raw('MONTH(proyek.updated_at) as month'),
                DB::raw('SUM(penawaran.total_penawaran) as total_omset'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get vendor omset data
     */
    private function getVendorOmset(Request $request)
    {
        return DB::table('vendor')
            ->join('barang', 'vendor.id_vendor', '=', 'barang.id_vendor')
            ->join('penawaran_detail', 'barang.id_barang', '=', 'penawaran_detail.id_barang')
            ->join('penawaran', 'penawaran_detail.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('proyek', 'penawaran.id_proyek', '=', 'proyek.id_proyek')
            ->where('proyek.status', 'Selesai')
            ->where('penawaran.status', 'ACC') // Only count ACC proposals
            ->select(
                'vendor.nama_vendor',
                DB::raw('SUM(penawaran_detail.subtotal) as total_omset'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek')
            )
            ->groupBy('vendor.id_vendor', 'vendor.nama_vendor')
            ->orderBy('total_omset', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get hutang vendor statistics
     */
    private function getHutangVendorStatistics()
    {
        // Note: Current pembayaran table structure doesn't have status_pembayaran and jatuh_tempo fields
        // This is placeholder implementation. These features need proper migration to add required fields.
        
        $totalHutang = 0; // Placeholder - need to implement proper hutang tracking
        $hutangJatuhTempo = 0; // Placeholder - need jatuh_tempo field in pembayaran table
        $jumlahVendorBerhutang = 0; // Placeholder - need status_pembayaran field

        // For now, let's count pending payments as temporary solution
        $pendingPayments = DB::table('pembayaran')
            ->where('status_verifikasi', 'Pending')
            ->sum('nominal_bayar');

        $jumlahVendorPending = DB::table('pembayaran')
            ->where('status_verifikasi', 'Pending')
            ->distinct('id_vendor')
            ->count();

        return [
            'total_hutang' => $pendingPayments ?? 0,
            'hutang_jatuh_tempo' => 0, // Need jatuh_tempo field
            'jumlah_vendor' => $jumlahVendorPending ?? 0,
            'rata_rata_hutang' => $jumlahVendorPending > 0 ? $pendingPayments / $jumlahVendorPending : 0
        ];
    }

    /**
     * Get hutang vendor list
     */
    private function getHutangVendorList(Request $request)
    {
        // Note: Current structure doesn't have proper hutang tracking fields
        // This returns pending payments as temporary solution
        
        return DB::table('vendor')
            ->join('pembayaran', 'vendor.id_vendor', '=', 'pembayaran.id_vendor')
            ->join('penawaran', 'pembayaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('proyek', 'penawaran.id_proyek', '=', 'proyek.id_proyek')
            ->where('pembayaran.status_verifikasi', 'Pending')
            ->select(
                'vendor.nama_vendor',
                'vendor.kontak as kontak_vendor',
                'proyek.kode_proyek',
                'proyek.nama_klien',
                'pembayaran.nominal_bayar as nominal_pembayaran',
                'pembayaran.tanggal_bayar as jatuh_tempo',
                'pembayaran.status_verifikasi as status_pembayaran',
                DB::raw('DATEDIFF(NOW(), pembayaran.tanggal_bayar) as hari_telat')
            )
            ->orderBy('pembayaran.tanggal_bayar', 'asc')
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
