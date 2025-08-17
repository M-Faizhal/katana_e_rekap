<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;

class DebugPembayaranData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:pembayaran-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug and fix pembayaran data relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DEBUG PEMBAYARAN DATA ===');
        
        $this->info('Total Proyek: ' . Proyek::count());
        $this->info('Proyek status Pembayaran: ' . Proyek::where('status', 'Pembayaran')->count());
        $this->info('Penawaran ACC: ' . Penawaran::where('status', 'ACC')->count());

        $this->info("\n=== PROYEK DETAIL ===");
        $proyekPembayaran = Proyek::where('status', 'Pembayaran')->get();
        
        foreach ($proyekPembayaran as $proyek) {
            $this->line("ID: {$proyek->id_proyek} - {$proyek->nama_barang} - id_penawaran: " . ($proyek->id_penawaran ?? 'NULL'));
            
            // Cek penawaran untuk proyek ini
            $penawaran = Penawaran::where('id_proyek', $proyek->id_proyek)->where('status', 'ACC')->first();
            if ($penawaran) {
                $this->line("  -> Penawaran ACC: {$penawaran->no_penawaran} (ID: {$penawaran->id_penawaran})");
            } else {
                $this->line("  -> Tidak ada penawaran ACC");
            }
        }

        $this->info("\n=== FIX DATA ===");
        // Update id_penawaran untuk setiap proyek
        $fixed = 0;
        foreach ($proyekPembayaran as $proyek) {
            $penawaran = Penawaran::where('id_proyek', $proyek->id_proyek)->where('status', 'ACC')->first();
            if ($penawaran && !$proyek->id_penawaran) {
                $proyek->update(['id_penawaran' => $penawaran->id_penawaran]);
                $this->line("Fixed: {$proyek->nama_barang} -> id_penawaran: {$penawaran->id_penawaran}");
                $fixed++;
            }
        }

        $this->info("Total fixed: $fixed proyek");
        
        // Test query controller
        $this->info("\n=== TEST CONTROLLER QUERY ===");
        $testQuery = Proyek::with(['penawaranAktif', 'adminMarketing', 'pembayaran'])
            ->where('status', 'Pembayaran')
            ->whereHas('penawaranAktif', function ($query) {
                $query->where('status', 'ACC');
            })->count();
            
        $this->info("Controller query result: $testQuery proyek");
        
        $this->info('DONE!');
        
        return 0;
    }
}
