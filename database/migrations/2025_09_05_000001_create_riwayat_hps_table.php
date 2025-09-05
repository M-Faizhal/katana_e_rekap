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
        Schema::create('riwayat_hps', function (Blueprint $table) {
            $table->id('id_riwayat_hps');
            $table->string('id_proyek');
            $table->string('id_barang');
            $table->string('id_vendor');
            $table->string('nama_barang');
            $table->string('nama_vendor')->nullable();
            $table->string('jenis_vendor')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('qty')->default(1);
            $table->decimal('harga_vendor', 20, 2)->default(0);
            $table->decimal('harga_diskon', 20, 2)->default(0);
            $table->decimal('nilai_diskon', 20, 2)->default(0);
            $table->decimal('total_diskon', 20, 2)->default(0);
            $table->decimal('total_harga', 20, 2)->default(0);
            $table->decimal('jumlah_volume', 20, 2)->default(0);
            $table->decimal('persen_kenaikan', 8, 2)->default(0);
            $table->decimal('proyeksi_kenaikan', 20, 2)->default(0);
            $table->decimal('ppn_dinas', 20, 2)->default(0);
            $table->decimal('pph_dinas', 20, 2)->default(0);
            $table->decimal('hps', 20, 2)->default(0);
            $table->decimal('harga_per_pcs', 20, 2)->default(0);
            $table->decimal('harga_pagu_dinas_per_pcs', 20, 2)->default(0);
            $table->decimal('pagu_total', 20, 2)->default(0);
            $table->decimal('selisih_pagu_hps', 20, 2)->default(0);
            $table->decimal('nilai_sp', 20, 2)->default(0);
            $table->decimal('dpp', 20, 2)->default(0);
            $table->decimal('asumsi_nilai_cair', 20, 2)->default(0);
            $table->decimal('ongkir', 20, 2)->default(0);
            $table->decimal('omzet_dinas_percent', 8, 2)->default(0);
            $table->decimal('omzet_nilai_dinas', 20, 2)->default(0);
            $table->decimal('bendera_percent', 8, 2)->default(0);
            $table->decimal('gross_nilai_bendera', 20, 2)->default(0);
            $table->decimal('bank_cost_percent', 8, 2)->default(0);
            $table->decimal('gross_nilai_bank_cost', 20, 2)->default(0);
            $table->decimal('biaya_ops_percent', 8, 2)->default(0);
            $table->decimal('gross_nilai_biaya_ops', 20, 2)->default(0);
            $table->decimal('sub_total_biaya_tidak_langsung', 20, 2)->default(0);
            $table->decimal('gross_income', 20, 2)->default(0);
            $table->decimal('gross_income_persentase', 8, 2)->default(0);
            $table->decimal('nilai_nett_income', 20, 2)->default(0);
            $table->decimal('nett_income_persentase', 8, 2)->default(0);
            $table->text('keterangan_1')->nullable();
            $table->text('keterangan_2')->nullable();
            
            // Riwayat info
            $table->string('created_by');
            $table->string('action_type')->default('edit'); // 'create', 'edit', 'delete'
            $table->text('action_description')->nullable();
            $table->json('changes')->nullable(); // JSON untuk menyimpan perubahan yang terjadi
            $table->timestamps();

            // Indexes
            $table->index('id_proyek');
            $table->index('id_barang');
            $table->index('id_vendor');
            $table->index('created_by');
            $table->index('action_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_hps');
    }
};
