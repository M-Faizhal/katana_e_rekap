@extends('layouts.app')

<!--
===========================================
SISTEM KALKULASI HPS MULTI-ITEM SUPPORT
===========================================

FITUR UTAMA:
1. Support multi-item permintaan klien
2. Auto-matching vendor item dengan client request
3. Perhitungan nett percent per row berdasarkan permintaan klien yang sesuai
4. Total calculation yang akurat untuk multiple items

FORMAT MULTI-ITEM PERMINTAAN KLIEN:
- Dapat didefinisikan di field 'deskripsi' proyek dengan format:
  "NamaBarang1|Qty1|HargaSatuan1;NamaBarang2|Qty2|HargaSatuan2"
  
CONTOH:
"Laptop Dell|5|85000000;Monitor 24 inch|10|15000000;Printer HP|3|8000000"

ALGORITMA MATCHING:
1. Exact match (nama vendor item = nama client request)
2. Partial match (vendor contains client atau sebaliknya)
3. Keyword matching (cocokkan kata kunci)
4. Default fallback (jika hanya 1 client request)

RUMUS PERHITUNGAN PER ROW:
- HPP Total = (harga_vendor × qty) - total_diskon
- HPS Total = HPP + proyeksi_kenaikan + PPH + PPN + ongkir
- Nett Total = HPS - biaya_operasional
- Nett % = (Nett Total / Harga Permintaan Client Total) × 100

RUMUS TOTAL KESELURUHAN:
- Total Nett % = (Sum of All Nett / Sum of All Client Requests) × 100
===========================================
-->

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-lg md:rounded-xl lg:rounded-2xl p-3 sm:p-4 md:p-6 lg:p-8 mb-4 sm:mb-6 lg:mb-8 text-white shadow-lg mt-2 sm:mt-4">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Kalkulasi Purchasing</h1>
            <p class="text-red-100 text-xs sm:text-sm md:text-base lg:text-lg">Hitung dan analisis biaya pengadaan</p>
        </div>
        <div class="hidden sm:flex items-center justify-center">
            <i class="fas fa-calculator text-2xl sm:text-3xl md:text-4xl lg:text-6xl opacity-80"></i>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
    <form method="GET" action="{{ route('purchasing.kalkulasi') }}" class="space-y-3 sm:space-y-0 sm:flex sm:gap-3 md:gap-4 sm:items-center sm:justify-between">
        <div class="flex flex-col sm:flex-row gap-3 md:gap-4 w-full sm:w-auto">
            <div class="relative">
                <select name="status" class="appearance-none bg-white border border-gray-300 rounded-lg px-3 sm:px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 w-full sm:w-auto text-sm">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ request('status')=='Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Penawaran" {{ request('status')=='Penawaran' ? 'selected' : '' }}>Penawaran</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none text-xs"></i>
            </div>
            <div class="relative flex-1 sm:flex-none">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari proyek..." 
                       class="border border-gray-300 rounded-lg px-3 sm:px-4 py-2 pl-9 sm:pl-10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 w-full sm:w-48 md:w-64 text-sm">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
            </div>
        </div>
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm w-full sm:w-auto">
            <i class="fas fa-filter mr-1 sm:mr-2"></i>
            Filter
        </button>
    </form>
</div>

<!-- Projects Table -->
<div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200">
        <h2 class="text-base sm:text-lg font-semibold text-gray-800">Daftar Proyek</h2>
        <p class="text-xs sm:text-sm text-gray-600 mt-1">Klik proyek untuk melakukan kalkulasi HPS</p>
    </div>
    
    <!-- Desktop Table View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Proyek</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($proyek as $p)
                <tr class="hover:bg-gray-50 cursor-pointer" 
                    onclick="openHpsModal({{ $p->id_proyek }})">
                    
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                    
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $p->nama_barang }}</div>
                        <div class="text-sm text-gray-500">PRJ{{ str_pad($p->id_proyek, 3, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $p->nama_klien }}</div>
                        <div class="text-sm text-gray-500">{{ $p->instansi }}</div>
                    </td>
                    
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                    </td>
                    
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $p->status == 'Menunggu' ? 'bg-yellow-100 text-yellow-800' : 
                               ($p->status == 'Penawaran' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($p->jumlah) }} {{ $p->satuan }}</div>
                        <div class="text-sm text-gray-500">{{ 'Rp ' . number_format($p->harga_total, 0, ',', '.') }}</div>
                    </td>
                    
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="event.stopPropagation(); openHpsModal({{ $p->id_proyek }})" 
                                class="text-red-600 hover:text-red-900 mr-3"
                                title="Buka Kalkulasi HPS">
                            <i class="fas fa-calculator"></i> Kalkulasi
                        </button>
                        
                        @if($p->status == 'Menunggu')
                        <button onclick="event.stopPropagation(); createPenawaranAction({{ $p->id_proyek }})" 
                                class="text-green-600 hover:text-green-900"
                                title="Buat Penawaran">
                            <i class="fas fa-file-contract"></i> Penawaran
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">Tidak ada proyek menunggu kalkulasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile & Tablet Card View -->
    <div class="lg:hidden">
        @forelse($proyek as $p)
        <div class="border-b border-gray-200 p-3 sm:p-4 hover:bg-gray-50 cursor-pointer" 
             onclick="openHpsModal({{ $p->id_proyek }})">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                    <h3 class="text-sm sm:text-base font-medium text-gray-900 line-clamp-2">{{ $p->nama_barang }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">PRJ{{ str_pad($p->id_proyek, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-3 shrink-0
                    {{ $p->status == 'Menunggu' ? 'bg-yellow-100 text-yellow-800' : 
                       ($p->status == 'Penawaran' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                    {{ $p->status }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                <div>
                    <p class="text-xs text-gray-500">Klien</p>
                    <p class="text-sm font-medium text-gray-900">{{ $p->nama_klien }}</p>
                    <p class="text-xs text-gray-500">{{ $p->instansi }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal</p>
                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</p>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Jumlah & Total</span>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ number_format($p->jumlah) }} {{ $p->satuan }}</p>
                        <p class="text-sm font-bold text-red-600">{{ 'Rp ' . number_format($p->harga_total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-2 pt-2 border-t border-gray-100">
                <button onclick="event.stopPropagation(); openHpsModal({{ $p->id_proyek }})" 
                        class="flex-1 bg-red-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-calculator mr-1"></i> Kalkulasi
                </button>
                
                @if($p->status == 'Menunggu')
                <button onclick="event.stopPropagation(); createPenawaranAction({{ $p->id_proyek }})" 
                        class="flex-1 bg-green-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-200">
                    <i class="fas fa-file-contract mr-1"></i> Penawaran
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
            <p class="text-sm">Tidak ada proyek menunggu kalkulasi</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="bg-white px-3 sm:px-4 md:px-6 py-3 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-xs sm:text-sm text-gray-700 order-2 sm:order-1">
                Menampilkan {{ $proyek->firstItem() ?? 0 }} - {{ $proyek->lastItem() ?? 0 }} dari {{ $proyek->total() }} proyek
            </div>
            <div class="order-1 sm:order-2">
                {{ $proyek->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Include HPS Modal -->
@include('pages.purchasing.kalkulasi-components.hps')

@endsection

@push('scripts')
<script>
let currentProyekId = null;
let currentProject = null;
let barangList = [];
let vendorList = [];
let kalkulasiData = [];

// Format currency to Indonesian Rupiah
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount || 0);
}

// Format number with thousand separator
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number || 0);
}

// Format input as user types (for display purposes)
function formatInputRupiah(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
        input.value = formatNumber(value);
    }
}

// Get numeric value from formatted input
function getNumericValue(formattedValue) {
    return parseFloat(formattedValue.replace(/[^\d]/g, '')) || 0;
}

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadBarangList();
    loadVendorList();
});

// Load barang list
async function loadBarangList() {
    try {
        const response = await fetch('/purchasing/kalkulasi/barang');
        barangList = await response.json();
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

// Show loading indicator
function showLoading(message = 'Memuat data...') {
    const loadingHtml = `
        <div class="loading-overlay">
            <div class="flex flex-col items-center">
                <div class="loading-spinner"></div>
                <p class="mt-3 text-sm text-gray-600">${message}</p>
            </div>
        </div>
    `;
    
    const modalContent = document.getElementById('hps-modal-content');
    if (modalContent) {
        modalContent.style.position = 'relative';
        modalContent.insertAdjacentHTML('beforeend', loadingHtml);
    }
}

// Hide loading indicator
function hideLoading() {
    const loadingOverlay = document.querySelector('.loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

// Enhanced open modal with loading
async function openHpsModal(proyekId) {
    currentProyekId = proyekId;
    
    // Show modal immediately
    document.getElementById('hps-modal').classList.remove('hidden');
    showLoading('Memuat data proyek...');
    
    try {
        const response = await fetch(`/purchasing/kalkulasi/proyek/${proyekId}`);
        const data = await response.json();
        
        if (data.success) {
            hideLoading();
            populateModalData(data.proyek, data.kalkulasi);
            
            // Optimize table display for current screen size
            setTimeout(() => {
                optimizeTableDisplay();
                optimizeInputFields();
            }, 100);
        } else {
            hideLoading();
            alert('Gagal memuat data proyek');
            closeHpsModal();
        }
    } catch (error) {
        console.error('Error:', error);
        hideLoading();
        alert('Terjadi kesalahan saat memuat data');
        closeHpsModal();
    }
}

// Close HPS Modal
function closeHpsModal() {
    document.getElementById('hps-modal').classList.add('hidden');
    currentProyekId = null;
    currentProject = null;
}

// Populate modal with project data
function populateModalData(proyek, kalkulasi) {
    // Store current project data
    currentProject = proyek;
    
    // Set header info
    document.getElementById('modal-project-name').textContent = proyek.nama_barang;
    document.getElementById('modal-project-id').textContent = `PRJ${String(proyek.id_proyek).padStart(3, '0')}`;
    document.getElementById('modal-client-name').textContent = proyek.nama_klien;
    
    // Populate client request section
    populateClientRequest(proyek);
    
    // Populate kalkulasi table
    kalkulasiData = kalkulasi || [];
    // Recalculate all nett percent after loading data
    recalculateAllNettPercent();
    populateKalkulasiTable();
    
    // Calculate totals
    calculateTotals();
}

// Additional functions for kalkulasi operations
// Populate client request section
function populateClientRequest(proyek) {
    // Parse client requests - supports multiple items from project description
    const clientRequests = parseClientRequestsFromProject(proyek);
    
    // Store client requests globally for calculation purposes
    window.clientRequests = clientRequests;
    
    const tbody = document.getElementById('client-request-table');
    tbody.innerHTML = clientRequests.map((request, index) => `
        <tr>
            <td class="px-3 py-2 text-sm text-blue-800">${index + 1}</td>
            <td class="px-3 py-2 text-sm text-blue-800">${request.nama_barang}</td>
            <td class="px-3 py-2 text-sm text-blue-800">${formatNumber(request.qty)}</td>
            <td class="px-3 py-2 text-sm text-blue-800">${request.satuan}</td>
            <td class="px-3 py-2 text-sm text-blue-800">${formatRupiah(request.harga_satuan)}</td>
            <td class="px-3 py-2 text-sm font-bold text-blue-800">${formatRupiah(request.harga_total)}</td>
            <td class="px-3 py-2">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    Permintaan
                </span>
            </td>
        </tr>
    `).join('');
    
    // Calculate total client requests
    const totalClient = clientRequests.reduce((sum, request) => sum + parseFloat(request.harga_total), 0);
    document.getElementById('grand-total-client').textContent = formatRupiah(totalClient);
}

// Add vendor item
function addVendorItem() {
    const newItem = {
        id_barang: '',
        id_vendor: '',
        qty: 1,
        harga_vendor: 0,
        diskon_amount: 0,
        total_diskon: 0,
        total_harga_hpp: 0,
        kenaikan_percent: 0,
        proyeksi_kenaikan: 0,
        pph: 0,
        ppn: 0,
        ongkir: 0,
        hps: 0,
        bank_cost: 0,
        biaya_ops: 0,
        bendera: 0,
        nett: 0,
        nett_percent: 0,
        catatan: ''
    };
    
    kalkulasiData.push(newItem);
    // Recalculate all nett percent after adding item
    recalculateAllNettPercent();
    populateKalkulasiTable();
    calculateTotals();
}

// Optimize input fields for mobile
function optimizeInputFields() {
    const inputs = document.querySelectorAll('.hps-table input, .hps-table select');
    
    if (isMobile()) {
        inputs.forEach(input => {
            input.style.fontSize = '14px'; // Larger text for mobile
            input.style.padding = '8px'; // More padding for easier touch
        });
    } else {
        inputs.forEach(input => {
            input.style.fontSize = '';
            input.style.padding = '';
        });
    }
}

// Enhanced populate kalkulasi table with responsive optimizations
function populateKalkulasiTable() {
    const tbody = document.getElementById('kalkulasi-table-body');
    
    if (kalkulasiData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="21" class="text-center py-6 text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-plus-circle text-3xl mb-2 opacity-50"></i>
                        <p class="text-sm">Belum ada data vendor</p>
                        <p class="text-xs text-gray-400 mt-1">Klik "Tambah Item Vendor" untuk memulai</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = kalkulasiData.map((item, index) => createKalkulasiRow(item, index)).join('');
    
    // Apply responsive optimizations
    setTimeout(() => {
        optimizeTableDisplay();
        optimizeInputFields();
    }, 100);
}

// Create kalkulasi row
function createKalkulasiRow(item, index) {
    const barangOptions = barangList.map(b => 
        `<option value="${b.id_barang}" ${b.id_barang == item.id_barang ? 'selected' : ''}>${b.nama_barang}</option>`
    ).join('');
    
    const selectedBarang = barangList.find(b => b.id_barang == item.id_barang);
    const satuan = selectedBarang ? selectedBarang.satuan : '';
    const vendorName = selectedBarang && selectedBarang.vendor ? selectedBarang.vendor.nama_vendor : '';
    
    return `
        <tr class="hover:bg-gray-50 transition-colors duration-150">
            <td class="px-2 py-3 text-sm text-gray-900 border-r border-gray-200 text-center">${index + 1}</td>
            
            <td class="px-3 py-3 border-r border-gray-200">
                <select onchange="updateBarang(${index}, this.value)" class="w-full border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Pilih Barang</option>
                    ${barangOptions}
                </select>
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200">
                <input type="text" readonly value="${vendorName}" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-xs bg-gray-50 text-gray-600"
                       placeholder="Vendor otomatis terisi">
            </td>
            
            <td class="px-2 py-3 border-r border-gray-200">
                <input type="number" value="${item.qty}" onchange="updateField(${index}, 'qty', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-xs no-spin focus:outline-none focus:ring-2 focus:ring-red-500 text-center">
            </td>
            
            <td class="px-2 py-3 border-r border-gray-200 text-xs text-gray-600 text-center">${satuan}</td>
            
            <td class="px-3 py-3 border-r border-gray-200">
                <input type="text" value="${item.harga_vendor > 0 ? formatNumber(item.harga_vendor) : ''}" 
                       oninput="formatInputRupiah(this)" 
                       onchange="updateFieldFormatted(${index}, 'harga_vendor', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-red-500 text-right"
                       placeholder="0">
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200">
                <input type="text" value="${item.diskon_amount > 0 ? formatNumber(item.diskon_amount) : ''}" 
                       oninput="formatInputRupiah(this)" 
                       onchange="updateFieldFormatted(${index}, 'diskon_amount', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-red-500 text-right"
                       placeholder="Diskon">
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200 text-xs font-medium text-display text-right">
                ${formatRupiah(item.total_diskon)}
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200 bg-yellow-50 text-xs font-bold text-display text-right">
                ${formatRupiah(item.total_harga_hpp)}
            </td>
            
            <td class="px-2 py-3 border-r border-gray-200">
                <input type="number" value="${item.kenaikan_percent}" onchange="updateField(${index}, 'kenaikan_percent', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-xs no-spin focus:outline-none focus:ring-2 focus:ring-red-500 text-center" 
                       step="0.1" placeholder="0">
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200 text-xs font-medium text-display text-right">
                ${formatRupiah(item.proyeksi_kenaikan)}
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200 text-xs font-medium text-display text-right">
                ${formatRupiah(item.pph)}
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200 text-xs font-medium text-display text-right">
                ${formatRupiah(item.ppn)}
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200">
                <input type="text" value="${item.ongkir > 0 ? formatNumber(item.ongkir) : ''}" 
                       oninput="formatInputRupiah(this)" 
                       onchange="updateFieldFormatted(${index}, 'ongkir', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-red-500 text-right"
                       placeholder="Ongkir">
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200 bg-blue-50 text-xs font-bold text-display text-right">
                ${formatRupiah(item.hps)}
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200">
                <input type="text" value="${item.bank_cost > 0 ? formatNumber(item.bank_cost) : ''}" 
                       oninput="formatInputRupiah(this)" 
                       onchange="updateFieldFormatted(${index}, 'bank_cost', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-red-500 text-right"
                       placeholder="Bank">
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200">
                <input type="text" value="${item.biaya_ops > 0 ? formatNumber(item.biaya_ops) : ''}" 
                       oninput="formatInputRupiah(this)" 
                       onchange="updateFieldFormatted(${index}, 'biaya_ops', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-red-500 text-right"
                       placeholder="Ops">
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200">
                <input type="text" value="${item.bendera > 0 ? formatNumber(item.bendera) : ''}" 
                       oninput="formatInputRupiah(this)" 
                       onchange="updateFieldFormatted(${index}, 'bendera', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-red-500 text-right"
                       placeholder="Bendera">
            </td>
            
            <td class="px-3 py-3 border-r border-gray-200 bg-green-50 text-xs font-bold text-display text-right">
                ${formatRupiah(item.nett)}
            </td>
            
            <td class="px-2 py-3 border-r border-gray-200 text-xs font-medium text-center">
                ${(parseFloat(item.nett_percent) || 0).toFixed(1)}%
            </td>
            
            <td class="px-2 py-3 text-center">
                <button onclick="removeItem(${index})" class="text-red-600 hover:text-red-800 hover:bg-red-50 p-1 rounded transition-colors duration-150" title="Hapus Item">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </td>
        </tr>
    `;
}

// Update field values and recalculate
function updateField(index, field, value) {
    kalkulasiData[index][field] = parseFloat(value) || 0;
    calculateRow(index);
    // Recalculate all nett percent since they depend on total permintaan klien
    recalculateAllNettPercent();
    populateKalkulasiTable();
    calculateTotals();
}

// Update field values from formatted input and recalculate
function updateFieldFormatted(index, field, formattedValue) {
    const numericValue = getNumericValue(formattedValue);
    kalkulasiData[index][field] = numericValue;
    calculateRow(index);
    // Recalculate all nett percent since they depend on total permintaan klien
    recalculateAllNettPercent();
    populateKalkulasiTable();
    calculateTotals();
}

// Update barang selection
function updateBarang(index, barangId) {
    const selectedBarang = barangList.find(b => b.id_barang == barangId);
    
    if (selectedBarang) {
        // Auto-fill vendor and price from selected barang
        kalkulasiData[index].id_barang = barangId;
        kalkulasiData[index].id_vendor = selectedBarang.id_vendor;
        kalkulasiData[index].harga_vendor = parseFloat(selectedBarang.harga_vendor) || 0;
        
        // Recalculate based on new price
        calculateRow(index);
    } else {
        kalkulasiData[index].id_barang = barangId;
        kalkulasiData[index].id_vendor = '';
        kalkulasiData[index].harga_vendor = 0;
    }
    
    // Recalculate all nett percent since they depend on total permintaan klien
    recalculateAllNettPercent();
    populateKalkulasiTable();
    calculateTotals();
}

// Update vendor selection
function updateVendor(index, vendorId) {
    kalkulasiData[index].id_vendor = vendorId;
    populateKalkulasiTable();
}

// Calculate row values
// Get total permintaan klien from current project (used for display reference)
function getTotalPermintaanKlien() {
    return currentProject ? parseFloat(currentProject.harga_total) || 0 : 0;
}

// Recalculate all nett percent based on individual row calculations
function recalculateAllNettPercent() {
    kalkulasiData.forEach((item, index) => {
        // Ensure all values are numbers and not NaN
        const qty = parseFloat(item.qty) || 0;
        const hargaSatuan = currentProject ? parseFloat(currentProject.harga_satuan) || 0 : 0;
        const nett = parseFloat(item.nett) || 0;
        
        // Recalculate nett percent using the matched client request
        const clientRequest = getClientRequestForVendorItem(item);
        let hargaPermintaanTotal = 0;
        
        if (clientRequest) {
            const clientQty = parseFloat(clientRequest.qty) || 0;
            const clientHargaSatuan = parseFloat(clientRequest.harga_satuan) || 0;
            hargaPermintaanTotal = clientQty * clientHargaSatuan;
        } else {
            // Fallback to current project data
            hargaPermintaanTotal = qty * hargaSatuan;
        }
        
        item.nett_percent = hargaPermintaanTotal > 0 ? (nett / hargaPermintaanTotal) * 100 : 0;
        
        // Ensure nett_percent is a valid number
        if (isNaN(item.nett_percent) || !isFinite(item.nett_percent)) {
            item.nett_percent = 0;
        }
    });
}

function calculateRow(index) {
    const item = kalkulasiData[index];

    // Ensure all input values are valid numbers
    const qty = parseFloat(item.qty) || 0;
    const hargaVendor = parseFloat(item.harga_vendor) || 0;
    const diskonAmount = parseFloat(item.diskon_amount) || 0;
    const kenaikanPercent = parseFloat(item.kenaikan_percent) || 0;
    const ongkir = parseFloat(item.ongkir) || 0;
    const bankCost = parseFloat(item.bank_cost) || 0;
    const biayaOps = parseFloat(item.biaya_ops) || 0;
    const bendera = parseFloat(item.bendera) || 0;

    // 1. Total diskon dalam rupiah
    item.total_diskon = diskonAmount * qty;

    // 2. Total harga setelah diskon = (harga_vendor * qty) - total_diskon
    const totalHargaVendor = hargaVendor * qty;
    const totalSetelahDiskon = totalHargaVendor - item.total_diskon;

    // 3. Total HPP = total setelah diskon (TOTAL untuk semua qty, bukan per unit)
    item.total_harga_hpp = totalSetelahDiskon;

    // 4. Proyeksi kenaikan = total_hpp * (kenaikan_percent / 100)
    item.proyeksi_kenaikan = item.total_harga_hpp * (kenaikanPercent / 100);

    // 5. Total setelah kenaikan = hpp + proyeksi kenaikan
    const totalSetelahKenaikan = item.total_harga_hpp + item.proyeksi_kenaikan;

    // 6. PPH 1.5% dari total setelah kenaikan
    item.pph = totalSetelahKenaikan * 0.015;

    // 7. PPN 11% dari total setelah kenaikan
    item.ppn = totalSetelahKenaikan * 0.11;

    // 8. HPS = total setelah kenaikan + PPH + PPN + ongkir (TOTAL untuk semua qty)
    item.hps = totalSetelahKenaikan + item.pph + item.ppn + ongkir;

    // 9. Nett = HPS - biaya operasional (untuk semua qty)
    const totalBiayaOps = (bankCost + biayaOps + bendera) * qty;
    item.nett = item.hps - totalBiayaOps;

    // 10. Nett percent per row = (nett TOTAL / harga permintaan TOTAL untuk barang yang sesuai) * 100
    const clientRequest = getClientRequestForVendorItem(item);
    let hargaPermintaanTotal = 0;
    
    if (clientRequest) {
        // Use the specific client request data for this vendor item
        const clientQty = parseFloat(clientRequest.qty) || 0;
        const clientHargaSatuan = parseFloat(clientRequest.harga_satuan) || 0;
        hargaPermintaanTotal = clientQty * clientHargaSatuan;
        
        // Store the matched client request info for display/debug
        item.matched_client_request = {
            nama_barang: clientRequest.nama_barang,
            qty: clientQty,
            harga_satuan: clientHargaSatuan,
            harga_total: hargaPermintaanTotal
        };
    } else {
        // Fallback to current project data (backward compatibility)
        const hargaSatuan = currentProject ? parseFloat(currentProject.harga_satuan) || 0 : 0;
        hargaPermintaanTotal = qty * hargaSatuan;
        
        item.matched_client_request = {
            nama_barang: currentProject ? currentProject.nama_barang : '',
            qty: qty,
            harga_satuan: hargaSatuan,
            harga_total: hargaPermintaanTotal
        };
    }
    
    item.nett_percent = hargaPermintaanTotal > 0 ? (item.nett / hargaPermintaanTotal) * 100 : 0;
    
    // Ensure all calculated values are valid numbers
    ['total_diskon', 'total_harga_hpp', 'proyeksi_kenaikan', 'pph', 'ppn', 'hps', 'nett', 'nett_percent'].forEach(field => {
        if (isNaN(item[field]) || !isFinite(item[field])) {
            item[field] = 0;
        }
    });
}

// Debug function untuk troubleshooting
function debugCalculation(index) {
    if (index !== undefined && kalkulasiData[index]) {
        const item = kalkulasiData[index];
        console.log('=== DEBUG CALCULATION ROW', index + 1, '===');
        console.log('Input Values:');
        console.log('- qty:', item.qty, typeof item.qty);
        console.log('- harga_vendor:', item.harga_vendor, typeof item.harga_vendor);
        console.log('- diskon_amount:', item.diskon_amount, typeof item.diskon_amount);
        console.log('- currentProject harga_satuan:', currentProject?.harga_satuan, typeof currentProject?.harga_satuan);
        
        const qty = parseFloat(item.qty) || 0;
        const hargaSatuan = currentProject ? parseFloat(currentProject.harga_satuan) || 0 : 0;
        const hargaPermintaan = qty * hargaSatuan;
        const nett = parseFloat(item.nett) || 0;
        const nettPercent = hargaPermintaan > 0 ? (nett / hargaPermintaan) * 100 : 0;
        
        console.log('Calculations:');
        console.log('- harga_permintaan:', hargaPermintaan);
        console.log('- nett:', nett);
        console.log('- nett_percent:', nettPercent);
        console.log('=====================================');
    } else {
        console.log('=== DEBUG ALL CALCULATIONS ===');
        console.log('currentProject:', currentProject);
        console.log('kalkulasiData:', kalkulasiData);
        kalkulasiData.forEach((item, idx) => debugCalculation(idx));
    }
}

function calculateTotals() {
    const totalHpp = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.total_harga_hpp) || 0), 0);
    const totalHps = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.hps) || 0), 0);
    const totalNett = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.nett) || 0), 0);
    
    // Calculate average nett percent based on total nett vs total permintaan per matched client request
    const totalPermintaanPerItem = kalkulasiData.reduce((sum, item) => {
        const clientRequest = getClientRequestForVendorItem(item);
        let hargaPermintaanTotal = 0;
        
        if (clientRequest) {
            const clientQty = parseFloat(clientRequest.qty) || 0;
            const clientHargaSatuan = parseFloat(clientRequest.harga_satuan) || 0;
            hargaPermintaanTotal = clientQty * clientHargaSatuan;
        } else {
            // Fallback to current project data
            const qty = parseFloat(item.qty) || 0;
            const hargaSatuan = currentProject ? parseFloat(currentProject.harga_satuan) || 0 : 0;
            hargaPermintaanTotal = qty * hargaSatuan;
        }
        
        return sum + hargaPermintaanTotal;
    }, 0);
    const avgNett = totalPermintaanPerItem > 0 ? (totalNett / totalPermintaanPerItem) * 100 : 0;
    
    // Ensure avgNett is a valid number
    const safeAvgNett = isNaN(avgNett) || !isFinite(avgNett) ? 0 : avgNett;
    
    // Update main totals
    document.getElementById('grand-total-hpp').textContent = formatRupiah(totalHpp);
    document.getElementById('grand-total-hps').textContent = formatRupiah(totalHps);
    document.getElementById('grand-total-nett').textContent = formatRupiah(totalNett);
    document.getElementById('grand-avg-nett').textContent = `${safeAvgNett.toFixed(1)}%`;
    
    // Update breakdown
    const totalDiskon = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.total_diskon) || 0), 0);
    const totalPph = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.pph) || 0), 0);
    const totalPpn = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.ppn) || 0), 0);
    const totalOngkir = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.ongkir) || 0), 0);
    const totalBank = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.bank_cost) || 0), 0);
    const totalOps = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.biaya_ops) || 0), 0);
    const totalBendera = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.bendera) || 0), 0);
    
    if (document.getElementById('breakdown-diskon')) {
        document.getElementById('breakdown-diskon').textContent = formatRupiah(totalDiskon);
        document.getElementById('breakdown-pph').textContent = formatRupiah(totalPph);
        document.getElementById('breakdown-ppn').textContent = formatRupiah(totalPpn);
        document.getElementById('breakdown-ongkir').textContent = formatRupiah(totalOngkir);
        document.getElementById('breakdown-bank').textContent = formatRupiah(totalBank);
        document.getElementById('breakdown-ops').textContent = formatRupiah(totalOps);
        document.getElementById('breakdown-bendera').textContent = formatRupiah(totalBendera);
        document.getElementById('breakdown-total-biaya').textContent = formatRupiah(totalBank + totalOps + totalBendera);
    }
}

// Responsive utilities
function isMobile() {
    return window.innerWidth < 640;
}

function isTablet() {
    return window.innerWidth >= 640 && window.innerWidth < 1024;
}

function isDesktop() {
    return window.innerWidth >= 1024;
}

// Optimize table display based on screen size
function optimizeTableDisplay() {
    const table = document.querySelector('.hps-table');
    if (!table) return;
    
    if (isMobile()) {
        // Hide less critical columns on mobile
        table.classList.add('mobile-optimized');
    } else if (isTablet()) {
        table.classList.add('tablet-optimized');
        table.classList.remove('mobile-optimized');
    } else {
        table.classList.remove('mobile-optimized', 'tablet-optimized');
    }
}

// Handle window resize
window.addEventListener('resize', function() {
    optimizeTableDisplay();
});

// Recalculate all rows
function recalculateAll() {
    kalkulasiData.forEach((item, index) => {
        calculateRow(index);
    });
    populateKalkulasiTable();
    calculateTotals();
}

// Clear vendor data
function clearVendorData() {
    if (confirm('Apakah Anda yakin ingin menghapus semua data vendor?')) {
        kalkulasiData = [];
        populateKalkulasiTable();
        calculateTotals();
    }
}

// Create penawaran (from modal)
function createPenawaran() {
    if (!currentProyekId) {
        alert('ID Proyek tidak ditemukan');
        return;
    }
    
    createPenawaranAction(currentProyekId);
}

// Create penawaran action
async function createPenawaranAction(proyekId) {
    if (!confirm('Apakah Anda yakin ingin mengubah status proyek menjadi Penawaran?')) {
        return;
    }
    
    try {
        const response = await fetch('/purchasing/kalkulasi/penawaran', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id_proyek: proyekId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Status proyek berhasil diubah menjadi Penawaran');
            closeHpsModal();
            location.reload(); // Refresh halaman
        } else {
            alert(data.message || 'Gagal mengubah status proyek');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah status proyek');
    }
}

// Remove vendor selection (not used anymore since vendor is auto-filled)
function updateVendor(index, vendorId) {
    // This function is kept for compatibility but vendor is auto-selected from barang
    kalkulasiData[index].id_vendor = vendorId;
}

// Remove item
function removeItem(index) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        kalkulasiData.splice(index, 1);
        // Recalculate all nett percent after removing item
        recalculateAllNettPercent();
        populateKalkulasiTable();
        calculateTotals();
    }
}

// Save kalkulasi
async function saveKalkulasi() {
    if (!currentProyekId) {
        alert('ID Proyek tidak ditemukan');
        return;
    }
    
    if (kalkulasiData.length === 0) {
        alert('Tidak ada data kalkulasi untuk disimpan');
        return;
    }
    
    // Validate data
    const hasEmptyFields = kalkulasiData.some(item => !item.id_barang || !item.id_vendor);
    if (hasEmptyFields) {
        alert('Mohon lengkapi semua field barang dan vendor');
        return;
    }
    
    try {
        const response = await fetch('/purchasing/kalkulasi/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                id_proyek: currentProyekId,
                kalkulasi: kalkulasiData
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Kalkulasi berhasil disimpan');
            
            // Show create penawaran button
            document.getElementById('btn-create-penawaran').style.display = 'inline-block';
            
            // Update last updated time
            const now = new Date().toLocaleString('id-ID');
            document.getElementById('last-updated').textContent = now;
            document.getElementById('last-updated-header').textContent = now;
        } else {
            alert(data.message || 'Gagal menyimpan kalkulasi');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan kalkulasi');
    }
}

// Manual Test Calculation - untuk verifikasi
function testCalculation() {
    console.log('=== MANUAL TEST CALCULATION ===');
    
    // Test data
    const testData = {
        qty: 5,
        harga_vendor: 83000000,
        diskon_amount: 5000,
        kenaikan_percent: 2,
        ongkir: 0,
        bank_cost: 0,
        biaya_ops: 0,
        bendera: 0
    };
    
    const harga_satuan_proyek = 85000000; // dari screenshot
    
    console.log('Input Test Data:');
    console.log('- qty:', testData.qty);
    console.log('- harga_vendor:', testData.harga_vendor);
    console.log('- diskon_amount:', testData.diskon_amount);
    console.log('- harga_satuan_proyek:', harga_satuan_proyek);
    
    // Manual calculation step by step
    const totalDiskon = testData.diskon_amount * testData.qty;
    const totalHargaVendor = testData.harga_vendor * testData.qty;
    const totalSetelahDiskon = totalHargaVendor - totalDiskon;
    const totalHpp = totalSetelahDiskon; // TOTAL, bukan per unit
    const proyeksiKenaikan = totalHpp * (testData.kenaikan_percent / 100);
    const totalSetelahKenaikan = totalHpp + proyeksiKenaikan;
    const pph = totalSetelahKenaikan * 0.015;
    const ppn = totalSetelahKenaikan * 0.11;
    const hps = totalSetelahKenaikan + pph + ppn + testData.ongkir;
    const totalBiayaOps = (testData.bank_cost + testData.biaya_ops + testData.bendera) * testData.qty;
    const nett = hps - totalBiayaOps;
    const hargaPermintaanTotal = testData.qty * harga_satuan_proyek;
    const nettPercent = hargaPermintaanTotal > 0 ? (nett / hargaPermintaanTotal) * 100 : 0;
    
    console.log('Manual Calculations:');
    console.log('1. total_diskon:', totalDiskon);
    console.log('2. total_harga_vendor:', totalHargaVendor);
    console.log('3. total_setelah_diskon:', totalSetelahDiskon);
    console.log('4. total_hpp:', totalHpp);
    console.log('5. proyeksi_kenaikan:', proyeksiKenaikan);
    console.log('6. total_setelah_kenaikan:', totalSetelahKenaikan);
    console.log('7. pph:', pph);
    console.log('8. ppn:', ppn);
    console.log('9. hps:', hps);
    console.log('10. total_biaya_ops:', totalBiayaOps);
    console.log('11. nett:', nett);
    console.log('12. harga_permintaan_total:', hargaPermintaanTotal);
    console.log('13. nett_percent:', nettPercent, '%');
    
    console.log('Expected Result: nett_percent should be around 112.1% (profit 12.1%)');
    console.log('================================');
}

// Parse client requests from project - supports multiple items
function parseClientRequestsFromProject(proyek) {
    // Try to parse multiple items from project description or name
    let clientRequests = [];
    
    // Check if project description contains multiple items (format: "Item1|Qty1|Price1;Item2|Qty2|Price2")
    if (proyek.deskripsi && proyek.deskripsi.includes('|') && proyek.deskripsi.includes(';')) {
        try {
            const items = proyek.deskripsi.split(';');
            items.forEach((item, index) => {
                const parts = item.trim().split('|');
                if (parts.length >= 3) {
                    const namaBarang = parts[0].trim();
                    const qty = parseFloat(parts[1]) || 1;
                    const hargaSatuan = parseFloat(parts[2]) || 0;
                    
                    clientRequests.push({
                        id: index + 1,
                        nama_barang: namaBarang,
                        qty: qty,
                        satuan: proyek.satuan || 'Unit',
                        harga_satuan: hargaSatuan,
                        harga_total: qty * hargaSatuan
                    });
                }
            });
        } catch (error) {
            console.warn('Error parsing multi-item description:', error);
        }
    }
    
    // If no multi-item format found, use default single item from main project data
    if (clientRequests.length === 0) {
        clientRequests = [
            {
                id: 1,
                nama_barang: proyek.nama_barang,
                qty: proyek.jumlah,
                satuan: proyek.satuan,
                harga_satuan: proyek.harga_satuan,
                harga_total: proyek.harga_total
            }
        ];
    }
    
    return clientRequests;
}

// Get total permintaan from all client requests
function getTotalPermintaanFromAllClientRequests() {
    if (!window.clientRequests) return 0;
    
    return window.clientRequests.reduce((total, request) => {
        return total + (parseFloat(request.harga_total) || 0);
    }, 0);
}

// Find best matching client request for vendor item (improved algorithm)
function findBestMatchingClientRequest(vendorItemName) {
    if (!window.clientRequests || window.clientRequests.length === 0) return null;
    
    const vendorName = vendorItemName.toLowerCase().trim();
    
    // 1. Try exact match first
    let match = window.clientRequests.find(request => 
        request.nama_barang.toLowerCase().trim() === vendorName
    );
    
    if (match) return match;
    
    // 2. Try partial match (vendor item contains client request name)
    match = window.clientRequests.find(request => 
        vendorName.includes(request.nama_barang.toLowerCase().trim())
    );
    
    if (match) return match;
    
    // 3. Try partial match (client request contains vendor item name)
    match = window.clientRequests.find(request => 
        request.nama_barang.toLowerCase().trim().includes(vendorName)
    );
    
    if (match) return match;
    
    // 4. Try keyword matching (split by space and check common words)
    const vendorWords = vendorName.split(/\s+/).filter(word => word.length > 2);
    if (vendorWords.length > 0) {
        match = window.clientRequests.find(request => {
            const requestWords = request.nama_barang.toLowerCase().trim().split(/\s+/);
            return vendorWords.some(vendorWord => 
                requestWords.some(requestWord => 
                    requestWord.includes(vendorWord) || vendorWord.includes(requestWord)
                )
            );
        });
    }
    
    if (match) return match;
    
    // 5. If still no match and only one client request, use it as default
    if (window.clientRequests.length === 1) {
        return window.clientRequests[0];
    }
    
    // 6. Return null if multiple client requests and no match found
    return null;
}

// Get client request for specific vendor item (for nett % calculation)
function getClientRequestForVendorItem(vendorItem) {
    const barangName = vendorItem.nama_barang || vendorItem.id_barang;
    if (!barangName) return null;
    
    const selectedBarang = barangList.find(b => b.id_barang == vendorItem.id_barang);
    const barangDisplayName = selectedBarang ? selectedBarang.nama_barang : barangName;
    
    return findBestMatchingClientRequest(barangDisplayName);
}

// Example function to create sample multi-item project (for testing)
function createSampleMultiItemProject() {
    return {
        id_proyek: 'PRJ-001',
        nama_proyek: 'Supply Multi Item Equipment',
        nama_barang: 'Mixed Items',
        jumlah: 1,
        satuan: 'Lot',
        harga_satuan: 1000000000, // Total for all items
        harga_total: 1000000000,
        deskripsi: 'Laptop Dell|5|85000000;Monitor 24 inch|10|15000000;Printer HP|3|8000000;Mouse Wireless|20|500000',
        // This description will be parsed as:
        // - Laptop Dell: 5 units @ 85,000,000 = 425,000,000
        // - Monitor 24 inch: 10 units @ 15,000,000 = 150,000,000  
        // - Printer HP: 3 units @ 8,000,000 = 24,000,000
        // - Mouse Wireless: 20 units @ 500,000 = 10,000,000
        // Total: 609,000,000
    };
}

// Helper function to validate nett percent calculation
function validateNettPercentCalculation() {
    console.log('=== NETT PERCENT VALIDATION ===');
    console.log('Client Requests:', window.clientRequests);
    
    kalkulasiData.forEach((item, index) => {
        const clientRequest = getClientRequestForVendorItem(item);
        const vendorNett = parseFloat(item.nett) || 0;
        const nettPercent = parseFloat(item.nett_percent) || 0;
        
        console.log(`Row ${index + 1}:`);
        console.log('- Vendor Item:', item);
        console.log('- Matched Client Request:', clientRequest);
        console.log('- Vendor Nett:', formatRupiah(vendorNett));
        console.log('- Calculated Nett %:', nettPercent.toFixed(2) + '%');
        
        if (clientRequest) {
            const expectedNettPercent = (vendorNett / clientRequest.harga_total) * 100;
            console.log('- Expected Nett %:', expectedNettPercent.toFixed(2) + '%');
            console.log('- Match:', Math.abs(expectedNettPercent - nettPercent) < 0.01 ? '✓' : '✗');
        }
        console.log('---');
    });
    
    console.log('=== SUMMARY ===');
    const totalClientRequests = getTotalPermintaanFromAllClientRequests();
    const totalVendorNett = kalkulasiData.reduce((sum, item) => sum + (parseFloat(item.nett) || 0), 0);
    const overallNettPercent = totalClientRequests > 0 ? (totalVendorNett / totalClientRequests) * 100 : 0;
    
    console.log('Total Client Requests:', formatRupiah(totalClientRequests));
    console.log('Total Vendor Nett:', formatRupiah(totalVendorNett));
    console.log('Overall Nett %:', overallNettPercent.toFixed(2) + '%');
    console.log('=================================');
}
</script>
@endpush

@push('styles')
<style>
/* Responsive optimizations for mobile and tablet */

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    /* Modal responsive */
    #hps-modal .relative {
        margin: 10px;
        max-width: calc(100vw - 20px);
    }
    
    /* Table optimizations */
    .hps-table.mobile-optimized {
        font-size: 11px;
    }
    
    .hps-table.mobile-optimized th,
    .hps-table.mobile-optimized td {
        padding: 4px 2px;
    }
    
    .hps-table.mobile-optimized input,
    .hps-table.mobile-optimized select {
        font-size: 12px;
        padding: 4px 6px;
        min-height: 28px;
    }
    
    /* Hide less critical columns on mobile */
    .hps-table.mobile-optimized th:nth-child(8),
    .hps-table.mobile-optimized td:nth-child(8),
    .hps-table.mobile-optimized th:nth-child(11),
    .hps-table.mobile-optimized td:nth-child(11),
    .hps-table.mobile-optimized th:nth-child(12),
    .hps-table.mobile-optimized td:nth-child(12),
    .hps-table.mobile-optimized th:nth-child(13),
    .hps-table.mobile-optimized td:nth-child(13) {
        display: none;
    }
    
    /* Smaller modal content padding */
    #hps-modal-content .p-4 {
        padding: 12px;
    }
    
    /* Responsive grid for summary */
    .grid.grid-cols-1.md\\:grid-cols-4 {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    
    .grid.grid-cols-1.md\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}

/* Tablet optimizations */
@media (min-width: 641px) and (max-width: 1023px) {
    .hps-table.tablet-optimized {
        font-size: 12px;
    }
    
    .hps-table.tablet-optimized th,
    .hps-table.tablet-optimized td {
        padding: 6px 4px;
    }
    
    .hps-table.tablet-optimized input,
    .hps-table.tablet-optimized select {
        font-size: 12px;
        padding: 6px 8px;
    }
    
    /* Hide some columns on tablet */
    .hps-table.tablet-optimized th:nth-child(8),
    .hps-table.tablet-optimized td:nth-child(8) {
        display: none;
    }
}

/* Improved touch targets for mobile */
@media (max-width: 768px) {
    button {
        min-height: 44px;
        min-width: 44px;
    }
    
    input, select {
        min-height: 44px;
    }
    
    /* Larger pagination buttons */
    .pagination a,
    .pagination span {
        padding: 12px 16px;
        font-size: 14px;
    }
}

/* Smooth scrolling for horizontal tables */
.overflow-x-auto {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

/* Better focus states for accessibility */
button:focus,
input:focus,
select:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Loading animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Custom scrollbar */
.hps-table-container::-webkit-scrollbar {
    height: 8px;
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

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .hps-table {
        font-size: 10px;
    }
    
    .hps-table th,
    .hps-table td {
        padding: 2px 4px;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-gray-50 {
        background-color: #ffffff;
        border: 1px solid #000000;
    }
    
    .bg-gray-100 {
        background-color: #f5f5f5;
        border: 1px solid #000000;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endpush
