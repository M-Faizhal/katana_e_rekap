<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalkulasiHps extends Model
{
    protected $table = 'kalkulasi_hps';
    protected $primaryKey = 'id_kalkulasi';

    protected $fillable = [
        'id_proyek',
        'id_barang',
        'id_vendor',
        'qty',
        'harga_vendor',
        'diskon_amount',
        'total_diskon',
        'total_harga_hpp',
        'kenaikan_percent',
        'proyeksi_kenaikan',
        'pph',
        'ppn',
        'ongkir',
        'hps',
        'bank_cost',
        'biaya_ops',
        'bendera',
        'nett',
        'nett_percent',
        'catatan',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_vendor' => 'decimal:2',
        'diskon_amount' => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'total_harga_hpp' => 'decimal:2',
        'kenaikan_percent' => 'decimal:2',
        'proyeksi_kenaikan' => 'decimal:2',
        'pph' => 'decimal:2',
        'ppn' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'hps' => 'decimal:2',
        'bank_cost' => 'decimal:2',
        'biaya_ops' => 'decimal:2',
        'bendera' => 'decimal:2',
        'nett' => 'decimal:2',
        'nett_percent' => 'decimal:2',
    ];

    // Relationships
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }
}
