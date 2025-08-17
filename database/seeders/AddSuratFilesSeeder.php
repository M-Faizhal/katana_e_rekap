<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penawaran;

class AddSuratFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update penawaran dengan file sample
        $penawarans = Penawaran::all();
        
        foreach ($penawarans as $index => $penawaran) {
            $penawaran->update([
                'surat_pesanan' => 'penawaran/surat_pesanan_' . ($index + 1) . '.pdf',
                'surat_penawaran' => 'penawaran/surat_penawaran_' . ($index + 1) . '.pdf'
            ]);
        }
        
        echo "✅ File surat sample berhasil ditambahkan ke " . $penawarans->count() . " penawaran\n";
    }
}
