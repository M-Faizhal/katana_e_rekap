<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenawaranDetail extends Model
{
    protected $table = 'penawaran_detail';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_penawaran',
        'id_barang',
        'nama_barang',
        'spesifikasi',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
