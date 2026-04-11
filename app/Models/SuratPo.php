<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Tambahkan import untuk relasi
use App\Models\SuratPoItem;
use App\Models\Proyek;
use App\Models\Vendor;
use App\Models\User;

class SuratPo extends Model
{
    protected $table = 'surat_po';
    protected $primaryKey = 'id_surat_po';

    protected $fillable = [
        'id_proyek',
        'id_vendor',
        'id_user_purchasing',
        'tanggal_surat',
        'po_number',
        'ship_to_instansi',
        'ship_to_alamat',
        'comments_html',
        'tax',
        'shipping',
        'other',
        'dp_percent',
        'termin2_percent',
        'pelunasan_percent',
        'lampiran_files',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'other' => 'decimal:2',
        'dp_percent' => 'decimal:2',
        'termin2_percent' => 'decimal:2',
        'pelunasan_percent' => 'decimal:2',
        // lampiran_files di-handle manual untuk konsistensi
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }

    public function purchasing()
    {
        return $this->belongsTo(User::class, 'id_user_purchasing', 'id_user');
    }

    public function items()
    {
        return $this->hasMany(SuratPoItem::class, 'id_surat_po', 'id_surat_po');
    }

    public function getDppAttribute(): float
    {
        return (float) $this->items
            ->map(fn($i) => ((float) $i->qty) * ((float) $i->unit_price))
            ->sum();
    }

    public function getTotalAttribute(): float
    {
        return $this->dpp + (float) $this->tax + (float) $this->shipping + (float) $this->other;
    }

    public function getDpAmountAttribute(): float
    {
        return $this->total * ((float) $this->dp_percent / 100);
    }

    public function getTermin2AmountAttribute(): float
    {
        return $this->total * ((float) $this->termin2_percent / 100);
    }

    public function getPelunasanAmountAttribute(): float
    {
        return $this->total * ((float) $this->pelunasan_percent / 100);
    }

    /**
     * Getter: $model->lampiran_files_list (array)
     */
    public function getLampiranFilesListAttribute(): array
    {
        $raw = $this->attributes['lampiran_files'] ?? null;

        if (is_null($raw) || $raw === '' || $raw === 'null') {
            return [];
        }

        if (is_array($raw)) {
            return $raw;
        }

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * Setter: simpan JSON string
     */
    public function setLampiranFilesAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['lampiran_files'] = json_encode(array_values($value));
        } elseif (is_string($value)) {
            $decoded = json_decode($value, true);
            $this->attributes['lampiran_files'] = is_array($decoded)
                ? json_encode(array_values($decoded))
                : json_encode([]);
        } else {
            $this->attributes['lampiran_files'] = json_encode([]);
        }
    }
}
