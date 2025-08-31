@extends('layouts.app')

@section('title', 'Produk - Cyber KATANA')

@push('styles')
<style>
    /* Completely remove number input spinners/arrows - ULTIMATE VERSION */
    input[type="number"] {
        -moz-appearance: textfield !important;
        -webkit-appearance: none !important;
        appearance: none !important;
    }
    
    /* Remove webkit spinners */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        margin: 0 !important;
        padding: 0 !important;
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
        width: 0 !important;
        height: 0 !important;
    }
    
    /* Force remove any calendar or date pickers */
    input[type="number"]::-webkit-calendar-picker-indicator {
        display: none !important;
        -webkit-appearance: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
    }
    
    /* Firefox specific removal */
    input[type="number"] {
        -moz-appearance: textfield !important;
    }
    
    /* IE/Edge specific */
    input[type="number"]::-ms-clear,
    input[type="number"]::-ms-reveal {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }
    
    /* Additional security - force text appearance */
    input[type="number"] {
        background-image: none !important;
        background: none !important;
    }
    
    /* Focus styling */
    input[type="number"]:focus {
        outline: 2px solid #dc2626;
        outline-offset: 2px;
    }
</style>
@endpush

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Manajemen Produk</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan pantau semua produk dari vendor</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-box text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-box text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Produk</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">{{ $totalProduk }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-microchip text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Elektronik</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">{{ $produkElektronik }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-cogs text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Mesin</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">{{ $produkMesin }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-chair text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Meubel</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">{{ $produkMeubel }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 mb-6">
    <form method="GET" action="{{ route('purchasing.produk') }}" class="space-y-4">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari produk berdasarkan nama, brand, spesifikasi, atau vendor..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Filter Buttons -->
            <div class="flex flex-wrap gap-2">
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-search"></i>
                    <span>Cari</span>
                </button>
                <a href="{{ route('purchasing.produk') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center space-x-2">
                    <i class="fas fa-refresh"></i>
                    <span>Reset</span>
                </a>
            </div>
        </div>
        
        <!-- Advanced Filters -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Vendor Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                <select name="vendor" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id_vendor }}" {{ request('vendor') == $vendor->id_vendor ? 'selected' : '' }}>
                            {{ $vendor->nama_vendor }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Min Price -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Min</label>
                <input type="number" 
                       name="min_harga" 
                       value="{{ request('min_harga') }}"
                       placeholder="0" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            
            <!-- Max Price -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Max</label>
                <input type="number" 
                       name="max_harga" 
                       value="{{ request('max_harga') }}"
                       placeholder="999999999" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            
            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                <select name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                    <option value="nama_barang" {{ request('sort_by') == 'nama_barang' ? 'selected' : '' }}>Nama Produk</option>
                    <option value="harga_vendor" {{ request('sort_by') == 'harga_vendor' ? 'selected' : '' }}>Harga</option>
                    <option value="brand" {{ request('sort_by') == 'brand' ? 'selected' : '' }}>Brand</option>
                </select>
                <select name="sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 mt-2">
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z / Rendah-Tinggi</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A / Tinggi-Rendah</option>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Main Content -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-20">
    <!-- Header -->
    <div class="p-4 sm:p-6 lg:p-8 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Daftar Produk</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">
                    Menampilkan {{ $produk->count() }} dari {{ $produk->total() }} produk
                    @if(request('search'))
                        untuk pencarian "{{ request('search') }}"
                    @endif
                </p>
            </div>
            <div class="text-sm text-gray-500">
                Halaman {{ $produk->currentPage() }} dari {{ $produk->lastPage() }}
            </div>
        </div>
    </div>

    <!-- Product Cards Grid -->
    <div class="p-4 sm:p-6">
        @if($produk->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-6">
                @foreach($produk as $item)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer flex flex-col" onclick="showProductDetail({{ $item->id_barang }})">
                        <div class="relative">
                            <!-- Product Image -->
                            <div class="w-full h-28 sm:h-48 bg-gray-100 overflow-hidden">
                                @if($item->foto_barang)
                                    <img src="{{ asset('storage/' . $item->foto_barang) }}" 
                                         alt="{{ $item->nama_barang }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <i class="fas fa-image text-2xl sm:text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <!-- Category Badge -->
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                                    @if($item->kategori == 'Elektronik') bg-blue-100 text-blue-800
                                    @elseif($item->kategori == 'Mesin') bg-green-100 text-green-800
                                    @elseif($item->kategori == 'Meubel') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $item->kategori }}
                                </span>
                            </div>
                            <!-- Product Code -->
                            <div class="absolute top-2 left-2">
                                <span class="bg-gray-800 text-white text-xs px-2 py-0.5 rounded-md">PRD-{{ str_pad($item->id_barang, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                        <!-- Product Info -->
                        <div class="p-2 sm:p-4 flex-1 flex flex-col justify-between">
                            <h3 class="font-semibold text-gray-900 text-xs sm:text-lg mb-1 sm:mb-2 line-clamp-1">{{ $item->nama_barang }}</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-4 line-clamp-2">{{ $item->brand }} - {{ $item->vendor->nama_vendor }}</p>
                            <div class="text-center mt-auto">
                                <p class="text-xs text-gray-500">Harga Vendor</p>
                                <p class="text-sm sm:text-lg font-bold text-red-600">
                                    Rp {{ number_format($item->harga_vendor, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-400">per {{ $item->satuan }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $produk->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-search text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak ada produk ditemukan</h3>
                <p class="text-gray-600 mb-6">
                    @if(request()->hasAny(['search', 'kategori', 'vendor', 'min_harga', 'max_harga']))
                        Coba ubah filter pencarian atau hapus beberapa filter.
                    @else
                        Belum ada produk yang tersedia saat ini.
                    @endif
                </p>
                <a href="{{ route('purchasing.produk') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-refresh mr-2"></i>
                    Reset Filter
                </a>
            </div>
        @endif
    </div>
</div>

@include('pages.purchasing.produk-components.detail')

<script>
    // Function to show product detail
    function showProductDetail(productId) {
        fetch(`/purchasing/produk/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const produk = data.produk;
                    
                    // Update modal content
                    document.getElementById('detailNoProduk').textContent = `PRD-${String(produk.id_barang).padStart(3, '0')}`;
                    document.getElementById('detailNamaBarang').textContent = produk.nama_barang;
                    document.getElementById('detailSpesifikasi').textContent = produk.spesifikasi;
                    document.getElementById('detailKategori').textContent = produk.kategori;
                    document.getElementById('detailBrand').textContent = produk.brand;
                    document.getElementById('detailVendor').textContent = produk.vendor.nama_vendor;
                    document.getElementById('detailHarga').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(produk.harga_vendor)} / ${produk.satuan}`;
                    
                    // Update product image
                    const imageElement = document.getElementById('detailProductImage');
                    if (produk.foto_barang) {
                        imageElement.src = `/storage/${produk.foto_barang}`;
                    } else {
                        imageElement.src = 'https://via.placeholder.com/300?text=No+Image';
                    }
                    
                    // Update category badge
                    const categoryBadge = document.getElementById('detailJenisBarang');
                    categoryBadge.textContent = produk.kategori;
                    
                    // Set badge color based on category
                    categoryBadge.className = 'inline-flex px-3 py-1 text-sm font-medium rounded-full';
                    switch(produk.kategori) {
                        case 'Elektronik':
                            categoryBadge.classList.add('bg-blue-100', 'text-blue-800');
                            break;
                        case 'Mesin':
                            categoryBadge.classList.add('bg-green-100', 'text-green-800');
                            break;
                        case 'Meubel':
                            categoryBadge.classList.add('bg-yellow-100', 'text-yellow-800');
                            break;
                        default:
                            categoryBadge.classList.add('bg-gray-100', 'text-gray-800');
                    }
                    
                    // Update additional information
                    document.getElementById('detailTanggalDibuat').textContent = new Date(produk.created_at).toLocaleDateString('id-ID');
                    document.getElementById('detailLastUpdate').textContent = new Date(produk.updated_at).toLocaleDateString('id-ID');
                    
                    // Show modal
                    showModal('modalDetailProduk');
                } else {
                    alert('Gagal memuat detail produk');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat detail produk');
            });
    }
    
    // Modal utility functions
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
    
    // Auto submit form when filter changes
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const selects = form.querySelectorAll('select');
        
        selects.forEach(select => {
            select.addEventListener('change', function() {
                form.submit();
            });
        });
        
        // Prevent number input from changing when scrolled
        const numberInputs = document.querySelectorAll('input[type="number"]');
        numberInputs.forEach(input => {
            // Disable mouse wheel on number inputs
            input.addEventListener('wheel', function(e) {
                e.preventDefault();
            });
            
            // Remove focus when scrolling to prevent accidental changes
            input.addEventListener('focus', function() {
                this.addEventListener('wheel', function(e) {
                    e.preventDefault();
                    this.blur();
                });
            });
            
            // Prevent keyboard arrow keys from changing value when not focused
            input.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                    if (document.activeElement !== this) {
                        e.preventDefault();
                    }
                }
            });
        });
    });
</script>
@endsection
