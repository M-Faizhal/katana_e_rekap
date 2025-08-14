<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengiriman')->insert([
            [
                'id_penawaran' => 1,
                'no_surat_jalan' => 'SJ/2025/07/001',
                'file_surat_jalan' => 'surat_jalan_001.pdf',
                'tanggal_kirim' => '2025-07-30',
                'foto_berangkat' => 'berangkat_001.jpg',
                'foto_perjalanan' => 'perjalanan_001.jpg',
                'foto_sampai' => 'sampai_001.jpg',
                'tanda_terima' => 'tanda_terima_001.pdf',
                'status_verifikasi' => 'terverifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_penawaran' => 2,
                'no_surat_jalan' => 'SJ/2025/08/002',
                'file_surat_jalan' => 'surat_jalan_002.pdf',
                'tanggal_kirim' => '2025-08-08',
                'foto_berangkat' => 'berangkat_002.jpg',
                'foto_perjalanan' => 'perjalanan_002.jpg',
                'foto_sampai' => 'sampai_002.jpg',
                'tanda_terima' => 'tanda_terima_002.pdf',
                'status_verifikasi' => 'terverifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_penawaran' => 3,
                'no_surat_jalan' => 'SJ/2025/08/003',
                'file_surat_jalan' => 'surat_jalan_003.pdf',
                'tanggal_kirim' => '2025-08-12',
                'foto_berangkat' => 'berangkat_003.jpg',
                'foto_perjalanan' => 'perjalanan_003.jpg',
                'foto_sampai' => null,
                'tanda_terima' => null,
                'status_verifikasi' => 'proses',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
