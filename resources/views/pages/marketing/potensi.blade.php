@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Potensi Proyek</h1>
            <p class="text-red-100 text-sm sm:text-base">Kelola data potensi proyek dan pencocokan vendor</p>
        </div>
        <div class="hidden sm:block lg:block">
            <div class="flex items-center space-x-4">
              <i class="fas fa-chart-line text-3xl sm:text-4xl lg:text-5xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-full bg-blue-100 text-blue-600 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-list-ul text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Potensi</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">{{ $totalPotensi }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-full bg-yellow-100 text-yellow-600 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-clock text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Pending</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-full bg-green-100 text-green-600 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-check-circle text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Sukses</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">{{ $suksesCount }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                <i class="fas fa-building text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Vendor Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ $vendorAktifCount }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-20">
    <!-- Header -->
    <div class="p-8 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Daftar Potensi Proyek</h2>
                <p class="text-gray-600 mt-1">Kelola pencocokan proyek dengan vendor</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('marketing.potensi') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari berdasarkan nama proyek, instansi, atau vendor..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <select name="tahun" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ $tahunFilter == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
                <select name="admin_marketing" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Semua Admin Marketing</option>
                    @foreach($adminMarketingList as $admin)
                        <option value="{{ $admin->id_user }}" {{ $adminMarketingFilter == $admin->id_user ? 'selected' : '' }}>{{ $admin->nama }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="sukses" {{ request('status') == 'sukses' ? 'selected' : '' }}>Sukses</option>
                </select>
                <button type="submit" class="px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- List Layout -->
    <div class="p-6">
        <!-- Table Header -->
        <div class="hidden md:grid md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-lg mb-4 text-sm font-semibold text-gray-700">
            <div class="col-span-3">Proyek</div>
            <div class="col-span-2">Instansi</div>
            <div class="col-span-2">Vendor</div>
            <div class="col-span-2">Nilai Proyek</div>
            <div class="col-span-1">Status</div>
            <div class="col-span-2">Aksi</div>
        </div>

        <!-- List Items -->
        <div class="space-y-6">
            @forelse($potensiData as $potensi)
            <!-- Potensi Item {{ $potensi['id'] }} -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200 hover:transform hover:scale-[1.01]">
                <!-- Header Card dengan Icon dan Status -->
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-chart-line text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $potensi['nama_proyek'] }}</h3>
                            <p class="text-sm text-gray-600 font-medium">{{ $potensi['kode_proyek'] }}</p>
                            <div class="flex items-center text-xs text-gray-500 mt-2">
                                <i class="fas fa-calendar mr-2"></i>
                                <span>Deadline: {{ $potensi['deadline'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-0">
                        <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full shadow-sm
                            {{ $potensi['status'] === 'sukses' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200' }}">
                            @if($potensi['status'] === 'sukses')
                                <i class="fas fa-check-circle mr-2"></i>Sukses
                            @else
                                <i class="fas fa-clock mr-2"></i>Pending
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Informasi Instansi -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-building text-blue-600 mr-2"></i>
                            Instansi
                        </h4>
                        <p class="font-semibold text-gray-900 mb-1">{{ $potensi['instansi'] }}</p>
                        <p class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>
                            {{ $potensi['kabupaten_kota'] }}
                        </p>
                    </div>

                    <!-- Informasi Vendor -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-handshake text-purple-600 mr-2"></i>
                            Vendor
                        </h4>
                        <p class="font-semibold text-gray-900 mb-1">{{ $potensi['vendor_nama'] }}</p>
                        <p class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-tag mr-1 text-gray-400"></i>
                            {{ $potensi['vendor_id'] }} - {{ $potensi['vendor_jenis'] }}
                        </p>
                    </div>

                    <!-- Informasi Finansial -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-100">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                            Nilai Proyek
                        </h4>
                        <p class="text-xl font-bold text-green-700 mb-1">Rp {{ number_format($potensi['nilai_proyek'], 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-gavel mr-1 text-gray-400"></i>
                            {{ $potensi['jenis_pengadaan'] }}
                        </p>
                    </div>
                </div>

                <!-- Admin Info dan Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-center space-x-4 mb-3 sm:mb-0">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-user-tie text-gray-400"></i>
                            <span class="text-sm text-gray-600">Marketing: <span class="font-medium text-gray-800">{{ $potensi['admin_marketing'] }}</span></span>
                        </div>
                        <div class="text-xs text-gray-400">•</div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                            <span class="text-sm text-gray-600">{{ $potensi['tahun'] }}</span>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <button onclick="openDetailPotensiModal({{ $potensi['id'] }})"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <!-- No Results Message -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-chart-line text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada data potensi</h3>
                <p class="text-gray-500 max-w-md mx-auto">Tambahkan proyek baru untuk melihat data potensi proyek yang bisa di-assign ke vendor</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-3 sm:space-y-0">
            <div class="text-sm text-gray-700 text-center sm:text-left">
                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">{{ count($potensiData) }}</span> dari <span class="font-medium">{{ $totalPotensi }}</span> hasil
            </div>

            <!-- Mobile Pagination (Simple) -->
            <div class="flex sm:hidden items-center justify-center space-x-3">
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 min-h-[44px] flex items-center" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span class="text-sm font-medium text-gray-700 px-3 py-2">1 / 3</span>
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 min-h-[44px] flex items-center">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Desktop Pagination (Full) -->
            <div class="hidden sm:flex space-x-2">
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-3 py-2 text-sm bg-red-600 text-white rounded-lg">1</button>
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include Modal Components (Detail only) -->
@include('pages.marketing.potensi-components.detail')
@include('components.success-modal')

<!-- Include Modal Functions -->
<script src="{{ asset('js/modal-functions.js') }}"></script>

<!-- Modal Styling -->
<style>
/* Ensure modals are properly centered and responsive */
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

/* Ensure modal content doesn't exceed viewport */
.modal-content {
    max-height: calc(100vh - 1rem);
    overflow-y: auto;
    width: 100%;
    max-width: 100%;
}

@media (min-width: 640px) {
    .modal-content {
        max-height: calc(100vh - 2rem);
        max-width: 32rem; /* 512px */
    }
}

@media (min-width: 768px) {
    .modal-content {
        max-width: 42rem; /* 672px */
    }
}

@media (min-width: 1024px) {
    .modal-content {
        max-width: 48rem; /* 768px */
    }
}

/* Smooth scrollbar for modal content */
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

/* Prevent body scroll when modal is open */
.modal-open {
    overflow: hidden;
}

/* Responsive modal adjustments */
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

    /* Make modal headers sticky on mobile */
    .modal-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background: white;
        border-bottom: 1px solid #e5e7eb;
    }

    /* Adjust form spacing on mobile */
    .modal-form .space-y-4 > * + * {
        margin-top: 0.75rem;
    }

    .modal-form .space-y-6 > * + * {
        margin-top: 1rem;
    }

    /* Make inputs more touch-friendly */
    .modal-form input,
    .modal-form select,
    .modal-form textarea {
        min-height: 44px;
        font-size: 16px; /* Prevents zoom on iOS */
    }

    /* Adjust button sizes for touch */
    .modal-form button {
        min-height: 44px;
        padding: 0.75rem 1rem;
    }

    /* Grid adjustments for mobile */
    .grid.grid-cols-1.md\\:grid-cols-12 {
        display: block;
    }

    .grid.grid-cols-1.md\\:grid-cols-12 > div {
        margin-bottom: 0.5rem;
    }
}

@media (min-width: 640px) and (max-width: 1023px) {
    /* Tablet specific adjustments */
    .modal-content {
        margin: 1rem;
        border-radius: 0.75rem;
    }

    /* Slightly larger touch targets for tablets */
    .modal-form input,
    .modal-form select,
    .modal-form textarea {
        min-height: 40px;
    }

    .modal-form button {
        min-height: 40px;
    }
}

/* Animation for modal */
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

/* Hover effects for list items */
.border.border-gray-200.rounded-lg:hover {
    border-color: #e5e7eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Mobile-first modal backdrop */
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

@media (max-width: 639px) {
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.75);
    }
}
</style>

<script>
// Data from controller
const potensiData = @json($potensiData);

// Function to open modal (if not already defined)
if (typeof openModal === 'undefined') {
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('modal-open');

            // Add animation class
            const modalContent = modal.querySelector('.bg-white');
            if (modalContent) {
                modalContent.classList.add('modal-enter');
            }
        }
    }
}

// Function to close modal (if not already defined)
if (typeof closeModal === 'undefined') {
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const modalContent = modal.querySelector('.bg-white');
            if (modalContent) {
                modalContent.classList.add('modal-exit');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('modal-open');
                    modalContent.classList.remove('modal-enter', 'modal-exit');
                }, 300);
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('modal-open');
            }
        }
    }
}

// Format rupiah function
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// Search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto submit form when filter changes
    const filterSelects = document.querySelectorAll('select[name="tahun"], select[name="admin_marketing"], select[name="status"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Search on enter key
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    }
});
</script>

@endsection
