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
                       class="w-24 text-center text-lg font-semibold border-2 border-gray-300 rounded-lg py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500 {{ request()->has('all') ? 'opacity-50' : '' }}" 
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
                <h3 class="text-xs sm:text-sm lg:text-base font-semibold text-gray-800 mb-1">Omset Proyek</h3>
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
                    <p class="text-sm text-gray-600" id="monthlyChartSubtitle">Distribusi bulanan {{ request()->has('all') ? 'semua tahun' : 'tahun ' . request('year', date('Y')) }}</p>
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
                <h3 class="text-lg font-bold text-gray-800">Nilai Proyek Per Bulan</h3>                    <p class="text-sm text-gray-600" id="valueChartSubtitle">Total nilai proyek {{ request()->has('all') ? 'semua tahun' : 'tahun ' . request('year', date('Y')) }}</p>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <canvas id="valueChart" width="400" height="300"></canvas>
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
    window.location.href = '{{ route("laporan.proyek") }}?all=1';
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
