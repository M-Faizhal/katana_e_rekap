<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;

class TestPembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil proyek pertama dan buat pembayaran parsial
        $proyek = Proyek::first();
        
        if ($proyek && $proyek->penawaranAktif) {
            // Hapus semua pembayaran existing untuk proyek ini
            Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)->delete();
            
            // Buat pembayaran DP saja (50% dari total)
            $dpAmount = $proyek->penawaranAktif->total_penawaran * 0.5;
            
            Pembayaran::create([
                'id_penawaran' => $proyek->penawaranAktif->id_penawaran,
                'tanggal_bayar' => now(),
                'jenis_bayar' => 'DP',
                'nominal_bayar' => $dpAmount,
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti/dp_' . time() . '.jpg',
                'status_verifikasi' => 'Approved'
            ]);
            
            echo "✅ Test data dibuat:\n";
            echo "- Proyek: {$proyek->nama_proyek}\n";
            echo "- Total Penawaran: Rp " . number_format($proyek->penawaranAktif->total_penawaran, 0, ',', '.') . "\n";
            echo "- DP Dibayar: Rp " . number_format($dpAmount, 0, ',', '.') . "\n";
            echo "- Sisa Bayar: Rp " . number_format($proyek->penawaranAktif->total_penawaran - $dpAmount, 0, ',', '.') . "\n";
        }
    }
}
