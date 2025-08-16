@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Kalkulasi Purchasing</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Hitung dan analisis biaya pengadaan</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-calculator text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 sm:p-6 mb-6">
    <form method="GET" action="{{ route('purchasing.kalkulasi') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <div class="relative">
                <select name="status" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ request('status')=='Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Penawaran" {{ request('status')=='Penawaran' ? 'selected' : '' }}>Penawaran</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
            </div>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari proyek..." 
                       class="border border-gray-300 rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                Filter
            </button>
        </div>
    </form>
</div>

<!-- Projects Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Proyek</h2>
        <p class="text-sm text-gray-600 mt-1">Klik proyek untuk melakukan kalkulasi HPS</p>
    </div>
    
    <div class="overflow-x-auto">
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
                        <button onclick="event.stopPropagation(); createPenawaran({{ $p->id_proyek }})" 
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
    
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $proyek->links() }}
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

// Open HPS Modal
async function openHpsModal(proyekId) {
    currentProyekId = proyekId;
    
    try {
        const response = await fetch(`/purchasing/kalkulasi/proyek/${proyekId}`);
        const data = await response.json();
        
        if (data.success) {
            populateModalData(data.proyek, data.kalkulasi);
            document.getElementById('hps-modal').classList.remove('hidden');
        } else {
            alert('Gagal memuat data proyek');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data');
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
    const tbody = document.getElementById('client-request-table');
    tbody.innerHTML = `
        <tr>
            <td class="px-3 py-2 text-sm text-blue-800">1</td>
            <td class="px-3 py-2 text-sm text-blue-800">${proyek.nama_barang}</td>
            <td class="px-3 py-2 text-sm text-blue-800">${proyek.jumlah.toLocaleString()}</td>
            <td class="px-3 py-2 text-sm text-blue-800">${proyek.satuan}</td>
            <td class="px-3 py-2 text-sm text-blue-800">Rp ${proyek.harga_satuan.toLocaleString()}</td>
            <td class="px-3 py-2 text-sm font-bold text-blue-800">Rp ${proyek.harga_total.toLocaleString()}</td>
            <td class="px-3 py-2">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    Permintaan
                </span>
            </td>
        </tr>
    `;
    
    // Update total client
    document.getElementById('grand-total-client').textContent = `Rp ${proyek.harga_total.toLocaleString()}`;
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

// Populate kalkulasi table
function populateKalkulasiTable() {
    const tbody = document.getElementById('kalkulasi-table-body');
    
    if (kalkulasiData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="21" class="text-center py-4 text-gray-500">
                    Belum ada data kalkulasi. Klik "Tambah Item Vendor" untuk menambah data.
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = kalkulasiData.map((item, index) => createKalkulasiRow(item, index)).join('');
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
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">${index + 1}</td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <select onchange="updateBarang(${index}, this.value)" class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Pilih Barang</option>
                    ${barangOptions}
                </select>
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <input type="text" readonly value="${vendorName}" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm bg-gray-50 text-gray-600"
                       placeholder="Vendor otomatis terisi">
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <input type="number" value="${item.qty}" onchange="updateField(${index}, 'qty', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm no-spin focus:outline-none focus:ring-2 focus:ring-red-500">
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200 text-sm text-gray-600">${satuan}</td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <input type="number" value="${item.harga_vendor}" onchange="updateField(${index}, 'harga_vendor', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm no-spin focus:outline-none focus:ring-2 focus:ring-red-500">
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <input type="number" value="${item.diskon_amount}" onchange="updateField(${index}, 'diskon_amount', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm no-spin focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Nominal diskon">
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200 text-sm font-medium">
                Rp ${item.total_diskon.toLocaleString()}
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200 bg-yellow-50 text-sm font-bold">
                Rp ${item.total_harga_hpp.toLocaleString()}
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <input type="number" value="${item.kenaikan_percent}" onchange="updateField(${index}, 'kenaikan_percent', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm no-spin focus:outline-none focus:ring-2 focus:ring-red-500" 
                       step="0.1" placeholder="% kenaikan">
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200 text-sm font-medium">
                Rp ${item.proyeksi_kenaikan.toLocaleString()}
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200 text-sm font-medium">
                Rp ${item.pph.toLocaleString()}
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200 text-sm font-medium">
                Rp ${item.ppn.toLocaleString()}
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <input type="number" value="${item.ongkir}" onchange="updateField(${index}, 'ongkir', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm no-spin focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Biaya ongkir">
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200 bg-blue-50 text-sm font-bold">
                Rp ${item.hps.toLocaleString()}
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <input type="number" value="${item.bank_cost}" onchange="updateField(${index}, 'bank_cost', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm no-spin focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Biaya bank">
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <input type="number" value="${item.biaya_ops}" onchange="updateField(${index}, 'biaya_ops', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm no-spin focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Biaya operasional">
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200">
                <input type="number" value="${item.bendera}" onchange="updateField(${index}, 'bendera', this.value)" 
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm no-spin focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Biaya bendera">
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200 bg-green-50 text-sm font-bold">
                Rp ${item.nett.toLocaleString()}
            </td>
            
            <td class="px-4 py-3 border-r border-gray-200 text-sm font-medium">
                ${(parseFloat(item.nett_percent) || 0).toFixed(1)}%
            </td>
            
            <td class="px-4 py-3">
                <button onclick="removeItem(${index})" class="text-red-600 hover:text-red-800" title="Hapus Item">
                    <i class="fas fa-trash-alt"></i>
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

// Update barang selection
function updateBarang(index, barangId) {
    const selectedBarang = barangList.find(b => b.id_barang == barangId);
    
    if (selectedBarang) {
        // Auto-fill vendor and price from selected barang
        kalkulasiData[index].id_barang = barangId;
        kalkulasiData[index].id_vendor = selectedBarang.id_vendor;
        kalkulasiData[index].harga_vendor = selectedBarang.harga_vendor;
        
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
// Get total permintaan klien from current project
function getTotalPermintaanKlien() {
    return currentProject ? parseFloat(currentProject.harga_total) || 0 : 0;
}

// Recalculate all nett percent based on total permintaan klien
function recalculateAllNettPercent() {
    const totalPermintaanKlien = getTotalPermintaanKlien();
    kalkulasiData.forEach(item => {
        item.nett_percent = totalPermintaanKlien > 0 ? (item.nett / totalPermintaanKlien) * 100 : 0;
    });
}

function calculateRow(index) {
    const item = kalkulasiData[index];
    
    // Calculate total diskon (qty * harga_vendor - diskon_amount)
    const subtotal = item.qty * item.harga_vendor;
    item.total_diskon = subtotal - item.diskon_amount;
    
    // Total HPP = total setelah diskon
    item.total_harga_hpp = item.total_diskon;
    
    // Proyeksi kenaikan = total_hpp * (kenaikan_percent / 100)
    item.proyeksi_kenaikan = item.total_harga_hpp * (item.kenaikan_percent / 100);
    
    // PPH 1.5% dari total setelah kenaikan
    const totalSetelahKenaikan = item.total_harga_hpp + item.proyeksi_kenaikan;
    item.pph = totalSetelahKenaikan * 0.015;
    
    // PPN 11% dari total setelah kenaikan
    item.ppn = totalSetelahKenaikan * 0.11;
    
    // HPS = total setelah kenaikan + PPH + PPN + Ongkir
    item.hps = totalSetelahKenaikan + item.pph + item.ppn + item.ongkir;
    
    // Nett = HPS - Bank Cost - Biaya Ops - Bendera
    item.nett = item.hps - item.bank_cost - item.biaya_ops - item.bendera;
    
    // Nett Percent = (nett / total permintaan klien) * 100
    const totalPermintaanKlien = getTotalPermintaanKlien();
    item.nett_percent = totalPermintaanKlien > 0 ? (item.nett / totalPermintaanKlien) * 100 : 0;
}

// Calculate totals
function calculateTotals() {
    const totalHpp = kalkulasiData.reduce((sum, item) => sum + item.total_harga_hpp, 0);
    const totalHps = kalkulasiData.reduce((sum, item) => sum + item.hps, 0);
    const totalNett = kalkulasiData.reduce((sum, item) => sum + item.nett, 0);
    
    // Calculate average nett percent based on total permintaan klien
    const totalPermintaanKlien = getTotalPermintaanKlien();
    const avgNett = totalPermintaanKlien > 0 ? (totalNett / totalPermintaanKlien) * 100 : 0;
    
    // Update main totals
    document.getElementById('grand-total-hpp').textContent = `Rp ${totalHpp.toLocaleString()}`;
    document.getElementById('grand-total-hps').textContent = `Rp ${totalHps.toLocaleString()}`;
    document.getElementById('grand-total-nett').textContent = `Rp ${totalNett.toLocaleString()}`;
    document.getElementById('grand-avg-nett').textContent = `${avgNett.toFixed(1)}%`;
    
    // Update breakdown
    const totalDiskon = kalkulasiData.reduce((sum, item) => sum + item.diskon_amount, 0);
    const totalPph = kalkulasiData.reduce((sum, item) => sum + item.pph, 0);
    const totalPpn = kalkulasiData.reduce((sum, item) => sum + item.ppn, 0);
    const totalOngkir = kalkulasiData.reduce((sum, item) => sum + item.ongkir, 0);
    const totalBank = kalkulasiData.reduce((sum, item) => sum + item.bank_cost, 0);
    const totalOps = kalkulasiData.reduce((sum, item) => sum + item.biaya_ops, 0);
    const totalBendera = kalkulasiData.reduce((sum, item) => sum + item.bendera, 0);
    
    if (document.getElementById('breakdown-diskon')) {
        document.getElementById('breakdown-diskon').textContent = `Rp ${totalDiskon.toLocaleString()}`;
        document.getElementById('breakdown-pph').textContent = `Rp ${totalPph.toLocaleString()}`;
        document.getElementById('breakdown-ppn').textContent = `Rp ${totalPpn.toLocaleString()}`;
        document.getElementById('breakdown-ongkir').textContent = `Rp ${totalOngkir.toLocaleString()}`;
        document.getElementById('breakdown-bank').textContent = `Rp ${totalBank.toLocaleString()}`;
        document.getElementById('breakdown-ops').textContent = `Rp ${totalOps.toLocaleString()}`;
        document.getElementById('breakdown-bendera').textContent = `Rp ${totalBendera.toLocaleString()}`;
        document.getElementById('breakdown-total-biaya').textContent = `Rp ${(totalBank + totalOps + totalBendera).toLocaleString()}`;
    }
}

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
</script>
@endpush
