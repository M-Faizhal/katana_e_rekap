@extends('layouts.app')

@section('title', 'Verifikasi Proyek - Cyber KATANA')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Verifikasi Proyek</h1>
                <p class="text-gray-600 mt-1">Verifikasi proyek yang telah sampai ke pelanggan untuk menyelesaikan status proyek</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.verifikasi-proyek.history') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-history mr-2"></i>
                    History Verifikasi
                </a>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    <span class="font-medium">{{ $proyekVerifikasi->count() }}</span>
                    <span class="text-blue-100 ml-1">Menunggu Verifikasi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Info -->
    @if($proyekVerifikasi->isEmpty())
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <div>
                <h4 class="text-green-800 font-medium">Tidak Ada Proyek yang Menunggu Verifikasi</h4>
                <p class="text-green-700 text-sm mt-1">Semua proyek telah diverifikasi atau belum ada yang sampai ke tahap pengiriman.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Proyek List -->
    @if($proyekVerifikasi->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengiriman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembayaran Dinas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokumentasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($proyekVerifikasi as $proyek)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $proyek->nama_barang }}</div>
                                <div class="text-sm text-gray-500">{{ $proyek->kode_proyek ?? 'PRJ-' . str_pad($proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($proyek->tanggal)->format('d M Y') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $proyek->nama_klien }}</div>
                                <div class="text-sm text-gray-500">{{ $proyek->instansi }}</div>
                                <div class="text-xs text-gray-400">{{ $proyek->kab_kota }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                @php
                                    $pengirimanAll = collect();
                                    if ($proyek->semuaPenawaran) {
                                        foreach ($proyek->semuaPenawaran as $penawaran) {
                                            if ($penawaran && $penawaran->pengiriman) {
                                                $pengirimanAll = $pengirimanAll->merge($penawaran->pengiriman);
                                            }
                                        }
                                    }
                                    $pengirimanCount = $pengirimanAll->count();
                                    $pengirimanSampai = $pengirimanAll->whereIn('status_verifikasi', ['Verified', 'Sampai_Tujuan'])->count();
                                @endphp
                                <div class="text-sm font-medium text-gray-900">{{ $pengirimanSampai }}/{{ $pengirimanCount }} Sudah Sampai</div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-truck mr-1"></i>
                                    @if($pengirimanAll->isNotEmpty())
                                        {{ \Carbon\Carbon::parse($pengirimanAll->first()->tanggal_kirim)->format('d M Y') }}
                                    @else
                                        Belum ada pengiriman
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $penagihanDinas = $proyek->penagihanDinas->first();
                                $statusPembayaran = $penagihanDinas->status_pembayaran ?? 'belum_bayar';
                                $statusColor = [
                                    'dp' => 'bg-yellow-100 text-yellow-800',
                                    'lunas' => 'bg-green-100 text-green-800',
                                    'belum_bayar' => 'bg-red-100 text-red-800'
                                ][$statusPembayaran] ?? 'bg-gray-100 text-gray-800';
                                
                                $statusLabel = [
                                    'dp' => 'DP',
                                    'lunas' => 'Lunas',
                                    'belum_bayar' => 'Belum Bayar'
                                ][$statusPembayaran] ?? 'Unknown';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                @if($statusPembayaran === 'lunas')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @elseif($statusPembayaran === 'dp')
                                    <i class="fas fa-clock mr-1"></i>
                                @else
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                @endif
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                @php
                                    $docs = [];
                                    foreach($pengirimanAll as $pengiriman) {
                                        $docs = array_merge($docs, [
                                            $pengiriman->foto_berangkat,
                                            $pengiriman->foto_perjalanan,
                                            $pengiriman->foto_sampai,
                                            $pengiriman->tanda_terima,
                                            $pengiriman->file_surat_jalan
                                        ]);
                                    }
                                    $docs = array_filter($docs);
                                    $docCount = count($docs);
                                @endphp
                                
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    {{ $docCount }} Dokumen
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('superadmin.verifikasi-proyek.detail', $proyek->id_proyek) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <i class="fas fa-eye mr-2"></i>
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Info Panel -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                <div>
                    <h4 class="text-blue-800 font-medium">Status Pengiriman</h4>
                    <p class="text-blue-700 text-sm mt-1">Proyek dengan status "Sampai Tujuan" menunggu verifikasi final Anda.</p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-check-double text-green-500 mr-3"></i>
                <div>
                    <h4 class="text-green-800 font-medium">Verifikasi Selesai</h4>
                    <p class="text-green-700 text-sm mt-1">Proyek akan berstatus "Selesai" setelah diverifikasi.</p>
                </div>
            </div>
        </div>
        
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                <div>
                    <h4 class="text-red-800 font-medium">Verifikasi Gagal</h4>
                    <p class="text-red-700 text-sm mt-1">Proyek akan berstatus "Gagal" jika ditolak.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
