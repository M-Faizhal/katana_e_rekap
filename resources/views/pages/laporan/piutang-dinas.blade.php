@extends('layouts.app')

@section('title', 'Piutang Dinas - Cyber KATANA')

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
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Hutang Vendor
            </a>
            <a href="{{ route('laporan.piutang-dinas') }}" 
               class="border-red-500 text-red-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Piutang Dinas
            </a>
        </nav>
    </div>
</div>

<!-- Header Section -->
<div class="bg-blue-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Piutang Dinas</h1>
            <p class="text-blue-100 text-sm sm:text-base lg:text-lg">Monitoring piutang dan tagihan dari instansi/dinas</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-receipt text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-money-bill-wave text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Piutang</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">Rp {{ $stats['total_piutang_formatted'] ?? '0' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-exclamation-triangle text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Jatuh Tempo</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">Rp {{ $stats['piutang_jatuh_tempo_formatted'] ?? '0' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-purple-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-file-invoice text-purple-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Jumlah Proyek</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600">{{ $stats['jumlah_proyek'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-calculator text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Rata-rata Piutang</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">Rp {{ $stats['rata_rata_piutang_formatted'] ?? '0' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-6 sm:mb-8">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-filter text-blue-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Filter Piutang Dinas</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Filter berdasarkan status dan instansi</p>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                <select id="status-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Belum Bayar/Belum Ditagih</option>
                    <option value="partial">Sebagian (DP)</option>
                    <option value="overdue">Terlambat</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instansi</label>
                <select id="instansi-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Instansi</option>
                    @foreach($piutangDinas->unique('proyek.instansi')->filter(function($item) { return $item->proyek && $item->proyek->instansi; }) as $piutang)
                        <option value="{{ $piutang->proyek->instansi }}" {{ request('instansi') == $piutang->proyek->instansi ? 'selected' : '' }}>
                            {{ $piutang->proyek->instansi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Range Nominal</label>
                <select id="nominal-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Nominal</option>
                    <option value="0-10jt">< Rp 10 Juta</option>
                    <option value="10-50jt">Rp 10 - 50 Juta</option>
                    <option value="50-100jt">Rp 50 - 100 Juta</option>
                    <option value="100jt+"> > Rp 100 Juta</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="applyFilters()" class="flex-1 sm:flex-none bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200">
                <i class="fas fa-search mr-2"></i>Terapkan Filter
            </button>
            <button onclick="resetFilters()" class="flex-1 sm:flex-none border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-undo mr-2"></i>Reset Filter
            </button>
            <button onclick="showAllData()" class="flex-1 sm:flex-none bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-200">
                <i class="fas fa-list-alt mr-2"></i>Tampilkan Semua Data
            </button>
        </div>
    </div>
</div>

<!-- Piutang Dinas Table -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-list text-blue-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Daftar Piutang Dinas</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Tagihan yang belum dibayar oleh instansi</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Pembayaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterlambatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($piutangDinas as $piutang)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $piutang->nomor_invoice }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $piutang->proyek->kode_proyek ?? '-' }}</div>
                        <div class="text-sm text-gray-500">{{ $piutang->proyek->kab_kota ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $piutang->proyek->instansi ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        @php
                            $nominal = $piutang->sisa_pembayaran;
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $piutang->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($piutang->status_pembayaran == 'belum_bayar' || $piutang->status_pembayaran == 'belum_ditagih') bg-yellow-100 text-yellow-800
                            @elseif($piutang->status_pembayaran == 'dp') bg-blue-100 text-blue-800
                            @elseif($piutang->status_pembayaran == 'lunas') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($piutang->status_pembayaran == 'belum_bayar') 
                                Belum Bayar
                            @elseif($piutang->status_pembayaran == 'belum_ditagih') 
                                Belum Ditagih
                            @elseif($piutang->status_pembayaran == 'dp') 
                                DP Dibayar
                            @elseif($piutang->status_pembayaran == 'lunas') 
                                Lunas
                            @else 
                                {{ ucfirst($piutang->status_pembayaran) }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($piutang->hari_telat > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $piutang->hari_telat }} hari
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                On Time
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada piutang dinas yang ditemukan
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
                Menampilkan <span class="font-medium">{{ $piutangDinas->firstItem() ?? 0 }}</span> sampai <span class="font-medium">{{ $piutangDinas->lastItem() ?? 0 }}</span> dari <span class="font-medium">{{ $piutangDinas->total() }}</span> tagihan
            </div>
            <div class="flex items-center space-x-2">
                {{ $piutangDinas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const status = document.getElementById('status-filter').value;
    const instansi = document.getElementById('instansi-filter').value;
    const nominal = document.getElementById('nominal-filter').value;

    // Build URL with query parameters
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (instansi) params.append('instansi', instansi);
    if (nominal) params.append('nominal', nominal);

    // Redirect with filters
    window.location.href = '{{ route("laporan.piutang-dinas") }}?' + params.toString();
}

function resetFilters() {
    window.location.href = '{{ route("laporan.piutang-dinas") }}';
}

function showAllData() {
    const params = new URLSearchParams();
    params.append('show_all', 'true');
    window.location.href = '{{ route("laporan.piutang-dinas") }}?' + params.toString();
}
</script>
@endsection
