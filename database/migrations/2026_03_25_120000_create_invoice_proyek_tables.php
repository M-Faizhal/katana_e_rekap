<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_proyek', function (Blueprint $table) {
            $table->id('id_invoice');

            $table->unsignedBigInteger('id_proyek');
            $table->unsignedBigInteger('id_penawaran')->nullable();

            $table->date('tanggal_surat')->nullable();
            $table->string('nomor_surat')->nullable();

            $table->string('bill_to_instansi')->nullable();
            $table->text('bill_to_alamat')->nullable();

            $table->string('ship_to_instansi')->nullable();
            $table->text('ship_to_alamat')->nullable();
            $table->text('rekening')->nullable();

            $table->timestamps();

            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->foreign('id_penawaran')->references('id_penawaran')->on('penawaran')->onDelete('set null');

            // 1 proyek = 1 invoice (ubah jika butuh multiple invoice per proyek)
            $table->unique('id_proyek');
        });

        Schema::create('invoice_proyek_items', function (Blueprint $table) {
            $table->id('id_invoice_item');

            $table->unsignedBigInteger('id_invoice');
            $table->unsignedBigInteger('id_penawaran_detail');

            // Rich text HTML untuk keterangan per barang
            $table->longText('keterangan_html')->nullable();

            $table->timestamps();

            $table->foreign('id_invoice')->references('id_invoice')->on('invoice_proyek')->onDelete('cascade');
            $table->foreign('id_penawaran_detail')->references('id_detail')->on('penawaran_detail')->onDelete('cascade');

            $table->unique(['id_invoice', 'id_penawaran_detail']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_proyek_items');
        Schema::dropIfExists('invoice_proyek');
    }
};
