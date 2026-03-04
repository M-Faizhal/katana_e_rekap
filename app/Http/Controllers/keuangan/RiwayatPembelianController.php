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
        // Layout:
        //   [Proyek header row]
        //     [Vendor header row]
        //       [Table header row]
        //       [Item rows ...]
        //     (next vendor ...)
        //   [blank spacer]
        //   (next proyek ...)
        //
        // Table columns (A–H): No | Nama Barang | Satuan | Qty | Harga Satuan | Total HPP | PPN % | Nominal PPN
        // ─────────────────────────────────────────────────────────────────────

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Pembelian');

        // Last data column
        $lastCol   = 'H';
        $colRange  = "A:{$lastCol}";
        $tableHeaders = ['No', 'Nama Barang', 'Satuan', 'Qty', 'Harga Satuan', 'Total HPP', 'PPN %', 'Nominal PPN'];
        $tCols     = ['A','B','C','D','E','F','G','H'];

        // Styles
        $styleProyek = [
            'font'      => ['bold' => true, 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD1D5DB']],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ];
        $styleVendor = [
            'font'      => ['bold' => true, 'size' => 9, 'italic' => true],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ];
        $styleThead  = [
            'font'      => ['bold' => true, 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEEF2FF']],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ];
        $styleData   = [
            'font'    => ['size' => 9],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];

        $row = 1;
        $no  = 1;

        foreach ($hasil as $idx => $hasilRow) {
            $proyek  = $hasilRow['proyek'];
            $vendors = $hasilRow['vendors'];

            // ── Proyek Header ────────────────────────────────────────────────────
            $statusLunas = $hasilRow['status_lunas'] ? 'LUNAS' : 'BELUM LUNAS';
            $sheet->mergeCells("A{$row}:{$lastCol}{$row}");
            $sheet->setCellValue("A{$row}",
                ($idx + 1) . '.  ' . $proyek->kode_proyek . '   —   ' . $proyek->instansi .
                '   |   ' . $proyek->kab_kota .
                '   |   ' . $statusLunas
            );
            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($styleProyek);
            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;

            foreach ($vendors as $vendor) {
                // ── Vendor Header ────────────────────────────────────────────────
                $sheet->mergeCells("A{$row}:{$lastCol}{$row}");
                $sheet->setCellValue("A{$row}", 'Vendor:  ' . $vendor['vendor_nama']);
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($styleVendor);
                $sheet->getRowDimension($row)->setRowHeight(16);
                $row++;

                // ── Table Header ─────────────────────────────────────────────────
                foreach ($tableHeaders as $ci => $h) {
                    $sheet->setCellValue("{$tCols[$ci]}{$row}", $h);
                }
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($styleThead);
                $sheet->getRowDimension($row)->setRowHeight(16);
                $row++;

                // ── Item Rows ────────────────────────────────────────────────────
                $subHpp  = 0;
                $subPpn  = 0;
                $adaPpnVendor = false;
                $persenPpn    = null;

                foreach ($vendor['items'] as $item) {
                    $sheet->setCellValue("A{$row}", $no++);
                    $sheet->setCellValue("B{$row}", $item['nama_barang']);
                    $sheet->setCellValue("C{$row}", $item['satuan']);
                    $sheet->setCellValue("D{$row}", $item['qty']);
                    $sheet->setCellValue("E{$row}", $item['harga_satuan']);
                    $sheet->setCellValue("F{$row}", $item['total_harga_hpp']);

                    if ($item['ada_ppn'] === true) {
                        $sheet->setCellValue("G{$row}", ($item['persen_ppn'] ?? 11) . '%');
                        $sheet->setCellValue("H{$row}", $item['nominal_ppn']);
                        $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                        $adaPpnVendor = true;
                        $persenPpn    = $item['persen_ppn'] ?? 11;
                        $subPpn      += floatval($item['nominal_ppn'] ?? 0);
                    } elseif ($item['ada_ppn'] === false) {
                        $sheet->setCellValue("G{$row}", 'Non-PPN');
                    } else {
                        $sheet->setCellValue("G{$row}", '-');
                    }

                    $subHpp += floatval($item['total_harga_hpp']);

                    $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($styleData);
                    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $row++;
                }

                // ── Subtotal rows (mirip gambar: Subtotal / PPN x% / Total) ───────
                $styleSubtotal = [
                    'font'      => ['size' => 9],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ];
                $styleTotalVendor = [
                    'font'      => ['bold' => true, 'size' => 9],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ];

                // Baris: Subtotal
                $sheet->mergeCells("A{$row}:E{$row}");
                $sheet->setCellValue("A{$row}", 'Subtotal');
                $sheet->setCellValue("F{$row}", $subHpp);
                $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($styleSubtotal);
                $row++;

                if ($adaPpnVendor) {
                    // Baris: PPN x%
                    $sheet->mergeCells("A{$row}:E{$row}");
                    $sheet->setCellValue("A{$row}", 'PPN ' . $persenPpn . '.0%');
                    $sheet->setCellValue("H{$row}", $subPpn);
                    $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($styleSubtotal);
                    $sheet->getStyle("A{$row}")->getFont()->getColor()->setARGB('FF6B7280');
                    $row++;
                }

                // Baris: Total
                $subTotal = $subHpp + $subPpn;
                $sheet->mergeCells("A{$row}:E{$row}");
                $sheet->setCellValue("A{$row}", 'Total');
                $sheet->setCellValue("F{$row}", $subTotal);
                $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($styleTotalVendor);
                $row++;

                $no = 1; // reset nomor per vendor
            }

            // ── 2 blank spacer rows antar proyek ─────────────────────────────────
            $row += 2;
        }

        // ── Column Widths ────────────────────────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(6);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(18);

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
