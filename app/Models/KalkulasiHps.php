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
        'harga_yang_diharapkan',
        'kenaikan_percent',
        'proyeksi_kenaikan',
        'pph',
        'ppn',
        'ongkir',
        'hps',
        'harga_per_pcs',
        'harga_pagu_dinas_per_pcs',
        'nilai_sp',
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
        'bendera_percent',
        'bank_cost_percent',
        'biaya_ops_percent',
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
        'bukti_file_approval',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_vendor' => 'decimal:2',
        'diskon_amount' => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'harga_akhir' => 'decimal:2',
        'total_harga_hpp' => 'decimal:2',
        'jumlah_volume' => 'decimal:2',
        'harga_yang_diharapkan' => 'decimal:2',
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
     * Relationship dengan RiwayatHps
     */
    public function riwayatHps()
    {
        return $this->hasMany(RiwayatHps::class, 'id_proyek', 'id_proyek');
    }

    /**
     * Get riwayat for this specific item
     */
    public function getRiwayatForItem()
    {
        return RiwayatHps::where('id_proyek', $this->id_proyek)
                        ->where('id_barang', $this->id_barang)
                        ->where('id_vendor', $this->id_vendor)
                        ->latest()
                        ->get();
    }

    /**
     * DEPRECATED: Calculate all derived values based on input fields
     *
     * NOTE: This method is no longer used to avoid duplicate calculations.
     * All calculations are now done in JavaScript (hps-calculator.js) and
     * the results are directly saved to the database.
     *
     * This method is kept for backward compatibility only.
     */
    public function calculateValues()
    {
        // This method is intentionally left empty to avoid duplicate calculations.
        // All calculations are now handled by JavaScript frontend and passed to backend.

        // If you need to recalculate from stored data, use the static methods below.
    }

    /**
     * DEPRECATED: Calculate values that depend on project totals
     *
     * NOTE: This method is no longer used to avoid duplicate calculations.
     * All calculations are now done in JavaScript and passed directly to save methods.
     */
    public static function calculateProjectTotals($proyekId)
    {
        // This method is intentionally simplified to avoid duplicate calculations
        return self::where('id_proyek', $proyekId)->get();
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

    /**
     * Helper method to validate and convert calculation data before saving
     * This ensures data integrity when receiving calculated values from frontend
     */
    public static function validateAndConvertData(array $data)
    {
        $numericFields = [
            'qty', 'harga_vendor', 'diskon_amount', 'total_diskon', 'harga_akhir',
            'total_harga_hpp', 'jumlah_volume', 'harga_yang_diharapkan', 'kenaikan_percent', 'proyeksi_kenaikan',
            'pph', 'ppn', 'ongkir', 'hps', 'nilai_tkdn_percent', 'nilai_pagu_anggaran',
            'nilai_penawaran_hps', 'nilai_pesanan', 'nilai_selisih', 'nilai_dpp',
            'ppn_percent', 'pph_badan_percent', 'nilai_ppn', 'nilai_pph_badan',
            'nilai_asumsi_cair', 'sub_total_langsung', 'bank_cost', 'biaya_ops',
            'bendera', 'omzet_dinas_percent', 'omzet_dinas', 'gross_bendera',
            'gross_bank_cost', 'gross_biaya_ops', 'sub_total_tidak_langsung',
            'nett', 'nett_percent', 'nilai_nett_pcs', 'total_nett_pcs',
            'gross_income', 'gross_income_percent', 'nett_income', 'nett_income_percent'
        ];

        $validated = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $numericFields)) {
                // Convert to float and handle null/empty values
                $validated[$key] = $value !== null && $value !== '' ? (float) $value : 0;
            } else {
                // Keep non-numeric fields as is
                $validated[$key] = $value;
            }
        }

        return $validated;
    }

    /**
     * Create or update kalkulasi record with validated data
     */
    public static function createOrUpdateWithValidation(array $data)
    {
        $validatedData = self::validateAndConvertData($data);

        if (isset($validatedData['id_kalkulasi']) && $validatedData['id_kalkulasi']) {
            $kalkulasi = self::findOrFail($validatedData['id_kalkulasi']);
            $kalkulasi->update($validatedData);
        } else {
            $kalkulasi = self::create($validatedData);
        }

        return $kalkulasi;
    }

    /**
     * Batch save multiple kalkulasi records with validation
     */
    public static function batchSaveWithValidation(array $items, $proyekId)
    {
        $results = [];

        foreach ($items as $itemData) {
            $itemData['id_proyek'] = $proyekId;
            $results[] = self::createOrUpdateWithValidation($itemData);
        }

        return $results;
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATION FLOW - IMPORTANT NOTES
    |--------------------------------------------------------------------------
    |
    | SINGLE SOURCE OF TRUTH: JavaScript Frontend (hps-calculator.js)
    |
    | OLD FLOW (INEFFICIENT - DEPRECATED):
    | 1. User input → JavaScript calculation (for display)
    | 2. Send data to backend
    | 3. Backend recalculates everything again (DUPLICATE!)
    | 4. Save to database
    |
    | NEW FLOW (EFFICIENT - CURRENT):
    | 1. User input → JavaScript calculation (hps-calculator.js)
    | 2. Send calculated results to backend
    | 3. Backend validates and saves directly (NO RECALCULATION!)
    | 4. Database contains final calculated values
    |
    | BENEFITS:
    | - No duplicate calculations
    | - Consistent results between frontend and database
    | - Better performance
    | - Single source of calculation logic
    |
    | CALCULATION RESPONSIBILITY:
    | - Frontend (JS): All HPS calculations, real-time updates
    | - Backend (PHP): Data validation, persistence, business logic
    |
    */

    // Relasi dengan RiwayatHps
    public function riwayat()
    {
        return $this->hasMany(RiwayatHps::class, 'id_proyek', 'id_proyek');
    }

    // Method untuk membuat riwayat sebelum update
    public function createRiwayat($actionType = 'edit', $actionDescription = '', $changes = null)
    {
        return RiwayatHps::createFromKalkulasi($this->id_proyek, $actionType, $actionDescription, $changes);
    }
}
