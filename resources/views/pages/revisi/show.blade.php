@extends('layouts.app')

@section('title', 'Detail Revisi - Cyber KATANA')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Revisi</h1>
                <p class="text-gray-600 mt-1">Detail permintaan revisi untuk proyek {{ $revisi->proyek->kode_proyek ?? 'PRJ-' . str_pad($revisi->proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <a href="{{ route('revisi.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Info Revisi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Detail Revisi -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Informasi Revisi
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">ID Revisi:</span>
                    <span class="font-medium text-gray-900">#{{ $revisi->id_revisi }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tipe Revisi:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @switch($revisi->tipe_revisi)
                            @case('proyek') bg-blue-100 text-blue-800 @break
                            @case('hps_penawaran') bg-purple-100 text-purple-800 @break
                            @case('penawaran') bg-green-100 text-green-800 @break
                            @case('penagihan_dinas') bg-yellow-100 text-yellow-800 @break
                            @case('pembayaran') bg-orange-100 text-orange-800 @break
                            @case('pengiriman') bg-red-100 text-red-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        {{ $revisi->tipe_revisi_nama }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @switch($revisi->status)
                            @case('pending') bg-yellow-100 text-yellow-800 @break
                            @case('in_progress') bg-blue-100 text-blue-800 @break
                            @case('completed') bg-green-100 text-green-800 @break
                            @case('rejected') bg-red-100 text-red-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        {{ $revisi->status_nama }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Dibuat Oleh:</span>
                    <span class="font-medium text-gray-900">{{ $revisi->createdBy->nama ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ditangani Oleh:</span>
                    <span class="font-medium text-gray-900">{{ $revisi->handledBy->nama ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Dibuat:</span>
                    <span class="font-medium text-gray-900">{{ $revisi->created_at->format('d M Y H:i') }}</span>
                </div>
                @if($revisi->updated_at != $revisi->created_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Terakhir Update:</span>
                    <span class="font-medium text-gray-900">{{ $revisi->updated_at->format('d M Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Data Proyek -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-project-diagram mr-2 text-green-500"></i>
                Data Proyek
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Kode Proyek:</span>
                    <span class="font-medium text-gray-900">{{ $revisi->proyek->kode_proyek ?? 'PRJ-' . str_pad($revisi->proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Instansi:</span>
                    <span class="font-medium text-gray-900">{{ $revisi->proyek->instansi }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Jenis Pengadaan:</span>
                    <span class="font-medium text-gray-900">{{ $revisi->proyek->jenis_pengadaan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Admin Marketing:</span>
                    <span class="font-medium text-gray-900">{{ $revisi->proyek->adminMarketing->nama ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Admin Purchasing:</span>
                    <span class="font-medium text-gray-900">{{ $revisi->proyek->adminPurchasing->nama ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Keterangan Revisi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-clipboard-list mr-2 text-orange-500"></i>
            Keterangan Revisi
        </h3>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-800 whitespace-pre-wrap">{{ $revisi->keterangan }}</p>
        </div>
    </div>

    @if($revisi->catatan_revisi)
    <!-- Catatan Revisi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-sticky-note mr-2 text-purple-500"></i>
            Catatan dari Penanganan
        </h3>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-800 whitespace-pre-wrap">{{ $revisi->catatan_revisi }}</p>
        </div>
    </div>
    @endif

    <!-- Actions -->
    @php
        $user = auth()->user();
        // Semua role bisa menangani revisi
        $canHandle = true;
    @endphp

    @if($canHandle && $revisi->status === 'pending')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-tools mr-2 text-blue-500"></i>
            Aksi Revisi
        </h3>
        <div class="flex space-x-4">
            <form action="{{ route('revisi.take', $revisi->id_revisi) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-hand-point-right mr-2"></i>
                    Ambil Revisi
                </button>
            </form>
        </div>
    </div>
    @elseif($canHandle && $revisi->status === 'in_progress' && $revisi->handled_by === $user->id_user)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-tools mr-2 text-blue-500"></i>
            Selesaikan Revisi
        </h3>
        
        <!-- Form Selesai -->
        <form action="{{ route('revisi.complete', $revisi->id_revisi) }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-4">
                <label for="catatan_revisi" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Penyelesaian (Opsional)
                </label>
                <textarea id="catatan_revisi" name="catatan_revisi" rows="3" 
                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Berikan catatan tentang penyelesaian revisi..."></textarea>
            </div>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-check mr-2"></i>
                Selesai
            </button>
        </form>
        
        <!-- Form Tolak -->
        <form action="{{ route('revisi.reject', $revisi->id_revisi) }}" method="POST" class="border-t pt-4">
            @csrf
            <div class="mb-4">
                <label for="catatan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea id="catatan_penolakan" name="catatan_revisi" rows="3" required
                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                          placeholder="Berikan alasan kenapa revisi ditolak..."></textarea>
            </div>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    onclick="return confirm('Apakah Anda yakin ingin menolak revisi ini?')">
                <i class="fas fa-times mr-2"></i>
                Tolak
            </button>
        </form>
    </div>
    @endif

    <!-- Data Target (berdasarkan tipe revisi) -->
    @if($targetData)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-database mr-2 text-indigo-500"></i>
            Data Yang Perlu Direvisi
        </h3>
        
        <div class="bg-gray-50 rounded-lg p-4">
            @switch($revisi->tipe_revisi)
                @case('proyek')
                    <p class="text-sm text-gray-600 mb-2">Data proyek yang perlu direvisi:</p>
                    <div class="text-sm">
                        <strong>Kode:</strong> {{ $targetData->kode_proyek }}<br>
                        <strong>Instansi:</strong> {{ $targetData->instansi }}<br>
                        <strong>Jenis:</strong> {{ $targetData->jenis_pengadaan }}
                    </div>
                    @break
                    
                @case('hps_penawaran')
                    <p class="text-sm text-gray-600 mb-2">Data HPS dan Penawaran:</p>
                    <div class="text-sm">
                        <strong>Total HPS:</strong> {{ $targetData['hps']->count() }} item<br>
                        <strong>Total Penawaran:</strong> {{ $targetData['penawaran']->count() }} item
                    </div>
                    @break
                    
                @case('penawaran')
                    <p class="text-sm text-gray-600 mb-2">Data Penawaran:</p>
                    <div class="text-sm">
                        <strong>Total Penawaran:</strong> {{ $targetData->count() }} item
                    </div>
                    @break
                    
                @case('penagihan_dinas')
                    <p class="text-sm text-gray-600 mb-2">Data Penagihan Dinas:</p>
                    <div class="text-sm">
                        <strong>Total Penagihan:</strong> {{ $targetData->count() }} item
                    </div>
                    @break
                    
                @case('pembayaran')
                    <p class="text-sm text-gray-600 mb-2">Data Pembayaran:</p>
                    <div class="text-sm">
                        <strong>Total Pembayaran:</strong> {{ $targetData->count() }} item
                    </div>
                    @break
                    
                @case('pengiriman')
                    <p class="text-sm text-gray-600 mb-2">Data Pengiriman:</p>
                    <div class="text-sm">
                        <strong>Total Pengiriman:</strong> {{ $targetData->count() }} item
                    </div>
                    @break
            @endswitch
        </div>
    </div>
    @endif
</div>
@endsection
