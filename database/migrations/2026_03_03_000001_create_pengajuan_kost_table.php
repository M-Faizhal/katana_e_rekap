<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_kost', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan', 30)->unique();  // KST-2026-001

            // Data Kegiatan
            $table->date('tanggal_kegiatan')->nullable(); // Tanggal kegiatan dinas
            $table->date('tanggal_pengajuan')->nullable();

            // PIC Marketing (siapa yang mengajukan biaya kost)
            $table->unsignedBigInteger('pic_marketing_id');

            // Detail Lokasi
            $table->string('lokasi', 255)->nullable();                  // Nama/alamat kost
            $table->string('kota', 100)->nullable();        // Kota tujuan dinas
            $table->text('keterangan_kegiatan')->nullable(); // Keperluan/tujuan dinas

            // Keuangan
            $table->decimal('nominal', 15, 2)->nullable();              // Total biaya (input manual)

            // Catatan dari marketing
            $table->text('catatan')->nullable();

            // Verifikasi Keuangan
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->dateTime('tanggal_verifikasi')->nullable();
            $table->text('catatan_keuangan')->nullable();    // Feedback dari keuangan

            // Status
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');

            // Meta
            $table->unsignedBigInteger('created_by');       // Yang menginput

            $table->timestamps();

            $table->foreign('pic_marketing_id')->references('id_user')->on('users')->onDelete('restrict');
            $table->foreign('verified_by')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id_user')->on('users')->onDelete('restrict');
        });

        // Tabel bukti pembayaran (multiple file per pengajuan)
        Schema::create('pengajuan_kost_bukti', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_kost_id');
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->string('file_type', 10)->nullable();    // jpg, pdf, png, dll

            $table->timestamps();

            $table->foreign('pengajuan_kost_id')
                ->references('id')
                ->on('pengajuan_kost')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kost_bukti');
        Schema::dropIfExists('pengajuan_kost');
    }
};
