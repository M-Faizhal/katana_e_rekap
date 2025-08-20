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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->unsignedBigInteger('id_penawaran');
            $table->unsignedBigInteger('id_vendor');
            $table->string('no_surat_jalan');
            $table->string('file_surat_jalan')->nullable();
            $table->date('tanggal_kirim');
            $table->string('alamat_kirim')->nullable();
            $table->string('foto_berangkat')->nullable();
            $table->string('foto_perjalanan')->nullable();
            $table->string('foto_sampai')->nullable();
            $table->string('tanda_terima')->nullable();
            $table->enum('status_verifikasi', ['Pending', 'Dalam_Proses', 'Sampai_Tujuan', 'Verified', 'Rejected', 'Gagal'])->default('Pending');
            $table->text('catatan_verifikasi')->nullable(); // Catatan dari superadmin
            $table->unsignedBigInteger('verified_by')->nullable(); // ID superadmin yang verifikasi
            $table->timestamp('verified_at')->nullable(); // Waktu verifikasi
            $table->timestamps();

            $table->foreign('id_penawaran')->references('id_penawaran')->on('penawaran')->onDelete('cascade');
            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('cascade');
            $table->foreign('verified_by')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
