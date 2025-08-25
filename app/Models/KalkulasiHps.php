<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalkulasiHps extends Model
{
    protected $table = 'kalkulasi_hps';
    protected $primaryKey = 'id_kalkulasi';

    protected $fillable = [
        'id_proyek',
        'id_barang',
        'id_vendor',
        'qty',
        'harga_vendor',
        'diskon_amount',
        'total_diskon',
        'harga_akhir',
        'total_harga_hpp',
        'jumlah_volume',
        'kenaikan_percent',
        'proyeksi_kenaikan',
        'pph',
        'ppn',
        'ongkir',
        'hps',
        'nilai_tkdn_percent',
        'jenis_vendor',
        'nilai_pagu_anggaran',
        'nilai_penawaran_hps',
        'nilai_pesanan',
        'nilai_selisih',
        'nilai_dpp',
        'ppn_percent',
        'pph_badan_percent',
        'nilai_ppn',
        'nilai_pph_badan',
        'nilai_asumsi_cair',
        'sub_total_langsung',
        'bank_cost',
        'biaya_ops',
        'bendera',
        'omzet_dinas_percent',
        'omzet_dinas',
        'gross_bendera',
        'gross_bank_cost',
        'gross_biaya_ops',
        'sub_total_tidak_langsung',
        'nett',
        'nett_percent',
        'nilai_nett_pcs',
        'total_nett_pcs',
        'gross_income',
        'gross_income_percent',
        'nett_income',
        'nett_income_percent',
        'catatan',
        'keterangan_1',
        'keterangan_2',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_vendor' => 'decimal:2',
        'diskon_amount' => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'harga_akhir' => 'decimal:2',
        'total_harga_hpp' => 'decimal:2',
        'jumlah_volume' => 'decimal:2',
        'kenaikan_percent' => 'decimal:2',
        'proyeksi_kenaikan' => 'decimal:2',
        'pph' => 'decimal:2',
        'ppn' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'hps' => 'decimal:2',
        'nilai_tkdn_percent' => 'decimal:2',
        'nilai_pagu_anggaran' => 'decimal:2',
        'nilai_penawaran_hps' => 'decimal:2',
        'nilai_pesanan' => 'decimal:2',
        'nilai_selisih' => 'decimal:2',
        'nilai_dpp' => 'decimal:2',
        'ppn_percent' => 'decimal:2',
        'pph_badan_percent' => 'decimal:2',
        'nilai_ppn' => 'decimal:2',
        'nilai_pph_badan' => 'decimal:2',
        'nilai_asumsi_cair' => 'decimal:2',
        'sub_total_langsung' => 'decimal:2',
        'bank_cost' => 'decimal:2',
        'biaya_ops' => 'decimal:2',
        'bendera' => 'decimal:2',
        'omzet_dinas_percent' => 'decimal:2',
        'omzet_dinas' => 'decimal:2',
        'gross_bendera' => 'decimal:2',
        'gross_bank_cost' => 'decimal:2',
        'gross_biaya_ops' => 'decimal:2',
        'sub_total_tidak_langsung' => 'decimal:2',
        'nett' => 'decimal:2',
        'nett_percent' => 'decimal:2',
        'nilai_nett_pcs' => 'decimal:2',
        'total_nett_pcs' => 'decimal:2',
        'gross_income' => 'decimal:2',
        'gross_income_percent' => 'decimal:2',
        'nett_income' => 'decimal:2',
        'nett_income_percent' => 'decimal:2',
    ];

    // Relationships
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }

    /**
     * Calculate all derived values based on input fields
     */
    public function calculateValues()
    {
        // 3. Nilai Diskon = Total Diskon / QTY
        if ($this->qty > 0) {
            $this->diskon_amount = $this->total_diskon / $this->qty;
        }

        // 4. Harga Akhir = Harga Diskon
        $this->harga_akhir = $this->harga_vendor - $this->diskon_amount;

        // 7. Jumlah Volume Yang Dikerjakan = Harga Akhir * Kuantitas
        $this->jumlah_volume = $this->harga_akhir * $this->qty;

        // 10. Nilai Pagu Anggaran = Pagu Total (from project)
        // This should be set from project data

        // 11. Nilai Penawaran/HPS = Harga Penawaran Sendiri
        $this->nilai_penawaran_hps = $this->hps;

        // 13. Nilai Selisih = Nilai Pagu Anggaran - Nilai Penawaran/HPS
        $this->nilai_selisih = $this->nilai_pagu_anggaran - $this->nilai_penawaran_hps;

        // 14. Nilai DPP = Nilai Penawaran/HPS / 1,11
        $this->nilai_dpp = $this->nilai_penawaran_hps / 1.11;

        // 15. Nilai PPN = Nilai DPP * PPN %
        $this->nilai_ppn = $this->nilai_dpp * ($this->ppn_percent / 100);

        // 16. Nilai PPH Badan = Nilai DPP * PPH Badan %
        $this->nilai_pph_badan = $this->nilai_dpp * ($this->pph_badan_percent / 100);

        // 17. Nilai Asumsi Cair = Nilai DPP - Nilai PPH Badan
        $this->nilai_asumsi_cair = $this->nilai_dpp - $this->nilai_pph_badan;

        // 28. Omzet Nilai Dinas = Asumsi Nilai Cair * Omzet Dinas %
        $this->omzet_dinas = $this->nilai_asumsi_cair * ($this->omzet_dinas_percent / 100);

        // 29. Gross Nilai Bendera = Asumsi Nilai Cair * Bendera %
        $this->gross_bendera = $this->nilai_asumsi_cair * ($this->bendera / 100);

        // 30. Gross Nilai Bank Cost = Asumsi Nilai Cair * Bank Cost %
        $this->gross_bank_cost = $this->nilai_asumsi_cair * ($this->bank_cost / 100);

        // 31. Gross Nilai Biaya Operasional = Asumsi Nilai Cair * Biaya Ops %
        $this->gross_biaya_ops = $this->nilai_asumsi_cair * ($this->biaya_ops / 100);

        // 32. Sub Total Biaya Tidak Langsung = Sum of all indirect costs
        $this->sub_total_tidak_langsung = $this->omzet_dinas + $this->gross_bendera + 
                                         $this->gross_bank_cost + $this->gross_biaya_ops;

        // 20. Nilai Nett Per PCS = Nilai Persentase Nett
        $this->nilai_nett_pcs = $this->nett_percent;

        // 21. Total Nilai Nett Per PCS = Nilai Nett
        $this->total_nett_pcs = $this->nett;
    }

    /**
     * Calculate values that depend on project totals
     */
    public static function calculateProjectTotals($proyekId)
    {
        $kalkulasi = self::where('id_proyek', $proyekId)->get();
        
        // 18. Sub Total Langsung = Total of all jumlah_volume
        $subTotalLangsung = $kalkulasi->sum('jumlah_volume');
        
        foreach ($kalkulasi as $item) {
            $item->sub_total_langsung = $subTotalLangsung;
            
            // 22. Gross Income = (Nilai DPP - Nilai PPH Badan) + Sub Total Langsung
            $item->gross_income = ($item->nilai_dpp - $item->nilai_pph_badan) + $subTotalLangsung;
            
            // 23. Gross Income Persentase = Gross Income / Nilai Asumsi Cair
            if ($item->nilai_asumsi_cair > 0) {
                $item->gross_income_percent = ($item->gross_income / $item->nilai_asumsi_cair) * 100;
            }
            
            // 33. Nilai Nett Income = Gross Income + Sub Total Biaya Tidak Langsung
            $item->nett_income = $item->gross_income + $item->sub_total_tidak_langsung;
            
            // 34. Nett Income Persentase = Nett Income / Nilai Asumsi Cair
            if ($item->nilai_asumsi_cair > 0) {
                $item->nett_income_percent = ($item->nett_income / $item->nilai_asumsi_cair) * 100;
            }
            
            $item->save();
        }
        
        return $kalkulasi;
    }

    /**
     * Get options for dropdown fields
     */
    public static function getJenisVendorOptions()
    {
        return [
            'Kecil' => 'Kecil',
            'Menengah' => 'Menengah', 
            'Besar' => 'Besar',
            'BUMN' => 'BUMN',
            'Asing' => 'Asing',
        ];
    }

    public static function getKeteranganOptions()
    {
        return [
            'Standar' => 'Standar',
            'Premium' => 'Premium',
            'Express' => 'Express',
            'Khusus' => 'Khusus',
            'Reguler' => 'Reguler',
        ];
    }
}
