<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_tanda_terima', function (Blueprint $table) {
            $table->id('id_surat_tanda_terima');

            $table->unsignedBigInteger('id_proyek');
            $table->unsignedBigInteger('id_penawaran')->nullable();

            $table->string('nomor_surat')->nullable();
            $table->string('tempat_surat')->nullable();
            $table->date('tanggal_surat')->nullable();

            // Lampiran file (format JSON array: [{path, original_name, uploaded_at, size}, ...])
            $table->json('lampiran_files')->nullable();

            $table->timestamps();

            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->foreign('id_penawaran')->references('id_penawaran')->on('penawaran')->onDelete('set null');

            // 1 proyek = 1 surat tanda terima
            $table->unique('id_proyek');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tanda_terima');
    }
};
