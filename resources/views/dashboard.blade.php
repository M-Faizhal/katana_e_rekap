@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 sidebar-gradient shadow-lg">
        <!-- Logo/Header -->
        <div class="p-6 border-b border-red-400">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-sword text-red-600 text-xl"></i>
                </div>
                <div class="text-white">
                    <h1 class="text-lg font-bold">Dashboard PT. Kamil Trio Niaga</h1>
                    <p class="text-sm text-red-200">(KATANA)</p>
                    <p class="text-xs text-red-200">Halo & Menager, selamat datang 🔥</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="mt-6">
            <ul class="space-y-2 px-4">
                <li>
                    <a href="#" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200 bg-red-500">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="font-medium">Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200">
                        <i class="fas fa-bullhorn w-5"></i>
                        <span class="font-medium">Marketing</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span class="font-medium">Purchasing</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200">
                        <i class="fas fa-coins w-5"></i>
                        <span class="font-medium">Keuangan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200">
                        <i class="fas fa-box w-5"></i>
                        <span class="font-medium">Produk</span>
                    </a>
                </li>
            </ul>

            <!-- Bottom Menu -->
            <div class="absolute bottom-0 w-64 px-4 pb-6">
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200">
                            <i class="fas fa-cog w-5"></i>
                            <span class="font-medium">Pengaturan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 text-white hover:bg-red-500 rounded-lg px-4 py-3 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="font-medium">Keluar</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Search Bar -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text"
                               placeholder="Miskirin apa?...."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 w-80">
                        <button class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <span class="bg-red-600 text-white px-3 py-1 rounded-md text-sm font-medium">Cari</span>
                        </button>
                    </div>
                </div>

                <!-- Right Header -->
                <div class="flex items-center space-x-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Bulanan
                    </button>
                    <button class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="card-placeholder p-6 min-h-[200px] flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-red-500 text-4xl mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-700">Statistik Penjualan</h3>
                        <p class="text-gray-500 mt-2">Data penjualan bulanan</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card-placeholder p-6 min-h-[200px] flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-users text-red-500 text-4xl mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-700">Total Pelanggan</h3>
                        <p class="text-gray-500 mt-2">Jumlah pelanggan aktif</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card-placeholder p-6 min-h-[200px] flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-money-bill-wave text-red-500 text-4xl mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-700">Pendapatan</h3>
                        <p class="text-gray-500 mt-2">Total pendapatan hari ini</p>
                    </div>
                </div>
            </div>

            <!-- Large Content Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Left Large Card -->
                <div class="card-placeholder p-6 min-h-[300px] flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-chart-bar text-red-500 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700">Grafik Analitik</h3>
                        <p class="text-gray-500 mt-2">Visualisasi data bisnis</p>
                    </div>
                </div>

                <!-- Right Large Card -->
                <div class="card-placeholder p-6 min-h-[300px] flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-tasks text-red-500 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700">Aktivitas Terbaru</h3>
                        <p class="text-gray-500 mt-2">Daftar aktivitas sistem</p>
                    </div>
                </div>
            </div>

            <!-- Indonesia Map Section -->
            <div class="card-placeholder p-6 min-h-[250px] indonesia-map relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <!-- SVG Indonesia Map -->
                    <svg viewBox="0 0 800 400" class="w-full h-full max-w-4xl">
                        <!-- Simplified Indonesia archipelago shape -->
                        <g fill="#00CED1" stroke="#008B8B" stroke-width="2">
                            <!-- Main islands representation -->
                            <ellipse cx="150" cy="200" rx="80" ry="40" opacity="0.8"/>
                            <ellipse cx="280" cy="180" rx="100" ry="50" opacity="0.8"/>
                            <ellipse cx="450" cy="200" rx="120" ry="60" opacity="0.8"/>
                            <ellipse cx="600" cy="220" rx="90" ry="45" opacity="0.8"/>
                            <circle cx="200" cy="300" r="30" opacity="0.8"/>
                            <circle cx="350" cy="320" r="25" opacity="0.8"/>
                            <circle cx="500" cy="310" r="35" opacity="0.8"/>
                            <circle cx="650" cy="300" r="28" opacity="0.8"/>
                        </g>
                        <!-- Location markers -->
                        <g fill="#FF6B6B">
                            <circle cx="280" cy="180" r="4"/>
                            <circle cx="450" cy="200" r="4"/>
                            <circle cx="600" cy="220" r="4"/>
                        </g>
                    </svg>
                </div>
                <div class="absolute top-4 left-4 bg-white bg-opacity-90 rounded-lg p-3">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Sebaran Geografis</h3>
                    <p class="text-sm text-gray-600">Distribusi data di seluruh Indonesia</p>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
