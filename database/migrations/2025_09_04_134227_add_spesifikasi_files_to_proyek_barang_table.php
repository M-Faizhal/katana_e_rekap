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
        Schema::table('proyek_barang', function (Blueprint $table) {
            $table->json('spesifikasi_files')->nullable()->after('spesifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyek_barang', function (Blueprint $table) {
            $table->dropColumn('spesifikasi_files');
        });
    }
};
