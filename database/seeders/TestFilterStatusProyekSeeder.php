<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;
use App\Models\User;

class TestFilterStatusProyekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user untuk marketing
        $marketing = User::firstOrCreate([
            'email' => 'marketing.filter@example.com'
        ], [
            'nama' => 'Marketing Filter Test',
            'password' => bcrypt('password'),
            'role' => 'admin_marketing'
        ]);

        echo "=== Membuat Data Test untuk Filter Status Proyek ===\n\n";

        // Scenario 1: Proyek LUNAS (100% dibayar dan approved)
        $proyekLunas = Proyek::create([
            'tanggal' => now(),
            'nama_barang' => 'Software ERP - Proyek Lunas',
            'instansi' => 'PT ABC Corp',
            'kota_kab' => 'Jakarta',
            'nama_klien' => 'Budi Santoso',
            'kontak_klien' => '081234567001',
            'jumlah' => 1,
            'satuan' => 'Unit',
            'spesifikasi' => 'Software ERP lengkap untuk testing filter lunas',
            'jenis_pengadaan' => 'Langsung',
            'id_admin_marketing' => $marketing->id_user,
            'id_admin_purchasing' => $marketing->id_user,
            'status' => 'Pembayaran'
        ]);

        $penawaranLunas = Penawaran::create([
            'id_proyek' => $proyekLunas->id_proyek,
            'no_penawaran' => 'PEN-LUNAS-' . date('Ymd') . '-001',
            'tanggal_penawaran' => now(),
            'masa_berlaku' => now()->addDays(30),
            'total_penawaran' => 15000000, // 15 juta
            'status' => 'ACC'
        ]);

        // Pembayaran lunas 100%
        Pembayaran::create([
            'id_penawaran' => $penawaranLunas->id_penawaran,
            'tanggal_bayar' => now(),
            'jenis_bayar' => 'Lunas',
            'nominal_bayar' => 15000000, // 15 juta (100%)
            'metode_bayar' => 'Transfer',
            'bukti_bayar' => 'bukti-lunas.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'Pembayaran lunas 100%'
        ]);

        echo "✅ Proyek LUNAS: {$proyekLunas->nama_barang}\n";
        echo "   Total: Rp 15.000.000 | Dibayar: Rp 15.000.000 (100%)\n\n";

        // Scenario 2: Proyek BELUM LUNAS (50% dibayar)
        $proyekBelumLunas1 = Proyek::create([
            'tanggal' => now(),
            'nama_barang' => 'Aplikasi Mobile - Proyek Cicilan',
            'instansi' => 'CV XYZ Digital',
            'kota_kab' => 'Bandung',
            'nama_klien' => 'Sari Dewi',
            'kontak_klien' => '081234567002',
            'jumlah' => 1,
            'satuan' => 'Unit',
            'spesifikasi' => 'Aplikasi mobile untuk testing filter belum lunas',
            'jenis_pengadaan' => 'Tender',
            'id_admin_marketing' => $marketing->id_user,
            'id_admin_purchasing' => $marketing->id_user,
            'status' => 'Pembayaran'
        ]);

        $penawaranBelumLunas1 = Penawaran::create([
            'id_proyek' => $proyekBelumLunas1->id_proyek,
            'no_penawaran' => 'PEN-CICIL-' . date('Ymd') . '-001',
            'tanggal_penawaran' => now(),
            'masa_berlaku' => now()->addDays(30),
            'total_penawaran' => 20000000, // 20 juta
            'status' => 'ACC'
        ]);

        // DP 50%
        Pembayaran::create([
            'id_penawaran' => $penawaranBelumLunas1->id_penawaran,
            'tanggal_bayar' => now(),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 10000000, // 10 juta (50%)
            'metode_bayar' => 'Transfer',
            'bukti_bayar' => 'bukti-dp-50.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'DP 50% telah diterima'
        ]);

        echo "🟡 Proyek BELUM LUNAS (Cicilan): {$proyekBelumLunas1->nama_barang}\n";
        echo "   Total: Rp 20.000.000 | Dibayar: Rp 10.000.000 (50%) | Sisa: Rp 10.000.000\n\n";

        // Scenario 3: Proyek BELUM LUNAS (0% dibayar - ada pembayaran tapi ditolak)
        $proyekBelumLunas2 = Proyek::create([
            'tanggal' => now(),
            'nama_barang' => 'Website Corporate - Pembayaran Ditolak',
            'instansi' => 'PT DEF Solutions',
            'kota_kab' => 'Surabaya',
            'nama_klien' => 'Ahmad Rahman',
            'kontak_klien' => '081234567003',
            'jumlah' => 1,
            'satuan' => 'Unit',
            'spesifikasi' => 'Website corporate untuk testing pembayaran ditolak',
            'jenis_pengadaan' => 'Langsung',
            'id_admin_marketing' => $marketing->id_user,
            'id_admin_purchasing' => $marketing->id_user,
            'status' => 'Pembayaran'
        ]);

        $penawaranBelumLunas2 = Penawaran::create([
            'id_proyek' => $proyekBelumLunas2->id_proyek,
            'no_penawaran' => 'PEN-TOLAK-' . date('Ymd') . '-001',
            'tanggal_penawaran' => now(),
            'masa_berlaku' => now()->addDays(30),
            'total_penawaran' => 12000000, // 12 juta
            'status' => 'ACC'
        ]);

        // Pembayaran ditolak
        Pembayaran::create([
            'id_penawaran' => $penawaranBelumLunas2->id_penawaran,
            'tanggal_bayar' => now(),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 6000000, // 6 juta
            'metode_bayar' => 'Transfer',
            'bukti_bayar' => 'bukti-ditolak.jpg',
            'status_verifikasi' => 'Ditolak',
            'catatan' => 'Bukti transfer tidak jelas, mohon upload ulang'
        ]);

        echo "🔴 Proyek BELUM LUNAS (Pembayaran Ditolak): {$proyekBelumLunas2->nama_barang}\n";
        echo "   Total: Rp 12.000.000 | Dibayar: Rp 0 (pembayaran ditolak) | Sisa: Rp 12.000.000\n\n";

        // Scenario 4: Proyek LUNAS dengan multiple pembayaran
        $proyekLunasMultiple = Proyek::create([
            'tanggal' => now(),
            'nama_barang' => 'Sistem Inventory - Multiple Payment',
            'instansi' => 'PT GHI Warehouse',
            'kota_kab' => 'Medan',
            'nama_klien' => 'Linda Sari',
            'kontak_klien' => '081234567004',
            'jumlah' => 1,
            'satuan' => 'Unit',
            'spesifikasi' => 'Sistem inventory untuk testing multiple payment',
            'jenis_pengadaan' => 'Tender',
            'id_admin_marketing' => $marketing->id_user,
            'id_admin_purchasing' => $marketing->id_user,
            'status' => 'Pembayaran'
        ]);

        $penawaranLunasMultiple = Penawaran::create([
            'id_proyek' => $proyekLunasMultiple->id_proyek,
            'no_penawaran' => 'PEN-MULTI-' . date('Ymd') . '-001',
            'tanggal_penawaran' => now(),
            'masa_berlaku' => now()->addDays(30),
            'total_penawaran' => 25000000, // 25 juta
            'status' => 'ACC'
        ]);

        // DP 40%
        Pembayaran::create([
            'id_penawaran' => $penawaranLunasMultiple->id_penawaran,
            'tanggal_bayar' => now()->subDays(10),
            'jenis_bayar' => 'DP',
            'nominal_bayar' => 10000000, // 10 juta (40%)
            'metode_bayar' => 'Transfer',
            'bukti_bayar' => 'bukti-dp-40.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'DP 40% tahap pertama'
        ]);

        // Cicilan 30%
        Pembayaran::create([
            'id_penawaran' => $penawaranLunasMultiple->id_penawaran,
            'tanggal_bayar' => now()->subDays(5),
            'jenis_bayar' => 'Cicilan',
            'nominal_bayar' => 7500000, // 7.5 juta (30%)
            'metode_bayar' => 'Transfer',
            'bukti_bayar' => 'bukti-cicilan-30.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'Cicilan 30% tahap kedua'
        ]);

        // Pelunasan 30%
        Pembayaran::create([
            'id_penawaran' => $penawaranLunasMultiple->id_penawaran,
            'tanggal_bayar' => now(),
            'jenis_bayar' => 'Pelunasan',
            'nominal_bayar' => 7500000, // 7.5 juta (30%)
            'metode_bayar' => 'Transfer',
            'bukti_bayar' => 'bukti-lunas-30.jpg',
            'status_verifikasi' => 'Approved',
            'catatan' => 'Pelunasan 30% final'
        ]);

        echo "✅ Proyek LUNAS (Multiple Payment): {$proyekLunasMultiple->nama_barang}\n";
        echo "   Total: Rp 25.000.000 | Dibayar: Rp 25.000.000 (100% dalam 3 tahap)\n";
        echo "   - DP: Rp 10.000.000 (40%)\n";
        echo "   - Cicilan: Rp 7.500.000 (30%)\n";
        echo "   - Pelunasan: Rp 7.500.000 (30%)\n\n";

        echo "=== SUMMARY DATA TEST ===\n";
        echo "✅ Proyek LUNAS: 2 proyek\n";
        echo "🟡 Proyek BELUM LUNAS: 2 proyek\n";
        echo "📊 Total: 4 proyek\n\n";

        echo "=== TESTING FILTER ===\n";
        echo "🔍 Filter 'Semua Status': Akan menampilkan 4 proyek\n";
        echo "🔍 Filter 'Lunas': Akan menampilkan 2 proyek (Software ERP & Sistem Inventory)\n";
        echo "🔍 Filter 'Belum Lunas': Akan menampilkan 2 proyek (Aplikasi Mobile & Website Corporate)\n\n";

        echo "Test seeder berhasil dijalankan! 🎉\n";
        echo "Silahkan test filter status proyek di: http://localhost:8000/purchasing/pembayaran\n";
    }
}
