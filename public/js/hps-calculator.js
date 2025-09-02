/**
 * HPS Calculator - Fixed Discount Logic
 * Input: nilai_diskon (per item)
 * Output: total_diskon (calculated)
 */

class HPSCalculator {
    constructor() {
        this.clientRequests = [];
        this.kalkulasiData = [];
        this.barangList = [];
        this.vendorList = [];
        this.jenisVendorOptions = [];
        this.keteranganOptions = [];
        this.satuanOptions = [];
    }

    // Initialize calculator with project data
    init(proyek, kalkulasi = [], barangList = [], vendorList = []) {
        this.clientRequests = this.parseClientRequests(proyek);
        this.kalkulasiData = kalkulasi;
        this.barangList = barangList;
        this.vendorList = vendorList;
        
        console.log('HPS Calculator initialized:', {
            clientRequests: this.clientRequests,
            kalkulasiData: this.kalkulasiData.length
        });
    }

    // Parse client requests from project data
    parseClientRequests(proyek) {
        let requests = [];
        
        if (proyek.proyek_barang && proyek.proyek_barang.length > 0) {
            requests = proyek.proyek_barang.map((item, index) => ({
                id: index + 1,
                nama_barang: item.nama_barang,
                qty: parseFloat(item.jumlah) || 1,
                satuan: item.satuan || 'Unit',
                harga_satuan: parseFloat(item.harga_satuan) || 0,
                harga_total: parseFloat(item.harga_total) || 0
            }));
        } else if (proyek.nama_barang) {
            requests = [{
                id: 1,
                nama_barang: proyek.nama_barang,
                qty: proyek.jumlah || 1,
                satuan: proyek.satuan || 'Unit',
                harga_satuan: proyek.harga_satuan || 0,
                harga_total: proyek.harga_total || 0
            }];
        }
        
        return requests;
    }

    // Find matching client request for vendor item
    findMatchingClientRequest(vendorItemName) {
        if (!this.clientRequests || this.clientRequests.length === 0) return null;
        
        const vendorName = vendorItemName.toLowerCase().trim();
        
        // Try exact match first
        let match = this.clientRequests.find(request => 
            request.nama_barang.toLowerCase().trim() === vendorName
        );
        
        if (match) return match;
        
        // Try partial match
        match = this.clientRequests.find(request => 
            vendorName.includes(request.nama_barang.toLowerCase().trim()) ||
            request.nama_barang.toLowerCase().trim().includes(vendorName)
        );
        
        if (match) return match;
        
        // If only one client request, use it as default
        if (this.clientRequests.length === 1) {
            return this.clientRequests[0];
        }
        
        return null;
    }

    // Calculate all values for a single item
    calculateItem(item) {
        const calculated = { ...item };
        
        // Get basic values
        const qty = parseFloat(calculated.qty) || 1;
        const hargaVendor = parseFloat(calculated.harga_vendor) || 0;
        const persenKenaikan = parseFloat(calculated.persen_kenaikan) || 0;
        const hargaPaguDinasPerPcs = parseFloat(calculated.harga_pagu_dinas_per_pcs) || 0;
        const nilaiSp = parseFloat(calculated.nilai_sp) || 0;
        const ongkir = parseFloat(calculated.ongkir) || 0;
        const omzetDinasPercent = parseFloat(calculated.omzet_dinas_percent) || 0;
        const benderaPercent = parseFloat(calculated.bendera_percent) || 0;
        const bankCostPercent = parseFloat(calculated.bank_cost_percent) || 0;
        const biayaOpsPercent = parseFloat(calculated.biaya_ops_percent) || 0;

        // === REVISED DISCOUNT CALCULATION ===
        // LOGIKA BARU: Admin input harga_diskon, sistem hitung nilai_diskon otomatis
        // INPUT: harga_diskon (harga akhir setelah diskon)
        const hargaDiskon = parseFloat(calculated.harga_diskon) || hargaVendor;
        calculated.harga_diskon = hargaDiskon;
        calculated.harga_akhir = hargaDiskon; // For compatibility
        
        // 8. NILAI DISKON (per item) → CALCULATED: Harga Vendor - Harga Diskon
        const nilaiDiskon = hargaVendor - hargaDiskon;
        calculated.nilai_diskon = Math.max(0, nilaiDiskon);
        
        // 9. TOTAL DISKON = NILAI DISKON × QTY → CALCULATED
        const totalDiskon = calculated.nilai_diskon * qty;
        calculated.total_diskon = totalDiskon;
        
        // 11. TOTAL HARGA = HARGA DISKON × QTY
        const totalHarga = hargaDiskon * qty;
        calculated.total_harga = totalHarga;
        
        // 12. JUMLAH VOLUME YANG DIKERJAKAN = HARGA AKHIR × QTY
        const jumlahVolume = calculated.harga_diskon * qty;
        calculated.jumlah_volume = jumlahVolume;
        
        // 13. Persen Kenaikan (inputan admin) (already set)
        
        // 14. PROYEKSI KENAIKAN = persen kenaikan * total harga
        const proyeksiKenaikan = (persenKenaikan / 100) * totalHarga;
        calculated.proyeksi_kenaikan = proyeksiKenaikan;
        
        // 15. PPN DINAS = 11% dari total harga
        const ppnDinas = totalHarga * 0.11;
        calculated.ppn_dinas = ppnDinas;
        
        // 16. PPH DINAS = 1.5% dari total harga
        const pphDinas = totalHarga * 0.015;
        calculated.pph_dinas = pphDinas;
        
        // 17. HARGA PENAWARAN SENDIRI (HPS) = total harga + proyeksi kenaikan + pph + ppn
        const hps = totalHarga + proyeksiKenaikan + pphDinas + ppnDinas;
        calculated.hps = hps;
        calculated.nilai_hps = hps; // For compatibility
        
        // 18. HARGA PER PCS = HPS / QTY
        const hargaPerPcs = qty > 0 ? hps / qty : 0;
        calculated.harga_per_pcs = hargaPerPcs;
        
        // 19. HARGA PAGU DINAS / PCS (inputan admin) (already set)
        
        // 20. PAGU TOTAL (Nilai Pagu Anggaran) = pagu dinas * qty
        const paguTotal = hargaPaguDinasPerPcs * qty;
        calculated.pagu_total = paguTotal;
        calculated.nilai_pagu_anggaran = paguTotal; // For compatibility
        
        // 21. SELISIH PAGU & HPS = PAGU TOTAL - HPS
        const selisihPaguHps = paguTotal - hps;
        calculated.selisih_pagu_hps = selisihPaguHps;
        calculated.nilai_selisih = selisihPaguHps; // For compatibility
        
        // 22. NILAI SP (Admin Input Nilai Harga) (already set)
        
        // 23. DPP = HPS / 1.11 (Revisi)
        const dpp = hps / 1.11;
        calculated.dpp = dpp;
        calculated.nilai_dpp = dpp; // For compatibility
        
        // 24. ASUMSI NILAI CAIR = DPP - PPH
        const pphFromDpp = dpp * 0.015; // PPH 1.5% dari DPP
        const asumsiNilaiCair = dpp - pphFromDpp;
        calculated.asumsi_nilai_cair = asumsiNilaiCair;
        calculated.nilai_asumsi_cair = asumsiNilaiCair; // For compatibility
        calculated.pph_from_dpp = pphFromDpp;
        calculated.nilai_pph_badan = pphFromDpp; // For compatibility
        
        // 25. ONGKIR (admin input) (already set)
        
        // 26. OMZET NILAI DINAS = ASUMSI NILAI CAIR × Persentase Admin
        const omzetNilaiDinas = asumsiNilaiCair * (omzetDinasPercent / 100);
        calculated.omzet_nilai_dinas = omzetNilaiDinas;
        calculated.omzet_dinas = omzetNilaiDinas; // For compatibility
        
        // 27. GROSS NILAI BENDERA = ASUMSI NILAI CAIR × Persentase Admin
        const grossNilaiBendera = asumsiNilaiCair * (benderaPercent / 100);
        calculated.gross_nilai_bendera = grossNilaiBendera;
        calculated.gross_bendera = grossNilaiBendera; // For compatibility
        
        // 28. GROSS NILAI BANK COST = ASUMSI NILAI CAIR × Persentase Admin
        const grossNilaiBankCost = asumsiNilaiCair * (bankCostPercent / 100);
        calculated.gross_nilai_bank_cost = grossNilaiBankCost;
        calculated.gross_bank_cost = grossNilaiBankCost; // For compatibility
        
        // 29. GROSS NILAI BIAYA OPERASIONAL = ASUMSI NILAI CAIR × Persentase Admin
        const grossNilaiBiayaOps = asumsiNilaiCair * (biayaOpsPercent / 100);
        calculated.gross_nilai_biaya_ops = grossNilaiBiayaOps;
        calculated.gross_biaya_ops = grossNilaiBiayaOps; // For compatibility
        
        // 30. SUB TOTAL BIAYA TIDAK LANGSUNG = (Omzet Dinas + Bendera + Bank Cost + Biaya Operasional)
        const subTotalBiayaTidakLangsung = omzetNilaiDinas + grossNilaiBendera + grossNilaiBankCost + grossNilaiBiayaOps;
        calculated.sub_total_biaya_tidak_langsung = subTotalBiayaTidakLangsung;

        // === Gross & Nett Income ===
        // 31. GROSS INCOME = (DPP - PPH) + SUB TOTAL VOLUME
        const grossIncome = asumsiNilaiCair - jumlahVolume;
        calculated.gross_income = grossIncome;
        
        // 32. GROSS INCOME PERSENTASE = GROSS INCOME / ASUMSI NILAI CAIR
        const grossIncomePersentase = asumsiNilaiCair > 0 ? (grossIncome / asumsiNilaiCair) * 100 : 0;
        calculated.gross_income_persentase = grossIncomePersentase;
        calculated.gross_income_percent = grossIncomePersentase; // For compatibility
        
        // 33. NILAI NETT INCOME = asumsi nilai cair - ongkir - dinas - bank cost - bendera - biaya operasional - total harga
        const nilaiNettIncome = asumsiNilaiCair - ongkir - omzetNilaiDinas - grossNilaiBankCost - grossNilaiBendera - grossNilaiBiayaOps - totalHarga;
        calculated.nilai_nett_income = nilaiNettIncome;
        calculated.nett_income = nilaiNettIncome; // For compatibility
        calculated.nett = nilaiNettIncome; // For compatibility
        
        // 34. NETT INCOME PERSENTASE = NILAI NETT INCOME / ASUMSI NILAI CAIR
        const nettIncomePersentase = asumsiNilaiCair > 0 ? (nilaiNettIncome / asumsiNilaiCair) * 100 : 0;
        calculated.nett_income_persentase = nettIncomePersentase;
        calculated.nett_income_percent = nettIncomePersentase; // For compatibility
        
        // === Keterangan ===
        // 35. Keterangan (Dropdown, admin bisa isi manual) (already set)
        
        // === Nilai Nett Per PCS ===
        // 36. Nilai Nett Per PCS (Persentase Nett) = NETT INCOME PERSENTASE
        calculated.nilai_nett_per_pcs_percent = nettIncomePersentase;
        calculated.nilai_nett_pcs = qty > 0 ? nilaiNettIncome / qty : 0; // For compatibility
        
        // 37. Total Nilai Nett Per PCS (Nilai Nett) = NILAI NETT INCOME
        calculated.total_nilai_nett_per_pcs = nilaiNettIncome;
        calculated.total_nett_pcs = nilaiNettIncome; // For compatibility
        
        // Additional compatibility fields
        calculated.sub_total_langsung = jumlahVolume;
        calculated.nilai_ppn = ppnDinas;
        
        return calculated;
    }

    // Calculate all items in kalkulasi data
    calculateAll() {
        this.kalkulasiData = this.kalkulasiData.map(item => this.calculateItem(item));
        return this.kalkulasiData;
    }

    // Get summary totals
    getSummary() {
        const summary = {
            // Client requests total
            totalClientRequests: this.clientRequests.reduce((sum, req) => sum + req.harga_total, 0),
            
            // Main totals
            totalHpp: 0,              // Total cost (jumlah_volume)
            totalHps: 0,              // Total HPS
            totalNett: 0,             // Total Nett Income
            
            // Detail totals
            totalItems: this.kalkulasiData.length,
            totalNilaiDiskon: 0,      // ← CALCULATED: Total nilai diskon (per item)
            totalDiskon: 0,           // ← CALCULATED: Total diskon keseluruhan
            totalVolume: 0,
            totalProyeksiKenaikan: 0,
            totalPpnDinas: 0,
            totalPphDinas: 0,
            totalDpp: 0,
            totalAsumsiCair: 0,
            totalOngkir: 0,
            totalOmzetDinas: 0,
            totalBendera: 0,
            totalBankCost: 0,
            totalBiayaOps: 0,
            totalSubTotalTidakLangsung: 0,
            totalGrossIncome: 0,
            totalNettIncome: 0,
            
            // Averages
            avgNettPercent: 0,
            avgGrossIncomePercent: 0,
            avgNettIncomePercent: 0,
            overallNettPercent: 0
        };

        if (this.kalkulasiData.length === 0) return summary;

        // Calculate totals
        this.kalkulasiData.forEach(item => {
            summary.totalHpp += parseFloat(item.jumlah_volume) || 0;
            summary.totalHps += parseFloat(item.hps) || 0;
            summary.totalNett += parseFloat(item.nilai_nett_income) || 0;
            summary.totalNilaiDiskon += parseFloat(item.nilai_diskon) || 0;  // ← CALCULATED: per item
            summary.totalDiskon += parseFloat(item.total_diskon) || 0;       // ← CALCULATED: total
            summary.totalVolume += parseFloat(item.jumlah_volume) || 0;
            summary.totalProyeksiKenaikan += parseFloat(item.proyeksi_kenaikan) || 0;
            summary.totalPpnDinas += parseFloat(item.ppn_dinas) || 0;
            summary.totalPphDinas += parseFloat(item.pph_dinas) || 0;
            summary.totalDpp += parseFloat(item.dpp) || 0;
            summary.totalAsumsiCair += parseFloat(item.asumsi_nilai_cair) || 0;
            summary.totalOngkir += parseFloat(item.ongkir) || 0;
            summary.totalOmzetDinas += parseFloat(item.omzet_nilai_dinas) || 0;
            summary.totalBendera += parseFloat(item.gross_nilai_bendera) || 0;
            summary.totalBankCost += parseFloat(item.gross_nilai_bank_cost) || 0;
            summary.totalBiayaOps += parseFloat(item.gross_nilai_biaya_ops) || 0;
            summary.totalSubTotalTidakLangsung += parseFloat(item.sub_total_biaya_tidak_langsung) || 0;
            summary.totalGrossIncome += parseFloat(item.gross_income) || 0;
            summary.totalNettIncome += parseFloat(item.nilai_nett_income) || 0;
        });

        // Calculate averages
        const count = this.kalkulasiData.length;
        if (count > 0) {
            summary.avgNettPercent = this.kalkulasiData.reduce((sum, item) => 
                sum + (parseFloat(item.nett_income_persentase) || 0), 0) / count;
            summary.avgGrossIncomePercent = this.kalkulasiData.reduce((sum, item) => 
                sum + (parseFloat(item.gross_income_persentase) || 0), 0) / count;
            summary.avgNettIncomePercent = this.kalkulasiData.reduce((sum, item) => 
                sum + (parseFloat(item.nett_income_persentase) || 0), 0) / count;
        }

        // Overall nett percent vs client requests
        if (summary.totalClientRequests > 0) {
            summary.overallNettPercent = (summary.totalNett / summary.totalClientRequests) * 100;
        } else if (summary.totalAsumsiCair > 0) {
            summary.overallNettPercent = (summary.totalNett / summary.totalAsumsiCair) * 100;
        } else {
            summary.overallNettPercent = 0;
        }

        return summary;
    }

    // Add new vendor item with corrected structure
    addVendorItem() {
        const newItem = {
            id: Date.now(),
            id_barang: '',
            nama_barang: '',
            id_vendor: '',
            nama_vendor: '',
            jenis_vendor: '',
            satuan: 'Unit',
            qty: 1,
            harga_vendor: 0,
            harga_diskon: 0,          // ← INPUT: Harga setelah diskon
            nilai_diskon: 0,          // ← CALCULATED: Diskon per item
            total_diskon: 0,          // ← CALCULATED: Total diskon
            harga_akhir: 0,
            total_harga: 0,
            jumlah_volume: 0,
            persen_kenaikan: 0,
            proyeksi_kenaikan: 0,
            ppn_dinas: 0,
            pph_dinas: 0,
            hps: 0,
            nilai_hps: 0,
            harga_per_pcs: 0,
            harga_pagu_dinas_per_pcs: 0,
            pagu_total: 0,
            nilai_pagu_anggaran: 0,
            selisih_pagu_hps: 0,
            nilai_selisih: 0,
            nilai_sp: 0,
            dpp: 0,
            nilai_dpp: 0,
            asumsi_nilai_cair: 0,
            nilai_asumsi_cair: 0,
            pph_from_dpp: 0,
            nilai_pph_badan: 0,
            ongkir: 0,
            omzet_nilai_dinas: 0,
            omzet_dinas: 0,
            omzet_dinas_percent: 0,
            gross_nilai_bendera: 0,
            gross_bendera: 0,
            bendera_percent: 0,
            gross_nilai_bank_cost: 0,
            gross_bank_cost: 0,
            bank_cost_percent: 0,
            gross_nilai_biaya_ops: 0,
            gross_biaya_ops: 0,
            biaya_ops_percent: 0,
            sub_total_biaya_tidak_langsung: 0,
            gross_income: 0,
            gross_income_persentase: 0,
            gross_income_percent: 0,
            nilai_nett_income: 0,
            nett_income: 0,
            nett: 0,
            nett_income_persentase: 0,
            nett_income_percent: 0,
            nilai_nett_per_pcs_percent: 0,
            nilai_nett_pcs: 0,
            total_nilai_nett_per_pcs: 0,
            total_nett_pcs: 0,
            sub_total_langsung: 0,
            nilai_ppn: 0,
            keterangan_1: '',
            keterangan_2: '',
            catatan: ''
        };

        this.kalkulasiData.push(newItem);
        return newItem;
    }

    // Remove vendor item
    removeVendorItem(index) {
        if (index >= 0 && index < this.kalkulasiData.length) {
            this.kalkulasiData.splice(index, 1);
        }
    }

    // Clear all vendor data
    clearAllVendorData() {
        this.kalkulasiData = [];
    }

    // Format currency
    formatRupiah(amount) {
        if (isNaN(amount) || amount === null || amount === undefined) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(amount));
    }

    // Format number
    formatNumber(number) {
        if (isNaN(number) || number === null || number === undefined) return '0';
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Format percentage
    formatPercent(percent) {
        if (isNaN(percent) || percent === null || percent === undefined) return '0%';
        return parseFloat(percent).toFixed(2) + '%';
    }

    // Get dropdown options
    getJenisVendorOptions() {
        if (this.jenisVendorOptions.length === 0) {
            this.jenisVendorOptions = [
                'Principle',
                'Distributor',
                'Lokal',
                'Import',
                'Authorized Dealer',
                'Reseller',
                'Kecil',
                'Menengah',
                'Besar'
            ];
        }
        return this.jenisVendorOptions;
    }

    getKeteranganOptions() {
        if (this.keteranganOptions.length === 0) {
            this.keteranganOptions = [
                'Normal',
                'Urgent',
                'Backorder',
                'Custom Order',
                'Special Price',
                'Bulk Discount',
                'Promo',
                'Last Stock'
            ];
        }
        return this.keteranganOptions;
    }

    getSatuanOptions() {
        if (this.satuanOptions.length === 0) {
            this.satuanOptions = [
                'Unit',
                'Pcs',
                'Set',
                'Paket',
                'Box',
                'Karton',
                'Kg',
                'Gram',
                'Liter',
                'Meter',
                'Cm',
                'Roll',
                'Lembar',
                'Buah',
                'Pasang',
                'Lusin'
            ];
        }
        return this.satuanOptions;
    }

    // Validate calculation
    validateCalculation() {
        const errors = [];
        
        this.kalkulasiData.forEach((item, index) => {
            if (!item.nama_barang) {
                errors.push(`Row ${index + 1}: Nama barang harus diisi`);
            }
            if (!item.id_vendor || !item.nama_vendor) {
                errors.push(`Row ${index + 1}: Vendor harus dipilih`);
            }
            if (parseFloat(item.qty) <= 0) {
                errors.push(`Row ${index + 1}: Quantity harus lebih dari 0`);
            }
            if (parseFloat(item.harga_vendor) <= 0) {
                errors.push(`Row ${index + 1}: Harga vendor harus lebih dari 0`);
            }
            if (parseFloat(item.nilai_diskon) > parseFloat(item.harga_vendor)) {
                errors.push(`Row ${index + 1}: Nilai diskon tidak boleh lebih besar dari harga vendor`);
            }
        });

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    // Helper method: Calculate discount example
    calculateDiscountExample(hargaVendor, qty, nilaiDiskon) {
        const totalDiskon = nilaiDiskon * qty;
        const hargaAkhir = hargaVendor - nilaiDiskon;
        const totalHarga = hargaAkhir * qty;
        
        return {
            harga_vendor: hargaVendor,
            qty: qty,
            nilai_diskon: nilaiDiskon,        // INPUT
            total_diskon: totalDiskon,        // CALCULATED
            harga_akhir: hargaAkhir,
            total_harga: totalHarga
        };
    }
}

// Example usage with corrected logic
const exampleCalculation = {
    harga_vendor: 100000,  // Rp 100.000 per item
    qty: 5,                // 5 items
    nilai_diskon: 10000    // INPUT: Rp 10.000 diskon per item
};

// Results:
// total_diskon = 10.000 × 5 = Rp 50.000 (CALCULATED)
// harga_akhir = 100.000 - 10.000 = Rp 90.000 per item
// total_harga = 90.000 × 5 = Rp 450.000

// Create global instance
window.hpsCalculator = new HPSCalculator();

console.log('Example calculation:', 
    window.hpsCalculator.calculateDiscountExample(100000, 5, 10000)
);