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

        // Get monthly omset data (tahun berjalan)
        $monthlyOmset = $this->getMonthlyOmset($request);

        // Get vendor omset data (top 10 vendor)
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
        
        // Calculate omset from nett_income pada kalkulasi HPS untuk proyek selesai
        $currentMonthOmset = KalkulasiHps::whereHas('proyek', function($query) use ($currentMonth) {
                $query->where('status', 'selesai')
                      ->whereMonth('updated_at', $currentMonth->month)
                      ->whereYear('updated_at', $currentMonth->year);
            })
            ->sum('nett_income');

        $lastMonthOmset = KalkulasiHps::whereHas('proyek', function($query) use ($lastMonth) {
                $query->where('status', 'selesai')
                      ->whereMonth('updated_at', $lastMonth->month)
                      ->whereYear('updated_at', $lastMonth->year);
            })
            ->sum('nett_income');

        $yearlyOmset = KalkulasiHps::whereHas('proyek', function($query) use ($currentMonth) {
                $query->where('status', 'selesai')
                      ->whereYear('updated_at', $currentMonth->year);
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
     * Get monthly omset data (tahun berjalan saja)
     */
    private function getMonthlyOmset(Request $request)
    {
        $currentYear = Carbon::now()->year;
        
        return DB::table('kalkulasi_hps')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->where('proyek.status', 'selesai')
            ->whereYear('proyek.updated_at', $currentYear)
            ->select(
                DB::raw('MONTH(proyek.updated_at) as month'),
                DB::raw('SUM(kalkulasi_hps.nett_income) as total_omset'),
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
            ->where('proyek.status', 'selesai')
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
     * Get hutang vendor statistics
     */
    private function getHutangVendorStatistics()
    {
        // Hitung total modal vendor dari kalkulasi HPS (total_harga_hpp)
        $totalModalVendor = KalkulasiHps::whereHas('proyek', function($query) {
                $query->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai']);
            })
            ->sum('total_harga_hpp');

        // Hitung total yang sudah dibayar (Approved)
        $totalSudahDibayar = Pembayaran::where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        // Total hutang = Modal vendor - yang sudah dibayar
        $totalHutang = $totalModalVendor - $totalSudahDibayar;

        // Hitung hutang yang sudah jatuh tempo
        // Ini berdasarkan pembayaran yang belum approved dan tanggalnya sudah lewat
        $hutangJatuhTempo = Pembayaran::whereIn('status_verifikasi', ['Pending', 'Ditolak'])
            ->where('tanggal_bayar', '<', Carbon::now())
            ->sum('nominal_bayar');

        // Jumlah vendor yang masih ada hutang (vendor yang ada di kalkulasi HPS tapi belum lunas)
        $vendorDenganModal = KalkulasiHps::whereHas('proyek', function($query) {
                $query->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai']);
            })
            ->select('id_vendor')
            ->groupBy('id_vendor')
            ->havingRaw('SUM(total_harga_hpp) > 0')
            ->get();

        $jumlahVendorBelumLunas = 0;
        foreach ($vendorDenganModal as $vendor) {
            $totalModal = KalkulasiHps::whereHas('proyek', function($query) {
                    $query->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai']);
                })
                ->where('id_vendor', $vendor->id_vendor)
                ->sum('total_harga_hpp');

            $totalDibayar = Pembayaran::where('id_vendor', $vendor->id_vendor)
                ->where('status_verifikasi', 'Approved')
                ->sum('nominal_bayar');

            if ($totalModal > $totalDibayar) {
                $jumlahVendorBelumLunas++;
            }
        }

        // Rata-rata hutang per vendor
        $rataRataHutang = $jumlahVendorBelumLunas > 0 ? $totalHutang / $jumlahVendorBelumLunas : 0;

        return [
            'total_hutang' => max(0, $totalHutang),
            'hutang_jatuh_tempo' => $hutangJatuhTempo ?? 0,
            'jumlah_vendor' => $jumlahVendorBelumLunas,
            'rata_rata_hutang' => $rataRataHutang
        ];
    }

    /**
     * Get hutang vendor list
     */
    private function getHutangVendorList(Request $request)
    {
        // Ambil semua vendor yang ada di kalkulasi HPS untuk proyek aktif
        $vendorData = collect();
        
        $vendors = KalkulasiHps::with(['vendor', 'proyek'])
            ->whereHas('proyek', function($query) {
                $query->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai']);
            })
            ->select('id_vendor', 'id_proyek', DB::raw('SUM(total_harga_hpp) as total_modal'))
            ->groupBy('id_vendor', 'id_proyek')
            ->havingRaw('SUM(total_harga_hpp) > 0')
            ->get();

        foreach ($vendors as $vendorProyek) {
            // Hitung total yang sudah dibayar untuk vendor ini di proyek ini
            $totalDibayar = Pembayaran::whereHas('penawaran', function($query) use ($vendorProyek) {
                    $query->where('id_proyek', $vendorProyek->id_proyek);
                })
                ->where('id_vendor', $vendorProyek->id_vendor)
                ->where('status_verifikasi', 'Approved')
                ->sum('nominal_bayar');

            $sisaHutang = $vendorProyek->total_modal - $totalDibayar;

            // Hanya tambahkan jika masih ada hutang
            if ($sisaHutang > 0) {
                // Ambil pembayaran pending/ditolak terbaru untuk informasi jatuh tempo
                $pembayaranTerbaru = Pembayaran::whereHas('penawaran', function($query) use ($vendorProyek) {
                        $query->where('id_proyek', $vendorProyek->id_proyek);
                    })
                    ->where('id_vendor', $vendorProyek->id_vendor)
                    ->whereIn('status_verifikasi', ['Pending', 'Ditolak'])
                    ->orderBy('tanggal_bayar', 'desc')
                    ->first();

                $data = (object) [
                    'nama_vendor' => $vendorProyek->vendor->nama_vendor,
                    'kontak_vendor' => $vendorProyek->vendor->kontak,
                    'kode_proyek' => $vendorProyek->proyek->kode_proyek,
                    'nama_klien' => $vendorProyek->proyek->nama_klien,
                    'nominal_pembayaran' => $sisaHutang,
                    'jatuh_tempo' => $pembayaranTerbaru ? $pembayaranTerbaru->tanggal_bayar : null,
                    'status_pembayaran' => $pembayaranTerbaru ? $pembayaranTerbaru->status_verifikasi : 'Belum Bayar',
                    'catatan' => $pembayaranTerbaru ? $pembayaranTerbaru->catatan : 'Belum ada pembayaran diajukan',
                    'hari_telat' => $pembayaranTerbaru && $pembayaranTerbaru->tanggal_bayar < Carbon::now() 
                        ? Carbon::now()->diffInDays(Carbon::parse($pembayaranTerbaru->tanggal_bayar)) 
                        : 0
                ];

                $vendorData->push($data);
            }
        }

        // Apply filters
        if ($request->filled('status')) {
            $vendorData = $vendorData->filter(function($vendor) use ($request) {
                if ($request->status == 'pending') {
                    return $vendor->status_pembayaran == 'Pending';
                } elseif ($request->status == 'overdue') {
                    return $vendor->hari_telat > 0;
                } elseif ($request->status == 'rejected') {
                    return $vendor->status_pembayaran == 'Ditolak';
                }
                return true;
            });
        }

        if ($request->filled('vendor')) {
            $vendorData = $vendorData->filter(function($vendor) use ($request) {
                return stripos($vendor->nama_vendor, $request->vendor) !== false;
            });
        }

        if ($request->filled('nominal')) {
            $vendorData = $vendorData->filter(function($vendor) use ($request) {
                $nominal = $vendor->nominal_pembayaran;
                switch ($request->nominal) {
                    case '0-10jt':
                        return $nominal <= 10000000;
                    case '10-50jt':
                        return $nominal > 10000000 && $nominal <= 50000000;
                    case '50-100jt':
                        return $nominal > 50000000 && $nominal <= 100000000;
                    case '100jt+':
                        return $nominal > 100000000;
                    default:
                        return true;
                }
            });
        }

        // Convert to paginated result manually
        $page = $request->get('page', 1);
        $perPage = 15;
        $total = $vendorData->count();
        $items = $vendorData->slice(($page - 1) * $perPage, $perPage)->values();

        // Create a paginator-like object
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginator;
    }

    /**
     * Get piutang dinas statistics
     */
    private function getPiutangDinasStatistics()
    {
        // Hitung piutang berdasarkan penawaran ACC yang proyeknya belum selesai pembayaran
        // Mirip dengan logic di controller penagihan dinas
        $piutangData = Penawaran::with(['proyek', 'penagihanDinas'])
            ->where('status', 'ACC')
            ->whereHas('proyek', function($query) {
                $query->whereIn('status', ['Pembayaran', 'Pengiriman']); // Proyek yang belum selesai
            })
            ->get();

        $totalPiutang = 0;
        $piutangJatuhTempo = 0;

        foreach ($piutangData as $penawaran) {
            $totalPenawaran = $penawaran->total_penawaran;
            
            // Hitung yang sudah dibayar dari penagihan dinas
            $totalDibayar = 0;
            $hasValidPenagihan = false;
            
            if ($penawaran->penagihanDinas && is_object($penawaran->penagihanDinas)) {
                $penagihan = $penawaran->penagihanDinas;
                $hasValidPenagihan = true;
                
                if ($penagihan->status_pembayaran == 'lunas') {
                    $totalDibayar = $penagihan->total_harga;
                } elseif ($penagihan->status_pembayaran == 'dp') {
                    $totalDibayar = $penagihan->jumlah_dp ?? 0;
                }
            }

            $sisaPiutang = $totalPenawaran - $totalDibayar;
            
            // Untuk penawaran yang belum memiliki penagihan dinas, tetap dihitung sebagai piutang penuh
            // Untuk yang sudah ada penagihan dinas, hitung sisanya
            if ($sisaPiutang > 0) {
                $totalPiutang += $sisaPiutang;

                // Cek jatuh tempo dari penagihan dinas yang terkait
                if ($hasValidPenagihan && 
                    in_array($penawaran->penagihanDinas->status_pembayaran, ['dp', 'belum_bayar']) &&
                    $penawaran->penagihanDinas->tanggal_jatuh_tempo < Carbon::now()) {
                    $piutangJatuhTempo += $sisaPiutang;
                }
            }
        }

        // Jumlah proyek yang memiliki piutang
        $jumlahProyekBelumLunas = $piutangData->where('total_penawaran', '>', 0)->count();

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
        // Ambil data piutang berdasarkan penawaran ACC seperti di controller penagihan dinas
        $query = Penawaran::with(['proyek', 'penagihanDinas'])
            ->where('status', 'ACC')
            ->whereHas('proyek', function($q) {
                $q->whereIn('status', ['Pembayaran', 'Pengiriman']); // Proyek yang belum selesai
            });

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status == 'pending') {
                $query->whereHas('penagihanDinas', function($q) {
                    $q->where('status_pembayaran', 'belum_bayar');
                });
            } elseif ($request->status == 'partial') {
                $query->whereHas('penagihanDinas', function($q) {
                    $q->where('status_pembayaran', 'dp');
                });
            } elseif ($request->status == 'overdue') {
                $query->whereHas('penagihanDinas', function($q) {
                    $q->where('tanggal_jatuh_tempo', '<', Carbon::now())
                      ->whereIn('status_pembayaran', ['dp', 'belum_bayar']);
                });
            }
        }

        if ($request->filled('instansi')) {
            $query->whereHas('proyek', function($q) use ($request) {
                $q->where('instansi', 'like', '%' . $request->instansi . '%');
            });
        }

        if ($request->filled('nominal')) {
            switch ($request->nominal) {
                case '0-10jt':
                    $query->where('total_penawaran', '<=', 10000000);
                    break;
                case '10-50jt':
                    $query->whereBetween('total_penawaran', [10000001, 50000000]);
                    break;
                case '50-100jt':
                    $query->whereBetween('total_penawaran', [50000001, 100000000]);
                    break;
                case '100jt+':
                    $query->where('total_penawaran', '>', 100000000);
                    break;
            }
        }

        $penawaran = $query->get();

        // Transform data untuk tampilan
        $piutangData = collect();
        foreach ($penawaran as $item) {
            $totalPenawaran = $item->total_penawaran;
            
            // Hitung yang sudah dibayar
            $totalDibayar = 0;
            $statusTerbaru = 'belum_bayar';
            $jatuhTempo = null;
            
            if ($item->penagihanDinas && is_object($item->penagihanDinas)) {
                $penagihan = $item->penagihanDinas;
                if ($penagihan->status_pembayaran == 'lunas') {
                    $totalDibayar = $penagihan->total_harga;
                } elseif ($penagihan->status_pembayaran == 'dp') {
                    $totalDibayar = $penagihan->jumlah_dp ?? 0;
                    $statusTerbaru = 'dp';
                }
                
                $jatuhTempo = $penagihan->tanggal_jatuh_tempo;
            }

            $sisaPembayaran = $totalPenawaran - $totalDibayar;
            
            // Tampilkan semua penawaran yang masih memiliki sisa pembayaran > 0
            if ($sisaPembayaran > 0) {
                $piutangData->push((object) [
                    'id' => $item->id_penawaran,
                    'nomor_invoice' => $item->no_penawaran,
                    'proyek' => $item->proyek,
                    'total_harga' => $totalPenawaran,
                    'sisa_pembayaran' => $sisaPembayaran,
                    'status_pembayaran' => $statusTerbaru,
                    'tanggal_jatuh_tempo' => $jatuhTempo,
                    'hari_telat' => $jatuhTempo && $jatuhTempo < Carbon::now() 
                        ? Carbon::now()->diffInDays(Carbon::parse($jatuhTempo)) 
                        : 0
                ]);
            }
        }

        // Manual pagination
        $page = $request->get('page', 1);
        $perPage = 15;
        $total = $piutangData->count();
        $items = $piutangData->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginator;
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
