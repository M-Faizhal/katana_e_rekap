@extends('layouts.app')

@section('content')

<!-- Header Section -->
<div class="bg-green-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Riwayat Pembayaran</h1>
            <p class="text-green-100 text-sm sm:text-base lg:text-lg">{{ $proyek->nama_barang }} - {{ $proyek->nama_klien }}</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-history text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Project Summary -->
<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Ringkasan Proyek</h2>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Proyek</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Klien:</span>
                        <span class="font-medium">{{ $proyek->nama_klien }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Instansi:</span>
                        <span class="font-medium">{{ $proyek->instansi }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. Penawaran:</span>
                        <span class="font-medium">{{ $proyek->penawaranAktif->no_penawaran }}</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status Keuangan</h3>
                @php
                    $totalPenawaran = $proyek->penawaranAktif->total_penawaran;
                    $totalDibayar = $riwayatPembayaran->where('status_verifikasi', '!=', 'Ditolak')->sum('nominal_bayar');
                    $sisaBayar = $totalPenawaran - $totalDibayar;
                    $persenBayar = $totalPenawaran > 0 ? ($totalDibayar / $totalPenawaran) * 100 : 0;
                @endphp
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Penawaran:</span>
                        <span class="font-bold text-green-600">
                            Rp {{ number_format($totalPenawaran, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sudah Dibayar:</span>
                        <span class="font-medium text-blue-600">
                            Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-600">Sisa Tagihan:</span>
                        <span class="font-bold {{ $sisaBayar > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($sisaBayar, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Progress</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span>Progress Pembayaran</span>
                        <span class="font-medium">{{ number_format($persenBayar, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: {{ $persenBayar }}%"></div>
                    </div>
                    
                    @if($sisaBayar <= 0)
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="font-medium">Pembayaran Lunas</span>
                    </div>
                    @else
                    <div class="flex items-center text-orange-600">
                        <i class="fas fa-clock mr-2"></i>
                        <span class="font-medium">Belum Lunas</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment History -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Riwayat Pembayaran</h2>
            @if($sisaBayar > 0)
            <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Tambah Pembayaran
            </a>
            @endif
        </div>
    </div>
    
    <div class="overflow-x-auto">
        @if($riwayatPembayaran->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($riwayatPembayaran as $pembayaran)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($pembayaran->jenis_bayar == 'Lunas') bg-green-100 text-green-800
                            @elseif($pembayaran->jenis_bayar == 'DP') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $pembayaran->jenis_bayar }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}
                        </div>
                        @php
                            $persenNominal = $totalPenawaran > 0 ? ($pembayaran->nominal_bayar / $totalPenawaran) * 100 : 0;
                        @endphp
                        <div class="text-xs text-gray-500">{{ number_format($persenNominal, 1) }}% dari total</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $pembayaran->metode_bayar }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($pembayaran->status_verifikasi == 'Pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-hourglass-half mr-1"></i>
                            Pending
                        </span>
                        @elseif($pembayaran->status_verifikasi == 'Approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Approved
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>
                            Ditolak
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($pembayaran->bukti_bayar)
                        <a href="{{ Storage::url($pembayaran->bukti_bayar) }}" 
                           target="_blank"
                           class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-file-image mr-1"></i>
                            <span class="text-sm">Lihat Bukti</span>
                        </a>
                        @else
                        <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($pembayaran->catatan)
                        <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $pembayaran->catatan }}">
                            {{ $pembayaran->catatan }}
                        </div>
                        @else
                        <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Summary Row -->
        <div class="bg-gray-50 px-6 py-3 border-t">
            <div class="flex justify-between items-center">
                <div class="text-sm font-medium text-gray-700">
                    Total {{ $riwayatPembayaran->count() }} transaksi pembayaran
                </div>
                <div class="text-sm font-bold text-gray-900">
                    Total Dibayar: Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <i class="fas fa-receipt text-4xl"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada riwayat pembayaran</h3>
            <p class="mt-1 text-sm text-gray-500">Tambahkan pembayaran pertama untuk proyek ini.</p>
            <div class="mt-6">
                <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Pembayaran
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Action Buttons -->
<div class="flex items-center justify-between pt-6">
    <a href="{{ route('purchasing.pembayaran') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar
    </a>
    
    <div class="flex space-x-3">
        @if($sisaBayar > 0)
        <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pembayaran
        </a>
        @endif
    </div>
</div>

@endsection
