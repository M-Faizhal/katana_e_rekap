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
        $stats['omset_tahun_ini_formatted'] = $this->formatRupiah($stats['omset_tahun_ini']);
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
        $lastYear = Carbon::now()->subYear();

        // Calculate omset tahun ini (revenue this year) - using kalkulasi_hps.hps
        // Using SUM(hps) from kalkulasi_hps with ACC penawaran status
        // TIME BASIS: penawaran.tanggal_penawaran (when deal was accepted)
        $omsetTahunIni = DB::table('kalkulasi_hps')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->where('penawaran.status', 'ACC')
            ->whereYear('penawaran.tanggal_penawaran', $currentYear)
            ->sum('kalkulasi_hps.hps') ?? 0;

        // Calculate omset tahun lalu untuk perbandingan
        $omsetTahunLalu = DB::table('kalkulasi_hps')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->where('penawaran.status', 'ACC')
            ->whereYear('penawaran.tanggal_penawaran', $lastYear->year)
            ->sum('kalkulasi_hps.hps') ?? 0;

        // Calculate growth percentage
        $omsetGrowth = $omsetTahunLalu > 0 ?
            (($omsetTahunIni - $omsetTahunLalu) / $omsetTahunLalu) * 100 :
            ($omsetTahunIni > 0 ? 100 : 0);

        // Count Proyek Sudah SP (Tahun Ini)
        // Proyek yang punya penawaran ACC di tahun ini berdasarkan tanggal_penawaran
        $proyekSPTahunIni = Proyek::where('status', '!=', 'Gagal')
            ->whereHas('semuaPenawaran', function($query) use ($currentYear) {
                $query->where('status', 'ACC')
                      ->whereYear('tanggal_penawaran', $currentYear);
            })
            ->count();
        
        // Count new projects this month (for backward compatibility if needed elsewhere)
        $proyekBaru = Proyek::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Total hutang and piutang will be calculated in separate methods and overridden in index()
        $totalHutang = 0;
        $vendorPending = 0;
        $totalPiutang = 0;
        $dinasPending = 0;

        return [
            'omset_tahun_ini' => $omsetTahunIni,
            'omset_growth' => round($omsetGrowth, 1),
            'proyek_sp_tahun_ini' => $proyekSPTahunIni,
            'proyek_baru' => $proyekBaru,
            'total_hutang' => $totalHutang,
            'vendor_pending' => $vendorPending,
            'total_piutang' => $totalPiutang,
            'dinas_pending' => $dinasPending
        ];
    }

    /**
     * Get monthly revenue data for chart
     * TIME BASIS: penawaran.tanggal_penawaran (when deal was accepted)
     */
    private function getMonthlyRevenue($year = null, $specificMonth = null)
    {
        $year = $year ?: Carbon::now()->year;
        $monthlyData = [];

        // If specific month is requested, only return that month's data
        if ($specificMonth) {
            $revenue = DB::table('kalkulasi_hps')
                ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                ->where('penawaran.status', 'ACC')
                ->whereMonth('penawaran.tanggal_penawaran', $specificMonth)
                ->whereYear('penawaran.tanggal_penawaran', $year)
                ->sum('kalkulasi_hps.hps') ?? 0;

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
                $revenue = DB::table('kalkulasi_hps')
                    ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
                    ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
                    ->where('penawaran.status', 'ACC')
                    ->whereMonth('penawaran.tanggal_penawaran', $month)
                    ->whereYear('penawaran.tanggal_penawaran', $year)
                    ->sum('kalkulasi_hps.hps') ?? 0;

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
     * TIME BASIS: penawaran.tanggal_penawaran (when deal was accepted)
     */
    private function getRevenuePerPerson()
    {
        // Get marketing admins only - using kalkulasi_hps.hps
        $marketingAdmins = DB::table('users')
            ->select(
                'users.nama',
                'users.id_user',
                DB::raw('SUM(kalkulasi_hps.hps) as total_revenue'),
                DB::raw('COUNT(DISTINCT proyek.id_proyek) as total_projects'),
                DB::raw("'Marketing' as role")
            )
            ->join('proyek', 'proyek.id_admin_marketing', '=', 'users.id_user')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->join('kalkulasi_hps', 'proyek.id_proyek', '=', 'kalkulasi_hps.id_proyek')
            ->where('penawaran.status', 'ACC')
            ->whereYear('penawaran.tanggal_penawaran', Carbon::now()->year)
            ->groupBy('users.id_user', 'users.nama')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        return $marketingAdmins;
    }

    /**
     * Get hutang vendor statistics for dashboard
     * Using EXACT same logic as LaporanController::getHutangVendorStatistics()
     */
    private function getHutangVendorStats()
    {
        // Menggunakan logika PERSIS SAMA dengan LaporanController
        $totalHutang = 0;
        $jumlahHutangVendor = 0;
        
        // Ambil proyek yang perlu bayar dengan cara yang sama seperti LaporanController
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

        $rataRataHutang = $jumlahHutangVendor > 0 ? $totalHutang / $jumlahHutangVendor : 0;

        return [
            'total_hutang' => $totalHutang,
            'jumlah_vendor' => $jumlahHutangVendor,
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
        // Menggunakan logika PERSIS SAMA dengan LaporanController
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
                    
                    $persenBayar = $totalVendor > 0 ? ($totalDibayarApproved / $totalVendor) * 100 : 0;
                    $statusLunas = $totalVendor > 0 ? $sisaBayar <= 0 : false;

                    return (object) [
                        'vendor' => $vendor,
                        'proyek' => $proyek,
                        'total_vendor' => $totalVendor,
                        'total_dibayar_approved' => $totalDibayarApproved,
                        'sisa_bayar' => $sisaBayar,
                        'persen_bayar' => $persenBayar,
                        'status_lunas' => $statusLunas,
                        'warning_hps' => $warning_hps,
                        'oldest_date' => $proyek->penawaranAktif->tanggal_penawaran ?? null
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

        // Sort by sisa_bayar descending and limit to top 4
        $vendorDebts = $results->sortByDesc('sisa_bayar')->take(4);

        // Jika tidak ada hutang vendor aktual, tampilkan data untuk display saja
        if ($vendorDebts->isEmpty()) {
            return collect([
                (object) [
                    'vendor' => (object) ['nama_vendor' => 'Tidak ada hutang vendor', 'jenis_perusahaan' => null],
                    'proyek' => (object) ['kode_proyek' => '-', 'instansi' => '-'],
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
            // Calculate days overdue
            $daysOverdue = $item->oldest_date ? Carbon::parse($item->oldest_date)->diffInDays(Carbon::now()) : 0;

            // Determine status
            if ($item->warning_hps) {
                $status = 'warning'; // HPS belum diisi
            } elseif ($item->status_lunas) {
                $status = 'Lunas';
            } elseif ($daysOverdue > 30) {
                $status = 'overdue';
            } elseif ($daysOverdue > 14) {
                $status = 'warning';
            } else {
                $status = 'normal';
            }

            return (object) [
                'nama_vendor' => $item->vendor->nama_vendor ?? 'Unknown',
                'kode_proyek' => $item->proyek->kode_proyek ?? '-',
                'instansi' => $item->proyek->instansi ?? '-',
                'nama_klien' => $item->proyek->nama_klien ?? '-',
                'jenis_perusahaan' => $item->vendor->jenis_perusahaan ?? null,
                'email' => $item->vendor->email ?? null,
                'total_vendor' => $item->total_vendor,
                'total_dibayar_approved' => $item->total_dibayar_approved,
                'sisa_bayar' => $item->sisa_bayar,
                'persen_bayar' => $item->persen_bayar,
                'warning_hps' => $item->warning_hps,
                'status_lunas' => $item->status_lunas,
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
                // Cek apakah semua vendor sudah mengirim barang (Sampai_Tujuan)
                $allVendorsDelivered = $this->checkAllVendorsDelivered($penawaran);
                
                // Skip jika barang belum sampai semua
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
                // Cek apakah semua vendor sudah mengirim barang (Sampai_Tujuan)
                $allVendorsDelivered = $this->checkAllVendorsDelivered($penawaran);
                
                // Skip jika barang belum sampai semua
                if (!$allVendorsDelivered) {
                    continue;
                }
                
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
                // Cek apakah semua vendor sudah mengirim barang (Sampai_Tujuan)
                $allVendorsDelivered = $this->checkAllVendorsDelivered($penawaran);
                
                // Skip jika barang belum sampai semua
                if (!$allVendorsDelivered) {
                    continue;
                }
                
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
                        if ($tanggalJatuhTempo) {
                            try {
                                $jatuhTempoDate = Carbon::createFromFormat('Y-m-d', (string)$tanggalJatuhTempo);
                                if ($jatuhTempoDate->isPast()) {
                                    $daysOverdue = abs($jatuhTempoDate->diffInDays(now()));
                                } else {
                                    $daysOverdue = 0;
                                }
                            } catch (\Exception $e) {
                                $daysOverdue = 0;
                            }
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
     * Check if all vendors have delivered their items for a penawaran
     */
    private function checkAllVendorsDelivered($penawaran)
    {
        // Get all unique vendor IDs from penawaran details
        $vendorIds = $penawaran->penawaranDetail()
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->pluck('barang.id_vendor')
            ->unique();

        // If no vendors found, return false
        if ($vendorIds->isEmpty()) {
            return false;
        }

        // Check each vendor has delivered (status_verifikasi = Sampai_Tujuan)
        foreach ($vendorIds as $vendorId) {
            $pengiriman = $penawaran->pengiriman()
                ->where('id_vendor', $vendorId)
                ->where('status_verifikasi', 'Sampai_Tujuan')
                ->exists();

            if (!$pengiriman) {
                return false; // At least one vendor hasn't delivered yet
            }
        }

        return true; // All vendors have delivered
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
     * TIME BASIS: penawaran.tanggal_penawaran (when deal was accepted)
     */
    private function getYearRange()
    {
        $yearRange = DB::table('penawaran')
            ->join('proyek', 'penawaran.id_proyek', '=', 'proyek.id_proyek')
            ->selectRaw('MIN(YEAR(penawaran.tanggal_penawaran)) as min_year, MAX(YEAR(penawaran.tanggal_penawaran)) as max_year')
            ->where('penawaran.status', 'ACC')
            ->where('proyek.status', '!=', 'Gagal') // Exclude failed projects
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
