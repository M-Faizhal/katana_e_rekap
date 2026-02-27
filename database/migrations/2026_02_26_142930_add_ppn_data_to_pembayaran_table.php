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
        Schema::table('pembayaran', function (Blueprint $table) {
            // Menyimpan data PPN per barang dalam format JSON
            // Format: {
            //   "items": [
            //     {
            //       "id_barang": 1,
            //       "nama_barang": "...",
            //       "harga": 1000000,
            //       "ada_ppn": true,
            //       "persen_ppn": 11,
            //       "nominal_ppn": 99099.10,
            //       "sebelum_ppn": 900900.90
            //     }
            //   ],
            //   "total_ppn": 99099.10,
            //   "total_sebelum_ppn": 900900.90
            // }
            $table->json('ppn_data')->nullable()->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('ppn_data');
        });
    }
};
