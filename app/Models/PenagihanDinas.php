<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenagihanDinas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penagihan_dinas';

    protected $fillable = [
        'proyek_id',
        'penawaran_id',
        'nomor_invoice',
        'total_harga',
        'status_pembayaran',
        'persentase_dp',
        'jumlah_dp',
        'sisa_pembayaran',
        'tanggal_jatuh_tempo',
        'keterangan',
        'berita_acara_serah_terima',
        'invoice',
        'pnbp',
        'faktur_pajak',
        'surat_lainnya',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'persentase_dp' => 'decimal:2',
        'jumlah_dp' => 'decimal:2',
        'sisa_pembayaran' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id', 'id_proyek');
    }

    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'penawaran_id', 'id_penawaran');
    }

    public function buktiPembayaran()
    {
        return $this->hasMany(BuktiPembayaran::class);
    }

    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format((float)$this->total_harga, 0, ',', '.');
    }

    public function getFormattedJumlahDpAttribute()
    {
        return $this->jumlah_dp ? 'Rp ' . number_format((float)$this->jumlah_dp, 0, ',', '.') : null;
    }

    public function getFormattedSisaPembayaranAttribute()
    {
        return $this->sisa_pembayaran ? 'Rp ' . number_format((float)$this->sisa_pembayaran, 0, ',', '.') : null;
    }

    public function getStatusPembayaranLabelAttribute()
    {
        return match($this->status_pembayaran) {
            'belum_bayar' => 'Belum Bayar',
            'dp' => 'DP',
            'lunas' => 'Lunas',
            default => 'Unknown'
        };
    }
}