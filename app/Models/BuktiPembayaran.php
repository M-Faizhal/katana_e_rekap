<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'bukti_pembayaran';

    protected $fillable = [
        'penagihan_dinas_id',
        'jenis_pembayaran',
        'jumlah_bayar',
        'tanggal_bayar',
        'bukti_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    public function penagihanDinas()
    {
        return $this->belongsTo(PenagihanDinas::class);
    }

    public function getFormattedJumlahBayarAttribute()
    {
        return 'Rp ' . number_format((float)$this->jumlah_bayar, 0, ',', '.');
    }

    public function getJenisPembayaranLabelAttribute()
    {
        return match($this->jenis_pembayaran) {
            'dp' => 'Down Payment',
            'lunas' => 'Lunas',
            'pelunasan' => 'Pelunasan',
            default => 'Unknown'
        };
    }
}
