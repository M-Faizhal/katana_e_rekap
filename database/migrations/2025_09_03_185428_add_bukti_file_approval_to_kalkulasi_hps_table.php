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
            $table->string('bukti_file_approval')->nullable()->after('keterangan_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kalkulasi_hps', function (Blueprint $table) {
            $table->dropColumn('bukti_file_approval');
        });
    }
};
