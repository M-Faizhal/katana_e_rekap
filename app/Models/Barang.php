<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'id_vendor',
        'nama_barang',
        'foto_barang',
        'spesifikasi',
        'spesifikasi_file',
        'brand',
        'kategori',
        'satuan',
        'harga_vendor',
        'harga_pasaran_inaproc',
        'spesifikasi_kunci',
        'garansi',
        'pdn_tkdn_impor',
        'skor_tkdn',
        'link_tkdn',
        'estimasi_ketersediaan',
        'link_produk',
    ];

    protected $casts = [
        'harga_vendor' => 'decimal:2',
        'harga_pasaran_inaproc' => 'decimal:2',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // When updating, delete old photo if changed
        static::updating(function ($barang) {
            if ($barang->isDirty('foto_barang')) {
                $oldPhoto = $barang->getOriginal('foto_barang');
                if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }
        });

        // When deleting, delete the photo file
        static::deleting(function ($barang) {
            if ($barang->foto_barang && Storage::disk('public')->exists($barang->foto_barang)) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
        });
    }

    /**
     * Get foto barang URL
     */
    public function getFotoBarangUrlAttribute()
    {
        if ($this->foto_barang && Storage::disk('public')->exists($this->foto_barang)) {
            return asset('storage/' . $this->foto_barang);
        }

        // Return null to indicate no photo
        return null;
    }

    /**
     * Get foto barang path for storage
     */
    public function getFotoBarangPathAttribute()
    {
        return $this->foto_barang;
    }

    /**
     * Check if barang has photo
     */
    public function hasFotoBarang()
    {
        return $this->foto_barang && Storage::disk('public')->exists($this->foto_barang);
    }

    /**
     * Delete foto barang from storage
     */
    public function deleteFotoBarang()
    {
        if ($this->foto_barang && Storage::disk('public')->exists($this->foto_barang)) {
            Storage::disk('public')->delete($this->foto_barang);
            $this->update(['foto_barang' => null]);
            return true;
        }
        return false;
    }

    /**
     * Get spesifikasi file URL
     */
    public function getSpesifikasiFileUrlAttribute()
    {
        if ($this->spesifikasi_file && Storage::disk('public')->exists($this->spesifikasi_file)) {
            return asset('storage/' . $this->spesifikasi_file);
        }

        // Return null to indicate no file
        return null;
    }

    /**
     * Get spesifikasi file path for storage
     */
    public function getSpesifikasiFilePathAttribute()
    {
        return $this->spesifikasi_file;
    }

    /**
     * Check if barang has spesifikasi file
     */
    public function hasSpesifikasiFile()
    {
        return $this->spesifikasi_file && Storage::disk('public')->exists($this->spesifikasi_file);
    }

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
