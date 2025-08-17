<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;
use App\Models\User;

class TestPembayaranDitolakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user untuk marketing
        $marketing = User::firstOrCreate([
            'email' => 'marketing.test@example.com'
        ], [
            'nama' => 'Marketing Test',
            'password' => bcrypt('password'),
            'role' => 'admin_marketing'
        ]);

        // Buat proyek test untuk pembayaran ditolak
        $proyek = Proyek::create([
            'tanggal' => now(),
            'nama_barang' => 'Software Testing Pembayaran Ditolak',
            'instansi' => 'Instansi Test Ditolak',
            'kota_kab' => 'Kota Test',
            'nama_klien' => 'Klien Test Ditolak',
            'kontak_klien' => '081234567890',
            'jumlah' => 1,
            'satuan' => 'Unit',
            'spesifikasi' => 'Software untuk testing pembayaran ditolak',
            'jenis_pengadaan' => 'Langsung',
            'id_admin_marketing' => $marketing->id_user,
            'id_admin_purchasing' => $marketing->id_user, // Untuk test, pakai user yang sama
            'status' => 'Pembayaran'
        ]);

        // Buat penawaran yang sudah ACC
        $penawaran = Penawaran::create([
            'id_proyek' => $proyek->id_proyek,
            'no_penawaran' => 'PEN-TEST-DITOLAK-' . date('Ymd') . '-001',
            'tanggal_penawaran' => now(),
            'masa_berlaku' => now()->addDays(30),
            'total_penawaran' => 10000000, // 10 juta
            'status' => 'ACC'
        ]);

        // Scenario 1: DP 5 juta ditolak, proyek harus tetap muncul di daftar
        Pembayaran::create([
            'id_penawaran' => $penawaran->id_penawaran,
            'tanggal_bayar' => now(),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 5000000, // 5 juta
            'metode_bayar' => 'Transfer',
            'bukti_bayar' => 'test-bukti-ditolak.jpg',
            'status_verifikasi' => 'Ditolak',
            'catatan' => 'Test pembayaran ditolak - Bukti transfer tidak jelas'
        ]);

        // Scenario 2: Pembayaran 3 juta approved (masih ada sisa 7 juta)
        Pembayaran::create([
            'id_penawaran' => $penawaran->id_penawaran,
            'tanggal_bayar' => now()->addDays(1),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 3000000, // 3 juta
            'metode_bayar' => 'Transfer',
            'bukti_bayar' => 'test-bukti-approved.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'Test pembayaran approved'
        ]);

        // Scenario 3: Pembayaran 2 juta pending
        Pembayaran::create([
            'id_penawaran' => $penawaran->id_penawaran,
            'tanggal_bayar' => now()->addDays(2),
            'jenis_bayar' => 'Cicilan',
            'nominal_bayar' => 2000000, // 2 juta
            'metode_bayar' => 'Transfer',
            'bukti_bayar' => 'test-bukti-pending.jpg',
            'status_verifikasi' => 'Pending',
            'catatan' => 'Test pembayaran pending'
        ]);

        echo "Test seeder pembayaran ditolak berhasil dijalankan!\n";
        echo "Proyek: {$proyek->nama_barang}\n";
        echo "Total Penawaran: Rp 10.000.000\n";
        echo "Pembayaran Ditolak: Rp 5.000.000 (tidak dihitung)\n";
        echo "Pembayaran Approved: Rp 3.000.000\n";
        echo "Pembayaran Pending: Rp 2.000.000 (tidak dihitung)\n";
        echo "Sisa Bayar yang harus tampil: Rp 7.000.000\n";
        echo "Proyek harus tetap muncul di daftar 'Perlu Pembayaran'\n";
    }
}
