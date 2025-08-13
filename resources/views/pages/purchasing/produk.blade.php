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

    <!-- Product Cards Grid -->
    <div class="p-4 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 sm:gap-6">
            <!-- Product Card 1 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(1)">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Laptop Dell Latitude 7420" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Elektronik
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-001</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Laptop Dell Latitude 7420</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">Intel i7, 16GB RAM, 512GB SSD</p>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(2)">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Mesin Bubut CNC" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Mesin
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-002</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Mesin Bubut CNC</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">3 Axis, Max 200mm chuck</p>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(3)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Meja Kerja Kayu Jati" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Meubel
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-003</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Meja Kerja Kayu Jati</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">120x60x75cm, Kayu Jati Grade A</p>
                </div>
            </div>

            <!-- Product Card 4 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(4)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Server HP ProLiant DL380" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Elektronik
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-004</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Server HP ProLiant DL380</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">Intel Xeon, 32GB RAM, 2TB HDD</p>
                </div>
            </div>

            <!-- Product Card 5 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(5)">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Kursi Kantor Ergonomis" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Meubel
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-005</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Kursi Kantor Ergonomis</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">Bahan kulit sintetis, adjustable height</p>
                </div>
            </div>

            <!-- Product Card 6 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(6)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Printer LaserJet Pro" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Elektronik
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-006</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Printer LaserJet Pro</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">Monochrome Laser, 35 ppm, USB & Network</p>
                </div>
            </div>

            <!-- Product Card 7 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(7)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="AC Split Inverter" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Elektronik
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-007</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">AC Split Inverter</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">1.5 PK, R32 Refrigerant, Energy Saving</p>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(8)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Proyektor LED" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Elektronik
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-008</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Proyektor LED</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">4000 Lumens, Full HD, HDMI & VGA</p>
                </div>
            </div>

            <!-- Product Card 9 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(9)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Mesin Gerinda" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Mesin
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-009</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Mesin Gerinda</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">4 inch, 580W, Variable Speed Control</p>
                </div>
            </div>

            <!-- Product Card 10 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(10)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Sofa Minimalis" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Meubel
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-010</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Sofa Minimalis</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">3 Seater, Fabric Cover, Modern Design</p>
                </div>
            </div>

            <!-- Product Card 11 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(11)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Monitor LED 24 inch" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Elektronik
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-011</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Monitor LED 24 inch</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">Full HD, IPS Panel, HDMI & VGA</p>
                </div>
            </div>

            <!-- Product Card 12 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(12)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Mesin Bor Listrik" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Mesin
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-012</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Mesin Bor Listrik</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">13mm Chuck, 650W, Reversible Function</p>
                </div>
            </div>

            <!-- Product Card 13 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(13)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Rak Buku Kayu" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Meubel
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-013</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Rak Buku Kayu</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">5 Tingkat, Kayu Mahoni, 180x80x30cm</p>
                </div>
            </div>

            <!-- Product Card 14 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(14)">

                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Speaker Bluetooth" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            Elektronik
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-014</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Speaker Bluetooth</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">50W RMS, Waterproof, 12 Hours Battery</p>
                </div>
            </div>

            <!-- Product Card 15 -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer" onclick="showProductDetail(15)">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-100 overflow-hidden">
                        <img src="https://via.placeholder.com/300x200" alt="Mesin Las Inverter" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Mesin
                        </span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-md">PRD-015</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">Mesin Las Inverter</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">200A, IGBT Technology, Arc Force Control</p>
                </div>
            </div>
        </div>
    </div>    <!-- Pagination -->
    <div class="p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 sm:pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
            <div class="text-sm text-gray-500 text-center sm:text-left">
                Menampilkan 1-15 dari 45 produk
            </div>

            <!-- Mobile Pagination (Simple) -->
            <div class="flex sm:hidden items-center justify-center space-x-3">
                <button class="px-3 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 min-h-[44px] flex items-center">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span class="text-sm font-medium text-gray-700 px-3 py-2">1 / 3</span>
                <button class="px-3 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 min-h-[44px] flex items-center">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Desktop Pagination (Full) -->
            <div class="hidden sm:flex items-center space-x-1">
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
<button onclick="openModal('modalTambahProduk')" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 lg:bottom-16 lg:right-16 bg-red-600 text-white w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full shadow-2xl hover:bg-red-700 hover:scale-110 transform transition-all duration-200 flex items-center justify-center group z-50">
    <i class="fas fa-plus text-sm sm:text-base lg:text-xl group-hover:rotate-90 transition-transform duration-200"></i>
    <div class="absolute bottom-full right-0 mb-2 px-2 py-1 sm:px-3 sm:py-1 bg-gray-800 text-white text-xs sm:text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
        Tambah Produk
    </div>
</button>

<!-- Include Modal Components -->
@include('pages.produk-components.tambah')

<!-- Product Detail Modal -->
<div id="modalDetailProduk" class="fixed inset-0 bg-black/20 backdrop-blur-xs overflow-y-auto h-full w-full hidden z-50 items-center justify-center p-4">
    <div class="relative mx-auto max-w-4xl w-full bg-white bg-opacity-95 backdrop-blur-md shadow-2xl rounded-2xl border border-white border-opacity-20 transform transition-all duration-300" id="modalContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-red-100 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-white text-lg"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900" id="modalProductTitle">Detail Produk</h3>
            </div>
            <button type="button" class="text-gray-400 bg-white bg-opacity-80 hover:bg-red-100 hover:text-red-600 rounded-xl text-sm p-2 transition-all duration-200 shadow-md hover:shadow-lg" onclick="closeModalDetail()">
                <i class="fas fa-times w-5 h-5"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 bg-gradient-to-br from-white to-gray-50">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Image Section -->
                <div class="space-y-6">
                    <div class="relative group">
                        <div class="w-full h-80 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl overflow-hidden shadow-lg">
                            <img id="modalProductImage" src="" alt="Product Image" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>
                        <div class="absolute top-4 left-4">
                            <span id="modalProductCode" class="bg-gray-900 bg-opacity-90 text-white text-sm font-medium px-3 py-2 rounded-xl shadow-lg backdrop-blur-sm"></span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span id="modalProductCategory" class="inline-flex px-3 py-2 text-sm font-semibold rounded-xl shadow-lg backdrop-blur-sm"></span>
                        </div>
                    </div>
                </div>

                <!-- Product Details Section -->
                <div class="space-y-6">
                    <div class="bg-white bg-opacity-80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white border-opacity-50">
                        <h4 class="text-2xl font-bold text-gray-900 mb-3" id="modalProductName"></h4>
                        <div class="flex items-center space-x-2 mb-4">
                            <i class="fas fa-cogs text-red-600"></i>
                            <p class="text-gray-700 font-medium" id="modalProductSpec"></p>
                        </div>
                    </div>                    <div class="bg-white bg-opacity-80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white border-opacity-50">
                        <div class="flex items-center space-x-2 mb-4">
                            <i class="fas fa-file-alt text-blue-600"></i>
                            <h5 class="text-lg font-bold text-gray-900">Deskripsi Produk</h5>
                        </div>
                        <p class="text-gray-700 leading-relaxed" id="modalProductDescription"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-6 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-2xl">
            <div class="flex items-center space-x-3 text-sm text-gray-600">
                <i class="fas fa-info-circle text-blue-500"></i>
                <span>Informasi produk diperbarui secara real-time</span>
            </div>
            <button type="button" onclick="closeModalDetail()"
                    class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:from-gray-700 hover:to-gray-800 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>

<!-- Include Success Modal -->
@include('components.success-modal')

@endsection

@push('scripts')
<script src="{{ asset('js/modal-functions.js') }}"></script>
<script>
    // Product data
    const products = {
        1: {
            name: 'Laptop Dell Latitude 7420',
            code: 'PRD-001',
            category: 'Elektronik',
            categoryClass: 'bg-blue-100 text-blue-800',
            spec: 'Intel i7, 16GB RAM, 512GB SSD',
            image: 'https://via.placeholder.com/400x300',
            date: '12 Agustus 2025',
            description: 'Laptop profesional dengan performa tinggi untuk kebutuhan bisnis dan produktivitas. Dilengkapi dengan processor Intel Core i7 generasi terbaru, RAM 16GB DDR4, dan storage SSD 512GB untuk kecepatan akses data yang optimal.'
        },
        2: {
            name: 'Mesin Bubut CNC',
            code: 'PRD-002',
            category: 'Mesin',
            categoryClass: 'bg-green-100 text-green-800',
            spec: '3 Axis, Max 200mm chuck',
            image: 'https://via.placeholder.com/400x300',
            date: '10 Agustus 2025',
            description: 'Mesin bubut CNC presisi tinggi dengan 3 axis untuk berbagai kebutuhan machining. Dilengkapi chuck maksimal 200mm dan kontrol numerik untuk akurasi tinggi dalam proses produksi.'
        },
        3: {
            name: 'Meja Kerja Kayu Jati',
            code: 'PRD-003',
            category: 'Meubel',
            categoryClass: 'bg-yellow-100 text-yellow-800',
            spec: '120x60x75cm, Kayu Jati Grade A',
            image: 'https://via.placeholder.com/400x300',
            date: '8 Agustus 2025',
            description: 'Meja kerja premium dari kayu jati grade A dengan dimensi 120x60x75cm. Finishing natural yang mempertahankan keindahan serat kayu alami, cocok untuk ruang kerja modern maupun klasik.'
        },
        4: {
            name: 'Server HP ProLiant DL380',
            code: 'PRD-004',
            category: 'Elektronik',
            categoryClass: 'bg-blue-100 text-blue-800',
            spec: 'Intel Xeon, 32GB RAM, 2TB HDD',
            image: 'https://via.placeholder.com/400x300',
            date: '6 Agustus 2025',
            description: 'Server enterprise HP ProLiant DL380 dengan processor Intel Xeon, RAM 32GB DDR4, dan storage 2TB HDD. Ideal untuk infrastruktur IT skala menengah hingga besar.'
        },
        5: {
            name: 'Kursi Kantor Ergonomis',
            code: 'PRD-005',
            category: 'Meubel',
            categoryClass: 'bg-yellow-100 text-yellow-800',
            spec: 'Bahan kulit sintetis, adjustable height',
            image: 'https://via.placeholder.com/400x300',
            date: '4 Agustus 2025',
            description: 'Kursi kantor ergonomis dengan bahan kulit sintetis premium. Dilengkapi fitur adjustable height, lumbar support, dan armrest yang dapat disesuaikan untuk kenyamanan maksimal.'
        },
        6: {
            name: 'Printer LaserJet Pro',
            code: 'PRD-006',
            category: 'Elektronik',
            categoryClass: 'bg-blue-100 text-blue-800',
            spec: 'Monochrome Laser, 35 ppm, USB & Network',
            image: 'https://via.placeholder.com/400x300',
            date: '2 Agustus 2025',
            description: 'Printer laser monochrome profesional dengan kecepatan cetak 35 halaman per menit. Dilengkapi konektivitas USB dan Network untuk sharing printer di lingkungan kantor.'
        },
        7: {
            name: 'AC Split Inverter',
            code: 'PRD-007',
            category: 'Elektronik',
            categoryClass: 'bg-blue-100 text-blue-800',
            spec: '1.5 PK, R32 Refrigerant, Energy Saving',
            image: 'https://via.placeholder.com/400x300',
            date: '1 Agustus 2025',
            description: 'Air conditioner split inverter 1.5 PK dengan teknologi hemat energi. Menggunakan refrigerant R32 yang ramah lingkungan dan dilengkapi fitur auto-cleaning.'
        },
        8: {
            name: 'Proyektor LED',
            code: 'PRD-008',
            category: 'Elektronik',
            categoryClass: 'bg-blue-100 text-blue-800',
            spec: '4000 Lumens, Full HD, HDMI & VGA',
            image: 'https://via.placeholder.com/400x300',
            date: '30 Juli 2025',
            description: 'Proyektor LED dengan brightness 4000 lumens dan resolusi Full HD. Dilengkapi multiple input HDMI dan VGA untuk berbagai kebutuhan presentasi dan hiburan.'
        },
        9: {
            name: 'Mesin Gerinda',
            code: 'PRD-009',
            category: 'Mesin',
            categoryClass: 'bg-green-100 text-green-800',
            spec: '4 inch, 580W, Variable Speed Control',
            image: 'https://via.placeholder.com/400x300',
            date: '28 Juli 2025',
            description: 'Mesin gerinda 4 inch dengan motor 580W dan variable speed control. Cocok untuk berbagai aplikasi grinding, cutting, dan polishing dengan kontrol kecepatan yang presisi.'
        },
        10: {
            name: 'Sofa Minimalis',
            code: 'PRD-010',
            category: 'Meubel',
            categoryClass: 'bg-yellow-100 text-yellow-800',
            spec: '3 Seater, Fabric Cover, Modern Design',
            image: 'https://via.placeholder.com/400x300',
            date: '26 Juli 2025',
            description: 'Sofa minimalis 3 seater dengan cover fabric berkualitas tinggi. Design modern yang cocok untuk ruang tamu contemporary dengan kenyamanan duduk yang optimal.'
        },
        11: {
            name: 'Monitor LED 24 inch',
            code: 'PRD-011',
            category: 'Elektronik',
            categoryClass: 'bg-blue-100 text-blue-800',
            spec: 'Full HD, IPS Panel, HDMI & VGA',
            image: 'https://via.placeholder.com/400x300',
            date: '24 Juli 2025',
            description: 'Monitor LED 24 inch dengan panel IPS Full HD untuk akurasi warna yang excellent. Dilengkapi input HDMI dan VGA untuk fleksibilitas koneksi.'
        },
        12: {
            name: 'Mesin Bor Listrik',
            code: 'PRD-012',
            category: 'Mesin',
            categoryClass: 'bg-green-100 text-green-800',
            spec: '13mm Chuck, 650W, Reversible Function',
            image: 'https://via.placeholder.com/400x300',
            date: '22 Juli 2025',
            description: 'Mesin bor listrik dengan chuck 13mm dan motor 650W. Dilengkapi reversible function untuk drilling dan driving, cocok untuk berbagai aplikasi konstruksi dan woodworking.'
        },
        13: {
            name: 'Rak Buku Kayu',
            code: 'PRD-013',
            category: 'Meubel',
            categoryClass: 'bg-yellow-100 text-yellow-800',
            spec: '5 Tingkat, Kayu Mahoni, 180x80x30cm',
            image: 'https://via.placeholder.com/400x300',
            date: '20 Juli 2025',
            description: 'Rak buku 5 tingkat dari kayu mahoni berkualitas dengan dimensi 180x80x30cm. Konstruksi kokoh dengan finishing natural untuk penyimpanan buku dan dekorasi ruangan.'
        },
        14: {
            name: 'Speaker Bluetooth',
            code: 'PRD-014',
            category: 'Elektronik',
            categoryClass: 'bg-blue-100 text-blue-800',
            spec: '50W RMS, Waterproof, 12 Hours Battery',
            image: 'https://via.placeholder.com/400x300',
            date: '18 Juli 2025',
            description: 'Speaker Bluetooth portable 50W RMS dengan sertifikasi waterproof. Baterai tahan hingga 12 jam untuk penggunaan outdoor dan indoor yang fleksibel.'
        },
        15: {
            name: 'Mesin Las Inverter',
            code: 'PRD-015',
            category: 'Mesin',
            categoryClass: 'bg-green-100 text-green-800',
            spec: '200A, IGBT Technology, Arc Force Control',
            image: 'https://via.placeholder.com/400x300',
            date: '16 Juli 2025',
            description: 'Mesin las inverter 200A dengan teknologi IGBT untuk efisiensi tinggi. Dilengkapi arc force control untuk stabilitas arc yang optimal dalam berbagai kondisi pengelasan.'
        }
    };

    function showProductDetail(productId) {
        const product = products[productId];
        if (!product) return;

        // Update modal content
        document.getElementById('modalProductTitle').textContent = product.name;
        document.getElementById('modalProductName').textContent = product.name;
        document.getElementById('modalProductCode').textContent = product.code;
        document.getElementById('modalProductSpec').textContent = product.spec;
        document.getElementById('modalProductImage').src = product.image;
        document.getElementById('modalProductImage').alt = product.name;
        document.getElementById('modalProductDescription').textContent = product.description;

        // Update category badge
        const categoryElement = document.getElementById('modalProductCategory');
        categoryElement.textContent = product.category;
        categoryElement.className = `inline-flex px-3 py-2 text-sm font-semibold rounded-xl shadow-lg backdrop-blur-sm ${product.categoryClass}`;

        // Show modal with animation
        const modal = document.getElementById('modalDetailProduk');
        const modalContent = document.getElementById('modalContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Animate modal appearance
        setTimeout(() => {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }, 10);
    }

    // Enhanced modal close function
    function closeModalDetail() {
        const modal = document.getElementById('modalDetailProduk');
        const modalContent = document.getElementById('modalContent');

        // Animate modal disappearance
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // Update close button onclick
    document.addEventListener('DOMContentLoaded', function() {
        // Update all close modal calls for detail modal
        const closeButtons = document.querySelectorAll('[onclick*="closeModal(\'modalDetailProduk\')"]');
        closeButtons.forEach(button => {
            button.setAttribute('onclick', 'closeModalDetail()');
        });

        // Close modal when clicking outside
        const modal = document.getElementById('modalDetailProduk');
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModalDetail();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('modalDetailProduk');
                if (!modal.classList.contains('hidden')) {
                    closeModalDetail();
                }
            }
        });

        // Initialize modal content styles
        const modalContent = document.getElementById('modalContent');
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
        modalContent.style.transition = 'all 0.3s ease-out';

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
    });
</script>
@endpush
