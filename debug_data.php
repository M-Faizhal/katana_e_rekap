<?php

use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;

// Debug data
echo "=== DEBUG DATA ===\n";
echo "Total Proyek: " . Proyek::count() . "\n";
echo "Proyek status Pembayaran: " . Proyek::where('status', 'Pembayaran')->count() . "\n";
echo "Penawaran ACC: " . Penawaran::where('status', 'ACC')->count() . "\n";

echo "\n=== PROYEK DETAIL ===\n";
$proyekPembayaran = Proyek::where('status', 'Pembayaran')->get();
foreach ($proyekPembayaran as $proyek) {
    echo "ID: {$proyek->id_proyek} - {$proyek->nama_barang} - id_penawaran: " . ($proyek->id_penawaran ?? 'NULL') . "\n";
    
    // Cek penawaran untuk proyek ini
    $penawaran = Penawaran::where('id_proyek', $proyek->id_proyek)->where('status', 'ACC')->first();
    if ($penawaran) {
        echo "  -> Penawaran ACC: {$penawaran->no_penawaran} (ID: {$penawaran->id_penawaran})\n";
    } else {
        echo "  -> Tidak ada penawaran ACC\n";
    }
}

echo "\n=== FIX DATA ===\n";
// Update id_penawaran untuk setiap proyek
$fixed = 0;
foreach ($proyekPembayaran as $proyek) {
    $penawaran = Penawaran::where('id_proyek', $proyek->id_proyek)->where('status', 'ACC')->first();
    if ($penawaran && !$proyek->id_penawaran) {
        $proyek->update(['id_penawaran' => $penawaran->id_penawaran]);
        echo "Fixed: {$proyek->nama_barang} -> id_penawaran: {$penawaran->id_penawaran}\n";
        $fixed++;
    }
}

echo "Total fixed: $fixed proyek\n";
echo "DONE!\n";
