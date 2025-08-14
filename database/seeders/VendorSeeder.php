<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendor')->insert([
            [
                'nama_vendor' => 'PT. Maju Berkah Teknologi',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'kontak' => '021-12345678',
                'jenis_perusahaan' => 'Principle',
                'email' => 'info@majuberkah.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_vendor' => 'CV. Sumber Rejeki Elektronik',
                'alamat' => 'Jl. Gajah Mada No. 456, Surabaya',
                'kontak' => '031-87654321',
                'jenis_perusahaan' => 'Distributor',
                'email' => 'info@sumberrejeki.com',

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_vendor' => 'PT. Global Tech Solutions',
                'alamat' => 'Jl. Asia Afrika No. 789, Bandung',
                'kontak' => '022-11223344',
                'jenis_perusahaan' => 'Retail',
                'email' => 'info@globaltech.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
