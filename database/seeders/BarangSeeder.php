<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('barang')->insert([
            [
                'id_vendor' => 1,
                'nama_barang' => 'Laptop ASUS ROG',

                'spesifikasi' => 'Intel Core i7, RAM 16GB, SSD 512GB, RTX 3060',
                'satuan' => 'unit',
                'brand' => 'ASUS',
                'kategori' => 'Elektronik',
                'harga_vendor' => 18500000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_vendor' => 2,
                'nama_barang' => 'Printer Canon Pixma',

                'spesifikasi' => 'Multifunction, WiFi, Print/Scan/Copy, A4',
                'satuan' => 'unit',
                'brand' => 'Canon',
                'kategori' => 'Elektronik',
                'harga_vendor' => 2750000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_vendor' => 3,
                'nama_barang' => 'Server Dell PowerEdge',
                'spesifikasi' => 'Intel Xeon Gold, RAM 64GB, SSD 2TB, Rackmount 2U',
                'satuan' => 'unit',
                'brand' => 'Dell',
                'kategori' => 'Mesin',
                'harga_vendor' => 65000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
