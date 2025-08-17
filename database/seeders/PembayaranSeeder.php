<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;
use App\Models\Proyek;
use App\Models\Penawaran;
use Carbon\Carbon;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Ambil proyek yang sudah ada penawaran dengan status ACC
        $proyekDenganPenawaran = Proyek::whereHas('penawaranAktif', function ($query) {
            $query->where('status', 'ACC');
        })->get();

        if ($proyekDenganPenawaran->isEmpty()) {
            // Jika belum ada proyek dengan penawaran ACC, update status beberapa proyek
            $proyekUntukUpdate = Proyek::whereNotNull('id_penawaran')->take(3)->get();
            
            foreach ($proyekUntukUpdate as $proyek) {
                if ($proyek->penawaranAktif) {
                    $proyek->penawaranAktif->update(['status' => 'ACC']);
                    $proyek->update(['status' => 'Pembayaran']);
                }
            }
            
            $proyekDenganPenawaran = Proyek::whereHas('penawaranAktif', function ($query) {
                $query->where('status', 'ACC');
            })->get();
        }

        // Sample pembayaran untuk proyek yang sudah di-ACC
        foreach ($proyekDenganPenawaran->take(5) as $index => $proyek) {
            $penawaran = $proyek->penawaranAktif;
            $totalPenawaran = $penawaran->total_penawaran;

            // Scenario pembayaran yang berbeda-beda
            switch ($index % 4) {
                case 0: // Pembayaran Lunas
                    Pembayaran::create([
                        'id_penawaran' => $penawaran->id_penawaran,
                        'jenis_bayar' => 'Lunas',
                        'nominal_bayar' => $totalPenawaran,
                        'tanggal_bayar' => Carbon::now()->subDays(rand(1, 7)),
                        'metode_bayar' => 'Transfer Bank',
                        'bukti_bayar' => 'pembayaran/sample_bukti_' . ($index + 1) . '.jpg',
                        'catatan' => 'Pembayaran lunas via transfer BCA',
                        'status_verifikasi' => 'Approved',
                    ]);
                    
                    // Update status proyek ke Pengiriman
                    $proyek->update(['status' => 'Pengiriman']);
                    break;

                case 1: // Pembayaran DP 50% (Approved)
                    Pembayaran::create([
                        'id_penawaran' => $penawaran->id_penawaran,
                        'jenis_bayar' => 'DP',
                        'nominal_bayar' => $totalPenawaran * 0.5,
                        'tanggal_bayar' => Carbon::now()->subDays(rand(1, 5)),
                        'metode_bayar' => 'Transfer Bank',
                        'bukti_bayar' => 'pembayaran/sample_bukti_' . ($index + 1) . '.jpg',
                        'catatan' => 'DP 50% untuk memulai proses produksi',
                        'status_verifikasi' => 'Approved',
                    ]);
                    break;

                case 2: // Pembayaran masih Pending
                    Pembayaran::create([
                        'id_penawaran' => $penawaran->id_penawaran,
                        'jenis_bayar' => 'DP',
                        'nominal_bayar' => $totalPenawaran * 0.3,
                        'tanggal_bayar' => Carbon::now()->subDays(rand(1, 3)),
                        'metode_bayar' => 'Cash',
                        'bukti_bayar' => 'pembayaran/sample_bukti_' . ($index + 1) . '.jpg',
                        'catatan' => 'DP 30% pembayaran cash di kantor',
                        'status_verifikasi' => 'Pending',
                    ]);
                    break;

                case 3: // Pembayaran bertahap (DP + Cicilan)
                    // DP 40%
                    Pembayaran::create([
                        'id_penawaran' => $penawaran->id_penawaran,
                        'jenis_bayar' => 'DP',
                        'nominal_bayar' => $totalPenawaran * 0.4,
                        'tanggal_bayar' => Carbon::now()->subDays(rand(5, 10)),
                        'metode_bayar' => 'Transfer Bank',
                        'bukti_bayar' => 'pembayaran/sample_bukti_' . ($index + 1) . '_dp.jpg',
                        'catatan' => 'DP 40% untuk memulai proyek',
                        'status_verifikasi' => 'Approved',
                    ]);
                    
                    // Cicilan 30%
                    Pembayaran::create([
                        'id_penawaran' => $penawaran->id_penawaran,
                        'jenis_bayar' => 'Cicilan',
                        'nominal_bayar' => $totalPenawaran * 0.3,
                        'tanggal_bayar' => Carbon::now()->subDays(rand(1, 3)),
                        'metode_bayar' => 'Transfer Bank',
                        'bukti_bayar' => 'pembayaran/sample_bukti_' . ($index + 1) . '_cicilan.jpg',
                        'catatan' => 'Cicilan kedua 30%',
                        'status_verifikasi' => 'Pending',
                    ]);
                    break;
            }
        }

        // Tambahkan beberapa pembayaran yang ditolak untuk testing
        if ($proyekDenganPenawaran->count() > 3) {
            $proyek = $proyekDenganPenawaran->skip(3)->first();
            Pembayaran::create([
                'id_penawaran' => $proyek->penawaranAktif->id_penawaran,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => $proyek->penawaranAktif->total_penawaran * 0.2,
                'tanggal_bayar' => Carbon::now()->subDays(rand(1, 3)),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'pembayaran/sample_bukti_ditolak.jpg',
                'catatan' => 'Bukti transfer tidak jelas, mohon upload ulang',
                'status_verifikasi' => 'Ditolak',
            ]);
        }
    }
}
