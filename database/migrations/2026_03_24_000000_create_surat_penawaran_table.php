<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_penawaran', function (Blueprint $table) {
            $table->id('id_surat_penawaran');
            $table->unsignedBigInteger('id_proyek');
            $table->unsignedBigInteger('id_penawaran')->nullable();

            $table->string('nomor_surat')->nullable();
            $table->string('tempat_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->string('lampiran')->nullable();

            $table->string('kepada')->nullable();
            $table->string('alamat_klien')->nullable();
            $table->string('wilayah_klien')->nullable();
            $table->string('perihal')->nullable();

            $table->string('jangka_waktu_pengerjaan')->nullable();
            $table->date('berlaku_sejak')->nullable();
            $table->date('berlaku_sampai')->nullable();

            $table->timestamps();

            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->foreign('id_penawaran')->references('id_penawaran')->on('penawaran')->onDelete('set null');

            $table->unique('id_proyek');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_penawaran');
    }
};
