<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'id_vendor',
        'nama_barang',
        'foto_barang',
        'spesifikasi',
        'brand',
        'kategori',
        'satuan',
        'harga_vendor',
    ];

    protected $casts = [
        'harga_vendor' => 'decimal:2',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }

    public function penawaranDetail()
    {
        return $this->hasMany(PenawaranDetail::class, 'id_barang');
    }
}
