@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Manajemen Vendor</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan pantau semua vendor</p>
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
                <i class="fas fa-handshake text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Vendor</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">28</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-check-circle text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Vendor Aktif</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">22</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-pause-circle text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Tidak Aktif</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">6</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-building text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Perusahaan</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">18</p>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Table -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-20">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Daftar Vendor</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola semua data vendor dan informasinya</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchVendor" placeholder="Cari vendor..."
                           class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <select id="filterJenis" class="px-3 py-2.5 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                    <option value="">Semua Jenis</option>
                    <option value="Perusahaan">Perusahaan</option>
                    <option value="Perorangan">Perorangan</option>
                    <option value="Koperasi">Koperasi</option>
                </select>
                <select id="filterStatus" class="px-3 py-2.5 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">Nama Vendor</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">No HP</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="vendorTableBody">
                <tr>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-900">VND001</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-building text-red-600 text-sm lg:text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold text-gray-900">PT. Teknologi Maju</p>
                                <p class="text-xs lg:text-sm text-gray-500">Jakarta Selatan</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">Perusahaan</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">info@tekmaju.co.id</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">021-5555-1234</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-1 lg:space-x-2">
                            <button onclick="detailVendor('VND001')" class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editVendor('VND001')" class="text-yellow-600 hover:text-yellow-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="hapusVendor('VND001')" class="text-red-600 hover:text-red-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-trash text-sm lg:text-base"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-900">VND002</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 text-sm lg:text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold text-gray-900">CV. Mandiri Sejahtera</p>
                                <p class="text-xs lg:text-sm text-gray-500">Bandung</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">Perorangan</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">mandiri@gmail.com</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">022-8888-5678</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-1 lg:space-x-2">
                            <button onclick="detailVendor('VND002')" class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editVendor('VND002')" class="text-yellow-600 hover:text-yellow-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="hapusVendor('VND002')" class="text-red-600 hover:text-red-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-trash text-sm lg:text-base"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-900">VND003</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-users text-green-600 text-sm lg:text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold text-gray-900">Koperasi Sukses Bersama</p>
                                <p class="text-xs lg:text-sm text-gray-500">Surabaya</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">Koperasi</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">koperasi@sukses.com</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">031-7777-9999</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Tidak Aktif
                        </span>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-1 lg:space-x-2">
                            <button onclick="detailVendor('VND003')" class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editVendor('VND003')" class="text-yellow-600 hover:text-yellow-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="hapusVendor('VND003')" class="text-red-600 hover:text-red-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-trash text-sm lg:text-base"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-900">VND004</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-industry text-purple-600 text-sm lg:text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold text-gray-900">PT. Global Industri</p>
                                <p class="text-xs lg:text-sm text-gray-500">Medan</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">Perusahaan</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">global@industri.co.id</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">061-4444-7777</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-1 lg:space-x-2">
                            <button onclick="detailVendor('VND004')" class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editVendor('VND004')" class="text-yellow-600 hover:text-yellow-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="hapusVendor('VND004')" class="text-red-600 hover:text-red-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-trash text-sm lg:text-base"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-900">VND005</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-orange-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-user-tie text-orange-600 text-sm lg:text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold text-gray-900">Budi Santoso</p>
                                <p class="text-xs lg:text-sm text-gray-500">Yogyakarta</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">Perorangan</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">budi.santoso@gmail.com</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">0274-5555-8888</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 lg:px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-1 lg:space-x-2">
                            <button onclick="detailVendor('VND005')" class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            <button onclick="editVendor('VND005')" class="text-yellow-600 hover:text-yellow-800 transition-colors p-1.5 lg:p-2">
                                <i class="fas fa-edit text-sm lg:text-base"></i>
                            </button>
                            <button onclick="hapusVendor('VND005')" class="text-red-600 hover:text-red-800 transition-colors p-1.5 lg:p-2">
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
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-building text-red-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">PT. Teknologi Maju</h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">VND001 • Jakarta Selatan</p>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">info@tekmaju.co.id</p>
                                <p class="text-sm text-gray-700">021-5555-1234</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                    Perusahaan
                                </span>
                            </div>
                            <div class="flex items-center justify-end mt-3 space-x-3">
                                <button onclick="detailVendor('VND001')" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                                <button onclick="editVendor('VND001')" class="text-yellow-600 hover:text-yellow-900 transition-colors p-2" title="Edit">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <button onclick="hapusVendor('VND001')" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
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
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">CV. Mandiri Sejahtera</h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">VND002 • Bandung</p>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">mandiri@gmail.com</p>
                                <p class="text-sm text-gray-700">022-8888-5678</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                    Perorangan
                                </span>
                            </div>
                            <div class="flex items-center justify-end mt-3 space-x-3">
                                <button onclick="detailVendor('VND002')" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                                <button onclick="editVendor('VND002')" class="text-yellow-600 hover:text-yellow-900 transition-colors p-2" title="Edit">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <button onclick="hapusVendor('VND002')" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
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
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">Koperasi Sukses Bersama</h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Tidak Aktif
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">VND003 • Surabaya</p>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">koperasi@sukses.com</p>
                                <p class="text-sm text-gray-700">031-7777-9999</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                    Koperasi
                                </span>
                            </div>
                            <div class="flex items-center justify-end mt-3 space-x-3">
                                <button onclick="detailVendor('VND003')" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                                <button onclick="editVendor('VND003')" class="text-yellow-600 hover:text-yellow-900 transition-colors p-2" title="Edit">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <button onclick="hapusVendor('VND003')" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
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
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-industry text-purple-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">PT. Global Industri</h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">VND004 • Medan</p>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">global@industri.co.id</p>
                                <p class="text-sm text-gray-700">061-4444-7777</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                    Perusahaan
                                </span>
                            </div>
                            <div class="flex items-center justify-end mt-3 space-x-3">
                                <button onclick="detailVendor('VND004')" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                                <button onclick="editVendor('VND004')" class="text-yellow-600 hover:text-yellow-900 transition-colors p-2" title="Edit">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <button onclick="hapusVendor('VND004')" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
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
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-tie text-orange-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">Budi Santoso</h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">VND005 • Yogyakarta</p>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">budi.santoso@gmail.com</p>
                                <p class="text-sm text-gray-700">0274-5555-8888</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                    Perorangan
                                </span>
                            </div>
                            <div class="flex items-center justify-end mt-3 space-x-3">
                                <button onclick="detailVendor('VND005')" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                                <button onclick="editVendor('VND005')" class="text-yellow-600 hover:text-yellow-900 transition-colors p-2" title="Edit">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <button onclick="hapusVendor('VND005')" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
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
        <div class="flex md:hidden items-center justify-between pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                1 / 6
            </div>
            <div class="flex space-x-2">
                <button class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Desktop Pagination -->
        <div class="hidden md:flex items-center justify-between pt-6 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                Menampilkan 1-5 dari 28 vendor
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>
                    Previous
                </button>
                <button class="px-3 py-1 text-sm bg-red-600 text-white rounded-lg">1</button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<button onclick="tambahVendor()" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 lg:bottom-16 lg:right-16 bg-red-600 hover:bg-red-700 text-white w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 z-50">
    <i class="fas fa-plus text-lg sm:text-xl"></i>
</button>

@include('pages.purchasing.vendor-components.tambah')
@include('pages.purchasing.vendor-components.edit')
@include('pages.purchasing.vendor-components.detail')
@include('pages.purchasing.vendor-components.hapus')
@include('components.success-modal')

<script>
// Sample vendor data
const vendorData = [
    {
        id: 'VND001',
        nama: 'PT. Teknologi Maju',
        jenis: 'Perusahaan',
        email: 'info@tekmaju.co.id',
        noHp: '021-5555-1234',
        alamat: 'Jl. Sudirman No. 123, Jakarta Selatan',
        status: 'Aktif'
    },
    {
        id: 'VND002',
        nama: 'CV. Mandiri Sejahtera',
        jenis: 'Perorangan',
        email: 'mandiri@gmail.com',
        noHp: '022-8888-5678',
        alamat: 'Jl. Asia Afrika No. 45, Bandung',
        status: 'Aktif'
    },
    {
        id: 'VND003',
        nama: 'Koperasi Sukses Bersama',
        jenis: 'Koperasi',
        email: 'koperasi@sukses.com',
        noHp: '031-7777-9999',
        alamat: 'Jl. Pemuda No. 78, Surabaya',
        status: 'Tidak Aktif'
    },
    {
        id: 'VND004',
        nama: 'PT. Global Industri',
        jenis: 'Perusahaan',
        email: 'global@industri.co.id',
        noHp: '061-4444-7777',
        alamat: 'Jl. Gatot Subroto No. 90, Medan',
        status: 'Aktif'
    },
    {
        id: 'VND005',
        nama: 'Budi Santoso',
        jenis: 'Perorangan',
        email: 'budi.santoso@gmail.com',
        noHp: '0274-5555-8888',
        alamat: 'Jl. Malioboro No. 56, Yogyakarta',
        status: 'Aktif'
    }
];

// Modal functions
function tambahVendor() {
    showModal('modalTambahVendor');
}

function editVendor(id) {
    const vendor = vendorData.find(v => v.id === id);
    if (vendor) {
        document.getElementById('editVendorId').value = vendor.id;
        document.getElementById('editNamaVendor').value = vendor.nama;
        document.getElementById('editJenisVendor').value = vendor.jenis;
        document.getElementById('editEmailVendor').value = vendor.email;
        document.getElementById('editNoHpVendor').value = vendor.noHp;
        document.getElementById('editAlamatVendor').value = vendor.alamat;
        document.getElementById('editStatusVendor').value = vendor.status;
        showModal('modalEditVendor');
    }
}

function detailVendor(id) {
    const vendor = vendorData.find(v => v.id === id);
    if (vendor) {
        document.getElementById('detailVendorId').textContent = vendor.id;
        document.getElementById('detailNamaVendor').textContent = vendor.nama;
        document.getElementById('detailJenisVendor').textContent = vendor.jenis;
        document.getElementById('detailEmailVendor').textContent = vendor.email;
        document.getElementById('detailNoHpVendor').textContent = vendor.noHp;
        document.getElementById('detailAlamatVendor').textContent = vendor.alamat;
        document.getElementById('detailStatusVendor').textContent = vendor.status;
        showModal('modalDetailVendor');
    }
}

function hapusVendor(id) {
    const vendor = vendorData.find(v => v.id === id);
    if (vendor) {
        document.getElementById('hapusVendorNama').textContent = vendor.nama;
        document.getElementById('hapusVendorId').value = id;
        showModal('modalHapusVendor');
    }
}

// Modal utility functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Legacy close functions (for backward compatibility)
function closeTambahVendor() {
    closeModal('modalTambahVendor');
}

function closeEditVendor() {
    closeModal('modalEditVendor');
}

function closeDetailVendor() {
    closeModal('modalDetailVendor');
}

function closeHapusVendor() {
    closeModal('modalHapusVendor');
}

// Form submit functions
function submitTambahVendor() {
    // Simulate API call
    setTimeout(() => {
        closeModal('modalTambahVendor');
        showSuccessModal('Vendor berhasil ditambahkan!');
        // Reset form
        const form = document.getElementById('formTambahVendor');
        if (form) form.reset();
    }, 500);
}

function submitEditVendor() {
    // Simulate API call
    setTimeout(() => {
        closeModal('modalEditVendor');
        showSuccessModal('Vendor berhasil diperbarui!');
    }, 500);
}

function confirmHapusVendor() {
    // Simulate API call
    setTimeout(() => {
        closeModal('modalHapusVendor');
        showSuccessModal('Vendor berhasil dihapus!');
    }, 500);
}

// Success modal function
function showSuccessModal(message) {
    document.getElementById('successMessage').textContent = message;
    document.getElementById('successModal').classList.remove('hidden');

    setTimeout(() => {
        document.getElementById('successModal').classList.add('hidden');
    }, 3000);
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
}

// Search and filter functions
document.getElementById('searchVendor').addEventListener('input', function() {
    // Implement search functionality
    console.log('Searching:', this.value);
});

document.getElementById('filterJenis').addEventListener('change', function() {
    // Implement filter functionality
    console.log('Filter by jenis:', this.value);
});

document.getElementById('filterStatus').addEventListener('change', function() {
    // Implement filter functionality
    console.log('Filter by status:', this.value);
});

// Product management variables
let vendorProducts = [];
let editVendorProducts = [];
let productIdCounter = 1;

// Function to update vendor product list display
function updateVendorProductList() {
    const listContainer = document.getElementById('vendorProductList');

    if (vendorProducts.length === 0) {
        listContainer.innerHTML = '<p class="text-gray-500 text-sm">Belum ada produk yang ditambahkan</p>';
        return;
    }

    listContainer.innerHTML = vendorProducts.map(product => `
        <div class="flex items-center justify-between bg-gray-50 p-2 rounded-lg">
            <div class="flex-1">
                <span class="font-medium text-gray-900">${product.name}</span>
                <span class="text-xs px-2 py-1 rounded-full ml-2 ${product.categoryClass}">${product.category}</span>
            </div>
            <button onclick="removeProductFromVendor(${product.id})" class="text-red-600 hover:text-red-800 p-1">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    `).join('');
}

// Function to update edit vendor product list display
function updateEditVendorProductList() {
    const listContainer = document.getElementById('editVendorProductList');

    if (editVendorProducts.length === 0) {
        listContainer.innerHTML = '<p class="text-gray-500 text-sm">Belum ada produk yang ditambahkan</p>';
        return;
    }

    listContainer.innerHTML = editVendorProducts.map(product => `
        <div class="flex items-center justify-between bg-gray-50 p-2 rounded-lg">
            <div class="flex-1">
                <span class="font-medium text-gray-900">${product.name}</span>
                <span class="text-xs px-2 py-1 rounded-full ml-2 ${product.categoryClass}">${product.category}</span>
            </div>
            <button onclick="removeProductFromEditVendor(${product.id})" class="text-red-600 hover:text-red-800 p-1">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    `).join('');
}

// Function to remove product from vendor list
function removeProductFromVendor(productId) {
    vendorProducts = vendorProducts.filter(product => product.id !== productId);
    updateVendorProductList();
}

// Function to remove product from edit vendor list
function removeProductFromEditVendor(productId) {
    editVendorProducts = editVendorProducts.filter(product => product.id !== productId);
    updateEditVendorProductList();
}

// Function to get category class for styling
function getCategoryClass(category) {
    const categoryClasses = {
        'Elektronik': 'bg-blue-100 text-blue-800',
        'Mesin': 'bg-green-100 text-green-800',
        'Meubel': 'bg-yellow-100 text-yellow-800',
        'Alat Tulis': 'bg-purple-100 text-purple-800',
        'Konsumsi': 'bg-orange-100 text-orange-800',
        'Lainnya': 'bg-gray-100 text-gray-800'
    };
    return categoryClasses[category] || 'bg-gray-100 text-gray-800';
}

// Function to generate product code
function generateProductCode() {
    const timestamp = Date.now().toString().slice(-6);
    return `PRD-${timestamp}`;
}

// Function to save products to main product page (simulate API call)
function saveProductsToMainPage() {
    const allProducts = [...vendorProducts, ...editVendorProducts];

    // In a real application, this would be an API call
    // For now, we'll store in localStorage to simulate persistence
    const existingProducts = JSON.parse(localStorage.getItem('mainProducts') || '[]');
    const updatedProducts = [...existingProducts, ...allProducts];
    localStorage.setItem('mainProducts', JSON.stringify(updatedProducts));

    console.log('Products saved to main page:', allProducts);
    return allProducts;
}

// Override submit functions to include product saving
const originalSubmitTambahVendor = submitTambahVendor;
submitTambahVendor = function() {
    // Save products to main page
    if (vendorProducts.length > 0) {
        saveProductsToMainPage();
    }

    // Call original function
    originalSubmitTambahVendor();

    // Reset product arrays
    vendorProducts = [];
    updateVendorProductList();
};

const originalSubmitEditVendor = submitEditVendor;
submitEditVendor = function() {
    // Save products to main page
    if (editVendorProducts.length > 0) {
        saveProductsToMainPage();
    }

    // Call original function
    originalSubmitEditVendor();

    // Reset product arrays
    editVendorProducts = [];
    updateEditVendorProductList();
};

// Image upload and preview functions
function previewImage(input, previewId, previewContainerId, uploadPromptId) {
    const file = input.files[0];
    if (file) {
        // Check file size (5MB limit)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 5MB.');
            input.value = '';
            return;
        }

        // Check file type
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar!');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById(previewId);
            const previewContainer = document.getElementById(previewContainerId);
            const uploadPrompt = document.getElementById(uploadPromptId);
            const removeBtn = document.getElementById(getRemoveButtonId(previewId));

            if (previewImg && previewContainer && uploadPrompt) {
                previewImg.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadPrompt.classList.add('hidden');

                if (removeBtn) {
                    removeBtn.classList.remove('hidden');
                }
            }
        };
        reader.readAsDataURL(file);
    }
}

function removeImage(previewId, previewContainerId, uploadPromptId, inputId) {
    const previewImg = document.getElementById(previewId);
    const previewContainer = document.getElementById(previewContainerId);
    const uploadPrompt = document.getElementById(uploadPromptId);
    const input = document.getElementById(inputId);
    const removeBtn = document.getElementById(getRemoveButtonId(previewId));

    if (previewImg && previewContainer && uploadPrompt && input) {
        previewImg.src = '';
        previewContainer.classList.add('hidden');
        uploadPrompt.classList.remove('hidden');
        input.value = '';

        if (removeBtn) {
            removeBtn.classList.add('hidden');
        }
    }
}

function getRemoveButtonId(previewId) {
    const mapping = {
        'imagePreview': 'removeImageBtn',
        'editImagePreview': 'editRemoveImageBtn',
        'productImagePreview': 'removeProductImageBtn',
        'editProductImagePreview': 'editRemoveProductImageBtn'
    };
    return mapping[previewId] || '';
}

// Enhanced product management functions with image support
function addProductToVendor() {
    const name = document.getElementById('newProductName').value.trim();
    const category = document.getElementById('newProductCategory').value;
    const price = document.getElementById('newProductPrice').value;
    const spec = document.getElementById('newProductSpec').value.trim();
    const description = document.getElementById('newProductDescription').value.trim();
    const imageInput = document.getElementById('newProductImage');

    if (!name || !category) {
        alert('Nama produk dan kategori harus diisi!');
        return;
    }

    let imageUrl = 'https://via.placeholder.com/400x300';
    if (imageInput.files[0]) {
        // In a real application, you would upload the file to server
        // For now, we'll use the file URL for preview
        imageUrl = URL.createObjectURL(imageInput.files[0]);
    }

    const product = {
        id: productIdCounter++,
        name: name,
        category: category,
        categoryClass: getCategoryClass(category),
        price: price || 0,
        spec: spec || 'Spesifikasi tidak tersedia',
        description: description || 'Deskripsi tidak tersedia',
        code: generateProductCode(),
        image: imageUrl
    };

    vendorProducts.push(product);
    updateVendorProductList();
    clearProductForm();
}

function addProductToEditVendor() {
    const name = document.getElementById('editNewProductName').value.trim();
    const category = document.getElementById('editNewProductCategory').value;
    const price = document.getElementById('editNewProductPrice').value;
    const spec = document.getElementById('editNewProductSpec').value.trim();
    const description = document.getElementById('editNewProductDescription').value.trim();
    const imageInput = document.getElementById('editNewProductImage');

    if (!name || !category) {
        alert('Nama produk dan kategori harus diisi!');
        return;
    }

    let imageUrl = 'https://via.placeholder.com/400x300';
    if (imageInput.files[0]) {
        // In a real application, you would upload the file to server
        // For now, we'll use the file URL for preview
        imageUrl = URL.createObjectURL(imageInput.files[0]);
    }

    const product = {
        id: productIdCounter++,
        name: name,
        category: category,
        categoryClass: getCategoryClass(category),
        price: price || 0,
        spec: spec || 'Spesifikasi tidak tersedia',
        description: description || 'Deskripsi tidak tersedia',
        code: generateProductCode(),
        image: imageUrl
    };

    editVendorProducts.push(product);
    updateEditVendorProductList();
    clearEditProductForm();
}

// Updated clear form functions
function clearProductForm() {
    document.getElementById('newProductName').value = '';
    document.getElementById('newProductCategory').value = '';
    document.getElementById('newProductPrice').value = '';
    document.getElementById('newProductSpec').value = '';
    document.getElementById('newProductDescription').value = '';

    // Clear image upload
    removeImage('productImagePreview', 'productImagePreviewContainer', 'productUploadPrompt', 'newProductImage');
}

function clearEditProductForm() {
    document.getElementById('editNewProductName').value = '';
    document.getElementById('editNewProductCategory').value = '';
    document.getElementById('editNewProductPrice').value = '';
    document.getElementById('editNewProductSpec').value = '';
    document.getElementById('editNewProductDescription').value = '';

    // Clear image upload
    removeImage('editProductImagePreview', 'editProductImagePreviewContainer', 'editProductUploadPrompt', 'editNewProductImage');
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    setupDragAndDrop();
});

function setupDragAndDrop() {
    const dropZones = document.querySelectorAll('[class*="border-dashed"]');

    dropZones.forEach(dropZone => {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);
    });
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight(e) {
    e.currentTarget.classList.add('border-red-400', 'bg-red-50');
}

function unhighlight(e) {
    e.currentTarget.classList.remove('border-red-400', 'bg-red-50');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;

    if (files.length > 0) {
        const dropZone = e.currentTarget;
        const fileInput = dropZone.querySelector('input[type="file"]');

        if (fileInput) {
            // Create a new FileList
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(files[0]);
            fileInput.files = dataTransfer.files;

            // Trigger the change event
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    }
}
</script>
@endsection
