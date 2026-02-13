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
        'pkp',
        'keterangan',
        'online_shop',
        'nama_online_shop',
    ];

    // Accessor untuk nama
    public function getNamaAttribute()
    {
        return $this->nama_vendor;
    }

    // Accessor untuk jenis
    public function getJenisAttribute()
    {
        return $this->jenis_perusahaan;
    }

    // Accessor untuk status (default aktif)
    public function getStatusAttribute()
    {
        return 'Aktif'; // Default status, bisa disesuaikan jika ada field status
    }

    // Relationships
    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_vendor');
    }

    public function penawaran()
    {
        return $this->hasMany(Penawaran::class, 'id_vendor');
    }
}
