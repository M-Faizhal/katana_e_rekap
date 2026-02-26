<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah kolom bukti_bayar dari string menjadi text untuk menyimpan JSON array multiple files.
     */
    public function up(): void
    {
        // Migrate existing single-file data to JSON array format
        $records = DB::table('pembayaran')
            ->whereNotNull('bukti_bayar')
            ->orderBy('id_pembayaran')
            ->get(['id_pembayaran', 'bukti_bayar']);

        foreach ($records as $record) {
            $decoded = json_decode($record->bukti_bayar, true);
            if (!is_array($decoded)) {
                DB::table('pembayaran')
                    ->where('id_pembayaran', $record->id_pembayaran)
                    ->update(['bukti_bayar' => json_encode([$record->bukti_bayar])]);
            }
        }

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->text('bukti_bayar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $records = DB::table('pembayaran')
            ->whereNotNull('bukti_bayar')
            ->orderBy('id_pembayaran')
            ->get(['id_pembayaran', 'bukti_bayar']);

        foreach ($records as $record) {
            $decoded = json_decode($record->bukti_bayar, true);
            if (is_array($decoded) && count($decoded) > 0) {
                DB::table('pembayaran')
                    ->where('id_pembayaran', $record->id_pembayaran)
                    ->update(['bukti_bayar' => $decoded[0]]);
            }
        }

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('bukti_bayar')->nullable()->change();
        });
    }
};
