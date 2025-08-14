<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_penawaran',
        'jenis_bayar',
        'nominal_bayar',
        'tanggal_bayar',
        'metode_bayar',
        'bukti_bayar',
        'catatan',
        'status_verifikasi',
    ];

    protected $casts = [
        'nominal_bayar' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    // Relationships
    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran');
    }
}
