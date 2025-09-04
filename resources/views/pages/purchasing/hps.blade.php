@extends('layouts.app')

@section('title', 'Kalkulasi HPS - ' . ($proyek->nama_klien ?? 'Unknown') . ' - Cyber KATANA')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-semibold text-gray-900 truncate">Kalkulasi HPS (Harga Perkiraan Sendiri)</h1>
                <div class="text-sm text-gray-600 mt-1 flex flex-wrap items-center gap-2">
                    <span class="font-medium">Proyek:</span> <span class="truncate">{{ $proyek->kode_proyek ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">ID:</span> <span>{{ $proyek->id_proyek ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">Instansi:</span> <span class="truncate">{{ $proyek->instansi ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">Total:</span> <span class="truncate text-green-600">{{ 'Rp ' . number_format($proyek->harga_total ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('purchasing.kalkulasi') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>


    <!-- Action Buttons -->
    @if($canEdit || (Auth::user()->role === 'superadmin'))
    <div class="bg-gray-50 rounded-lg p-4 mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <div class="flex flex-wrap gap-2">
            <button onclick="clearVendorData()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg text-sm">
                Hapus Semua Data Vendor
            </button>
           
        </div>
        <div class="text-sm text-gray-600 flex items-center">
            @if(Auth::user()->role === 'superadmin')
                <span class="text-green-600 ml-2"><i class="fas fa-user-shield"></i> Superadmin: akses penuh</span>
            @endif
        </div>
    </div>
    @else
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <span class="text-amber-700"><i class="fas fa-lock mr-2"></i> Anda tidak memiliki akses untuk mengedit kalkulasi HPS pada proyek ini.</span>
        </div>
    </div>
    @endif

    <!-- Permintaan Klien Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                    Permintaan Klien
                    <span class="text-sm font-normal text-gray-500 ml-2">(Dari Admin Marketing - Read Only)</span>
                </h2>
                <div class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm flex items-center gap-2">
                    <i class="fas fa-lock"></i>
                    Data Terkunci
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Qty</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Satuan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Harga Satuan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Harga Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-blue-200">
                    @if($proyek->proyekBarang && $proyek->proyekBarang->count() > 0)
                        @foreach($proyek->proyekBarang as $index => $item)
                        <tr class="hover:bg-blue-50">
                            <td class="px-4 py-3 text-sm text-blue-800">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-blue-800">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-3 text-sm text-blue-800">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-blue-800">{{ $item->satuan }}</td>
                            <td class="px-4 py-3 text-sm text-blue-800">{{ 'Rp ' . number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-blue-800 font-semibold">{{ 'Rp ' . number_format($item->harga_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data permintaan klien</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <div class="bg-blue-100 px-4 py-3 border-t border-blue-200">
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-blue-700">Total Permintaan Klien:</span>
                <span class="text-lg font-bold text-blue-800">{{ 'Rp ' . number_format($proyek->harga_total ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Kalkulasi HPS Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-boxes text-green-600 mr-2"></i>
                    Kalkulasi HPS (Harga Perkiraan Sendiri)
                    <span class="text-sm font-normal text-green-600 ml-2">(Area Admin Purchasing)</span>
                </h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('purchasing.kalkulasi.hps.summary', ['id' => $proyek->id_proyek]) }}" target="_blank" rel="noopener" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                        <i class="fas fa-table"></i>
                        Ringkasan HPS
                    </a>
                    @if($canEdit ?? false)
                    <button onclick="addVendorItem()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Tambah Item Vendor
                    </button>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm hps-table">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Vendor</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Jenis Vendor</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Satuan</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Harga Vendor</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase bg-yellow-100" title="Input: Harga akhir setelah diskon">Harga Diskon <br><small class="text-blue-600">(INPUT)</small></th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase bg-gray-100" title="Calculated: Harga Vendor - Harga Diskon">Nilai Diskon <br><small class="text-gray-600">(AUTO)</small></th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase bg-gray-100" title="Calculated: Nilai Diskon × Qty">Total Diskon <br><small class="text-gray-600">(AUTO)</small></th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Jumlah Volume</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">% Kenaikan</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Proyeksi Kenaikan</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">PPN</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">PPH</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">HPS</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Harga/PCS</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Pagu Dinas/PCS</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Pagu Total</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Selisih Pagu</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Nilai SP</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">DPP</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Asumsi Cair</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Ongkir</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">% Omzet Dinas</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Omzet Dinas</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">% Bendera</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Gross Bendera</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">% Bank Cost</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Gross Bank Cost</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">% Biaya Ops</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Gross Biaya Ops</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Sub Total Tidak Langsung</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Gross Income</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Gross %</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Nett Income</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Nett %</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kalkulasi-table-body" class="bg-white divide-y divide-gray-200">
                    <!-- Dynamic content will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Total Kalkulasi HPS</h3>
        
        <!-- Main Summary Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-center mb-4">
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Total HPP (Modal)</div>
                <div class="text-lg font-bold text-yellow-700" id="grand-total-hpp">Rp 0</div>
                <div class="text-xs text-gray-500">Harga beli dari vendor</div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Total HPS</div>
                <div class="text-lg font-bold text-blue-700" id="grand-total-hps">Rp 0</div>
                <div class="text-xs text-gray-500">Harga penawaran ke klien</div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Total Nett</div>
                <div class="text-lg font-bold text-green-700" id="grand-total-nett">Rp 0</div>
                <div class="text-xs text-gray-500">Pendapatan bersih</div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Rata-rata % Nett</div>
                <div class="text-lg font-bold text-red-700" id="grand-avg-nett">0%</div>
                <div class="text-xs text-gray-500">Margin bersih</div>
            </div>
        </div>
        
        <!-- Additional Summary Details -->
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 text-center">
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Items</div>
                <div class="text-sm font-semibold" id="total-items">0</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Diskon</div>
                <div class="text-sm font-semibold" id="total-diskon">Rp 0</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Volume</div>
                <div class="text-sm font-semibold" id="total-volume">Rp 0</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total DPP</div>
                <div class="text-sm font-semibold" id="total-dpp">Rp 0</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Asumsi Cair</div>
                <div class="text-sm font-semibold" id="total-asumsi-cair">Rp 0</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Ongkir</div>
                <div class="text-sm font-semibold" id="total-ongkir">Rp 0</div>
            </div>
        </div>
    </div>

    <!-- Bukti Approval Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-file-check text-green-600 mr-2"></i>
                    Bukti Approval Kalkulasi
                </h3>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Max 2MB (PDF, JPG, PNG)
                </div>
            </div>
        </div>
        
        <div class="p-4">
            @if($canEdit || (Auth::user()->role === 'superadmin'))
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Upload Area -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">Upload File Bukti Approval</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                            <div class="space-y-2">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                                <div class="text-sm text-gray-600">
                                    <label for="bukti-approval-file" class="cursor-pointer text-blue-600 hover:text-blue-500">
                                        Klik untuk upload file
                                    </label>
                                    atau drag & drop
                                </div>
                                <input type="file" id="bukti-approval-file" name="bukti_approval" 
                                       accept=".pdf,.jpg,.jpeg,.png" 
                                       onchange="handleFileUpload(this)"
                                       class="hidden">
                                <div class="text-xs text-gray-500">
                                    PDF, JPG, PNG (Max 2MB)
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div id="upload-progress" class="hidden">
                            <div class="bg-gray-200 rounded-full h-2">
                                <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <div class="text-sm text-gray-600 mt-1" id="upload-status">Uploading...</div>
                        </div>
                        
                        <!-- Error/Success Messages -->
                        <div id="upload-message" class="hidden"></div>
                    </div>
                    
                    <!-- Current File Display -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">File Saat Ini</label>
                        <div id="current-file-display" class="border border-gray-200 rounded-lg p-4">
                            @if(isset($currentApprovalFile) && $currentApprovalFile)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-alt text-blue-500 text-xl"></i>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ basename($currentApprovalFile) }}</div>
                                            <div class="text-xs text-gray-500">
                                                Uploaded: {{ \Carbon\Carbon::parse($proyek->updated_at)->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ asset('storage/' . $currentApprovalFile) }}" target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        <button onclick="deleteApprovalFile()" 
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-gray-500 py-4">
                                    <i class="fas fa-file-alt text-3xl text-gray-300 mb-2"></i>
                                    <div class="text-sm">Belum ada file bukti approval</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-lock text-amber-600 mr-2"></i>
                        <span class="text-amber-700">Anda tidak memiliki akses untuk upload file bukti approval.</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Footer -->
    <div class="flex justify-between items-center bg-white rounded-lg p-4 border border-gray-200">
        <div class="text-sm text-gray-600">
            Terakhir diupdate: <span class="font-medium" id="last-updated-footer">{{ $proyek->updated_at ? $proyek->updated_at->format('d/m/Y H:i') : '-' }}</span>
        </div>
        @if($canEdit ?? false)
        <div class="flex gap-3">
            <button onclick="saveKalkulasi()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-save mr-2"></i>
                Simpan Kalkulasi
            </button>
            <button onclick="createPenawaran()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700" id="btn-create-penawaran">
                <i class="fas fa-file-contract mr-2"></i>
                Buat Penawaran
            </button>
        </div>
        @else
        <div class="text-sm text-gray-500 italic">
            <i class="fas fa-eye mr-1"></i>
            Mode tampilan saja
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
/* HPS Table Styles */
.hps-table {
    min-width: 2400px;
    table-layout: fixed;
}

/* Input vs Calculated field styling */
.hps-table .bg-yellow-100 input {
    background-color: #fef3c7 !important;
    border-color: #f59e0b !important;
    font-weight: 600;
}

.hps-table .bg-gray-50 span {
    color: #6b7280 !important;
    font-style: italic;
}

.hps-table input[type="text"],
.hps-table input[type="number"],
.hps-table select {
    font-size: 12px;
    padding: 6px 8px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    background-color: #ffffff;
    transition: all 0.2s ease;
    min-height: 32px;
    width: 100%;
}

.hps-table input:focus,
.hps-table select:focus {
    outline: none;
    border-color: #ef4444;
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
}

.hps-table .no-spin::-webkit-outer-spin-button,
.hps-table .no-spin::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.hps-table .no-spin[type=number] {
    -moz-appearance: textfield;
}

.hps-table td {
    padding: 8px 6px;
    vertical-align: middle;
    white-space: nowrap;
}

.hps-table .bg-yellow-50 {
    background-color: #fefce8 !important;
}

.hps-table .bg-green-50 {
    background-color: #f0fdf4 !important;
}

/* Disable mouse wheel scroll on number inputs */
.hps-table input[type="number"] {
    -moz-appearance: textfield;
}

.hps-table input[type="number"]::-webkit-outer-spin-button,
.hps-table input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Prevent mouse wheel scrolling on focused number inputs */
.hps-table input[type="number"]:focus {
    pointer-events: auto;
}

/* Responsive */
@media (max-width: 768px) {
    .hps-table {
        min-width: 2200px;
        font-size: 11px;
    }
    
    .hps-table input,
    .hps-table select {
        font-size: 11px;
        padding: 4px 6px;
        min-height: 28px;
    }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/hps-calculator.js') }}"></script>
<script>
// IMPORTANT: Logika Diskon Telah Diubah
// LOGIKA BARU:
// - harga_diskon = INPUT (yang diinput admin, harga akhir setelah diskon)
// - nilai_diskon = CALCULATED (otomatis dihitung: harga_vendor - harga_diskon)
// - total_diskon = CALCULATED (otomatis dihitung: nilai_diskon × qty)

// Global variables
let currentProyekId = {{ $proyek->id_proyek ?? 'null' }};
let currentProject = @json($proyek);
let canEdit = {{ ($canEdit ?? false) ? 'true' : 'false' }};
let barangList = [];
let vendorList = [];
let kalkulasiData = @json($kalkulasiData ?? []);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeHPS();
    preventNumberInputScroll();
});

// Prevent mouse wheel scroll on number inputs
function preventNumberInputScroll() {
    // Add event listener to prevent wheel events on number inputs
    document.addEventListener('wheel', function(e) {
        if (e.target.type === 'number' && e.target.matches('.hps-table input[type="number"]')) {
            e.preventDefault();
        }
    }, { passive: false });
    
    // Also prevent focus on wheel to avoid accidental changes
    document.addEventListener('mousedown', function(e) {
        if (e.target.type === 'number' && e.target.matches('.hps-table input[type="number"]')) {
            e.target.addEventListener('wheel', function(wheelEvent) {
                wheelEvent.preventDefault();
                wheelEvent.stopPropagation();
            }, { passive: false });
        }
    });
}

async function initializeHPS() {
    try {
        // Load data
        await loadBarangList();
        await loadVendorList();
        
        // Initialize HPS Calculator
        window.hpsCalculator.init(currentProject, kalkulasiData, barangList, vendorList);
        
        // Populate table
        populateKalkulasiTable();
        calculateTotals();
        
        // Check initial button visibility
        checkPenawaranButtonVisibility();
        
    } catch (error) {
        console.error('Error initializing HPS:', error);
        alert('Terjadi kesalahan saat memuat data');
    }
}
// Load barang list
async function loadBarangList() {
    try {
        const response = await fetch('/purchasing/kalkulasi/barang');
        barangList = await response.json();

        // Urutkan berdasarkan nama_barang (A-Z)
        barangList.sort((a, b) => a.nama_barang.localeCompare(b.nama_barang));

    } catch (error) {
        console.error('Error loading barang:', error);
    }
}


// Load vendor list
async function loadVendorList() {
    try {
        const response = await fetch('/purchasing/kalkulasi/vendor');
        vendorList = await response.json();
    } catch (error) {
        console.error('Error loading vendor:', error);
    }
}

// Populate kalkulasi table
function populateKalkulasiTable() {
    const tbody = document.getElementById('kalkulasi-table-body');
    if (!tbody) return;
    
    let html = '';
    kalkulasiData.forEach((item, index) => {
        html += createKalkulasiTableRow(item, index);
    });
    
    tbody.innerHTML = html;
}

// Create kalkulasi table row
function createKalkulasiTableRow(item, index) {
    const barangOptions = createBarangOptions(item.id_barang);
    
    return `
        <tr class="hover:bg-gray-50">
            <td class="px-2 py-3 text-sm text-gray-900">${index + 1}</td>
            <td class="px-2 py-3">
                <select onchange="updateBarang(${index}, this.value)" class="no-spin" id="barang-select-${index}" ${!canEdit ? 'disabled' : ''}>
                    <option value="">Pilih Barang</option>
                    ${barangOptions}
                </select>
            </td>
            <td class="px-2 py-3 text-sm text-gray-700">
                <span>${item.nama_vendor || '-'}</span>
            </td>
            <td class="px-2 py-3 text-sm text-gray-700">
                <span>${item.jenis_vendor || '-'}</span>
            </td>
            <td class="px-2 py-3 text-sm text-gray-700">
                <span>${item.satuan || '-'}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.qty || 1}" onchange="updateValue(${index}, 'qty', this.value)" class="no-spin text-right w-16" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.harga_vendor || 0}" onchange="updateValue(${index}, 'harga_vendor', this.value)" class="no-spin text-right w-20" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-yellow-100">
                <input type="number" value="${item.harga_diskon || 0}" onchange="updateValue(${index}, 'harga_diskon', this.value)" class="no-spin text-right w-20 font-semibold" placeholder="Input harga final" title="INPUT: Masukkan harga akhir setelah diskon" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-gray-50 text-xs">
                <span class="text-gray-600" title="CALCULATED: Harga Vendor - Harga Diskon">${formatRupiah(item.nilai_diskon || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-gray-50 text-xs">
                <span class="text-gray-600" title="CALCULATED: Nilai Diskon × Qty">${formatRupiah(item.total_diskon || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-yellow-50 text-xs">
                <span>${formatRupiah(item.total_harga || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-yellow-50 text-xs">
                <span>${formatRupiah(item.jumlah_volume || 0)}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.persen_kenaikan || 0}" onchange="updateValue(${index}, 'persen_kenaikan', this.value)" class="no-spin text-right w-16" step="0.1" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-blue-50 text-xs">
                <span>${formatRupiah(item.proyeksi_kenaikan || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-blue-50 text-xs">
                <span>${formatRupiah(item.ppn_dinas || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-blue-50 text-xs">
                <span>${formatRupiah(item.pph_dinas || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-purple-50 text-xs">
                <span class="font-semibold">${formatRupiah(item.hps || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-purple-50 text-xs">
                <span>${formatRupiah(item.harga_per_pcs || 0)}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.harga_pagu_dinas_per_pcs || 0}" onchange="updateValue(${index}, 'harga_pagu_dinas_per_pcs', this.value)" class="no-spin text-right w-20" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-gray-50 text-xs">
                <span>${formatRupiah(item.pagu_total || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-gray-50 text-xs">
                <span class="${(item.selisih_pagu_hps || 0) >= 0 ? 'text-green-700' : 'text-red-700'}">${formatRupiah(item.selisih_pagu_hps || 0)}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.nilai_sp || 0}" onchange="updateValue(${index}, 'nilai_sp', this.value)" class="no-spin text-right w-20" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-orange-50 text-xs">
                <span>${formatRupiah(item.dpp || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-orange-50 text-xs">
                <span>${formatRupiah(item.asumsi_nilai_cair || 0)}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.ongkir || 0}" onchange="updateValue(${index}, 'ongkir', this.value)" class="no-spin text-right w-16" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.omzet_dinas_percent || 0}" onchange="updateValue(${index}, 'omzet_dinas_percent', this.value)" class="no-spin text-right w-12" step="0.1" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-red-50 text-xs">
                <span>${formatRupiah(item.omzet_nilai_dinas || 0)}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.bendera_percent || 0}" onchange="updateValue(${index}, 'bendera_percent', this.value)" class="no-spin text-right w-12" step="0.1" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-red-50 text-xs">
                <span>${formatRupiah(item.gross_nilai_bendera || 0)}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.bank_cost_percent || 0}" onchange="updateValue(${index}, 'bank_cost_percent', this.value)" class="no-spin text-right w-12" step="0.1" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-red-50 text-xs">
                <span>${formatRupiah(item.gross_nilai_bank_cost || 0)}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.biaya_ops_percent || 0}" onchange="updateValue(${index}, 'biaya_ops_percent', this.value)" class="no-spin text-right w-12" step="0.1" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-red-50 text-xs">
                <span>${formatRupiah(item.gross_nilai_biaya_ops || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-red-100 text-xs">
                <span class="font-semibold">${formatRupiah(item.sub_total_biaya_tidak_langsung || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-green-50 text-xs">
                <span>${formatRupiah(item.gross_income || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-green-50 text-xs">
                <span>${formatPercent(item.gross_income_persentase || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-green-100 text-xs">
                <span class="font-bold">${formatRupiah(item.nilai_nett_income || 0)}</span>
            </td>
            <td class="px-2 py-3 bg-green-100 text-xs">
                <span class="font-bold ${(item.nett_income_persentase || 0) >= 0 ? 'text-green-700' : 'text-red-700'}">${formatPercent(item.nett_income_persentase || 0)}</span>
            </td>
            <td class="px-2 py-3">
                <input type="text" value="${item.keterangan_1 || ''}" onchange="updateValue(${index}, 'keterangan_1', this.value)" class="w-20 text-xs" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3">
                ${canEdit ? `<button onclick="removeItem(${index})" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                    <i class="fas fa-trash"></i>
                </button>` : '<span class="text-gray-400 text-xs">-</span>'}
            </td>
        </tr>
    `;
}

// Helper functions for creating options
function createBarangOptions(selectedId) {
    return barangList.map(barang => 
        `<option value="${barang.id_barang}" ${barang.id_barang == selectedId ? 'selected' : ''}>${barang.nama_barang}</option>`
    ).join('');
}

function createVendorOptions(selectedId) {
    return vendorList.map(vendor => 
        `<option value="${vendor.id_vendor}" ${vendor.id_vendor == selectedId ? 'selected' : ''}>${vendor.nama_vendor}</option>`
    ).join('');
}

function createSatuanOptions(selectedSatuan) {
    const satuanList = window.hpsCalculator.getSatuanOptions();
    return satuanList.map(satuan => 
        `<option value="${satuan}" ${satuan === selectedSatuan ? 'selected' : ''}>${satuan}</option>`
    ).join('');
}

function createJenisVendorOptions(selectedJenis) {
    const jenisVendorList = [
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
    
    let options = '<option value="">Pilih Jenis Vendor</option>';
    options += jenisVendorList.map(jenis => 
        `<option value="${jenis}" ${jenis === selectedJenis ? 'selected' : ''}>${jenis}</option>`
    ).join('');
    
    return options;
}

// Add vendor item
function addVendorItem() {
    if (!canEdit) {
        alert('Anda tidak memiliki akses untuk menambah item.');
        return;
    }
    const newItem = window.hpsCalculator.addVendorItem();
    populateKalkulasiTable();
}

// Update functions
async function updateBarang(index, barangId) {
    if (barangId) {
        try {
            // Show loading indicator
            const loadingRow = document.querySelector(`tbody tr:nth-child(${index + 1})`);
            if (loadingRow) {
                loadingRow.style.opacity = '0.6';
            }
            
            // Fetch detailed barang data with vendor information
            const response = await fetch(`/purchasing/kalkulasi/barang/${barangId}`);
            const result = await response.json();
            
            if (result.success && result.barang) {
                const barangData = result.barang;
                
                // Update barang information
                kalkulasiData[index].id_barang = barangData.id_barang;
                kalkulasiData[index].nama_barang = barangData.nama_barang;
                
                // Auto-populate vendor information
                if (barangData.id_vendor && barangData.vendor) {
                    kalkulasiData[index].id_vendor = barangData.id_vendor;
                    kalkulasiData[index].nama_vendor = barangData.vendor.nama_vendor;
                    kalkulasiData[index].jenis_vendor = barangData.vendor.jenis_perusahaan;
                }
                
                // Auto-populate satuan
                if (barangData.satuan) {
                    kalkulasiData[index].satuan = barangData.satuan;
                }
                
                // Auto-populate harga vendor
                if (barangData.harga_vendor) {
                    kalkulasiData[index].harga_vendor = parseFloat(barangData.harga_vendor);
                }
                
                // Show success message briefly
                showSuccessMessage(`Data ${barangData.nama_barang} berhasil dimuat dengan vendor ${barangData.vendor ? barangData.vendor.nama_vendor : 'N/A'}`);
                
            } else {
                // Fallback to basic data if detailed fetch fails
                const barang = barangList.find(b => b.id_barang == barangId);
                if (barang) {
                    kalkulasiData[index].id_barang = barangId;
                    kalkulasiData[index].nama_barang = barang.nama_barang;
                    
                    if (barang.id_vendor) {
                        kalkulasiData[index].id_vendor = barang.id_vendor;
                        const vendor = vendorList.find(v => v.id_vendor == barang.id_vendor);
                        if (vendor) {
                            kalkulasiData[index].nama_vendor = vendor.nama_vendor;
                        }
                    }
                }
            }
            
            // Restore opacity
            if (loadingRow) {
                loadingRow.style.opacity = '1';
            }
            
        } catch (error) {
            console.error('Error fetching barang details:', error);
            
            // Fallback to basic data
            const barang = barangList.find(b => b.id_barang == barangId);
            if (barang) {
                kalkulasiData[index].id_barang = barangId;
                kalkulasiData[index].nama_barang = barang.nama_barang;
                
                if (barang.id_vendor) {
                    kalkulasiData[index].id_vendor = barang.id_vendor;
                    const vendor = vendorList.find(v => v.id_vendor == barang.id_vendor);
                    if (vendor) {
                        kalkulasiData[index].nama_vendor = vendor.nama_vendor;
                    }
                }
            }
            
            showErrorMessage('Gagal memuat detail barang, menggunakan data dasar');
        }
    } else {
        // Clear data if no barang selected
        kalkulasiData[index].id_barang = null;
        kalkulasiData[index].nama_barang = '';
        kalkulasiData[index].id_vendor = null;
        kalkulasiData[index].nama_vendor = '';
        kalkulasiData[index].satuan = '';
        kalkulasiData[index].harga_vendor = 0;
        kalkulasiData[index].jenis_vendor = '';
    }
    
    calculateRow(index);
    populateKalkulasiTable();
    calculateTotals();
}

function updateVendor(index, vendorId) {
    if (vendorId) {
        const vendor = vendorList.find(v => v.id_vendor == vendorId);
        if (vendor) {
            kalkulasiData[index].id_vendor = vendorId;
            kalkulasiData[index].nama_vendor = vendor.nama_vendor;
        }
    }
    
    calculateRow(index);
    populateKalkulasiTable();
    calculateTotals();
}

function updateValue(index, field, value) {
    // Validasi khusus untuk harga_diskon
    if (field === 'harga_diskon') {
        const hargaVendor = parseFloat(kalkulasiData[index].harga_vendor) || 0;
        const hargaDiskon = parseFloat(value) || 0;
        
        if (hargaDiskon > hargaVendor && hargaVendor > 0) {
            showErrorMessage('Harga diskon tidak boleh lebih besar dari harga vendor!');
            // Reset ke harga vendor jika lebih besar
            value = hargaVendor;
        }
    }
    
    kalkulasiData[index][field] = value;
    calculateRow(index);
    populateKalkulasiTable();
    calculateTotals();
}

function calculateRow(index) {
    kalkulasiData[index] = window.hpsCalculator.calculateItem(kalkulasiData[index]);
}

function calculateTotals() {
    kalkulasiData = window.hpsCalculator.calculateAll();
    const summary = window.hpsCalculator.getSummary();
    
    // Main summary totals
    document.getElementById('grand-total-hpp').textContent = formatRupiah(summary.totalHpp);
    document.getElementById('grand-total-hps').textContent = formatRupiah(summary.totalHps);
    document.getElementById('grand-total-nett').textContent = formatRupiah(summary.totalNett);
    document.getElementById('grand-avg-nett').textContent = formatPercent(summary.avgNettIncomePercent);
    
    // Additional summary details
    if (document.getElementById('total-items')) {
        document.getElementById('total-items').textContent = summary.totalItems || 0;
    }
    if (document.getElementById('total-diskon')) {
        document.getElementById('total-diskon').textContent = formatRupiah(summary.totalDiskon || 0);
    }
    if (document.getElementById('total-volume')) {
        document.getElementById('total-volume').textContent = formatRupiah(summary.totalVolume || 0);
    }
    if (document.getElementById('total-dpp')) {
        document.getElementById('total-dpp').textContent = formatRupiah(summary.totalDpp || 0);
    }
    if (document.getElementById('total-asumsi-cair')) {
        document.getElementById('total-asumsi-cair').textContent = formatRupiah(summary.totalAsumsiCair || 0);
    }
    if (document.getElementById('total-ongkir')) {
        document.getElementById('total-ongkir').textContent = formatRupiah(summary.totalOngkir || 0);
    }
    
    // Check if penawaran button should be shown
    checkPenawaranButtonVisibility();
}

function checkPenawaranButtonVisibility() {
    const btnCreatePenawaran = document.getElementById('btn-create-penawaran');
    if (!btnCreatePenawaran) return;
    
    // Show button if there's valid kalkulasi data
    const hasValidData = kalkulasiData.length > 0 && 
                        kalkulasiData.some(item => item.id_barang && item.id_vendor && item.hps > 0);
    
    if (hasValidData) {
        btnCreatePenawaran.style.display = 'inline-block';
        btnCreatePenawaran.disabled = false;
    } else {
        btnCreatePenawaran.style.display = 'none';
        btnCreatePenawaran.disabled = true;
    }
}

function removeItem(index) {
    if (!canEdit) {
        alert('Anda tidak memiliki akses untuk menghapus item.');
        return;
    }
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        kalkulasiData.splice(index, 1);
        populateKalkulasiTable();
        calculateTotals();
    }
}

function clearVendorData() {
    if (!canEdit) {
        alert('Anda tidak memiliki akses untuk menghapus data.');
        return;
    }
    if (confirm('Apakah Anda yakin ingin menghapus semua data vendor?')) {
        kalkulasiData = [];
        populateKalkulasiTable();
        calculateTotals();
    }
}

function recalculateAll() {
    kalkulasiData.forEach((item, index) => {
        calculateRow(index);
    });
    populateKalkulasiTable();
    calculateTotals();
}

function validateCalculation() {
    const validation = window.hpsCalculator.validateCalculation();
    
    if (validation.isValid) {
        alert('Validasi berhasil! Semua perhitungan sudah benar.');
    } else {
        alert('Validasi gagal:\n' + validation.errors.join('\n'));
    }
}

// Save kalkulasi
async function saveKalkulasi() {
    if (!canEdit) {
        alert('Anda tidak memiliki akses untuk menyimpan kalkulasi.');
        return;
    }
    if (!currentProyekId) {
        alert('ID Proyek tidak ditemukan');
        return;
    }
    if (kalkulasiData.length === 0) {
        alert('Tidak ada data kalkulasi untuk disimpan');
        return;
    }
    const hasEmptyFields = kalkulasiData.some(item => !item.id_barang || !item.id_vendor);
    if (hasEmptyFields) {
        alert('Mohon lengkapi semua field barang dan vendor');
        return;
    }

    // --- Batasi nilai persentase agar tidak out of range ---
    const sanitizedKalkulasi = kalkulasiData.map(item => {
        // Batasi gross_income_percent dan nett_income_percent maksimal 100
        const grossIncomePercent = Math.min(parseFloat(item.gross_income_persentase || 0), 100);
        const nettIncomePercent = Math.min(parseFloat(item.nett_income_persentase || 0), 100);
        return {
            ...item,
            gross_income_persentase: grossIncomePercent,
            gross_income_percent: grossIncomePercent,
            nett_income_persentase: nettIncomePercent,
            nett_income_percent: nettIncomePercent
        };
    });

    try {
        const response = await fetch('/purchasing/kalkulasi/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                id_proyek: currentProyekId,
                kalkulasi: sanitizedKalkulasi
            })
        });
        const data = await response.json();
        if (data.success) {
            showSuccessMessage('Kalkulasi berhasil disimpan');
            const now = new Date().toLocaleString('id-ID');
            if (document.getElementById('last-updated')) {
                document.getElementById('last-updated').textContent = now;
            }
            if (document.getElementById('last-updated-footer')) {
                document.getElementById('last-updated-footer').textContent = now;
            }
            // Check if penawaran button should be shown
            checkPenawaranButtonVisibility();
        } else {
            alert(data.message || 'Gagal menyimpan kalkulasi');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan kalkulasi');
    }
}

// Create penawaran
async function createPenawaran() {
    if (!canEdit) {
        alert('Anda tidak memiliki akses untuk membuat penawaran.');
        return;
    }
    
    if (!currentProyekId) {
        showErrorMessage('ID Proyek tidak ditemukan');
        return;
    }
    
    if (kalkulasiData.length === 0) {
        showErrorMessage('Tidak ada data kalkulasi untuk membuat penawaran');
        return;
    }
    
    // Validasi data kalkulasi
    const invalidItems = [];
    kalkulasiData.forEach((item, index) => {
        if (!item.id_barang || !item.id_vendor) {
            invalidItems.push(`Item ${index + 1}: Barang dan vendor harus dipilih`);
        }
        if (!item.qty || item.qty <= 0) {
            invalidItems.push(`Item ${index + 1}: Quantity harus lebih dari 0`);
        }
        if (!item.harga_vendor || item.harga_vendor <= 0) {
            invalidItems.push(`Item ${index + 1}: Harga vendor harus lebih dari 0`);
        }
        if (!item.hps || item.hps <= 0) {
            invalidItems.push(`Item ${index + 1}: HPS harus dihitung dan lebih dari 0`);
        }
    });
    
    if (invalidItems.length > 0) {
        showErrorMessage('Data tidak lengkap:\n' + invalidItems.join('\n'));
        return;
    }
    
    // Tampilkan preview sebelum konfirmasi
    const previewResponse = await fetch('/purchasing/kalkulasi/penawaran/preview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id_proyek: currentProyekId })
    });
    
    const previewData = await previewResponse.json();
    
    if (!previewData.success) {
        showErrorMessage('Gagal membuat preview: ' + previewData.message);
        return;
    }
    
    // Buat preview summary yang lebih detail
    const previewSummary = previewData.preview;
    const confirmMessage = `Apakah Anda yakin ingin membuat penawaran dari kalkulasi ini?

PREVIEW PENAWARAN:
Klien: ${previewSummary.proyek.nama_klien}
Instansi: ${previewSummary.proyek.instansi}
Total Items: ${previewSummary.total_items}
Total Penawaran: ${formatRupiah(previewSummary.total_penawaran)}

DETAIL ITEMS:
${previewSummary.details.map((detail, index) => 
    `${index + 1}. ${detail.nama_barang}\n   ${detail.qty} ${detail.satuan} × ${formatRupiah(detail.harga_satuan)} = ${formatRupiah(detail.subtotal)}`
).join('\n')}

Status proyek akan berubah menjadi "Penawaran".`;
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    try {
        // Validasi ulang data sebelum dikirim
        const validatedData = kalkulasiData.map(item => ({
            ...item,
            qty: parseInt(item.qty) || 1,
            harga_vendor: parseFloat(item.harga_vendor) || 0,
            hps: parseFloat(item.hps) || 0
        }));
        
        const response = await fetch('/purchasing/kalkulasi/penawaran', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                id_proyek: currentProyekId,
                debug_data: validatedData // Kirim data untuk debugging
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccessMessage('Penawaran berhasil dibuat dengan nomor: ' + data.data.no_penawaran);
            setTimeout(() => {
                window.location.href = '/purchasing/kalkulasi';
            }, 2000);
        } else {
            showErrorMessage(data.message || 'Gagal membuat penawaran');
        }
    } catch (error) {
        console.error('Error:', error);
        showErrorMessage('Terjadi kesalahan saat membuat penawaran');
    }
}

// Utility functions
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount || 0);
}

function formatPercent(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'percent',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format((value || 0) / 100);
}

// Helper functions for notifications
function showSuccessMessage(message) {
    // Create and show a temporary success message
    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300';
    messageDiv.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(messageDiv);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        messageDiv.style.opacity = '0';
        messageDiv.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.parentNode.removeChild(messageDiv);
            }
        }, 300);
    }, 3000);
}

function showErrorMessage(message) {
    // Create and show a temporary error message
    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300';
    messageDiv.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(messageDiv);
    
    // Auto-remove after 4 seconds
    setTimeout(() => {
        messageDiv.style.opacity = '0';
        messageDiv.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.parentNode.removeChild(messageDiv);
            }
        }, 300);
    }, 4000);
}

// Handle file upload for bukti approval
function handleFileUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validate file size (2MB max)
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    if (file.size > maxSize) {
        showErrorMessage('Ukuran file terlalu besar. Maksimal 2MB.');
        input.value = '';
        return;
    }
    
    // Validate file type
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        showErrorMessage('Tipe file tidak diizinkan. Hanya PDF, JPG, PNG yang diperbolehkan.');
        input.value = '';
        return;
    }
    
    uploadApprovalFile(file);
}

// Upload approval file
async function uploadApprovalFile(file) {
    if (!currentProyekId) {
        showErrorMessage('ID Proyek tidak ditemukan');
        return;
    }
    
    const formData = new FormData();
    formData.append('bukti_approval', file);
    formData.append('id_proyek', currentProyekId);
    formData.append('action', 'upload');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Show progress
    const progressDiv = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const statusDiv = document.getElementById('upload-status');
    const messageDiv = document.getElementById('upload-message');
    
    progressDiv.classList.remove('hidden');
    messageDiv.classList.add('hidden');
    
    try {
        const xhr = new XMLHttpRequest();
        
        // Track upload progress
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
                statusDiv.textContent = `Uploading... ${Math.round(percentComplete)}%`;
            }
        });
        
        xhr.onload = function() {
            progressDiv.classList.add('hidden');
            
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    showSuccessMessage('File bukti approval berhasil diupload');
                    updateCurrentFileDisplay(response.file_path, response.file_name || file.name);
                    // Clear input
                    document.getElementById('bukti-approval-file').value = '';
                } else {
                    showErrorMessage(response.message || 'Gagal upload file');
                }
            } else {
                showErrorMessage('Terjadi kesalahan saat upload file');
            }
        };
        
        xhr.onerror = function() {
            progressDiv.classList.add('hidden');
            showErrorMessage('Terjadi kesalahan saat upload file');
        };
        
        xhr.open('POST', '/purchasing/kalkulasi/manage-approval');
        xhr.send(formData);
        
    } catch (error) {
        progressDiv.classList.add('hidden');
        showErrorMessage('Terjadi kesalahan saat upload file');
        console.error('Upload error:', error);
    }
}

// Update current file display
function updateCurrentFileDisplay(filePath, fileName) {
    const currentFileDisplay = document.getElementById('current-file-display');
    const now = new Date().toLocaleString('id-ID');
    
    currentFileDisplay.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-file-alt text-blue-500 text-xl"></i>
                <div>
                    <div class="text-sm font-medium text-gray-900">${fileName}</div>
                    <div class="text-xs text-gray-500">
                        Uploaded: ${now}
                    </div>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="${filePath}" target="_blank" 
                   class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-eye mr-1"></i>Lihat
                </a>
                <button onclick="deleteApprovalFile()" 
                        class="text-red-600 hover:text-red-800 text-sm">
                    <i class="fas fa-trash mr-1"></i>Hapus
                </button>
            </div>
        </div>
    `;
}

// Delete approval file
async function deleteApprovalFile() {
    if (!currentProyekId) {
        showErrorMessage('ID Proyek tidak ditemukan');
        return;
    }
    
    if (!confirm('Apakah Anda yakin ingin menghapus file bukti approval?')) {
        return;
    }
    
    try {
        const response = await fetch('/purchasing/kalkulasi/manage-approval', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                action: 'delete',
                id_proyek: currentProyekId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccessMessage('File bukti approval berhasil dihapus');
            updateCurrentFileDisplayEmpty();
        } else {
            showErrorMessage(data.message || 'Gagal menghapus file');
        }
    } catch (error) {
        showErrorMessage('Terjadi kesalahan saat menghapus file');
        console.error('Delete error:', error);
    }
}

// Update current file display to empty state
function updateCurrentFileDisplayEmpty() {
    const currentFileDisplay = document.getElementById('current-file-display');
    currentFileDisplay.innerHTML = `
        <div class="text-center text-gray-500 py-4">
            <i class="fas fa-file-alt text-3xl text-gray-300 mb-2"></i>
            <div class="text-sm">Belum ada file bukti approval</div>
        </div>
    `;
}
</script>
@endpush
