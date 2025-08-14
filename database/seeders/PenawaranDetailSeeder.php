<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenawaranDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('penawaran_detail')->insert([
            [
                'id_penawaran' => 1,
                'id_barang' => 1,
                'nama_barang' => 'Laptop ASUS ROG',
                'spesifikasi' => 'Intel Core i7, RAM 16GB, SSD 512GB, RTX 3060',
                'qty' => 50,
                'harga_satuan' => 12500000.00,
                'subtotal' => 625000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_penawaran' => 2,
                'id_barang' => 2,
                'nama_barang' => 'Printer Canon Pixma',
                'spesifikasi' => 'Multifunction, WiFi, Print/Scan/Copy, A4',
                'qty' => 25,
                'harga_satuan' => 4500000.00,
                'subtotal' => 112500000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_penawaran' => 3,
                'id_barang' => 3,
                'nama_barang' => 'Server Dell PowerEdge',
                'spesifikasi' => 'Intel Xeon Gold, RAM 64GB, SSD 2TB, Rackmount 2U',
                'qty' => 5,
                'harga_satuan' => 85000000.00,
                'subtotal' => 425000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
