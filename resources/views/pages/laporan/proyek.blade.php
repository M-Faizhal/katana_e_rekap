@extends('layouts.app')

@section('title', 'Laporan Proyek - Cyber KATANA')

@section('content')
<!-- Na    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-5 border border-gray-100"> -->
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
        <div class="flex items-center space-x-4">
            <button onclick="exportProyek()" class="bg-white text-red-800 px-4 py-2 rounded-lg hover:bg-red-50 transition-colors duration-200 flex items-center space-x-2 shadow-md">
                <i class="fas fa-file-excel text-lg"></i>
                <span class="font-semibold hidden sm:inline">Export Excel</span>
            </button>
            <div class="hidden sm:block lg:block">
                <i class="fas fa-chart-bar text-3xl sm:text-4xl lg:text-6xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Year Filter Section -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-6">
    <div class="p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-red-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Filter Tahun</h3>
                    <p class="text-sm text-gray-600">Pilih tahun untuk melihat statistik dan data proyek</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="changeGlobalYear(-1)" class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-red-500 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <input type="number" id="globalYear" value="{{ request('year', date('Y')) }}" 
                       min="{{ $yearRange['min_year'] }}" 
                       max="{{ $yearRange['max_year'] }}"
                       class="w-24 text-center text-lg font-semibold border-2 border-gray-300 rounded-lg py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                       onchange="updateGlobalYear()" 
                       readonly
                       title="Range: {{ $yearRange['min_year'] }} - {{ $yearRange['max_year'] }}">
                <button onclick="changeGlobalYear(1)" class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-red-500 transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <button onclick="showAllYears()" class="ml-2 px-4 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                    <i class="fas fa-infinity mr-1"></i>
                    Semua Tahun
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-5 border border-gray-100">
        <div class="flex flex-col text-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-3 w-fit mx-auto">
                <i class="fas fa-folder text-blue-600 text-lg sm:text-xl lg:text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xs sm:text-sm lg:text-base font-semibold text-gray-800 mb-1">Total Proyek</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">{{ $stats['total_proyek'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-5 border border-gray-100">
        <div class="flex flex-col text-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-indigo-100 mb-3 w-fit mx-auto">
                <i class="fas fa-handshake text-indigo-600 text-lg sm:text-xl lg:text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xs sm:text-sm lg:text-base font-semibold text-gray-800 mb-1">Proyek Sudah SP</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-indigo-600">{{ $stats['proyek_sp'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-5 border border-gray-100">
        <div class="flex flex-col text-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-3 w-fit mx-auto">
                <i class="fas fa-check-circle text-green-600 text-lg sm:text-xl lg:text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xs sm:text-sm lg:text-base font-semibold text-gray-800 mb-1">Proyek Selesai</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">{{ $stats['proyek_selesai'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-5 border border-gray-100">
        <div class="flex flex-col text-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-purple-100 mb-3 w-fit mx-auto">
                <i class="fas fa-money-bill-wave text-purple-600 text-lg sm:text-xl lg:text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xs sm:text-sm lg:text-base font-semibold text-gray-800 mb-1">Total Nilai</h3>
                <p class="text-sm sm:text-base lg:text-lg font-bold text-purple-600">Rp {{ number_format($stats['total_nilai_proyek'] ?? 0, 2, ',', '.') }}</p>
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
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-bar text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Proyek Per Bulan</h3>
                    <p class="text-sm text-gray-600" id="monthlyChartSubtitle">Distribusi bulanan tahun {{ request('year', date('Y')) }}</p>
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
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line text-purple-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Nilai Proyek Per Bulan</h3>
                <p class="text-sm text-gray-600" id="valueChartSubtitle">Total nilai proyek tahun {{ request('year', date('Y')) }}</p>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <canvas id="valueChart" width="400" height="300"></canvas>
    </div>
</div>

<!-- Advanced Filter Section (hidden, kept for JS compatibility) -->
<div class="hidden">
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

<!-- Projects Table (hidden) -->
<div class="hidden">
    <div>
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

// Dynamic year range from PHP
const yearRange = @json($yearRange);

// Global Year Filter Functions
function changeGlobalYear(direction) {
    const input = document.getElementById('globalYear');
    const currentYear = parseInt(input.value);
    const newYear = currentYear + direction;

    if (newYear >= yearRange.min_year && newYear <= yearRange.max_year) {
        input.value = newYear;
        updateGlobalYear();
    } else {
        const limitText = newYear < yearRange.min_year ? `minimum (${yearRange.min_year})` : `maksimum (${yearRange.max_year})`;
        showNotification(`Tahun ${limitText} tercapai`, 'warning');
    }
}

function updateGlobalYear() {
    const selectedYear = document.getElementById('globalYear').value;
    
    // Reload page with selected year parameter
    const params = new URLSearchParams(window.location.search);
    params.set('year', selectedYear);
    
    window.location.href = '{{ route("laporan.proyek") }}?' + params.toString();
}

function showAllYears() {
    // Reload page without year parameter (show all data)
    const params = new URLSearchParams(window.location.search);
    params.delete('year');
    
    window.location.href = '{{ route("laporan.proyek") }}' + (params.toString() ? '?' + params.toString() : '');
}

function exportProyek() {
    const selectedYear = document.getElementById('globalYear').value;
    showNotification(`Mengunduh laporan proyek tahun ${selectedYear}...`, 'info');
    window.location.href = '{{ route("laporan.export") }}?year=' + selectedYear;
}

// Update chart subtitles based on current year
document.addEventListener('DOMContentLoaded', function() {
    const currentYear = document.getElementById('globalYear').value;
    const monthlySubtitle = document.getElementById('monthlyChartSubtitle');
    const valueSubtitle = document.getElementById('valueChartSubtitle');
    
    if (monthlySubtitle) {
        monthlySubtitle.textContent = `Distribusi bulanan tahun ${currentYear}`;
    }
    if (valueSubtitle) {
        valueSubtitle.textContent = `Total nilai proyek tahun ${currentYear}`;
    }
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

// Monthly Value Chart (Line) - sama seperti grafik omset
const valueCtx = document.getElementById('valueChart').getContext('2d');
valueChart = new Chart(valueCtx, {
    type: 'line',
    data: {
        labels: chartData.monthly_values.map(item => item.month),
        datasets: [{
            label: 'Nilai Proyek (Rp)',
            data: chartData.monthly_values.map(item => item.value),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgb(34, 197, 94)',
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
                        if (value >= 1000000000000) {
                            return (value / 1000000000000).toFixed(1) + ' T';
                        } else if (value >= 1000000000) {
                            return (value / 1000000000).toFixed(1) + ' M';
                        } else if (value >= 1000000) {
                            return (value / 1000000).toFixed(1) + ' jt';
                        } else if (value >= 1000) {
                            return (value / 1000).toFixed(1) + ' rb';
                        } else {
                            return value.toLocaleString('id-ID');
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
                        return 'Nilai: Rp ' + value.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        }
    }
});
</script>
@endsection
