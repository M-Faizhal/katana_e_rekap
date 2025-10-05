<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\User;
use App\Models\Wilayah;

class ProyekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have required data
        $this->ensureRequiredData();

        // Clear existing projects (optional - remove if you want to keep existing data)
        // Proyek::truncate();

        // Create a variety of projects using factory states
        
        // 1. Create projects from this year (2025)
        Proyek::factory()
            ->count(25)
            ->thisYear()
            ->create();

        // 2. Create projects from last year (2024)
        Proyek::factory()
            ->count(35)
            ->lastYear()
            ->create();

        // 3. Create completed projects
        Proyek::factory()
            ->count(20)
            ->completed()
            ->create();

        // 4. Create ongoing projects
        Proyek::factory()
            ->count(15)
            ->ongoing()
            ->create();

        // 5. Create pending projects
        Proyek::factory()
            ->count(10)
            ->pending()
            ->create();

        // 6. Create high value projects
        Proyek::factory()
            ->count(8)
            ->highValue()
            ->thisYear()
            ->create();

        // 7. Create medium value projects
        Proyek::factory()
            ->count(12)
            ->mediumValue()
            ->create();

        // 8. Create low value projects
        Proyek::factory()
            ->count(15)
            ->lowValue()
            ->create();

        // 9. Create government projects
        Proyek::factory()
            ->count(20)
            ->government()
            ->create();

        // 10. Create private company projects
        Proyek::factory()
            ->count(18)
            ->private()
            ->create();

        // 11. Create projects distributed across months for better chart visualization
        $months = [
            ['start' => '2025-01-01', 'end' => '2025-01-31'],
            ['start' => '2025-02-01', 'end' => '2025-02-28'],
            ['start' => '2025-03-01', 'end' => '2025-03-31'],
            ['start' => '2025-04-01', 'end' => '2025-04-30'],
            ['start' => '2025-05-01', 'end' => '2025-05-31'],
            ['start' => '2025-06-01', 'end' => '2025-06-30'],
            ['start' => '2025-07-01', 'end' => '2025-07-31'],
            ['start' => '2025-08-01', 'end' => '2025-08-31'],
            ['start' => '2025-09-01', 'end' => '2025-09-30'],
            ['start' => '2025-10-01', 'end' => '2025-10-31'],
        ];

        foreach ($months as $month) {
            Proyek::factory()
                ->count(rand(2, 8)) // Random 2-8 projects per month
                ->state([
                    'tanggal' => fake()->dateTimeBetween($month['start'], $month['end'])
                ])
                ->create();
        }

        $this->command->info('Successfully created ' . Proyek::count() . ' projects');
    }

    /**
     * Ensure required data exists before creating projects
     */
    private function ensureRequiredData(): void
    {
        // Create admin users if they don't exist
        if (User::where('role', 'admin_marketing')->count() === 0) {
            User::factory()
                ->count(3)
                ->state(['role' => 'admin_marketing'])
                ->create();
            $this->command->info('Created admin_marketing users');
        }

        if (User::where('role', 'admin_purchasing')->count() === 0) {
            User::factory()
                ->count(3)
                ->state(['role' => 'admin_purchasing'])
                ->create();
            $this->command->info('Created admin_purchasing users');
        }

        // Create wilayah if they don't exist
        if (Wilayah::count() === 0) {
            // Create some sample wilayah
            $wilayahData = [
                ['nama_wilayah' => 'Jawa Barat', 'provinsi' => 'Jawa Barat', 'kode_wilayah' => 'JABAR'],
                ['nama_wilayah' => 'DKI Jakarta', 'provinsi' => 'DKI Jakarta', 'kode_wilayah' => 'JKT'],
                ['nama_wilayah' => 'Jawa Tengah', 'provinsi' => 'Jawa Tengah', 'kode_wilayah' => 'JATENG'],
                ['nama_wilayah' => 'Jawa Timur', 'provinsi' => 'Jawa Timur', 'kode_wilayah' => 'JATIM'],
                ['nama_wilayah' => 'Banten', 'provinsi' => 'Banten', 'kode_wilayah' => 'BANTEN'],
            ];

            foreach ($wilayahData as $wilayah) {
                Wilayah::create($wilayah);
            }
            $this->command->info('Created sample wilayah data');
        }
    }
}
