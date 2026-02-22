<?php

namespace App\Exports;

use App\Models\Barang;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class ProdukExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function export()
    {
        // Query data produk dengan filter
        $query = Barang::with('vendor');

        // Apply filters
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%")
                  ->orWhere('spesifikasi', 'LIKE', "%{$search}%")
                  ->orWhereHas('vendor', function($vendorQuery) use ($search) {
                      $vendorQuery->where('nama_vendor', 'LIKE', "%{$search}%");
                  });
            });
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('kategori', $this->filters['kategori']);
        }

        if (!empty($this->filters['vendor'])) {
            $query->where('id_vendor', $this->filters['vendor']);
        }

        if (!empty($this->filters['pdn_tkdn_impor'])) {
            $query->where('pdn_tkdn_impor', $this->filters['pdn_tkdn_impor']);
        }

        if (!empty($this->filters['min_harga'])) {
            $query->where('harga_vendor', '>=', $this->filters['min_harga']);
        }

        if (!empty($this->filters['max_harga'])) {
            $query->where('harga_vendor', '<=', $this->filters['max_harga']);
        }

        // Sort
        $sortBy = $this->filters['sort_by'] ?? 'created_at';
        $sortOrder = $this->filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $produkList = $query->get();

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul
        $sheet->setCellValue('A1', 'DAFTAR PRODUK');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Info filter
        $filterInfo = 'Filter: ';
        if (!empty($this->filters['kategori'])) $filterInfo .= "Kategori: {$this->filters['kategori']} | ";
        if (!empty($this->filters['vendor'])) {
            $vendor = \App\Models\Vendor::find($this->filters['vendor']);
            if ($vendor) $filterInfo .= "Vendor: {$vendor->nama_vendor} | ";
        }
        if (!empty($this->filters['pdn_tkdn_impor'])) $filterInfo .= "PDN/TKDN/Impor: {$this->filters['pdn_tkdn_impor']} | ";
        if (!empty($this->filters['search'])) $filterInfo .= "Pencarian: {$this->filters['search']} | ";
        $filterInfo .= 'Tanggal Export: ' . Carbon::now()->format('d/m/Y H:i');

        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);

        // Header tabel
        $row = 4;
        $headers = [
            'No',
            'Nama Barang',
            'Brand',
            'Spesifikasi',
            'Garansi',
            'PDN/TKDN/Impor',
            'Link Produk',
            'Gambar'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }

        // Style header
        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Data
        $row++;
        $no = 1;

        foreach ($produkList as $produk) {
            // Set row height untuk gambar
            $sheet->getRowDimension($row)->setRowHeight(60);

            // No
            $sheet->setCellValue('A' . $row, $no);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                                        ->setVertical(Alignment::VERTICAL_CENTER);

            // Nama Barang
            $sheet->setCellValue('B' . $row, $produk->nama_barang);
            $sheet->getStyle('B' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
                                                        ->setWrapText(true);

            // Brand
            $sheet->setCellValue('C' . $row, $produk->brand ?? '-');
            $sheet->getStyle('C' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // Spesifikasi
            $sheet->setCellValue('D' . $row, $produk->spesifikasi ?? '-');
            $sheet->getStyle('D' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
                                                        ->setWrapText(true);

            // Garansi
            $sheet->setCellValue('E' . $row, $produk->garansi ?? '-');
            $sheet->getStyle('E' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // PDN/TKDN/Impor
            $sheet->setCellValue('F' . $row, $produk->pdn_tkdn_impor ?? '-');
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                                        ->setVertical(Alignment::VERTICAL_CENTER);

            // Link Produk
            if ($produk->link_produk) {
                $sheet->setCellValue('G' . $row, $produk->link_produk);
                $sheet->getCell('G' . $row)->getHyperlink()->setUrl($produk->link_produk);
                $sheet->getStyle('G' . $row)->getFont()->setUnderline(true)->getColor()->setRGB('0000FF');
            } else {
                $sheet->setCellValue('G' . $row, '-');
            }
            $sheet->getStyle('G' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
                                                        ->setWrapText(true);

            // Gambar Produk (di kolom terakhir)
            if ($produk->foto_barang && file_exists(storage_path('app/public/' . $produk->foto_barang))) {
                try {
                    $drawing = new Drawing();
                    $drawing->setName('Produk');
                    $drawing->setDescription('Foto Produk');
                    $drawing->setPath(storage_path('app/public/' . $produk->foto_barang));
                    $drawing->setHeight(50);
                    $drawing->setCoordinates('H' . $row);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                } catch (\Exception $e) {
                    $sheet->setCellValue('H' . $row, 'No Image');
                }
            } else {
                $sheet->setCellValue('H' . $row, 'No Image');
            }
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                                        ->setVertical(Alignment::VERTICAL_CENTER);

            $row++;
            $no++;
        }

        // Borders untuk semua data
        $lastRow = $row - 1;
        $sheet->getStyle('A4:H' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);   // No
        $sheet->getColumnDimension('B')->setWidth(35);  // Nama Barang
        $sheet->getColumnDimension('C')->setWidth(20);  // Brand
        $sheet->getColumnDimension('D')->setWidth(40);  // Spesifikasi
        $sheet->getColumnDimension('E')->setWidth(20);  // Garansi
        $sheet->getColumnDimension('F')->setWidth(15);  // PDN/TKDN/Impor
        $sheet->getColumnDimension('G')->setWidth(40);  // Link Produk
        $sheet->getColumnDimension('H')->setWidth(15);  // Gambar

        // Generate filename
        $filename = 'Daftar_Produk_' . Carbon::now()->format('YmdHis') . '.xlsx';

        // Save file
        $writer = new Xlsx($spreadsheet);
        
        // Set headers untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
