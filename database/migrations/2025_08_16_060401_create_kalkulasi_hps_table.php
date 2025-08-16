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
        Schema::create('kalkulasi_hps', function (Blueprint $table) {
            $table->id('id_kalkulasi');
            $table->unsignedBigInteger('id_proyek');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_vendor');
            $table->integer('qty');
            $table->decimal('harga_vendor', 15, 2);
            $table->decimal('diskon_amount', 15, 2)->default(0);
            $table->decimal('total_diskon', 15, 2)->default(0);
            $table->decimal('total_harga_hpp', 15, 2);
            $table->decimal('kenaikan_percent', 5, 2)->default(0);
            $table->decimal('proyeksi_kenaikan', 15, 2)->default(0);
            $table->decimal('pph', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('ongkir', 15, 2)->default(0);
            $table->decimal('hps', 15, 2);
            $table->decimal('bank_cost', 15, 2)->default(0);
            $table->decimal('biaya_ops', 15, 2)->default(0);
            $table->decimal('bendera', 15, 2)->default(0);
            $table->decimal('nett', 15, 2);
            $table->decimal('nett_percent', 5, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kalkulasi_hps');
    }
};
