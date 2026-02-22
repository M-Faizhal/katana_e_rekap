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
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Omset Marketing</h1>
           
        </div>
        <div class="flex items-center space-x-4">
            <button onclick="exportOmset()" class="bg-white text-green-800 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors duration-200 flex items-center space-x-2 shadow-md">
                <i class="fas fa-file-excel text-lg"></i>
                <span class="font-semibold">Export Excel</span>
            </button>
            <div class="hidden lg:block">
                <i class="fas fa-chart-line text-3xl sm:text-4xl lg:text-6xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">

    {{-- Card: Omset tahun terpilih --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-calendar text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-base font-semibold text-gray-600 truncate">
                    @if(request()->has('all'))
                        Omset Tahun Ini ({{ date('Y') }})
                    @else
                        Omset Tahun Potensi {{ $selectedYear }}
                    @endif
                </h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">
                    Rp {{ number_format($stats['omset_tahun_ini'] ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Card: Omset bulan berjalan --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-calendar-alt text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-base font-semibold text-gray-600 truncate">
                    @if(!request()->has('all') && $selectedYear != date('Y'))
                        Omset Desember {{ $selectedYear }}
                    @else
                        Omset Bulan Ini ({{ \Carbon\Carbon::now()->translatedFormat('F') }})
                    @endif
                </h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">
                    Rp {{ number_format($stats['omset_bulan_ini'] ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

</div>

<!-- Omset Chart: Perbandingan Internal vs Eksternal -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-6 sm:mb-8">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-pie text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Perbandingan Omset: Internal vs Eksternal
                    </h3>
                    <p class="text-sm text-gray-500">
                        @if(request()->has('all'))
                            Semua tahun potensi
                        @else
                            Tahun potensi {{ $selectedYear }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button onclick="changeOmsetYear(-1)" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" title="Tahun sebelumnya">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <input type="text" id="omsetChartYear" value="{{ request()->has('all') ? 'all' : $selectedYear }}"
                       class="w-20 text-sm font-semibold text-center border-2 border-gray-300 rounded-lg py-1.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                       readonly
                       title="Range: {{ $yearRange['min_year'] ?? 2020 }} - {{ $yearRange['max_year'] ?? date('Y') }}">
                <button onclick="changeOmsetYear(1)" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" title="Tahun berikutnya">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
                <button onclick="showAllYears()" class="px-3 py-1.5 text-xs font-medium {{ request()->has('all') ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">
                    <i class="fas fa-layer-group mr-1"></i>Semua Tahun
                </button>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-16">
            {{-- Pie chart --}}
            <div class="relative" style="width:260px;height:260px;flex-shrink:0;">
                <canvas id="labelPieChart"></canvas>
            </div>
            {{-- Legend --}}
            <div class="flex-1 max-w-sm w-full space-y-4 text-sm" id="labelPieLegend">
                {{-- filled by JS --}}
            </div>
        </div>
    </div>
</div>

<!-- Top Admin by Omset -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Top Admin Marketing Internal -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bullhorn text-red-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Top Marketing Internal</h2>
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Omset</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="marketingInternalTable">
                    <!-- Data will be loaded via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Admin Marketing Eksternal -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-globe text-orange-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Top Marketing Eksternal</h2>
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Omset</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="marketingEksternalTable">
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
const minYear = {{ isset($yearRange) && isset($yearRange['min_year']) ? $yearRange['min_year'] : 2020 }};
const maxYear = {{ isset($yearRange) && isset($yearRange['max_year']) ? $yearRange['max_year'] : date('Y') }};

// Short format for chart Y-axis ticks only
function formatRupiahShort(value) {
    if (value >= 1000000000000) return (value / 1000000000000).toFixed(1) + ' T';
    if (value >= 1000000000)    return (value / 1000000000).toFixed(1) + ' M';
    if (value >= 1000000)       return (value / 1000000).toFixed(1) + ' jt';
    if (value >= 1000)          return (value / 1000).toFixed(1) + ' rb';
    return value.toLocaleString('id-ID');
}

// Debug year range
console.log('Year range received:', {!! json_encode($yearRange ?? []) !!});
console.log('Min year:', minYear, 'Max year:', maxYear);




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
    window.location.href = '{{ route("laporan.omset") }}?all=1';
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

// Function to build a marketing table body HTML string
function buildMarketingRows(data, accentColor) {
    if (!data || data.length === 0) {
        return `<tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada data</td></tr>`;
    }
    // Use static classes to avoid Tailwind purge issues
    const bgClass   = accentColor === 'red'    ? 'bg-red-100'    : 'bg-orange-100';
    const textClass = accentColor === 'red'    ? 'text-red-600'  : 'text-orange-600';
    return data.map((admin, index) => `
        <tr class="hover:bg-gray-50 transition-colors duration-150">
            <td class="px-4 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="w-8 h-8 ${bgClass} rounded-full flex items-center justify-center mr-3">
                        <span class="text-xs font-medium ${textClass}">${index + 1}</span>
                    </div>
                    <div class="text-sm font-medium text-gray-900">${admin.name || 'N/A'}</div>
                </div>
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                ${admin.jumlah_proyek || 0} proyek
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                Rp ${parseInt(admin.total_omset || 0).toLocaleString('id-ID')}
            </td>
        </tr>
    `).join('');
}

// Function to update admin tables
function updateAdminTables(marketingInternalData, marketingEksternalData, purchasingData) {
    // Marketing Internal
    const marketingInternalTable = document.getElementById('marketingInternalTable');
    if (marketingInternalTable) {
        marketingInternalTable.innerHTML = buildMarketingRows(marketingInternalData, 'red');
    }

    // Marketing Eksternal
    const marketingEksternalTable = document.getElementById('marketingEksternalTable');
    if (marketingEksternalTable) {
        marketingEksternalTable.innerHTML = buildMarketingRows(marketingEksternalData, 'orange');
    }

    // Purchasing
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
                        Rp ${parseInt(admin.total_omset || 0).toLocaleString('id-ID')}
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
    const adminMarketingInternal = @json($adminMarketingInternal);
    const adminMarketingEksternal = @json($adminMarketingEksternal);
    const omsetByLabel = @json($omsetByLabel);

    console.log('Initial data loaded:', { 
        monthlyCount: monthlyOmset ? monthlyOmset.length : 0,
        marketingCount: adminMarketing ? adminMarketing.length : 0,
        marketingInternalCount: adminMarketingInternal ? adminMarketingInternal.length : 0,
        marketingEksternalCount: adminMarketingEksternal ? adminMarketingEksternal.length : 0,
        purchasingCount: adminPurchasing ? adminPurchasing.length : 0,
        omsetByLabel,
        yearRange: { min: minYear, max: maxYear }
    });

    // Initialize bar chart
    initializeOmsetChart(monthlyOmset);

    // Initialize pie chart (internal vs eksternal)
    initializeLabelPieChart(omsetByLabel);
    // Initialize admin tables
    updateAdminTables(adminMarketingInternal, adminMarketingEksternal, adminPurchasing);
});

// Pie chart: internal vs eksternal
function initializeLabelPieChart(omsetByLabel) {
    const internal  = omsetByLabel.internal  || 0;
    const eksternal = omsetByLabel.eksternal || 0;
    const total     = internal + eksternal;

    const ctx = document.getElementById('labelPieChart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Internal', 'Eksternal'],
            datasets: [{
                data: [internal, eksternal],
                backgroundColor: ['#ef4444', '#f97316'],
                borderColor: ['#fff', '#fff'],
                borderWidth: 4,
                hoverOffset: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const val = context.parsed;
                            const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return ' ' + context.label + ': Rp ' + Math.round(val).toLocaleString('id-ID') + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });

    // Build legend
    const legend = document.getElementById('labelPieLegend');
    const pctInt = total > 0 ? ((internal / total) * 100).toFixed(1) : 0;
    const pctExt = total > 0 ? ((eksternal / total) * 100).toFixed(1) : 0;

    legend.innerHTML = `
        <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl border border-red-100">
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full bg-red-500 flex-shrink-0"></div>
                <div>
                    <div class="font-bold text-gray-800 text-base">Internal</div>
                    <div class="text-xs text-gray-500">Marketing Internal</div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-xl font-bold text-red-600">${pctInt}%</div>
                <div class="text-sm font-semibold text-gray-700">Rp ${Math.round(internal).toLocaleString('id-ID')}</div>
            </div>
        </div>
        <div class="flex items-center justify-between p-4 bg-orange-50 rounded-xl border border-orange-100">
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full bg-orange-500 flex-shrink-0"></div>
                <div>
                    <div class="font-bold text-gray-800 text-base">Eksternal</div>
                    <div class="text-xs text-gray-500">Marketing Eksternal</div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-xl font-bold text-orange-600">${pctExt}%</div>
                <div class="text-sm font-semibold text-gray-700">Rp ${Math.round(eksternal).toLocaleString('id-ID')}</div>
            </div>
        </div>
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 mt-2">
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full bg-gray-400 flex-shrink-0"></div>
                <div class="font-semibold text-gray-600 text-base">Total Omset</div>
            </div>
            <div class="text-right">
                <div class="text-sm font-bold text-gray-800">Rp ${Math.round(total).toLocaleString('id-ID')}</div>
            </div>
        </div>
    `;
}

// Initialize omset chart (kept for reference, not rendered)
function initializeOmsetChart(monthlyData) {
    // Bar chart replaced by pie chart — function kept to avoid JS errors
}

// Function to export omset to Excel
function exportOmset() {
    const selectedYear = document.getElementById('omsetChartYear').value;
    const yearParam = selectedYear !== 'all' ? selectedYear : new Date().getFullYear();
    
    showNotification('Mengunduh laporan omset tahun ' + yearParam + '...', 'info');
    
    // Build URL with year parameter
    const url = '{{ route("laporan.export-omset-marketing") }}?year=' + yearParam;
    
    // Open in new window to trigger download
    window.location.href = url;
}
</script>
@endsection

