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
            $table->string('status');
            $table->timestamps();

            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
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
