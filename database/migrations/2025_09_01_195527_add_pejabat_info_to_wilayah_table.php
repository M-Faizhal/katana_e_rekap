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
        Schema::table('wilayah', function (Blueprint $table) {
            $table->string('nama_pejabat')->nullable()->after('instansi');
            $table->string('jabatan')->nullable()->after('nama_pejabat');
            $table->string('no_telp')->nullable()->after('jabatan');
            $table->string('email')->nullable()->after('no_telp');
            $table->text('alamat')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wilayah', function (Blueprint $table) {
            $table->dropColumn(['nama_pejabat', 'jabatan', 'no_telp', 'email', 'alamat']);
        });
    }
};
