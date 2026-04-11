<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPenawaran extends Model
{
    protected $table = 'surat_penawaran';
    protected $primaryKey = 'id_surat_penawaran';

    protected $fillable = [
        'id_proyek',
        'id_penawaran',
        'nomor_surat',
        'tempat_surat',
        'tanggal_surat',
        'lampiran',
        'lampiran_files',
        'kepada',
        'alamat_klien',
        'wilayah_klien',
        'perihal',
        'jangka_waktu_pengerjaan',
        'berlaku_sejak',
        'berlaku_sampai',
    ];

    protected $casts = [
        'tanggal_surat'  => 'date',
        'berlaku_sejak'  => 'date',
        'berlaku_sampai' => 'date',
        // TIDAK ada cast 'array' untuk lampiran_files — handle manual via getter/setter
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
     * Getter accessor untuk $model->lampiran_files_list
     * Selalu kembalikan array dari JSON string di kolom lampiran_files.
     */
    public function getLampiranFilesListAttribute(): array
    {
        // Ambil raw value langsung dari attributes (tidak lewat getter lain)
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
     * Setter: selalu simpan sebagai JSON string ke kolom lampiran_files.
     * Dipanggil dengan: $surat->lampiran_files = $array;
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

    /**
     * TIDAK ada getLampiranFilesAttribute() — biarkan Eloquent akses kolom asli.
     * Gunakan selalu ->lampiran_files_list untuk baca sebagai array.
     */
}