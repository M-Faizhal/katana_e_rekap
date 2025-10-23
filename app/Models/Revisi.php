<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Revisi extends Model
{
    use HasFactory;

    protected $table = 'revisi';
    protected $primaryKey = 'id_revisi';

    protected $fillable = [
        'id_proyek',
        'tipe_revisi',
        'target_id',
        'keterangan',
        'status',
        'created_by',
        'handled_by',
        'catatan_revisi'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Tipe revisi yang tersedia
    const TIPE_REVISI = [
        'proyek' => 'Data Proyek',
        'hps_penawaran' => 'HPS & Penawaran', 
        'penawaran' => 'Dokumen Penawaran',
        'penagihan_dinas' => 'Penagihan Dinas',
        'pembayaran' => 'Riwayat Pembayaran',
        'pengiriman' => 'Informasi Pengiriman'
    ];

    // Status revisi
    const STATUS_REVISI = [
        'pending' => 'Menunggu',
        'in_progress' => 'Sedang Dikerjakan',
        'completed' => 'Selesai',
        'rejected' => 'Ditolak'
    ];

    /**
     * Relasi ke model Proyek
     */
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    /**
     * Relasi ke user yang membuat revisi
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Relasi ke user yang menangani revisi
     */
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by', 'id_user');
    }

    /**
     * Accessor untuk mendapatkan nama tipe revisi
     */
    public function getTipeRevisiNamaAttribute()
    {
        return self::TIPE_REVISI[$this->tipe_revisi] ?? $this->tipe_revisi;
    }

    /**
     * Accessor untuk mendapatkan nama status revisi
     */
    public function getStatusNamaAttribute()
    {
        return self::STATUS_REVISI[$this->status] ?? $this->status;
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan tipe revisi
     */
    public function scopeTipeRevisi($query, $tipe)
    {
        return $query->where('tipe_revisi', $tipe);
    }
}
