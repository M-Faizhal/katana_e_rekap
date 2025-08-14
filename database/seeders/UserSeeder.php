<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superadmin
        User::create([
            'nama' => 'Super Administrator',
            'username' => 'superadmin',
            'email' => 'superadmin@katana.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
        ]);

        // Create admin marketing
        User::create([
            'nama' => 'Admin Marketing',
            'username' => 'admin_marketing',
            'email' => 'marketing@katana.com',
            'password' => Hash::make('password123'),
            'role' => 'admin_marketing',
        ]);

        // Create admin purchasing
        User::create([
            'nama' => 'Admin Purchasing',
            'username' => 'admin_purchasing',
            'email' => 'purchasing@katana.com',
            'password' => Hash::make('password123'),
            'role' => 'admin_purchasing',
        ]);

        // Create admin keuangan
        User::create([
            'nama' => 'Admin Keuangan',
            'username' => 'admin_keuangan',
            'email' => 'keuangan@katana.com',
            'password' => Hash::make('password123'),
            'role' => 'admin_keuangan',
        ]);
    }
}
