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
    <!-- Card 1 - Total Omset Tahun Ini -->
    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 w-full">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="p-2 sm:p-3 rounded-xl bg-green-600 shadow-md">
                <i class="fas fa-chart-line text-white text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 truncate">Omset Tahun Ini</h3>
                <p class="text-xl sm:text-2xl font-bold text-green-600 mb-1">Rp {{ $stats['omset_tahun_ini_formatted'] ?? '0' }}</p>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-arrow-{{ $stats['omset_growth'] >= 0 ? 'up' : 'down' }} text-{{ $stats['omset_growth'] >= 0 ? 'green' : 'red' }}-500 text-sm"></i>
                    <span class="text-sm font-medium text-{{ $stats['omset_growth'] >= 0 ? 'green' : 'red' }}-500">{{ $stats['omset_growth'] >= 0 ? '+' : '' }}{{ $stats['omset_growth'] }}%</span>
                    <span class="text-xs sm:text-sm text-gray-500 hidden sm:inline">dari tahun lalu</span>
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
                <p class="text-xl sm:text-2xl font-bold text-red-600 mb-1">Rp {{ number_format($stats['total_hutang'] ?? 0, 0, ',', '.') }}</p>
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
                <p class="text-xl sm:text-2xl font-bold text-yellow-600 mb-1">Rp {{ number_format($stats['total_piutang'] ?? 0, 0, ',', '.') }}</p>
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
                    @for($year = $yearRange['min_year']; $year <= $yearRange['max_year']; $year++)
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
                        <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $debt->nama_vendor ?? 'Unknown Vendor' }}</p>
                        @if(isset($debt->jenis_perusahaan) && $debt->jenis_perusahaan)
                            <p class="text-xs sm:text-sm text-gray-600">{{ $debt->jenis_perusahaan }}</p>
                        @endif
                        <p class="text-xs text-{{ $statusColor }}-600 font-medium">{{ $debt->kode_proyek ?? '-' }} - {{ $debt->instansi ?? '-' }}</p>
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
                $statusPembayaran = $receivable->status_pembayaran ?? 'unknown';
                $daysOverdue = $receivable->days_overdue ?? 0;

                if ($statusPembayaran == 'belum_bayar' || $statusPembayaran == 'belum_ditagih') {
                    $statusColor = $daysOverdue > 0 ? 'red' : 'yellow';
                } elseif ($statusPembayaran == 'dp') {
                    $statusColor = $daysOverdue > 0 ? 'orange' : 'blue';
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
                                @if($statusPembayaran == 'belum_bayar' || $statusPembayaran == 'belum_ditagih') bg-yellow-100 text-yellow-800
                                @elseif($statusPembayaran == 'dp') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($statusPembayaran == 'belum_bayar')
                                    Belum Bayar
                                @elseif($statusPembayaran == 'belum_ditagih')
                                    Belum Ditagih
                                @elseif($statusPembayaran == 'dp')
                                    DP Dibayar
                                @else
                                    {{ ucfirst($statusPembayaran) }}
                                @endif
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600">{{ $receivable->kode_proyek ?? '-' }}</p>
                        <p class="text-xs text-{{ $statusColor }}-600 font-medium">Invoice: {{ $receivable->nomor_invoice ?? '-' }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-sm sm:text-lg font-bold text-{{ $statusColor }}-600">
                        @php
                            $nominal = $receivable->sisa_piutang ?? 0;
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
                    @if($daysOverdue > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $daysOverdue }} hari telat
                        </span>
                    @else
                        @if(($receivable->progress ?? 0) > 0)
                            <div class="text-xs text-gray-500 mb-1">{{ number_format($receivable->progress ?? 0, 1) }}% terbayar</div>
                            <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                <div class="bg-{{ $statusColor }}-600 h-1.5 rounded-full" style="width: {{ min($receivable->progress ?? 0, 100) }}%"></div>
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

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
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
