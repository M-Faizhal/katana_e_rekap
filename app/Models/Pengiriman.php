<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'id_penawaran',
        'id_vendor',
        'no_surat_jalan',
        'file_surat_jalan',
        'tanggal_kirim',
        'alamat_kirim',
        'foto_berangkat',
        'foto_perjalanan',
        'foto_sampai',
        'tanda_terima',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'tanggal_kirim' => 'date',
        'verified_at' => 'datetime'
    ];

    // Relationship dengan Penawaran
    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran', 'id_penawaran');
    }

    // Relationship dengan Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }

    // Accessor untuk status verifikasi yang lebih readable
    public function getStatusVerifikasiLabelAttribute()
    {
        $labels = [
            'Pending' => 'Menunggu Verifikasi',
            'Dalam_Proses' => 'Dalam Perjalanan',
            'Sampai_Tujuan' => 'Sampai di Tujuan',
            'Verified' => 'Diverifikasi (Selesai)',
            'Rejected' => 'Ditolak'
        ];

        return $labels[$this->status_verifikasi] ?? 'Unknown';
    }

    // Accessor untuk cek kelengkapan dokumentasi
    public function getDokumentasiLengkapAttribute()
    {
        return !empty($this->foto_berangkat) && 
               !empty($this->foto_perjalanan) && 
               !empty($this->foto_sampai) && 
               !empty($this->tanda_terima);
    }

    /**
     * Ambil checklist data dari catatan_verifikasi (format: [CHECKLIST]{...})
     * Tanpa migration — checklist disimpan sebagai prefix JSON di catatan_verifikasi
     */
    public function getChecklistDataAttribute(): array
    {
        if ($this->catatan_verifikasi && str_starts_with($this->catatan_verifikasi, '[CHECKLIST]')) {
            $json = substr($this->catatan_verifikasi, strlen('[CHECKLIST]'));
            $decoded = json_decode($json, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    /**
     * Hitung progress berdasarkan file (prioritas) + checklist (fallback)
     * Tiap item bernilai 1 dari total 4
     */
    public function getProgressPersenAttribute(): int
    {
        $checklist = $this->checklist_data;
        $fields = [
            ['file' => 'foto_berangkat',  'check' => 'berangkat'],
            ['file' => 'foto_perjalanan', 'check' => 'perjalanan'],
            ['file' => 'foto_sampai',     'check' => 'sampai'],
            ['file' => 'tanda_terima',    'check' => 'terima'],
        ];
        $done = 0;
        foreach ($fields as $f) {
            if (!empty($this->{$f['file']}) || !empty($checklist[$f['check']])) {
                $done++;
            }
        }
        return (int) round(($done / 4) * 100);
    }

    /**
     * Jumlah item terpenuhi (file atau checklist)
     */
    public function getProgressCountAttribute(): int
    {
        $checklist = $this->checklist_data;
        $fields = [
            ['file' => 'foto_berangkat',  'check' => 'berangkat'],
            ['file' => 'foto_perjalanan', 'check' => 'perjalanan'],
            ['file' => 'foto_sampai',     'check' => 'sampai'],
            ['file' => 'tanda_terima',    'check' => 'terima'],
        ];
        $done = 0;
        foreach ($fields as $f) {
            if (!empty($this->{$f['file']}) || !empty($checklist[$f['check']])) {
                $done++;
            }
        }
        return $done;
    }

    // Relationship dengan User (untuk verified_by)
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id_user');
    }

    // Check apakah pengiriman sudah bisa diverifikasi
    public function canBeVerified()
    {
        return $this->dokumentasi_lengkap && 
               in_array($this->status_verifikasi, ['Sampai_Tujuan']);
    }
}
