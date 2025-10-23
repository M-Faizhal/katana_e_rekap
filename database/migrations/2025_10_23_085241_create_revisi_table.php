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
        Schema::create('revisi', function (Blueprint $table) {
            $table->id('id_revisi');
            $table->unsignedBigInteger('id_proyek');
            $table->enum('tipe_revisi', ['proyek', 'hps_penawaran', 'penawaran', 'penagihan_dinas', 'pembayaran', 'pengiriman']);
            $table->unsignedBigInteger('target_id')->nullable(); // ID dari model yang akan direvisi
            $table->text('keterangan'); // Keterangan revisi apa yang dibutuhkan
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->unsignedBigInteger('created_by'); // User yang membuat revisi
            $table->unsignedBigInteger('handled_by')->nullable(); // User yang menangani revisi
            $table->text('catatan_revisi')->nullable(); // Catatan dari user yang menangani revisi
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->foreign('created_by')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('handled_by')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisi');
    }
};
