<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call other seeders in proper order (respecting foreign key constraints)
        $this->call([
            UserSeeder::class,
            VendorSeeder::class,
            BarangSeeder::class,
            ProyekSeeder::class,
            PenawaranSeeder::class,
            PenawaranDetailSeeder::class,
            UpdateProyekPenawaranSeeder::class, // Update relasi setelah semua data ada
            PembayaranSeeder::class,
            PengirimanSeeder::class,
        ]);
    }
}
