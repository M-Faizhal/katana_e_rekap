<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyek;
use Illuminate\Support\Facades\DB;
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

        // --- Hutang Vendor: satu kali load, dua hasil ---
        $hutangVendorResult = $this->getHutangVendorData();
        $hutangVendorStats  = $hutangVendorResult['stats'];
        $vendorDebts        = $hutangVendorResult['debts'];

        // --- Piutang Dinas: satu kali load, tiga hasil ---
        $piutangResult    = $this->getPiutangDinasData();
        $piutangDinasStats  = $piutangResult['stats'];
        $clientReceivables  = $piutangResult['receivables'];
        $debtAgeAnalysis    = $piutangResult['debtAge'];

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

        // Get monthly revenue data (default to current year) â€” single grouped query
        $monthlyRevenue = $this->getMonthlyRevenue();

        // Get revenue per admin marketing
        $revenuePerPerson = $this->getRevenuePerPerson();

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
     * Get monthly revenue data for chart â€” single GROUP BY query instead of 12 queries.
     * TIME BASIS: penawaran.tanggal_penawaran (when deal was accepted)
     */
    private function getMonthlyRevenue($year = null, $specificMonth = null)
    {
        $year = $year ?: Carbon::now()->year;

        // One query: SUM per month
        $rows = DB::table('kalkulasi_hps')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->selectRaw('MONTH(penawaran.tanggal_penawaran) as month, SUM(kalkulasi_hps.hps) as revenue')
            ->where('penawaran.status', 'ACC')
            ->whereYear('penawaran.tanggal_penawaran', $year)
            ->when($specificMonth, fn($q) => $q->whereMonth('penawaran.tanggal_penawaran', $specificMonth))
            ->groupByRaw('MONTH(penawaran.tanggal_penawaran)')
            ->pluck('revenue', 'month'); // keyed by month number

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyData[] = [
                'month'      => $month,
                'month_name' => Carbon::create($year, $month, 1)->format('M'),
                'revenue'    => $rows[$month] ?? 0,
            ];
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
     * Single load for hutang vendor â€” returns stats + top-4 debts list.
     * Replaces the previous separate getHutangVendorStats() + getVendorDebts() calls.
     *
     * Key optimisations:
     * - Single Eloquent load with eager-loading for all relations.
     * - KalkulasiHps totals fetched in ONE grouped query for all projects, then keyed in PHP.
     * - Pembayaran totals likewise fetched in ONE grouped query.
     */
    private function getHutangVendorData(): array
    {
        // 1. Load all relevant projects with needed relations (one query + eager loads)
        $proyekList = Proyek::with([
                'penawaranAktif.penawaranDetail.barang.vendor',
                'pembayaran' => fn($q) => $q->where('status_verifikasi', 'Approved'),
            ])
            ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai'])
            ->whereHas('penawaranAktif', fn($q) => $q->where('status', 'ACC'))
            ->get();

        $proyekIds = $proyekList->pluck('id_proyek')->all();

        // 2. Batch: total_harga_hpp per (id_proyek, id_vendor)
        $hppRows = DB::table('kalkulasi_hps')
            ->selectRaw('id_proyek, id_vendor, SUM(total_harga_hpp) as total')
            ->whereIn('id_proyek', $proyekIds)
            ->groupBy('id_proyek', 'id_vendor')
            ->get()
            ->groupBy('id_proyek')          // Collection keyed by id_proyek
            ->map(fn($rows) => $rows->pluck('total', 'id_vendor'));

        // 3. Batch: nominal_bayar (Approved) per (id_proyek, id_vendor)
        // pembayaran has no id_proyek — join via penawaran to get it
        $bayarRows = DB::table('pembayaran')
            ->join('penawaran', 'pembayaran.id_penawaran', '=', 'penawaran.id_penawaran')
            ->selectRaw('penawaran.id_proyek, pembayaran.id_vendor, SUM(pembayaran.nominal_bayar) as total')
            ->whereIn('penawaran.id_proyek', $proyekIds)
            ->where('pembayaran.status_verifikasi', 'Approved')
            ->groupBy('penawaran.id_proyek', 'pembayaran.id_vendor')
            ->get()
            ->groupBy('id_proyek')
            ->map(fn($rows) => $rows->pluck('total', 'id_vendor'));

        // 4. Build flat results list
        $results    = collect();
        $totalHutang = 0;
        $jumlahHutangVendor = 0;

        foreach ($proyekList as $proyek) {
            if (!$proyek->penawaranAktif) {
                continue;
            }

            $vendors = $proyek->penawaranAktif->penawaranDetail
                ->pluck('barang.vendor')
                ->filter()
                ->unique('id_vendor');

            $hppByVendor  = $hppRows[$proyek->id_proyek]  ?? collect();
            $bayarByVendor = $bayarRows[$proyek->id_proyek] ?? collect();

            foreach ($vendors as $vendor) {
                $totalVendor          = (float) ($hppByVendor[$vendor->id_vendor]  ?? 0);
                $totalDibayarApproved = (float) ($bayarByVendor[$vendor->id_vendor] ?? 0);
                $sisaBayar            = $totalVendor - $totalDibayarApproved;
                $warningHps           = $totalVendor == 0 ? 'Data kalkulasi HPS belum diisi' : null;
                $statusLunas          = $totalVendor > 0 && $sisaBayar <= 0;

                if ($sisaBayar <= 0 && !$warningHps) {
                    continue; // already paid, skip
                }

                $totalHutang += $sisaBayar;
                $jumlahHutangVendor++;

                $results->push((object) [
                    'nama_vendor'           => $vendor->nama_vendor ?? 'Unknown',
                    'jenis_perusahaan'      => $vendor->jenis_perusahaan ?? null,
                    'email'                 => $vendor->email ?? null,
                    'kode_proyek'           => $proyek->kode_proyek ?? '-',
                    'instansi'              => $proyek->instansi ?? '-',
                    'nama_klien'            => $proyek->nama_klien ?? '-',
                    'total_vendor'          => $totalVendor,
                    'total_dibayar_approved'=> $totalDibayarApproved,
                    'sisa_bayar'            => $sisaBayar,
                    'persen_bayar'          => $totalVendor > 0 ? ($totalDibayarApproved / $totalVendor) * 100 : 0,
                    'warning_hps'           => $warningHps,
                    'status_lunas'          => $statusLunas,
                    'oldest_date'           => $proyek->penawaranAktif->tanggal_penawaran ?? null,
                ]);
            }
        }

        // 5. Build top-4 debts list
        $top4 = $results->sortByDesc('sisa_bayar')->take(4)->map(function ($item) {
            $daysOverdue = $item->oldest_date
                ? Carbon::parse($item->oldest_date)->diffInDays(Carbon::now())
                : 0;

            if ($item->warning_hps) {
                $status = 'warning';
            } elseif ($item->status_lunas) {
                $status = 'Lunas';
            } elseif ($daysOverdue > 30) {
                $status = 'overdue';
            } elseif ($daysOverdue > 14) {
                $status = 'warning';
            } else {
                $status = 'normal';
            }

            return (object) array_merge((array) $item, [
                'status'      => $status,
                'days_overdue'=> $daysOverdue,
            ]);
        });

        if ($top4->isEmpty()) {
            $top4 = collect([(object) [
                'vendor'                 => (object) ['nama_vendor' => 'Tidak ada hutang vendor', 'jenis_perusahaan' => null],
                'kode_proyek'            => '-',
                'instansi'               => '-',
                'total_vendor'           => 0,
                'total_dibayar_approved' => 0,
                'sisa_bayar'             => 0,
                'persen_bayar'           => 100,
                'warning_hps'            => null,
                'status_lunas'           => true,
                'status'                 => 'Lunas',
                'days_overdue'           => 0,
            ]]);
        }

        $rataRataHutang = $jumlahHutangVendor > 0 ? $totalHutang / $jumlahHutangVendor : 0;

        return [
            'stats' => [
                'total_hutang'             => $totalHutang,
                'jumlah_vendor'            => $jumlahHutangVendor,
                'rata_rata_hutang'         => $rataRataHutang,
                'total_hutang_formatted'   => $this->formatRupiah($totalHutang),
                'rata_rata_hutang_formatted' => $this->formatRupiah($rataRataHutang),
            ],
            'debts' => $top4,
        ];
    }

    /**
     * Single load for piutang dinas â€” returns stats + top-4 receivables + debt-age list.
     * Replaces getPiutangDinasStats(), getClientReceivables(), getDebtAgeAnalysis().
     *
     * Key optimisations:
     * - ONE Eloquent load with all needed eager relations.
     * - checkAllVendorsDelivered() replaced by a SINGLE batch query (pengiriman keyed in memory).
     * - All three result sets built in a single pass over the same data.
     */
    private function getPiutangDinasData(): array
    {
        // 1. Load projects + relations once
        $proyekAcc = Proyek::with([
                'semuaPenawaran'         => fn($q) => $q->where('status', 'ACC'),
                'semuaPenawaran.penawaranDetail.barang',   // for vendor IDs
                'penagihanDinas.buktiPembayaran',
            ])
            ->whereHas('semuaPenawaran', fn($q) => $q->where('status', 'ACC'))
            ->get();

        // Collect all penawaran IDs for the batch pengiriman query
        $allPenawaranIds = $proyekAcc->flatMap(fn($p) => $p->semuaPenawaran->pluck('id_penawaran'))->all();

        // 2. Batch: for each penawaran, which vendor IDs have Sampai_Tujuan?
        //    Result: [ id_penawaran => [id_vendor, ...] ]
        $deliveredMap = DB::table('pengiriman')
            ->whereIn('id_penawaran', $allPenawaranIds)
            ->where('status_verifikasi', 'Sampai_Tujuan')
            ->select('id_penawaran', 'id_vendor')
            ->get()
            ->groupBy('id_penawaran')
            ->map(fn($rows) => $rows->pluck('id_vendor')->unique()->values()->all());

        // 3. Build results in one pass
        $totalPiutang      = 0;
        $piutangJatuhTempo = 0;
        $jumlahProyek      = 0;
        $piutangList       = collect();
        $debtAgeList       = collect();

        foreach ($proyekAcc as $proyek) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
                // Determine vendor IDs required for this penawaran
                $requiredVendorIds = $penawaran->penawaranDetail
                    ->pluck('barang.id_vendor')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                if (empty($requiredVendorIds)) {
                    continue; // no vendors linked â€” skip
                }

                // Check all vendors delivered (in-memory, no extra queries)
                $deliveredVendors = $deliveredMap[$penawaran->id_penawaran] ?? [];
                $allDelivered = empty(array_diff($requiredVendorIds, $deliveredVendors));

                if (!$allDelivered) {
                    continue;
                }

                // Resolve penagihan
                $penagihan = $proyek->penagihanDinas->where('penawaran_id', $penawaran->id_penawaran)->first();

                $sisaPembayaran   = 0;
                $status           = '';
                $tanggalJatuhTempo = null;
                $nomorInvoice     = '';
                $totalBayar       = 0;
                $daysOverdue      = 0;
                $shouldInclude    = false;

                if (!$penagihan) {
                    $shouldInclude     = true;
                    $sisaPembayaran    = $penawaran->total_penawaran ?? 0;
                    $status            = 'belum_ditagih';
                    $tanggalJatuhTempo = now()->addDays(30);
                    $nomorInvoice      = 'Belum Ditagih';
                    $daysOverdue       = 0;
                } elseif ($penagihan->status_pembayaran !== 'lunas') {
                    $totalBayar     = $penagihan->buktiPembayaran->sum('jumlah_bayar');
                    $sisaPembayaran = $penagihan->total_harga - $totalBayar;

                    if ($sisaPembayaran > 0) {
                        $shouldInclude     = true;
                        $status            = $penagihan->status_pembayaran;
                        $tanggalJatuhTempo = $penagihan->tanggal_jatuh_tempo;
                        $nomorInvoice      = $penagihan->nomor_invoice;

                        if ($tanggalJatuhTempo) {
                            try {
                                $jtDate = Carbon::createFromFormat('Y-m-d', (string) $tanggalJatuhTempo);
                                $daysOverdue = $jtDate->isPast() ? abs($jtDate->diffInDays(now())) : 0;
                            } catch (\Exception $e) {
                                $daysOverdue = 0;
                            }
                        }
                    }
                }

                if (!$shouldInclude || $sisaPembayaran <= 0) {
                    continue;
                }

                // --- Stats ---
                $totalPiutang += $sisaPembayaran;
                $jumlahProyek++;
                if ($status === 'belum_ditagih') {
                    if ($penawaran->updated_at < now()->subDays(30)) {
                        $piutangJatuhTempo += $sisaPembayaran;
                    }
                } elseif ($tanggalJatuhTempo && $tanggalJatuhTempo < now()) {
                    $piutangJatuhTempo += $sisaPembayaran;
                }

                // --- Receivables list ---
                $totalHarga  = $penawaran->total_penawaran ?? 0;
                $progress    = $totalHarga > 0 ? ($totalBayar / $totalHarga) * 100 : 0;
                $statusColor = $daysOverdue > 0 ? 'overdue' : 'pending';

                $piutangList->push((object) [
                    'instansi'           => $proyek->instansi,
                    'kode_proyek'        => $proyek->kode_proyek,
                    'nomor_invoice'      => $nomorInvoice,
                    'total_harga'        => $totalHarga,
                    'total_dibayar'      => $totalBayar,
                    'sisa_piutang'       => $sisaPembayaran,
                    'progress'           => $progress,
                    'tanggal_jatuh_tempo'=> $tanggalJatuhTempo,
                    'status_pembayaran'  => $status,
                    'days_overdue'       => $daysOverdue,
                    'status'             => $statusColor,
                    'hari_telat'         => $daysOverdue,
                ]);

                // --- Debt age list ---
                if ($daysOverdue <= 0) {
                    $ageCategory = '0-30 hari';
                    $colorClass  = 'green';
                    $statusText  = $status === 'belum_ditagih' ? 'Belum Ditagih' : 'Baik';
                } elseif ($daysOverdue <= 30) {
                    $ageCategory = '0-30 hari';  $colorClass = 'green';  $statusText = 'Baik';
                } elseif ($daysOverdue <= 60) {
                    $ageCategory = '30-60 hari'; $colorClass = 'yellow'; $statusText = 'Perhatian';
                } elseif ($daysOverdue <= 90) {
                    $ageCategory = '60-90 hari'; $colorClass = 'orange'; $statusText = 'Waspada';
                } elseif ($daysOverdue <= 120) {
                    $ageCategory = '90-120 hari';  $colorClass = 'red'; $statusText = 'Buruk';
                } elseif ($daysOverdue <= 150) {
                    $ageCategory = '120-150 hari'; $colorClass = 'red'; $statusText = 'Sangat Buruk';
                } else {
                    $ageCategory = '>150 hari'; $colorClass = 'red'; $statusText = 'Kritis';
                }

                $debtAgeList->push((object) [
                    'instansi'            => $proyek->instansi,
                    'kode_proyek'         => $proyek->kode_proyek,
                    'nomor_invoice'       => $nomorInvoice,
                    'total_harga'         => $totalHarga,
                    'total_dibayar'       => $totalBayar,
                    'outstanding_amount'  => $sisaPembayaran,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                    'status_pembayaran'   => $status,
                    'days_overdue'        => $daysOverdue,
                    'age_category'        => $ageCategory,
                    'color_class'         => $colorClass,
                    'status_text'         => $statusText,
                ]);
            }
        }

        $rataRataPiutang = $jumlahProyek > 0 ? $totalPiutang / $jumlahProyek : 0;

        return [
            'stats' => [
                'total_piutang'              => $totalPiutang,
                'piutang_jatuh_tempo'        => $piutangJatuhTempo,
                'jumlah_proyek'              => $jumlahProyek,
                'rata_rata_piutang'          => $rataRataPiutang,
                'total_piutang_formatted'    => $this->formatRupiah($totalPiutang),
                'piutang_jatuh_tempo_formatted' => $this->formatRupiah($piutangJatuhTempo),
                'rata_rata_piutang_formatted'   => $this->formatRupiah($rataRataPiutang),
            ],
            'receivables' => $piutangList->sortBy('tanggal_jatuh_tempo')->take(4),
            'debtAge'     => $debtAgeList->sortByDesc('outstanding_amount')->take(8),
        ];
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