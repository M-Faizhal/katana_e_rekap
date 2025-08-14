<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenawaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('penawaran')->insert([
            [
                'id_proyek' => 1,
                'no_penawaran' => 'PNW/2025/07/001',
                'tanggal_penawaran' => '2025-07-16',
                'masa_berlaku' => '2025-08-15',
                'surat_pesanan' => 'SP-2025-001.pdf',
                'surat_penawaran' => 'SPN-2025-001.pdf',
                'total_penawaran' => 625000000.00,
                'status' => 'disetujui',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_proyek' => 2,
                'no_penawaran' => 'PNW/2025/07/002',
                'tanggal_penawaran' => '2025-07-21',
                'masa_berlaku' => '2025-08-20',
                'surat_pesanan' => 'SP-2025-002.pdf',
                'surat_penawaran' => 'SPN-2025-002.pdf',
                'total_penawaran' => 112500000.00,
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_proyek' => 3,
                'no_penawaran' => 'PNW/2025/08/003',
                'tanggal_penawaran' => '2025-08-02',
                'masa_berlaku' => '2025-09-01',
                'surat_pesanan' => 'SP-2025-003.pdf',
                'surat_penawaran' => 'SPN-2025-003.pdf',
                'total_penawaran' => 425000000.00,
                'status' => 'menunggu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
