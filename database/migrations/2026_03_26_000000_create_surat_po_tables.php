<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_po', function (Blueprint $table) {
            $table->id('id_surat_po');

            $table->unsignedBigInteger('id_proyek');
            $table->unsignedBigInteger('id_vendor');
            $table->unsignedBigInteger('id_user_purchasing');

            // Meta
            $table->date('tanggal_surat')->nullable();
            $table->string('po_number'); // default: kode_proyek

            // Ship to
            $table->string('ship_to_instansi')->nullable();
            $table->text('ship_to_alamat')->nullable();

            // Comments / instructions (RTE HTML)
            $table->longText('comments_html')->nullable();

            // Totals input (can be 0)
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping', 15, 2)->default(0);
            $table->decimal('other', 15, 2)->default(0);

            // Payment terms (percent)
            $table->decimal('dp_percent', 5, 2)->default(30);
            $table->decimal('termin2_percent', 5, 2)->default(30);
            $table->decimal('pelunasan_percent', 5, 2)->default(40);
            // Lampiran file (format JSON array: [{path, original_name, uploaded_at, size}, ...])
            $table->json('lampiran_files')->nullable();

            $table->timestamps();

            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('cascade');
            $table->foreign('id_user_purchasing')->references('id_user')->on('users')->onDelete('cascade');

            $table->unique(['id_proyek', 'id_vendor'], 'surat_po_proyek_vendor_unique');
        });

        Schema::create('surat_po_items', function (Blueprint $table) {
            $table->id('id_surat_po_item');
            $table->unsignedBigInteger('id_surat_po');

            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_kalkulasi_hps')->nullable();

            $table->integer('qty')->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);

            // Spec per item (RTE HTML)
            $table->longText('spec_html')->nullable();

            $table->timestamps();

            $table->foreign('id_surat_po')->references('id_surat_po')->on('surat_po')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('restrict');
            $table->foreign('id_kalkulasi_hps')->references('id_kalkulasi')->on('kalkulasi_hps')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_po_items');
        Schema::dropIfExists('surat_po');
    }
};
