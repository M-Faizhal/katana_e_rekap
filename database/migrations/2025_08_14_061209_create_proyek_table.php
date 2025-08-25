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
            $table->string('nama_klien'); // Nama klien yang request
            $table->string('kontak_klien')->nullable(); // Kontak klien (telp/email)
            $table->string('jenis_pengadaan');
            $table->date('deadline')->nullable(); // Deadline dari klien
            $table->unsignedBigInteger('id_admin_marketing');
            $table->unsignedBigInteger('id_admin_purchasing');
            $table->unsignedBigInteger('id_penawaran')->nullable(); // Link ke penawaran aktif
            $table->text('catatan')->nullable();
            $table->decimal('harga_total', 15, 2)->nullable(); // Akumulasi dari proyek_barang
            $table->enum('status', ['Menunggu', 'Penawaran', 'Pembayaran', 'Pengiriman', 'Selesai', 'Gagal'])->default('Menunggu');
            $table->timestamps();

            $table->foreign('id_admin_marketing')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_admin_purchasing')->references('id_user')->on('users')->onDelete('cascade');
            // Foreign key untuk id_penawaran akan ditambahkan setelah tabel penawaran dibuat
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
