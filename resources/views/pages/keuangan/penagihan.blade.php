@extends('layouts.app')

@section('title', 'Penagihan - Cyber KATANA')

@section('content')
<!-- Access Control Info -->
@php
    $user = auth()->user();
    $isAdminKeuangan = $user->role === 'admin_keuangan' || $user->role === 'superadmin';
@endphp


<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Penagihan Dinas</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">
                @if($isAdminKeuangan)
                    Kelola penagihan dan pembayaran dari dinas/instansi
                @else
                    Monitor penagihan dan pembayaran dari dinas/instansi (Mode Hanya Lihat)
                @endif
            </p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-file-invoice-dollar text-3xl sm:text-4xl lg:text-6xl text-red-200"></i>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-2 sm:space-x-8" aria-label="Tabs">
            <button type="button" 
                class="tab-button py-2 sm:py-4 px-2 sm:px-6 text-center border-b-2 font-medium text-xs sm:text-sm md:text-base focus:outline-none transition-colors duration-200"
                data-tab="belum-bayar">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <i class="fas fa-clock text-yellow-500 text-xs sm:text-sm md:text-base"></i>
                    <span>Belum Bayar</span>
                    <span class="bg-yellow-100 text-yellow-800 py-0.5 px-1.5 rounded-full text-xs">{{ $proyekBelumBayar->count() }}</span>
                </div>
            </button>
            <button type="button" 
                class="tab-button py-2 sm:py-4 px-2 sm:px-6 text-center border-b-2 font-medium text-xs sm:text-sm md:text-base focus:outline-none transition-colors duration-200"
                data-tab="dp">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <i class="fas fa-hand-holding-usd text-blue-500 text-xs sm:text-sm md:text-base"></i>
                    <span>DP</span>
                    <span class="bg-blue-100 text-blue-800 py-0.5 px-1.5 rounded-full text-xs">{{ $proyekDp->count() }}</span>
                </div>
            </button>
            <button type="button" 
                class="tab-button py-2 sm:py-4 px-2 sm:px-6 text-center border-b-2 font-medium text-xs sm:text-sm md:text-base focus:outline-none transition-colors duration-200"
                data-tab="lunas">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <i class="fas fa-check-circle text-green-500 text-xs sm:text-sm md:text-base"></i>
                    <span>Lunas</span>
                    <span class="bg-green-100 text-green-800 py-0.5 px-1.5 rounded-full text-xs">{{ $proyekLunas->count() }}</span>
                </div>
            </button>
        </nav>
    </div>
</div>

<!-- Tab Content -->
<div class="tab-content">
    <!-- Belum Bayar Tab -->
    <div id="belum-bayar" class="tab-pane">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-base sm:text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-1 sm:mr-2"></i>
                            Proyek Belum Bayar
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1">Daftar proyek yang sudah di ACC klien namun belum dibuat penagihan</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Search Bar -->
                        <form method="GET" action="{{ route('keuangan.penagihan') }}" class="flex-1 md:flex-none">
                            <input type="hidden" name="tab" value="belum-bayar">
                            <div class="flex items-center space-x-2">
                                <div class="relative">
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Cari proyek, instansi..." 
                                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 w-full md:w-64 text-sm">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('search'))
                                <a href="{{ route('keuangan.penagihan') }}?tab=belum-bayar" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div>
                        </form>
                        @if(!$isAdminKeuangan)
                        <div class="flex items-center px-2 sm:px-3 py-1 sm:py-2 bg-blue-50 border border-blue-200 rounded-lg">
                            <i class="fas fa-eye text-blue-600 mr-1 sm:mr-2"></i>
                            <span class="text-xs sm:text-sm font-medium text-blue-700">Mode Lihat Saja</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi/Klien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($proyekBelumBayar as $proyek)
                        @php
                            $penawaran = $proyek->penawaranAktif;
                            $totalHarga = $proyek->harga_total ?? 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $proyek->kode_proyek ?? 'PRJ-' . str_pad($proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="text-sm text-gray-600">{{ $proyek->instansi }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($proyek->tanggal)->format('d M Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">{{ $proyek->kode_proyek }}</div>
                                    @if($proyek->kontak_klien)
                                    <div class="text-sm text-gray-500">{{ $proyek->kontak_klien }}</div>
                                    @endif
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $proyek->kab_kota }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">{{ $proyek->nama_barang }}</div>
                                    <div class="text-sm text-gray-600">{{ $proyek->jumlah }} {{ $proyek->satuan }}</div>
                                    <div class="text-xs text-gray-500">{{ $proyek->jenis_pengadaan }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-bold text-green-600">
                                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ ($penawaran && $penawaran->penawaranDetail) ? $penawaran->penawaranDetail->count() : 0 }} item
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        {{ $penawaran->status }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $penawaran->updated_at->format('d M Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                @if($isAdminKeuangan)
                                    <a href="{{ route('penagihan-dinas.create', $proyek->id_proyek) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                        <i class="fas fa-file-invoice mr-2"></i>
                                        Buat Penagihan
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 bg-gray-100 border border-gray-200"
                                          title="Hanya Admin Keuangan/Superadmin yang dapat membuat penagihan">
                                        <i class="fas fa-lock mr-2 text-gray-400"></i>
                                        Akses Terbatas
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                    <p class="text-lg">Tidak ada proyek yang belum dibuat penagihan</p>
                                    <p class="text-sm">Semua proyek dengan penawaran ACC sudah dibuatkan penagihan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination untuk Belum Bayar -->
            @if($proyekBelumBayar->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Menampilkan {{ $proyekBelumBayar->firstItem() ?? 0 }} - {{ $proyekBelumBayar->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-800">{{ $proyekBelumBayar->total() }}</span> proyek
                    </div>
                    <div class="flex justify-center">
                        {{ $proyekBelumBayar->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- DP Tab -->
    <div id="dp" class="tab-pane hidden">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-hand-holding-usd text-blue-500 mr-2"></i>
                            Pembayaran DP
                        </h2>
                        <p class="text-gray-600 mt-1">Daftar pembayaran yang masih dalam status DP (menunggu pelunasan)</p>
                    </div>
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('keuangan.penagihan') }}">
                        <input type="hidden" name="tab" value="dp">
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Cari invoice, proyek..." 
                                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64 text-sm">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request('search'))
                            <a href="{{ route('keuangan.penagihan') }}?tab=dp" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DP Dibayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($proyekDp as $penagihan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-semibold text-gray-900">{{ $penagihan->nomor_invoice }}</div>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($penagihan->tanggal_jatuh_tempo)->format('d M Y') }}
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1 w-fit">
                                        <i class="fas fa-hand-holding-usd mr-1"></i>
                                        DP
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">{{ $penagihan->proyek->kode_proyek ?? 'PRJ-' . str_pad($penagihan->proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-sm text-gray-600">{{ $penagihan->proyek->nama_barang }}</div>
                                    <div class="text-xs text-gray-500">{{ $penagihan->proyek->instansi }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">{{ $penagihan->proyek->kode_proyek }}</div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $penagihan->proyek->kab_kota }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">
                                    Rp {{ number_format((float)$penagihan->total_harga, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-bold text-green-600">
                                        Rp {{ number_format((float)$penagihan->jumlah_dp, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $penagihan->persentase_dp }}% dari total</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $totalBayar = $penagihan->buktiPembayaran->sum('jumlah_bayar');
                                    $sisaPembayaran = $penagihan->total_harga - $totalBayar;
                                @endphp
                                <div class="text-sm font-bold text-red-600">
                                    Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex flex-col space-y-1">
                                    <a href="{{ route('penagihan-dinas.show', $penagihan->id) }}" 
                                       class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-blue-600 bg-blue-100 hover:bg-blue-200 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </a>
                                    <a href="{{ route('penagihan-dinas.history', $penagihan->id) }}" 
                                       class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-purple-600 bg-purple-100 hover:bg-purple-200 transition-colors duration-200">
                                        <i class="fas fa-history mr-1"></i>
                                        History
                                    </a>
                                    @if($isAdminKeuangan)
                                        <a href="{{ route('penagihan-dinas.show-pelunasan', $penagihan->id) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-green-600 bg-green-100 hover:bg-green-200 transition-colors duration-200">
                                            <i class="fas fa-money-check-alt mr-1"></i>
                                            Lunasi
                                        </a>
                                        <a href="{{ route('penagihan-dinas.edit', $penagihan->id) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-yellow-600 bg-yellow-100 hover:bg-yellow-200 transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded text-gray-600 bg-gray-100 border border-gray-200"
                                              title="Hanya Admin Keuangan/Superadmin yang dapat melakukan pelunasan dan edit">
                                            <i class="fas fa-lock mr-1 text-gray-400"></i>
                                            Akses Terbatas
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                    <p class="text-lg">Tidak ada pembayaran DP</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination untuk DP -->
            @if($proyekDp->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Menampilkan {{ $proyekDp->firstItem() ?? 0 }} - {{ $proyekDp->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-800">{{ $proyekDp->total() }}</span> pembayaran DP
                    </div>
                    <div class="flex justify-center">
                        {{ $proyekDp->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Lunas Tab -->
    <div id="lunas" class="tab-pane hidden">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Pembayaran Lunas
                        </h2>
                        <p class="text-gray-600 mt-1">Daftar pembayaran yang sudah lunas</p>
                    </div>
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('keuangan.penagihan') }}">
                        <input type="hidden" name="tab" value="lunas">
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Cari invoice, proyek..." 
                                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 w-64 text-sm">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request('search'))
                            <a href="{{ route('keuangan.penagihan') }}?tab=lunas" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($proyekLunas as $penagihan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-semibold text-gray-900">{{ $penagihan->nomor_invoice }}</div>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($penagihan->tanggal_jatuh_tempo)->format('d M Y') }}
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1 w-fit">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Lunas
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">{{ $penagihan->proyek->kode_proyek ?? 'PRJ-' . str_pad($penagihan->proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-sm text-gray-600">{{ $penagihan->proyek->nama_barang }}</div>
                                    <div class="text-xs text-gray-500">{{ $penagihan->proyek->instansi }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">{{ $penagihan->proyek->kode_proyek }}</div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $penagihan->proyek->kab_kota }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-bold text-green-600">
                                        Rp {{ number_format((float)$penagihan->total_harga, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @php
                                            $totalPembayaran = $penagihan->buktiPembayaran->count();
                                        @endphp
                                        {{ $totalPembayaran }} pembayaran
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Lunas
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex flex-col space-y-1">
                                    <a href="{{ route('penagihan-dinas.show', $penagihan->id) }}" 
                                       class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-blue-600 bg-blue-100 hover:bg-blue-200 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </a>
                                    <a href="{{ route('penagihan-dinas.history', $penagihan->id) }}" 
                                       class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-purple-600 bg-purple-100 hover:bg-purple-200 transition-colors duration-200">
                                        <i class="fas fa-history mr-1"></i>
                                        History
                                    </a>
                                    @if($isAdminKeuangan)
                                        <a href="{{ route('penagihan-dinas.edit', $penagihan->id) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-yellow-600 bg-yellow-100 hover:bg-yellow-200 transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded text-gray-600 bg-gray-100 border border-gray-200"
                                              title="Hanya Admin Keuangan yang dapat melakukan edit">
                                            <i class="fas fa-lock mr-1 text-gray-400"></i>
                                            Akses Terbatas
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                    <p class="text-lg">Tidak ada pembayaran lunas</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination untuk Lunas -->
            @if($proyekLunas->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Menampilkan {{ $proyekLunas->firstItem() ?? 0 }} - {{ $proyekLunas->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-800">{{ $proyekLunas->total() }}</span> pembayaran lunas
                    </div>
                    <div class="flex justify-center">
                        {{ $proyekLunas->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    // Get active tab from URL parameter or default to 'belum-bayar'
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'belum-bayar';
    
    // Set initial active tab
    setActiveTab(activeTab);
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            setActiveTab(tabId);
            
            // Update URL with tab parameter
            const newUrl = new URL(window.location);
            newUrl.searchParams.set('tab', tabId);
            // Remove pagination parameters when switching tabs
            newUrl.searchParams.delete('belum_bayar_page');
            newUrl.searchParams.delete('dp_page');
            newUrl.searchParams.delete('lunas_page');
            window.history.pushState({}, '', newUrl);
        });
    });
    
    function setActiveTab(tabId) {
        // Remove active classes from all tabs
        tabButtons.forEach(btn => {
            btn.classList.remove('border-red-500', 'text-red-600');
            btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        });
        
        // Hide all tab panes
        tabPanes.forEach(pane => {
            pane.classList.add('hidden');
        });
        
        // Activate selected tab
        const activeButton = document.querySelector(`[data-tab="${tabId}"]`);
        const activePane = document.getElementById(tabId);
        
        if (activeButton && activePane) {
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            activeButton.classList.add('border-red-500', 'text-red-600');
            activePane.classList.remove('hidden');
        }
    }
});
</script>
@endpush

@endsection
