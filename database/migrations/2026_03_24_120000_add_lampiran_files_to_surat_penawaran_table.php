<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('surat_penawaran', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_penawaran', 'lampiran_files')) {
                $table->json('lampiran_files')->nullable()->after('lampiran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('surat_penawaran', function (Blueprint $table) {
            if (Schema::hasColumn('surat_penawaran', 'lampiran_files')) {
                $table->dropColumn('lampiran_files');
            }
        });
    }
};
