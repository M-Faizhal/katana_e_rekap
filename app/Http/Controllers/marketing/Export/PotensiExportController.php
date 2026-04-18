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
            ->where(function ($q) {
                $q->where('status', 'Menunggu')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'Penawaran')
                         ->whereHas('penawaranList', function ($p) {
                             $p->where('status', 'Menunggu');
                         })
                         ->whereDoesntHave('penawaranList', function ($p) {
                             $p->where('status', 'ACC');
                         });
                  });
            })
            ->orderBy('tanggal', 'desc');

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

        // Judul
        $sheet->setCellValue('A1', 'LAPORAN DATA POTENSI');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Info filter
        $filterInfo = 'Filter: ';
        if ($tahun) $filterInfo .= "Tahun {$tahun} | ";
        if ($picMarketing) $filterInfo .= "PIC: {$picMarketing} | ";
        if ($search) $filterInfo .= "Pencarian: {$search} | ";
        $filterInfo .= 'Tanggal Export: ' . Carbon::now()->format('d/m/Y H:i');

        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);

        // Header tabel
        $row = 4;
        $headers = ['No', 'Tanggal', 'Tahun Potensi', 'Triwulan', 'Nama Instansi', 'Nama Pengadaan', 'Harga (Rp)', 'Nomor Proyek', 'PIC Marketing'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }

        // Style header
        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
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
            $triwulan = $proyek->triwulan ? 'TW ' . $proyek->triwulan : '-';

            $barangList = $proyek->proyekBarang;

            if ($barangList->count() > 0) {
                $startRow = $row;

                foreach ($barangList as $barang) {
                    $namaBarang = $barang->nama_barang;
                    $hargaTotal = $barang->harga_total;
                    $totalKeseluruhan += $hargaTotal;

                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $tanggal);
                    $sheet->setCellValue('C' . $row, $tahunPotensi);
                    $sheet->setCellValue('D' . $row, $triwulan);
                    $sheet->setCellValue('E' . $row, $instansi);
                    $sheet->setCellValue('F' . $row, $namaBarang);
                    $sheet->setCellValue('G' . $row, $hargaTotal);
                    $sheet->setCellValue('H' . $row, $proyek->kode_proyek);
                    $sheet->setCellValue('I' . $row, $picMarketing);

                    $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0');

                    $row++;
                }

                if ($barangList->count() > 1) {
                    $endRow = $row - 1;
                    $sheet->mergeCells('A' . $startRow . ':A' . $endRow);
                    $sheet->mergeCells('B' . $startRow . ':B' . $endRow);
                    $sheet->mergeCells('C' . $startRow . ':C' . $endRow);
                    $sheet->mergeCells('D' . $startRow . ':D' . $endRow);
                    $sheet->mergeCells('E' . $startRow . ':E' . $endRow);
                    $sheet->mergeCells('H' . $startRow . ':H' . $endRow);
                    $sheet->mergeCells('I' . $startRow . ':I' . $endRow);

                    foreach (['A', 'B', 'C', 'D', 'E', 'H', 'I'] as $c) {
                        $sheet->getStyle($c . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    }
                }

                $no++;
            }
        }

        // Total keseluruhan
        $sheet->setCellValue('A' . $row, 'TOTAL KESELURUHAN');
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $sheet->setCellValue('G' . $row, $totalKeseluruhan);
        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0');

        // Borders untuk semua data
        $sheet->getStyle('A4:I' . $row)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Auto width columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set minimum width untuk kolom tertentu
        $sheet->getColumnDimension('E')->setWidth(30); // Instansi
        $sheet->getColumnDimension('F')->setWidth(35); // Nama Pengadaan
        $sheet->getColumnDimension('H')->setWidth(20); // Nomor Proyek

        // Generate filename
        $filename = 'Data_Potensi_' . Carbon::now()->format('YmdHis') . '.xlsx';

        // Save & download
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}