<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Vendor;
use App\Models\Barang;
use App\Models\User;
use App\Models\Penawaran;
use App\Models\PenawaranDetail;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\KalkulasiHps;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
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
     * Display the dashboard
     */
    public function index()
    {
        // Get basic statistics
        $stats = $this->getDashboardStats();

        // Add formatted versions for display (same as omset report)
        $stats['omset_bulan_ini_formatted'] = $this->formatRupiah($stats['omset_bulan_ini']);
        $stats['total_hutang_formatted'] = $this->formatRupiah($stats['total_hutang']);
        $stats['total_piutang_formatted'] = $this->formatRupiah($stats['total_piutang']);

        // Get monthly revenue data (default to current year)
        $monthlyRevenue = $this->getMonthlyRevenue();

        // Get revenue per admin marketing
        $revenuePerPerson = $this->getRevenuePerPerson();

        // Get vendor debts (hutang)
        $vendorDebts = $this->getVendorDebts();

        // Get client receivables (piutang)
        $clientReceivables = $this->getClientReceivables();

        // Get geographic distribution
        $geographicData = $this->getGeographicDistribution();

        // Get geographic statistics for map display
        $geographicStats = $this->getGeographicStats($geographicData);

        // Get debt age analysis
        $debtAgeAnalysis = $this->getDebtAgeAnalysis();

        return view('pages.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'revenuePerPerson',
            'vendorDebts',
            'clientReceivables',
            'geographicData',
            'geographicStats',
            'debtAgeAnalysis'
        ));
    }

    /**
     * Get main dashboard statistics
     */
    private function getDashboardStats()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth();

        // Calculate omset bulan ini (revenue this month) - use same method as omset report
        // Using nett_income from kalkulasi_hps instead of harga_total from proyek
        $omsetBulanIni = KalkulasiHps::whereHas('proyek', function($query) use ($currentMonth, $currentYear) {
                $query->whereNotIn('status', ['Gagal', 'Menunggu']) // Exclude gagal and menunggu
                      ->whereMonth('created_at', $currentMonth)
                      ->whereYear('created_at', $currentYear);
            })
            ->sum('nett_income') ?? 0;

        // Calculate omset bulan lalu untuk perbandingan
        $omsetBulanLalu = KalkulasiHps::whereHas('proyek', function($query) use ($lastMonth) {
                $query->whereNotIn('status', ['Gagal', 'Menunggu'])
                      ->whereMonth('created_at', $lastMonth->month)
                      ->whereYear('created_at', $lastMonth->year);
            })
            ->sum('nett_income') ?? 0;

        // Calculate growth percentage
        $omsetGrowth = $omsetBulanLalu > 0 ?
            (($omsetBulanIni - $omsetBulanLalu) / $omsetBulanLalu) * 100 :
            ($omsetBulanIni > 0 ? 100 : 0);

        // Count active projects
        $proyekAktif = Proyek::whereNotIn('status', ['selesai', 'gagal'])->count();
        $proyekBaru = Proyek::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Calculate total hutang (belum dibayar ke vendor) - menggunakan logika yang sama dengan Laporan Hutang Vendor
        $totalHutangData = DB::table('proyek')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->join('penawaran_detail', 'penawaran.id_penawaran', '=', 'penawaran_detail.id_penawaran')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->join('vendor', 'barang.id_vendor', '=', 'vendor.id_vendor')
            ->leftJoin('kalkulasi_hps', function($join) {
                $join->on('proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
                     ->on('vendor.id_vendor', '=', 'kalkulasi_hps.id_vendor');
            })
            ->leftJoin('pembayaran', function($join) {
                $join->on('penawaran.id_penawaran', '=', 'pembayaran.id_penawaran')
                     ->on('vendor.id_vendor', '=', 'pembayaran.id_vendor')
                     ->where('pembayaran.status_verifikasi', '=', 'Approved');
            })
            ->where('penawaran.status', 'ACC')
            ->whereIn('proyek.status', ['Pembayaran', 'Pengiriman', 'Selesai'])
            ->select(
                DB::raw('SUM(COALESCE(kalkulasi_hps.total_harga_hpp, 0) - COALESCE(pembayaran.nominal_bayar, 0)) as total_hutang'),
                DB::raw('COUNT(DISTINCT vendor.id_vendor) as vendor_pending')
            )
            ->first();

        $totalHutang = $totalHutangData->total_hutang ?? 0;
        $vendorPending = $totalHutangData->vendor_pending ?? 0;        // Calculate total piutang (belum dibayar dari klien)
        // Using penagihan_dinas table - outstanding invoices
        $totalPiutang = DB::table('penagihan_dinas')
            ->leftJoin('bukti_pembayaran', 'penagihan_dinas.id', '=', 'bukti_pembayaran.penagihan_dinas_id')
            ->where('penagihan_dinas.status_pembayaran', '!=', 'lunas')
            ->sum(DB::raw('penagihan_dinas.total_harga - COALESCE(bukti_pembayaran.jumlah_bayar, 0)')) ?? 0;

        $dinasPending = DB::table('penagihan_dinas')
            ->where('status_pembayaran', '!=', 'lunas')
            ->count();

        return [
            'omset_bulan_ini' => $omsetBulanIni,
            'omset_growth' => round($omsetGrowth, 1),
            'proyek_aktif' => $proyekAktif,
            'proyek_baru' => $proyekBaru,
            'total_hutang' => $totalHutang,
            'vendor_pending' => $vendorPending,
            'total_piutang' => $totalPiutang,
            'dinas_pending' => $dinasPending
        ];
    }

    /**
     * Get monthly revenue data for chart
     */
    private function getMonthlyRevenue($year = null, $specificMonth = null)
    {
        $year = $year ?: Carbon::now()->year;
        $monthlyData = [];

        // If specific month is requested, only return that month's data
        if ($specificMonth) {
            $revenue = KalkulasiHps::whereHas('proyek', function($query) use ($specificMonth, $year) {
                    $query->whereNotIn('status', ['Gagal', 'Menunggu'])
                          ->whereMonth('created_at', $specificMonth)
                          ->whereYear('created_at', $year);
                })
                ->sum('nett_income') ?? 0;

            // Still return 12 months but highlight the selected month
            for ($month = 1; $month <= 12; $month++) {
                $monthlyData[] = [
                    'month' => $month,
                    'month_name' => Carbon::create($year, $month, 1)->format('M'),
                    'revenue' => $month == $specificMonth ? $revenue : 0
                ];
            }
        } else {
            // Return all months
            for ($month = 1; $month <= 12; $month++) {
                $revenue = KalkulasiHps::whereHas('proyek', function($query) use ($month, $year) {
                        $query->whereNotIn('status', ['Gagal', 'Menunggu'])
                              ->whereMonth('created_at', $month)
                              ->whereYear('created_at', $year);
                    })
                    ->sum('nett_income') ?? 0;

                $monthlyData[] = [
                    'month' => $month,
                    'month_name' => Carbon::create($year, $month, 1)->format('M'),
                    'revenue' => $revenue
                ];
            }
        }

        return $monthlyData;
    }

    /**
     * Get revenue leaderboard combining marketing and purchasing admins
     */
    private function getRevenuePerPerson()
    {
        // Get marketing admins
        $marketingAdmins = DB::table('users')
            ->select(
                'users.nama',
                'users.id_user',
                DB::raw('SUM(kalkulasi_hps.nett_income) as total_revenue'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as total_projects'),
                DB::raw("'Marketing' as role")
            )
            ->join('proyek', 'proyek.id_admin_marketing', '=', 'users.id_user')
            ->join('kalkulasi_hps', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->whereNotIn('proyek.status', ['Gagal', 'Menunggu'])
            ->whereYear('proyek.created_at', Carbon::now()->year)
            ->groupBy('users.id_user', 'users.nama')
            ->get();

        // Get purchasing admins
        $purchasingAdmins = DB::table('users')
            ->select(
                'users.nama',
                'users.id_user',
                DB::raw('SUM(kalkulasi_hps.nett_income) as total_revenue'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as total_projects'),
                DB::raw("'Purchasing' as role")
            )
            ->join('proyek', 'proyek.id_admin_purchasing', '=', 'users.id_user')
            ->join('kalkulasi_hps', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->whereNotIn('proyek.status', ['Gagal', 'Menunggu'])
            ->whereYear('proyek.created_at', Carbon::now()->year)
            ->groupBy('users.id_user', 'users.nama')
            ->get();

        // Combine and sort by revenue
        $combinedData = collect($marketingAdmins)->merge($purchasingAdmins)
            ->sortByDesc('total_revenue')
            ->take(10)
            ->values();

        return $combinedData;
    }

    /**
     * Get vendor debts data - menggunakan logika yang sama dengan Laporan Hutang Vendor
     */
    private function getVendorDebts()
    {
        // Get vendor debts berdasarkan kalkulasi HPS yang belum lunas
        $vendorDebts = DB::table('proyek')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->join('penawaran_detail', 'penawaran.id_penawaran', '=', 'penawaran_detail.id_penawaran')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->join('vendor', 'barang.id_vendor', '=', 'vendor.id_vendor')
            ->leftJoin('kalkulasi_hps', function($join) {
                $join->on('proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
                     ->on('vendor.id_vendor', '=', 'kalkulasi_hps.id_vendor');
            })
            ->leftJoin('pembayaran', function($join) {
                $join->on('penawaran.id_penawaran', '=', 'pembayaran.id_penawaran')
                     ->on('vendor.id_vendor', '=', 'pembayaran.id_vendor')
                     ->where('pembayaran.status_verifikasi', '=', 'Approved');
            })
            ->where('penawaran.status', 'ACC')
            ->whereIn('proyek.status', ['Pembayaran', 'Pengiriman', 'Selesai'])
            ->select(
                'vendor.nama_vendor',
                'barang.nama_barang',
                'proyek.kode_proyek',
                DB::raw('COALESCE(kalkulasi_hps.total_harga_hpp, 0) as total_vendor'),
                DB::raw('COALESCE(pembayaran.nominal_bayar, 0) as total_dibayar'),
                DB::raw('COALESCE(kalkulasi_hps.total_harga_hpp, 0) - COALESCE(pembayaran.nominal_bayar, 0) as total_hutang'),
                DB::raw('MAX(penawaran.tanggal_penawaran) as oldest_date')
            )
            ->groupBy('vendor.id_vendor', 'vendor.nama_vendor', 'barang.nama_barang', 'proyek.kode_proyek', 'kalkulasi_hps.total_harga_hpp', 'pembayaran.nominal_bayar')
            ->havingRaw('(COALESCE(kalkulasi_hps.total_harga_hpp, 0) - COALESCE(pembayaran.nominal_bayar, 0)) > 0')
            ->orderBy('total_hutang', 'desc')
            ->limit(4)
            ->get();

        // Jika tidak ada hutang vendor aktual, tampilkan data untuk display saja
        if ($vendorDebts->isEmpty()) {
            return DB::table('vendor')
                ->select(
                    'vendor.nama_vendor',
                    'barang.nama_barang',
                    'proyek.kode_proyek',
                    DB::raw('0 as total_vendor'),
                    DB::raw('0 as total_dibayar'),
                    DB::raw('0 as total_hutang'),
                    DB::raw('MAX(penawaran.tanggal_penawaran) as oldest_date')
                )
                ->join('barang', 'vendor.id_vendor', '=', 'barang.id_vendor')
                ->join('penawaran_detail', 'barang.id_barang', '=', 'penawaran_detail.id_barang')
                ->join('penawaran', 'penawaran_detail.id_penawaran', '=', 'penawaran.id_penawaran')
                ->join('proyek', 'penawaran.id_proyek', '=', 'proyek.id_proyek')
                ->whereNotIn('proyek.status', ['Gagal', 'Menunggu'])
                ->groupBy('vendor.nama_vendor', 'barang.nama_barang', 'proyek.kode_proyek')
                ->orderBy('oldest_date', 'desc')
                ->limit(4)
                ->get()
                ->map(function($item) {
                    // Untuk tampilan, tandai sebagai lunas
                    $item->days_overdue = 0;
                    $item->status = 'Lunas';
                    return $item;
                });
        }

        return $vendorDebts->map(function($item) {
            $daysOverdue = Carbon::parse($item->oldest_date)->diffInDays(Carbon::now());
            $item->days_overdue = $daysOverdue;
            $item->status = $daysOverdue > 30 ? 'overdue' : ($daysOverdue > 14 ? 'warning' : 'normal');
            return $item;
        });
    }

    /**
     * Get client receivables data
     */
    private function getClientReceivables()
    {
        return DB::table('penagihan_dinas')
            ->select(
                'proyek.instansi',
                'proyek.kode_proyek',
                'penagihan_dinas.nomor_invoice',
                'penagihan_dinas.total_harga',
                'penagihan_dinas.tanggal_jatuh_tempo',
                'penagihan_dinas.status_pembayaran',
                DB::raw('COALESCE(SUM(bukti_pembayaran.jumlah_bayar), 0) as total_dibayar')
            )
            ->join('proyek', 'penagihan_dinas.proyek_id', '=', 'proyek.id_proyek')
            ->leftJoin('bukti_pembayaran', 'penagihan_dinas.id', '=', 'bukti_pembayaran.penagihan_dinas_id')
            ->where('penagihan_dinas.status_pembayaran', '!=', 'lunas')
            ->groupBy('penagihan_dinas.id', 'proyek.instansi', 'proyek.kode_proyek',
                     'penagihan_dinas.nomor_invoice', 'penagihan_dinas.total_harga',
                     'penagihan_dinas.tanggal_jatuh_tempo', 'penagihan_dinas.status_pembayaran')
            ->orderBy('penagihan_dinas.tanggal_jatuh_tempo', 'asc')
            ->limit(4)
            ->get()
            ->map(function($item) {
                $item->sisa_piutang = $item->total_harga - $item->total_dibayar;
                $item->progress = $item->total_harga > 0 ? ($item->total_dibayar / $item->total_harga) * 100 : 0;
                $daysOverdue = Carbon::parse($item->tanggal_jatuh_tempo)->diffInDays(Carbon::now());
                $item->days_overdue = $daysOverdue;
                $item->status = $daysOverdue > 0 ? 'overdue' : 'pending';
                return $item;
            });
    }

    /**
     * Get geographic distribution of sales
     */
    private function getGeographicDistribution()
    {
        try {
            // Check if we have any projects at all
            $hasProjects = Proyek::count() > 0;

            if (!$hasProjects) {
                Log::info('No projects found in database');
                return collect([]);
            }

            // Use nett_income from kalkulasi_hps for consistency with omset report
            $wilayahData = DB::table('proyek')
                ->select(
                    DB::raw("TRIM(proyek.kab_kota) as city_name"),
                    DB::raw('SUM(kalkulasi_hps.nett_income) as total_sales'),
                    DB::raw('COUNT(DISTINCT proyek.id_proyek) as total_projects'),
                    DB::raw('AVG(kalkulasi_hps.nett_income) as avg_sales')
                )
                ->join('kalkulasi_hps', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                ->whereNotIn('proyek.status', ['Gagal', 'Menunggu'])
                ->whereNotNull('proyek.kab_kota')
                ->where('proyek.kab_kota', '!=', '')
                ->whereNotNull('kalkulasi_hps.nett_income')
                ->groupBy('proyek.kab_kota')
                ->orderBy('total_sales', 'desc')
                ->get();

            Log::info("Geographic data query successful using kab_kota column. Found " . $wilayahData->count() . " records");

        } catch (\Exception $e) {
            Log::error('Geographic data query failed: ' . $e->getMessage());
            Log::error('Error details: ' . $e->getTraceAsString());
            return collect([]);
        }

        return $wilayahData->map(function($item) {
            $cityName = trim($item->city_name);

            // Get coordinates using enhanced matching with collision avoidance
            $coordinates = $this->getCoordinatesWithCollisionAvoidance($cityName);

            // Determine level based on sales
            $level = 'low';
            if ($item->total_sales > 100000000) $level = 'very-high';
            elseif ($item->total_sales > 50000000) $level = 'high';
            elseif ($item->total_sales > 20000000) $level = 'medium';

            // Calculate growth percentage (compare with average)
            $avgSales = $item->avg_sales ?? 0;
            $currentSales = $item->total_sales ?? 0;
            $growth = $avgSales > 0 ? (($currentSales - $avgSales) / $avgSales) * 100 : 0;
            $growth = max(0, min(50, abs($growth))); // Limit between 0-50%

            return [
                'name' => $cityName,
                'coordinates' => $coordinates['coords'],
                'sales' => round($item->total_sales / 1000000, 1), // Convert to millions
                'projects' => $item->total_projects,
                'growth' => round($growth, 1),
                'level' => $level,
                'population' => $coordinates['population'],
                'area' => $coordinates['area']
            ];
        });
    }

    /**
     * Get coordinates for a city with collision avoidance and geocoding
     */
    private function getCoordinatesWithCollisionAvoidance($cityName)
    {
        // Enhanced coordinates database with collision-aware positioning
        $coordinatesDatabase = [
            // DKI Jakarta area with offset positioning to avoid overlap
            'Jakarta' => [-6.2088, 106.8456, '10.6 Juta', 'DKI Jakarta'],
            'Jakarta Pusat' => [-6.1805, 106.8284, '0.9 Juta', 'DKI Jakarta'],
            'Jakarta Selatan' => [-6.2615, 106.8106, '2.3 Juta', 'DKI Jakarta'],
            'Jakarta Utara' => [-6.1344, 106.8827, '1.8 Juta', 'DKI Jakarta'],
            'Jakarta Barat' => [-6.1352, 106.7733, '2.5 Juta', 'DKI Jakarta'],
            'Jakarta Timur' => [-6.2250, 106.9004, '2.8 Juta', 'DKI Jakarta'],

            // Jawa Barat with spread positioning
            'Bandung' => [-6.9175, 107.6191, '2.5 Juta', 'Jawa Barat'],
            'Bogor' => [-6.5971, 106.8060, '1.1 Juta', 'Jawa Barat'],
            'Depok' => [-6.4025, 106.7942, '2.3 Juta', 'Jawa Barat'],
            'Bekasi' => [-6.2383, 107.0025, '2.5 Juta', 'Jawa Barat'],
            'Cimahi' => [-6.8722, 107.5419, '0.6 Juta', 'Jawa Barat'],
            'Cirebon' => [-6.7063, 108.5570, '0.3 Juta', 'Jawa Barat'],
            'Karawang' => [-6.3015, 107.3071, '2.3 Juta', 'Jawa Barat'],

            // Banten with proper spacing
            'Tangerang' => [-6.1783, 106.6319, '2.2 Juta', 'Banten'],
            'Tangerang Selatan' => [-6.2875, 106.7137, '1.5 Juta', 'Banten'],
            'Serang' => [-6.1200, 106.1500, '0.6 Juta', 'Banten'],

            // Jawa Timur with spread
            'Surabaya' => [-7.2575, 112.7521, '2.9 Juta', 'Jawa Timur'],
            'Malang' => [-7.9666, 112.6326, '0.9 Juta', 'Jawa Timur'],
            'Sidoarjo' => [-7.4471, 112.7186, '2.3 Juta', 'Jawa Timur'],
            'Gresik' => [-7.1564, 112.6539, '1.3 Juta', 'Jawa Timur'],
            'Mojokerto' => [-7.4664, 112.4336, '0.3 Juta', 'Jawa Timur'],
            'Kediri' => [-7.8166, 112.0178, '0.3 Juta', 'Jawa Timur'],

            // Jawa Tengah
            'Semarang' => [-6.9667, 110.4167, '1.8 Juta', 'Jawa Tengah'],
            'Solo' => [-7.5755, 110.8243, '0.5 Juta', 'Jawa Tengah'],
            'Salatiga' => [-7.3318, 110.5074, '0.2 Juta', 'Jawa Tengah'],
            'Magelang' => [-7.4697, 110.2175, '0.1 Juta', 'Jawa Tengah'],

            // DI Yogyakarta with spread
            'Yogyakarta' => [-7.7956, 110.3695, '0.4 Juta', 'DI Yogyakarta'],
            'Bantul' => [-7.8887, 110.3297, '1.0 Juta', 'DI Yogyakarta'],
            'Sleman' => [-7.7326, 110.3467, '1.2 Juta', 'DI Yogyakarta'],

            // Other major cities
            'Medan' => [3.5952, 98.6722, '2.4 Juta', 'Sumatera Utara'],
            'Makassar' => [-5.1477, 119.4327, '1.5 Juta', 'Sulawesi Selatan'],
            'Palembang' => [-2.9761, 104.7754, '1.7 Juta', 'Sumatera Selatan'],
            'Balikpapan' => [-1.2379, 116.8529, '0.7 Juta', 'Kalimantan Timur'],
            'Denpasar' => [-8.6705, 115.2126, '0.9 Juta', 'Bali'],
            'Pontianak' => [-0.0263, 109.3425, '0.7 Juta', 'Kalimantan Barat'],
            'Manado' => [1.4748, 124.8421, '0.5 Juta', 'Sulawesi Utara'],
            'Binjai' => [3.6000, 98.4854, '0.3 Juta', 'Sumatera Utara'],
            'Badung' => [-8.5503, 115.1758, '0.6 Juta', 'Bali'],
            'Gowa' => [-5.2111, 119.4414, '0.7 Juta', 'Sulawesi Selatan'],
            'Deli Serdang' => [3.4310, 98.6951, '1.9 Juta', 'Sumatera Utara']
        ];

        // First, try exact match
        if (isset($coordinatesDatabase[$cityName])) {
            $coords = $coordinatesDatabase[$cityName];
            return [
                'coords' => [$coords[0], $coords[1]],
                'population' => $coords[2],
                'area' => $coords[3]
            ];
        }

        // Try partial match with fuzzy matching for better city recognition
        $bestMatch = null;
        $bestScore = 0;

        foreach ($coordinatesDatabase as $key => $coords) {
            // Calculate similarity score
            $score = 0;

            // Exact substring match gets highest priority
            if (stripos($cityName, $key) !== false || stripos($key, $cityName) !== false) {
                $score = 90;
            }
            // Levenshtein distance for fuzzy matching
            else {
                $distance = levenshtein(strtolower($cityName), strtolower($key));
                $maxLen = max(strlen($cityName), strlen($key));
                $score = $maxLen > 0 ? (1 - $distance / $maxLen) * 100 : 0;
            }

            if ($score > $bestScore && $score > 60) { // Minimum 60% similarity
                $bestScore = $score;
                $bestMatch = $coords;
            }
        }

        if ($bestMatch) {
            return [
                'coords' => [$bestMatch[0], $bestMatch[1]],
                'population' => $bestMatch[2],
                'area' => $bestMatch[3]
            ];
        }

        // Fallback: Use Nominatim geocoding API for unknown cities
        try {
            $geocodedCoords = $this->geocodeCity($cityName);
            if ($geocodedCoords) {
                return [
                    'coords' => $geocodedCoords,
                    'population' => 'Data tidak tersedia',
                    'area' => 'Indonesia'
                ];
            }
        } catch (\Exception $e) {
            Log::warning("Geocoding failed for city: $cityName. Error: " . $e->getMessage());
        }

        // Final fallback: Default Indonesia center with small random offset to avoid overlap
        $baseOffset = crc32($cityName) % 1000; // Generate consistent offset based on city name
        $latOffset = (($baseOffset % 100) - 50) * 0.01; // ±0.5 degrees
        $lngOffset = ((($baseOffset / 100) % 100) - 50) * 0.01; // ±0.5 degrees

        return [
            'coords' => [-2.5 + $latOffset, 118 + $lngOffset],
            'population' => '0.1 Juta',
            'area' => 'Indonesia'
        ];
    }

    /**
     * Geocode city name using Nominatim API (OpenStreetMap)
     */
    private function geocodeCity($cityName)
    {
        try {
            $url = 'https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&city=' . urlencode($cityName) . '&limit=1';

            $context = stream_context_create([
                'http' => [
                    'timeout' => 3, // Short timeout for performance
                    'user_agent' => 'Cyber KATANA Dashboard/1.0'
                ]
            ]);

            $response = file_get_contents($url, false, $context);

            if ($response) {
                $data = json_decode($response, true);
                if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                    return [(float)$data[0]['lat'], (float)$data[0]['lon']];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Geocoding API call failed for '$cityName': " . $e->getMessage());
        }

        return null;
    }

    /**
     * Get geographic statistics for map display
     */
    private function getGeographicStats($geographicData)
    {
        if ($geographicData->isEmpty()) {
            return [
                'total_cities' => 0,
                'total_sales' => 0,
                'top_cities' => [],
                'others_sales' => 0
            ];
        }

        $totalSales = $geographicData->sum('sales');
        $topCities = $geographicData->take(4);
        $othersSales = $geographicData->skip(4)->sum('sales');

        return [
            'total_cities' => $geographicData->count(),
            'total_sales' => round($totalSales, 1),
            'top_cities' => $topCities,
            'others_sales' => round($othersSales, 1)
        ];
    }

    /**
     * Get debt age analysis for clients based on invoice due dates
     */
    private function getDebtAgeAnalysis()
    {
        // Get all outstanding invoices with their age in days
        $outstandingInvoices = DB::table('penagihan_dinas')
            ->select(
                'proyek.instansi',
                'proyek.kode_proyek',
                'penagihan_dinas.nomor_invoice',
                'penagihan_dinas.total_harga',
                'penagihan_dinas.tanggal_jatuh_tempo',
                'penagihan_dinas.status_pembayaran',
                DB::raw('COALESCE(SUM(bukti_pembayaran.jumlah_bayar), 0) as total_dibayar'),
                DB::raw('DATEDIFF(CURDATE(), penagihan_dinas.tanggal_jatuh_tempo) as days_overdue')
            )
            ->join('proyek', 'penagihan_dinas.proyek_id', '=', 'proyek.id_proyek')
            ->leftJoin('bukti_pembayaran', 'penagihan_dinas.id', '=', 'bukti_pembayaran.penagihan_dinas_id')
            ->where('penagihan_dinas.status_pembayaran', '!=', 'lunas')
            ->groupBy('penagihan_dinas.id', 'proyek.instansi', 'proyek.kode_proyek',
                     'penagihan_dinas.nomor_invoice', 'penagihan_dinas.total_harga',
                     'penagihan_dinas.tanggal_jatuh_tempo', 'penagihan_dinas.status_pembayaran')
            ->having(DB::raw('(penagihan_dinas.total_harga - COALESCE(SUM(bukti_pembayaran.jumlah_bayar), 0))'), '>', 0)
            ->orderBy('days_overdue', 'desc')
            ->limit(8)
            ->get();

        return $outstandingInvoices->map(function($item) {
            // Calculate outstanding amount
            $item->outstanding_amount = $item->total_harga - $item->total_dibayar;

            // Determine age category and color
            if ($item->days_overdue <= 30) {
                $item->age_category = '0-30 hari';
                $item->color_class = 'green';
                $item->status_text = 'Baik';
            } elseif ($item->days_overdue <= 60) {
                $item->age_category = '30-60 hari';
                $item->color_class = 'yellow';
                $item->status_text = 'Perhatian';
            } elseif ($item->days_overdue <= 90) {
                $item->age_category = '60-90 hari';
                $item->color_class = 'orange';
                $item->status_text = 'Waspada';
            } elseif ($item->days_overdue <= 120) {
                $item->age_category = '90-120 hari';
                $item->color_class = 'red';
                $item->status_text = 'Buruk';
            } elseif ($item->days_overdue <= 150) {
                $item->age_category = '120-150 hari';
                $item->color_class = 'red';
                $item->status_text = 'Sangat Buruk';
            } else {
                $item->age_category = '>150 hari';
                $item->color_class = 'red';
                $item->status_text = 'Kritis';
            }

            return $item;
        });
    }

    /**
     * API endpoint for real-time dashboard updates
     */
    public function getRealtimeData()
    {
        $stats = $this->getDashboardStats();

        return response()->json([
            'success' => true,
            'data' => $stats,
            'timestamp' => Carbon::now()->toISOString()
        ]);
    }

    /**
     * API endpoint for chart data
     */
    public function getChartData(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month'); // Optional month filter

        $monthlyRevenue = $this->getMonthlyRevenue($year, $month);
        $revenuePerPerson = $this->getRevenuePerPerson();

        return response()->json([
            'success' => true,
            'monthlyRevenue' => $monthlyRevenue,
            'revenuePerPerson' => $revenuePerPerson
        ]);
    }
}
