<?php

namespace App\Models;

use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Penawaran;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\KalkulasiHps;
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
        'jenis_pengadaan',
        'deadline',
        'id_admin_marketing',
        'id_admin_purchasing',
        'id_penawaran',
        'catatan',
        'harga_total',
        'status',
        'potensi',
        'tahun_potensi'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'deadline' => 'date',
        'harga_total' => 'decimal:2',
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

    // Relasi ke kalkulasi HPS
    public function kalkulasiHps()
    {
        return $this->hasMany(KalkulasiHps::class, 'id_proyek', 'id_proyek');
    }

    // Alias untuk kompatibilitas - relationship untuk penawaran aktif/terbaru
    public function penawaran()
    {
        return $this->hasOne(Penawaran::class, 'id_proyek', 'id_proyek')->latest();
    }

    // Relationship untuk multiple barang per proyek
    public function proyekBarang()
    {
        return $this->hasMany(ProyekBarang::class, 'id_proyek', 'id_proyek');
    }

    // Method untuk menghitung total harga dari proyek_barang
    public function calculateHargaTotal()
    {
        $total = $this->proyekBarang()->sum('harga_total');
        $this->update(['harga_total' => $total]);
        return $total;
    }

    // Method untuk auto-update harga_total saat proyek_barang berubah
    public static function updateHargaTotal($idProyek)
    {
        $proyek = self::find($idProyek);
        if ($proyek) {
            return $proyek->calculateHargaTotal();
        }
        return 0;
    }

    // Method untuk mendapatkan total nilai proyek dengan prioritas penawaran
    public function getTotalNilaiAttribute()
    {
        // Jika ada penawaran, gunakan total_nilai dari penawaran
        if ($this->penawaran && $this->penawaran->total_nilai) {
            return $this->penawaran->total_nilai;
        }
        
        // Jika tidak ada penawaran, gunakan harga_total dari proyek
        return $this->harga_total;
    }
}
