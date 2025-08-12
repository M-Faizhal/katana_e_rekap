@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Manajemen Produk</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan pantau semua produk</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-box text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-box text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Produk</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">45</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-microchip text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Elektronik</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">18</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-cogs text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Mesin</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">15</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-chair text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Meubel</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">12</p>
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
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Daftar Produk</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola semua data produk dan informasinya</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Cari nama barang atau spesifikasi..." 
                           class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <select class="px-3 py-2.5 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                    <option>Semua Jenis</option>
                    <option>Elektronik</option>
                    <option>Mesin</option>
                    <option>Meubel</option>
                </select>
                <select class="px-3 py-2.5 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                    <option>Urutkan</option>
                    <option>Nama A-Z</option>
                    <option>Nama Z-A</option>
                    <option>TKDN Tertinggi</option>
                    <option>TKDN Terendah</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="text-left py-3 lg:py-4 px-4 lg:px-6 font-semibold text-gray-800 text-xs lg:text-sm">No</th>
                    <th class="text-left py-3 lg:py-4 px-4 lg:px-6 font-semibold text-gray-800 text-xs lg:text-sm">Gambar</th>
                    <th class="text-left py-3 lg:py-4 px-4 lg:px-6 font-semibold text-gray-800 text-xs lg:text-sm">Nama Barang</th>
                    <th class="text-left py-3 lg:py-4 px-4 lg:px-6 font-semibold text-gray-800 text-xs lg:text-sm">Spesifikasi</th>
                    <th class="text-left py-3 lg:py-4 px-4 lg:px-6 font-semibold text-gray-800 text-xs lg:text-sm">Jenis Barang</th>
                    <th class="text-left py-3 lg:py-4 px-4 lg:px-6 font-semibold text-gray-800 text-xs lg:text-sm">Nilai TKDN</th>
                    <th class="text-center py-3 lg:py-4 px-4 lg:px-6 font-semibold text-gray-800 text-xs lg:text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Product Row 1 -->
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="font-medium text-gray-800 text-sm lg:text-base">PRD-001</span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="https://via.placeholder.com/48" alt="Produk" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm lg:text-base">Laptop Dell Latitude 7420</p>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <p class="text-gray-600 text-xs lg:text-sm">Intel i7, 16GB RAM, 512GB SSD</p>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Elektronik
                        </span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 w-16 lg:w-20">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 25%"></div>
                            </div>
                            <span class="text-xs lg:text-sm font-medium text-green-600">25%</span>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center justify-center space-x-1 lg:space-x-2">
                            <button onclick="viewDetailProduk(1)" class="p-1.5 lg:p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editProduk(1)" class="p-1.5 lg:p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="deleteProduk(1)" class="p-1.5 lg:p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                                <i class="fas fa-trash text-sm lg:text-base"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Product Row 2 -->
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="font-medium text-gray-800 text-sm lg:text-base">PRD-002</span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="https://via.placeholder.com/48" alt="Produk" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm lg:text-base">Mesin Bubut CNC</p>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <p class="text-gray-600 text-xs lg:text-sm">3 Axis, Max 200mm chuck</p>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Mesin
                        </span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 w-16 lg:w-20">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 60%"></div>
                            </div>
                            <span class="text-xs lg:text-sm font-medium text-green-600">60%</span>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center justify-center space-x-1 lg:space-x-2">
                            <button onclick="viewDetailProduk(2)" class="p-1.5 lg:p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editProduk(2)" class="p-1.5 lg:p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="deleteProduk(2)" class="p-1.5 lg:p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                                <i class="fas fa-trash text-sm lg:text-base"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Product Row 3 -->
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="font-medium text-gray-800 text-sm lg:text-base">PRD-003</span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="https://via.placeholder.com/48" alt="Produk" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm lg:text-base">Meja Kerja Kayu Jati</p>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <p class="text-gray-600 text-xs lg:text-sm">120x60x75cm, Kayu Jati Grade A</p>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Meubel
                        </span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 w-16 lg:w-20">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-xs lg:text-sm font-medium text-green-600">85%</span>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center justify-center space-x-1 lg:space-x-2">
                            <button onclick="viewDetailProduk(3)" class="p-1.5 lg:p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editProduk(3)" class="p-1.5 lg:p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="deleteProduk(3)" class="p-1.5 lg:p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                                <i class="fas fa-trash text-sm lg:text-base"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Product Row 4 -->
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="font-medium text-gray-800 text-sm lg:text-base">PRD-004</span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="https://via.placeholder.com/48" alt="Produk" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm lg:text-base">Server HP ProLiant DL380</p>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <p class="text-gray-600 text-xs lg:text-sm">Intel Xeon, 32GB RAM, 2TB HDD</p>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Elektronik
                        </span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 w-16 lg:w-20">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 30%"></div>
                            </div>
                            <span class="text-xs lg:text-sm font-medium text-green-600">30%</span>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center justify-center space-x-1 lg:space-x-2">
                            <button onclick="viewDetailProduk(4)" class="p-1.5 lg:p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editProduk(4)" class="p-1.5 lg:p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="deleteProduk(4)" class="p-1.5 lg:p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                                <i class="fas fa-trash text-sm lg:text-base"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Product Row 5 -->
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="font-medium text-gray-800 text-sm lg:text-base">PRD-005</span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="https://via.placeholder.com/48" alt="Produk" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm lg:text-base">Kursi Kantor Ergonomis</p>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <p class="text-gray-600 text-xs lg:text-sm">Bahan kulit sintetis, adjustable height</p>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Meubel
                        </span>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 w-16 lg:w-20">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 70%"></div>
                            </div>
                            <span class="text-xs lg:text-sm font-medium text-green-600">70%</span>
                        </div>
                    </td>
                    <td class="py-3 lg:py-4 px-4 lg:px-6">
                        <div class="flex items-center justify-center space-x-1 lg:space-x-2">
                            <button onclick="viewDetailProduk(5)" class="p-1.5 lg:p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200" title="Lihat Detail">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editProduk(5)" class="p-1.5 lg:p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="deleteProduk(5)" class="p-1.5 lg:p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200" title="Hapus">
                                <i class="fas fa-trash text-sm lg:text-base"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="block md:hidden">
        <div class="p-4 space-y-4">
            <!-- Card 1 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-lg object-cover" src="https://via.placeholder.com/48" alt="Laptop">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">Laptop Dell Latitude 7420</h3>
                                <span class="text-xs font-medium text-gray-500">PRD-001</span>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">Intel i7, 16GB RAM, 512GB SSD</p>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Elektronik
                                    </span>
                                    <div class="flex items-center">
                                        <div class="w-8 bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 25%"></div>
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-900">25%</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button onclick="viewDetailProduk(1)" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    <button onclick="editProduk(1)" class="text-green-600 hover:text-green-900 transition-colors p-2" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <button onclick="deleteProduk(1)" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-lg object-cover" src="https://via.placeholder.com/48" alt="Mesin">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">Mesin Bubut CNC</h3>
                                <span class="text-xs font-medium text-gray-500">PRD-002</span>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">3 Axis, Max 200mm chuck</p>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Mesin
                                    </span>
                                    <div class="flex items-center">
                                        <div class="w-8 bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 60%"></div>
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-900">60%</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button onclick="viewDetailProduk(2)" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    <button onclick="editProduk(2)" class="text-green-600 hover:text-green-900 transition-colors p-2" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <button onclick="deleteProduk(2)" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-lg object-cover" src="https://via.placeholder.com/48" alt="Meja">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">Meja Kerja Kayu Jati</h3>
                                <span class="text-xs font-medium text-gray-500">PRD-003</span>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">120x60x75cm, Kayu Jati Grade A</p>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Meubel
                                    </span>
                                    <div class="flex items-center">
                                        <div class="w-8 bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 85%"></div>
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-900">85%</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button onclick="viewDetailProduk(3)" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    <button onclick="editProduk(3)" class="text-green-600 hover:text-green-900 transition-colors p-2" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <button onclick="deleteProduk(3)" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-lg object-cover" src="https://via.placeholder.com/48" alt="Server">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">Server HP ProLiant DL380</h3>
                                <span class="text-xs font-medium text-gray-500">PRD-004</span>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">Intel Xeon, 32GB RAM, 2TB HDD</p>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Elektronik
                                    </span>
                                    <div class="flex items-center">
                                        <div class="w-8 bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 30%"></div>
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-900">30%</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button onclick="viewDetailProduk(4)" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    <button onclick="editProduk(4)" class="text-green-600 hover:text-green-900 transition-colors p-2" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <button onclick="deleteProduk(4)" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-lg object-cover" src="https://via.placeholder.com/48" alt="Kursi">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">Kursi Kantor Ergonomis</h3>
                                <span class="text-xs font-medium text-gray-500">PRD-005</span>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">Bahan kulit sintetis, adjustable height</p>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Meubel
                                    </span>
                                    <div class="flex items-center">
                                        <div class="w-8 bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 70%"></div>
                                        </div>
                                        <span class="ml-2 text-xs font-medium text-gray-900">70%</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button onclick="viewDetailProduk(5)" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    <button onclick="editProduk(5)" class="text-green-600 hover:text-green-900 transition-colors p-2" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <button onclick="deleteProduk(5)" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="p-4 sm:p-6">
        <!-- Mobile Pagination -->
        <div class="flex md:hidden items-center justify-between pt-4 sm:pt-6 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                1 / 9
            </div>
            <div class="flex items-center space-x-2">
                <button class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Desktop Pagination -->
        <div class="hidden md:flex items-center justify-between pt-6 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Menampilkan 1-5 dari 45 produk
            </div>
            <div class="flex items-center space-x-1">
                <button class="px-3 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg">1</button>
                <button class="px-4 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">2</button>
                <button class="px-4 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">3</button>
                <button class="px-3 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Add Button -->
<button onclick="openModal('modalTambahProduk')" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 lg:bottom-16 lg:right-16 bg-red-600 text-white w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full shadow-2xl hover:bg-red-700 hover:scale-110 transform transition-all duration-200 flex items-center justify-center group z-50">
    <i class="fas fa-plus text-lg sm:text-xl group-hover:rotate-90 transition-transform duration-200"></i>
    <div class="absolute bottom-full right-0 mb-2 px-3 py-1 bg-gray-800 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
        Tambah Produk
    </div>
</button>

<!-- Include Modal Components -->
@include('pages.purchasing.produk-components.tambah')
@include('pages.purchasing.produk-components.edit')
@include('pages.purchasing.produk-components.detail')
@include('pages.purchasing.produk-components.hapus')

<!-- Include Success Modal -->
@include('components.success-modal')

@endsection

@push('scripts')
<script src="{{ asset('js/modal-functions.js') }}"></script>
<script>
    function viewDetailProduk(id) {
        // Logic to show detail modal with sample data
        const sampleData = {
            no_produk: 'PRD-' + String(id).padStart(3, '0'),
            nama_barang: id === 1 ? 'Laptop Dell Latitude 7420' : 
                        id === 2 ? 'Mesin Bubut CNC' :
                        id === 3 ? 'Meja Kerja Kayu Jati' :
                        id === 4 ? 'Server HP ProLiant DL380' :
                        'Kursi Kantor Ergonomis',
            spesifikasi: id === 1 ? 'Intel Core i7-1165G7 Processor, 16GB LPDDR4x RAM, 512GB PCIe NVMe SSD' :
                        id === 2 ? '3 Axis CNC Lathe, Maximum chuck diameter 200mm' :
                        id === 3 ? '120x60x75cm, Kayu Jati Grade A, Finishing natural' :
                        id === 4 ? 'Intel Xeon Scalable Processor, 32GB DDR4 RAM, 2TB Enterprise HDD' :
                        'Bahan kulit sintetis premium, adjustable height, ergonomic design',
            jenis_barang: id === 1 || id === 4 ? 'Elektronik' : 
                         id === 2 ? 'Mesin' : 'Meubel',
            nilai_tkdn: id === 1 ? 25 : id === 2 ? 60 : id === 3 ? 85 : id === 4 ? 30 : 70,
            gambar: 'https://via.placeholder.com/300',
            tanggal_dibuat: '12 Agustus 2025',
            last_update: 'Hari ini'
        };
        
        // Populate detail modal with sample data
        populateDetailModal(sampleData);
        openModal('modalDetailProduk');
    }

    function editProduk(id) {
        // Logic to show edit modal with sample data
        const sampleData = {
            no_produk: 'PRD-' + String(id).padStart(3, '0'),
            nama_barang: id === 1 ? 'Laptop Dell Latitude 7420' : 
                        id === 2 ? 'Mesin Bubut CNC' :
                        id === 3 ? 'Meja Kerja Kayu Jati' :
                        id === 4 ? 'Server HP ProLiant DL380' :
                        'Kursi Kantor Ergonomis',
            spesifikasi: id === 1 ? 'Intel i7, 16GB RAM, 512GB SSD' :
                        id === 2 ? '3 Axis, Max 200mm chuck' :
                        id === 3 ? '120x60x75cm, Kayu Jati Grade A' :
                        id === 4 ? 'Intel Xeon, 32GB RAM, 2TB HDD' :
                        'Bahan kulit sintetis, adjustable height',
            jenis_barang: id === 1 || id === 4 ? 'Elektronik' : 
                         id === 2 ? 'Mesin' : 'Meubel',
            nilai_tkdn: id === 1 ? 25 : id === 2 ? 60 : id === 3 ? 85 : id === 4 ? 30 : 70,
            gambar: 'https://via.placeholder.com/150'
        };
        
        // Populate edit modal with sample data
        populateEditForm(sampleData);
        openModal('modalEditProduk');
    }

    function deleteProduk(id) {
        // Logic to show delete confirmation modal with sample data
        const sampleData = {
            no_produk: 'PRD-' + String(id).padStart(3, '0'),
            nama_barang: id === 1 ? 'Laptop Dell Latitude 7420' : 
                        id === 2 ? 'Mesin Bubut CNC' :
                        id === 3 ? 'Meja Kerja Kayu Jati' :
                        id === 4 ? 'Server HP ProLiant DL380' :
                        'Kursi Kantor Ergonomis',
            gambar: 'https://via.placeholder.com/48'
        };
        
        // Populate delete modal with sample data
        populateDeleteModal(sampleData);
        openModal('modalHapusProduk');
    }

    // Override the form submission handlers to show success messages
    document.addEventListener('DOMContentLoaded', function() {
        // Tambah Produk Form
        const formTambah = document.getElementById('formTambahProduk');
        if (formTambah) {
            formTambah.addEventListener('submit', function(e) {
                e.preventDefault();
                closeModal('modalTambahProduk');
                showSuccessModalWithAutoClose('Produk berhasil ditambahkan!', 3000);
                // In real app, you would send AJAX request here
            });
        }

        // Edit Produk Form
        const formEdit = document.getElementById('formEditProduk');
        if (formEdit) {
            formEdit.addEventListener('submit', function(e) {
                e.preventDefault();
                closeModal('modalEditProduk');
                showSuccessModalWithAutoClose('Produk berhasil diperbarui!', 3000);
                // In real app, you would send AJAX request here
            });
        }
    });

    // Override the confirmDeleteProduct function
    function confirmDeleteProduct() {
        const checkbox = document.getElementById('confirmDelete');
        if (!checkbox.checked) {
            alert('Harap centang kotak konfirmasi terlebih dahulu.');
            return;
        }

        // Close delete modal and show success
        closeModal('modalHapusProduk');
        showSuccessModalWithAutoClose('Produk berhasil dihapus!', 3000);
        
        // In real application, you would:
        // 1. Send AJAX request to delete the product
        // 2. Remove the product row from the table
        // 3. Update the stats cards
    }
</script>
@endpush
