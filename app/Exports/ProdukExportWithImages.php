<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProdukExportWithImages
{
    protected $produk;

    public function __construct($produk)
    {
        $this->produk = $produk;
    }

    public function generateSpreadsheet()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Cyber KATANA')
            ->setTitle('Daftar Produk')
            ->setSubject('Export Daftar Produk')
            ->setDescription('Export daftar produk dengan gambar');
        
        // Set column headers
        $headers = [
            'A1' => 'No',
            'B1' => 'Nama Barang',
            'C1' => 'Vendor',
            'D1' => 'Brand',
            'E1' => 'Kategori',
            'F1' => 'Harga Pasaran Inaproc',
            'G1' => 'Harga Vendor',
            'H1' => 'PDN/TKDN/Impor',
            'I1' => 'Garansi',
            'J1' => 'Estimasi Ketersediaan',
            'K1' => 'Link Produk',
            'L1' => 'Gambar',
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            ],
        ]);
        
        $sheet->getRowDimension(1)->setRowHeight(25);
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(25);
        $sheet->getColumnDimension('K')->setWidth(40);
        $sheet->getColumnDimension('L')->setWidth(15);
        
        // Fill data
        $row = 2;
        foreach ($this->produk as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_barang);
            $sheet->setCellValue('C' . $row, $item->vendor->nama_vendor ?? '-');
            $sheet->setCellValue('D' . $row, $item->brand ?? '-');
            $sheet->setCellValue('E' . $row, $item->kategori ?? '-');
            $sheet->setCellValue('F' . $row, $item->harga_pasaran_inaproc ? 'Rp ' . number_format($item->harga_pasaran_inaproc, 0, ',', '.') : '-');
            $sheet->setCellValue('G' . $row, 'Rp ' . number_format($item->harga_vendor, 0, ',', '.'));
            $sheet->setCellValue('H' . $row, $item->pdn_tkdn_impor ?? '-');
            $sheet->setCellValue('I' . $row, $item->garansi ?? '-');
            $sheet->setCellValue('J' . $row, $item->estimasi_ketersediaan ?? '-');
            $sheet->setCellValue('K' . $row, $item->link_produk ?? '-');
            
            // Add image if exists
            if ($item->foto_barang) {
                $imagePath = storage_path('app/public/' . $item->foto_barang);
                
                if (file_exists($imagePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Product Image');
                    $drawing->setDescription('Product Image');
                    $drawing->setPath($imagePath);
                    $drawing->setHeight(50);
                    $drawing->setCoordinates('L' . $row);
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue('L' . $row, 'No Image');
                }
            } else {
                $sheet->setCellValue('L' . $row, 'No Image');
            }
            
            // Style data rows
            $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);
            
            // Set row height for images
            $sheet->getRowDimension($row)->setRowHeight(60);
            
            // Center align specific columns
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }
        
        return $spreadsheet;
    }
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
        
        $sortBy = $this->filters['sort_by'] ?? 'created_at';
        $sortOrder = $this->filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        $this->produk = $query->get();
    }

    public function collection()
    {
        return $this->produk->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'nama_barang' => $item->nama_barang,
                'vendor' => $item->vendor->nama_vendor ?? '-',
                'brand' => $item->brand ?? '-',
                'kategori' => $item->kategori ?? '-',
                'harga_inaproc' => $item->harga_pasaran_inaproc ? 'Rp ' . number_format($item->harga_pasaran_inaproc, 0, ',', '.') : '-',
                'harga_vendor' => 'Rp ' . number_format($item->harga_vendor, 0, ',', '.'),
                'pdn_tkdn_impor' => $item->pdn_tkdn_impor ?? '-',
                'garansi' => $item->garansi ?? '-',
                'estimasi_ketersediaan' => $item->estimasi_ketersediaan ?? '-',
                'link_produk' => $item->link_produk ?? '-',
                'gambar' => '', // Placeholder for image
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Vendor',
            'Brand',
            'Kategori',
            'Harga Pasaran Inaproc',
            'Harga Vendor',
            'PDN/TKDN/Impor',
            'Garansi',
            'Estimasi Ketersediaan',
            'Link Produk',
            'Gambar',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '991B1B'], // Red 800
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Style data rows
        $rowCount = $this->produk->count() + 1;
        for ($i = 2; $i <= $rowCount; $i++) {
            $sheet->getStyle("A{$i}:L{$i}")->applyFromArray([
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);
            
            // Set row height for images (60 pixels = ~45 points)
            $sheet->getRowDimension($i)->setRowHeight(60);
        }

        // Center align columns
        $sheet->getStyle('A2:A' . $rowCount)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H2:H' . $rowCount)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L2:L' . $rowCount)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // No
            'B' => 30,  // Nama Barang
            'C' => 25,  // Vendor
            'D' => 20,  // Brand
            'E' => 15,  // Kategori
            'F' => 20,  // Harga Inaproc
            'G' => 20,  // Harga Vendor
            'H' => 15,  // PDN/TKDN/Impor
            'I' => 20,  // Garansi
            'J' => 25,  // Estimasi Ketersediaan
            'K' => 35,  // Link Produk
            'L' => 15,  // Gambar
        ];
    }

    public function drawings()
    {
        $drawings = [];
        
        foreach ($this->produk as $index => $item) {
            if ($item->foto_barang && file_exists(storage_path('app/public/' . $item->foto_barang))) {
                $drawing = new Drawing();
                $drawing->setName('Product Image');
                $drawing->setDescription('Product Image');
                $drawing->setPath(storage_path('app/public/' . $item->foto_barang));
                $drawing->setHeight(50); // Height in pixels
                $drawing->setCoordinates('L' . ($index + 2)); // Column L, row index + 2 (header is row 1)
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(5);
                
                $drawings[] = $drawing;
            }
        }
        
        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto-filter
                $event->sheet->setAutoFilter('A1:L1');
                
                // Freeze first row
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
