@extends('layouts.app')

@section('content')

<!-- Header Section -->
<div class="bg-indigo-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Detail Pembayaran</h1>
            <p class="text-indigo-100 text-sm sm:text-base lg:text-lg">{{ $pembayaran->penawaran->proyek->nama_barang }}</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-file-invoice-dollar text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Payment Details -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Informasi Pembayaran</h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @if($pembayaran->status_verifikasi == 'Pending') bg-yellow-100 text-yellow-800
                @elseif($pembayaran->status_verifikasi == 'Approved') bg-green-100 text-green-800
                @else bg-red-100 text-red-800 @endif">
                {{ $pembayaran->status_verifikasi }}
            </span>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column - Payment Info -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Pembayaran</h3>
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">ID Pembayaran:</dt>
                        <dd class="text-sm text-gray-900">#{{ $pembayaran->id_pembayaran }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Jenis Pembayaran:</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($pembayaran->jenis_bayar == 'Lunas') bg-green-100 text-green-800
                                @elseif($pembayaran->jenis_bayar == 'DP') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $pembayaran->jenis_bayar }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Nominal:</dt>
                        <dd class="text-sm font-bold text-gray-900">
                            Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Bayar:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->tanggal_bayar->format('d F Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Metode Pembayaran:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->metode_bayar }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Waktu Input:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->created_at->format('d F Y H:i') }}</dd>
                    </div>
                </dl>
                
                @if($pembayaran->catatan)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Catatan:</h4>
                    <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $pembayaran->catatan }}</p>
                </div>
                @endif
            </div>
            
            <!-- Right Column - Project Info -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Proyek</h3>
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Nama Barang:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->penawaran->proyek->nama_barang }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Klien:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->penawaran->proyek->nama_klien }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Instansi:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->penawaran->proyek->instansi }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">No. Penawaran:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->penawaran->no_penawaran }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Penawaran:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->penawaran->tanggal_penawaran->format('d F Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Masa Berlaku:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->penawaran->masa_berlaku->format('d F Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Status Penawaran:</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($pembayaran->penawaran->status == 'ACC') bg-green-100 text-green-800
                                @elseif($pembayaran->penawaran->status == 'Dikirim') bg-blue-100 text-blue-800
                                @elseif($pembayaran->penawaran->status == 'Ditolak') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $pembayaran->penawaran->status }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Total Penawaran:</dt>
                        <dd class="text-sm font-bold text-gray-900">
                            Rp {{ number_format($pembayaran->penawaran->total_penawaran, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
                
                @if($pembayaran->bukti_bayar)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Bukti Pembayaran:</h4>
                    <div class="border border-gray-200 rounded-lg p-4">
                        @php
                            $fileExtension = pathinfo($pembayaran->bukti_bayar, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                        <img src="{{ Storage::url($pembayaran->bukti_bayar) }}" 
                             alt="Bukti Pembayaran" 
                             class="w-full h-48 object-cover rounded-lg mb-3">
                        @else
                        <div class="flex items-center justify-center h-24 bg-gray-100 rounded-lg mb-3">
                            <i class="fas fa-file-pdf text-red-500 text-3xl"></i>
                        </div>
                        @endif
                        
                        <a href="{{ Storage::url($pembayaran->bukti_bayar) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Buka File Asli
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Documents Section (Surat Pesanan & Surat Penawaran) -->
                @if($pembayaran->penawaran && ($pembayaran->penawaran->surat_pesanan || $pembayaran->penawaran->surat_penawaran))
                
                <!-- Surat Pesanan -->
                @if($pembayaran->penawaran->surat_pesanan)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Surat Pesanan:</h4>
                    <div class="border border-gray-200 rounded-lg p-4">
                        @php
                            $fileSuratPesanan = pathinfo($pembayaran->penawaran->surat_pesanan, PATHINFO_EXTENSION);
                        @endphp
                        
                        <div class="flex items-center justify-center h-16 bg-blue-50 rounded-lg mb-3">
                            @if(in_array(strtolower($fileSuratPesanan), ['pdf']))
                                <i class="fas fa-file-pdf text-red-500 text-2xl mr-2"></i>
                            @elseif(in_array(strtolower($fileSuratPesanan), ['jpg', 'jpeg', 'png']))
                                <i class="fas fa-file-image text-blue-500 text-2xl mr-2"></i>
                            @else
                                <i class="fas fa-file-alt text-gray-500 text-2xl mr-2"></i>
                            @endif
                            <span class="text-sm font-medium text-gray-700">Surat Pesanan</span>
                        </div>
                        
                        <a href="{{ asset('storage/' . $pembayaran->penawaran->surat_pesanan) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100">
                            <i class="fas fa-download mr-2"></i>
                            Lihat Surat Pesanan
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Surat Penawaran -->
                @if($pembayaran->penawaran->surat_penawaran)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Surat Penawaran:</h4>
                    <div class="border border-gray-200 rounded-lg p-4">
                        @php
                            $fileSuratPenawaran = pathinfo($pembayaran->penawaran->surat_penawaran, PATHINFO_EXTENSION);
                        @endphp
                        
                        <div class="flex items-center justify-center h-16 bg-green-50 rounded-lg mb-3">
                            @if(in_array(strtolower($fileSuratPenawaran), ['pdf']))
                                <i class="fas fa-file-pdf text-red-500 text-2xl mr-2"></i>
                            @elseif(in_array(strtolower($fileSuratPenawaran), ['jpg', 'jpeg', 'png']))
                                <i class="fas fa-file-image text-green-500 text-2xl mr-2"></i>
                            @else
                                <i class="fas fa-file-alt text-gray-500 text-2xl mr-2"></i>
                            @endif
                            <span class="text-sm font-medium text-gray-700">Surat Penawaran</span>
                        </div>
                        
                        <a href="{{ asset('storage/' . $pembayaran->penawaran->surat_penawaran) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100">
                            <i class="fas fa-download mr-2"></i>
                            Lihat Surat Penawaran
                        </a>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Progress Section -->
@php
    $totalPenawaran = $pembayaran->penawaran->total_penawaran;
    $totalDibayar = $pembayaran->penawaran->pembayaran()
        ->where('status_verifikasi', '!=', 'Ditolak')
        ->sum('nominal_bayar');
    $sisaBayar = $totalPenawaran - $totalDibayar;
    $persenBayar = $totalPenawaran > 0 ? ($totalDibayar / $totalPenawaran) * 100 : 0;
@endphp

<div class="bg-white rounded-lg shadow-lg mt-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Progress Pembayaran Proyek</h2>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">
                    Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-500">Total Dibayar</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold {{ $sisaBayar > 0 ? 'text-red-600' : 'text-green-600' }}">
                    Rp {{ number_format($sisaBayar, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-500">Sisa Tagihan</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900">{{ number_format($persenBayar, 1) }}%</div>
                <div class="text-sm text-gray-500">Progress</div>
            </div>
        </div>
        
        <div class="mt-6">
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $persenBayar }}%"></div>
            </div>
        </div>
        
        @if($sisaBayar <= 0)
        <div class="flex items-center justify-center mt-4 text-green-600">
            <i class="fas fa-check-circle mr-2"></i>
            <span class="font-medium">Pembayaran Proyek Telah Lunas</span>
        </div>
        @endif
    </div>
</div>

<!-- Action Buttons -->
<div class="flex items-center justify-between pt-6">
    <a href="{{ route('purchasing.pembayaran.history', $pembayaran->penawaran->proyek->id_proyek) }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Riwayat
    </a>
    
    <div class="flex space-x-3">
        <a href="{{ route('purchasing.pembayaran') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-list mr-2"></i>
            Lihat Semua Pembayaran
        </a>
        
        @if($sisaBayar > 0)
        <a href="{{ route('purchasing.pembayaran.create', $pembayaran->penawaran->proyek->id_proyek) }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pembayaran
        </a>
        @endif
    </div>
</div>

@endsection
