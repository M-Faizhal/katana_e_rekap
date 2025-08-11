@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-2xl p-8 mb-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Manajemen Penawaran</h1>
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

@endsection