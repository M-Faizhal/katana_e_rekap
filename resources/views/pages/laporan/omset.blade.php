@extends('layouts.app')

@section('title', 'Laporan Omset - Cyber KATANA')

@section('content')
<!-- Navigation Tabs -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-6">
    <div class="px-6 py-4">
        <nav class="flex space-x-8" aria-label="Tabs">
            <a href="{{ route('laporan.proyek') }}" 
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Laporan Proyek
            </a>
            <a href="{{ route('laporan.omset') }}" 
               class="border-red-500 text-red-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
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
<div class="bg-green-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Laporan Omset</h1>
            <p class="text-green-100 text-sm sm:text-base lg:text-lg">Analisis omset dan performa keuangan perusahaan</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-chart-line text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-calendar-alt text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Omset Bulan Ini</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">Rp {{ $stats['omset_bulan_ini_formatted'] ?? '0' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-calendar text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Omset Tahun Ini</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">Rp {{ $stats['omset_tahun_ini_formatted'] ?? '0' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-purple-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-chart-bar text-purple-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Rata-rata Bulanan</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600">Rp {{ $stats['rata_rata_bulanan_formatted'] ?? '0' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-6">
    <div class="p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan</h3>
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select id="yearFilter" name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @for($year = 2022; $year <= date('Y') + 1; $year++)
                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <select id="monthFilter" name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Bulan</option>
                    @for($month = 1; $month <= 12; $month++)
                        <option value="{{ $month }}">
                            {{ \Carbon\Carbon::createFromDate(null, $month, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select id="periodFilter" name="period" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="monthly">Bulanan</option>
                    <option value="quarterly">Kuartalan</option>
                    <option value="yearly">Tahunan</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Monthly Omset Chart -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-6 sm:mb-8">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Grafik Omset</h2>
                    <p class="text-sm sm:text-base text-gray-600 mt-1" id="chartSubtitle">Tren omset per bulan tahun {{ date('Y') }}</p>
                </div>
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                <span id="chartInfo">Data untuk {{ date('Y') }}</span>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="h-80">
            <canvas id="omsetChart"></canvas>
        </div>
    </div>
</div>

<!-- Top Admin by Omset -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Admin Marketing -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bullhorn text-red-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Top Marketing</h2>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Berdasarkan omset proyek yang ditangani</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Omset</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="marketingTable">
                    <!-- Data will be loaded via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Admin Purchasing -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Top Purchasing</h2>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Berdasarkan omset proyek yang ditangani</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Omset</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="purchasingTable">
                    <!-- Data will be loaded via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prevent MetaMask conflicts
try {
    if (typeof window.ethereum !== 'undefined') {
        window.ethereum.autoRefreshOnNetworkChange = false;
    }
} catch (e) {
    console.log('MetaMask disabled for this page');
}

// Global variables
let omsetChart;
const currentYear = {{ date('Y') }};
const currentMonth = {{ date('n') }};

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

// Function to load chart data
function loadChartData(year = currentYear, month = null, period = 'monthly') {
    // Show loading indicator
    const submitButton = document.querySelector('#filterForm button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...';
    submitButton.disabled = true;

    const params = new URLSearchParams({
        year: year,
        period: period
    });
    
    if (month) {
        params.append('month', month);
    }

    console.log('Loading data with params:', {year, month, period}); // Debug log

    fetch(`{{ route('laporan.omset') }}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data); // Debug log
        updateChart(data.monthlyOmset || [], period, year, month);
        updateAdminTables(data.adminMarketing || [], data.adminPurchasing || []);
        
        // Reset button
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    })
    .catch(error => {
        console.error('Error loading data:', error);
        alert('Terjadi kesalahan saat memuat data: ' + error.message);
        
        // Reset button
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

// Function to update chart
function updateChart(monthlyData, period, year, month) {
    const ctx = document.getElementById('omsetChart').getContext('2d');
    
    // Destroy existing chart
    if (omsetChart) {
        omsetChart.destroy();
    }

    let labels, data;
    let chartTitle = '';

    if (period === 'monthly') {
        labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'];
        data = new Array(12).fill(0);
        if (monthlyData && monthlyData.length > 0) {
            monthlyData.forEach(item => {
                if (item.month && item.month >= 1 && item.month <= 12) {
                    data[item.month - 1] = parseFloat(item.total_omset) || 0;
                }
            });
        }
        chartTitle = month ? `Omset ${labels[month-1]} ${year}` : `Omset Bulanan ${year}`;
    } else if (period === 'quarterly') {
        labels = ['Q1', 'Q2', 'Q3', 'Q4'];
        data = new Array(4).fill(0);
        if (monthlyData && monthlyData.length > 0) {
            monthlyData.forEach(item => {
                if (item.month && item.month >= 1 && item.month <= 12) {
                    const quarter = Math.ceil(item.month / 3) - 1;
                    data[quarter] += parseFloat(item.total_omset) || 0;
                }
            });
        }
        chartTitle = `Omset Kuartalan ${year}`;
    } else if (period === 'yearly') {
        labels = [];
        data = [];
        if (monthlyData && monthlyData.length > 0) {
            const yearlyData = {};
            monthlyData.forEach(item => {
                const itemYear = item.year || year;
                if (!yearlyData[itemYear]) {
                    yearlyData[itemYear] = 0;
                }
                yearlyData[itemYear] += parseFloat(item.total_omset) || 0;
            });
            
            Object.keys(yearlyData).sort().forEach(yr => {
                labels.push(yr);
                data.push(yearlyData[yr]);
            });
        }
        chartTitle = `Omset Tahunan`;
    }

    // Update chart subtitle
    document.getElementById('chartSubtitle').textContent = chartTitle;
    document.getElementById('chartInfo').textContent = `Data untuk ${year}${month ? ` - ${labels[month-1] || month}` : ''}`;

    omsetChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Omset (Rp)',
                data: data,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.1,
                fill: true
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
                            return 'Rp ' + formatRupiahShort(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed.y;
                            return 'Omset: Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
}

// Function to update stats cards
function updateStats(stats) {
    // Update the stats cards with new data if needed
    // This would require updating the backend to return dynamic stats
}

// Function to update admin tables
function updateAdminTables(marketingData, purchasingData) {
    // Update Marketing Table
    const marketingTable = document.getElementById('marketingTable');
    marketingTable.innerHTML = '';
    
    if (marketingData && marketingData.length > 0) {
        marketingData.forEach((admin, index) => {
            const row = `
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-xs font-medium text-red-600">${index + 1}</span>
                            </div>
                            <div class="text-sm font-medium text-gray-900">${admin.name || 'N/A'}</div>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${admin.jumlah_proyek || 0} proyek
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Rp ${formatRupiahShort(admin.total_omset || 0)}
                    </td>
                </tr>
            `;
            marketingTable.innerHTML += row;
        });
    } else {
        marketingTable.innerHTML = `
            <tr>
                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                    Tidak ada data admin marketing
                </td>
            </tr>
        `;
    }

    // Update Purchasing Table
    const purchasingTable = document.getElementById('purchasingTable');
    purchasingTable.innerHTML = '';
    
    if (purchasingData && purchasingData.length > 0) {
        purchasingData.forEach((admin, index) => {
            const row = `
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-xs font-medium text-blue-600">${index + 1}</span>
                            </div>
                            <div class="text-sm font-medium text-gray-900">${admin.name || 'N/A'}</div>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${admin.jumlah_proyek || 0} proyek
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Rp ${formatRupiahShort(admin.total_omset || 0)}
                    </td>
                </tr>
            `;
            purchasingTable.innerHTML += row;
        });
    } else {
        purchasingTable.innerHTML = `
            <tr>
                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                    Tidak ada data admin purchasing
                </td>
            </tr>
        `;
    }
}

// Form filter event handler
document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const year = document.getElementById('yearFilter').value;
    const month = document.getElementById('monthFilter').value;
    const period = document.getElementById('periodFilter').value;
    
    loadChartData(year, month || null, period);
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    const monthlyData = @json($monthlyOmset ?? []);
    const adminMarketing = @json($adminMarketing ?? []);
    const adminPurchasing = @json($adminPurchasing ?? []);
    
    // Debug logs
    console.log('Initial monthly data:', monthlyData);
    console.log('Initial admin marketing:', adminMarketing);
    console.log('Initial admin purchasing:', adminPurchasing);
    
    // Initialize chart with current data
    updateChart(monthlyData, 'monthly', currentYear, null);
    updateAdminTables(adminMarketing, adminPurchasing);
    
    console.log('Omset chart loaded for year ' + currentYear);
});
</script>
@endsection
