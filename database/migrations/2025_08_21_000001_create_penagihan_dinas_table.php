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
        Schema::create('penagihan_dinas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyek_id');
            $table->unsignedBigInteger('penawaran_id');
            $table->string('nomor_invoice')->unique();
            $table->decimal('total_harga', 15, 2);
            $table->enum('status_pembayaran', ['belum_bayar', 'dp', 'lunas'])->default('belum_bayar');
            $table->decimal('persentase_dp', 5, 2)->nullable();
            $table->decimal('jumlah_dp', 15, 2)->nullable();
            $table->date('tanggal_jatuh_tempo');
            $table->text('keterangan')->nullable();
            
            // Dokumen yang diunggah
            $table->string('berita_acara_serah_terima')->nullable();
            $table->string('invoice')->nullable();
            $table->string('pnbp')->nullable();
            $table->string('faktur_pajak')->nullable();
            $table->string('surat_lainnya')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('proyek_id')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->foreign('penawaran_id')->references('id_penawaran')->on('penawaran')->onDelete('cascade');
        });
        
        // Tabel untuk bukti pembayaran
        Schema::create('bukti_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penagihan_dinas_id')->constrained('penagihan_dinas')->onDelete('cascade');
            $table->enum('jenis_pembayaran', ['dp', 'lunas', 'pelunasan']);
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('bukti_pembayaran');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_pembayaran');
        Schema::dropIfExists('penagihan_dinas');
    }
};