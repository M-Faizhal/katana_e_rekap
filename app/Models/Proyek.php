<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    protected $table = 'proyek';
    protected $primaryKey = 'id_proyek';

    protected $fillable = [
        'tanggal',
        'kota_kab',
        'instansi',
        'nama_barang',
        'jumlah',
        'satuan',
        'spesifikasi',
        'harga_satuan',
        'harga_total',
        'jenis_pengadaan',
        'id_admin_marketing',
        'id_admin_purchasing',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga_satuan' => 'decimal:2',
        'harga_total' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    // Relationships
    public function adminMarketing()
    {
        return $this->belongsTo(User::class, 'id_admin_marketing');
    }

    public function adminPurchasing()
    {
        return $this->belongsTo(User::class, 'id_admin_purchasing');
    }

    public function penawaran()
    {
        return $this->hasMany(Penawaran::class, 'id_proyek');
    }
}
