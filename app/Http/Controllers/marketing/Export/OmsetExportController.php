<?php

namespace App\Http\Controllers\marketing\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class OmsetExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        // Ambil filter tahun dari request
        $tahun = $request->get('year');
        
        // Jika tidak ada tahun atau 'all', gunakan tahun sekarang
        if (!$tahun || $tahun === 'all') {
            $tahun = Carbon::now()->year;
        }

        // Query data omset berdasarkan tanggal penawaran ACC
        // Data diambil dari kalkulasi_hps yang join dengan proyek dan penawaran
        $omsetData = DB::table('kalkulasi_hps')
            ->join('proyek', 'kalkulasi_hps.id_proyek', '=', 'proyek.id_proyek')
            ->join('penawaran', 'proyek.id_proyek', '=', 'penawaran.id_proyek')
            ->leftJoin('users', 'proyek.id_admin_marketing', '=', 'users.id_user')
            ->leftJoin('barang', 'kalkulasi_hps.id_barang', '=', 'barang.id_barang')
            ->leftJoin('wilayah', 'proyek.id_wilayah', '=', 'wilayah.id_wilayah')
            ->where('penawaran.status', 'ACC')
            ->whereYear('penawaran.tanggal_penawaran', $tahun)
            ->select(
                'proyek.id_proyek',
                'penawaran.tanggal_penawaran',
                'proyek.instansi',
                'proyek.kab_kota',
                'wilayah.instansi as wilayah_instansi',
                'barang.nama_barang',
                'kalkulasi_hps.hps',
                'proyek.kode_proyek',
                'users.nama as pic_marketing'
            )
            ->orderBy('proyek.id_proyek', 'asc')
            ->orderBy('penawaran.tanggal_penawaran', 'asc')
            ->get();

        // Kelompokkan data berdasarkan proyek
        $proyekGrouped = [];
        foreach ($omsetData as $data) {
            $proyekId = $data->id_proyek;
            if (!isset($proyekGrouped[$proyekId])) {
                $proyekGrouped[$proyekId] = [
                    'tanggal_penawaran' => $data->tanggal_penawaran,
                    'instansi' => $data->instansi,
                    'kab_kota' => $data->kab_kota,
                    'wilayah_instansi' => $data->wilayah_instansi,
                    'kode_proyek' => $data->kode_proyek,
                    'pic_marketing' => $data->pic_marketing,
                    'items' => []
                ];
            }
            $proyekGrouped[$proyekId]['items'][] = [
                'nama_barang' => $data->nama_barang,
                'hps' => $data->hps
            ];
        }

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul
        $sheet->setCellValue('A1', 'LAPORAN DATA OMSET TAHUN ' . $tahun);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Info tanggal export
        $sheet->setCellValue('A2', 'Tanggal Export: ' . Carbon::now()->format('d/m/Y H:i'));
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);

        // Header tabel
        $row = 4;
        $headers = ['Nomor', 'Tanggal SP', 'Nama Dinas', 'Nama Pengadaan', 'Nilai SP (Rp)', 'Nomor Proyek', 'PIC Marketing'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }

        // Style header
        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '16A34A']], // Green
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Data
        $row++;
        $no = 1;
        $totalOmset = 0;

        foreach ($proyekGrouped as $proyek) {
            // Nama Dinas: prioritas dari proyek.instansi - proyek.kab_kota, fallback ke wilayah.instansi
            $namaDinas = '-';
            if ($proyek['instansi'] && $proyek['kab_kota']) {
                $namaDinas = $proyek['instansi'] . ' - ' . $proyek['kab_kota'];
            } elseif ($proyek['instansi']) {
                $namaDinas = $proyek['instansi'];
            } elseif ($proyek['wilayah_instansi']) {
                $namaDinas = $proyek['wilayah_instansi'];
            } elseif ($proyek['kab_kota']) {
                $namaDinas = $proyek['kab_kota'];
            }

            $tanggalSp = Carbon::parse($proyek['tanggal_penawaran'])->format('d/m/Y');
            $nomorProyek = $proyek['kode_proyek'] ?: '-';
            $picMarketing = $proyek['pic_marketing'] ?: '-';

            $items = $proyek['items'];
            $startRow = $row;

            // Loop untuk setiap item barang dalam proyek
            foreach ($items as $item) {
                $namaPengadaan = $item['nama_barang'] ?: '-';
                $nilaiSp = $item['hps'] ?: 0;
                $totalOmset += $nilaiSp;

                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $tanggalSp);
                $sheet->setCellValue('C' . $row, $namaDinas);
                $sheet->setCellValue('D' . $row, $namaPengadaan);
                $sheet->setCellValue('E' . $row, $nilaiSp);
                $sheet->setCellValue('F' . $row, $nomorProyek);
                $sheet->setCellValue('G' . $row, $picMarketing);

                // Format harga
                $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0');

                // Borders untuk row
                $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                $row++;
            }

            // Merge cells untuk info proyek yang sama jika ada lebih dari 1 item
            if (count($items) > 1) {
                $endRow = $row - 1;
                $sheet->mergeCells('A' . $startRow . ':A' . $endRow); // Nomor
                $sheet->mergeCells('B' . $startRow . ':B' . $endRow); // Tanggal SP
                $sheet->mergeCells('C' . $startRow . ':C' . $endRow); // Nama Dinas
                $sheet->mergeCells('F' . $startRow . ':F' . $endRow); // Nomor Proyek
                $sheet->mergeCells('G' . $startRow . ':G' . $endRow); // PIC Marketing
                
                // Center alignment untuk merged cells
                $sheet->getStyle('A' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('B' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('C' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('F' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('G' . $startRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }

            $no++;
        }

        // Total
        $sheet->setCellValue('A' . $row, 'TOTAL OMSET');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->setCellValue('E' . $row, $totalOmset);
        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCFCE7']], // Light green
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0');

        // Auto width columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set minimum width untuk kolom tertentu
        $sheet->getColumnDimension('C')->setWidth(35); // Nama Dinas
        $sheet->getColumnDimension('D')->setWidth(40); // Nama Pengadaan
        $sheet->getColumnDimension('F')->setWidth(20); // Nomor Proyek

        // Generate filename
        $filename = 'Data_Omset_' . $tahun . '_' . Carbon::now()->format('YmdHis') . '.xlsx';

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
