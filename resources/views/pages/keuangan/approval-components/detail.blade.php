@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-indigo-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Detail Approval Pembayaran</h1>
            <p class="text-indigo-100 text-sm sm:text-base lg:text-lg">
                {{ $pembayaran->penawaran->proyek->nama_barang }} - {{ $pembayaran->penawaran->proyek->nama_klien }}
            </p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-search-dollar text-3xl sm:text-4xl lg:text-6xl"></i>
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

@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <div class="flex items-center mb-2">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <span class="font-medium">Terdapat kesalahan:</span>
    </div>
    <ul class="list-disc list-inside ml-4">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Payment Details -->
<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Detail Pembayaran</h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                <i class="fas fa-clock mr-2"></i>
                {{ $pembayaran->status_verifikasi }}
            </span>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column - Payment Info -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>
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
                        <dt class="text-sm font-medium text-gray-500">Total Penawaran:</dt>
                        <dd class="text-sm font-bold text-gray-900">
                            Rp {{ number_format($totalPenawaran, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Sudah Dibayar:</dt>
                        <dd class="text-sm text-blue-600">
                            Rp {{ number_format($totalApproved, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <dt class="text-sm font-medium text-gray-500">Sisa Setelah Approve:</dt>
                        <dd class="text-sm font-bold {{ ($sisaBayar - $pembayaran->nominal_bayar) > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($sisaBayar - $pembayaran->nominal_bayar, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
                
                <!-- Bukti Pembayaran -->
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
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 w-full justify-center">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Buka Bukti Pembayaran
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
                           class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 w-full justify-center">
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
                           class="inline-flex items-center px-3 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 w-full justify-center">
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
    $persenBayar = $totalPenawaran > 0 ? (($totalApproved + $pembayaran->nominal_bayar) / $totalPenawaran) * 100 : 0;
@endphp

<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Progress Pembayaran</h2>
    </div>
    
    <div class="p-6">
        <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Progress jika disetujui</span>
                <span>{{ number_format($persenBayar, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: {{ $persenBayar }}%"></div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-900">{{ number_format($persenBayar, 1) }}%</div>
                <div class="text-sm text-gray-600">Progress</div>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-xl font-bold text-blue-600">Rp {{ number_format($totalApproved + $pembayaran->nominal_bayar, 0, ',', '.') }}</div>
                <div class="text-sm text-gray-600">Total Akan Dibayar</div>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg">
                <div class="text-xl font-bold text-red-600">Rp {{ number_format($sisaBayar - $pembayaran->nominal_bayar, 0, ',', '.') }}</div>
                <div class="text-sm text-gray-600">Sisa Tagihan</div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
@if($pembayaran->status_verifikasi === 'Pending')
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Keputusan Approval</h2>
        <p class="text-gray-600 mt-1">Pilih tindakan untuk pembayaran ini</p>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Approve Section -->
            <div class="border border-green-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-green-800 mb-3">
                    <i class="fas fa-check-circle mr-2"></i>
                    Setujui Pembayaran
                </h3>
                <p class="text-sm text-gray-600 mb-4">Pembayaran akan disetujui dan status berubah menjadi "Approved".</p>
                
                <form action="{{ route('keuangan.approval.approve', $pembayaran->id_pembayaran) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="catatan_approval" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Approval (Opsional)
                        </label>
                        <textarea name="catatan_approval" id="catatan_approval" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                  placeholder="Tambahkan catatan jika diperlukan...">{{ $pembayaran->catatan }}</textarea>
                    </div>
                    
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-check mr-2"></i>
                        Setujui Pembayaran
                    </button>
                </form>
            </div>
            
            <!-- Reject Section -->
            <div class="border border-red-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-red-800 mb-3">
                    <i class="fas fa-times-circle mr-2"></i>
                    Tolak Pembayaran
                </h3>
                <p class="text-sm text-gray-600 mb-4">Pembayaran akan ditolak dan dikembalikan ke purchasing untuk diperbaiki.</p>
                
                <form action="{{ route('keuangan.approval.reject', $pembayaran->id_pembayaran) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                  placeholder="Masukkan alasan penolakan pembayaran..."></textarea>
                    </div>
                    
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?')"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-times mr-2"></i>
                        Tolak Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Navigation -->
<div class="flex items-center justify-between pt-6">
    <a href="{{ route('keuangan.approval') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar
    </a>
</div>

@endsection