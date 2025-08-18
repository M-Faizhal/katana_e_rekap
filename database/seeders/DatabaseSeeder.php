<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear all tables first (optional - careful in production!)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('pengiriman')->truncate();
        // DB::table('pembayaran')->truncate();
        // DB::table('penawaran_detail')->truncate();
        // DB::table('kalkulasi_hps')->truncate();
        // DB::table('penawaran')->truncate();
        // DB::table('proyek')->truncate();
        // DB::table('barang')->truncate();
        // DB::table('vendor')->truncate();
        // DB::table('users')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call seeders in proper order (respecting foreign key constraints)
        $this->call([
            CompleteSystemSeeder::class,
        ]);

        echo "\n=== DATABASE SEEDING COMPLETED ===\n";
        echo "Silakan login dengan akun berikut:\n";
        echo "- Super Admin: superadmin@katana.com / admin123\n";
        echo "- Marketing: marketing@katana.com / marketing123\n";
        echo "- Purchasing: purchasing@katana.com / purchasing123\n";
        echo "- Keuangan: keuangan@katana.com / keuangan123\n";
        echo "- Demo: demo@katana.com / demo123\n";
        echo "\nData lengkap telah dibuat untuk testing flow bisnis!\n";
    }
}
