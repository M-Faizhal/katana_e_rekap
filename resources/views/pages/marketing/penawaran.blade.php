@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Penawaran</h1>
            <p class="text-red-100 text-sm sm:text-base">Kelola penawaran dan proposal marketing</p>
        </div>
        <div class="hidden sm:block lg:block">
            <div class="flex items-center space-x-4">
              <i class="fas fa-file-contract text-3xl sm:text-4xl lg:text-5xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-full bg-blue-100 text-blue-600 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-file-contract text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Penawaran</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">35</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-full bg-yellow-100 text-yellow-600 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-clock text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Proses</p>
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
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Berhasil</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">12</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-full bg-red-100 text-red-600 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-times-circle text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Gagal</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">8</p>
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
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Daftar Penawaran</h2>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Kelola penawaran dan proposal marketing</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Cari berdasarkan nomor penawaran, proyek, atau klien..." 
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <select class="px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="proses">Proses</option>
                    <option value="berhasil">Berhasil</option>
                    <option value="gagal">Gagal</option>
                </select>
            </div>
        </div>
    </div>

    <!-- List Layout -->
    <div class="p-4 sm:p-6">
        <!-- Table Header -->
        <div class="hidden lg:grid lg:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-lg mb-4 text-sm font-semibold text-gray-700">
            <div class="col-span-2">No. Penawaran</div>
            <div class="col-span-2">Kode Proyek</div>
            <div class="col-span-3">Nama Proyek</div>
            <div class="col-span-2">Klien</div>
            <div class="col-span-1">Status</div>
            <div class="col-span-2">Aksi</div>
        </div>

        <!-- List Items -->
        <div class="space-y-3 sm:space-y-4">
            <!-- Sample Item 1 -->
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                <!-- Desktop Grid Layout -->
                <div class="hidden lg:grid lg:grid-cols-12 gap-4 items-center">
                    <div class="col-span-2">
                        <h3 class="font-semibold text-gray-900">PNW-2024-001</h3>
                        <p class="text-sm text-gray-600">15 Jan 2024</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PRJ-2024-001</p>
                        <p class="text-sm text-gray-600">Kode Proyek</p>
                    </div>
                    <div class="col-span-3">
                        <p class="font-medium text-gray-900">Pembangunan Gedung Kantor</p>
                        <p class="text-sm text-gray-600">Project Description</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PT. ABC Company</p>
                        <p class="text-sm text-gray-600">Client</p>
                    </div>
                    <div class="col-span-1">
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Proses
                        </span>
                    </div>
                    <div class="col-span-2 flex space-x-2">
                        <button onclick="viewDetailPenawaran(1)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPenawaran(1)" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Tablet Layout -->
                <div class="hidden md:block lg:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-lg truncate">PNW-2024-001</h3>
                            <p class="text-sm text-gray-600">15 Jan 2024</p>
                        </div>
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 ml-3">
                            Proses
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</p>
                            <p class="font-medium text-gray-900">PRJ-2024-001</p>
                            <p class="text-sm text-gray-600 truncate">Pembangunan Gedung Kantor</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</p>
                            <p class="font-medium text-gray-900 truncate">PT. ABC Company</p>
                        </div>
                    </div>
                    <div class="flex space-x-3 pt-3 border-t border-gray-100">
                        <button onclick="viewDetailPenawaran(1)" class="flex-1 bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-eye mr-2"></i>Detail
                        </button>
                        <button onclick="editPenawaran(1)" class="flex-1 bg-amber-50 text-amber-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-100 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Card Layout -->
                <div class="block md:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-base">PNW-2024-001</h3>
                            <p class="text-xs text-gray-600">15 Jan 2024</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 ml-2">
                            Proses
                        </span>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Proyek:</span>
                            <span class="text-gray-900 text-xs">PRJ-2024-001</span>
                        </div>
                        <div class="flex items-start text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Nama:</span>
                            <span class="text-gray-900 text-xs">Pembangunan Gedung Kantor</span>
                        </div>
                        <div class="flex items-start text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Klien:</span>
                            <span class="text-gray-900 text-xs">PT. ABC Company</span>
                        </div>
                    </div>
                    <div class="flex space-x-2 pt-3 border-t border-gray-100">
                        <button onclick="viewDetailPenawaran(1)" class="flex-1 bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </button>
                        <button onclick="editPenawaran(1)" class="flex-1 bg-amber-50 text-amber-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-amber-100 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sample Item 2 -->
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                <!-- Desktop Grid Layout -->
                <div class="hidden lg:grid lg:grid-cols-12 gap-4 items-center">
                    <div class="col-span-2">
                        <h3 class="font-semibold text-gray-900">PNW-2024-002</h3>
                        <p class="text-sm text-gray-600">20 Jan 2024</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PRJ-2024-002</p>
                        <p class="text-sm text-gray-600">Kode Proyek</p>
                    </div>
                    <div class="col-span-3">
                        <p class="font-medium text-gray-900">Renovasi Pabrik</p>
                        <p class="text-sm text-gray-600">Project Description</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PT. XYZ Manufacturing</p>
                        <p class="text-sm text-gray-600">Client</p>
                    </div>
                    <div class="col-span-1">
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Berhasil
                        </span>
                    </div>
                    <div class="col-span-2 flex space-x-2">
                        <button onclick="viewDetailPenawaran(2)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPenawaran(2)" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Tablet Layout -->
                <div class="hidden md:block lg:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-lg truncate">PNW-2024-002</h3>
                            <p class="text-sm text-gray-600">20 Jan 2024</p>
                        </div>
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ml-3">
                            Berhasil
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</p>
                            <p class="font-medium text-gray-900">PRJ-2024-002</p>
                            <p class="text-sm text-gray-600 truncate">Renovasi Pabrik</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</p>
                            <p class="font-medium text-gray-900 truncate">PT. XYZ Manufacturing</p>
                        </div>
                    </div>
                    <div class="flex space-x-3 pt-3 border-t border-gray-100">
                        <button onclick="viewDetailPenawaran(2)" class="flex-1 bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-eye mr-2"></i>Detail
                        </button>
                        <button onclick="editPenawaran(2)" class="flex-1 bg-amber-50 text-amber-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-100 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Card Layout -->
                <div class="block md:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-base">PNW-2024-002</h3>
                            <p class="text-xs text-gray-600">20 Jan 2024</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ml-2">
                            Berhasil
                        </span>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Proyek:</span>
                            <span class="text-gray-900 text-xs">PRJ-2024-002</span>
                        </div>
                        <div class="flex items-start text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Nama:</span>
                            <span class="text-gray-900 text-xs">Renovasi Pabrik</span>
                        </div>
                        <div class="flex items-start text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Klien:</span>
                            <span class="text-gray-900 text-xs">PT. XYZ Manufacturing</span>
                        </div>
                    </div>
                    <div class="flex space-x-2 pt-3 border-t border-gray-100">
                        <button onclick="viewDetailPenawaran(2)" class="flex-1 bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </button>
                        <button onclick="editPenawaran(2)" class="flex-1 bg-amber-50 text-amber-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-amber-100 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sample Item 3 -->
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                <!-- Desktop Grid Layout -->
                <div class="hidden lg:grid lg:grid-cols-12 gap-4 items-center">
                    <div class="col-span-2">
                        <h3 class="font-semibold text-gray-900">PNW-2024-003</h3>
                        <p class="text-sm text-gray-600">25 Jan 2024</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PRJ-2024-003</p>
                        <p class="text-sm text-gray-600">Kode Proyek</p>
                    </div>
                    <div class="col-span-3">
                        <p class="font-medium text-gray-900">Pembangunan Warehouse</p>
                        <p class="text-sm text-gray-600">Project Description</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PT. Logistik Nusantara</p>
                        <p class="text-sm text-gray-600">Client</p>
                    </div>
                    <div class="col-span-1">
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            Gagal
                        </span>
                    </div>
                    <div class="col-span-2 flex space-x-2">
                        <button onclick="viewDetailPenawaran(3)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPenawaran(3)" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Tablet Layout -->
                <div class="hidden md:block lg:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-lg truncate">PNW-2024-003</h3>
                            <p class="text-sm text-gray-600">25 Jan 2024</p>
                        </div>
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 ml-3">
                            Gagal
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</p>
                            <p class="font-medium text-gray-900">PRJ-2024-003</p>
                            <p class="text-sm text-gray-600 truncate">Pembangunan Warehouse</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</p>
                            <p class="font-medium text-gray-900 truncate">PT. Logistik Nusantara</p>
                        </div>
                    </div>
                    <div class="flex space-x-3 pt-3 border-t border-gray-100">
                        <button onclick="viewDetailPenawaran(3)" class="flex-1 bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-eye mr-2"></i>Detail
                        </button>
                        <button onclick="editPenawaran(3)" class="flex-1 bg-amber-50 text-amber-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-100 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Card Layout -->
                <div class="block md:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-base">PNW-2024-003</h3>
                            <p class="text-xs text-gray-600">25 Jan 2024</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 ml-2">
                            Gagal
                        </span>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Proyek:</span>
                            <span class="text-gray-900 text-xs">PRJ-2024-003</span>
                        </div>
                        <div class="flex items-start text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Nama:</span>
                            <span class="text-gray-900 text-xs">Pembangunan Warehouse</span>
                        </div>
                        <div class="flex items-start text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Klien:</span>
                            <span class="text-gray-900 text-xs">PT. Logistik Nusantara</span>
                        </div>
                    </div>
                    <div class="flex space-x-2 pt-3 border-t border-gray-100">
                        <button onclick="viewDetailPenawaran(3)" class="flex-1 bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </button>
                        <button onclick="editPenawaran(3)" class="flex-1 bg-amber-50 text-amber-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-amber-100 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sample Item 4 -->
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                <!-- Desktop Grid Layout -->
                <div class="hidden lg:grid lg:grid-cols-12 gap-4 items-center">
                    <div class="col-span-2">
                        <h3 class="font-semibold text-gray-900">PNW-2024-005</h3>
                        <p class="text-sm text-gray-600">30 Jan 2024</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PRJ-2024-005</p>
                        <p class="text-sm text-gray-600">Kode Proyek</p>
                    </div>
                    <div class="col-span-3">
                        <p class="font-medium text-gray-900">Renovasi Hotel</p>
                        <p class="text-sm text-gray-600">Project Description</p>
                    </div>
                    <div class="col-span-2">
                        <p class="font-medium text-gray-900">PT. Hospitality Group</p>
                        <p class="text-sm text-gray-600">Client</p>
                    </div>
                    <div class="col-span-1">
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Proses
                        </span>
                    </div>
                    <div class="col-span-2 flex space-x-2">
                        <button onclick="viewDetailPenawaran(4)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPenawaran(4)" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Tablet Layout -->
                <div class="hidden md:block lg:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-lg truncate">PNW-2024-005</h3>
                            <p class="text-sm text-gray-600">30 Jan 2024</p>
                        </div>
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 ml-3">
                            Proses
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</p>
                            <p class="font-medium text-gray-900">PRJ-2024-005</p>
                            <p class="text-sm text-gray-600 truncate">Renovasi Hotel</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</p>
                            <p class="font-medium text-gray-900 truncate">PT. Hospitality Group</p>
                        </div>
                    </div>
                    <div class="flex space-x-3 pt-3 border-t border-gray-100">
                        <button onclick="viewDetailPenawaran(4)" class="flex-1 bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-eye mr-2"></i>Detail
                        </button>
                        <button onclick="editPenawaran(4)" class="flex-1 bg-amber-50 text-amber-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-100 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Card Layout -->
                <div class="block md:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-base">PNW-2024-005</h3>
                            <p class="text-xs text-gray-600">30 Jan 2024</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 ml-2">
                            Proses
                        </span>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Proyek:</span>
                            <span class="text-gray-900 text-xs">PRJ-2024-005</span>
                        </div>
                        <div class="flex items-start text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Nama:</span>
                            <span class="text-gray-900 text-xs">Renovasi Hotel</span>
                        </div>
                        <div class="flex items-start text-sm">
                            <span class="font-medium text-gray-500 w-16 flex-shrink-0">Klien:</span>
                            <span class="text-gray-900 text-xs">PT. Hospitality Group</span>
                        </div>
                    </div>
                    <div class="flex space-x-2 pt-3 border-t border-gray-100">
                        <button onclick="viewDetailPenawaran(4)" class="flex-1 bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </button>
                        <button onclick="editPenawaran(4)" class="flex-1 bg-amber-50 text-amber-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-amber-100 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-3 sm:space-y-0">
            <div class="text-xs sm:text-sm text-gray-700 text-center sm:text-left">
                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">4</span> dari <span class="font-medium">27</span> hasil
            </div>
            
            <!-- Mobile Pagination (Simple) -->
            <div class="flex sm:hidden items-center justify-center space-x-3">
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 min-h-[44px] flex items-center" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span class="text-sm font-medium text-gray-700 px-3 py-2">1 / 7</span>
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

<!-- Include Modal Components -->
@include('pages.marketing.penawaran-components.detail')
@include('pages.marketing.penawaran-components.edit')

<!-- Include Modal Functions -->
<script>
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
</script>

@endsection
