<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'id_vendor';

    protected $fillable = [
        'nama_vendor',
        'jenis_perusahaan',
        'email',
        'alamat',
        'kontak',
    ];

    // Relationships
    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_vendor');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_vendor');
    }
}
