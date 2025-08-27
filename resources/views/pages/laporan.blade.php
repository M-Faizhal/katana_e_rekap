@extends('layouts.app')

@section('title', 'Laporan - Cyber KATANA')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Laporan Proyek</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Laporan proyek yang telah diverifikasi dan disetujui admin keuangan</p>
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
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-check-circle text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Proyek Selesai</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">{{ $stats['proyek_selesai'] }}</p>
                <p class="text-xs sm:text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +{{ $stats['proyek_selesai_bulan_ini'] }} bulan ini
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-money-bill-wave text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Nilai Proyek</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">Rp {{ number_format($stats['total_nilai_proyek'] / 1000000, 1) }}M</p>
                <p class="text-xs sm:text-sm text-blue-500">
                    <i class="fas fa-money-bill-wave"></i> Disetujui
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-purple-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-building text-purple-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Vendor Aktif</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600">{{ $stats['vendor_aktif'] }}</p>
                <p class="text-xs sm:text-sm text-purple-500">
                    <i class="fas fa-handshake"></i> Partner
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-box text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Jenis Produk</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">{{ $stats['jenis_produk'] }}</p>
                <p class="text-xs sm:text-sm text-yellow-500">
                    <i class="fas fa-tags"></i> Kategori
                </p>
            </div>
        </div>
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
                <p class="text-sm sm:text-base text-gray-600 mt-1">Filter berdasarkan vendor, produk, periode, dan status</p>
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Periode Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Laporan</label>
                <select id="periode-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Periode</option>
                    <option value="bulan-ini" {{ request('periode') == 'bulan-ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="3-bulan" {{ request('periode') == '3-bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    <option value="6-bulan" {{ request('periode') == '6-bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                    <option value="tahun-ini" {{ request('periode') == 'tahun-ini' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>

            <!-- Vendor Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                <select id="vendor-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Vendor</option>
                    @if(isset($filterOptions['vendors']))
                        @foreach($filterOptions['vendors'] as $vendor)
                            <option value="{{ $vendor->nama_vendor }}" {{ request('vendor') == $vendor->nama_vendor ? 'selected' : '' }}>
                                {{ $vendor->nama_vendor }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Product Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Produk</label>
                <select id="kategori-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Kategori</option>
                    @if(isset($filterOptions['categories']))
                        @foreach($filterOptions['categories'] as $category)
                            @if($category->kategori)
                                <option value="{{ $category->kategori }}" {{ request('kategori') == $category->kategori ? 'selected' : '' }}>
                                    {{ $category->kategori }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Verifikasi</label>
                <select id="status-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Status</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Diverifikasi</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pembayaran Selesai</option>
                </select>
            </div>
        </div>

        <!-- Specific Product Filter -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Produk Spesifik</label>
                <select id="produk-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Produk</option>
                    @if(isset($filterOptions['products']))
                        @foreach($filterOptions['products'] as $product)
                            <option value="{{ $product->nama_barang }}" {{ request('produk') == $product->nama_barang ? 'selected' : '' }}>
                                {{ $product->nama_barang }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Departemen Pemohon</label>
                <select id="departemen-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Departemen</option>
                    <option value="marketing">Marketing</option>
                    <option value="purchasing">Purchasing</option>
                    <option value="finance">Keuangan</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Range Nilai</label>
                <select id="nilai-filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Nilai</option>
                    <option value="0-5jt" {{ request('nilai') == '0-5jt' ? 'selected' : '' }}>Rp 0 - 5 Juta</option>
                    <option value="5-10jt" {{ request('nilai') == '5-10jt' ? 'selected' : '' }}>Rp 5 - 10 Juta</option>
                    <option value="10-25jt" {{ request('nilai') == '10-25jt' ? 'selected' : '' }}>Rp 10 - 25 Juta</option>
                    <option value="25-50jt" {{ request('nilai') == '25-50jt' ? 'selected' : '' }}>Rp 25 - 50 Juta</option>
                    <option value="50jt+" {{ request('nilai') == '50jt+' ? 'selected' : '' }}>Rp 50 Juta+</option>
                </select>
            </div>
        </div>

        <!-- Custom Date Range (Hidden by default) -->
        <div id="custom-date-range" class="grid-cols-1 md:grid-cols-2 gap-4 mb-6 {{ request('periode') == 'custom' ? 'grid' : 'hidden' }}"
             style="{{ request('periode') == 'custom' ? 'display: grid;' : 'display: none;' }}"
             data-grid="true">
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
        </div>
    </div>
</div>

<!-- Reports Grid -->
<!-- Verified Project Reports Table -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-red-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Laporan Proyek Terverifikasi</h2>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Proyek yang telah diverifikasi admin keuangan</p>
                </div>
            </div>
            <div class="flex gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>{{ $stats['proyek_selesai'] }} Terverifikasi
                </span>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-clock mr-1"></i>{{ $projects->total() - $stats['proyek_selesai'] }} Menunggu
                </span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Proyek
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Vendor
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Produk
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Departemen
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nilai
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="projects-table-body">
                @forelse($projects as $project)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-project-diagram text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $project->jenis_pengadaan }}</div>
                                <div class="text-sm text-gray-500">{{ $project->kode_proyek }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        @if($project->penawaran && $project->penawaran->penawaranDetail->first())
                            <div class="text-sm text-gray-900">{{ $project->penawaran->penawaranDetail->first()->barang->vendor->nama_vendor }}</div>
                            <div class="text-sm text-gray-500">{{ $project->penawaran->penawaranDetail->first()->barang->kategori ?? 'Lainnya' }}</div>
                        @else
                            <div class="text-sm text-gray-500">-</div>
                        @endif
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        @if($project->penawaran && $project->penawaran->penawaranDetail->first())
                            <div class="text-sm text-gray-900">{{ $project->penawaran->penawaranDetail->first()->barang->nama_barang }}</div>
                            <div class="text-sm text-gray-500">{{ $project->penawaran->penawaranDetail->first()->barang->kategori ?? 'Lainnya' }} - {{ $project->penawaran->penawaranDetail->first()->jumlah }} {{ $project->penawaran->penawaranDetail->first()->barang->satuan }}</div>
                        @else
                            <div class="text-sm text-gray-500">-</div>
                        @endif
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $project->adminMarketing->role ?? 'Admin' }}
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Rp {{ number_format($project->harga_total, 0, ',', '.') }}</div>
                        @if($project->penawaran && $project->penawaran->penawaranDetail->first())
                            <div class="text-sm text-gray-500">{{ number_format($project->penawaran->penawaranDetail->first()->harga_satuan, 0, ',', '.') }}/{{ $project->penawaran->penawaranDetail->first()->barang->satuan }}</div>
                        @endif
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        @php
                            $statusColors = [
                                'selesai' => 'bg-green-100 text-green-800',
                                'pengiriman' => 'bg-orange-100 text-orange-800',
                                'pembayaran' => 'bg-purple-100 text-purple-800',
                                'penawaran' => 'bg-blue-100 text-blue-800',
                                'menunggu' => 'bg-gray-100 text-gray-800'
                            ];
                            $statusIcons = [
                                'selesai' => 'fas fa-check-circle',
                                'pengiriman' => 'fas fa-truck',
                                'pembayaran' => 'fas fa-credit-card',
                                'penawaran' => 'fas fa-file-alt',
                                'menunggu' => 'fas fa-clock'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-800' }}">
                            <i class="{{ $statusIcons[$project->status] ?? 'fas fa-question' }} mr-1"></i>{{ ucfirst($project->status) }}
                        </span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>{{ $project->tanggal->format('d M Y') }}</div>
                        <div class="text-xs">Updated: {{ $project->updated_at->format('d M') }}</div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-red-600 hover:text-red-900 mr-3" onclick="viewProjectDetail('{{ $project->id_proyek }}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-blue-600 hover:text-blue-900" onclick="downloadReport('{{ $project->kode_proyek }}')">
                            <i class="fas fa-download"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-medium">Tidak ada data proyek</p>
                            <p class="text-gray-400 text-sm">Belum ada proyek yang memenuhi kriteria filter</p>
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
                Menampilkan <span class="font-medium">{{ $projects->firstItem() ?? 0 }}</span> sampai <span class="font-medium">{{ $projects->lastItem() ?? 0 }}</span> dari <span class="font-medium">{{ $projects->total() }}</span> proyek
            </div>
            <div class="flex items-center space-x-2">
                @if ($projects->hasPages())
                    {{-- Previous Page Link --}}
                    @if ($projects->onFirstPage())
                        <button class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $projects->previousPageUrl() }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
                        @if ($page == $projects->currentPage())
                            <button class="px-3 py-2 text-sm bg-red-600 text-white rounded-md">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($projects->hasMorePages())
                        <a href="{{ $projects->nextPageUrl() }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
function applyFilters() {
    const periode = document.getElementById('periode-filter').value;
    const vendor = document.getElementById('vendor-filter').value;
    const kategori = document.getElementById('kategori-filter').value;
    const status = document.getElementById('status-filter').value;
    const produk = document.getElementById('produk-filter').value;
    const departemen = document.getElementById('departemen-filter').value;
    const nilai = document.getElementById('nilai-filter').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    // Build URL with query parameters
    const params = new URLSearchParams();
    if (periode) params.append('periode', periode);
    if (vendor) params.append('vendor', vendor);
    if (kategori) params.append('kategori', kategori);
    if (status) params.append('status', status);
    if (produk) params.append('produk', produk);
    if (departemen) params.append('departemen', departemen);
    if (nilai) params.append('nilai', nilai);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);

    // Redirect to laporan page with filters
    window.location.href = '{{ route("laporan") }}?' + params.toString();
}

function resetFilters() {
    // Redirect to laporan page without any filters
    window.location.href = '{{ route("laporan") }}';
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
                    <h2 class="text-2xl font-bold text-gray-800">Detail Proyek ${project.kode_proyek}</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Informasi Proyek</h3>
                        <div class="space-y-3">
                            <div><span class="font-medium">Nama Klien:</span> ${project.nama_klien}</div>
                            <div><span class="font-medium">Instansi:</span> ${project.instansi}</div>
                            <div><span class="font-medium">Jenis Pengadaan:</span> ${project.jenis_pengadaan}</div>
                            <div><span class="font-medium">Tanggal:</span> ${project.tanggal}</div>
                            <div><span class="font-medium">Deadline:</span> ${project.deadline}</div>
                            <div><span class="font-medium">Status:</span> <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">${project.status}</span></div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Tim & Nilai</h3>
                        <div class="space-y-3">
                            <div><span class="font-medium">Admin Marketing:</span> ${project.admin_marketing}</div>
                            <div><span class="font-medium">Admin Purchasing:</span> ${project.admin_purchasing}</div>
                            <div><span class="font-medium">Total Nilai:</span> <span class="text-lg font-bold text-green-600">Rp ${project.total_nilai}</span></div>
                            <div><span class="font-medium">Catatan:</span> ${project.catatan}</div>
                        </div>
                    </div>
                </div>
                ${project.penawaran ? `
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-4">Detail Penawaran</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div><span class="font-medium">No. Penawaran:</span> ${project.penawaran.no_penawaran}</div>
                            <div><span class="font-medium">Tanggal:</span> ${project.penawaran.tanggal_penawaran}</div>
                            <div><span class="font-medium">Total:</span> Rp ${project.penawaran.total_nilai}</div>
                        </div>
                    </div>
                    <h4 class="font-medium mb-3">Daftar Barang</h4>
                    <div class="space-y-3">
                        ${project.penawaran.detail_barang.map(item => `
                        <div class="border rounded-lg p-3">
                            <div class="font-medium">${item.nama_barang}</div>
                            <div class="text-sm text-gray-600">Vendor: ${item.vendor} | Kategori: ${item.kategori}</div>
                            <div class="text-sm text-gray-600">Jumlah: ${item.jumlah} ${item.satuan} × Rp ${item.harga_satuan} = Rp ${item.subtotal}</div>
                        </div>
                        `).join('')}
                    </div>
                </div>
                ` : ''}
            </div>
        </div>
    `;

    document.body.appendChild(modal);
}

function downloadReport(projectCode) {
    showNotification(`Mengunduh laporan untuk proyek ${projectCode}...`, 'info');

    // Here you would typically generate a specific project report
    // For now, we'll just show a success message
    setTimeout(() => {
        showNotification(`Laporan proyek ${projectCode} berhasil diunduh!`, 'success');
    }, 2000);
}

function resetFilters() {
    document.getElementById('periode-filter').value = '';
    document.getElementById('vendor-filter').value = '';
    document.getElementById('kategori-filter').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('produk-filter').value = '';
    document.getElementById('departemen-filter').value = '';
    document.getElementById('nilai-filter').value = '';

    // Hide custom date range
    document.getElementById('custom-date-range').style.display = 'none';
    document.getElementById('start-date').value = '';
    document.getElementById('end-date').value = '';
}

function exportReport() {
    const currentDate = new Date().toISOString().split('T')[0];
    const filename = `laporan-proyek-${currentDate}.xlsx`;

    // Show export notification
    showNotification('Export berhasil! File sedang diunduh...', 'success');

    // Here you would typically trigger the actual export
    console.log('Exporting report as:', filename);
}

function viewProjectDetail(projectId) {
    console.log('Viewing project detail for:', projectId);

    // Create modal for project detail
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 backdrop-blur-xs bg-black/30 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">Detail Proyek ${projectId}</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Informasi Proyek</h3>
                        <div class="space-y-3">
                            <div><span class="font-medium">ID Proyek:</span> ${projectId}</div>
                            <div><span class="font-medium">Nama:</span> Pengadaan Laptop</div>
                            <div><span class="font-medium">Status:</span> <span class="text-green-600">Selesai</span></div>
                            <div><span class="font-medium">Departemen:</span> IT Department</div>
                            <div><span class="font-medium">Tanggal Mulai:</span> 15 Des 2024</div>
                            <div><span class="font-medium">Tanggal Verifikasi:</span> 18 Des 2024</div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Detail Finansial</h3>
                        <div class="space-y-3">
                            <div><span class="font-medium">Total Nilai:</span> Rp 375,000,000</div>
                            <div><span class="font-medium">Harga per Unit:</span> Rp 15,000,000</div>
                            <div><span class="font-medium">Jumlah Unit:</span> 25 Unit</div>
                            <div><span class="font-medium">Status Pembayaran:</span> <span class="text-blue-600">Lunas</span></div>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-4">Dokumen Terkait</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border rounded-lg p-4">
                            <i class="fas fa-file-pdf text-red-500 text-2xl mb-2"></i>
                            <p class="font-medium">Purchase Order</p>
                            <p class="text-sm text-gray-500">Diverifikasi</p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <i class="fas fa-file-invoice text-blue-500 text-2xl mb-2"></i>
                            <p class="font-medium">Invoice</p>
                            <p class="text-sm text-gray-500">Diverifikasi</p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <i class="fas fa-file-signature text-green-500 text-2xl mb-2"></i>
                            <p class="font-medium">Kontrak</p>
                            <p class="text-sm text-gray-500">Diverifikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
}

function downloadReport(projectId) {
    showNotification(`Mengunduh laporan untuk proyek ${projectId}...`, 'info');

    // Simulate download
    setTimeout(() => {
        showNotification('Laporan berhasil diunduh!', 'success');
    }, 2000);
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
    } else {
        customDateRange.style.display = 'none';
        customDateRange.classList.remove('grid');
    }
});

// Auto-save filter preferences
document.addEventListener('DOMContentLoaded', function() {
    // Load saved filters from localStorage
    const savedFilters = localStorage.getItem('laporanFilters');
    if (savedFilters) {
        const filters = JSON.parse(savedFilters);
        Object.keys(filters).forEach(key => {
            const element = document.getElementById(key);
            if (element) {
                element.value = filters[key];
            }
        });
    }

    // Save filters when changed
    const filterElements = [
        'periode-filter', 'vendor-filter', 'kategori-filter',
        'status-filter', 'produk-filter', 'departemen-filter', 'nilai-filter'
    ];

    filterElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', function() {
                const filters = {};
                filterElements.forEach(filterId => {
                    const filterElement = document.getElementById(filterId);
                    if (filterElement) {
                        filters[filterId] = filterElement.value;
                    }
                });
                localStorage.setItem('laporanFilters', JSON.stringify(filters));
            });
        }
    });
});
</script>
@endsection
