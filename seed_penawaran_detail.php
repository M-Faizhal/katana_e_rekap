<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Penawaran;
use App\Models\PenawaranDetail;

echo "=== MENAMBAHKAN SAMPLE PENAWARAN DETAIL ===\n";

// Get first 3 penawaran
$penawarans = Penawaran::limit(3)->get();

foreach ($penawarans as $index => $penawaran) {
    echo "\nProses penawaran ID: {$penawaran->id_penawaran}\n";

    // Hapus detail yang ada jika ada
    PenawaranDetail::where('id_penawaran', $penawaran->id_penawaran)->delete();

    // Tambah sample detail
    $details = [
        [
            'nama_barang' => 'Laptop Dell Inspiron 15',
            'spesifikasi' => 'Intel Core i5, 8GB RAM, 512GB SSD',
            'qty' => 10,
            'harga_satuan' => 8500000,
            'subtotal' => 85000000
        ],
        [
            'nama_barang' => 'Mouse Wireless Logitech',
            'spesifikasi' => 'Optical, 2.4GHz, 1000 DPI',
            'qty' => 10,
            'harga_satuan' => 150000,
            'subtotal' => 1500000
        ],
        [
            'nama_barang' => 'Keyboard Mechanical',
            'spesifikasi' => 'Cherry MX Blue, RGB Backlight',
            'qty' => 10,
            'harga_satuan' => 750000,
            'subtotal' => 7500000
        ]
    ];

    $totalPenawaran = 0;

    foreach ($details as $detail) {
        PenawaranDetail::create([
            'id_penawaran' => $penawaran->id_penawaran,
            'nama_barang' => $detail['nama_barang'],
            'spesifikasi' => $detail['spesifikasi'],
            'qty' => $detail['qty'],
            'harga_satuan' => $detail['harga_satuan'],
            'subtotal' => $detail['subtotal']
        ]);

        $totalPenawaran += $detail['subtotal'];
        echo "- Ditambahkan: {$detail['nama_barang']} (Rp " . number_format($detail['subtotal'], 0, ',', '.') . ")\n";
    }

    // Update total nilai penawaran
    $penawaran->total_nilai = $totalPenawaran;
    $penawaran->save();

    echo "Total penawaran: Rp " . number_format($totalPenawaran, 0, ',', '.') . "\n";
}

echo "\n=== SELESAI ===\n";

?>
