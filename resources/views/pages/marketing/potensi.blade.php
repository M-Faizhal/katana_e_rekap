@extends('layouts.app')

@section('title', 'Potensi - Cyber KATANA')

@section('content')

@php
// Fungsi helper untuk menghitung statistik
function countTotal($data) {
    return count($data);
}

function calculateTotalNilai($data) {
    return array_reduce($data, function($carry, $item) {
        return $carry + ($item['total_nilai'] ?? 0);
    }, 0);
}

// Hitung statistik
$totalPotensi = countTotal($proyekData);
$totalNilaiPotensi = calculateTotalNilai($proyekData);

// Cek akses user
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
        <div class="hidden sm:block lg:block">
            <i class="fas fa-lightbulb text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <!-- Total Potensi Card -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 sm:p-4 rounded-xl bg-red-100 mr-4">
                <i class="fas fa-lightbulb text-red-600 text-2xl sm:text-3xl lg:text-4xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-600 mb-1">Total Potensi</h3>
                <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-red-600">{{ $totalPotensi }}</p>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Potensi yang sedang menunggu</p>
            </div>
        </div>
    </div>

    <!-- Total Nilai Card -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 sm:p-4 rounded-xl bg-green-100 mr-4">
                <i class="fas fa-money-bill-wave text-green-600 text-2xl sm:text-3xl lg:text-4xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-600 mb-1">Total Nilai Potensi</h3>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-600">Rp {{ number_format($totalNilaiPotensi, 0, ',', '.') }}</p>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Estimasi nilai keseluruhan</p>
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
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <select id="tahunFilter" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Tahun</option>
                    @php
                        $currentYear = date('Y');
                        $futureYears = 5; // Tahun ke depan yang ditampilkan

                        // Ambil tahun yang ada di data
                        $availableYears = collect($proyekData)
                            ->pluck('tahun_potensi')
                            ->filter()
                            ->unique()
                            ->sort()
                            ->values();

                        // Filter hanya tahun dari tahun ini ke depan
                        $filteredYears = $availableYears->filter(function($year) use ($currentYear, $futureYears) {
                            return $year >= $currentYear && $year <= ($currentYear + $futureYears);
                        })->sortDesc();
                    @endphp
                    @foreach($filteredYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <select id="picMarketingFilter" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua PIC Marketing</option>
                    @php
                        $uniqueMarketing = collect($proyekData)->pluck('admin_marketing')->unique()->sort()->values();
                    @endphp
                    @foreach($uniqueMarketing as $marketing)
                        <option value="{{ $marketing }}">{{ $marketing }}</option>
                    @endforeach
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
        <div id="proyekContainer" class="grid grid-cols-1 gap-4 sm:gap-6">
            @foreach($proyekData as $index => $potensi)
            <!-- Card {{ $index + 1 }} -->
            <div class="proyek-card bg-white border border-gray-200 rounded-xl sm:rounded-2xl p-4 sm:p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200 cursor-pointer relative"
                 data-status="{{ $potensi['status'] }}"
                 data-kabupaten="{{ strtolower($potensi['kabupaten']) }}"
                 data-instansi="{{ strtolower($potensi['instansi']) }}"
                 data-tanggal="{{ $potensi['tanggal'] }}"
                 onclick="buatPenawaran({{ $potensi['id'] }})"
                 title="Klik untuk membuat penawaran">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4">
                    <div class="flex items-center space-x-3 mb-3 sm:mb-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-red-600 font-bold text-sm sm:text-lg">{{ $index + 1 }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-base sm:text-lg font-bold text-gray-800">{{ $potensi['kode'] }}</h3>
                            </div>

                            <div class="flex items-center gap-2">
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

                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 sm:space-x-2 self-start" onclick="event.stopPropagation()">
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
                        <button onclick="deleteProyek({{ $potensi['id'] }})" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Tanggal</p>
                        <p class="font-medium text-gray-800 text-sm sm:text-base">{{ \Carbon\Carbon::parse($potensi['tanggal'])->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Kabupaten/Kota</p>
                        <p class="font-medium text-gray-800 text-sm sm:text-base">{{ $potensi['kabupaten'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Nama Instansi</p>
                        <p class="font-medium text-gray-800 text-sm sm:text-base">{{ $potensi['instansi'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-3 sm:mt-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">PIC Marketing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-red-600 text-xs sm:text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800 text-sm sm:text-base truncate">{{ $potensi['admin_marketing'] }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">PIC Purchasing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-blue-600 text-xs sm:text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800 text-sm sm:text-base truncate">{{ $potensi['admin_purchasing'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Nilai -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">Total Nilai Potensi:</span>
                        <span class="text-lg font-bold text-red-600">Rp {{ number_format($potensi['total_nilai'], 2, ',', '.') }}</span>
                    </div>

                    <!-- Penawaran Info -->
                    @if(isset($potensi['penawaran']))
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">No. Penawaran:</span>
                        <span class="font-medium text-blue-600">{{ $potensi['penawaran']['no_penawaran'] }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm mt-1">
                        <span class="text-gray-500">Tanggal Penawaran:</span>
                        <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($potensi['penawaran']['tanggal_penawaran'])->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
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
                <!-- Mobile Pagination (Simple) -->
                <div id="paginationMobile" class="flex items-center space-x-1 sm:hidden">
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

                <!-- Tablet & Desktop Pagination (Full) -->
                <div id="paginationDesktop" class="hidden sm:flex items-center space-x-1 md:space-x-2">
                    <!-- Pagination buttons will be generated dynamically -->
                </div>
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
@include('components.success-modal')

<!-- Include Modal Functions -->
<script src="{{ asset('js/modal-functions.js') }}"></script>

<!-- Styles -->
<style>
/* Modal Styling */
.modal-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 0.5rem;
}

@media (min-width: 640px) {
    .modal-container {
        padding: 1rem;
    }
}

.modal-content {
    max-height: calc(100vh - 1rem);
    overflow-y: auto;
    width: 100%;
    max-width: 100%;
}

@media (min-width: 640px) {
    .modal-content {
        max-height: calc(100vh - 2rem);
        max-width: 32rem;
    }
}

@media (min-width: 768px) {
    .modal-content {
        max-width: 42rem;
    }
}

@media (min-width: 1024px) {
    .modal-content {
        max-width: 48rem;
    }
}

.modal-content::-webkit-scrollbar {
    width: 4px;
}

@media (min-width: 768px) {
    .modal-content::-webkit-scrollbar {
        width: 6px;
    }
}

.modal-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.modal-open {
    overflow: hidden;
}

.potensi-btn, .potensi-btn-edit {
    transition: all 0.2s ease-in-out;
}

.potensi-btn:hover, .potensi-btn-edit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

@media (max-width: 639px) {
    .modal-container {
        padding: 0;
        align-items: flex-start;
    }

    .modal-content {
        max-height: 100vh;
        border-radius: 0;
        margin: 0;
        min-height: 100vh;
    }

    .modal-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background: white;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-form .space-y-4 > * + * {
        margin-top: 0.75rem;
    }

    .modal-form .space-y-6 > * + * {
        margin-top: 1rem;
    }

    .modal-form input,
    .modal-form select,
    .modal-form textarea {
        min-height: 44px;
        font-size: 16px;
    }

    .modal-form button {
        min-height: 44px;
        padding: 0.75rem 1rem;
    }
}

@media (min-width: 640px) and (max-width: 1023px) {
    .modal-content {
        margin: 1rem;
        border-radius: 0.75rem;
    }

    .modal-form input,
    .modal-form select,
    .modal-form textarea {
        min-height: 40px;
    }

    .modal-form button {
        min-height: 40px;
    }
}

.modal-enter {
    animation: modalFadeIn 0.3s ease-out;
}

.modal-exit {
    animation: modalFadeOut 0.3s ease-in;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes modalFadeOut {
    from {
        opacity: 1;
        transform: scale(1);
    }
    to {
        opacity: 0;
        transform: scale(0.95);
    }
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

@media (max-width: 639px) {
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.75);
    }
}

/* Status Change Animations */
.status-change-highlight {
    animation: statusGlow 0.8s ease-in-out;
}

@keyframes statusGlow {
    0%, 100% {
        box-shadow: 0 0 0 rgba(239, 68, 68, 0);
    }
    50% {
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
        transform: scale(1.02);
    }
}

/* Dropdown styling */
select.status-dropdown {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2rem;
}

/* Tooltip styling */
.tooltip-wrapper {
    position: relative;
}

.tooltip {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    margin-bottom: 0.5rem;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease-in-out;
    z-index: 10;
}

.tooltip-wrapper:hover .tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(-2px);
}

/* Success notification animation */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.notification-enter {
    animation: slideInRight 0.3s ease-out;
}

.notification-exit {
    animation: slideOutRight 0.3s ease-in;
}

/* Flexbox container for card reordering */
#proyekContainer.reordering {
    display: flex !important;
    flex-direction: column !important;
    gap: 1rem !important;
}

#proyekContainer.reordering .proyek-card {
    width: 100% !important;
    margin: 0 !important;
}
</style>

<script>
// Data dari PHP untuk JavaScript
const potensiData = @json($proyekData);
let filteredData = [...potensiData];
let currentData = [...potensiData];

// Pagination variables
let currentPage = 1;
const itemsPerPage = 10;
let totalPages = 1;

// DOM Elements
const searchInput = document.getElementById('searchInput');
const tahunFilter = document.getElementById('tahunFilter');
const picMarketingFilter = document.getElementById('picMarketingFilter');
const potensiContainer = document.getElementById('proyekContainer');
const noResults = document.getElementById('noResults');
const paginationInfo = document.getElementById('paginationInfo');

// Event Listeners untuk filter dan search
if (searchInput) {
    searchInput.addEventListener('input', debounce(filterAndSort, 300));
}
if (tahunFilter) {
    tahunFilter.addEventListener('change', filterAndSort);
}
if (picMarketingFilter) {
    picMarketingFilter.addEventListener('change', filterAndSort);
}

// Debounce function untuk search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Filter dan sort function
function filterAndSort() {
    let filtered = [...potensiData];

    console.log('=== FILTER AND SORT DEBUG ===');
    console.log('Starting filterAndSort with total data:', filtered.length);
    console.log('Data sample:', filtered.slice(0, 2));

    // Apply search filter
    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    console.log('Search term:', searchTerm);
    if (searchTerm) {
        console.log('Applying search filter for term:', searchTerm);
        const beforeSearch = filtered.length;
        filtered = filtered.filter(proyek => {
            // Pastikan semua field ada dan bukan null/undefined
            const instansi = proyek.instansi ? proyek.instansi.toLowerCase() : '';
            const kabupaten = proyek.kabupaten ? proyek.kabupaten.toLowerCase() : '';
            const namaProyek = proyek.nama_proyek ? proyek.nama_proyek.toLowerCase() : '';
            const kode = proyek.kode ? proyek.kode.toLowerCase() : '';

            const match = instansi.includes(searchTerm) ||
                         kabupaten.includes(searchTerm) ||
                         namaProyek.includes(searchTerm) ||
                         kode.includes(searchTerm);

            if (match) {
                console.log('Match found in:', { instansi, kabupaten, namaProyek, kode });
            }

            return match;
        });
        console.log('After search filter:', filtered.length, 'items remaining (was', beforeSearch, ')');
    }

    // Apply tahun filter
    const selectedTahun = tahunFilter ? tahunFilter.value : '';
    console.log('Selected tahun:', selectedTahun);
    if (selectedTahun) {
        console.log('Applying tahun filter for:', selectedTahun);
        const beforeTahunFilter = filtered.length;
        filtered = filtered.filter(proyek => {
            const tahunPotensi = proyek.tahun_potensi ? proyek.tahun_potensi.toString() : '';
            const match = tahunPotensi === selectedTahun;
            console.log('Tahun check:', tahunPotensi, '===', selectedTahun, '=', match);
            return match;
        });
        console.log('After tahun filter:', filtered.length, 'items remaining (was', beforeTahunFilter, ')');
    }

    // Apply PIC Marketing filter
    const selectedPicMarketing = picMarketingFilter ? picMarketingFilter.value : '';
    console.log('Selected PIC Marketing:', selectedPicMarketing);
    if (selectedPicMarketing) {
        console.log('Applying PIC Marketing filter for:', selectedPicMarketing);
        const beforePicFilter = filtered.length;
        filtered = filtered.filter(proyek => {
            const match = proyek.admin_marketing === selectedPicMarketing;
            console.log('PIC Marketing check:', proyek.admin_marketing, '===', selectedPicMarketing, '=', match);
            return match;
        });
        console.log('After PIC Marketing filter:', filtered.length, 'items remaining (was', beforePicFilter, ')');
    }

    // Sort by tanggal terbaru (default)
    filtered.sort((a, b) => {
        const dateA = new Date(a.tanggal);
        const dateB = new Date(b.tanggal);
        return dateB - dateA;
    });

    console.log('Final filtered data:', filtered.length, 'items');
    console.log('Sample final data:', filtered.slice(0, 2).map(p => ({ kode: p.kode, instansi: p.instansi, status: p.status })));

    currentData = filtered;
    currentPage = 1; // Reset to first page when filtering
    displayResults();
    updatePaginationInfo();
    renderPagination();

    console.log('=== END FILTER AND SORT DEBUG ===');
}

// Reset all filters
function resetFilters() {
    console.log('=== RESET FILTERS DEBUG ===');
    console.log('Starting reset filters');

    // Reset form elements
    if (searchInput) {
        console.log('Resetting search input from:', searchInput.value);
        searchInput.value = '';
    }
    if (tahunFilter) {
        console.log('Resetting tahun filter from:', tahunFilter.value);
        tahunFilter.value = '';
    }
    if (picMarketingFilter) {
        console.log('Resetting PIC Marketing filter from:', picMarketingFilter.value);
        picMarketingFilter.value = '';
    }

    // Reset data to original
    console.log('Resetting currentData from', currentData.length, 'to', potensiData.length, 'items');
    currentData = [...potensiData];
    currentPage = 1; // Reset to first page

    // Hide no results message
    const noResults = document.getElementById('noResults');
    const proyekContainer = document.getElementById('proyekContainer');
    if (noResults) {
        noResults.classList.add('hidden');
        console.log('No results message hidden');
    }
    if (proyekContainer) {
        proyekContainer.classList.remove('hidden');
        proyekContainer.classList.remove('reordering');
        console.log('Proyek container shown');
    }

    // Update pagination info and render - this will handle showing/hiding cards
    displayResults();
    updatePaginationInfo();
    renderPagination();

    console.log('Reset complete - showing first page of', potensiData.length, 'items');
    console.log('=== END RESET DEBUG ===');
}

// Display results
function displayResults() {
    console.log('=== DISPLAY RESULTS DEBUG ===');
    const container = document.getElementById('proyekContainer');
    const noResults = document.getElementById('noResults');

    if (!container) {
        console.error('Proyek container not found');
        return;
    }

    // Calculate total pages
    totalPages = Math.ceil(currentData.length / itemsPerPage);
    console.log('Total pages:', totalPages, 'Current page:', currentPage);

    // Get all cards
    const cards = document.querySelectorAll('.proyek-card');
    console.log('Found', cards.length, 'total cards');
    console.log('Current data length:', currentData.length);

    // If there's no filtered data, show no results
    if (currentData.length === 0) {
        console.log('No data to show - displaying no results message');
        cards.forEach(card => card.style.display = 'none');
        if (noResults) noResults.classList.remove('hidden');
        if (container) container.classList.add('hidden');
        return;
    }

    // Calculate start and end index for current page
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentPageData = currentData.slice(startIndex, endIndex);

    console.log('Displaying items from', startIndex, 'to', endIndex);
    console.log('Items on current page:', currentPageData.length);

    // Create a set of current page potensi IDs
    const currentPageIds = new Set(currentPageData.map(potensi => potensi.id));
    console.log('Current page potensi IDs:', Array.from(currentPageIds));

    // Show/hide cards based on current page data
    let visibleCount = 0;

    // Process each card
    cards.forEach((card, cardIndex) => {
        const originalPotensi = potensiData[cardIndex];
        if (originalPotensi && currentPageIds.has(originalPotensi.id)) {
            card.style.display = 'block';
            visibleCount++;
            console.log('Showing card for potensi:', originalPotensi.kode, 'at index', cardIndex);
        } else {
            card.style.display = 'none';
        }
    });

    console.log('Visible cards:', visibleCount);

    // Show/hide appropriate messages
    if (visibleCount === 0) {
        if (noResults) noResults.classList.remove('hidden');
        if (container) container.classList.add('hidden');
        console.log('No cards visible - showing no results');
    } else {
        if (noResults) noResults.classList.add('hidden');
        if (container) container.classList.remove('hidden');
        console.log('Cards visible - hiding no results');
    }

    // Always reorder cards if we have filtered data (due to default date sorting)
    if (currentData.length > 0) {
        console.log('Reordering cards based on filter/sort');
        reorderCards();
    } else {
        // Reset to original order if no data
        console.log('No data - resetting card order');
        const container = document.getElementById('proyekContainer');
        if (container) {
            container.classList.remove('reordering');
        }
    }

    console.log('=== END DISPLAY RESULTS DEBUG ===');
}

// Function to reset card order to original
function resetCardOrder() {
    console.log('=== RESET CARD ORDER ===');
    const container = document.getElementById('proyekContainer');
    if (!container) return;

    // Remove reordering class
    container.classList.remove('reordering');

    const allCards = document.querySelectorAll('.proyek-card');
    allCards.forEach((card, index) => {
        card.style.order = '';
        card.style.display = '';
    });

    console.log('Card order reset to original');
    console.log('=== END RESET CARD ORDER ===');
}

// Function to reorder cards based on current sorted data
function reorderCards() {
    console.log('=== REORDER CARDS DEBUG ===');
    const container = document.getElementById('proyekContainer');
    if (!container) {
        console.error('Container not found for reordering');
        return;
    }

    // Add reordering class to container for flexbox styling
    container.classList.add('reordering');

    const allCards = document.querySelectorAll('.proyek-card');
    console.log('Reordering', allCards.length, 'cards using CSS order property');

    // Calculate start and end index for current page
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentPageData = currentData.slice(startIndex, endIndex);

    console.log('Reordering only current page data:', currentPageData.length, 'items');

    // Reset all cards to default order first
    allCards.forEach(card => {
        card.style.order = '999';
        card.style.display = 'none';
    });

    // Set order for visible cards based on current page data only
    currentPageData.forEach((sortedPotensi, sortedIndex) => {
        // Find the card that corresponds to this potensi
        const cardIndex = potensiData.findIndex(p => p.id === sortedPotensi.id);
        if (cardIndex !== -1 && allCards[cardIndex]) {
            allCards[cardIndex].style.order = sortedIndex.toString();
            allCards[cardIndex].style.display = 'block';
            console.log('Set order', sortedIndex, 'for potensi:', sortedPotensi.kode);
        }
    });

    console.log('Cards reordered successfully - showing', currentPageData.length, 'items');
    console.log('=== END REORDER CARDS DEBUG ===');
}

// Update pagination info
function updatePaginationInfo() {
    if (paginationInfo) {
        const totalVisible = currentData.length;
        const totalAll = potensiData.length;
        const startItem = totalVisible === 0 ? 0 : ((currentPage - 1) * itemsPerPage) + 1;
        const endItem = Math.min(currentPage * itemsPerPage, totalVisible);

        if (totalVisible === 0) {
            paginationInfo.innerHTML = 'Tidak ada potensi yang ditampilkan';
        } else {
            paginationInfo.innerHTML = 'Menampilkan <span class="font-medium">' + startItem + '</span> sampai <span class="font-medium">' + endItem + '</span> dari <span class="font-medium">' + totalVisible + '</span> potensi';
        }
    }
}

// Render pagination buttons
function renderPagination() {
    const paginationDesktop = document.getElementById('paginationDesktop');
    const paginationMobile = document.getElementById('currentPageMobile');
    const prevBtnMobile = document.getElementById('prevPageMobile');
    const nextBtnMobile = document.getElementById('nextPageMobile');

    if (!paginationDesktop) return;

    // Clear existing buttons
    paginationDesktop.innerHTML = '';

    // Update mobile pagination
    if (paginationMobile) {
        paginationMobile.textContent = currentPage + ' / ' + Math.max(1, totalPages);
    }

    // Enable/disable mobile buttons
    if (prevBtnMobile) {
        prevBtnMobile.disabled = currentPage === 1;
    }
    if (nextBtnMobile) {
        nextBtnMobile.disabled = currentPage >= totalPages;
    }

    // Previous button
    const prevBtn = document.createElement('button');
    prevBtn.onclick = () => goToPage(currentPage - 1);
    prevBtn.disabled = currentPage === 1;
    prevBtn.className = 'px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed';
    prevBtn.innerHTML = '<i class="fas fa-chevron-left mr-0 md:mr-1"></i><span class="hidden md:inline">Previous</span>';
    paginationDesktop.appendChild(prevBtn);

    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    // Adjust startPage if we're near the end
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    // First page button (if not in range)
    if (startPage > 1) {
        const firstBtn = createPageButton(1);
        paginationDesktop.appendChild(firstBtn);

        if (startPage > 2) {
            const dots = document.createElement('span');
            dots.className = 'px-2 py-2 text-gray-500';
            dots.textContent = '...';
            paginationDesktop.appendChild(dots);
        }
    }

    // Page number buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageBtn = createPageButton(i);
        paginationDesktop.appendChild(pageBtn);
    }

    // Last page button (if not in range)
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            const dots = document.createElement('span');
            dots.className = 'px-2 py-2 text-gray-500';
            dots.textContent = '...';
            paginationDesktop.appendChild(dots);
        }

        const lastBtn = createPageButton(totalPages);
        paginationDesktop.appendChild(lastBtn);
    }

    // Next button
    const nextBtn = document.createElement('button');
    nextBtn.onclick = () => goToPage(currentPage + 1);
    nextBtn.disabled = currentPage >= totalPages;
    nextBtn.className = 'px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed';
    nextBtn.innerHTML = '<span class="hidden md:inline">Next</span><i class="fas fa-chevron-right ml-0 md:ml-1"></i>';
    paginationDesktop.appendChild(nextBtn);
}

// Create page button
function createPageButton(pageNum) {
    const btn = document.createElement('button');
    btn.onclick = () => goToPage(pageNum);
    btn.textContent = pageNum;

    if (pageNum === currentPage) {
        btn.className = 'px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-white bg-red-600 border border-red-600 rounded-lg';
    } else {
        btn.className = 'px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50';
    }

    return btn;
}

// Go to specific page
function goToPage(page) {
    if (page < 1 || page > totalPages) return;

    currentPage = page;
    displayResults();
    updatePaginationInfo();
    renderPagination();

    // Scroll to top of proyek container
    const container = document.getElementById('proyekContainer');
    if (container) {
        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Function to view detail potensi
function viewDetail(id) {
    console.log('viewDetail called with ID:', id);

    const data = potensiData.find(p => p.id == id);

    if (!data) {
        console.error('Data proyek tidak ditemukan dengan ID:', id);
        alert('Data proyek tidak ditemukan!');
        return;
    }

    console.log('Data found:', data);

    const formattedData = {
        id: data.id,
        kode: data.kode,
        nama_proyek: data.nama_proyek,
        instansi: data.instansi,
        kabupaten: data.kabupaten,
        jenis_pengadaan: data.jenis_pengadaan,
        tanggal: formatTanggal(data.tanggal),
        status: data.status,
        admin_marketing: data.admin_marketing,
        admin_purchasing: data.admin_purchasing,
        catatan: data.catatan,
        potensi: data.potensi === 'ya' ? 'Ya' : 'Tidak',
        tahun_potensi: data.tahun_potensi,
        total_nilai: data.total_nilai,
        daftar_barang: data.daftar_barang || []
    };

    // Populate detail modal elements
    const setElementText = (id, text) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = text || '-';
        } else {
            console.warn('Element dengan ID ' + id + ' tidak ditemukan');
        }
    };

    // Set basic info
    setElementText('detailIdProyek', formattedData.kode);
    setElementText('detailNamaProyek', formattedData.nama_proyek);
    setElementText('detailNamaInstansi', formattedData.instansi);
    setElementText('detailKabupatenKota', formattedData.kabupaten);
    setElementText('detailJenisPengadaan', formattedData.jenis_pengadaan);
    setElementText('detailTanggal', formattedData.tanggal);
    setElementText('detailAdminMarketing', formattedData.admin_marketing);
    setElementText('detailAdminPurchasing', formattedData.admin_purchasing);
    setElementText('detailPotensi', formattedData.potensi);
    setElementText('detailTahunPotensi', formattedData.tahun_potensi);
    setElementText('detailTotalKeseluruhan', formatRupiah(formattedData.total_nilai));

    // Update status badge
    const statusBadge = document.getElementById('detailStatusBadge');
    if (statusBadge) {
        statusBadge.textContent = ucfirst(formattedData.status);
        statusBadge.className = 'inline-flex px-3 py-1 text-sm font-medium rounded-full';

        if (formattedData.status === 'berhasil') {
            statusBadge.classList.add('bg-green-100', 'text-green-800');
        } else if (formattedData.status === 'proses') {
            statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
        } else if (formattedData.status === 'gagal') {
            statusBadge.classList.add('bg-red-100', 'text-red-800');
        }
    }

    // Populate daftar barang
    const daftarBarangContainer = document.getElementById('detailDaftarBarang');
    if (daftarBarangContainer && formattedData.daftar_barang && formattedData.daftar_barang.length > 0) {
        daftarBarangContainer.innerHTML = '';

        formattedData.daftar_barang.forEach((item, index) => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'bg-gray-50 border border-gray-200 rounded-lg p-4 mb-3';

            // Build spesifikasi files HTML
            let filesHtml = '';
            if (item.spesifikasi_files && Array.isArray(item.spesifikasi_files) && item.spesifikasi_files.length > 0) {
                filesHtml = `
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-paperclip mr-2 text-gray-500"></i>
                            File Spesifikasi (${item.spesifikasi_files.length} file)
                        </div>
                        <div class="space-y-1">
                            ${item.spesifikasi_files.map(file => {
                                const fileExtension = file.original_name.split('.').pop().toLowerCase();
                                let fileIcon = 'fas fa-file';
                                if (['pdf'].includes(fileExtension)) {
                                    fileIcon = 'fas fa-file-pdf text-red-500';
                                } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                                    fileIcon = 'fas fa-file-image text-green-500';
                                } else if (['doc', 'docx'].includes(fileExtension)) {
                                    fileIcon = 'fas fa-file-word text-blue-500';
                                } else if (['xls', 'xlsx'].includes(fileExtension)) {
                                    fileIcon = 'fas fa-file-excel text-green-600';
                                }

                                return `
                                    <div class="flex items-center justify-between bg-white border border-gray-200 rounded p-2">
                                        <div class="flex items-center space-x-2">
                                            <i class="${fileIcon}"></i>
                                            <span class="text-sm text-gray-700 truncate">${file.original_name}</span>
                                            <span class="text-xs text-gray-500">(${formatFileSize(file.file_size)})</span>
                                        </div>
                                        <div class="flex space-x-1">
                                            ${(['pdf', 'jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) ?
                                                `<button onclick="previewFile('${file.stored_name}')" class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1 rounded hover:bg-blue-50" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </button>` : ''
                                            }
                                            <button onclick="downloadFile('${file.stored_name}')" class="text-green-600 hover:text-green-800 text-xs px-2 py-1 rounded hover:bg-green-50" title="Download">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }

            itemDiv.innerHTML = [
                '<div class="flex justify-between items-start mb-2">',
                    '<h5 class="font-medium text-gray-800">' + (item.nama_barang || item.nama || 'Nama barang tidak tersedia') + '</h5>',
                    '<span class="text-lg font-bold text-red-600">' + formatRupiah(item.harga_total || (item.jumlah * item.harga_satuan)) + '</span>',
                '</div>',
                '<div class="grid grid-cols-3 gap-4 text-sm text-gray-600">',
                    '<div>',
                        '<span class="font-medium">Qty:</span> ' + (item.jumlah || item.qty || 0),
                    '</div>',
                    '<div>',
                        '<span class="font-medium">Satuan:</span> ' + (item.satuan || '-'),
                    '</div>',
                    '<div>',
                        '<span class="font-medium">Harga Satuan:</span> ' + formatRupiah(item.harga_satuan || 0),
                    '</div>',
                '</div>',
                '<div class="mt-2 text-sm text-gray-600">',
                    '<span class="font-medium">Spesifikasi:</span> ' + (item.spesifikasi || 'Tidak ada spesifikasi'),
                '</div>',
                filesHtml
            ].join('');
            daftarBarangContainer.appendChild(itemDiv);
        });
    } else if (daftarBarangContainer) {
        daftarBarangContainer.innerHTML = '<p class="text-gray-500 text-sm">Tidak ada data barang</p>';
    }

    // Handle catatan
    const catatanElement = document.getElementById('detailCatatan');
    const catatanSection = document.getElementById('detailCatatanSection');
    if (formattedData.catatan && formattedData.catatan.trim() !== '') {
        if (catatanElement) catatanElement.textContent = formattedData.catatan;
        if (catatanSection) catatanSection.style.display = 'block';
    } else {
        if (catatanSection) catatanSection.style.display = 'none';
    }

    // Load documents for this project
    loadDetailDocuments(id);

    // Show modal
    openModal('modalDetailProyek');
}

// Helper function untuk capitalize first letter
function ucfirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

// Helper function untuk class styling status
function getStatusClass(status) {
    switch (status.toLowerCase()) {
        case 'menunggu':
            return 'text-yellow-600';
        case 'penawaran':
            return 'text-blue-600';
        case 'pembayaran':
            return 'text-purple-600';
        case 'pengiriman':
            return 'text-indigo-600';
        case 'selesai':
            return 'text-green-600';
        case 'gagal':
            return 'text-red-600';
        default:
            return 'text-gray-600';
    }
}

// Function to create penawaran (redirect to penawaran page)
function buatPenawaran(id) {
    // Check user role access
    @if(!(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing'))
        alert('Tidak memiliki akses untuk membuat penawaran. Hanya superadmin dan PIC marketing yang dapat melakukan aksi ini.');
        return;
    @endif

    console.log('buatPenawaran called with ID:', id);

    const data = potensiData.find(p => p.id == id);

    if (!data) {
        console.error('Data potensi tidak ditemukan dengan ID:', id);
        alert('Data potensi tidak ditemukan!');
        return;
    }

    // Redirect to penawaran detail page
    window.location.href = `/marketing/penawaran/${id}`;
}

// Function to edit potensi
function editProyek(id) {
    // Check user role access
    @if(!(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing'))
        alert('Tidak memiliki akses untuk mengedit potensi. Hanya superadmin dan PIC marketing yang dapat melakukan aksi ini.');
        return;
    @endif

    console.log('editProyek called with ID:', id);

    const data = potensiData.find(p => p.id == id);

    if (!data) {
        console.error('Data potensi tidak ditemukan dengan ID:', id);
        alert('Data potensi tidak ditemukan!');
        return;
    }

    console.log('Data found for edit:', data);

    // Format data untuk edit modal
    const editData = {
        id: data.id,
        kode: data.kode,
        nama_proyek: data.nama_proyek,
        kabupaten: data.kabupaten,
        kabupaten_kota: data.kabupaten, // Mapping field
        instansi: data.instansi,
        nama_instansi: data.instansi, // Mapping field
        jenis_pengadaan: data.jenis_pengadaan,
        tanggal: data.tanggal,
        admin_marketing: data.admin_marketing,
        admin_purchasing: data.admin_purchasing,
        id_admin_marketing: data.id_admin_marketing,
        id_admin_purchasing: data.id_admin_purchasing,
        catatan: data.catatan,
        potensi: data.potensi,
        tahun_potensi: data.tahun_potensi,
        status: data.status,
        total_nilai: data.total_nilai,
        spesifikasi: data.spesifikasi,
        jumlah: data.jumlah,
        satuan: data.satuan,
        harga_satuan: data.harga_satuan,
        daftar_barang: data.daftar_barang || []
    };

    // Load data into edit form
    setTimeout(() => {
        // Set ID proyek untuk form submission
        const editIdField = document.getElementById('editId');
        if (editIdField) editIdField.value = editData.id;

        // Set field values dengan mapping yang benar
        const setFieldValue = (id, value) => {
            const field = document.getElementById(id);
            if (field) {
                field.value = value || '';
            }
        };

        setFieldValue('editIdProyek', editData.kode);
        setFieldValue('editTanggal', editData.tanggal);
        setFieldValue('editKabupatenKota', editData.kabupaten);
        setFieldValue('editNamaInstansi', editData.instansi);
        setFieldValue('editNamaProyek', editData.nama_proyek);
        setFieldValue('editJenisPengadaan', editData.jenis_pengadaan);
        setFieldValue('editAdminMarketing', editData.admin_marketing);
        setFieldValue('editAdminPurchasing', editData.admin_purchasing);
        setFieldValue('editStatus', editData.status);
        setFieldValue('editCatatan', editData.catatan);
        setFieldValue('editTahunPotensi', editData.tahun_potensi);

        // Handle potensi buttons
        if (typeof togglePotensiEdit === 'function') {
            togglePotensiEdit(editData.potensi);
        }

        // Load data barang dan informasi lengkap menggunakan fungsi dari edit modal
        if (typeof loadEditData === 'function') {
            console.log('Loading edit data with items:', editData.daftar_barang);
            loadEditData(editData);
        } else {
            console.error('loadEditData function not found');
        }

        console.log('Edit form populated successfully');
    }, 100);

    // Show modal
    openModal('modalEditProyek');
}

// Function to delete potensi
function deleteProyek(id) {
    // Check user role access
    @if(!(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing'))
        alert('Tidak memiliki akses untuk menghapus potensi. Hanya superadmin dan PIC marketing yang dapat melakukan aksi ini.');
        return;
    @endif

    console.log('deleteProyek called with ID:', id);

    const data = potensiData.find(p => p.id == id);

    if (!data) {
        console.error('Data potensi tidak ditemukan dengan ID:', id);
        alert('Data potensi tidak ditemukan!');
        return;
    }

    console.log('Data found for delete:', data);

    // Store data globally for deletion process
    window.hapusData = {
        id: data.id,
        kode: data.kode,
        nama_proyek: data.nama_proyek,
        nama_instansi: data.instansi, // Map untuk compatibility dengan hapus modal
        instansi: data.instansi,
        kabupaten_kota: data.kabupaten, // Map untuk compatibility dengan hapus modal
        kabupaten: data.kabupaten,
        status: data.status
    };

    // Populate hapus modal
    const setElementText = (id, text) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = text || '-';
        }
    };

    setElementText('hapusKode', window.hapusData.kode);
    setElementText('hapusInstansi', window.hapusData.instansi);
    setElementText('hapusKabupaten', window.hapusData.kabupaten);

    // Set status dengan styling
    const statusElement = document.getElementById('hapusStatus');
    if (statusElement) {
        statusElement.textContent = ucfirst(window.hapusData.status);
        statusElement.className = 'text-sm font-medium ' + getStatusClass(window.hapusData.status);
    }

    // Also call loadHapusData if it exists for the hapus modal's internal functions
    if (typeof loadHapusData === 'function') {
        loadHapusData({
            id: data.id,
            kode: data.kode,
            nama_instansi: data.instansi,
            kabupaten_kota: data.kabupaten,
            status: data.status
        });
    }

    // Show modal
    openModal('modalHapusProyek');
}

// Function to change status quickly via dropdown
function changeStatusQuick(potensiId, newStatus, selectElement = null) {
    // Stop event propagation to prevent card click
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }

    if (!newStatus) return; // Jika tidak ada status yang dipilih

    const potensi = potensiData.find(p => p.id == potensiId);
    if (!potensi) {
        showErrorMessage('Data potensi tidak ditemukan!');
        if (selectElement) selectElement.value = '';
        return;
    }

    // Validasi apakah status bisa diubah
    if (!validateDropdownChange(selectElement, potensiId)) {
        if (selectElement) selectElement.value = '';
        return;
    }

    // Konfirmasi perubahan
    const statusNames = {
        'penawaran': 'Penawaran',
        'pembayaran': 'Pembayaran',
        'pengiriman': 'Pengiriman',
        'selesai': 'Selesai',
        'gagal': 'Gagal'
    };

    const confirmMessage = 'Apakah Anda yakin ingin mengubah status potensi "' + potensi.nama_proyek + '" menjadi "' + statusNames[newStatus] + '"?';
    if (!confirm(confirmMessage)) {
        // Reset dropdown ke nilai awal
        if (selectElement) selectElement.value = '';
        return;
    }

    // Show loading state
    const originalOptions = selectElement ? selectElement.innerHTML : '';
    if (selectElement) {
        selectElement.innerHTML = '<option value="">⏳ Memproses...</option>';
        selectElement.disabled = true;
        selectElement.classList.add('opacity-50');
    }

    // Update status via API
    fetch(`/marketing/proyek/${potensiId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(async response => {
        // Check if response is ok
        if (!response.ok) {
            // Try to get response text for debugging
            const responseText = await response.text();
            console.error('HTTP Error Response:', responseText);
            throw new Error(`HTTP error! status: ${response.status}. Response: ${responseText.substring(0, 200)}...`);
        }

        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // Get response text for debugging
            const responseText = await response.text();
            console.error('Non-JSON Response:', responseText);
            throw new Error('Response bukan JSON yang valid. Content-Type: ' + (contentType || 'unknown') + '. Response: ' + responseText.substring(0, 200) + '...');
        }

        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update data
            potensi.status = newStatus;

            // Update UI
            updatePotensiStatusInUI(potensiId, newStatus);

            // Reset dropdown
            if (selectElement) {
                selectElement.disabled = false;
                selectElement.classList.remove('opacity-50');
                selectElement.innerHTML = originalOptions;
                selectElement.value = '';
                selectElement.dataset.currentStatus = newStatus;
            }

            // Show success message
            showSuccessMessage('Status potensi berhasil diubah menjadi "' + statusNames[newStatus] + '"!');

            // Update statistics
            updateStatistics();

            // Refresh filter if needed
            filterAndSort();
        } else {
            throw new Error(data.message || 'Gagal mengubah status');
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
        showErrorMessage('Terjadi kesalahan saat mengubah status: ' + error.message);

        // Reset dropdown on error
        if (selectElement) {
            selectElement.disabled = false;
            selectElement.classList.remove('opacity-50');
            selectElement.innerHTML = originalOptions;
            selectElement.value = '';
        }
    });
}

// Function to show success message with animation
function showSuccessMessage(message) {
    // Create success notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    notification.innerHTML = [
        '<div class="flex items-center">',
            '<i class="fas fa-check-circle mr-2"></i>',
            '<span>' + message + '</span>',
        '</div>'
    ].join('');

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Animate out and remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Function to update potensi status in UI
function updatePotensiStatusInUI(potensiId, newStatus) {
    // Find the card element
    const cards = document.querySelectorAll('.proyek-card');
    cards.forEach(card => {
        const cardData = potensiData.find(p => p.id == potensiId);
        if (cardData) {
            const cardIndex = potensiData.indexOf(cardData);
            if (card === cards[cardIndex]) {
                // Update status badge
                const statusBadge = card.querySelector('span[class*="bg-"]');
                if (statusBadge) {
                    // Remove old classes
                    statusBadge.classList.remove('bg-green-100', 'text-green-800', 'bg-orange-100', 'text-orange-800',
                                              'bg-purple-100', 'text-purple-800', 'bg-blue-100', 'text-blue-800',
                                              'bg-yellow-100', 'text-yellow-800', 'bg-red-100', 'text-red-800');

                    // Add new classes based on status
                    switch(newStatus) {
                        case 'selesai':
                            statusBadge.classList.add('bg-green-100', 'text-green-800');
                            break;
                        case 'kontrak':
                            statusBadge.classList.add('bg-orange-100', 'text-orange-800');
                            break;
                        case 'persetujuan':
                            statusBadge.classList.add('bg-purple-100', 'text-purple-800');
                            break;
                        case 'penawaran':
                            statusBadge.classList.add('bg-blue-100', 'text-blue-800');
                            break;
                        case 'proses':
                            statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
                            break;
                        default:
                            statusBadge.classList.add('bg-red-100', 'text-red-800');
                    }

                    // Update text
                    statusBadge.textContent = ucfirst(newStatus);
                }

                // Update data attribute
                card.setAttribute('data-status', newStatus);
            }
        }
    });
}

// Function to update statistics after status change
function updateStatistics() {
    // Recalculate statistics from current data
    const totalPotensi = potensiData.length;
    const penawaranCount = potensiData.filter(p => p.status === 'penawaran').length;
    const persetujuanCount = potensiData.filter(p => p.status === 'persetujuan').length;
    const kontrakCount = potensiData.filter(p => p.status === 'kontrak').length;
    const selesaiCount = potensiData.filter(p => p.status === 'selesai').length;
    const prosesCount = potensiData.filter(p => p.status === 'proses').length;
    const gagalCount = potensiData.filter(p => p.status === 'gagal').length;

    // Update stats cards by finding them more specifically
    const statsContainer = document.querySelector('.grid.grid-cols-2.lg\\:grid-cols-6');
    if (statsContainer) {
        const statCards = statsContainer.querySelectorAll('.bg-white');

        // Update each stat card
        if (statCards[0]) { // Total
            const countElement = statCards[0].querySelector('.font-bold');
            if (countElement) countElement.textContent = totalPotensi;
        }
        if (statCards[1]) { // Penawaran
            const countElement = statCards[1].querySelector('.font-bold');
            if (countElement) countElement.textContent = penawaranCount;
        }
        if (statCards[2]) { // Persetujuan
            const countElement = statCards[2].querySelector('.font-bold');
            if (countElement) countElement.textContent = persetujuanCount;
        }
        if (statCards[3]) { // Kontrak
            const countElement = statCards[3].querySelector('.font-bold');
            if (countElement) countElement.textContent = kontrakCount;
        }
        if (statCards[4]) { // Selesai
            const countElement = statCards[4].querySelector('.font-bold');
            if (countElement) countElement.textContent = selesaiCount;
        }
        if (statCards[5]) { // Gagal
            const countElement = statCards[5].querySelector('.font-bold');
            if (countElement) countElement.textContent = gagalCount;
        }
    }

    // Add animation to updated stats
    const allCountElements = document.querySelectorAll('.grid.grid-cols-2.lg\\:grid-cols-6 .font-bold');
    allCountElements.forEach(element => {
        element.classList.add('animate-pulse');
        setTimeout(() => {
            element.classList.remove('animate-pulse');
        }, 1000);
    });
}

// Utility Functions
function formatRupiah(angka) {
    if (!angka && angka !== 0) return 'Rp 0,00';

    let number = parseFloat(angka);

    if (isNaN(number)) return 'Rp 0,00';

    // Format dengan 2 desimal
    let formatted = number.toLocaleString('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    return 'Rp ' + formatted;
}

function formatTanggal(tanggal) {
    if (!tanggal || tanggal === '-') return '-';
    try {
        const date = new Date(tanggal);
        if (isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    } catch (error) {
        console.error('Error formatting date:', error);
        return '-';
    }
}

function ucfirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Toggle potensi buttons for edit modal
function togglePotensiEdit(value) {
    const yaBtn = document.getElementById('editPotensiYa');
    const tidakBtn = document.getElementById('editPotensiTidak');
    const hiddenInput = document.getElementById('editPotensiValue');

    if (yaBtn && tidakBtn) {
        // Reset all buttons
        yaBtn.classList.remove('bg-green-500', 'text-white', 'border-green-500');
        tidakBtn.classList.remove('bg-red-500', 'text-white', 'border-red-500');
        yaBtn.classList.add('border-gray-300', 'text-gray-700');
        tidakBtn.classList.add('border-gray-300', 'text-gray-700');

        if (value === 'ya') {
            yaBtn.classList.remove('border-gray-300', 'text-gray-700');
            yaBtn.classList.add('bg-green-500', 'text-white', 'border-green-500');
            if (hiddenInput) hiddenInput.value = 'ya';
        } else if (value === 'tidak') {
            tidakBtn.classList.remove('border-gray-300', 'text-gray-700');
            tidakBtn.classList.add('bg-red-500', 'text-white', 'border-red-500');
            if (hiddenInput) hiddenInput.value = 'tidak';
        }
    }
}

// Modal Functions (Fallbacks)
if (typeof openModal === 'undefined') {
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('modal-open');
            console.log('Opened modal: ' + modalId);
        } else {
            console.error('Modal dengan ID ' + modalId + ' tidak ditemukan');
        }
    }
}

if (typeof closeModal === 'undefined') {
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('modal-open');
            console.log('Closed modal: ' + modalId);
        }
    }
}

if (typeof showSuccessModal === 'undefined') {
    function showSuccessModal(message) {
        alert(message);
    }
}

// Additional utility functions for better UX
function showErrorMessage(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    notification.innerHTML = [
        '<div class="flex items-center">',
            '<i class="fas fa-exclamation-circle mr-2"></i>',
            '<span>' + message + '</span>',
        '</div>'
    ].join('');

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

function showLoadingIndicator(element, show = true) {
    if (show) {
        element.disabled = true;
        element.classList.add('opacity-50', 'cursor-not-allowed');
        const originalText = element.textContent || element.value;
        element.dataset.originalText = originalText;

        if (element.tagName === 'SELECT') {
            element.innerHTML = '<option value="">⏳ Memproses...</option>';
        } else {
            element.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...`;
        }
    } else {
        element.disabled = false;
        element.classList.remove('opacity-50', 'cursor-not-allowed');

        if (element.dataset.originalText) {
            if (element.tagName === 'SELECT') {
                // For select elements, we need to restore options properly
                // This should be handled in the specific context
            } else {
                element.textContent = element.dataset.originalText;
            }
            delete element.dataset.originalText;
        }
    }
}

function getStatusEmoji(status) {
    const emojis = {
        'penawaran': '📋',
        'persetujuan': '✅',
        'kontrak': '📄',
        'selesai': '🎯',
        'proses': '⚡',
        'gagal': '❌'
    };
    return emojis[status] || '📋';
}

function getStatusLabel(status) {
    const labels = {
        'penawaran': 'Penawaran',
        'persetujuan': 'Persetujuan',
        'kontrak': 'Kontrak',
        'selesai': 'Selesai',
        'proses': 'Proses',
        'gagal': 'Gagal'
    };
    return labels[status] || status;
}

// Helper function untuk format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Function untuk download file
function downloadFile(filename) {
    window.open(`/marketing/proyek/file/${filename}`, '_blank');
}

// Function untuk preview file
function previewFile(filename) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-xl max-w-4xl max-h-[90vh] w-full mx-4 overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold">Preview File: ${filename}</h3>
                <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4 h-96 overflow-auto">
                <iframe src="/marketing/proyek/file/${filename}/preview"
                        class="w-full h-full border-0"
                        onload="this.style.height=this.contentWindow.document.body.scrollHeight+'px'">
                </iframe>
            </div>
            <div class="flex justify-end space-x-2 p-4 border-t">
                <button onclick="downloadFile('${filename}')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Download
                </button>
                <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Tutup
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function validateDropdownChange(selectElement, proyekId) {
    const newStatus = selectElement.value;
    const currentStatus = selectElement.dataset.currentStatus;

    if (!newStatus || newStatus === currentStatus) {
        return false;
    }

    // Additional validation can be added here
    // For example, checking if the user has permission to change status

    return true;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM LOADED DEBUG ===');
    console.log('DOM loaded, initializing...');
    console.log('Potensi data loaded:', potensiData.length, 'items');

    // Debug: Show first item structure
    if (potensiData.length > 0) {
        console.log('Sample data structure:', {
            id: potensiData[0].id,
            kode: potensiData[0].kode,
            instansi: potensiData[0].instansi,
            kabupaten: potensiData[0].kabupaten,
            status: potensiData[0].status,
            tanggal: potensiData[0].tanggal,
            nama_proyek: potensiData[0].nama_proyek
        });
    }

    // Check if input elements exist
    console.log('Form elements check:');
    console.log('searchInput:', searchInput ? 'found' : 'NOT FOUND');
    console.log('statusFilter:', statusFilter ? 'found' : 'NOT FOUND');
    console.log('sortBy:', sortBy ? 'found' : 'NOT FOUND');

    // Initialize pagination
    currentData = [...potensiData];
    totalPages = Math.ceil(currentData.length / itemsPerPage);
    console.log('Initial pagination - Total pages:', totalPages, 'Items per page:', itemsPerPage);

    // Initialize display
    displayResults();
    updatePaginationInfo();
    renderPagination();

    // Add event listeners for modal close buttons
    document.querySelectorAll('[onclick*="closeModal"]').forEach(button => {
        console.log('Found modal close button');
    });

    console.log('Initialization complete');
    console.log('=== END DOM LOADED DEBUG ===');
});
</script>

@endsection
