<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah kolom persentase agar bisa menerima nilai yang lebih besar
     * seperti -28000% untuk kasus khusus
     */
    public function up(): void
    {
        Schema::table('kalkulasi_hps', function (Blueprint $table) {
            // Ubah kolom persentase dari decimal(5,2) menjadi decimal(8,2)
            // Ini akan mengizinkan nilai dari -999999.99 sampai 999999.99
            $table->decimal('kenaikan_percent', 8, 2)->default(0)->change();
            $table->decimal('nilai_tkdn_percent', 8, 2)->default(0)->change();
            $table->decimal('ppn_percent', 8, 2)->default(11)->change();
            $table->decimal('pph_badan_percent', 8, 2)->default(1.5)->change();
            $table->decimal('omzet_dinas_percent', 8, 2)->default(0)->change();
            $table->decimal('nett_percent', 8, 2)->nullable()->change();
            $table->decimal('gross_income_percent', 8, 2)->default(0)->change();
            $table->decimal('nett_income_percent', 8, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kalkulasi_hps', function (Blueprint $table) {
            // Kembalikan ke decimal(5,2) jika rollback
            $table->decimal('kenaikan_percent', 5, 2)->default(0)->change();
            $table->decimal('nilai_tkdn_percent', 5, 2)->default(0)->change();
            $table->decimal('ppn_percent', 5, 2)->default(11)->change();
            $table->decimal('pph_badan_percent', 5, 2)->default(1.5)->change();
            $table->decimal('omzet_dinas_percent', 5, 2)->default(0)->change();
            $table->decimal('nett_percent', 5, 2)->nullable()->change();
            $table->decimal('gross_income_percent', 5, 2)->default(0)->change();
            $table->decimal('nett_income_percent', 5, 2)->default(0)->change();
        });
    }
};
