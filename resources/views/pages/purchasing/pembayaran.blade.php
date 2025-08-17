@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-2xl p-6 lg:p-8 mb-8 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl lg:text-4xl font-bold mb-2">Pembayaran Purchasing</h1>
                <p class="text-red-100 text-base lg:text-lg opacity-90">Kelola pembayaran proyek yang sudah di-ACC klien</p>
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
                    <p class="text-gray-600 mt-1">Daftar proyek yang sudah di-ACC dan menunggu pembayaran dari klien</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                @if($proyekPerluBayar->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penawaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($proyekPerluBayar as $proyek)
                        @php
                            // Hitung total yang sudah dibayar dan disetujui (approved saja)
                            $totalDibayarApproved = $proyek->pembayaran->where('status_verifikasi', 'Approved')->sum('nominal_bayar');
                            $sisaBayar = $proyek->penawaranAktif->total_penawaran - $totalDibayarApproved;
                            $persenBayar = $proyek->penawaranAktif->total_penawaran > 0 ? 
                                ($totalDibayarApproved / $proyek->penawaranAktif->total_penawaran) * 100 : 0;
                        @endphp
                        
                        @if($sisaBayar > 0)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $proyek->nama_barang }}</div>
                                    <div class="text-sm text-gray-500">{{ $proyek->instansi }} - {{ $proyek->kota_kab }}</div>
                                    <div class="text-xs text-gray-400">No. Penawaran: {{ $proyek->penawaranAktif->no_penawaran }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $proyek->nama_klien }}</div>
                                <div class="text-sm text-gray-500">{{ $proyek->kontak_klien }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($proyek->penawaranAktif->total_penawaran, 0, ',', '.') }}
                                </div>
                                @if($totalDibayarApproved > 0)
                                <div class="text-xs text-gray-500">
                                    Dibayar: Rp {{ number_format($totalDibayarApproved, 0, ',', '.') }} ({{ number_format($persenBayar, 1) }}%)
                                </div>
                                <div class="text-xs text-orange-600">
                                    Sisa: Rp {{ number_format($sisaBayar, 0, ',', '.') }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($sisaBayar <= 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Lunas
                                    </span>
                                @elseif($totalDibayarApproved > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Cicilan ({{ number_format($persenBayar, 0) }}%)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Belum Bayar
                                    </span>
                                @endif
                                
                                @php
                                    $pendingCount = $proyek->pembayaran->where('status_verifikasi', 'Pending')->count();
                                    $ditolakCount = $proyek->pembayaran->where('status_verifikasi', 'Ditolak')->count();
                                @endphp
                                
                                @if($pendingCount > 0)
                                <div class="text-xs text-yellow-600 mt-1">
                                    <i class="fas fa-hourglass-half"></i> {{ $pendingCount }} pending
                                </div>
                                @endif
                                
                                @if($ditolakCount > 0)
                                <div class="text-xs text-red-600 mt-1">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $ditolakCount }} ditolak
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    @if($sisaBayar > 0)
                                    <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-plus mr-1"></i>
                                        Input Bayar
                                    </a>
                                    @endif
                                    
                                    @if($proyek->pembayaran->count() > 0)
                                    <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-history mr-1"></i>
                                        Riwayat
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
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
                    <p class="text-gray-600 mt-1">Daftar lengkap proyek dengan status pembayaran (termasuk yang sudah lunas)</p>
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
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penawaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress Bayar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($semuaProyek as $proyek)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $proyek->nama_barang }}</div>
                            <div class="text-sm text-gray-500">{{ $proyek->instansi }} - {{ $proyek->kota_kab }}</div>
                            <div class="text-xs text-gray-400">No. Penawaran: {{ $proyek->penawaranAktif->no_penawaran }}</div>
                            <div class="text-xs text-gray-400">Dibuat: {{ $proyek->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $proyek->nama_klien }}</div>
                        <div class="text-sm text-gray-500">{{ $proyek->kontak_klien }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($proyek->penawaranAktif->total_penawaran, 0, ',', '.') }}
                        </div>
                        @if($proyek->total_dibayar_approved > 0)
                        <div class="text-xs text-gray-500">
                            Dibayar: Rp {{ number_format($proyek->total_dibayar_approved, 0, ',', '.') }}
                        </div>
                        @endif
                        @if(!$proyek->status_lunas)
                        <div class="text-xs text-orange-600">
                            Sisa: Rp {{ number_format($proyek->sisa_bayar, 0, ',', '.') }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min($proyek->persen_bayar, 100) }}%"></div>
                        </div>
                        <div class="text-xs text-gray-600">
                            {{ number_format($proyek->persen_bayar, 1) }}% dari total
                        </div>
                        @if($proyek->total_dibayar_approved > 0)
                        <div class="text-xs text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>
                            Rp {{ number_format($proyek->total_dibayar_approved, 0, ',', '.') }} verified
                        </div>
                        @endif
                        
                        @php
                            $pendingCount = $proyek->pembayaran->where('status_verifikasi', 'Pending')->count();
                            $ditolakCount = $proyek->pembayaran->where('status_verifikasi', 'Ditolak')->count();
                        @endphp
                        
                        @if($pendingCount > 0)
                        <div class="text-xs text-yellow-600">
                            <i class="fas fa-hourglass-half mr-1"></i>{{ $pendingCount }} pending
                        </div>
                        @endif
                        
                        @if($ditolakCount > 0)
                        <div class="text-xs text-red-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $ditolakCount }} ditolak
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
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
                        
                        <!-- Total Pembayaran Count -->
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-receipt mr-1"></i>
                            {{ $proyek->pembayaran->count() }} pembayaran
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            @if(!$proyek->status_lunas)
                            <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
                               class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-plus mr-1"></i>
                                Input
                            </a>
                            @endif
                            
                            @if($proyek->pembayaran->count() > 0)
                            <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}" 
                               class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-history mr-1"></i>
                                History
                            </a>
                            @endif
                            
                            <!-- Detail Proyek Link -->
                            <a href="#" 
                               onclick="showProyekDetail({{ json_encode($proyek) }})"
                               class="inline-flex items-center px-2 py-1 border border-indigo-300 text-xs leading-4 font-medium rounded text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
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
                    <p class="text-gray-600 mt-1">Daftar lengkap pembayaran dengan semua status (Pending, Approved, Ditolak)</p>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
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
                        <div class="text-sm text-gray-900">{{ $pembayaran->penawaran->proyek->nama_klien }}</div>
                        <div class="text-sm text-gray-500">{{ $pembayaran->penawaran->proyek->kontak_klien }}</div>
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
                            $persenNominal = $pembayaran->penawaran->total_penawaran > 0 ? 
                                ($pembayaran->nominal_bayar / $pembayaran->penawaran->total_penawaran) * 100 : 0;
                        @endphp
                        <div class="text-xs text-gray-500">{{ number_format($persenNominal, 1) }}% dari total</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $pembayaran->metode_bayar }}</div>
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
function showProyekDetail(proyek) {
    const modal = document.getElementById('proyekDetailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    modalTitle.textContent = `Detail: ${proyek.nama_barang}`;
    
    const totalPembayaran = proyek.pembayaran.length;
    const pendingCount = proyek.pembayaran.filter(p => p.status_verifikasi === 'Pending').length;
    const approvedCount = proyek.pembayaran.filter(p => p.status_verifikasi === 'Approved').length;
    const ditolakCount = proyek.pembayaran.filter(p => p.status_verifikasi === 'Ditolak').length;
    
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
        
        <!-- Action Buttons -->
        <div class="mt-6 flex flex-wrap gap-2">
            ${!proyek.status_lunas ? `
                <a href="/purchasing/pembayaran/create/${proyek.id_proyek}" 
                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Input Pembayaran Baru
                </a>
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
</script>
@endpush
