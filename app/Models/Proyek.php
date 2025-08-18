<?php

namespace App\Models;

use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';
    protected $primaryKey = 'id_proyek';

    protected $fillable = [
        'kode_proyek',
        'tanggal',
        'id_wilayah',
        'kab_kota',
        'instansi',
        'nama_klien',
        'kontak_klien',
        'nama_barang',
        'jumlah',
        'satuan',
        'spesifikasi',
        'harga_satuan',
        'harga_total',
        'jenis_pengadaan',
        'deadline',
        'id_admin_marketing',
        'id_admin_purchasing',
        'id_penawaran',
        'catatan',
        'status',
        'potensi',
        'tahun_potensi'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'deadline' => 'date',
        'harga_satuan' => 'decimal:2',
        'harga_total' => 'decimal:2',
        'jumlah' => 'integer',
        'tahun_potensi' => 'integer'
    ];

    /**
     * Boot method untuk generate kode proyek otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($proyek) {
            if (empty($proyek->kode_proyek)) {
                $proyek->kode_proyek = 'PRJ-' . str_pad($proyek->id_proyek, 5, '0', STR_PAD_LEFT);
                $proyek->save();
            }
        });
    }

    /**
     * Generate kode proyek baru
     */
    public static function generateKodeProyek($id)
    {
        return 'PRJ-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }

    public function adminMarketing()
    {
        return $this->belongsTo(User::class, 'id_admin_marketing', 'id_user');
    }

    public function adminPurchasing()
    {
        return $this->belongsTo(User::class, 'id_admin_purchasing', 'id_user');
    }

    public function penawaranAktif()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran', 'id_penawaran');
    }

    // Relationship untuk semua penawaran yang pernah dibuat untuk proyek ini
    public function semuaPenawaran()
    {
        return $this->hasMany(Penawaran::class, 'id_proyek', 'id_proyek');
    }

    public function pembayaran()
    {
        return $this->hasManyThrough(
            Pembayaran::class,
            Penawaran::class,
            'id_proyek', // Foreign key on penawaran table
            'id_penawaran', // Foreign key on pembayaran table
            'id_proyek', // Local key on proyek table
            'id_penawaran' // Local key on penawaran table
        );
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_proyek', 'id_proyek');
    }
}
