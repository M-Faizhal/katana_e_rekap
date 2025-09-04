<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyekBarang extends Model
{
    use HasFactory;

    protected $table = 'proyek_barang';
    protected $primaryKey = 'id_proyek_barang';

    protected $fillable = [
        'id_proyek',
        'nama_barang',
        'jumlah',
        'satuan',
        'spesifikasi',
        'spesifikasi_files',
        'harga_satuan',
        'harga_total'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'harga_total' => 'decimal:2',
        'spesifikasi_files' => 'array'
    ];

    // Relationship
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    // Helper methods untuk file spesifikasi
    public function getSpecificationFilesAttribute($value)
    {
        return $value ? (is_string($value) ? json_decode($value, true) : $value) : [];
    }

    public function hasSpecificationFiles()
    {
        $files = $this->spesifikasi_files;
        return is_array($files) && count($files) > 0;
    }

    public function getSpecificationFileCount()
    {
        $files = $this->spesifikasi_files;
        return is_array($files) ? count($files) : 0;
    }

    public function addSpecificationFile($fileData)
    {
        $files = $this->spesifikasi_files ?: [];
        $files[] = $fileData;
        $this->spesifikasi_files = $files;
        return $this;
    }

    // Observer untuk auto-update harga_total proyek
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($proyekBarang) {
            if ($proyekBarang->id_proyek) {
                \App\Models\Proyek::updateHargaTotal($proyekBarang->id_proyek);
            }
        });

        static::deleted(function ($proyekBarang) {
            if ($proyekBarang->id_proyek) {
                \App\Models\Proyek::updateHargaTotal($proyekBarang->id_proyek);
            }
        });
    }
}
