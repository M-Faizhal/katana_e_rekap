<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kalkulasi_hps', function (Blueprint $table) {
            // Menambahkan field-field yang hilang
            $table->decimal('harga_per_pcs', 15, 2)->default(0)->after('hps');
            $table->decimal('harga_pagu_dinas_per_pcs', 15, 2)->default(0)->after('harga_per_pcs');
            $table->decimal('nilai_sp', 15, 2)->default(0)->after('harga_pagu_dinas_per_pcs');
            $table->decimal('bendera_percent', 5, 2)->default(0)->after('omzet_dinas');
            $table->decimal('bank_cost_percent', 5, 2)->default(0)->after('bendera_percent');
            $table->decimal('biaya_ops_percent', 5, 2)->default(0)->after('bank_cost_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kalkulasi_hps', function (Blueprint $table) {
            // Menghapus field-field yang ditambahkan
            $table->dropColumn([
                'harga_per_pcs',
                'harga_pagu_dinas_per_pcs',
                'nilai_sp',
                'bendera_percent',
                'bank_cost_percent',
                'biaya_ops_percent'
            ]);
        });
    }
};
