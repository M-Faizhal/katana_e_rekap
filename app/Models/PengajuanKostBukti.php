<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanKostBukti extends Model
{
    protected $table = 'pengajuan_kost_bukti';

    protected $fillable = [
        'pengajuan_kost_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    public function pengajuanKost()
    {
        return $this->belongsTo(PengajuanKost::class, 'pengajuan_kost_id');
    }
}
