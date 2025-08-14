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
            $table->string('no_surat_jalan');
            $table->string('file_surat_jalan')->nullable();
            $table->date('tanggal_kirim');
            $table->string('foto_berangkat')->nullable();
            $table->string('foto_perjalanan')->nullable();
            $table->string('foto_sampai')->nullable();
            $table->string('tanda_terima')->nullable();
            $table->string('status_verifikasi');
            $table->timestamps();

            $table->foreign('id_penawaran')->references('id_penawaran')->on('penawaran')->onDelete('cascade');
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
