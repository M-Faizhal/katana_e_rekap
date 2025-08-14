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
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang');
            $table->unsignedBigInteger('id_vendor');
            $table->string('nama_barang');
            $table->string('foto_barang')->nullable(); // Assuming foto_barang can be nullable
            $table->string('brand');
            $table->text('spesifikasi');
            $table->enum('kategori', ['Elektronik', 'Meubel', 'Mesin', 'Lain-lain'])->nullable(); // Assuming this is the intended enum
            $table->string('satuan');
            $table->decimal('harga_vendor', 15, 2);
            $table->timestamps();

            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
