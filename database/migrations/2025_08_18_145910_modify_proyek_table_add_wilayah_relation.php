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
            // Tambah kolom id_wilayah sebagai foreign key
            $table->unsignedBigInteger('id_wilayah')->nullable()->after('tanggal');

            // Ubah nama kolom kota_kab menjadi kab_kota untuk konsistensi
            $table->renameColumn('kota_kab', 'kab_kota');

            // Tambah foreign key constraint
            $table->foreign('id_wilayah')->references('id_wilayah')->on('wilayah')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['id_wilayah']);

            // Hapus kolom id_wilayah
            $table->dropColumn('id_wilayah');

            // Kembalikan nama kolom
            $table->renameColumn('kab_kota', 'kota_kab');
        });
    }
};
