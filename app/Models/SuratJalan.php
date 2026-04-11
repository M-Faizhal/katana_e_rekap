<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalan extends Model
{
    protected $table = 'surat_jalan';
    protected $primaryKey = 'id_surat_jalan';

    protected $fillable = [
        'id_proyek',
        'id_penawaran',
        'tanggal_surat',
        'nama_klien',
        'alamat_klien',
        'nomor_klien',
        'special_instruction',
        'lampiran_files',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        // lampiran_files di-handle manual untuk konsistensi
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran', 'id_penawaran');
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
