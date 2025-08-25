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
        Schema::create('penawaran', function (Blueprint $table) {
            $table->id('id_penawaran');
            $table->unsignedBigInteger('id_proyek');
            $table->string('no_penawaran');
            $table->date('tanggal_penawaran');
            $table->date('masa_berlaku');
            $table->string('surat_pesanan')->nullable();
            $table->string('surat_penawaran')->nullable();
            $table->decimal('total_penawaran', 15, 2);
            $table->enum('status', ['Menunggu', 'ACC', 'Ditolak'])->default('Menunggu');
            $table->timestamps();

            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
        });

        // Tambahkan foreign key untuk id_penawaran di tabel proyek setelah tabel penawaran dibuat
        Schema::table('proyek', function (Blueprint $table) {
            $table->foreign('id_penawaran')->references('id_penawaran')->on('penawaran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penawaran');
    }
};