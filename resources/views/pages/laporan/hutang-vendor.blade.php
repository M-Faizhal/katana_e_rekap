@extends('layouts.app')

@section('title', 'Hutang Vendor - Cyber KATANA')

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
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Laporan Omset
            </a>
            <a href="{{ route('laporan.hutang-vendor') }}" 
               class="border-red-500 text-red-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
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
<div class="bg-orange-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Hutang Vendor</h1>
            <p class="text-orange-100 text-sm sm:text-base lg:text-lg">Monitoring hutang dan kewajiban pembayaran kepada vendor</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-credit-card text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-exclamation-triangle text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Hutang</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">Rp {{ number_format($stats['total_hutang'] ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-purple-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-building text-purple-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Vendor Belum Lunas</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600">{{ $stats['jumlah_vendor'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-calculator text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Rata-rata Hutang</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">Rp {{ number_format($stats['rata_rata_hutang'] ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-6 sm:mb-8">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-filter text-orange-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Filter Hutang Vendor</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Filter berdasarkan status dan vendor</p>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                <select id="vendor-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Semua Vendor</option>
                    @if(isset($allVendors))
                        @foreach($allVendors as $vendor)
                            <option value="{{ $vendor->nama_vendor }}" {{ request('vendor') == $vendor->nama_vendor ? 'selected' : '' }}>
                                {{ $vendor->nama_vendor }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Range Nominal</label>
                <select id="nominal-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Semua Nominal</option>
                    <option value="0-10jt" {{ request('nominal') == '0-10jt' ? 'selected' : '' }}>< Rp 10 Juta</option>
                    <option value="10-50jt" {{ request('nominal') == '10-50jt' ? 'selected' : '' }}>Rp 10 - 50 Juta</option>
                    <option value="50-100jt" {{ request('nominal') == '50-100jt' ? 'selected' : '' }}>Rp 50 - 100 Juta</option>
                    <option value="100jt+" {{ request('nominal') == '100jt+' ? 'selected' : '' }}>> Rp 100 Juta</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="applyFilters()" class="flex-1 sm:flex-none bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-all duration-200">
                <i class="fas fa-search mr-2"></i>Terapkan Filter
            </button>
            <button onclick="resetFilters()" class="flex-1 sm:flex-none border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-undo mr-2"></i>Reset Filter
            </button>
        </div>
    </div>
</div>

<!-- Hutang Vendor Table -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-list text-orange-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Daftar Hutang Vendor</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Pembayaran ke vendor yang masih pending atau belum lunas</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Modal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sudah Dibayar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Bayar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($hutangVendor as $hutang)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-building text-purple-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $hutang->vendor->nama_vendor }}</div>
                                <div class="text-sm text-gray-500">{{ $hutang->vendor->jenis_perusahaan ?? 'Vendor' }}</div>
                                @if($hutang->vendor->email)
                                    <div class="text-xs text-gray-400">{{ $hutang->vendor->email }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-medium">{{ $hutang->proyek->kode_proyek }}</div>
                        <div class="text-sm text-gray-500">{{ $hutang->proyek->nama_klien }}</div>
                        @if($hutang->proyek->instansi)
                            <div class="text-xs text-gray-400">{{ $hutang->proyek->instansi }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        @if($hutang->warning_hps)
                            <span class="text-orange-600">-</span>
                            <div class="text-xs text-orange-600">{{ $hutang->warning_hps }}</div>
                        @else
                            @php
                                $nominal = $hutang->total_vendor;
                                if ($nominal >= 1000000000) {
                                    echo 'Rp ' . number_format($nominal / 1000000000, 1, ',', '.') . ' M';
                                } elseif ($nominal >= 1000000) {
                                    echo 'Rp ' . number_format($nominal / 1000000, 1, ',', '.') . ' jt';
                                } elseif ($nominal >= 1000) {
                                    echo 'Rp ' . number_format($nominal / 1000, 1, ',', '.') . ' rb';
                                } else {
                                    echo 'Rp ' . number_format($nominal, 2, ',', '.');
                                }
                            @endphp
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @php
                            $nominal = $hutang->total_dibayar_approved;
                            if ($nominal >= 1000000000) {
                                echo 'Rp ' . number_format($nominal / 1000000000, 1, ',', '.') . ' M';
                            } elseif ($nominal >= 1000000) {
                                echo 'Rp ' . number_format($nominal / 1000000, 1, ',', '.') . ' jt';
                            } elseif ($nominal >= 1000) {
                                echo 'Rp ' . number_format($nominal / 1000, 1, ',', '.') . ' rb';
                            } else {
                                echo 'Rp ' . number_format($nominal, 2, ',', '.');
                            }
                        @endphp
                        @if($hutang->total_vendor > 0)
                            <div class="text-xs text-gray-500">{{ number_format($hutang->persen_bayar, 1) }}%</div>
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                <div class="@if($hutang->status_lunas) bg-green-600 @else bg-blue-600 @endif h-1.5 rounded-full transition-all duration-300" 
                                     style="width: {{ min($hutang->persen_bayar, 100) }}%"></div>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if($hutang->warning_hps)
                            <span class="text-orange-600">-</span>
                        @else
                            @php
                                $nominal = $hutang->sisa_bayar;
                                if ($nominal >= 1000000000) {
                                    echo '<span class="text-red-600">Rp ' . number_format($nominal / 1000000000, 1, ',', '.') . ' M</span>';
                                } elseif ($nominal >= 1000000) {
                                    echo '<span class="text-red-600">Rp ' . number_format($nominal / 1000000, 1, ',', '.') . ' jt</span>';
                                } elseif ($nominal >= 1000) {
                                    echo '<span class="text-red-600">Rp ' . number_format($nominal / 1000, 1, ',', '.') . ' rb</span>';
                                } else {
                                    echo '<span class="text-red-600">Rp ' . number_format($nominal, 2, ',', '.') . '</span>';
                                }
                            @endphp
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($hutang->warning_hps)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                HPS Belum Diisi
                            </span>
                        @elseif($hutang->status_lunas)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                LUNAS
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                BELUM LUNAS
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center py-8">
                            <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium mb-2">Tidak ada hutang vendor</p>
                            <p class="text-sm">Semua pembayaran ke vendor sudah disetujui atau tidak ada pembayaran pending</p>
                        </div>
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
                Menampilkan <span class="font-medium">{{ $hutangVendor->firstItem() ?? 0 }}</span> sampai <span class="font-medium">{{ $hutangVendor->lastItem() ?? 0 }}</span> dari <span class="font-medium">{{ $hutangVendor->total() }}</span> hutang
            </div>
            <div class="flex items-center space-x-2">
                {{ $hutangVendor->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const vendor = document.getElementById('vendor-filter').value;
    const nominal = document.getElementById('nominal-filter').value;

    // Build URL with query parameters
    const params = new URLSearchParams();
    if (vendor) params.append('vendor', vendor);
    if (nominal) params.append('nominal', nominal);

    // Redirect with filters
    window.location.href = '{{ route("laporan.hutang-vendor") }}?' + params.toString();
}

function resetFilters() {
    window.location.href = '{{ route("laporan.hutang-vendor") }}';
}
</script>
@endsection
