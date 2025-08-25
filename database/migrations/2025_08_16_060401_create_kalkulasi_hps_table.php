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
            
            // Basic fields
            $table->integer('qty');
            $table->decimal('harga_vendor', 15, 2); // Harga Awal
            $table->decimal('diskon_amount', 15, 2)->default(0); // Nilai Diskon
            $table->decimal('total_diskon', 15, 2)->default(0);
            $table->decimal('harga_akhir', 15, 2)->default(0); // Harga Akhir = Harga Diskon
            $table->decimal('total_harga_hpp', 15, 2);
            $table->decimal('jumlah_volume', 15, 2)->default(0); // Jumlah Volume Yang Dikerjakan
            
            // Pricing calculations
            $table->decimal('kenaikan_percent', 5, 2)->default(0);
            $table->decimal('proyeksi_kenaikan', 15, 2)->default(0);
            $table->decimal('pph', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('ongkir', 15, 2)->default(0);
            $table->decimal('hps', 15, 2);
            
            // Additional fields
            $table->decimal('nilai_tkdn_percent', 5, 2)->default(0); // Nilai TKDN %
            $table->string('jenis_vendor')->nullable(); // Jenis Vendor
            $table->decimal('nilai_pagu_anggaran', 15, 2)->default(0); // Nilai Pagu Anggaran
            $table->decimal('nilai_penawaran_hps', 15, 2)->default(0); // Nilai Penawaran/HPS
            $table->decimal('nilai_pesanan', 15, 2)->default(0); // Nilai Pesanan
            $table->decimal('nilai_selisih', 15, 2)->default(0); // Nilai Selisih
            $table->decimal('nilai_dpp', 15, 2)->default(0); // Nilai DPP
            $table->decimal('ppn_percent', 5, 2)->default(11); // PPN %
            $table->decimal('pph_badan_percent', 5, 2)->default(1.5); // PPH Badan %
            $table->decimal('nilai_ppn', 15, 2)->default(0); // Nilai PPN
            $table->decimal('nilai_pph_badan', 15, 2)->default(0); // Nilai PPH Badan
            $table->decimal('nilai_asumsi_cair', 15, 2)->default(0); // Nilai Asumsi Cair
            $table->decimal('sub_total_langsung', 15, 2)->default(0); // Sub Total Langsung
            
            // Cost components
            $table->decimal('bank_cost', 15, 2)->default(0);
            $table->decimal('biaya_ops', 15, 2)->default(0);
            $table->decimal('bendera', 15, 2)->default(0);
            $table->decimal('omzet_dinas_percent', 5, 2)->default(0); // Omzet Nilai Dinas %
            $table->decimal('omzet_dinas', 15, 2)->default(0); // Omzet Nilai Dinas
            $table->decimal('gross_bendera', 15, 2)->default(0); // Gross Nilai Bendera
            $table->decimal('gross_bank_cost', 15, 2)->default(0); // Gross Nilai Bank Cost
            $table->decimal('gross_biaya_ops', 15, 2)->default(0); // Gross Nilai Biaya Operasional
            $table->decimal('sub_total_tidak_langsung', 15, 2)->default(0); // Sub Total Biaya Tidak Langsung
            
            // Final calculations
            $table->decimal('nett', 15, 2);
            $table->decimal('nett_percent', 5, 2);
            $table->decimal('nilai_nett_pcs', 15, 2)->default(0); // Nilai Nett Per PCS
            $table->decimal('total_nett_pcs', 15, 2)->default(0); // Total Nilai Nett Per PCS
            $table->decimal('gross_income', 15, 2)->default(0); // Gross Income
            $table->decimal('gross_income_percent', 5, 2)->default(0); // Gross Income Persentase
            $table->decimal('nett_income', 15, 2)->default(0); // Nilai Nett Income
            $table->decimal('nett_income_percent', 5, 2)->default(0); // Nett Income Persentase
            
            // Additional fields
            $table->text('catatan')->nullable();
            $table->string('keterangan_1')->nullable(); // Keterangan dropdown 1
            $table->string('keterangan_2')->nullable(); // Keterangan dropdown 2
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
