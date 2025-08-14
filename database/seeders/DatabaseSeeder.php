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
        // Create users first (required for foreign keys)
        DB::table('users')->insert([
            [
                'nama' => 'Super Administrator',
                'username' => 'superadmin',
                'email' => 'superadmin@katana.com',
                'password' => bcrypt('admin123'),
                'role' => 'superadmin',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Admin Marketing',
                'username' => 'marketing',
                'email' => 'marketing@katana.com',
                'password' => bcrypt('marketing123'),
                'role' => 'admin_marketing',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Admin Purchasing',
                'username' => 'purchasing',
                'email' => 'purchasing@katana.com',
                'password' => bcrypt('purchasing123'),
                'role' => 'admin_purchasing',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Call other seeders in proper order (respecting foreign key constraints)
        $this->call([
            VendorSeeder::class,
            BarangSeeder::class,
            ProyekSeeder::class,
            PenawaranSeeder::class,
            PenawaranDetailSeeder::class,
            PembayaranSeeder::class,
            PengirimanSeeder::class,
        ]);
    }
}
