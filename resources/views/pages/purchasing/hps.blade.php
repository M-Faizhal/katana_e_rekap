@extends('layouts.app')

@section('title', 'Kalkulasi HPS - ' . ($proyek->kode_proyek ?? 'Unknown') . ' - Cyber KATANA')

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
            <button onclick="lihatRiwayatHps()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm">
                <i class="fas fa-history mr-1"></i>
                Lihat Riwayat
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
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase bg-yellow-100" title="Input: Harga yang diharapkan">Harga Yang Diharapkan <br><small class="text-blue-600">(INPUT)</small></th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase bg-gray-100" title="Calculated: (((Harga Yang Diharapkan × QTY) - (Total Harga hpp + Nilai PPH + Nilai PPN)) / Total Harga hpp) × 100">% Kenaikan <br><small class="text-gray-600">(AUTO)</small></th>
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
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">% Dinas</th>
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">Dinas</th>
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
                        <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase">TKDN</th>
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
                <div class="text-lg font-bold text-yellow-700" id="grand-total-hpp">-</div>
                <div class="text-xs text-gray-500">Harga beli dari vendor</div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Total HPS</div>
                <div class="text-lg font-bold text-blue-700" id="grand-total-hps">-</div>
                <div class="text-xs text-gray-500">Harga penawaran ke klien</div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Total Nett</div>
                <div class="text-lg font-bold text-green-700" id="grand-total-nett">-</div>
                <div class="text-xs text-gray-500">Pendapatan bersih</div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Rata-rata % Nett</div>
                <div class="text-lg font-bold text-red-700" id="grand-avg-nett">-</div>
                <div class="text-xs text-gray-500">Margin bersih</div>
            </div>
        </div>
        
        <!-- Additional Summary Details -->
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 text-center">
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Items</div>
                <div class="text-sm font-semibold" id="total-items">-</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Diskon</div>
                <div class="text-sm font-semibold" id="total-diskon">-</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Volume</div>
                <div class="text-sm font-semibold" id="total-volume">-</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total DPP</div>
                <div class="text-sm font-semibold" id="total-dpp">-</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Asumsi Cair</div>
                <div class="text-sm font-semibold" id="total-asumsi-cair">-</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Ongkir</div>
                <div class="text-sm font-semibold" id="total-ongkir">-</div>
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
            <button onclick="saveKalkulasiWithHistory()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>
                Simpan Kalkulasi dengan Riwayat
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

/* Client mapping styles */
.hps-table .bg-blue-50 {
    background-color: #eff6ff !important;
}

.hps-table .bg-blue-50 input {
    background-color: #dbeafe !important;
    border-color: #3b82f6 !important;
}

.hps-table .border-blue-300 {
    border-color: #93c5fd !important;
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

/* Searchable Dropdown Styles */
.barang-search-container {
    position: relative;
}

.barang-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #d1d5db;
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    max-height: 250px;
    overflow-y: auto;
    z-index: 9999;
    margin-top: 1px;
}

.barang-dropdown-item {
    padding: 14px 18px;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
    line-height: 1.4;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: block;
    width: 100%;
    text-align: left;
    user-select: none;
    position: relative;
    background: linear-gradient(90deg, transparent 0%, transparent 100%);
    border-left: 3px solid transparent;
}

.barang-dropdown-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 0;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    transition: width 0.3s ease;
    z-index: 0;
}

.barang-dropdown-item span {
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
}

.barang-dropdown-item:hover {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    color: #0f172a;
    transform: translateX(4px) scale(1.02);
    border-left: 3px solid #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.barang-dropdown-item:hover::before {
    width: 4px;
}

.barang-dropdown-item:hover span {
    font-weight: 500;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.barang-dropdown-item:last-child {
    border-bottom: none;
    border-radius: 0 0 8px 8px;
}

.barang-dropdown-item.active {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    transform: translateX(4px) scale(1.02);
    border-left: 3px solid #1d4ed8;
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
}

.barang-dropdown-item.active::before {
    width: 4px;
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
}

.barang-dropdown-item:active {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateX(2px) scale(0.98);
    transition: all 0.1s ease;
}

/* Alternate hover styles for variety */
.barang-dropdown-item:nth-child(even):hover {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-left: 3px solid #f59e0b;
    color: #92400e;
}

.barang-dropdown-item:nth-child(3n):hover {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    border-left: 3px solid #10b981;
    color: #065f46;
}

.barang-dropdown-item:nth-child(4n):hover {
    background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
    border-left: 3px solid #ec4899;
    color: #831843;
}

/* Pulse animation on focus */
@keyframes pulse-subtle {
    0%, 100% { box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15); }
    50% { box-shadow: 0 6px 16px rgba(59, 130, 246, 0.25); }
}

.barang-dropdown-item:hover {
    animation: pulse-subtle 2s infinite;
}

/* Icon and arrow styling */
.barang-dropdown-item .item-icon {
    transition: all 0.3s ease;
    width: 20px;
    display: flex;
    justify-content: center;
}

.barang-dropdown-item .item-arrow {
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateX(-10px);
}

.barang-dropdown-item:hover .item-icon {
    transform: scale(1.2) rotate(5deg);
    color: inherit;
}

.barang-dropdown-item:hover .item-arrow {
    opacity: 1;
    transform: translateX(0);
}

.barang-dropdown-item:nth-child(even):hover .item-icon i {
    color: #f59e0b;
}

.barang-dropdown-item:nth-child(3n):hover .item-icon i {
    color: #10b981;
}

.barang-dropdown-item:nth-child(4n):hover .item-icon i {
    color: #ec4899;
}

.barang-dropdown-item.active .item-icon {
    transform: scale(1.2) rotate(5deg);
    color: white;
}

.barang-dropdown-item.active .item-arrow {
    opacity: 1;
    transform: translateX(0);
    color: white;
}

/* Staggered animation for dropdown items */
.barang-dropdown-item {
    opacity: 0;
    animation: slideInFromLeft 0.3s ease forwards;
}

.barang-dropdown-item:nth-child(1) { animation-delay: 0.05s; }
.barang-dropdown-item:nth-child(2) { animation-delay: 0.1s; }
.barang-dropdown-item:nth-child(3) { animation-delay: 0.15s; }
.barang-dropdown-item:nth-child(4) { animation-delay: 0.2s; }
.barang-dropdown-item:nth-child(5) { animation-delay: 0.25s; }
.barang-dropdown-item:nth-child(6) { animation-delay: 0.3s; }
.barang-dropdown-item:nth-child(7) { animation-delay: 0.35s; }
.barang-dropdown-item:nth-child(8) { animation-delay: 0.4s; }
.barang-dropdown-item:nth-child(9) { animation-delay: 0.45s; }
.barang-dropdown-item:nth-child(10) { animation-delay: 0.5s; }

@keyframes slideInFromLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Special highlight effect */
.barang-dropdown-item:hover::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    width: 6px;
    height: 6px;
    background: currentColor;
    border-radius: 50%;
    animation: dot-pulse 1.5s infinite;
}

@keyframes dot-pulse {
    0%, 100% { 
        opacity: 0.6;
        transform: translateY(-50%) scale(1);
    }
    50% { 
        opacity: 1;
        transform: translateY(-50%) scale(1.2);
    }
}

.barang-dropdown-empty,
.barang-dropdown-loading {
    padding: 16px;
    text-align: center;
    color: #6b7280;
    font-size: 12px;
    font-style: italic;
    background-color: #f8fafc;
    border-radius: 0 0 8px 8px;
}

.barang-dropdown-loading {
    background-color: #f0f9ff;
    color: #0369a1;
    font-weight: 500;
}

.barang-dropdown-summary {
    padding: 8px 16px;
    text-align: center;
    color: #64748b;
    font-size: 11px;
    background-color: #f1f5f9;
    border-top: 1px solid #e2e8f0;
    font-weight: 500;
}

/* Improve search input appearance */
.barang-search-input {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.2s ease;
    background-color: white;
    min-height: 40px;
    line-height: 1.4;
}

.barang-search-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background-color: #fafbff;
}

.barang-search-input:hover:not(:focus) {
    border-color: #9ca3af;
}

.barang-search-input::placeholder {
    color: #9ca3af;
    font-style: italic;
}

/* Loading animation for search */
.barang-search-loading::after {
    content: '';
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid #3b82f6;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s ease-in-out infinite;
    margin-left: 8px;
    vertical-align: middle;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive dropdown */
@media (max-width: 768px) {
    .barang-dropdown {
        max-height: 200px;
        font-size: 12px;
    }
    
    .barang-dropdown-item {
        padding: 10px 12px;
        font-size: 12px;
    }
    
    .barang-dropdown-item:hover {
        transform: translateX(2px) scale(1.01);
    }
    
    .barang-search-input {
        padding: 8px 10px;
        font-size: 12px;
        min-height: 36px;
    }
    
    /* Reduce animation complexity on mobile */
    .barang-dropdown-item::before {
        display: none;
    }
    
    .barang-dropdown-item:hover {
        animation: none;
    }
}

/* Dark mode support (optional) */
@media (prefers-color-scheme: dark) {
    .barang-dropdown-item:hover {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        color: #f1f5f9;
        border-left-color: #3b82f6;
    }
}

/* Scroll styling for dropdown */
.barang-dropdown::-webkit-scrollbar {
    width: 6px;
}

.barang-dropdown::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.barang-dropdown::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.barang-dropdown::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
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

// Initialize default values for new fields
kalkulasiData = kalkulasiData.map(item => ({
    ...item,
    harga_yang_diharapkan: item.harga_yang_diharapkan || 0,
    persen_kenaikan: item.persen_kenaikan || item.kenaikan_percent || 0
}));

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeHPS();
    preventNumberInputScroll();
    setupNumberFormatting();
});

// Setup number formatting for input fields
function setupNumberFormatting() {
    // Add event listeners for number formatting
    document.addEventListener('input', function(e) {
        // Skip search inputs - don't format barang search
        if (e.target.id && e.target.id.startsWith('barang-search-')) {
            return; // Don't format search inputs
        }
        
        // Only format text inputs that are used for numbers
        if (e.target.type === 'text' && 
            (e.target.placeholder.includes('.') || 
             ['qty', 'harga_vendor', 'harga_diskon', 'harga_yang_diharapkan', 'harga_pagu_dinas_per_pcs', 'nilai_sp', 'ongkir'].some(field => 
                e.target.getAttribute('onchange') && e.target.getAttribute('onchange').includes(field)
             ))) {
            
            // Get cursor position
            const cursorPos = e.target.selectionStart;
            const oldValue = e.target.value;
            
            // Format the value
            const numericValue = parseFormattedNumber(e.target.value);
            if (numericValue > 0) {
                const formattedValue = formatNumber(numericValue);
                
                // Only update if different to avoid cursor jumping
                if (formattedValue !== oldValue) {
                    e.target.value = formattedValue;
                    
                    // Restore cursor position (approximately)
                    const newCursorPos = Math.min(cursorPos + (formattedValue.length - oldValue.length), formattedValue.length);
                    e.target.setSelectionRange(newCursorPos, newCursorPos);
                }
            }
        }
    });
    
    // Prevent invalid characters in number inputs (but exclude search inputs)
    document.addEventListener('keypress', function(e) {
        // Skip search inputs - don't restrict characters for barang search
        if (e.target.id && e.target.id.startsWith('barang-search-')) {
            return; // Allow all characters for search
        }
        
        if (e.target.type === 'text' && 
            (e.target.placeholder.includes('.') || 
             ['qty', 'harga_vendor', 'harga_diskon', 'harga_yang_diharapkan', 'harga_pagu_dinas_per_pcs', 'nilai_sp', 'ongkir'].some(field => 
                e.target.getAttribute('onchange') && e.target.getAttribute('onchange').includes(field)
             ))) {
            
            // Allow: backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            
            // Ensure that it is a number or dot and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && e.keyCode !== 190 && e.keyCode !== 110) {
                e.preventDefault();
            }
        }
    });
}

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
        
        // Auto-fill existing kalkulasi data dari client requests jika diperlukan
        if (kalkulasiData.length > 0) {
            window.hpsCalculator.autoFillFromClientRequests();
        }
        
        // Populate table
        populateKalkulasiTable();
        calculateTotals();
        
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
    const clientRequest = currentProject.proyekBarang && currentProject.proyekBarang[index];
    const hasClientMapping = clientRequest ? true : false;
    
    // Tambahkan visual indicator jika ada mapping dengan client request
    const mappingIndicator = hasClientMapping ? 
        `<span class="text-xs text-blue-600 font-medium" title="Data dari permintaan klien: ${clientRequest.nama_barang}">
            <i class="fas fa-link"></i> ${index + 1}
        </span>` : 
        `<span class="text-xs text-gray-500">${index + 1}</span>`;
    
    return `
        <tr class="hover:bg-gray-50 ${hasClientMapping ? 'bg-blue-50' : ''}">
            <td class="px-2 py-3 text-sm text-gray-900">${mappingIndicator}</td>
            <td class="px-2 py-3">
                <div class="barang-search-container">
                    <div style="position: relative;">
                        <input type="text" 
                               id="barang-search-${index}" 
                               class="barang-search-input" 
                               placeholder="🔍 Ketik untuk mencari barang..." 
                               autocomplete="off"
                               oninput="searchBarang(${index}, this.value)"
                               onfocus="showBarangDropdown(${index})"
                               onblur="hideBarangDropdown(${index})"
                               ${!canEdit ? 'readonly' : ''}
                               value="${item.nama_barang || ''}" />
                        ${item.nama_barang ? `<button type="button" 
                               onclick="clearBarangSelection(${index})"
                               style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); 
                                      background: none; border: none; color: #6b7280; cursor: pointer; 
                                      padding: 4px; border-radius: 4px; hover:background-color: #f3f4f6;"
                               title="Hapus pilihan">
                            ✕
                        </button>` : ''}
                    </div>
                    <div id="barang-dropdown-${index}" 
                         class="barang-dropdown hidden">
                    </div>
                </div>
                ${hasClientMapping ? `<div class="text-xs text-blue-600 mt-1">Permintaan: ${clientRequest.nama_barang}</div>` : ''}
            </td>
            <td class="px-2 py-3 text-sm text-gray-700">
                <span>${item.nama_vendor || '-'}</span>
            </td>
            <td class="px-2 py-3 text-sm text-gray-700">
                <span>${item.jenis_vendor || '-'}</span>
            </td>
            <td class="px-2 py-3 text-sm text-gray-700">
                <span>${item.satuan || '-'}</span>
                ${hasClientMapping ? `<div class="text-xs text-blue-600">Klien: ${clientRequest.satuan}</div>` : ''}
            </td>
            <td class="px-2 py-3">
                <input type="text" value="${item.qty > 0 ? formatNumber(item.qty) : ''}" onchange="updateValue(${index}, 'qty', this.value)" class="no-spin text-right w-16 ${hasClientMapping ? 'bg-blue-50 border-blue-300' : ''}" placeholder="Jumlah" ${!canEdit ? 'readonly' : ''}>
                ${hasClientMapping ? `<div class="text-xs text-blue-600">Klien: ${formatNumber(clientRequest.jumlah)}</div>` : ''}
            </td>
            <td class="px-2 py-3">
                <input type="text" value="${item.harga_vendor > 0 ? formatNumber(item.harga_vendor) : ''}" onchange="updateValue(${index}, 'harga_vendor', this.value)" class="no-spin text-right w-20" placeholder="10.000" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-yellow-100">
                <input type="text" value="${item.harga_diskon > 0 ? formatNumber(item.harga_diskon) : ''}" onchange="updateValue(${index}, 'harga_diskon', this.value)" class="no-spin text-right w-20 font-semibold" placeholder="9.500" title="INPUT: Masukkan harga akhir setelah diskon" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-gray-50 text-xs">
                <span class="text-gray-600" title="CALCULATED: Harga Vendor - Harga Diskon">${(item.nilai_diskon && item.nilai_diskon > 0) ? formatRupiah(item.nilai_diskon) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-gray-50 text-xs">
                <span class="text-gray-600" title="CALCULATED: Nilai Diskon × Qty">${(item.total_diskon && item.total_diskon > 0) ? formatRupiah(item.total_diskon) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-yellow-50 text-xs">
                <span>${(item.total_harga && item.total_harga > 0) ? formatRupiah(item.total_harga) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-yellow-50 text-xs">
                <span>${(item.jumlah_volume && item.jumlah_volume > 0) ? formatRupiah(item.jumlah_volume) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-yellow-100">
                <input type="text" value="${item.harga_yang_diharapkan > 0 ? formatNumber(item.harga_yang_diharapkan) : ''}" onchange="updateValue(${index}, 'harga_yang_diharapkan', this.value)" class="no-spin text-right w-20 font-semibold" placeholder="12.000" title="INPUT: Masukkan harga yang diharapkan" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-gray-50 text-xs">
                <span class="text-gray-600 ${(item.persen_kenaikan || 0) >= 0 ? 'text-green-700' : 'text-red-700'}" title="CALCULATED: (((Harga Yang Diharapkan × QTY) - (Total Harga hpp + Nilai PPH + Nilai PPN)) / Total Harga hpp) × 100">${item.persen_kenaikan ? formatPercent(item.persen_kenaikan) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-blue-50 text-xs">
                <span>${(item.proyeksi_kenaikan && item.proyeksi_kenaikan > 0) ? formatRupiah(item.proyeksi_kenaikan) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-blue-50 text-xs">
                <span>${(item.ppn_dinas && item.ppn_dinas > 0) ? formatRupiah(item.ppn_dinas) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-blue-50 text-xs">
                <span>${(item.pph_dinas && item.pph_dinas > 0) ? formatRupiah(item.pph_dinas) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-purple-50 text-xs">
                <span class="font-semibold">${(item.hps && item.hps > 0) ? formatRupiah(item.hps) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-purple-50 text-xs">
                <span>${(item.harga_per_pcs && item.harga_per_pcs > 0) ? formatRupiah(item.harga_per_pcs) : '-'}</span>
            </td>
            <td class="px-2 py-3">
                <input type="text" value="${item.harga_pagu_dinas_per_pcs > 0 ? formatNumber(item.harga_pagu_dinas_per_pcs) : ''}" onchange="updateValue(${index}, 'harga_pagu_dinas_per_pcs', this.value)" class="no-spin text-right w-20 ${hasClientMapping ? 'bg-blue-50 border-blue-300' : ''}" placeholder="11.000" ${!canEdit ? 'readonly' : ''}>
                ${hasClientMapping ? `<div class="text-xs text-blue-600">Klien: ${formatRupiah(clientRequest.harga_satuan)}</div>` : ''}
            </td>
            <td class="px-2 py-3 bg-gray-50 text-xs">
                <span>${(item.pagu_total && item.pagu_total > 0) ? formatRupiah(item.pagu_total) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-gray-50 text-xs">
                <span class="${(item.selisih_pagu_hps || 0) >= 0 ? 'text-green-700' : 'text-red-700'}">${item.selisih_pagu_hps ? formatRupiah(item.selisih_pagu_hps) : '-'}</span>
            </td>
            <td class="px-2 py-3">
                <input type="text" value="${item.nilai_sp > 0 ? formatNumber(item.nilai_sp) : ''}" onchange="updateValue(${index}, 'nilai_sp', this.value)" class="no-spin text-right w-20" placeholder="5.000" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-orange-50 text-xs">
                <span>${(item.dpp && item.dpp > 0) ? formatRupiah(item.dpp) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-orange-50 text-xs">
                <span>${(item.asumsi_nilai_cair && item.asumsi_nilai_cair > 0) ? formatRupiah(item.asumsi_nilai_cair) : '-'}</span>
            </td>
            <td class="px-2 py-3">
                <input type="text" value="${item.ongkir > 0 ? formatNumber(item.ongkir) : ''}" onchange="updateValue(${index}, 'ongkir', this.value)" class="no-spin text-right w-16" placeholder="50.000" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.omzet_dinas_percent > 0 ? item.omzet_dinas_percent : ''}" onchange="updateValue(${index}, 'omzet_dinas_percent', this.value)" class="no-spin text-right w-12" step="0.1" placeholder="%" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-red-50 text-xs">
                <span>${(item.omzet_nilai_dinas && item.omzet_nilai_dinas > 0) ? formatRupiah(item.omzet_nilai_dinas) : '-'}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.bendera_percent > 0 ? item.bendera_percent : ''}" onchange="updateValue(${index}, 'bendera_percent', this.value)" class="no-spin text-right w-12" step="0.1" placeholder="%" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-red-50 text-xs">
                <span>${(item.gross_nilai_bendera && item.gross_nilai_bendera > 0) ? formatRupiah(item.gross_nilai_bendera) : '-'}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.bank_cost_percent > 0 ? item.bank_cost_percent : ''}" onchange="updateValue(${index}, 'bank_cost_percent', this.value)" class="no-spin text-right w-12" step="0.1" placeholder="%" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-red-50 text-xs">
                <span>${(item.gross_nilai_bank_cost && item.gross_nilai_bank_cost > 0) ? formatRupiah(item.gross_nilai_bank_cost) : '-'}</span>
            </td>
            <td class="px-2 py-3">
                <input type="number" value="${item.biaya_ops_percent > 0 ? item.biaya_ops_percent : ''}" onchange="updateValue(${index}, 'biaya_ops_percent', this.value)" class="no-spin text-right w-12" step="0.1" placeholder="%" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3 bg-red-50 text-xs">
                <span>${(item.gross_nilai_biaya_ops && item.gross_nilai_biaya_ops > 0) ? formatRupiah(item.gross_nilai_biaya_ops) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-red-100 text-xs">
                <span class="font-semibold">${(item.sub_total_biaya_tidak_langsung && item.sub_total_biaya_tidak_langsung > 0) ? formatRupiah(item.sub_total_biaya_tidak_langsung) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-green-50 text-xs">
                <span>${(item.gross_income && item.gross_income > 0) ? formatRupiah(item.gross_income) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-green-50 text-xs">
                <span>${item.gross_income_persentase ? formatPercent(item.gross_income_persentase) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-green-100 text-xs">
                <span class="font-bold">${(item.nilai_nett_income && item.nilai_nett_income > 0) ? formatRupiah(item.nilai_nett_income) : '-'}</span>
            </td>
            <td class="px-2 py-3 bg-green-100 text-xs">
                <span class="font-bold ${(item.nett_income_persentase || 0) >= 0 ? 'text-green-700' : 'text-red-700'}">${item.nett_income_persentase ? formatPercent(item.nett_income_persentase) : '-'}</span>
            </td>
            <td class="px-2 py-3">
                <input type="text" value="${item.keterangan_1 || ''}" onchange="updateValue(${index}, 'keterangan_1', this.value)" class="w-20 text-xs" placeholder="Keterangan" ${!canEdit ? 'readonly' : ''}>
            </td>
            <td class="px-2 py-3">
                <input type="text" value="${item.keterangan_2 || ''}" onchange="updateValue(${index}, 'keterangan_2', this.value)" class="w-20 text-xs" placeholder="TKDN" ${!canEdit ? 'readonly' : ''}>
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
    
    // Show info jika ada auto-fill
    const currentIndex = kalkulasiData.length - 1;
    const clientRequest = currentProject.proyekBarang && currentProject.proyekBarang[currentIndex];
    if (clientRequest) {
        showSuccessMessage(`Item baru ditambah dengan data dari permintaan klien: ${clientRequest.nama_barang} (${clientRequest.jumlah} ${clientRequest.satuan})`);
    }
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
                
                // Reset semua field yang dapat di-input user sebelum update
                resetInputFields(index);
                
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
                
                // Immediately recalculate and refresh table after reset and update
                calculateRow(index);
                populateKalkulasiTable();
                calculateTotals();
                
                // Show success message briefly
                showSuccessMessage(`Data ${barangData.nama_barang} berhasil dimuat dengan vendor ${barangData.vendor ? barangData.vendor.nama_vendor : 'N/A'}`);
                
            } else {
                // Fallback to basic data if detailed fetch fails
                const barang = barangList.find(b => b.id_barang == barangId);
                if (barang) {
                    // Reset semua field yang dapat di-input user sebelum update
                    resetInputFields(index);
                    
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
                // Reset semua field yang dapat di-input user sebelum update
                resetInputFields(index);
                
                kalkulasiData[index].id_barang = barangId;
                kalkulasiData[index].nama_barang = barang.nama_barang;
                
                if (barang.id_vendor) {
                    kalkulasiData[index].id_vendor = barang.id_vendor;
                    const vendor = vendorList.find(v => v.id_vendor == barang.id_vendor);
                    if (vendor) {
                        kalkulasiData[index].nama_vendor = vendor.nama_vendor;
                    }
                }
                
                // Immediately recalculate and refresh table after reset and update
                calculateRow(index);
                populateKalkulasiTable();
                calculateTotals();
            }
            
            showErrorMessage('Gagal memuat detail barang, menggunakan data dasar');
        }
    } else {
        // Clear data if no barang selected
        resetInputFields(index);
        kalkulasiData[index].id_barang = null;
        kalkulasiData[index].nama_barang = '';
        kalkulasiData[index].id_vendor = null;
        kalkulasiData[index].nama_vendor = '';
        kalkulasiData[index].satuan = '';
        kalkulasiData[index].jenis_vendor = '';
        
        // Immediately recalculate and refresh table after reset and clear
        calculateRow(index);
        populateKalkulasiTable();
        calculateTotals();
    }
}

// Reset semua input fields yang dapat diedit user ketika ganti barang
function resetInputFields(index) {
    console.log('Resetting input fields for item index:', index, '(qty dan pagu dinas tidak direset)');
    
    // TIDAK RESET: qty dan harga_pagu_dinas_per_pcs (akan dipertahankan)
    // Reset field input lainnya
    kalkulasiData[index].harga_vendor = 0;
    kalkulasiData[index].harga_diskon = 0;
    kalkulasiData[index].harga_yang_diharapkan = 0;
    kalkulasiData[index].nilai_sp = 0;
    kalkulasiData[index].ongkir = 0;
    
    // Reset percentage fields (kecuali yang sync global)
    // Percentage yang sync global tidak perlu di-reset karena sama untuk semua item
    
    // Reset keterangan
    kalkulasiData[index].keterangan_1 = '';
    kalkulasiData[index].keterangan_2 = '';
    
    // Reset calculated fields (akan dihitung ulang)
    kalkulasiData[index].nilai_diskon = 0;
    kalkulasiData[index].total_diskon = 0;
    kalkulasiData[index].total_harga = 0;
    kalkulasiData[index].jumlah_volume = 0;
    kalkulasiData[index].persen_kenaikan = 0;
    kalkulasiData[index].proyeksi_kenaikan = 0;
    kalkulasiData[index].ppn_dinas = 0;
    kalkulasiData[index].pph_dinas = 0;
    kalkulasiData[index].hps = 0;
    kalkulasiData[index].harga_per_pcs = 0;
    kalkulasiData[index].pagu_total = 0;
    kalkulasiData[index].selisih_pagu_hps = 0;
    kalkulasiData[index].dpp = 0;
    kalkulasiData[index].asumsi_nilai_cair = 0;
    kalkulasiData[index].omzet_nilai_dinas = 0;
    kalkulasiData[index].gross_nilai_bendera = 0;
    kalkulasiData[index].gross_nilai_bank_cost = 0;
    kalkulasiData[index].gross_nilai_biaya_ops = 0;
    kalkulasiData[index].sub_total_biaya_tidak_langsung = 0;
    kalkulasiData[index].gross_income = 0;
    kalkulasiData[index].gross_income_persentase = 0;
    kalkulasiData[index].nilai_nett_income = 0;
    kalkulasiData[index].nett_income_persentase = 0;
    
    console.log('Input fields reset complete for item index:', index, kalkulasiData[index]);
    
    // Force update UI inputs to reflect reset values
    setTimeout(() => {
        const inputIds = [
            `qty-${index}`, `harga_vendor-${index}`, `harga_diskon-${index}`, 
            `harga_yang_diharapkan-${index}`, `harga_pagu_dinas_per_pcs-${index}`, 
            `nilai_sp-${index}`, `ongkir-${index}`, `keterangan_1-${index}`, `keterangan_2-${index}`
        ];
        
        inputIds.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            if (inputElement) {
                if (inputId.includes('keterangan')) {
                    inputElement.value = '';
                } else {
                    inputElement.value = '0';
                }
            }
        });
    }, 100); // Small delay to ensure DOM is updated
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
    // Handle empty values
    if (value === '' || value === null || value === undefined) {
        // For text fields, allow empty strings
        if (['keterangan_1', 'keterangan_2', 'catatan'].includes(field)) {
            kalkulasiData[index][field] = '';
            return;
        }
        
        kalkulasiData[index][field] = 0;
        
        // Handle percentage sync for empty values
        if (['omzet_dinas_percent', 'bendera_percent', 'bank_cost_percent', 'biaya_ops_percent'].includes(field)) {
            syncPercentageToAllItems(field, 0);
        } else {
            calculateRow(index);
            populateKalkulasiTable();
            calculateTotals();
        }
        return;
    }
    
    // Handle text fields that should not be converted to numbers
    if (['keterangan_1', 'keterangan_2', 'catatan'].includes(field)) {
        kalkulasiData[index][field] = value;
        return; // No calculation needed for text fields
    }
    
    // Convert formatted number (with dots) to actual number
    const numericValue = parseFormattedNumber(value);
    
    // Validasi khusus untuk harga_diskon
    if (field === 'harga_diskon') {
        const hargaVendor = parseFloat(kalkulasiData[index].harga_vendor) || 0;
        
        if (numericValue > hargaVendor && hargaVendor > 0) {
            showErrorMessage('Harga diskon tidak boleh lebih besar dari harga vendor!');
            // Reset ke harga vendor jika lebih besar
            kalkulasiData[index][field] = hargaVendor;
        } else {
            kalkulasiData[index][field] = numericValue;
        }
        calculateRow(index);
        populateKalkulasiTable();
        calculateTotals();
    } 
    // Handle percentage fields that should sync across all items
    else if (['omzet_dinas_percent', 'bendera_percent', 'bank_cost_percent', 'biaya_ops_percent'].includes(field)) {
        syncPercentageToAllItems(field, numericValue);
    } 
    else {
        kalkulasiData[index][field] = numericValue;
        calculateRow(index);
        populateKalkulasiTable();
        calculateTotals();
    }
}

// Function to parse formatted number (remove dots and convert to number)
function parseFormattedNumber(value) {
    if (typeof value === 'number') return value;
    if (typeof value !== 'string') return 0;
    
    // Remove dots and convert to number
    const cleanValue = value.replace(/\./g, '').replace(/,/g, '.');
    const numericValue = parseFloat(cleanValue) || 0;
    
    return numericValue;
}

// Function to format number with thousand separators for input
function formatNumber(number) {
    if (number === null || number === undefined || isNaN(number)) return '';
    if (number === 0) return '';
    
    // Convert to integer if it's a whole number, otherwise keep decimals
    const isWholeNumber = number % 1 === 0;
    const formattedNumber = isWholeNumber ? 
        Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') :
        number.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    return formattedNumber;
}

// Function to sync percentage values to all items
function syncPercentageToAllItems(field, value) {
    // Update all items with the same percentage value
    for (let i = 0; i < kalkulasiData.length; i++) {
        kalkulasiData[i][field] = value;
        calculateRow(i);
    }
    
    // Update the table display
    populateKalkulasiTable();
    calculateTotals();
}

function calculateRow(index) {
    kalkulasiData[index] = window.hpsCalculator.calculateItem(kalkulasiData[index]);
}

function calculateTotals() {
    kalkulasiData = window.hpsCalculator.calculateAll();
    const summary = window.hpsCalculator.getSummary();
    
    // Main summary totals - format with proper zero handling
    document.getElementById('grand-total-hpp').textContent = summary.totalHpp > 0 ? formatRupiah(summary.totalHpp) : '-';
    document.getElementById('grand-total-hps').textContent = summary.totalHps > 0 ? formatRupiah(summary.totalHps) : '-';
    document.getElementById('grand-total-nett').textContent = summary.totalNett > 0 ? formatRupiah(summary.totalNett) : '-';
    document.getElementById('grand-avg-nett').textContent = summary.rataRataNet ? formatPercent(summary.rataRataNet) : '-';
    
    // Additional summary details - format with proper zero handling
    if (document.getElementById('total-items')) {
        document.getElementById('total-items').textContent = (summary.totalItems && summary.totalItems > 0) ? summary.totalItems : '-';
    }
    if (document.getElementById('total-diskon')) {
        document.getElementById('total-diskon').textContent = (summary.totalDiskon && summary.totalDiskon > 0) ? formatRupiah(summary.totalDiskon) : '-';
    }
    if (document.getElementById('total-volume')) {
        document.getElementById('total-volume').textContent = (summary.totalVolume && summary.totalVolume > 0) ? formatRupiah(summary.totalVolume) : '-';
    }
    if (document.getElementById('total-dpp')) {
        document.getElementById('total-dpp').textContent = (summary.totalDpp && summary.totalDpp > 0) ? formatRupiah(summary.totalDpp) : '-';
    }
    if (document.getElementById('total-asumsi-cair')) {
        document.getElementById('total-asumsi-cair').textContent = (summary.totalAsumsiCair && summary.totalAsumsiCair > 0) ? formatRupiah(summary.totalAsumsiCair) : '-';
    }
    if (document.getElementById('total-ongkir')) {
        document.getElementById('total-ongkir').textContent = (summary.totalOngkir && summary.totalOngkir > 0) ? formatRupiah(summary.totalOngkir) : '-';
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

    // --- Batasi nilai persentase agar tidak out of range database ---
    const sanitizedKalkulasi = kalkulasiData.map(item => {
        // Batasi persentase dalam range yang aman untuk database (-999.99 sampai 999.99)
        const safePercentRange = (value) => {
            const numValue = parseFloat(value || 0);
            return Math.max(-999.99, Math.min(999.99, numValue));
        };
        
        const grossIncomePercent = safePercentRange(item.gross_income_percent);
        const nettIncomePercent = safePercentRange(item.nett_income_persentase);
        const persenKenaikan = safePercentRange(item.persen_kenaikan);
        
        return {
            ...item,
            gross_income_percent: grossIncomePercent,
            nett_income_persentase: nettIncomePercent,
            persen_kenaikan: persenKenaikan,
            kenaikan_percent: persenKenaikan
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
    if (amount === null || amount === undefined || isNaN(amount)) return '-';
    if (amount === 0) return '-';
    
    // Format dengan Rupiah dan pemisah ribuan menggunakan titik
    return 'Rp ' + Math.round(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function formatPercent(value) {
    if (value === null || value === undefined || isNaN(value)) return '-';
    if (value === 0) return '-';
    return new Intl.NumberFormat('id-ID', {
        style: 'percent',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value / 100);
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

// Lihat riwayat HPS
async function lihatRiwayatHps() {
    if (!currentProyekId) {
        alert('ID Proyek tidak ditemukan');
        return;
    }

    // Redirect ke halaman riwayat detail
    window.location.href = `{{ route("kalkulasi.riwayat.detail", ":id") }}`.replace(':id', currentProyekId);
}

// Tampilkan modal riwayat
function tampilkanModalRiwayat(riwayatData) {
    let modalHtml = `
        <div id="modal-riwayat" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-semibold">Riwayat Kalkulasi HPS</h3>
                    <button onclick="tutupModalRiwayat()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto max-h-[70vh]">
    `;

    if (riwayatData.length === 0) {
        modalHtml += `
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-history text-4xl mb-4"></i>
                <p>Belum ada riwayat kalkulasi HPS</p>
            </div>
        `;
    } else {
        modalHtml += `
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Tanggal</th>
                            <th class="px-3 py-2 text-left">Barang</th>
                            <th class="px-3 py-2 text-left">Vendor</th>
                            <th class="px-3 py-2 text-left">Qty</th>
                            <th class="px-3 py-2 text-left">Harga Vendor</th>
                            <th class="px-3 py-2 text-left">HPS</th>
                            <th class="px-3 py-2 text-left">Action</th>
                            <th class="px-3 py-2 text-left">User</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        riwayatData.forEach(item => {
            modalHtml += `
                <tr class="border-b">
                    <td class="px-3 py-2">${item.created_at}</td>
                    <td class="px-3 py-2">${item.nama_barang || '-'}</td>
                    <td class="px-3 py-2">${item.nama_vendor || '-'}</td>
                    <td class="px-3 py-2">${item.qty}</td>
                    <td class="px-3 py-2">Rp ${new Intl.NumberFormat('id-ID').format(item.harga_vendor)}</td>
                    <td class="px-3 py-2">Rp ${new Intl.NumberFormat('id-ID').format(item.hps)}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-1 text-xs rounded ${item.action_type === 'edit' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">
                            ${item.action_type}
                        </span>
                    </td>
                    <td class="px-3 py-2">${item.created_by}</td>
                </tr>
            `;
        });

        modalHtml += `
                    </tbody>
                </table>
            </div>
        `;
    }

    modalHtml += `
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

// Tutup modal riwayat
function tutupModalRiwayat() {
    const modal = document.getElementById('modal-riwayat');
    if (modal) {
        modal.remove();
    }
}

// Modifikasi fungsi saveKalkulasi untuk menggunakan endpoint dengan riwayat
async function saveKalkulasiWithHistory() {
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

    // --- Batasi nilai persentase agar tidak out of range database ---
    const sanitizedKalkulasi = kalkulasiData.map(item => {
        // Batasi persentase dalam range yang aman untuk database (-999.99 sampai 999.99)
        const safePercentRange = (value) => {
            const numValue = parseFloat(value || 0);
            return Math.max(-999.99, Math.min(999.99, numValue));
        };
        
        const grossIncomePercent = safePercentRange(item.gross_income_percent);
        const nettIncomePercent = safePercentRange(item.nett_income_persentase);
        const persenKenaikan = safePercentRange(item.persen_kenaikan);
        
        return {
            ...item,
            gross_income_percent: grossIncomePercent,
            nett_income_persentase: nettIncomePercent,
            persen_kenaikan: persenKenaikan,
            kenaikan_percent: persenKenaikan
        };
    });

    try {
        const response = await fetch('{{ route("kalkulasi.save.history") }}', {
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

        const result = await response.json();

        if (result.success) {
            showSuccessMessage('Kalkulasi berhasil disimpan dan riwayat telah diperbarui!');
            // Refresh display
            populateKalkulasiTable();
            calculateTotals();
            const now = new Date().toLocaleString('id-ID');
            if (document.getElementById('last-updated')) {
                document.getElementById('last-updated').textContent = now;
            }
            if (document.getElementById('last-updated-footer')) {
                document.getElementById('last-updated-footer').textContent = now;
            }
        } else {
            alert('Gagal menyimpan kalkulasi: ' + result.message);
        }

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan kalkulasi');
    }
}

// Fungsi untuk searchable dropdown barang
let searchTimeout;
let filteredBarangResults = [];

function searchBarang(index, searchTerm) {
    clearTimeout(searchTimeout);
    const dropdown = document.getElementById(`barang-dropdown-${index}`);
    
    if (searchTerm.length === 0) {
        dropdown.innerHTML = '';
        dropdown.classList.add('hidden');
        return;
    }
    
    if (searchTerm.length < 2) {
        dropdown.innerHTML = '<div class="barang-dropdown-empty">Ketik minimal 2 karakter</div>';
        dropdown.classList.remove('hidden');
        return;
    }
    
    // Show loading indicator
    dropdown.innerHTML = `
        <div class="barang-dropdown-loading">
            <span>Mencari barang</span>
            <span class="barang-search-loading"></span>
        </div>
    `;
    dropdown.classList.remove('hidden');
    
    searchTimeout = setTimeout(() => {
        
        // Filter barang berdasarkan pencarian
        filteredBarangResults = barangList.filter(barang => 
            barang.nama_barang.toLowerCase().includes(searchTerm.toLowerCase())
        );
        
        if (filteredBarangResults.length === 0) {
            dropdown.innerHTML = `
                <div class="barang-dropdown-empty">
                    <div>Tidak ada barang ditemukan</div>
                    <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Coba kata kunci lain</div>
                </div>
            `;
        } else {
            const maxResults = Math.min(filteredBarangResults.length, 10); // Batasi maksimal 10 hasil
            dropdown.innerHTML = filteredBarangResults.slice(0, maxResults).map((barang, itemIndex) => 
                `<div class="barang-dropdown-item" 
                     onmousedown="event.preventDefault(); selectBarang(${index}, '${barang.id_barang}', '${barang.nama_barang.replace(/'/g, "\\'")}');"
                     onmouseenter="highlightItem(this)"
                     onmouseleave="unhighlightItem(this)"
                     data-item-index="${itemIndex}">
                    <div class="flex items-center gap-3">
                        <div class="item-icon">
                            <i class="fas fa-cube text-gray-400"></i>
                        </div>
                        <span class="block truncate flex-1">${barang.nama_barang}</span>
                        <div class="item-arrow">
                            <i class="fas fa-chevron-right text-gray-300"></i>
                        </div>
                    </div>
                 </div>`
            ).join('');
            
            if (filteredBarangResults.length > 10) {
                dropdown.innerHTML += '<div class="barang-dropdown-summary">Menampilkan 10 dari ' + filteredBarangResults.length + ' hasil</div>';
            }
        }
        
        // Use smooth entrance animation
        animateDropdownEntrance(dropdown);
    }, 300); // Debounce 300ms
}

function selectBarang(index, barangId, namaBarang) {
    const input = document.getElementById(`barang-search-${index}`);
    const dropdown = document.getElementById(`barang-dropdown-${index}`);
    
    console.log('Selecting barang:', namaBarang, 'for index:', index);
    
    input.value = namaBarang;
    dropdown.classList.add('hidden');
    
    // Remove active class from all items
    dropdown.querySelectorAll('.barang-dropdown-item.active').forEach(item => {
        item.classList.remove('active');
    });
    
    // Update data barang - ini akan trigger reset input fields
    updateBarang(index, barangId);
}

function clearBarangSelection(index) {
    const input = document.getElementById(`barang-search-${index}`);
    const dropdown = document.getElementById(`barang-dropdown-${index}`);
    
    input.value = '';
    dropdown.classList.add('hidden');
    
    // Clear barang data
    kalkulasiData[index].id_barang = null;
    kalkulasiData[index].nama_barang = '';
    kalkulasiData[index].satuan = '';
    kalkulasiData[index].harga_vendor = 0;
    
    // Refresh table
    populateKalkulasiTable();
}

// Helper functions for item highlighting
function highlightItem(element) {
    // Remove active class from siblings
    const siblings = element.parentElement.querySelectorAll('.barang-dropdown-item');
    siblings.forEach(item => {
        item.classList.remove('active');
        item.style.animationDelay = '0s'; // Reset animation delay
    });
    
    // Add active class to current item
    element.classList.add('active');
    
    // Add special hover effect
    element.style.animationDelay = '0s';
    element.style.transform = 'translateX(4px) scale(1.02)';
    
    // Optional: Add subtle haptic feedback for mobile devices
    if ('vibrate' in navigator) {
        navigator.vibrate(10); // Very short vibration
    }
}

function unhighlightItem(element) {
    if (!element.classList.contains('active')) {
        element.style.transform = '';
    }
}

// Add smooth entrance animation when dropdown appears
function animateDropdownEntrance(dropdown) {
    dropdown.style.opacity = '0';
    dropdown.style.transform = 'translateY(-10px)';
    dropdown.classList.remove('hidden');
    
    // Trigger animation
    requestAnimationFrame(() => {
        dropdown.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        dropdown.style.opacity = '1';
        dropdown.style.transform = 'translateY(0)';
    });
}

function showBarangDropdown(index) {
    const input = document.getElementById(`barang-search-${index}`);
    const searchTerm = input.value;
    
    if (searchTerm.length >= 2) {
        searchBarang(index, searchTerm);
    }
}

function hideBarangDropdown(index) {
    // Delay yang lebih lama untuk memungkinkan click pada dropdown item
    setTimeout(() => {
        const dropdown = document.getElementById(`barang-dropdown-${index}`);
        if (dropdown) {
            dropdown.classList.add('hidden');
        }
    }, 300);
}

// Tutup dropdown ketika click di luar area
document.addEventListener('click', function(event) {
    // Cari semua dropdown yang terbuka dan tutup jika click di luar
    const dropdowns = document.querySelectorAll('[id^="barang-dropdown-"]');
    dropdowns.forEach(dropdown => {
        const container = dropdown.closest('.barang-search-container');
        // Jangan tutup jika click pada dropdown item
        if (container && !container.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
});

// Prevent dropdown from closing when clicking inside dropdown
document.addEventListener('mousedown', function(event) {
    if (event.target.closest('.barang-dropdown')) {
        event.preventDefault();
    }
});

// Keyboard navigation untuk dropdown
document.addEventListener('keydown', function(event) {
    if (event.target.id && event.target.id.startsWith('barang-search-')) {
        const index = event.target.id.split('-')[2];
        const dropdown = document.getElementById(`barang-dropdown-${index}`);
        
        if (event.key === 'Escape') {
            dropdown.classList.add('hidden');
            event.target.blur();
        } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            const items = dropdown.querySelectorAll('.barang-dropdown-item');
            if (items.length > 0) {
                const currentActive = dropdown.querySelector('.barang-dropdown-item.active');
                let nextIndex = 0;
                
                if (currentActive) {
                    currentActive.classList.remove('active');
                    const currentIndex = Array.from(items).indexOf(currentActive);
                    if (event.key === 'ArrowDown') {
                        nextIndex = (currentIndex + 1) % items.length;
                    } else {
                        nextIndex = (currentIndex - 1 + items.length) % items.length;
                    }
                }
                
                items[nextIndex].classList.add('active');
                items[nextIndex].scrollIntoView({ block: 'nearest' });
            }
        } else if (event.key === 'Enter') {
            event.preventDefault();
            const activeItem = dropdown.querySelector('.barang-dropdown-item.active');
            if (activeItem) {
                activeItem.click();
            }
        }
    }
});
</script>
@endpush
