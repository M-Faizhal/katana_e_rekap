@extends('layouts.app')

@section('content')

<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Pembayaran Purchasing</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola pembayaran proyek yang sudah di-ACC klien</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-credit-card text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
    
    <!-- Info Flow -->
    <div class="mt-4 p-3 bg-red-700 rounded-lg">
        <div class="flex items-center gap-2 text-sm mb-2">
            <i class="fas fa-info-circle"></i>
            <span class="font-medium">Alur:</span>
            <span class="flex items-center gap-1">
                Marketing → Penawaran → ACC Klien → 
                <span class="bg-red-600 px-2 py-1 rounded text-xs font-bold">Purchasing (Anda di sini)</span>
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
            <div class="flex items-center gap-2">
                <i class="fas fa-clock text-yellow-300"></i>
                <span><strong>DP Dulu:</strong> Input DP → Verifikasi → Input Pelunasan</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-300"></i>
                <span><strong>Langsung Lunas:</strong> Input Pembayaran Full → Selesai</span>
            </div>
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
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <i class="fas fa-clock text-blue-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Menunggu Pembayaran</p>
                <p class="text-lg font-semibold">{{ $proyekPerluBayar->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
                <i class="fas fa-hourglass-half text-yellow-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Pending Verifikasi</p>
                <p class="text-lg font-semibold">
                    {{ $proyekPerluBayar->sum(function($proyek) { 
                        return $proyek->pembayaran->where('status_verifikasi', 'Pending')->count(); 
                    }) }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Terverifikasi</p>
                <p class="text-lg font-semibold">
                    {{ $proyekPerluBayar->sum(function($proyek) { 
                        return $proyek->pembayaran->where('status_verifikasi', 'Approved')->count(); 
                    }) }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
                <i class="fas fa-times-circle text-red-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Ditolak</p>
                <p class="text-lg font-semibold">
                    {{ $proyekPerluBayar->sum(function($proyek) { 
                        return $proyek->pembayaran->where('status_verifikasi', 'Ditolak')->count(); 
                    }) }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Projects List -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Proyek Perlu Pembayaran</h2>
        <p class="text-gray-600 mt-1">Daftar proyek yang sudah di-ACC dan menunggu pembayaran dari klien</p>
    </div>
    
    <div class="overflow-x-auto">
        @if($proyekPerluBayar->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penawaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Bayar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($proyekPerluBayar as $proyek)
                @php
                    $totalDibayar = $proyek->pembayaran->where('status_verifikasi', '!=', 'Ditolak')->sum('nominal_bayar');
                    $sisaBayar = $proyek->penawaranAktif->total_penawaran - $totalDibayar;
                    $persenBayar = $proyek->penawaranAktif->total_penawaran > 0 ? 
                        ($totalDibayar / $proyek->penawaranAktif->total_penawaran) * 100 : 0;
                @endphp
                
                @if($sisaBayar > 0)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $proyek->nama_barang }}</div>
                            <div class="text-sm text-gray-500">{{ $proyek->instansi }} - {{ $proyek->kota_kab }}</div>
                            <div class="text-xs text-gray-400">No. Penawaran: {{ $proyek->penawaranAktif->no_penawaran }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $proyek->nama_klien }}</div>
                        <div class="text-sm text-gray-500">{{ $proyek->kontak_klien }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($proyek->penawaranAktif->total_penawaran, 0, ',', '.') }}
                        </div>
                        @if($totalDibayar > 0)
                        <div class="text-xs text-gray-500">
                            Dibayar: Rp {{ number_format($totalDibayar, 0, ',', '.') }} ({{ number_format($persenBayar, 1) }}%)
                        </div>
                        <div class="text-xs text-orange-600">
                            Sisa: Rp {{ number_format($sisaBayar, 0, ',', '.') }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($sisaBayar <= 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Lunas
                            </span>
                        @elseif($totalDibayar > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>
                                Cicilan ({{ number_format($persenBayar, 0) }}%)
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Belum Bayar
                            </span>
                        @endif
                        
                        @php
                            $pendingCount = $proyek->pembayaran->where('status_verifikasi', 'Pending')->count();
                            $ditolakCount = $proyek->pembayaran->where('status_verifikasi', 'Ditolak')->count();
                        @endphp
                        
                        @if($pendingCount > 0)
                        <div class="text-xs text-yellow-600 mt-1">
                            <i class="fas fa-hourglass-half"></i> {{ $pendingCount }} pending
                        </div>
                        @endif
                        
                        @if($ditolakCount > 0)
                        <div class="text-xs text-red-600 mt-1">
                            <i class="fas fa-exclamation-triangle"></i> {{ $ditolakCount }} ditolak
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            @if($sisaBayar > 0)
                            <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
                               class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus mr-1"></i>
                                Input Bayar
                            </a>
                            @endif
                            
                            @if($proyek->pembayaran->count() > 0)
                            <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}" 
                               class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-history mr-1"></i>
                                Riwayat
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <i class="fas fa-credit-card text-4xl"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada proyek yang perlu pembayaran</h3>
            <p class="mt-1 text-sm text-gray-500">Semua proyek sudah dalam tahap selanjutnya atau belum ada yang di-ACC.</p>
        </div>
        @endif
    </div>
    
    @if($proyekPerluBayar->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $proyekPerluBayar->links() }}
    </div>
    @endif
</div>

<!-- All Payments Section -->
<div class="bg-white rounded-lg shadow-lg mt-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Semua Pembayaran</h2>
        <p class="text-gray-600 mt-1">Daftar lengkap pembayaran dengan semua status (Pending, Approved, Ditolak)</p>
    </div>
    
    <div class="overflow-x-auto">
        @if($semuaPembayaran->count() > 0)
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
                @foreach($semuaPembayaran as $pembayaran)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $pembayaran->penawaran->proyek->nama_barang }}</div>
                        <div class="text-sm text-gray-500">{{ $pembayaran->penawaran->proyek->instansi }}</div>
                        <div class="text-xs text-gray-400">No. {{ $pembayaran->penawaran->no_penawaran }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $pembayaran->penawaran->proyek->nama_klien }}</div>
                        <div class="text-sm text-gray-500">{{ $pembayaran->penawaran->proyek->kontak_klien }}</div>
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
                            $persenNominal = $pembayaran->penawaran->total_penawaran > 0 ? 
                                ($pembayaran->nominal_bayar / $pembayaran->penawaran->total_penawaran) * 100 : 0;
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
                        
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $pembayaran->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('purchasing.pembayaran.show', $pembayaran->id_pembayaran) }}" 
                               class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
                            
                            @if($pembayaran->bukti_bayar)
                            <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" 
                               target="_blank"
                               class="inline-flex items-center px-2 py-1 border border-blue-300 text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100">
                                <i class="fas fa-file-image mr-1"></i>
                                Bukti
                            </a>
                            @endif
                            
                            @if($pembayaran->status_verifikasi == 'Pending')
                            <a href="{{ route('purchasing.pembayaran.edit', $pembayaran->id_pembayaran) }}" 
                               class="inline-flex items-center px-2 py-1 border border-yellow-300 text-xs leading-4 font-medium rounded text-yellow-700 bg-yellow-50 hover:bg-yellow-100">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>
                            
                            <form action="{{ route('purchasing.pembayaran.destroy', $pembayaran->id_pembayaran) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini? File bukti pembayaran juga akan dihapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-2 py-1 border border-red-300 text-xs leading-4 font-medium rounded text-red-700 bg-red-50 hover:bg-red-100">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Summary Footer -->
        <div class="bg-gray-50 px-6 py-3 border-t">
            <div class="flex justify-between items-center text-sm">
                <div class="flex space-x-6">
                    <span class="text-gray-700">
                        <i class="fas fa-hourglass-half text-yellow-600 mr-1"></i>
                        Pending: {{ $semuaPembayaran->where('status_verifikasi', 'Pending')->count() }}
                    </span>
                    <span class="text-gray-700">
                        <i class="fas fa-check-circle text-green-600 mr-1"></i>
                        Approved: {{ $semuaPembayaran->where('status_verifikasi', 'Approved')->count() }}
                    </span>
                    <span class="text-gray-700">
                        <i class="fas fa-times-circle text-red-600 mr-1"></i>
                        Ditolak: {{ $semuaPembayaran->where('status_verifikasi', 'Ditolak')->count() }}
                    </span>
                </div>
                <div class="font-medium text-gray-900">
                    Total: {{ $semuaPembayaran->count() }} pembayaran
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <i class="fas fa-receipt text-4xl"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pembayaran</h3>
            <p class="mt-1 text-sm text-gray-500">Pembayaran akan muncul setelah admin purchasing menginput data.</p>
        </div>
        @endif
    </div>
    
    @if($semuaPembayaran->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $semuaPembayaran->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection
