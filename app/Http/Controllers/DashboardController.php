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

        // Get hutang vendor statistics (using same logic as laporan)
        $hutangVendorStats = $this->getHutangVendorStats();

        // Get piutang dinas statistics (using same logic as laporan)
        $piutangDinasStats = $this->getPiutangDinasStats();

        // Override hutang statistics with more accurate calculation
        $stats['total_hutang'] = $hutangVendorStats['total_hutang'];
        $stats['jumlah_vendor_hutang'] = $hutangVendorStats['jumlah_vendor'];
        $stats['rata_rata_hutang'] = $hutangVendorStats['rata_rata_hutang'];

        // Override piutang statistics with more accurate calculation
        $stats['total_piutang'] = $piutangDinasStats['total_piutang'];
        $stats['piutang_jatuh_tempo'] = $piutangDinasStats['piutang_jatuh_tempo'];
        $stats['jumlah_proyek_piutang'] = $piutangDinasStats['jumlah_proyek'];
        $stats['rata_rata_piutang'] = $piutangDinasStats['rata_rata_piutang'];

        // Add formatted versions for display (same as omset/laporan report)
        $stats['omset_bulan_ini_formatted'] = $this->formatRupiah($stats['omset_bulan_ini']);
        $stats['total_hutang_formatted'] = $hutangVendorStats['total_hutang_formatted'];
        $stats['rata_rata_hutang_formatted'] = $hutangVendorStats['rata_rata_hutang_formatted'];
        $stats['total_piutang_formatted'] = $piutangDinasStats['total_piutang_formatted'];
        $stats['piutang_jatuh_tempo_formatted'] = $piutangDinasStats['piutang_jatuh_tempo_formatted'];
        $stats['rata_rata_piutang_formatted'] = $piutangDinasStats['rata_rata_piutang_formatted'];

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

        // Get year range from project data (same as laporan)
        $yearRange = $this->getYearRange();

        return view('pages.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'revenuePerPerson',
            'vendorDebts',
            'clientReceivables',
            'geographicData',
            'geographicStats',
            'debtAgeAnalysis',
            'yearRange'
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

        // Calculate omset bulan ini (revenue this month) - using same method as LaporanController
        // Using harga_total from proyek table and tanggal field for completed projects only
        $omsetBulanIni = DB::table('proyek')
            ->where('status', 'Selesai')
            ->whereNotNull('harga_total')
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('harga_total') ?? 0;

        // Calculate omset bulan lalu untuk perbandingan
        $omsetBulanLalu = DB::table('proyek')
            ->where('status', 'Selesai')
            ->whereNotNull('harga_total')
            ->whereMonth('tanggal', $lastMonth->month)
            ->whereYear('tanggal', $lastMonth->year)
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

        // Calculate total hutang (belum dibayar ke vendor) - menggunakan logika yang sama dengan LaporanController
        $hutangVendorData = DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('penawaran_detail', 'penawaran.id_penawaran', '=', 'penawaran_detail.id_penawaran')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->join('vendor', 'barang.id_vendor', '=', 'vendor.id_vendor')
            ->leftJoin('kalkulasi_hps', function($join) {
                $join->on('proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
                     ->on('vendor.id_vendor', '=', 'kalkulasi_hps.id_vendor');
            })
            ->leftJoin(DB::raw('(SELECT
                p.id_vendor,
                pn.id_proyek,
                COALESCE(SUM(CASE WHEN p.status_verifikasi = "Approved" THEN p.nominal_bayar ELSE 0 END), 0) as total_dibayar_approved
                FROM pembayaran p
                JOIN penawaran pn ON p.id_penawaran = pn.id_penawaran
                GROUP BY p.id_vendor, pn.id_proyek
            ) as pb'), function($join) {
                $join->on('vendor.id_vendor', '=', 'pb.id_vendor')
                     ->on('proyek.id_proyek', '=', 'pb.id_proyek');
            })
            ->whereIn('proyek.status', ['Pembayaran', 'Pengiriman', 'Selesai'])
            ->where('penawaran.status', 'ACC')
            ->select([
                'proyek.id_proyek',
                'vendor.id_vendor',
                DB::raw('COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) as total_vendor'),
                DB::raw('COALESCE(MAX(pb.total_dibayar_approved), 0) as total_dibayar_approved'),
                DB::raw('CASE
                    WHEN COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) = 0 THEN "Data kalkulasi HPS belum diisi"
                    ELSE NULL
                END as warning_hps')
            ])
            ->groupBy(['proyek.id_proyek', 'vendor.id_vendor'])
            ->havingRaw('(COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) - COALESCE(MAX(pb.total_dibayar_approved), 0) > 0) OR warning_hps IS NOT NULL')
            ->get();

        $totalHutang = 0;
        $vendorPending = 0;

        foreach ($hutangVendorData as $data) {
            $sisaBayar = $data->total_vendor - $data->total_dibayar_approved;
            if ($sisaBayar > 0 || $data->warning_hps) {
                $totalHutang += $sisaBayar;
                $vendorPending++;
            }
        }        // Calculate total piutang (belum dibayar dari klien)
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
            $revenue = DB::table('proyek')
                ->where('status', 'Selesai')
                ->whereNotNull('harga_total')
                ->whereMonth('tanggal', $specificMonth)
                ->whereYear('tanggal', $year)
                ->sum('harga_total') ?? 0;

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
                $revenue = DB::table('proyek')
                    ->where('status', 'Selesai')
                    ->whereNotNull('harga_total')
                    ->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year)
                    ->sum('harga_total') ?? 0;

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
     * Get revenue leaderboard for marketing admins only
     */
    private function getRevenuePerPerson()
    {
        // Get marketing admins only - using same method as LaporanController
        $marketingAdmins = DB::table('users')
            ->select(
                'users.nama',
                'users.id_user',
                DB::raw('SUM(proyek.harga_total) as total_revenue'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as total_projects'),
                DB::raw("'Marketing' as role")
            )
            ->join('proyek', 'proyek.id_admin_marketing', '=', 'users.id_user')
            ->where('proyek.status', 'Selesai')
            ->whereNotNull('proyek.harga_total')
            ->whereYear('proyek.tanggal', Carbon::now()->year)
            ->groupBy('users.id_user', 'users.nama')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        return $marketingAdmins;
    }

    /**
     * Get hutang vendor statistics for dashboard
     */
    private function getHutangVendorStats()
    {
        // Query untuk mendapatkan semua hutang vendor (konsisten dengan LaporanController)
        $hutangVendorData = DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('penawaran_detail', 'penawaran.id_penawaran', '=', 'penawaran_detail.id_penawaran')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->join('vendor', 'barang.id_vendor', '=', 'vendor.id_vendor')
            ->leftJoin('kalkulasi_hps', function($join) {
                $join->on('proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
                     ->on('vendor.id_vendor', '=', 'kalkulasi_hps.id_vendor');
            })
            ->leftJoin(DB::raw('(SELECT
                p.id_vendor,
                pn.id_proyek,
                COALESCE(SUM(CASE WHEN p.status_verifikasi = "Approved" THEN p.nominal_bayar ELSE 0 END), 0) as total_dibayar_approved
                FROM pembayaran p
                JOIN penawaran pn ON p.id_penawaran = pn.id_penawaran
                GROUP BY p.id_vendor, pn.id_proyek
            ) as pb'), function($join) {
                $join->on('vendor.id_vendor', '=', 'pb.id_vendor')
                     ->on('proyek.id_proyek', '=', 'pb.id_proyek');
            })
            ->whereIn('proyek.status', ['Pembayaran', 'Pengiriman', 'Selesai'])
            ->where('penawaran.status', 'ACC')
            ->select([
                'proyek.id_proyek',
                'vendor.id_vendor',
                DB::raw('COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) as total_vendor'),
                DB::raw('COALESCE(MAX(pb.total_dibayar_approved), 0) as total_dibayar_approved'),
                DB::raw('CASE
                    WHEN COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) = 0 THEN 1
                    ELSE 0
                END as warning_hps')
            ])
            ->groupBy(['proyek.id_proyek', 'vendor.id_vendor'])
            ->havingRaw('(COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) - COALESCE(MAX(pb.total_dibayar_approved), 0) > 0) OR warning_hps = 1')
            ->get();

        $totalHutang = 0;
        $jumlahVendor = 0;

        foreach ($hutangVendorData as $data) {
            $sisaBayar = $data->total_vendor - $data->total_dibayar_approved;
            if ($sisaBayar > 0 || $data->warning_hps) {
                $totalHutang += $sisaBayar;
                $jumlahVendor++;
            }
        }

        $rataRataHutang = $jumlahVendor > 0 ? $totalHutang / $jumlahVendor : 0;

        return [
            'total_hutang' => $totalHutang,
            'jumlah_vendor' => $jumlahVendor,
            'rata_rata_hutang' => $rataRataHutang,
            'total_hutang_formatted' => $this->formatRupiah($totalHutang),
            'rata_rata_hutang_formatted' => $this->formatRupiah($rataRataHutang),
        ];
    }

    /**
     * Get vendor debts data - menggunakan logika yang sama dengan Laporan Hutang Vendor
     */
    private function getVendorDebts()
    {
        // Query langsung dengan join untuk menghindari N+1 queries
        // Menggunakan logika yang sama dengan LaporanController
        $vendorDebts = DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('penawaran_detail', 'penawaran.id_penawaran', '=', 'penawaran_detail.id_penawaran')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->leftJoin('vendor', 'barang.id_vendor', '=', 'vendor.id_vendor')
            ->leftJoin('kalkulasi_hps', function($join) {
                $join->on('proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
                     ->on('vendor.id_vendor', '=', 'kalkulasi_hps.id_vendor');
            })
            ->leftJoin(DB::raw('(SELECT
                p.id_vendor,
                pn.id_proyek,
                COALESCE(SUM(CASE WHEN p.status_verifikasi = "Approved" THEN p.nominal_bayar ELSE 0 END), 0) as total_dibayar_approved
                FROM pembayaran p
                JOIN penawaran pn ON p.id_penawaran = pn.id_penawaran
                GROUP BY p.id_vendor, pn.id_proyek
            ) as pb'), function($join) {
                $join->on('vendor.id_vendor', '=', 'pb.id_vendor')
                     ->on('proyek.id_proyek', '=', 'pb.id_proyek');
            })
            ->whereIn('proyek.status', ['Pembayaran', 'Pengiriman', 'Selesai'])
            ->where('penawaran.status', 'ACC')
            ->select([
                'proyek.id_proyek',
                'proyek.kode_proyek',
                'proyek.nama_klien',
                'proyek.instansi',
                'vendor.id_vendor',
                DB::raw('COALESCE(vendor.nama_vendor, "Vendor Tidak Ditemukan") as nama_vendor'),
                DB::raw('vendor.jenis_perusahaan as jenis_perusahaan'),
                DB::raw('vendor.email as email'),
                DB::raw('COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) as total_vendor'),
                DB::raw('COALESCE(MAX(pb.total_dibayar_approved), 0) as total_dibayar_approved'),
                DB::raw('CASE
                    WHEN COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) = 0 THEN "Data kalkulasi HPS belum diisi"
                    ELSE NULL
                END as warning_hps'),
                DB::raw('MAX(penawaran.tanggal_penawaran) as oldest_date')
            ])
            ->groupBy([
                'proyek.id_proyek', 'proyek.kode_proyek', 'proyek.nama_klien', 'proyek.instansi',
                'vendor.id_vendor', 'vendor.nama_vendor', 'vendor.jenis_perusahaan', 'vendor.email'
            ])
            ->havingRaw('(COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) - COALESCE(MAX(pb.total_dibayar_approved), 0) > 0) OR warning_hps IS NOT NULL')
            ->orderByRaw('COALESCE(SUM(kalkulasi_hps.total_harga_hpp), 0) - COALESCE(MAX(pb.total_dibayar_approved), 0) DESC')
            ->limit(4)
            ->get();

        // Jika tidak ada hutang vendor aktual, tampilkan data untuk display saja
        if ($vendorDebts->isEmpty()) {
            return collect([
                (object) [
                    'nama_vendor' => 'Tidak ada hutang vendor',
                    'jenis_perusahaan' => null,
                    'kode_proyek' => '-',
                    'instansi' => '-',
                    'total_vendor' => 0,
                    'total_dibayar_approved' => 0,
                    'sisa_bayar' => 0,
                    'persen_bayar' => 100,
                    'warning_hps' => null,
                    'status_lunas' => true,
                    'status' => 'Lunas',
                    'days_overdue' => 0
                ]
            ]);
        }

        return $vendorDebts->map(function($item) {
            $sisaBayar = $item->total_vendor - $item->total_dibayar_approved;
            $persenBayar = $item->total_vendor > 0 ? ($item->total_dibayar_approved / $item->total_vendor) * 100 : 0;
            $statusLunas = $item->total_vendor > 0 ? $sisaBayar <= 0 : false;

            // Calculate days overdue
            $daysOverdue = $item->oldest_date ? Carbon::parse($item->oldest_date)->diffInDays(Carbon::now()) : 0;

            // Determine status
            if ($item->warning_hps) {
                $status = 'warning'; // HPS belum diisi
            } elseif ($statusLunas) {
                $status = 'Lunas';
            } elseif ($daysOverdue > 30) {
                $status = 'overdue';
            } elseif ($daysOverdue > 14) {
                $status = 'warning';
            } else {
                $status = 'normal';
            }

            return (object) [
                'nama_vendor' => $item->nama_vendor,
                'kode_proyek' => $item->kode_proyek,
                'instansi' => $item->instansi,
                'nama_klien' => $item->nama_klien,
                'jenis_perusahaan' => $item->jenis_perusahaan,
                'email' => $item->email,
                'total_vendor' => $item->total_vendor,
                'total_dibayar_approved' => $item->total_dibayar_approved,
                'sisa_bayar' => $sisaBayar,
                'persen_bayar' => $persenBayar,
                'warning_hps' => $item->warning_hps,
                'status_lunas' => $statusLunas,
                'status' => $status,
                'days_overdue' => $daysOverdue,
                'oldest_date' => $item->oldest_date
            ];
        });
    }

    /**
     * Get piutang dinas statistics for dashboard
     */
    private function getPiutangDinasStats()
    {
        // Ambil semua proyek yang sudah di ACC dan hitung piutangnya (same logic as LaporanController)
        $proyekAcc = Proyek::with(['semuaPenawaran' => function($query) {
            $query->where('status', 'ACC');
        }, 'penagihanDinas.buktiPembayaran'])
        ->whereHas('semuaPenawaran', function($query) {
            $query->where('status', 'ACC');
        })
        ->get();

        $totalPiutang = 0;
        $piutangJatuhTempo = 0;
        $jumlahProyek = 0;

        foreach ($proyekAcc as $proyek) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
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
            'total_piutang_formatted' => $this->formatRupiah($totalPiutang),
            'piutang_jatuh_tempo_formatted' => $this->formatRupiah($piutangJatuhTempo),
            'rata_rata_piutang_formatted' => $this->formatRupiah($rataRataPiutang),
        ];
    }

    /**
     * Get client receivables data - updated to match LaporanController logic
     */
    private function getClientReceivables()
    {
        // Ambil semua proyek yang sudah di ACC (same logic as LaporanController)
        $proyekAcc = Proyek::with(['semuaPenawaran' => function($query) {
            $query->where('status', 'ACC');
        }, 'penagihanDinas.buktiPembayaran'])
        ->whereHas('semuaPenawaran', function($query) {
            $query->where('status', 'ACC');
        })
        ->get();

        // Transform ke format yang dibutuhkan untuk dashboard
        $piutangList = collect();

        foreach ($proyekAcc as $proyek) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
                $penagihan = $proyek->penagihanDinas->where('penawaran_id', $penawaran->id_penawaran)->first();

                $shouldInclude = false;
                $sisaPembayaran = 0;
                $status = '';
                $tanggalJatuhTempo = null;
                $nomorInvoice = '';
                $totalBayar = 0;

                if (!$penagihan) {
                    // Belum ada penagihan sama sekali
                    $shouldInclude = true;
                    $sisaPembayaran = $penawaran->total_penawaran ?? 0;
                    $status = 'belum_ditagih';
                    $tanggalJatuhTempo = now()->addDays(30); // Default 30 hari dari sekarang
                    $nomorInvoice = 'Belum Ditagih';
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
                }

                if ($shouldInclude) {
                    // Calculate progress and overdue status
                    $totalHarga = $penawaran->total_penawaran ?? 0;
                    $progress = $totalHarga > 0 ? ($totalBayar / $totalHarga) * 100 : 0;
                    $daysOverdue = $tanggalJatuhTempo && $tanggalJatuhTempo < now() ? now()->diffInDays($tanggalJatuhTempo) : 0;
                    $statusColor = $daysOverdue > 0 ? 'overdue' : 'pending';

                    // Create receivable item matching dashboard format
                    $piutangItem = (object)[
                        'instansi' => $proyek->instansi,
                        'kode_proyek' => $proyek->kode_proyek,
                        'nomor_invoice' => $nomorInvoice,
                        'total_harga' => $totalHarga,
                        'total_dibayar' => $totalBayar,
                        'sisa_piutang' => $sisaPembayaran,
                        'progress' => $progress,
                        'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                        'status_pembayaran' => $status,
                        'days_overdue' => $daysOverdue,
                        'status' => $statusColor,
                        'hari_telat' => $daysOverdue
                    ];

                    $piutangList->push($piutangItem);
                }
            }
        }

        // Sort by due date (ascending) and limit to 4 for dashboard
        return $piutangList->sortBy('tanggal_jatuh_tempo')->take(4);
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

            // Use harga_total from proyek for consistency with dashboard omset calculation
            $wilayahData = DB::table('proyek')
                ->select(
                    DB::raw("TRIM(proyek.kab_kota) as city_name"),
                    DB::raw('SUM(proyek.harga_total) as total_sales'),
                    DB::raw('COUNT(DISTINCT proyek.id_proyek) as total_projects'),
                    DB::raw('AVG(proyek.harga_total) as avg_sales')
                )
                ->where('proyek.status', 'Selesai')
                ->whereNotNull('proyek.kab_kota')
                ->where('proyek.kab_kota', '!=', '')
                ->whereNotNull('proyek.harga_total')
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
     * Get debt age analysis for clients based on invoice due dates - using same logic as piutang dinas
     */
    private function getDebtAgeAnalysis()
    {
        // Ambil semua proyek yang sudah di ACC (same logic as piutang dinas)
        $proyekAcc = Proyek::with(['semuaPenawaran' => function($query) {
            $query->where('status', 'ACC');
        }, 'penagihanDinas.buktiPembayaran'])
        ->whereHas('semuaPenawaran', function($query) {
            $query->where('status', 'ACC');
        })
        ->get();

        // Transform ke format untuk debt age analysis
        $debtAgeList = collect();

        foreach ($proyekAcc as $proyek) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
                $penagihan = $proyek->penagihanDinas->where('penawaran_id', $penawaran->id_penawaran)->first();

                $shouldInclude = false;
                $sisaPembayaran = 0;
                $status = '';
                $tanggalJatuhTempo = null;
                $nomorInvoice = '';
                $totalBayar = 0;
                $daysOverdue = 0;

                if (!$penagihan) {
                    // Belum ada penagihan sama sekali
                    $shouldInclude = true;
                    $sisaPembayaran = $penawaran->total_penawaran ?? 0;
                    $status = 'belum_ditagih';
                    $tanggalJatuhTempo = now()->addDays(30); // Default 30 hari dari sekarang
                    $nomorInvoice = 'Belum Ditagih';
                    $daysOverdue = 0; // Belum telat karena belum ditagih
                } else if ($penagihan->status_pembayaran != 'lunas') {
                    // Ada penagihan tapi belum lunas
                    $totalBayar = $penagihan->buktiPembayaran->sum('jumlah_bayar');
                    $sisaPembayaran = $penagihan->total_harga - $totalBayar;

                    if ($sisaPembayaran > 0) {
                        $shouldInclude = true;
                        $status = $penagihan->status_pembayaran;
                        $tanggalJatuhTempo = $penagihan->tanggal_jatuh_tempo;
                        $nomorInvoice = $penagihan->nomor_invoice;

                        // Calculate days overdue
                        if ($tanggalJatuhTempo && $tanggalJatuhTempo < now()) {
                            $daysOverdue = now()->diffInDays($tanggalJatuhTempo);
                        } else {
                            $daysOverdue = 0;
                        }
                    }
                }

                if ($shouldInclude && $sisaPembayaran > 0) {
                    // Determine age category and color
                    $ageCategory = '';
                    $colorClass = '';
                    $statusText = '';

                    if ($daysOverdue <= 0) {
                        $ageCategory = '0-30 hari';
                        $colorClass = 'green';
                        $statusText = $status == 'belum_ditagih' ? 'Belum Ditagih' : 'Baik';
                    } elseif ($daysOverdue <= 30) {
                        $ageCategory = '0-30 hari';
                        $colorClass = 'green';
                        $statusText = 'Baik';
                    } elseif ($daysOverdue <= 60) {
                        $ageCategory = '30-60 hari';
                        $colorClass = 'yellow';
                        $statusText = 'Perhatian';
                    } elseif ($daysOverdue <= 90) {
                        $ageCategory = '60-90 hari';
                        $colorClass = 'orange';
                        $statusText = 'Waspada';
                    } elseif ($daysOverdue <= 120) {
                        $ageCategory = '90-120 hari';
                        $colorClass = 'red';
                        $statusText = 'Buruk';
                    } elseif ($daysOverdue <= 150) {
                        $ageCategory = '120-150 hari';
                        $colorClass = 'red';
                        $statusText = 'Sangat Buruk';
                    } else {
                        $ageCategory = '>150 hari';
                        $colorClass = 'red';
                        $statusText = 'Kritis';
                    }

                    // Create debt age item
                    $debtAgeItem = (object)[
                        'instansi' => $proyek->instansi,
                        'kode_proyek' => $proyek->kode_proyek,
                        'nomor_invoice' => $nomorInvoice,
                        'total_harga' => $penawaran->total_penawaran ?? 0,
                        'total_dibayar' => $totalBayar,
                        'outstanding_amount' => $sisaPembayaran,
                        'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                        'status_pembayaran' => $status,
                        'days_overdue' => $daysOverdue,
                        'age_category' => $ageCategory,
                        'color_class' => $colorClass,
                        'status_text' => $statusText
                    ];

                    $debtAgeList->push($debtAgeItem);
                }
            }
        }

        // Sort by outstanding amount (descending) and limit to top 8
        return $debtAgeList->sortByDesc('outstanding_amount')->take(8);
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

    /**
     * Get year range from project data - same logic as LaporanController
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
