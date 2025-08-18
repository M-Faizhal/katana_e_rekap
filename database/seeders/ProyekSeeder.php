<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\User;
use Carbon\Carbon;

class ProyekSeeder extends Seeder
{
    public function run()
    {
        // Ambil beberapa user untuk admin marketing dan purchasing
        $marketingAdmins = User::whereIn('role', ['admin', 'marketing'])->limit(3)->get();
        $purchasingAdmins = User::whereIn('role', ['admin', 'purchasing'])->limit(3)->get();

        if ($marketingAdmins->isEmpty() || $purchasingAdmins->isEmpty()) {
            $this->command->info('Warning: Tidak ada user marketing atau purchasing. Pastikan user sudah di-seed terlebih dahulu.');
            return;
        }

        $proyekData = [
            [
                'tanggal' => Carbon::now()->subDays(30),
                'kota_kab' => 'Jakarta Pusat',
                'instansi' => 'Dinas Pendidikan DKI',
                'nama_klien' => 'Dr. Ahmad Wijaya',
                'kontak_klien' => '081234567890',
                'nama_barang' => 'Sistem Informasi Manajemen Pendidikan',
                'jumlah' => 1,
                'satuan' => 'paket',
                'spesifikasi' => 'Sistem informasi manajemen pendidikan berbasis web dengan fitur manajemen siswa, guru, nilai, dan laporan.',
                'harga_satuan' => 850000000,
                'harga_total' => 850000000,
                'jenis_pengadaan' => 'Pelelangan Umum',
                'deadline' => Carbon::now()->addDays(15),
                'catatan' => 'Proyek pembangunan sistem informasi manajemen pendidikan untuk meningkatkan efisiensi administrasi.',
                'status' => 'Penawaran',
                'potensi' => 'ya',
                'tahun_potensi' => 2025
            ],
            [
                'tanggal' => Carbon::now()->subDays(25),
                'kota_kab' => 'Bandung',
                'instansi' => 'Pemkot Bandung',
                'nama_klien' => 'Ir. Sari Dewi',
                'kontak_klien' => '082345678901',
                'nama_barang' => 'Website Portal Layanan Publik',
                'jumlah' => 1,
                'satuan' => 'paket',
                'spesifikasi' => 'Website portal layanan publik dengan fitur pendaftaran online, tracking status, dan pembayaran digital.',
                'harga_satuan' => 650000000,
                'harga_total' => 650000000,
                'jenis_pengadaan' => 'Penunjukan Langsung',
                'deadline' => Carbon::now()->addDays(20),
                'catatan' => 'Pengembangan portal layanan publik online untuk memudahkan akses masyarakat.',
                'status' => 'Pembayaran',
                'potensi' => 'ya',
                'tahun_potensi' => 2025
            ],
            [
                'tanggal' => Carbon::now()->subDays(45),
                'kota_kab' => 'Surabaya',
                'instansi' => 'Pemkot Surabaya',
                'nama_klien' => 'Drs. Budi Santoso',
                'kontak_klien' => '083456789012',
                'nama_barang' => 'Aplikasi Mobile E-Government',
                'jumlah' => 1,
                'satuan' => 'paket',
                'spesifikasi' => 'Aplikasi mobile untuk layanan e-government dengan fitur notifikasi, chat support, dan integrasi dengan sistem legacy.',
                'harga_satuan' => 720000000,
                'harga_total' => 720000000,
                'jenis_pengadaan' => 'Tender',
                'deadline' => Carbon::now()->subDays(10),
                'catatan' => 'Aplikasi mobile untuk layanan e-government yang terintegrasi.',
                'status' => 'Gagal',
                'potensi' => 'tidak',
                'tahun_potensi' => 2025
            ],
            [
                'tanggal' => Carbon::now()->subDays(15),
                'kota_kab' => 'Yogyakarta',
                'instansi' => 'Pemda DIY',
                'nama_klien' => 'Prof. Dr. Maya Sari',
                'kontak_klien' => '084567890123',
                'nama_barang' => 'Dashboard Analytics Pembangunan',
                'jumlah' => 1,
                'satuan' => 'paket',
                'spesifikasi' => 'Dashboard analytics untuk monitoring dan analisis data pembangunan daerah dengan visualisasi interaktif.',
                'harga_satuan' => 920000000,
                'harga_total' => 920000000,
                'jenis_pengadaan' => 'Pelelangan Umum',
                'deadline' => Carbon::now()->addDays(10),
                'catatan' => 'Dashboard untuk monitoring dan analisis data pembangunan daerah.',
                'status' => 'Selesai',
                'potensi' => 'ya',
                'tahun_potensi' => 2025
            ],
            [
                'tanggal' => Carbon::now()->subDays(10),
                'kota_kab' => 'Semarang',
                'instansi' => 'BPKAD Kota Semarang',
                'nama_klien' => 'Dra. Nina Kartika',
                'kontak_klien' => '085678901234',
                'nama_barang' => 'Sistem Inventory Aset Daerah',
                'jumlah' => 1,
                'satuan' => 'paket',
                'spesifikasi' => 'Sistem manajemen inventory aset daerah dengan fitur barcode scanning, laporan real-time, dan audit trail.',
                'harga_satuan' => 980000000,
                'harga_total' => 980000000,
                'jenis_pengadaan' => 'Pemilihan Langsung',
                'deadline' => Carbon::now()->addDays(45),
                'catatan' => 'Sistem manajemen inventory aset daerah untuk meningkatkan akuntabilitas.',
                'status' => 'Pengiriman',
                'potensi' => 'ya',
                'tahun_potensi' => 2025
            ],
            [
                'tanggal' => Carbon::now()->subDays(5),
                'kota_kab' => 'Malang',
                'instansi' => 'BPKD Kota Malang',
                'nama_klien' => 'Ir. Dedi Kurniawan',
                'kontak_klien' => '086789012345',
                'nama_barang' => 'Sistem Keuangan Daerah Terintegrasi',
                'jumlah' => 1,
                'satuan' => 'paket',
                'spesifikasi' => 'Sistem keuangan daerah yang terintegrasi dengan sistem nasional, termasuk modul anggaran, akuntansi, dan pelaporan.',
                'harga_satuan' => 1200000000,
                'harga_total' => 1200000000,
                'jenis_pengadaan' => 'Tender',
                'deadline' => Carbon::now()->addDays(60),
                'catatan' => 'Implementasi sistem keuangan daerah yang terintegrasi dengan sistem nasional.',
                'status' => 'Menunggu',
                'potensi' => 'tidak',
                'tahun_potensi' => 2025
            ]
        ];

        foreach ($proyekData as $index => $data) {
            $marketingAdmin = $marketingAdmins->get($index % $marketingAdmins->count());
            $purchasingAdmin = $purchasingAdmins->get($index % $purchasingAdmins->count());

            Proyek::create([
                'tanggal' => $data['tanggal'],
                'kota_kab' => $data['kota_kab'],
                'instansi' => $data['instansi'],
                'nama_klien' => $data['nama_klien'],
                'kontak_klien' => $data['kontak_klien'],
                'nama_barang' => $data['nama_barang'],
                'jumlah' => $data['jumlah'],
                'satuan' => $data['satuan'],
                'spesifikasi' => $data['spesifikasi'],
                'harga_satuan' => $data['harga_satuan'],
                'harga_total' => $data['harga_total'],
                'jenis_pengadaan' => $data['jenis_pengadaan'],
                'deadline' => $data['deadline'],
                'id_admin_marketing' => $marketingAdmin->id_user,
                'id_admin_purchasing' => $purchasingAdmin->id_user,
                'catatan' => $data['catatan'],
                'status' => $data['status'],
                'potensi' => $data['potensi'],
                'tahun_potensi' => $data['tahun_potensi']
            ]);
        }

        $this->command->info('Proyek seeder berhasil dijalankan!');
    }
}
