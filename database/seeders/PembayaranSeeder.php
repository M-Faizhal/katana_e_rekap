<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pembayaran')->insert([
            [
                'id_penawaran' => 1,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 187500000.00,
                'tanggal_bayar' => '2025-07-25',
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_001.pdf',
                'catatan' => 'Pembayaran down payment 30%',
                'status_verifikasi' => 'terverifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_penawaran' => 2,
                'jenis_bayar' => 'Lunas',
                'nominal_bayar' => 112500000.00,
                'tanggal_bayar' => '2025-08-05',
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_lunas_002.pdf',
                'catatan' => 'Pembayaran lunas setelah barang diterima',
                'status_verifikasi' => 'terverifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_penawaran' => 3,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 127500000.00,
                'tanggal_bayar' => '2025-08-10',
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_003.pdf',
                'catatan' => 'Pembayaran down payment 30%',
                'status_verifikasi' => 'menunggu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
