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
        Schema::table('barang', function (Blueprint $table) {
            $table->decimal('harga_pasaran_inaproc', 15, 2)->nullable()->after('harga_vendor');
            $table->text('spesifikasi_kunci')->nullable()->after('spesifikasi_file');
            $table->string('garansi')->nullable()->after('spesifikasi_kunci');
            $table->enum('pdn_tkdn_impor', ['PDN', 'TKDN', 'Impor'])->nullable()->after('garansi');
            $table->string('skor_tkdn')->nullable()->after('pdn_tkdn_impor');
            $table->string('link_tkdn')->nullable()->after('skor_tkdn');
            $table->string('estimasi_ketersediaan')->nullable()->after('link_tkdn');
            $table->string('link_produk')->nullable()->after('estimasi_ketersediaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn([
                'harga_pasaran_inaproc',
                'spesifikasi_kunci',
                'garansi',
                'pdn_tkdn_impor',
                'skor_tkdn',
                'link_tkdn',
                'estimasi_ketersediaan',
                'link_produk'
            ]);
        });
    }
};
