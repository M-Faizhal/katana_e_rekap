<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\Pembayaran;
use App\Models\KalkulasiHps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class RiwayatPembelianController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->get('search');
        $filterStatus = $request->get('status_filter', 'all');   // all | lunas | belum_lunas
        $filterPpn   = $request->get('ppn_filter', 'all');        // all | ada_ppn | non_ppn
        $sortBy      = $request->get('sort_by', 'desc');

        // --- Ambil proyek yang punya penawaran ACC + pembayaran ---
        $query = Proyek::with([
            'penawaranAktif.penawaranDetail.barang.vendor',
            'adminMarketing',
        ])
        ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai', 'Gagal'])
        ->whereHas('penawaranAktif', fn($q) => $q->where('status', 'ACC'));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('instansi', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%");
            });
        }

        $sortBy === 'asc'
            ? $query->orderBy('created_at', 'asc')
            : $query->orderBy('created_at', 'desc');

        $proyekAll = $query->get();

        // --- Batch load KalkulasiHps ---
        $proyekIds   = $proyekAll->pluck('id_proyek')->all();
        $penawaranIds = $proyekAll->pluck('penawaranAktif.id_penawaran')->filter()->all();

        $kalkulasiAll = KalkulasiHps::with('barang.vendor')
            ->whereIn('id_proyek', $proyekIds)
            ->get()
            ->groupBy('id_proyek');    // [ id_proyek => Collection<KalkulasiHps> ]

        // Batch total harga_akhir per (proyek, vendor)
        $hpsMap = [];
        foreach ($kalkulasiAll as $pid => $rows) {
            foreach ($rows->groupBy('id_vendor') as $vid => $vrows) {
                $hpsMap[$pid][$vid] = $vrows->sum('total_harga_hpp');
            }
        }

        // Batch approved pembayaran per (proyek via penawaran, vendor)
        $pembayaranApproved = Pembayaran::whereIn('id_penawaran', $penawaranIds)
            ->where('status_verifikasi', 'Approved')
            ->select('id_penawaran', 'id_vendor', DB::raw('SUM(nominal_bayar) as total'))
            ->groupBy('id_penawaran', 'id_vendor')
            ->get();

        $penawaranToProyek = $proyekAll->pluck('penawaranAktif.id_penawaran', 'id_proyek')
            ->flip()->all();   // [ id_penawaran => id_proyek ]

        $bayarMap = [];
        foreach ($pembayaranApproved as $row) {
            $pid = $penawaranToProyek[$row->id_penawaran] ?? null;
            if ($pid) $bayarMap[$pid][$row->id_vendor] = (float) $row->total;
        }

        // --- Ambil ppn_data terbaru per (penawaran, vendor) ---
        // Satu query: latest per (id_penawaran, id_vendor) yang ada ppn_data
        $latestPpnRows = Pembayaran::whereIn('id_penawaran', $penawaranIds)
            ->whereNotNull('ppn_data')
            ->orderBy('id_pembayaran', 'desc')
            ->get()
            ->groupBy('id_penawaran')
            ->map(fn($rows) => $rows->groupBy('id_vendor')->map(fn($vrows) => $vrows->first()));
        // $latestPpnRows[ id_penawaran ][ id_vendor ] = Pembayaran

        // --- Build hasil per proyek ---
        $hasil = $proyekAll->map(function ($proyek) use ($kalkulasiAll, $hpsMap, $bayarMap, $latestPpnRows, $penawaranToProyek) {
            $idPenawaran = $proyek->penawaranAktif?->id_penawaran;
            $kalkRows    = $kalkulasiAll->get($proyek->id_proyek, collect());

            // Vendor unik dari kalkulasi
            $vendorIds = $kalkRows->pluck('id_vendor')->unique()->values();

            $vendors = $vendorIds->map(function ($vendorId) use ($proyek, $kalkRows, $hpsMap, $bayarMap, $latestPpnRows, $idPenawaran) {
                $vKalk       = $kalkRows->where('id_vendor', $vendorId);
                $vendorModel = $vKalk->first()?->barang?->vendor;
                $vendorNama  = $vendorModel?->nama_vendor ?? "Vendor #{$vendorId}";

                $totalHarga  = $hpsMap[$proyek->id_proyek][$vendorId] ?? 0;
                $totalBayar  = $bayarMap[$proyek->id_proyek][$vendorId] ?? 0;
                $sisaBayar   = $totalHarga - $totalBayar;

                // PPN snapshot terbaru
                $latestPpn = $latestPpnRows[$idPenawaran][$vendorId] ?? null;

                // Build items: merge data KalkulasiHps + ppn_data snapshot
                $ppnMap = [];
                if ($latestPpn && !empty($latestPpn->ppn_data['items'])) {
                    foreach ($latestPpn->ppn_data['items'] as $pItem) {
                        $ppnMap[$pItem['id_kalkulasi_hps']] = $pItem;
                    }
                }

                $items = $vKalk->map(function ($k) use ($ppnMap) {
                    $snap       = $ppnMap[$k->id_kalkulasi] ?? null;
                    $adaPpn     = $snap ? (bool) $snap['ada_ppn'] : null;
                    $nominalPpn = isset($snap['nominal_ppn']) ? (float) $snap['nominal_ppn'] : null;
                    $qty        = (int) $k->qty ?: 1;
                    $hargaAkhir = (float) $k->harga_akhir;

                    // Harga satuan = harga per unit SEBELUM PPN
                    // Jika ada PPN: harga_akhir (sudah include PPN per unit) - (nominal_ppn / qty)
                    // Jika non-PPN atau belum dikonfigurasi: sama dengan harga_akhir
                    if ($adaPpn === true && $nominalPpn !== null) {
                        $hargaSatuan = $hargaAkhir - ($nominalPpn / $qty);
                    } else {
                        $hargaSatuan = $hargaAkhir;
                    }

                    return [
                        'id_kalkulasi'      => $k->id_kalkulasi,
                        'nama_barang'       => $k->barang->nama_barang ?? 'N/A',
                        'satuan'            => $k->barang->satuan ?? '-',
                        'qty'               => $qty,
                        'harga_vendor'      => (float) $k->harga_vendor,
                        'harga_satuan'      => $hargaSatuan,   // harga per unit sebelum PPN
                        'harga_akhir'       => $hargaAkhir,    // harga per unit sudah include PPN
                        'total_harga_hpp'   => (float) $k->total_harga_hpp,
                        // PPN dari snapshot (null = belum dikonfigurasi)
                        'ada_ppn'           => $adaPpn,
                        'persen_ppn'        => $snap['persen_ppn'] ?? null,
                        'harga_sebelum_ppn' => $snap['harga_sebelum_ppn'] ?? null,
                        'nominal_ppn'       => $nominalPpn,
                    ];
                })->values()->all();

                // Agregat PPN vendor ini dari semua pembayaran Approved
                $totalPpnApproved = 0;
                if ($idPenawaran) {
                    // Sudah dalam $latestPpnRows — tapi perlu semua approved, bukan hanya latest
                    // (akan dihitung di blade via $pembayaranVendor)
                }

                return [
                    'id_vendor'          => $vendorId,
                    'vendor_nama'        => $vendorNama,
                    'total_harga'        => $totalHarga,
                    'total_bayar'        => $totalBayar,
                    'sisa_bayar'         => $sisaBayar,
                    'status_lunas'       => $sisaBayar <= 0,
                    'has_ppn_snapshot'   => $latestPpn !== null,
                    'snapshot_id'        => $latestPpn?->id_pembayaran,
                    'snapshot_tanggal'   => $latestPpn?->tanggal_bayar,
                    'ada_ppn'            => $latestPpn ? ($latestPpn->ada_ppn) : false,
                    'total_ppn_snapshot' => $latestPpn ? floatval($latestPpn->ppn_data['total_ppn'] ?? 0) : 0,
                    'items'              => $items,
                ];
            })->values()->all();

            $grandTotal   = collect($vendors)->sum('total_harga');
            $grandBayar   = collect($vendors)->sum('total_bayar');
            $grandSisa    = $grandTotal - $grandBayar;
            $adaPpn       = collect($vendors)->contains('ada_ppn', true);

            return [
                'proyek'        => $proyek,
                'vendors'       => $vendors,
                'grand_total'   => $grandTotal,
                'grand_bayar'   => $grandBayar,
                'grand_sisa'    => $grandSisa,
                'status_lunas'  => $grandSisa <= 0,
                'ada_ppn'       => $adaPpn,
            ];
        });

        // --- Filter status lunas / belum_lunas ---
        if ($filterStatus === 'lunas') {
            $hasil = $hasil->filter(fn($h) => $h['status_lunas']);
        } elseif ($filterStatus === 'belum_lunas') {
            $hasil = $hasil->filter(fn($h) => !$h['status_lunas']);
        }

        // --- Filter ada/tidak ada PPN ---
        if ($filterPpn === 'ada_ppn') {
            $hasil = $hasil->filter(fn($h) => $h['ada_ppn']);
        } elseif ($filterPpn === 'non_ppn') {
            $hasil = $hasil->filter(fn($h) => !$h['ada_ppn']);
        }

        $hasil = $hasil->values();

        // --- Manual paginate ---
        $perPage     = 15;
        $currentPage = (int) $request->get('page', 1);
        $paginated   = new \Illuminate\Pagination\LengthAwarePaginator(
            $hasil->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $hasil->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // --- Summary stats ---
        $stats = [
            'total_proyek'       => $hasil->count(),
            'total_lunas'        => $hasil->filter(fn($h) => $h['status_lunas'])->count(),
            'total_belum_lunas'  => $hasil->filter(fn($h) => !$h['status_lunas'])->count(),
            'total_ada_ppn'      => $hasil->filter(fn($h) => $h['ada_ppn'])->count(),
            'grand_nilai'        => $hasil->sum('grand_total'),
            'grand_dibayar'      => $hasil->sum('grand_bayar'),
            'grand_sisa'         => $hasil->sum('grand_sisa'),
        ];

        return view('pages.keuangan.riwayat-pembelian', compact(
            'paginated', 'stats', 'search', 'filterStatus', 'filterPpn', 'sortBy'
        ));
    }

    public function export(Request $request)
    {
        // Re-use the same query/build logic as index() but without pagination
        $search       = $request->get('search');
        $filterStatus = $request->get('status_filter', 'all');
        $filterPpn    = $request->get('ppn_filter', 'all');
        $sortBy       = $request->get('sort_by', 'desc');

        $query = Proyek::with([
            'penawaranAktif.penawaranDetail.barang.vendor',
            'adminMarketing',
        ])
        ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai', 'Gagal'])
        ->whereHas('penawaranAktif', fn($q) => $q->where('status', 'ACC'));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('instansi', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%");
            });
        }

        $sortBy === 'asc'
            ? $query->orderBy('created_at', 'asc')
            : $query->orderBy('created_at', 'desc');

        $proyekAll    = $query->get();
        $proyekIds    = $proyekAll->pluck('id_proyek')->all();
        $penawaranIds = $proyekAll->pluck('penawaranAktif.id_penawaran')->filter()->all();

        $kalkulasiAll = KalkulasiHps::with('barang.vendor')
            ->whereIn('id_proyek', $proyekIds)
            ->get()
            ->groupBy('id_proyek');

        $hpsMap = [];
        foreach ($kalkulasiAll as $pid => $rows) {
            foreach ($rows->groupBy('id_vendor') as $vid => $vrows) {
                $hpsMap[$pid][$vid] = $vrows->sum('total_harga_hpp');
            }
        }

        $pembayaranApproved = Pembayaran::whereIn('id_penawaran', $penawaranIds)
            ->where('status_verifikasi', 'Approved')
            ->select('id_penawaran', 'id_vendor', DB::raw('SUM(nominal_bayar) as total'))
            ->groupBy('id_penawaran', 'id_vendor')
            ->get();

        $penawaranToProyek = $proyekAll->pluck('penawaranAktif.id_penawaran', 'id_proyek')
            ->flip()->all();

        $bayarMap = [];
        foreach ($pembayaranApproved as $row) {
            $pid = $penawaranToProyek[$row->id_penawaran] ?? null;
            if ($pid) $bayarMap[$pid][$row->id_vendor] = (float) $row->total;
        }

        $latestPpnRows = Pembayaran::whereIn('id_penawaran', $penawaranIds)
            ->whereNotNull('ppn_data')
            ->orderBy('id_pembayaran', 'desc')
            ->get()
            ->groupBy('id_penawaran')
            ->map(fn($rows) => $rows->groupBy('id_vendor')->map(fn($vrows) => $vrows->first()));

        $hasil = $proyekAll->map(function ($proyek) use ($kalkulasiAll, $hpsMap, $bayarMap, $latestPpnRows, $penawaranToProyek) {
            $idPenawaran = $proyek->penawaranAktif?->id_penawaran;
            $kalkRows    = $kalkulasiAll->get($proyek->id_proyek, collect());
            $vendorIds   = $kalkRows->pluck('id_vendor')->unique()->values();

            $vendors = $vendorIds->map(function ($vendorId) use ($proyek, $kalkRows, $hpsMap, $bayarMap, $latestPpnRows, $idPenawaran) {
                $vKalk       = $kalkRows->where('id_vendor', $vendorId);
                $vendorModel = $vKalk->first()?->barang?->vendor;
                $vendorNama  = $vendorModel?->nama_vendor ?? "Vendor #{$vendorId}";
                $totalHarga  = $hpsMap[$proyek->id_proyek][$vendorId] ?? 0;
                $totalBayar  = $bayarMap[$proyek->id_proyek][$vendorId] ?? 0;
                $sisaBayar   = $totalHarga - $totalBayar;
                $latestPpn   = $latestPpnRows[$idPenawaran][$vendorId] ?? null;

                $ppnMap = [];
                if ($latestPpn && !empty($latestPpn->ppn_data['items'])) {
                    foreach ($latestPpn->ppn_data['items'] as $pItem) {
                        $ppnMap[$pItem['id_kalkulasi_hps']] = $pItem;
                    }
                }

                $items = $vKalk->map(function ($k) use ($ppnMap) {
                    $snap       = $ppnMap[$k->id_kalkulasi] ?? null;
                    $adaPpn     = $snap ? (bool) $snap['ada_ppn'] : null;
                    $nominalPpn = isset($snap['nominal_ppn']) ? (float) $snap['nominal_ppn'] : null;
                    $qty        = (int) $k->qty ?: 1;
                    $hargaAkhir = (float) $k->harga_akhir;

                    if ($adaPpn === true && $nominalPpn !== null) {
                        $hargaSatuan = $hargaAkhir - ($nominalPpn / $qty);
                    } else {
                        $hargaSatuan = $hargaAkhir;
                    }

                    return [
                        'nama_barang'       => $k->barang->nama_barang ?? 'N/A',
                        'satuan'            => $k->barang->satuan ?? '-',
                        'qty'               => $qty,
                        'harga_satuan'      => $hargaSatuan,
                        'harga_akhir'       => $hargaAkhir,
                        'total_harga_hpp'   => (float) $k->total_harga_hpp,
                        'ada_ppn'           => $adaPpn,
                        'persen_ppn'        => $snap['persen_ppn'] ?? null,
                        'harga_sebelum_ppn' => $snap['harga_sebelum_ppn'] ?? null,
                        'nominal_ppn'       => $nominalPpn,
                    ];
                })->values()->all();

                return [
                    'vendor_nama'  => $vendorNama,
                    'total_harga'  => $totalHarga,
                    'total_bayar'  => $totalBayar,
                    'sisa_bayar'   => $sisaBayar,
                    'status_lunas' => $sisaBayar <= 0,
                    'ada_ppn'      => $latestPpn ? ($latestPpn->ada_ppn) : false,
                    'items'        => $items,
                ];
            })->values()->all();

            $grandTotal = collect($vendors)->sum('total_harga');
            $grandBayar = collect($vendors)->sum('total_bayar');
            $grandSisa  = $grandTotal - $grandBayar;
            $adaPpn     = collect($vendors)->contains('ada_ppn', true);

            return [
                'proyek'       => $proyek,
                'vendors'      => $vendors,
                'grand_total'  => $grandTotal,
                'grand_bayar'  => $grandBayar,
                'grand_sisa'   => $grandSisa,
                'status_lunas' => $grandSisa <= 0,
                'ada_ppn'      => $adaPpn,
            ];
        });

        if ($filterStatus === 'lunas') {
            $hasil = $hasil->filter(fn($h) => $h['status_lunas']);
        } elseif ($filterStatus === 'belum_lunas') {
            $hasil = $hasil->filter(fn($h) => !$h['status_lunas']);
        }
        if ($filterPpn === 'ada_ppn') {
            $hasil = $hasil->filter(fn($h) => $h['ada_ppn']);
        } elseif ($filterPpn === 'non_ppn') {
            $hasil = $hasil->filter(fn($h) => !$h['ada_ppn']);
        }
        $hasil = $hasil->values();

        // ── Build Spreadsheet ────────────────────────────────────────────────────
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Pembelian');

        // ── Styles helper ───────────────────────────────────────────────────────
        $headerProyekStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF991B1B']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFFFFFFF']]],
        ];
        $headerVendorStyle = [
            'font'      => ['bold' => true, 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
        ];
        $tableHeadStyle = [
            'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FF374151']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF9FAFB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
        ];
        $dataStyle = [
            'font'    => ['size' => 9],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE5E7EB']]],
        ];
        $subtotalStyle = [
            'font'      => ['bold' => true, 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFF7ED']],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFFCD34D']]],
        ];
        $grandTotalStyle = [
            'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F2937']],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF374151']]],
        ];
        $idxCols = ['A','B','C','D','E','F','G','H','I'];
        // Columns: No | Nama Barang | Satuan | Qty | Harga Satuan | Harga Akhir (inc PPN) | PPN % | DPP | Nominal PPN | Total HPP
        // → 10 cols A–J
        $cols = ['A','B','C','D','E','F','G','H','I','J'];

        // ── Title Row ───────────────────────────────────────────────────────────
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'RIWAYAT PEMBELIAN');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        $sheet->mergeCells('A2:J2');
        $filterDesc = 'Filter: ' . ($filterStatus === 'all' ? 'Semua Status' : ($filterStatus === 'lunas' ? 'Lunas' : 'Belum Lunas'));
        $filterDesc .= ' | PPN: ' . ($filterPpn === 'all' ? 'Semua' : ($filterPpn === 'ada_ppn' ? 'Ada PPN' : 'Non-PPN'));
        if ($search) $filterDesc .= ' | Cari: ' . $search;
        $filterDesc .= ' | Diekspor: ' . now()->format('d/m/Y H:i');
        $sheet->setCellValue('A2', $filterDesc);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 9, 'color' => ['argb' => 'FF6B7280'], 'italic' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $row = 4; // start data from row 4

        foreach ($hasil as $idx => $hasilRow) {
            $proyek  = $hasilRow['proyek'];
            $vendors = $hasilRow['vendors'];
            $noProyek = $idx + 1;

            // ── Proyek Header Row ────────────────────────────────────────────────
            $sheet->mergeCells("A{$row}:J{$row}");
            $statusLunas = $hasilRow['status_lunas'] ? 'LUNAS' : 'BELUM LUNAS';
            $ppnBadge    = $hasilRow['ada_ppn'] ? ' | Ada PPN' : '';
            $sheet->setCellValue("A{$row}",
                "#{$noProyek}  {$proyek->kode_proyek}  —  {$proyek->instansi}  |  {$proyek->kab_kota}  |  No. Penawaran: {$proyek->penawaranAktif->no_penawaran}  |  Status: {$proyek->status}  |  {$statusLunas}{$ppnBadge}"
            );
            $sheet->getStyle("A{$row}")->applyFromArray($headerProyekStyle);
            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;

            // ── Proyek Summary Row ───────────────────────────────────────────────
            $sheet->setCellValue("A{$row}", 'Total Nilai Pembelian');
            $sheet->setCellValue("B{$row}", $hasilRow['grand_total']);
            $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->setCellValue("D{$row}", 'Sudah Dibayar (Approved)');
            $sheet->setCellValue("E{$row}", $hasilRow['grand_bayar']);
            $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->setCellValue("G{$row}", 'Sisa');
            $sheet->setCellValue("H{$row}", $hasilRow['grand_sisa']);
            $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FF1F2937']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEFF6FF']],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
            ]);
            $row++;

            foreach ($vendors as $vendor) {
                $adaPpnVendor = collect($vendor['items'])->contains(fn($i) => $i['ada_ppn'] === true);

                // ── Vendor Sub-header ────────────────────────────────────────────
                $sheet->mergeCells("A{$row}:J{$row}");
                $ppnStatus = $adaPpnVendor ? 'Ada PPN' : (collect($vendor['items'])->contains(fn($i) => $i['ada_ppn'] === false) ? 'Non-PPN' : 'Belum Dikonfigurasi');
                $sheet->setCellValue("A{$row}",
                    "Vendor: {$vendor['vendor_nama']}  |  {$ppnStatus}  |  Total: " . number_format($vendor['total_harga'], 2) .
                    "  |  Dibayar: " . number_format($vendor['total_bayar'], 2) .
                    "  |  Sisa: " . number_format($vendor['sisa_bayar'], 2)
                );
                $sheet->getStyle("A{$row}")->applyFromArray($headerVendorStyle);
                $row++;

                // ── Table Header ─────────────────────────────────────────────────
                $headers = ['No', 'Nama Barang', 'Satuan', 'Qty', 'Harga Satuan', 'Harga Akhir (inc PPN)', 'PPN %', 'DPP', 'Nominal PPN', 'Total HPP'];
                foreach ($headers as $ci => $h_label) {
                    $sheet->setCellValue("{$cols[$ci]}{$row}", $h_label);
                }
                $sheet->getStyle("A{$row}:J{$row}")->applyFromArray($tableHeadStyle);
                $sheet->getRowDimension($row)->setRowHeight(22);
                $row++;

                // ── Item Rows ────────────────────────────────────────────────────
                foreach ($vendor['items'] as $iIdx => $item) {
                    $sheet->setCellValue("A{$row}", $iIdx + 1);
                    $sheet->setCellValue("B{$row}", $item['nama_barang']);
                    $sheet->setCellValue("C{$row}", $item['satuan']);
                    $sheet->setCellValue("D{$row}", $item['qty']);
                    $sheet->setCellValue("E{$row}", $item['harga_satuan']);
                    $sheet->setCellValue("F{$row}", $item['harga_akhir']);

                    if ($item['ada_ppn'] === true) {
                        $sheet->setCellValue("G{$row}", ($item['persen_ppn'] ?? 11) . '%');
                        $sheet->setCellValue("H{$row}", $item['harga_sebelum_ppn']);
                        $sheet->setCellValue("I{$row}", $item['nominal_ppn']);
                    } elseif ($item['ada_ppn'] === false) {
                        $sheet->setCellValue("G{$row}", 'Non-PPN');
                    } else {
                        $sheet->setCellValue("G{$row}", '—');
                    }

                    $sheet->setCellValue("J{$row}", $item['total_harga_hpp']);

                    // Number format for currency cols
                    foreach (['E','F','H','I','J'] as $nc) {
                        $sheet->getStyle("{$nc}{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                    }
                    $sheet->getStyle("A{$row}:J{$row}")->applyFromArray($dataStyle);
                    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    if ($item['ada_ppn'] === true) {
                        $sheet->getStyle("A{$row}:J{$row}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFBEB');
                    }
                    $row++;
                }

                // ── Subtotal Vendor Row ──────────────────────────────────────────
                $items      = collect($vendor['items']);
                $subDpp     = $adaPpnVendor ? $items->sum(fn($i) => floatval($i['harga_sebelum_ppn'] ?? 0)) : null;
                $subPpn     = $adaPpnVendor ? $items->sum(fn($i) => floatval($i['nominal_ppn'] ?? 0)) : null;
                $subTotal   = $vendor['total_harga'];

                $sheet->mergeCells("A{$row}:D{$row}");
                $sheet->setCellValue("A{$row}", 'Subtotal Vendor');
                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                // F col: sum of total_harga_hpp (same as total_harga for vendor)
                $sheet->setCellValue("F{$row}", $items->sum('total_harga_hpp'));
                $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                if ($adaPpnVendor) {
                    $sheet->setCellValue("H{$row}", $subDpp);
                    $sheet->setCellValue("I{$row}", $subPpn);
                    $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle("I{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                }
                $sheet->setCellValue("J{$row}", $subTotal);
                $sheet->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("A{$row}:J{$row}")->applyFromArray($subtotalStyle);
                $row++;
            }

            // ── Grand Total Row ──────────────────────────────────────────────────
            $sheet->mergeCells("A{$row}:I{$row}");
            $sheet->setCellValue("A{$row}", "GRAND TOTAL  —  {$proyek->kode_proyek}");
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue("J{$row}", $hasilRow['grand_total']);
            $sheet->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("A{$row}:J{$row}")->applyFromArray($grandTotalStyle);
            $row++;

            $row++; // blank spacer row between projects
        }

        // ── Column Widths ────────────────────────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(8);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(22);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(18);
        $sheet->getColumnDimension('J')->setWidth(20);

        // ── Freeze top rows ──────────────────────────────────────────────────────
        $sheet->freezePane('A4');

        // ── Output ──────────────────────────────────────────────────────────────
        $filename = 'riwayat-pembelian-' . now()->format('Ymd-His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
