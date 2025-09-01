<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wilayah;

class UpdateWilayahWithAdminMarketingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminMarketingNames = [
            'Sari Dewi',
            'Ahmad Fauzi',
            'Lisa Permata',
            'Agus Setiawan',
            'Maya Sinta',
            'Eko Prabowo',
            'Rina Maharani'
        ];

        $wilayahRecords = Wilayah::whereNull('admin_marketing_text')->get();

        foreach ($wilayahRecords as $index => $wilayah) {
            $randomAdminMarketing = $adminMarketingNames[array_rand($adminMarketingNames)];

            $wilayah->update([
                'admin_marketing_text' => $randomAdminMarketing
            ]);

            $this->command->info("Updated wilayah ID {$wilayah->id_wilayah} with admin marketing: {$randomAdminMarketing}");
        }

        $this->command->info('Successfully updated ' . $wilayahRecords->count() . ' wilayah records with admin marketing text.');
    }
}
