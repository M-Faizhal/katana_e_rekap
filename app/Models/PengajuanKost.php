<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PengajuanKostBukti;

class PengajuanKost extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_kost';

    protected $fillable = [
        'kode_pengajuan',
        'tanggal_kegiatan',
        'tanggal_kegiatan_sampai',
        'tanggal_pengajuan',
        'pic_marketing_id',
        'lokasi',
        'kota',
        'keterangan_kegiatan',
        'nominal',
        'catatan',
        'verified_by',
        'tanggal_verifikasi',
        'catatan_keuangan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal_kegiatan'        => 'date:Y-m-d',
        'tanggal_kegiatan_sampai' => 'date:Y-m-d',
        'tanggal_pengajuan'       => 'date:Y-m-d',
        'tanggal_verifikasi'      => 'datetime',
        'nominal'                 => 'decimal:2',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function picMarketing()
    {
        return $this->belongsTo(User::class, 'pic_marketing_id', 'id_user');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id_user');
    }

    public function buktiBayar()
    {
        return $this->hasMany(PengajuanKostBukti::class, 'pengajuan_kost_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeRevisi($query)
    {
        return $query->where('status', 'revisi');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Generate kode pengajuan: KST-2026-001
     */
    public static function generateKode(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)->lockForUpdate()->count();
        $seq  = str_pad($last + 1, 3, '0', STR_PAD_LEFT);
        return "KST-{$year}-{$seq}";
    }

    /**
     * Cek apakah pengajuan bisa diedit (menunggu atau perlu direvisi)
     */
    public function canEdit(): bool
    {
        return in_array($this->status, ['menunggu', 'revisi']);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>',
            'disetujui' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>',
            'revisi'    => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Perlu Revisi</span>',
            default     => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>',
        };
    }
}
