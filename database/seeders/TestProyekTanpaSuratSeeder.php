<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Vendor;

class TestProyekTanpaSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah vendor sudah ada atau buat yang baru
        $vendor = Vendor::where('email', 'test.tanpa.surat@vendor.com')->first();
        if (!$vendor) {
            $vendor = Vendor::create([
                'nama_vendor' => 'Test Vendor Tanpa Surat',
                'email' => 'test.tanpa.surat@vendor.com',
                'alamat' => 'Jl. Test No. 123',
                'kontak' => '08123456789',
                'jenis_perusahaan' => 'Lain-lain'
            ]);
        }

        // Buat proyek tanpa file surat
        $proyek = Proyek::create([
            'tanggal' => now()->subDays(10),
            'kota_kab' => 'Jakarta Selatan',
            'instansi' => 'Test Instansi',
            'nama_klien' => 'Test Client Tanpa Surat',
            'kontak_klien' => '081234567890',
            'nama_barang' => 'Test Barang Tanpa Surat',
            'jumlah' => 10,
            'satuan' => 'unit',
            'spesifikasi' => 'Test spesifikasi untuk proyek tanpa surat',
            'harga_satuan' => 5000000.00,
            'harga_total' => 50000000.00,
            'jenis_pengadaan' => 'Test Pengadaan',
            'deadline' => now()->addDays(20),
            'id_admin_marketing' => 2, // Sesuaikan dengan user yang ada
            'id_admin_purchasing' => 3, // Sesuaikan dengan user yang ada
            'id_penawaran' => null,
            'catatan' => 'Proyek untuk testing input pembayaran tanpa dokumen surat',
            'status' => 'Penawaran'
        ]);

        // Buat penawaran tanpa file surat
        Penawaran::create([
            'id_proyek' => $proyek->id_proyek,
            'no_penawaran' => 'PNW/TEST/TANPA_SURAT/001',
            'tanggal_penawaran' => now()->subDays(5),
            'masa_berlaku' => now()->addDays(15),
            'total_penawaran' => 45000000.00,
            'status' => 'ACC',
            // Sengaja tidak ada surat_pesanan dan surat_penawaran
            'surat_pesanan' => null,
            'surat_penawaran' => null
        ]);

        $this->command->info('✅ Test proyek tanpa surat berhasil dibuat - ID: ' . $proyek->id_proyek);
    }
}
