<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;
use App\Models\User;
use Carbon\Carbon;

class TestFilterSearchProyekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat users untuk testing
        $marketing1 = User::firstOrCreate([
            'email' => 'marketing1.test@example.com'
        ], [
            'nama' => 'Marketing Test 1',
            'password' => bcrypt('password'),
            'role' => 'admin_marketing'
        ]);

        $marketing2 = User::firstOrCreate([
            'email' => 'marketing2.test@example.com'
        ], [
            'nama' => 'Marketing Test 2', 
            'password' => bcrypt('password'),
            'role' => 'admin_marketing'
        ]);

        $purchasing = User::firstOrCreate([
            'email' => 'purchasing.test@example.com'
        ], [
            'nama' => 'Purchasing Test',
            'password' => bcrypt('password'),
            'role' => 'admin_purchasing'
        ]);

        echo "=== CREATING TEST DATA FOR FILTER & SEARCH ===\n\n";

        // Scenario 1: Proyek Lunas (100% paid)
        $proyek1 = Proyek::create([
            'tanggal' => Carbon::now()->subDays(30),
            'nama_barang' => 'Software ERP Jakarta',
            'instansi' => 'PT Maju Bersama Jakarta',
            'kota_kab' => 'Jakarta Selatan',
            'nama_klien' => 'Budi Santoso',
            'kontak_klien' => '081234567890',
            'jumlah' => 1,
            'satuan' => 'License',
            'spesifikasi' => 'Software ERP untuk management perusahaan',
            'jenis_pengadaan' => 'Tender',
            'id_admin_marketing' => $marketing1->id_user,
            'id_admin_purchasing' => $purchasing->id_user,
            'status' => 'Pembayaran'
        ]);

        $penawaran1 = Penawaran::create([
            'id_proyek' => $proyek1->id_proyek,
            'no_penawaran' => 'PEN-JKT-' . date('Ymd') . '-001',
            'tanggal_penawaran' => Carbon::now()->subDays(25),
            'masa_berlaku' => Carbon::now()->addDays(30),
            'total_penawaran' => 50000000, // 50 juta
            'status' => 'ACC'
        ]);

        // Pembayaran lunas
        Pembayaran::create([
            'id_penawaran' => $penawaran1->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(20),
            'jenis_bayar' => 'Lunas',
            'nominal_bayar' => 50000000,
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-lunas-jakarta.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'Pembayaran lunas - Jakarta'
        ]);

        // Scenario 2: Proyek Cicilan (50% paid, 1 pending, 1 ditolak)
        $proyek2 = Proyek::create([
            'tanggal' => Carbon::now()->subDays(25),
            'nama_barang' => 'Aplikasi Mobile Bandung',
            'instansi' => 'Dinas Komunikasi Kota Bandung',
            'kota_kab' => 'Bandung',
            'nama_klien' => 'Siti Nurhaliza',
            'kontak_klien' => '082345678901',
            'jumlah' => 1,
            'satuan' => 'Aplikasi',
            'spesifikasi' => 'Aplikasi mobile untuk layanan publik',
            'jenis_pengadaan' => 'Langsung',
            'id_admin_marketing' => $marketing2->id_user,
            'id_admin_purchasing' => $purchasing->id_user,
            'status' => 'Pembayaran'
        ]);

        $penawaran2 = Penawaran::create([
            'id_proyek' => $proyek2->id_proyek,
            'no_penawaran' => 'PEN-BDG-' . date('Ymd') . '-001',
            'tanggal_penawaran' => Carbon::now()->subDays(20),
            'masa_berlaku' => Carbon::now()->addDays(25),
            'total_penawaran' => 75000000, // 75 juta
            'status' => 'ACC'
        ]);

        // DP Approved (50%)
        Pembayaran::create([
            'id_penawaran' => $penawaran2->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(15),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 37500000, // 50% = 37.5 juta
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-dp-bandung.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'DP 50% - Bandung'
        ]);

        // Cicilan Pending
        Pembayaran::create([
            'id_penawaran' => $penawaran2->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(5),
            'jenis_bayar' => 'Cicilan',
            'nominal_bayar' => 20000000, // 20 juta
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-cicilan-pending.jpg',
            'status_verifikasi' => 'Pending',
            'catatan' => 'Cicilan menunggu verifikasi'
        ]);

        // Pembayaran Ditolak
        Pembayaran::create([
            'id_penawaran' => $penawaran2->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(10),
            'jenis_bayar' => 'Cicilan',
            'nominal_bayar' => 15000000, // 15 juta
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-cicilan-ditolak.jpg',
            'status_verifikasi' => 'Ditolak',
            'catatan' => 'Bukti transfer tidak jelas'
        ]);

        // Scenario 3: Proyek Belum Bayar (semua pembayaran ditolak)
        $proyek3 = Proyek::create([
            'tanggal' => Carbon::now()->subDays(15),
            'nama_barang' => 'Website Portal Surabaya',
            'instansi' => 'Pemkot Surabaya',
            'kota_kab' => 'Surabaya',
            'nama_klien' => 'Ahmad Wijaya',
            'kontak_klien' => '083456789012',
            'jumlah' => 1,
            'satuan' => 'Website',
            'spesifikasi' => 'Website portal informasi publik',
            'jenis_pengadaan' => 'Tender',
            'id_admin_marketing' => $marketing1->id_user,
            'id_admin_purchasing' => $purchasing->id_user,
            'status' => 'Pembayaran'
        ]);

        $penawaran3 = Penawaran::create([
            'id_proyek' => $proyek3->id_proyek,
            'no_penawaran' => 'PEN-SBY-' . date('Ymd') . '-001',
            'tanggal_penawaran' => Carbon::now()->subDays(12),
            'masa_berlaku' => Carbon::now()->addDays(20),
            'total_penawaran' => 30000000, // 30 juta
            'status' => 'ACC'
        ]);

        // Semua pembayaran ditolak
        Pembayaran::create([
            'id_penawaran' => $penawaran3->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(8),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 15000000,
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-dp-ditolak-1.jpg',
            'status_verifikasi' => 'Ditolak',
            'catatan' => 'Rekening tujuan salah'
        ]);

        Pembayaran::create([
            'id_penawaran' => $penawaran3->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(3),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 15000000,
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-dp-ditolak-2.jpg',
            'status_verifikasi' => 'Ditolak',
            'catatan' => 'Nominal transfer tidak sesuai'
        ]);

        // Scenario 4: Proyek dengan Multi Pembayaran (DP + multiple cicilan)
        $proyek4 = Proyek::create([
            'tanggal' => Carbon::now()->subDays(40),
            'nama_barang' => 'Sistem Informasi Yogyakarta',
            'instansi' => 'Universitas Gadjah Mada',
            'kota_kab' => 'Yogyakarta',
            'nama_klien' => 'Dr. Rini Kusuma',
            'kontak_klien' => '084567890123',
            'jumlah' => 1,
            'satuan' => 'Sistem',
            'spesifikasi' => 'Sistem informasi akademik terintegrasi',
            'jenis_pengadaan' => 'Penunjukan Langsung',
            'id_admin_marketing' => $marketing2->id_user,
            'id_admin_purchasing' => $purchasing->id_user,
            'status' => 'Pembayaran'
        ]);

        $penawaran4 = Penawaran::create([
            'id_proyek' => $proyek4->id_proyek,
            'no_penawaran' => 'PEN-YGY-' . date('Ymd') . '-001',
            'tanggal_penawaran' => Carbon::now()->subDays(35),
            'masa_berlaku' => Carbon::now()->addDays(15),
            'total_penawaran' => 100000000, // 100 juta
            'status' => 'ACC'
        ]);

        // DP 30% Approved
        Pembayaran::create([
            'id_penawaran' => $penawaran4->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(30),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 30000000, // 30%
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-dp-yogya.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'DP 30% - Yogyakarta'
        ]);

        // Cicilan 1: 25% Approved
        Pembayaran::create([
            'id_penawaran' => $penawaran4->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(20),
            'jenis_bayar' => 'Cicilan',
            'nominal_bayar' => 25000000, // 25%
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-cicilan-1-yogya.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'Cicilan 1 - 25%'
        ]);

        // Cicilan 2: 20% Approved
        Pembayaran::create([
            'id_penawaran' => $penawaran4->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(10),
            'jenis_bayar' => 'Cicilan',
            'nominal_bayar' => 20000000, // 20%
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-cicilan-2-yogya.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'Cicilan 2 - 20%'
        ]);

        // Cicilan 3: 25% Pending (sisa pelunasan)
        Pembayaran::create([
            'id_penawaran' => $penawaran4->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(2),
            'jenis_bayar' => 'Pelunasan',
            'nominal_bayar' => 25000000, // 25% sisa
            'metode_bayar' => 'Transfer Bank',
            'bukti_bayar' => 'bukti-pelunasan-yogya.jpg',
            'status_verifikasi' => 'Pending',
            'catatan' => 'Pelunasan 25% - menunggu verifikasi'
        ]);

        // Scenario 5: Proyek Baru (hanya DP pending)
        $proyek5 = Proyek::create([
            'tanggal' => Carbon::now()->subDays(5),
            'nama_barang' => 'Dashboard Analytics Medan',
            'instansi' => 'Bank Sumut Medan',
            'kota_kab' => 'Medan',
            'nama_klien' => 'Indra Kusuma',
            'kontak_klien' => '085678901234',
            'jumlah' => 1,
            'satuan' => 'Dashboard',
            'spesifikasi' => 'Dashboard analytics untuk monitoring transaksi',
            'jenis_pengadaan' => 'Langsung',
            'id_admin_marketing' => $marketing1->id_user,
            'id_admin_purchasing' => $purchasing->id_user,
            'status' => 'Pembayaran'
        ]);

        $penawaran5 = Penawaran::create([
            'id_proyek' => $proyek5->id_proyek,
            'no_penawaran' => 'PEN-MDN-' . date('Ymd') . '-001',
            'tanggal_penawaran' => Carbon::now()->subDays(3),
            'masa_berlaku' => Carbon::now()->addDays(35),
            'total_penawaran' => 40000000, // 40 juta
            'status' => 'ACC'
        ]);

        // DP Pending
        Pembayaran::create([
            'id_penawaran' => $penawaran5->id_penawaran,
            'tanggal_bayar' => Carbon::now()->subDays(1),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 20000000, // 50%
            'metode_bayar' => 'Transfer Bank', 
            'bukti_bayar' => 'bukti-dp-medan-pending.jpg',
            'status_verifikasi' => 'Pending',
            'catatan' => 'DP baru masuk - menunggu verifikasi'
        ]);

        echo "✅ SEEDER COMPLETED! Test data created:\n\n";
        
        echo "📊 SUMMARY DATA:\n";
        echo "================\n";
        echo "1. Software ERP Jakarta (PT Maju Bersama) - LUNAS 100%\n";
        echo "   💰 Total: Rp 50.000.000 | Approved: Rp 50.000.000 | Sisa: Rp 0\n";
        echo "   📍 Status: LUNAS | Pembayaran: 1 Approved\n\n";
        
        echo "2. Aplikasi Mobile Bandung (Dinas Komunikasi) - CICILAN 50%\n";
        echo "   💰 Total: Rp 75.000.000 | Approved: Rp 37.500.000 | Sisa: Rp 37.500.000\n";
        echo "   📍 Status: CICILAN | Pembayaran: 1 Approved, 1 Pending, 1 Ditolak\n\n";
        
        echo "3. Website Portal Surabaya (Pemkot) - BELUM BAYAR\n";
        echo "   💰 Total: Rp 30.000.000 | Approved: Rp 0 | Sisa: Rp 30.000.000\n";
        echo "   📍 Status: BELUM BAYAR | Pembayaran: 2 Ditolak\n\n";
        
        echo "4. Sistem Informasi Yogyakarta (UGM) - CICILAN 75%\n";
        echo "   💰 Total: Rp 100.000.000 | Approved: Rp 75.000.000 | Sisa: Rp 25.000.000\n";
        echo "   📍 Status: CICILAN | Pembayaran: 3 Approved, 1 Pending\n\n";
        
        echo "5. Dashboard Analytics Medan (Bank Sumut) - BELUM BAYAR\n";
        echo "   💰 Total: Rp 40.000.000 | Approved: Rp 0 | Sisa: Rp 40.000.000\n";
        echo "   📍 Status: BELUM BAYAR | Pembayaran: 1 Pending\n\n";
        
        echo "🔍 TESTING SCENARIOS:\n";
        echo "====================\n";
        echo "✅ Search 'Jakarta' → Should find 1 project\n";
        echo "✅ Search 'Bandung' → Should find 1 project\n";
        echo "✅ Search 'Software' → Should find 1 project\n";
        echo "✅ Search 'Aplikasi' → Should find 1 project\n";
        echo "✅ Search 'Budi' → Should find 1 project (by client name)\n";
        echo "✅ Filter Status 'Pending' → Should find 3 payments\n";
        echo "✅ Filter Status 'Approved' → Should find 6 payments\n";
        echo "✅ Filter Status 'Ditolak' → Should find 3 payments\n";
        echo "✅ Sort by 'Nama Barang' → Should sort alphabetically\n";
        echo "✅ Sort by 'Instansi' → Should sort by institution name\n";
        echo "✅ Projects that need payment: 4 projects (excluding fully paid Jakarta)\n";
        echo "✅ All projects in payment status: 5 projects total\n";
    }
}
