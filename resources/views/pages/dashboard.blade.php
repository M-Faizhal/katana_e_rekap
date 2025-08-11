@extends('layouts.app')

@section('content')
<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Card 1 - Penjualan -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 mr-4">
                <i class="fas fa-chart-line text-red-500 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Total Penjualan</h3>
                <p class="text-3xl font-bold text-red-600">Rp 125,000,000</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +12% dari bulan lalu
                </p>
            </div>
        </div>
    </div>

    <!-- Card 2 - Pelanggan -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <i class="fas fa-users text-blue-500 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Total Pelanggan</h3>
                <p class="text-3xl font-bold text-blue-600">1,245</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +8% pelanggan baru
                </p>
            </div>
        </div>
    </div>

    <!-- Card 3 - Pendapatan -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <i class="fas fa-money-bill-wave text-green-500 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Pendapatan Hari Ini</h3>
                <p class="text-3xl font-bold text-green-600">Rp 8,500,000</p>
                <p class="text-sm text-red-500">
                    <i class="fas fa-arrow-down"></i> -3% dari kemarin
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Large Content Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Left Large Card - Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Grafik Penjualan Bulanan</h3>
            <div class="flex space-x-2">
                <button class="px-3 py-1 bg-red-100 text-red-600 rounded-md text-sm">2024</button>
                <button class="px-3 py-1 bg-gray-100 text-gray-600 rounded-md text-sm">2023</button>
            </div>
        </div>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center">
                <i class="fas fa-chart-bar text-red-500 text-5xl mb-4"></i>
                <p class="text-gray-500">Chart akan ditampilkan di sini</p>
                <p class="text-sm text-gray-400">Integrasi dengan Chart.js atau library lainnya</p>
            </div>
        </div>
    </div>

    <!-- Right Large Card - Activities -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
        <div class="space-y-4">
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-green-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">Pesanan baru diterima</p>
                    <p class="text-sm text-gray-500">Order #1234 - Rp 2,500,000</p>
                    <p class="text-xs text-gray-400">2 menit yang lalu</p>
                </div>
            </div>

            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">Pelanggan baru terdaftar</p>
                    <p class="text-sm text-gray-500">John Doe bergabung</p>
                    <p class="text-xs text-gray-400">5 menit yang lalu</p>
                </div>
            </div>

            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">Stock produk menipis</p>
                    <p class="text-sm text-gray-500">Produk ABC tinggal 5 unit</p>
                    <p class="text-xs text-gray-400">10 menit yang lalu</p>
                </div>
            </div>

            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-red-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">Pembayaran diterima</p>
                    <p class="text-sm text-gray-500">Invoice #5678 telah dibayar</p>
                    <p class="text-xs text-gray-400">15 menit yang lalu</p>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center">
            <button class="text-red-600 hover:text-red-800 text-sm font-medium">
                Lihat semua aktivitas <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
    </div>
</div>

<!-- Indonesia Map Section -->
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold text-gray-800">Sebaran Geografis Pelanggan</h3>
        <div class="flex space-x-2">
            <button class="px-3 py-1 bg-red-100 text-red-600 rounded-md text-sm">Real-time</button>
            <button class="px-3 py-1 bg-gray-100 text-gray-600 rounded-md text-sm">Historical</button>
        </div>
    </div>

    <div class="h-80 bg-gradient-to-br from-blue-50 to-green-50 rounded-lg relative overflow-hidden">
        <!-- SVG Indonesia Map -->
        <svg viewBox="0 0 800 400" class="w-full h-full">
            <!-- Background water -->
            <rect width="800" height="400" fill="#E6F3FF"/>

            <!-- Simplified Indonesia archipelago shape -->
            <g fill="#22C55E" stroke="#16A34A" stroke-width="1">
                <!-- Sumatra -->
                <ellipse cx="120" cy="180" rx="60" ry="120" opacity="0.9"/>
                <!-- Java -->
                <ellipse cx="280" cy="280" rx="140" ry="30" opacity="0.9"/>
                <!-- Kalimantan -->
                <ellipse cx="350" cy="150" rx="100" ry="80" opacity="0.9"/>
                <!-- Sulawesi -->
                <path d="M450 120 Q480 140 470 180 Q460 200 480 220 Q500 200 520 180 Q510 140 540 120 Q520 100 480 110 Q460 100 450 120" opacity="0.9"/>
                <!-- Papua -->
                <ellipse cx="650" cy="200" rx="120" ry="80" opacity="0.9"/>
                <!-- Smaller islands -->
                <circle cx="200" cy="320" r="20" opacity="0.9"/>
                <circle cx="380" cy="320" r="15" opacity="0.9"/>
                <circle cx="500" cy="280" r="25" opacity="0.9"/>
                <circle cx="580" cy="140" r="18" opacity="0.9"/>
            </g>

            <!-- Location markers with data -->
            <g>
                <!-- Jakarta -->
                <circle cx="280" cy="280" r="8" fill="#DC2626"/>
                <text x="290" y="285" fill="#1F2937" font-size="10" font-weight="bold">Jakarta: 450</text>

                <!-- Surabaya -->
                <circle cx="360" cy="290" r="6" fill="#DC2626"/>
                <text x="370" y="295" fill="#1F2937" font-size="10" font-weight="bold">Surabaya: 280</text>

                <!-- Medan -->
                <circle cx="120" cy="120" r="5" fill="#DC2626"/>
                <text x="130" y="125" fill="#1F2937" font-size="10" font-weight="bold">Medan: 190</text>

                <!-- Bandung -->
                <circle cx="260" cy="290" r="4" fill="#DC2626"/>
                <text x="270" y="295" fill="#1F2937" font-size="10" font-weight="bold">Bandung: 120</text>

                <!-- Makassar -->
                <circle cx="500" cy="220" r="4" fill="#DC2626"/>
                <text x="510" y="225" fill="#1F2937" font-size="10" font-weight="bold">Makassar: 95</text>
            </g>
        </svg>

        <!-- Map Legend -->
        <div class="absolute bottom-4 left-4 bg-white bg-opacity-90 rounded-lg p-3">
            <h4 class="font-semibold text-gray-700 mb-2">Legenda</h4>
            <div class="flex items-center space-x-2 mb-1">
                <div class="w-3 h-3 bg-red-600 rounded-full"></div>
                <span class="text-sm text-gray-600">Kota dengan pelanggan</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm text-gray-600">Wilayah Indonesia</span>
            </div>
        </div>

        <!-- Statistics Box -->
        <div class="absolute top-4 right-4 bg-white bg-opacity-90 rounded-lg p-3">
            <h4 class="font-semibold text-gray-700 mb-2">Total Distribusi</h4>
            <div class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Jawa:</span>
                    <span class="font-medium">850 pelanggan</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Sumatra:</span>
                    <span class="font-medium">285 pelanggan</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Lainnya:</span>
                    <span class="font-medium">110 pelanggan</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
