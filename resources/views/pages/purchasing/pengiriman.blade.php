@extends('layouts.app')

@section('title', 'Pengiriman - Cyber KATANA')

@section('content')
<style>
    /* Custom styles untuk barang display */
    .barang-tooltip {
        transform: translateY(-100%);
        transition: all 0.2s ease-in-out;
    }
    
    .barang-preview:hover .barang-tooltip {
        opacity: 1;
        visibility: visible;
    }
    
    .barang-item-hover:hover {
        background-color: rgba(59, 130, 246, 0.05);
        border-color: rgba(59, 130, 246, 0.3);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Smooth scrolling untuk list barang */
    .barang-scroll {
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
    }
    
    .barang-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .barang-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .barang-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
    }
    
    .barang-scroll::-webkit-scrollbar-thumb:hover {
        background-color: rgba(156, 163, 175, 0.8);
    }
    
    /* Animation untuk modal barang */
    @keyframes slideInModal {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .modal-barang-content {
        animation: slideInModal 0.2s ease-out;
    }
    
    /* Highlight search results */
    .search-highlight {
        background-color: rgba(255, 235, 59, 0.4);
        padding: 1px 2px;
        border-radius: 2px;
    }
</style>
<!-- Access Control Info -->
@php
    $currentUser = Auth::user();
    $isAdminPurchasing = $currentUser->role === 'admin_purchasing';
    $isSuperadmin = $currentUser->role === 'superadmin';
@endphp



<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Manajemen Pengiriman</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola pengiriman proyek yang sudah diverifikasi pembayarannya</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-shipping-fast text-4xl sm:text-5xl lg:text-6xl text-red-200"></i>
        </div>
    </div>
</div>


<!-- Content Card -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Ready Kirim</p>
                    <p class="text-2xl font-bold">{{ $proyekReady->sum(function($p) { return collect($p->vendors_ready)->where('ready_to_ship', true)->count(); }) }}</p>
                </div>
                <i class="fas fa-box text-2xl text-blue-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Dalam Proses</p>
                    <p class="text-2xl font-bold">{{ $pengirimanBerjalan->total() ?? 0 }}</p>
                </div>
                <i class="fas fa-truck text-2xl text-yellow-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Sampai/Selesai</p>
                    <p class="text-2xl font-bold">{{ $pengirimanSelesai->total() ?? 0 }}</p>
                </div>
                <i class="fas fa-check-circle text-2xl text-green-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Kirim</p>
                    <p class="text-2xl font-bold">{{ ($pengirimanBerjalan->total() ?? 0) + ($pengirimanSelesai->total() ?? 0) }}</p>
                </div>
                <i class="fas fa-chart-line text-2xl text-purple-200"></i>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button id="tabReady" onclick="switchTab('ready')" 
                    class="border-red-500 text-red-600 tab-active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-box mr-2"></i>Ready Kirim
            </button>
            <button id="tabProses" onclick="switchTab('proses')" 
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-truck mr-2"></i>Dalam Proses
            </button>
            <button id="tabSelesai" onclick="switchTab('selesai')" 
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-check-circle mr-2"></i>Sampai/Selesai
            </button>
        </nav>
    </div>

    <!-- Tab Content: Ready Kirim -->
    <div id="contentReady" class="tab-content">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Vendor Ready untuk Dikirim</h3>
                <p class="text-sm text-gray-600">Vendor yang sudah memiliki pembayaran dengan status "Approved" dan siap untuk pengiriman</p>
            </div>
            
            <!-- Search Bar -->
            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="tab" value="ready">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari pengiriman..." 
                               class="px-4 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    @if(request('search'))
                    <a href="{{ route('purchasing.pengiriman') }}?tab=ready" 
                       class="px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 whitespace-nowrap">
                        <i class="fas fa-times mr-1"></i>
                        Reset
                    </a>
                    @endif
                </form>
            </div>
        </div>
        
        @if($proyekReadyPaginated->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modal Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($proyekReadyPaginated as $vendorProyek)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">{{ $vendorProyek->proyek->kode_proyek }}</div>
                                    <div class="text-gray-500">{{ $vendorProyek->proyek->instansi }}</div>
                                    <div class="text-xs text-gray-400">{{ $vendorProyek->proyek->penawaranAktif->no_penawaran ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">{{ is_array($vendorProyek->vendor) ? $vendorProyek->vendor['nama_vendor'] : $vendorProyek->vendor->nama_vendor }}</div>
                                    <div class="text-gray-500 text-xs">{{ is_array($vendorProyek->vendor) ? $vendorProyek->vendor['jenis_perusahaan'] : $vendorProyek->vendor->jenis_perusahaan }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs">
                                    @php
                                        // PRIORITAS 1: Gunakan data barang_vendor yang sudah disiapkan controller
                                        $barangVendor = [];
                                        if (isset($vendorProyek->barang_vendor) && !empty($vendorProyek->barang_vendor)) {
                                            $barangVendor = is_array($vendorProyek->barang_vendor) ? 
                                                          $vendorProyek->barang_vendor : 
                                                          [$vendorProyek->barang_vendor];
                                        }
                                        
                                        // PRIORITAS 2: Cari barang dari kalkulasi HPS jika tidak ada dari controller
                                        if (empty($barangVendor) && isset($vendorProyek->proyek->kalkulasiHps)) {
                                            foreach ($vendorProyek->proyek->kalkulasiHps as $kalkulasi) {
                                                $vendorId = is_array($vendorProyek->vendor) ? $vendorProyek->vendor['id_vendor'] : $vendorProyek->vendor->id_vendor;
                                                if ($kalkulasi->id_vendor == $vendorId) {
                                                    // Cek dulu dari relasi barang jika ada
                                                    if (isset($kalkulasi->barang->nama_barang)) {
                                                        $barangVendor[] = $kalkulasi->barang->nama_barang;
                                                    } elseif (isset($kalkulasi->nama_barang)) {
                                                        // Fallback ke field nama_barang langsung jika tidak ada relasi
                                                        $barangVendor[] = $kalkulasi->nama_barang;
                                                    }
                                                }
                                            }
                                        }
                                        
                                        // PRIORITAS 3: Fallback dari data vendor langsung
                                        if (empty($barangVendor)) {
                                            $namaBarang = is_array($vendorProyek->vendor) ? 
                                                ($vendorProyek->vendor['nama_barang'] ?? null) : 
                                                ($vendorProyek->vendor->nama_barang ?? null);
                                            if ($namaBarang) {
                                                $barangVendor = [$namaBarang];
                                            }
                                        }
                                        
                                        // PRIORITAS 4: Final fallback - gunakan nama proyek
                                        if (empty($barangVendor)) {
                                            $barangVendor = ['Barang untuk ' . $vendorProyek->proyek->kode_proyek];
                                        }
                                                         // Debug info - hapus setelah testing
                        \Log::info('View Barang Debug:', [
                            'proyek_code' => $vendorProyek->proyek->kode_proyek,
                            'vendor_id' => is_array($vendorProyek->vendor) ? $vendorProyek->vendor['id_vendor'] : $vendorProyek->vendor->id_vendor, 
                            'vendor_name' => is_array($vendorProyek->vendor) ? $vendorProyek->vendor['nama_vendor'] : $vendorProyek->vendor->nama_vendor,
                            'vendor_is_array' => is_array($vendorProyek->vendor),
                            'barang_vendor_from_controller' => $vendorProyek->barang_vendor ?? 'tidak ada',
                            'kalkulasi_hps_count' => $vendorProyek->proyek->kalkulasiHps ? $vendorProyek->proyek->kalkulasiHps->count() : 'no kalkulasi',
                            'final_barang_array' => $barangVendor
                        ]);
                                        
                                        // Remove duplicates and clean up
                                        $barangVendor = array_unique(array_filter($barangVendor));
                                    @endphp
                                    
                                    @if(!empty($barangVendor))
                                        <div class="relative group">
                                            @if(count($barangVendor) == 1)
                                                <div class="text-sm">
                                                    <i class="fas fa-box text-blue-500 mr-1"></i>
                                                    <span class="font-medium text-gray-900">{{ $barangVendor[0] }}</span>
                                                </div>
                                            @else
                                                <!-- Tampilan ringkas untuk multiple items -->
                                                <div class="text-sm cursor-pointer hover:bg-blue-50 p-2 rounded border border-gray-200" 
                                                     onclick="showBarangDetail('{{ is_array($vendorProyek->vendor) ? $vendorProyek->vendor['id_vendor'] : $vendorProyek->vendor->id_vendor }}', {{ json_encode($barangVendor) }}, '{{ is_array($vendorProyek->vendor) ? $vendorProyek->vendor['nama_vendor'] : $vendorProyek->vendor->nama_vendor }}')"
                                                     >
                                                    <i class="fas fa-boxes text-blue-500 mr-1"></i>
                                                    <span class="font-medium text-gray-900">{{ $barangVendor[0] }}</span>
                                                    @if(count($barangVendor) > 1)
                                                        <span class="text-blue-600 font-medium">
                                                            @if(count($barangVendor) == 2)
                                                                & {{ $barangVendor[1] }}
                                                            @else
                                                                & {{ count($barangVendor) - 1 }} lainnya
                                                            @endif
                                                        </span>
                                                    @endif
                                                    <i class="fas fa-eye text-gray-400 ml-1 text-xs"></i>
                                                </div>
                                                
                                                <!-- Tooltip hover untuk preview cepat -->
                                                <div class="absolute left-0 top-full mt-1 bg-gray-900 text-white text-xs rounded-lg p-2 shadow-lg z-10 min-w-64 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                                                    <div class="font-medium mb-1">{{ count($barangVendor) }} Jenis Barang:</div>
                                                    <div class="max-h-32 overflow-y-auto space-y-1">
                                                        @foreach($barangVendor as $index => $barang)
                                                            <div class="flex items-start">
                                                                <span class="text-gray-300 mr-1">{{ $index + 1 }}.</span>
                                                                <span>{{ $barang }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="text-gray-400 text-xs mt-1 border-t border-gray-700 pt-1">
                                                        Klik untuk detail lengkap
                                                    </div>
                                                    <!-- Arrow pointer -->
                                                    <div class="absolute -top-1 left-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                </div>
                                                
                                                <!-- Badge jumlah -->
                                                <div class="text-xs text-green-600 mt-1">
                                                    <i class="fas fa-layer-group mr-1"></i>{{ count($barangVendor) }} jenis barang
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-400">
                                            <i class="fas fa-question-circle mr-1"></i>Data barang tidak tersedia
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="text-blue-700 text-md">
                                        Dibayar: Rp {{ number_format($vendorProyek->total_dibayar_approved, 0, ',', '.') }}
                                    </div>
                                    @php
                                        $sisaBayar = $vendorProyek->total_vendor - $vendorProyek->total_dibayar_approved;
                                        $persenBayar = $vendorProyek->total_vendor > 0 ? ($vendorProyek->total_dibayar_approved / $vendorProyek->total_vendor) * 100 : 0;
                                    @endphp
                                    @if($sisaBayar > 0)
                                        <div class="text-orange-600 text-xs">
                                            Sisa: Rp {{ number_format($sisaBayar, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Siap Kirim
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @php
                                    $canAccess = ($currentUser->role === 'admin_purchasing' && $vendorProyek->proyek->id_admin_purchasing == $currentUser->id_user) || $currentUser->role === 'superadmin';
                                @endphp

                                @if($canAccess)
                                    <button onclick="buatPengiriman({{ $vendorProyek->proyek->penawaranAktif->id_penawaran }}, {{ is_array($vendorProyek->vendor) ? $vendorProyek->vendor['id_vendor'] : $vendorProyek->vendor->id_vendor }})" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                        <i class="fas fa-plus mr-1"></i> Buat Pengiriman
                                    </button>
                                @else
                                    <button disabled
                                            class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm cursor-not-allowed">
                                        <i class="fas fa-lock mr-1"></i> 
                                        @if($currentUser->role !== 'admin_purchasing' && $currentUser->role !== 'superadmin')
                                            Tidak Ada Akses
                                        @else
                                            Bukan Proyek Anda
                                        @endif
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination untuk Ready Kirim -->
            @if($proyekReadyPaginated->hasPages())
            <div class="mt-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Menampilkan {{ $proyekReadyPaginated->firstItem() ?? 0 }} - {{ $proyekReadyPaginated->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-800">{{ $proyekReadyPaginated->total() }}</span> vendor siap kirim
                    </div>
                    <div class="flex justify-center">
                        {{ $proyekReadyPaginated->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        @else
            <div class="text-center py-8">
                <i class="fas fa-box text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Tidak ada vendor yang ready untuk dikirim</p>
                <p class="text-xs text-gray-400 mt-2">Pastikan ada vendor yang sudah memiliki pembayaran dengan status "Approved"</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Dalam Proses -->
    <div id="contentProses" class="tab-content hidden">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Pengiriman Dalam Proses</h3>
                <p class="text-sm text-gray-600">Pengiriman yang sedang berlangsung</p>
            </div>
            
            <!-- Search Bar -->
            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="tab" value="proses">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari pengiriman..." 
                               class="px-4 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    @if(request('search'))
                    <a href="{{ route('purchasing.pengiriman') }}?tab=proses" 
                       class="px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 whitespace-nowrap">
                        <i class="fas fa-times mr-1"></i>
                        Reset
                    </a>
                    @endif
                </form>
            </div>
        </div>
        
        @if($pengirimanBerjalan->count() > 0)
            <div class="grid gap-4">
                @foreach($pengirimanBerjalan as $pengiriman)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $pengiriman->penawaran->proyek->kode_proyek }}</h4>
                            <p class="text-sm text-gray-600">{{ $pengiriman->penawaran->proyek->instansi }}</p>
                            <div class="text-sm text-gray-500 mt-1 space-y-1">
                                <div><span class="font-medium">Vendor:</span> {{ $pengiriman->vendor->nama_vendor }}</div>
                                @php
                                    // Process barang list untuk pengiriman dalam proses
                                    $barangProses = [];
                                    if (isset($pengiriman->barang_list) && !empty($pengiriman->barang_list)) {
                                        $barangProses = is_array($pengiriman->barang_list) ? $pengiriman->barang_list : [$pengiriman->barang_list];
                                    } else {
                                        // Fallback dari kalkulasi HPS
                                        if (isset($pengiriman->penawaran->proyek->kalkulasiHps)) {
                                            foreach ($pengiriman->penawaran->proyek->kalkulasiHps as $kalkulasi) {
                                                if ($kalkulasi->id_vendor == $pengiriman->id_vendor) {
                                                    $barangProses[] = $kalkulasi->nama_barang;
                                                }
                                            }
                                        }
                                    }
                                    $barangProses = array_unique(array_filter($barangProses));
                                @endphp
                                
                                @if(!empty($barangProses))
                                    <div class="mb-2">
                                        <span class="font-medium text-gray-700 text-sm">
                                            <i class="fas fa-boxes mr-1"></i>Barang yang dikirim:
                                        </span>
                                        <div class="mt-1">
                                            @if(count($barangProses) == 1)
                                                <div class="text-sm text-blue-600 bg-blue-50 px-2 py-1 rounded inline-block">
                                                    <i class="fas fa-box mr-1"></i>{{ $barangProses[0] }}
                                                </div>
                                            @elseif(count($barangProses) <= 2)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($barangProses as $index => $barang)
                                                        <div class="text-sm text-blue-600 bg-blue-50 px-2 py-1 rounded flex items-center">
                                                            <span class="w-4 h-4 bg-blue-500 text-white rounded-full text-xs flex items-center justify-center mr-1">{{ $index + 1 }}</span>
                                                            {{ $barang }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="flex flex-wrap gap-1 items-center">
                                                    @foreach(array_slice($barangProses, 0, 1) as $index => $barang)
                                                        <div class="text-sm text-blue-600 bg-blue-50 px-2 py-1 rounded flex items-center">
                                                            <span class="w-4 h-4 bg-blue-500 text-white rounded-full text-xs flex items-center justify-center mr-1">{{ $index + 1 }}</span>
                                                            {{ $barang }}
                                                        </div>
                                                    @endforeach
                                                    <button onclick="showBarangDetail('{{ $pengiriman->id_vendor }}', {{ json_encode($barangProses) }}, '{{ $pengiriman->vendor->nama_vendor }}', 'Pengiriman: {{ $pengiriman->no_surat_jalan }}')" 
                                                            class="text-sm text-blue-600 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded border border-blue-200 hover:border-blue-300 transition-colors">
                                                        <i class="fas fa-eye mr-1"></i>+ {{ count($barangProses) - 1 }} lainnya
                                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div><span class="font-medium">Surat Jalan:</span> {{ $pengiriman->no_surat_jalan }}</div>
                                <div><span class="font-medium">Tanggal Kirim:</span> {{ \Carbon\Carbon::parse($pengiriman->tanggal_kirim)->format('d M Y') }}</div>
                            </div>
                            
                            <!-- Status Dokumentasi -->
                            <div class="mt-3">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        $docs = [
                                            ['field' => 'foto_berangkat', 'label' => 'Berangkat', 'icon' => 'fas fa-camera'],
                                            ['field' => 'foto_perjalanan', 'label' => 'Perjalanan', 'icon' => 'fas fa-road'],
                                            ['field' => 'foto_sampai', 'label' => 'Sampai', 'icon' => 'fas fa-map-marker-alt'],
                                            ['field' => 'tanda_terima', 'label' => 'TTD', 'icon' => 'fas fa-signature']
                                        ];
                                    @endphp
                                    
                                    @foreach($docs as $doc)
                                        @if($pengiriman->{$doc['field']})
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 mr-1 mb-1">
                                                <i class="{{ $doc['icon'] }} mr-1"></i>{{ $doc['label'] }}
                                                <button onclick="viewFile('{{ $pengiriman->{$doc['field']} }}')" 
                                                        class="ml-2 text-green-600 hover:text-green-800">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500 mr-1 mb-1">
                                                <i class="{{ $doc['icon'] }} mr-1"></i>{{ $doc['label'] }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                                
                                @php
                                    $totalDocs = 4;
                                    $completedDocs = collect($docs)->filter(function($doc) use ($pengiriman) {
                                        return $pengiriman->{$doc['field']};
                                    })->count();
                                    $progressPercent = ($completedDocs / $totalDocs) * 100;
                                @endphp
                                
                                <div class="mt-2">
                                    <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                        <span>Dokumentasi: {{ $completedDocs }}/{{ $totalDocs }}</span>
                                        <span>{{ round($progressPercent) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $pengiriman->status_verifikasi == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($pengiriman->status_verifikasi == 'Dalam_Proses' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ str_replace('_', ' ', $pengiriman->status_verifikasi) }}
                            </span>
                            <div class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-building mr-1"></i>{{ $pengiriman->vendor->jenis_perusahaan }}
                            </div>
                            <div class="mt-2">
                                @php
                                    $canAccessUpdate = ($currentUser->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing == $currentUser->id_user) || $currentUser->role === 'superadmin';
                                @endphp
                                
                                @if($canAccessUpdate)
                                    <div class="flex space-x-2">
                                        <button onclick="editPengiriman({{ $pengiriman->id_pengiriman }})" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button onclick="updateDokumentasi({{ $pengiriman->id_pengiriman }})" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                            <i class="fas fa-upload mr-1"></i>Update
                                        </button>
                                    </div>
                                @else
                                    <button disabled
                                            class="bg-gray-300 text-gray-500 px-3 py-1 rounded text-sm cursor-not-allowed">
                                        <i class="fas fa-lock mr-1"></i>
                                        @if($currentUser->role !== 'admin_purchasing' && $currentUser->role !== 'superadmin')
                                            Terkunci
                                        @else
                                            Bukan Proyek Anda
                                        @endif
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination untuk Dalam Proses -->
            @if($pengirimanBerjalan->hasPages())
            <div class="mt-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Menampilkan {{ $pengirimanBerjalan->firstItem() ?? 0 }} - {{ $pengirimanBerjalan->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-800">{{ $pengirimanBerjalan->total() }}</span> pengiriman dalam proses
                    </div>
                    <div class="flex justify-center">
                        {{ $pengirimanBerjalan->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        @else
            <div class="text-center py-8">
                <i class="fas fa-truck text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Tidak ada pengiriman dalam proses</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Selesai -->
    <div id="contentSelesai" class="tab-content hidden">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Pengiriman Selesai</h3>
                <p class="text-sm text-gray-600">Pengiriman dengan dokumentasi lengkap atau yang sudah verified (untuk proyek status "Selesai")</p>
            </div>
            
            <!-- Search Bar -->
            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="tab" value="selesai">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari pengiriman..." 
                               class="px-4 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    @if(request('search'))
                    <a href="{{ route('purchasing.pengiriman') }}?tab=selesai" 
                       class="px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 whitespace-nowrap">
                        <i class="fas fa-times mr-1"></i>
                        Reset
                    </a>
                    @endif
                </form>
            </div>
        </div>
        
        @if(count($pengirimanSelesai) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surat Jalan & Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokumentasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pengirimanSelesai as $pengiriman)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">{{ $pengiriman->penawaran->proyek->kode_proyek }}</div>
                                    <div class="text-gray-500">{{ $pengiriman->penawaran->proyek->instansi }}</div>
                                    <div class="text-xs text-gray-400">{{ $pengiriman->penawaran->no_penawaran ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">{{ $pengiriman->vendor->nama_vendor }}</div>
                                    <div class="text-gray-500 text-xs">{{ $pengiriman->vendor->jenis_perusahaan }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs">
                                    @php
                                        // Ambil daftar barang untuk pengiriman ini dari barang_list yang sudah disiapkan controller
                                        $barangSelesai = [];
                                        if (isset($pengiriman->barang_list) && !empty($pengiriman->barang_list)) {
                                            $barangSelesai = is_array($pengiriman->barang_list) ? $pengiriman->barang_list : [$pengiriman->barang_list];
                                        }
                                        
                                        // Remove duplicates and clean up
                                        $barangSelesai = array_unique(array_filter($barangSelesai));
                                    @endphp
                                    
                                    @if(!empty($barangSelesai))
                                        <div class="relative group">
                                            @if(count($barangSelesai) == 1)
                                                <div class="text-sm">
                                                    <i class="fas fa-box text-blue-500 mr-1"></i>
                                                    <span class="font-medium text-gray-900">{{ $barangSelesai[0] }}</span>
                                                </div>
                                            @else
                                                <!-- Tampilan ringkas untuk multiple items -->
                                                <div class="text-sm cursor-pointer hover:bg-blue-50 p-2 rounded border border-gray-200" 
                                                     onclick="showBarangDetail('{{ $pengiriman->id_vendor }}', {{ json_encode($barangSelesai) }}, '{{ $pengiriman->vendor->nama_vendor }}', 'Pengiriman Selesai - {{ $pengiriman->no_surat_jalan }}')">
                                                    <i class="fas fa-boxes text-blue-500 mr-1"></i>
                                                    <span class="font-medium text-gray-900">{{ $barangSelesai[0] }}</span>
                                                    @if(count($barangSelesai) > 1)
                                                        <span class="text-blue-600 font-medium">
                                                            @if(count($barangSelesai) == 2)
                                                                & {{ $barangSelesai[1] }}
                                                            @else
                                                                & {{ count($barangSelesai) - 1 }} lainnya
                                                            @endif
                                                        </span>
                                                    @endif
                                                    <i class="fas fa-eye text-gray-400 ml-1 text-xs"></i>
                                                </div>
                                                
                                                <!-- Tooltip hover untuk preview cepat -->
                                                <div class="absolute left-0 top-full mt-1 bg-gray-900 text-white text-xs rounded-lg p-2 shadow-lg z-10 min-w-64 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                                                    <div class="font-medium mb-1">{{ count($barangSelesai) }} Jenis Barang:</div>
                                                    <div class="max-h-32 overflow-y-auto space-y-1">
                                                        @foreach($barangSelesai as $index => $barang)
                                                            <div class="flex items-start">
                                                                <span class="text-gray-300 mr-1">{{ $index + 1 }}.</span>
                                                                <span>{{ $barang }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="text-gray-400 text-xs mt-1 border-t border-gray-700 pt-1">
                                                        Klik untuk detail lengkap
                                                    </div>
                                                    <!-- Arrow pointer -->
                                                    <div class="absolute -top-1 left-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                </div>
                                                
                                                <!-- Badge jumlah -->
                                                <div class="text-xs text-green-600 mt-1">
                                                    <i class="fas fa-layer-group mr-1"></i>{{ count($barangSelesai) }} jenis barang
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-400">
                                            <i class="fas fa-question-circle mr-1"></i>Data barang tidak tersedia
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">{{ $pengiriman->no_surat_jalan }}</div>
                                    <div class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($pengiriman->tanggal_kirim)->format('d M Y') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pengiriman->penawaran->proyek->status == 'Selesai')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Verified
                                    </span>
                                @elseif($pengiriman->status_verifikasi == 'Sampai_Tujuan' || ($pengiriman->foto_sampai && $pengiriman->tanda_terima))
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-truck mr-1"></i>Dokumentasi Lengkap
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Menunggu Dokumentasi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @php
                                    $docs = [
                                        'foto_berangkat' => ['icon' => 'fas fa-camera', 'label' => 'Berangkat'],
                                        'foto_perjalanan' => ['icon' => 'fas fa-road', 'label' => 'Perjalanan'], 
                                        'foto_sampai' => ['icon' => 'fas fa-map-marker-alt', 'label' => 'Sampai'],
                                        'tanda_terima' => ['icon' => 'fas fa-signature', 'label' => 'TTD']
                                    ];
                                    $completedDocs = 0;
                                    foreach($docs as $field => $info) {
                                        if($pengiriman->$field) $completedDocs++;
                                    }
                                @endphp
                                <div class="flex items-center space-x-1">
                                    @foreach($docs as $field => $info)
                                        @if($pengiriman->$field)
                                            <span class="inline-flex items-center p-1 rounded-full bg-green-100 text-green-600" title="{{ $info['label'] }}">
                                                <i class="{{ $info['icon'] }} text-xs"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center p-1 rounded-full bg-gray-100 text-gray-400" title="{{ $info['label'] }}">
                                                <i class="{{ $info['icon'] }} text-xs"></i>
                                            </span>
                                        @endif
                                    @endforeach
                                    <span class="text-xs text-gray-500 ml-2">{{ $completedDocs }}/4</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @php
                                    $canAccessEditSelesai = ($currentUser->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing == $currentUser->id_user) || $currentUser->role === 'superadmin';
                                @endphp
                                
                                <div class="flex space-x-2">
                                    @if($pengiriman->status_verifikasi == 'Sampai_Tujuan' && $canAccessEditSelesai)
                                        <button onclick="editPengiriman({{ $pengiriman->id_pengiriman }})" 
                                                class="text-orange-600 hover:text-orange-900">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                    @endif
                                    <button onclick="lihatDetailSelesai({{ $pengiriman->id_pengiriman }})" 
                                            class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye mr-1"></i> Lihat Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination untuk Selesai -->
            @if($pengirimanSelesai->hasPages())
            <div class="mt-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Menampilkan {{ $pengirimanSelesai->firstItem() ?? 0 }} - {{ $pengirimanSelesai->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-800">{{ $pengirimanSelesai->total() }}</span> pengiriman selesai
                    </div>
                    <div class="flex justify-center">
                        {{ $pengirimanSelesai->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        @else
            <div class="text-center py-8">
                <i class="fas fa-check-circle text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Belum ada pengiriman yang selesai</p>
                <p class="text-xs text-gray-400 mt-2">Pengiriman akan muncul di sini ketika dokumentasi lengkap atau proyek sudah selesai</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Buat Pengiriman -->
<div id="modalBuatPengiriman" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Buat Pengiriman Baru</h3>
                    <button onclick="tutupModal('modalBuatPengiriman')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('purchasing.pengiriman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id_penawaran" name="id_penawaran">
                    <input type="hidden" id="id_vendor" name="id_vendor">
                    
                    <div id="infoProyek" class="bg-gray-50 p-4 rounded-lg mb-4">
                        <!-- Info proyek dan vendor akan diisi via JavaScript -->
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Surat Jalan</label>
                            <input type="text" name="no_surat_jalan" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kirim</label>
                            <input type="date" name="tanggal_kirim" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                        <textarea name="alamat_kirim" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Surat Jalan (PDF/Gambar)</label>
                        <input type="file" name="file_surat_jalan" accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 5MB, format: PDF, JPG, PNG</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="tutupModal('modalBuatPengiriman')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-1"></i> Simpan Pengiriman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Dokumentasi -->
<div id="modalUpdateDokumentasi" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Update Dokumentasi Pengiriman</h3>
                    <button onclick="tutupModal('modalUpdateDokumentasi')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="formUpdateDokumentasi" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div id="infoPengirimanUpdate" class="bg-gray-50 p-4 rounded-lg mb-4">
                        <!-- Info pengiriman akan diisi via JavaScript -->
                    </div>
                    
                    <div id="formDokumentasiFields" class="space-y-4">
                        <!-- Field dokumentasi akan diisi via JavaScript -->
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="tutupModal('modalUpdateDokumentasi')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-upload mr-1"></i> Update Dokumentasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pengiriman Selesai -->
<div id="modalDetailSelesai" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Detail Pengiriman Selesai</h3>
                    <button onclick="tutupModal('modalDetailSelesai')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Header Info -->
                <div id="headerDetailSelesai" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg mb-6">
                    <!-- Header akan diisi via JavaScript -->
                </div>
                
                <!-- Tab Navigation untuk Detail -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button id="tabInfoDetail" onclick="switchDetailTab('info')" 
                                class="border-green-500 text-green-600 detail-tab-active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-info-circle mr-2"></i>Info Pengiriman
                        </button>
                        <button id="tabDokumentasiDetail" onclick="switchDetailTab('dokumentasi')" 
                                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-camera mr-2"></i>Dokumentasi
                        </button>
                        <button id="tabTimelineDetail" onclick="switchDetailTab('timeline')" 
                                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-history mr-2"></i>Timeline
                        </button>
                    </nav>
                </div>
                
                <!-- Tab Content Info -->
                <div id="contentInfo" class="detail-tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Proyek</h4>
                            <div id="infoProyekDetail" class="space-y-2 text-sm">
                                <!-- Info proyek akan diisi via JavaScript -->
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-3">Detail Pengiriman</h4>
                            <div id="infoPengirimanDetailSelesai" class="space-y-2 text-sm">
                                <!-- Info pengiriman akan diisi via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Content Dokumentasi -->
                <div id="contentDokumentasi" class="detail-tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div id="dokumentasiSelesaiContent">
                            <!-- Dokumentasi akan diisi via JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Tab Content Timeline -->
                <div id="contentTimeline" class="detail-tab-content hidden">
                    <div id="timelineSelesaiContent">
                        <!-- Timeline akan diisi via JavaScript -->
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <button onclick="tutupModal('modalDetailSelesai')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Barang -->
<div id="modalDetailBarang" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[80vh] overflow-hidden shadow-2xl modal-barang-content">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold" id="modalBarangTitle">Detail Barang Vendor</h3>
                        <p class="text-blue-100 text-sm" id="modalBarangSubtitle">Daftar lengkap barang</p>
                    </div>
                    <button onclick="tutupModal('modalDetailBarang')" class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="searchBarang" placeholder="Cari nama barang..." 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Statistik -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-boxes text-blue-500 mr-2"></i>
                            <span class="text-sm text-gray-600">Total Barang:</span>
                        </div>
                        <span class="font-bold text-lg text-blue-600" id="totalBarangCount">0</span>
                    </div>
                </div>
                
                <!-- List Barang -->
                <div class="max-h-96 overflow-y-auto barang-scroll" id="listBarangContainer">
                    <div id="listBarang" class="space-y-2">
                        <!-- Barang items akan diisi via JavaScript -->
                    </div>
                    
                    <!-- No results message -->
                    <div id="noResults" class="hidden text-center py-8 text-gray-500">
                        <i class="fas fa-search text-4xl mb-3"></i>
                        <p>Tidak ada barang yang ditemukan</p>
                        <p class="text-sm">Coba ubah kata kunci pencarian</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Gunakan pencarian untuk menemukan barang tertentu
                    </div>
                    <button onclick="tutupModal('modalDetailBarang')" 
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables for access control
window.currentUserId = {{ $currentUser->id_user }};
window.currentUserRole = '{{ $currentUser->role }}';

// Tab switching
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tabs
    document.querySelectorAll('nav button').forEach(tab => {
        tab.classList.remove('border-red-500', 'text-red-600', 'tab-active');
        tab.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    document.getElementById('content' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).classList.remove('hidden');

    // Add active class to selected tab
    const activeTab = document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-red-500', 'text-red-600', 'tab-active');
}

// Buat pengiriman
function buatPengiriman(penawaranId, vendorId) {
    // Check access control - Allow admin_purchasing and superadmin
    if (!['admin_purchasing', 'superadmin'].includes(window.currentUserRole)) {
        alert('Tidak memiliki akses untuk membuat pengiriman. Hanya admin purchasing atau superadmin yang dapat melakukan aksi ini.');
        return;
    }

    // Set ID penawaran dan vendor
    document.getElementById('id_penawaran').value = penawaranId;
    document.getElementById('id_vendor').value = vendorId;
    
    // Find project and vendor data
    const proyekData = @json($proyekReady);
    let selectedProyek = null;
    let selectedVendor = null;
    
    // Cari proyek dan vendor yang dipilih
    for (const proyek of proyekData) {
        for (const vendorData of proyek.vendors_ready) {
            if (proyek.penawaranAktif && 
                proyek.penawaranAktif.id_penawaran == penawaranId && 
                vendorData.vendor.id_vendor == vendorId) {
                selectedProyek = proyek;
                selectedVendor = vendorData;
                break;
            }
        }
        if (selectedProyek && selectedVendor) break;
    }
    
    if (selectedProyek && selectedVendor) {
        // Check if current user is assigned to this project or is superadmin
        if (window.currentUserRole === 'admin_purchasing' && selectedProyek.id_admin_purchasing != window.currentUserId) {
            alert('Tidak memiliki akses untuk proyek ini. Hanya admin purchasing yang ditugaskan atau superadmin yang dapat membuat pengiriman untuk proyek ini.');
            return;
        }

        // Generate barang list untuk vendor dengan smart display
        let barangVendorHtml = '';
        let barangList = [];
        
        if (selectedVendor.barang_vendor && Array.isArray(selectedVendor.barang_vendor) && selectedVendor.barang_vendor.length > 0) {
            barangList = selectedVendor.barang_vendor;
        } else if (selectedVendor.nama_barang) {
            barangList = [selectedVendor.nama_barang];
        }
        
        if (barangList.length > 0) {
            if (barangList.length === 1) {
                barangVendorHtml = `<div><span class="text-gray-500">Barang Vendor:</span> <span class="font-medium text-blue-600">
                    <i class="fas fa-box mr-1"></i>${barangList[0]}
                </span></div>`;
            } else if (barangList.length <= 3) {
                barangVendorHtml = `<div><span class="text-gray-500">Barang Vendor:</span> <span class="font-medium text-blue-600">
                    <i class="fas fa-boxes mr-1"></i>${barangList.join(', ')}
                </span></div>`;
            } else {
                barangVendorHtml = `<div><span class="text-gray-500">Barang Vendor:</span> 
                    <span class="font-medium text-blue-600 cursor-pointer hover:underline" 
                          onclick="showBarangDetail('${selectedVendor.vendor.id_vendor}', ${JSON.stringify(barangList)}, '${selectedVendor.vendor.nama_vendor}', 'Modal Buat Pengiriman')">
                        <i class="fas fa-boxes mr-1"></i>${barangList[0]} & ${barangList.length - 1} lainnya
                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                    </span>
                    <div class="text-xs text-green-600 mt-1">
                        <i class="fas fa-layer-group mr-1"></i>${barangList.length} jenis barang
                    </div>
                </div>`;
            }
        }

        document.getElementById('infoProyek').innerHTML = `
            <h4 class="font-semibold text-gray-900 mb-2">Informasi Proyek & Vendor</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">No. Penawaran:</span> <span class="font-medium">${selectedProyek.penawaranAktif.no_penawaran}</span></div>
                <div><span class="text-gray-500">Nama Proyek:</span> <span class="font-medium">${selectedProyek.kode_proyek}</span></div>
                <div><span class="text-gray-500">Instansi:</span> <span class="font-medium">${selectedProyek.instansi}</span></div>
                <div><span class="text-gray-500">Vendor:</span> <span class="font-medium">${selectedVendor.vendor.nama_vendor}</span></div>
                ${barangVendorHtml}
                <div><span class="text-gray-500">Modal Vendor:</span> <span class="font-medium">Rp ${new Intl.NumberFormat('id-ID').format(selectedVendor.total_vendor)}</span></div>
                <div><span class="text-gray-500">Dibayar (Approved):</span> <span class="font-medium text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(selectedVendor.total_dibayar_approved)}</span></div>
                <div><span class="text-gray-500">Status:</span> <span class="font-medium ${selectedVendor.status_lunas ? 'text-green-600' : 'text-blue-600'}">
                    <i class="fas ${selectedVendor.status_lunas ? 'fa-check-circle' : 'fa-credit-card'} mr-1"></i>
                    ${selectedVendor.status_lunas ? 'Lunas' : Math.round((selectedVendor.total_dibayar_approved / selectedVendor.total_vendor) * 100) + '% Terbayar'}
                </span></div>
            </div>
        `;

        // Set default alamat dari vendor atau proyek
        let defaultAlamat = '';
        if (selectedVendor.vendor.alamat) {
            defaultAlamat = selectedVendor.vendor.alamat;
        } else if (selectedProyek.alamat_instansi) {
            defaultAlamat = selectedProyek.alamat_instansi;
        }
        
        if (defaultAlamat) {
            document.querySelector('textarea[name="alamat_kirim"]').value = defaultAlamat;
        }
    }

    // Set default date to today
    document.querySelector('input[name="tanggal_kirim"]').value = new Date().toISOString().split('T')[0];

    // Show modal
    document.getElementById('modalBuatPengiriman').classList.remove('hidden');
}

// Update dokumentasi
function updateDokumentasi(pengirimanId) {
    // Check access control - Allow admin_purchasing and superadmin
    if (!['admin_purchasing', 'superadmin'].includes(window.currentUserRole)) {
        alert('Tidak memiliki akses untuk mengupdate dokumentasi pengiriman. Hanya admin purchasing atau superadmin yang dapat melakukan aksi ini.');
        return;
    }

    // Set form action - gunakan route Laravel yang benar
    document.getElementById('formUpdateDokumentasi').action = `/purchasing/pengiriman/${pengirimanId}/update-dokumentasi`;
    
    // Find pengiriman data - perbaiki untuk handle pagination
    const pengirimanData = @json($pengirimanBerjalan->items()); // Ambil items() dari paginated data
    const pengiriman = pengirimanData.find(p => p.id_pengiriman == pengirimanId);
    
    // Check if current user is assigned to this project or is superadmin
    if (pengiriman && window.currentUserRole === 'admin_purchasing' && pengiriman.penawaran.proyek.id_admin_purchasing != window.currentUserId) {
        alert('Tidak memiliki akses untuk proyek ini. Hanya admin purchasing yang ditugaskan atau superadmin yang dapat mengupdate dokumentasi pengiriman untuk proyek ini.');
        return;
    }
    
    if (pengiriman) {
        // Generate barang list untuk pengiriman dengan smart display
        let barangPengirimanHtml = '';
        let barangListUpdate = [];
        
        if (pengiriman.barang_list && Array.isArray(pengiriman.barang_list) && pengiriman.barang_list.length > 0) {
            barangListUpdate = pengiriman.barang_list;
        }
        
        if (barangListUpdate.length > 0) {
            if (barangListUpdate.length === 1) {
                barangPengirimanHtml = `<div><span class="text-gray-500">Barang:</span> <span class="font-medium text-blue-600">
                    <i class="fas fa-box mr-1"></i>${barangListUpdate[0]}
                </span></div>`;
            } else if (barangListUpdate.length <= 3) {
                barangPengirimanHtml = `<div><span class="text-gray-500">Barang:</span> <span class="font-medium text-blue-600">
                    <i class="fas fa-boxes mr-1"></i>${barangListUpdate.join(', ')}
                </span></div>`;
            } else {
                barangPengirimanHtml = `<div><span class="text-gray-500">Barang:</span> 
                    <span class="font-medium text-blue-600 cursor-pointer hover:underline" 
                          onclick="showBarangDetail('${pengiriman.id_vendor}', ${JSON.stringify(barangListUpdate)}, '${pengiriman.vendor.nama_vendor}', 'Update Dokumentasi - ${pengiriman.no_surat_jalan}')">
                        <i class="fas fa-boxes mr-1"></i>${barangListUpdate[0]} & ${barangListUpdate.length - 1} lainnya
                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                    </span>
                    <div class="text-xs text-green-600 mt-1">
                        <i class="fas fa-layer-group mr-1"></i>${barangListUpdate.length} jenis barang
                    </div>
                </div>`;
            }
        }

        document.getElementById('infoPengirimanUpdate').innerHTML = `
            <h4 class="font-semibold text-gray-900 mb-2">Informasi Pengiriman</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Surat Jalan:</span> <span class="font-medium">${pengiriman.no_surat_jalan}</span></div>
                <div><span class="text-gray-500">Proyek:</span> <span class="font-medium">${pengiriman.penawaran.proyek.kode_proyek}</span></div>
                <div><span class="text-gray-500">Vendor:</span> <span class="font-medium">${pengiriman.vendor.nama_vendor}</span></div>
                ${barangPengirimanHtml}
                <div><span class="text-gray-500">Status:</span> <span class="font-medium">${pengiriman.status_verifikasi.replace('_', ' ')}</span></div>
            </div>
        `;

        // Build dokumentasi fields dengan status file yang sudah ada
        const dokumentasiFields = [
            { 
                name: 'foto_berangkat', 
                label: 'Foto Keberangkatan', 
                accept: '.jpg,.jpeg,.png',
                icon: 'fas fa-camera',
                current: pengiriman.foto_berangkat 
            },
            { 
                name: 'foto_perjalanan', 
                label: 'Foto Perjalanan', 
                accept: '.jpg,.jpeg,.png',
                icon: 'fas fa-road',
                current: pengiriman.foto_perjalanan 
            },
            { 
                name: 'foto_sampai', 
                label: 'Foto Sampai Tujuan', 
                accept: '.jpg,.jpeg,.png',
                icon: 'fas fa-map-marker-alt',
                current: pengiriman.foto_sampai 
            },
            { 
                name: 'tanda_terima', 
                label: 'Tanda Terima', 
                accept: '.pdf,.jpg,.jpeg,.png',
                icon: 'fas fa-signature',
                current: pengiriman.tanda_terima 
            }
        ];

        const fieldsHtml = dokumentasiFields.map(field => {
            const hasFile = field.current && field.current.trim() !== '';
            const statusBadge = hasFile ? 
                `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-2">
                    <i class="fas fa-check-circle mr-1"></i> File tersedia
                </span>` :
                `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 mb-2">
                    <i class="fas fa-times-circle mr-1"></i> Belum ada file
                </span>`;
            
            const currentFileInfo = hasFile ? 
                `<div class="mt-2 p-2 bg-blue-50 rounded border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-blue-700">
                            <i class="${field.icon} mr-2"></i>
                            <span>File saat ini: ${field.current.split('/').pop()}</span>
                        </div>
                        <button type="button" onclick="viewFile('${field.current}')" 
                                class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-eye mr-1"></i> Lihat
                        </button>
                    </div>
                </div>` : '';

            return `
                <div class="border border-gray-200 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="${field.icon} mr-2"></i>${field.label}
                    </label>
                    ${statusBadge}
                    <input type="file" name="${field.name}" accept="${field.accept}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">
                        ${hasFile ? 'Upload file baru untuk mengganti file yang ada' : 'Belum ada file, upload untuk menambahkan'}
                    </p>
                    ${currentFileInfo}
                </div>
            `;
        }).join('');

        document.getElementById('formDokumentasiFields').innerHTML = fieldsHtml;
    }

    // Show modal
    document.getElementById('modalUpdateDokumentasi').classList.remove('hidden');
}

// Lihat detail pengiriman selesai
function lihatDetailSelesai(pengirimanId) {
    // Find pengiriman data - perbaiki untuk handle pagination
    const pengirimanData = @json($pengirimanSelesai->items()); // Ambil items() dari paginated data
    const pengiriman = pengirimanData.find(p => p.id_pengiriman == pengirimanId);
    
    if (pengiriman) {
        // Fill header info
        document.getElementById('headerDetailSelesai').innerHTML = `
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="text-xl font-bold">${pengiriman.penawaran.proyek.kode_proyek}</h4>
                    <p class="text-green-100">${pengiriman.penawaran.proyek.instansi}</p>
                    <p class="text-green-100 text-sm"><i class="fas fa-building mr-1"></i>${pengiriman.vendor.nama_vendor}</p>
                </div>
                <div class="text-right">
                    ${pengiriman.penawaran.proyek.status === 'Selesai' ? 
                        `<span class="bg-white text-green-600 px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Verified
                        </span>` :
                        `<span class="bg-white text-blue-600 px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-truck mr-1"></i> Dokumentasi Lengkap
                        </span>`
                    }
                    <p class="text-green-100 text-sm mt-1">Surat Jalan: ${pengiriman.no_surat_jalan}</p>
                </div>
            </div>
        `;

        // Generate barang list untuk modal detail dengan smart display
        let barangDetailHtml = '';
        let barangListModal = [];
        
        if (pengiriman.barang_list && Array.isArray(pengiriman.barang_list) && pengiriman.barang_list.length > 0) {
            barangListModal = pengiriman.barang_list;
        }
        
        if (barangListModal.length > 0) {
            if (barangListModal.length === 1) {
                barangDetailHtml = `<div><span class="text-gray-500">Barang Dikirim:</span> <span class="font-medium text-blue-600">
                    <i class="fas fa-box mr-1"></i>${barangListModal[0]}
                </span></div>`;
            } else if (barangListModal.length <= 3) {
                barangDetailHtml = `<div><span class="text-gray-500">Barang Dikirim:</span> <span class="font-medium text-blue-600">
                    <i class="fas fa-boxes mr-1"></i>${barangListModal.join(', ')}
                </span></div>`;
            } else {
                barangDetailHtml = `<div><span class="text-gray-500">Barang Dikirim:</span> 
                    <span class="font-medium text-blue-600 cursor-pointer hover:underline" 
                          onclick="showBarangDetail('${pengiriman.id_vendor}', ${JSON.stringify(barangListModal)}, '${pengiriman.vendor.nama_vendor}', 'Detail Pengiriman Selesai - ${pengiriman.no_surat_jalan}')">
                        <i class="fas fa-boxes mr-1"></i>${barangListModal[0]} & ${barangListModal.length - 1} lainnya
                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                    </span>
                    <div class="text-xs text-green-600 mt-1">
                        <i class="fas fa-layer-group mr-1"></i>${barangListModal.length} jenis barang
                    </div>
                </div>`;
            }
        } else {
            barangDetailHtml = `<div><span class="text-gray-500">Barang Dikirim:</span> <span class="text-gray-400 text-sm">
                <i class="fas fa-question-circle mr-1"></i>Data tidak tersedia
            </span></div>`;
        }

        // Fill project info
        document.getElementById('infoProyekDetail').innerHTML = `
            <div><span class="text-gray-500">No. Penawaran:</span> <span class="font-medium">${pengiriman.penawaran.no_penawaran || 'N/A'}</span></div>
            <div><span class="text-gray-500">Nama Proyek:</span> <span class="font-medium">${pengiriman.penawaran.proyek.kode_proyek}</span></div>
            <div><span class="text-gray-500">Instansi:</span> <span class="font-medium">${pengiriman.penawaran.proyek.instansi}</span></div>
            <div><span class="text-gray-500">Vendor:</span> <span class="font-medium">${pengiriman.vendor.nama_vendor}</span></div>
            <div><span class="text-gray-500">Tanggal Kirim:</span> <span class="font-medium">${new Date(pengiriman.tanggal_kirim).toLocaleDateString('id-ID')}</span></div>
            ${barangDetailHtml}
        `;

        // Fill shipping info
        document.getElementById('infoPengirimanDetailSelesai').innerHTML = `
            <div><span class="text-gray-500">No. Surat Jalan:</span> <span class="font-medium">${pengiriman.no_surat_jalan}</span></div>
            <div><span class="text-gray-500">Status:</span> <span class="font-medium">${pengiriman.penawaran.proyek.status === 'Selesai' ? 'Verified' : 'Dokumentasi Lengkap'}</span></div>
            <div><span class="text-gray-500">Status Proyek:</span> <span class="font-medium">${pengiriman.penawaran.proyek.status}</span></div>
            <div><span class="text-gray-500">Alamat Kirim:</span> <span class="font-medium">${pengiriman.alamat_kirim || 'Tidak tersedia'}</span></div>
            <div><span class="text-gray-500">Tanggal Update:</span> <span class="font-medium">${new Date(pengiriman.updated_at).toLocaleDateString('id-ID')}</span></div>
        `;

        // Fill dokumentasi
        fillDokumentasiSelesai(pengiriman);

        // Fill timeline
        fillTimelineSelesai(pengiriman);

        // Reset to first tab
        switchDetailTab('info');

        // Show modal
        document.getElementById('modalDetailSelesai').classList.remove('hidden');
    }
}

// Fill dokumentasi selesai
function fillDokumentasiSelesai(pengiriman) {
    const dokumentasiList = [
        { key: 'file_surat_jalan', label: 'File Surat Jalan', icon: 'fas fa-file-pdf' },
        { key: 'foto_berangkat', label: 'Foto Keberangkatan', icon: 'fas fa-camera' },
        { key: 'foto_perjalanan', label: 'Foto Perjalanan', icon: 'fas fa-road' },
        { key: 'foto_sampai', label: 'Foto Sampai Tujuan', icon: 'fas fa-map-marker-alt' },
        { key: 'tanda_terima', label: 'Tanda Terima', icon: 'fas fa-signature' }
    ];

    const dokumentasiHtml = dokumentasiList.map(dok => {
        const isAvailable = pengiriman[dok.key];
        const statusClass = isAvailable ? 'text-green-600' : 'text-gray-400';
        const statusIcon = isAvailable ? 'fas fa-check-circle' : 'fas fa-times-circle';
        const actionButton = isAvailable ? 
            `<button onclick="viewFile('${pengiriman[dok.key]}')" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-eye mr-1"></i> Lihat
            </button>` : 
            '<span class="text-gray-400 text-sm">Tidak ada</span>';

        return `
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="${dok.icon} ${statusClass} mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">${dok.label}</p>
                            <p class="text-sm ${statusClass}">
                                <i class="${statusIcon} mr-1"></i>
                                ${isAvailable ? 'Tersedia' : 'Tidak Tersedia'}
                            </p>
                        </div>
                    </div>
                    <div>
                        ${actionButton}
                    </div>
                </div>
            </div>
        `;
    }).join('');

    document.getElementById('dokumentasiSelesaiContent').innerHTML = `
        <div class="grid grid-cols-1 gap-4">
            <h4 class="font-semibold text-gray-900 mb-4">Dokumentasi Lengkap</h4>
            ${dokumentasiHtml}
        </div>
    `;
}

// Fill timeline selesai
function fillTimelineSelesai(pengiriman) {
    const timelineData = [
        {
            title: 'Pengiriman Dibuat',
            date: pengiriman.created_at,
            status: 'completed',
            description: `Surat jalan ${pengiriman.no_surat_jalan} dibuat dan pengiriman dimulai`
        },
        {
            title: 'Dokumentasi Keberangkatan',
            date: pengiriman.created_at,
            status: pengiriman.foto_berangkat ? 'completed' : 'pending',
            description: 'Foto keberangkatan diupload'
        },
        {
            title: 'Dalam Perjalanan',
            date: pengiriman.updated_at,
            status: pengiriman.foto_perjalanan ? 'completed' : 'pending',
            description: 'Foto perjalanan diupload'
        },
        {
            title: 'Sampai Tujuan',
            date: pengiriman.updated_at,
            status: pengiriman.foto_sampai ? 'completed' : 'pending',
            description: 'Foto sampai tujuan diupload'
        },
        {
            title: 'Selesai',
            date: pengiriman.updated_at,
            status: pengiriman.tanda_terima ? 'completed' : 'pending',
            description: 'Tanda terima diterima dan pengiriman selesai'
        }
    ];

    const timelineHtml = timelineData.map((item, index) => {
        const isCompleted = item.status === 'completed';
        const statusClass = isCompleted ? 'bg-green-500' : 'bg-gray-300';
        const lineClass = index < timelineData.length - 1 ? (isCompleted ? 'bg-green-500' : 'bg-gray-300') : '';
        
        return `
            <div class="flex">
                <div class="flex flex-col items-center mr-4">
                    <div class="w-4 h-4 ${statusClass} rounded-full flex items-center justify-center">
                        ${isCompleted ? '<i class="fas fa-check text-white text-xs"></i>' : ''}
                    </div>
                    ${index < timelineData.length - 1 ? `<div class="w-0.5 h-16 ${lineClass}"></div>` : ''}
                </div>
                <div class="flex-1 pb-8">
                    <div class="flex items-center justify-between">
                        <h5 class="font-medium text-gray-900">${item.title}</h5>
                        <span class="text-sm text-gray-500">${new Date(item.date).toLocaleDateString('id-ID')}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">${item.description}</p>
                </div>
            </div>
        `;
    }).join('');

    document.getElementById('timelineSelesaiContent').innerHTML = `
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-6">Timeline Pengiriman</h4>
            <div class="space-y-0">
                ${timelineHtml}
            </div>
        </div>
    `;
}

// Switch detail modal tabs
function switchDetailTab(tabName) {
    // Hide all detail tab contents
    document.querySelectorAll('.detail-tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all detail tabs
    document.querySelectorAll('#modalDetailSelesai nav button').forEach(tab => {
        tab.classList.remove('border-green-500', 'text-green-600', 'detail-tab-active');
        tab.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    const contentMap = {
        'info': 'contentInfo',
        'dokumentasi': 'contentDokumentasi', 
        'timeline': 'contentTimeline'
    };

    if (contentMap[tabName]) {
        document.getElementById(contentMap[tabName]).classList.remove('hidden');
    }

    // Add active class to selected tab
    const tabButton = document.querySelector(`#modalDetailSelesai button[onclick="switchDetailTab('${tabName}')"]`);
    if (tabButton) {
        tabButton.classList.remove('border-transparent', 'text-gray-500');
        tabButton.classList.add('border-green-500', 'text-green-600', 'detail-tab-active');
    }
}

// Fungsi untuk melihat file yang sudah diupload
function viewFile(filePath) {
    if (filePath) {
        let fullUrl;
        // Tentukan path berdasarkan jenis file
        if (filePath.includes('_surat_jalan_')) {
            fullUrl = `/storage/pengiriman/surat_jalan/${filePath}`;
        } else if (filePath.includes('_foto_') || filePath.includes('_tanda_terima_')) {
            fullUrl = `/storage/pengiriman/dokumentasi/${filePath}`;
        } else {
            // Fallback untuk kompatibilitas file lama
            fullUrl = `/storage/${filePath}`;
        }
        window.open(fullUrl, '_blank');
    }
}

// Print and download functions
function printDetailSelesai() {
    alert('Fitur cetak laporan akan membuat dokumen PDF lengkap dengan semua dokumentasi pengiriman untuk keperluan arsip dan audit.');
}

function downloadDetailSelesai() {
    alert('Fitur download PDF akan mengunduh laporan lengkap pengiriman dalam format PDF yang dapat disimpan sebagai dokumentasi.');
}

// Tutup modal
function tutupModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        tutupModal(e.target.id);
    }
});

// Show barang detail modal
function showBarangDetail(vendorId, barangList, vendorName, subtitle = '') {
    // Set title and subtitle
    document.getElementById('modalBarangTitle').textContent = `Detail Barang - ${vendorName}`;
    document.getElementById('modalBarangSubtitle').textContent = subtitle || 'Daftar lengkap barang untuk vendor ini';
    
    // Set total count
    document.getElementById('totalBarangCount').textContent = barangList.length;
    
    // Store original list for searching
    window.currentBarangList = barangList;
    
    // Render barang list
    renderBarangList(barangList, '');
    
    // Clear search
    document.getElementById('searchBarang').value = '';
    
    // Setup search functionality
    setupBarangSearch();
    
    // Show modal
    document.getElementById('modalDetailBarang').classList.remove('hidden');
    
    // Focus on search input after a short delay
    setTimeout(() => {
        document.getElementById('searchBarang').focus();
    }, 100);
}

// Render barang list
function renderBarangList(barangList, searchTerm = '') {
    const container = document.getElementById('listBarang');
    const noResults = document.getElementById('noResults');
    
    if (barangList.length === 0) {
        container.innerHTML = '';
        noResults.classList.remove('hidden');
        return;
    }
    
    noResults.classList.add('hidden');
    
    const html = barangList.map((barang, index) => {
        let displayName = barang;
        
        // Highlight search term
        if (searchTerm && searchTerm.length > 0) {
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            displayName = barang.replace(regex, '<span class="search-highlight font-semibold">$1</span>');
        }
        
        return `
            <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg barang-item-hover transition-all duration-200 cursor-default">
                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-medium text-sm mr-3 shadow-sm">
                    ${index + 1}
                </div>
                <div class="flex-grow">
                    <div class="font-medium text-gray-900">${displayName}</div>
                    <div class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-tag mr-1"></i>Item ${index + 1} dari ${barangList.length}
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-50 rounded-full flex items-center justify-center">
                        <i class="fas fa-box text-blue-500"></i>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = html;
}

// Setup search functionality
function setupBarangSearch() {
    const searchInput = document.getElementById('searchBarang');
    
    // Remove existing event listeners
    const newSearchInput = searchInput.cloneNode(true);
    searchInput.parentNode.replaceChild(newSearchInput, searchInput);
    
    // Add new event listener
    document.getElementById('searchBarang').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        
        if (searchTerm === '') {
            renderBarangList(window.currentBarangList, '');
            document.getElementById('totalBarangCount').textContent = window.currentBarangList.length;
            return;
        }
        
        const filteredList = window.currentBarangList.filter(barang => 
            barang.toLowerCase().includes(searchTerm)
        );
        
        renderBarangList(filteredList, searchTerm);
        
        // Update count with search indicator
        document.getElementById('totalBarangCount').textContent = 
            `${filteredList.length} / ${window.currentBarangList.length}`;
    });
    
    // Reset count display
    document.getElementById('totalBarangCount').textContent = window.currentBarangList.length;
}

// Enhanced modal closing
function tutupModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    
    // Reset search if closing barang modal
    if (modalId === 'modalDetailBarang') {
        document.getElementById('searchBarang').value = '';
        if (window.currentBarangList) {
            renderBarangList(window.currentBarangList, '');
            document.getElementById('totalBarangCount').textContent = window.currentBarangList.length;
        }
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(modal => {
            if (modal.id === 'modalDetailBarang') {
                tutupModal('modalDetailBarang');
            } else {
                modal.classList.add('hidden');
            }
        });
    }
    
    // Quick search with Ctrl+F when barang modal is open
    if (e.ctrlKey && e.key === 'f' && !document.getElementById('modalDetailBarang').classList.contains('hidden')) {
        e.preventDefault();
        document.getElementById('searchBarang').focus();
        document.getElementById('searchBarang').select();
    }
});

// Handle tab navigation on page load based on URL parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || '{{ $activeTab ?? "ready" }}';
    
    if (activeTab) {
        switchTab(activeTab);
    }
});
</script>

<!-- Include Edit Pengiriman Component -->
@include('pages.purchasing.pengiriman-components.edit')

@endsection
