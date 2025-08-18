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
        $users = [
            [
                'nama' => 'Super Administrator',
                'username' => 'superadmin',
                'email' => 'superadmin@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'no_telepon' => '081234567890',
                'alamat' => 'Jakarta Pusat, DKI Jakarta'
            ],
            [
                'nama' => 'Admin Marketing',
                'username' => 'admin_marketing',
                'email' => 'marketing@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_marketing',
                'no_telepon' => '081234567891',
                'alamat' => 'Jakarta Selatan, DKI Jakarta'
            ],
            [
                'nama' => 'Manager Marketing',
                'username' => 'manager_marketing',
                'email' => 'manager.marketing@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_marketing',
                'no_telepon' => '081234567892',
                'alamat' => 'Jakarta Selatan, DKI Jakarta'
            ],
            [
                'nama' => 'Admin Purchasing',
                'username' => 'admin_purchasing',
                'email' => 'purchasing@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_purchasing',
                'no_telepon' => '081234567893',
                'alamat' => 'Jakarta Timur, DKI Jakarta'
            ],
            [
                'nama' => 'Manager Purchasing',
                'username' => 'manager_purchasing',
                'email' => 'manager.purchasing@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_purchasing',
                'no_telepon' => '081234567894',
                'alamat' => 'Jakarta Timur, DKI Jakarta'
            ],
            [
                'nama' => 'Staff Purchasing',
                'username' => 'staff_purchasing',
                'email' => 'staff.purchasing@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_purchasing',
                'no_telepon' => '081234567895',
                'alamat' => 'Jakarta Timur, DKI Jakarta'
            ],
            [
                'nama' => 'Admin Keuangan',
                'username' => 'admin_keuangan',
                'email' => 'keuangan@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_keuangan',
                'no_telepon' => '081234567896',
                'alamat' => 'Jakarta Barat, DKI Jakarta'
            ],
            [
                'nama' => 'Manager Keuangan',
                'username' => 'manager_keuangan',
                'email' => 'manager.keuangan@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_keuangan',
                'no_telepon' => '081234567897',
                'alamat' => 'Jakarta Barat, DKI Jakarta'
            ],
            [
                'nama' => 'Staff Keuangan',
                'username' => 'staff_keuangan',
                'email' => 'staff.keuangan@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_keuangan',
                'no_telepon' => '081234567898',
                'alamat' => 'Jakarta Barat, DKI Jakarta'
            ],
            [
                'nama' => 'Demo User',
                'username' => 'demo',
                'email' => 'demo@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'no_telepon' => '081234567899',
                'alamat' => 'Jakarta, DKI Jakarta'
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        echo "User seeder completed! Created " . count($users) . " users.\n";
        echo "Login credentials:\n";
        echo "- Super Admin: superadmin@katana.com / password123\n";
        echo "- Admin Marketing: marketing@katana.com / password123\n";
        echo "- Admin Purchasing: purchasing@katana.com / password123\n";
        echo "- Admin Keuangan: keuangan@katana.com / keuangan123\n";
        echo "- Demo User: demo@katana.com / demo123\n";
    }
}
