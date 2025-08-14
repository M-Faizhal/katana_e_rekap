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
        'brand',
        'kategori',
        'satuan',
        'harga_vendor',
    ];

    protected $casts = [
        'harga_vendor' => 'decimal:2',
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
