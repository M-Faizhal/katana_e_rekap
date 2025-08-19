<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Proyek;

echo "=== TEST AUTO GENERATE KODE PROYEK ===\n";

// Test 1: Lihat kode proyek terakhir
echo "\n1. Checking current proyek codes:\n";
$proyeks = Proyek::orderBy('kode_proyek', 'desc')->take(5)->get();
foreach ($proyeks as $proyek) {
    echo "- ID: {$proyek->id_proyek}, Kode: {$proyek->kode_proyek}\n";
}

// Test 2: Generate next kode
echo "\n2. Testing next kode generation:\n";
try {
    $nextKode = Proyek::generateNextKodeProyek();
    echo "Next kode will be: {$nextKode}\n";
} catch (Exception $e) {
    echo "Error: {$e->getMessage()}\n";
}

// Test 3: Simulasi pembuatan proyek baru
echo "\n3. Testing actual proyek creation:\n";
try {
    $testProyek = new Proyek();
    $testProyek->tanggal = now();
    $testProyek->kab_kota = 'Test Kota';
    $testProyek->instansi = 'Test Instansi';
    $testProyek->nama_klien = 'Test Klien';
    $testProyek->nama_barang = 'Test Barang';
    $testProyek->jumlah = 1;
    $testProyek->satuan = 'unit';
    $testProyek->spesifikasi = 'Test spesifikasi';
    $testProyek->jenis_pengadaan = 'Test Pengadaan';
    $testProyek->id_admin_marketing = 1;
    $testProyek->id_admin_purchasing = 1;
    $testProyek->status = 'Menunggu';
    $testProyek->potensi = 'tidak';
    $testProyek->tahun_potensi = 2025;

    $testProyek->save();

    echo "Test proyek created with kode: {$testProyek->kode_proyek}\n";
    echo "ID: {$testProyek->id_proyek}\n";

} catch (Exception $e) {
    echo "Error creating test proyek: {$e->getMessage()}\n";
}

echo "\n=== END TEST ===\n";

?>
