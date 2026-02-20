@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-green-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Pembayaran Approved</h1>
            <p class="text-green-100 text-sm sm:text-base lg:text-lg">Daftar pembayaran yang telah disetujui</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-check-circle text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 px-6">
            <a href="{{ route('keuangan.approval') }}" 
               class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Pending Approval
            </a>
            <a href="{{ route('keuangan.approval.approved') }}" 
               class="py-4 px-1 border-b-2 border-green-500 font-medium text-sm text-green-600">
                Approved
            </a>
            <a href="{{ route('keuangan.approval.rejected') }}" 
               class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Rejected
            </a>
        </nav>
    </div>
</div>

<!-- Approved Payments Table -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Pembayaran Disetujui</h2>
                <p class="text-gray-600 mt-1">{{ $approvedPayments->total() }} pembayaran telah disetujui</p>
            </div>
            <!-- Search Bar -->
            <form method="GET" action="{{ route('keuangan.approval.approved') }}" class="mt-4 md:mt-0">
                <div class="flex items-center space-x-2">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari proyek, instansi, vendor..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                    <a href="{{ route('keuangan.approval.approved') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    
    @if($approvedPayments->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Approval</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($approvedPayments as $pembayaran)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $pembayaran->updated_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->updated_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $pembayaran->penawaran->proyek->nama_barang }}</div>
                        <div class="text-sm text-gray-600">{{ $pembayaran->penawaran->proyek->kode_proyek }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->penawaran->no_penawaran }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $pembayaran->vendor->nama_vendor }}</div>
                        <div class="text-sm text-gray-600">{{ $pembayaran->vendor->jenis_perusahaan }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->vendor->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($pembayaran->jenis_bayar == 'Lunas') bg-green-100 text-green-800
                            @elseif($pembayaran->jenis_bayar == 'DP') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $pembayaran->jenis_bayar }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($pembayaran->nominal_bayar, 2, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $pembayaran->metode_bayar }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            {{ $pembayaran->status_verifikasi }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('keuangan.approval.detail', $pembayaran->id_pembayaran) }}" 
                           class="inline-flex items-center px-3 py-1 border border-blue-300 shadow-sm text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-3 border-t border-gray-200">
        {{ $approvedPayments->appends(request()->query())->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <div class="mx-auto h-12 w-12 text-gray-400">
            <i class="fas fa-check-circle text-4xl"></i>
        </div>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pembayaran disetujui</h3>
        <p class="mt-1 text-sm text-gray-500">Pembayaran yang disetujui akan muncul di sini.</p>
    </div>
    @endif
</div>

<!-- Navigation -->
<div class="flex items-center justify-between pt-6">
    <a href="{{ route('keuangan.approval') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Pending
    </a>
</div>

@endsection
