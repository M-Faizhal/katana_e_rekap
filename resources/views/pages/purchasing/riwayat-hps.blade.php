@extends('layouts.app')

@section('title', 'Riwayat Kalkulasi HPS - ' . ($proyek->kode_proyek ?? 'Unknown') . ' - Cyber KATANA')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-semibold text-gray-900 truncate">
                    <i class="fas fa-history text-blue-600 mr-2"></i>
                    Riwayat Kalkulasi HPS
                </h1>
                <div class="text-sm text-gray-600 mt-1 flex flex-wrap items-center gap-2">
                    <span class="font-medium">Proyek:</span> <span class="truncate">{{ $proyek->kode_proyek ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">Klien:</span> <span class="truncate">{{ $proyek->nama_klien ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">Total Riwayat:</span> <span class="text-blue-600">{{ $riwayatData->count() }} Perubahan</span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('purchasing.kalkulasi.hps.ajukan', ['id' => $proyek->id_proyek]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke HPS
                </a>
             
            </div>
        </div>
    </div>
    @if($riwayatData->count() > 0)
        <!-- Riwayat Items -->
        <div class="space-y-4">
            @foreach($riwayatData->groupBy(function($item) { return $item->created_at->format('Y-m-d H:i'); }) as $datetime => $items)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Header Timestamp -->
                    <div class="bg-blue-50 px-4 py-3 border-b border-blue-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-semibold">
                                    {{ \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i:s') }}
                                </div>
                                <div class="text-sm text-blue-700">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $items->first()->createdBy->nama ?? 'Unknown User' }}
                                </div>
                                <div class="text-sm text-blue-700">
                                    <i class="fas fa-list mr-1"></i>
                                    {{ $items->count() }} Item{{ $items->count() > 1 ? 's' : '' }} Diubah
                                </div>
                            </div>
                            <button onclick="toggleDetails('{{ $datetime }}')" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-chevron-down transition-transform" id="chevron-{{ $datetime }}"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Detail Items -->
                    <div id="details-{{ $datetime }}" class="hidden">
                        <div class="border-b border-gray-200 mb-4">
                        <!-- HPS Summary Table -->
                        <div class="bg-white border-t border-gray-200">
                            <div class="px-4 py-3 bg-blue-50 border-b border-blue-200">
                                <h4 class="font-semibold text-gray-800">Tabel Ringkasan HPS Per Item</h4>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">NO</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">NAMA BARANG</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">VENDOR</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">JENIS VENDOR</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">HARGA AWAL</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">DISKON</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">HARGA AKHIR</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">QTY</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">SATUAN</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">JUMLAH VOLUME YANG DIKERJAKAN</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">HARGA JUAL</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">TOTAL HPS</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">KETERANGAN</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">TKDN</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">NETT PER PCS</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-600">TOTAL NETT PER PCS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($riwayatSummaryData[$datetime]['summaryData'] as $index => $data)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-2 py-2 text-center">{{ $index + 1 }}</td>
                                            <td class="px-2 py-2">{{ $data['nama_barang'] }}</td>
                                            <td class="px-2 py-2">{{ $data['vendor'] }}</td>
                                            <td class="px-2 py-2">{{ $data['jenis_vendor'] }}</td>
                                            <td class="px-2 py-2 text-right">{{ 'Rp ' . number_format($data['harga_awal']) }}</td>
                                            <td class="px-2 py-2 text-right">{{ 'Rp ' . number_format($data['diskon']) }}</td>
                                            <td class="px-2 py-2 text-right">{{ 'Rp ' . number_format($data['harga_akhir']) }}</td>
                                            <td class="px-2 py-2 text-center">{{ number_format($data['qty']) }}</td>
                                            <td class="px-2 py-2 text-center">{{ $data['satuan'] }}</td>
                                            <td class="px-2 py-2 text-right">{{ 'Rp ' . number_format($data['volume']) }}</td>
                                            <td class="px-2 py-2 text-right">{{ 'Rp ' . number_format($data['harga_yang_diharapkan'] ?? 0) }}</td>
                                            <td class="px-2 py-2 text-right font-semibold text-blue-600">{{ 'Rp ' . number_format($data['total_hps']) }}</td>
                                            <td class="px-2 py-2">-</td>
                                            <td class="px-2 py-2">-</td>
                                            <td class="px-2 py-2 text-right @if($data['nett_per_pcs'] < 0) text-red-600 @else text-green-600 @endif">
                                                {{ number_format($data['nett_persen'], 2) }}%
                                            </td>
                                            <td class="px-2 py-2 text-right @if($data['total_nett'] < 0) text-red-600 @else text-green-600 @endif">
                                                {{ 'Rp ' . number_format($data['total_nett']) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-gray-100 font-semibold">
                                            <td class="px-2 py-2" colspan="10">Total</td>
                                            <td class="px-2 py-2 text-right">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalVolume']) }}</td>
                                            <td class="px-2 py-2"></td>
                                            <td class="px-2 py-2 text-right text-blue-600">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalHps']) }}</td>
                                            <td class="px-2 py-2"></td>
                                            <td class="px-2 py-2"></td>
                                            <td class="px-2 py-2"></td>
                                            <td class="px-2 py-2 text-right @if($riwayatSummaryData[$datetime]['totalNett'] < 0) text-red-600 @else text-green-600 @endif">
                                                {{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalNett']) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Ringkasan Total Kalkulasi HPS -->
                            <div class="px-4 py-4 bg-gray-50 border-t border-gray-200">
                                <h5 class="font-semibold text-gray-800 mb-4">Ringkasan Total Kalkulasi HPS</h5>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <!-- Total HPP (Modal) -->
                                    <div class="bg-white rounded-lg border border-orange-200 p-3 text-center">
                                        <div class="text-lg font-bold text-orange-600">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalHppModal']) }}</div>
                                        <div class="text-xs text-gray-600">Total HPP (Modal)</div>
                                        <div class="text-xs text-gray-500">Harga beli dari vendor</div>
                                    </div>
                                    
                                    <!-- Total HPS -->
                                    <div class="bg-white rounded-lg border border-blue-200 p-3 text-center">
                                        <div class="text-lg font-bold text-blue-600">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalHps']) }}</div>
                                        <div class="text-xs text-gray-600">Total HPS</div>
                                        <div class="text-xs text-gray-500">Harga penawaran ke klien</div>
                                    </div>
                                    
                                    <!-- Total Nett -->
                                    <div class="bg-white rounded-lg border border-green-200 p-3 text-center">
                                        <div class="text-lg font-bold @if($riwayatSummaryData[$datetime]['totalNett'] >= 0) text-green-600 @else text-red-600 @endif">
                                            {{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalNett']) }}
                                        </div>
                                        <div class="text-xs text-gray-600">Total Nett</div>
                                        <div class="text-xs text-gray-500">Pendapatan bersih</div>
                                    </div>
                                    
                                    <!-- Rata-rata % Nett -->
                                    <div class="bg-white rounded-lg border border-purple-200 p-3 text-center">
                                        <div class="text-lg font-bold @if($riwayatSummaryData[$datetime]['avgNettPersen'] >= 0) text-green-600 @else text-red-600 @endif">
                                            {{ number_format($riwayatSummaryData[$datetime]['avgNettPersen'], 2) }}%
                                        </div>
                                        <div class="text-xs text-gray-600">Rata-rata % Nett</div>
                                        <div class="text-xs text-gray-500">Margin bersih</div>
                                    </div>
                                </div>

                                <!-- Detail Metrics -->
                                <div class="grid grid-cols-2 md:grid-cols-6 gap-3 text-sm">
                                    <div class="bg-white rounded border p-2 text-center">
                                        <div class="font-semibold">{{ $riwayatSummaryData[$datetime]['totalItems'] }}</div>
                                        <div class="text-xs text-gray-600">Total Items</div>
                                    </div>
                                    <div class="bg-white rounded border p-2 text-center">
                                        <div class="font-semibold">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalDiskon']) }}</div>
                                        <div class="text-xs text-gray-600">Total Diskon</div>
                                    </div>
                                    <div class="bg-white rounded border p-2 text-center">
                                        <div class="font-semibold">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalVolume']) }}</div>
                                        <div class="text-xs text-gray-600">Total Volume</div>
                                    </div>
                                    <div class="bg-white rounded border p-2 text-center">
                                        <div class="font-semibold">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalDpp']) }}</div>
                                        <div class="text-xs text-gray-600">Total DPP</div>
                                    </div>
                                    <div class="bg-white rounded border p-2 text-center">
                                        <div class="font-semibold">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalAsumsiCair']) }}</div>
                                        <div class="text-xs text-gray-600">Total Asumsi Cair</div>
                                    </div>
                                    <div class="bg-white rounded border p-2 text-center">
                                        <div class="font-semibold">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalAsumsiOngkir']) }}</div>
                                        <div class="text-xs text-gray-600">Total Ongkir</div>
                                    </div>
                                </div>

                                <!-- Biaya Tidak Langsung -->
                                <div class="mt-4">
                                    <h6 class="font-medium text-gray-800 mb-3">Biaya Tidak Langsung</h6>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div class="bg-white rounded border p-2 text-center">
                                            <div class="font-semibold text-blue-600">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalOmzetDinas']) }}</div>
                                            <div class="text-xs text-gray-600">Dinas</div>
                                            <div class="text-xs text-gray-500">Biaya dinas</div>
                                        </div>
                                        <div class="bg-white rounded border p-2 text-center">
                                            <div class="font-semibold text-green-600">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalBendera']) }}</div>
                                            <div class="text-xs text-gray-600">Bendera</div>
                                            <div class="text-xs text-gray-500">Biaya bendera</div>
                                        </div>
                                        <div class="bg-white rounded border p-2 text-center">
                                            <div class="font-semibold text-red-600">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalBankCost']) }}</div>
                                            <div class="text-xs text-gray-600">Bank Cost</div>
                                            <div class="text-xs text-gray-500">Biaya administrasi bank</div>
                                        </div>
                                        <div class="bg-white rounded border p-2 text-center">
                                            <div class="font-semibold text-purple-600">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['totalBiayaOps']) }}</div>
                                            <div class="text-xs text-gray-600">Biaya Operasional</div>
                                            <div class="text-xs text-gray-500">Biaya operasional</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Subtotal -->
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded">
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-red-600">{{ 'Rp ' . number_format($riwayatSummaryData[$datetime]['subTotalBiayaTidakLangsung']) }}</div>
                                            <div class="text-sm text-gray-600">Subtotal Biaya Tidak Langsung</div>
                                            <div class="text-xs text-gray-500">Total keseluruhan</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="text-center">
                <i class="fas fa-history text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat</h3>
                <p class="text-gray-500 mb-4">Belum ada perubahan kalkulasi HPS yang tercatat untuk proyek ini.</p>
                <a href="{{ route('purchasing.kalkulasi.hps.ajukan', ['id' => $proyek->id_proyek]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Mulai Kalkulasi HPS
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.transition-transform {
    transition: transform 0.2s ease;
}

.rotate-180 {
    transform: rotate(180deg);
}

.summary-card {
    transition: all 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.comparison-badge {
    position: relative;
    overflow: hidden;
}

.comparison-badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.comparison-badge:hover::before {
    left: 100%;
}

/* Custom scrollbar for horizontal scroll */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Table responsive styling */
@media (max-width: 768px) {
    .table-responsive-text {
        font-size: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function toggleDetails(datetime) {
    const details = document.getElementById('details-' + datetime);
    const chevron = document.getElementById('chevron-' + datetime);
    
    if (details.classList.contains('hidden')) {
        details.classList.remove('hidden');
        chevron.classList.add('rotate-180');
        
        // Add smooth slide animation
        details.style.maxHeight = '0';
        details.style.overflow = 'hidden';
        details.style.transition = 'max-height 0.3s ease-out';
        
        setTimeout(() => {
            details.style.maxHeight = details.scrollHeight + 'px';
        }, 10);
        
        setTimeout(() => {
            details.style.maxHeight = 'none';
            details.style.overflow = 'visible';
        }, 300);
    } else {
        details.style.maxHeight = details.scrollHeight + 'px';
        details.style.overflow = 'hidden';
        details.style.transition = 'max-height 0.3s ease-in';
        
        setTimeout(() => {
            details.style.maxHeight = '0';
        }, 10);
        
        setTimeout(() => {
            details.classList.add('hidden');
            chevron.classList.remove('rotate-180');
            details.style.maxHeight = 'none';
            details.style.overflow = 'visible';
            details.style.transition = 'none';
        }, 300);
    }
}

function applyFilter() {
    const startDate = document.getElementById('filter-start-date').value;
    const endDate = document.getElementById('filter-end-date').value;
    const userId = document.getElementById('filter-user').value;
    
    // Validate dates
    if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
        alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        return;
    }
    
    const params = new URLSearchParams();
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (userId) params.append('user_id', userId);
    
    const url = new URL(window.location);
    params.forEach((value, key) => url.searchParams.set(key, value));
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...';
    button.disabled = true;
    
    window.location.href = url.toString();
}

function resetFilter() {
    const url = new URL(window.location);
    url.search = '';
    window.location.href = url.toString();
}

function exportData(format) {
    const params = new URLSearchParams(window.location.search);
    params.append('export', format);
    
    const url = new URL(window.location);
    url.search = params.toString();
    
    window.open(url.toString(), '_blank');
}

// Auto-fill current date as end date and maintain filter values
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const today = new Date().toISOString().split('T')[0];
    
    // End date handling
    const endDateInput = document.getElementById('filter-end-date');
    const endDateParam = urlParams.get('end_date');
    if (endDateParam) {
        endDateInput.value = endDateParam;
    } else if (!endDateInput.value) {
        endDateInput.value = today;
    }
    
    // Start date handling
    const startDateInput = document.getElementById('filter-start-date');
    const startDateParam = urlParams.get('start_date');
    if (startDateParam) {
        startDateInput.value = startDateParam;
    } else if (!startDateInput.value) {
        const oneMonthAgo = new Date();
        oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
        startDateInput.value = oneMonthAgo.toISOString().split('T')[0];
    }
    
    // User filter handling
    const userSelect = document.getElementById('filter-user');
    const userParam = urlParams.get('user_id');
    if (userParam) {
        userSelect.value = userParam;
    }
    
    // Add event listeners for filter inputs
    startDateInput.addEventListener('change', function() {
        const endDate = endDateInput.value;
        if (endDate && this.value > endDate) {
            endDateInput.value = this.value;
        }
    });
    
    endDateInput.addEventListener('change', function() {
        const startDate = startDateInput.value;
        if (startDate && this.value < startDate) {
            startDateInput.value = this.value;
        }
    });

    // Initialize tooltips and enhanced interactions
    initializeEnhancements();
});

function initializeEnhancements() {
    // Add hover effects to summary cards
    const summaryCards = document.querySelectorAll('.summary-card');
    summaryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'f':
                    e.preventDefault();
                    document.getElementById('filter-start-date').focus();
                    break;
                case 'r':
                    e.preventDefault();
                    resetFilter();
                    break;
            }
        }
        
        if (e.key === 'Escape') {
            // Close all opened details
            const openDetails = document.querySelectorAll('[id^="details-"]:not(.hidden)');
            openDetails.forEach(detail => {
                const datetime = detail.id.replace('details-', '');
                toggleDetails(datetime);
            });
        }
    });
}
</script>
@endpush
