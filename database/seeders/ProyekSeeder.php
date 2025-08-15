<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('proyek')->insert([
            [
                'tanggal' => '2025-07-15',
                'kota_kab' => 'Jakarta Pusat',
                'instansi' => 'Dinas Pendidikan DKI Jakarta',
                'nama_klien' => 'Budi Santoso',
                'kontak_klien' => '081234567890',
                'nama_barang' => 'Laptop untuk Lab Komputer',
                'jumlah' => 50,
                'satuan' => 'unit',
                'spesifikasi' => 'Intel Core i5, RAM 8GB, SSD 256GB, Windows 11',
                'harga_satuan' => 12500000.00,
                'harga_total' => 625000000.00,
                'jenis_pengadaan' => 'Tender Terbuka',
                'deadline' => '2025-08-15',
                'id_admin_marketing' => 2,
                'id_admin_purchasing' => 3,
                'id_penawaran' => null,
                'catatan' => 'Proyek pengadaan laptop untuk laboratorium komputer sekolah',
                'status' => 'Penawaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-07-20',
                'kota_kab' => 'Surabaya',
                'instansi' => 'RSUD Dr. Soetomo',
                'nama_klien' => 'Dr. Siti Aminah',
                'kontak_klien' => 'siti.aminah@rssoetomo.go.id',
                'nama_barang' => 'Printer Multifungsi',
                'jumlah' => 25,
                'satuan' => 'unit',
                'spesifikasi' => 'Laser, Duplex, Network, A4/A3',
                'harga_satuan' => 4500000.00,
                'harga_total' => 112500000.00,
                'jenis_pengadaan' => 'Penunjukan Langsung',
                'deadline' => '2025-08-30',
                'id_admin_marketing' => 2,
                'id_admin_purchasing' => 3,
                'id_penawaran' => null,
                'catatan' => 'Pengadaan printer untuk unit administrasi rumah sakit',
                'status' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-08-01',
                'kota_kab' => 'Bandung',
                'instansi' => 'PT. Telkom Indonesia',
                'nama_klien' => 'Ahmad Wijaya',
                'kontak_klien' => '+62218001234',
                'nama_barang' => 'Server Rack',
                'jumlah' => 5,
                'satuan' => 'unit',
                'spesifikasi' => 'Intel Xeon Gold, RAM 128GB, Storage 10TB',
                'harga_satuan' => 85000000.00,
                'harga_total' => 425000000.00,
                'jenis_pengadaan' => 'Lelang Terbatas',
                'deadline' => '2025-09-15',
                'id_admin_marketing' => 2,
                'id_admin_purchasing' => 3,
                'id_penawaran' => null,
                'catatan' => 'Upgrade infrastruktur data center',
                'status' => 'Menunggu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
