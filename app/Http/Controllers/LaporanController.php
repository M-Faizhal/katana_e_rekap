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
        // Check if this is an AJAX request for chart data
        if ($request->ajax() && $request->has('chart_year')) {
            $chartData = $this->getChartData($request);
            return response()->json([
                'success' => true,
                'chartData' => $chartData
            ]);
        }

        // Get basic statistics
        $stats = $this->getStatistics();

        // Get projects with filters applied
        $projects = $this->getFilteredProjects($request);

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        // Get chart data
        $chartData = $this->getChartData($request);

        // Get year range from project data
        $yearRange = $this->getYearRange();

        return view('pages.laporan.proyek', compact('stats', 'projects', 'filterOptions', 'chartData', 'yearRange'));
    }

    /**
     * Display Laporan Omset
     */
    public function omset(Request $request)
    {
        // Get omset statistics with year filter
        $stats = $this->getOmsetStatistics($request);

        // Get monthly omset data with year filter
        $monthlyOmset = $this->getMonthlyOmset($request);

        // Get admin omset data with year filter
        $adminMarketing = $this->getAdminMarketingOmset($request);
        $adminPurchasing = $this->getAdminPurchasingOmset($request);

        // Get year range from project data
        $yearRange = $this->getYearRange();
        
        // Debug log
        Log::info('Year range data:', $yearRange);

        return view('pages.laporan.omset', compact('stats', 'monthlyOmset', 'adminMarketing', 'adminPurchasing', 'yearRange'));
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
        $hutangVendorData = $this->getHutangVendorList($request);
        $hutangVendor = $hutangVendorData['paginator'];
        $allVendors = $hutangVendorData['all_vendors'];

        return view('pages.laporan.hutang-vendor', compact('stats', 'hutangVendor', 'allVendors'));
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
        // Hitung total nilai dari harga_total proyek yang status tidak 'Gagal'
        $totalNilai = Proyek::where('status', '!=', 'Gagal')
            ->whereNotNull('harga_total')
            ->sum('harga_total');

        // Hitung proyek yang sudah SP (proyek dengan penawaran status ACC)
        $proyekSP = Proyek::whereHas('semuaPenawaran', function($query) {
            $query->where('status', 'ACC');
        })->where('status', '!=', 'Gagal')->count();

        // Hitung proyek selesai
        $proyekSelesai = Proyek::where('status', 'Selesai')->count();

        // Hitung proyek berjalan (selisih proyek SP dan proyek selesai)
        $proyekBerjalan = $proyekSP - $proyekSelesai;

        $stats = [
            'total_proyek' => Proyek::where('status', '!=', 'Gagal')->count(),
            'proyek_selesai' => $proyekSelesai,
            'proyek_sp' => $proyekSP,
            'proyek_berjalan' => max(0, $proyekBerjalan), // Pastikan tidak negatif
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
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('periode')) {
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
    private function getOmsetStatistics(Request $request = null)
    {
        $currentMonth = Carbon::now();
        $currentYear = Carbon::now()->year;
        
        // Get year filter parameter
        $selectedYear = $request ? $request->get('year') : null;
        
        if ($selectedYear && $selectedYear !== 'all') {
            // For specific year
            $year = (int) $selectedYear;
            
            // Total Omset = Omset kumulatif dari awal sampai tahun terpilih dari kalkulasi HPS
            $totalOmset = DB::table('kalkulasi_hps')
                ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                ->where('penawaran.status', 'ACC')
                ->whereYear('penawaran.tanggal_penawaran', '<=', $year)
                ->sum('kalkulasi_hps.hps');
                
            // Omset Tahun = Omset hanya di tahun terpilih saja dari kalkulasi HPS
            $omsetTahunIni = DB::table('kalkulasi_hps')
                ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                ->where('penawaran.status', 'ACC')
                ->whereYear('penawaran.tanggal_penawaran', $year)
                ->sum('kalkulasi_hps.hps');
            
            // Omset bulan ini (jika tahun terpilih adalah tahun sekarang)
            if ($year == $currentYear) {
                $omsetBulanIni = DB::table('kalkulasi_hps')
                    ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                    ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                    ->where('penawaran.status', 'ACC')
                    ->whereYear('penawaran.tanggal_penawaran', $year)
                    ->whereMonth('penawaran.tanggal_penawaran', $currentMonth->month)
                    ->sum('kalkulasi_hps.hps');
            } else {
                // Jika bukan tahun sekarang, ambil bulan terakhir dari tahun itu
                $omsetBulanIni = DB::table('kalkulasi_hps')
                    ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                    ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                    ->where('penawaran.status', 'ACC')
                    ->whereYear('penawaran.tanggal_penawaran', $year)
                    ->whereMonth('penawaran.tanggal_penawaran', 12) // Desember
                    ->sum('kalkulasi_hps.hps');
            }
        } else {
            // For "Semua Tahun" option, show cumulative data
            
            // Total Omset - semua proyek dengan penawaran ACC dari awal sampai akhir
            $totalOmset = DB::table('kalkulasi_hps')
                ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                ->where('penawaran.status', 'ACC')
                ->sum('kalkulasi_hps.hps');

            // Omset Tahun Ini - proyek dengan penawaran ACC tahun ini
            $omsetTahunIni = DB::table('kalkulasi_hps')
                ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                ->where('penawaran.status', 'ACC')
                ->whereYear('penawaran.tanggal_penawaran', $currentYear)
                ->sum('kalkulasi_hps.hps');

            // Omset Bulan Ini - proyek dengan penawaran ACC bulan ini
            $omsetBulanIni = DB::table('kalkulasi_hps')
                ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                ->where('penawaran.status', 'ACC')
                ->whereYear('penawaran.tanggal_penawaran', $currentYear)
                ->whereMonth('penawaran.tanggal_penawaran', $currentMonth->month)
                ->sum('kalkulasi_hps.hps');
        }

        return [
            'total_omset' => $totalOmset ?? 0,
            'omset_tahun_ini' => $omsetTahunIni ?? 0,
            'omset_bulan_ini' => $omsetBulanIni ?? 0,
        ];
    }

    /**
     * Get monthly omset data (with year filter)
     */
    private function getMonthlyOmset(Request $request)
    {
        $selectedYear = $request->get('year');
        
        if ($selectedYear && $selectedYear === 'all') {
            // For "Semua Tahun", show yearly data instead of monthly
            return DB::table('kalkulasi_hps')
                ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                ->where('penawaran.status', 'ACC')
                ->select(
                    DB::raw('YEAR(penawaran.tanggal_penawaran) as year'),
                    DB::raw('SUM(kalkulasi_hps.hps) as total_omset'),
                    DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek')
                )
                ->groupBy('year')
                ->orderBy('year')
                ->get();
        } else {
            // For specific year or default (current year), show monthly data
            $year = $selectedYear ? (int) $selectedYear : Carbon::now()->year;
            
            return DB::table('kalkulasi_hps')
                ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                ->where('penawaran.status', 'ACC')
                ->whereYear('penawaran.tanggal_penawaran', $year)
                ->select(
                    DB::raw('MONTH(penawaran.tanggal_penawaran) as month'),
                    DB::raw('SUM(kalkulasi_hps.hps) as total_omset'),
                    DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }
    }

    /**
     * Get vendor omset data (Top 10 Vendor berdasarkan keuntungan)
     */
    private function getVendorOmset(Request $request)
    {
        // Hitung omset vendor berdasarkan nett_income dari kalkulasi HPS untuk proyek dengan penawaran ACC
        // Vendor omset = kontribusi keuntungan vendor dari semua proyek yang memiliki penawaran ACC
        return DB::table('vendor')
            ->join('kalkulasi_hps', 'vendor.id_vendor', '=', 'kalkulasi_hps.id_vendor')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->where('penawaran.status', 'ACC')
            ->whereYear('penawaran.tanggal_penawaran', Carbon::now()->year) // Hanya tahun ini
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
        // Base query
        $query = DB::table('users')
            ->join('proyek', 'users.id_user', '=', 'proyek.id_admin_marketing')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->join('kalkulasi_hps', 'proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
            ->where('penawaran.status', 'ACC');
            
        // Apply year filter
        $selectedYear = $request ? $request->get('year') : null;
        
        if ($selectedYear && $selectedYear !== 'all') {
            // For specific year
            $year = (int) $selectedYear;
            $query->whereYear('penawaran.tanggal_penawaran', $year);
        }
        // For "all" years, no additional filter needed
        
        $result = $query->select(
                'users.nama as name',
                DB::raw('SUM(kalkulasi_hps.hps) as total_omset'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek'),
                DB::raw('AVG(kalkulasi_hps.hps) as rata_rata_omset_per_proyek')
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
        // Base query
        $query = DB::table('users')
            ->join('proyek', 'users.id_user', '=', 'proyek.id_admin_purchasing')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->join('kalkulasi_hps', 'proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
            ->where('penawaran.status', 'ACC');
            
        // Apply year filter
        $selectedYear = $request ? $request->get('year') : null;
        
        if ($selectedYear && $selectedYear !== 'all') {
            // For specific year
            $year = (int) $selectedYear;
            $query->whereYear('penawaran.tanggal_penawaran', $year);
        }
        // For "all" years, no additional filter needed
        
        $result = $query->select(
                'users.nama as name',
                DB::raw('SUM(kalkulasi_hps.hps) as total_omset'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek'),
                DB::raw('AVG(kalkulasi_hps.hps) as rata_rata_omset_per_proyek')
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
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->where('penawaran.status', 'ACC');

        if ($period === 'yearly') {
            // For yearly view, group by year
            return $query->select(
                    DB::raw('YEAR(penawaran.tanggal_penawaran) as year'),
                    DB::raw('SUM(kalkulasi_hps.hps) as total_omset'),
                    DB::raw('COUNT(DISTINCT proyek.id_proyek) as jumlah_proyek')
                )
                ->groupBy('year')
                ->orderBy('year')
                ->get();
        } else {
            // For monthly or quarterly, filter by year and optionally by month
            $query->whereYear('penawaran.tanggal_penawaran', $year);
            
            if ($month) {
                $query->whereMonth('penawaran.tanggal_penawaran', $month);
            }
            
            return $query->select(
                    DB::raw('MONTH(penawaran.tanggal_penawaran) as month'),
                    DB::raw('SUM(kalkulasi_hps.hps) as total_omset'),
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
        $currentQuery = DB::table('kalkulasi_hps')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->where('penawaran.status', 'ACC')
            ->whereYear('penawaran.tanggal_penawaran', $year);
            
        if ($month) {
            $currentQuery->whereMonth('penawaran.tanggal_penawaran', $month);
        }

        $currentOmset = $currentQuery->sum('kalkulasi_hps.hps');
        
        // For comparison, get previous period data
        $previousQuery = DB::table('kalkulasi_hps')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->where('penawaran.status', 'ACC');
            
        if ($month) {
            // Previous month
            $prevMonth = $month - 1;
            $prevYear = $year;
            if ($prevMonth <= 0) {
                $prevMonth = 12;
                $prevYear = $year - 1;
            }
            $previousQuery->whereMonth('penawaran.tanggal_penawaran', $prevMonth)->whereYear('penawaran.tanggal_penawaran', $prevYear);
        } else {
            // Previous year
            $previousQuery->whereYear('penawaran.tanggal_penawaran', $year - 1);
        }

        $previousOmset = $previousQuery->sum('kalkulasi_hps.hps');
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
        // Get year from chart_year parameter or default to current year
        $year = $request->get('chart_year', $request->get('year', Carbon::now()->year));
        
        // Get monthly project data for the entire year (12 months, excluding 'Gagal' status)
        $monthlyProjects = DB::table('proyek')
            ->select(
                DB::raw('MONTH(tanggal) as month_num'),
                DB::raw('MONTHNAME(tanggal) as month_name'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', '!=', 'Gagal') // Exclude failed projects
            ->whereYear('tanggal', $year)
            ->groupBy('month_num', 'month_name')
            ->orderBy('month_num')
            ->get();

        // Create complete 12-month data array
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyProjectsFormatted = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyProjects->firstWhere('month_num', $i);
            $monthlyProjectsFormatted[] = [
                'month' => $monthNames[$i - 1],
                'count' => $monthData ? $monthData->count : 0
            ];
        }

        // Get status distribution for the selected year
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

        // Get monthly values from proyek.harga_total for the entire year (excluding 'Gagal' status)
        $monthlyValues = DB::table('proyek')
            ->select(
                DB::raw('MONTH(tanggal) as month_num'),
                DB::raw('MONTHNAME(tanggal) as month_name'),
                DB::raw('SUM(COALESCE(harga_total, 0)) as total_value')
            )
            ->where('status', '!=', 'Gagal') // Exclude failed projects
            ->whereYear('tanggal', $year)
            ->groupBy('month_num', 'month_name')
            ->orderBy('month_num')
            ->get();

        // Create complete 12-month values data array
        $monthlyValuesFormatted = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyValues->firstWhere('month_num', $i);
            $monthlyValuesFormatted[] = [
                'month' => $monthNames[$i - 1],
                'value' => $monthData ? $monthData->total_value : 0
            ];
        }

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
        // Hitung total record hutang vendor (vendor per proyek)
        // Menggunakan logika yang sama dengan getHutangVendorList untuk konsistensi
        
        $totalHutang = 0;
        $jumlahHutangVendor = 0;
        
        // Ambil proyek yang perlu bayar dengan cara yang sama seperti getHutangVendorList
        $proyekPerluBayar = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminMarketing', 'pembayaran.vendor'])
            ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai'])
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
                        'warning_hps' => $warning_hps,
                        'proyek' => $proyek
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
            });

        // Hitung total hutang dan jumlah record
        foreach ($proyekPerluBayar as $proyek) {
            foreach ($proyek->vendors_data as $vendorData) {
                $totalHutang += $vendorData->sisa_bayar;
                $jumlahHutangVendor++;
            }
        }
            
        // For overdue payments, we need pending payments past due date
        $hutangJatuhTempo = Pembayaran::where('status_verifikasi', '!=', 'Approved')
            ->where('tanggal_bayar', '<', Carbon::now())
            ->sum('nominal_bayar');
            
        $rataRataHutang = $jumlahHutangVendor > 0 ? $totalHutang / $jumlahHutangVendor : 0;

        return [
            'total_hutang' => $totalHutang ?? 0,
            'hutang_jatuh_tempo' => $hutangJatuhTempo ?? 0,
            'jumlah_vendor' => $jumlahHutangVendor ?? 0,
            'rata_rata_hutang' => $rataRataHutang ?? 0,
        ];
    }

    /**
     * Get hutang vendor list (menggunakan logika yang sama dengan getHutangVendorStatistics)
     */
    private function getHutangVendorList(Request $request)
    {
        // Ambil proyek yang perlu bayar dengan logika yang sama seperti getHutangVendorStatistics
        $proyekPerluBayar = Proyek::with(['penawaranAktif.penawaranDetail.barang.vendor', 'adminMarketing', 'pembayaran.vendor'])
            ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai'])
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
                        'warning_hps' => $warning_hps,
                        'proyek' => $proyek
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
            });

        // Flatten menjadi list vendor per proyek
        $results = collect();
        foreach ($proyekPerluBayar as $proyek) {
            foreach ($proyek->vendors_data as $vendorData) {
                $results->push($vendorData);
            }
        }

        // Get all unique vendors for filter dropdown (before applying filters)
        $allVendors = $results->map(function($item) {
            return $item->vendor;
        })->unique('id_vendor')->values();

        // Apply filters
        if ($request->filled('vendor')) {
            $results = $results->filter(function($item) use ($request) {
                return stripos($item->vendor->nama_vendor, $request->vendor) !== false;
            });
        }

        if ($request->filled('nominal')) {
            $nominal = $request->nominal;
            $results = $results->filter(function($item) use ($nominal) {
                $total = $item->total_vendor;
                if ($nominal === '0-10jt') {
                    return $total < 10000000;
                } elseif ($nominal === '10-50jt') {
                    return $total >= 10000000 && $total < 50000000;
                } elseif ($nominal === '50-100jt') {
                    return $total >= 50000000 && $total < 100000000;
                } elseif ($nominal === '100jt+') {
                    return $total >= 100000000;
                }
                return true;
            });
        }

        // Manual pagination
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $total = $results->count();
        $items = $results->forPage($currentPage, $perPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return [
            'paginator' => $paginator,
            'all_vendors' => $allVendors
        ];
    }

    /**
     * Helper method untuk mengecek apakah semua vendor sudah mengirim dengan status "Sampai_Tujuan"
     * 
     * @param Penawaran $penawaran
     * @return bool
     */
    private function checkAllVendorsDelivered($penawaran)
    {
        // Ambil semua vendor yang terlibat dalam penawaran ini
        $vendorIds = $penawaran->penawaranDetail()
            ->with('barang.vendor')
            ->get()
            ->pluck('barang.id_vendor')
            ->filter()
            ->unique()
            ->values();

        // Jika tidak ada vendor, return false (tidak bisa ditagih)
        if ($vendorIds->isEmpty()) {
            return false;
        }

        // Cek pengiriman untuk setiap vendor
        foreach ($vendorIds as $vendorId) {
            $pengiriman = $penawaran->pengiriman()
                ->where('id_vendor', $vendorId)
                ->latest()
                ->first();

            // Jika vendor belum ada pengiriman atau status bukan "Sampai_Tujuan", return false
            if (!$pengiriman || $pengiriman->status_verifikasi !== 'Sampai_Tujuan') {
                return false;
            }
        }

        // Semua vendor sudah mengirim dengan status "Sampai_Tujuan"
        return true;
    }

    /**
     * Get piutang dinas statistics
     */
    private function getPiutangDinasStatistics()
    {
        // Ambil semua proyek yang sudah di ACC dan hitung piutangnya
        $proyekAcc = Proyek::with(['semuaPenawaran' => function($query) {
            $query->where('status', 'ACC');
        }, 'semuaPenawaran.pengiriman.vendor', 'semuaPenawaran.penawaranDetail.barang.vendor', 'penagihanDinas.buktiPembayaran'])
        ->whereHas('semuaPenawaran', function($query) {
            $query->where('status', 'ACC');
        })
        ->get();

        $totalPiutang = 0;
        $piutangJatuhTempo = 0;
        $jumlahProyek = 0;

        foreach ($proyekAcc as $proyek) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
                // Cek apakah semua vendor sudah mengirim dengan status "Sampai_Tujuan"
                $allVendorsDelivered = $this->checkAllVendorsDelivered($penawaran);
                
                // Skip jika vendor belum mengirim semua barang
                if (!$allVendorsDelivered) {
                    continue;
                }
                
                // Cek apakah ada penagihan untuk penawaran ini
                $penagihan = $proyek->penagihanDinas->where('penawaran_id', $penawaran->id_penawaran)->first();
                
                if (!$penagihan) {
                    // Belum ada penagihan sama sekali - semua jadi piutang
                    $totalPiutang += $penawaran->total_penawaran ?? 0;
                    $jumlahProyek++;
                    // Anggap jatuh tempo jika sudah lebih dari 30 hari sejak ACC
                    if ($penawaran->updated_at < now()->subDays(30)) {
                        $piutangJatuhTempo += $penawaran->total_penawaran ?? 0;
                    }
                               } else if ($penagihan->status_pembayaran != 'lunas') {
                    // Ada penagihan tapi belum lunas
                    $totalBayar = $penagihan->buktiPembayaran->sum('jumlah_bayar');
                    $sisaPembayaran = $penagihan->total_harga - $totalBayar;
                    
                    if ($sisaPembayaran > 0) {
                        $totalPiutang += $sisaPembayaran;
                        $jumlahProyek++;
                        
                        // Check if overdue
                        if ($penagihan->tanggal_jatuh_tempo && $penagihan->tanggal_jatuh_tempo < now()) {
                            $piutangJatuhTempo += $sisaPembayaran;
                        }
                    }
                }
            }
        }
            
        $rataRataPiutang = $jumlahProyek > 0 ? $totalPiutang / $jumlahProyek : 0;

        return [
            'total_piutang' => $totalPiutang,
            'piutang_jatuh_tempo' => $piutangJatuhTempo,
            'jumlah_proyek' => $jumlahProyek,
            'rata_rata_piutang' => $rataRataPiutang,
        ];
    }

    /**
     * Get piutang dinas list
     */
    private function getPiutangDinasList(Request $request)
    {
        // Ambil semua proyek yang sudah di ACC
        $proyekAcc = Proyek::with(['semuaPenawaran' => function($query) {
            $query->where('status', 'ACC');
        }, 'semuaPenawaran.pengiriman.vendor', 'semuaPenawaran.penawaranDetail.barang.vendor', 'penagihanDinas.buktiPembayaran'])
        ->whereHas('semuaPenawaran', function($query) {
            $query->where('status', 'ACC');
        });

        // Apply filters for proyek
        if ($request->filled('instansi')) {
            $proyekAcc->where('instansi', 'like', '%' . $request->get('instansi') . '%');
        }

        $proyekAcc = $proyekAcc->get();

        // Debug: Log data proyek yang ditemukan
        Log::info('Debug Piutang Dinas:', [
            'total_proyek_acc' => $proyekAcc->count(),
            'proyekAcc' => $proyekAcc->map(function($p) {
                return [
                    'kode_proyek' => $p->kode_proyek,
                    'penawaran_count' => $p->semuaPenawaran->count(),
                    'penagihan_count' => $p->penagihanDinas->count(),
                    'penawaran_ids' => $p->semuaPenawaran->pluck('id_penawaran')->toArray(),
                    'penagihan' => $p->penagihanDinas->map(function($pn) {
                        return [
                            'penawaran_id' => $pn->penawaran_id,
                            'status' => $pn->status_pembayaran,
                            'nomor_invoice' => $pn->nomor_invoice
                        ];
                    })->toArray()
                ];
            })->toArray()
        ]);

        // Transform ke format yang dibutuhkan untuk ditampilkan
        $piutangList = collect();

        foreach ($proyekAcc as $proyek) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
                // Cek apakah semua vendor sudah mengirim dengan status "Sampai_Tujuan"
                $allVendorsDelivered = $this->checkAllVendorsDelivered($penawaran);
                
                // Skip jika vendor belum mengirim semua barang
                if (!$allVendorsDelivered) {
                    Log::info('Skip penawaran - vendor belum kirim semua:', [
                        'proyek' => $proyek->kode_proyek,
                        'penawaran_id' => $penawaran->id_penawaran,
                        'all_vendors_delivered' => false
                    ]);
                    continue;
                }
                
                $penagihan = $proyek->penagihanDinas->where('penawaran_id', $penawaran->id_penawaran)->first();
                
                $shouldInclude = false;
                $sisaPembayaran = 0;
                $status = '';
                $tanggalJatuhTempo = null;
                $nomorInvoice = '';
                
                // Debug log untuk setiap iterasi
                Log::info('Processing penawaran:', [
                    'proyek' => $proyek->kode_proyek,
                    'penawaran_id' => $penawaran->id_penawaran,
                    'has_penagihan' => $penagihan ? true : false,
                    'penagihan_status' => $penagihan ? $penagihan->status_pembayaran : null
                ]);
                
                if (!$penagihan) {
                    // Belum ada penagihan sama sekali
                    $shouldInclude = true;
                    $sisaPembayaran = $penawaran->total_penawaran ?? 0;
                    $status = 'belum_ditagih';
                    $tanggalJatuhTempo = now()->addDays(30); // Default 30 hari dari sekarang
                    $nomorInvoice = 'Belum Ditagih';
                    
                    Log::info('Case: Belum ada penagihan', [
                        'proyek' => $proyek->kode_proyek,
                        'penawaran_id' => $penawaran->id_penawaran,
                        'should_include' => $shouldInclude
                    ]);
                } else if ($penagihan->status_pembayaran != 'lunas') {
                    // Ada penagihan tapi belum lunas
                    $totalBayar = $penagihan->buktiPembayaran->sum('jumlah_bayar');
                    $sisaPembayaran = $penagihan->total_harga - $totalBayar;
                    
                    if ($sisaPembayaran > 0) {
                        $shouldInclude = true;
                        $status = $penagihan->status_pembayaran;
                        $tanggalJatuhTempo = $penagihan->tanggal_jatuh_tempo;
                        $nomorInvoice = $penagihan->nomor_invoice;
                    }
                    
                    Log::info('Case: Ada penagihan belum lunas', [
                        'proyek' => $proyek->kode_proyek,
                        'penawaran_id' => $penawaran->id_penawaran,
                        'status_pembayaran' => $penagihan->status_pembayaran,
                        'total_harga' => $penagihan->total_harga,
                        'total_bayar' => $totalBayar,
                        'sisa_pembayaran' => $sisaPembayaran,
                        'should_include' => $shouldInclude
                    ]);
                } else if ($request->has('show_all') && $request->get('show_all') === 'true') {
                    // Tampilkan yang lunas juga jika show_all = true
                    $shouldInclude = true;
                    $totalBayar = $penagihan->buktiPembayaran->sum('jumlah_bayar');
                    $sisaPembayaran = $penagihan->total_harga - $totalBayar;
                    $status = $penagihan->status_pembayaran;
                    $tanggalJatuhTempo = $penagihan->tanggal_jatuh_tempo;
                    $nomorInvoice = $penagihan->nomor_invoice;
                    
                    Log::info('Case: Show all (lunas)', [
                        'proyek' => $proyek->kode_proyek,
                        'penawaran_id' => $penawaran->id_penawaran,
                        'should_include' => $shouldInclude
                    ]);
                } else {
                    Log::info('Case: Tidak diinclude (lunas)', [
                        'proyek' => $proyek->kode_proyek,
                        'penawaran_id' => $penawaran->id_penawaran,
                        'status_pembayaran' => $penagihan->status_pembayaran,
                        'should_include' => false
                    ]);
                }

                if ($shouldInclude) {
                    // Apply additional filters
                    if ($request->filled('status')) {
                        $filterStatus = $request->get('status');
                        if ($filterStatus == 'pending' && $status != 'belum_bayar' && $status != 'belum_ditagih') continue;
                        if ($filterStatus == 'partial' && $status != 'dp') continue;
                        if ($filterStatus == 'overdue' && $tanggalJatuhTempo >= now()) continue;
                    }

                    if ($request->filled('nominal')) {
                        $nominal = $request->get('nominal');
                        $totalHarga = $penawaran->total_penawaran ?? 0;
                        switch ($nominal) {
                            case '0-10jt':
                                if ($totalHarga >= 10000000) continue 2;
                                break;
                            case '10-50jt':
                                if ($totalHarga < 10000000 || $totalHarga > 50000000) continue 2;
                                break;
                            case '50-100jt':
                                if ($totalHarga < 50000000 || $totalHarga > 100000000) continue 2;
                                break;
                            case '100jt+':
                                if ($totalHarga <= 100000000) continue 2;
                                break;
                        }
                    }

                    // Create a pseudo model object
                    $piutangItem = (object)[
                        'id' => $penagihan ? $penagihan->id : 'no-penagihan-' . $proyek->id_proyek . '-' . $penawaran->id_penawaran,
                        'nomor_invoice' => $nomorInvoice,
                        'proyek' => $proyek,
                        'penawaran' => $penawaran,
                        'total_harga' => $penawaran->total_penawaran ?? 0,
                        'sisa_pembayaran' => $sisaPembayaran,
                        'status_pembayaran' => $status,
                        'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                        'hari_telat' => $tanggalJatuhTempo && $tanggalJatuhTempo < now() 
                            ? now()->diffInDays($tanggalJatuhTempo) 
                            : 0,
                    ];

                    $piutangList->push($piutangItem);
                }
            }
        }

        // Sort by tanggal jatuh tempo (yang paling urgent di atas)
        $piutangList = $piutangList->sortBy(function($item) {
            return $item->tanggal_jatuh_tempo ?? now()->addYears(10);
        });

        // Convert to paginated collection
        $currentPage = $request->get('page', 1);
        $perPage = 10;
        $total = $piutangList->count();
        $items = $piutangList->forPage($currentPage, $perPage)->values();

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );

        // Append query parameters
        $paginated->appends($request->query());

        return $paginated;
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
                
                // Use harga_total from proyek with ACC penawaran status
                $omset = Proyek::whereHas('semuaPenawaran', function($query) {
                        $query->where('status', 'ACC');
                    })
                    ->whereNotNull('harga_total')
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $m)
                    ->sum('harga_total');

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
                
                $omset = Proyek::whereHas('semuaPenawaran', function($query) {
                        $query->where('status', 'ACC');
                    })
                    ->whereNotNull('harga_total')
                    ->whereYear('tanggal', $year)
                    ->whereBetween(DB::raw('MONTH(tanggal)'), [$startMonth, $endMonth])
                    ->sum('harga_total');

                $data[] = [
                    'label' => 'Q' . $q . ' ' . $year,
                    'value' => $omset ?? 0
                ];
            }
        } else {
            // Yearly data
            $omset = Proyek::whereHas('semuaPenawaran', function($query) {
                    $query->where('status', 'ACC');
                })
                ->whereNotNull('harga_total')
                ->whereYear('tanggal', $year)
                ->sum('harga_total');

            $data[] = [
                'label' => 'Tahun ' . $year,
                'value' => $omset ?? 0
            ];
        }

        return $data;
    }

    /**
     * Get dynamic year range from project data
     */
    private function getYearRange()
    {
        $yearRange = DB::table('proyek')
            ->selectRaw('MIN(YEAR(tanggal)) as min_year, MAX(YEAR(tanggal)) as max_year')
            ->where('status', '!=', 'Gagal') // Exclude failed projects
            ->first();

        // Set defaults if no projects exist
        $currentYear = Carbon::now()->year;
        $minYear = $yearRange->min_year ?? $currentYear;
        $maxYear = $yearRange->max_year ?? $currentYear;

        // Ensure min year is not less than 2020 (reasonable minimum)
        $minYear = max($minYear, 2020);
        
        // Allow max year to extend to next year for future projects
        $maxYear = max($maxYear, $currentYear);

        return [
            'min_year' => $minYear,
            'max_year' => $maxYear,
            'current_year' => $currentYear
        ];
    }

}
