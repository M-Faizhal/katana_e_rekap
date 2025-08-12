@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-2xl p-8 mb-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Manajemen Proyek</h1>
            <p class="text-red-100 text-lg">Kelola dan pantau semua penawaran proyek Anda</p>
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-handshake text-6xl "></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-red-100 mr-4">
                <i class="fas fa-file-alt text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Total Penawaran</h3>
                <p class="text-2xl font-bold text-red-600">24</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-green-100 mr-4">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Diterima</h3>
                <p class="text-2xl font-bold text-green-600">18</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-yellow-100 mr-4">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Pending</h3>
                <p class="text-2xl font-bold text-yellow-600">4</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-red-100 mr-4">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Ditolak</h3>
                <p class="text-2xl font-bold text-red-600">2</p>
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
                <h2 class="text-2xl font-bold text-gray-800">Daftar Penawaran Proyek</h2>
                <p class="text-gray-600 mt-1">Kelola semua penawaran dan proposal proyek</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Cari nama instansi atau kabupaten/kota..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
            </div>
            <div class="flex gap-3">
                <select class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option>Semua Status</option>
                    <option>Pending</option>
                    <option>Diterima</option>
                    <option>Ditolak</option>
                </select>
                <select class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option>Urutkan</option>
                    <option>Terbaru</option>
                    <option>Deadline</option>
                    <option>Kabupaten</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Cards Layout -->
    <div class="p-6">
        <div class="grid grid-cols-1 gap-6">
            <!-- Card 1 -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <span class="text-red-600 font-bold text-lg">1</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Sistem Informasi Manajemen</h3>
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                Diterima
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="viewDetail(1)" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPenawaran(1)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePenawaran(1)" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal</p>
                        <p class="font-medium text-gray-800">15 Sep 2024</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kabupaten/Kota</p>
                        <p class="font-medium text-gray-800">Jakarta Pusat</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Instansi</p>
                        <p class="font-medium text-gray-800">Dinas Pendidikan DKI</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Deadline</p>
                        <p class="font-medium text-red-600">30 Sep 2024</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-red-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Andi Prasetyo</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Purchasing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Sari Wijaya</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <span class="text-red-600 font-bold text-lg">2</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Website Portal Layanan</h3>
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="viewDetail(2)" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPenawaran(2)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePenawaran(2)" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal</p>
                        <p class="font-medium text-gray-800">20 Sep 2024</p>
                        </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kabupaten/Kota</p>
                        <p class="font-medium text-gray-800">Bandung</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Instansi</p>
                        <p class="font-medium text-gray-800">Pemkot Bandung</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Deadline</p>
                        <p class="font-medium text-red-600">05 Okt 2024</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-red-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Budi Santoso</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Purchasing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Maya Indah</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <span class="text-red-600 font-bold text-lg">3</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Aplikasi Mobile E-Government</h3>
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                Expired
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="viewDetail(3)" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPenawaran(3)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePenawaran(3)" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal</p>
                        <p class="font-medium text-gray-800">25 Agt 2024</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kabupaten/Kota</p>
                        <p class="font-medium text-gray-800">Surabaya</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Instansi</p>
                        <p class="font-medium text-gray-800">Pemkot Surabaya</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Deadline</p>
                        <p class="font-medium text-red-600">15 Sep 2024</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-red-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Dewi Lestari</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Purchasing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Roni Hidayat</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <span class="text-red-600 font-bold text-lg">4</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Dashboard Analytics Daerah</h3>
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                Review
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="viewDetail(4)" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPenawaran(4)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePenawaran(4)" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal</p>
                        <p class="font-medium text-gray-800">10 Okt 2024</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kabupaten/Kota</p>
                        <p class="font-medium text-gray-800">Yogyakarta</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Instansi</p>
                        <p class="font-medium text-gray-800">Pemda DIY</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Deadline</p>
                        <p class="font-medium text-red-600">25 Okt 2024</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-red-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Fajar Ramadhan</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Purchasing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Lisa Permata</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <span class="text-red-600 font-bold text-lg">5</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Sistem Inventory Aset</h3>
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                Diterima
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="viewDetail(5)" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPenawaran(5)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePenawaran(5)" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal</p>
                        <p class="font-medium text-gray-800">30 Sep 2024</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kabupaten/Kota</p>
                        <p class="font-medium text-gray-800">Semarang</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Instansi</p>
                        <p class="font-medium text-gray-800">BPKAD Kota Semarang</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Deadline</p>
                        <p class="font-medium text-red-600">15 Nov 2024</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-red-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Agus Setiawan</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Purchasing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">Nina Kartika</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">5</span> dari <span class="font-medium">24</span> penawaran
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>
                    <i class="fas fa-chevron-left mr-1"></i>Previous
                </button>
                <button class="px-3 py-2 text-sm font-medium text-white bg-red-600 border border-red-600 rounded-lg">1</button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Next<i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<button onclick="openModal('modalTambahPenawaran')" class="fixed bottom-16 right-16 bg-red-600 text-white w-16 h-16 rounded-full shadow-2xl hover:bg-red-700 hover:scale-110 transform transition-all duration-200 flex items-center justify-center group z-50">
    <i class="fas fa-plus text-xl group-hover:rotate-180 transition-transform duration-300"></i>
    <span class="absolute right-full mr-3 bg-gray-800 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
        Tambah Penawaran
    </span>
</button>

<!-- Include Modal Components -->
@include('pages.marketing.penawaran-components.tambah')
@include('pages.marketing.penawaran-components.edit')
@include('pages.marketing.penawaran-components.detail')
@include('pages.marketing.penawaran-components.hapus')
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
    padding: 1rem;
}

/* Ensure modal content doesn't exceed viewport */
.modal-content {
    max-height: calc(100vh - 2rem);
    overflow-y: auto;
    width: 100%;
}

/* Smooth scrollbar for modal content */
.modal-content::-webkit-scrollbar {
    width: 6px;
}


/* Prevent body scroll when modal is open */
.modal-open {
    overflow: hidden;
}

/* Potensi button styling */
.potensi-btn, .potensi-btn-edit {
    transition: all 0.2s ease-in-out;
}

.potensi-btn:hover, .potensi-btn-edit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Responsive modal adjustments */
@media (max-width: 768px) {
    .modal-container {
        padding: 0.5rem;
    }
    
    .modal-content {
        max-height: 100vh;
        border-radius: 0;
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
        transform: scale(0.9);
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
        transform: scale(0.9);
    }
}
</style>

<script>
// Function to view detail penawaran
function viewDetail(id) {
    // Sample data - replace with actual data from backend
    const sampleData = {
        1: {
            id: 1,
            kode: 'PNW-2024-001',
            instansi: 'Dinas Pendidikan DKI',
            kabupaten: 'Jakarta Pusat',
            jenis_pengadaan: 'Pelelangan Umum',
            tanggal: '15 Sep 2024',
            deadline: '30 Sep 2024',
            status: 'Diterima',
            admin_marketing: 'Budi Santoso',
            admin_purchasing: 'Sari Indah',
            nilai_penawaran: 'Rp 850.000.000',
            catatan: 'Penawaran sistem informasi manajemen pendidikan',
            potensi: 'Ya',
            tahun_potensi: 2024
        },
        2: {
            id: 2,
            kode: 'PNW-2024-002',
            instansi: 'RSUD Kota Bogor',
            kabupaten: 'Bogor',
            jenis_pengadaan: 'Penunjukan Langsung',
            tanggal: '20 Sep 2024',
            deadline: '05 Oct 2024',
            status: 'Pending',
            admin_marketing: 'Andi Pratama',
            admin_purchasing: 'Maya Sari',
            nilai_penawaran: 'Rp 650.000.000',
            catatan: 'Sistem informasi rumah sakit',
            potensi: 'Tidak',
            tahun_potensi: 2025
        },
        3: {
            id: 3,
            kode: 'PNW-2024-003',
            instansi: 'Dinas Kominfo Depok',
            kabupaten: 'Depok',
            jenis_pengadaan: 'Tender',
            tanggal: '25 Sep 2024',
            deadline: '10 Oct 2024',
            status: 'Pending',
            admin_marketing: 'Rini Wahyuni',
            admin_purchasing: 'Agus Setiawan',
            nilai_penawaran: 'Rp 450.000.000',
            catatan: 'Portal informasi publik',
            potensi: 'Ya',
            tahun_potensi: 2024
        },
        4: {
            id: 4,
            kode: 'PNW-2024-004',
            instansi: 'Bappeda Tangerang',
            kabupaten: 'Tangerang',
            jenis_pengadaan: 'Pelelangan Umum',
            tanggal: '28 Sep 2024',
            deadline: '15 Oct 2024',
            status: 'Ditolak',
            admin_marketing: 'Dedi Kurniawan',
            admin_purchasing: 'Nina Kartika',
            nilai_penawaran: 'Rp 750.000.000',
            catatan: 'Sistem perencanaan pembangunan',
            potensi: 'Tidak',
            tahun_potensi: 2025
        },
        5: {
            id: 5,
            kode: 'PNW-2024-005',
            instansi: 'Pemkot Bekasi',
            kabupaten: 'Bekasi',
            jenis_pengadaan: 'Pemilihan Langsung',
            tanggal: '01 Oct 2024',
            deadline: '15 Nov 2024',
            status: 'Pending',
            admin_marketing: 'Agus Setiawan',
            admin_purchasing: 'Nina Kartika',
            nilai_penawaran: 'Rp 920.000.000',
            catatan: 'Sistem administrasi kependudukan',
            potensi: 'Ya',
            tahun_potensi: 2024
        }
    };

    const data = sampleData[id];
    if (data) {
        // Populate detail modal
        document.getElementById('detailKode').textContent = data.kode;
        document.getElementById('detailNamaInstansi').textContent = data.instansi;
        document.getElementById('detailKabupatenKota').textContent = data.kabupaten;
        document.getElementById('detailJenisPengadaan').textContent = data.jenis_pengadaan;
        document.getElementById('detailTanggal').textContent = data.tanggal;
        document.getElementById('detailDeadline').textContent = data.deadline;
        
        // Update status badge
        const statusBadge = document.getElementById('detailStatusBadge');
        statusBadge.textContent = data.status;
        // Set status badge color based on status
        statusBadge.className = 'inline-flex px-4 py-2 text-sm font-medium rounded-full';
        if (data.status === 'Diterima') {
            statusBadge.classList.add('bg-green-100', 'text-green-800');
        } else if (data.status === 'Pending') {
            statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
        } else if (data.status === 'Ditolak') {
            statusBadge.classList.add('bg-red-100', 'text-red-800');
        }
        
        document.getElementById('detailAdminMarketing').textContent = data.admin_marketing;
        document.getElementById('detailAdminPurchasing').textContent = data.admin_purchasing;
        document.getElementById('detailTotalKeseluruhan').textContent = data.nilai_penawaran;
        
        // Update new fields
        document.getElementById('detailPotensi').textContent = data.potensi || '-';
        document.getElementById('detailTahunPotensi').textContent = data.tahun_potensi || '-';
        
        // Show catatan section if exists
        if (data.catatan && data.catatan !== '-') {
            document.getElementById('detailCatatan').textContent = data.catatan;
            document.getElementById('detailCatatanSection').style.display = 'block';
        } else {
            document.getElementById('detailCatatanSection').style.display = 'none';
        }
        
        // Show modal
        openModal('modalDetailPenawaran');
    }
}

// Function to edit penawaran
function editPenawaran(id) {
    // Sample data - replace with actual data from backend
    const sampleData = {
        1: {
            id: 1,
            kode: 'PNW-2024-001',
            kabupaten_kota: 'Jakarta Pusat',
            nama_instansi: 'Dinas Pendidikan DKI',
            jenis_pengadaan: 'Pelelangan Umum',
            deadline_penawaran: '2024-09-30',
            admin_purchasing: 'Sari Indah',
            catatan: 'Penawaran sistem informasi manajemen pendidikan',
            potensi: 'ya',
            tahun_potensi: 2024,
            status: 'Diterima'
        },
        2: {
            id: 2,
            kode: 'PNW-2024-002',
            kabupaten_kota: 'Bogor',
            nama_instansi: 'RSUD Kota Bogor',
            jenis_pengadaan: 'Penunjukan Langsung',
            deadline_penawaran: '2024-10-05',
            admin_purchasing: 'Maya Sari',
            catatan: 'Sistem informasi rumah sakit',
            potensi: 'tidak',
            tahun_potensi: 2025,
            status: 'Pending'
        },
        3: {
            id: 3,
            kode: 'PNW-2024-003',
            kabupaten_kota: 'Depok',
            nama_instansi: 'Dinas Kominfo Depok',
            jenis_pengadaan: 'Tender',
            deadline_penawaran: '2024-10-10',
            admin_purchasing: 'Agus Setiawan',
            catatan: 'Portal informasi publik',
            potensi: 'ya',
            tahun_potensi: 2024,
            status: 'Pending'
        },
        4: {
            id: 4,
            kode: 'PNW-2024-004',
            kabupaten_kota: 'Tangerang',
            nama_instansi: 'Bappeda Tangerang',
            jenis_pengadaan: 'Pelelangan Umum',
            deadline_penawaran: '2024-10-15',
            admin_purchasing: 'Nina Kartika',
            catatan: 'Sistem perencanaan pembangunan',
            potensi: 'tidak',
            tahun_potensi: 2025,
            status: 'Ditolak'
        },
        5: {
            id: 5,
            kode: 'PNW-2024-005',
            kabupaten_kota: 'Bekasi',
            nama_instansi: 'Pemkot Bekasi',
            jenis_pengadaan: 'Pemilihan Langsung',
            deadline_penawaran: '2024-11-15',
            admin_purchasing: 'Nina Kartika',
            catatan: 'Sistem administrasi kependudukan',
            potensi: 'ya',
            tahun_potensi: 2024,
            status: 'Pending'
        }
    };

    const data = sampleData[id];
    if (data) {
        // Create data object for loadEditData function
        const editData = {
            id: data.id,
            kode: data.kode,
            kabupaten_kota: data.kabupaten_kota,
            nama_instansi: data.nama_instansi,
            jenis_pengadaan: data.jenis_pengadaan,
            deadline: data.deadline_penawaran,
            admin_purchasing: data.admin_purchasing,
            catatan: data.catatan,
            potensi: data.potensi,
            tahun_potensi: data.tahun_potensi,
            status: data.status
        };
        
        // Load data into edit form using the loadEditData function
        loadEditData(editData);
        
        // Show modal
        openModal('modalEditPenawaran');
    }
}

// Function to delete penawaran
function deletePenawaran(id) {
    // Sample data - replace with actual data from backend
    const sampleData = {
        1: {
            id: 1,
            kode: 'PNW-2024-001',
            instansi: 'Dinas Pendidikan DKI',
            kabupaten: 'Jakarta Pusat',
            status: 'Diterima'
        },
        2: {
            id: 2,
            kode: 'PNW-2024-002',
            instansi: 'RSUD Kota Bogor',
            kabupaten: 'Bogor',
            status: 'Pending'
        },
        3: {
            id: 3,
            kode: 'PNW-2024-003',
            instansi: 'Dinas Kominfo Depok',
            kabupaten: 'Depok',
            status: 'Pending'
        },
        4: {
            id: 4,
            kode: 'PNW-2024-004',
            instansi: 'Bappeda Tangerang',
            kabupaten: 'Tangerang',
            status: 'Ditolak'
        },
        5: {
            id: 5,
            kode: 'PNW-2024-005',
            instansi: 'Pemkot Bekasi',
            kabupaten: 'Bekasi',
            status: 'Pending'
        }
    };

    const data = sampleData[id];
    if (data) {
        // Store data globally for deletion process
        window.hapusData = data;
        
        // Populate hapus modal
        document.getElementById('hapusKode').textContent = data.kode;
        document.getElementById('hapusInstansi').textContent = data.instansi;
        document.getElementById('hapusKabupaten').textContent = data.kabupaten;
        document.getElementById('hapusStatus').textContent = data.status;
        
        // Show modal
        openModal('modalHapusPenawaran');
    }
}

// Function to load edit data (called from edit modal)
function loadEditData(data) {
    // This function is defined in the edit modal component
    // We need to ensure it's called after the modal is loaded
    setTimeout(() => {
        if (typeof window.loadEditData === 'function') {
            window.loadEditData(data);
        } else {
            // Fallback: directly populate fields
            document.getElementById('editId').value = data.id;
            document.getElementById('editKode').value = data.kode;
            document.getElementById('editKabupatenKota').value = data.kabupaten_kota;
            document.getElementById('editNamaInstansi').value = data.nama_instansi;
            document.getElementById('editJenisPengadaan').value = data.jenis_pengadaan;
            document.getElementById('editDeadline').value = data.deadline;
            document.getElementById('editAdminPurchasing').value = data.admin_purchasing;
            document.getElementById('editCatatan').value = data.catatan;
            
            // Populate new fields
            if (data.potensi) {
                togglePotensiEdit(data.potensi);
            }
            document.getElementById('editTahunPotensi').value = data.tahun_potensi || '';
            document.getElementById('editStatus').value = data.status || '';
        }
    }, 100);
}

// Toggle potensi buttons for edit modal
function togglePotensiEdit(value) {
    const yaBtn = document.getElementById('editPotensiYa');
    const tidakBtn = document.getElementById('editPotensiTidak');
    const hiddenInput = document.getElementById('editPotensiValue');
    
    // Reset all buttons
    yaBtn.classList.remove('bg-green-500', 'text-white', 'border-green-500');
    tidakBtn.classList.remove('bg-red-500', 'text-white', 'border-red-500');
    yaBtn.classList.add('border-gray-300', 'text-gray-700');
    tidakBtn.classList.add('border-gray-300', 'text-gray-700');
    
    if (value === 'ya') {
        yaBtn.classList.remove('border-gray-300', 'text-gray-700');
        yaBtn.classList.add('bg-green-500', 'text-white', 'border-green-500');
        hiddenInput.value = 'ya';
    } else if (value === 'tidak') {
        tidakBtn.classList.remove('border-gray-300', 'text-gray-700');
        tidakBtn.classList.add('bg-red-500', 'text-white', 'border-red-500');
        hiddenInput.value = 'tidak';
    }
}

// Function to open modal (if not already defined)
if (typeof openModal === 'undefined') {
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }
}

// Function to close modal (if not already defined)
if (typeof closeModal === 'undefined') {
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
}
</script>

@endsection