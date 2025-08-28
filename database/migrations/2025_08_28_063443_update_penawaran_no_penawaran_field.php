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
        Schema::table('penawaran', function (Blueprint $table) {
            // Make sure no_penawaran field exists and is properly configured
            // If it already exists, this will modify it to ensure it's nullable during creation
            $table->string('no_penawaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penawaran', function (Blueprint $table) {
            // Revert back to non-nullable
            $table->string('no_penawaran')->nullable(false)->change();
        });
    }
};
