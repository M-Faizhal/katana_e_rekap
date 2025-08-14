<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'id_penawaran',
        'no_surat_jalan',
        'file_surat_jalan',
        'tanggal_kirim',
        'foto_berangkat',
        'foto_perjalanan',
        'foto_sampai',
        'tanda_terima',
        'status_verifikasi',
    ];

    protected $casts = [
        'tanggal_kirim' => 'date',
    ];

    // Relationships
    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran');
    }
}
