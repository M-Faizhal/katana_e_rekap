@extends('layouts.app')

@section('title', 'Kalkulasi - Cyber KATANA')

@section('content')
<!-- Access Control Info -->
@php
    $currentUser = Auth::user();
    $isAdminPurchasing = $currentUser->role === 'admin_purchasing';
    $isSuperadmin = $currentUser->role === 'superadmin';
@endphp


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

<!-- Tabs Section -->
<div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 mb-4 sm:mb-6">
    <div class="flex border-b border-gray-200">
        <button onclick="showTab('menunggu')" id="tab-menunggu" class="tab-button flex-1 px-4 py-3 text-sm font-medium text-center border-b-2 border-red-600 text-red-600 bg-red-50">
            <i class="fas fa-clock mr-2"></i>
            Menunggu Kalkulasi
        </button>
        <button onclick="showTab('proses')" id="tab-proses" class="tab-button flex-1 px-4 py-3 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
            <i class="fas fa-cog mr-2"></i>
            Proses Penawaran
        </button>
        <button onclick="showTab('berhasil')" id="tab-berhasil" class="tab-button flex-1 px-4 py-3 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
            <i class="fas fa-check-circle mr-2"></i>
            Penawaran Berhasil
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
    <form method="GET" action="{{ route('purchasing.kalkulasi') }}" class="space-y-3 sm:space-y-0 sm:flex sm:gap-3 md:gap-4 sm:items-center sm:justify-between">
        <div class="flex flex-col sm:flex-row gap-3 md:gap-4 w-full sm:w-auto">
            <div class="relative flex-1 sm:flex-none">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari proyek..."
                       class="border border-gray-300 rounded-lg px-3 sm:px-4 py-2 pl-9 sm:pl-10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 w-full sm:w-48 md:w-64 text-sm">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
            </div>
        </div>
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm w-full sm:w-auto">
            <i class="fas fa-search mr-1 sm:mr-2"></i>
            Cari
        </button>
    </form>
</div>


<!-- Tab Content -->
<div id="tab-content">
    <!-- Tab Menunggu Kalkulasi -->
    <div id="content-menunggu" class="tab-content active">
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800">Proyek Menunggu Kalkulasi</h2>
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marketing</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchasing</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Items</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $proyekMenunggu = $proyekMenunggu ?? collect();
                        @endphp
                        @forelse($proyekMenunggu as $p)
                        @php
                            $currentUser = Auth::user();
                            $isSuperadmin = $currentUser->role === 'superadmin';
                            $canAccess = ($currentUser->role === 'admin_purchasing' && $p->id_admin_purchasing == $currentUser->id_user) || $isSuperadmin;
                        @endphp
                        <tr class="hover:bg-gray-50 {{ $canAccess ? 'cursor-pointer' : 'cursor-not-allowed opacity-75' }}"
                            @if($canAccess) onclick="window.location.href='/purchasing/kalkulasi/{{ $p->id_proyek }}/hps'" @endif>

                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ $p->proyekBarang->first()->nama_barang }}
                                        @if($p->proyekBarang->count() > 1)
                                            <span class="text-xs text-gray-500">+{{ $p->proyekBarang->count() - 1 }} items</span>
                                        @endif
                                    @else
                                        Project Items
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">PRJ{{ str_pad($p->id_proyek, 3, '0', STR_PAD_LEFT) }}</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $p->kode_proyek }}</div>
                                <div class="text-sm text-gray-500">{{ $p->instansi }}</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $marketingName = 'N/A';
                                    if ($p->adminMarketing) {
                                        $marketingName = $p->adminMarketing->nama;
                                    } elseif ($p->id_admin_marketing) {
                                        $marketingUser = \App\Models\User::find($p->id_admin_marketing);
                                        $marketingName = $marketingUser ? $marketingUser->nama : 'User ID: ' . $p->id_admin_marketing;
                                    }
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $marketingName }}</div>
                                <div class="text-sm text-gray-500">Marketing</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $purchasingName = 'N/A';
                                    if ($p->adminPurchasing) {
                                        $purchasingName = $p->adminPurchasing->nama;
                                    } elseif ($p->id_admin_purchasing) {
                                        $purchasingUser = \App\Models\User::find($p->id_admin_purchasing);
                                        $purchasingName = $purchasingUser ? $purchasingUser->nama : 'User ID: ' . $p->id_admin_purchasing;
                                    }
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $purchasingName }}</div>
                                <div class="text-sm text-gray-500">Purchasing</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ $p->proyekBarang->count() }} Item{{ $p->proyekBarang->count() > 1 ? 's' : '' }}
                                    @else
                                        0 Items
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ number_format($p->proyekBarang->sum('jumlah')) }} {{ $p->proyekBarang->first()->satuan }}
                                    @else
                                        0 Unit
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-red-600">{{ 'Rp ' . number_format($p->harga_total, 0, ',', '.') }}</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                @if($canAccess)
                                    <button onclick="event.stopPropagation(); openHpsModal({{ $p->id_proyek }})"
                                            class="text-red-600 hover:text-red-900 mr-3"
                                            title="Buka Kalkulasi HPS">
                                        <i class="fas fa-calculator"></i> Kalkulasi
                                    </button>
                                    @if($isSuperadmin)
                                        <span class="text-xs text-green-600 ml-2"><i class="fas fa-user-shield"></i> Superadmin: akses penuh</span>
                                    @endif
                                @else
                                    @if($currentUser->role !== 'admin_purchasing')
                                        <span class="text-gray-400 text-xs">
                                            <i class="fas fa-info-circle"></i> Hanya admin purchasing
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">
                                            <i class="fas fa-lock"></i> Tidak memiliki akses
                                        </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-500">Tidak ada proyek menunggu kalkulasi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile & Tablet Card View -->
            <div class="lg:hidden">
                @forelse($proyekMenunggu as $p)
                @php
                    $currentUser = Auth::user();
                    $canAccess = $currentUser->role === 'admin_purchasing' && $p->id_admin_purchasing == $currentUser->id_user;
                @endphp

                <div class="border-b border-gray-200 p-3 sm:p-4 hover:bg-gray-50 {{ $canAccess ? 'cursor-pointer' : 'cursor-not-allowed opacity-75' }}"
                     @if($canAccess) onclick="openHpsModal({{ $p->id_proyek }})" @endif>
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="text-sm sm:text-base font-medium text-gray-900 line-clamp-2">
                                @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                    {{ $p->proyekBarang->first()->nama_barang }}
                                    @if($p->proyekBarang->count() > 1)
                                        <span class="text-xs text-gray-500">+{{ $p->proyekBarang->count() - 1 }} items</span>
                                    @endif
                                @else
                                    Project Items
                                @endif
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">PRJ{{ str_pad($p->id_proyek, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-3 shrink-0 bg-yellow-100 text-yellow-800">
                            Menunggu
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                        <div>
                            <p class="text-xs text-gray-500">Klien</p>
                            <p class="text-sm font-medium text-gray-900">{{ $p->kode_proyek }}</p>
                            <p class="text-xs text-gray-500">{{ $p->instansi }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                        <div>
                            <p class="text-xs text-gray-500">Marketing</p>
                            @php
                                $marketingName = 'N/A';
                                if ($p->adminMarketing) {
                                    $marketingName = $p->adminMarketing->nama;
                                } elseif ($p->id_admin_marketing) {
                                    $marketingUser = \App\Models\User::find($p->id_admin_marketing);
                                    $marketingName = $marketingUser ? $marketingUser->nama : 'User ID: ' . $p->id_admin_marketing;
                                }
                            @endphp
                            <p class="text-sm font-medium text-gray-900">{{ $marketingName }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Purchasing</p>
                            @php
                                $purchasingName = 'N/A';
                                if ($p->adminPurchasing) {
                                    $purchasingName = $p->adminPurchasing->nama;
                                } elseif ($p->id_admin_purchasing) {
                                    $purchasingUser = \App\Models\User::find($p->id_admin_purchasing);
                                    $purchasingName = $purchasingUser ? $purchasingUser->nama : 'User ID: ' . $p->id_admin_purchasing;
                                }
                            @endphp
                            <p class="text-sm font-medium text-gray-900">{{ $purchasingName }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Jumlah & Total</span>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ number_format($p->proyekBarang->sum('jumlah')) }} {{ $p->proyekBarang->first()->satuan }}
                                    @else
                                        0 Unit
                                    @endif
                                </p>
                                <p class="text-sm font-bold text-red-600">{{ 'Rp ' . number_format($p->harga_total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2 border-t border-gray-100">
                        @if($canAccess)
                            <button onclick="event.stopPropagation(); window.location.href='/purchasing/kalkulasi/{{ $p->id_proyek }}/hps'"
                                    class="flex-1 bg-red-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-700 transition-colors duration-200">
                                <i class="fas fa-calculator mr-1"></i> Kalkulasi
                            </button>
                        @else
                            @if($currentUser->role !== 'admin_purchasing')
                                <div class="flex-1 bg-gray-300 text-gray-500 px-3 py-2 rounded-lg text-sm text-center">
                                    <i class="fas fa-info-circle mr-1"></i> Hanya Admin Purchasing
                                </div>
                            @else
                                <div class="flex-1 bg-gray-300 text-gray-500 px-3 py-2 rounded-lg text-sm text-center">
                                    <i class="fas fa-lock mr-1"></i> Tidak Memiliki Akses
                                </div>
                            @endif
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

            <!-- Pagination for Tab Menunggu -->
            @if($proyekMenunggu->hasPages())
            <div class="px-3 sm:px-4 py-3 border-t border-gray-200 bg-gray-50">
                <!-- Mobile Pagination -->
                <div class="flex sm:hidden items-center justify-between">
                    <div class="text-xs text-gray-600">
                        {{ $proyekMenunggu->firstItem() ?? 0 }}-{{ $proyekMenunggu->lastItem() ?? 0 }} dari {{ $proyekMenunggu->total() }}
                    </div>
                    <div class="flex items-center space-x-1">
                        @if ($proyekMenunggu->onFirstPage())
                            <span class="px-2 py-1 text-xs text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $proyekMenunggu->appends(request()->except('menunggu'))->previousPageUrl() }}" 
                               class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        <span class="px-2 py-1 text-xs text-white bg-red-600 rounded font-medium current-page">
                            {{ $proyekMenunggu->currentPage() }}
                        </span>

                        @if ($proyekMenunggu->hasMorePages())
                            <a href="{{ $proyekMenunggu->appends(request()->except('menunggu'))->nextPageUrl() }}" 
                               class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-2 py-1 text-xs text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Desktop Pagination -->
                <div class="hidden sm:flex items-center justify-between">
                    <div class="text-sm text-gray-700 pagination-info">
                        Menampilkan {{ $proyekMenunggu->firstItem() ?? 0 }} - {{ $proyekMenunggu->lastItem() ?? 0 }} dari {{ $proyekMenunggu->total() }} proyek
                    </div>
                    <div class="flex items-center space-x-1 pagination-nav">
                        {{-- Previous Page Link --}}
                        @if ($proyekMenunggu->onFirstPage())
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $proyekMenunggu->appends(request()->except('menunggu'))->previousPageUrl() }}" 
                               class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $start = max(1, $proyekMenunggu->currentPage() - 2);
                            $end = min($proyekMenunggu->lastPage(), $proyekMenunggu->currentPage() + 2);
                        @endphp

                        {{-- First page --}}
                        @if($start > 1)
                            <a href="{{ $proyekMenunggu->appends(request()->except('menunggu'))->url(1) }}" class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">1</a>
                            @if($start > 2)
                                <span class="px-3 py-1 text-sm text-gray-400">...</span>
                            @endif
                        @endif

                        {{-- Page numbers --}}
                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $proyekMenunggu->currentPage())
                                <span class="px-3 py-1 text-sm text-white bg-red-600 rounded font-medium current-page">{{ $page }}</span>
                            @else
                                <a href="{{ $proyekMenunggu->appends(request()->except('menunggu'))->url($page) }}" class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">{{ $page }}</a>
                            @endif
                        @endfor

                        {{-- Last page --}}
                        @if($end < $proyekMenunggu->lastPage())
                            @if($end < $proyekMenunggu->lastPage() - 1)
                                <span class="px-3 py-1 text-sm text-gray-400">...</span>
                            @endif
                            <a href="{{ $proyekMenunggu->appends(request()->except('menunggu'))->url($proyekMenunggu->lastPage()) }}" class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">{{ $proyekMenunggu->lastPage() }}</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($proyekMenunggu->hasMorePages())
                            <a href="{{ $proyekMenunggu->appends(request()->except('menunggu'))->nextPageUrl() }}" 
                               class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Tab Proses Penawaran -->
    <div id="content-proses" class="tab-content hidden">
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800">Proyek dalam Proses Penawaran</h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Proyek yang sedang dalam tahap penawaran dengan status menunggu</p>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Proyek</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Penawaran</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Penawaran</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marketing</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchasing</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penawaran</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $proyekProses = $proyekProses ?? collect();
                        @endphp
                        @forelse($proyekProses as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ $p->proyekBarang->first()->nama_barang }}
                                        @if($p->proyekBarang->count() > 1)
                                            <span class="text-xs text-gray-500">+{{ $p->proyekBarang->count() - 1 }} items</span>
                                        @endif
                                    @else
                                        Project Items
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">PRJ{{ str_pad($p->kode_proyek, 3, '0', STR_PAD_LEFT) }}</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $p->kode_proyek }}</div>
                                <div class="text-sm text-gray-500">{{ $p->instansi }}</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $p->penawaran->no_penawaran ?? 'N/A' }}</div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $p->penawaran->status ?? 'Menunggu' }}
                                </span>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $p->penawaran ? \Carbon\Carbon::parse($p->penawaran->tanggal_penawaran)->format('d M Y') : 'N/A' }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $marketingName = 'N/A';
                                    if ($p->adminMarketing) {
                                        $marketingName = $p->adminMarketing->nama;
                                    } elseif ($p->id_admin_marketing) {
                                        $marketingUser = \App\Models\User::find($p->id_admin_marketing);
                                        $marketingName = $marketingUser ? $marketingUser->nama : 'User ID: ' . $p->id_admin_marketing;
                                    }
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $marketingName }}</div>
                                <div class="text-sm text-gray-500">Marketing</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $purchasingName = 'N/A';
                                    if ($p->adminPurchasing) {
                                        $purchasingName = $p->adminPurchasing->nama;
                                    } elseif ($p->id_admin_purchasing) {
                                        $purchasingUser = \App\Models\User::find($p->id_admin_purchasing);
                                        $purchasingName = $purchasingUser ? $purchasingUser->nama : 'User ID: ' . $p->id_admin_purchasing;
                                    }
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $purchasingName }}</div>
                                <div class="text-sm text-gray-500">Purchasing</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-blue-600">{{ 'Rp ' . number_format($p->harga_total ?? 0, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ number_format($p->proyekBarang->sum('jumlah')) }} {{ $p->proyekBarang->first()->satuan }}
                                    @else
                                        0 Unit
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewPenawaranDetail({{ $p->id_proyek }})"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Lihat Detail Penawaran">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-500">Tidak ada proyek dalam proses penawaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile & Tablet Card View -->
            <div class="lg:hidden">
                @forelse($proyekProses as $p)
                <div class="border-b border-gray-200 p-3 sm:p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="text-sm sm:text-base font-medium text-gray-900 line-clamp-2">
                                @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                    {{ $p->proyekBarang->first()->nama_barang }}
                                    @if($p->proyekBarang->count() > 1)
                                        <span class="text-xs text-gray-500">+{{ $p->proyekBarang->count() - 1 }} items</span>
                                    @endif
                                @else
                                    Project Items
                                @endif
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">PRJ{{ str_pad($p->id_proyek, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-3 shrink-0 bg-yellow-100 text-yellow-800">
                            {{ $p->penawaran->status ?? 'Menunggu' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                        <div>
                            <p class="text-xs text-gray-500">Klien</p>
                            <p class="text-sm font-medium text-gray-900">{{ $p->kode_proyek }}</p>
                            <p class="text-xs text-gray-500">{{ $p->instansi }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">No. Penawaran</p>
                            <p class="text-sm font-medium text-gray-900">{{ $p->penawaran->no_penawaran ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $p->penawaran ? \Carbon\Carbon::parse($p->penawaran->tanggal_penawaran)->format('d M Y') : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                        <div>
                            <p class="text-xs text-gray-500">Marketing</p>
                            @php
                                $marketingName = 'N/A';
                                if ($p->adminMarketing) {
                                    $marketingName = $p->adminMarketing->nama;
                                } elseif ($p->id_admin_marketing) {
                                    $marketingUser = \App\Models\User::find($p->id_admin_marketing);
                                    $marketingName = $marketingUser ? $marketingUser->nama : 'User ID: ' . $p->id_admin_marketing;
                                }
                            @endphp
                            <p class="text-sm font-medium text-gray-900">{{ $marketingName }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Purchasing</p>
                            @php
                                $purchasingName = 'N/A';
                                if ($p->adminPurchasing) {
                                    $purchasingName = $p->adminPurchasing->nama;
                                } elseif ($p->id_admin_purchasing) {
                                    $purchasingUser = \App\Models\User::find($p->id_admin_purchasing);
                                    $purchasingName = $purchasingUser ? $purchasingUser->nama : 'User ID: ' . $p->id_admin_purchasing;
                                }
                            @endphp
                            <p class="text-sm font-medium text-gray-900">{{ $purchasingName }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Total Penawaran</span>
                            <div class="text-right">
                                <p class="text-sm font-bold text-blue-600">{{ 'Rp ' . number_format($p->harga_total ?? 0, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ number_format($p->proyekBarang->sum('jumlah')) }} {{ $p->proyekBarang->first()->satuan }}
                                    @else
                                        0 Unit
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100">
                        <button onclick="viewPenawaranDetail({{ $p->id_proyek }})"
                                class="w-full bg-blue-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-eye mr-1"></i> Lihat Detail
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-file-contract text-4xl mb-3 opacity-50"></i>
                    <p class="text-sm">Tidak ada proyek dalam proses penawaran</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination for Tab Proses -->
            @if($proyekProses->hasPages())
            <div class="px-3 sm:px-4 py-3 border-t border-gray-200 bg-gray-50">
                <!-- Mobile Pagination -->
                <div class="flex sm:hidden items-center justify-between">
                    <div class="text-xs text-gray-600">
                        {{ $proyekProses->firstItem() ?? 0 }}-{{ $proyekProses->lastItem() ?? 0 }} dari {{ $proyekProses->total() }}
                    </div>
                    <div class="flex items-center space-x-1">
                        @if ($proyekProses->onFirstPage())
                            <span class="px-2 py-1 text-xs text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $proyekProses->appends(request()->except('proses'))->previousPageUrl() }}" 
                               class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        <span class="px-2 py-1 text-xs text-white bg-red-600 rounded font-medium current-page">
                            {{ $proyekProses->currentPage() }}
                        </span>

                        @if ($proyekProses->hasMorePages())
                            <a href="{{ $proyekProses->appends(request()->except('proses'))->nextPageUrl() }}" 
                               class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-2 py-1 text-xs text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Desktop Pagination -->
                <div class="hidden sm:flex items-center justify-between">
                    <div class="text-sm text-gray-700 pagination-info">
                        Menampilkan {{ $proyekProses->firstItem() ?? 0 }} - {{ $proyekProses->lastItem() ?? 0 }} dari {{ $proyekProses->total() }} proyek
                    </div>
                    <div class="flex items-center space-x-1 pagination-nav">
                        {{-- Previous Page Link --}}
                        @if ($proyekProses->onFirstPage())
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $proyekProses->appends(request()->except('proses'))->previousPageUrl() }}" 
                               class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $start = max(1, $proyekProses->currentPage() - 2);
                            $end = min($proyekProses->lastPage(), $proyekProses->currentPage() + 2);
                        @endphp

                        {{-- First page --}}
                        @if($start > 1)
                            <a href="{{ $proyekProses->appends(request()->except('proses'))->url(1) }}" class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">1</a>
                            @if($start > 2)
                                <span class="px-3 py-1 text-sm text-gray-400">...</span>
                            @endif
                        @endif

                        {{-- Page numbers --}}
                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $proyekProses->currentPage())
                                <span class="px-3 py-1 text-sm text-white bg-red-600 rounded font-medium current-page">{{ $page }}</span>
                            @else
                                <a href="{{ $proyekProses->appends(request()->except('proses'))->url($page) }}" class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">{{ $page }}</a>
                            @endif
                        @endfor

                        {{-- Last page --}}
                        @if($end < $proyekProses->lastPage())
                            @if($end < $proyekProses->lastPage() - 1)
                                <span class="px-3 py-1 text-sm text-gray-400">...</span>
                            @endif
                            <a href="{{ $proyekProses->appends(request()->except('proses'))->url($proyekProses->lastPage()) }}" class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">{{ $proyekProses->lastPage() }}</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($proyekProses->hasMorePages())
                            <a href="{{ $proyekProses->appends(request()->except('proses'))->nextPageUrl() }}" 
                               class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Tab Penawaran Berhasil -->
    <div id="content-berhasil" class="tab-content hidden">
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-3 sm:p-4 md:p-6 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800">Penawaran Berhasil (ACC)</h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Proyek yang penawaran telah diterima/ACC oleh klien</p>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Proyek</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Penawaran</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal ACC</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marketing</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchasing</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penawaran</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $proyekBerhasil = $proyekBerhasil ?? collect();
                        @endphp
                        @forelse($proyekBerhasil as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ $p->proyekBarang->first()->nama_barang }}
                                        @if($p->proyekBarang->count() > 1)
                                            <span class="text-xs text-gray-500">+{{ $p->proyekBarang->count() - 1 }} items</span>
                                        @endif
                                    @else
                                        Project Items
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">PRJ{{ str_pad($p->id_proyek, 3, '0', STR_PAD_LEFT) }}</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $p->kode_proyek }}</div>
                                <div class="text-sm text-gray-500">{{ $p->instansi }}</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $p->penawaran->no_penawaran ?? 'N/A' }}</div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $p->penawaran->status ?? 'ACC' }}
                                </span>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $p->penawaran ? \Carbon\Carbon::parse($p->penawaran->updated_at)->format('d M Y') : 'N/A' }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $marketingName = 'N/A';
                                    if ($p->adminMarketing) {
                                        $marketingName = $p->adminMarketing->nama;
                                    } elseif ($p->id_admin_marketing) {
                                        $marketingUser = \App\Models\User::find($p->id_admin_marketing);
                                        $marketingName = $marketingUser ? $marketingUser->nama : 'User ID: ' . $p->id_admin_marketing;
                                    }
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $marketingName }}</div>
                                <div class="text-sm text-gray-500">Marketing</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $purchasingName = 'N/A';
                                    if ($p->adminPurchasing) {
                                        $purchasingName = $p->adminPurchasing->nama;
                                    } elseif ($p->id_admin_purchasing) {
                                        $purchasingUser = \App\Models\User::find($p->id_admin_purchasing);
                                        $purchasingName = $purchasingUser ? $purchasingUser->nama : 'User ID: ' . $p->id_admin_purchasing;
                                    }
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $purchasingName }}</div>
                                <div class="text-sm text-gray-500">Purchasing</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">{{ 'Rp ' . number_format($p->harga_total ?? 0, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ number_format($p->proyekBarang->sum('jumlah')) }} {{ $p->proyekBarang->first()->satuan }}
                                    @else
                                        0 Unit
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <button onclick="viewPenawaranDetail({{ $p->id_proyek }})"
                                            class="text-green-600 hover:text-green-900"
                                            title="Lihat Detail Penawaran">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                    @if($p->penawaranAktif && $p->penawaranAktif->status === 'ACC' && $p->status === 'Penawaran')
                                        <a href="{{ route('purchasing.kalkulasi.hps.ajukan', $p->id_proyek) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit Kalkulasi & Ajukan Pembayaran">
                                            <i class="fas fa-calculator"></i> Ajukan Pembayaran
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-500">Belum ada penawaran yang berhasil (ACC)</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile & Tablet Card View -->
            <div class="lg:hidden">
                @forelse($proyekBerhasil as $p)
                <div class="border-b border-gray-200 p-3 sm:p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="text-sm sm:text-base font-medium text-gray-900 line-clamp-2">
                                @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                    {{ $p->proyekBarang->first()->nama_barang }}
                                    @if($p->proyekBarang->count() > 1)
                                        <span class="text-xs text-gray-500">+{{ $p->proyekBarang->count() - 1 }} items</span>
                                    @endif
                                @else
                                    Project Items
                                @endif
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">PRJ{{ str_pad($p->id_proyek, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-3 shrink-0 bg-green-100 text-green-800">
                            {{ $p->penawaran->status ?? 'ACC' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                        <div>
                            <p class="text-xs text-gray-500">Klien</p>
                            <p class="text-sm font-medium text-gray-900">{{ $p->kode_proyek }}</p>
                            <p class="text-xs text-gray-500">{{ $p->instansi }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">No. Penawaran</p>
                            <p class="text-sm font-medium text-gray-900">{{ $p->penawaran->no_penawaran ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">ACC: {{ $p->penawaran ? \Carbon\Carbon::parse($p->penawaran->updated_at)->format('d M Y') : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                        <div>
                            <p class="text-xs text-gray-500">Marketing</p>
                            @php
                                $marketingName = 'N/A';
                                if ($p->adminMarketing) {
                                    $marketingName = $p->adminMarketing->nama;
                                } elseif ($p->id_admin_marketing) {
                                    $marketingUser = \App\Models\User::find($p->id_admin_marketing);
                                    $marketingName = $marketingUser ? $marketingUser->nama : 'User ID: ' . $p->id_admin_marketing;
                                }
                            @endphp
                            <p class="text-sm font-medium text-gray-900">{{ $marketingName }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Purchasing</p>
                            @php
                                $purchasingName = 'N/A';
                                if ($p->adminPurchasing) {
                                    $purchasingName = $p->adminPurchasing->nama;
                                } elseif ($p->id_admin_purchasing) {
                                    $purchasingUser = \App\Models\User::find($p->id_admin_purchasing);
                                    $purchasingName = $purchasingUser ? $purchasingUser->nama : 'User ID: ' . $p->id_admin_purchasing;
                                }
                            @endphp
                            <p class="text-sm font-medium text-gray-900">{{ $purchasingName }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Total Penawaran</span>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">{{ 'Rp ' . number_format($p->harga_total ?? 0, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($p->proyekBarang && $p->proyekBarang->count() > 0)
                                        {{ number_format($p->proyekBarang->sum('jumlah')) }} {{ $p->proyekBarang->first()->satuan }}
                                    @else
                                        0 Unit
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100">
                        @if($p->penawaranAktif && $p->penawaranAktif->status === 'ACC' && $p->status === 'Penawaran')
                            <div class="flex gap-2">
                                <button onclick="viewPenawaranDetail({{ $p->id_proyek }})"
                                        class="flex-1 bg-green-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </button>
                                <a href="{{ route('purchasing.kalkulasi.hps.ajukan', $p->id_proyek) }}"
                                   class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors duration-200 text-center">
                                    <i class="fas fa-calculator mr-1"></i> Ajukan Pembayaran
                                </a>
                            </div>
                        @else
                            <button onclick="viewPenawaranDetail({{ $p->id_proyek }})"
                                    class="w-full bg-green-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                            </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-check-circle text-4xl mb-3 opacity-50"></i>
                    <p class="text-sm">Belum ada penawaran yang berhasil (ACC)</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination for Tab Berhasil -->
            @if($proyekBerhasil->hasPages())
            <div class="px-3 sm:px-4 py-3 border-t border-gray-200 bg-gray-50">
                <!-- Mobile Pagination -->
                <div class="flex sm:hidden items-center justify-between">
                    <div class="text-xs text-gray-600">
                        {{ $proyekBerhasil->firstItem() ?? 0 }}-{{ $proyekBerhasil->lastItem() ?? 0 }} dari {{ $proyekBerhasil->total() }}
                    </div>
                    <div class="flex items-center space-x-1">
                        @if ($proyekBerhasil->onFirstPage())
                            <span class="px-2 py-1 text-xs text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $proyekBerhasil->appends(request()->except('berhasil'))->previousPageUrl() }}" 
                               class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        <span class="px-2 py-1 text-xs text-white bg-red-600 rounded font-medium current-page">
                            {{ $proyekBerhasil->currentPage() }}
                        </span>

                        @if ($proyekBerhasil->hasMorePages())
                            <a href="{{ $proyekBerhasil->appends(request()->except('berhasil'))->nextPageUrl() }}" 
                               class="px-2 py-1 text-xs text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-2 py-1 text-xs text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Desktop Pagination -->
                <div class="hidden sm:flex items-center justify-between">
                    <div class="text-sm text-gray-700 pagination-info">
                        Menampilkan {{ $proyekBerhasil->firstItem() ?? 0 }} - {{ $proyekBerhasil->lastItem() ?? 0 }} dari {{ $proyekBerhasil->total() }} proyek
                    </div>
                    <div class="flex items-center space-x-1 pagination-nav">
                        {{-- Previous Page Link --}}
                        @if ($proyekBerhasil->onFirstPage())
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $proyekBerhasil->appends(request()->except('berhasil'))->previousPageUrl() }}" 
                               class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $start = max(1, $proyekBerhasil->currentPage() - 2);
                            $end = min($proyekBerhasil->lastPage(), $proyekBerhasil->currentPage() + 2);
                        @endphp

                        {{-- First page --}}
                        @if($start > 1)
                            <a href="{{ $proyekBerhasil->appends(request()->except('berhasil'))->url(1) }}" class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">1</a>
                            @if($start > 2)
                                <span class="px-3 py-1 text-sm text-gray-400">...</span>
                            @endif
                        @endif

                        {{-- Page numbers --}}
                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $proyekBerhasil->currentPage())
                                <span class="px-3 py-1 text-sm text-white bg-red-600 rounded font-medium current-page">{{ $page }}</span>
                            @else
                                <a href="{{ $proyekBerhasil->appends(request()->except('berhasil'))->url($page) }}" class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">{{ $page }}</a>
                            @endif
                        @endfor

                        {{-- Last page --}}
                        @if($end < $proyekBerhasil->lastPage())
                            @if($end < $proyekBerhasil->lastPage() - 1)
                                <span class="px-3 py-1 text-sm text-gray-400">...</span>
                            @endif
                            <a href="{{ $proyekBerhasil->appends(request()->except('berhasil'))->url($proyekBerhasil->lastPage()) }}" class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">{{ $proyekBerhasil->lastPage() }}</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($proyekBerhasil->hasMorePages())
                            <a href="{{ $proyekBerhasil->appends(request()->except('berhasil'))->nextPageUrl() }}" 
                               class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded cursor-not-allowed disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.tab-button {
    transition: all 0.2s ease-in-out;
}

.tab-button:hover {
    background-color: #fef2f2;
}

.tab-content {
    display: block;
}

.tab-content.hidden {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Mobile responsive tabs */
@media (max-width: 640px) {
    .tab-button {
        padding: 8px 12px;
        font-size: 12px;
    }

    .tab-button i {
        display: none;
    }
}

/* Pagination Styles */
.pagination-info {
    font-size: 0.875rem;
    color: #374151;
}

.pagination-nav {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.pagination-nav a,
.pagination-nav span {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
}

.pagination-nav a {
    color: #4b5563;
    background-color: white;
    border: 1px solid #d1d5db;
    text-decoration: none;
}

.pagination-nav a:hover {
    background-color: #f3f4f6;
    color: #374151;
}

.pagination-nav .current-page {
    color: white;
    background-color: #dc2626;
    font-weight: 500;
}

.pagination-nav .disabled {
    color: #9ca3af;
    background-color: #e5e7eb;
    cursor: not-allowed;
}

@media (max-width: 640px) {
    .pagination-info {
        font-size: 0.75rem;
    }
    
    .pagination-nav a,
    .pagination-nav span {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Global variables
let currentProyekId = null;

// Tab Management
function showTab(tabName) {
    // Store current tab in URL hash for persistence
    window.history.replaceState(null, null, '#' + tabName);
    
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
        content.classList.remove('active');
    });

    // Remove active state from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-red-600', 'text-red-600', 'bg-red-50');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    const contentElement = document.getElementById(`content-${tabName}`);
    if (contentElement) {
        contentElement.classList.remove('hidden');
        contentElement.classList.add('active');
    }

    // Add active state to selected tab button
    const buttonElement = document.getElementById(`tab-${tabName}`);
    if (buttonElement) {
        buttonElement.classList.add('border-red-600', 'text-red-600', 'bg-red-50');
        buttonElement.classList.remove('border-transparent', 'text-gray-500');
    }

    // Store selected tab in localStorage for session persistence
    localStorage.setItem('selectedKalkulasiTab', tabName);
}

// Open HPS Page (instead of modal) with permission check
function openHpsModal(proyekId) {
    // Note: Backend will handle the permission check
    window.location.href = `/purchasing/kalkulasi/${proyekId}/hps`;
}

// Action functions with permission check
function createPenawaranAction(proyekId) {
    if (confirm('Apakah Anda yakin ingin membuat penawaran untuk proyek ini?')) {
        // Note: Backend will handle the permission check
        window.location.href = `/purchasing/penawaran/create/${proyekId}`;
    }
}

function viewPenawaranDetail(proyekId) {
    window.location.href = `/purchasing/kalkulasi/penawaran/${proyekId}/detail`;
}

// Function to navigate to payment submission page
function ajukanPembayaran(proyekId) {
    // Redirect to the "Ajukan Pembayaran" page
    window.location.href = `/purchasing/kalkulasi/${proyekId}/hps-ajukan`;
}


// Format currency
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount || 0);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check for URL hash first
    let initialTab = 'menunggu'; // default tab
    
    if (window.location.hash) {
        const hashTab = window.location.hash.substring(1);
        if (['menunggu', 'proses', 'berhasil'].includes(hashTab)) {
            initialTab = hashTab;
        }
    } else {
        // Check for stored tab preference if no hash
        const storedTab = localStorage.getItem('selectedKalkulasiTab');
        if (storedTab && ['menunggu', 'proses', 'berhasil'].includes(storedTab)) {
            initialTab = storedTab;
        }
    }
    
    // Show the determined initial tab
    showTab(initialTab);
    
    // Handle pagination link clicks to preserve tab state
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href*="page="], a[href*="menunggu="], a[href*="proses="], a[href*="berhasil="]');
        if (link) {
            // Add current tab to pagination URL
            const currentTab = localStorage.getItem('selectedKalkulasiTab') || 'menunggu';
            if (!link.href.includes('#')) {
                link.href += '#' + currentTab;
            }
        }
    });
});
</script>
@endpush
