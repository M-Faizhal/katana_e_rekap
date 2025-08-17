<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\Penawaran;

class UpdateProyekPenawaranSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Update proyek dengan penawaran yang sudah ACC
        $proyekUpdate = [
            1 => 1, // Proyek ID 1 -> Penawaran ID 1
            2 => 2, // Proyek ID 2 -> Penawaran ID 2
        ];
        
        foreach ($proyekUpdate as $idProyek => $idPenawaran) {
            $proyek = Proyek::find($idProyek);
            if ($proyek) {
                $proyek->update([
                    'id_penawaran' => $idPenawaran,
                    'status' => 'Pembayaran'
                ]);
            }
        }
    }
}
