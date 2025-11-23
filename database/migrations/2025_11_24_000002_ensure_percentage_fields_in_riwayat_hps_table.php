<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Memastikan kolom persentase di riwayat_hps sudah menggunakan decimal(8,2)
     */
    public function up(): void
    {
        Schema::table('riwayat_hps', function (Blueprint $table) {
            // Pastikan semua kolom persentase menggunakan decimal(8,2)
            // Walaupun sudah ada, ini untuk memastikan konsistensi
            $table->decimal('persen_kenaikan', 8, 2)->default(0)->change();
            $table->decimal('omzet_dinas_percent', 8, 2)->default(0)->change();
            $table->decimal('bendera_percent', 8, 2)->default(0)->change();
            $table->decimal('bank_cost_percent', 8, 2)->default(0)->change();
            $table->decimal('biaya_ops_percent', 8, 2)->default(0)->change();
            $table->decimal('gross_income_persentase', 8, 2)->default(0)->change();
            $table->decimal('nett_income_persentase', 8, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini hanya memastikan tipe data yang benar
    }
};
