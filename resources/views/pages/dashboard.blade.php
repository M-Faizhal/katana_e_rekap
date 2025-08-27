@extends('layouts.app')

@section('title', 'Dashboard - Cyber KATANA')

@section('content')
<div class="max-w-7xl mx-auto">
<!-- Welcome Banner -->
<div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Dashboard Cyber KATANA</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Monitoring Omset, Hutang, dan Piutang Perusahaan</p>
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-chart-area text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8 justify-items-center">
    <!-- Card 1 - Total Omset Bulan Ini -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 w-full max-w-sm">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="p-2 sm:p-3 rounded-xl bg-green-600 shadow-md">
                <i class="fas fa-chart-line text-white text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 truncate">Omset Bulan Ini</h3>
                <p class="text-xl sm:text-2xl font-bold text-green-600 mb-1">Rp 125M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                    <span class="text-sm font-medium text-green-500">+12%</span>
                    <span class="text-xs sm:text-sm text-gray-500 hidden sm:inline">dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2 - Total Proyek Aktif -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 w-full max-w-sm">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="p-2 sm:p-3 rounded-xl bg-blue-600 shadow-md">
                <i class="fas fa-project-diagram text-white text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 truncate">Proyek Aktif</h3>
                <p class="text-xl sm:text-2xl font-bold text-blue-600 mb-1">24</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                    <span class="text-sm font-medium text-green-500">+3</span>
                    <span class="text-xs sm:text-sm text-gray-500 hidden sm:inline">proyek baru</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3 - Total Hutang -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 w-full max-w-sm">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="p-2 sm:p-3 rounded-xl bg-red-600 shadow-md">
                <i class="fas fa-credit-card text-white text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 truncate">Total Hutang</h3>
                <p class="text-xl sm:text-2xl font-bold text-red-600 mb-1">Rp 45M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-exclamation-triangle text-orange-500 text-sm"></i>
                    <span class="text-sm font-medium text-orange-500">12</span>
                    <span class="text-xs sm:text-sm text-gray-500 hidden sm:inline">vendor pending</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4 - Total Piutang -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 w-full max-w-sm">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="p-2 sm:p-3 rounded-xl bg-yellow-600 shadow-md">
                <i class="fas fa-hand-holding-usd text-white text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 truncate">Total Piutang</h3>
                <p class="text-xl sm:text-2xl font-bold text-yellow-600 mb-1">Rp 78M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-clock text-yellow-500 text-sm"></i>
                    <span class="text-sm font-medium text-yellow-500">8</span>
                    <span class="text-xs sm:text-sm text-gray-500 hidden sm:inline">dinas pending</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Large Content Cards -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8">
    <!-- Left Large Card - Grafik Omset Per Bulan -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Grafik Omset Per Bulan</h3>
            <div class="flex space-x-2">
                <button class="px-3 sm:px-4 py-2 bg-green-600 text-white rounded-xl text-xs sm:text-sm font-medium hover:bg-green-700 transition-colors duration-200">2024</button>
                <button class="px-3 sm:px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-xs sm:text-sm font-medium hover:bg-gray-200 transition-colors duration-200">2023</button>
            </div>
        </div>
        <div class="h-64 sm:h-80 flex items-center justify-center bg-gradient-to-br from-green-50 to-blue-50 rounded-xl relative">
            <!-- Simple Bar Chart Visualization -->
            <div class="w-full h-full p-2 sm:p-4">
                <div class="flex items-end justify-between h-full space-x-1 sm:space-x-2">
                    <!-- Jan -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 rounded-t-lg w-4 sm:w-6 lg:w-8" style="height: 45%"></div>
                        <span class="text-xs text-gray-600 mt-1 sm:mt-2">Jan</span>
                        <span class="text-xs text-gray-500 hidden sm:block">89M</span>
                    </div>
                    <!-- Feb -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 rounded-t-lg w-4 sm:w-6 lg:w-8" style="height: 52%"></div>
                        <span class="text-xs text-gray-600 mt-1 sm:mt-2">Feb</span>
                        <span class="text-xs text-gray-500 hidden sm:block">102M</span>
                    </div>
                    <!-- Mar -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 rounded-t-lg w-4 sm:w-6 lg:w-8" style="height: 38%"></div>
                        <span class="text-xs text-gray-600 mt-1 sm:mt-2">Mar</span>
                        <span class="text-xs text-gray-500 hidden sm:block">75M</span>
                    </div>
                    <!-- Apr -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 rounded-t-lg w-4 sm:w-6 lg:w-8" style="height: 65%"></div>
                        <span class="text-xs text-gray-600 mt-1 sm:mt-2">Apr</span>
                        <span class="text-xs text-gray-500 hidden sm:block">128M</span>
                    </div>
                    <!-- May -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-600 rounded-t-lg w-4 sm:w-6 lg:w-8" style="height: 70%"></div>
                        <span class="text-xs text-gray-600 mt-1 sm:mt-2">May</span>
                        <span class="text-xs text-gray-500 hidden sm:block">138M</span>
                    </div>
                    <!-- Jun -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-600 rounded-t-lg w-4 sm:w-6 lg:w-8" style="height: 58%"></div>
                        <span class="text-xs text-gray-600 mt-1 sm:mt-2">Jun</span>
                        <span class="text-xs text-gray-500 hidden sm:block">115M</span>
                    </div>
                    <!-- Jul -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-600 rounded-t-lg w-4 sm:w-6 lg:w-8" style="height: 75%"></div>
                        <span class="text-xs text-gray-600 mt-1 sm:mt-2">Jul</span>
                        <span class="text-xs text-gray-500 hidden sm:block">148M</span>
                    </div>
                    <!-- Aug -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-700 rounded-t-lg w-4 sm:w-6 lg:w-8" style="height: 63%"></div>
                        <span class="text-xs text-gray-600 mt-1 sm:mt-2">Aug</span>
                        <span class="text-xs text-gray-500 font-bold hidden sm:block">125M</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Large Card - Omset Per Orang -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Omset Per Orang (Asal Proyek)</h3>
            <button class="text-blue-600 hover:text-blue-700 text-xs sm:text-sm font-medium self-start sm:self-auto">
                Lihat Detail
            </button>
        </div>
        <div class="space-y-3 sm:space-y-4">
            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-user-tie text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">Ahmad Sudirman</p>
                        <p class="text-xs sm:text-sm text-gray-600">Project Manager</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-blue-600">Rp 45M</p>
                    <p class="text-xs text-gray-500">8 proyek</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-user-tie text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">Siti Rahayu</p>
                        <p class="text-xs sm:text-sm text-gray-600">Senior Engineer</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-green-600">Rp 38M</p>
                    <p class="text-xs text-gray-500">6 proyek</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-user-tie text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">Budi Santoso</p>
                        <p class="text-xs sm:text-sm text-gray-600">Lead Developer</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-purple-600">Rp 32M</p>
                    <p class="text-xs text-gray-500">5 proyek</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-user-tie text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">Rina Kartika</p>
                        <p class="text-xs sm:text-sm text-gray-600">Business Analyst</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-orange-600">Rp 28M</p>
                    <p class="text-xs text-gray-500">4 proyek</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hutang dan Piutang Section -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8">
    <!-- Left Card - Hutang Vendor -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Hutang Vendor</h3>
            <div class="flex items-center space-x-2">
                <span class="px-2 sm:px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs sm:text-sm font-medium">12 Pending</span>
                <button class="text-red-600 hover:text-red-700 text-xs sm:text-sm font-medium whitespace-nowrap">
                    Lihat Semua
                </button>
            </div>
        </div>
        <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 overflow-y-auto">
            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-xl border-l-4 border-red-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-building text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">PT. Maju Bersama</p>
                        <p class="text-xs sm:text-sm text-gray-600">Material konstruksi</p>
                        <p class="text-xs text-red-500 font-medium">Jatuh tempo: 15 Agt 2025</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-red-600">Rp 12.5M</p>
                    <span class="px-2 py-1 bg-red-200 text-red-700 rounded-full text-xs">Overdue</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl border-l-4 border-orange-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-truck text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">CV. Logistik Prima</p>
                        <p class="text-xs sm:text-sm text-gray-600">Jasa transportasi</p>
                        <p class="text-xs text-orange-500 font-medium">Jatuh tempo: 20 Agt 2025</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-orange-600">Rp 8.2M</p>
                    <span class="px-2 py-1 bg-orange-200 text-orange-700 rounded-full text-xs">3 hari lagi</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl border-l-4 border-yellow-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-tools text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">UD. Teknik Jaya</p>
                        <p class="text-xs sm:text-sm text-gray-600">Alat berat</p>
                        <p class="text-xs text-yellow-600 font-medium">Jatuh tempo: 25 Agt 2025</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-yellow-600">Rp 15.8M</p>
                    <span class="px-2 py-1 bg-yellow-200 text-yellow-700 rounded-full text-xs">8 hari lagi</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border-l-4 border-gray-400">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-hard-hat text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">PT. Kontraktor Ahli</p>
                        <p class="text-xs sm:text-sm text-gray-600">Jasa konstruksi</p>
                        <p class="text-xs text-gray-600 font-medium">Jatuh tempo: 30 Agt 2025</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-gray-600">Rp 22.3M</p>
                    <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs">13 hari lagi</span>
                </div>
            </div>
        </div>
        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-gray-600 font-medium text-sm sm:text-base">Total Hutang:</span>
                <span class="text-lg sm:text-xl font-bold text-red-600">Rp 45.2M</span>
            </div>
        </div>
    </div>

    <!-- Right Card - Piutang Dinas -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Piutang Dinas</h3>
            <div class="flex items-center space-x-2">
                <span class="px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs sm:text-sm font-medium">8 Pending</span>
                <button class="text-yellow-600 hover:text-yellow-700 text-xs sm:text-sm font-medium whitespace-nowrap">
                    Lihat Semua
                </button>
            </div>
        </div>
        <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 overflow-y-auto">
            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border-l-4 border-green-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-university text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">Dinas PU Kota Jakarta</p>
                        <p class="text-xs sm:text-sm text-gray-600">Proyek jalan raya</p>
                        <p class="text-xs text-green-600 font-medium">Kontrak: #DPU-2024-001</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-green-600">Rp 25.5M</p>
                    <span class="px-2 py-1 bg-green-200 text-green-700 rounded-full text-xs">90% selesai</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border-l-4 border-blue-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-building-shield text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">Pemkot Surabaya</p>
                        <p class="text-xs sm:text-sm text-gray-600">Pembangunan gedung</p>
                        <p class="text-xs text-blue-600 font-medium">Kontrak: #PKS-2024-007</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-blue-600">Rp 18.7M</p>
                    <span class="px-2 py-1 bg-blue-200 text-blue-700 rounded-full text-xs">75% selesai</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border-l-4 border-purple-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-water text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">PDAM Bandung</p>
                        <p class="text-xs sm:text-sm text-gray-600">Infrastruktur air</p>
                        <p class="text-xs text-purple-600 font-medium">Kontrak: #PDAM-2024-012</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-purple-600">Rp 12.3M</p>
                    <span class="px-2 py-1 bg-purple-200 text-purple-700 rounded-full text-xs">60% selesai</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-xl border-l-4 border-indigo-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-road text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">Dinas Perhubungan Medan</p>
                        <p class="text-xs sm:text-sm text-gray-600">Terminal bus</p>
                        <p class="text-xs text-indigo-600 font-medium">Kontrak: #DPH-2024-003</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-indigo-600">Rp 21.8M</p>
                    <span class="px-2 py-1 bg-indigo-200 text-indigo-700 rounded-full text-xs">45% selesai</span>
                </div>
            </div>
        </div>
        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-gray-600 font-medium text-sm sm:text-base">Total Piutang:</span>
                <span class="text-lg sm:text-xl font-bold text-yellow-600">Rp 78.3M</span>
            </div>
        </div>
    </div>
</div>

<!-- Indonesia Map Section -->
<div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100 mb-6 sm:mb-8">
    <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Distribusi Geografis Penjualan</h3>
        <div class="flex space-x-2">
            <button class="px-3 sm:px-4 py-2 bg-red-800 text-white rounded-xl text-xs sm:text-sm font-medium hover:bg-red-900 transition-colors duration-200">Real-time</button>
            <button class="px-3 sm:px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-xs sm:text-sm font-medium hover:bg-gray-200 transition-colors duration-200">Historical</button>
        </div>
    </div>

    <div class="h-64 sm:h-80 lg:h-96 bg-gradient-to-br from-blue-50 via-white to-green-50 rounded-2xl relative overflow-hidden shadow-inner border border-gray-100">
        <!-- Leaflet Map Container -->
        <div id="indonesiaMap" class="w-full h-full rounded-2xl z-10"></div>

        <!-- Collapsible Map Legend -->
        <div class="absolute bottom-1 sm:bottom-6 left-1 sm:left-6 z-50">
            <div class="bg-white/95 backdrop-blur-sm rounded-lg sm:rounded-xl shadow-lg border border-gray-200">
                <!-- Legend Button Header -->
                <button
                    onclick="toggleLegend()"
                    class="w-full flex items-center justify-between p-2 sm:p-3 hover:bg-gray-50 transition-colors duration-200 rounded-lg sm:rounded-xl"
                >
                    <h4 class="font-bold text-gray-800 text-xs sm:text-base">Legenda</h4>
                    <i id="legendIcon" class="fas fa-chevron-up text-gray-600 text-xs sm:text-sm transition-transform duration-200"></i>
                </button>

                <!-- Legend Content (Collapsible) -->
                <div id="legendContent" class="px-2 pb-2 sm:px-3 sm:pb-3 border-t border-gray-200">
                    <div class="space-y-0.5 sm:space-y-2 pt-1 sm:pt-2">
                        <div class="flex items-center space-x-1 sm:space-x-3">
                            <div class="w-2 h-2 sm:w-4 sm:h-4 bg-blue-500 rounded-sm shadow-sm flex-shrink-0"></div>
                            <span class="text-xs sm:text-sm text-gray-700 font-medium">> 100M (Sangat Tinggi)</span>
                        </div>
                        <div class="flex items-center space-x-1 sm:space-x-3">
                            <div class="w-2 h-2 sm:w-4 sm:h-4 bg-green-500 rounded-sm shadow-sm flex-shrink-0"></div>
                            <span class="text-xs sm:text-sm text-gray-700 font-medium">50M - 100M (Tinggi)</span>
                        </div>
                        <div class="flex items-center space-x-1 sm:space-x-3">
                            <div class="w-2 h-2 sm:w-4 sm:h-4 bg-orange-500 rounded-sm shadow-sm flex-shrink-0"></div>
                            <span class="text-xs sm:text-sm text-gray-700 font-medium">20M - 50M (Sedang)</span>
                        </div>
                        <div class="flex items-center space-x-1 sm:space-x-3">
                            <div class="w-2 h-2 sm:w-4 sm:h-4 bg-red-300 rounded-sm shadow-sm flex-shrink-0"></div>
                            <span class="text-xs sm:text-sm text-gray-700 font-medium">< 20M (Rendah)</span>
                        </div>
                        <div class="flex items-center space-x-1 sm:space-x-3">
                            <div class="w-2 h-2 sm:w-4 sm:h-4 bg-red-800 rounded-full shadow-sm flex-shrink-0 animate-none city-pulse-legend"></div>
                            <span class="text-xs sm:text-sm text-gray-700 font-medium">Kota Utama</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Collapsible Statistics Box -->
        <div class="absolute top-1 sm:top-6 right-1 sm:right-6 z-50">
            <div class="bg-white/95 backdrop-blur-sm rounded-lg sm:rounded-xl shadow-lg border border-gray-200">
                <!-- Statistics Button Header -->
                <button
                    onclick="toggleStats()"
                    class="w-full flex items-center justify-between p-2 sm:p-3 hover:bg-gray-50 transition-colors duration-200 rounded-lg sm:rounded-xl"
                >
                    <h4 class="font-bold text-gray-800 text-xs sm:text-base">Total Penjualan</h4>
                    <i id="statsIcon" class="fas fa-chevron-up text-gray-600 text-xs sm:text-sm transition-transform duration-200"></i>
                </button>

                <!-- Statistics Content (Collapsible) -->
                <div id="statsContent" class="px-2 pb-2 sm:px-3 sm:pb-3 border-t border-gray-200">
                    <div class="space-y-0.5 sm:space-y-2 text-xs sm:text-sm pt-1 sm:pt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Jawa:</span>
                            <span class="font-bold text-blue-600">Rp 125.5M</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Sumatra:</span>
                            <span class="font-bold text-green-600">Rp 45.8M</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Kalimantan:</span>
                            <span class="font-bold text-orange-600">Rp 28.3M</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Lainnya:</span>
                            <span class="font-bold text-gray-600">Rp 62.1M</span>
                        </div>
                        <hr class="border-gray-200 my-0.5 sm:my-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Total:</span>
                            <span class="font-bold text-red-600">Rp 261.7M</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
.city-pulse-legend {
    background-color: #991b1b !important;
}

.leaflet-popup-content-wrapper {
    background: rgba(0, 0, 0, 0.9);
    color: white;
    border-radius: 8px;
    padding: 0;
}

.leaflet-popup-content {
    margin: 0;
    padding: 12px;
    font-size: 13px;
}

.leaflet-popup-tip {
    background: rgba(0, 0, 0, 0.9);
}

.city-marker {
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
}

.city-marker:hover {
    transform: scale(1.2);
    box-shadow: 0 4px 8px rgba(0,0,0,0.4);
    animation: pulse-on-hover 0.6s ease-in-out;
}

@keyframes pulse-on-hover {
    0%, 100% {
        box-shadow: 0 4px 8px rgba(0,0,0,0.4);
    }
    50% {
        box-shadow: 0 4px 8px rgba(0,0,0,0.4), 0 0 0 10px rgba(255,255,255,0.3);
    }
}

/* Custom marker colors based on sales performance */
.marker-very-high { background-color: #3182CE; }
.marker-high { background-color: #48BB78; }
.marker-medium { background-color: #ED8936; }
.marker-low { background-color: #F56565; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map centered on Indonesia
    const map = L.map('indonesiaMap').setView([-2.5, 118], 5);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    // Sales data for Indonesian cities
    const salesData = [
        {
            name: 'Jakarta',
            position: [-6.2088, 106.8456],
            sales: 125.5,
            projects: 28,
            growth: 25,
            level: 'very-high'
        },
        {
            name: 'Surabaya',
            position: [-7.2575, 112.7521],
            sales: 45.8,
            projects: 12,
            growth: 18,
            level: 'high'
        },
        {
            name: 'Medan',
            position: [3.5952, 98.6722],
            sales: 28.3,
            projects: 8,
            growth: 12,
            level: 'medium'
        },
        {
            name: 'Bandung',
            position: [-6.9175, 107.6191],
            sales: 22.7,
            projects: 6,
            growth: 8,
            level: 'medium'
        },
        {
            name: 'Makassar',
            position: [-5.1477, 119.4327],
            sales: 15.3,
            projects: 4,
            growth: 15,
            level: 'medium'
        },
        {
            name: 'Palembang',
            position: [-2.9761, 104.7754],
            sales: 12.4,
            projects: 3,
            growth: 7,
            level: 'low'
        },
        {
            name: 'Semarang',
            position: [-6.9667, 110.4167],
            sales: 8.2,
            projects: 3,
            growth: 5,
            level: 'low'
        },
        {
            name: 'Balikpapan',
            position: [-1.2379, 116.8529],
            sales: 5.8,
            projects: 2,
            growth: 3,
            level: 'low'
        },
        {
            name: 'Jayapura',
            position: [-2.5489, 140.7197],
            sales: 3.2,
            projects: 1,
            growth: 2,
            level: 'low'
        }
    ];

    // Create custom divIcon for each city
    salesData.forEach(city => {
        const markerSize = city.level === 'very-high' ? 16 :
                          city.level === 'high' ? 14 :
                          city.level === 'medium' ? 12 : 10;

        const customIcon = L.divIcon({
            className: `city-marker marker-${city.level}`,
            html: '',
            iconSize: [markerSize, markerSize],
            iconAnchor: [markerSize/2, markerSize/2]
        });

        // Create marker
        const marker = L.marker(city.position, { icon: customIcon }).addTo(map);

        // Create popup content
        const popupContent = `
            <div style="color: white; font-family: system-ui;">
                <div style="font-weight: bold; margin-bottom: 4px; color: #FFF;">${city.name}</div>
                <div style="font-size: 12px; color: #D1D5DB;">Penjualan: Rp ${city.sales}M</div>
                <div style="font-size: 12px; color: #D1D5DB;">Proyek: ${city.projects} aktif</div>
                <div style="font-size: 12px; color: #86EFAC;">Pertumbuhan: +${city.growth}%</div>
            </div>
        `;

        // Bind popup to marker
        marker.bindPopup(popupContent, {
            offset: [0, -markerSize/2],
            closeButton: false,
            className: 'custom-popup'
        });

        // Show popup on hover, hide on mouseout
        marker.on('mouseover', function() {
            this.openPopup();
        });

        marker.on('mouseout', function() {
            this.closePopup();
        });
    });

    // Disable zoom on scroll to prevent accidental zooming
    map.scrollWheelZoom.disable();

    // Add zoom control back
    map.addControl(L.control.zoom({
        position: 'topright'
    }));

    // Custom control for enabling/disabling scroll zoom
    const scrollControl = L.control({position: 'topright'});
    scrollControl.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
        div.style.backgroundColor = 'white';
        div.style.backgroundImage = 'none';
        div.style.width = '30px';
        div.style.height = '30px';
        div.style.cursor = 'pointer';
        div.style.fontSize = '16px';
        div.style.display = 'flex';
        div.style.alignItems = 'center';
        div.style.justifyContent = 'center';
        div.innerHTML = '🔒';
        div.title = 'Toggle scroll zoom';

        div.onclick = function() {
            if (map.scrollWheelZoom.enabled()) {
                map.scrollWheelZoom.disable();
                div.innerHTML = '🔒';
                div.title = 'Enable scroll zoom';
            } else {
                map.scrollWheelZoom.enable();
                div.innerHTML = '🔓';
                div.title = 'Disable scroll zoom';
            }
        };

        return div;
    };
    scrollControl.addTo(map);
});

// Toggle functions for collapsible boxes
function toggleLegend() {
    const content = document.getElementById('legendContent');
    const icon = document.getElementById('legendIcon');

    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
        icon.style.transform = 'rotate(0deg)';
    } else {
        content.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
        icon.style.transform = 'rotate(180deg)';
    }
}

function toggleStats() {
    const content = document.getElementById('statsContent');
    const icon = document.getElementById('statsIcon');

    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
        icon.style.transform = 'rotate(0deg)';
    } else {
        content.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>
</div>
@endsection
