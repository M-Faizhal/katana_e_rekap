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
        Schema::table('vendor', function (Blueprint $table) {
            $table->enum('pkp', ['ya', 'tidak'])->default('tidak')->after('alamat');
            $table->text('keterangan')->nullable()->after('pkp');
            $table->enum('online_shop', ['ya', 'tidak'])->default('tidak')->after('keterangan');
            $table->string('nama_online_shop')->nullable()->after('online_shop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->dropColumn(['pkp', 'keterangan', 'online_shop', 'nama_online_shop']);
        });
    }
};
