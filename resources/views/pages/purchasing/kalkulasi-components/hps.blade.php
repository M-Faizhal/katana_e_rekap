<!-- HPS Modal -->
<div id="hps-modal" class="hidden fixed inset-0 bg-black/20 backdrop-blur-xs overflow-y-auto h-full w-full z-50">
    <div class="relative top-2 sm:top-4 mx-2 sm:mx-auto p-3 sm:p-5 border w-auto sm:w-11/12 max-w-7xl shadow-lg rounded-lg sm:rounded-md bg-white" id="hps-modal-content">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-3 sm:p-4 border-b border-gray-200">
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Kalkulasi HPS (Harga Perkiraan Sendiri)</h3>
                <div class="text-xs sm:text-sm text-gray-600 mt-1 flex flex-wrap items-center gap-2">
                    <span class="font-medium">Proyek:</span> <span id="modal-project-name" class="truncate">-</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">ID:</span> <span id="modal-project-id">-</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">Klien:</span> <span id="modal-client-name" class="truncate">-</span>
                </div>
            </div>
            <button onclick="closeHpsModal()" class="text-gray-400 hover:text-gray-600 ml-3 flex-shrink-0">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-3 sm:p-4 max-h-[75vh] sm:max-h-[80vh] overflow-y-auto">
            <!-- Action Buttons -->
            <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-3 sm:p-4 rounded-lg gap-3 sm:gap-0">
                <div class="flex flex-wrap gap-2">
                    <button onclick="clearVendorData()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg text-sm" id="btn-clear-vendor">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Hapus Data
                    </button>
                    <button onclick="recalculateAll()" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-lg text-sm" id="btn-recalculate">
                        <i class="fas fa-calculator mr-1"></i>
                        Hitung Ulang
                    </button>
                    <button onclick="validateNettPercentCalculation()" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg text-sm" id="btn-validate">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span class="hidden sm:inline">Validasi</span>
                        <span class="sm:hidden">Check</span>
                    </button>
                </div>
                <div class="text-xs sm:text-sm text-gray-600 flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    <span id="last-updated-header">-</span>
                </div>
            </div>

            <!-- Kalkulasi Table -->
            <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                <!-- Permintaan Klien Section -->
                <div class="mb-4 sm:mb-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-3 gap-2">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-800">
                            <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                            Permintaan Klien
                            <span class="text-xs sm:text-sm font-normal text-gray-500 ml-2 block sm:inline">(Dari Admin Marketing - Read Only)</span>
                        </h4>
                        <div class="bg-blue-100 text-blue-700 px-2 sm:px-3 py-1 sm:py-2 rounded-lg text-xs sm:text-sm flex items-center gap-2">
                            <i class="fas fa-lock"></i>
                            Data Terkunci
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg overflow-hidden">
                        <div class="max-h-40 sm:max-h-60 overflow-y-auto">
                            <table class="w-full">
                                <thead class="bg-blue-100 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 w-8 sm:w-12">No</th>
                                        <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 min-w-[120px] sm:min-w-[150px]">Nama Barang</th>
                                        <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 w-16 sm:w-20">Qty</th>
                                        <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 w-16 sm:w-24">Satuan</th>
                                        <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 min-w-[80px] sm:min-w-[100px]">Harga</th>
                                        <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase border-r border-blue-200 min-w-[80px] sm:min-w-[100px]">Total</th>
                                        <th class="px-2 sm:px-3 py-2 text-left text-xs font-medium text-blue-700 uppercase w-16 sm:w-20">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="client-request-table" class="bg-white divide-y divide-blue-200">
                                    <!-- Client requests will be loaded dynamically here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-blue-100 px-2 sm:px-3 py-2 border-t border-blue-200">
                            <div class="flex justify-between items-center">
                                <span class="text-xs sm:text-sm font-medium text-blue-700">Total Permintaan Klien:</span>
                                <span class="text-sm sm:text-lg font-bold text-blue-800" id="grand-total-client">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Barang Vendor Section -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-3 gap-2">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-800">
                            <i class="fas fa-boxes text-green-600 mr-2"></i>
                            Kalkulasi HPS (Harga Perkiraan Sendiri)
                            <span class="text-xs sm:text-sm font-normal text-green-600 ml-2 block sm:inline">(Area Admin Purchasing)</span>
                        </h4>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                            <div class="text-xs text-gray-500 hidden md:block">
                                <i class="fas fa-info-circle mr-1"></i>
                                Scroll horizontal untuk melihat semua kolom
                            </div>
                            <button onclick="addVendorItem()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm flex items-center gap-2 transition-colors duration-200 w-full sm:w-auto justify-center sm:justify-start">
                                <i class="fas fa-plus"></i>
                                <span class="hidden sm:inline">Tambah Item Vendor</span>
                                <span class="sm:hidden">Tambah Item</span>
                            </button>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="max-h-64 sm:max-h-96 overflow-y-auto overflow-x-auto hps-table-container">
                            <table class="w-full text-xs sm:text-sm hps-table">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-1 sm:px-2 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-8 sm:w-12">No</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[120px] sm:min-w-[160px]">Nama Barang</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px] sm:min-w-[130px]">Nama Vendor</th>
                                        <th class="px-1 sm:px-2 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-12 sm:w-16">Qty</th>
                                        <th class="px-1 sm:px-2 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-16 sm:w-20">Satuan</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[90px] sm:min-w-[110px]">Harga Vendor</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[90px] sm:min-w-[110px]">Diskon</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[90px] sm:min-w-[110px]">Total Diskon</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px] sm:min-w-[120px] bg-yellow-50">Total HPP</th>
                                        <th class="px-1 sm:px-2 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-16 sm:w-20">Kenaikan (%)</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[90px] sm:min-w-[110px]">Proyeksi Kenaikan</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[70px] sm:min-w-[90px]">PPH 1.5%</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[70px] sm:min-w-[90px]">PPN 11%</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px] sm:min-w-[100px]">Ongkir</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px] sm:min-w-[120px] bg-blue-50">HPS</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px] sm:min-w-[100px]">Bank Cost</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px] sm:min-w-[100px]">Biaya Ops</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[80px] sm:min-w-[100px]">Bendera</th>
                                        <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 min-w-[100px] sm:min-w-[120px] bg-green-50">Nett</th>
                                        <th class="px-1 sm:px-2 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200 w-12 sm:w-16">% Nett*</th>
                                        <th class="px-1 sm:px-2 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase w-12 sm:w-16">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="kalkulasi-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Vendor items will be loaded dynamically here -->
                                </tbody>
                            </table>
                            <div class="px-2 sm:px-4 py-2 bg-gray-50 text-xs text-gray-600 border-t">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                    <span class="font-medium">* % Nett dihitung per row: (Nett Total / Harga Permintaan Client yang sesuai) × 100</span>
                                    <div class="flex items-center gap-2 sm:gap-4 text-xs">
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-yellow-100 border border-yellow-300 rounded"></div>
                                            <span>HPP</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-100 border border-blue-300 rounded"></div>
                                            <span>HPS</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-100 border border-green-300 rounded"></div>
                                            <span>Nett</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Sistem otomatis mencocokkan item vendor dengan permintaan klien berdasarkan nama barang
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="mt-6 sm:mt-8 bg-gray-50 rounded-lg p-3 sm:p-4">
                <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Ringkasan Total</h4>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-center">
                    <div class="bg-white rounded-lg p-2 sm:p-3 border">
                        <div class="text-xs sm:text-sm text-gray-600">Total HPP (Modal)</div>
                        <div class="text-sm sm:text-lg font-bold text-yellow-700" id="grand-total-hpp">Rp 0</div>
                        <div class="text-xs text-gray-500 hidden sm:block">Harga beli dari vendor</div>
                    </div>
                    <div class="bg-white rounded-lg p-2 sm:p-3 border">
                        <div class="text-xs sm:text-sm text-gray-600">Total HPS</div>
                        <div class="text-sm sm:text-lg font-bold text-blue-700" id="grand-total-hps">Rp 0</div>
                        <div class="text-xs text-gray-500 hidden sm:block">Harga penawaran ke klien</div>
                    </div>
                    <div class="bg-white rounded-lg p-2 sm:p-3 border">
                        <div class="text-xs sm:text-sm text-gray-600">Total Nett</div>
                        <div class="text-sm sm:text-lg font-bold text-green-700" id="grand-total-nett">Rp 0</div>
                        <div class="text-xs text-gray-500 hidden sm:block">Pendapatan bersih</div>
                    </div>
                    <div class="bg-white rounded-lg p-2 sm:p-3 border">
                        <div class="text-xs sm:text-sm text-gray-600">Rata-rata % Nett</div>
                        <div class="text-sm sm:text-lg font-bold text-red-700" id="grand-avg-nett">0%</div>
                        <div class="text-xs text-gray-500 hidden sm:block">Margin bersih dari total permintaan klien</div>
                    </div>
                </div>
                
                <!-- Detailed Breakdown -->
                <div class="mt-4 sm:mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <div class="bg-white rounded-lg p-3 sm:p-4 border">
                        <h5 class="font-semibold text-gray-800 mb-3">Breakdown Biaya</h5>
                        <div class="space-y-2 text-xs sm:text-sm">
                            <div class="flex justify-between">
                                <span>Total Diskon:</span>
                                <span class="font-medium text-green-600" id="breakdown-diskon">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total PPH 1.5%:</span>
                                <span class="font-medium text-orange-600" id="breakdown-pph">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total PPN 11%:</span>
                                <span class="font-medium text-orange-600" id="breakdown-ppn">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Ongkir:</span>
                                <span class="font-medium text-blue-600" id="breakdown-ongkir">Rp 0</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 sm:p-4 border">
                        <h5 class="font-semibold text-gray-800 mb-3">Biaya Operasional</h5>
                        <div class="space-y-2 text-xs sm:text-sm">
                            <div class="flex justify-between">
                                <span>Total Bank Cost:</span>
                                <span class="font-medium text-red-600" id="breakdown-bank">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Biaya Ops:</span>
                                <span class="font-medium text-red-600" id="breakdown-ops">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Bendera:</span>
                                <span class="font-medium text-red-600" id="breakdown-bendera">Rp 0</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between font-semibold">
                                <span>Total Biaya Tidak Langsung:</span>
                                <span class="text-red-700" id="breakdown-total-biaya">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 sm:p-4 border-t border-gray-200 gap-3 sm:gap-0">
            <div class="text-xs sm:text-sm text-gray-600 order-2 sm:order-1">
                Terakhir diupdate: <span class="font-medium" id="last-updated">-</span>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 order-1 sm:order-2">
                <button onclick="closeHpsModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                    Tutup
                </button>
                <button onclick="saveKalkulasi()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                    <i class="fas fa-save mr-1 sm:mr-2"></i>
                    Simpan Kalkulasi
                </button>
                <button onclick="createPenawaran()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm" id="btn-create-penawaran" style="display: none;">
                    <i class="fas fa-file-contract mr-1 sm:mr-2"></i>
                    Buat Penawaran
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for removing number input spinners -->
<style>
/* Remove spinner arrows from number inputs */
.no-spin::-webkit-outer-spin-button,
.no-spin::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.no-spin[type=number] {
    -moz-appearance: textfield;
}

/* Better table layout */
.hps-table {
    min-width: 1800px; /* Reduced for better mobile support */
    table-layout: fixed;
}

/* Responsive table adjustments */
@media (max-width: 640px) {
    .hps-table {
        min-width: 1400px;
    }
}

/* Improved input styling */
.hps-table input[type="text"],
.hps-table input[type="number"],
.hps-table select {
    font-size: 11px;
    padding: 4px 6px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    background-color: #ffffff;
    transition: all 0.2s ease;
    min-height: 28px;
}

@media (min-width: 640px) {
    .hps-table input[type="text"],
    .hps-table input[type="number"],
    .hps-table select {
        font-size: 12px;
        padding: 6px 8px;
        min-height: 32px;
    }
}

.hps-table input[type="text"]:focus,
.hps-table input[type="number"]:focus,
.hps-table select:focus {
    outline: none;
    border-color: #ef4444;
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
    background-color: #fefefe;
}

/* Better cell spacing */
.hps-table td {
    padding: 6px 4px;
    vertical-align: middle;
    white-space: nowrap;
}

@media (min-width: 640px) {
    .hps-table td {
        padding: 8px 6px;
    }
}

.hps-table th {
    padding: 8px 4px;
    font-size: 9px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    background-color: #f9fafb;
    border-bottom: 2px solid #e5e7eb;
}

@media (min-width: 640px) {
    .hps-table th {
        padding: 12px 6px;
        font-size: 10px;
    }
}

/* Readonly input styling */
.hps-table input[readonly] {
    background-color: #f3f4f6;
    color: #6b7280;
    cursor: not-allowed;
}

/* Button styling in table */
.hps-table button {
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 11px;
    transition: all 0.2s ease;
}

@media (min-width: 640px) {
    .hps-table button {
        padding: 4px 8px;
        font-size: 12px;
    }
}

/* Responsive text sizing */
.hps-table .text-display {
    font-size: 10px;
    font-weight: 500;
    color: #374151;
}

@media (min-width: 640px) {
    .hps-table .text-display {
        font-size: 11px;
    }
}

/* Highlighted columns */
.hps-table .bg-yellow-50 {
    background-color: #fefce8 !important;
}

.hps-table .bg-blue-50 {
    background-color: #eff6ff !important;
}

.hps-table .bg-green-50 {
    background-color: #f0fdf4 !important;
}

/* Better scrollbar */
.hps-table-container::-webkit-scrollbar {
    height: 6px;
}

@media (min-width: 640px) {
    .hps-table-container::-webkit-scrollbar {
        height: 8px;
    }
}

.hps-table-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.hps-table-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.hps-table-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Mobile responsiveness for modal */
@media (max-width: 768px) {
    .hps-table {
        min-width: 1400px;
    }
    
    .hps-table th,
    .hps-table td {
        padding: 4px 3px;
    }
    
    .hps-table input,
    .hps-table select {
        font-size: 10px;
        padding: 3px 4px;
        min-height: 24px;
    }
    
    /* Hide some less critical columns on mobile */
    .hps-table th:nth-child(n+16),
    .hps-table td:nth-child(n+16) {
        display: none;
    }
}

@media (max-width: 480px) {
    /* Show only essential columns on very small screens */
    .hps-table th:nth-child(n+12),
    .hps-table td:nth-child(n+12) {
        display: none;
    }
    
    .hps-table {
        min-width: 800px;
    }
}

/* Mobile-specific optimizations */
.hps-table.mobile-optimized th:nth-child(n+13),
.hps-table.mobile-optimized td:nth-child(n+13) {
    display: none;
}

.hps-table.mobile-optimized {
    min-width: 900px;
}

/* Tablet-specific optimizations */
.hps-table.tablet-optimized th:nth-child(n+17),
.hps-table.tablet-optimized td:nth-child(n+17) {
    display: none;
}

.hps-table.tablet-optimized {
    min-width: 1200px;
}

/* Touch-friendly buttons */
@media (max-width: 768px) {
    .hps-table button,
    button {
        min-height: 44px; /* iOS recommended touch target size */
        min-width: 44px;
    }
}

/* Improved modal positioning on mobile */
@media (max-width: 640px) {
    #hps-modal {
        padding: 0;
    }
    
    #hps-modal-content {
        border-radius: 0;
        height: 100vh;
        max-height: 100vh;
        overflow-y: auto;
    }
}

/* Better text sizing for readability */
@media (max-width: 480px) {
    .text-xs {
        font-size: 0.7rem;
    }
    
    .text-sm {
        font-size: 0.8rem;
    }
}

/* Improved card spacing on mobile */
@media (max-width: 640px) {
    .grid-cols-2 {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
}

/* Improved focus states */
.hps-table input:focus {
    transform: scale(1.02);
    z-index: 10;
    position: relative;
}

/* Better alignment for numeric fields */
.hps-table .text-right {
    text-align: right;
}

.hps-table .text-center {
    text-align: center;
}

/* Enhanced hover effects */
.hps-table tbody tr:hover {
    background-color: #f8fafc;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Focus styles for dropdowns and inputs */
select:focus, input:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    border-color: #3b82f6;
}

/* Loading overlay */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.loading-spinner {
    width: 32px;
    height: 32px;
    border: 3px solid #f3f4f6;
    border-top: 3px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@media (min-width: 640px) {
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f4f6;
        border-top: 4px solid #3b82f6;
    }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive modal sizing */
@media (max-width: 640px) {
    #hps-modal-content {
        margin: 0.5rem;
        width: calc(100% - 1rem);
        max-height: calc(100vh - 1rem);
        top: 0.5rem;
    }
}

/* Line clamp utility for mobile */
.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}
</style>
