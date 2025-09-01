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

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-{{ ($stats['pertumbuhan'] ?? 0) >= 0 ? 'green' : 'red' }}-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-trending-{{ ($stats['pertumbuhan'] ?? 0) >= 0 ? 'up' : 'down' }} text-{{ ($stats['pertumbuhan'] ?? 0) >= 0 ? 'green' : 'red' }}-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Pertumbuhan</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-{{ ($stats['pertumbuhan'] ?? 0) >= 0 ? 'green' : 'red' }}-600">
                    {{ number_format($stats['pertumbuhan'] ?? 0, 1) }}%
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
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Grafik Omset Bulanan</h2>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Tren omset per bulan tahun ini</p>
                </div>
            </div>
            <div>
                <select id="year-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                    <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                    <option value="{{ date('Y') - 2 }}">{{ date('Y') - 2 }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="h-80">
            <canvas id="omsetChart"></canvas>
        </div>
    </div>
</div>

<!-- Top Vendor by Omset -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-building text-green-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Top 10 Vendor by Omset</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Vendor dengan kontribusi omset terbesar</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Vendor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Omset</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontribusi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $totalOmsetAll = $vendorOmset->sum('total_omset');
                @endphp
                @forelse($vendorOmset as $index => $vendor)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($index < 3)
                                <div class="w-8 h-8 rounded-full bg-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : 'yellow') }}-400 flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $vendor->nama_vendor }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $vendor->jumlah_proyek }} proyek
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        @php
                            $totalOmset = $vendor->total_omset;
                            if ($totalOmset >= 1000000000) {
                                echo 'Rp ' . number_format($totalOmset / 1000000000, 1, ',', '.') . ' M';
                            } elseif ($totalOmset >= 1000000) {
                                echo 'Rp ' . number_format($totalOmset / 1000000, 1, ',', '.') . ' jt';
                            } elseif ($totalOmset >= 1000) {
                                echo 'Rp ' . number_format($totalOmset / 1000, 1, ',', '.') . ' rb';
                            } else {
                                echo 'Rp ' . number_format($totalOmset, 0, ',', '.');
                            }
                        @endphp
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $percentage = $totalOmsetAll > 0 ? ($vendor->total_omset / $totalOmsetAll) * 100 : 0;
                        @endphp
                        <div class="flex items-center">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600">{{ number_format($percentage, 1) }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data vendor ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prevent MetaMask conflicts
try {
    // Disable MetaMask auto-injection if it exists
    if (typeof window.ethereum !== 'undefined') {
        window.ethereum.autoRefreshOnNetworkChange = false;
    }
} catch (e) {
    // Ignore MetaMask errors
    console.log('MetaMask disabled for this page');
}

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

// Prepare data for chart
const monthlyData = @json($monthlyOmset);
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'];

// Create array for 12 months with 0 as default
let omsetData = new Array(12).fill(0);
let proyekData = new Array(12).fill(0);

// Fill data from backend
monthlyData.forEach(data => {
    omsetData[data.month - 1] = data.total_omset;
    proyekData[data.month - 1] = data.jumlah_proyek;
});

// Chart configuration
const ctx = document.getElementById('omsetChart').getContext('2d');
let omsetChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Omset (Rp)',
            data: omsetData,
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
                    callback: function(value, index, values) {
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

// Year filter event handler
document.addEventListener('DOMContentLoaded', function() {
    const yearFilter = document.getElementById('year-filter');
    if (yearFilter) {
        yearFilter.addEventListener('change', function(e) {
            try {
                const selectedYear = e.target.value;
                
                // Send AJAX request to get new data
                fetch('{{ route("laporan.omset") }}?year=' + selectedYear, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.monthlyOmset) {
                        // Update chart data
                        let newOmsetData = new Array(12).fill(0);
                        data.monthlyOmset.forEach(item => {
                            newOmsetData[item.month - 1] = item.total_omset;
                        });
                        
                        omsetChart.data.datasets[0].data = newOmsetData;
                        omsetChart.update();
                    }
                })
                .catch(error => {
                    console.log('Error updating chart:', error);
                    // Fallback: reload page if AJAX fails
                    window.location.href = '{{ route("laporan.omset") }}?year=' + selectedYear;
                });
            } catch (error) {
                console.log('Year filter error:', error);
                // Fallback: reload page
                const selectedYear = e.target.value;
                window.location.href = '{{ route("laporan.omset") }}?year=' + selectedYear;
            }
        });
    }
});
</script>
@endsection
