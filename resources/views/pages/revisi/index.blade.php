@extends('layouts.app')

@section('title', 'Manajemen Revisi - Cyber KATANA')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Revisi</h1>
                <p class="text-gray-600 mt-1">Kelola permintaan revisi untuk berbagai komponen proyek</p>
            </div>
        </div>
    </div>

   

    <!-- Filter dan Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Menunggu</p>
                    <p class="text-2xl font-bold">{{ $revisi->where('status', 'pending')->count() }}</p>
                </div>
                <i class="fas fa-clock text-3xl text-yellow-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Sedang Dikerjakan</p>
                    <p class="text-2xl font-bold">{{ $revisi->where('status', 'in_progress')->count() }}</p>
                </div>
                <i class="fas fa-cog text-3xl text-blue-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Selesai</p>
                    <p class="text-2xl font-bold">{{ $revisi->where('status', 'completed')->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl text-green-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-red-400 to-red-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Ditolak</p>
                    <p class="text-2xl font-bold">{{ $revisi->where('status', 'rejected')->count() }}</p>
                </div>
                <i class="fas fa-times-circle text-3xl text-red-200"></i>
            </div>
        </div>
    </div>

     <!-- Search dan Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('revisi.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="ID Proyek / Yang Mengerjakan"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Filter Tipe Revisi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Revisi</label>
                    <select name="tipe_revisi" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tipe</option>
                        <option value="proyek" {{ request('tipe_revisi') == 'proyek' ? 'selected' : '' }}>Proyek</option>
                        <option value="hps_penawaran" {{ request('tipe_revisi') == 'hps_penawaran' ? 'selected' : '' }}>HPS & Penawaran</option>
                        <option value="penawaran" {{ request('tipe_revisi') == 'penawaran' ? 'selected' : '' }}>Penawaran</option>
                        <option value="penagihan_dinas" {{ request('tipe_revisi') == 'penagihan_dinas' ? 'selected' : '' }}>Penagihan Dinas</option>
                        <option value="pembayaran" {{ request('tipe_revisi') == 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                        <option value="pengiriman" {{ request('tipe_revisi') == 'pengiriman' ? 'selected' : '' }}>Pengiriman</option>
                    </select>
                </div>

                <!-- Filter Yang Mengerjakan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Yang Mengerjakan</label>
                    <select name="handled_by" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua</option>
                        @foreach(\App\Models\User::orderBy('nama')->get() as $user)
                            <option value="{{ $user->id_user }}" {{ request('handled_by') == $user->id_user ? 'selected' : '' }}>
                                {{ $user->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('revisi.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Reset
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Revisi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Daftar Revisi</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Revisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ditangani Oleh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($revisi as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $item->proyek->kode_proyek ?? 'PRJ-' . str_pad($item->proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $item->proyek->instansi }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @switch($item->tipe_revisi)
                                    @case('proyek') bg-blue-100 text-blue-800 @break
                                    @case('hps_penawaran') bg-purple-100 text-purple-800 @break
                                    @case('penawaran') bg-green-100 text-green-800 @break
                                    @case('penagihan_dinas') bg-yellow-100 text-yellow-800 @break
                                    @case('pembayaran') bg-orange-100 text-orange-800 @break
                                    @case('pengiriman') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ $item->tipe_revisi_nama }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($item->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('in_progress') bg-blue-100 text-blue-800 @break
                                    @case('completed') bg-green-100 text-green-800 @break
                                    @case('rejected') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ $item->status_nama }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->handledBy->nama ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('revisi.show', $item->id_revisi) }}" 
                               class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center py-8">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada revisi</p>
                                <p class="text-sm">Revisi akan muncul di sini ketika dibuat</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($revisi->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $revisi->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Auto refresh setiap 30 detik untuk update status revisi
setInterval(function() {
    // Hanya refresh jika tidak ada modal yang terbuka
    if (!document.querySelector('.modal.show')) {
        location.reload();
    }
}, 30000);
</script>
@endpush
@endsection
