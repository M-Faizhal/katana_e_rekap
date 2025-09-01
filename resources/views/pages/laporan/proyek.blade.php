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
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-bar text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Proyek Per Bulan</h3>
                    <p class="text-sm text-gray-600">6 bulan terakhir</p>
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
                <p class="text-sm text-gray-600">Total nilai proyek 6 bulan terakhir</p>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Periode Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Laporan</label>
                <select id="periode-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Periode</option>
                    <option value="bulan-ini" {{ request('periode') == 'bulan-ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="3-bulan" {{ request('periode') == '3-bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    <option value="6-bulan" {{ request('periode') == '6-bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                    <option value="tahun-ini" {{ request('periode') == 'tahun-ini' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Proyek</label>
                <select id="status-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Status</option>
                    @foreach($filterOptions['statuses'] as $status)
                        <option value="{{ $status['value'] }}" {{ request('status') == $status['value'] ? 'selected' : '' }}>
                            {{ $status['label'] }}
                        </option>
                    @endforeach
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

        <!-- Custom Date Range (Hidden by default) -->
        <div id="custom-date-range" class="grid-cols-1 md:grid-cols-2 gap-4 mb-6 {{ request('periode') == 'custom' ? 'grid' : 'hidden' }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" id="start-date" value="{{ request('start_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" id="end-date" value="{{ request('end_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="applyFilters()" class="flex-1 sm:flex-none bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-all duration-200">
                <i class="fas fa-search mr-2"></i>Terapkan Filter
            </button>
            <button onclick="resetFilters()" class="flex-1 sm:flex-none border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-undo mr-2"></i>Reset Filter
            </button>
            <button onclick="exportReport()" class="flex-1 sm:flex-none bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-200">
                <i class="fas fa-download mr-2"></i>Export Excel
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
                            @if($project->status == 'selesai') bg-green-100 text-green-800
                            @elseif($project->status == 'pengiriman') bg-blue-100 text-blue-800
                            @elseif($project->status == 'pembayaran') bg-yellow-100 text-yellow-800
                            @elseif($project->status == 'penawaran') bg-purple-100 text-purple-800
                            @elseif($project->status == 'purchasing') bg-indigo-100 text-indigo-800
                            @elseif($project->status == 'verifikasi') bg-orange-100 text-orange-800
                            @elseif($project->status == 'menunggu') bg-red-100 text-red-800
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
    const periode = document.getElementById('periode-filter').value;
    const status = document.getElementById('status-filter').value;
    const nilai = document.getElementById('nilai-filter').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    // Build URL with query parameters
    const params = new URLSearchParams();
    if (periode) params.append('periode', periode);
    if (status) params.append('status', status);
    if (nilai) params.append('nilai', nilai);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);

    // Redirect to laporan page with filters
    window.location.href = '{{ route("laporan.proyek") }}?' + params.toString();
}

function resetFilters() {
    // Redirect to laporan page without any filters
    window.location.href = '{{ route("laporan.proyek") }}';
}

function exportReport() {
    // Get current URL parameters for export
    const urlParams = new URLSearchParams(window.location.search);

    // Build export URL with same filters
    const exportUrl = '{{ route("laporan.export") }}?' + urlParams.toString();

    // Show export notification
    showNotification('Export sedang diproses...', 'info');

    // Trigger download
    window.location.href = exportUrl;

    // Show success notification after a delay
    setTimeout(() => {
        showNotification('Export berhasil! File sedang diunduh...', 'success');
    }, 1000);
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
                            <div><span class="font-medium">Admin Marketing:</span> ${project.admin_marketing}</div>
                            <div><span class="font-medium">Admin Purchasing:</span> ${project.admin_purchasing}</div>
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

    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    notification.classList.add(bgColor, 'text-white');

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
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

// Handle periode filter change
document.getElementById('periode-filter').addEventListener('change', function() {
    const customDateRange = document.getElementById('custom-date-range');
    if (this.value === 'custom') {
        customDateRange.style.display = 'grid';
        customDateRange.classList.add('grid');
        customDateRange.classList.remove('hidden');
    } else {
        customDateRange.style.display = 'none';
        customDateRange.classList.remove('grid');
        customDateRange.classList.add('hidden');
    }
});
</script>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart data from PHP
const chartData = @json($chartData);
console.log('Chart Data:', chartData);

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
const monthlyChart = new Chart(monthlyCtx, {
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
const valueChart = new Chart(valueCtx, {
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
