@extends('layouts.app')

@section('title', 'History Verifikasi Proyek - Cyber KATANA')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">History Verifikasi Proyek</h1>
                <p class="text-gray-600 mt-1">Riwayat semua proyek yang telah diverifikasi</p>
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
        <div class="flex space-x-1">
            <button id="btnAll" onclick="filterHistory('all')" 
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 bg-blue-500 text-white">
                <i class="fas fa-list mr-2"></i>Semua
            </button>
            <button id="btnSelesai" onclick="filterHistory('selesai')" 
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-gray-500 hover:text-gray-700">
                <i class="fas fa-check mr-2"></i>Selesai
            </button>
            <button id="btnGagal" onclick="filterHistory('gagal')" 
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times mr-2"></i>Gagal
            </button>
        </div>
    </div>

    <!-- History List -->
    @if($historyVerifikasi->isEmpty())
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
        <i class="fas fa-history text-gray-400 text-4xl mb-4"></i>
        <h4 class="text-gray-800 font-medium text-lg">Belum Ada History Verifikasi</h4>
        <p class="text-gray-600 text-sm mt-1">Belum ada proyek yang telah diverifikasi.</p>
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
                    <tr class="hover:bg-gray-50 transition-colors duration-200 history-row" 
                        data-status="{{ strtolower($history->status) }}">
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $history->nama_barang }}</div>
                                <div class="text-sm text-gray-500">{{ $history->no_penawaran ?? 'N/A' }}</div>
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
                                Rp {{ number_format($history->total_penawaran ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($history->status === 'Selesai')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Gagal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $verifikator = 'System';
                                if (auth()->user()->role === 'superadmin') {
                                    $verifikator = 'Super Administrator';
                                } elseif (auth()->user()->role === 'admin_marketing' && auth()->user()->jabatan === 'manager_marketing') {
                                    $verifikator = 'Manager Marketing';
                                }
                            @endphp
                            <div class="text-sm text-gray-900">{{ $verifikator }}</div>
                            <div class="text-xs text-gray-500">Authorized Verifier</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($history->updated_at)->format('d M Y H:i') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($history->updated_at)->diffForHumans() }}
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
            $totalSelesai = $historyVerifikasi->where('status', 'Selesai')->count();
            $totalGagal = $historyVerifikasi->where('status', 'Gagal')->count();
            $totalValue = $historyVerifikasi->where('status', 'Selesai')->sum('total_penawaran');
        @endphp
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-2xl"></i>
                <div>
                    <h4 class="text-green-800 font-bold text-xl">{{ $totalSelesai }}</h4>
                    <p class="text-green-700 text-sm">Proyek Selesai</p>
                </div>
            </div>
        </div>
        
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-times-circle text-red-500 mr-3 text-2xl"></i>
                <div>
                    <h4 class="text-red-800 font-bold text-xl">{{ $totalGagal }}</h4>
                    <p class="text-red-700 text-sm">Proyek Gagal</p>
                </div>
            </div>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-money-bill-wave text-blue-500 mr-3 text-2xl"></i>
                <div>
                    <h4 class="text-blue-800 font-bold text-lg">Rp {{ number_format($totalValue, 0, ',', '.') }}</h4>
                    <p class="text-blue-700 text-sm">Total Nilai Selesai</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterHistory(status) {
    // Reset semua tombol
    const buttons = ['btnAll', 'btnSelesai', 'btnGagal'];
    buttons.forEach(btnId => {
        const btn = document.getElementById(btnId);
        btn.classList.remove('bg-blue-500', 'bg-green-500', 'bg-red-500', 'text-white');
        btn.classList.add('text-gray-500', 'hover:text-gray-700');
    });
    
    // Set active button
    let activeBtn = document.getElementById('btnAll');
    let activeColor = 'bg-blue-500';
    
    if (status === 'selesai') {
        activeBtn = document.getElementById('btnSelesai');
        activeColor = 'bg-green-500';
    } else if (status === 'gagal') {
        activeBtn = document.getElementById('btnGagal');
        activeColor = 'bg-red-500';
    }
    
    activeBtn.classList.remove('text-gray-500', 'hover:text-gray-700');
    activeBtn.classList.add(activeColor, 'text-white');
    
    // Filter rows
    const rows = document.querySelectorAll('.history-row');
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        
        if (status === 'all') {
            row.style.display = '';
        } else {
            if (rowStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
    
    // Update counts in summary
    updateSummaryDisplay(status);
}

function updateSummaryDisplay(activeFilter) {
    // Optional: Update summary cards based on active filter
    // This can be implemented later if needed
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    filterHistory('all');
});
</script>
@endsection
