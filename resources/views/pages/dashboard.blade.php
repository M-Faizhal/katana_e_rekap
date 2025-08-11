@extends('layouts.app')

@section('content')
<!-- Welcome Banner -->
<div class="bg-red-800 rounded-2xl p-8 mb-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Selamat Datang di KATANA Dashboard</h1>
            <p class="text-red-100 text-lg">Kelola bisnis Anda dengan mudah dan efisien</p>
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-chart-line text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Card 1 - Penjualan -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center space-x-4">
            <div class="p-3 rounded-xl bg-red-900 shadow-md">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Total Penjualan</h3>
                <p class="text-2xl font-bold text-red-800 mb-1">Rp 125M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                    <span class="text-sm font-medium text-green-500">+12%</span>
                    <span class="text-sm text-gray-500">dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2 - Pelanggan -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center space-x-4">
            <div class="p-3 rounded-xl bg-red-800 shadow-md">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Total Pelanggan</h3>
                <p class="text-2xl font-bold text-red-700 mb-1">1,245</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                    <span class="text-sm font-medium text-green-500">+8%</span>
                    <span class="text-sm text-gray-500">pelanggan baru</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3 - Pendapatan -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center space-x-4">
            <div class="p-3 rounded-xl bg-red-700 shadow-md">
                <i class="fas fa-money-bill-wave text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Pendapatan Hari Ini</h3>
                <p class="text-2xl font-bold text-red-800 mb-1">Rp 8.5M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-down text-red-500 text-sm"></i>
                    <span class="text-sm font-medium text-red-500">-3%</span>
                    <span class="text-sm text-gray-500">dari kemarin</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Large Content Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Left Large Card - Chart -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Grafik Penjualan</h3>
            <div class="flex space-x-2">
                <button class="px-4 py-2 bg-red-800 text-white rounded-xl text-sm font-medium hover:bg-red-900 transition-colors duration-200">2024</button>
                <button class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors duration-200">2023</button>
            </div>
        </div>
        <div class="h-80 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
            <div class="text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-bar text-red-800 text-3xl"></i>
                </div>
                <p class="text-gray-600 font-medium mb-2">Chart Penjualan Bulanan</p>
                <p class="text-sm text-gray-500">Integrasi dengan Chart.js</p>
            </div>
        </div>
    </div>

    <!-- Right Large Card - Activities -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Aktivitas Terbaru</h3>
            <button class="text-red-800 hover:text-red-900 text-sm font-medium">
                Lihat Semua
            </button>
        </div>
        <div class="space-y-4">
            <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-shopping-cart text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Pesanan Baru Diterima</p>
                    <p class="text-sm text-gray-600">Order #1234 - Rp 2,500,000</p>
                    <p class="text-xs text-gray-500 mt-1">2 menit yang lalu</p>
                </div>
            </div>

            <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-user-plus text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Pelanggan Baru Terdaftar</p>
                    <p class="text-sm text-gray-600">John Doe bergabung</p>
                    <p class="text-xs text-gray-500 mt-1">5 menit yang lalu</p>
                </div>
            </div>

            <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Stock Produk Menipis</p>
                    <p class="text-sm text-gray-600">Produk ABC tinggal 5 unit</p>
                    <p class="text-xs text-gray-500 mt-1">10 menit yang lalu</p>
                </div>
            </div>

            <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-credit-card text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Pembayaran Diterima</p>
                    <p class="text-sm text-gray-600">Invoice #5678 telah dibayar</p>
                    <p class="text-xs text-gray-500 mt-1">15 menit yang lalu</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Indonesia Map Section -->
<div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Sebaran Geografis Pelanggan</h3>
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
                <text x="295" y="285" fill="#1F2937" font-size="12" font-weight="bold">Jakarta: 450</text>

                <!-- Surabaya -->
                <circle cx="360" cy="290" r="8" fill="#DC2626" stroke="#FFF" stroke-width="2"/>
                <circle cx="360" cy="290" r="12" fill="#DC2626" opacity="0.3"/>
                <text x="375" y="295" fill="#1F2937" font-size="11" font-weight="bold">Surabaya: 280</text>

                <!-- Medan -->
                <circle cx="120" cy="120" r="6" fill="#DC2626" stroke="#FFF" stroke-width="2"/>
                <circle cx="120" cy="120" r="10" fill="#DC2626" opacity="0.3"/>
                <text x="135" y="125" fill="#1F2937" font-size="11" font-weight="bold">Medan: 190</text>

                <!-- Bandung -->
                <circle cx="260" cy="290" r="5" fill="#DC2626" stroke="#FFF" stroke-width="2"/>
                <circle cx="260" cy="290" r="8" fill="#DC2626" opacity="0.3"/>
                <text x="275" y="295" fill="#1F2937" font-size="10" font-weight="bold">Bandung: 120</text>

                <!-- Makassar -->
                <circle cx="500" cy="220" r="5" fill="#DC2626" stroke="#FFF" stroke-width="2"/>
                <circle cx="500" cy="220" r="8" fill="#DC2626" opacity="0.3"/>
                <text x="515" y="225" fill="#1F2937" font-size="10" font-weight="bold">Makassar: 95</text>
            </g>
        </svg>

        <!-- Enhanced Map Legend -->
        <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-200">
            <h4 class="font-bold text-gray-800 mb-3">Legenda</h4>
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <div class="w-4 h-4 bg-red-600 rounded-full shadow-sm"></div>
                    <span class="text-sm text-gray-700 font-medium">Kota dengan pelanggan</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-4 h-4 bg-green-500 rounded-sm shadow-sm"></div>
                    <span class="text-sm text-gray-700 font-medium">Wilayah Indonesia</span>
                </div>
            </div>
        </div>

        <!-- Enhanced Statistics Box -->
        <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-200">
            <h4 class="font-bold text-gray-800 mb-3">Total Distribusi</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Jawa:</span>
                    <span class="font-bold text-gray-800">850 pelanggan</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Sumatra:</span>
                    <span class="font-bold text-gray-800">285 pelanggan</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Lainnya:</span>
                    <span class="font-bold text-gray-800">110 pelanggan</span>
                </div>
                <hr class="border-gray-200 my-2">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium">Total:</span>
                    <span class="font-bold text-red-600">1,245 pelanggan</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection