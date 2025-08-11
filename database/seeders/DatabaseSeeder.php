<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@katana.com',
            'password' => bcrypt('admin123'),
        ]);

        // Create manager user
        User::factory()->create([
            'name' => 'Manager PT. Kamil Trio Niaga',
            'email' => 'manager@katana.com',
            'password' => bcrypt('manager123'),
        ]);

        // Create staff user
        User::factory()->create([
            'name' => 'Staff KATANA',
            'email' => 'staff@katana.com',
            'password' => bcrypt('staff123'),
        ]);

        // Create test user (original)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create additional dummy users
        User::factory(5)->create();
    }
}
