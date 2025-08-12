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
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">25</p>
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
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">15</p>
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
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">8</p>
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
                <p class="text-2xl font-bold text-gray-900">12</p>
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
            <div class="flex space-x-3">
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Cari berdasarkan nama proyek, instansi, atau vendor..." 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <select class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="sukses">Sukses</option>
                </select>
                <select class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Semua Tahun</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>
        </div>
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
        <div class="space-y-4">
            <!-- Sample Item 1 -->
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <div class="col-span-3">
                        <h3 class="font-semibold text-gray-900">Sistem Informasi Sekolah</h3>
                        <p class="text-sm text-gray-600">PNW-20240810-143052</p>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1"></i>Deadline: 30 Sep 2024
                        </div>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">Dinas Pendidikan DKI</p>
                        <p class="text-sm text-gray-600">Jakarta Pusat</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PT. Teknologi Maju</p>
                        <p class="text-sm text-gray-600">VND001</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-semibold text-green-600">Rp 850.000.000</p>
                        <p class="text-sm text-gray-600">Pelelangan Umum</p>
                    </div>
                    <div class="col-span-1">
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </div>
                    <div class="col-span-2 flex space-x-2">
                        <button onclick="viewDetailPotensi(1)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPotensi(1)" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sample Item 2 -->
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <div class="col-span-3">
                        <h3 class="font-semibold text-gray-900">Aplikasi E-Learning</h3>
                        <p class="text-sm text-gray-600">PNW-20240715-120034</p>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1"></i>Deadline: 15 Oct 2024
                        </div>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">Universitas Negeri</p>
                        <p class="text-sm text-gray-600">Bandung</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">CV. Mandiri Sejahtera</p>
                        <p class="text-sm text-gray-600">VND002</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-semibold text-green-600">Rp 650.000.000</p>
                        <p class="text-sm text-gray-600">Penunjukan Langsung</p>
                    </div>
                    <div class="col-span-1">
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Sukses
                        </span>
                    </div>
                    <div class="col-span-2 flex space-x-2">
                        <button onclick="viewDetailPotensi(2)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPotensi(2)" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sample Item 3 -->
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <div class="col-span-3">
                        <h3 class="font-semibold text-gray-900">Portal Website Pemerintah</h3>
                        <p class="text-sm text-gray-600">PNW-20240620-095021</p>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1"></i>Deadline: 20 Nov 2024
                        </div>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">Pemkot Surabaya</p>
                        <p class="text-sm text-gray-600">Surabaya</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PT. Global Industri</p>
                        <p class="text-sm text-gray-600">VND004</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-semibold text-green-600">Rp 1.200.000.000</p>
                        <p class="text-sm text-gray-600">Pelelangan Umum</p>
                    </div>
                    <div class="col-span-1">
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </div>
                    <div class="col-span-2 flex space-x-2">
                        <button onclick="viewDetailPotensi(3)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPotensi(3)" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">10</span> dari <span class="font-medium">25</span> hasil
            </div>
            <div class="flex space-x-2">
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

<!-- Floating Action Button -->
<button onclick="openModal('modalTambahPotensi')" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 lg:bottom-16 lg:right-16 bg-red-600 text-white w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full shadow-2xl hover:bg-red-700 hover:scale-110 transform transition-all duration-200 flex items-center justify-center group z-50">
    <i class="fas fa-plus text-sm sm:text-base lg:text-lg group-hover:rotate-180 transition-transform duration-300"></i>
    <div class="absolute bottom-full right-0 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
        Tambah Potensi
        <div class="absolute top-full right-3 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
    </div>
</button>

<!-- Include Modal Components -->
@include('pages.marketing.potensi-components.tambah')
@include('pages.marketing.potensi-components.edit')
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
// Sample data for potensi (projects with potensi flag and vendors)
const potensiData = {
    1: {
        id: 1,
        kode_proyek: 'PNW-20240810-143052',
        nama_proyek: 'Sistem Informasi Sekolah',
        instansi: 'Dinas Pendidikan DKI',
        kabupaten_kota: 'Jakarta Pusat',
        jenis_pengadaan: 'Pelelangan Umum',
        nilai_proyek: 850000000,
        deadline: '30 September 2024',
        vendor_id: 'VND001',
        vendor_nama: 'PT. Teknologi Maju',
        status: 'pending',
        tanggal_assign: '2024-08-10',
        catatan: 'Proyek sistem informasi manajemen sekolah dengan fitur lengkap'
    },
    2: {
        id: 2,
        kode_proyek: 'PNW-20240715-120034',
        nama_proyek: 'Aplikasi E-Learning',
        instansi: 'Universitas Negeri',
        kabupaten_kota: 'Bandung',
        jenis_pengadaan: 'Penunjukan Langsung',
        nilai_proyek: 650000000,
        deadline: '15 Oktober 2024',
        vendor_id: 'VND002',
        vendor_nama: 'CV. Mandiri Sejahtera',
        status: 'sukses',
        tanggal_assign: '2024-07-15',
        catatan: 'Platform e-learning untuk universitas'
    },
    3: {
        id: 3,
        kode_proyek: 'PNW-20240620-095021',
        nama_proyek: 'Portal Website Pemerintah',
        instansi: 'Pemkot Surabaya',
        kabupaten_kota: 'Surabaya',
        jenis_pengadaan: 'Pelelangan Umum',
        nilai_proyek: 1200000000,
        deadline: '20 November 2024',
        vendor_id: 'VND004',
        vendor_nama: 'PT. Global Industri',
        status: 'pending',
        tanggal_assign: '2024-06-20',
        catatan: 'Website resmi pemerintah kota dengan portal layanan publik'
    }
};

// Available vendors data
const vendorData = [
    { id: 'VND001', nama: 'PT. Teknologi Maju', jenis: 'Perusahaan', status: 'Aktif' },
    { id: 'VND002', nama: 'CV. Mandiri Sejahtera', jenis: 'Perorangan', status: 'Aktif' },
    { id: 'VND003', nama: 'Koperasi Sukses Bersama', jenis: 'Koperasi', status: 'Tidak Aktif' },
    { id: 'VND004', nama: 'PT. Global Industri', jenis: 'Perusahaan', status: 'Aktif' },
    { id: 'VND005', nama: 'Budi Santoso', jenis: 'Perorangan', status: 'Aktif' }
];

// Projects with potensi flag (sample data)
const proyekPotensiData = [
    {
        kode: 'PNW-20240810-143052',
        nama: 'Sistem Informasi Sekolah',
        instansi: 'Dinas Pendidikan DKI',
        kabupaten_kota: 'Jakarta Pusat',
        jenis_pengadaan: 'Pelelangan Umum',
        nilai_proyek: 850000000,
        deadline: '30 September 2024',
        potensi: 'ya'
    },
    {
        kode: 'PNW-20240715-120034',
        nama: 'Aplikasi E-Learning',
        instansi: 'Universitas Negeri',
        kabupaten_kota: 'Bandung',
        jenis_pengadaan: 'Penunjukan Langsung',
        nilai_proyek: 650000000,
        deadline: '15 Oktober 2024',
        potensi: 'ya'
    }
];

// Function to view detail potensi
function viewDetailPotensi(id) {
    const data = potensiData[id];
    if (data) {
        // Populate detail modal with data
        loadDetailPotensiData(data);
        openModal('modalDetailPotensi');
    }
}

// Function to edit potensi
function editPotensi(id) {
    const data = potensiData[id];
    if (data) {
        // Populate edit modal with data
        loadEditPotensiData(data);
        openModal('modalEditPotensi');
    }
}

// Function to load detail data
function loadDetailPotensiData(data) {
    // This function will be implemented in detail modal component
    if (typeof loadPotensiDetailData === 'function') {
        loadPotensiDetailData(data);
    }
}

// Function to load edit data
function loadEditPotensiData(data) {
    // This function will be implemented in edit modal component
    if (typeof loadPotensiEditData === 'function') {
        loadPotensiEditData(data);
    }
}

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
    // Search functionality
    const searchInput = document.querySelector('input[placeholder*="Cari"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            console.log('Searching:', this.value);
            // Implement search logic here
        });
    }
    
    // Filter functionality
    const statusFilter = document.querySelector('select option[value="pending"]').parentElement;
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            console.log('Filter by status:', this.value);
            // Implement filter logic here
        });
    }
});
</script>

@endsection