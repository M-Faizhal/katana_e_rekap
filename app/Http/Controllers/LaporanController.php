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
use App\Models\KalkulasiHps;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // Check if this is an AJAX request for filtered data
        if ($request->ajax()) {
            return $this->getOmsetFilteredData($request);
        }

        // Get omset statistics
        $stats = $this->getOmsetStatistics();

        // Add formatted versions for display
        $stats['omset_bulan_ini_formatted'] = $this->formatRupiah($stats['omset_bulan_ini']);
        $stats['omset_tahun_ini_formatted'] = $this->formatRupiah($stats['omset_tahun_ini']);
        $stats['rata_rata_bulanan_formatted'] = $this->formatRupiah($stats['rata_rata_bulanan']);

        // Get monthly omset data (tahun berjalan)
        $monthlyOmset = $this->getMonthlyOmset($request);

        // Get admin omset data instead of vendor
        $adminMarketing = $this->getAdminMarketingOmset($request);
        $adminPurchasing = $this->getAdminPurchasingOmset($request);

        // Debug log with more detail
        Log::info('Omset page data:', [
            'monthlyOmsetCount' => count($monthlyOmset),
            'adminMarketingCount' => count($adminMarketing),
            'adminPurchasingCount' => count($adminPurchasing),
            'year' => $request->get('year', Carbon::now()->year),
            'month' => $request->get('month')
        ]);

        return view('pages.laporan.omset', compact('stats', 'monthlyOmset', 'adminMarketing', 'adminPurchasing'));
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
            'proyek_selesai' => Proyek::where('status', 'Selesai')->count(),
            'proyek_berjalan' => Proyek::whereNotIn('status', ['Selesai', 'Gagal'])->count(),
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
    // Get projects with HPS kalkulasi only (ACC status and has kalkulasi_hps)
    $projects = $this->getProjectsWithHPS($request);

    // Create CSV response
    $filename = 'laporan-proyek-' . Carbon::now()->format('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0'
    ];

    $callback = function() use ($projects) {
        $file = fopen('php://output', 'w');
        
        // Add BOM for proper UTF-8 encoding in Excel
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add CSV headers sesuai format yang diminta
        fputcsv($file, [
            'No',
            'Kab/Kota',
            'Nama Instansi',
            'Nama Barang',
            'Qty',
            'Satuan',
            'Spesifikasi',
            'Pagu Satuan',
            'Pagu Total',
            'Jenis Pengadaan',
            'Note',
            'Marketing',
            'Purchasing',
            'Status'
        ], ';'); // Gunakan semicolon sebagai delimiter untuk Excel Indonesia

        // Add data rows - setiap barang dalam penawaran detail jadi 1 baris
        $no = 1;
        foreach ($projects as $project) {
            if ($project->penawaran && $project->penawaran->penawaranDetail) {
                foreach ($project->penawaran->penawaranDetail as $detail) {
                    // Clean data - hapus koma dan karakter khusus yang bisa merusak CSV
                    $instansi = str_replace([',', ';', '"', "\n", "\r"], [' ', ' ', '\'', ' ', ' '], 
                        $project->instansi);
                    
                    $namaBarang = str_replace([',', ';', '"', "\n", "\r"], [' ', ' ', '\'', ' ', ' '], 
                        $detail->barang->nama_barang ?? '-');
                    
                    $spesifikasi = str_replace([',', ';', '"', "\n", "\r"], [' ', ' ', '\'', ' ', ' '], 
                        $detail->barang->spesifikasi ?? $detail->barang->deskripsi ?? '-');
                    
                    $jenisPN = str_replace([',', ';', '"', "\n", "\r"], [' ', ' ', '\'', ' ', ' '], 
                        $project->jenis_pengadaan);
                    
                    $note = str_replace([',', ';', '"', "\n", "\r"], [' ', ' ', '\'', ' ', ' '], 
                        $project->catatan ?? '-');

                    fputcsv($file, [
                        $no,
                        $project->kab_kota ?? '-', // Kab/Kota dari field kab_kota
                        $instansi,
                        $namaBarang,
                        $detail->qty ?? 0,
                        $detail->barang->satuan ?? '-',
                        $spesifikasi,
                        number_format($detail->harga_satuan ?? 0, 0, ',', '.'),
                        number_format($detail->subtotal ?? 0, 0, ',', '.'),
                        $jenisPN,
                        $note,
                        $project->adminMarketing->nama ?? '-',
                        $project->adminPurchasing->nama ?? '-',
                        ucfirst($project->status)
                    ], ';');
                    
                    $no++;
                }
            }
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    /**
     * Get projects that have HPS kalkulasi (only ACC status with kalkulasi_hps)
     */
    private function getProjectsWithHPS(Request $request)
    {
        $query = Proyek::with([
            'adminMarketing',
            'adminPurchasing',
            'penawaran.penawaranDetail.barang.vendor',
            'kalkulasiHps'
        ])
        ->whereHas('penawaran', function($q) {
            $q->where('status', 'ACC');
        })
        ->whereHas('kalkulasiHps'); // Hanya proyek yang sudah ada HPS kalkulasi

        // Apply filters (sama seperti getFilteredProjects)
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

        return $query->orderBy('tanggal', 'desc')->get();
    }

/**
 * Alternative: Export sebagai Excel langsung menggunakan Laravel Excel
 * Tambahkan package: composer require maatwebsite/excel
 */
/*
public function exportExcel(Request $request)
{
    $projects = $this->getFilteredProjects($request);
    $filename = 'laporan-proyek-' . Carbon::now()->format('Y-m-d') . '.xlsx';
    
    return Excel::download(new ProjectsExport($projects), $filename);
}
*/

/**
 * Export dengan format TSV (Tab-separated values) - lebih aman untuk Excel
 */
public function exportTSV(Request $request)
{
    $projects = $this->getProjectsWithHPS($request);
    $filename = 'laporan-proyek-' . Carbon::now()->format('Y-m-d') . '.tsv';

    $headers = [
        'Content-Type' => 'text/tab-separated-values; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function() use ($projects) {
        $file = fopen('php://output', 'w');
        
        // Add BOM
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Headers dengan tab separator sesuai format yang diminta
        fwrite($file, implode("\t", [
            'No',
            'Kab/Kota',
            'Nama Instansi',
            'Nama Barang',
            'Qty',
            'Satuan',
            'Spesifikasi',
            'Pagu Satuan',
            'Pagu Total',
            'Jenis Pengadaan',
            'Note',
            'Marketing',
            'Purchasing',
            'Status'
        ]) . "\n");

        // Data rows - setiap barang dalam penawaran detail jadi 1 baris
        $no = 1;
        foreach ($projects as $project) {
            if ($project->penawaran && $project->penawaran->penawaranDetail) {
                foreach ($project->penawaran->penawaranDetail as $detail) {
                    $instansi = str_replace(["\t", "\n", "\r"], [' ', ' ', ' '], $project->instansi);
                    $namaBarang = str_replace(["\t", "\n", "\r"], [' ', ' ', ' '], $detail->barang->nama_barang ?? '-');
                    $spesifikasi = str_replace(["\t", "\n", "\r"], [' ', ' ', ' '], $detail->barang->spesifikasi ?? '-');
                    $jenisPN = str_replace(["\t", "\n", "\r"], [' ', ' ', ' '], $project->jenis_pengadaan);
                    $note = str_replace(["\t", "\n", "\r"], [' ', ' ', ' '], $project->catatan ?? '-');

                    $row = [
                        $no,
                        $project->kab_kota ?? '-',
                        $instansi,
                        $namaBarang,
                        $detail->qty ?? 0,
                        $detail->barang->satuan ?? '-',
                        $spesifikasi,
                        number_format($detail->harga_satuan ?? 0, 0, ',', '.'),
                        number_format($detail->subtotal ?? 0, 0, ',', '.'),
                        $jenisPN,
                        $note,
                        $project->adminMarketing->nama ?? '-',
                        $project->adminPurchasing->nama ?? '-',
                        ucfirst($project->status)
                    ];
                    
                    fwrite($file, implode("\t", $row) . "\n");
                    $no++;
                }
            }
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
                            'qty' => $detail->qty,
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
        
        // Calculate omset from nett_income pada kalkulasi HPS
        // Ubah untuk tidak hanya bergantung pada status 'Selesai'
        $currentMonthOmset = KalkulasiHps::whereHas('proyek', function($query) use ($currentMonth) {
                $query->whereNotIn('status', ['Gagal', 'Menunggu']) // Exclude gagal and menunggu
                      ->whereMonth('created_at', $currentMonth->month)
                      ->whereYear('created_at', $currentMonth->year);
            })
            ->sum('nett_income');

        $lastMonthOmset = KalkulasiHps::whereHas('proyek', function($query) use ($lastMonth) {
                $query->whereNotIn('status', ['Gagal', 'Menunggu'])
                      ->whereMonth('created_at', $lastMonth->month)
                      ->whereYear('created_at', $lastMonth->year);
            })
            ->sum('nett_income');

        $yearlyOmset = KalkulasiHps::whereHas('proyek', function($query) use ($currentMonth) {
                $query->whereNotIn('status', ['Gagal', 'Menunggu'])
                      ->whereYear('created_at', $currentMonth->year);
            })
            ->sum('nett_income');

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
     * Get monthly omset data (with filters)
     */
    private function getMonthlyOmset(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month');
        
        $query = DB::table('kalkulasi_hps')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->whereNotIn('proyek.status', ['Gagal', 'Menunggu']) // Include more statuses
            ->whereYear('proyek.created_at', $year);
            
        if ($month) {
            $query->whereMonth('proyek.created_at', $month);
        }
        
        return $query->select(
                DB::raw('MONTH(proyek.created_at) as month'),
                DB::raw('SUM(COALESCE(kalkulasi_hps.nett_income, 0)) as total_omset'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get vendor omset data (Top 10 Vendor berdasarkan keuntungan)
     */
    private function getVendorOmset(Request $request)
    {
        // Hitung omset vendor berdasarkan nett_income dari kalkulasi HPS untuk proyek selesai
        // Vendor omset = kontribusi keuntungan vendor dari semua proyek yang selesai
        return DB::table('vendor')
            ->join('kalkulasi_hps', 'vendor.id_vendor', '=', 'kalkulasi_hps.id_vendor')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->where('proyek.status', 'Selesai')
            ->whereYear('proyek.updated_at', Carbon::now()->year) // Hanya tahun ini
            ->select(
                'vendor.nama_vendor',
                DB::raw('SUM(kalkulasi_hps.nett_income) as total_omset'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek'),
                DB::raw('AVG(kalkulasi_hps.nett_income) as rata_rata_omset_per_proyek')
            )
            ->groupBy('vendor.id_vendor', 'vendor.nama_vendor')
            ->having('total_omset', '>', 0) // Hanya vendor dengan omset positif
            ->orderBy('total_omset', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get admin marketing omset data (Top 10 Admin Marketing berdasarkan keuntungan)
     */
    private function getAdminMarketingOmset(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month');
        
        // Ubah query untuk tidak hanya bergantung pada status 'Selesai'
        $query = DB::table('users')
            ->join('proyek', 'users.id_user', '=', 'proyek.id_admin_marketing')
            ->leftJoin('kalkulasi_hps', 'proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
            ->whereYear('proyek.created_at', $year)
            ->where('proyek.status', '!=', 'Gagal'); // Exclude gagal projects
            
        if ($month) {
            $query->whereMonth('proyek.created_at', $month);
        }
        
        $result = $query->select(
                'users.nama as name',
                DB::raw('COALESCE(SUM(kalkulasi_hps.nett_income), 0) as total_omset'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek'),
                DB::raw('COALESCE(AVG(kalkulasi_hps.nett_income), 0) as rata_rata_omset_per_proyek')
            )
            ->groupBy('users.id_user', 'users.nama')
            ->orderBy('total_omset', 'desc')
            ->limit(10)
            ->get();

        // Jika tidak ada data, buat data dummy untuk testing
        if ($result->isEmpty()) {
            $allMarketingUsers = DB::table('users')
                ->join('proyek', 'users.id_user', '=', 'proyek.id_admin_marketing')
                ->select(
                    'users.nama as name',
                    DB::raw('0 as total_omset'),
                    DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek'),
                    DB::raw('0 as rata_rata_omset_per_proyek')
                )
                ->groupBy('users.id_user', 'users.nama')
                ->limit(10)
                ->get();
            
            return $allMarketingUsers;
        }
        
        return $result;
    }

    /**
     * Get admin purchasing omset data (Top 10 Admin Purchasing berdasarkan keuntungan)
     */
    private function getAdminPurchasingOmset(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month');
        
        // Ubah query untuk tidak hanya bergantung pada status 'Selesai'
        $query = DB::table('users')
            ->join('proyek', 'users.id_user', '=', 'proyek.id_admin_purchasing')
            ->leftJoin('kalkulasi_hps', 'proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
            ->whereYear('proyek.created_at', $year)
            ->where('proyek.status', '!=', 'Gagal'); // Exclude gagal projects
            
        if ($month) {
            $query->whereMonth('proyek.created_at', $month);
        }
        
        $result = $query->select(
                'users.nama as name',
                DB::raw('COALESCE(SUM(kalkulasi_hps.nett_income), 0) as total_omset'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek'),
                DB::raw('COALESCE(AVG(kalkulasi_hps.nett_income), 0) as rata_rata_omset_per_proyek')
            )
            ->groupBy('users.id_user', 'users.nama')
            ->orderBy('total_omset', 'desc')
            ->limit(10)
            ->get();

        // Jika tidak ada data, buat data dummy untuk testing
        if ($result->isEmpty()) {
            $allPurchasingUsers = DB::table('users')
                ->join('proyek', 'users.id_user', '=', 'proyek.id_admin_purchasing')
                ->select(
                    'users.nama as name',
                    DB::raw('0 as total_omset'),
                    DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek'),
                    DB::raw('0 as rata_rata_omset_per_proyek')
                )
                ->groupBy('users.id_user', 'users.nama')
                ->limit(10)
                ->get();
            
            return $allPurchasingUsers;
        }
        
        return $result;
    }

    /**
     * Get filtered omset data for AJAX requests
     */
    private function getOmsetFilteredData(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month');
        $period = $request->get('period', 'monthly');

        // Get filtered monthly omset data
        $monthlyOmset = $this->getFilteredMonthlyOmset($year, $month, $period);
        
        // Get filtered admin data
        $adminMarketing = $this->getAdminMarketingOmset($request);
        $adminPurchasing = $this->getAdminPurchasingOmset($request);
        
        // Get updated stats for the filtered period
        $stats = $this->getFilteredOmsetStatistics($year, $month);

        return response()->json([
            'success' => true,
            'monthlyOmset' => $monthlyOmset,
            'adminMarketing' => $adminMarketing,
            'adminPurchasing' => $adminPurchasing,
            'stats' => $stats
        ]);
    }

    /**
     * Get filtered monthly omset data based on year, month, and period
     */
    private function getFilteredMonthlyOmset($year, $month = null, $period = 'monthly')
    {
        $query = DB::table('kalkulasi_hps')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->where('proyek.status', 'Selesai');

        if ($period === 'yearly') {
            // For yearly view, group by year
            return $query->select(
                    DB::raw('YEAR(proyek.updated_at) as year'),
                    DB::raw('SUM(kalkulasi_hps.nett_income) as total_omset'),
                    DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek')
                )
                ->groupBy('year')
                ->orderBy('year')
                ->get();
        } else {
            // For monthly or quarterly, filter by year and optionally by month
            $query->whereYear('proyek.updated_at', $year);
            
            if ($month) {
                $query->whereMonth('proyek.updated_at', $month);
            }
            
            return $query->select(
                    DB::raw('MONTH(proyek.updated_at) as month'),
                    DB::raw('SUM(kalkulasi_hps.nett_income) as total_omset'),
                    DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }
    }

    /**
     * Get filtered omset statistics
     */
    private function getFilteredOmsetStatistics($year, $month = null)
    {
        $currentQuery = KalkulasiHps::whereHas('proyek', function($query) use ($year, $month) {
            $query->where('status', 'Selesai')
                  ->whereYear('updated_at', $year);
            if ($month) {
                $query->whereMonth('updated_at', $month);
            }
        });

        $currentOmset = $currentQuery->sum('nett_income');
        
        // For comparison, get previous period data
        $previousQuery = KalkulasiHps::whereHas('proyek', function($query) use ($year, $month) {
            $query->where('status', 'Selesai');
            if ($month) {
                // Previous month
                $prevMonth = $month - 1;
                $prevYear = $year;
                if ($prevMonth <= 0) {
                    $prevMonth = 12;
                    $prevYear = $year - 1;
                }
                $query->whereMonth('updated_at', $prevMonth)->whereYear('updated_at', $prevYear);
            } else {
                // Previous year
                $query->whereYear('updated_at', $year - 1);
            }
        });

        $previousOmset = $previousQuery->sum('nett_income');
        $pertumbuhan = $previousOmset > 0 ? (($currentOmset - $previousOmset) / $previousOmset) * 100 : 0;

        return [
            'omset_periode_ini' => $currentOmset ?? 0,
            'omset_periode_lalu' => $previousOmset ?? 0,
            'pertumbuhan' => $pertumbuhan,
            'omset_periode_ini_formatted' => $this->formatRupiah($currentOmset ?? 0),
            'omset_periode_lalu_formatted' => $this->formatRupiah($previousOmset ?? 0),
        ];
    }

    /**
     * Get chart data for laporan proyek
     */
    private function getChartData(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        
        // Get monthly project data for last 6 months
        $monthlyProjects = DB::table('proyek')
            ->select(
                DB::raw('MONTH(tanggal) as month_num'),
                DB::raw('MONTHNAME(tanggal) as month_name'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('tanggal', $year)
            ->where('tanggal', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy('month_num', 'month_name')
            ->orderBy('month_num')
            ->get();

        // Transform monthly projects to expected format
        $monthlyProjectsFormatted = $monthlyProjects->map(function($item) {
            return [
                'month' => substr($item->month_name, 0, 3), // Jan, Feb, etc
                'count' => $item->count
            ];
        });

        // Get status distribution
        $statusDistribution = DB::table('proyek')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereYear('tanggal', $year)
            ->where('status', '!=', 'Gagal') // Exclude failed projects
            ->groupBy('status')
            ->get();

        // Transform status distribution to expected format
        $statusDistributionFormatted = $statusDistribution->map(function($item) {
            return [
                'status' => $item->status,
                'total' => $item->count
            ];
        });

        // Get monthly values from ACC proposals for last 6 months
        $monthlyValues = DB::table('penawaran')
            ->join('proyek', 'penawaran.id_proyek', '=', 'proyek.id_proyek')
            ->select(
                DB::raw('MONTH(proyek.tanggal) as month_num'),
                DB::raw('MONTHNAME(proyek.tanggal) as month_name'),
                DB::raw('SUM(penawaran.total_penawaran) as total_value')
            )
            ->where('penawaran.status', 'ACC')
            ->whereYear('proyek.tanggal', $year)
            ->where('proyek.tanggal', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy('month_num', 'month_name')
            ->orderBy('month_num')
            ->get();

        // Transform monthly values to expected format
        $monthlyValuesFormatted = $monthlyValues->map(function($item) {
            return [
                'month' => substr($item->month_name, 0, 3), // Jan, Feb, etc
                'value' => $item->total_value ?? 0
            ];
        });

        return [
            'status_distribution' => $statusDistributionFormatted,
            'monthly_projects' => $monthlyProjectsFormatted,
            'monthly_values' => $monthlyValuesFormatted
        ];
    }

    /**
     * Debug method to test chart data
     */
    public function debugChartData(Request $request)
    {
        $stats = $this->getStatistics();
        $chartData = $this->getChartData($request);
        
        return response()->json([
            'stats' => $stats,
            'chartData' => $chartData,
            'debug' => [
                'total_projects' => Proyek::count(),
                'projects_this_year' => Proyek::whereYear('tanggal', Carbon::now()->year)->count(),
                'projects_with_penawaran' => Proyek::whereHas('penawaran')->count(),
                'acc_penawaran' => Penawaran::where('status', 'ACC')->count()
            ]
        ]);
    }

    /**
     * Get hutang vendor statistics
     */
    private function getHutangVendorStatistics()
    {
        // Calculate vendor debt from unpaid payments
        $totalHutang = Pembayaran::where('status_verifikasi', '!=', 'Approved')->sum('nominal_bayar');
        
        $hutangJatuhTempo = Pembayaran::where('status_verifikasi', '!=', 'Approved')
            ->where('tanggal_bayar', '<', Carbon::now())
            ->sum('nominal_bayar');
            
        $jumlahVendor = Pembayaran::where('status_verifikasi', '!=', 'Approved')
            ->distinct('id_vendor')
            ->count();
            
        $rataRataHutang = $jumlahVendor > 0 ? $totalHutang / $jumlahVendor : 0;

        return [
            'total_hutang' => $totalHutang ?? 0,
            'hutang_jatuh_tempo' => $hutangJatuhTempo ?? 0,
            'jumlah_vendor' => $jumlahVendor ?? 0,
            'rata_rata_hutang' => $rataRataHutang ?? 0,
        ];
    }

    /**
     * Get hutang vendor list
     */
    private function getHutangVendorList(Request $request)
    {
        return DB::table('vendor')
            ->join('pembayaran', 'vendor.id_vendor', '=', 'pembayaran.id_vendor')
            ->select(
                'vendor.nama_vendor',
                'vendor.kontak',
                DB::raw('SUM(pembayaran.nominal_bayar) as total_hutang'),
                DB::raw('COUNT(pembayaran.id_pembayaran) as jumlah_transaksi'),
                DB::raw('MIN(pembayaran.tanggal_bayar) as jatuh_tempo_terdekat')
            )
            ->where('pembayaran.status_verifikasi', '!=', 'Approved')
            ->groupBy('vendor.id_vendor', 'vendor.nama_vendor', 'vendor.kontak')
            ->orderBy('total_hutang', 'desc')
            ->paginate(10);
    }

    /**
     * Get piutang dinas statistics
     */
    private function getPiutangDinasStatistics()
    {
        // Calculate dinas debt from unpaid penagihan
        $totalPiutang = PenagihanDinas::where('status_pembayaran', '!=', 'lunas')->sum('total_harga');
        
        $piutangJatuhTempo = PenagihanDinas::where('status_pembayaran', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<', Carbon::now())
            ->sum('total_harga');
            
        $jumlahDinas = PenagihanDinas::where('status_pembayaran', '!=', 'lunas')
            ->distinct('proyek_id')
            ->count();
            
        $rataRataPiutang = $jumlahDinas > 0 ? $totalPiutang / $jumlahDinas : 0;

        return [
            'total_piutang' => $totalPiutang ?? 0,
            'piutang_jatuh_tempo' => $piutangJatuhTempo ?? 0,
            'jumlah_dinas' => $jumlahDinas ?? 0,
            'rata_rata_piutang' => $rataRataPiutang ?? 0,
        ];
    }

    /**
     * Get piutang dinas list
     */
    private function getPiutangDinasList(Request $request)
    {
        return DB::table('penagihan_dinas')
            ->join('proyek', 'penagihan_dinas.proyek_id', '=', 'proyek.id_proyek')
            ->select(
                'proyek.instansi',
                'proyek.nama_klien',
                'proyek.kode_proyek',
                DB::raw('SUM(penagihan_dinas.total_harga) as total_piutang'),
                DB::raw('COUNT(penagihan_dinas.id) as jumlah_transaksi'),
                DB::raw('MIN(penagihan_dinas.tanggal_jatuh_tempo) as jatuh_tempo_terdekat')
            )
            ->where('penagihan_dinas.status_pembayaran', '!=', 'lunas')
            ->groupBy('proyek.id_proyek', 'proyek.instansi', 'proyek.nama_klien', 'proyek.kode_proyek')
            ->orderBy('total_piutang', 'desc')
            ->paginate(10);
    }

    /**
     * Export omset report to Excel/CSV
     */
    public function exportOmset(Request $request)
    {
        // Get omset data with the same filters as the view
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        $period = $request->get('period', 'monthly');

        // Debug logging
        Log::info('Export Omset Debug:', [
            'year' => $year,
            'month' => $month,
            'period' => $period
        ]);

        // Get omset data for export
        $omsetData = $this->getOmsetData($year, $month, $period);
        
        // Create request object for admin methods
        $filterRequest = new Request();
        $filterRequest->merge([
            'year' => $year,
            'month' => $month,
            'period' => $period
        ]);
        
        $adminMarketing = $this->getAdminMarketingOmset($filterRequest);
        $adminPurchasing = $this->getAdminPurchasingOmset($filterRequest);

        // Debug logging for data
        Log::info('Export Data Debug:', [
            'omsetDataCount' => count($omsetData),
            'adminMarketingCount' => count($adminMarketing),
            'adminPurchasingCount' => count($adminPurchasing),
            'firstOmsetData' => $omsetData[0] ?? null,
            'firstMarketingData' => $adminMarketing[0] ?? null
        ]);

        // Create CSV response
        $filename = 'laporan-omset-' . $year . ($month ? '-' . str_pad($month, 2, '0', STR_PAD_LEFT) : '') . '-' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];

        $callback = function() use ($omsetData, $adminMarketing, $adminPurchasing, $year, $month, $period) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for proper UTF-8 encoding in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Add header information
            fputcsv($file, ['LAPORAN OMSET'], ';');
            fputcsv($file, ['Tahun: ' . $year], ';');
            if ($month) {
                fputcsv($file, ['Bulan: ' . Carbon::createFromDate($year, $month, 1)->format('F Y')], ';');
            }
            fputcsv($file, ['Periode: ' . ucfirst($period)], ';');
            fputcsv($file, ['Tanggal Export: ' . Carbon::now()->format('d/m/Y H:i:s')], ';');
            fputcsv($file, [], ';'); // Empty row

            // Omset per bulan/periode
            fputcsv($file, ['DATA OMSET PER ' . strtoupper($period)], ';');
            fputcsv($file, ['Periode', 'Jumlah Omset (Rp)'], ';');
            
            foreach ($omsetData as $data) {
                fputcsv($file, [
                    $data['label'],
                    number_format($data['value'], 0, ',', '.')
                ], ';');
            }
            
            fputcsv($file, [], ';'); // Empty row

            // Top Marketing
            fputcsv($file, ['TOP MARKETING'], ';');
            fputcsv($file, ['Nama Admin', 'Jumlah Proyek', 'Total Omset (Rp)'], ';');
            
            foreach ($adminMarketing as $admin) {
                fputcsv($file, [
                    $admin->name ?? $admin->nama ?? 'N/A',
                    $admin->jumlah_proyek ?? $admin->total_proyek ?? 0,
                    number_format($admin->total_omset ?? 0, 0, ',', '.')
                ], ';');
            }
            
            fputcsv($file, [], ';'); // Empty row

            // Top Purchasing
            fputcsv($file, ['TOP PURCHASING'], ';');
            fputcsv($file, ['Nama Admin', 'Jumlah Proyek', 'Total Omset (Rp)'], ';');
            
            foreach ($adminPurchasing as $admin) {
                fputcsv($file, [
                    $admin->name ?? $admin->nama ?? 'N/A',
                    $admin->jumlah_proyek ?? $admin->total_proyek ?? 0,
                    number_format($admin->total_omset ?? 0, 0, ',', '.')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get omset data for charts and export
     */
    private function getOmsetData($year, $month = null, $period = 'monthly')
    {
        $data = [];
        
        if ($period === 'monthly') {
            // Get monthly data for the year
            for ($m = 1; $m <= 12; $m++) {
                if ($month && $month != $m) {
                    continue; // Skip if specific month is requested
                }
                
                // Use the same logic as other methods - exclude only Gagal and Menunggu
                $omset = KalkulasiHps::whereHas('proyek', function($q) use ($year, $m) {
                    $q->whereNotIn('status', ['Gagal', 'Menunggu'])
                      ->whereYear('created_at', $year)
                      ->whereMonth('created_at', $m);
                })
                ->sum('nett_income');

                $data[] = [
                    'label' => Carbon::createFromDate($year, $m, 1)->format('F Y'),
                    'value' => $omset ?? 0
                ];
            }
        } elseif ($period === 'quarterly') {
            // Get quarterly data
            for ($q = 1; $q <= 4; $q++) {
                $startMonth = ($q - 1) * 3 + 1;
                $endMonth = $q * 3;
                
                $omset = KalkulasiHps::whereHas('proyek', function($query) use ($year, $startMonth, $endMonth) {
                    $query->whereNotIn('status', ['Gagal', 'Menunggu'])
                          ->whereYear('created_at', $year)
                          ->whereBetween(DB::raw('MONTH(created_at)'), [$startMonth, $endMonth]);
                })
                ->sum('nett_income');

                $data[] = [
                    'label' => 'Q' . $q . ' ' . $year,
                    'value' => $omset ?? 0
                ];
            }
        } else {
            // Yearly data
            $omset = KalkulasiHps::whereHas('proyek', function($q) use ($year) {
                $q->whereNotIn('status', ['Gagal', 'Menunggu'])
                  ->whereYear('created_at', $year);
            })
            ->sum('nett_income');

            $data[] = [
                'label' => 'Tahun ' . $year,
                'value' => $omset ?? 0
            ];
        }

        return $data;
    }
}
