<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengiriman;
use App\Models\Penawaran;
use App\Models\Vendor;

class PengirimanTestSeeder extends Seeder
{
    public function run()
    {
        // Ambil penawaran dan vendor yang ada
        $penawaran = Penawaran::with('penawaranDetail.barang.vendor')->first();
        
        if (!$penawaran) {
            $this->command->info('Tidak ada penawaran yang tersedia untuk test');
            return;
        }

        // Ambil vendor dari penawaran
        $vendors = $penawaran->penawaranDetail
            ->pluck('barang.vendor')
            ->unique('id_vendor')
            ->take(3);

        $statusList = ['Pending', 'Dalam_Proses', 'Sampai_Tujuan'];
        
        foreach ($vendors as $index => $vendor) {
            if (!$vendor) continue;
            
            // Buat pengiriman dengan dokumentasi yang berbeda-beda
            $pengiriman = Pengiriman::create([
                'id_penawaran' => $penawaran->id_penawaran,
                'id_vendor' => $vendor->id_vendor,
                'no_surat_jalan' => 'SJ-TEST-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'tanggal_kirim' => now()->subDays(rand(1, 10)),
                'alamat_kirim' => 'Alamat Test Pengiriman ' . ($index + 1),
                'status_verifikasi' => $statusList[$index] ?? 'Pending',
            ]);

            // Tambahkan dokumentasi secara bertahap
            if ($index >= 0) {
                // Semua pengiriman punya foto berangkat
                $pengiriman->update([
                    'foto_berangkat' => 'pengiriman/test_berangkat_' . $pengiriman->id_pengiriman . '.jpg'
                ]);
            }
            
            if ($index >= 1) {
                // Pengiriman kedua dan ketiga punya foto perjalanan
                $pengiriman->update([
                    'foto_perjalanan' => 'pengiriman/test_perjalanan_' . $pengiriman->id_pengiriman . '.jpg'
                ]);
            }
            
            if ($index >= 2) {
                // Pengiriman ketiga punya foto sampai dan tanda terima (lengkap)
                $pengiriman->update([
                    'foto_sampai' => 'pengiriman/test_sampai_' . $pengiriman->id_pengiriman . '.jpg',
                    'tanda_terima' => 'pengiriman/test_tanda_terima_' . $pengiriman->id_pengiriman . '.pdf'
                ]);
            }

            $this->command->info("Pengiriman test #{$pengiriman->id_pengiriman} dibuat untuk vendor {$vendor->nama_vendor}");
        }

        // Buat satu pengiriman yang sudah verified
        if ($vendors->count() > 0) {
            $verifiedPengiriman = Pengiriman::create([
                'id_penawaran' => $penawaran->id_penawaran,
                'id_vendor' => $vendors->first()->id_vendor,
                'no_surat_jalan' => 'SJ-VERIFIED-001',
                'tanggal_kirim' => now()->subDays(5),
                'alamat_kirim' => 'Alamat Test Verified',
                'status_verifikasi' => 'Verified',
                'foto_berangkat' => 'pengiriman/verified_berangkat.jpg',
                'foto_perjalanan' => 'pengiriman/verified_perjalanan.jpg',
                'foto_sampai' => 'pengiriman/verified_sampai.jpg',
                'tanda_terima' => 'pengiriman/verified_tanda_terima.pdf',
                'verified_at' => now()->subDays(1),
                'diverifikasi_oleh' => 1
            ]);

            $this->command->info("Pengiriman verified #{$verifiedPengiriman->id_pengiriman} dibuat");
        }
    }
}
