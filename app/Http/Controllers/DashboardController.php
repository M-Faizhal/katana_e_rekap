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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        // Get basic statistics
        $stats = $this->getDashboardStats();

        // Get monthly revenue data
        $monthlyRevenue = $this->getMonthlyRevenue();

        // Get revenue per admin marketing
        $revenuePerPerson = $this->getRevenuePerPerson();

        // Get vendor debts (hutang)
        $vendorDebts = $this->getVendorDebts();

        // Get client receivables (piutang)
        $clientReceivables = $this->getClientReceivables();

        // Get geographic distribution
        $geographicData = $this->getGeographicDistribution();

        return view('pages.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'revenuePerPerson',
            'vendorDebts',
            'clientReceivables',
            'geographicData'
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

        // Calculate omset bulan ini (revenue this month)
        $omsetBulanIni = Proyek::whereIn('status', ['selesai', 'pengiriman'])
            ->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->sum('harga_total') ?? 0;

        // Calculate omset bulan lalu untuk perbandingan
        $omsetBulanLalu = Proyek::whereIn('status', ['selesai', 'pengiriman'])
            ->whereMonth('updated_at', $lastMonth->month)
            ->whereYear('updated_at', $lastMonth->year)
            ->sum('harga_total') ?? 0;

        // Calculate growth percentage
        $omsetGrowth = $omsetBulanLalu > 0 ?
            (($omsetBulanIni - $omsetBulanLalu) / $omsetBulanLalu) * 100 :
            ($omsetBulanIni > 0 ? 100 : 0);

        // Count active projects
        $proyekAktif = Proyek::whereNotIn('status', ['selesai', 'gagal'])->count();
        $proyekBaru = Proyek::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Calculate total hutang (belum dibayar ke vendor)
        // Based on penawaran where project status requires payment but no payment exists
        $totalHutang = DB::table('penawaran')
            ->join('proyek', 'penawaran.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('pembayaran', 'penawaran.id_penawaran', '=', 'pembayaran.id_penawaran')
            ->whereIn('proyek.status', ['pembayaran', 'pengiriman'])
            ->whereNull('pembayaran.id_pembayaran')
            ->sum('penawaran.total_nilai') ?? 0;

        $vendorPending = DB::table('penawaran')
            ->join('proyek', 'penawaran.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('pembayaran', 'penawaran.id_penawaran', '=', 'pembayaran.id_penawaran')
            ->whereIn('proyek.status', ['pembayaran', 'pengiriman'])
            ->whereNull('pembayaran.id_pembayaran')
            ->distinct('penawaran.id_penawaran')
            ->count();

        // Calculate total piutang (belum dibayar dari klien)
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
    private function getMonthlyRevenue()
    {
        $currentYear = Carbon::now()->year;
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $revenue = Proyek::whereIn('status', ['selesai', 'pengiriman'])
                ->whereMonth('updated_at', $month)
                ->whereYear('updated_at', $currentYear)
                ->sum('harga_total') ?? 0;

            $monthlyData[] = [
                'month' => $month,
                'month_name' => Carbon::create($currentYear, $month, 1)->format('M'),
                'revenue' => $revenue
            ];
        }

        return $monthlyData;
    }

    /**
     * Get revenue per admin marketing
     */
    private function getRevenuePerPerson()
    {
        return Proyek::select(
                'users.nama',
                'users.id_user',
                DB::raw('SUM(proyek.harga_total) as total_revenue'),
                DB::raw('COUNT(proyek.id_proyek) as total_projects')
            )
            ->join('users', 'proyek.id_admin_marketing', '=', 'users.id_user')
            ->whereIn('proyek.status', ['selesai', 'pengiriman'])
            ->groupBy('users.id_user', 'users.nama')
            ->orderBy('total_revenue', 'desc')
            ->limit(4)
            ->get();
    }

    /**
     * Get vendor debts data
     */
    private function getVendorDebts()
    {
        return DB::table('penawaran')
            ->select(
                'penawaran_detail.id_barang',
                'barang.nama_barang',
                'vendor.nama_vendor',
                'vendor.id_vendor',
                DB::raw('SUM(penawaran_detail.subtotal) as total_hutang'),
                DB::raw('MIN(penawaran.tanggal_penawaran) as oldest_date'),
                DB::raw('COUNT(DISTINCT penawaran.id_penawaran) as total_penawaran')
            )
            ->join('penawaran_detail', 'penawaran.id_penawaran', '=', 'penawaran_detail.id_penawaran')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->join('vendor', 'barang.id_vendor', '=', 'vendor.id_vendor')
            ->join('proyek', 'penawaran.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('pembayaran', 'penawaran.id_penawaran', '=', 'pembayaran.id_penawaran')
            ->whereNull('pembayaran.id_pembayaran')
            ->whereIn('proyek.status', ['pembayaran', 'pengiriman'])
            ->groupBy('vendor.id_vendor', 'vendor.nama_vendor', 'penawaran_detail.id_barang', 'barang.nama_barang')
            ->orderBy('total_hutang', 'desc')
            ->limit(4)
            ->get()
            ->map(function($item) {
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
        $wilayahData = Proyek::select(
                'proyek.kab_kota',
                DB::raw('SUM(proyek.harga_total) as total_sales'),
                DB::raw('COUNT(proyek.id_proyek) as total_projects')
            )
            ->whereIn('proyek.status', ['selesai', 'pengiriman'])
            ->whereNotNull('proyek.kab_kota')
            ->groupBy('proyek.kab_kota')
            ->orderBy('total_sales', 'desc')
            ->get();

        // Map to coordinates (simplified mapping)
        $coordinates = [
            'Jakarta' => [-6.2088, 106.8456],
            'Surabaya' => [-7.2575, 112.7521],
            'Medan' => [3.5952, 98.6722],
            'Bandung' => [-6.9175, 107.6191],
            'Makassar' => [-5.1477, 119.4327],
            'Palembang' => [-2.9761, 104.7754],
            'Semarang' => [-6.9667, 110.4167],
            'Balikpapan' => [-1.2379, 116.8529],
            'Jayapura' => [-2.5489, 140.7197]
        ];

        return $wilayahData->map(function($item) use ($coordinates) {
            $cityKey = ucfirst(strtolower($item->kab_kota));
            $position = $coordinates[$cityKey] ?? [-2.5, 118]; // Default to center of Indonesia

            // Determine level based on sales
            $level = 'low';
            if ($item->total_sales > 100000000) $level = 'very-high';
            elseif ($item->total_sales > 50000000) $level = 'high';
            elseif ($item->total_sales > 20000000) $level = 'medium';

            return [
                'name' => $item->kab_kota,
                'position' => $position,
                'sales' => $item->total_sales / 1000000, // Convert to millions
                'projects' => $item->total_projects,
                'growth' => rand(3, 25), // Placeholder for growth calculation
                'level' => $level
            ];
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
    public function getChartData()
    {
        $monthlyRevenue = $this->getMonthlyRevenue();
        $revenuePerPerson = $this->getRevenuePerPerson();

        return response()->json([
            'success' => true,
            'data' => [
                'monthly_revenue' => $monthlyRevenue,
                'revenue_per_person' => $revenuePerPerson
            ]
        ]);
    }
}
