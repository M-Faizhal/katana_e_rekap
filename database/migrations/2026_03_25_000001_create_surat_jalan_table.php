<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_jalan', function (Blueprint $table) {
            $table->id('id_surat_jalan');

            $table->unsignedBigInteger('id_proyek');
            $table->unsignedBigInteger('id_penawaran')->nullable();

            $table->date('tanggal_surat')->nullable();

            // Data klien
            $table->string('nama_klien')->nullable();
            $table->text('alamat_klien')->nullable();
            $table->string('nomor_klien')->nullable();

            // Comments / special instruction (rich text HTML)
            $table->longText('special_instruction')->nullable();

            // Lampiran file (format JSON array: [{path, original_name, uploaded_at, size}, ...])
            $table->json('lampiran_files')->nullable();

            $table->timestamps();

            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->foreign('id_penawaran')->references('id_penawaran')->on('penawaran')->onDelete('set null');

            // 1 proyek = 1 surat jalan
            $table->unique('id_proyek');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_jalan');
    }
};
