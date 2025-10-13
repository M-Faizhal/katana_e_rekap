@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-indigo-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Detail Approval Pembayaran</h1>
            <p class="text-indigo-100 text-sm sm:text-base lg:text-lg">
                {{ $pembayaran->penawaran->proyek->nama_barang }} - {{ $pembayaran->penawaran->proyek->kode_proyek }}
            </p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-search-dollar text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-6 rounded-xl shadow-lg mb-6">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                <i class="fas fa-check-circle text-green-600 text-lg"></i>
            </div>
        </div>
        <div class="ml-4">
            <div class="text-lg font-semibold text-green-800">Berhasil!</div>
            <div class="text-green-700">{{ session('success') }}</div>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-400 p-6 rounded-xl shadow-lg mb-6">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            </div>
        </div>
        <div class="ml-4">
            <div class="text-lg font-semibold text-red-800">Terjadi Kesalahan!</div>
            <div class="text-red-700">{{ session('error') }}</div>
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-gradient-to-r from-orange-50 to-red-50 border-l-4 border-orange-400 p-6 rounded-xl shadow-lg mb-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-orange-100">
                <i class="fas fa-exclamation-triangle text-orange-600 text-lg"></i>
            </div>
        </div>
        <div class="ml-4">
            <div class="text-lg font-semibold text-orange-800 mb-2">Terdapat kesalahan:</div>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                <li class="flex items-center text-orange-700">
                    <i class="fas fa-circle text-xs mr-2"></i>
                    {{ $error }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
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
                        <dt class="text-sm font-medium text-gray-500">Vendor:</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $pembayaran->vendor->nama_vendor }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Jenis Perusahaan:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->vendor->jenis_perusahaan }}</dd>
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
                        <dd class="text-lg font-bold text-green-600">
                            Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Total Modal Vendor:</dt>
                        <dd class="text-sm font-semibold text-gray-900">
                            Rp {{ number_format($totalModalVendor, 0, ',', '.') }}
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
                        <dt class="text-sm font-medium text-gray-500">Kode Proyek:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->penawaran->proyek->kode_proyek }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Kota/Kab:</dt>
                        <dd class="text-sm text-gray-900">{{ $pembayaran->penawaran->proyek->kab_kota }}</dd>
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
                        <dt class="text-sm font-medium text-gray-500">Sudah Dibayar Vendor:</dt>
                        <dd class="text-sm text-blue-600">
                            Rp {{ number_format($totalApproved, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <dt class="text-sm font-medium text-gray-500">Sisa Vendor Setelah Approve:</dt>
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
                        
                        <a href="{{ asset('storage/penawaran/' . $pembayaran->penawaran->surat_pesanan) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 w-full justify-center transition-colors">
                            <i class="fas fa-eye mr-2"></i>
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
                        
                        <a href="{{ asset('storage/penawaran/' . $pembayaran->penawaran->surat_penawaran) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 w-full justify-center transition-colors">
                            <i class="fas fa-eye mr-2"></i>
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



<!-- Action Buttons -->
@if($pembayaran->status_verifikasi === 'Pending')
<div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl shadow-lg border border-indigo-100">
    <div class="p-6 border-b border-indigo-200">
        <div class="flex items-center">
            <div class="bg-indigo-100 p-3 rounded-full mr-4">
                <i class="fas fa-gavel text-indigo-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Keputusan Approval</h2>
                <p class="text-gray-600 mt-1">Pilih tindakan untuk pembayaran ini dengan bijak</p>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Approve Button -->
            <button type="button" 
                    onclick="openApproveModal()"
                    class="group relative bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-center mb-3">
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-2">Setujui Pembayaran</h3>
                <p class="text-green-100 text-sm">Pembayaran akan disetujui dan status berubah menjadi "Approved"</p>
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right text-white"></i>
                </div>
            </button>
            
            <!-- Reject Button -->
            <button type="button" 
                    onclick="openRejectModal()"
                    class="group relative bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white rounded-xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-center mb-3">
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-2">Tolak Pembayaran</h3>
                <p class="text-red-100 text-sm">Pembayaran akan ditolak dan dikembalikan untuk diperbaiki</p>
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right text-white"></i>
                </div>
            </button>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="approveModalContent">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                            <i class="fas fa-check-circle text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold">Setujui Pembayaran</h3>
                    </div>
                    <button type="button" onclick="closeApproveModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="mt-2 text-green-100">Pembayaran akan disetujui dan status berubah menjadi "Approved"</p>
            </div>
            
            <form action="{{ route('keuangan.approval.approve', $pembayaran->id_pembayaran) }}" method="POST" id="approveForm">
                @csrf
                <div class="p-6">
                    <div class="mb-4">
                        <label for="catatan_approval" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-1"></i>
                            Catatan Approval (Opsional)
                        </label>
                        <textarea name="catatan_approval" id="catatan_approval" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                  placeholder="Tambahkan catatan jika diperlukan...">{{ $pembayaran->catatan }}</textarea>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-green-600 mt-1 mr-2"></i>
                            <div class="text-sm text-green-800">
                                <strong>Konfirmasi:</strong> Pembayaran sebesar 
                                <span class="font-bold">Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}</span>
                                akan disetujui.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-3 p-6 pt-0">
                    <button type="button" 
                            onclick="closeApproveModal()"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" 
                            id="approveButton"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-check mr-2"></i>
                        <span id="approveButtonText">Setujui</span>
                        <i class="fas fa-spinner fa-spin hidden" id="approveSpinner"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="rejectModalContent">
            <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                            <i class="fas fa-times-circle text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold">Tolak Pembayaran</h3>
                    </div>
                    <button type="button" onclick="closeRejectModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="mt-2 text-red-100">Pembayaran akan ditolak dan dikembalikan untuk diperbaiki</p>
            </div>
            
            <form action="{{ route('keuangan.approval.reject', $pembayaran->id_pembayaran) }}" method="POST" id="rejectForm">
                @csrf
                <div class="p-6">
                    <div class="mb-4">
                        <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                  placeholder="Masukkan alasan penolakan pembayaran..."></textarea>
                        <div class="text-red-500 text-xs mt-1 hidden" id="rejectError">Alasan penolakan wajib diisi</div>
                    </div>
                    
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 mt-1 mr-2"></i>
                            <div class="text-sm text-red-800">
                                <strong>Peringatan:</strong> Pembayaran sebesar 
                                <span class="font-bold">Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}</span>
                                akan ditolak dan dikembalikan ke purchasing.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-3 p-6 pt-0">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" 
                            id="rejectButton"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>
                        <span id="rejectButtonText">Tolak</span>
                        <i class="fas fa-spinner fa-spin hidden" id="rejectSpinner"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Navigation -->
<div class="flex items-center justify-between pt-6">
    <a href="{{ route('keuangan.approval') }}" 
       class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-105">
        <i class="fas fa-arrow-left mr-3 transition-transform group-hover:-translate-x-1"></i>
        <span>Kembali ke Daftar Approval</span>
    </a>
    
    @if($pembayaran->status_verifikasi !== 'Pending')
    <div class="flex items-center space-x-3">
        <div class="px-4 py-2 rounded-lg font-medium {{ $pembayaran->status_verifikasi === 'Approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            <i class="fas {{ $pembayaran->status_verifikasi === 'Approved' ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
            Status: {{ $pembayaran->status_verifikasi }}
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Modal Functions
function openApproveModal() {
    const modal = document.getElementById('approveModal');
    const content = document.getElementById('approveModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    const content = document.getElementById('approveModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function openRejectModal() {
    const modal = document.getElementById('rejectModal');
    const content = document.getElementById('rejectModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    const content = document.getElementById('rejectModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Close modals when clicking outside
document.getElementById('approveModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApproveModal();
    }
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// Form submission with loading states
document.getElementById('approveForm').addEventListener('submit', function(e) {
    const button = document.getElementById('approveButton');
    const buttonText = document.getElementById('approveButtonText');
    const spinner = document.getElementById('approveSpinner');
    
    button.disabled = true;
    buttonText.textContent = 'Memproses...';
    spinner.classList.remove('hidden');
});

document.getElementById('rejectForm').addEventListener('submit', function(e) {
    const alasan = document.getElementById('alasan_penolakan').value.trim();
    const errorDiv = document.getElementById('rejectError');
    
    if (!alasan) {
        e.preventDefault();
        errorDiv.classList.remove('hidden');
        document.getElementById('alasan_penolakan').focus();
        return;
    }
    
    errorDiv.classList.add('hidden');
    
    const button = document.getElementById('rejectButton');
    const buttonText = document.getElementById('rejectButtonText');
    const spinner = document.getElementById('rejectSpinner');
    
    button.disabled = true;
    buttonText.textContent = 'Memproses...';
    spinner.classList.remove('hidden');
});

// Real-time validation for reject form
document.getElementById('alasan_penolakan').addEventListener('input', function() {
    const errorDiv = document.getElementById('rejectError');
    if (this.value.trim()) {
        errorDiv.classList.add('hidden');
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Escape key to close modals
    if (e.key === 'Escape') {
        if (!document.getElementById('approveModal').classList.contains('hidden')) {
            closeApproveModal();
        }
        if (!document.getElementById('rejectModal').classList.contains('hidden')) {
            closeRejectModal();
        }
    }
});
</script>
@endpush