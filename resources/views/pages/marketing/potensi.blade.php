@extends('layouts.app')

@section('title', 'Potensi - Cyber KATANA')

@section('content')

@php
$totalPotensi = count($proyekData);
$totalNilaiPotensi = array_reduce($proyekData, fn($carry, $item) => $carry + ($item['total_nilai'] ?? 0), 0);
$hasEditAccess = auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing';
@endphp

<!-- Header Section -->
<div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Manajemen Potensi</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan pantau semua potensi proyek Anda</p>
            @if(!$hasEditAccess)
            <div class="mt-2 bg-red-700 bg-opacity-50 rounded-lg px-3 py-2">
                <p class="text-red-100 text-xs sm:text-sm">
                    <i class="fas fa-info-circle mr-1"></i>
                    Mode View Only - Anda hanya dapat melihat data potensi
                </p>
            </div>
            @endif
        </div>
        <div class="hidden sm:block">
            <i class="fas fa-lightbulb text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 sm:p-4 rounded-xl bg-red-100 mr-4">
                <i class="fas fa-lightbulb text-red-600 text-2xl sm:text-3xl lg:text-4xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-600 mb-1">Total Potensi</h3>
                <p id="totalPotensiCount" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-red-600">{{ $totalPotensi }}</p>
                <p id="totalPotensiLabel" class="text-xs sm:text-sm text-gray-500 mt-1">Menunggu &amp; Penawaran (belum ACC)</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 sm:p-4 rounded-xl bg-green-100 mr-4">
                <i class="fas fa-money-bill-wave text-green-600 text-2xl sm:text-3xl lg:text-4xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-600 mb-1">Total Nilai Potensi</h3>
                <p id="totalNilaiPotensi" class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-600">Rp {{ number_format($totalNilaiPotensi, 2, ',', '.') }}</p>
                <p id="totalNilaiLabel" class="text-xs sm:text-sm text-gray-500 mt-1">Estimasi nilai keseluruhan</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-20">
    <!-- Header -->
    <div class="p-4 sm:p-6 lg:p-8 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Daftar Potensi</h2>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Kelola semua potensi proyek dan prospek bisnis</p>
            </div>
            <div class="flex gap-2">
                <button onclick="exportToExcel()" class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl transition-colors duration-200 flex items-center text-sm sm:text-base">
                    <i class="fas fa-file-excel mr-2"></i>
                    <span class="hidden sm:inline">Export Excel</span>
                    <span class="sm:hidden">Export</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-3 sm:p-4 lg:p-6 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row gap-3 sm:gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama instansi atau kabupaten/kota..."
                           class="w-full pl-9 sm:pl-10 pr-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 flex-wrap">
                <select id="tahunFilter" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Tahun</option>
                    @php
                        $currentYear = date('Y');
                        $availableYears = collect($proyekData)
                            ->pluck('tahun_potensi')
                            ->filter()
                            ->unique()
                            ->sort()
                            ->values()
                            ->filter(fn($y) => $y >= $currentYear && $y <= ($currentYear + 5))
                            ->sortDesc();
                    @endphp
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <select id="picMarketingFilter" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua PIC Marketing</option>
                    @foreach(collect($proyekData)->pluck('admin_marketing')->unique()->sort()->values() as $marketing)
                        <option value="{{ $marketing }}">{{ $marketing }}</option>
                    @endforeach
                </select>
                <select id="triwulanFilter" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Triwulan</option>
                    <option value="1">Triwulan 1</option>
                    <option value="2">Triwulan 2</option>
                    <option value="3">Triwulan 3</option>
                    <option value="4">Triwulan 4</option>
                </select>
                <select id="prioritasFilter" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Prioritas</option>
                    <option value="tinggi">Prioritas Tinggi (&lt;7 hari)</option>
                    <option value="sedang">Prioritas Sedang (7–14 hari)</option>
                    <option value="rendah">Prioritas Rendah (&gt;14 hari)</option>
                    <option value="expired">Expired</option>
                    <option value="none">Tanpa Deadline</option>
                </select>
                <select id="sortByFilter" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Urutkan</option>
                    <option value="terbaru">Terbaru</option>
                    <option value="terlama">Terlama</option>
                    <option value="deadline_asc">Deadline Terdekat</option>
                    <option value="deadline_desc">Deadline Terjauh</option>
                    <option value="prioritas">Prioritas Tertinggi</option>
                </select>
                <button onclick="resetFilters()" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-lg sm:rounded-xl transition-colors duration-200">
                    <i class="fas fa-redo text-gray-600"></i>
                    <span class="hidden sm:inline ml-1">Reset</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Cards Layout -->
    <div class="p-3 sm:p-4 lg:p-6">
        <div id="proyekContainer" class="flex flex-col gap-4 sm:gap-6">
            @foreach($proyekData as $index => $potensi)
            @php
                $deadlineVal = $potensi['deadline'] ?? null;
                $prioritasInfo = null;
                $borderClass = 'border-gray-200';
                if ($deadlineVal) {
                    $today = \Carbon\Carbon::today();
                    $deadlineCarbon = \Carbon\Carbon::parse($deadlineVal)->startOfDay();
                    $hari = $today->diffInDays($deadlineCarbon, false);
                    if ($hari < 0) {
                        $prioritasInfo = ['label' => 'Expired', 'badge' => 'bg-black text-white'];
                        $borderClass = 'border-black';
                    } elseif ($hari < 7) {
                        $prioritasInfo = ['label' => 'Prioritas Tinggi (' . $hari . ' hari)', 'badge' => 'bg-red-100 text-red-800'];
                        $borderClass = 'border-red-500';
                    } elseif ($hari <= 14) {
                        $prioritasInfo = ['label' => 'Prioritas Sedang (' . $hari . ' hari)', 'badge' => 'bg-yellow-100 text-yellow-800'];
                        $borderClass = 'border-yellow-400';
                    } else {
                        $prioritasInfo = ['label' => 'Prioritas Rendah (' . $hari . ' hari)', 'badge' => 'bg-green-100 text-green-800'];
                        $borderClass = 'border-green-400';
                    }
                }
            @endphp

            {{-- =====================================================================
                 SPLIT CARD: kiri = info potensi | kanan = daftar barang (scrollable)
                 Desktop: 2 kolom (grid-cols-[1fr_320px])
                 Mobile: stacked (flex-col)
                 ===================================================================== --}}
            <div class="proyek-card bg-white border-2 {{ $borderClass }} rounded-xl sm:rounded-2xl hover:shadow-lg transition-all duration-300 hover:border-red-200 relative overflow-hidden"
                 data-status="{{ $potensi['status'] }}"
                 data-triwulan="{{ $potensi['triwulan'] ?? '' }}"
                 data-kabupaten="{{ strtolower($potensi['kabupaten']) }}"
                 data-instansi="{{ strtolower($potensi['instansi']) }}"
                 data-tanggal="{{ $potensi['tanggal'] }}">

                <div class="flex flex-col lg:flex-row">

                    {{-- ---- PANEL KIRI: Info Potensi ---- --}}
                    <div class="flex-1 p-4 sm:p-6 cursor-pointer"
                         onclick="window.location.href='{{ route('chat.proyek', $potensi['id']) }}'"
                         title="Klik untuk membuka chat proyek">

                        {{-- Header card: nomor + kode + status + actions --}}
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4 gap-3">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-red-600 font-bold text-sm sm:text-lg">{{ $index + 1 }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-2">{{ $potensi['kode'] }}</h3>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex px-2 sm:px-3 py-1 text-xs font-medium rounded-full
                                            @if($potensi['status'] === 'selesai') bg-green-100 text-green-800
                                            @elseif($potensi['status'] === 'pengiriman') bg-orange-100 text-orange-800
                                            @elseif($potensi['status'] === 'pembayaran') bg-purple-100 text-purple-800
                                            @elseif($potensi['status'] === 'penawaran') bg-blue-100 text-blue-800
                                            @elseif($potensi['status'] === 'menunggu') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($potensi['status']) }}
                                        </span>
                                        @if($prioritasInfo)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $prioritasInfo['badge'] }}">
                                            {{ $prioritasInfo['label'] }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Action buttons — stopPropagation agar tidak trigger onclick card --}}
                            <div class="flex items-center space-x-1 sm:space-x-2 self-start flex-shrink-0" onclick="event.stopPropagation()">
                                @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing')
                                <button onclick="buatPenawaran({{ $potensi['id'] }})" class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors duration-200" title="Buat Penawaran">
                                    <i class="fas fa-file-invoice text-sm"></i>
                                </button>
                                @endif
                                <button onclick="viewDetail({{ $potensi['id'] }})" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                                @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing')
                                <button onclick="editProyek({{ $potensi['id'] }})" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button onclick="ubahStatusGagal({{ $potensi['id'] }})" class="p-2 text-orange-600 hover:bg-orange-100 rounded-lg transition-colors duration-200" title="Ubah ke Gagal">
                                    <i class="fas fa-times-circle text-sm"></i>
                                </button>
                                <button onclick="deleteProyek({{ $potensi['id'] }})" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                @endif
                            </div>
                        </div>

                        {{-- Info grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-3">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Tanggal</p>
                                <p class="font-medium text-gray-800 text-sm sm:text-base">{{ \Carbon\Carbon::parse($potensi['tanggal'])->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Tahun Potensi</p>
                                <p class="font-medium text-gray-800 text-sm sm:text-base">
                                    @if(isset($potensi['tahun_potensi']) && $potensi['tahun_potensi'])
                                        {{ $potensi['tahun_potensi'] }}
                                    @else
                                        <span class="text-gray-400 italic text-xs">Belum diisi</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Triwulan</p>
                                <p class="font-medium text-gray-800 text-sm sm:text-base">
                                    @if(isset($potensi['triwulan']) && $potensi['triwulan'])
                                        Triwulan {{ $potensi['triwulan'] }}
                                    @else
                                        <span class="text-gray-400 italic text-xs">Belum diisi</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Provinsi / Kab/Kota</p>
                                <p class="font-medium text-gray-800 text-sm sm:text-base">{{ $potensi['kabupaten'] }}</p>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Nama Instansi</p>
                                <p class="font-medium text-gray-800 text-sm sm:text-base">{{ $potensi['instansi'] }}</p>
                            </div>
                        </div>

                        {{-- PIC & Total Nilai --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">PIC Marketing</p>
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user text-red-600 text-xs"></i>
                                    </div>
                                    <p class="font-medium text-gray-800 text-sm truncate">{{ $potensi['admin_marketing'] }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">PIC Purchasing</p>
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <p class="font-medium text-gray-800 text-sm truncate">{{ $potensi['admin_purchasing'] }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Deadline & Total Nilai --}}
                        <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2">
                            <div>
                                @if($deadlineVal)
                                <p class="text-xs text-gray-500"><i class="fas fa-calendar-alt mr-1"></i>Deadline:
                                    <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($deadlineVal)->format('d M Y') }}</span>
                                </p>
                                @else
                                <p class="text-xs text-gray-400 italic">Tanpa deadline</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 mb-0.5">Total Nilai</p>
                                <span class="text-base sm:text-lg font-bold text-red-600">Rp {{ number_format($potensi['total_nilai'], 2, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Penawaran Info (jika ada) --}}
                        @if(isset($potensi['penawaran']) && $potensi['penawaran'])
                        <div class="mt-2 pt-2 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-500">
                            <span>No. Penawaran: <span class="font-medium text-blue-600">{{ $potensi['penawaran']['no_penawaran'] }}</span></span>
                            @if($potensi['penawaran']['tanggal_penawaran'])
                            <span>Tgl Penawaran: <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($potensi['penawaran']['tanggal_penawaran'])->format('d M Y') }}</span></span>
                            @endif
                        </div>
                        @endif
                    </div>{{-- end panel kiri --}}

                    {{-- ---- DIVIDER: vertical di desktop, horizontal di mobile ---- --}}
                    <div class="hidden lg:block w-px bg-gray-200 my-4"></div>
                    <div class="lg:hidden h-px bg-gray-200 mx-4"></div>

                    {{-- ---- PANEL KANAN: Daftar Barang (scrollable) ---- --}}
                    <div class="w-full lg:w-72 xl:w-80 flex-shrink-0 p-4 sm:p-5 bg-gray-50 lg:rounded-r-2xl"
                         onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-1.5">
                                <i class="fas fa-boxes text-red-500 text-xs"></i>
                                Daftar Barang
                            </h4>
                            @php $jumlahBarang = count($potensi['daftar_barang'] ?? []); @endphp
                            @if($jumlahBarang > 0)
                            <span class="text-xs bg-red-100 text-red-700 font-semibold px-2 py-0.5 rounded-full">{{ $jumlahBarang }} item</span>
                            @endif
                        </div>

                        @if(!empty($potensi['daftar_barang']))
                        {{-- Scrollable list: max-h + overflow-y auto --}}
                        <div class="space-y-2 max-h-80 overflow-y-auto pr-1 potensi-barang-scroll">
                            @foreach($potensi['daftar_barang'] as $barang)
                            <div class="bg-white border border-gray-200 rounded-lg p-2.5 text-xs">
                                <p class="font-semibold text-gray-800 truncate mb-1.5" title="{{ $barang['nama_barang'] ?? '-' }}">
                                    {{ $barang['nama_barang'] ?? '-' }}
                                </p>
                                <div class="flex flex-wrap gap-x-3 gap-y-1 text-gray-500">
                                    <span><span class="font-medium text-gray-600">Qty:</span> {{ $barang['jumlah'] ?? 0 }} {{ $barang['satuan'] ?? '' }}</span>
                                    @if(!empty($barang['harga_satuan']))
                                    <span><span class="font-medium text-gray-600"></span> Rp {{ number_format($barang['harga_satuan'], 0, ',', '.') }}</span>
                                    @endif
                                    @if(!empty($barang['harga_total']))
                                    <span class="w-full font-semibold text-red-600">= Rp {{ number_format($barang['harga_total'], 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <i class="fas fa-box-open text-gray-300 text-2xl mb-2"></i>
                            <p class="text-xs text-gray-400">Tidak ada data barang</p>
                        </div>
                        @endif

                      
                    </div>{{-- end panel kanan --}}

                </div>{{-- end flex kiri-kanan --}}
            </div>{{-- end proyek-card --}}
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="hidden text-center py-12">
            <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada potensi ditemukan</h3>
            <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian Anda</p>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-xs sm:text-sm text-gray-700 text-center sm:text-left">
                <span id="paginationInfo">Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">10</span> dari <span class="font-medium">{{ $totalPotensi }}</span> potensi</span>
            </div>
            <div class="flex items-center justify-center sm:justify-end">
                <!-- Mobile Pagination -->
                <div class="flex items-center space-x-1 sm:hidden">
                    <button onclick="goToPage(currentPage - 1)" id="prevPageMobile" class="px-2 py-2 text-xs border border-gray-300 rounded-md bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span id="currentPageMobile" class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md">
                        1 / 1
                    </span>
                    <button onclick="goToPage(currentPage + 1)" id="nextPageMobile" class="px-2 py-2 text-xs border border-gray-300 rounded-md bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <!-- Desktop Pagination -->
                <div id="paginationDesktop" class="hidden sm:flex items-center space-x-1 md:space-x-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
@if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing')
<button onclick="openModal('modalTambahProyek')" class="fixed bottom-4 right-4 sm:bottom-16 sm:right-16 bg-red-600 text-white w-12 h-12 sm:w-16 sm:h-16 rounded-full shadow-2xl hover:bg-red-700 hover:scale-110 transform transition-all duration-200 flex items-center justify-center group z-50">
    <i class="fas fa-plus text-lg sm:text-xl group-hover:rotate-180 transition-transform duration-300"></i>
    <span class="absolute right-full mr-2 sm:mr-3 bg-gray-800 text-white text-xs sm:text-sm px-2 sm:px-3 py-1 sm:py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap hidden sm:block">
        Tambah Potensi
    </span>
</button>
@endif

<!-- Include Modal Components -->
@include('pages.marketing.potensi-components.tambah')
@include('pages.marketing.potensi-components.edit')
@include('pages.marketing.potensi-components.detail')
@include('pages.marketing.potensi-components.hapus')
@include('pages.marketing.potensi-components.ubah-status')
@include('pages.marketing.potensi-components.ubah-status-gagal')
@include('components.success-modal')

<!-- Include Modal Functions -->
<script src="{{ asset('js/modal-functions.js') }}"></script>

<style>
/* ============================================================
   Modal Styling
   ============================================================ */
.modal-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 0.5rem;
}
@media (min-width: 640px) { .modal-container { padding: 1rem; } }
.modal-content {
    max-height: calc(100vh - 1rem);
    overflow-y: auto;
    width: 100%;
    max-width: 100%;
}
@media (min-width: 640px)  { .modal-content { max-height: calc(100vh - 2rem); max-width: 32rem; } }
@media (min-width: 768px)  { .modal-content { max-width: 42rem; } }
@media (min-width: 1024px) { .modal-content { max-width: 48rem; } }
.modal-content::-webkit-scrollbar       { width: 4px; }
.modal-content::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 3px; }
.modal-content::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
.modal-content::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
.modal-open { overflow: hidden; }

@media (max-width: 639px) {
    .modal-container { padding: 0; align-items: flex-start; }
    .modal-content   { max-height: 100vh; border-radius: 0; margin: 0; min-height: 100vh; }
    .modal-header    { position: sticky; top: 0; z-index: 10; background: white; border-bottom: 1px solid #e5e7eb; }
    .modal-form input, .modal-form select, .modal-form textarea { min-height: 44px; font-size: 16px; }
    .modal-form button { min-height: 44px; padding: 0.75rem 1rem; }
}

/* ============================================================
   Barang scroll panel custom scrollbar
   ============================================================ */
.potensi-barang-scroll::-webkit-scrollbar       { width: 4px; }
.potensi-barang-scroll::-webkit-scrollbar-track { background: #f3f4f6; border-radius: 4px; }
.potensi-barang-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
.potensi-barang-scroll::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

/* ============================================================
   Modal animations
   ============================================================ */
@keyframes modalFadeIn  { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
@keyframes modalFadeOut { from { opacity: 1; transform: scale(1); } to { opacity: 0; transform: scale(0.95); } }
.modal-enter { animation: modalFadeIn  0.3s ease-out; }
.modal-exit  { animation: modalFadeOut 0.3s ease-in; }
.modal-backdrop { background-color: rgba(0, 0, 0, 0.5); }
@media (max-width: 639px) { .modal-backdrop { background-color: rgba(0, 0, 0, 0.75); } }

/* ============================================================
   Notification animations
   ============================================================ */
@keyframes slideInRight  { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
@keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
.notification-enter { animation: slideInRight  0.3s ease-out; }
.notification-exit  { animation: slideOutRight 0.3s ease-in; }
</style>

<script>
/* ============================================================
   DATA
   ============================================================ */
const potensiData = @json($proyekData);

/* ============================================================
   PAGINATION STATE
   ============================================================ */
let currentData  = [...potensiData];
let currentPage  = 1;
const itemsPerPage = 10;
let totalPages   = 1;

/* ============================================================
   DOM REFS
   ============================================================ */
const searchInput       = document.getElementById('searchInput');
const tahunFilter       = document.getElementById('tahunFilter');
const picMarketingFilter= document.getElementById('picMarketingFilter');
const prioritasFilter   = document.getElementById('prioritasFilter');
const sortByFilter      = document.getElementById('sortByFilter');
const triwulanFilter    = document.getElementById('triwulanFilter');
const paginationInfo    = document.getElementById('paginationInfo');

/* ============================================================
   UTILITY FUNCTIONS
   ============================================================ */

/** Rupiah formatter */
function formatRupiah(angka) {
    const number = parseFloat(angka);
    if (isNaN(number)) return 'Rp 0,00';
    return 'Rp ' + number.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

/** Date formatter */
function formatTanggal(tanggal) {
    if (!tanggal || tanggal === '-') return '-';
    try {
        const date = new Date(tanggal);
        if (isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    } catch (e) { return '-'; }
}

/** Capitalize first letter */
function ucfirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

/** Status badge color class */
function getStatusColor(status) {
    switch ((status || '').toLowerCase()) {
        case 'selesai':    return 'bg-green-100 text-green-800';
        case 'pengiriman': return 'bg-orange-100 text-orange-800';
        case 'pembayaran': return 'bg-purple-100 text-purple-800';
        case 'penawaran':  return 'bg-blue-100 text-blue-800';
        case 'menunggu':   return 'bg-gray-100 text-gray-800';
        case 'gagal':      return 'bg-red-100 text-red-800';
        default:           return 'bg-gray-100 text-gray-800';
    }
}

/** Status text color class */
function getStatusClass(status) {
    switch ((status || '').toLowerCase()) {
        case 'menunggu':   return 'text-yellow-600';
        case 'penawaran':  return 'text-blue-600';
        case 'pembayaran': return 'text-purple-600';
        case 'pengiriman': return 'text-indigo-600';
        case 'selesai':    return 'text-green-600';
        case 'gagal':      return 'text-red-600';
        default:           return 'text-gray-600';
    }
}

/**
 * Kembalikan level prioritas deadline sebagai string.
 * @returns {'expired'|'tinggi'|'sedang'|'rendah'|null}
 */
function getPrioritasLevelPotensi(deadline) {
    if (!deadline) return null;
    const today = new Date(); today.setHours(0, 0, 0, 0);
    const dl    = new Date(deadline); dl.setHours(0, 0, 0, 0);
    const hari  = Math.round((dl - today) / 86400000);
    if (hari < 0)   return 'expired';
    if (hari < 7)   return 'tinggi';
    if (hari <= 14) return 'sedang';
    return 'rendah';
}

/**
 * Kembalikan objek prioritas dengan label & badge class untuk UI.
 */
function hitungPrioritasDeadline(deadline) {
    if (!deadline) return null;
    const today = new Date(); today.setHours(0, 0, 0, 0);
    const dl    = new Date(deadline); dl.setHours(0, 0, 0, 0);
    const hari  = Math.round((dl - today) / 86400000);
    if (hari < 0)   return { level: 'expired', label: 'Expired',                          badgeClass: 'bg-black text-white',           borderClass: 'border-black',   hari };
    if (hari < 7)   return { level: 'tinggi',  label: 'Prioritas Tinggi (' + hari + ' hari)', badgeClass: 'bg-red-100 text-red-800',   borderClass: 'border-red-500', hari };
    if (hari <= 14) return { level: 'sedang',  label: 'Prioritas Sedang (' + hari + ' hari)', badgeClass: 'bg-yellow-100 text-yellow-800', borderClass: 'border-yellow-500', hari };
    return              { level: 'rendah',  label: 'Prioritas Rendah (' + hari + ' hari)',  badgeClass: 'bg-green-100 text-green-800',  borderClass: 'border-green-500',  hari };
}

/** Format file size */
function formatFileSize(bytes) {
    if (!bytes) return '0 Bytes';
    const k = 1024, sizes = ['Bytes','KB','MB','GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/** Debounce */
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

/* ============================================================
   NOTIFICATION HELPERS
   ============================================================ */

function showSuccessMessage(message) {
    _showNotification(message, 'bg-green-500', 'fa-check-circle', 3000);
}

function showErrorMessage(message) {
    _showNotification(message, 'bg-red-500', 'fa-exclamation-circle', 4000);
}

function _showNotification(message, bgClass, iconClass, duration) {
    const el = document.createElement('div');
    el.className = 'fixed top-4 right-4 ' + bgClass + ' text-white px-4 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    el.innerHTML = '<div class="flex items-center"><i class="fas ' + iconClass + ' mr-2"></i><span>' + message + '</span></div>';
    document.body.appendChild(el);
    setTimeout(() => el.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        el.classList.add('translate-x-full');
        setTimeout(() => el.parentNode && el.parentNode.removeChild(el), 300);
    }, duration);
}

/* ============================================================
   STATISTICS UPDATE
   ============================================================ */

function updateStatistics() {
    const totalCount = currentData.length;
    const totalNilai = currentData.reduce((s, i) => s + (i.total_nilai || 0), 0);

    const countEl = document.getElementById('totalPotensiCount');
    const nilaiEl = document.getElementById('totalNilaiPotensi');
    const labelEl = document.getElementById('totalPotensiLabel');
    const nilaiLabelEl = document.getElementById('totalNilaiLabel');

    const selectedTahun = tahunFilter ? tahunFilter.value : '';

    if (countEl) {
        countEl.textContent = totalCount;
        countEl.classList.add('animate-pulse');
        setTimeout(() => countEl.classList.remove('animate-pulse'), 500);
    }
    if (nilaiEl) {
        nilaiEl.textContent = formatRupiah(totalNilai);
        nilaiEl.classList.add('animate-pulse');
        setTimeout(() => nilaiEl.classList.remove('animate-pulse'), 500);
    }
    if (labelEl)      labelEl.textContent      = selectedTahun ? 'Potensi tahun ' + selectedTahun : 'Menunggu & Penawaran (belum ACC)';
    if (nilaiLabelEl) nilaiLabelEl.textContent = selectedTahun ? 'Estimasi nilai tahun ' + selectedTahun : 'Estimasi nilai keseluruhan';
}

/* ============================================================
   FILTER & SORT
   ============================================================ */

function filterAndSort() {
    let filtered = [...potensiData];

    // Search
    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    if (searchTerm) {
        filtered = filtered.filter(p => {
            const instansi    = (p.instansi    || '').toLowerCase();
            const kabupaten   = (p.kabupaten   || '').toLowerCase();
            const namaProyek  = (p.nama_proyek || '').toLowerCase();
            const kode        = (p.kode        || '').toLowerCase();
            return instansi.includes(searchTerm) || kabupaten.includes(searchTerm) ||
                   namaProyek.includes(searchTerm) || kode.includes(searchTerm);
        });
    }

    // Tahun
    const selectedTahun = tahunFilter ? tahunFilter.value : '';
    if (selectedTahun) {
        filtered = filtered.filter(p => p.tahun_potensi && p.tahun_potensi.toString() === selectedTahun);
    }

    // PIC Marketing
    const selectedPic = picMarketingFilter ? picMarketingFilter.value : '';
    if (selectedPic) {
        filtered = filtered.filter(p => p.admin_marketing === selectedPic);
    }

    // Prioritas
    const selectedPrioritas = prioritasFilter ? prioritasFilter.value : '';
    if (selectedPrioritas) {
        filtered = filtered.filter(p => {
            if (selectedPrioritas === 'none') return !p.deadline;
            return getPrioritasLevelPotensi(p.deadline) === selectedPrioritas;
        });
    }

    // Triwulan
    const selectedTriwulan = triwulanFilter ? triwulanFilter.value : '';
    if (selectedTriwulan) {
        filtered = filtered.filter(p => p.triwulan && p.triwulan.toString() === selectedTriwulan);
    }

    // Sort
    const selectedSort = sortByFilter ? sortByFilter.value : '';
    const prioritasOrder = { expired: 0, tinggi: 1, sedang: 2, rendah: 3 };
    filtered.sort((a, b) => {
        switch (selectedSort) {
            case 'terbaru':      return new Date(b.tanggal) - new Date(a.tanggal);
            case 'terlama':      return new Date(a.tanggal) - new Date(b.tanggal);
            case 'deadline_asc':
                if (!a.deadline && !b.deadline) return 0;
                if (!a.deadline) return 1; if (!b.deadline) return -1;
                return a.deadline < b.deadline ? -1 : 1;
            case 'deadline_desc':
                if (!a.deadline && !b.deadline) return 0;
                if (!a.deadline) return 1; if (!b.deadline) return -1;
                return a.deadline > b.deadline ? -1 : 1;
            case 'prioritas': {
                const la = a.deadline ? (prioritasOrder[getPrioritasLevelPotensi(a.deadline)] ?? 3) : 4;
                const lb = b.deadline ? (prioritasOrder[getPrioritasLevelPotensi(b.deadline)] ?? 3) : 4;
                return la - lb;
            }
            default: return new Date(b.tanggal) - new Date(a.tanggal);
        }
    });

    currentData  = filtered;
    currentPage  = 1;
    updateStatistics();
    displayResults();
    updatePaginationInfo();
    renderPagination();
}

function resetFilters() {
    if (searchInput)        searchInput.value        = '';
    if (tahunFilter)        tahunFilter.value        = '';
    if (picMarketingFilter) picMarketingFilter.value = '';
    if (prioritasFilter)    prioritasFilter.value    = '';
    if (sortByFilter)       sortByFilter.value       = '';
    if (triwulanFilter)     triwulanFilter.value     = '';

    currentData = [...potensiData];
    currentPage = 1;
    updateStatistics();
    displayResults();
    updatePaginationInfo();
    renderPagination();
}

/* ============================================================
   DISPLAY / PAGINATION
   ============================================================ */

function displayResults() {
    const container = document.getElementById('proyekContainer');
    const noResultsEl = document.getElementById('noResults');
    if (!container) return;

    totalPages = Math.max(1, Math.ceil(currentData.length / itemsPerPage));

    const allCards = document.querySelectorAll('.proyek-card');

    if (currentData.length === 0) {
        allCards.forEach(c => c.style.display = 'none');
        if (noResultsEl) noResultsEl.classList.remove('hidden');
        return;
    }

    if (noResultsEl) noResultsEl.classList.add('hidden');

    const startIndex = (currentPage - 1) * itemsPerPage;
    const pageData   = currentData.slice(startIndex, startIndex + itemsPerPage);
    const pageIds    = new Set(pageData.map(p => p.id));

    // Hide all first, then set CSS order for current page items
    allCards.forEach(card => { card.style.display = 'none'; card.style.order = '999'; });

    pageData.forEach((sortedPotensi, sortedIndex) => {
        const originalIndex = potensiData.findIndex(p => p.id === sortedPotensi.id);
        if (originalIndex !== -1 && allCards[originalIndex]) {
            allCards[originalIndex].style.display = 'block';
            allCards[originalIndex].style.order   = sortedIndex.toString();
        }
    });
}

function updatePaginationInfo() {
    if (!paginationInfo) return;
    const total    = currentData.length;
    const startItem = total === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const endItem   = Math.min(currentPage * itemsPerPage, total);
    paginationInfo.innerHTML = total === 0
        ? 'Tidak ada potensi yang ditampilkan'
        : 'Menampilkan <span class="font-medium">' + startItem + '</span> sampai <span class="font-medium">' + endItem + '</span> dari <span class="font-medium">' + total + '</span> potensi';
}

function renderPagination() {
    const desktop    = document.getElementById('paginationDesktop');
    const mobileText = document.getElementById('currentPageMobile');
    const prevMobile = document.getElementById('prevPageMobile');
    const nextMobile = document.getElementById('nextPageMobile');

    if (!desktop) return;
    desktop.innerHTML = '';

    if (mobileText) mobileText.textContent = currentPage + ' / ' + Math.max(1, totalPages);
    if (prevMobile) prevMobile.disabled = currentPage === 1;
    if (nextMobile) nextMobile.disabled = currentPage >= totalPages;

    // Prev
    const prevBtn = _paginationBtn('<i class="fas fa-chevron-left mr-0 md:mr-1"></i><span class="hidden md:inline">Previous</span>', currentPage === 1);
    prevBtn.onclick = () => goToPage(currentPage - 1);
    desktop.appendChild(prevBtn);

    // Page numbers
    const maxVisible = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let endPage   = Math.min(totalPages, startPage + maxVisible - 1);
    if (endPage - startPage < maxVisible - 1) startPage = Math.max(1, endPage - maxVisible + 1);

    if (startPage > 1) {
        desktop.appendChild(_pageNumBtn(1));
        if (startPage > 2) desktop.appendChild(_dotsEl());
    }
    for (let i = startPage; i <= endPage; i++) desktop.appendChild(_pageNumBtn(i));
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) desktop.appendChild(_dotsEl());
        desktop.appendChild(_pageNumBtn(totalPages));
    }

    // Next
    const nextBtn = _paginationBtn('<span class="hidden md:inline">Next</span><i class="fas fa-chevron-right ml-0 md:ml-1"></i>', currentPage >= totalPages);
    nextBtn.onclick = () => goToPage(currentPage + 1);
    desktop.appendChild(nextBtn);
}

function _paginationBtn(html, disabled) {
    const btn = document.createElement('button');
    btn.innerHTML = html;
    btn.disabled  = disabled;
    btn.className = 'px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed';
    return btn;
}

function _pageNumBtn(pageNum) {
    const btn = document.createElement('button');
    btn.textContent = pageNum;
    btn.onclick     = () => goToPage(pageNum);
    btn.className   = pageNum === currentPage
        ? 'px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-white bg-red-600 border border-red-600 rounded-lg'
        : 'px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50';
    return btn;
}

function _dotsEl() {
    const s = document.createElement('span');
    s.className   = 'px-2 py-2 text-gray-500';
    s.textContent = '...';
    return s;
}

function goToPage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    displayResults();
    updatePaginationInfo();
    renderPagination();
    const container = document.getElementById('proyekContainer');
    if (container) container.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/* ============================================================
   EVENT LISTENERS
   ============================================================ */
if (searchInput)        searchInput.addEventListener('input', debounce(filterAndSort, 300));
if (tahunFilter)        tahunFilter.addEventListener('change', filterAndSort);
if (picMarketingFilter) picMarketingFilter.addEventListener('change', filterAndSort);
if (prioritasFilter)    prioritasFilter.addEventListener('change', filterAndSort);
if (sortByFilter)       sortByFilter.addEventListener('change', filterAndSort);
if (triwulanFilter)     triwulanFilter.addEventListener('change', filterAndSort);

/* ============================================================
   MODAL — VIEW DETAIL
   ============================================================ */

function viewDetail(id) {
    const data = potensiData.find(p => p.id == id);
    if (!data) { alert('Data proyek tidak ditemukan!'); return; }

    const setText = (elId, text) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = text || '-';
    };

    setText('detailIdProyek',       data.kode);
    setText('detailNamaProyek',     data.nama_proyek);
    setText('detailNamaInstansi',   data.instansi);
    setText('detailKabupatenKota',  data.kabupaten);
    setText('detailJenisPengadaan', data.jenis_pengadaan);
    setText('detailTanggal',        formatTanggal(data.tanggal));
    setText('detailAdminMarketing', data.admin_marketing);
    setText('detailAdminPurchasing',data.admin_purchasing);
    setText('detailPotensi',        data.potensi === 'ya' ? 'Ya' : 'Tidak');
    setText('detailTahunPotensi',   data.tahun_potensi);
    setText('detailTotalKeseluruhan', formatRupiah(data.total_nilai));
    setText('detailTriwulan', data.triwulan ? 'Triwulan ' + data.triwulan : '-');

    // Deadline
    const deadlineEl = document.getElementById('detailDeadline');
    if (deadlineEl) deadlineEl.textContent = data.deadline ? formatTanggal(data.deadline) : '-';

    // Prioritas badge
    const badgeEl = document.getElementById('detailPrioritasBadge');
    if (badgeEl) {
        const p = hitungPrioritasDeadline(data.deadline);
        if (p) {
            badgeEl.textContent = p.label;
            badgeEl.className   = 'inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full ' + p.badgeClass;
            badgeEl.classList.remove('hidden');
        } else {
            badgeEl.classList.add('hidden');
        }
    }

    // Status badge
    const statusBadge = document.getElementById('detailStatusBadge');
    if (statusBadge) {
        statusBadge.textContent = ucfirst(data.status);
        statusBadge.className   = 'inline-flex px-4 py-2 text-sm font-medium rounded-full ' + getStatusColor(data.status);
    }

    // Daftar Barang
    const barangContainer = document.getElementById('detailDaftarBarang');
    if (barangContainer) {
        if (data.daftar_barang && data.daftar_barang.length > 0) {
            barangContainer.innerHTML = '';
            data.daftar_barang.forEach((item, idx) => {
                const hargaTotal = item.harga_total ?? ((item.jumlah || 0) * (item.harga_satuan || 0));
                let filesHtml = '';
                if (item.spesifikasi_files && item.spesifikasi_files.length > 0) {
                    filesHtml = '<div class="mt-3 pt-3 border-t border-gray-200"><div class="text-sm font-medium text-gray-700 mb-2"><i class="fas fa-paperclip mr-1 text-gray-500"></i>File Spesifikasi (' + item.spesifikasi_files.length + ' file)</div><div class="space-y-1">' +
                        item.spesifikasi_files.map(function(file) {
                            var ext = file.original_name.split('.').pop().toLowerCase();
                            var icon = 'fas fa-file';
                            if (ext === 'pdf') icon = 'fas fa-file-pdf text-red-500';
                            else if (['jpg','jpeg','png','gif'].includes(ext)) icon = 'fas fa-file-image text-green-500';
                            else if (['doc','docx'].includes(ext)) icon = 'fas fa-file-word text-blue-500';
                            else if (['xls','xlsx'].includes(ext)) icon = 'fas fa-file-excel text-green-600';
                            return '<div class="flex items-center justify-between bg-white border border-gray-200 rounded p-2"><div class="flex items-center space-x-2"><i class="' + icon + '"></i><span class="text-sm text-gray-700 truncate">' + file.original_name + '</span><span class="text-xs text-gray-500">(' + formatFileSize(file.file_size || file.size) + ')</span></div><div class="flex space-x-1">' +
                                (['pdf','jpg','jpeg','png','gif'].includes(ext) ? '<button onclick="downloadDetailFile(\'' + file.stored_name + '\')" class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1 rounded hover:bg-blue-50"><i class="fas fa-eye"></i></button>' : '') +
                                '<button onclick="downloadDetailFile(\'' + file.stored_name + '\')" class="text-green-600 hover:text-green-800 text-xs px-2 py-1 rounded hover:bg-green-50"><i class="fas fa-download"></i></button></div></div>';
                        }).join('') + '</div></div>';
                }

                const div = document.createElement('div');
                div.className = 'bg-gray-50 border border-gray-200 rounded-lg p-4 mb-3';
                div.innerHTML =
                    '<div class="flex justify-between items-start mb-2">' +
                        '<h5 class="font-medium text-gray-800">' + (item.nama_barang || '-') + '</h5>' +
                        '<span class="text-lg font-bold text-red-600">' + formatRupiah(hargaTotal) + '</span>' +
                    '</div>' +
                    '<div class="grid grid-cols-3 gap-4 text-sm text-gray-600">' +
                        '<div><span class="font-medium">Qty:</span> ' + (item.jumlah || 0) + '</div>' +
                        '<div><span class="font-medium">Satuan:</span> ' + (item.satuan || '-') + '</div>' +
                        '<div><span class="font-medium">Harga Satuan:</span> ' + formatRupiah(item.harga_satuan || 0) + '</div>' +
                    '</div>' +
                    (item.spesifikasi ? '<div class="mt-2 text-sm text-gray-600"><span class="font-medium">Spesifikasi:</span> ' + item.spesifikasi + '</div>' : '') +
                    filesHtml;
                barangContainer.appendChild(div);
            });
        } else {
            barangContainer.innerHTML = '<p class="text-gray-500 text-sm">Tidak ada data barang</p>';
        }
    }

    // Catatan
    const catatanEl = document.getElementById('detailCatatan');
    const catatanSection = document.getElementById('detailCatatanSection');
    if (data.catatan && data.catatan.trim()) {
        if (catatanEl) catatanEl.textContent = data.catatan;
        if (catatanSection) catatanSection.style.display = 'block';
    } else {
        if (catatanSection) catatanSection.style.display = 'none';
    }

    loadDetailDocuments(id);
    openModal('modalDetailProyek');
}

/* ============================================================
   MODAL — EDIT
   ============================================================ */

function editProyek(id) {
    @if(!(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing'))
        alert('Tidak memiliki akses untuk mengedit potensi.');
        return;
    @endif

    const data = potensiData.find(p => p.id == id);
    if (!data) { alert('Data potensi tidak ditemukan!'); return; }

    setTimeout(() => {
        const setVal = (elId, val) => {
            const el = document.getElementById(elId);
            if (el) el.value = val || '';
        };

        setVal('editId',            data.id);
        setVal('editIdProyek',      data.kode);
        setVal('editTanggal',       data.tanggal);
        setVal('editKabupatenKota', data.kabupaten);
        setVal('editNamaInstansi',  data.instansi);
        setVal('editNamaProyek',    data.nama_proyek);
        setVal('editJenisPengadaan',data.jenis_pengadaan);
        setVal('editAdminMarketing',data.admin_marketing);
        setVal('editAdminPurchasing',data.admin_purchasing);
        setVal('editStatus',        data.status);
        setVal('editCatatan',       data.catatan);
        setVal('editTahunPotensi',  data.tahun_potensi);
        setVal('editDeadline',      data.deadline);

        if (typeof togglePotensiEdit === 'function') togglePotensiEdit(data.potensi);
        if (typeof loadEditData      === 'function') loadEditData(data);
    }, 100);

    openModal('modalEditProyek');
}

/* ============================================================
   MODAL — DELETE
   ============================================================ */

function deleteProyek(id) {
    @if(!(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing'))
        alert('Tidak memiliki akses untuk menghapus potensi.');
        return;
    @endif

    const data = potensiData.find(p => p.id == id);
    if (!data) { alert('Data potensi tidak ditemukan!'); return; }

    window.hapusData = { id: data.id, kode: data.kode, instansi: data.instansi, kabupaten: data.kabupaten, status: data.status };

    const setText = (elId, text) => { const el = document.getElementById(elId); if (el) el.textContent = text || '-'; };
    setText('hapusKode',     data.kode);
    setText('hapusInstansi', data.instansi);
    setText('hapusKabupaten',data.kabupaten);

    const statusEl = document.getElementById('hapusStatus');
    if (statusEl) {
        statusEl.textContent = ucfirst(data.status);
        statusEl.className   = 'text-sm font-medium ' + getStatusClass(data.status);
    }

    if (typeof loadHapusData === 'function') {
        loadHapusData({ id: data.id, kode: data.kode, nama_instansi: data.instansi, kabupaten_kota: data.kabupaten, status: data.status });
    }

    openModal('modalHapusProyek');
}

/* ============================================================
   MODAL — BUAT PENAWARAN
   ============================================================ */

function buatPenawaran(id) {
    @if(!(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing'))
        alert('Tidak memiliki akses untuk membuat penawaran.');
        return;
    @endif

    const data = potensiData.find(p => p.id == id);
    if (!data) { alert('Data potensi tidak ditemukan!'); return; }

    window.location.href = '/marketing/penawaran/' + id;
}

/* ============================================================
   STATUS CHANGE (quick change via API)
   ============================================================ */

function changeStatusQuick(potensiId, newStatus, selectElement) {
    if (event) { event.stopPropagation(); event.preventDefault(); }
    if (!newStatus) return;

    const potensi = potensiData.find(p => p.id == potensiId);
    if (!potensi) { showErrorMessage('Data potensi tidak ditemukan!'); return; }

    if (!validateDropdownChange(selectElement, potensiId)) {
        if (selectElement) selectElement.value = '';
        return;
    }

    const statusNames = { penawaran:'Penawaran', pembayaran:'Pembayaran', pengiriman:'Pengiriman', selesai:'Selesai', gagal:'Gagal' };
    if (!confirm('Ubah status "' + potensi.nama_proyek + '" menjadi "' + (statusNames[newStatus] || newStatus) + '"?')) {
        if (selectElement) selectElement.value = '';
        return;
    }

    const originalHTML = selectElement ? selectElement.innerHTML : '';
    if (selectElement) { selectElement.innerHTML = '<option>⏳ Memproses...</option>'; selectElement.disabled = true; }

    fetch('/marketing/proyek/' + potensiId + '/status', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(async res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const ct = res.headers.get('content-type');
        if (!ct || !ct.includes('application/json')) throw new Error('Response bukan JSON');
        return res.json();
    })
    .then(data => {
        if (data.success) {
            potensi.status = newStatus;
            updatePotensiStatusInUI(potensiId, newStatus);
            if (selectElement) { selectElement.disabled = false; selectElement.innerHTML = originalHTML; selectElement.value = ''; selectElement.dataset.currentStatus = newStatus; }
            showSuccessMessage('Status berhasil diubah menjadi "' + (statusNames[newStatus] || newStatus) + '"!');
            updateStatistics();
            filterAndSort();
        } else {
            throw new Error(data.message || 'Gagal mengubah status');
        }
    })
    .catch(err => {
        showErrorMessage('Terjadi kesalahan: ' + err.message);
        if (selectElement) { selectElement.disabled = false; selectElement.innerHTML = originalHTML; selectElement.value = ''; }
    });
}

function updatePotensiStatusInUI(potensiId, newStatus) {
    const allCards = document.querySelectorAll('.proyek-card');
    const idx = potensiData.findIndex(p => p.id == potensiId);
    if (idx === -1 || !allCards[idx]) return;

    const badge = allCards[idx].querySelector('span[class*="rounded-full"]');
    if (badge) {
        badge.className = 'inline-flex px-2 sm:px-3 py-1 text-xs font-medium rounded-full ' + getStatusColor(newStatus);
        badge.textContent = ucfirst(newStatus);
    }
    allCards[idx].setAttribute('data-status', newStatus);
}

function validateDropdownChange(selectElement, proyekId) {
    if (!selectElement) return false;
    const newStatus = selectElement.value;
    const currentStatus = selectElement.dataset.currentStatus;
    return !!(newStatus && newStatus !== currentStatus);
}

/* ============================================================
   TOGGLE POTENSI (edit modal)
   ============================================================ */

function togglePotensiEdit(value) {
    const yaBtn     = document.getElementById('editPotensiYa');
    const tidakBtn  = document.getElementById('editPotensiTidak');
    const hiddenInput = document.getElementById('editPotensiValue');
    if (!yaBtn || !tidakBtn) return;

    yaBtn.classList.remove('bg-green-500', 'text-white', 'border-green-500');
    tidakBtn.classList.remove('bg-red-500', 'text-white', 'border-red-500');
    yaBtn.classList.add('border-gray-300', 'text-gray-700');
    tidakBtn.classList.add('border-gray-300', 'text-gray-700');

    if (value === 'ya') {
        yaBtn.classList.replace('border-gray-300', 'border-green-500');
        yaBtn.classList.replace('text-gray-700', 'text-white');
        yaBtn.classList.add('bg-green-500');
        if (hiddenInput) hiddenInput.value = 'ya';
    } else if (value === 'tidak') {
        tidakBtn.classList.replace('border-gray-300', 'border-red-500');
        tidakBtn.classList.replace('text-gray-700', 'text-white');
        tidakBtn.classList.add('bg-red-500');
        if (hiddenInput) hiddenInput.value = 'tidak';
    }
}

/* ============================================================
   FILE HELPERS (untuk panel barang / detail modal)
   ============================================================ */

function downloadFile(filename) {
    window.open('/marketing/proyek/file/' + filename, '_blank');
}

function downloadDetailFile(filename) {
    window.open('/marketing/potensi/file/' + filename, '_blank');
}

function previewFile(filename) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
    modal.innerHTML =
        '<div class="bg-white rounded-lg shadow-xl max-w-4xl max-h-[90vh] w-full mx-4 overflow-hidden">' +
            '<div class="flex items-center justify-between p-4 border-b">' +
                '<h3 class="text-lg font-semibold">Preview File: ' + filename + '</h3>' +
                '<button onclick="this.closest(\'.fixed\').remove()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times text-xl"></i></button>' +
            '</div>' +
            '<div class="p-4 h-96 overflow-auto">' +
                '<iframe src="/marketing/proyek/file/' + filename + '/preview" class="w-full h-full border-0"></iframe>' +
            '</div>' +
            '<div class="flex justify-end space-x-2 p-4 border-t">' +
                '<button onclick="downloadFile(\'' + filename + '\')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"><i class="fas fa-download mr-2"></i>Download</button>' +
                '<button onclick="this.closest(\'.fixed\').remove()" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Tutup</button>' +
            '</div>' +
        '</div>';
    document.body.appendChild(modal);
}

/* ============================================================
   MODAL FALLBACKS (jika modal-functions.js belum memuat)
   ============================================================ */

if (typeof openModal === 'undefined') {
    window.openModal = function(modalId) {
        const m = document.getElementById(modalId);
        if (m) { m.classList.remove('hidden'); m.classList.add('flex'); document.body.classList.add('modal-open'); }
    };
}
if (typeof closeModal === 'undefined') {
    window.closeModal = function(modalId) {
        const m = document.getElementById(modalId);
        if (m) { m.classList.add('hidden'); m.classList.remove('flex'); document.body.classList.remove('modal-open'); }
    };
}
if (typeof showSuccessModal === 'undefined') {
    window.showSuccessModal = function(message) { alert(message); };
}

/* ============================================================
   EXPORT EXCEL
   ============================================================ */

function exportToExcel() {
    const params = new URLSearchParams();
    if (tahunFilter && tahunFilter.value)               params.append('tahun', tahunFilter.value);
    if (picMarketingFilter && picMarketingFilter.value) params.append('pic_marketing', picMarketingFilter.value);
    if (searchInput && searchInput.value)               params.append('search', searchInput.value);

    const exportUrl = '{{ route("marketing.potensi.export.excel") }}' + (params.toString() ? '?' + params.toString() : '');
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengekspor...';
    window.location.href = exportUrl;
    setTimeout(() => { btn.disabled = false; btn.innerHTML = originalHTML; }, 2000);
}

/* ============================================================
   INIT
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    currentData = [...potensiData];
    totalPages  = Math.ceil(currentData.length / itemsPerPage);
    updateStatistics();
    displayResults();
    updatePaginationInfo();
    renderPagination();
});
</script>

@endsection