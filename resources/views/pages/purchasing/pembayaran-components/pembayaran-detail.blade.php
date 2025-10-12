@extends('layouts.app')

@section('content')
@php
    $currentUser = Auth::user();
    $canAccess = $currentUser->role === 'admin_purchasing' && $pembayaran->penawaran->proyek->id_admin_purchasing == $currentUser->id_user;
    $sisaBayar = $pembayaran->penawaran->proyek->sisa_bayar ?? 0;
@endphp

<!-- Header Section Enhanced -->
<div class="bg-gradient-to-r from-indigo-800 to-purple-900 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-xl mt-4">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <div class="flex items-center mb-3">
                <div class="bg-indigo-700 rounded-lg p-2 mr-3">
                    <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold">Detail Pembayaran</h1>
                    <p class="text-indigo-100 text-sm sm:text-base lg:text-lg">
                        {{ $pembayaran->penawaran->proyek->nama_barang }}
                    </p>
                    <p class="text-indigo-200 text-xs sm:text-sm">
                        Vendor: {{ $pembayaran->vendor->nama_vendor ?? 'N/A' }}
                    </p>
                </div>
            </div>
            <div class="bg-indigo-700/30 rounded-lg p-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-indigo-200">ID Pembayaran:</span>
                        <span class="font-semibold ml-2">#{{ $pembayaran->id_pembayaran }}</span>
                    </div>
                    <div>
                        <span class="text-indigo-200">Status:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2
                            @if($pembayaran->status_verifikasi == 'Pending') bg-yellow-500 text-white
                            @elseif($pembayaran->status_verifikasi == 'Approved') bg-green-500 text-white
                            @else bg-red-500 text-white @endif">
                            {{ $pembayaran->status_verifikasi }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-receipt text-5xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Payment Details Enhanced -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100">
    <div class="p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 rounded-t-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Informasi Pembayaran</h2>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-sm
                @if($pembayaran->status_verifikasi == 'Pending') bg-yellow-100 text-yellow-800 border border-yellow-300
                @elseif($pembayaran->status_verifikasi == 'Approved') bg-green-100 text-green-800 border border-green-300
                @else bg-red-100 text-red-800 border border-red-300 @endif">
                @if($pembayaran->status_verifikasi == 'Pending')
                    <i class="fas fa-hourglass-half mr-2"></i>
                @elseif($pembayaran->status_verifikasi == 'Approved')
                    <i class="fas fa-check-circle mr-2"></i>
                @else
                    <i class="fas fa-times-circle mr-2"></i>
                @endif
                {{ $pembayaran->status_verifikasi }}
            </span>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column - Payment Info Enhanced -->
            <div class="space-y-6">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                        <i class="fas fa-money-check-alt mr-2"></i>
                        Detail Pembayaran
                    </h3>
                    <dl class="space-y-4">
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-600">ID Pembayaran:</dt>
                            <dd class="text-sm font-semibold text-gray-900 bg-gray-100 px-2 py-1 rounded">#{{ $pembayaran->id_pembayaran }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-600">Jenis Pembayaran:</dt>
                            <dd class="text-sm">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($pembayaran->jenis_bayar == 'Lunas') bg-green-100 text-green-800 border border-green-300
                                    @elseif($pembayaran->jenis_bayar == 'DP') bg-blue-100 text-blue-800 border border-blue-300
                                    @else bg-yellow-100 text-yellow-800 border border-yellow-300 @endif">
                                    @if($pembayaran->jenis_bayar == 'Lunas')
                                        <i class="fas fa-check mr-1"></i>
                                    @elseif($pembayaran->jenis_bayar == 'DP')
                                        <i class="fas fa-arrow-down mr-1"></i>
                                    @else
                                        <i class="fas fa-clock mr-1"></i>
                                    @endif
                                    {{ $pembayaran->jenis_bayar }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center bg-white rounded-lg p-3 border border-gray-200">
                            <dt class="text-sm font-medium text-gray-600">Nominal:</dt>
                            <dd class="text-lg font-bold text-green-600">
                                Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-600">Tanggal Bayar:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $pembayaran->tanggal_bayar->format('d F Y') }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-600">Metode Pembayaran:</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                <span class="inline-flex items-center">
                                    @if($pembayaran->metode_bayar == 'Transfer Bank')
                                        <i class="fas fa-university text-blue-500 mr-1"></i>
                                    @elseif($pembayaran->metode_bayar == 'Cash')
                                        <i class="fas fa-money-bill text-green-500 mr-1"></i>
                                    @else
                                        <i class="fas fa-credit-card text-purple-500 mr-1"></i>
                                    @endif
                                    {{ $pembayaran->metode_bayar }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-600">Waktu Input:</dt>
                            <dd class="text-sm text-gray-700">{{ $pembayaran->created_at->format('d F Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between items-center bg-purple-50 rounded-lg p-3 border border-purple-200">
                            <dt class="text-sm font-medium text-purple-700">Total Modal Vendor:</dt>
                            <dd class="text-sm font-bold text-purple-800">
                                Rp {{ number_format($totalModalVendor, 0, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                    
                    @if($pembayaran->catatan)
                    <div class="mt-6 pt-4 border-t border-blue-200">
                        <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                            <i class="fas fa-sticky-note mr-1"></i>
                            Catatan:
                        </h4>
                        <p class="text-sm text-gray-700 bg-white p-3 rounded-lg border border-gray-200 italic">
                            "{{ $pembayaran->catatan }}"
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Right Column - Project Info Enhanced -->
            <div class="space-y-6">
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                        <i class="fas fa-project-diagram mr-2"></i>
                        Informasi Proyek & Vendor
                    </h3>
                    <dl class="space-y-4">
                        <div class="flex justify-between items-start">
                            <dt class="text-sm font-medium text-gray-600">Kode Proyek:</dt>
                            <dd class="text-sm font-semibold text-gray-900 text-right max-w-xs">{{ $pembayaran->penawaran->proyek->kode_proyek }}</dd>
                        </div>
                        <div class="flex justify-between items-start">
                            <dt class="text-sm font-medium text-gray-600">Vendor:</dt>
                            <dd class="text-sm font-semibold text-purple-700 text-right max-w-xs bg-purple-100 px-2 py-1 rounded">{{ $pembayaran->vendor->nama_vendor ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between items-start">
                            <dt class="text-sm font-medium text-gray-600">Kota/Kab:</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right max-w-xs">{{ $pembayaran->penawaran->proyek->kab_kota }}</dd>
                        </div>
                        <div class="flex justify-between items-start">
                            <dt class="text-sm font-medium text-gray-600">Instansi:</dt>
                            <dd class="text-sm text-gray-700 text-right max-w-xs">{{ $pembayaran->penawaran->proyek->instansi }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-600">No. Penawaran:</dt>
                            <dd class="text-sm font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded">{{ $pembayaran->penawaran->no_penawaran }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-600">Tanggal Penawaran:</dt>
                            <dd class="text-sm text-gray-700">{{ $pembayaran->penawaran->tanggal_penawaran->format('d F Y') }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-600">Masa Berlaku:</dt>
                            <dd class="text-sm text-gray-700">{{ $pembayaran->penawaran->masa_berlaku->format('d F Y') }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-600">Status Penawaran:</dt>
                            <dd class="text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($pembayaran->penawaran->status == 'ACC') bg-green-100 text-green-800
                                    @elseif($pembayaran->penawaran->status == 'Dikirim') bg-blue-100 text-blue-800
                                    @elseif($pembayaran->penawaran->status == 'Ditolak') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $pembayaran->penawaran->status }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center bg-white rounded-lg p-3 border border-gray-200">
                            <dt class="text-sm font-medium text-gray-600">Total Penawaran:</dt>
                            <dd class="text-lg font-bold text-green-600">
                                Rp {{ number_format($pembayaran->penawaran->total_penawaran, 0, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                </div>
                
                <!-- Bukti Pembayaran Enhanced -->
                @if($pembayaran->bukti_bayar)
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                    <h4 class="text-sm font-semibold text-purple-800 mb-3 flex items-center">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Bukti Pembayaran
                    </h4>
                    <div class="bg-white border border-purple-200 rounded-lg p-4">
                        @php
                            $fileExtension = pathinfo($pembayaran->bukti_bayar, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                        <img src="{{ Storage::url($pembayaran->bukti_bayar) }}" 
                             alt="Bukti Pembayaran" 
                             class="w-full h-48 object-cover rounded-lg mb-3 shadow-sm border border-gray-200">
                        @else
                        <div class="flex items-center justify-center h-24 bg-gray-100 rounded-lg mb-3 border border-gray-200">
                            <i class="fas fa-file-pdf text-red-500 text-4xl"></i>
                        </div>
                        @endif
                        
                        <a href="{{ Storage::url($pembayaran->bukti_bayar) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-purple-300 shadow-sm text-sm leading-4 font-medium rounded-lg text-purple-700 bg-purple-50 hover:bg-purple-100 w-full justify-center transition-colors">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Lihat File Bukti Pembayaran
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

<!-- Breakdown Modal per Barang Section -->
@if($breakdownBarang && $breakdownBarang->count() > 0)
<div class="bg-white rounded-xl shadow-lg border border-gray-100 mt-6">
    <div class="p-6 bg-gradient-to-r from-purple-50 to-blue-50 border-b border-gray-200 rounded-t-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-chart-pie text-purple-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Modal per Barang</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $pembayaran->vendor->nama_vendor ?? 'Vendor' }} - Detail modal setiap item barang</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold text-purple-600">
                    {{ $breakdownBarang->count() }} Item
                </div>
                <div class="text-sm text-gray-500">Total Barang</div>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <!-- Desktop View -->
        <div class="hidden md:block">
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">No.</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">Nama Barang</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">Qty</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">Satuan</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">Harga Vendor</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">Harga Akhir</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">Total Modal</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">% dari Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($breakdownBarang as $index => $item)
                        @php
                            $persentaseModal = $totalModalVendor > 0 ? ($item->total_harga_hpp / $totalModalVendor) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 border-b border-gray-100 text-sm">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 border-b border-gray-100">
                                <div class="font-medium text-gray-900">{{ $item->nama_barang }}</div>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-100 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ number_format($item->qty, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-100 text-center text-sm text-gray-600">
                                {{ $item->satuan }}
                            </td>
                            <td class="py-3 px-4 border-b border-gray-100 text-right text-sm">
                                <span class="text-gray-600">Rp {{ number_format($item->harga_vendor, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-100 text-right text-sm font-medium">
                                <span class="text-purple-600">Rp {{ number_format($item->harga_akhir, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-100 text-right">
                                <span class="font-bold text-green-600">Rp {{ number_format($item->total_harga_hpp, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-100 text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full" style="width: {{ $persentaseModal }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">{{ number_format($persentaseModal, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 font-semibold">
                            <td colspan="6" class="py-4 px-4 text-right border-t-2 border-gray-300">
                                <span class="text-gray-700">Total Modal Vendor:</span>
                            </td>
                            <td class="py-4 px-4 text-right border-t-2 border-gray-300">
                                <span class="text-lg font-bold text-green-600">Rp {{ number_format($totalModalVendor, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-4 px-4 text-center border-t-2 border-gray-300">
                                <span class="text-sm font-medium text-green-600">100%</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Mobile View -->
        <div class="md:hidden space-y-4">
            @foreach($breakdownBarang as $index => $item)
            @php
                $persentaseModal = $totalModalVendor > 0 ? ($item->total_harga_hpp / $totalModalVendor) * 100 : 0;
            @endphp
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $item->nama_barang }}</h4>
                        <p class="text-sm text-gray-600 mt-1">Item #{{ $index + 1 }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-green-600">
                            Rp {{ number_format($item->total_harga_hpp, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-500">{{ number_format($persentaseModal, 1) }}% dari total</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-600">Qty:</span>
                        <span class="font-medium ml-1">{{ number_format($item->qty, 0, ',', '.') }} {{ $item->satuan }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Harga Vendor:</span>
                        <span class="font-medium ml-1">Rp {{ number_format($item->harga_vendor, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-600">Harga Akhir:</span>
                        <span class="font-medium ml-1 text-purple-600">Rp {{ number_format($item->harga_akhir, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="flex justify-between items-center text-xs text-gray-600 mb-1">
                        <span>Kontribusi Modal</span>
                        <span>{{ number_format($persentaseModal, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full transition-all duration-300" style="width: {{ $persentaseModal }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Total Mobile -->
            <div class="bg-green-50 rounded-lg p-4 border border-green-200 mt-4">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-green-800">Total Modal Vendor:</span>
                    <span class="text-lg font-bold text-green-600">Rp {{ number_format($totalModalVendor, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Progress Section -->
@php
    $totalPenawaran = $pembayaran->penawaran->total_penawaran;
    // Gunakan data dari controller yang sudah difilter per vendor
    $totalDibayar = $totalDibayarVendor;
    $sisaBayar = $totalModalVendor - $totalDibayar;
    $persenBayar = $totalModalVendor > 0 ? ($totalDibayar / $totalModalVendor) * 100 : 0;
@endphp

<div class="bg-white rounded-lg shadow-lg mt-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Progress Pembayaran Vendor</h2>
        <p class="text-sm text-gray-600 mt-1">{{ $pembayaran->vendor->nama_vendor ?? 'Vendor' }} - Berdasarkan total modal vendor</p>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-lg font-bold text-green-600">
                    Rp {{ number_format($totalModalVendor, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-500">Total Modal Vendor (Harga Akhir Kalkulasi HPS)</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-bold text-blue-600">
                    Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-500">Total Dibayar</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-bold {{ $sisaBayar > 0 ? 'text-red-600' : 'text-green-600' }}">
                    Rp {{ number_format($sisaBayar, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-500">Sisa Tagihan</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900">{{ number_format($persenBayar, 1) }}%</div>
                <div class="text-sm text-gray-500">Progress</div>
            </div>
        </div>
        
        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
            <div class="text-xs text-blue-700 mb-1">
                <i class="fas fa-info-circle mr-1"></i>
                Referensi: Total penawaran ke klien Rp {{ number_format($totalPenawaran, 0, ',', '.') }}. Modal vendor menggunakan <strong>harga akhir dari Kalkulasi HPS</strong>.
                @if($breakdownBarang && $breakdownBarang->count() > 0)
                <br>Detail breakdown per barang dapat dilihat pada tabel di atas untuk transparansi perhitungan modal vendor.
                @endif
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
            @if($canAccess)
                <a href="{{ route('purchasing.pembayaran.create', $pembayaran->penawaran->proyek->id_proyek) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Pembayaran
                </a>
            @else
                <button disabled
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i>
                    Tambah Pembayaran (Terkunci)
                </button>
            @endif
        @endif
    </div>
</div>

@endsection
