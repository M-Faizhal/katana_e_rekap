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
        Schema::create('proyek', function (Blueprint $table) {
            $table->id('id_proyek');
            $table->date('tanggal');
            $table->string('kota_kab');
            $table->string('instansi');
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->text('spesifikasi');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('harga_total', 15, 2);
            $table->string('jenis_pengadaan');
            $table->unsignedBigInteger('id_admin_marketing');
            $table->unsignedBigInteger('id_admin_purchasing');
            $table->text('catatan')->nullable();
            $table->string('status');
            $table->timestamps();

            $table->foreign('id_admin_marketing')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_admin_purchasing')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek');
    }
};
