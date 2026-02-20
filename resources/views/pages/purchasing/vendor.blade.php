@extends('layouts.app')

@section('title', 'Manajemen Vendor - Cyber KATANA')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Manajemen Vendor</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan pantau semua vendor</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-handshake text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-handshake text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Vendor</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">{{ $totalVendors }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-building text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Principle</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">{{ $vendorPrinciple }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-truck text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Distributor</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">{{ $vendorDistributor }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-store text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Retail</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">{{ $vendorRetail }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Role Information Alert -->
@auth
    @if(auth()->user()->role !== 'admin_purchasing' && auth()->user()->role !== 'superadmin')
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-800">
                        <span class="font-medium">Info:</span> 
                        Anda hanya memiliki akses untuk melihat detail vendor. Hanya admin purchasing dan superadmin yang dapat menambah, mengedit, atau menghapus data vendor.
                    </p>
                </div>
            </div>
        </div>
    @endif
@endauth

<!-- Vendor Table -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-20">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Daftar Vendor</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola semua data vendor dan informasinya</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
        <!-- Active Filters Display -->
        @if(request()->hasAny(['search', 'jenis', 'pkp', 'online_shop']))
        <div class="mb-4 flex flex-wrap items-center gap-2">
            <span class="text-sm font-medium text-gray-600">Filter Aktif:</span>
            @if(request('search'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-search mr-1"></i>{{ request('search') }}
                </span>
            @endif
            @if(request('jenis'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-building mr-1"></i>{{ request('jenis') }}
                </span>
            @endif
            @if(request('pkp'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>{{ request('pkp') == 'ya' ? 'PKP' : 'Non-PKP' }}
                </span>
            @endif
            @if(request('online_shop'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    <i class="fas fa-store mr-1"></i>{{ request('online_shop') == 'ya' ? 'Ada Online Shop' : 'Tidak Ada Online Shop' }}
                </span>
            @endif
        </div>
        @endif
        
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchVendor" placeholder="Cari vendor..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base"
                           onkeyup="debounceSearch()">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <select id="filterJenis" onchange="filterVendors()" class="px-3 py-2.5 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                    <option value="">Semua Jenis</option>
                    <option value="Principle" {{ request('jenis') == 'Principle' ? 'selected' : '' }}>Principle</option>
                    <option value="Distributor" {{ request('jenis') == 'Distributor' ? 'selected' : '' }}>Distributor</option>
                    <option value="Retail" {{ request('jenis') == 'Retail' ? 'selected' : '' }}>Retail</option>
                    <option value="Lain-lain" {{ request('jenis') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                </select>
                <select id="filterPkp" onchange="filterVendors()" class="px-3 py-2.5 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm sm:text-base">
                    <option value="">Semua Status PKP</option>
                    <option value="ya" {{ request('pkp') == 'ya' ? 'selected' : '' }}>PKP</option>
                    <option value="tidak" {{ request('pkp') == 'tidak' ? 'selected' : '' }}>Non-PKP</option>
                </select>
                <select id="filterOnlineShop" onchange="filterVendors()" class="px-3 py-2.5 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                    <option value="">Semua Online Shop</option>
                    <option value="ya" {{ request('online_shop') == 'ya' ? 'selected' : '' }}>Ada Online Shop</option>
                    <option value="tidak" {{ request('online_shop') == 'tidak' ? 'selected' : '' }}>Tidak Ada</option>
                </select>
                <button onclick="clearFilters()" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg sm:rounded-xl transition-colors text-sm sm:text-base font-medium">
                    <i class="fas fa-times mr-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gradient-to-r from-gray-100 to-gray-50">
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-800 uppercase tracking-wider border border-gray-300" rowspan="2" style="width: 50px;">
                        No
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-800 uppercase tracking-wider border border-gray-300">
                        Nama Vendor
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-800 uppercase tracking-wider border border-gray-300">
                        Jenis
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-800 uppercase tracking-wider border border-gray-300">
                        Lainnya
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-800 uppercase tracking-wider border border-gray-300">
                        Kontak
                    </th>
                    <th class="px-4 py-3 text-center text-sm font-bold text-gray-800 uppercase tracking-wider border border-gray-300" rowspan="2" style="width: 120px;">
                        Aksi
                    </th>
                </tr>
                
            </thead>
            <tbody class="bg-white" id="vendorTableBody">
                @foreach($vendors as $index => $vendor)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-4 text-center text-sm text-gray-900 font-semibold border border-gray-200">
                        {{ ($vendors->currentPage() - 1) * $vendors->perPage() + $index + 1 }}
                    </td>
                    <td class="px-4 py-3 border border-gray-200">
                        <div class="space-y-1">
                            <div class="text-sm font-bold text-gray-900">{{ $vendor->nama_vendor }}</div>
                            <div class="text-xs text-gray-600">{{ $vendor->keterangan ?: '-' }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 border border-gray-200">
                        <div class="space-y-2">
                            <div class="text-sm font-medium text-gray-900">{{ $vendor->jenis_perusahaan }}</div>
                            <div>
                                @if($vendor->pkp === 'ya')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>PKP
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        -
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 border border-gray-200">
                        <div class="space-y-2">
                            <div>
                                @if($vendor->online_shop === 'ya')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        <i class="fas fa-check mr-1"></i>Ya
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        -
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-700">{{ $vendor->nama_online_shop ?: '-' }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 border border-gray-200">
                        <div class="space-y-1">
                            <div class="text-sm text-gray-900">
                                <i class="fas fa-phone text-green-600 mr-1"></i>{{ $vendor->kontak ?: '-' }}
                            </div>
                            <div class="text-xs text-gray-700">
                                <i class="fas fa-envelope text-blue-600 mr-1"></i>{{ $vendor->email ?: '-' }}
                            </div>
                            <div class="text-xs text-gray-600">
                                <i class="fas fa-map-marker-alt text-red-600 mr-1"></i>{{ Str::limit($vendor->alamat, 40) ?: '-' }}
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-center border border-gray-200">
                        <div class="flex items-center justify-center space-x-1">
                            <button onclick="detailVendor({{ $vendor->id_vendor }})" 
                                    class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" 
                                    title="Lihat Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            @auth
                                @if(auth()->user()->role === 'admin_purchasing' || auth()->user()->role === 'superadmin')
                                    <button onclick="editVendor({{ $vendor->id_vendor }})" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 hover:bg-yellow-100 rounded-lg transition-colors" 
                                            title="Edit Vendor">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button onclick="hapusVendor({{ $vendor->id_vendor }})" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-100 rounded-lg transition-colors" 
                                            title="Hapus Vendor">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="block md:hidden">
        <div class="p-4 space-y-4" id="vendorMobileList">
            @foreach($vendors as $index => $vendor)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm vendor-card">
                <div class="p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <span class="text-red-600 font-bold">{{ ($vendors->currentPage() - 1) * $vendors->perPage() + $index + 1 }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $vendor->nama_vendor }}</h3>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $vendor->jenis_perusahaan }} • {{ Str::limit($vendor->alamat, 20) }}</p>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">{{ $vendor->email }}</p>
                                <p class="text-sm text-gray-700">{{ $vendor->kontak }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                    {{ $vendor->jenis_perusahaan }}
                                </span>
                            </div>
                            <div class="flex items-center justify-end mt-3 space-x-3">
                                <button onclick="detailVendor({{ $vendor->id_vendor }})" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                                @auth
                                    @if(auth()->user()->role === 'admin_purchasing' || auth()->user()->role === 'superadmin')
                                        <button onclick="editVendor({{ $vendor->id_vendor }})" class="text-yellow-600 hover:text-yellow-900 transition-colors p-2" title="Edit Vendor">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>
                                        <button onclick="hapusVendor({{ $vendor->id_vendor }})" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus Vendor">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    @if($vendors->hasPages())
    <div class="p-4 sm:p-6 border-t border-gray-200">
        <!-- Mobile Pagination -->
        <div class="flex md:hidden items-center justify-between">
            <div class="text-sm text-gray-600">
                Halaman {{ $vendors->currentPage() }} dari {{ $vendors->lastPage() }}
            </div>
            <div class="flex space-x-2">
                @if($vendors->onFirstPage())
                    <span class="flex items-center justify-center w-10 h-10 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $vendors->appends(request()->query())->previousPageUrl() }}" 
                       class="flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif
                
                @if($vendors->hasMorePages())
                    <a href="{{ $vendors->appends(request()->query())->nextPageUrl() }}" 
                       class="flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="flex items-center justify-center w-10 h-10 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>

        <!-- Desktop Pagination -->
        <div class="hidden md:flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $vendors->firstItem() ?? 0 }} - {{ $vendors->lastItem() ?? 0 }} dari {{ $vendors->total() }} vendor
            </div>
            
            <div class="flex items-center space-x-1">
                {{-- Previous Page Link --}}
                @if($vendors->onFirstPage())
                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left mr-1"></i>Previous
                    </span>
                @else
                    <a href="{{ $vendors->appends(request()->query())->previousPageUrl() }}" 
                       class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-chevron-left mr-1"></i>Previous
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $start = max(1, $vendors->currentPage() - 2);
                    $end = min($vendors->lastPage(), $vendors->currentPage() + 2);
                @endphp
                
                @if($start > 1)
                    <a href="{{ $vendors->appends(request()->query())->url(1) }}" 
                       class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        1
                    </a>
                    @if($start > 2)
                        <span class="px-3 py-2 text-sm text-gray-400">...</span>
                    @endif
                @endif
                
                @for($i = $start; $i <= $end; $i++)
                    @if($i == $vendors->currentPage())
                        <span class="px-3 py-2 text-sm bg-red-600 text-white rounded-lg font-medium">{{ $i }}</span>
                    @else
                        <a href="{{ $vendors->appends(request()->query())->url($i) }}" 
                           class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            {{ $i }}
                        </a>
                    @endif
                @endfor
                
                @if($end < $vendors->lastPage())
                    @if($end < $vendors->lastPage() - 1)
                        <span class="px-3 py-2 text-sm text-gray-400">...</span>
                    @endif
                    <a href="{{ $vendors->appends(request()->query())->url($vendors->lastPage()) }}" 
                       class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        {{ $vendors->lastPage() }}
                    </a>
                @endif

                {{-- Next Page Link --}}
                @if($vendors->hasMorePages())
                    <a href="{{ $vendors->appends(request()->query())->nextPageUrl() }}" 
                       class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Next<i class="fas fa-chevron-right ml-1"></i>
                    </a>
                @else
                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        Next<i class="fas fa-chevron-right ml-1"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Floating Action Button -->
@auth
    @if(auth()->user()->role === 'admin_purchasing' || auth()->user()->role === 'superadmin')
        <button onclick="tambahVendor()" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 lg:bottom-16 lg:right-16 bg-red-600 hover:bg-red-700 text-white w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 z-50" title="Tambah Vendor Baru">
            <i class="fas fa-plus text-lg sm:text-xl"></i>
        </button>
    @endif
@endauth

@include('pages.purchasing.vendor-components.tambah')
@include('pages.purchasing.vendor-components.edit')
@include('pages.purchasing.vendor-components.detail')
@include('pages.purchasing.vendor-components.hapus')
@include('components.success-modal')

<script>
// Global variables
let vendorProducts = [];
let editVendorProducts = [];
let productIdCounter = 1;
let userRole = @json(auth()->user()->role ?? 'guest');

// Modal functions
function tambahVendor() {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk menambah vendor', 'error');
        return;
    }
    
    // Reset form
    document.getElementById('formTambahVendor').reset();
    vendorProducts = [];
    updateVendorProductList();
    showModal('modalTambahVendor');
}

function editVendor(id) {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk mengedit vendor', 'error');
        return;
    }
    
    // Fetch vendor data
    fetch(`/purchasing/vendor/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const vendor = data.vendor;
                
                // Populate form
                document.getElementById('editVendorId').value = vendor.id_vendor;
                document.getElementById('editNamaVendor').value = vendor.nama_vendor;
                document.getElementById('editEmailVendor').value = vendor.email;
                document.getElementById('editJenisPerusahaan').value = vendor.jenis_perusahaan;
                document.getElementById('editKontakVendor').value = vendor.kontak;
                document.getElementById('editAlamatVendor').value = vendor.alamat || '';
                
                // Populate new fields
                const pkpCheckbox = document.getElementById('editPkpVendor');
                if (pkpCheckbox) pkpCheckbox.checked = vendor.pkp === 'ya';
                
                document.getElementById('editKeteranganVendor').value = vendor.keterangan || '';
                
                const onlineShopCheckbox = document.getElementById('editOnlineShopVendor');
                if (onlineShopCheckbox) {
                    onlineShopCheckbox.checked = vendor.online_shop === 'ya';
                    toggleEditOnlineShopInput();
                }
                
                document.getElementById('editNamaOnlineShop').value = vendor.nama_online_shop || '';
                
                // Load existing barang
                editVendorProducts = vendor.barang.map(barang => ({
                    id_barang: barang.id_barang,
                    nama_barang: barang.nama_barang,
                    brand: barang.brand,
                    kategori: barang.kategori,
                    satuan: barang.satuan,
                    spesifikasi: barang.spesifikasi,
                    harga_vendor: barang.harga_vendor,
                    harga_pasaran_inaproc: barang.harga_pasaran_inaproc,
                    spesifikasi_kunci: barang.spesifikasi_kunci,
                    garansi: barang.garansi,
                    pdn_tkdn_impor: barang.pdn_tkdn_impor,
                    skor_tkdn: barang.skor_tkdn,
                    link_tkdn: barang.link_tkdn,
                    estimasi_ketersediaan: barang.estimasi_ketersediaan,
                    link_produk: barang.link_produk,
                    foto_barang: barang.foto_barang,
                    spesifikasi_file: barang.spesifikasi_file
                }));
                
                updateEditVendorProductList();
                showModal('modalEditVendor');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data vendor');
        });
}

function detailVendor(id) {
    // Fetch vendor data
    fetch(`/purchasing/vendor/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const vendor = data.vendor;
                
                // Populate detail modal
                document.getElementById('detailNamaVendor').textContent = vendor.nama_vendor;
                document.getElementById('detailJenisPerusahaan').textContent = vendor.jenis_perusahaan;
                document.getElementById('detailEmailVendor').textContent = vendor.email;
                document.getElementById('detailKontakVendor').textContent = vendor.kontak;
                document.getElementById('detailAlamatVendor').textContent = vendor.alamat || '-';
                
                // Populate new fields with badges
                const pkpElement = document.getElementById('detailPkpVendor');
                if (vendor.pkp === 'ya') {
                    pkpElement.innerHTML = '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold"><i class="fas fa-check-circle mr-1"></i>Ya, PKP</span>';
                } else {
                    pkpElement.innerHTML = '<span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm"><i class="fas fa-times-circle mr-1"></i>Bukan PKP</span>';
                }
                
                const onlineShopElement = document.getElementById('detailOnlineShopVendor');
                const namaOnlineShopContainer = document.getElementById('detailNamaOnlineShopContainer');
                if (vendor.online_shop === 'ya') {
                    onlineShopElement.innerHTML = '<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold"><i class="fas fa-store mr-1"></i>Ya, ada online shop</span>';
                    namaOnlineShopContainer.style.display = 'block';
                    document.getElementById('detailNamaOnlineShop').textContent = vendor.nama_online_shop || '-';
                } else {
                    onlineShopElement.innerHTML = '<span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm"><i class="fas fa-times-circle mr-1"></i>Tidak ada</span>';
                    namaOnlineShopContainer.style.display = 'none';
                }
                
                document.getElementById('detailKeteranganVendor').textContent = vendor.keterangan || '-';
                
                // Show products
                const productListHtml = vendor.barang.map(barang => `
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                        <div>
                            <p class="font-medium">${barang.nama_barang}</p>
                            <p class="text-sm text-gray-500">${barang.brand} • ${barang.kategori}</p>
                            <p class="text-sm text-blue-600">Rp ${Number(barang.harga_vendor).toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                `).join('');
                
                document.getElementById('detailProductList').innerHTML = productListHtml || '<p class="text-gray-500">Tidak ada produk</p>';
                
                showModal('modalDetailVendor');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data vendor');
        });
}

function hapusVendor(id) {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk menghapus vendor', 'error');
        return;
    }
    
    // Fetch vendor data to get the name
    fetch(`/purchasing/vendor/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('hapusVendorId').value = id;
                document.getElementById('hapusVendorNama').textContent = data.vendor.nama_vendor;
                showModal('modalHapusVendor');
            } else {
                showToast('Gagal memuat data vendor', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat memuat data vendor', 'error');
        });
}

// Modal utility functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        
        // Reset edit mode when closing edit vendor modal
        if (modalId === 'modalEditVendor') {
            resetProductEditMode();
            clearEditProductForm();
        }
    }
}

// Legacy close functions
function closeTambahVendor() { closeModal('modalTambahVendor'); }
function closeEditVendor() { closeModal('modalEditVendor'); }
function closeDetailVendor() { closeModal('modalDetailVendor'); }
function closeHapusVendor() { closeModal('modalHapusVendor'); }

// Form submit functions
function submitTambahVendor() {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk menambah vendor', 'error');
        return;
    }
    
    console.log('=== SUBMITTING VENDOR ===');
    console.log('User role:', userRole);
    console.log('Vendor products:', vendorProducts);
    
    const form = document.getElementById('formTambahVendor');
    const formData = new FormData(form);
    
    // Handle PKP checkbox
    const pkpCheckbox = document.getElementById('pkpVendor');
    formData.set('pkp', pkpCheckbox && pkpCheckbox.checked ? 'ya' : 'tidak');
    
    // Handle Online Shop checkbox
    const onlineShopCheckbox = document.getElementById('onlineShopVendor');
    formData.set('online_shop', onlineShopCheckbox && onlineShopCheckbox.checked ? 'ya' : 'tidak');
    
    // Add barang data
    vendorProducts.forEach((product, index) => {
        console.log(`Adding product ${index}:`, product);
        formData.append(`barang[${index}][nama_barang]`, product.nama_barang);
        formData.append(`barang[${index}][brand]`, product.brand);
        formData.append(`barang[${index}][kategori]`, product.kategori);
        formData.append(`barang[${index}][satuan]`, product.satuan);
        formData.append(`barang[${index}][spesifikasi]`, product.spesifikasi);
        formData.append(`barang[${index}][harga_vendor]`, product.harga_vendor);
        
        // Add new fields
        if (product.harga_pasaran_inaproc) {
            formData.append(`barang[${index}][harga_pasaran_inaproc]`, product.harga_pasaran_inaproc);
        }
        if (product.spesifikasi_kunci) {
            formData.append(`barang[${index}][spesifikasi_kunci]`, product.spesifikasi_kunci);
        }
        if (product.garansi) {
            formData.append(`barang[${index}][garansi]`, product.garansi);
        }
        if (product.pdn_tkdn_impor) {
            formData.append(`barang[${index}][pdn_tkdn_impor]`, product.pdn_tkdn_impor);
        }
        if (product.skor_tkdn) {
            formData.append(`barang[${index}][skor_tkdn]`, product.skor_tkdn);
        }
        if (product.link_tkdn) {
            formData.append(`barang[${index}][link_tkdn]`, product.link_tkdn);
        }
        if (product.estimasi_ketersediaan) {
            formData.append(`barang[${index}][estimasi_ketersediaan]`, product.estimasi_ketersediaan);
        }
        if (product.link_produk) {
            formData.append(`barang[${index}][link_produk]`, product.link_produk);
        }
        
        if (product.foto_barang && product.foto_barang instanceof File) {
            console.log(`Adding photo for product ${index}:`, product.foto_barang.name);
            formData.append(`barang[${index}][foto_barang]`, product.foto_barang);
        }
        
        if (product.spesifikasi_file && product.spesifikasi_file instanceof File) {
            console.log(`Adding spesifikasi file for product ${index}:`, product.spesifikasi_file.name);
            formData.append(`barang[${index}][spesifikasi_file]`, product.spesifikasi_file);
        }
    });
    
    fetch('/purchasing/vendor', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json', // Ensure JSON response
        },
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // For 422 errors, we still want to parse the JSON to see validation errors
        if (response.status === 422) {
            return response.json().then(data => {
                console.error('Validation failed:', data);
                throw new Error(`Validation Error: ${data.message}`);
            });
        }
        
        // Check content type for other errors
        const contentType = response.headers.get('content-type');
        console.log('Content-Type:', contentType);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        // Check if response is JSON
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Expected JSON but received:', text.substring(0, 500));
                throw new Error('Server returned non-JSON response. Check server logs.');
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showSuccessModal(data.message);
            closeModal('modalTambahVendor');
            location.reload();
        } else {
            showToast(data.message || 'Gagal menambahkan vendor', 'error');
            if (data.errors) {
                console.error('Validation errors:', data.errors);
                // Show first validation error
                const firstError = Object.values(data.errors)[0][0];
                showToast(firstError, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Full error object:', error);
        showToast(error.message || 'Terjadi kesalahan saat menambahkan vendor', 'error');
    });
}

function submitEditVendor() {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk mengedit vendor', 'error');
        return;
    }
    
    const vendorId = document.getElementById('editVendorId').value;
    
    // Validate required fields before sending
    const requiredFields = {
        'nama_vendor': document.getElementById('editNamaVendor').value,
        'jenis_perusahaan': document.getElementById('editJenisPerusahaan').value
    };
    
    // Check for empty required fields
    for (const [field, value] of Object.entries(requiredFields)) {
        if (!value || value.trim() === '') {
            showToast(`Field ${field.replace('_', ' ')} wajib diisi!`, 'error');
            return;
        }
    }
    
    // Validate email format only if email is provided
    const emailValue = document.getElementById('editEmailVendor').value;
    if (emailValue && emailValue.trim() !== '') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailValue)) {
            showToast('Format email tidak valid!', 'error');
            return;
        }
    }
    
    // Prepare vendor data
    const vendorData = {
        _method: 'PUT',
        nama_vendor: document.getElementById('editNamaVendor').value || '',
        email: document.getElementById('editEmailVendor').value || '',
        jenis_perusahaan: document.getElementById('editJenisPerusahaan').value || '',
        kontak: document.getElementById('editKontakVendor').value || '',
        alamat: document.getElementById('editAlamatVendor').value || '',
        pkp: document.getElementById('editPkpVendor').checked ? 'ya' : 'tidak',
        keterangan: document.getElementById('editKeteranganVendor').value || '',
        online_shop: document.getElementById('editOnlineShopVendor').checked ? 'ya' : 'tidak',
        nama_online_shop: document.getElementById('editNamaOnlineShop').value || '',
        barang: []
    };
    
    console.log('Vendor data being sent:', {
        ...requiredFields,
        email: document.getElementById('editEmailVendor').value || ''
    });
    
    // Process and validate products
    let validProductCount = 0;
    let hasFiles = false;
    
    editVendorProducts.forEach((product, index) => {
        // Validate each product before adding
        const requiredProductFields = {
            'nama_barang': product.nama_barang,
            'brand': product.brand,
            'kategori': product.kategori,
            'satuan': product.satuan,
            'harga_vendor': product.harga_vendor
        };
        
        // Check if all required fields are filled
        let isValidProduct = true;
        for (const [field, value] of Object.entries(requiredProductFields)) {
            if (!value || (typeof value === 'string' && value.trim() === '')) {
                console.warn(`Product ${index}: Missing ${field}`);
                isValidProduct = false;
                break;
            }
        }
        
        // Check if harga_vendor is a valid number
        if (isValidProduct && (isNaN(product.harga_vendor) || parseFloat(product.harga_vendor) < 0)) {
            console.warn(`Product ${index}: Invalid harga_vendor`);
            isValidProduct = false;
        }
        
        if (isValidProduct) {
            const productData = {
                nama_barang: product.nama_barang,
                brand: product.brand,
                kategori: product.kategori,
                satuan: product.satuan,
                spesifikasi: product.spesifikasi || '',
                harga_vendor: product.harga_vendor,
                harga_pasaran_inaproc: product.harga_pasaran_inaproc || null,
                spesifikasi_kunci: product.spesifikasi_kunci || '',
                garansi: product.garansi || '',
                pdn_tkdn_impor: product.pdn_tkdn_impor || '',
                skor_tkdn: product.skor_tkdn || '',
                link_tkdn: product.link_tkdn || '',
                estimasi_ketersediaan: product.estimasi_ketersediaan || '',
                link_produk: product.link_produk || ''
            };
            
            if (product.id_barang) {
                productData.id_barang = product.id_barang;
            }
            
            // Check if there are files
            if ((product.foto_barang && product.foto_barang instanceof File) || 
                (product.spesifikasi_file && product.spesifikasi_file instanceof File)) {
                hasFiles = true;
            }
            
            vendorData.barang.push(productData);
            validProductCount++;
        } else {
            console.error(`Product ${index} validation failed:`, product);
        }
    });
    
    console.log(`Valid products to send: ${validProductCount} out of ${editVendorProducts.length}`);
    
    // Estimate total form variables to avoid max_input_vars limit
    const estimatedVars = 6 + (validProductCount * 7); // 6 vendor fields + 7 fields per product
    console.log(`Estimated form variables: ${estimatedVars}`);
    
    let requestBody;
    let headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
    };
    
    // Always use JSON for large datasets to avoid max_input_vars limit
    // Only use FormData for small datasets with files
    if (estimatedVars > 100 || (!hasFiles && validProductCount > 0)) {
        console.log('Using JSON submission to avoid max_input_vars limit');
        
        // For JSON requests, we can't send files, so we'll skip files for now
        // Files will need to be handled separately if needed
        if (hasFiles) {
            console.warn('Files detected but using JSON submission - files will be skipped');
            showToast('Perhatian: File akan diabaikan untuk menghindari error. Silakan edit produk satu per satu untuk menambah file.', 'warning');
        }
        
        requestBody = JSON.stringify(vendorData);
        headers['Content-Type'] = 'application/json';
        
    } else {
        console.log('Using FormData submission for small dataset with files');
        // Use FormData for small datasets with file uploads
        const formData = new FormData();
        
        // Add vendor data
        formData.append('_method', vendorData._method);
        formData.append('nama_vendor', vendorData.nama_vendor);
        formData.append('email', vendorData.email);
        formData.append('jenis_perusahaan', vendorData.jenis_perusahaan);
        formData.append('kontak', vendorData.kontak);
        formData.append('alamat', vendorData.alamat);
        
        // Add product data (only for small datasets)
        vendorData.barang.forEach((product, index) => {
            if (product.id_barang) {
                formData.append(`barang[${index}][id_barang]`, product.id_barang);
            }
            formData.append(`barang[${index}][nama_barang]`, product.nama_barang);
            formData.append(`barang[${index}][brand]`, product.brand);
            formData.append(`barang[${index}][kategori]`, product.kategori);
            formData.append(`barang[${index}][satuan]`, product.satuan);
            formData.append(`barang[${index}][spesifikasi]`, product.spesifikasi);
            formData.append(`barang[${index}][harga_vendor]`, product.harga_vendor);
            
            // Add new fields
            if (product.harga_pasaran_inaproc) {
                formData.append(`barang[${index}][harga_pasaran_inaproc]`, product.harga_pasaran_inaproc);
            }
            if (product.spesifikasi_kunci) {
                formData.append(`barang[${index}][spesifikasi_kunci]`, product.spesifikasi_kunci);
            }
            if (product.garansi) {
                formData.append(`barang[${index}][garansi]`, product.garansi);
            }
            if (product.pdn_tkdn_impor) {
                formData.append(`barang[${index}][pdn_tkdn_impor]`, product.pdn_tkdn_impor);
            }
            if (product.skor_tkdn) {
                formData.append(`barang[${index}][skor_tkdn]`, product.skor_tkdn);
            }
            if (product.link_tkdn) {
                formData.append(`barang[${index}][link_tkdn]`, product.link_tkdn);
            }
            if (product.estimasi_ketersediaan) {
                formData.append(`barang[${index}][estimasi_ketersediaan]`, product.estimasi_ketersediaan);
            }
            if (product.link_produk) {
                formData.append(`barang[${index}][link_produk]`, product.link_produk);
            }
            
            // Add files from original products array
            const originalProduct = editVendorProducts[index];
            if (originalProduct.foto_barang && originalProduct.foto_barang instanceof File) {
                formData.append(`barang[${index}][foto_barang]`, originalProduct.foto_barang);
            }
            if (originalProduct.spesifikasi_file && originalProduct.spesifikasi_file instanceof File) {
                formData.append(`barang[${index}][spesifikasi_file]`, originalProduct.spesifikasi_file);
            }
        });
        
        requestBody = formData;
    }
    
    fetch(`/purchasing/vendor/${vendorId}`, {
        method: 'POST',
        headers: headers,
        body: requestBody,
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // For 422 errors, we want to parse the JSON to see validation errors
        if (response.status === 422) {
            return response.json().then(data => {
                console.error('Validation failed:', data);
                
                // Show detailed validation errors
                if (data.errors) {
                    const errorMessages = [];
                    for (const [field, messages] of Object.entries(data.errors)) {
                        errorMessages.push(`${field}: ${messages.join(', ')}`);
                    }
                    console.error('Detailed validation errors:', errorMessages);
                    showToast(`Validation Error: ${errorMessages[0]}`, 'error');
                } else {
                    showToast(`Validation Error: ${data.message}`, 'error');
                }
                
                throw new Error(`Validation Error: ${data.message}`);
            });
        }
        
        const contentType = response.headers.get('content-type');
        console.log('Content-Type:', contentType);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Expected JSON but received:', text.substring(0, 500));
                throw new Error('Server returned non-JSON response. Check server logs.');
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showSuccessModal(data.message);
            closeModal('modalEditVendor');
            location.reload();
        } else {
            // Handle specific error codes
            if (data.error_code === 'MAX_INPUT_VARS_EXCEEDED' || data.error_code === 'TOO_MANY_INPUT_VARS') {
                showToast(data.message + ' Silakan edit vendor dengan jumlah produk yang lebih sedikit.', 'error');
                if (data.suggestion) {
                    setTimeout(() => {
                        showToast(data.suggestion, 'info');
                    }, 3000);
                }
            } else {
                showToast(data.message || 'Gagal mengupdate vendor', 'error');
                if (data.errors) {
                    console.error('Validation errors:', data.errors);
                    const firstError = Object.values(data.errors)[0][0];
                    showToast(firstError, 'error');
                }
            }
        }
    })
    .catch(error => {
        console.error('Full error object:', error);
        
        // Special handling for max_input_vars related errors
        if (error.message && error.message.includes('Server returned non-JSON response')) {
            showToast('Server mengalami masalah dalam memproses data. Kemungkinan terlalu banyak produk. Silakan coba dengan jumlah produk yang lebih sedikit.', 'error');
            setTimeout(() => {
                showToast('Tips: Maksimal 50 produk per vendor untuk performa terbaik.', 'info');
            }, 3000);
        } else {
            showToast(error.message || 'Terjadi kesalahan saat mengupdate vendor', 'error');
        }
    });
}

function confirmHapusVendor() {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk menghapus vendor', 'error');
        return;
    }
    
    const vendorId = document.getElementById('hapusVendorId').value;
    
    fetch(`/purchasing/vendor/${vendorId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },

    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal(data.message);
            closeModal('modalHapusVendor');
            location.reload();
        } else {
            alert(data.message || 'Gagal menghapus vendor');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus vendor');
    });
}

// Success modal functions
function showSuccessModal(message) {
    const modal = document.getElementById('successModal');
    const messageElement = document.getElementById('successMessage');
    
    if (modal && messageElement) {
        messageElement.textContent = message;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            closeSuccessModal();
        }, 3000);
    }
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Search and filter functions
function filterVendors() {
    const searchTerm = document.getElementById('searchVendor').value;
    const jenisFilter = document.getElementById('filterJenis').value;
    const pkpFilter = document.getElementById('filterPkp').value;
    const onlineShopFilter = document.getElementById('filterOnlineShop').value;
    
    // Create URL with search parameters
    const url = new URL(window.location.href);
    url.searchParams.delete('page'); // Reset to first page when filtering
    
    if (searchTerm && searchTerm.trim() !== '') {
        url.searchParams.set('search', searchTerm.trim());
    } else {
        url.searchParams.delete('search');
    }
    
    if (jenisFilter && jenisFilter !== '') {
        url.searchParams.set('jenis', jenisFilter);
    } else {
        url.searchParams.delete('jenis');
    }
    
    if (pkpFilter && pkpFilter !== '') {
        url.searchParams.set('pkp', pkpFilter);
    } else {
        url.searchParams.delete('pkp');
    }
    
    if (onlineShopFilter && onlineShopFilter !== '') {
        url.searchParams.set('online_shop', onlineShopFilter);
    } else {
        url.searchParams.delete('online_shop');
    }
    
    // Redirect to new URL with filters
    window.location.href = url.toString();
}

function clearFilters() {
    // Clear all filter values
    document.getElementById('searchVendor').value = '';
    document.getElementById('filterJenis').value = '';
    document.getElementById('filterPkp').value = '';
    document.getElementById('filterOnlineShop').value = '';
    
    // Redirect to base URL without any filters
    const url = new URL(window.location.href);
    url.search = ''; // Clear all query parameters
    window.location.href = url.toString();
}

// Debounce search input to avoid too many requests
let searchTimeout;
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(filterVendors, 500); // Wait 500ms after user stops typing
}

// Product management functions
function addProductToVendor() {
    const namaBarang = document.getElementById('newProductName')?.value.trim();
    const brand = document.getElementById('newProductBrand')?.value.trim();
    const kategori = document.getElementById('newProductKategori')?.value;
    const satuan = document.getElementById('newProductSatuan')?.value.trim();
    const spesifikasi = document.getElementById('newProductSpesifikasi')?.value.trim();
    const hargaVendor = document.getElementById('newProductHarga')?.value;
    const hargaPasaran = document.getElementById('newProductHargaPasaran')?.value;
    const spesifikasiKunci = document.getElementById('newProductSpesifikasiKunci')?.value.trim();
    const garansi = document.getElementById('newProductGaransi')?.value.trim();
    const pdnTkdnImpor = document.getElementById('newProductPdnTkdnImpor')?.value;
    const skorTkdn = document.getElementById('newProductSkorTkdn')?.value.trim();
    const linkTkdn = document.getElementById('newProductLinkTkdn')?.value.trim();
    const estimasiKetersediaan = document.getElementById('newProductEstimasiKetersediaan')?.value.trim();
    const linkProduk = document.getElementById('newProductLinkProduk')?.value.trim();
    const fotoInput = document.getElementById('newProductFoto');
    const spesifikasiFileInput = document.getElementById('newProductSpesifikasiFile');
    
    if (!namaBarang || !brand || !kategori || !satuan || !hargaVendor) {
        showToast('Semua field produk yang wajib harus diisi!', 'error');
        return;
    }
    
    const product = {
        nama_barang: namaBarang,
        brand: brand,
        kategori: kategori,
        satuan: satuan,
        spesifikasi: spesifikasi || '',
        harga_vendor: parseFloat(hargaVendor),
        harga_pasaran_inaproc: hargaPasaran ? parseFloat(hargaPasaran) : null,
        spesifikasi_kunci: spesifikasiKunci || '',
        garansi: garansi || '',
        pdn_tkdn_impor: pdnTkdnImpor || '',
        skor_tkdn: skorTkdn || '',
        link_tkdn: linkTkdn || '',
        estimasi_ketersediaan: estimasiKetersediaan || '',
        link_produk: linkProduk || '',
        foto_barang: fotoInput.files[0] || null,
        spesifikasi_file: spesifikasiFileInput.files[0] || null
    };
    
    vendorProducts.push(product);
    updateVendorProductList();
    clearProductForm();
    
    // Show success message
    showToast('Produk berhasil ditambahkan!', 'success');
}

function addProductToEditVendor() {
    const namaBarang = document.getElementById('editNewProductName')?.value.trim();
    const brand = document.getElementById('editNewProductBrand')?.value.trim();
    const kategori = document.getElementById('editNewProductKategori')?.value;
    const satuan = document.getElementById('editNewProductSatuan')?.value.trim();
    const spesifikasi = document.getElementById('editNewProductSpesifikasi')?.value.trim();
    const hargaVendor = document.getElementById('editNewProductHarga')?.value;
    const hargaPasaran = document.getElementById('editNewProductHargaPasaran')?.value;
    const spesifikasiKunci = document.getElementById('editNewProductSpesifikasiKunci')?.value.trim();
    const garansi = document.getElementById('editNewProductGaransi')?.value.trim();
    const pdnTkdnImpor = document.getElementById('editNewProductPdnTkdnImpor')?.value;
    const skorTkdn = document.getElementById('editNewProductSkorTkdn')?.value.trim();
    const linkTkdn = document.getElementById('editNewProductLinkTkdn')?.value.trim();
    const estimasiKetersediaan = document.getElementById('editNewProductEstimasiKetersediaan')?.value.trim();
    const linkProduk = document.getElementById('editNewProductLinkProduk')?.value.trim();
    const fotoInput = document.getElementById('editNewProductFoto');
    const spesifikasiFileInput = document.getElementById('editNewProductSpesifikasiFile');
    
    if (!namaBarang || !brand || !kategori || !satuan || !hargaVendor) {
        showToast('Semua field produk harus diisi!', 'error');
        return;
    }
    
    const product = {
        nama_barang: namaBarang,
        brand: brand,
        kategori: kategori,
        satuan: satuan,
        spesifikasi: spesifikasi || '',
        harga_vendor: parseFloat(hargaVendor),
        harga_pasaran_inaproc: hargaPasaran ? parseFloat(hargaPasaran) : null,
        spesifikasi_kunci: spesifikasiKunci || '',
        garansi: garansi || '',
        pdn_tkdn_impor: pdnTkdnImpor || '',
        skor_tkdn: skorTkdn || '',
        link_tkdn: linkTkdn || '',
        estimasi_ketersediaan: estimasiKetersediaan || '',
        link_produk: linkProduk || '',
        foto_barang: fotoInput.files[0] || null,
        spesifikasi_file: spesifikasiFileInput.files[0] || null
    };
    
    editVendorProducts.push(product);
    updateEditVendorProductList();
    clearEditProductForm();
    
    // Show success message
    showToast('Produk berhasil ditambahkan!', 'success');
}

function updateVendorProductList() {
    const container = document.getElementById('vendorProductList');
    const counterElement = document.getElementById('productCount');
    
    if (!container) return;
    
    // Update counter
    if (counterElement) {
        counterElement.textContent = `${vendorProducts.length} produk`;
    }
    
    if (vendorProducts.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-box-open text-4xl mb-4 text-gray-400"></i>
                <p class="text-lg">Belum ada produk ditambahkan</p>
                <p class="text-sm">Gunakan form di atas untuk menambah produk</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = vendorProducts.map((product, index) => `
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-white to-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200">
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">${product.nama_barang}</p>
                        <p class="text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <i class="fas fa-tag mr-1"></i>${product.brand}
                            </span>
                            <span class="mx-2">•</span>
                            <span class="inline-flex items-center">
                                <i class="fas fa-layer-group mr-1"></i>${product.kategori}
                            </span>
                            <span class="mx-2">•</span>
                            <span class="inline-flex items-center">
                                <i class="fas fa-weight mr-1"></i>${product.satuan}
                            </span>
                        </p>
                        <p class="text-lg font-bold text-green-600 mt-1">
                            <i class="fas fa-money-bill-wave mr-1"></i>
                            Rp ${Number(product.harga_vendor).toLocaleString('id-ID')}
                        </p>
                        ${product.spesifikasi ? `<p class="text-xs text-gray-600 mt-1"><i class="fas fa-file-text mr-1"></i>${product.spesifikasi}</p>` : ''}
                        ${product.spesifikasi_file ? `<p class="text-xs text-blue-600 mt-1"><i class="fas fa-file mr-1"></i>File spesifikasi: ${product.spesifikasi_file.name}</p>` : ''}
                    </div>
                </div>
            </div>
            <button onclick="removeProductFromVendor(${index})" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 transform hover:scale-105" title="Hapus produk">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `).join('');
}

function updateEditVendorProductList() {
    const container = document.getElementById('editVendorProductList');
    const counterElement = document.getElementById('editProductCount');
    const searchContainer = document.getElementById('productSearchContainer');
    
    if (!container) return;
    
    // Show/hide search container based on product count
    if (searchContainer) {
        if (editVendorProducts.length > 3) {
            searchContainer.style.display = 'flex';
        } else {
            searchContainer.style.display = 'none';
        }
    }
    
    // Get search and filter values
    const searchTerm = document.getElementById('searchProducts')?.value.toLowerCase() || '';
    const selectedCategory = document.getElementById('filterProductCategory')?.value || '';
    
    // Filter products based on search and category
    let filteredProducts = editVendorProducts;
    if (searchTerm || selectedCategory) {
        filteredProducts = editVendorProducts.filter((product, index) => {
            const matchesSearch = !searchTerm || 
                product.nama_barang.toLowerCase().includes(searchTerm) ||
                product.brand.toLowerCase().includes(searchTerm) ||
                product.kategori.toLowerCase().includes(searchTerm) ||
                product.spesifikasi.toLowerCase().includes(searchTerm);
            
            const matchesCategory = !selectedCategory || product.kategori === selectedCategory;
            
            return matchesSearch && matchesCategory;
        });
    }
    
    // Update counter (show filtered count if filtering)
    if (counterElement) {
        if (filteredProducts.length !== editVendorProducts.length) {
            counterElement.textContent = `${filteredProducts.length} dari ${editVendorProducts.length} produk`;
        } else {
            counterElement.textContent = `${editVendorProducts.length} produk`;
        }
    }
    
    // Update warning display if function exists
    if (typeof updateEditProductCountDisplay === 'function') {
        updateEditProductCountDisplay();
    }
    
    if (filteredProducts.length === 0) {
        if (editVendorProducts.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-4 text-gray-400"></i>
                    <p class="text-lg">Belum ada produk ditambahkan</p>
                    <p class="text-sm">Gunakan form di atas untuk menambah produk</p>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-search text-3xl mb-3 text-gray-400"></i>
                    <p class="text-base">Tidak ada produk yang sesuai dengan pencarian</p>
                    <p class="text-sm">Coba ubah kata kunci atau filter kategori</p>
                </div>
            `;
        }
        return;
    }

    container.innerHTML = filteredProducts.map((product) => {
        // Find original index in the full array
        const originalIndex = editVendorProducts.findIndex(p => 
            p.nama_barang === product.nama_barang && 
            p.brand === product.brand && 
            p.kategori === product.kategori &&
            p.harga_vendor === product.harga_vendor
        );
        
        return `
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-white to-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200 ${editProductIndex === originalIndex ? 'border-yellow-400 bg-yellow-50' : ''}">
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 ${editProductIndex === originalIndex ? 'bg-yellow-100' : 'bg-blue-100'} rounded-lg flex items-center justify-center">
                        <i class="fas fa-box ${editProductIndex === originalIndex ? 'text-yellow-600' : 'text-blue-600'}"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            ${editProductIndex === originalIndex ? '<span class="text-xs font-medium text-yellow-600"><i class="fas fa-edit mr-1"></i>SEDANG DIEDIT</span>' : ''}
                            ${product.id_barang ? '<span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full"><i class="fas fa-database mr-1"></i>DATABASE</span>' : '<span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full"><i class="fas fa-plus mr-1"></i>BARU</span>'}
                        </div>
                        <p class="font-semibold text-gray-800" id="productName_${originalIndex}">${product.nama_barang}</p>
                        <p class="text-sm text-gray-500" id="productInfo_${originalIndex}">
                            <span class="inline-flex items-center">
                                <i class="fas fa-tag mr-1"></i>${product.brand}
                            </span>
                            <span class="mx-2">•</span>
                            <span class="inline-flex items-center">
                                <i class="fas fa-layer-group mr-1"></i>${product.kategori}
                            </span>
                            <span class="mx-2">•</span>
                            <span class="inline-flex items-center">
                                <i class="fas fa-weight mr-1"></i>${product.satuan}
                            </span>
                        </p>
                        <p class="text-lg font-bold text-green-600 mt-1" id="productPrice_${originalIndex}">
                            <i class="fas fa-money-bill-wave mr-1"></i>
                            Rp ${Number(product.harga_vendor).toLocaleString('id-ID')}
                        </p>
                        ${product.spesifikasi ? `<p class="text-xs text-gray-600 mt-1" id="productSpec_${originalIndex}">${product.spesifikasi}</p>` : ''}
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="duplicateProductInVendor(${originalIndex})" class="px-3 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 transform hover:scale-105" title="Duplikat produk">
                    <i class="fas fa-copy"></i>
                </button>
                <button onclick="editProductInVendor(${originalIndex})" class="px-3 py-2 ${editProductIndex === originalIndex ? 'bg-yellow-200 text-yellow-700' : 'bg-yellow-100 text-yellow-600'} rounded-lg hover:bg-yellow-200 transition-all duration-200 transform hover:scale-105" title="${editProductIndex === originalIndex ? 'Sedang diedit' : 'Edit produk'}">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="removeProductFromEditVendor(${originalIndex})" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 transform hover:scale-105" title="${product.id_barang ? 'Hapus dari database' : 'Hapus produk'}" ${editProductIndex === originalIndex ? 'disabled' : ''}>
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    }).join('');
}

function removeProductFromVendor(index) {
    vendorProducts.splice(index, 1);
    updateVendorProductList();
}

function removeProductFromEditVendor(index) {
    const product = editVendorProducts[index];
    
    // If product has ID, it means it exists in database - show confirmation
    if (product.id_barang) {
        if (confirm(`Apakah Anda yakin ingin menghapus produk "${product.nama_barang}"? Data ini akan dihapus secara permanen dari database.`)) {
            editVendorProducts.splice(index, 1);
            updateEditVendorProductList();
            
            // Reset edit mode if the deleted product was being edited
            if (editProductIndex === index) {
                resetProductEditMode();
                clearEditProductForm();
            } else if (editProductIndex > index) {
                // Adjust edit index if a product before it was deleted
                editProductIndex--;
            }
        }
    } else {
        // New product (not in database yet), just remove without confirmation
        editVendorProducts.splice(index, 1);
        updateEditVendorProductList();
        
        // Reset edit mode if the deleted product was being edited
        if (editProductIndex === index) {
            resetProductEditMode();
            clearEditProductForm();
        } else if (editProductIndex > index) {
            // Adjust edit index if a product before it was deleted
            editProductIndex--;
        }
    }
}

// Global variable to track edit mode
let editProductIndex = -1;

function editProductInVendor(index) {
    const product = editVendorProducts[index];
    if (!product) return;
    
    // Store the index being edited
    editProductIndex = index;
    
    // Fill form with product data
    document.getElementById('editNewProductName').value = product.nama_barang || '';
    document.getElementById('editNewProductBrand').value = product.brand || '';
    document.getElementById('editNewProductKategori').value = product.kategori || '';
    document.getElementById('editNewProductSatuan').value = product.satuan || '';
    document.getElementById('editNewProductSpesifikasi').value = product.spesifikasi || '';
    document.getElementById('editNewProductHarga').value = product.harga_vendor || '';
    document.getElementById('editNewProductHargaPasaran').value = product.harga_pasaran_inaproc || '';
    document.getElementById('editNewProductSpesifikasiKunci').value = product.spesifikasi_kunci || '';
    document.getElementById('editNewProductGaransi').value = product.garansi || '';
    document.getElementById('editNewProductPdnTkdnImpor').value = product.pdn_tkdn_impor || '';
    document.getElementById('editNewProductSkorTkdn').value = product.skor_tkdn || '';
    document.getElementById('editNewProductLinkTkdn').value = product.link_tkdn || '';
    document.getElementById('editNewProductEstimasiKetersediaan').value = product.estimasi_ketersediaan || '';
    document.getElementById('editNewProductLinkProduk').value = product.link_produk || '';
    
    // Toggle TKDN fields based on PDN/TKDN/Impor selection in edit form
    toggleEditTkdnFields();
    
    // Update form title and hint
    const formTitle = document.getElementById('productFormTitle');
    const formHint = document.getElementById('productFormHint');
    if (formTitle) {
        formTitle.innerHTML = '<i class="fas fa-edit text-yellow-600 mr-2"></i>Edit Produk';
    }
    if (formHint) {
        formHint.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Ubah data produk yang diperlukan';
    }
    
    // Change button text and behavior to indicate edit mode
    const addButton = document.querySelector('button[onclick="addProductToEditVendor()"]');
    if (addButton) {
        addButton.innerHTML = '<i class="fas fa-save mr-2"></i>Update Produk';
        addButton.setAttribute('onclick', 'updateProductInVendor()');
        addButton.className = 'px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-lg hover:from-yellow-700 hover:to-yellow-800 transition-all duration-200 transform hover:scale-105 shadow-lg';
    }
    
    // Show cancel button
    const cancelButton = document.getElementById('cancelEditProductBtn');
    if (cancelButton) {
        cancelButton.classList.remove('hidden');
    }
    
    // Scroll to form
    document.querySelector('.bg-gradient-to-br.from-blue-50').scrollIntoView({ behavior: 'smooth' });
}

function updateProductInVendor() {
    if (editProductIndex < 0 || editProductIndex >= editVendorProducts.length) {
        showToast('Error: Produk tidak ditemukan!', 'error');
        return;
    }
    
    const namaBarang = document.getElementById('editNewProductName')?.value.trim();
    const brand = document.getElementById('editNewProductBrand')?.value.trim();
    const kategori = document.getElementById('editNewProductKategori')?.value;
    const satuan = document.getElementById('editNewProductSatuan')?.value.trim();
    const spesifikasi = document.getElementById('editNewProductSpesifikasi')?.value.trim();
    const hargaVendor = document.getElementById('editNewProductHarga')?.value;
    const hargaPasaran = document.getElementById('editNewProductHargaPasaran')?.value;
    const spesifikasiKunci = document.getElementById('editNewProductSpesifikasiKunci')?.value.trim();
    const garansi = document.getElementById('editNewProductGaransi')?.value.trim();
    const pdnTkdnImpor = document.getElementById('editNewProductPdnTkdnImpor')?.value;
    const skorTkdn = document.getElementById('editNewProductSkorTkdn')?.value.trim();
    const linkTkdn = document.getElementById('editNewProductLinkTkdn')?.value.trim();
    const estimasiKetersediaan = document.getElementById('editNewProductEstimasiKetersediaan')?.value.trim();
    const linkProduk = document.getElementById('editNewProductLinkProduk')?.value.trim();
    const fotoInput = document.getElementById('editNewProductFoto');
    
    if (!namaBarang || !brand || !kategori || !satuan || !hargaVendor) {
        showToast('Semua field produk harus diisi!', 'error');
        return;
    }
    
    // Update product in array
    editVendorProducts[editProductIndex] = {
        ...editVendorProducts[editProductIndex], // Keep existing properties like id if any
        nama_barang: namaBarang,
        brand: brand,
        kategori: kategori,
        satuan: satuan,
        spesifikasi: spesifikasi || '',
        harga_vendor: parseFloat(hargaVendor),
        harga_pasaran_inaproc: hargaPasaran ? parseFloat(hargaPasaran) : null,
        spesifikasi_kunci: spesifikasiKunci || '',
        garansi: garansi || '',
        pdn_tkdn_impor: pdnTkdnImpor || '',
        skor_tkdn: skorTkdn || '',
        link_tkdn: linkTkdn || '',
        estimasi_ketersediaan: estimasiKetersediaan || '',
        link_produk: linkProduk || '',
        foto_barang: fotoInput.files[0] || editVendorProducts[editProductIndex].foto_barang || null
    };
    
    // Reset edit mode
    resetProductEditMode();
    
    // Update display
    updateEditVendorProductList();
    clearEditProductForm();
    
    // Show success message
    showToast('Produk berhasil diupdate!', 'success');
}

function resetProductEditMode() {
    editProductIndex = -1;
    
    // Reset form title and hint
    const formTitle = document.getElementById('productFormTitle');
    const formHint = document.getElementById('productFormHint');
    if (formTitle) {
        formTitle.innerHTML = '<i class="fas fa-plus-circle text-green-600 mr-2"></i>Tambah Produk Baru';
    }
    if (formHint) {
        formHint.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Isi semua field untuk menambah produk';
    }
    
    // Reset button to add mode
    const addButton = document.querySelector('button[onclick="updateProductInVendor()"]');
    if (addButton) {
        addButton.innerHTML = '<i class="fas fa-plus mr-2"></i>Tambah ke Daftar Produk';
        addButton.setAttribute('onclick', 'addProductToEditVendor()');
        addButton.className = 'px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 transform hover:scale-105 shadow-lg';
    }
    
    // Hide cancel button
    const cancelButton = document.getElementById('cancelEditProductBtn');
    if (cancelButton) {
        cancelButton.classList.add('hidden');
    }
}

function cancelEditProduct() {
    clearEditProductForm();
    resetProductEditMode();
}

function duplicateProductInVendor(index) {
    const product = editVendorProducts[index];
    if (!product) return;
    
    // Create a copy of the product (without id_barang to mark it as new)
    const duplicatedProduct = {
        nama_barang: product.nama_barang + ' (Copy)',
        brand: product.brand,
        kategori: product.kategori,
        satuan: product.satuan,
        spesifikasi: product.spesifikasi,
        harga_vendor: product.harga_vendor,
        foto_barang: product.foto_barang
    };
    
    // Add to the list
    editVendorProducts.push(duplicatedProduct);
    updateEditVendorProductList();
    
    // Show success message
    showToast('Produk berhasil diduplikat!', 'success');
}

function clearAllProducts() {
    if (editVendorProducts.length === 0) {
        showToast('Tidak ada produk untuk dihapus!', 'info');
        return;
    }
    
    const hasDbProducts = editVendorProducts.some(product => product.id_barang);
    const message = hasDbProducts 
        ? `Apakah Anda yakin ingin menghapus semua ${editVendorProducts.length} produk? Produk yang sudah ada di database akan dihapus secara permanen.`
        : `Apakah Anda yakin ingin menghapus semua ${editVendorProducts.length} produk?`;
    
    if (confirm(message)) {
        editVendorProducts = [];
        resetProductEditMode();
        clearEditProductForm();
        updateEditVendorProductList();
        showToast('Semua produk berhasil dihapus!', 'success');
    }
}

function exportProductList() {
    if (editVendorProducts.length === 0) {
        showToast('Tidak ada produk untuk diekspor!', 'info');
        return;
    }
    
    // Prepare data for export
    const exportData = editVendorProducts.map((product, index) => ({
        'No': index + 1,
        'Nama Produk': product.nama_barang,
        'Brand': product.brand,
        'Kategori': product.kategori,
        'Satuan': product.satuan,
        'Harga Vendor': product.harga_vendor,
        'Spesifikasi': product.spesifikasi || '',
        'Status': product.id_barang ? 'Database' : 'Baru'
    }));
    
    // Convert to CSV
    const headers = Object.keys(exportData[0]);
    const csvContent = [
        headers.join(','),
        ...exportData.map(row => headers.map(header => `"${row[header]}"`).join(','))
    ].join('\n');
    
    // Download CSV file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const vendorName = document.getElementById('editNamaVendor')?.value || 'Vendor';
    link.href = URL.createObjectURL(blob);
    link.download = `Produk_${vendorName}_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
    
    showToast('Data produk berhasil diekspor!', 'success');
}

function clearProductForm() {
    const fields = ['newProductName', 'newProductBrand', 'newProductKategori', 'newProductSatuan', 'newProductSpesifikasi', 'newProductHarga', 'newProductHargaPasaran', 'newProductSpesifikasiKunci', 'newProductGaransi', 'newProductPdnTkdnImpor', 'newProductSkorTkdn', 'newProductLinkTkdn', 'newProductEstimasiKetersediaan', 'newProductLinkProduk', 'newProductFoto', 'newProductSpesifikasiFile'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) element.value = '';
    });
    
    // Reset spesifikasi input to text mode
    const textRadio = document.querySelector('input[name="spesifikasi_type"][value="text"]');
    if (textRadio) {
        textRadio.checked = true;
        toggleSpesifikasiInput('text');
    }
    
    // Hide file preview
    const filePreview = document.getElementById('spesifikasiFilePreview');
    if (filePreview) filePreview.classList.add('hidden');
}

function clearEditProductForm() {
    const fields = ['editNewProductName', 'editNewProductBrand', 'editNewProductKategori', 'editNewProductSatuan', 'editNewProductSpesifikasi', 'editNewProductHarga', 'editNewProductHargaPasaran', 'editNewProductSpesifikasiKunci', 'editNewProductGaransi', 'editNewProductPdnTkdnImpor', 'editNewProductSkorTkdn', 'editNewProductLinkTkdn', 'editNewProductEstimasiKetersediaan', 'editNewProductLinkProduk', 'editNewProductFoto', 'editNewProductSpesifikasiFile'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) element.value = '';
    });
    
    // Reset spesifikasi input to text mode
    const textRadio = document.querySelector('input[name="edit_spesifikasi_type"][value="text"]');
    if (textRadio) {
        textRadio.checked = true;
        toggleEditSpesifikasiInput('text');
    }
    
    // Hide file preview
    const filePreview = document.getElementById('editSpesifikasiFilePreview');
    if (filePreview) filePreview.classList.add('hidden');
    
    // Reset edit mode if active
    if (editProductIndex >= 0) {
        resetProductEditMode();
    }
}

// Toggle TKDN fields based on PDN/TKDN/Impor selection in edit form
function toggleEditTkdnFields() {
    const pdnTkdnImpor = document.getElementById('editNewProductPdnTkdnImpor')?.value;
    const skorTkdnInput = document.getElementById('editNewProductSkorTkdn');
    const linkTkdnInput = document.getElementById('editNewProductLinkTkdn');
    
    if (pdnTkdnImpor === 'TKDN') {
        // Enable TKDN fields
        if (skorTkdnInput) {
            skorTkdnInput.disabled = false;
            skorTkdnInput.placeholder = 'Masukkan skor TKDN (contoh: 25%, 40%)';
            skorTkdnInput.parentElement.classList.remove('opacity-50');
        }
        if (linkTkdnInput) {
            linkTkdnInput.disabled = false;
            linkTkdnInput.placeholder = 'https://...';
            linkTkdnInput.parentElement.classList.remove('opacity-50');
        }
    } else {
        // Disable TKDN fields
        if (skorTkdnInput) {
            skorTkdnInput.disabled = true;
            skorTkdnInput.value = '';
            skorTkdnInput.placeholder = '~Menu Muncul Jika "TKDN"~';
            skorTkdnInput.parentElement.classList.add('opacity-50');
        }
        if (linkTkdnInput) {
            linkTkdnInput.disabled = true;
            linkTkdnInput.value = '';
            linkTkdnInput.placeholder = '~Menu Muncul Jika "TKDN"~';
            linkTkdnInput.parentElement.classList.add('opacity-50');
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize filter functionality
    const searchInput = document.getElementById('searchVendor');
    const filterSelect = document.getElementById('filterJenis');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            debounceSearch();
        });
        
        // Set initial value from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search');
        if (searchParam) {
            searchInput.value = searchParam;
        }
    }
    
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            filterVendors();
        });
        
        // Set initial value from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const jenisParam = urlParams.get('jenis');
        if (jenisParam) {
            filterSelect.value = jenisParam;
        }
    }
    
    // Initialize product search and filter functionality
    const productSearchInput = document.getElementById('searchProducts');
    const productFilterSelect = document.getElementById('filterProductCategory');
    
    if (productSearchInput) {
        productSearchInput.addEventListener('input', function() {
            updateEditVendorProductList();
        });
    }
    
    if (productFilterSelect) {
        productFilterSelect.addEventListener('change', function() {
            updateEditVendorProductList();
        });
    }
    
    // Initialize modal close buttons
    const closeButtons = document.querySelectorAll('[data-modal-close]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-close');
            closeModal(modalId);
        });
    });
    
    console.log('Vendor management initialized successfully');
});

// Toast notification function
function showToast(message, type = 'success') {
    // Remove existing toast if any
    const existingToast = document.getElementById('toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.id = 'toast-notification';
    toast.className = `fixed top-4 right-4 z-50 transform transition-all duration-300 ease-in-out`;
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    toast.innerHTML = `
        <div class="${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-80">
            <i class="fas ${icon} text-xl"></i>
            <span class="font-medium">${message}</span>
            <button onclick="removeToast()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Add to body
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        removeToast();
    }, 5000);
}

function removeToast() {
    const toast = document.getElementById('toast-notification');
    if (toast) {
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}
</script>
@endsection
