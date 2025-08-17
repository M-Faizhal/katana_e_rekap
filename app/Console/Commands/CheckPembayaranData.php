<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;

class CheckPembayaranData extends Command
{
    protected $signature = 'check:pembayaran-data';
    protected $description = 'Check pembayaran data for debugging';

    public function handle()
    {
        $this->info('=== CHECKING PEMBAYARAN DATA ===');
        
        $this->info("\n1. PROYEK DATA:");
        $proyeks = Proyek::all();
        foreach ($proyeks as $proyek) {
            $this->line("ID: {$proyek->id_proyek} | {$proyek->nama_barang} | Status: {$proyek->status}");
        }
        
        $this->info("\n2. PENAWARAN DATA:");
        $penawarans = Penawaran::with('proyek')->get();
        foreach ($penawarans as $penawaran) {
            $this->line("ID: {$penawaran->id_penawaran} | No: {$penawaran->no_penawaran} | Status: {$penawaran->status} | Proyek: {$penawaran->proyek->nama_barang}");
        }
        
        $this->info("\n3. PEMBAYARAN DATA:");
        $pembayarans = Pembayaran::with('penawaran.proyek')->get();
        foreach ($pembayarans as $pembayaran) {
            $this->line("ID: {$pembayaran->id_pembayaran} | Jenis: {$pembayaran->jenis_bayar} | Status: {$pembayaran->status_verifikasi} | Nominal: Rp " . number_format((float)$pembayaran->nominal_bayar) . " | Proyek: {$pembayaran->penawaran->proyek->nama_barang}");
        }
        
        $this->info("\n4. QUERY CONTROLLER TEST:");
        
        // Test query proyek perlu bayar
        $proyekPerluBayar = Proyek::with(['penawaranAktif', 'adminMarketing', 'pembayaran'])
            ->where('status', 'Pembayaran')
            ->whereHas('penawaranAktif', function ($query) {
                $query->where('status', 'ACC');
            })
            ->get();
        
        $this->info("Proyek perlu bayar count: " . $proyekPerluBayar->count());
        foreach ($proyekPerluBayar as $proyek) {
            $this->line("- {$proyek->nama_barang} (Status: {$proyek->status})");
        }
        
        // Test query semua pembayaran
        $semuaPembayaran = Pembayaran::with(['penawaran.proyek.adminMarketing'])
            ->whereHas('penawaran.proyek', function ($query) {
                $query->whereHas('penawaranAktif', function ($subQuery) {
                    $subQuery->where('status', 'ACC');
                });
            })
            ->get();
            
        $this->info("\nSemua pembayaran count: " . $semuaPembayaran->count());
        foreach ($semuaPembayaran as $pembayaran) {
            $this->line("- {$pembayaran->jenis_bayar} Rp " . number_format((float)$pembayaran->nominal_bayar) . " ({$pembayaran->status_verifikasi}) - {$pembayaran->penawaran->proyek->nama_barang}");
        }
        
        return 0;
    }
}
