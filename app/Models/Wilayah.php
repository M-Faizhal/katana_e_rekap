<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';

    protected $fillable = [
        'nama_wilayah',
        'provinsi',
        'kode_wilayah',
        'deskripsi',
        'instansi',
        'nama_pejabat',
        'jabatan',
        'no_telp',
        'email',
        'alamat',
        'admin_marketing_text',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi dengan proyek
    public function proyeks()
    {
        return $this->hasMany(Proyek::class, 'id_wilayah', 'id_wilayah');
    }

    // Scope untuk wilayah aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor untuk nama lengkap
    public function getNamaLengkapAttribute()
    {
        return "{$this->nama_wilayah}, {$this->provinsi}";
    }
}
