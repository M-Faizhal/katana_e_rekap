@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Laporan Proyek</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Laporan proyek yang telah diverifikasi dan disetujui admin keuangan</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-chart-bar text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-check-circle text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Proyek Selesai</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">12</p>
                <p class="text-xs sm:text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +3 bulan ini
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-money-bill-wave text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Nilai Proyek</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">Rp 2.5M</p>
                <p class="text-xs sm:text-sm text-blue-500">
                    <i class="fas fa-money-bill-wave"></i> Disetujui
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-purple-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-building text-purple-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Vendor Aktif</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600">8</p>
                <p class="text-xs sm:text-sm text-purple-500">
                    <i class="fas fa-handshake"></i> Partner
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-box text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Jenis Produk</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">24</p>
                <p class="text-xs sm:text-sm text-yellow-500">
                    <i class="fas fa-tags"></i> Kategori
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filter Section -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-6 sm:mb-8">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-filter text-red-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Filter Laporan Proyek</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Filter berdasarkan vendor, produk, periode, dan status</p>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Periode Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Laporan</label>
                <select id="periode-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Periode</option>
                    <option value="bulan-ini">Bulan Ini</option>
                    <option value="3-bulan">3 Bulan Terakhir</option>
                    <option value="6-bulan">6 Bulan Terakhir</option>
                    <option value="tahun-ini">Tahun Ini</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>

            <!-- Vendor Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                <select id="vendor-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Vendor</option>
                    <option value="pt-abc-corp">PT. ABC Corporation</option>
                    <option value="cv-xyz-trading">CV. XYZ Trading</option>
                    <option value="pt-global-solutions">PT. Global Solutions</option>
                    <option value="pt-tech-innovation">PT. Tech Innovation</option>
                    <option value="cv-mitra-sejahtera">CV. Mitra Sejahtera</option>
                    <option value="pt-digital-media">PT. Digital Media</option>
                    <option value="cv-karya-mandiri">CV. Karya Mandiri</option>
                    <option value="pt-prima-industri">PT. Prima Industri</option>
                </select>
            </div>

            <!-- Product Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Produk</label>
                <select id="kategori-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Kategori</option>
                    <option value="elektronik">Elektronik</option>
                    <option value="furniture">Furniture</option>
                    <option value="alat-tulis">Alat Tulis Kantor</option>
                    <option value="peralatan">Peralatan</option>
                    <option value="bahan-baku">Bahan Baku</option>
                    <option value="jasa">Jasa</option>
                    <option value="software">Software</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Verifikasi</label>
                <select id="status-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Status</option>
                    <option value="verified">Diverifikasi</option>
                    <option value="completed">Selesai</option>
                    <option value="paid">Pembayaran Selesai</option>
                </select>
            </div>
        </div>

        <!-- Specific Product Filter -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Produk Spesifik</label>
                <select id="produk-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Produk</option>
                    <option value="laptop-dell">Laptop Dell Inspiron 15</option>
                    <option value="printer-hp">Printer HP LaserJet Pro</option>
                    <option value="meja-kantor">Meja Kantor Executive</option>
                    <option value="kursi-kantor">Kursi Kantor Ergonomis</option>
                    <option value="kertas-a4">Kertas A4 80gsm</option>
                    <option value="tinta-printer">Tinta Printer Original</option>
                    <option value="kabel-lan">Kabel LAN Cat6</option>
                    <option value="mouse-wireless">Mouse Wireless</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Departemen Pemohon</label>
                <select id="departemen-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Departemen</option>
                    <option value="marketing">Marketing</option>
                    <option value="purchasing">Purchasing</option>
                    <option value="it">IT</option>
                    <option value="hr">HR</option>
                    <option value="finance">Finance</option>
                    <option value="operations">Operations</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Range Nilai</label>
                <select id="nilai-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Nilai</option>
                    <option value="0-5jt">Rp 0 - 5 Juta</option>
                    <option value="5-10jt">Rp 5 - 10 Juta</option>
                    <option value="10-25jt">Rp 10 - 25 Juta</option>
                    <option value="25-50jt">Rp 25 - 50 Juta</option>
                    <option value="50jt+">Rp 50 Juta+</option>
                </select>
            </div>
        </div>

        <!-- Custom Date Range (Hidden by default) -->
        <div id="custom-date-range" class="grid-cols-1 md:grid-cols-2 gap-4 mb-6 hidden"
             style="display: none;"
             data-grid="true">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" id="start-date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" id="end-date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="applyFilters()" class="flex-1 sm:flex-none bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-all duration-200">
                <i class="fas fa-search mr-2"></i>Terapkan Filter
            </button>
            <button onclick="resetFilters()" class="flex-1 sm:flex-none border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-undo mr-2"></i>Reset Filter
            </button>
            <button onclick="exportReport()" class="flex-1 sm:flex-none bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-200">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>
</div>

<!-- Reports Grid -->
<!-- Verified Project Reports Table -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-red-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Laporan Proyek Terverifikasi</h2>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Proyek yang telah diverifikasi admin keuangan</p>
                </div>
            </div>
            <div class="flex gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>12 Terverifikasi
                </span>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-clock mr-1"></i>3 Menunggu
                </span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Proyek
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Vendor
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Produk
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Departemen
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nilai
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="projects-table-body">
                <!-- Project 1 -->
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-laptop text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Pengadaan Laptop</div>
                                <div class="text-sm text-gray-500">PRJ-2024-001</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">PT. Tech Innovation</div>
                        <div class="text-sm text-gray-500">Teknologi</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Laptop Dell Inspiron 15</div>
                        <div class="text-sm text-gray-500">Elektronik - 25 Unit</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            IT Department
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Rp 375,000,000</div>
                        <div class="text-sm text-gray-500">15 jt/unit</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Selesai
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>15 Des 2024</div>
                        <div class="text-xs">Verifikasi: 18 Des</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-red-600 hover:text-red-900 mr-3" onclick="viewProjectDetail('PRJ-2024-001')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-blue-600 hover:text-blue-900" onclick="downloadReport('PRJ-2024-001')">
                            <i class="fas fa-download"></i>
                        </button>
                    </td>
                </tr>

                <!-- Project 2 -->
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-chair text-green-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Furniture Kantor</div>
                                <div class="text-sm text-gray-500">PRJ-2024-002</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">CV. Mitra Sejahtera</div>
                        <div class="text-sm text-gray-500">Furniture</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Meja & Kursi Executive</div>
                        <div class="text-sm text-gray-500">Furniture - 50 Set</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Operations
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Rp 125,000,000</div>
                        <div class="text-sm text-gray-500">2.5 jt/set</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Selesai
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>12 Des 2024</div>
                        <div class="text-xs">Verifikasi: 16 Des</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-red-600 hover:text-red-900 mr-3" onclick="viewProjectDetail('PRJ-2024-002')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-blue-600 hover:text-blue-900" onclick="downloadReport('PRJ-2024-002')">
                            <i class="fas fa-download"></i>
                        </button>
                    </td>
                </tr>

                <!-- Project 3 -->
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-print text-yellow-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Peralatan Printing</div>
                                <div class="text-sm text-gray-500">PRJ-2024-003</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">PT. Digital Media</div>
                        <div class="text-sm text-gray-500">Teknologi</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Printer HP LaserJet Pro</div>
                        <div class="text-sm text-gray-500">Peralatan - 10 Unit</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Marketing
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Rp 85,000,000</div>
                        <div class="text-sm text-gray-500">8.5 jt/unit</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-money-bill-wave mr-1"></i>Dibayar
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>10 Des 2024</div>
                        <div class="text-xs">Verifikasi: 14 Des</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-red-600 hover:text-red-900 mr-3" onclick="viewProjectDetail('PRJ-2024-003')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-blue-600 hover:text-blue-900" onclick="downloadReport('PRJ-2024-003')">
                            <i class="fas fa-download"></i>
                        </button>
                    </td>
                </tr>

                <!-- Project 4 -->
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-alt text-indigo-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">ATK & Supplies</div>
                                <div class="text-sm text-gray-500">PRJ-2024-004</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">CV. Karya Mandiri</div>
                        <div class="text-sm text-gray-500">Stationery</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Kertas A4 & Supplies</div>
                        <div class="text-sm text-gray-500">ATK - Bulk Order</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            HR
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Rp 25,500,000</div>
                        <div class="text-sm text-gray-500">Bulk pricing</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Selesai
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>08 Des 2024</div>
                        <div class="text-xs">Verifikasi: 12 Des</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-red-600 hover:text-red-900 mr-3" onclick="viewProjectDetail('PRJ-2024-004')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-blue-600 hover:text-blue-900" onclick="downloadReport('PRJ-2024-004')">
                            <i class="fas fa-download"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">4</span> dari <span class="font-medium">12</span> proyek
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-3 py-2 text-sm bg-red-600 text-white rounded-md">1</button>
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">2</button>
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">3</button>
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
function applyFilters() {
    const periode = document.getElementById('periode-filter').value;
    const vendor = document.getElementById('vendor-filter').value;
    const kategori = document.getElementById('kategori-filter').value;
    const status = document.getElementById('status-filter').value;
    const produk = document.getElementById('produk-filter').value;
    const departemen = document.getElementById('departemen-filter').value;
    const nilai = document.getElementById('nilai-filter').value;

    console.log('Applying filters:', {
        periode, vendor, kategori, status, produk, departemen, nilai
    });

    // Here you would typically make an AJAX call to filter the data
    // For demo purposes, we'll just show a loading state
    const tableBody = document.getElementById('projects-table-body');
    tableBody.innerHTML = `
        <tr>
            <td colspan="8" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600 mb-4"></div>
                    <p class="text-gray-500">Memuat data...</p>
                </div>
            </td>
        </tr>
    `;

    // Simulate loading and restore data after 2 seconds
    setTimeout(() => {
        location.reload();
    }, 2000);
}

function resetFilters() {
    document.getElementById('periode-filter').value = '';
    document.getElementById('vendor-filter').value = '';
    document.getElementById('kategori-filter').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('produk-filter').value = '';
    document.getElementById('departemen-filter').value = '';
    document.getElementById('nilai-filter').value = '';

    // Hide custom date range
    document.getElementById('custom-date-range').style.display = 'none';
    document.getElementById('start-date').value = '';
    document.getElementById('end-date').value = '';
}

function exportReport() {
    const currentDate = new Date().toISOString().split('T')[0];
    const filename = `laporan-proyek-${currentDate}.xlsx`;

    // Show export notification
    showNotification('Export berhasil! File sedang diunduh...', 'success');

    // Here you would typically trigger the actual export
    console.log('Exporting report as:', filename);
}

function viewProjectDetail(projectId) {
    console.log('Viewing project detail for:', projectId);

    // Create modal for project detail
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">Detail Proyek ${projectId}</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Informasi Proyek</h3>
                        <div class="space-y-3">
                            <div><span class="font-medium">ID Proyek:</span> ${projectId}</div>
                            <div><span class="font-medium">Nama:</span> Pengadaan Laptop</div>
                            <div><span class="font-medium">Status:</span> <span class="text-green-600">Selesai</span></div>
                            <div><span class="font-medium">Departemen:</span> IT Department</div>
                            <div><span class="font-medium">Tanggal Mulai:</span> 15 Des 2024</div>
                            <div><span class="font-medium">Tanggal Verifikasi:</span> 18 Des 2024</div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Detail Finansial</h3>
                        <div class="space-y-3">
                            <div><span class="font-medium">Total Nilai:</span> Rp 375,000,000</div>
                            <div><span class="font-medium">Harga per Unit:</span> Rp 15,000,000</div>
                            <div><span class="font-medium">Jumlah Unit:</span> 25 Unit</div>
                            <div><span class="font-medium">Status Pembayaran:</span> <span class="text-blue-600">Lunas</span></div>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-4">Dokumen Terkait</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border rounded-lg p-4">
                            <i class="fas fa-file-pdf text-red-500 text-2xl mb-2"></i>
                            <p class="font-medium">Purchase Order</p>
                            <p class="text-sm text-gray-500">Diverifikasi</p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <i class="fas fa-file-invoice text-blue-500 text-2xl mb-2"></i>
                            <p class="font-medium">Invoice</p>
                            <p class="text-sm text-gray-500">Diverifikasi</p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <i class="fas fa-file-signature text-green-500 text-2xl mb-2"></i>
                            <p class="font-medium">Kontrak</p>
                            <p class="text-sm text-gray-500">Diverifikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
}

function downloadReport(projectId) {
    showNotification(`Mengunduh laporan untuk proyek ${projectId}...`, 'info');

    // Simulate download
    setTimeout(() => {
        showNotification('Laporan berhasil diunduh!', 'success');
    }, 2000);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    notification.classList.add(bgColor, 'text-white');

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Handle periode filter change
document.getElementById('periode-filter').addEventListener('change', function() {
    const customDateRange = document.getElementById('custom-date-range');
    if (this.value === 'custom') {
        customDateRange.style.display = 'grid';
        customDateRange.classList.add('grid');
    } else {
        customDateRange.style.display = 'none';
        customDateRange.classList.remove('grid');
    }
});

// Auto-save filter preferences
document.addEventListener('DOMContentLoaded', function() {
    // Load saved filters from localStorage
    const savedFilters = localStorage.getItem('laporanFilters');
    if (savedFilters) {
        const filters = JSON.parse(savedFilters);
        Object.keys(filters).forEach(key => {
            const element = document.getElementById(key);
            if (element) {
                element.value = filters[key];
            }
        });
    }

    // Save filters when changed
    const filterElements = [
        'periode-filter', 'vendor-filter', 'kategori-filter',
        'status-filter', 'produk-filter', 'departemen-filter', 'nilai-filter'
    ];

    filterElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', function() {
                const filters = {};
                filterElements.forEach(filterId => {
                    const filterElement = document.getElementById(filterId);
                    if (filterElement) {
                        filters[filterId] = filterElement.value;
                    }
                });
                localStorage.setItem('laporanFilters', JSON.stringify(filters));
            });
        }
    });
});
</script>
@endsection
