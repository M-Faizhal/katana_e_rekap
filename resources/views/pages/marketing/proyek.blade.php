@extends('layouts.app')

@section('content')

@php
// Data dummy proyek - bisa dipindah ke controller nanti
$proyekData = [
    [
        'id' => 1,
        'kode' => 'PNW-2024-001',
        'nama_proyek' => 'Sistem Informasi Manajemen',
        'kabupaten_kota' => 'Jakarta Pusat',
        'nama_instansi' => 'Dinas Pendidikan DKI',
        'jenis_pengadaan' => 'Pelelangan Umum',
        'tanggal' => '2024-09-15',
        'deadline' => '2024-09-30',
        'status' => 'berhasil',
        'admin_marketing' => 'Andi Prasetyo',
        'admin_purchasing' => 'Sari Wijaya',
        'catatan' => 'Proyek pembangunan sistem informasi manajemen pendidikan untuk meningkatkan efisiensi administrasi.',
        'potensi' => 'ya',
        'tahun_potensi' => 2024,
        'total_nilai' => 850000000,
        'daftar_barang' => [
            ['nama' => 'Server Database', 'qty' => 2, 'satuan' => 'unit', 'harga_satuan' => 15000000, 'harga_total' => 30000000],
            ['nama' => 'Workstation', 'qty' => 10, 'satuan' => 'unit', 'harga_satuan' => 8000000, 'harga_total' => 80000000],
            ['nama' => 'Software License', 'qty' => 1, 'satuan' => 'paket', 'harga_satuan' => 50000000, 'harga_total' => 50000000],
            ['nama' => 'Training & Support', 'qty' => 1, 'satuan' => 'layanan', 'harga_satuan' => 25000000, 'harga_total' => 25000000]
        ]
    ],
    [
        'id' => 2,
        'kode' => 'PNW-2024-002',
        'nama_proyek' => 'Website Portal Layanan',
        'kabupaten_kota' => 'Bandung',
        'nama_instansi' => 'Pemkot Bandung',
        'jenis_pengadaan' => 'Penunjukan Langsung',
        'tanggal' => '2024-09-20',
        'deadline' => '2024-10-05',
        'status' => 'proses',
        'admin_marketing' => 'Budi Santoso',
        'admin_purchasing' => 'Maya Indah',
        'catatan' => 'Pengembangan portal layanan publik online untuk memudahkan akses masyarakat.',
        'potensi' => 'tidak',
        'tahun_potensi' => 2025,
        'total_nilai' => 650000000,
        'daftar_barang' => [
            ['nama' => 'Web Development', 'qty' => 1, 'satuan' => 'paket', 'harga_satuan' => 45000000, 'harga_total' => 45000000],
            ['nama' => 'Hosting & Domain', 'qty' => 1, 'satuan' => 'tahun', 'harga_satuan' => 5000000, 'harga_total' => 5000000],
            ['nama' => 'Maintenance', 'qty' => 1, 'satuan' => 'tahun', 'harga_satuan' => 12000000, 'harga_total' => 12000000]
        ]
    ],
    [
        'id' => 3,
        'kode' => 'PNW-2024-003',
        'nama_proyek' => 'Aplikasi Mobile E-Government',
        'kabupaten_kota' => 'Surabaya',
        'nama_instansi' => 'Pemkot Surabaya',
        'jenis_pengadaan' => 'Tender',
        'tanggal' => '2024-08-25',
        'deadline' => '2024-09-15',
        'status' => 'gagal',
        'admin_marketing' => 'Dewi Lestari',
        'admin_purchasing' => 'Roni Hidayat',
        'catatan' => 'Aplikasi mobile untuk layanan e-government yang terintegrasi.',
        'potensi' => 'ya',
        'tahun_potensi' => 2024,
        'total_nilai' => 720000000,
        'daftar_barang' => [
            ['nama' => 'Mobile App Development', 'qty' => 1, 'satuan' => 'paket', 'harga_satuan' => 60000000, 'harga_total' => 60000000],
            ['nama' => 'Backend API', 'qty' => 1, 'satuan' => 'paket', 'harga_satuan' => 40000000, 'harga_total' => 40000000],
            ['nama' => 'Testing & QA', 'qty' => 1, 'satuan' => 'layanan', 'harga_satuan' => 15000000, 'harga_total' => 15000000]
        ]
    ],
    [
        'id' => 4,
        'kode' => 'PNW-2024-004',
        'nama_proyek' => 'Dashboard Analytics Daerah',
        'kabupaten_kota' => 'Yogyakarta',
        'nama_instansi' => 'Pemda DIY',
        'jenis_pengadaan' => 'Pelelangan Umum',
        'tanggal' => '2024-10-10',
        'deadline' => '2024-10-25',
        'status' => 'proses',
        'admin_marketing' => 'Fajar Ramadhan',
        'admin_purchasing' => 'Lisa Permata',
        'catatan' => 'Dashboard untuk monitoring dan analisis data pembangunan daerah.',
        'potensi' => 'ya',
        'tahun_potensi' => 2024,
        'total_nilai' => 920000000,
        'daftar_barang' => [
            ['nama' => 'Dashboard System', 'qty' => 1, 'satuan' => 'paket', 'harga_satuan' => 75000000, 'harga_total' => 75000000],
            ['nama' => 'Data Integration', 'qty' => 1, 'satuan' => 'layanan', 'harga_satuan' => 35000000, 'harga_total' => 35000000],
            ['nama' => 'User Training', 'qty' => 1, 'satuan' => 'paket', 'harga_satuan' => 20000000, 'harga_total' => 20000000]
        ]
    ],
    [
        'id' => 5,
        'kode' => 'PNW-2024-005',
        'nama_proyek' => 'Sistem Inventory Aset',
        'kabupaten_kota' => 'Semarang',
        'nama_instansi' => 'BPKAD Kota Semarang',
        'jenis_pengadaan' => 'Pemilihan Langsung',
        'tanggal' => '2024-09-30',
        'deadline' => '2024-11-15',
        'status' => 'proses',
        'admin_marketing' => 'Agus Setiawan',
        'admin_purchasing' => 'Nina Kartika',
        'catatan' => 'Sistem manajemen inventory aset daerah untuk meningkatkan akuntabilitas.',
        'potensi' => 'ya',
        'tahun_potensi' => 2024,
        'total_nilai' => 980000000,
        'daftar_barang' => [
            ['nama' => 'Inventory System', 'qty' => 1, 'satuan' => 'paket', 'harga_satuan' => 85000000, 'harga_total' => 85000000],
            ['nama' => 'Barcode Scanner', 'qty' => 10, 'satuan' => 'unit', 'harga_satuan' => 2500000, 'harga_total' => 25000000],
            ['nama' => 'Installation & Setup', 'qty' => 1, 'satuan' => 'layanan', 'harga_satuan' => 15000000, 'harga_total' => 15000000]
        ]
    ],
    [
        'id' => 6,
        'kode' => 'PNW-2024-006',
        'nama_proyek' => 'Sistem Keuangan Daerah',
        'kabupaten_kota' => 'Malang',
        'nama_instansi' => 'BPKD Kota Malang',
        'jenis_pengadaan' => 'Tender',
        'tanggal' => '2024-10-15',
        'deadline' => '2024-12-01',
        'status' => 'berhasil',
        'admin_marketing' => 'Rina Sari',
        'admin_purchasing' => 'Dedi Kurniawan',
        'catatan' => 'Implementasi sistem keuangan daerah yang terintegrasi dengan sistem nasional.',
        'potensi' => 'tidak',
        'tahun_potensi' => 2025,
        'total_nilai' => 1200000000,
        'daftar_barang' => [
            ['nama' => 'Financial System', 'qty' => 1, 'satuan' => 'paket', 'harga_satuan' => 95000000, 'harga_total' => 95000000],
            ['nama' => 'Database Server', 'qty' => 2, 'satuan' => 'unit', 'harga_satuan' => 25000000, 'harga_total' => 50000000],
            ['nama' => 'Security Module', 'qty' => 1, 'satuan' => 'paket', 'harga_satuan' => 30000000, 'harga_total' => 30000000]
        ]
    ]
];

// Hitung statistik
$totalProyek = count($proyekData);
$berhasilCount = count(array_filter($proyekData, fn($p) => $p['status'] === 'berhasil'));
$prosesCount = count(array_filter($proyekData, fn($p) => $p['status'] === 'proses'));
$gagalCount = count(array_filter($proyekData, fn($p) => $p['status'] === 'gagal'));
@endphp

<!-- Header Section -->
<div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Manajemen Proyek</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan pantau semua proyek Anda</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-handshake text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-file-alt text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Proyek</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">{{ $totalProyek }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-check-circle text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Berhasil</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">{{ $berhasilCount }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-clock text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Proses</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">{{ $prosesCount }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-times-circle text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Gagal</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">{{ $gagalCount }}</p>
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
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Daftar Proyek</h2>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Kelola semua proyek dan proposal proyek</p>
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
                <select id="statusFilter" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Status</option>
                    <option value="proses">Proses</option>
                    <option value="berhasil">Berhasil</option>
                    <option value="gagal">Gagal</option>
                </select>
                <select id="sortBy" class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Urutkan</option>
                    <option value="tanggal">Terbaru</option>
                    <option value="deadline">Deadline</option>
                    <option value="kabupaten">Kabupaten</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Cards Layout -->
    <div class="p-3 sm:p-4 lg:p-6">
        <div id="proyekContainer" class="grid grid-cols-1 gap-4 sm:gap-6">
            @foreach($proyekData as $index => $proyek)
            <!-- Card {{ $index + 1 }} -->
            <div class="proyek-card bg-white border border-gray-200 rounded-xl sm:rounded-2xl p-4 sm:p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200"
                 data-status="{{ $proyek['status'] }}"
                 data-kabupaten="{{ strtolower($proyek['kabupaten_kota']) }}"
                 data-instansi="{{ strtolower($proyek['nama_instansi']) }}"
                 data-tanggal="{{ $proyek['tanggal'] }}"
                 data-deadline="{{ $proyek['deadline'] }}">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4">
                    <div class="flex items-center space-x-3 mb-3 sm:mb-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-red-600 font-bold text-sm sm:text-lg">{{ $index + 1 }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-bold text-gray-800 truncate">{{ $proyek['nama_proyek'] }}</h3>
                            <span class="inline-flex px-2 sm:px-3 py-1 text-xs font-medium rounded-full 
                                @if($proyek['status'] === 'berhasil') bg-green-100 text-green-800
                                @elseif($proyek['status'] === 'proses') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif mt-1">
                                {{ ucfirst($proyek['status']) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 sm:space-x-2 self-start">
                        <button onclick="viewDetail({{ $proyek['id'] }})" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                        <button onclick="editProyek({{ $proyek['id'] }})" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        <button onclick="deleteProyek({{ $proyek['id'] }})" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Tanggal</p>
                        <p class="font-medium text-gray-800 text-sm sm:text-base">{{ \Carbon\Carbon::parse($proyek['tanggal'])->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Kabupaten/Kota</p>
                        <p class="font-medium text-gray-800 text-sm sm:text-base">{{ $proyek['kabupaten_kota'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Nama Instansi</p>
                        <p class="font-medium text-gray-800 text-sm sm:text-base">{{ $proyek['nama_instansi'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Deadline</p>
                        <p class="font-medium text-red-600 text-sm sm:text-base">{{ \Carbon\Carbon::parse($proyek['deadline'])->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-3 sm:mt-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Admin Marketing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-red-600 text-xs sm:text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800 text-sm sm:text-base truncate">{{ $proyek['admin_marketing'] }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">Admin Purchasing</p>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-blue-600 text-xs sm:text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800 text-sm sm:text-base truncate">{{ $proyek['admin_purchasing'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Nilai -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Nilai Proyek:</span>
                        <span class="text-lg font-bold text-red-600">Rp {{ number_format($proyek['total_nilai'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="hidden text-center py-12">
            <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada proyek ditemukan</h3>
            <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian Anda</p>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-xs sm:text-sm text-gray-700 text-center sm:text-left">
                <span id="paginationInfo">Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">{{ count($proyekData) }}</span> dari <span class="font-medium">{{ $totalProyek }}</span> proyek</span>
            </div>
            <div class="flex items-center justify-center sm:justify-end">
                <!-- Mobile Pagination (Simple) -->
                <div class="flex items-center space-x-1 sm:hidden">
                    <button class="px-2 py-2 text-xs border border-gray-300 rounded-md bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md">
                        1 / 1
                    </span>
                    <button class="px-2 py-2 text-xs border border-gray-300 rounded-md bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                
                <!-- Tablet & Desktop Pagination (Full) -->
                <div class="hidden sm:flex items-center space-x-1 md:space-x-2">
                    <button class="px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-chevron-left mr-0 md:mr-1"></i>
                        <span class="hidden md:inline">Previous</span>
                    </button>
                    <button class="px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-white bg-red-600 border border-red-600 rounded-lg">1</button>
                    <button class="px-2 md:px-3 py-2 text-xs md:text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <span class="hidden md:inline">Next</span>
                        <i class="fas fa-chevron-right ml-0 md:ml-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<button onclick="openModal('modalTambahProyek')" class="fixed bottom-4 right-4 sm:bottom-16 sm:right-16 bg-red-600 text-white w-12 h-12 sm:w-16 sm:h-16 rounded-full shadow-2xl hover:bg-red-700 hover:scale-110 transform transition-all duration-200 flex items-center justify-center group z-50">
    <i class="fas fa-plus text-lg sm:text-xl group-hover:rotate-180 transition-transform duration-300"></i>
    <span class="absolute right-full mr-2 sm:mr-3 bg-gray-800 text-white text-xs sm:text-sm px-2 sm:px-3 py-1 sm:py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap hidden sm:block">
        Tambah Proyek
    </span>
</button>

<!-- Include Modal Components -->
@include('pages.marketing.proyek-components.tambah')
@include('pages.marketing.proyek-components.edit')
@include('pages.marketing.proyek-components.detail')
@include('pages.marketing.proyek-components.hapus')
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
</style>

<script>
// Data dari PHP untuk JavaScript
const proyekData = @json($proyekData);
let filteredData = [...proyekData];
let currentData = [...proyekData];

// DOM Elements
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const sortBy = document.getElementById('sortBy');
const proyekContainer = document.getElementById('proyekContainer');
const noResults = document.getElementById('noResults');
const paginationInfo = document.getElementById('paginationInfo');

// Event Listeners untuk filter dan search
if (searchInput) {
    searchInput.addEventListener('input', debounce(filterAndSort, 300));
}
if (statusFilter) {
    statusFilter.addEventListener('change', filterAndSort);
}
if (sortBy) {
    sortBy.addEventListener('change', filterAndSort);
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
    let filtered = [...proyekData];
    
    // Apply search filter
    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    if (searchTerm) {
        filtered = filtered.filter(proyek => 
            proyek.nama_instansi.toLowerCase().includes(searchTerm) ||
            proyek.kabupaten_kota.toLowerCase().includes(searchTerm) ||
            proyek.nama_proyek.toLowerCase().includes(searchTerm)
        );
    }
    
    // Apply status filter
    const selectedStatus = statusFilter ? statusFilter.value : '';
    if (selectedStatus) {
        filtered = filtered.filter(proyek => proyek.status === selectedStatus);
    }
    
    // Apply sorting
    const selectedSort = sortBy ? sortBy.value : '';
    if (selectedSort) {
        switch (selectedSort) {
            case 'tanggal':
                filtered.sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));
                break;
            case 'deadline':
                filtered.sort((a, b) => new Date(a.deadline) - new Date(b.deadline));
                break;
            case 'kabupaten':
                filtered.sort((a, b) => a.kabupaten_kota.localeCompare(b.kabupaten_kota));
                break;
        }
    }
    
    currentData = filtered;
    displayResults();
    updatePaginationInfo();
}

// Display results
function displayResults() {
    const cards = document.querySelectorAll('.proyek-card');
    let visibleCount = 0;
    
    cards.forEach((card, index) => {
        const originalProyek = proyekData[index];
        const isVisible = currentData.some(proyek => proyek.id === originalProyek.id);
        
        if (isVisible) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    if (visibleCount === 0) {
        if (noResults) noResults.classList.remove('hidden');
        if (proyekContainer) proyekContainer.classList.add('hidden');
    } else {
        if (noResults) noResults.classList.add('hidden');
        if (proyekContainer) proyekContainer.classList.remove('hidden');
    }
}

// Update pagination info
function updatePaginationInfo() {
    if (paginationInfo) {
        const totalVisible = currentData.length;
        const totalAll = proyekData.length;
        
        if (totalVisible === 0) {
            paginationInfo.innerHTML = 'Tidak ada proyek yang ditampilkan';
        } else {
            paginationInfo.innerHTML = `Menampilkan <span class="font-medium">${totalVisible}</span> dari <span class="font-medium">${totalAll}</span> proyek`;
        }
    }
}

// Function to view detail proyek
function viewDetail(id) {
    console.log('viewDetail called with ID:', id);
    
    const data = proyekData.find(p => p.id == id);
    
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
        instansi: data.nama_instansi,
        kabupaten: data.kabupaten_kota,
        jenis_pengadaan: data.jenis_pengadaan,
        tanggal: formatTanggal(data.tanggal),
        deadline: formatTanggal(data.deadline),
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
            console.warn(`Element dengan ID ${id} tidak ditemukan`);
        }
    };

    // Set basic info
    setElementText('detailIdProyek', formattedData.kode);
    setElementText('detailNamaProyek', formattedData.nama_proyek);
    setElementText('detailNamaInstansi', formattedData.instansi);
    setElementText('detailKabupatenKota', formattedData.kabupaten);
    setElementText('detailJenisPengadaan', formattedData.jenis_pengadaan);
    setElementText('detailTanggal', formattedData.tanggal);
    setElementText('detailDeadline', formattedData.deadline);
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
            itemDiv.innerHTML = `
                <div class="flex justify-between items-start mb-2">
                    <h5 class="font-medium text-gray-800">${item.nama}</h5>
                    <span class="text-lg font-bold text-red-600">${formatRupiah(item.harga_total)}</span>
                </div>
                <div class="grid grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Qty:</span> ${item.qty}
                    </div>
                    <div>
                        <span class="font-medium">Satuan:</span> ${item.satuan}
                    </div>
                    <div>
                        <span class="font-medium">Harga Satuan:</span> ${formatRupiah(item.harga_satuan)}
                    </div>
                </div>
            `;
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

    // Show modal
    openModal('modalDetailProyek');
}

// Function to edit proyek
function editProyek(id) {
    console.log('editProyek called with ID:', id);
    
    const data = proyekData.find(p => p.id == id);
    
    if (!data) {
        console.error('Data proyek tidak ditemukan dengan ID:', id);
        alert('Data proyek tidak ditemukan!');
        return;
    }
    
    console.log('Data found for edit:', data);
    
    // Format data untuk edit modal
    const editData = {
        id: data.id,
        kode: data.kode,
        nama_proyek: data.nama_proyek,
        kabupaten_kota: data.kabupaten_kota,
        nama_instansi: data.nama_instansi,
        jenis_pengadaan: data.jenis_pengadaan,
        tanggal: data.tanggal,
        deadline: data.deadline,
        admin_marketing: data.admin_marketing,
        admin_purchasing: data.admin_purchasing,
        catatan: data.catatan,
        potensi: data.potensi,
        tahun_potensi: data.tahun_potensi,
        status: data.status,
        total_nilai: data.total_nilai,
        daftar_barang: data.daftar_barang || []
    };

    // Load data into edit form
    setTimeout(() => {
        if (typeof window.loadEditData === 'function') {
            window.loadEditData(editData);
        } else {
            // Fallback: directly populate fields
            const fields = {
                'editId': editData.id,
                'editKode': editData.kode,
                'editNamaProyek': editData.nama_proyek,
                'editKabupatenKota': editData.kabupaten_kota,
                'editNamaInstansi': editData.nama_instansi,
                'editJenisPengadaan': editData.jenis_pengadaan,
                'editTanggal': editData.tanggal,
                'editDeadline': editData.deadline,
                'editAdminMarketing': editData.admin_marketing,
                'editAdminPurchasing': editData.admin_purchasing,
                'editCatatan': editData.catatan,
                'editTahunPotensi': editData.tahun_potensi,
                'editStatus': editData.status
            };
            
            Object.keys(fields).forEach(fieldId => {
                const element = document.getElementById(fieldId);
                if (element) {
                    element.value = fields[fieldId] || '';
                }
            });

            // Handle potensi buttons
            if (typeof togglePotensiEdit === 'function') {
                togglePotensiEdit(editData.potensi);
            }
        }
    }, 100);

    // Show modal
    openModal('modalEditProyek');
}

// Function to delete proyek
function deleteProyek(id) {
    console.log('deleteProyek called with ID:', id);
    
    const data = proyekData.find(p => p.id == id);
    
    if (!data) {
        console.error('Data proyek tidak ditemukan dengan ID:', id);
        alert('Data proyek tidak ditemukan!');
        return;
    }
    
    console.log('Data found for delete:', data);
    
    // Store data globally for deletion process
    window.hapusData = {
        id: data.id,
        kode: data.kode,
        nama_proyek: data.nama_proyek,
        instansi: data.nama_instansi,
        kabupaten: data.kabupaten_kota,
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
    setElementText('hapusNamaProyek', window.hapusData.nama_proyek);
    setElementText('hapusInstansi', window.hapusData.instansi);
    setElementText('hapusKabupaten', window.hapusData.kabupaten);
    setElementText('hapusStatus', ucfirst(window.hapusData.status));

    // Also call loadHapusData if it exists for the hapus modal's internal functions
    if (typeof loadHapusData === 'function') {
        loadHapusData({
            id: data.id,
            kode: data.kode,
            nama_instansi: data.nama_instansi,
            kabupaten_kota: data.kabupaten_kota,
            status: data.status
        });
    }

    // Show modal
    openModal('modalHapusProyek');
}

// Utility Functions
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

function formatTanggal(tanggal) {
    if (!tanggal) return '-';
    const date = new Date(tanggal);
    return date.toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric' 
    });
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
            console.log(`Opened modal: ${modalId}`);
        } else {
            console.error(`Modal dengan ID ${modalId} tidak ditemukan`);
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
            console.log(`Closed modal: ${modalId}`);
        }
    }
}

if (typeof showSuccessModal === 'undefined') {
    function showSuccessModal(message) {
        alert(message);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    console.log('Proyek data loaded:', proyekData.length, 'items');
    
    // Initialize filter and sort
    filterAndSort();
    
    // Add event listeners for modal close buttons
    document.querySelectorAll('[onclick*="closeModal"]').forEach(button => {
        console.log('Found modal close button');
    });
    
    console.log('Initialization complete');
});
</script>

@endsection