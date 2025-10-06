@extends('layouts.app')

@section('title', 'Dashboard - Cyber KATANA')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8">
<!-- Welcome Banner -->
<div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4 w-full">
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
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <!-- Card 1 - Total Omset Bulan Ini -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 w-full">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="p-2 sm:p-3 rounded-xl bg-green-600 shadow-md">
                <i class="fas fa-chart-line text-white text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 truncate">Omset Bulan Ini</h3>
                <p class="text-xl sm:text-2xl font-bold text-green-600 mb-1">Rp {{ $stats['omset_bulan_ini_formatted'] ?? '0' }}</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-{{ $stats['omset_growth'] >= 0 ? 'up' : 'down' }} text-{{ $stats['omset_growth'] >= 0 ? 'green' : 'red' }}-500 text-sm"></i>
                    <span class="text-sm font-medium text-{{ $stats['omset_growth'] >= 0 ? 'green' : 'red' }}-500">{{ $stats['omset_growth'] >= 0 ? '+' : '' }}{{ $stats['omset_growth'] }}%</span>
                    <span class="text-xs sm:text-sm text-gray-500 hidden sm:inline">dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2 - Total Proyek Aktif -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 w-full">
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
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 w-full">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="p-2 sm:p-3 rounded-xl bg-red-600 shadow-md">
                <i class="fas fa-credit-card text-white text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 truncate">Total Hutang</h3>
                <p class="text-xl sm:text-2xl font-bold text-red-600 mb-1">Rp {{ $stats['total_hutang_formatted'] ?? '0' }}</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-exclamation-triangle text-orange-500 text-sm"></i>
                    <span class="text-sm font-medium text-orange-500">{{ $stats['jumlah_vendor_hutang'] ?? 0 }}</span>
                    <span class="text-xs sm:text-sm text-gray-500 hidden sm:inline">vendor hutang</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4 - Total Piutang -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 w-full">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="p-2 sm:p-3 rounded-xl bg-yellow-600 shadow-md">
                <i class="fas fa-hand-holding-usd text-white text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 truncate">Total Piutang</h3>
                <p class="text-xl sm:text-2xl font-bold text-yellow-600 mb-1">Rp {{ $stats['total_piutang_formatted'] ?? '0' }}</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-exclamation-triangle text-red-500 text-sm"></i>
                    <span class="text-sm font-medium text-red-500">{{ $stats['jumlah_proyek_piutang'] ?? 0 }}</span>
                    <span class="text-xs sm:text-sm text-gray-500 hidden sm:inline">proyek piutang</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Large Content Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8 w-full">
    <!-- Left Large Card - Grafik Omset Per Bulan -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Grafik Omset Per Bulan</h3>
                    <p class="text-sm text-gray-600" id="dashboardChartInfo">Data untuk {{ date('Y') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700 hidden sm:block">Tahun:</label>
                <select id="dashboardYearFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                    @for($year = 2022; $year <= date('Y') + 1; $year++)
                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="h-64 sm:h-80 lg:h-96 w-full">
            <canvas id="dashboardOmsetChart"></canvas>
        </div>
    </div>

    <!-- Right Large Card - Omset Per Orang -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-trophy text-yellow-600"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Omset Per PIC Marketing</h3>
                    <p class="text-sm text-gray-600">Top performer PIC marketing</p>
                </div>
            </div>
            <a href="{{ route('laporan.omset') }}" class="text-blue-600 hover:text-blue-700 text-xs sm:text-sm font-medium self-start sm:self-auto">
                Lihat Detail
            </a>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC Marketing</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Omset</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($revenuePerPerson as $index => $person)
                    @php
                        $rankColor = $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : ($index == 2 ? 'orange' : 'blue'));
                        $rankIcon = $index == 0 ? 'fa-crown' : ($index == 1 ? 'fa-medal' : ($index == 2 ? 'fa-award' : 'fa-user-tie'));
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-{{ $rankColor }}-100 rounded-full flex items-center justify-center mr-2">
                                    @if($index < 3)
                                        <i class="fas {{ $rankIcon }} text-{{ $rankColor }}-600 text-sm"></i>
                                    @else
                                        <span class="text-xs font-medium text-{{ $rankColor }}-600">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-user-tie text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $person->nama }}</div>
                                    <div class="text-xs text-red-600 font-medium">Marketing</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $person->total_projects }} proyek
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <div class="flex flex-col">
                                <span class="text-lg font-bold text-green-600">
                                    @php
                                        $formatAmount = function($amount) {
                                            if ($amount >= 1000000000000) {
                                                return number_format($amount / 1000000000000, 1, ',', '.') . ' T';
                                            } elseif ($amount >= 1000000000) {
                                                return number_format($amount / 1000000000, 1, ',', '.') . ' M';
                                            } elseif ($amount >= 1000000) {
                                                return number_format($amount / 1000000, 1, ',', '.') . ' jt';
                                            } elseif ($amount >= 1000) {
                                                return number_format($amount / 1000, 1, ',', '.') . ' rb';
                                            } else {
                                                return number_format($amount, 0, ',', '.');
                                            }
                                        };
                                    @endphp
                                    Rp {{ $formatAmount($person->total_revenue) }}
                                </span>
                                <div class="w-20 bg-gray-200 rounded-full h-2 mt-1">
                                    @php
                                        $maxRevenue = $revenuePerPerson->max('total_revenue');
                                        $percentage = $maxRevenue > 0 ? ($person->total_revenue / $maxRevenue) * 100 : 0;
                                    @endphp
                                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-3xl mb-2"></i>
                            <p>Belum ada data omset PIC marketing</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($revenuePerPerson->count() > 0)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Total PIC Marketing: {{ $revenuePerPerson->count() }}</span>
                <span class="text-gray-600">Total Omset: Rp {{ $formatAmount($revenuePerPerson->sum('total_revenue')) }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Hutang dan Piutang Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8 w-full">
    <!-- Left Card - Hutang Vendor -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('laporan.hutang-vendor') }}'">
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Hutang Vendor</h3>
            <div class="flex items-center space-x-2">
                <span class="px-2 sm:px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs sm:text-sm font-medium">{{ $stats['jumlah_vendor_hutang'] ?? 0 }} Vendor</span>
                <a href="{{ route('laporan.hutang-vendor') }}" class="text-red-600 hover:text-red-700 text-xs sm:text-sm font-medium whitespace-nowrap" onclick="event.stopPropagation();">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 lg:max-h-96 overflow-y-auto w-full">
            @forelse($vendorDebts as $debt)
            @php
                if ($debt->warning_hps) {
                    $statusColor = 'orange';
                    $statusText = 'HPS Belum Diisi';
                } elseif ($debt->status_lunas) {
                    $statusColor = 'green';
                    $statusText = 'Lunas';
                } elseif ($debt->status == 'overdue') {
                    $statusColor = 'red';
                    $statusText = 'Overdue (' . $debt->days_overdue . ' hari)';
                } elseif ($debt->status == 'warning') {
                    $statusColor = 'orange';
                    $statusText = 'Mendekati Jatuh Tempo';
                } else {
                    $statusColor = 'blue';
                    $statusText = 'Normal';
                }
            @endphp
            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-{{ $statusColor }}-50 to-{{ $statusColor }}-100 rounded-xl border-l-4 border-{{ $statusColor }}-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-{{ $statusColor }}-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-building text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $debt->nama_vendor }}</p>
                        @if($debt->jenis_perusahaan)
                            <p class="text-xs sm:text-sm text-gray-600">{{ $debt->jenis_perusahaan }}</p>
                        @endif
                        <p class="text-xs text-{{ $statusColor }}-600 font-medium">{{ $debt->kode_proyek }} - {{ $debt->instansi }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    @if($debt->warning_hps)
                        <p class="text-sm sm:text-lg font-bold text-{{ $statusColor }}-600">-</p>
                        <p class="text-xs text-{{ $statusColor }}-600">{{ $debt->warning_hps }}</p>
                    @else
                        <p class="text-sm sm:text-lg font-bold text-{{ $statusColor }}-600">
                            @if($debt->sisa_bayar >= 1000000000)
                                Rp {{ number_format($debt->sisa_bayar / 1000000000, 1) }}M
                            @elseif($debt->sisa_bayar >= 1000000)
                                Rp {{ number_format($debt->sisa_bayar / 1000000, 1) }}jt
                            @elseif($debt->sisa_bayar >= 1000)
                                Rp {{ number_format($debt->sisa_bayar / 1000, 0) }}rb
                            @else
                                Rp {{ number_format($debt->sisa_bayar, 0) }}
                            @endif
                        </p>
                        @if($debt->total_vendor > 0)
                            <div class="text-xs text-gray-500 mb-1">{{ number_format($debt->persen_bayar, 1) }}% terbayar</div>
                            <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                <div class="@if($debt->status_lunas) bg-green-600 @else bg-{{ $statusColor }}-600 @endif h-1.5 rounded-full transition-all duration-300" 
                                     style="width: {{ min($debt->persen_bayar, 100) }}%"></div>
                            </div>
                        @endif
                    @endif
                    <span class="inline-block mt-2 px-2 py-1 bg-{{ $statusColor }}-200 text-{{ $statusColor }}-700 rounded-full text-xs">{{ $statusText }}</span>
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
                <span class="text-lg sm:text-xl font-bold text-red-600">Rp {{ $stats['total_hutang_formatted'] ?? '0' }}</span>
            </div>
        </div>
    </div>

    <!-- Right Card - Piutang Dinas -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('laporan.piutang-dinas') }}'">
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Piutang Dinas</h3>
            <div class="flex items-center space-x-2">
                <span class="px-2 sm:px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs sm:text-sm font-medium">{{ $stats['piutang_jatuh_tempo_formatted'] ?? '0' }} Jatuh Tempo</span>
                <a href="{{ route('laporan.piutang-dinas') }}" class="text-yellow-600 hover:text-yellow-700 text-xs sm:text-sm font-medium whitespace-nowrap" onclick="event.stopPropagation();">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="space-y-3 sm:space-y-4 max-h-64 sm:max-h-80 lg:max-h-96 overflow-y-auto w-full">
            @forelse($clientReceivables as $receivable)
            @php
                // Status color based on payment status and overdue (consistent with laporan)
                $statusColor = 'gray';
                if ($receivable->status_pembayaran == 'belum_bayar' || $receivable->status_pembayaran == 'belum_ditagih') {
                    $statusColor = $receivable->days_overdue > 0 ? 'red' : 'yellow';
                } elseif ($receivable->status_pembayaran == 'dp') {
                    $statusColor = $receivable->days_overdue > 0 ? 'orange' : 'blue';
                } else {
                    $statusColor = 'green';
                }
            @endphp
            <div class="flex items-center justify-between p-3 sm:p-4 bg-gradient-to-r from-{{ $statusColor }}-50 to-{{ $statusColor }}-100 rounded-xl border-l-4 border-{{ $statusColor }}-500">
                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-{{ $statusColor }}-500 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <i class="fas fa-university text-white text-sm sm:text-base"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $receivable->instansi ?? '-' }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                @if($receivable->status_pembayaran == 'belum_bayar' || $receivable->status_pembayaran == 'belum_ditagih') bg-yellow-100 text-yellow-800
                                @elseif($receivable->status_pembayaran == 'dp') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($receivable->status_pembayaran == 'belum_bayar') 
                                    Belum Bayar
                                @elseif($receivable->status_pembayaran == 'belum_ditagih') 
                                    Belum Ditagih
                                @elseif($receivable->status_pembayaran == 'dp') 
                                    DP Dibayar
                                @else 
                                    {{ ucfirst($receivable->status_pembayaran) }}
                                @endif
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600">{{ $receivable->kode_proyek ?? '-' }}</p>
                        <p class="text-xs text-{{ $statusColor }}-600 font-medium">Invoice: {{ $receivable->nomor_invoice }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-{{ $statusColor }}-600">
                        @php
                            $nominal = $receivable->sisa_piutang;
                            if ($nominal >= 1000000000) {
                                echo 'Rp ' . number_format($nominal / 1000000000, 1, ',', '.') . ' M';
                            } elseif ($nominal >= 1000000) {
                                echo 'Rp ' . number_format($nominal / 1000000, 1, ',', '.') . ' jt';
                            } elseif ($nominal >= 1000) {
                                echo 'Rp ' . number_format($nominal / 1000, 1, ',', '.') . ' rb';
                            } else {
                                echo 'Rp ' . number_format($nominal, 0, ',', '.');
                            }
                        @endphp
                    </p>
                    @if($receivable->days_overdue > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $receivable->days_overdue }} hari telat
                        </span>
                    @else
                        @if($receivable->progress > 0)
                            <div class="text-xs text-gray-500 mb-1">{{ number_format($receivable->progress, 1) }}% terbayar</div>
                            <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                <div class="bg-{{ $statusColor }}-600 h-1.5 rounded-full" style="width: {{ min($receivable->progress, 100) }}%"></div>
                            </div>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                On Time
                            </span>
                        @endif
                    @endif
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
                <div class="text-xs sm:text-sm text-gray-600">
                    <span class="font-medium">Total Piutang:</span>
                    <span class="text-yellow-600 font-bold ml-1">Rp {{ $stats['total_piutang_formatted'] ?? '0' }}</span>
                </div>
                <div class="text-xs sm:text-sm text-gray-600">
                    <span class="font-medium">Jatuh Tempo:</span>
                    <span class="text-red-600 font-bold ml-1">Rp {{ $stats['piutang_jatuh_tempo_formatted'] ?? '0' }}</span>
                </div>
                <div class="text-xs sm:text-sm text-gray-600">
                    <span class="font-medium">Proyek:</span>
                    <span class="text-blue-600 font-bold ml-1">{{ $stats['jumlah_proyek_piutang'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Usia Hutang Section -->
<div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100 mb-6 sm:mb-8 w-full">
    <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Usia Piutang Klien (Top 5)</h3>
        <div class="flex items-center space-x-2">
            <span class="px-2 sm:px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-xs sm:text-sm font-medium">{{ $debtAgeAnalysis->count() }} Invoice</span>
            <a href="{{ route('laporan.piutang-dinas') }}" class="text-purple-600 hover:text-purple-700 text-xs sm:text-sm font-medium whitespace-nowrap">
                Lihat Semua
            </a>
        </div>
    </div>

    @if($debtAgeAnalysis && $debtAgeAnalysis->count() > 0)
    <!-- Chart Container -->
    <div class="h-64 sm:h-80 lg:h-96 mb-6 w-full">
        <canvas id="debtAgeChart"></canvas>
    </div>
    @else
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-clock text-3xl mb-2"></i>
        <p>Tidak ada data usia piutang saat ini</p>
    </div>
    @endif

    <!-- Legend for Age Categories -->
    <div class="mt-4 pt-4 border-t border-gray-200">
        <h4 class="text-sm font-medium text-gray-700 mb-3">Kategori Usia Hutang:</h4>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2 text-xs w-full">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-gray-600">0-30 hari</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <span class="text-gray-600">30-60 hari</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                <span class="text-gray-600">60-90 hari</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                <span class="text-gray-600">90-120 hari</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-gray-600">120-150 hari</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-red-700 rounded-full"></div>
                <span class="text-gray-600">>150 hari</span>
            </div>
        </div>
    </div>
</div>



<!-- Indonesia Map Section -->
<div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 border border-gray-100 mb-6 sm:mb-8 w-full">
    <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Distribusi Geografis Penjualan</h3>
        <div class="flex space-x-2">
            <button class="px-3 sm:px-4 py-2 bg-red-800 text-white rounded-xl text-xs sm:text-sm font-medium hover:bg-red-900 transition-colors duration-200">Real-time</button>
        </div>
    </div>

    <div class="h-64 sm:h-80 lg:h-96 xl:h-[32rem] bg-gradient-to-br from-blue-50 via-white to-green-50 rounded-2xl relative overflow-hidden shadow-inner border border-gray-100 w-full">
        <!-- Leaflet Map Container -->
        <div id="indonesiaMap" class="w-full h-full rounded-2xl z-10"></div>

        <!-- Collapsible Map Legend -->
        <div class="absolute bottom-1 sm:bottom-6 right-1 sm:right-6 z-40">
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
        <div class="absolute top-1 sm:top-6 left-1 sm:left-6 z-40">
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

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    cursor: pointer;
    transition: all 0.2s ease;
}

.city-marker .marker:hover {
    filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.3));
}

/* Map control spacing improvements */
.leaflet-top.leaflet-left {
    top: 20px;
    left: 20px;
}

.leaflet-control-zoom {
    margin-bottom: 10px;
}

.leaflet-bar {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
}

.leaflet-control-zoom a {
    width: 34px;
    height: 34px;
    line-height: 34px;
    font-size: 18px;
    font-weight: bold;
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
            case 'very-high': return 28;
            case 'high': return 24;
            case 'medium': return 20;
            case 'low': return 18;
            default: return 16;
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

    // Add zoom control back with better positioning
    map.addControl(L.control.zoom({
        position: 'topleft'
    }));

    // Custom control for enabling/disabling scroll zoom
    const scrollControl = L.control({position: 'topleft'});
    scrollControl.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
        div.style.backgroundColor = 'white';
        div.style.backgroundImage = 'none';
        div.style.width = '34px';
        div.style.height = '34px';
        div.style.cursor = 'pointer';
        div.style.fontSize = '16px';
        div.style.display = 'flex';
        div.style.alignItems = 'center';
        div.style.justifyContent = 'center';
        div.style.marginTop = '10px';
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

// Dashboard Chart Variables
let dashboardOmsetChart;

// Debt Age Chart Configuration
@if($debtAgeAnalysis && $debtAgeAnalysis->count() > 0)
document.addEventListener('DOMContentLoaded', function() {
    const debtAgeCtx = document.getElementById('debtAgeChart');
    if (debtAgeCtx) {
        // Take only top 5 items and prepare data
        const debtData = @json($debtAgeAnalysis->take(5)->values());
        
        // Validate data before processing
        if (!debtData || !Array.isArray(debtData) || debtData.length === 0) {
            console.log('No debt age data available for chart');
            return;
        }

        // Function to get color based on age category
        function getColorByAgeCategory(colorClass) {
            const colorMap = {
                'green': {
                    bg: 'rgba(34, 197, 94, 0.8)',
                    border: 'rgba(34, 197, 94, 1)'
                },
                'yellow': {
                    bg: 'rgba(234, 179, 8, 0.8)',
                    border: 'rgba(234, 179, 8, 1)'
                },
                'orange': {
                    bg: 'rgba(249, 115, 22, 0.8)',
                    border: 'rgba(249, 115, 22, 1)'
                },
                'red': {
                    bg: 'rgba(239, 68, 68, 0.8)',
                    border: 'rgba(239, 68, 68, 1)'
                }
            };
            return colorMap[colorClass] || colorMap['red'];
        }



        // Format currency for tooltips
        function formatCurrency(value) {
            if (!value || isNaN(value)) return 'Rp 0';
            if (value >= 1000000000) {
                return 'Rp ' + (value / 1000000000).toFixed(1) + 'M';
            } else if (value >= 1000000) {
                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
            } else if (value >= 1000) {
                return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
            } else {
                return 'Rp ' + value.toLocaleString();
            }
        }

        // Additional validation for each debt item
        const validDebtData = debtData.filter(debt => 
            debt && 
            typeof debt === 'object' && 
            debt.instansi && 
            debt.outstanding_amount !== undefined
        );

        if (validDebtData.length === 0) {
            console.log('No valid debt age data after filtering');
            return;
        }

        // Use validated data
        const labels = validDebtData.map(debt => debt.instansi || 'Unknown');
        const amounts = validDebtData.map(debt => parseFloat(debt.outstanding_amount) || 0);
        const backgroundColors = validDebtData.map(debt => getColorByAgeCategory(debt.color_class || 'red').bg);
        const borderColors = validDebtData.map(debt => getColorByAgeCategory(debt.color_class || 'red').border);

        new Chart(debtAgeCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Outstanding Amount',
                    data: amounts,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y', // This makes it horizontal
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return debtData[index].instansi;
                            },
                            label: function(context) {
                                const index = context.dataIndex;
                                const debt = debtData[index];
                                return [
                                    'Jumlah: ' + formatCurrency(context.parsed.x),
                                    'Proyek: ' + debt.kode_proyek,
                                    'Invoice: ' + debt.nomor_invoice,
                                    'Kategori: ' + debt.age_category,
                                    'Status: ' + debt.status_text
                                ];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6B7280',
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return formatCurrency(value);
                            }
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#374151',
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            callback: function(value, index) {
                                const label = this.getLabelForValue(value);
                                return label.length > 20 ? label.substring(0, 20) + '...' : label;
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        right: 10,
                        bottom: 10,
                        left: 10
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }
});
@endif

const currentYear = {{ date('Y') }};

// Function to format Rupiah in Indonesian format
function formatRupiahShort(amount) {
    if (amount >= 1000000000000) {
        return (amount / 1000000000000).toFixed(1) + ' T';
    } else if (amount >= 1000000000) {
        return (amount / 1000000000).toFixed(1) + ' M';
    } else if (amount >= 1000000) {
        return (amount / 1000000).toFixed(1) + ' jt';
    } else if (amount >= 1000) {
        return (amount / 1000).toFixed(1) + ' rb';
    } else {
        return amount.toLocaleString('id-ID');
    }
}

// Dashboard Year Selection Handler
document.addEventListener('DOMContentLoaded', function() {
    const dashboardYearFilter = document.getElementById('dashboardYearFilter');

    // Initialize chart with current year data
    const initialMonthlyData = @json($monthlyRevenue ?? []);
    initializeDashboardChart(initialMonthlyData, currentYear);

    if (dashboardYearFilter) {
        // Add change handler for year dropdown
        dashboardYearFilter.addEventListener('change', function() {
            const selectedYear = parseInt(this.value);
            loadDashboardChartData(selectedYear);
        });
    }
});

function initializeDashboardChart(monthlyData, year) {
    const ctx = document.getElementById('dashboardOmsetChart').getContext('2d');

    // Prepare data for chart
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'];
    const data = new Array(12).fill(0);

    if (monthlyData && monthlyData.length > 0) {
        monthlyData.forEach(item => {
            if (item.month && item.month >= 1 && item.month <= 12) {
                data[item.month - 1] = parseFloat(item.revenue) || 0;
            }
        });
    }

    dashboardOmsetChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Omset (Rp)',
                data: data,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.1,
                fill: true,
                pointBackgroundColor: 'rgb(34, 197, 94)',
                pointBorderColor: 'rgb(34, 197, 94)',
                pointHoverBackgroundColor: 'rgb(21, 128, 61)',
                pointHoverBorderColor: 'rgb(21, 128, 61)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatRupiahShort(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Omset: Rp ' + formatRupiahShort(context.parsed.y);
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });

    // Update chart info
    document.getElementById('dashboardChartInfo').textContent = `Data untuk ${year}`;
}

function loadDashboardChartData(year) {
    // Show loading state on chart
    if (dashboardOmsetChart) {
        dashboardOmsetChart.data.datasets[0].data = new Array(12).fill(0);
        dashboardOmsetChart.update();
    }

    // Build URL with parameters
    const params = new URLSearchParams({
        year: year
    });

    // Make AJAX request to get data for selected year
    fetch(`/dashboard/chart-data?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Update chart with new data
        updateDashboardChart(data.monthlyRevenue || [], year);
    })
    .catch(error => {
        console.error('Error loading dashboard chart data:', error);
        // Show error notification
        showDashboardNotification('Terjadi kesalahan saat memuat data grafik', 'error');
    });
}

function updateDashboardChart(monthlyData, year) {
    if (!dashboardOmsetChart) return;

    // Prepare new data
    const data = new Array(12).fill(0);

    if (monthlyData && monthlyData.length > 0) {
        monthlyData.forEach(item => {
            if (item.month && item.month >= 1 && item.month <= 12) {
                data[item.month - 1] = parseFloat(item.revenue) || 0;
            }
        });
    }

    // Update chart data
    dashboardOmsetChart.data.datasets[0].data = data;
    dashboardOmsetChart.update('active');

    // Update chart info
    document.getElementById('dashboardChartInfo').textContent = `Data untuk ${year}`;
}function showDashboardNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    } text-white`;

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' :
                'fa-info-circle'
            } mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}
</script>
</div>
@endsection
