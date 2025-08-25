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
        Schema::create('proyek_barang', function (Blueprint $table) {
            $table->id('id_proyek_barang');
            $table->unsignedBigInteger('id_proyek');
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->string('satuan', 50);
            $table->text('spesifikasi');
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('harga_total', 15, 2)->nullable();
            $table->timestamps();
            
            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->index('id_proyek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek_barang');
    }
};
