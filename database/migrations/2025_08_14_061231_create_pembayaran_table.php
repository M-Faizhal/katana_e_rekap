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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_penawaran');
            $table->unsignedBigInteger('id_vendor');
            $table->string('jenis_bayar');
            $table->decimal('nominal_bayar', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('metode_bayar');
            $table->string('bukti_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status_verifikasi', ['Pending', 'Approved', 'Ditolak'])->default('Pending');
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamps();

            $table->foreign('id_penawaran')->references('id_penawaran')->on('penawaran')->onDelete('cascade');
            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('cascade');
            $table->foreign('diverifikasi_oleh')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
