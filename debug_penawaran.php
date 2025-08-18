<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Proyek;
use App\Models\Penawaran;

echo "=== DEBUG PENAWARAN ===\n";

// Check Proyek model
echo "\n1. Checking Proyek data:\n";
$proyeks = Proyek::limit(3)->get();
foreach ($proyeks as $proyek) {
    echo "- Proyek ID: {$proyek->id_proyek}, Kode: {$proyek->kode_proyek}\n";
}

// Check primary key issues
echo "\n2. Checking Proyek primary key:\n";
$proyek = new Proyek();
echo "- Primary key: {$proyek->getKeyName()}\n";

// Check Penawaran model
echo "\n3. Checking Penawaran data:\n";
$penawarans = Penawaran::limit(3)->get();
foreach ($penawarans as $penawaran) {
    echo "- Penawaran ID: {$penawaran->id_penawaran}, Proyek ID: {$penawaran->id_proyek}, Total: {$penawaran->total_nilai}\n";
}

// Check Penawaran primary key
echo "\n4. Checking Penawaran primary key:\n";
$penawaran = new Penawaran();
echo "- Primary key: {$penawaran->getKeyName()}\n";

// Test relationship
echo "\n5. Testing relationship:\n";
$penawaran = Penawaran::first();
if ($penawaran) {
    echo "- Penawaran found: {$penawaran->id_penawaran}\n";
    echo "- Trying to get proyek...\n";
    try {
        $proyek = $penawaran->proyek;
        if ($proyek) {
            echo "- Proyek found: {$proyek->kode_proyek}\n";
        } else {
            echo "- No proyek found for this penawaran\n";
        }
    } catch (Exception $e) {
        echo "- Error getting proyek: {$e->getMessage()}\n";
    }
} else {
    echo "- No penawaran found\n";
}

echo "\n=== END DEBUG ===\n";

?>
