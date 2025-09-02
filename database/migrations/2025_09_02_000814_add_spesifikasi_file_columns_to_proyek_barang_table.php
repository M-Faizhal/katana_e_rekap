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
        Schema::table('proyek_barang', function (Blueprint $table) {
            $table->string('spesifikasi_file_path')->nullable()->after('spesifikasi');
            $table->string('spesifikasi_file_name')->nullable()->after('spesifikasi_file_path');
            $table->enum('spesifikasi_type', ['text', 'file'])->default('text')->after('spesifikasi_file_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyek_barang', function (Blueprint $table) {
            $table->dropColumn(['spesifikasi_file_path', 'spesifikasi_file_name', 'spesifikasi_type']);
        });
    }
};
