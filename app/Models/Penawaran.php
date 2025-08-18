<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penawaran extends Model
{
    use HasFactory;

    protected $table = 'penawaran';
    protected $primaryKey = 'id_penawaran';

    protected $fillable = [
        'id_proyek',
        'no_penawaran',
        'tanggal_penawaran',
        'masa_berlaku',
        'surat_pesanan',
        'surat_penawaran',
        'total_penawaran',
        'status',
    ];

    protected $casts = [
        'tanggal_penawaran' => 'date',
        'masa_berlaku' => 'date',
        'total_penawaran' => 'decimal:2',
    ];

    // Relationships
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function penawaranDetail()
    {
        return $this->hasMany(PenawaranDetail::class, 'id_penawaran', 'id_penawaran');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_penawaran', 'id_penawaran');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_penawaran', 'id_penawaran');
    }

    // Method untuk mendapatkan vendor yang terlibat dalam penawaran ini
    public function getVendorsAttribute()
    {
        return $this->penawaranDetail()
            ->with('barang.vendor')
            ->get()
            ->pluck('barang.vendor')
            ->unique('id_vendor')
            ->values();
    }

    // Method untuk mendapatkan total per vendor (menggunakan harga modal)
    public function getTotalPerVendor($vendorId)
    {
        return $this->penawaranDetail()
            ->with('barang')
            ->whereHas('barang', function($query) use ($vendorId) {
                $query->where('id_vendor', $vendorId);
            })
            ->get()
            ->sum(function($detail) {
                return $detail->qty * $detail->barang->harga_vendor; // harga modal
            });
    }

    // Accessor untuk admin purchasing dari proyek
    public function getAdminPurchasingAttribute()
    {
        return $this->proyek->adminPurchasing ?? null;
    }
}
