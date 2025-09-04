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
                <p class="text-xl sm:text-2xl font-bold text-green-600 mb-1">Rp {{ number_format($stats['omset_bulan_ini'] / 1000000, 1) }}M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-{{ $stats['omset_growth'] >= 0 ? 'up' : 'down' }} text-{{ $stats['omset_growth'] >= 0 ? 'green' : 'red' }}-500 text-sm"></i>
                    <span class="text-sm font-medium text-{{ $stats['omset_growth'] >= 0 ? 'green' : 'red' }}-500">{{ $stats['omset_growth'] >= 0 ? '+' : '' }}{{ $stats['omset_growth'] }}%</span>
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
                <p class="text-xl sm:text-2xl font-bold text-blue-600 mb-1">{{ $stats['proyek_aktif'] }}</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                    <span class="text-sm font-medium text-green-500">+{{ $stats['proyek_baru'] }}</span>
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
                <p class="text-xl sm:text-2xl font-bold text-red-600 mb-1">Rp {{ number_format($stats['total_hutang'] / 1000000, 1) }}M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-exclamation-triangle text-orange-500 text-sm"></i>
                    <span class="text-sm font-medium text-orange-500">{{ $stats['vendor_pending'] }}</span>
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
                <p class="text-xl sm:text-2xl font-bold text-yellow-600 mb-1">Rp {{ number_format($stats['total_piutang'] / 1000000, 1) }}M</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-clock text-yellow-500 text-sm"></i>
                    <span class="text-sm font-medium text-yellow-500">{{ $stats['dinas_pending'] }}</span>
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
                    @foreach($monthlyRevenue as $month)
                    @php
                        $maxRevenue = collect($monthlyRevenue)->max('revenue');
                        $height = $maxRevenue > 0 ? ($month['revenue'] / $maxRevenue) * 100 : 0;
                        $color = $month['month'] <= date('n') ? 'bg-green-500' : 'bg-gray-300';
                        if($month['month'] <= date('n') && $month['revenue'] > 0) {
                            if($month['revenue'] > $maxRevenue * 0.7) $color = 'bg-green-700';
                            elseif($month['revenue'] > $maxRevenue * 0.5) $color = 'bg-green-600';
                        }
                    @endphp
                    <div class="flex flex-col items-center">
                        <div class="{{ $color }} rounded-t-lg w-4 sm:w-6 lg:w-8 transition-all duration-500 hover:opacity-80"
                             style="height: {{ max($height, 3) }}%"
                             title="{{ $month['month_name'] }}: Rp {{ number_format($month['revenue'] / 1000000, 1) }}M"></div>
                        <span class="text-xs text-gray-600 mt-1 sm:mt-2">{{ $month['month_name'] }}</span>
                        <span class="text-xs text-gray-500 hidden sm:block">{{ number_format($month['revenue'] / 1000000, 0) }}M</span>
                    </div>
                    @endforeach
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
            @forelse($revenuePerPerson as $person)
            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:shadow-md transition-all duration-200">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-user-tie text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $person->nama }}</p>
                        <p class="text-xs sm:text-sm text-gray-600">{{ $person->total_projects }} Proyek</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-blue-600">Rp {{ number_format($person->total_revenue / 1000000, 1) }}M</p>
                    <div class="w-16 sm:w-20 bg-gray-200 rounded-full h-2 mt-1">
                        @php
                            $maxRevenue = $revenuePerPerson->max('total_revenue');
                            $percentage = $maxRevenue > 0 ? ($person->total_revenue / $maxRevenue) * 100 : 0;
                        @endphp
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-users text-3xl mb-2"></i>
                <p>Belum ada data omset per orang</p>
            </div>
            @endforelse
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
                <span class="px-2 sm:px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs sm:text-sm font-medium">{{ $stats['vendor_pending'] }} Pending</span>
                <button class="text-red-600 hover:text-red-700 text-xs sm:text-sm font-medium whitespace-nowrap">
                    Lihat Semua
                </button>
            </div>
        </div>
        <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 overflow-y-auto">
            @forelse($vendorDebts as $debt)
            @php
                $statusColor = $debt->status == 'overdue' ? 'red' : ($debt->status == 'warning' ? 'orange' : 'yellow');
                $statusText = $debt->status == 'overdue' ? 'Overdue' : ($debt->status == 'warning' ? $debt->days_overdue . ' hari lagi' : 'Normal');
            @endphp
            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-{{ $statusColor }}-50 to-{{ $statusColor }}-100 rounded-xl border-l-4 border-{{ $statusColor }}-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-{{ $statusColor }}-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-building text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $debt->nama_vendor }}</p>
                        <p class="text-xs sm:text-sm text-gray-600">{{ $debt->nama_barang }}</p>
                        <p class="text-xs text-{{ $statusColor }}-500 font-medium">{{ $debt->total_penawaran }} penawaran</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-{{ $statusColor }}-600">Rp {{ number_format($debt->total_hutang / 1000000, 1) }}M</p>
                    <span class="px-2 py-1 bg-{{ $statusColor }}-200 text-{{ $statusColor }}-700 rounded-full text-xs">{{ $statusText }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-handshake text-3xl mb-2"></i>
                <p>Tidak ada hutang vendor saat ini</p>
            </div>
            @endforelse
        </div>
        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-gray-600 font-medium text-sm sm:text-base">Total Hutang:</span>
                <span class="text-lg sm:text-xl font-bold text-red-600">Rp {{ number_format($stats['total_hutang'] / 1000000, 1) }}M</span>
            </div>
        </div>
    </div>

    <!-- Right Card - Piutang Dinas -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Piutang Dinas</h3>
            <div class="flex items-center space-x-2">
                <span class="px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs sm:text-sm font-medium">{{ $stats['dinas_pending'] }} Pending</span>
                <button class="text-yellow-600 hover:text-yellow-700 text-xs sm:text-sm font-medium whitespace-nowrap">
                    Lihat Semua
                </button>
            </div>
        </div>
        <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 overflow-y-auto">
            @forelse($clientReceivables as $receivable)
            @php
                $statusColor = $receivable->status == 'overdue' ? 'red' : 'green';
                $progressColor = $receivable->progress > 75 ? 'green' : ($receivable->progress > 50 ? 'yellow' : ($receivable->progress > 25 ? 'orange' : 'red'));
            @endphp
            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-{{ $progressColor }}-50 to-{{ $progressColor }}-100 rounded-xl border-l-4 border-{{ $progressColor }}-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-{{ $progressColor }}-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-university text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $receivable->instansi }}</p>
                        <p class="text-xs sm:text-sm text-gray-600">{{ $receivable->kode_proyek }}</p>
                        <p class="text-xs text-{{ $progressColor }}-600 font-medium">Invoice: {{ $receivable->nomor_invoice }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-{{ $progressColor }}-600">Rp {{ number_format($receivable->sisa_piutang / 1000000, 1) }}M</p>
                    <span class="px-2 py-1 bg-{{ $progressColor }}-200 text-{{ $progressColor }}-700 rounded-full text-xs">{{ number_format($receivable->progress, 0) }}% dibayar</span>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-file-invoice text-3xl mb-2"></i>
                <p>Tidak ada piutang dinas saat ini</p>
            </div>
            @endforelse
        </div>
        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-gray-600 font-medium text-sm sm:text-base">Total Piutang:</span>
                <span class="text-lg sm:text-xl font-bold text-yellow-600">Rp {{ number_format($stats['total_piutang'] / 1000000, 1) }}M</span>
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
                        <hr class="border-gray-300 my-1 sm:my-2">
                        <div class="flex items-center space-x-1 sm:space-x-3">
                            <div class="w-2 h-2 sm:w-3 sm:h-3 bg-gray-400 rounded-full shadow-sm flex-shrink-0 border border-white"></div>
                            <span class="text-xs sm:text-sm text-gray-700 font-medium">Titik Kota</span>
                        </div>
                        <div class="flex items-center space-x-1 sm:space-x-3 mt-1">
                            <span class="text-xs text-gray-500 ml-4 sm:ml-5">Ukuran = Volume Penjualan</span>
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
                        @if($geographicStats['total_cities'] > 0)
                            @foreach($geographicStats['top_cities'] as $city)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ $city['name'] }}:</span>
                                <span class="font-bold text-
                                    @if($city['level'] == 'very-high') blue-600
                                    @elseif($city['level'] == 'high') green-600
                                    @elseif($city['level'] == 'medium') orange-600
                                    @else red-300
                                    @endif
                                ">
                                    @php
                                        $sales = $city['sales'];
                                        if ($sales >= 1000) {
                                            echo 'Rp ' . number_format($sales / 1000, 1) . ' M';
                                        } elseif ($sales >= 1) {
                                            echo 'Rp ' . number_format($sales, 1) . ' juta';
                                        } else {
                                            echo 'Rp ' . number_format($sales * 1000, 0) . ' ribu';
                                        }
                                    @endphp
                                </span>
                            </div>
                            @endforeach
                            @if($geographicStats['others_sales'] > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ $geographicStats['total_cities'] - 4 }} Kota Lainnya:</span>
                                <span class="font-bold text-gray-600">
                                    @php
                                        $others = $geographicStats['others_sales'];
                                        if ($others >= 1000) {
                                            echo 'Rp ' . number_format($others / 1000, 1) . ' M';
                                        } elseif ($others >= 1) {
                                            echo 'Rp ' . number_format($others, 1) . ' juta';
                                        } else {
                                            echo 'Rp ' . number_format($others * 1000, 0) . ' ribu';
                                        }
                                    @endphp
                                </span>
                            </div>
                            @endif
                            <hr class="border-gray-200 my-0.5 sm:my-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Total ({{ $geographicStats['total_cities'] }} Kota):</span>
                                <span class="font-bold text-red-600">
                                    @php
                                        $total = $geographicStats['total_sales'];
                                        if ($total >= 1000) {
                                            echo 'Rp ' . number_format($total / 1000, 1) . ' M';
                                        } elseif ($total >= 1) {
                                            echo 'Rp ' . number_format($total, 1) . ' juta';
                                        } else {
                                            echo 'Rp ' . number_format($total * 1000, 0) . ' ribu';
                                        }
                                    @endphp
                                </span>
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-chart-pie text-2xl mb-2"></i>
                                <p class="text-xs">Belum ada data penjualan</p>
                                <p class="text-xs text-gray-400">Data akan muncul setelah ada proyek selesai</p>
                            </div>
                        @endif
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

/* City marker styles */
.city-marker {
    background: transparent !important;
    border: none !important;
}

.city-marker .marker {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
}

.city-marker .marker:hover {
    animation: pulse-marker 0.6s ease-in-out;
}

@keyframes pulse-marker {
    0%, 100% {
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    50% {
        box-shadow: 0 6px 20px rgba(0,0,0,0.4), 0 0 0 10px rgba(255,255,255,0.3);
    }
}

/* Custom marker colors based on sales performance */
.marker-very-high {
    background-color: #3182CE !important;
    box-shadow: 0 0 0 4px rgba(49, 130, 206, 0.3) !important;
}
.marker-high {
    background-color: #48BB78 !important;
    box-shadow: 0 0 0 4px rgba(72, 187, 120, 0.3) !important;
}
.marker-medium {
    background-color: #ED8936 !important;
    box-shadow: 0 0 0 4px rgba(237, 137, 54, 0.3) !important;
}
.marker-low {
    background-color: #F56565 !important;
    box-shadow: 0 0 0 4px rgba(245, 101, 101, 0.3) !important;
}

/* Enhanced leaflet interactive elements */
.leaflet-interactive {
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

/* Smooth tooltip animations */
.custom-tooltip {
    background: rgba(0, 0, 0, 0.95) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4), 0 2px 16px rgba(0, 0, 0, 0.2) !important;
    color: white !important;
    font-family: system-ui, -apple-system, sans-serif !important;
    font-size: 13px !important;
    padding: 16px !important;
    backdrop-filter: blur(16px) saturate(180%) !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    transform-origin: bottom left !important;
}

/* Smooth map container */
#indonesiaMap {
    transition: filter 0.3s ease !important;
}

/* Collision warning styles - hidden from users */
.collision-warning {
    /* Keep collision detection for internal use but hide visual indicators */
    /* animation: collision-pulse 2s infinite !important; */
    position: relative !important;
}

.collision-warning::before {
    /* Hide warning icon from users */
    display: none !important;
    content: '';
    position: absolute;
    top: -8px;
    right: -8px;
    font-size: 10px;
    background: white;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    z-index: 10;
}

@keyframes collision-pulse {
    /* Disable collision pulse animation for cleaner user experience */
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1);
        opacity: 1;
    }
}
}

/* Enhanced marker effects */
.city-marker .marker {
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.city-marker .marker:hover {
    filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.3));
}

/* Marker bounce animation on load */
@keyframes markerBounce {
    0% {
        transform: translateY(-20px) scale(0);
        opacity: 0;
    }
    50% {
        transform: translateY(-5px) scale(1.1);
        opacity: 0.8;
    }
    100% {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

.city-marker .marker {
    animation: markerBounce 0.6s ease-out;
}
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

    // City sales data from database (real data)
    const citiesData = @json($geographicData);

    // Debug: Log the data received from server
    console.log('Geographic Data from Database:', citiesData);

    // Check if we have data, if not show message
    if (!citiesData || citiesData.length === 0) {
        document.getElementById('indonesiaMap').innerHTML = `
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <i class="fas fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Data Penjualan</h3>
                    <p class="text-gray-500">Data distribusi geografis akan muncul setelah ada proyek yang selesai</p>
                    <div class="mt-4 text-sm text-gray-400">
                        <p>Pastikan ada proyek dengan status "Selesai" atau "Pengiriman" di database</p>
                    </div>
                </div>
            </div>
        `;
        return;
    }

    // Function to check if two markers are overlapping with improved precision
    function areMarkersOverlapping(coords1, coords2, zoomLevel) {
        const pixelDistance = map.distance(coords1, coords2);
        // Adaptive threshold based on zoom level - closer zoom allows closer markers
        const threshold = Math.max(5000, 20000 / Math.pow(2, zoomLevel - 4));
        return pixelDistance < threshold;
    }

    // Function to apply smart collision avoidance
    function applySmartCollisionAvoidance() {
        const processedMarkers = new Set();
        const collisionGroups = [];

        // Group overlapping markers
        citiesData.forEach((city1, index1) => {
            if (processedMarkers.has(index1)) return;

            const group = [index1];
            const baseCoords = city1.coordinates;

            citiesData.forEach((city2, index2) => {
                if (index1 !== index2 && !processedMarkers.has(index2)) {
                    if (areMarkersOverlapping(baseCoords, city2.coordinates, map.getZoom())) {
                        group.push(index2);
                    }
                }
            });

            if (group.length > 1) {
                collisionGroups.push(group);
                group.forEach(i => processedMarkers.add(i));
            }
        });

        // Apply circular positioning for collision groups
        collisionGroups.forEach((group, groupIndex) => {
            const centerLat = group.reduce((sum, i) => sum + citiesData[i].coordinates[0], 0) / group.length;
            const centerLng = group.reduce((sum, i) => sum + citiesData[i].coordinates[1], 0) / group.length;

            // Calculate radius based on zoom level
            const baseRadius = 0.05 / Math.pow(2, Math.max(0, map.getZoom() - 6));

            group.forEach((cityIndex, posIndex) => {
                if (posIndex === 0) return; // Keep first marker at original position

                // Distribute markers in a circle around the center
                const angle = (2 * Math.PI * posIndex) / group.length;
                const radius = baseRadius * (1 + Math.floor(posIndex / 6) * 0.5); // Expand radius for many markers

                const newLat = centerLat + radius * Math.cos(angle);
                const newLng = centerLng + radius * Math.sin(angle);

                // Update marker position
                const marker = cityMarkers[cityIndex];
                if (marker) {
                    marker.setLatLng([newLat, newLng]);

                    // Add visual indicator for repositioned markers
                    if (marker._icon) {
                        const markerElement = marker._icon.querySelector('.marker');
                        if (markerElement) {
                            markerElement.style.border = '3px solid #ff6b6b';
                            markerElement.style.boxShadow = '0 0 0 2px rgba(255, 107, 107, 0.3), 0 4px 8px rgba(0,0,0,0.3)';
                        }
                    }
                }
            });
        });

        return collisionGroups;
    }

    // Function to detect all overlapping markers (simplified)
    function detectOverlappingMarkers() {
        const overlappingGroups = [];
        const processed = new Set();

        citiesData.forEach((city1, index1) => {
            if (processed.has(index1)) return;

            const group = [index1];
            citiesData.forEach((city2, index2) => {
                if (index1 !== index2 && !processed.has(index2)) {
                    if (areMarkersOverlapping(city1.coordinates, city2.coordinates, map.getZoom())) {
                        group.push(index2);
                        processed.add(index2);
                    }
                }
            });

            if (group.length > 1) {
                group.forEach(i => processed.add(i));
                overlappingGroups.push(group);
            }
        });

        return overlappingGroups;
    }

    // Function to apply collision effects with smart repositioning
    function applyCollisionEffects() {
        // First, reset all markers to original state
        citiesData.forEach((city, index) => {
            const marker = cityMarkers[index];
            if (marker && marker._icon) {
                const markerElement = marker._icon.querySelector('.marker');
                if (markerElement) {
                    markerElement.classList.remove('collision-warning', 'collision-group');
                    markerElement.style.borderColor = 'white';
                    markerElement.style.borderWidth = '3px';
                    markerElement.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
                }
            }
            // Reset marker to original position
            marker.setLatLng(city.coordinates);
        });

        // Apply smart collision avoidance
        const collisionGroups = applySmartCollisionAvoidance();

        // Update collision info display
        updateCollisionInfo(collisionGroups);

        return collisionGroups;
    }

    // Function to update collision information display (hidden from users)
    function updateCollisionInfo(overlappingGroups) {
        let infoDiv = document.getElementById('collisionInfo');
        if (!infoDiv) {
            infoDiv = document.createElement('div');
            infoDiv.id = 'collisionInfo';
            infoDiv.style.cssText = `
                position: absolute;
                top: 60px;
                right: 6px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-blur: 16px;
                border-radius: 12px;
                padding: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border: 1px solid rgba(0,0,0,0.1);
                font-size: 12px;
                font-family: system-ui, -apple-system, sans-serif;
                z-index: 1000;
                max-width: 250px;
                transition: all 0.3s ease;
                display: none;
                visibility: hidden;
                opacity: 0;
            `;
            document.querySelector('#indonesiaMap').appendChild(infoDiv);
        }

        // Keep collision detection logic for internal use but hide from users
        if (overlappingGroups.length > 0) {
            // Console logging for debugging (only visible to developers)
            console.log(`Collision detected: ${overlappingGroups.length} groups overlapping`);
            overlappingGroups.forEach((group, index) => {
                console.log(`Group ${index + 1}:`, group.map(cityIndex => citiesData[cityIndex].name));
            });

            // Keep info div hidden
            infoDiv.style.display = 'none';
        } else {
            console.log('No marker collisions detected');
            // Keep info div hidden
            infoDiv.style.display = 'none';
        }
    }

    // Function to format currency based on value
    function formatCurrency(value) {
        if (value >= 1000) {
            return `Rp ${(value / 1000).toFixed(1)} M`;
        } else if (value >= 1) {
            return `Rp ${value.toFixed(1)} juta`;
        } else {
            return `Rp ${(value * 1000).toFixed(0)} ribu`;
        }
    }

    // Function to get marker size based on sales level
    function getMarkerSize(level) {
        switch(level) {
            case 'very-high': return 20;
            case 'high': return 16;
            case 'medium': return 14;
            case 'low': return 12;
            default: return 10;
        }
    }

    // Function to get marker color based on sales level
    function getMarkerColor(level) {
        switch(level) {
            case 'very-high': return '#3182CE';
            case 'high': return '#48BB78';
            case 'medium': return '#ED8936';
            case 'low': return '#F56565';
            default: return '#CBD5E0';
        }
    }

    // Function to get hover color (lighter version)
    function getHoverColor(level) {
        switch(level) {
            case 'very-high': return '#4299E1';
            case 'high': return '#68D391';
            case 'medium': return '#F6AD55';
            case 'low': return '#FC8181';
            default: return '#E2E8F0';
        }
    }

    // Create floating tooltip with enhanced styling
    const tooltip = document.createElement('div');
    tooltip.style.cssText = `
        position: fixed;
        background: rgba(0, 0, 0, 0.95);
        color: white;
        padding: 16px;
        border-radius: 12px;
        font-size: 13px;
        font-family: system-ui, -apple-system, sans-serif;
        pointer-events: none;
        z-index: 1000;
        display: none;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4), 0 2px 16px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(16px) saturate(180%);
        max-width: 250px;
        transform-origin: bottom left;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    `;
    document.body.appendChild(tooltip);

    // Add city markers to map
    const cityMarkers = []; // Store markers for collision detection

    citiesData.forEach((city, index) => {
        const markerSize = getMarkerSize(city.level);

        // Create custom divIcon for each city
        const customIcon = L.divIcon({
            className: 'city-marker',
            html: `<div class="marker marker-${city.level}" style="
                width: ${markerSize}px;
                height: ${markerSize}px;
                background-color: ${getMarkerColor(city.level)};
                border: 3px solid white;
                border-radius: 50%;
                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                cursor: pointer;
            "></div>`,
            iconSize: [markerSize, markerSize],
            iconAnchor: [markerSize/2, markerSize/2]
        });

        // Create marker
        const marker = L.marker(city.coordinates, { icon: customIcon }).addTo(map);
        cityMarkers.push(marker); // Store marker for collision detection

        // Hover effects
        marker.on('mouseover', function(e) {
            const markerElement = e.target._icon.querySelector('.marker');

            // Enhanced hover effect
            markerElement.style.transform = 'scale(1.3)';
            markerElement.style.backgroundColor = getHoverColor(city.level);
            markerElement.style.boxShadow = '0 6px 20px rgba(0,0,0,0.4)';
            markerElement.style.zIndex = '1000';

            // Show tooltip with fade in effect
            tooltip.innerHTML = `
                <div style="font-weight: bold; margin-bottom: 8px; color: #FFF; font-size: 15px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 6px;">${city.name}</div>
                <div style="font-size: 11px; color: #9CA3AF; margin-bottom: 6px; display: flex; align-items: center;">
                    <span style="margin-right: 6px;">📍</span>${city.area} • ${city.population}
                </div>
                <div style="font-size: 12px; color: #E5E7EB; margin-bottom: 4px; display: flex; align-items: center;">
                    <span style="margin-right: 6px;">💰</span>Penjualan: <strong style="color: #34D399; margin-left: 4px;">${formatCurrency(city.sales)}</strong>
                </div>
                <div style="font-size: 12px; color: #E5E7EB; margin-bottom: 4px; display: flex; align-items: center;">
                    <span style="margin-right: 6px;">📊</span>Proyek: <strong style="color: #60A5FA; margin-left: 4px;">${city.projects} proyek</strong>
                </div>
                <div style="font-size: 12px; color: #E5E7EB; display: flex; align-items: center;">
                    <span style="margin-right: 6px;">📈</span>Pertumbuhan: <strong style="color: #FBBF24; margin-left: 4px;">+${city.growth}%</strong>
                </div>
            `;

            tooltip.style.display = 'block';
            tooltip.style.opacity = '0';
            tooltip.style.transform = 'translateY(5px)';

            // Smooth fade in
            setTimeout(() => {
                tooltip.style.transition = 'all 0.2s ease-out';
                tooltip.style.opacity = '1';
                tooltip.style.transform = 'translateY(0)';
            }, 10);
        });

        marker.on('mouseout', function(e) {
            const markerElement = e.target._icon.querySelector('.marker');

            // Reset marker style
            markerElement.style.transform = 'scale(1)';
            markerElement.style.backgroundColor = getMarkerColor(city.level);
            markerElement.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';

            // Smooth fade out tooltip
            tooltip.style.transition = 'all 0.15s ease-in';
            tooltip.style.opacity = '0';
            tooltip.style.transform = 'translateY(-3px)';

            setTimeout(() => {
                tooltip.style.display = 'none';
                tooltip.style.transition = '';
            }, 150);
        });

        // Mouse move to update tooltip position
        marker.on('mousemove', function(e) {
            const x = e.originalEvent.clientX;
            const y = e.originalEvent.clientY;

            tooltip.style.left = (x + 20) + 'px';
            tooltip.style.top = (y - 10) + 'px';
        });
    });

    // Initial collision detection
    setTimeout(() => {
        applyCollisionEffects();
    }, 500);

    // Update collision detection on zoom
    map.on('zoomend', function() {
        setTimeout(() => {
            applyCollisionEffects();
        }, 100);
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
