<?php

namespace App\Http\Controllers\marketing\Export;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class OmsetMarketingExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        // Ambil filter dari request
        $tahun        = $request->get('year');
        $picMarketing = $request->get('pic_marketing');
        $search       = $request->get('search');
        $label        = $request->get('label'); // 'internal', 'eksternal', atau kosong = semua

        // Normalise tahun: null / 'all' → semua tahun
        $filterTahun = ($tahun && $tahun !== 'all') ? (int) $tahun : null;

        // Query data proyek dengan filter
        // Menggunakan proyek_barang.harga_total + tahun_potensi,
        // konsisten dengan LaporanController
        $query = Proyek::with(['wilayah', 'adminMarketing', 'proyekBarang'])
            ->where('status', '!=', 'Gagal')
            ->orderBy('tahun_potensi', 'asc')
            ->orderBy('tanggal', 'asc');

        // Filter tahun_potensi
        if ($filterTahun) {
            $query->where('tahun_potensi', $filterTahun);
        }

        // Filter PIC marketing
        if ($picMarketing) {
            $query->whereHas('adminMarketing', function ($q) use ($picMarketing) {
                $q->where('nama', $picMarketing);
            });
        }

        // Filter label marketing (internal mencakup NULL)
        if ($label === 'internal') {
            $query->whereHas('adminMarketing', function ($q) {
                $q->where(function ($sub) {
                    $sub->where('label', 'internal')->orWhereNull('label');
                });
            });
        } elseif ($label === 'eksternal') {
            $query->whereHas('adminMarketing', function ($q) {
                $q->where('label', 'eksternal');
            });
        }

        // Filter pencarian (kode proyek / nama instansi)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_proyek', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%")
                  ->orWhere('kab_kota', 'like', "%{$search}%")
                  ->orWhereHas('wilayah', function ($wq) use ($search) {
                      $wq->where('instansi', 'like', "%{$search}%")
                         ->orWhere('nama_wilayah', 'like', "%{$search}%");
                  });
            });
        }

        $proyekList = $query->get();

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Set judul
        $judulTahun = $filterTahun ? 'TAHUN POTENSI ' . $filterTahun : 'SEMUA TAHUN';
        $sheet->setCellValue('A1', 'LAPORAN DATA OMSET MARKETING — ' . $judulTahun);
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Info filter
        $filterParts = [];
        if ($filterTahun)  $filterParts[] = 'Tahun Potensi: ' . $filterTahun;
        if ($picMarketing) $filterParts[] = 'PIC Marketing: ' . $picMarketing;
        if ($label)        $filterParts[] = 'Label: ' . ucfirst($label);
        if ($search)       $filterParts[] = 'Pencarian: ' . $search;
        $filterParts[] = 'Tanggal Export: ' . Carbon::now()->format('d/m/Y H:i');

        $sheet->setCellValue('A2', implode(' | ', $filterParts));
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);

        // Header tabel — 9 kolom (A–I)
        $row     = 4;
        $headers = [
            'No',
            'Tanggal',
            'Tahun Potensi',
            'Nama Instansi',
            'Nama Barang / Pengadaan',
            'Harga Total (Rp)',
            'Nomor Proyek',
            'PIC Marketing',
            'Label',
        ];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }

        // Style header — hijau sesuai tema Laporan Omset
        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '16A34A']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Data
        $row++;
        $no               = 1;
        $totalKeseluruhan = 0;

        foreach ($proyekList as $proyek) {
            // Nama instansi: gabungan instansi + kab_kota, fallback ke wilayah
            $instansi = '-';
            if ($proyek->instansi && $proyek->kab_kota) {
                $instansi = $proyek->instansi . ' - ' . $proyek->kab_kota;
            } elseif ($proyek->instansi) {
                $instansi = $proyek->instansi;
            } elseif ($proyek->kab_kota) {
                $instansi = $proyek->kab_kota;
            } elseif ($proyek->wilayah) {
                $instansi = $proyek->wilayah->instansi ?? $proyek->wilayah->nama_wilayah ?? '-';
            }

            $picNama      = $proyek->adminMarketing ? $proyek->adminMarketing->nama : '-';
            $labelNama    = $proyek->adminMarketing
                ? ucfirst($proyek->adminMarketing->label ?? 'internal')
                : 'Internal';
            $tanggal      = $proyek->tanggal ? Carbon::parse($proyek->tanggal)->format('d/m/Y') : '-';
            $tahunPotensi = $proyek->tahun_potensi ?: '-';

            // Ambil list barang dari proyek_barang
            $barangList = $proyek->proyekBarang;

            if ($barangList->count() > 0) {
                $startRow = $row;

                foreach ($barangList as $barang) {
                    $namaBarang        = $barang->nama_barang ?: '-';
                    $hargaTotal        = (float) ($barang->harga_total ?? 0);
                    $totalKeseluruhan += $hargaTotal;

                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $tanggal);
                    $sheet->setCellValue('C' . $row, $tahunPotensi);
                    $sheet->setCellValue('D' . $row, $instansi);
                    $sheet->setCellValue('E' . $row, $namaBarang);
                    $sheet->setCellValue('F' . $row, $hargaTotal);
                    $sheet->setCellValue('G' . $row, $proyek->kode_proyek ?: '-');
                    $sheet->setCellValue('H' . $row, $picNama);
                    $sheet->setCellValue('I' . $row, $labelNama);

                    // Format angka harga
                    $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');

                    $row++;
                }

                // Merge kolom non-barang jika proyek punya lebih dari 1 barang
                if ($barangList->count() > 1) {
                    $endRow = $row - 1;
                    foreach (['A', 'B', 'C', 'D', 'G', 'H', 'I'] as $mergeCol) {
                        $sheet->mergeCells($mergeCol . $startRow . ':' . $mergeCol . $endRow);
                        $sheet->getStyle($mergeCol . $startRow)
                              ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    }
                }

                $no++;
            }
        }

        // Baris total keseluruhan
        $sheet->setCellValue('A' . $row, 'TOTAL KESELURUHAN');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->setCellValue('F' . $row, $totalKeseluruhan);
        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
            'font'    => ['bold' => true],
            'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCFCE7']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');

        // Borders seluruh tabel
        $sheet->getStyle('A4:I' . $row)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Auto-size kolom lalu set lebar minimum kolom kunci
        foreach (range('A', 'I') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }
        $sheet->getColumnDimension('D')->setWidth(32); // Nama Instansi
        $sheet->getColumnDimension('E')->setWidth(38); // Nama Barang
        $sheet->getColumnDimension('G')->setWidth(18); // Nomor Proyek

        // Nama file
        $suffixTahun = $filterTahun ? '_' . $filterTahun : '_SemuaTahun';
        $filename    = 'Data_Omset_Marketing' . $suffixTahun . '_' . Carbon::now()->format('YmdHis') . '.xlsx';

        // Output ke browser
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}
