@extends('layouts.app')

@section('title', 'History Verifikasi Proyek')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">History Verifikasi Proyek</h1>
                <p class="text-gray-600 mt-1">Riwayat semua proyek yang telah diverifikasi oleh superadmin</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.verifikasi-proyek') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Verifikasi
                </a>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-medium">{{ $historyVerifikasi->count() }}</span>
                    <span class="text-purple-100 ml-1">Telah Diverifikasi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex space-x-1" x-data="{ activeTab: 'all' }">
            <button @click="activeTab = 'all'" 
                    :class="activeTab === 'all' ? 'bg-blue-500 text-white' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                <i class="fas fa-list mr-2"></i>Semua
            </button>
            <button @click="activeTab = 'verified'" 
                    :class="activeTab === 'verified' ? 'bg-green-500 text-white' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                <i class="fas fa-check mr-2"></i>Berhasil
            </button>
            <button @click="activeTab = 'rejected'" 
                    :class="activeTab === 'rejected' ? 'bg-red-500 text-white' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>Ditolak
            </button>
        </div>
    </div>

    <!-- History List -->
    @if($historyVerifikasi->isEmpty())
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
        <i class="fas fa-history text-gray-400 text-4xl mb-4"></i>
        <h4 class="text-gray-800 font-medium text-lg">Belum Ada History Verifikasi</h4>
        <p class="text-gray-600 text-sm mt-1">Belum ada proyek yang diverifikasi oleh superadmin.</p>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Verifikasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verifikator</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Verifikasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($historyVerifikasi as $history)
                    <tr class="hover:bg-gray-50 transition-colors duration-200" 
                        x-show="activeTab === 'all' || (activeTab === 'verified' && '{{ $history->status_verifikasi }}' === 'Verified') || (activeTab === 'rejected' && '{{ $history->status_verifikasi }}' === 'Rejected')">
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $history->nama_barang }}</div>
                                <div class="text-sm text-gray-500">{{ $history->no_penawaran }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($history->tanggal)->format('d M Y') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $history->nama_klien }}</div>
                                <div class="text-sm text-gray-500">{{ $history->instansi }}</div>
                                <div class="text-xs text-gray-400">{{ $history->kota_kab }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-green-600">
                                Rp {{ number_format($history->total_penawaran, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($history->status_verifikasi === 'Verified')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Berhasil
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $history->verified_by_name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($history->verified_at)->format('d M Y H:i') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($history->verified_at)->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('superadmin.verifikasi-proyek.detail', $history->id_proyek) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $totalVerified = $historyVerifikasi->where('status_verifikasi', 'Verified')->count();
            $totalRejected = $historyVerifikasi->where('status_verifikasi', 'Rejected')->count();
            $totalValue = $historyVerifikasi->where('status_verifikasi', 'Verified')->sum('total_penawaran');
        @endphp
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-2xl"></i>
                <div>
                    <h4 class="text-green-800 font-bold text-xl">{{ $totalVerified }}</h4>
                    <p class="text-green-700 text-sm">Proyek Berhasil</p>
                </div>
            </div>
        </div>
        
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-times-circle text-red-500 mr-3 text-2xl"></i>
                <div>
                    <h4 class="text-red-800 font-bold text-xl">{{ $totalRejected }}</h4>
                    <p class="text-red-700 text-sm">Proyek Ditolak</p>
                </div>
            </div>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-money-bill-wave text-blue-500 mr-3 text-2xl"></i>
                <div>
                    <h4 class="text-blue-800 font-bold text-lg">Rp {{ number_format($totalValue, 0, ',', '.') }}</h4>
                    <p class="text-blue-700 text-sm">Total Nilai Berhasil</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('historyTabs', () => ({
        activeTab: 'all'
    }))
})
</script>
@endsection
