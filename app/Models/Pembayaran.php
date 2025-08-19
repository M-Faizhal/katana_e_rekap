<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_penawaran',
        'id_vendor',
        'jenis_bayar',
        'nominal_bayar',
        'tanggal_bayar',
        'metode_bayar',
        'bukti_bayar',
        'catatan',
        'status_verifikasi',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'nominal_bayar' => 'decimal:2',
        'tanggal_bayar' => 'date',
        'tanggal_verifikasi' => 'datetime',
    ];

    // Relationships
    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran', 'id_penawaran');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }

    public function proyek()
    {
        return $this->hasOneThrough(
            Proyek::class,
            Penawaran::class,
            'id_penawaran', // Foreign key on penawaran table
            'id_proyek', // Foreign key on proyek table
            'id_penawaran', // Local key on pembayaran table
            'id_proyek' // Local key on penawaran table
        );
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh', 'id_user');
    }
}
