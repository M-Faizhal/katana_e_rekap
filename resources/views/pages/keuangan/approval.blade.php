@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Approval Pembayaran</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan approve permintaan pembayaran</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-clipboard-check text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-check text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Approved</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-times text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rejected</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-money-bill-wave text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pending</p>
                <p class="text-xl font-bold text-gray-900">Rp {{ number_format($stats['total_amount_pending'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 px-6">
            <a href="{{ route('keuangan.approval') }}" 
               class="py-4 px-1 border-b-2 border-red-500 font-medium text-sm text-red-600">
                Pending Approval
                @if($stats['pending'] > 0)
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $stats['pending'] }}
                </span>
                @endif
            </a>
            <a href="{{ route('keuangan.approval.approved') }}" 
               class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Approved
            </a>
            <a href="{{ route('keuangan.approval.rejected') }}" 
               class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Rejected
            </a>
        </nav>
    </div>
</div>

<!-- Pending Payments Table -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Pembayaran Pending Approval</h2>
        <p class="text-gray-600 mt-1">{{ $pendingPayments->count() }} pembayaran menunggu persetujuan</p>
    </div>
    
    @if($pendingPayments->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pendingPayments as $pembayaran)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $pembayaran->penawaran->proyek->nama_barang }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->penawaran->no_penawaran }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $pembayaran->penawaran->proyek->nama_klien }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->penawaran->proyek->instansi }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($pembayaran->jenis_bayar == 'Lunas') bg-green-100 text-green-800
                            @elseif($pembayaran->jenis_bayar == 'DP') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $pembayaran->jenis_bayar }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}
                        </div>
                        @php
                            $persenNominal = $pembayaran->penawaran->total_penawaran > 0 ? 
                                ($pembayaran->nominal_bayar / $pembayaran->penawaran->total_penawaran) * 100 : 0;
                        @endphp
                        <div class="text-xs text-gray-500">{{ number_format($persenNominal, 1) }}% dari total</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $pembayaran->metode_bayar }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $pembayaran->status_verifikasi }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('keuangan.approval.detail', $pembayaran->id_pembayaran) }}" 
                           class="inline-flex items-center px-3 py-1 border border-blue-300 shadow-sm text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                        
                        <!-- Quick Approve Button -->
                        <form action="{{ route('keuangan.approval.approve', $pembayaran->id_pembayaran) }}" 
                              method="POST" 
                              class="inline-block"
                              onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1 border border-green-300 shadow-sm text-xs leading-4 font-medium rounded text-green-700 bg-green-50 hover:bg-green-100">
                                <i class="fas fa-check mr-1"></i>
                                Approve
                            </button>
                        </form>
                        
                        <!-- Quick Reject Button -->
                        <button onclick="openRejectModal({{ $pembayaran->id_pembayaran }})" 
                                class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs leading-4 font-medium rounded text-red-700 bg-red-50 hover:bg-red-100">
                            <i class="fas fa-times mr-1"></i>
                            Reject
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <div class="mx-auto h-12 w-12 text-gray-400">
            <i class="fas fa-clipboard-check text-4xl"></i>
        </div>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pembayaran pending</h3>
        <p class="mt-1 text-sm text-gray-500">Semua pembayaran sudah diproses.</p>
    </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tolak Pembayaran</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                              placeholder="Masukkan alasan penolakan pembayaran..."></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i>
                        Tolak Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal(paymentId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/keuangan/approval/${paymentId}/reject`;
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    document.getElementById('alasan_penolakan').value = '';
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>

@endsection
