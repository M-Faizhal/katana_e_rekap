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
        Schema::table('proyek', function (Blueprint $table) {
            // Ubah nama_klien dan kontak_klien menjadi nullable
            $table->string('nama_klien')->nullable()->change();
            $table->string('kontak_klien')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            // Kembalikan ke NOT NULL (hati-hati dengan data yang sudah ada)
            $table->string('nama_klien')->nullable(false)->change();
            $table->string('kontak_klien')->nullable(false)->change();
        });
    }
};
