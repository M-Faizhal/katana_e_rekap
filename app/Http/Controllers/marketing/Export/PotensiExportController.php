<?php

namespace App\Http\Controllers\marketing\Export;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\ProyekBarang;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class PotensiExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        // Ambil filter dari request
        $tahun = $request->get('tahun');
        $picMarketing = $request->get('pic_marketing');
        $search = $request->get('search');

        // Query data proyek dengan filter
        $query = Proyek::with(['wilayah', 'adminMarketing', 'proyekBarang'])
            ->where('potensi', 'ya')
            ->where('status', 'Menunggu')  // Filter hanya status Menunggu
            ->orderBy('tanggal', 'desc');

        // Apply filters
        if ($tahun) {
            $query->where('tahun_potensi', $tahun);
        }

        if ($picMarketing) {
            $query->whereHas('adminMarketing', function($q) use ($picMarketing) {
                $q->where('nama', $picMarketing);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode_proyek', 'like', "%{$search}%")
                  ->orWhereHas('wilayah', function($wq) use ($search) {
                      $wq->where('instansi', 'like', "%{$search}%")
                         ->orWhere('nama_wilayah', 'like', "%{$search}%");
                  });
            });
        }

        $proyekList = $query->get();

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul
        $sheet->setCellValue('A1', 'LAPORAN DATA POTENSI');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Info filter
        $filterInfo = 'Filter: ';
        if ($tahun) $filterInfo .= "Tahun {$tahun} | ";
        if ($picMarketing) $filterInfo .= "PIC: {$picMarketing} | ";
        if ($search) $filterInfo .= "Pencarian: {$search} | ";
        $filterInfo .= 'Tanggal Export: ' . Carbon::now()->format('d/m/Y H:i');

        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);

        // Header tabel
        $row = 4;
        $headers = ['No', 'Tanggal', 'Tahun Potensi', 'Nama Instansi', 'Nama Pengadaan', 'Harga (Rp)', 'Nomor Proyek', 'PIC Marketing'];
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
        $totalKeseluruhan = 0;

        foreach ($proyekList as $proyek) {
            // Ambil nama instansi dari kombinasi instansi dan kab_kota proyek
            $instansi = '-';
            if ($proyek->instansi && $proyek->kab_kota) {
                $instansi = $proyek->instansi . ' - ' . $proyek->kab_kota;
            } elseif ($proyek->instansi) {
                $instansi = $proyek->instansi;
            } elseif ($proyek->kab_kota) {
                $instansi = $proyek->kab_kota;
            }
            
            $picMarketing = $proyek->adminMarketing ? $proyek->adminMarketing->nama : '-';
            $tanggal = Carbon::parse($proyek->tanggal)->format('d/m/Y');
            $tahunPotensi = $proyek->tahun_potensi ?: '-';

            // Ambil list barang dari proyek_barang
            $barangList = $proyek->proyekBarang;

            if ($barangList->count() > 0) {
                // Group barang berdasarkan proyek
                $startRow = $row;
                
                foreach ($barangList as $barang) {
                    $namaBarang = $barang->nama_barang;
                    $hargaTotal = $barang->harga_total;
                    $totalKeseluruhan += $hargaTotal;

                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $tanggal);
                    $sheet->setCellValue('C' . $row, $tahunPotensi);
                    $sheet->setCellValue('D' . $row, $instansi);
                    $sheet->setCellValue('E' . $row, $namaBarang);
                    $sheet->setCellValue('F' . $row, $hargaTotal);
                    $sheet->setCellValue('G' . $row, $proyek->kode_proyek);
                    $sheet->setCellValue('H' . $row, $picMarketing);

                    // Format harga
                    $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');

                    $row++;
                }

                // Merge cells untuk info proyek yang sama
                if ($barangList->count() > 1) {
                    $endRow = $row - 1;
                    $sheet->mergeCells('A' . $startRow . ':A' . $endRow); // No
                    $sheet->mergeCells('B' . $startRow . ':B' . $endRow); // Tanggal
                    $sheet->mergeCells('C' . $startRow . ':C' . $endRow); // Tahun
                    $sheet->mergeCells('D' . $startRow . ':D' . $endRow); // Instansi
                    $sheet->mergeCells('G' . $startRow . ':G' . $endRow); // Nomor Proyek
                    $sheet->mergeCells('H' . $startRow . ':H' . $endRow); // PIC
                    
                    // Center alignment untuk merged cells
                    $sheet->getStyle('A' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('B' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('C' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('D' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('G' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('H' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }

                $no++;
            }
        }

        // Total keseluruhan
        $sheet->setCellValue('A' . $row, 'TOTAL KESELURUHAN');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->setCellValue('F' . $row, $totalKeseluruhan);
        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');

        // Borders untuk semua data
        $sheet->getStyle('A4:H' . ($row))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Auto width columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set minimum width untuk kolom tertentu
        $sheet->getColumnDimension('D')->setWidth(30); // Instansi
        $sheet->getColumnDimension('E')->setWidth(35); // Nama Pengadaan
        $sheet->getColumnDimension('G')->setWidth(20); // Nomor Proyek

        // Generate filename
        $filename = 'Data_Potensi_' . Carbon::now()->format('YmdHis') . '.xlsx';

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
