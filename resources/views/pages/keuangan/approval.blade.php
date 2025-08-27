@extends('layouts.app')

@section('title', 'Approval - Cyber KATANA')

@section('content')
<!-- Access Control Info -->
@php
    $user = auth()->user();
    $isAdminKeuangan = $user->role === 'admin_keuangan';
@endphp

<div class="container mx-auto px-4 py-6">

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-2xl p-6 lg:p-8 mb-8 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl lg:text-4xl font-bold mb-2">Approval Pembayaran</h1>
                <p class="text-red-100 text-base lg:text-lg opacity-90">
                    @if($isAdminKeuangan)
                        Verifikasi dan persetujuan pembayaran per vendor
                    @else
                        Monitoring pembayaran per vendor (Mode Hanya Lihat)
                    @endif
                </p>
            </div>
            <div class="hidden lg:flex items-center justify-center w-20 h-20 bg-red-700 rounded-2xl">
                <i class="fas fa-clipboard-check text-4xl opacity-80"></i>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPending ?? 0 }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $totalApproved ?? 0 }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $totalRejected ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAll ?? 0 }}</p>
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
                    @if(($totalPending ?? 0) > 0)
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $totalPending }}
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
            <p class="text-gray-600 mt-1">{{ ($pendingPayments ?? collect())->count() }} pembayaran menunggu persetujuan</p>
        </div>
        
        @if(isset($pendingPayments) && $pendingPayments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
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
                            <div class="text-sm text-gray-600">{{ $pembayaran->penawaran->proyek->nama_klien }}</div>
                            <div class="text-xs text-gray-500">{{ $pembayaran->penawaran->no_penawaran }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $pembayaran->vendor->nama_vendor }}</div>
                            <div class="text-sm text-gray-600">{{ $pembayaran->vendor->jenis_perusahaan }}</div>
                            <div class="text-xs text-gray-500">{{ $pembayaran->vendor->email }}</div>
                            @php
                                // Hitung info modal vendor
                                $totalModalVendor = $pembayaran->penawaran->proyek->penawaranAktif->penawaranDetail
                                    ->where('barang.id_vendor', $pembayaran->id_vendor)
                                    ->sum(function($detail) {
                                        return $detail->qty * $detail->barang->harga_vendor;
                                    });
                                $totalDibayarVendor = \App\Models\Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
                                    ->where('id_vendor', $pembayaran->id_vendor)
                                    ->where('status_verifikasi', 'Approved')
                                    ->sum('nominal_bayar');
                            @endphp
                            <div class="text-xs text-blue-600 mt-1">
                                Modal: Rp {{ number_format($totalModalVendor, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-green-600">
                                Dibayar: Rp {{ number_format($totalDibayarVendor, 0, ',', '.') }}
                            </div>
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
                                $persenNominal = $totalModalVendor > 0 ? 
                                    ($pembayaran->nominal_bayar / $totalModalVendor) * 100 : 0;
                            @endphp
                            <div class="text-xs text-gray-500">{{ number_format($persenNominal, 1) }}% dari modal vendor</div>
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
                            
                            @if($isAdminKeuangan)
                                <!-- Modern Approve Button -->
                                <button onclick="openApproveModal({{ $pembayaran->id_pembayaran }}, '{{ $pembayaran->penawaran->proyek->nama_barang }}', '{{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}', '{{ $pembayaran->vendor->nama_vendor }}')" 
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-1 transition-all duration-200 shadow-sm">
                                    <i class="fas fa-check mr-1.5"></i>
                                    Approve
                                </button>
                                
                                <!-- Modern Reject Button -->
                                <button onclick="openRejectModal({{ $pembayaran->id_pembayaran }}, '{{ $pembayaran->penawaran->proyek->nama_barang }}', '{{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}', '{{ $pembayaran->vendor->nama_vendor }}')" 
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-all duration-200 shadow-sm">
                                    <i class="fas fa-times mr-1.5"></i>
                                    Reject
                                </button>
                            @else
                                <!-- Read-only indicators for non-admin users -->
                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-gray-600 bg-gray-100 border border-gray-200">
                                    <i class="fas fa-lock mr-1.5 text-gray-400"></i>
                                    Akses Terbatas
                                </span>
                            @endif
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

    @if($isAdminKeuangan)
    <!-- Modern Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" style="backdrop-filter: blur(5px);">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-md shadow-2xl rounded-2xl bg-white transform transition-all duration-300">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Approve Pembayaran</h3>
                        <p class="text-sm text-gray-500">Konfirmasi persetujuan pembayaran</p>
                    </div>
                </div>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-2 transition-colors duration-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-green-600 mr-2"></i>
                        <span class="text-sm font-medium text-green-800">Detail Pembayaran</span>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-green-700">Proyek:</span>
                            <span class="text-sm font-medium text-green-900" id="approve-project-name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-green-700">Vendor:</span>
                            <span class="text-sm font-medium text-green-900" id="approve-vendor-name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-green-700">Nominal:</span>
                            <span class="text-sm font-medium text-green-900" id="approve-amount"></span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Perhatian</p>
                            <p class="text-xs text-yellow-700 mt-1">Setelah disetujui, pembayaran tidak dapat dibatalkan. Pastikan semua dokumen telah diverifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Actions -->
            <form id="approveForm" method="POST">
                @csrf
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeApproveModal()" 
                            class="px-6 py-2.5 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-gray-300 focus:ring-offset-1 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-1 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-check mr-2"></i>
                        Setujui Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modern Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" style="backdrop-filter: blur(5px);">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-lg shadow-2xl rounded-2xl bg-white transform transition-all duration-300">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-times text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Tolak Pembayaran</h3>
                        <p class="text-sm text-gray-500">Berikan alasan penolakan pembayaran</p>
                    </div>
                </div>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-2 transition-colors duration-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        <span class="text-sm font-medium text-red-800">Detail Pembayaran</span>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-red-700">Proyek:</span>
                            <span class="text-sm font-medium text-red-900" id="reject-project-name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-red-700">Vendor:</span>
                            <span class="text-sm font-medium text-red-900" id="reject-vendor-name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-red-700">Nominal:</span>
                            <span class="text-sm font-medium text-red-900" id="reject-amount"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-edit mr-2 text-gray-500"></i>
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none transition-all duration-200"
                                  placeholder="Jelaskan alasan mengapa pembayaran ini ditolak. Contoh: Dokumen tidak lengkap, nominal tidak sesuai, dll."></textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                            <span id="char-count">0</span>/500
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-lightbulb mr-1"></i>
                        Berikan alasan yang jelas agar purchasing dapat melakukan perbaikan yang diperlukan.
                    </p>
                </div>
                
                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-6 py-2.5 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-gray-300 focus:ring-offset-1 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-ban mr-2"></i>
                        Tolak Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Modern Approve Modal Functions
    function openApproveModal(paymentId, projectName, amount, vendorName) {
        const modal = document.getElementById('approveModal');
        const form = document.getElementById('approveForm');
        const projectNameEl = document.getElementById('approve-project-name');
        const amountEl = document.getElementById('approve-amount');
        const vendorNameEl = document.getElementById('approve-vendor-name');
        
        // Set form action
        form.action = `/keuangan/approval/${paymentId}/approve`;
        
        // Set project details
        projectNameEl.textContent = projectName;
        amountEl.textContent = `Rp ${amount}`;
        if (vendorNameEl) vendorNameEl.textContent = vendorName;
        
        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.relative').classList.add('scale-100');
            modal.querySelector('.relative').classList.remove('scale-95');
        }, 10);
    }

    function closeApproveModal() {
        const modal = document.getElementById('approveModal');
        const modalContent = modal.querySelector('.relative');
        
        // Hide with animation
        modalContent.classList.add('scale-95');
        modalContent.classList.remove('scale-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    // Modern Reject Modal Functions
    function openRejectModal(paymentId, projectName, amount, vendorName) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        const projectNameEl = document.getElementById('reject-project-name');
        const amountEl = document.getElementById('reject-amount');
        const vendorNameEl = document.getElementById('reject-vendor-name');
        const textarea = document.getElementById('alasan_penolakan');
        
        // Set form action
        form.action = `/keuangan/approval/${paymentId}/reject`;
        
        // Set project details
        projectNameEl.textContent = projectName;
        amountEl.textContent = `Rp ${amount}`;
        if (vendorNameEl) vendorNameEl.textContent = vendorName;
        
        // Clear textarea
        textarea.value = '';
        updateCharCount();
        
        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.relative').classList.add('scale-100');
            modal.querySelector('.relative').classList.remove('scale-95');
            textarea.focus();
        }, 10);
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        const modalContent = modal.querySelector('.relative');
        const textarea = document.getElementById('alasan_penolakan');
        
        // Hide with animation
        modalContent.classList.add('scale-95');
        modalContent.classList.remove('scale-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            textarea.value = '';
            updateCharCount();
        }, 200);
    }

    // Character count for textarea
    function updateCharCount() {
        const textarea = document.getElementById('alasan_penolakan');
        const charCount = document.getElementById('char-count');
        const currentLength = textarea.value.length;
        charCount.textContent = currentLength;
        
        // Change color based on length
        if (currentLength > 450) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-400');
        } else if (currentLength > 350) {
            charCount.classList.add('text-yellow-500');
            charCount.classList.remove('text-gray-400', 'text-red-500');
        } else {
            charCount.classList.add('text-gray-400');
            charCount.classList.remove('text-yellow-500', 'text-red-500');
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Character count for reject textarea
        const textarea = document.getElementById('alasan_penolakan');
        if (textarea) {
            textarea.addEventListener('input', updateCharCount);
            textarea.addEventListener('keyup', updateCharCount);
        }
        
        // Close modals when clicking outside
        document.getElementById('approveModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeApproveModal();
            }
        });
        
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
        
        // Close modals with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const approveModal = document.getElementById('approveModal');
                const rejectModal = document.getElementById('rejectModal');
                
                if (!approveModal.classList.contains('hidden')) {
                    closeApproveModal();
                }
                if (!rejectModal.classList.contains('hidden')) {
                    closeRejectModal();
                }
            }
        });
        
        // Form validation for reject modal
        const rejectForm = document.getElementById('rejectForm');
        if (rejectForm) {
            rejectForm.addEventListener('submit', function(e) {
                const textarea = document.getElementById('alasan_penolakan');
                const value = textarea.value.trim();
                
                if (value.length < 10) {
                    e.preventDefault();
                    alert('Alasan penolakan minimal 10 karakter.');
                    textarea.focus();
                    return false;
                }
                
                if (value.length > 500) {
                    e.preventDefault();
                    alert('Alasan penolakan maksimal 500 karakter.');
                    textarea.focus();
                    return false;
                }
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalHTML = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                submitBtn.disabled = true;
                
                // Reset after 3 seconds if form doesn't submit (fallback)
                setTimeout(() => {
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }, 3000);
            });
        }
        
        // Form validation for approve modal
        const approveForm = document.getElementById('approveForm');
        if (approveForm) {
            approveForm.addEventListener('submit', function(e) {
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalHTML = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                submitBtn.disabled = true;
                
                // Reset after 3 seconds if form doesn't submit (fallback)
                setTimeout(() => {
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }, 3000);
            });
        }
    });

    // Add initial scale classes for animations
    document.addEventListener('DOMContentLoaded', function() {
        const approveModal = document.getElementById('approveModal');
        const rejectModal = document.getElementById('rejectModal');
        
        if (approveModal) {
            approveModal.querySelector('.relative').classList.add('scale-95');
        }
        if (rejectModal) {
            rejectModal.querySelector('.relative').classList.add('scale-95');
        }
    });
    </script>

    @else
    <!-- JavaScript for non-admin users (basic functionality only) -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show info tooltip for locked buttons
        const lockedButtons = document.querySelectorAll('[data-locked="true"]');
        lockedButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Anda tidak memiliki izin untuk melakukan aksi ini. Hanya Admin Keuangan yang dapat melakukan approve/reject pembayaran.');
            });
        });
        
        // Add tooltip on hover for locked elements
        const accessLimitedElements = document.querySelectorAll('[data-access="limited"]');
        accessLimitedElements.forEach(element => {
            element.title = 'Akses terbatas - Hanya Admin Keuangan yang dapat melakukan approve/reject';
        });
    });
    </script>
    @endif

</div>

@endsection