@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-green-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Pelunasan Pembayaran</h1>
            <p class="text-green-100 text-sm sm:text-base lg:text-lg">{{ $penagihanDinas->nomor_invoice }}</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-money-check-alt text-3xl sm:text-4xl lg:text-6xl text-green-200"></i>
        </div>
    </div>
</div>

<!-- Navigation -->
<div class="flex items-center justify-between mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('penagihan-dinas.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                    Penagihan Dinas
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('penagihan-dinas.show', $penagihanDinas->id) }}" class="text-sm font-medium text-gray-700 hover:text-green-600">
                        {{ $penagihanDinas->nomor_invoice }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Pelunasan</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <a href="{{ route('penagihan-dinas.show', $penagihanDinas->id) }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Detail
    </a>
</div>

<!-- Main Content -->
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
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        <i class="fas fa-hand-holding-usd mr-1"></i>Down Payment
                    </span>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Harga:</span>
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format((float)$penagihanDinas->proyek->harga_total ?? 0, 2, ',', '.') }}</span>
                    </div>
                    
                    @php
                        $totalBayar = (float)($penagihanDinas->buktiPembayaran->sum('jumlah_bayar') ?? 0);
                    @endphp
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Sudah Terbayar:</span>
                        <span class="text-lg font-semibold text-green-600">Rp {{ number_format($totalBayar, 2, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Sisa Pembayaran:</span>
                        <span class="text-xl font-bold text-red-600">Rp {{ number_format($sisaPembayaran, 2, ',', '.') }}</span>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                @php
                    $totalHarga = (float)($penagihanDinas->proyek->harga_total ?? 0);
                    $persentaseTerbayar = $totalHarga > 0 ? ($totalBayar / $totalHarga) * 100 : 0;
                @endphp
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

        <!-- Detail Barang -->
        <div class="bg-white rounded-lg shadow-lg mt-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-boxes text-purple-500 mr-2"></i>
                    Detail Barang
                </h2>
            </div>
            <div class="p-6">
                @if($penagihanDinas->penawaran && $penagihanDinas->penawaran->penawaranDetail->count() > 0)
                <div class="space-y-3">
                    @foreach($penagihanDinas->penawaran->penawaranDetail as $detail)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $detail->barang->nama_barang ?? 'Barang tidak ditemukan' }}</p>
                            <p class="text-xs text-gray-500">{{ $detail->qty }} x Rp {{ number_format((float)$detail->harga_satuan, 2, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">Rp {{ number_format((float)$detail->subtotal, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500 text-center py-4">Tidak ada detail barang</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column - Form Pelunasan -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-money-check-alt text-green-500 mr-2"></i>
                    Form Pelunasan
                </h2>
                <p class="text-sm text-gray-600 mt-1">Lengkapi pembayaran terakhir untuk penagihan ini</p>
            </div>
            
            <form action="{{ route('penagihan-dinas.pelunasan', $penagihanDinas->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <!-- Alert Sisa Pembayaran -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-info text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-blue-900">Konfirmasi Pelunasan</h3>
                                <p class="text-blue-700">Jumlah yang akan dibayarkan sebagai pelunasan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-blue-600">Sisa Pembayaran</p>
                            <p class="text-2xl font-bold text-blue-900">Rp {{ number_format($sisaPembayaran, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bukti Pembayaran -->
                    <div class="md:col-span-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-file-image text-green-600 mr-2"></i>
                            Bukti Pembayaran *
                        </label>
                        <input type="file" name="bukti_pembayaran" accept=".pdf,.jpg,.jpeg,.png" required
                               class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 file:transition-colors file:duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <p class="mt-2 text-xs text-gray-500">Format: PDF, JPG, JPEG, PNG - Maksimal 2MB</p>
                        @error('bukti_pembayaran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Bayar -->
                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Tanggal Bayar *
                        </label>
                        <input type="date" name="tanggal_bayar" required value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                               class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3">
                        @error('tanggal_bayar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-1">
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-comment-alt text-green-600 mr-2"></i>
                            Keterangan
                        </label>
                        <textarea name="keterangan" rows="4"
                                  class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3"
                                  placeholder="Keterangan pelunasan (opsional)...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('penagihan-dinas.show', $penagihanDinas->id) }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pelunasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Prevent scroll on number inputs */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

input[type="number"]:focus {
    outline: none;
}
</style>
@endpush

@endsection
