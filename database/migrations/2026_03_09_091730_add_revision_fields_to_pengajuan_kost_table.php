<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuan_kost', function (Blueprint $table) {
            // Tambah kolom tanggal selesai kegiatan (setelah tanggal_kegiatan)
            $table->date('tanggal_kegiatan_sampai')->nullable()->after('tanggal_kegiatan');
        });

        // Ubah enum: ganti 'ditolak' → 'revisi'
        // MySQL: ubah definisi kolom enum langsung
        DB::statement("ALTER TABLE pengajuan_kost MODIFY COLUMN status ENUM('menunggu','disetujui','revisi') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan enum ke semula (pastikan data revisi diubah dulu)
        DB::statement("UPDATE pengajuan_kost SET status = 'menunggu' WHERE status = 'revisi'");
        DB::statement("ALTER TABLE pengajuan_kost MODIFY COLUMN status ENUM('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");

        Schema::table('pengajuan_kost', function (Blueprint $table) {
            $table->dropColumn('tanggal_kegiatan_sampai');
        });
    }
};
