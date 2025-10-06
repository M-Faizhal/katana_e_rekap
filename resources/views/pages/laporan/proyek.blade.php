@extends('layouts.app')

@section('title', 'Laporan Proyek - Cyber KATANA')

@section('content')
<!-- Navigation Tabs -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-6">
    <div class="px-6 py-4">
        <nav class="flex space-x-8" aria-label="Tabs">
            <a href="{{ route('laporan.proyek') }}"
               class="border-red-500 text-red-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Laporan Proyek
            </a>
            <a href="{{ route('laporan.omset') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Laporan Omset
            </a>
            <a href="{{ route('laporan.hutang-vendor') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Hutang Vendor
            </a>
            <a href="{{ route('laporan.piutang-dinas') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Piutang Dinas
            </a>
        </nav>
    </div>
</div>

<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Laporan Proyek</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Laporan semua proyek dengan berbagai status</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-chart-bar text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-folder text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Proyek</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">{{ $stats['total_proyek'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-check-circle text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Proyek Selesai</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">{{ $stats['proyek_selesai'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-clock text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Proyek Berjalan</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">{{ $stats['proyek_berjalan'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-purple-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-money-bill-wave text-purple-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Nilai</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600">
                    @php
                        $totalNilai = $stats['total_nilai_proyek'] ?? 0;
                        if ($totalNilai >= 1000000000) {
                            echo 'Rp ' . number_format($totalNilai / 1000000000, 1) . ' Miliar';
                        } elseif ($totalNilai >= 1000000) {
                            echo 'Rp ' . number_format($totalNilai / 1000000, 1) . ' Juta';
                        } else {
                            echo 'Rp ' . number_format($totalNilai, 0, ',', '.');
                        }
                    @endphp
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 sm:mb-8">
    <!-- Status Distribution Chart -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-pie text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Distribusi Status Proyek</h3>
                    <p class="text-sm text-gray-600">Status proyek saat ini</p>
                </div>
            </div>
        </div>
        <div class="p-4 sm:p-6">
            <canvas id="statusChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Monthly Projects Chart -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Proyek Per Bulan</h3>
                        <p class="text-sm text-gray-600">Distribusi bulanan per tahun</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Tahun:</label>
                    <div class="flex items-center space-x-1">
                        <button onclick="changeYear('monthly', -1)" class="w-8 h-8 flex items-center justify-center text-sm border border-gray-300 rounded-l-lg hover:bg-gray-50 focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                        <input type="number" id="monthlyChartYear" value="{{ date('Y') }}" min="{{ $yearRange['min_year'] }}" max="{{ $yearRange['max_year'] }}"
                               class="w-20 text-sm text-center border-t border-b border-gray-300 py-1 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               onchange="updateMonthlyChart()" onkeyup="handleYearInput(this, 'monthly')"
                               title="Range: {{ $yearRange['min_year'] }} - {{ $yearRange['max_year'] }}">
                        <button onclick="changeYear('monthly', 1)" class="w-8 h-8 flex items-center justify-center text-sm border border-gray-300 rounded-r-lg hover:bg-gray-50 focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 sm:p-6">
            <canvas id="monthlyChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Monthly Value Chart -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-6 sm:mb-8">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Nilai Proyek Per Bulan</h3>
                    <p class="text-sm text-gray-600">Total nilai proyek per tahun</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Tahun:</label>
                <div class="flex items-center space-x-1">
                    <button onclick="changeYear('value', -1)" class="w-8 h-8 flex items-center justify-center text-sm border border-gray-300 rounded-l-lg hover:bg-gray-50 focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <input type="number" id="valueChartYear" value="{{ date('Y') }}" min="{{ $yearRange['min_year'] }}" max="{{ $yearRange['max_year'] }}"
                           class="w-20 text-sm text-center border-t border-b border-gray-300 py-1 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           onchange="updateValueChart()" onkeyup="handleYearInput(this, 'value')"
                           title="Range: {{ $yearRange['min_year'] }} - {{ $yearRange['max_year'] }}">
                    <button onclick="changeYear('value', 1)" class="w-8 h-8 flex items-center justify-center text-sm border border-gray-300 rounded-r-lg hover:bg-gray-50 focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <canvas id="valueChart" width="400" height="300"></canvas>
    </div>
</div>

<!-- Advanced Filter Section -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-6 sm:mb-8">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-filter text-red-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Filter Laporan Proyek</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Filter berdasarkan periode dan status proyek</p>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Periode Filter dengan Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" id="start-date" value="{{ request('start_date') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                       placeholder="Pilih tanggal mulai">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" id="end-date" value="{{ request('end_date') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                       placeholder="Pilih tanggal akhir">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Proyek</label>
                <select id="status-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Penawaran" {{ request('status') == 'Penawaran' ? 'selected' : '' }}>Penawaran</option>
                    <option value="Pembayaran" {{ request('status') == 'Pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                    <option value="Pengiriman" {{ request('status') == 'Pengiriman' ? 'selected' : '' }}>Pengiriman</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Gagal" {{ request('status') == 'Gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>

            <!-- Range Nilai Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Range Nilai</label>
                <select id="nilai-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Nilai</option>
                    <option value="0-10jt" {{ request('nilai') == '0-10jt' ? 'selected' : '' }}>≤ Rp 10 Juta</option>
                    <option value="10-50jt" {{ request('nilai') == '10-50jt' ? 'selected' : '' }}>Rp 10 - 50 Juta</option>
                    <option value="50-100jt" {{ request('nilai') == '50-100jt' ? 'selected' : '' }}>Rp 50 - 100 Juta</option>
                    <option value="100-500jt" {{ request('nilai') == '100-500jt' ? 'selected' : '' }}>Rp 100 - 500 Juta</option>
                    <option value="500jt-1m" {{ request('nilai') == '500jt-1m' ? 'selected' : '' }}>Rp 500 Juta - 1 Miliar</option>
                    <option value="1m+" {{ request('nilai') == '1m+' ? 'selected' : '' }}>> Rp 1 Miliar</option>
                </select>
            </div>
        </div>

        <!-- Quick Filter Buttons -->
        <div class="flex flex-wrap gap-2 mb-6">
            <button onclick="setQuickFilter('today')" class="px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                <i class="fas fa-calendar-day mr-2"></i>Hari Ini
            </button>
            <button onclick="setQuickFilter('week')" class="px-4 py-2 text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                <i class="fas fa-calendar-week mr-2"></i>7 Hari Terakhir
            </button>
            <button onclick="setQuickFilter('month')" class="px-4 py-2 text-sm bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                <i class="fas fa-calendar-alt mr-2"></i>30 Hari Terakhir
            </button>
            <button onclick="setQuickFilter('quarter')" class="px-4 py-2 text-sm bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">
                <i class="fas fa-calendar mr-2"></i>3 Bulan Terakhir
            </button>
            <button onclick="setQuickFilter('year')" class="px-4 py-2 text-sm bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors">
                <i class="fas fa-calendar-check mr-2"></i>Tahun Ini
            </button>
            <button onclick="setQuickFilter('all')" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-infinity mr-2"></i>Semua Data
            </button>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="applyFilters()" class="flex-1 sm:flex-none bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-all duration-200">
                <i class="fas fa-search mr-2"></i>Terapkan Filter
            </button>
            <button onclick="resetFilters()" class="flex-1 sm:flex-none border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-undo mr-2"></i>Reset Filter
            </button>
        </div>
    </div>
</div>

<!-- Projects Table -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-table text-red-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Daftar Proyek</h2>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Semua proyek dengan berbagai status</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nilai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($projects as $project)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $project->kode_proyek }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $project->instansi }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($project->tanggal)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($project->status == 'Selesai') bg-green-100 text-green-800
                            @elseif($project->status == 'Pengiriman') bg-blue-100 text-blue-800
                            @elseif($project->status == 'Pembayaran') bg-yellow-100 text-yellow-800
                            @elseif($project->status == 'Penawaran') bg-purple-100 text-purple-800
                            @elseif($project->status == 'Menunggu') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($project->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @php
                            $nilaiProyek = $project->total_nilai ?? 0;
                            if ($nilaiProyek >= 1000000000) {
                                echo 'Rp ' . number_format($nilaiProyek / 1000000000, 1) . ' Miliar';
                            } elseif ($nilaiProyek >= 1000000) {
                                echo 'Rp ' . number_format($nilaiProyek / 1000000, 1) . ' Juta';
                            } else {
                                echo 'Rp ' . number_format($nilaiProyek, 0, ',', '.');
                            }
                        @endphp
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewProjectDetail({{ $project->id_proyek }})"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data proyek yang ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">{{ $projects->firstItem() ?? 0 }}</span> sampai <span class="font-medium">{{ $projects->lastItem() ?? 0 }}</span> dari <span class="font-medium">{{ $projects->total() }}</span> proyek
            </div>
            <div class="flex items-center space-x-2">
                {{ $projects->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
// Format currency function
function formatCurrency(amount) {
    const num = parseInt(amount) || 0;
    if (num >= 1000000000) {
        return 'Rp ' + (num / 1000000000).toFixed(1) + ' Miliar';
    } else if (num >= 1000000) {
        return 'Rp ' + (num / 1000000).toFixed(1) + ' Juta';
    } else {
        return 'Rp ' + num.toLocaleString('id-ID');
    }
}

// Filter functionality
function applyFilters() {
    const status = document.getElementById('status-filter').value;
    const nilai = document.getElementById('nilai-filter').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    // Build URL with query parameters
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (nilai) params.append('nilai', nilai);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);

    // Redirect to laporan page with filters
    window.location.href = '{{ route("laporan.proyek") }}?' + params.toString();
}

// Quick filter functions
function setQuickFilter(period) {
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const today = new Date();
    let startDate, endDate;

    switch(period) {
        case 'today':
            startDate = new Date(today);
            endDate = new Date(today);
            break;
        case 'week':
            startDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            endDate = new Date(today);
            break;
        case 'month':
            startDate = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
            endDate = new Date(today);
            break;
        case 'quarter':
            startDate = new Date(today.getTime() - 90 * 24 * 60 * 60 * 1000);
            endDate = new Date(today);
            break;
        case 'year':
            startDate = new Date(today.getFullYear(), 0, 1);
            endDate = new Date(today);
            break;
        case 'all':
            startDateInput.value = '';
            endDateInput.value = '';
            applyFilters();
            return;
    }

    // Format dates for input[type="date"]
    startDateInput.value = startDate.toISOString().split('T')[0];
    endDateInput.value = endDate.toISOString().split('T')[0];

    // Auto apply filter
    applyFilters();
}

function resetFilters() {
    // Redirect to laporan page without any filters
    window.location.href = '{{ route("laporan.proyek") }}';
}

function viewProjectDetail(projectId) {
    // Fetch project detail from API
    fetch(`{{ url('/laporan/project') }}/${projectId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showProjectDetailModal(data.data);
            } else {
                showNotification('Gagal memuat detail proyek', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat detail proyek', 'error');
        });
}

function showProjectDetailModal(project) {
    // Create modal for project detail
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 backdrop-blur-xs bg-black/30 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Detail Proyek ${project.kode_proyek}</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Informasi Proyek</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Instansi:</span> ${project.instansi}</div>
                            <div><span class="font-medium">Jenis Pengadaan:</span> ${project.jenis_pengadaan}</div>
                            <div><span class="font-medium">Tanggal:</span> ${project.tanggal}</div>
                            <div><span class="font-medium">Deadline:</span> ${project.deadline}</div>
                            <div><span class="font-medium">Status:</span> ${project.status}</div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Tim & Nilai</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">PIC Marketing:</span> ${project.admin_marketing}</div>
                            <div><span class="font-medium">PIC Purchasing:</span> ${project.admin_purchasing}</div>
                            <div><span class="font-medium">Total Nilai:</span> ${formatCurrency(project.total_nilai)}</div>
                            <div><span class="font-medium">Catatan:</span> ${project.catatan}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

    const bgColor = type === 'success' ? 'bg-green-500' :
                   type === 'error' ? 'bg-red-500' :
                   type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
    const iconName = type === 'success' ? 'check' :
                    type === 'error' ? 'exclamation-triangle' :
                    type === 'warning' ? 'exclamation-circle' : 'info-circle';

    notification.classList.add(bgColor, 'text-white');

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${iconName} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Update monthly chart based on selected year
async function updateMonthlyChart() {
    const selectedYear = document.getElementById('monthlyChartYear').value;

    try {
        // Show loading indicator
        const canvas = document.getElementById('monthlyChart');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#6b7280';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Memuat data...', canvas.width / 2, canvas.height / 2);

        // Fetch new data for selected year
        const response = await fetch(`{{ route('laporan.proyek') }}?ajax=1&chart_year=${selectedYear}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success && data.chartData) {
            // Update chart data
            monthlyChart.data.labels = data.chartData.monthly_projects.map(item => item.month);
            monthlyChart.data.datasets[0].data = data.chartData.monthly_projects.map(item => item.count);
            monthlyChart.update();
        } else {
            throw new Error('Invalid response data');
        }
    } catch (error) {
        console.error('Error updating monthly chart:', error);
        showNotification('Gagal memuat data chart: ' + error.message, 'error');

        // Restore chart
        monthlyChart.update();
    }
}

// Update value chart based on selected year
async function updateValueChart() {
    const selectedYear = document.getElementById('valueChartYear').value;

    try {
        // Show loading indicator
        const canvas = document.getElementById('valueChart');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#6b7280';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Memuat data...', canvas.width / 2, canvas.height / 2);

        // Fetch new data for selected year
        const response = await fetch(`{{ route('laporan.proyek') }}?ajax=1&chart_year=${selectedYear}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success && data.chartData) {
            // Update chart data
            valueChart.data.labels = data.chartData.monthly_values.map(item => item.month);
            valueChart.data.datasets[0].data = data.chartData.monthly_values.map(item => item.value);
            valueChart.update();
        } else {
            throw new Error('Invalid response data');
        }
    } catch (error) {
        console.error('Error updating value chart:', error);
        showNotification('Gagal memuat data chart: ' + error.message, 'error');

        // Restore chart
        valueChart.update();
    }
}

// Sync year filters for both charts
function syncChartYears() {
    const monthlyYear = document.getElementById('monthlyChartYear').value;
    const valueYear = document.getElementById('valueChartYear').value;

    // Sync both dropdowns to use same year
    if (monthlyYear !== valueYear) {
        document.getElementById('valueChartYear').value = monthlyYear;
        updateValueChart();
    }
}

// Dynamic year range from PHP
const yearRange = @json($yearRange);

// Handle year input changes with validation
function handleYearInput(input, chartType) {
    const year = parseInt(input.value);

    // Validate year range using dynamic data
    if (year < yearRange.min_year) {
        input.value = yearRange.min_year;
        showNotification(`Tahun minimum adalah ${yearRange.min_year}`, 'warning');
    } else if (year > yearRange.max_year) {
        input.value = yearRange.max_year;
        showNotification(`Tahun maksimum adalah ${yearRange.max_year}`, 'warning');
    }

    // Auto update chart after short delay to avoid rapid API calls
    clearTimeout(window.yearInputTimeout);
    window.yearInputTimeout = setTimeout(() => {
        if (chartType === 'monthly') {
            document.getElementById('valueChartYear').value = input.value;
            updateMonthlyChart();
            updateValueChart();
        } else {
            document.getElementById('monthlyChartYear').value = input.value;
            updateValueChart();
            updateMonthlyChart();
        }
    }, 800);
}

// Change year with +/- buttons
function changeYear(chartType, direction) {
    const input = document.getElementById(chartType === 'monthly' ? 'monthlyChartYear' : 'valueChartYear');
    const otherInput = document.getElementById(chartType === 'monthly' ? 'valueChartYear' : 'monthlyChartYear');
    const currentYear = parseInt(input.value);
    const newYear = currentYear + direction;

    if (newYear >= yearRange.min_year && newYear <= yearRange.max_year) {
        input.value = newYear;
        otherInput.value = newYear;

        // Update both charts
        updateMonthlyChart();
        updateValueChart();
    } else {
        const limitText = newYear < yearRange.min_year ? `minimum (${yearRange.min_year})` : `maksimum (${yearRange.max_year})`;
        showNotification(`Tahun ${limitText} tercapai`, 'warning');
    }
}

// Add event listener to sync years
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('monthlyChartYear').addEventListener('change', function() {
        document.getElementById('valueChartYear').value = this.value;
        updateValueChart();
    });

    document.getElementById('valueChartYear').addEventListener('change', function() {
        document.getElementById('monthlyChartYear').value = this.value;
        updateMonthlyChart();
    });
});
</script>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart data from PHP
const chartData = @json($chartData);
console.log('Chart Data:', chartData);

// Global chart variables for updates
let monthlyChart, valueChart;

// Status Distribution Chart (Doughnut)
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: chartData.status_distribution.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
        datasets: [{
            data: chartData.status_distribution.map(item => item.total),
            backgroundColor: [
                '#EF4444', // Red
                '#F97316', // Orange
                '#EAB308', // Yellow
                '#22C55E', // Green
                '#3B82F6', // Blue
                '#8B5CF6', // Purple
                '#EC4899'  // Pink
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            }
        }
    }
});

// Monthly Projects Chart (Bar)
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
monthlyChart = new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: chartData.monthly_projects.map(item => item.month),
        datasets: [{
            label: 'Jumlah Proyek',
            data: chartData.monthly_projects.map(item => item.count),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Monthly Value Chart (Line)
const valueCtx = document.getElementById('valueChart').getContext('2d');
valueChart = new Chart(valueCtx, {
    type: 'line',
    data: {
        labels: chartData.monthly_values.map(item => item.month),
        datasets: [{
            label: 'Total Nilai (Rp)',
            data: chartData.monthly_values.map(item => item.value),
            borderColor: 'rgb(147, 51, 234)',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgb(147, 51, 234)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6
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
                        if (value >= 1000000000) {
                            return 'Rp ' + (value / 1000000000).toFixed(1) + 'M';
                        } else if (value >= 1000000) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                        } else {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let value = context.parsed.y;
                        if (value >= 1000000000) {
                            return 'Total Nilai: Rp ' + (value / 1000000000).toFixed(1) + ' Miliar';
                        } else if (value >= 1000000) {
                            return 'Total Nilai: Rp ' + (value / 1000000).toFixed(1) + ' Juta';
                        } else {
                            return 'Total Nilai: Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    }
});
</script>
@endsection
