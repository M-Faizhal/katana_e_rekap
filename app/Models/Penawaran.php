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
        'total_nilai',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal_penawaran' => 'date',
        'masa_berlaku' => 'date',
        'total_penawaran' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($penawaran) {
            if (empty($penawaran->no_penawaran)) {
                $penawaran->no_penawaran = static::generateNoPenawaran();
            }

            // Set default values if not provided
            if (empty($penawaran->masa_berlaku)) {
                $penawaran->masa_berlaku = now()->addDays(30);
            }

            if (empty($penawaran->total_penawaran)) {
                $penawaran->total_penawaran = 0;
            }
        });
    }

    public static function generateNoPenawaran()
    {
        $lastPenawaran = static::whereYear('created_at', date('Y'))
                              ->whereMonth('created_at', date('m'))
                              ->orderBy('id_penawaran', 'desc')
                              ->first();

        $counter = $lastPenawaran ? (int)substr($lastPenawaran->no_penawaran, -3) + 1 : 1;
        return 'PNW/' . date('Y/m') . '/' . str_pad($counter, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function penawaranDetail()
    {
        return $this->hasMany(PenawaranDetail::class, 'id_penawaran', 'id_penawaran');
    }

    public function details()
    {
        return $this->hasMany(PenawaranDetail::class, 'id_penawaran', 'id_penawaran');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_penawaran', 'id_penawaran');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'id_penawaran', 'id_penawaran');
    }

    public function penagihanDinas()
    {
        return $this->hasOne(PenagihanDinas::class, 'penawaran_id', 'id_penawaran');
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

    // Method untuk menghitung total otomatis dari detail penawaran
    public function calculateTotal()
    {
        return $this->details()->sum('subtotal');
    }

    // Method untuk update total nilai berdasarkan detail
    public function updateTotalNilai()
    {
        $total = $this->calculateTotal();
        $this->update(['total_nilai' => $total]);
        return $total;
    }
}
