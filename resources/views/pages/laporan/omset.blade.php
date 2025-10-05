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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-purple-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-chart-line text-purple-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">
                    @if(request('year') && request('year') != 'all')
                        Total Omset Sampai Tahun {{ request('year') }}
                    @else
                        Total Omset Sampai Saat Ini
                    @endif
                </h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600">
                    @php
                        $totalOmset = $stats['total_omset'] ?? 0;
                        if ($totalOmset >= 1000000000) {
                            echo 'Rp ' . number_format($totalOmset / 1000000000, 1) . ' Miliar';
                        } elseif ($totalOmset >= 1000000) {
                            echo 'Rp ' . number_format($totalOmset / 1000000, 1) . ' Juta';
                        } else {
                            echo 'Rp ' . number_format($totalOmset, 0, ',', '.');
                        }
                    @endphp
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-calendar text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">
                    @if(request('year') && request('year') != 'all')
                        Omset Tahun {{ request('year') }}
                    @else
                        Omset Tahun Ini
                    @endif
                </h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">
                    @php
                        $omsetTahunIni = $stats['omset_tahun_ini'] ?? 0;
                        if ($omsetTahunIni >= 1000000000) {
                            echo 'Rp ' . number_format($omsetTahunIni / 1000000000, 1) . ' Miliar';
                        } elseif ($omsetTahunIni >= 1000000) {
                            echo 'Rp ' . number_format($omsetTahunIni / 1000000, 1) . ' Juta';
                        } else {
                            echo 'Rp ' . number_format($omsetTahunIni, 0, ',', '.');
                        }
                    @endphp
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-calendar-alt text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">
                    @if(request('year') && request('year') != 'all' && request('year') != date('Y'))
                        Omset Desember {{ request('year') }}
                    @else
                        Omset Bulan Ini
                    @endif
                </h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">
                    @php
                        $omsetBulanIni = $stats['omset_bulan_ini'] ?? 0;
                        if ($omsetBulanIni >= 1000000000) {
                            echo 'Rp ' . number_format($omsetBulanIni / 1000000000, 1) . ' Miliar';
                        } elseif ($omsetBulanIni >= 1000000) {
                            echo 'Rp ' . number_format($omsetBulanIni / 1000000, 1) . ' Juta';
                        } else {
                            echo 'Rp ' . number_format($omsetBulanIni, 0, ',', '.');
                        }
                    @endphp
                </p>
            </div>
        </div>
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
                    <h3 class="text-lg font-bold text-gray-800">Omset Per Bulan</h3>
                    <p class="text-sm text-gray-600" id="chartSubtitle">
                        @if(request('year') == 'all')
                            Distribusi omset tahunan
                        @else
                            Distribusi omset bulanan {{ request('year', date('Y')) }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Tahun:</label>
                <div class="flex items-center space-x-1">
                    <button onclick="changeOmsetYear(-1)" class="w-8 h-8 flex items-center justify-center text-sm border border-gray-300 rounded-l-lg hover:bg-gray-50 focus:ring-2 focus:ring-green-500">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <input type="text" id="omsetChartYear" value="{{ request('year') == 'all' ? 'all' : request('year', date('Y')) }}" 
                           min="{{ $yearRange['min'] ?? 2020 }}" max="{{ $yearRange['max'] ?? date('Y') }}"
                           class="w-20 text-sm text-center border-t border-b border-gray-300 py-1 focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           onchange="updateOmsetChart()" readonly>
                    <button onclick="changeOmsetYear(1)" class="w-8 h-8 flex items-center justify-center text-sm border border-gray-300 rounded-r-lg hover:bg-gray-50 focus:ring-2 focus:ring-green-500">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
                <button onclick="showAllYears()" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-chart-line mr-1"></i>
                    Lihat Semua Tahun
                </button>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
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
const minYear = {{ $yearRange['min'] ?? 2020 }};
const maxYear = {{ $yearRange['max'] ?? date('Y') }};

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

// Update omset chart based on selected year
function updateOmsetChart() {
    const selectedYear = document.getElementById('omsetChartYear').value;
    console.log('updateOmsetChart called with selectedYear:', selectedYear);
    
    try {
        showNotification(`Memuat data omset tahun ${selectedYear === 'all' ? 'semua' : selectedYear}...`, 'info');
        
        // Simple page redirect with year filter
        const params = new URLSearchParams();
        if (selectedYear !== 'all') {
            params.append('year', selectedYear);
        } else {
            params.append('year', 'all');
        }
        
        const newUrl = '{{ route("laporan.omset") }}?' + params.toString();
        console.log('Redirecting to:', newUrl);
        window.location.href = newUrl;
        
    } catch (error) {
        console.error('Error updating omset chart:', error);
        showNotification('Gagal memuat data omset: ' + error.message, 'error');
    }
}

// Change year with +/- buttons
function changeOmsetYear(direction) {
    const input = document.getElementById('omsetChartYear');
    let currentValue = input.value;
    
    // Skip if current value is 'all'
    if (currentValue === 'all') {
        if (direction === 1) {
            // From 'all', go to current year + 1 if direction is +1
            input.value = Math.min(currentYear + 1, maxYear);
        } else {
            // From 'all', go to current year - 1 if direction is -1
            input.value = Math.max(currentYear - 1, minYear);
        }
        updateOmsetChart();
        return;
    }
    
    let currentYearValue = parseInt(currentValue);
    const newYear = currentYearValue + direction;
    
    if (newYear >= minYear && newYear <= maxYear) {
        input.value = newYear;
        updateOmsetChart();
    } else {
        const limitText = newYear < minYear ? `minimum (${minYear})` : `maksimum (${maxYear})`;
        showNotification(`Tahun ${limitText} tercapai`, 'warning');
    }
}

// Show all years data
function showAllYears() {
    const input = document.getElementById('omsetChartYear');
    console.log('Before setting all - current value:', input.value);
    input.value = 'all';
    console.log('After setting all - new value:', input.value);
    updateOmsetChart();
}

// Simple notification function
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
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
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

// Initialize chart on page load
document.addEventListener('DOMContentLoaded', function() {
    // Data from PHP
    const monthlyOmset = @json($monthlyOmset);
    const adminMarketing = @json($adminMarketing);
    const adminPurchasing = @json($adminPurchasing);
    
    console.log('Initial data loaded:', { 
        monthlyCount: monthlyOmset ? monthlyOmset.length : 0,
        marketingCount: adminMarketing ? adminMarketing.length : 0,
        purchasingCount: adminPurchasing ? adminPurchasing.length : 0,
        yearRange: { min: minYear, max: maxYear }
    });
    
    // Initialize chart
    initializeOmsetChart(monthlyOmset);
    
    // Initialize admin tables
    updateAdminTables(adminMarketing, adminPurchasing);
});

// Initialize omset chart
function initializeOmsetChart(monthlyData) {
    const ctx = document.getElementById('omsetChart').getContext('2d');
    const currentYear = {{ date('Y') }};
    const requestYear = '{{ request("year") }}';
    
    console.log('initializeOmsetChart called with:');
    console.log('- requestYear:', requestYear);
    console.log('- monthlyData:', monthlyData);
    
    // Prepare data for chart
    let labels, data;
    
    if (requestYear === 'all') {
        console.log('Using YEARLY mode');
        // Show yearly data
        labels = [];
        data = [];
        
        if (monthlyData && monthlyData.length > 0) {
            monthlyData.forEach(item => {
                if (item.year) {
                    labels.push('Tahun ' + item.year.toString());
                    data.push(parseFloat(item.total_omset) || 0);
                }
            });
        }
        console.log('Yearly labels:', labels);
        console.log('Yearly data:', data);
    } else {
        console.log('Using MONTHLY mode for year:', requestYear || 'current');
        // Show monthly data for selected year
        labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        data = new Array(12).fill(0);
        
        if (monthlyData && monthlyData.length > 0) {
            monthlyData.forEach(item => {
                if (item.month && item.month >= 1 && item.month <= 12) {
                    data[item.month - 1] = parseFloat(item.total_omset) || 0;
                }
            });
        }
        console.log('Monthly labels:', labels);
        console.log('Monthly data:', data);
    }
    
    // Destroy existing chart if it exists
    if (omsetChart) {
        omsetChart.destroy();
    }
    
    omsetChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Omset (Rp)',
                data: data,
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
                            return formatRupiahShort(value);
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
                            const value = context.parsed.y;
                            if (value >= 1000000000) {
                                return 'Omset: Rp ' + (value / 1000000000).toFixed(1) + ' Miliar';
                            } else if (value >= 1000000) {
                                return 'Omset: Rp ' + (value / 1000000).toFixed(1) + ' Juta';
                            } else {
                                return 'Omset: Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        }
    });
}
</script>
@endsection
