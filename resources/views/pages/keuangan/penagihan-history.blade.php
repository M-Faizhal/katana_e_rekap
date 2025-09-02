@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">History Pembayaran</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">{{ $penagihanDinas->nomor_invoice }}</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-history text-3xl sm:text-4xl lg:text-6xl text-red-200"></i>
        </div>
    </div>
</div>

<!-- Navigation -->
<div class="flex items-center justify-between mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('penagihan-dinas.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                    Penagihan Dinas
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('penagihan-dinas.show', $penagihanDinas->id) }}" class="text-sm font-medium text-gray-700 hover:text-red-600">
                        {{ $penagihanDinas->nomor_invoice }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">History</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <a href="{{ route('penagihan-dinas.show', $penagihanDinas->id) }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Detail
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Summary -->
    <div class="lg:col-span-1">
        <!-- Summary Card -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                    Ringkasan Pembayaran
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                        @if($penagihanDinas->status_pembayaran === 'belum_bayar') bg-yellow-100 text-yellow-800
                        @elseif($penagihanDinas->status_pembayaran === 'dp') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        @if($penagihanDinas->status_pembayaran === 'belum_bayar')
                            <i class="fas fa-clock mr-1"></i>Belum Bayar
                        @elseif($penagihanDinas->status_pembayaran === 'dp')
                            <i class="fas fa-hand-holding-usd mr-1"></i>Down Payment
                        @else
                            <i class="fas fa-check-circle mr-1"></i>Lunas
                        @endif
                    </span>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Harga:</span>
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format((float)$penagihanDinas->total_harga, 0, ',', '.') }}</span>
                    </div>
                    
                    @php
                        $totalHarga = (float)($penagihanDinas->total_harga ?? 0);
                        $totalBayar = (float)($penagihanDinas->buktiPembayaran->sum('jumlah_bayar') ?? 0);
                        $sisaBayar = $totalHarga - $totalBayar;
                        $persentaseTerbayar = $totalHarga > 0 ? ($totalBayar / $totalHarga) * 100 : 0;
                    @endphp
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Terbayar:</span>
                        <span class="text-lg font-semibold text-green-600">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($sisaBayar > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Sisa Pembayaran:</span>
                        <span class="text-lg font-semibold text-red-600">Rp {{ number_format($sisaBayar, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-500">Progress Pembayaran</span>
                        <span class="font-medium text-gray-900">{{ number_format($persentaseTerbayar, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: {{ $persentaseTerbayar }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proyek Info -->
        <div class="bg-white rounded-lg shadow-lg mt-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-project-diagram text-green-500 mr-2"></i>
                    Informasi Proyek
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Proyek</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $penagihanDinas->proyek->kode_proyek ?? 'PRJ-' . str_pad($penagihanDinas->proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</label>
                    <p class="text-sm text-gray-900">{{ $penagihanDinas->proyek->instansi }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Proyek</label>
                    <p class="text-sm text-gray-900">{{ $penagihanDinas->proyek->kode_proyek }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</label>
                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($penagihanDinas->tanggal_jatuh_tempo)->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - History Timeline -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-timeline text-purple-500 mr-2"></i>
                    Timeline Pembayaran
                    <span class="ml-2 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $penagihanDinas->buktiPembayaran->count() }} transaksi
                    </span>
                </h2>
            </div>
            
            <div class="p-6">
                @forelse($penagihanDinas->buktiPembayaran as $index => $bukti)
                <div class="relative">
                    <!-- Timeline Line -->
                    @if(!$loop->last)
                    <div class="absolute left-6 top-16 w-0.5 h-20 bg-gray-200"></div>
                    @endif
                    
                    <!-- Timeline Item -->
                    <div class="flex items-start space-x-4 pb-8">
                        <!-- Timeline Icon -->
                        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center shadow-lg
                            @if($bukti->jenis_pembayaran === 'dp') bg-gradient-to-br from-blue-100 to-blue-200 border-2 border-blue-300
                            @elseif($bukti->jenis_pembayaran === 'lunas') bg-gradient-to-br from-green-100 to-green-200 border-2 border-green-300
                            @else bg-gradient-to-br from-purple-100 to-purple-200 border-2 border-purple-300 @endif">
                            @if($bukti->jenis_pembayaran === 'dp')
                                <i class="fas fa-hand-holding-usd text-blue-600 text-lg"></i>
                            @elseif($bukti->jenis_pembayaran === 'lunas')
                                <i class="fas fa-check-circle text-green-600 text-lg"></i>
                            @else
                                <i class="fas fa-money-check-alt text-purple-600 text-lg"></i>
                            @endif
                        </div>
                        
                        <!-- Timeline Content -->
                        <div class="flex-1 min-w-0">
                            <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-6 shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                            @if($bukti->jenis_pembayaran === 'dp') bg-blue-200 text-blue-900 border border-blue-300
                                            @elseif($bukti->jenis_pembayaran === 'lunas') bg-green-200 text-green-900 border border-green-300
                                            @else bg-purple-200 text-purple-900 border border-purple-300 @endif">
                                            @if($bukti->jenis_pembayaran === 'dp')
                                                <i class="fas fa-hand-holding-usd mr-2"></i>Down Payment
                                            @elseif($bukti->jenis_pembayaran === 'lunas')
                                                <i class="fas fa-check-circle mr-2"></i>Pelunasan
                                            @else
                                                <i class="fas fa-credit-card mr-2"></i>{{ ucfirst($bukti->jenis_pembayaran) }}
                                            @endif
                                        </span>
                                        @if($index === 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                                            <i class="fas fa-star mr-1"></i>
                                            Terbaru
                                        </span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold text-green-600">Rp {{ number_format((float)$bukti->jumlah_bayar, 0, ',', '.') }}</span>
                                        <div class="text-xs text-gray-500">Pembayaran #{{ $index + 1 }}</div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Bayar</label>
                                        <div class="flex items-center text-gray-900">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-calendar-alt text-blue-600 text-sm"></i>
                                            </div>
                                            <span class="font-semibold">{{ \Carbon\Carbon::parse($bukti->tanggal_bayar)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Diinput</label>
                                        <div class="flex items-center text-gray-900">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-clock text-gray-600 text-sm"></i>
                                            </div>
                                            <span class="font-semibold">{{ $bukti->created_at->format('d M Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($bukti->keterangan)
                                <div class="mt-4">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Keterangan</label>
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <p class="text-sm text-gray-700">{{ $bukti->keterangan }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-receipt text-green-600 text-sm"></i>
                                        </div>
                                        <span class="font-medium">Bukti Pembayaran</span>
                                    </div>
                                    @if($bukti->bukti_pembayaran)
                                    <a href="{{ asset('storage/penagihan-dinas/bukti-pembayaran/' . $bukti->bukti_pembayaran) }}" download
                                       class="inline-flex items-center px-4 py-2 border border-green-300 rounded-lg shadow-sm text-sm font-semibold text-green-700 bg-green-50 hover:bg-green-100 hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200">
                                        <i class="fas fa-download mr-2"></i>
                                        Download Bukti
                                    </a>
                                    @else
                                    <span class="text-sm text-gray-400">Tidak tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-16">
                    <div class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-receipt text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Belum Ada Pembayaran</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">Belum ada transaksi pembayaran untuk penagihan ini. Riwayat pembayaran akan muncul di sini setelah ada transaksi.</p>
                    <div class="mt-6">
                        <a href="{{ route('penagihan-dinas.show', $penagihanDinas->id) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Detail
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        
        @if($penagihanDinas->status_pembayaran === 'dp')
        <!-- Action Card -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl shadow-lg p-8 mt-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-green-900 mb-2 flex items-center">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-hand-holding-usd text-white"></i>
                        </div>
                        Siap untuk Pelunasan?
                    </h3>
                    <p class="text-green-700 text-lg">
                        Sisa pembayaran: 
                        <span class="font-bold text-green-800">Rp {{ number_format($sisaBayar, 0, ',', '.') }}</span>
                    </p>
                    <p class="text-green-600 text-sm mt-1">Lengkapi pembayaran untuk menyelesaikan transaksi</p>
                </div>
                <a href="{{ route('penagihan-dinas.show-pelunasan', $penagihanDinas->id) }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-money-check-alt mr-2"></i>
                    Tambah Pelunasan
                </a>
            </div>
        </div>
        @endif
    </div>
</div>



@push('styles')
<style>
/* Prevent scrolling on number inputs */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
}

/* Prevent mouse wheel scrolling on number inputs */
input[type=number] {
    -moz-appearance: textfield;
}

/* Timeline smooth transitions */
.timeline-item {
    transition: all 0.3s ease;
}

.timeline-item:hover {
    transform: translateY(-2px);
}

/* Progress bar animation */
.progress-bar {
    transition: width 1s ease-in-out;
}

/* Button hover effects */
.btn-hover-scale {
    transition: transform 0.2s ease;
}

.btn-hover-scale:hover {
    transform: scale(1.05);
}

/* Card hover effects */
.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    transform: translateY(-1px);
}

/* Loading states */
.loading {
    opacity: 0.7;
    pointer-events: none;
}

/* File input styling */
.file-input::-webkit-file-upload-button {
    transition: all 0.2s ease;
}

.file-input::-webkit-file-upload-button:hover {
    background-color: #f3f4f6;
}
</style>
@endpush

@push('scripts')
<script>
// Prevent number input scroll
document.addEventListener('DOMContentLoaded', function() {
    const numberInputs = document.querySelectorAll('input[type="number"]');
    
    numberInputs.forEach(function(input) {
        input.addEventListener('wheel', function(e) {
            e.preventDefault();
        });
        
        input.addEventListener('focus', function() {
            this.addEventListener('wheel', function(e) {
                e.preventDefault();
            });
        });
        
        input.addEventListener('blur', function() {
            this.removeEventListener('wheel', function(e) {
                e.preventDefault();
            });
        });
    });
});

// Smooth scroll for timeline
function scrollToTimelineItem(index) {
    const timelineItems = document.querySelectorAll('.timeline-item');
    if (timelineItems[index]) {
        timelineItems[index].scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
}

// Initialize tooltips (if using any tooltip library)
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here
    console.log('Penagihan History page loaded successfully');
});
</script>
@endpush

@endsection
