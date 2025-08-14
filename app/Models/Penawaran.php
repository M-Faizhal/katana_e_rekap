<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    protected $table = 'penawaran';
    protected $primaryKey = 'id_penawaran';

    protected $fillable = [
        'id_proyek',
        'no_penawaran',
        'tanggal_penawaran',
        'masa_berlaku',
        'surat_pesanan',
        'surat_penawaran',
        'total_penawaran',
        'status',
    ];

    protected $casts = [
        'tanggal_penawaran' => 'date',
        'masa_berlaku' => 'date',
        'total_penawaran' => 'decimal:2',
    ];

    // Relationships
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek');
    }

    public function penawaranDetail()
    {
        return $this->hasMany(PenawaranDetail::class, 'id_penawaran');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_penawaran');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_penawaran');
    }
}
