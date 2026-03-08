<?php

namespace App\Exports;

use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class ProdukPurchasingExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function export()
    {
        // ── Query produk ─────────────────────────────────────────────────────────
        $query = Barang::with('vendor');

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%")
                  ->orWhere('spesifikasi', 'LIKE', "%{$search}%")
                  ->orWhereHas('vendor', fn($v) => $v->where('nama_vendor', 'LIKE', "%{$search}%"));
            });
        }
        if (!empty($this->filters['kategori']))      $query->where('kategori', $this->filters['kategori']);
        if (!empty($this->filters['vendor']))        $query->where('id_vendor', $this->filters['vendor']);
        if (!empty($this->filters['pdn_tkdn_impor'])) $query->where('pdn_tkdn_impor', $this->filters['pdn_tkdn_impor']);
        if (!empty($this->filters['min_harga']))     $query->where('harga_vendor', '>=', $this->filters['min_harga']);
        if (!empty($this->filters['max_harga']))     $query->where('harga_vendor', '<=', $this->filters['max_harga']);

        $sortBy    = $this->filters['sort_by']    ?? 'created_at';
        $sortOrder = $this->filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $produkList = $query->get();

        // ── Ambil harga_marketing (harga_yang_diharapkan terbaru) per id_barang ─
        $hargaMap = DB::table('kalkulasi_hps as k')
            ->join(
                DB::raw('(SELECT id_barang, MAX(id_kalkulasi) as max_id FROM kalkulasi_hps WHERE id_barang IS NOT NULL GROUP BY id_barang) as latest'),
                fn($j) => $j->on('k.id_barang', '=', 'latest.id_barang')->on('k.id_kalkulasi', '=', 'latest.max_id')
            )
            ->pluck('k.harga_yang_diharapkan', 'k.id_barang');

        // ── Build Spreadsheet ────────────────────────────────────────────────────
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Produk Purchasing');

        // ── Title ───────────────────────────────────────────────────────────────
        $lastCol = 'K';
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'DAFTAR PRODUK — PURCHASING');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Filter info ──────────────────────────────────────────────────────────
        $filterInfo = 'Filter: ';
        if (!empty($this->filters['kategori']))      $filterInfo .= "Kategori: {$this->filters['kategori']} | ";
        if (!empty($this->filters['vendor'])) {
            $v = \App\Models\Vendor::find($this->filters['vendor']);
            if ($v) $filterInfo .= "Vendor: {$v->nama_vendor} | ";
        }
        if (!empty($this->filters['pdn_tkdn_impor'])) $filterInfo .= "PDN/TKDN/Impor: {$this->filters['pdn_tkdn_impor']} | ";
        if (!empty($this->filters['search']))        $filterInfo .= "Pencarian: {$this->filters['search']} | ";
        $filterInfo .= 'Diekspor: ' . Carbon::now()->format('d/m/Y H:i');

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Table Header ─────────────────────────────────────────────────────────
        $headerRow = 4;
        $headers   = [
            'A' => 'No',
            'B' => 'Nama Barang',
            'C' => 'Brand',
            'D' => 'Spesifikasi',
            'E' => 'Garansi',
            'F' => 'PDN/TKDN/Impor',
            'G' => 'Harga Vendor',
            'H' => 'Harga Jual',
            'I' => 'Harga Inaproc',
            'J' => 'Link Produk',
            'K' => 'Gambar',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}{$headerRow}", $label);
        }
        $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDC2626']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(20);
        $sheet->freezePane('A5');

        // ── Data Rows ────────────────────────────────────────────────────────────
        $row = $headerRow + 1;
        $no  = 1;
        $currencyFmt = '#,##0.00';

        foreach ($produkList as $produk) {
            $sheet->getRowDimension($row)->setRowHeight(60);

            // No
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->getStyle("A{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            // Nama Barang
            $sheet->setCellValue("B{$row}", $produk->nama_barang);
            $sheet->getStyle("B{$row}")->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);

            // Brand
            $sheet->setCellValue("C{$row}", $produk->brand ?? '-');
            $sheet->getStyle("C{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // Spesifikasi
            $sheet->setCellValue("D{$row}", $produk->spesifikasi ?? '-');
            $sheet->getStyle("D{$row}")->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);

            // Garansi
            $sheet->setCellValue("E{$row}", $produk->garansi ?? '-');
            $sheet->getStyle("E{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // PDN/TKDN/Impor
            $sheet->setCellValue("F{$row}", $produk->pdn_tkdn_impor ?? '-');
            $sheet->getStyle("F{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            // Harga Vendor
            if ($produk->harga_vendor) {
                $sheet->setCellValue("G{$row}", (float) $produk->harga_vendor);
                $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode($currencyFmt);
            } else {
                $sheet->setCellValue("G{$row}", '-');
            }
            $sheet->getStyle("G{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // Harga Jual (harga_yang_diharapkan dari kalkulasi_hps)
            $hargaJual = isset($hargaMap[$produk->id_barang]) ? (float) $hargaMap[$produk->id_barang] : null;
            if ($hargaJual) {
                $sheet->setCellValue("H{$row}", $hargaJual);
                $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode($currencyFmt);
            } else {
                $sheet->setCellValue("H{$row}", '-');
            }
            $sheet->getStyle("H{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // Harga Inaproc
            if ($produk->harga_pasaran_inaproc) {
                $sheet->setCellValue("I{$row}", (float) $produk->harga_pasaran_inaproc);
                $sheet->getStyle("I{$row}")->getNumberFormat()->setFormatCode($currencyFmt);
            } else {
                $sheet->setCellValue("I{$row}", '-');
            }
            $sheet->getStyle("I{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // Link Produk
            if ($produk->link_produk) {
                $sheet->setCellValue("J{$row}", $produk->link_produk);
                $sheet->getCell("J{$row}")->getHyperlink()->setUrl($produk->link_produk);
                $sheet->getStyle("J{$row}")->getFont()->setUnderline(true)->getColor()->setRGB('0000FF');
            } else {
                $sheet->setCellValue("J{$row}", '-');
            }
            $sheet->getStyle("J{$row}")->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);

            // Gambar
            if ($produk->foto_barang && file_exists(storage_path('app/public/' . $produk->foto_barang))) {
                try {
                    $drawing = new Drawing();
                    $drawing->setName('Produk');
                    $drawing->setDescription('Foto Produk');
                    $drawing->setPath(storage_path('app/public/' . $produk->foto_barang));
                    $drawing->setHeight(50);
                    $drawing->setCoordinates("K{$row}");
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                } catch (\Exception $e) {
                    $sheet->setCellValue("K{$row}", 'No Image');
                }
            } else {
                $sheet->setCellValue("K{$row}", 'No Image');
            }
            $sheet->getStyle("K{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            // Stripe warna selang-seling
            if ($no % 2 === 0) {
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFAFAFA']],
                ]);
            }

            $row++;
        }

        // ── Borders semua data ───────────────────────────────────────────────────
        $sheet->getStyle("A{$headerRow}:{$lastCol}" . ($row - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
        ]);

        // ── Column Widths ────────────────────────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(5);   // No
        $sheet->getColumnDimension('B')->setWidth(35);  // Nama Barang
        $sheet->getColumnDimension('C')->setWidth(18);  // Brand
        $sheet->getColumnDimension('D')->setWidth(40);  // Spesifikasi
        $sheet->getColumnDimension('E')->setWidth(18);  // Garansi
        $sheet->getColumnDimension('F')->setWidth(15);  // PDN/TKDN/Impor
        $sheet->getColumnDimension('G')->setWidth(18);  // Harga Vendor
        $sheet->getColumnDimension('H')->setWidth(18);  // Harga Jual
        $sheet->getColumnDimension('I')->setWidth(18);  // Harga Inaproc
        $sheet->getColumnDimension('J')->setWidth(40);  // Link Produk
        $sheet->getColumnDimension('K')->setWidth(15);  // Gambar

        // ── Output ───────────────────────────────────────────────────────────────
        $filename = 'Produk_Purchasing_' . Carbon::now()->format('YmdHis') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
