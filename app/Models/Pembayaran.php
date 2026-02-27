<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_penawaran',
        'id_vendor',
        'jenis_bayar',
        'nominal_bayar',
        'tanggal_bayar',
        'metode_bayar',
        'bukti_bayar',
        'catatan',
        'ppn_data',
        'status_verifikasi',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'nominal_bayar'     => 'decimal:2',
        'tanggal_bayar'     => 'date',
        'tanggal_verifikasi' => 'datetime',
        'ppn_data'          => 'array',
    ];

    /**
     * Total nominal PPN dari pembayaran ini
     */
    public function getTotalPpnAttribute(): float
    {
        return (float) ($this->ppn_data['total_ppn'] ?? 0);
    }

    /**
     * Apakah ada item yang kena PPN
     */
    public function getAdaPpnAttribute(): bool
    {
        if (empty($this->ppn_data['items'])) return false;
        return collect($this->ppn_data['items'])->contains('ada_ppn', true);
    }

    /**
     * Get bukti_bayar as array (handles both legacy string and new JSON array format)
     */
    public function getBuktiBayarArrayAttribute(): array
    {
        $value = $this->bukti_bayar;
        if (empty($value)) {
            return [];
        }
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        // Legacy single-file string
        return [$value];
    }

    /**
     * Set bukti_bayar: always store as JSON array
     */
    public function setBuktiBayarAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['bukti_bayar'] = json_encode(array_values($value));
        } elseif (is_string($value) && $value !== '') {
            // Check if already JSON
            $decoded = json_decode($value, true);
            $this->attributes['bukti_bayar'] = is_array($decoded) ? $value : json_encode([$value]);
        } else {
            $this->attributes['bukti_bayar'] = null;
        }
    }

    // Relationships
    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran', 'id_penawaran');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }

    public function proyek()
    {
        return $this->hasOneThrough(
            Proyek::class,
            Penawaran::class,
            'id_penawaran',
            'id_proyek',
            'id_penawaran',
            'id_proyek'
        );
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh', 'id_user');
    }
}
