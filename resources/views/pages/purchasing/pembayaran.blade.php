@extends('layouts.app')

@section('content')
<!-- Access Control Info -->
@php
    $currentUser = Auth::user();
    $isAdminPurchasing = $currentUser->role === 'admin_purchasing';
@endphp


<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-2xl p-6 lg:p-8 mb-8 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl lg:text-4xl font-bold mb-2">Pembayaran Purchasing</h1>
                <p class="text-red-100 text-base lg:text-lg opacity-90">Kelola pembayaran proyek yang sudah di-ACC klien (termasuk proyek selesai dan gagal yang belum lunas)</p>
            </div>
            <div class="hidden lg:flex items-center justify-center w-20 h-20 bg-red-700 rounded-2xl">
                <i class="fas fa-credit-card text-4xl opacity-80"></i>
            </div>
        </div>
        
        
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-6 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-600 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium opacity-90">Menunggu Pembayaran</p>
                    <p class="text-3xl font-bold mt-1">{{ $proyekPerluBayar->total() }}</p>
                </div>
                <div class="bg-blue-400/30 p-3 rounded-xl">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium opacity-90">Pending Verifikasi</p>
                    <p class="text-3xl font-bold mt-1">
                        {{ $semuaProyek->sum(function($proyek) { 
                            return $proyek->pembayaran->where('status_verifikasi', 'Pending')->count(); 
                        }) }}
                    </p>
                </div>
                <div class="bg-yellow-400/30 p-3 rounded-xl">
                    <i class="fas fa-hourglass-half text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium opacity-90">Terverifikasi</p>
                    <p class="text-3xl font-bold mt-1">
                        {{ $semuaProyek->sum(function($proyek) { 
                            return $proyek->pembayaran->where('status_verifikasi', 'Approved')->count(); 
                        }) }}
                    </p>
                </div>
                <div class="bg-green-400/30 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium opacity-90">Ditolak</p>
                    <p class="text-3xl font-bold mt-1">
                        {{ $semuaProyek->sum(function($proyek) { 
                            return $proyek->pembayaran->where('status_verifikasi', 'Ditolak')->count(); 
                        }) }}
                    </p>
                </div>
                <div class="bg-red-400/30 p-3 rounded-xl">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
        <!-- Tab Headers -->
        <div class="border-b border-gray-200 bg-gray-50 rounded-t-2xl">
            <nav class="flex space-x-0" aria-label="Tabs">
                <button onclick="openTab(event, 'tab-perlu-bayar')" 
                        class="tab-button flex-1 py-4 px-6 border-b-3 font-semibold text-sm focus:outline-none transition-all duration-300 border-red-500 text-red-600 bg-white rounded-tl-2xl" 
                        id="defaultOpen">
                    <div class="flex items-center justify-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="hidden sm:inline">Proyek Perlu Pembayaran</span>
                        <span class="sm:hidden">Perlu Bayar</span>
                        <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded-full min-w-[1.5rem] h-6 flex items-center justify-center">
                            {{ $proyekPerluBayar->total() }}
                        </span>
                    </div>
                </button>
                <button onclick="openTab(event, 'tab-semua-proyek')" 
                        class="tab-button flex-1 py-4 px-6 border-b-3 font-semibold text-sm focus:outline-none transition-all duration-300 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 bg-gray-50">
                    <div class="flex items-center justify-center gap-2">
                        <i class="fas fa-list"></i>
                        <span class="hidden sm:inline">Semua Proyek</span>
                        <span class="sm:hidden">Proyek</span>
                        <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2 py-1 rounded-full min-w-[1.5rem] h-6 flex items-center justify-center">
                            {{ $semuaProyek->total() }}
                        </span>
                    </div>
                </button>
                <button onclick="openTab(event, 'tab-semua-pembayaran')" 
                        class="tab-button flex-1 py-4 px-6 border-b-3 font-semibold text-sm focus:outline-none transition-all duration-300 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 bg-gray-50 rounded-tr-2xl">
                    <div class="flex items-center justify-center gap-2">
                        <i class="fas fa-receipt"></i>
                        <span class="hidden sm:inline">Semua Pembayaran</span>
                        <span class="sm:hidden">Pembayaran</span>
                        <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2 py-1 rounded-full min-w-[1.5rem] h-6 flex items-center justify-center">
                            {{ $semuaPembayaran->total() }}
                        </span>
                    </div>
                </button>
            </nav>
        </div>

    <!-- Tab Content 1: Proyek Perlu Pembayaran -->
    <div id="tab-perlu-bayar" class="tab-content">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Proyek Perlu Pembayaran</h2>
                    <p class="text-gray-600 mt-1">Daftar proyek yang sudah di-ACC dan menunggu pembayaran dari klien, termasuk proyek dalam tahap pengiriman, selesai, dan gagal yang perlu pelunasan atau pengembalian dana</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                @if($proyekPerluBayar->count() > 0)
                <div class="space-y-6">
                    @foreach($proyekPerluBayar as $proyek)
                    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <!-- Header Proyek dengan Badge Status -->
                        <div class="p-6 border-b-2 border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-xl">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-3 h-3 rounded-full 
                                            @if($proyek->status == 'Pembayaran') bg-blue-500
                                            @elseif($proyek->status == 'Pengiriman') bg-purple-500
                                            @elseif($proyek->status == 'Selesai') bg-green-500
                                            @elseif($proyek->status == 'Gagal') bg-red-500
                                            @else bg-gray-500 @endif animate-pulse">
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900">{{ $proyek->nama_barang }}</h3>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium text-gray-700 flex items-center">
                                            <i class="fas fa-building text-blue-600 mr-2"></i>
                                            {{ $proyek->instansi }} - {{ $proyek->kota_kab }}
                                        </p>
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-file-contract text-gray-400 mr-2"></i>
                                            No. Penawaran: {{ $proyek->penawaranAktif->no_penawaran }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right space-y-2">
                                    <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-semibold
                                        @if($proyek->status == 'Pembayaran') bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($proyek->status == 'Pengiriman') bg-purple-100 text-purple-800 border border-purple-200
                                        @elseif($proyek->status == 'Selesai') bg-green-100 text-green-800 border border-green-200
                                        @elseif($proyek->status == 'Gagal') bg-red-100 text-red-800 border border-red-200
                                        @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                        @if($proyek->status == 'Pembayaran')
                                            <i class="fas fa-credit-card mr-2"></i>
                                        @elseif($proyek->status == 'Pengiriman')
                                            <i class="fas fa-shipping-fast mr-2"></i>
                                        @elseif($proyek->status == 'Selesai')
                                            <i class="fas fa-check-circle mr-2"></i>
                                        @elseif($proyek->status == 'Gagal')
                                            <i class="fas fa-times-circle mr-2"></i>
                                        @endif
                                        {{ $proyek->status }}
                                    </span>
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">Total Modal ke Vendor</p>
                                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($proyek->vendors_data->sum('total_vendor'), 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Area dengan Background yang Berbeda -->
                        <div class="p-6 bg-white">
                            <!-- Panel Informasi Finansial -->
                            <div class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-blue-50 border-l-4 border-emerald-400 rounded-lg">
                                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-chart-line text-emerald-600 mr-2"></i>
                                    Analisis Finansial Proyek
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="text-center p-3 bg-white rounded-lg border border-blue-200">
                                        <p class="text-xs text-blue-700 font-medium">Harga Penawaran ke Klien</p>
                                        <p class="text-lg font-bold text-blue-900">Rp {{ number_format($proyek->penawaranAktif->total_penawaran, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg border border-green-200">
                                        <p class="text-xs text-green-700 font-medium">Total Modal ke Vendor</p>
                                        <p class="text-lg font-bold text-green-900">Rp {{ number_format($proyek->vendors_data->sum('total_vendor'), 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg border border-purple-200">
                                        <p class="text-xs text-purple-700 font-medium">Margin Keuntungan</p>
                                        <p class="text-lg font-bold text-purple-900">Rp {{ number_format($proyek->penawaranAktif->total_penawaran - $proyek->vendors_data->sum('total_vendor'), 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 p-2 bg-amber-50 rounded border border-amber-200">
                                    <p class="text-xs text-amber-800 flex items-center">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Catatan:</strong> Pembayaran ke vendor menggunakan harga modal, klien membayar dengan harga penawaran (sudah termasuk margin)
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Dropdown Vendor dengan Styling yang Lebih Jelas -->
                            <div class="mb-6">
                                <button onclick="toggleVendorDetails('proyek-{{ $proyek->id_proyek }}')" 
                                        class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-purple-50 hover:from-indigo-100 hover:to-purple-100 rounded-xl border-2 border-indigo-200 hover:border-indigo-300 transition-all duration-300 shadow-sm hover:shadow-md">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-building text-indigo-600 text-lg"></i>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-lg font-bold text-gray-800 block">
                                                Detail Vendor yang Perlu Pembayaran
                                            </span>
                                            <span class="text-sm text-gray-600">
                                                {{ $proyek->vendors_data->count() }} vendor terlibat dalam proyek ini
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="text-right">
                                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full font-medium">
                                                {{ $proyek->vendors_data->where('status_lunas', false)->count() }} Perlu Pembayaran
                                            </span>
                                        </div>
                                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center border border-indigo-300">
                                            <i class="fas fa-chevron-down text-indigo-600 transition-transform duration-300" id="chevron-proyek-{{ $proyek->id_proyek }}"></i>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            
                            <!-- Vendor Details (Hidden by default) -->
                            <div id="vendor-details-proyek-{{ $proyek->id_proyek }}" class="hidden space-y-3">
                                @foreach($proyek->vendors_data as $vendorData)
                                <div class="p-4 border rounded-lg 
                                    @if($vendorData->status_lunas) bg-green-50 border-green-200 
                                    @else bg-white border-gray-200 @endif">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h5 class="font-medium text-gray-900">{{ $vendorData->vendor->nama_vendor }}</h5>
                                                @if($vendorData->status_lunas)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    LUNAS
                                                </span>
                                                @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    BELUM LUNAS
                                                </span>
                                                @endif
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                                <div>
                                                    <p class="text-sm text-gray-600">{{ $vendorData->vendor->jenis_perusahaan }}</p>
                                                    <p class="text-xs text-gray-500">{{ $vendorData->vendor->email }}</p>
                                                    @if($vendorData->vendor->no_telp)
                                                    <p class="text-xs text-gray-500">{{ $vendorData->vendor->no_telp }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Total Modal:</span>
                                                        <span class="font-medium">Rp {{ number_format($vendorData->total_vendor, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Dibayar:</span>
                                                        <span class="font-medium text-green-600">Rp {{ number_format($vendorData->total_dibayar_approved, 0, ',', '.') }}</span>
                                                    </div>
                                                    @if(!$vendorData->status_lunas)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Sisa:</span>
                                                        <span class="font-medium text-red-600">Rp {{ number_format($vendorData->sisa_bayar, 0, ',', '.') }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Progress Bar -->
                                            <div class="mb-3">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-xs text-gray-600">Progress Pembayaran</span>
                                                    <span class="text-xs font-medium text-gray-700">{{ number_format($vendorData->persen_bayar, 1) }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="@if($vendorData->status_lunas) bg-green-600 @else bg-blue-600 @endif h-2 rounded-full transition-all duration-300" 
                                                         style="width: {{ min($vendorData->persen_bayar, 100) }}%"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex items-center space-x-2">
                                                @php
                                                    $canAccess = $currentUser->role === 'admin_purchasing' && $proyek->id_admin_purchasing == $currentUser->id_user;
                                                @endphp
                                                
                                                @if(!$vendorData->status_lunas)
                                                    @if($canAccess)
                                                    <a href="{{ route('purchasing.pembayaran.create', [$proyek->id_proyek, $vendorData->vendor->id_vendor]) }}" 
                                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                        <i class="fas fa-plus mr-1"></i>
                                                        Input Pembayaran
                                                    </a>
                                                    @else
                                                    <span class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 cursor-not-allowed">
                                                        <i class="fas fa-lock mr-1"></i>
                                                        @if($currentUser->role !== 'admin_purchasing')
                                                            Hanya Admin Purchasing
                                                        @else
                                                            Tidak Memiliki Akses
                                                        @endif
                                                    </span>
                                                    @endif
                                                @endif
                                                
                                                @if($proyek->pembayaran->where('id_vendor', $vendorData->vendor->id_vendor)->count() > 0)
                                                <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}?vendor={{ $vendorData->vendor->id_vendor }}" 
                                                   class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                    <i class="fas fa-history mr-1"></i>
                                                    History
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div> 
                @else
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <i class="fas fa-credit-card text-4xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada proyek yang perlu pembayaran</h3>
                    <p class="mt-1 text-sm text-gray-500">Semua proyek sudah dalam tahap selanjutnya atau belum ada yang di-ACC.</p>
                </div>
                @endif
            </div>
            
            @if($proyekPerluBayar->hasPages())
            <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Menampilkan {{ $proyekPerluBayar->firstItem() ?? 0 }} - {{ $proyekPerluBayar->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-800">{{ $proyekPerluBayar->total() }}</span> proyek
                    </div>
                    <div class="flex justify-center">
                        {{ $proyekPerluBayar->appends(['tab' => 'perlu-bayar'])->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Tab Content 2: Semua Proyek -->
    <div id="tab-semua-proyek" class="tab-content" style="display:none;">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Semua Proyek Pembayaran</h2>
                    <p class="text-gray-600 mt-1">Daftar lengkap proyek dengan status pembayaran, pengiriman, selesai, atau gagal (termasuk yang sudah lunas)</p>
                </div>
                
                <!-- Filter & Search Controls -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="GET" class="flex flex-col sm:flex-row gap-2">
                        <input type="hidden" name="tab" value="semua-proyek">
                        <!-- Search Input -->
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search }}" 
                                   placeholder="Cari proyek, klien, atau instansi..."
                                   class="block w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Status Proyek Filter -->
                        <select name="proyek_status_filter" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all" {{ $proyekStatusFilter == 'all' || !$proyekStatusFilter ? 'selected' : '' }}>Semua Status</option>
                            <option value="lunas" {{ $proyekStatusFilter == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="belum_lunas" {{ $proyekStatusFilter == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                        
                        <!-- Sort By -->
                        <select name="sort_by" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                            <option value="nama_barang" {{ $sortBy == 'nama_barang' ? 'selected' : '' }}>Nama Barang</option>
                            <option value="instansi" {{ $sortBy == 'instansi' ? 'selected' : '' }}>Instansi</option>
                            <option value="nama_klien" {{ $sortBy == 'nama_klien' ? 'selected' : '' }}>Klien</option>
                        </select>
                        
                        <!-- Sort Order -->
                        <select name="sort_order" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Z-A / Terbaru</option>
                            <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>A-Z / Terlama</option>
                        </select>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-filter mr-1"></i>
                            Filter
                        </button>
                        
                        <!-- Reset Button -->
                        @if($search || ($proyekStatusFilter && $proyekStatusFilter != 'all') || $sortBy != 'created_at' || $sortOrder != 'desc')
                        <a href="{{ route('purchasing.pembayaran') }}?tab=semua-proyek" 
                           class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <i class="fas fa-times mr-1"></i>
                            Reset
                        </a>
                        @endif
                    </form>
                </div>
            </div>
    
    <div class="overflow-x-auto">
        @if($semuaProyek->count() > 0)
        <div class="space-y-6">
            @foreach($semuaProyek as $proyek)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <!-- Header Proyek -->
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $proyek->nama_barang }}</h3>
                            <p class="text-sm text-gray-600">{{ $proyek->instansi }} - {{ $proyek->kota_kab }}</p>
                            <p class="text-xs text-gray-500">
                                No. Penawaran: {{ $proyek->penawaranAktif->no_penawaran }} | 
                                Klien: {{ $proyek->nama_klien }} | 
                                Dibuat: {{ $proyek->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                @if($proyek->status == 'Pembayaran') bg-blue-100 text-blue-800
                                @elseif($proyek->status == 'Pengiriman') bg-purple-100 text-purple-800
                                @elseif($proyek->status == 'Selesai') bg-green-100 text-green-800
                                @elseif($proyek->status == 'Gagal') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($proyek->status == 'Pembayaran')
                                    <i class="fas fa-credit-card mr-1"></i>
                                @elseif($proyek->status == 'Pengiriman')
                                    <i class="fas fa-shipping-fast mr-1"></i>
                                @elseif($proyek->status == 'Selesai')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @elseif($proyek->status == 'Gagal')
                                    <i class="fas fa-times-circle mr-1"></i>
                                @endif
                                {{ $proyek->status }}
                            </span>
                            <div class="mt-1">
                                @if($proyek->status_lunas)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Lunas
                                </span>
                                @elseif($proyek->total_dibayar_approved > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Cicilan
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Belum Bayar
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Pembayaran -->
                <div class="p-4">
                    <!-- Info Harga dan Margin -->
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <span class="font-medium text-blue-800">Harga Penawaran ke Klien:</span>
                                <span class="text-blue-900 font-semibold">Rp {{ number_format($proyek->penawaranAktif->total_penawaran, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-green-800">Total Harga Modal ke Vendor:</span>
                                <span class="text-green-900 font-semibold">Rp {{ number_format($proyek->vendors_data->sum('total_vendor'), 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-purple-800">Margin Keuntungan:</span>
                                <span class="text-purple-900 font-semibold">Rp {{ number_format($proyek->penawaranAktif->total_penawaran - $proyek->vendors_data->sum('total_vendor'), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Keseluruhan -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Progress Pembayaran Keseluruhan</span>
                            <span class="text-sm text-gray-600">{{ number_format($proyek->persen_bayar, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min($proyek->persen_bayar, 100) }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Dibayar: Rp {{ number_format($proyek->total_dibayar_approved, 0, ',', '.') }}</span>
                            @if(!$proyek->status_lunas)
                            <span>Sisa: Rp {{ number_format($proyek->sisa_bayar, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Dropdown Detail Vendor -->
                    <div class="mb-4">
                        <button onclick="toggleVendorDetails('semua-proyek-{{ $proyek->id_proyek }}')" 
                                class="w-full flex items-center justify-between p-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                            <div class="flex items-center">
                                <i class="fas fa-building text-gray-600 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">
                                    Detail Vendor & Pembayaran ({{ $proyek->vendors_data->count() }} vendor)
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-500">
                                    {{ $proyek->vendors_data->where('status_lunas', true)->count() }} lunas, 
                                    {{ $proyek->vendors_data->where('status_lunas', false)->count() }} belum lunas
                                </span>
                                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="chevron-semua-proyek-{{ $proyek->id_proyek }}"></i>
                            </div>
                        </button>
                    </div>
                    
                    <!-- Vendor Details (Hidden by default) -->
                    <div id="vendor-details-semua-proyek-{{ $proyek->id_proyek }}" class="hidden space-y-3 mb-4">
                        @foreach($proyek->vendors_data as $vendorData)
                        <div class="p-4 border rounded-lg 
                            @if($vendorData->status_lunas) bg-green-50 border-green-200 
                            @else bg-white border-gray-200 @endif">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h5 class="font-medium text-gray-900">{{ $vendorData->vendor->nama_vendor }}</h5>
                                        @if($vendorData->status_lunas)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            LUNAS
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            BELUM LUNAS
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                        <div>
                                            <p class="text-sm text-gray-600">{{ $vendorData->vendor->jenis_perusahaan }}</p>
                                            <p class="text-xs text-gray-500">{{ $vendorData->vendor->email }}</p>
                                            @if($vendorData->vendor->no_telp)
                                            <p class="text-xs text-gray-500">{{ $vendorData->vendor->no_telp }}</p>
                                            @endif
                                        </div>
                                        <div class="text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Total Modal:</span>
                                                <span class="font-medium">Rp {{ number_format($vendorData->total_vendor, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Dibayar:</span>
                                                <span class="font-medium text-green-600">Rp {{ number_format($vendorData->total_dibayar_approved, 0, ',', '.') }}</span>
                                            </div>
                                            @if(!$vendorData->status_lunas)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Sisa:</span>
                                                <span class="font-medium text-red-600">Rp {{ number_format($vendorData->sisa_bayar, 0, ',', '.') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs text-gray-600">Progress Pembayaran</span>
                                            <span class="text-xs font-medium text-gray-700">{{ number_format($vendorData->persen_bayar, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="@if($vendorData->status_lunas) bg-green-600 @else bg-blue-600 @endif h-2 rounded-full transition-all duration-300" 
                                                 style="width: {{ min($vendorData->persen_bayar, 100) }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Pembayaran History Summary -->
                                    @php
                                        $vendorPembayaran = $proyek->pembayaran->where('id_vendor', $vendorData->vendor->id_vendor);
                                        $vendorPending = $vendorPembayaran->where('status_verifikasi', 'Pending')->count();
                                        $vendorApproved = $vendorPembayaran->where('status_verifikasi', 'Approved')->count();
                                        $vendorDitolak = $vendorPembayaran->where('status_verifikasi', 'Ditolak')->count();
                                    @endphp
                                    
                                    @if($vendorPembayaran->count() > 0)
                                    <div class="mb-3 p-2 bg-gray-50 rounded text-xs">
                                        <span class="font-medium text-gray-700">Riwayat Pembayaran:</span>
                                        <span class="text-gray-600">{{ $vendorPembayaran->count() }} transaksi</span>
                                        @if($vendorPending > 0)<span class="text-yellow-600">({{ $vendorPending }} pending)</span>@endif
                                        @if($vendorApproved > 0)<span class="text-green-600">({{ $vendorApproved }} approved)</span>@endif
                                        @if($vendorDitolak > 0)<span class="text-red-600">({{ $vendorDitolak }} ditolak)</span>@endif
                                    </div>
                                    @endif
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-2">
                                        @if(!$vendorData->status_lunas)
                                            @if($canAccess)
                                            <a href="{{ route('purchasing.pembayaran.create', [$proyek->id_proyek, $vendorData->vendor->id_vendor]) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                <i class="fas fa-plus mr-1"></i>
                                                Input Pembayaran
                                            </a>
                                            @else
                                            <span class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 cursor-not-allowed">
                                                <i class="fas fa-lock mr-1"></i>
                                                @if($currentUser->role !== 'admin_purchasing')
                                                    Hanya Admin Purchasing
                                                @else
                                                    Tidak Memiliki Akses
                                                @endif
                                            </span>
                                            @endif
                                        @endif
                                        
                                        @if($vendorPembayaran->count() > 0)
                                        <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}?vendor={{ $vendorData->vendor->id_vendor }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <i class="fas fa-history mr-1"></i>
                                            History Vendor
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Statistik Pembayaran -->
                    @php
                        $pendingCount = $proyek->pembayaran->where('status_verifikasi', 'Pending')->count();
                        $approvedCount = $proyek->pembayaran->where('status_verifikasi', 'Approved')->count();
                        $ditolakCount = $proyek->pembayaran->where('status_verifikasi', 'Ditolak')->count();
                        $totalPembayaran = $proyek->pembayaran->count();
                    @endphp
                    
                    @if($totalPembayaran > 0)
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Statistik Pembayaran:</h5>
                        <div class="flex space-x-4 text-xs">
                            <span class="text-gray-600">
                                <i class="fas fa-receipt mr-1"></i>
                                Total: {{ $totalPembayaran }}
                            </span>
                            @if($pendingCount > 0)
                            <span class="text-yellow-600">
                                <i class="fas fa-hourglass-half mr-1"></i>
                                Pending: {{ $pendingCount }}
                            </span>
                            @endif
                            @if($approvedCount > 0)
                            <span class="text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>
                                Approved: {{ $approvedCount }}
                            </span>
                            @endif
                            @if($ditolakCount > 0)
                            <span class="text-red-600">
                                <i class="fas fa-times-circle mr-1"></i>
                                Ditolak: {{ $ditolakCount }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if(!$proyek->status_lunas)
                            @if($canAccess)
                            <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-plus mr-1"></i>
                                Input Pembayaran
                            </a>
                            @else
                            <span class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 cursor-not-allowed">
                                <i class="fas fa-lock mr-1"></i>
                                @if($currentUser->role !== 'admin_purchasing')
                                    Hanya Admin Purchasing
                                @else
                                    Tidak Memiliki Akses
                                @endif
                            </span>
                            @endif
                        @endif
                        
                        @if($totalPembayaran > 0)
                        <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-history mr-1"></i>
                            History Pembayaran
                        </a>
                        @endif
                        
                        <a href="#" 
                           onclick="showProyekDetail({{ json_encode($proyek) }})"
                           class="inline-flex items-center px-3 py-2 border border-indigo-300 text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                            <i class="fas fa-eye mr-1"></i>
                            Detail Lengkap
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Summary Footer -->
        <div class="mt-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
            <div class="flex justify-between items-center text-sm">
                <div class="flex space-x-6">
                    <span class="text-gray-700">
                        <i class="fas fa-check-circle text-green-600 mr-1"></i>
                        Lunas: {{ $semuaProyek->where('status_lunas', true)->count() }}
                    </span>
                    <span class="text-gray-700">
                        <i class="fas fa-clock text-yellow-600 mr-1"></i>
                        Belum Lunas: {{ $semuaProyek->where('status_lunas', false)->count() }}
                    </span>
                    @if($search)
                    <span class="text-blue-700">
                        <i class="fas fa-search mr-1"></i>
                        Pencarian: "{{ $search }}"
                    </span>
                    @endif
                    @if($proyekStatusFilter && $proyekStatusFilter !== 'all')
                    <span class="text-purple-700">
                        <i class="fas fa-filter mr-1"></i>
                        Filter: {{ $proyekStatusFilter == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                    @endif
                </div>
                <div class="font-medium text-gray-900">
                    Total: {{ $semuaProyek->count() }} proyek
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <i class="fas fa-project-diagram text-4xl"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                @if($search)
                    Tidak ada proyek yang sesuai dengan pencarian
                @else
                    Belum ada proyek pembayaran
                @endif
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                @if($search)
                    Coba gunakan kata kunci yang berbeda atau reset filter.
                @else
                    Proyek akan muncul setelah penawaran di-ACC dan masuk tahap pembayaran.
                @endif
            </p>
        </div>
        @endif
    </div>
            
            @if($semuaProyek->hasPages())
            <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Menampilkan {{ $semuaProyek->firstItem() ?? 0 }} - {{ $semuaProyek->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-800">{{ $semuaProyek->total() }}</span> proyek
                    </div>
                    <div class="flex justify-center">
                        {{ $semuaProyek->appends(['tab' => 'semua-proyek'])->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Tab Content 3: Semua Pembayaran -->
    <div id="tab-semua-pembayaran" class="tab-content" style="display:none;">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Semua Pembayaran</h2>
                    <p class="text-gray-600 mt-1">Daftar lengkap pembayaran dari proyek dengan status pembayaran, pengiriman, selesai, atau gagal (Pending, Approved, Ditolak)</p>
                </div>
                
                <!-- Filter Controls untuk Pembayaran -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="GET" class="flex flex-col sm:flex-row gap-2">
                        <input type="hidden" name="tab" value="semua-pembayaran">
                        @if($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                        @endif
                        
                        <!-- Status Filter -->
                        <select name="status_filter" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                            <option value="all" {{ $statusFilter == 'all' || !$statusFilter ? 'selected' : '' }}>Semua Status</option>
                            <option value="Pending" {{ $statusFilter == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $statusFilter == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Ditolak" {{ $statusFilter == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        
                        @if($statusFilter && $statusFilter !== 'all')
                        <a href="{{ route('purchasing.pembayaran') }}?tab=semua-pembayaran" 
                           class="px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                            <i class="fas fa-times mr-1"></i>
                            Reset Filter
                        </a>
                        @endif
                    </form>
                </div>
            </div>
    
    <div class="overflow-x-auto">
        @if($semuaPembayaran->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($semuaPembayaran as $pembayaran)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $pembayaran->penawaran->proyek->nama_barang }}</div>
                        <div class="text-sm text-gray-500">{{ $pembayaran->penawaran->proyek->instansi }}</div>
                        <div class="text-xs text-gray-400">No. {{ $pembayaran->penawaran->no_penawaran }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $pembayaran->vendor->nama_vendor }}</div>
                        <div class="text-sm text-gray-500">{{ $pembayaran->vendor->jenis_perusahaan }}</div>
                        <div class="text-xs text-gray-400">{{ $pembayaran->vendor->email }}</div>
                    </td>
              
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($pembayaran->jenis_bayar == 'Lunas') bg-green-100 text-green-800
                            @elseif($pembayaran->jenis_bayar == 'DP') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $pembayaran->jenis_bayar }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}
                        </div>
                        @php
                            // Hitung total modal vendor untuk vendor pembayaran ini
                            $totalModalVendorPembayaran = $pembayaran->penawaran->proyek->penawaranAktif->penawaranDetail
                                ->where('barang.id_vendor', $pembayaran->id_vendor)
                                ->sum(function($detail) {
                                    return $detail->qty * $detail->barang->harga_vendor;
                                });
                            $persenNominal = $totalModalVendorPembayaran > 0 ? 
                                ($pembayaran->nominal_bayar / $totalModalVendorPembayaran) * 100 : 0;
                        @endphp
                        <div class="text-xs text-gray-500">{{ number_format($persenNominal, 1) }}% dari modal vendor</div>
                    </td>
            
                    <td class="px-6 py-4">
                        @if($pembayaran->status_verifikasi == 'Pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-hourglass-half mr-1"></i>
                            Pending
                        </span>
                        @elseif($pembayaran->status_verifikasi == 'Approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Approved
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>
                            Ditolak
                        </span>
                        @endif
                        
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $pembayaran->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('purchasing.pembayaran.show', $pembayaran->id_pembayaran) }}" 
                               class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
                            
                            @if($pembayaran->bukti_bayar)
                            <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" 
                               target="_blank"
                               class="inline-flex items-center px-2 py-1 border border-blue-300 text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100">
                                <i class="fas fa-file-image mr-1"></i>
                                Bukti
                            </a>
                            @endif
                            
                            @if($pembayaran->status_verifikasi == 'Pending')
                                @php
                                    $canAccessPembayaran = $currentUser->role === 'admin_purchasing' && $pembayaran->penawaran->proyek->id_admin_purchasing == $currentUser->id_user;
                                @endphp
                                
                                @if($canAccessPembayaran)
                                <a href="{{ route('purchasing.pembayaran.edit', $pembayaran->id_pembayaran) }}" 
                                   class="inline-flex items-center px-2 py-1 border border-yellow-300 text-xs leading-4 font-medium rounded text-yellow-700 bg-yellow-50 hover:bg-yellow-100">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                
                                <form action="{{ route('purchasing.pembayaran.destroy', $pembayaran->id_pembayaran) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini? File bukti pembayaran juga akan dihapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-2 py-1 border border-red-300 text-xs leading-4 font-medium rounded text-red-700 bg-red-50 hover:bg-red-100">
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </form>
                                @else
                                <span class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-500 bg-gray-100 cursor-not-allowed">
                                    <i class="fas fa-lock mr-1"></i>
                                    @if($currentUser->role !== 'admin_purchasing')
                                        Hanya Admin Purchasing
                                    @else
                                        Tidak Memiliki Akses
                                    @endif
                                </span>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Summary Footer -->
        <div class="bg-gray-50 px-6 py-3 border-t">
            <div class="flex justify-between items-center text-sm">
                <div class="flex space-x-6">
                    <span class="text-gray-700">
                        <i class="fas fa-hourglass-half text-yellow-600 mr-1"></i>
                        Pending: {{ $semuaPembayaran->where('status_verifikasi', 'Pending')->count() }}
                    </span>
                    <span class="text-gray-700">
                        <i class="fas fa-check-circle text-green-600 mr-1"></i>
                        Approved: {{ $semuaPembayaran->where('status_verifikasi', 'Approved')->count() }}
                    </span>
                    <span class="text-gray-700">
                        <i class="fas fa-times-circle text-red-600 mr-1"></i>
                        Ditolak: {{ $semuaPembayaran->where('status_verifikasi', 'Ditolak')->count() }}
                    </span>
                </div>
                <div class="font-medium text-gray-900">
                    Total: {{ $semuaPembayaran->count() }} pembayaran
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <i class="fas fa-receipt text-4xl"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pembayaran</h3>
            <p class="mt-1 text-sm text-gray-500">Pembayaran akan muncul setelah admin purchasing menginput data.</p>
        </div>
        @endif
    </div>
            
            @if($semuaPembayaran->hasPages())
            <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Menampilkan {{ $semuaPembayaran->firstItem() ?? 0 }} - {{ $semuaPembayaran->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-800">{{ $semuaPembayaran->total() }}</span> pembayaran
                    </div>
                    <div class="flex justify-center">
                        {{ $semuaPembayaran->appends(['tab' => 'semua-pembayaran'])->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Modal Detail Proyek -->
<div id="proyekDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Detail Proyek</h3>
                <button onclick="closeProyekDetail()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="mt-4" id="modalContent">
                <!-- Content will be populated by JavaScript -->
            </div>
            
            <!-- Modal Footer -->
            <div class="flex justify-end pt-4 border-t mt-4">
                <button onclick="closeProyekDetail()" 
                        class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables for access control
window.currentUserId = {{ $currentUser->id_user }};
window.currentUserRole = '{{ $currentUser->role }}';

function showProyekDetail(proyek) {
    const modal = document.getElementById('proyekDetailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    modalTitle.textContent = `Detail: ${proyek.nama_barang}`;
    
    const totalPembayaran = proyek.pembayaran.length;
    const pendingCount = proyek.pembayaran.filter(p => p.status_verifikasi === 'Pending').length;
    const approvedCount = proyek.pembayaran.filter(p => p.status_verifikasi === 'Approved').length;
    const ditolakCount = proyek.pembayaran.filter(p => p.status_verifikasi === 'Ditolak').length;
    
    // Get current user info from global variables
    const currentUserId = window.currentUserId;
    const currentUserRole = window.currentUserRole;
    const canAccess = currentUserRole === 'admin_purchasing' && proyek.id_admin_purchasing == currentUserId;
    
    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Informasi Proyek -->
            <div class="space-y-3">
                <h4 class="font-medium text-gray-900 border-b pb-1">Informasi Proyek</h4>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium text-gray-600">Nama Barang:</span> ${proyek.nama_barang}</div>
                    <div><span class="font-medium text-gray-600">Instansi:</span> ${proyek.instansi}</div>
                    <div><span class="font-medium text-gray-600">Kota/Kab:</span> ${proyek.kota_kab}</div>
                    <div><span class="font-medium text-gray-600">Klien:</span> ${proyek.nama_klien}</div>
                    <div><span class="font-medium text-gray-600">Kontak:</span> ${proyek.kontak_klien || 'Tidak ada'}</div>
                    <div><span class="font-medium text-gray-600">No. Penawaran:</span> ${proyek.penawaran_aktif.no_penawaran}</div>
                    <div><span class="font-medium text-gray-600">Dibuat:</span> ${new Date(proyek.created_at).toLocaleDateString('id-ID')}</div>
                </div>
            </div>
            
            <!-- Informasi Pembayaran -->
            <div class="space-y-3">
                <h4 class="font-medium text-gray-900 border-b pb-1">Informasi Pembayaran</h4>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium text-gray-600">Total Penawaran:</span> 
                        <span class="font-semibold text-green-600">Rp ${new Intl.NumberFormat('id-ID').format(proyek.penawaran_aktif.total_penawaran)}</span>
                    </div>
                    <div><span class="font-medium text-gray-600">Total Dibayar (Approved):</span> 
                        <span class="font-semibold text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(proyek.total_dibayar_approved)}</span>
                    </div>
                    <div><span class="font-medium text-gray-600">Sisa Bayar:</span> 
                        <span class="font-semibold ${proyek.status_lunas ? 'text-green-600' : 'text-orange-600'}">
                            ${proyek.status_lunas ? 'LUNAS' : 'Rp ' + new Intl.NumberFormat('id-ID').format(proyek.sisa_bayar)}
                        </span>
                    </div>
                    <div><span class="font-medium text-gray-600">Progress:</span> 
                        <span class="font-semibold">${proyek.persen_bayar.toFixed(1)}%</span>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: ${Math.min(proyek.persen_bayar, 100)}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistik Pembayaran -->
        <div class="mt-6">
            <h4 class="font-medium text-gray-900 border-b pb-1 mb-3">Statistik Pembayaran</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-gray-800">${totalPembayaran}</div>
                    <div class="text-xs text-gray-600">Total Transaksi</div>
                </div>
                <div class="text-center p-3 bg-yellow-50 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">${pendingCount}</div>
                    <div class="text-xs text-yellow-600">Pending</div>
                </div>
                <div class="text-center p-3 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">${approvedCount}</div>
                    <div class="text-xs text-green-600">Approved</div>
                </div>
                <div class="text-center p-3 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">${ditolakCount}</div>
                    <div class="text-xs text-red-600">Ditolak</div>
                </div>
            </div>
        </div>
        
        ${!canAccess && currentUserRole !== 'admin_purchasing' ? `
            <!-- Access Info Banner for Non-Admin -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    <span class="text-blue-700 text-sm font-medium">
                        Anda dapat melihat detail proyek ini namun tidak dapat melakukan aksi pembayaran.
                    </span>
                </div>
            </div>
        ` : ''}
        
        ${!canAccess && currentUserRole === 'admin_purchasing' ? `
            <!-- Access Info Banner for Other Admin Purchasing -->
            <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-lock text-orange-500 mr-2"></i>
                    <span class="text-orange-700 text-sm font-medium">
                        Proyek ini ditangani oleh admin purchasing lain. Anda hanya dapat melihat detail.
                    </span>
                </div>
            </div>
        ` : ''}
        
        <!-- Action Buttons -->
        <div class="mt-6 flex flex-wrap gap-2">
            ${canAccess && !proyek.status_lunas ? `
                <a href="/purchasing/pembayaran/create/${proyek.id_proyek}" 
                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Input Pembayaran Baru
                </a>
            ` : ''}
            
            ${!canAccess && !proyek.status_lunas ? `
                <button disabled
                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i>
                    Input Pembayaran (Terkunci)
                </button>
            ` : ''}
            
            ${totalPembayaran > 0 ? `
                <a href="/purchasing/pembayaran/history/${proyek.id_proyek}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-history mr-2"></i>
                    Lihat Riwayat Pembayaran
                </a>
            ` : ''}
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeProyekDetail() {
    const modal = document.getElementById('proyekDetailModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('proyekDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProyekDetail();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProyekDetail();
    }
});

// Tab Navigation Functions
function openTab(evt, tabName) {
    // Hide all tab content
    const tabcontent = document.getElementsByClassName("tab-content");
    for (let i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    
    // Remove active class from all tab buttons
    const tabbuttons = document.getElementsByClassName("tab-button");
    for (let i = 0; i < tabbuttons.length; i++) {
        tabbuttons[i].classList.remove("border-red-500", "text-red-600", "bg-white");
        tabbuttons[i].classList.add("border-transparent", "text-gray-500", "bg-gray-50");
    }
    
    // Show the selected tab content
    document.getElementById(tabName).style.display = "block";
    
    // Add active class to the clicked button
    evt.currentTarget.classList.remove("border-transparent", "text-gray-500", "bg-gray-50");
    evt.currentTarget.classList.add("border-red-500", "text-red-600", "bg-white");
    
    // Update badge colors for active tab
    const badges = evt.currentTarget.querySelectorAll('span');
    badges.forEach(badge => {
        if (badge.classList.contains('bg-gray-200')) {
            badge.classList.remove('bg-gray-200', 'text-gray-700');
            badge.classList.add('bg-red-100', 'text-red-800');
        }
    });
    
    // Reset other tab badges
    tabbuttons.forEach(button => {
        if (button !== evt.currentTarget) {
            const badges = button.querySelectorAll('span');
            badges.forEach(badge => {
                if (badge.classList.contains('bg-red-100')) {
                    badge.classList.remove('bg-red-100', 'text-red-800');
                    badge.classList.add('bg-gray-200', 'text-gray-700');
                }
            });
        }
    });
    
    // Update URL parameter without page reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName.replace('tab-', ''));
    window.history.pushState({}, '', url);
}

// Initialize tabs on page load
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = '{{ $activeTab ?? "perlu-bayar" }}';
    
    // Show the tab from backend/URL parameter
    const tabButton = document.querySelector(`button[onclick*="tab-${activeTab}"]`);
    if (tabButton) {
        // Manually trigger the tab display
        const tabcontent = document.getElementsByClassName("tab-content");
        for (let i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        
        const tabbuttons = document.getElementsByClassName("tab-button");
        for (let i = 0; i < tabbuttons.length; i++) {
            tabbuttons[i].classList.remove("border-red-500", "text-red-600", "bg-white");
            tabbuttons[i].classList.add("border-transparent", "text-gray-500", "bg-gray-50");
        }
        
        document.getElementById(`tab-${activeTab}`).style.display = "block";
        tabButton.classList.remove("border-transparent", "text-gray-500", "bg-gray-50");
        tabButton.classList.add("border-red-500", "text-red-600", "bg-white");
    } else {
        // Default: show first tab
        document.getElementById("defaultOpen").click();
    }
});

// Toggle Vendor Details Dropdown
function toggleVendorDetails(elementId) {
    const vendorDetails = document.getElementById(`vendor-details-${elementId}`);
    const chevronIcon = document.getElementById(`chevron-${elementId}`);
    
    if (vendorDetails.classList.contains('hidden')) {
        // Show the details
        vendorDetails.classList.remove('hidden');
        vendorDetails.classList.add('animate-fadeIn');
        chevronIcon.style.transform = 'rotate(180deg)';
    } else {
        // Hide the details
        vendorDetails.classList.add('hidden');
        vendorDetails.classList.remove('animate-fadeIn');
        chevronIcon.style.transform = 'rotate(0deg)';
    }
}

// Close all dropdowns when clicking outside
document.addEventListener('click', function(event) {
    // Check if the click is outside of any dropdown button or content
    if (!event.target.closest('button[onclick*="toggleVendorDetails"]') && 
        !event.target.closest('[id*="vendor-details-"]')) {
        
        // Find all open dropdowns and close them
        const openDropdowns = document.querySelectorAll('[id*="vendor-details-"]:not(.hidden)');
        openDropdowns.forEach(dropdown => {
            const elementId = dropdown.id.replace('vendor-details-', '');
            const chevronIcon = document.getElementById(`chevron-${elementId}`);
            
            dropdown.classList.add('hidden');
            dropdown.classList.remove('animate-fadeIn');
            if (chevronIcon) {
                chevronIcon.style.transform = 'rotate(0deg)';
            }
        });
    }
});
</script>

<!-- Add custom CSS for smooth animations -->
<style>
.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Smooth transition for chevron rotation */
[id*="chevron-"] {
    transition: transform 0.3s ease-in-out;
}
</style>
@endpush
