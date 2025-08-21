<?php

namespace App\Models;

use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Penawaran;
use App\Models\User;
use App\Models\Wilayah;
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

        static::creating(function ($proyek) {
            if (empty($proyek->kode_proyek)) {
                $proyek->kode_proyek = static::generateNextKodeProyek();
            }
        });
    }

    /**
     * Generate kode proyek baru berdasarkan urutan
     */
    public static function generateNextKodeProyek()
    {
        // Ambil kode proyek terakhir
        $lastProyek = static::orderBy('kode_proyek', 'desc')->first();

        if (!$lastProyek || !$lastProyek->kode_proyek) {
            return 'PRJ-001';
        }

        // Extract nomor dari kode terakhir
        $lastKode = $lastProyek->kode_proyek;
        $lastNumber = (int) substr($lastKode, 4); // Ambil bagian setelah "PRJ-"

        // Generate nomor baru
        $newNumber = $lastNumber + 1;

        return 'PRJ-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode proyek baru (untuk kompatibilitas)
     */
    public static function generateKodeProyek($id)
    {
        return 'PRJ-' . str_pad($id, 3, '0', STR_PAD_LEFT);
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
        return $this->hasManyThrough(
            Pengiriman::class,
            Penawaran::class,
            'id_proyek', // Foreign key on penawaran table
            'id_penawaran', // Foreign key on pengiriman table
            'id_proyek', // Local key on proyek table
            'id_penawaran' // Local key on penawaran table
        );
    }
    
    // Relasi langsung ke penagihan dinas
    public function penagihanDinas()
    {
        return $this->hasMany(PenagihanDinas::class, 'proyek_id', 'id_proyek');
    }

    // Alias untuk kompatibilitas
    public function penawaran()
    {
        return $this->hasMany(Penawaran::class, 'id_proyek', 'id_proyek');
    }
}
