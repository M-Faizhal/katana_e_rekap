@extends('layouts.app')

@section('content')

<!-- Header Section -->
<div class="bg-gradient-to-r from-blue-800 to-blue-900 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-xl mt-4">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <div class="flex items-center mb-2">
                <div class="bg-blue-700 rounded-lg p-2 mr-3">
                    <i class="fas fa-money-bill-wave text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold">Input Pembayaran</h1>
                    <p class="text-blue-100 text-sm sm:text-base lg:text-lg">Catat pembayaran dari klien untuk proyek</p>
                </div>
            </div>
            <div class="bg-blue-700/30 rounded-lg p-3 mt-4">
                <div class="text-sm">
                    <div class="font-medium">{{ $proyek->nama_barang }}</div>
                    <div class="text-blue-200">{{ $proyek->nama_klien }} • {{ $proyek->instansi }}</div>
                </div>
            </div>
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-file-invoice-dollar text-5xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Alert Messages -->
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
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span>Terdapat kesalahan pada form:</span>
    </div>
    <ul class="mt-2 ml-4">
        @foreach($errors->all() as $error)
        <li class="list-disc">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Project Info Card - Enhanced -->
<div class="bg-white rounded-xl shadow-lg mb-6 border border-gray-100">
    <div class="p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 rounded-t-xl">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-lg p-2 mr-3">
                <i class="fas fa-project-diagram text-blue-600"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Informasi Proyek</h2>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left: Project Details -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Detail Proyek
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600 font-medium">Kode Proyek:</span>
                            <span class="font-semibold text-gray-900 text-right">{{ $proyek->kode_proyek }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600 font-medium">Kota/Kab:</span>
                            <span class="font-semibold text-gray-900 text-right">{{ $proyek->kab_kota }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600 font-medium">Instansi:</span>
                            <span class="font-semibold text-gray-900 text-right">{{ $proyek->instansi }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600 font-medium">No. Penawaran:</span>
                            <span class="font-semibold text-blue-600 text-right">{{ $proyek->penawaranAktif->no_penawaran }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right: Payment Status -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calculator text-green-500 mr-2"></i>
                        Status Pembayaran
                    </h3>
                    <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg p-4 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Total Modal Vendor:</span>
                            <span class="font-bold text-lg text-green-700">
                                Rp {{ number_format($totalModalVendor ?? 0, 2, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium text-sm">Total Penawaran Klien:</span>
                            <span class="font-semibold text-sm text-blue-600">
                                Rp {{ number_format((float)$proyek->penawaranAktif->total_penawaran, 2, ',', '.') }}
                            </span>
                        </div>
                        @if($totalDibayar > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Sudah Dibayar:</span>
                            <span class="font-semibold text-blue-600">
                                Rp {{ number_format($totalDibayar, 2, ',', '.') }}
                            </span>
                        </div>
                        @endif
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Sisa Tagihan:</span>
                                <span class="font-bold text-xl {{ $sisaBayar > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rp {{ number_format($sisaBayar, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar Enhanced -->
                @php
                    $persenBayar = $totalModalVendor > 0 ? 
                        ($totalDibayar / $totalModalVendor) * 100 : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span class="font-medium">Progress Pembayaran</span>
                        <span class="font-bold">{{ number_format($persenBayar, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 shadow-inner">
                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-500 ease-out" 
                             style="width: {{ $persenBayar }}%"></div>
                    </div>
                    <div class="mt-2 text-center">
                        @if($sisaBayar <= 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Pembayaran Lunas
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-clock mr-1"></i>
                                Menunggu Pembayaran
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Documents Section Enhanced -->
        @if($proyek->penawaranAktif && ($proyek->penawaranAktif->surat_pesanan || $proyek->penawaranAktif->surat_penawaran))
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-file-contract text-purple-500 mr-2"></i>
                Dokumen Terkait
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Surat Pesanan -->
                @if($proyek->penawaranAktif->surat_pesanan)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h4 class="text-sm font-semibold text-blue-800 mb-3">Surat Pesanan</h4>
                    <div class="bg-white border border-blue-200 rounded-lg p-4">
                        @php
                            $fileSuratPesanan = pathinfo($proyek->penawaranAktif->surat_pesanan, PATHINFO_EXTENSION);
                        @endphp
                        
                        <div class="flex items-center justify-center h-16 bg-blue-100 rounded-lg mb-3">
                            @if(in_array(strtolower($fileSuratPesanan), ['pdf']))
                                <i class="fas fa-file-pdf text-red-500 text-2xl mr-2"></i>
                            @elseif(in_array(strtolower($fileSuratPesanan), ['jpg', 'jpeg', 'png']))
                                <i class="fas fa-file-image text-blue-500 text-2xl mr-2"></i>
                            @else
                                <i class="fas fa-file-alt text-gray-500 text-2xl mr-2"></i>
                            @endif
                            <span class="text-sm font-medium text-gray-700">Surat Pesanan</span>
                        </div>
                        
                        <a href="{{ asset('storage/penawaran/' . $proyek->penawaranAktif->surat_pesanan) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 w-full justify-center transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Surat Pesanan
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Surat Penawaran -->
                @if($proyek->penawaranAktif->surat_penawaran)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h4 class="text-sm font-semibold text-green-800 mb-3">Surat Penawaran</h4>
                    <div class="bg-white border border-green-200 rounded-lg p-4">
                        @php
                            $fileSuratPenawaran = pathinfo($proyek->penawaranAktif->surat_penawaran, PATHINFO_EXTENSION);
                        @endphp
                        
                        <div class="flex items-center justify-center h-16 bg-green-100 rounded-lg mb-3">
                            @if(in_array(strtolower($fileSuratPenawaran), ['pdf']))
                                <i class="fas fa-file-pdf text-red-500 text-2xl mr-2"></i>
                            @elseif(in_array(strtolower($fileSuratPenawaran), ['jpg', 'jpeg', 'png']))
                                <i class="fas fa-file-image text-green-500 text-2xl mr-2"></i>
                            @else
                                <i class="fas fa-file-alt text-gray-500 text-2xl mr-2"></i>
                            @endif
                            <span class="text-sm font-medium text-gray-700">Surat Penawaran</span>
                        </div>
                        
                        <a href="{{ asset('storage/penawaran/' . $proyek->penawaranAktif->surat_penawaran) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 w-full justify-center transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Surat Penawaran
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Payment Form Enhanced -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100">
    <div class="p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 rounded-t-xl">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-lg p-2 mr-3">
                <i class="fas fa-edit text-green-600"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Form Input Pembayaran</h2>
                <p class="text-gray-600 mt-1">Masukkan detail pembayaran dari klien</p>
            </div>
        </div>
    </div>
    
    <form action="{{ route('purchasing.pembayaran.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        <input type="hidden" name="id_proyek" value="{{ $proyek->id_proyek }}">
        @if(isset($selectedVendor))
        <input type="hidden" name="id_vendor" value="{{ $selectedVendor->id_vendor }}">
        @endif
        
        <!-- Vendor Selection Section (if not pre-selected) -->
        @if(!isset($selectedVendor))
        <div class="mb-8 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-200">
            <h3 class="font-semibold text-purple-800 mb-4 flex items-center">
                <i class="fas fa-building mr-2"></i>
                Pilih Vendor untuk Pembayaran
            </h3>
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Vendor <span class="text-red-500">*</span>
                </label>
                <select name="id_vendor" id="vendor_select" required 
                        class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    <option value="">-- Pilih Vendor --</option>
                    @foreach($vendors ?? [] as $vendor)
                    <option value="{{ $vendor->id_vendor }}" 
                            data-total="{{ $vendor->total_vendor }}"
                            data-dibayar="{{ $vendor->total_dibayar }}"
                            data-sisa="{{ $vendor->sisa_bayar }}">
                        {{ $vendor->nama_vendor }} 
                        (Sisa: Rp {{ number_format($vendor->sisa_bayar, 2, ',', '.') }})
                    </option>
                    @endforeach
                </select>
                
                <!-- Vendor Info Display -->
                <div id="vendor_info" class="hidden mt-4 p-3 bg-white rounded-lg border border-purple-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-purple-700 font-medium">Total Modal:</span>
                            <span id="vendor_total" class="font-semibold text-purple-900"></span>
                        </div>
                        <div>
                            <span class="text-purple-700 font-medium">Sudah Dibayar:</span>
                            <span id="vendor_dibayar" class="font-semibold text-green-600"></span>
                        </div>
                        <div>
                            <span class="text-purple-700 font-medium">Sisa Tagihan:</span>
                            <span id="vendor_sisa" class="font-semibold text-red-600"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Selected Vendor Info -->
        <div class="mb-8 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
            <h3 class="font-semibold text-green-800 mb-4 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                Vendor Dipilih untuk Pembayaran
            </h3>
            <div class="bg-white rounded-lg p-4 border border-green-200">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-gray-900">{{ $selectedVendor->nama_vendor }}</h4>
                    <span class="text-sm text-gray-600">{{ $selectedVendor->jenis_perusahaan }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Total Modal (Harga Akhir Kalkulasi HPS):</span>
                        <span class="font-medium">Rp {{ number_format($selectedVendor->total_vendor ?? $totalModalVendor, 2, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Dibayar:</span>
                        <span class="font-medium text-green-600">Rp {{ number_format($selectedVendor->total_dibayar ?? $totalDibayar, 2, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Sisa:</span>
                        <span class="font-medium text-red-600">Rp {{ number_format($selectedVendor->sisa_bayar ?? $sisaBayar, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column - Payment Details -->
            <div class="space-y-6">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h3 class="font-semibold text-blue-800 mb-4 flex items-center">
                        <i class="fas fa-money-check mr-2"></i>
                        Detail Pembayaran
                    </h3>
                    
                    <!-- Jenis Pembayaran -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_bayar" id="jenis_bayar" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">-- Pilih Jenis Pembayaran --</option>
                            <option value="Lunas" {{ old('jenis_bayar') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="DP" {{ old('jenis_bayar') == 'DP' ? 'selected' : '' }}>DP (Down Payment)</option>
                            <option value="Cicilan" {{ old('jenis_bayar') == 'Cicilan' ? 'selected' : '' }}>Cicilan</option>
                        </select>
                    </div>
                    
                    <!-- Nominal Pembayaran -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nominal Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 font-medium">Rp</span>
                            <input type="number" name="nominal_bayar" id="nominal_bayar" required 
                                   step="0.01"
                                   min="0.01" max="{{ $sisaBayar }}"
                                   value="{{ old('nominal_bayar') }}"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="0.00">
                        </div>
                        <p class="text-sm text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Maksimal: Rp {{ number_format($sisaBayar, 2, ',', '.') }}
                        </p>
                        
                        <!-- Quick Suggestions Enhanced -->
                        <div class="mt-3" id="suggestions">
                            <p class="text-xs text-gray-600 mb-2">Saran cepat:</p>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="suggestion-btn px-3 py-2 text-xs bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors border border-green-300" 
                                        data-amount="{{ $sisaBayar }}">
                                    <i class="fas fa-check mr-1"></i>
                                    Lunas (Rp {{ number_format($sisaBayar, 2, ',', '.') }})
                                </button>
                                @if($sisaBayar > 0)
                                <button type="button" class="suggestion-btn px-3 py-2 text-xs bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors border border-blue-300" 
                                        data-amount="{{ $sisaBayar * 0.3 }}">
                                    30% (Rp {{ number_format($sisaBayar * 0.3, 2, ',', '.') }})
                                </button>
                                <button type="button" class="suggestion-btn px-3 py-2 text-xs bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors border border-purple-300" 
                                        data-amount="{{ $sisaBayar * 0.5 }}">
                                    50% (Rp {{ number_format($sisaBayar * 0.5, 2, ',', '.') }})
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="metode_bayar" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">-- Pilih Metode --</option>
                            <option value="Transfer Bank" {{ old('metode_bayar') == 'Transfer Bank' ? 'selected' : '' }}>
                                <i class="fas fa-university mr-2"></i>Transfer Bank
                            </option>
                            <option value="Cash" {{ old('metode_bayar') == 'Cash' ? 'selected' : '' }}>
                                <i class="fas fa-money-bill mr-2"></i>Cash
                            </option>
                            <option value="Cek" {{ old('metode_bayar') == 'Cek' ? 'selected' : '' }}>
                                <i class="fas fa-check mr-2"></i>Cek
                            </option>
                            <option value="Giro" {{ old('metode_bayar') == 'Giro' ? 'selected' : '' }}>
                                <i class="fas fa-money-check mr-2"></i>Giro
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Documents & Notes -->
            <div class="space-y-6">
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                    <h3 class="font-semibold text-purple-800 mb-4 flex items-center">
                        <i class="fas fa-file-upload mr-2"></i>
                        Dokumen & Catatan
                    </h3>
                    
                    <!-- Bukti Pembayaran -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bukti Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-purple-300 rounded-lg p-6 text-center bg-white hover:bg-purple-50 transition-colors">
                            <div class="mx-auto h-12 w-12 text-purple-400 mb-4">
                                <i class="fas fa-cloud-upload-alt text-4xl"></i>
                            </div>
                            <input type="file" name="bukti_bayar" id="bukti_bayar" required 
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="hidden">
                            <label for="bukti_bayar" class="cursor-pointer">
                                <span class="text-purple-600 hover:text-purple-500 font-medium">Upload file</span>
                                <span class="text-gray-500"> atau drag & drop</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-2">JPG, JPEG, PNG, PDF (max 5MB)</p>
                        </div>
                        <div id="file-info" class="mt-3 hidden">
                            <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-200">
                                <i class="fas fa-file text-green-600 mr-2"></i>
                                <span id="file-name" class="text-sm text-green-800 flex-1"></span>
                                <button type="button" id="remove-file" class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea name="catatan" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none"
                                  placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                    </div>
                </div>
                
                <!-- Breakdown Modal per Barang Section (untuk selected vendor) -->
                @if(isset($breakdownBarang) && $breakdownBarang && $breakdownBarang->count() > 0)
                <div class="p-4 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg border border-purple-200">
                    <h3 class="text-sm font-semibold text-purple-800 mb-3 flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Modal per Barang - {{ $selectedVendor->nama_vendor ?? 'Vendor' }} ({{ $breakdownBarang->count() }} item)
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($breakdownBarang as $item)
                        @php
                            $persentaseModal = $totalModalVendor > 0 ? ($item->total_harga_hpp / $totalModalVendor) * 100 : 0;
                        @endphp
                        <div class="bg-white rounded-lg p-3 border border-purple-200 shadow-sm">
                            <h4 class="font-medium text-gray-900 text-sm mb-2">{{ $item->nama_barang }}</h4>
                            <div class="space-y-1 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Qty:</span>
                                    <span class="font-medium">{{ number_format($item->qty, 2, ',', '.') }} {{ $item->satuan }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Modal:</span>
                                    <span class="font-bold text-green-600">Rp {{ number_format($item->total_harga_hpp, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kontribusi:</span>
                                    <span class="font-medium text-purple-600">{{ number_format($persentaseModal, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1 mt-2">
                                    <div class="bg-purple-500 h-1 rounded-full" style="width: {{ $persentaseModal }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3 pt-3 border-t border-purple-200 flex justify-between items-center">
                        <span class="text-sm font-medium text-purple-700">Total Modal Vendor:</span>
                        <span class="text-sm font-bold text-purple-800">Rp {{ number_format($totalModalVendor, 2, ',', '.') }}</span>
                    </div>
                </div>
                @endif
                
                <!-- Info Enhanced -->
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-500 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-semibold text-yellow-800 mb-2">Informasi Penting</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li class="flex items-start">
                                    <i class="fas fa-clock text-yellow-500 mr-2 mt-1 text-xs"></i>
                                    Pembayaran akan berstatus "Pending" menunggu verifikasi admin keuangan
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-yellow-500 mr-2 mt-1 text-xs"></i>
                                    Pastikan bukti pembayaran jelas dan valid
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-calendar-check text-yellow-500 mr-2 mt-1 text-xs"></i>
                                    Admin keuangan akan memverifikasi dalam 1-2 hari kerja
                                </li>
                            </ul>
                            <p class="text-xs text-yellow-800 flex items-center mt-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Catatan:</strong> Pembayaran ke vendor menggunakan <strong>harga akhir dari Kalkulasi HPS</strong>, bukan harga vendor barang.
                                @if(isset($breakdownBarang) && $breakdownBarang && $breakdownBarang->count() > 0)
                                <br>Detail breakdown per barang dapat dilihat pada card di atas untuk transparansi perhitungan modal vendor.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons Enhanced -->
        <div class="flex items-center justify-between pt-8 border-t border-gray-200 mt-8">
            <a href="{{ route('purchasing.pembayaran') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar
            </a>
            
            <button type="submit" 
                    class="inline-flex items-center px-8 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <i class="fas fa-save mr-2"></i>
                Simpan Pembayaran
            </button>
        </div>
    </form>
</div>

@push('scripts')
<style>
/* Disable scroll on number inputs */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
}

/* Prevent scroll wheel from changing number input values */
input[type=number] {
    -webkit-appearance: none;
    -moz-appearance: textfield;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent scroll wheel from changing number input values
    const numberInputs = document.querySelectorAll('input[type="number"]');
    
    numberInputs.forEach(function(input) {
        // Disable scroll wheel on number inputs
        input.addEventListener('wheel', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }, { passive: false });
        
        // Also prevent mousewheel event
        input.addEventListener('mousewheel', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }, { passive: false });
        
        // Prevent DOMMouseScroll for Firefox
        input.addEventListener('DOMMouseScroll', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }, { passive: false });
        
        // Blur the input when mouse enters to prevent accidental scroll
        input.addEventListener('mouseenter', function() {
            if (document.activeElement === this) {
                this.blur();
            }
        });
    });
    
    // File upload handling
    const fileInput = document.getElementById('bukti_bayar');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const removeFile = document.getElementById('remove-file');
    
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
            
            // Check file size
            if (file.size > maxSize) {
                // Show error message
                showFileError('Ukuran file terlalu besar! Maksimal 5MB. Ukuran file saat ini: ' + formatFileSize(file.size));
                
                // Clear the input
                this.value = '';
                fileInfo.classList.add('hidden');
                return;
            }
            
            // If file size is OK, show file info
            fileName.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
            fileInfo.classList.remove('hidden');
            hideFileError();
        }
    });
    
    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        fileInfo.classList.add('hidden');
        hideFileError();
    });
    
    // Helper function to format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Helper function to show file error
    function showFileError(message) {
        // Remove existing error if any
        hideFileError();
        
        // Create error element
        const errorDiv = document.createElement('div');
        errorDiv.id = 'file-error';
        errorDiv.className = 'mt-3 p-3 bg-red-50 rounded-lg border border-red-200';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                <span class="text-sm text-red-800">${message}</span>
            </div>
        `;
        
        // Insert after file input container
        const fileContainer = document.querySelector('#bukti_bayar').closest('.border-2');
        fileContainer.parentNode.insertBefore(errorDiv, fileContainer.nextSibling);
    }
    
    // Helper function to hide file error
    function hideFileError() {
        const existingError = document.getElementById('file-error');
        if (existingError) {
            existingError.remove();
        }
    }
    
    // Vendor selection handling
    const vendorSelect = document.getElementById('vendor_select');
    const vendorInfo = document.getElementById('vendor_info');
    const nominalInput = document.getElementById('nominal_bayar');
    const suggestionsContainer = document.getElementById('suggestions');
    
    if (vendorSelect) {
        vendorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                // Show vendor info
                vendorInfo.classList.remove('hidden');
                
                // Update vendor info display
                const total = parseFloat(selectedOption.dataset.total) || 0;
                const dibayar = parseFloat(selectedOption.dataset.dibayar) || 0;
                const sisa = parseFloat(selectedOption.dataset.sisa) || 0;
                
                document.getElementById('vendor_total').textContent = 'Rp ' + total.toLocaleString('id-ID');
                document.getElementById('vendor_dibayar').textContent = 'Rp ' + dibayar.toLocaleString('id-ID');
                document.getElementById('vendor_sisa').textContent = 'Rp ' + sisa.toLocaleString('id-ID');
                
                // Update nominal input max value
                nominalInput.max = sisa;
                nominalInput.setAttribute('data-vendor-sisa', sisa);
                
                // Update suggestions
                updateSuggestions(sisa);
            } else {
                vendorInfo.classList.add('hidden');
                nominalInput.max = '';
                nominalInput.removeAttribute('data-vendor-sisa');
            }
        });
    }
    
    // Update suggestions based on vendor sisa
    function updateSuggestions(sisa) {
        if (!suggestionsContainer) return;
        
        const suggestionButtons = suggestionsContainer.querySelectorAll('.suggestion-btn');
        suggestionButtons.forEach(btn => {
            const amount = parseFloat(btn.getAttribute('data-amount'));
            if (amount > sisa) {
                btn.style.display = 'none';
            } else {
                btn.style.display = 'inline-block';
            }
        });
        
        // Add vendor-specific suggestions
        const existingVendorSuggestions = suggestionsContainer.querySelectorAll('.vendor-suggestion');
        existingVendorSuggestions.forEach(btn => btn.remove());
        
        // Add lunas button for this vendor
        const lunasBtn = document.createElement('button');
        lunasBtn.type = 'button';
        lunasBtn.className = 'vendor-suggestion suggestion-btn px-3 py-2 text-xs bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors border border-green-300';
        lunasBtn.setAttribute('data-amount', sisa);
        lunasBtn.innerHTML = '<i class="fas fa-check mr-1"></i>Lunas Vendor (Rp ' + sisa.toLocaleString('id-ID') + ')';
        suggestionsContainer.appendChild(lunasBtn);
        
        // Add event listener to new button
        lunasBtn.addEventListener('click', function() {
            nominalInput.value = parseFloat(sisa).toFixed(2);
        });
    }
    
    // Suggestion buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('suggestion-btn') || e.target.closest('.suggestion-btn')) {
            const btn = e.target.classList.contains('suggestion-btn') ? e.target : e.target.closest('.suggestion-btn');
            const amount = btn.getAttribute('data-amount');
            nominalInput.value = parseFloat(amount).toFixed(2);
        }
    });
    
    // Auto-select jenis bayar based on nominal
    const jenisSelect = document.getElementById('jenis_bayar');
    
    nominalInput.addEventListener('input', function() {
        const nominal = parseFloat(this.value) || 0;
        const maxAmount = parseFloat(this.max) || parseFloat(this.getAttribute('data-vendor-sisa')) || {{ $sisaBayar }};
        
        if (nominal >= maxAmount && jenisSelect.value === '') {
            jenisSelect.value = 'Lunas';
        }
        
        // Validate against max
        if (nominal > maxAmount) {
            this.setCustomValidity('Nominal tidak boleh melebihi sisa tagihan vendor');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Format number input - support decimal values
    nominalInput.addEventListener('blur', function() {
        const value = parseFloat(this.value);
        if (!isNaN(value) && value > 0) {
            // Keep decimal values, format to 2 decimal places if needed
            this.value = value;
        }
    });
});
</script>
@endpush

@endsection
