<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RiwayatHps extends Model
{
    use HasFactory;

    protected $table = 'riwayat_hps';
    protected $primaryKey = 'id_riwayat_hps';

    protected $fillable = [
        'id_proyek',
        'id_barang',
        'id_vendor',
        'nama_barang',
        'nama_vendor',
        'jenis_vendor',
        'satuan',
        'qty',
        'harga_vendor',
        'harga_diskon',
        'nilai_diskon',
        'total_diskon',
        'total_harga',
        'jumlah_volume',
        'persen_kenaikan',
        'proyeksi_kenaikan',
        'ppn_dinas',
        'pph_dinas',
        'hps',
        'harga_per_pcs',
        'harga_pagu_dinas_per_pcs',
        'pagu_total',
        'selisih_pagu_hps',
        'nilai_sp',
        'dpp',
        'asumsi_nilai_cair',
        'ongkir',
        'omzet_dinas_percent',
        'omzet_nilai_dinas',
        'bendera_percent',
        'gross_nilai_bendera',
        'bank_cost_percent',
        'gross_nilai_bank_cost',
        'biaya_ops_percent',
        'gross_nilai_biaya_ops',
        'sub_total_biaya_tidak_langsung',
        'gross_income',
        'gross_income_persentase',
        'nilai_nett_income',
        'nett_income_persentase',
        'keterangan_1',
        'keterangan_2',
        'created_by',
        'action_type',
        'action_description',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array',
        'qty' => 'integer',
        'harga_vendor' => 'decimal:2',
        'harga_diskon' => 'decimal:2',
        'nilai_diskon' => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'jumlah_volume' => 'decimal:2',
        'persen_kenaikan' => 'decimal:2',
        'proyeksi_kenaikan' => 'decimal:2',
        'ppn_dinas' => 'decimal:2',
        'pph_dinas' => 'decimal:2',
        'hps' => 'decimal:2',
        'harga_per_pcs' => 'decimal:2',
        'harga_pagu_dinas_per_pcs' => 'decimal:2',
        'pagu_total' => 'decimal:2',
        'selisih_pagu_hps' => 'decimal:2',
        'nilai_sp' => 'decimal:2',
        'dpp' => 'decimal:2',
        'asumsi_nilai_cair' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'omzet_dinas_percent' => 'decimal:2',
        'omzet_nilai_dinas' => 'decimal:2',
        'bendera_percent' => 'decimal:2',
        'gross_nilai_bendera' => 'decimal:2',
        'bank_cost_percent' => 'decimal:2',
        'gross_nilai_bank_cost' => 'decimal:2',
        'biaya_ops_percent' => 'decimal:2',
        'gross_nilai_biaya_ops' => 'decimal:2',
        'sub_total_biaya_tidak_langsung' => 'decimal:2',
        'gross_income' => 'decimal:2',
        'gross_income_persentase' => 'decimal:2',
        'nilai_nett_income' => 'decimal:2',
        'nett_income_persentase' => 'decimal:2'
    ];

    /**
     * Relationship dengan Proyek
     */
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    /**
     * Relationship dengan Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    /**
     * Relationship dengan Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }

    /**
     * Relationship dengan User yang membuat riwayat
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Alias untuk createdBy relationship (untuk kompatibilitas)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Scope untuk filter berdasarkan proyek
     */
    public function scopeByProyek($query, $idProyek)
    {
        return $query->where('id_proyek', $idProyek);
    }

    /**
     * Scope untuk filter berdasarkan action type
     */
    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope untuk mengurutkan berdasarkan tanggal terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Format action type untuk display
     */
    public function getFormattedActionTypeAttribute()
    {
        $actionTypes = [
            'create' => 'Dibuat',
            'edit' => 'Diedit',
            'delete' => 'Dihapus',
            'save' => 'Disimpan',
            'ajukan_pembayaran' => 'Diajukan Pembayaran'
        ];

        return $actionTypes[$this->action_type] ?? $this->action_type;
    }

    /**
     * Static method untuk mencatat riwayat
     */
    public static function recordHistory($kalkulasiData, $idProyek, $actionType = 'edit', $actionDescription = null, $changes = null)
    {
        $userId = Auth::id();
        
        foreach ($kalkulasiData as $item) {
            self::create([
                'id_proyek' => $idProyek,
                'id_barang' => $item['id_barang'] ?? null,
                'id_vendor' => $item['id_vendor'] ?? null,
                'nama_barang' => $item['nama_barang'] ?? '',
                'nama_vendor' => $item['nama_vendor'] ?? '',
                'jenis_vendor' => $item['jenis_vendor'] ?? '',
                'satuan' => $item['satuan'] ?? '',
                'qty' => $item['qty'] ?? 1,
                'harga_vendor' => $item['harga_vendor'] ?? 0,
                'harga_diskon' => $item['harga_diskon'] ?? 0,
                'nilai_diskon' => $item['nilai_diskon'] ?? 0,
                'total_diskon' => $item['total_diskon'] ?? 0,
                'total_harga' => $item['total_harga'] ?? 0,
                'jumlah_volume' => $item['jumlah_volume'] ?? 0,
                'persen_kenaikan' => $item['persen_kenaikan'] ?? 0,
                'proyeksi_kenaikan' => $item['proyeksi_kenaikan'] ?? 0,
                'ppn_dinas' => $item['ppn_dinas'] ?? 0,
                'pph_dinas' => $item['pph_dinas'] ?? 0,
                'hps' => $item['hps'] ?? 0,
                'harga_per_pcs' => $item['harga_per_pcs'] ?? 0,
                'harga_pagu_dinas_per_pcs' => $item['harga_pagu_dinas_per_pcs'] ?? 0,
                'pagu_total' => $item['pagu_total'] ?? 0,
                'selisih_pagu_hps' => $item['selisih_pagu_hps'] ?? 0,
                'nilai_sp' => $item['nilai_sp'] ?? 0,
                'dpp' => $item['dpp'] ?? 0,
                'asumsi_nilai_cair' => $item['asumsi_nilai_cair'] ?? 0,
                'ongkir' => $item['ongkir'] ?? 0,
                'omzet_dinas_percent' => $item['omzet_dinas_percent'] ?? 0,
                'omzet_nilai_dinas' => $item['omzet_nilai_dinas'] ?? 0,
                'bendera_percent' => $item['bendera_percent'] ?? 0,
                'gross_nilai_bendera' => $item['gross_nilai_bendera'] ?? 0,
                'bank_cost_percent' => $item['bank_cost_percent'] ?? 0,
                'gross_nilai_bank_cost' => $item['gross_nilai_bank_cost'] ?? 0,
                'biaya_ops_percent' => $item['biaya_ops_percent'] ?? 0,
                'gross_nilai_biaya_ops' => $item['gross_nilai_biaya_ops'] ?? 0,
                'sub_total_biaya_tidak_langsung' => $item['sub_total_biaya_tidak_langsung'] ?? 0,
                'gross_income' => $item['gross_income'] ?? 0,
                'gross_income_persentase' => $item['gross_income_persentase'] ?? 0,
                'nilai_nett_income' => $item['nilai_nett_income'] ?? 0,
                'nett_income_persentase' => $item['nett_income_persentase'] ?? 0,
                'keterangan_1' => $item['keterangan_1'] ?? '',
                'keterangan_2' => $item['keterangan_2'] ?? '',
                'created_by' => $userId,
                'action_type' => $actionType,
                'action_description' => $actionDescription,
                'changes' => $changes
            ]);
        }
    }
}
