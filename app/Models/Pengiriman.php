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
