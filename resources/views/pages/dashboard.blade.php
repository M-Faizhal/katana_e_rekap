@extends('layouts.app')

@section('content')
<!-- Welcome Banner -->
<div class="bg-red-800 rounded-2xl p-8 mb-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Dashboard E-Rekap KATANA</h1>
            <p class="text-red-100 text-lg">Monitoring Omset, Hutang, dan Piutang Perusahaan</p>
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-chart-area text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 - Total Omset Bulan Ini -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center space-x-4">
            <div class="p-3 rounded-xl bg-green-600 shadow-md">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Omset Bulan Ini</h3>
                <p class="text-2xl font-bold text-green-600 mb-1">Rp 125M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                    <span class="text-sm font-medium text-green-500">+12%</span>
                    <span class="text-sm text-gray-500">dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2 - Total Proyek Aktif -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center space-x-4">
            <div class="p-3 rounded-xl bg-blue-600 shadow-md">
                <i class="fas fa-project-diagram text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Proyek Aktif</h3>
                <p class="text-2xl font-bold text-blue-600 mb-1">24</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                    <span class="text-sm font-medium text-green-500">+3</span>
                    <span class="text-sm text-gray-500">proyek baru</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3 - Total Hutang -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center space-x-4">
            <div class="p-3 rounded-xl bg-red-600 shadow-md">
                <i class="fas fa-credit-card text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Total Hutang</h3>
                <p class="text-2xl font-bold text-red-600 mb-1">Rp 45M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-exclamation-triangle text-orange-500 text-sm"></i>
                    <span class="text-sm font-medium text-orange-500">12</span>
                    <span class="text-sm text-gray-500">vendor pending</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4 - Total Piutang -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center space-x-4">
            <div class="p-3 rounded-xl bg-yellow-600 shadow-md">
                <i class="fas fa-hand-holding-usd text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Total Piutang</h3>
                <p class="text-2xl font-bold text-yellow-600 mb-1">Rp 78M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-clock text-yellow-500 text-sm"></i>
                    <span class="text-sm font-medium text-yellow-500">8</span>
                    <span class="text-sm text-gray-500">dinas pending</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Large Content Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Left Large Card - Grafik Omset Per Bulan -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Grafik Omset Per Bulan</h3>
            <div class="flex space-x-2">
                <button class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-medium hover:bg-green-700 transition-colors duration-200">2024</button>
                <button class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors duration-200">2023</button>
            </div>
        </div>
        <div class="h-80 flex items-center justify-center bg-gradient-to-br from-green-50 to-blue-50 rounded-xl relative">
            <!-- Simple Bar Chart Visualization -->
            <div class="w-full h-full p-4">
                <div class="flex items-end justify-between h-full space-x-2">
                    <!-- Jan -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 rounded-t-lg w-8" style="height: 45%"></div>
                        <span class="text-xs text-gray-600 mt-2">Jan</span>
                        <span class="text-xs text-gray-500">89M</span>
                    </div>
                    <!-- Feb -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 rounded-t-lg w-8" style="height: 52%"></div>
                        <span class="text-xs text-gray-600 mt-2">Feb</span>
                        <span class="text-xs text-gray-500">102M</span>
                    </div>
                    <!-- Mar -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 rounded-t-lg w-8" style="height: 38%"></div>
                        <span class="text-xs text-gray-600 mt-2">Mar</span>
                        <span class="text-xs text-gray-500">75M</span>
                    </div>
                    <!-- Apr -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-500 rounded-t-lg w-8" style="height: 65%"></div>
                        <span class="text-xs text-gray-600 mt-2">Apr</span>
                        <span class="text-xs text-gray-500">128M</span>
                    </div>
                    <!-- May -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-600 rounded-t-lg w-8" style="height: 70%"></div>
                        <span class="text-xs text-gray-600 mt-2">May</span>
                        <span class="text-xs text-gray-500">138M</span>
                    </div>
                    <!-- Jun -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-600 rounded-t-lg w-8" style="height: 58%"></div>
                        <span class="text-xs text-gray-600 mt-2">Jun</span>
                        <span class="text-xs text-gray-500">115M</span>
                    </div>
                    <!-- Jul -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-600 rounded-t-lg w-8" style="height: 75%"></div>
                        <span class="text-xs text-gray-600 mt-2">Jul</span>
                        <span class="text-xs text-gray-500">148M</span>
                    </div>
                    <!-- Aug -->
                    <div class="flex flex-col items-center">
                        <div class="bg-green-700 rounded-t-lg w-8" style="height: 63%"></div>
                        <span class="text-xs text-gray-600 mt-2">Aug</span>
                        <span class="text-xs text-gray-500 font-bold">125M</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Large Card - Omset Per Orang -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Omset Per Orang (Asal Proyek)</h3>
            <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Lihat Detail
            </button>
        </div>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Ahmad Sudirman</p>
                        <p class="text-sm text-gray-600">Project Manager</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-blue-600">Rp 45M</p>
                    <p class="text-xs text-gray-500">8 proyek</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Siti Rahayu</p>
                        <p class="text-sm text-gray-600">Senior Engineer</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-green-600">Rp 38M</p>
                    <p class="text-xs text-gray-500">6 proyek</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Budi Santoso</p>
                        <p class="text-sm text-gray-600">Lead Developer</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-purple-600">Rp 32M</p>
                    <p class="text-xs text-gray-500">5 proyek</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Rina Kartika</p>
                        <p class="text-sm text-gray-600">Business Analyst</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-orange-600">Rp 28M</p>
                    <p class="text-xs text-gray-500">4 proyek</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hutang dan Piutang Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Left Card - Hutang Vendor -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Hutang Vendor</h3>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm font-medium">12 Pending</span>
                <button class="text-red-600 hover:text-red-700 text-sm font-medium">
                    Lihat Semua
                </button>
            </div>
        </div>
        <div class="space-y-4 max-h-80 overflow-y-auto">
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-xl border-l-4 border-red-500">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-building text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">PT. Maju Bersama</p>
                        <p class="text-sm text-gray-600">Material konstruksi</p>
                        <p class="text-xs text-red-500 font-medium">Jatuh tempo: 15 Agt 2025</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-red-600">Rp 12.5M</p>
                    <span class="px-2 py-1 bg-red-200 text-red-700 rounded-full text-xs">Overdue</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl border-l-4 border-orange-500">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-truck text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">CV. Logistik Prima</p>
                        <p class="text-sm text-gray-600">Jasa transportasi</p>
                        <p class="text-xs text-orange-500 font-medium">Jatuh tempo: 20 Agt 2025</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-orange-600">Rp 8.2M</p>
                    <span class="px-2 py-1 bg-orange-200 text-orange-700 rounded-full text-xs">3 hari lagi</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl border-l-4 border-yellow-500">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-tools text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">UD. Teknik Jaya</p>
                        <p class="text-sm text-gray-600">Alat berat</p>
                        <p class="text-xs text-yellow-600 font-medium">Jatuh tempo: 25 Agt 2025</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-yellow-600">Rp 15.8M</p>
                    <span class="px-2 py-1 bg-yellow-200 text-yellow-700 rounded-full text-xs">8 hari lagi</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border-l-4 border-gray-400">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-hard-hat text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">PT. Kontraktor Ahli</p>
                        <p class="text-sm text-gray-600">Jasa konstruksi</p>
                        <p class="text-xs text-gray-600 font-medium">Jatuh tempo: 30 Agt 2025</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-gray-600">Rp 22.3M</p>
                    <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs">13 hari lagi</span>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-gray-600 font-medium">Total Hutang:</span>
                <span class="text-xl font-bold text-red-600">Rp 45.2M</span>
            </div>
        </div>
    </div>

    <!-- Right Card - Piutang Dinas -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Piutang Dinas</h3>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-sm font-medium">8 Pending</span>
                <button class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">
                    Lihat Semua
                </button>
            </div>
        </div>
        <div class="space-y-4 max-h-80 overflow-y-auto">
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border-l-4 border-green-500">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-university text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Dinas PU Kota Jakarta</p>
                        <p class="text-sm text-gray-600">Proyek jalan raya</p>
                        <p class="text-xs text-green-600 font-medium">Kontrak: #DPU-2024-001</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-green-600">Rp 25.5M</p>
                    <span class="px-2 py-1 bg-green-200 text-green-700 rounded-full text-xs">90% selesai</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border-l-4 border-blue-500">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-building-shield text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Pemkot Surabaya</p>
                        <p class="text-sm text-gray-600">Pembangunan gedung</p>
                        <p class="text-xs text-blue-600 font-medium">Kontrak: #PKS-2024-007</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-blue-600">Rp 18.7M</p>
                    <span class="px-2 py-1 bg-blue-200 text-blue-700 rounded-full text-xs">75% selesai</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border-l-4 border-purple-500">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-water text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">PDAM Bandung</p>
                        <p class="text-sm text-gray-600">Infrastruktur air</p>
                        <p class="text-xs text-purple-600 font-medium">Kontrak: #PDAM-2024-012</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-purple-600">Rp 12.3M</p>
                    <span class="px-2 py-1 bg-purple-200 text-purple-700 rounded-full text-xs">60% selesai</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-xl border-l-4 border-indigo-500">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-road text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Dinas Perhubungan Medan</p>
                        <p class="text-sm text-gray-600">Terminal bus</p>
                        <p class="text-xs text-indigo-600 font-medium">Kontrak: #DPH-2024-003</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-indigo-600">Rp 21.8M</p>
                    <span class="px-2 py-1 bg-indigo-200 text-indigo-700 rounded-full text-xs">45% selesai</span>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-gray-600 font-medium">Total Piutang:</span>
                <span class="text-xl font-bold text-yellow-600">Rp 78.3M</span>
            </div>
        </div>
    </div>
</div>

<!-- Indonesia Map Section -->
<div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Distribusi Geografis Proyek</h3>
        <div class="flex space-x-2">
            <button class="px-4 py-2 bg-red-800 text-white rounded-xl text-sm font-medium hover:bg-red-900 transition-colors duration-200">Real-time</button>
            <button class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors duration-200">Historical</button>
        </div>
    </div>

    <div class="h-96 bg-gradient-to-br from-blue-50 via-white to-green-50 rounded-2xl relative overflow-hidden shadow-inner border border-gray-100">
        <!-- SVG Indonesia Map -->
        <svg viewBox="0 0 800 400" class="w-full h-full">
            <!-- Background water -->
            <defs>
                <linearGradient id="waterGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#EBF8FF;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#DBEAFE;stop-opacity:1" />
                </linearGradient>
                <linearGradient id="landGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#10B981;stop-opacity:0.9" />
                    <stop offset="100%" style="stop-color:#059669;stop-opacity:0.9" />
                </linearGradient>
            </defs>

            <rect width="800" height="400" fill="url(#waterGradient)"/>

            <!-- Simplified Indonesia archipelago shape with enhanced styling -->
            <g fill="url(#landGradient)" stroke="#065F46" stroke-width="1.5">
                <!-- Sumatra -->
                <ellipse cx="120" cy="180" rx="60" ry="120" opacity="0.95"/>
                <!-- Java -->
                <ellipse cx="280" cy="280" rx="140" ry="30" opacity="0.95"/>
                <!-- Kalimantan -->
                <ellipse cx="350" cy="150" rx="100" ry="80" opacity="0.95"/>
                <!-- Sulawesi -->
                <path d="M450 120 Q480 140 470 180 Q460 200 480 220 Q500 200 520 180 Q510 140 540 120 Q520 100 480 110 Q460 100 450 120" opacity="0.95"/>
                <!-- Papua -->
                <ellipse cx="650" cy="200" rx="120" ry="80" opacity="0.95"/>
                <!-- Smaller islands -->
                <circle cx="200" cy="320" r="20" opacity="0.95"/>
                <circle cx="380" cy="320" r="15" opacity="0.95"/>
                <circle cx="500" cy="280" r="25" opacity="0.95"/>
                <circle cx="580" cy="140" r="18" opacity="0.95"/>
            </g>

            <!-- Enhanced location markers -->
            <g>
                <!-- Jakarta -->
                <circle cx="280" cy="280" r="10" fill="#DC2626" stroke="#FFF" stroke-width="2"/>
                <circle cx="280" cy="280" r="15" fill="#DC2626" opacity="0.3"/>
                <text x="295" y="285" fill="#1F2937" font-size="12" font-weight="bold">Jakarta: 8 proyek</text>

                <!-- Surabaya -->
                <circle cx="360" cy="290" r="8" fill="#DC2626" stroke="#FFF" stroke-width="2"/>
                <circle cx="360" cy="290" r="12" fill="#DC2626" opacity="0.3"/>
                <text x="375" y="295" fill="#1F2937" font-size="11" font-weight="bold">Surabaya: 6 proyek</text>

                <!-- Medan -->
                <circle cx="120" cy="120" r="6" fill="#DC2626" stroke="#FFF" stroke-width="2"/>
                <circle cx="120" cy="120" r="10" fill="#DC2626" opacity="0.3"/>
                <text x="135" y="125" fill="#1F2937" font-size="11" font-weight="bold">Medan: 4 proyek</text>

                <!-- Bandung -->
                <circle cx="260" cy="290" r="5" fill="#DC2626" stroke="#FFF" stroke-width="2"/>
                <circle cx="260" cy="290" r="8" fill="#DC2626" opacity="0.3"/>
                <text x="275" y="295" fill="#1F2937" font-size="10" font-weight="bold">Bandung: 3 proyek</text>

                <!-- Makassar -->
                <circle cx="500" cy="220" r="5" fill="#DC2626" stroke="#FFF" stroke-width="2"/>
                <circle cx="500" cy="220" r="8" fill="#DC2626" opacity="0.3"/>
                <text x="515" y="225" fill="#1F2937" font-size="10" font-weight="bold">Makassar: 3 proyek</text>
            </g>
        </svg>

        <!-- Enhanced Map Legend -->
        <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-200">
            <h4 class="font-bold text-gray-800 mb-3">Legenda</h4>
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <div class="w-4 h-4 bg-red-600 rounded-full shadow-sm"></div>
                    <span class="text-sm text-gray-700 font-medium">Lokasi proyek aktif</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-4 h-4 bg-green-500 rounded-sm shadow-sm"></div>
                    <span class="text-sm text-gray-700 font-medium">Wilayah Indonesia</span>
                </div>
            </div>
        </div>

        <!-- Enhanced Statistics Box -->
        <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-200">
            <h4 class="font-bold text-gray-800 mb-3">Distribusi Proyek</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Jawa:</span>
                    <span class="font-bold text-gray-800">17 proyek</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Sumatra:</span>
                    <span class="font-bold text-gray-800">4 proyek</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Lainnya:</span>
                    <span class="font-bold text-gray-800">3 proyek</span>
                </div>
                <hr class="border-gray-200 my-2">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium">Total:</span>
                    <span class="font-bold text-red-600">24 proyek</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
