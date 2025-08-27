<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear all tables first (optional - careful in production!)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('pengiriman')->truncate();
        // DB::table('pembayaran')->truncate();
        // DB::table('penawaran_detail')->truncate();
        // DB::table('kalkulasi_hps')->truncate();
        // DB::table('penawaran')->truncate();
        // DB::table('proyek')->truncate();
        // DB::table('barang')->truncate();
        // DB::table('vendor')->truncate();
        // DB::table('users')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call seeders in proper order (respecting foreign key constraints)
        // $this->call([
        //     CompleteSystemSeeder::class,
        // ]);

        // echo "\nData lengkap telah dibuat untuk testing flow bisnis!\n";


        User::create([
                'nama' => 'Super Administrator',
                'username' => 'superadmin',
                'email' => 'superadmin@katana.com',
                'password' => Hash::make('K@mil6969'),
                'role' => 'superadmin',
                'no_telepon' => '081234567890',
                'alamat' => 'Jakarta Pusat, DKI Jakarta'
            ]
        );
    }
}
