@extends('layouts.app')

@section('content')
@php
    $currentUser = Auth::user();
    $canAccess = $currentUser->role === 'admin_purchasing' && $proyek->id_admin_purchasing == $currentUser->id_user;
    $sisaBayar = $proyek->sisa_bayar ?? 0;
@endphp

<!-- Header Section Enhanced -->
<div class="bg-gradient-to-r from-green-800 to-emerald-900 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-xl mt-4">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <div class="flex items-center mb-3">
                <div class="bg-green-700 rounded-lg p-2 mr-3">
                    <i class="fas fa-history text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold">Riwayat Pembayaran</h1>
                    <p class="text-green-100 text-sm sm:text-base lg:text-lg">
                        Catatan lengkap pembayaran proyek
                    </p>
                </div>
            </div>
            <div class="bg-green-700/30 rounded-lg p-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-green-200">Kode Proyek:</span>
                        <span class="font-semibold ml-2">{{ $proyek->kode_proyek }}</span>
                    </div>
                    <div>
                        <span class="text-green-200">Nama Instansi:</span>
                        <span class="font-semibold ml-2">{{ $proyek->instansi }} - {{ $proyek->kab_kota }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-chart-line text-5xl opacity-20"></i>
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
                        <span class="text-gray-600">Kode Proyek:</span>
                        <span class="font-medium">{{ $proyek->kode_proyek }}</span>
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
                    $totalDibayar = $riwayatPembayaran->where('status_verifikasi', 'Approved')->sum('nominal_bayar');
                    $sisaBayar = $totalModalVendor - $totalDibayar;
                    $persenBayar = $totalModalVendor > 0 ? ($totalDibayar / $totalModalVendor) * 100 : 0;
                @endphp
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Modal Vendor (Harga Akhir Kalkulasi HPS):</span>
                        <span class="font-medium">Rp {{ number_format($totalModalVendor, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 text-sm">Total Penawaran Klien:</span>
                        <span class="font-medium text-sm text-blue-600">
                            Rp {{ number_format((float)$totalPenawaran, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sudah Dibayar:</span>
                        <span class="font-medium text-blue-600">
                            Rp {{ number_format($totalDibayar, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-600">Sisa Tagihan:</span>
                        <span class="font-bold {{ $sisaBayar > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($sisaBayar, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Progress</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Progress pembayaran vendor (berdasarkan harga akhir Kalkulasi HPS):</span>
                        <span class="font-bold text-gray-900">{{ number_format($persenBayar, 1) }}%</span>
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

<!-- Documents Section -->
@if($proyek->penawaranAktif && ($proyek->penawaranAktif->surat_pesanan || $proyek->penawaranAktif->surat_penawaran))
<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Dokumen Proyek</h2>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Surat Pesanan -->
            @if($proyek->penawaranAktif->surat_pesanan)
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3">Surat Pesanan:</h4>
                <div class="border border-gray-200 rounded-lg p-4">
                    @php
                        $fileSuratPesanan = pathinfo($proyek->penawaranAktif->surat_pesanan, PATHINFO_EXTENSION);
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
                    
                    <a href="{{ asset('storage/penawaran/' . $proyek->penawaranAktif->surat_pesanan) }}" 
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 w-full justify-center">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Surat Pesanan
                    </a>
                </div>
            </div>
            @endif
            
            <!-- Surat Penawaran -->
            @if($proyek->penawaranAktif->surat_penawaran)
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3">Surat Penawaran:</h4>
                <div class="border border-gray-200 rounded-lg p-4">
                    @php
                        $fileSuratPenawaran = pathinfo($proyek->penawaranAktif->surat_penawaran, PATHINFO_EXTENSION);
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
                    
                    <a href="{{ asset('storage/penawaran/' . $proyek->penawaranAktif->surat_penawaran) }}" 
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 w-full justify-center">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Surat Penawaran
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- PPN Rekap per Vendor -->
@if(!empty($ppnRekap))
<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="bg-amber-100 rounded-lg p-2">
                <i class="fas fa-percentage text-amber-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Rekap PPN per Vendor</h2>
                <p class="text-sm text-gray-500">
                    Semua vendor beserta status PPN per barang berdasarkan pembayaran terakhir
                </p>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-6">
        @foreach($ppnRekap as $vendorId => $rekap)
        @php
            $hasSnapshot = $rekap['has_snapshot'] ?? false;
        @endphp
        <div class="border {{ $rekap['ada_ppn'] ? 'border-amber-200' : 'border-gray-200' }} rounded-xl overflow-hidden">

            <!-- Vendor header -->
            <div class="flex items-center justify-between px-5 py-3
                {{ $rekap['ada_ppn'] ? 'bg-amber-50 border-b border-amber-200' : 'bg-gray-50 border-b border-gray-200' }}">
                <div class="flex items-center gap-2">
                    <i class="fas fa-store text-gray-500 text-sm"></i>
                    <span class="font-semibold text-gray-800">{{ $rekap['vendor_nama'] }}</span>
                    @if($rekap['ada_ppn'])
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-300">
                            <i class="fas fa-percentage mr-1"></i> Ada PPN
                        </span>
                    @elseif($hasSnapshot)
                        {{-- Punya snapshot tapi semua item non-PPN --}}
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-300">
                            <i class="fas fa-check mr-1"></i> Non-PPN
                        </span>
                    @else
                        {{-- Belum pernah input ppn_data —– tampilkan data dari Kalkulasi HPS --}}
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 border border-gray-300">
                            <i class="fas fa-question-circle mr-1"></i> Belum dikonfigurasi
                        </span>
                    @endif
                </div>
                <div class="text-xs text-gray-400">
                    @if($hasSnapshot)
                        Snapshot: pembayaran #{{ $rekap['snapshot_id'] }}
                        &nbsp;|&nbsp;
                        {{ $rekap['snapshot_tanggal'] instanceof \Carbon\Carbon
                            ? $rekap['snapshot_tanggal']->format('d/m/Y')
                            : $rekap['snapshot_tanggal'] }}
                    @endif
                </div>
            </div>

            <!-- Items table -->
            @if(!empty($rekap['items']))
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">PPN %</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">DPP (Sebelum PPN)</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Nominal PPN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($rekap['items'] as $item)
                        <tr class="{{ $item['ada_ppn'] ? 'bg-amber-50/40' : '' }} hover:bg-gray-50 transition">
                            <td class="px-4 py-2.5 text-gray-800 font-medium">
                                {{ $item['nama_barang'] ?? '-' }}
                            </td>
                            <td class="px-4 py-2.5 text-right text-gray-700">
                                Rp {{ number_format($item['harga_total'] ?? 0, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                @if($item['ada_ppn'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        {{ $item['persen_ppn'] ?? 11 }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400">
                                        Non-PPN
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-right text-gray-600">
                                @if($item['ada_ppn'])
                                    Rp {{ number_format($item['harga_sebelum_ppn'] ?? 0, 2, ',', '.') }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-right font-semibold {{ $item['ada_ppn'] ? 'text-amber-700' : 'text-gray-400' }}">
                                @if($item['ada_ppn'])
                                    Rp {{ number_format($item['nominal_ppn'] ?? 0, 2, ',', '.') }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if($rekap['ada_ppn'])
                    <tfoot class="bg-amber-50 border-t-2 border-amber-200">
                        <tr>
                            <td colspan="3" class="px-4 py-2.5 text-right text-sm font-semibold text-gray-700">
                                Total (dari pembayaran Approved):
                            </td>
                            <td class="px-4 py-2.5 text-right text-sm font-bold text-gray-800">
                                Rp {{ number_format($rekap['total_sebelum_ppn'], 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-2.5 text-right text-sm font-bold text-amber-700">
                                Rp {{ number_format($rekap['total_ppn_approved'], 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @else
            <div class="px-5 py-4 text-sm text-gray-400 italic">Tidak ada data item.</div>
            @endif

        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Payment History -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Riwayat Pembayaran</h2>
            @if($sisaBayar > 0)
                @if($canAccess)
                    <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
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
    
    <div class="overflow-x-auto">
        @if(count($riwayatPembayaran) > 0)
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PPN</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail PPN</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($riwayatPembayaran as $pembayaran)
                @php
                    $ppnRow = $pembayaran->ppn_data;
                    $totalPpnRow = floatval($ppnRow['total_ppn'] ?? 0);
                    $adaPpnRow   = $totalPpnRow > 0;
                    $ppnItemsRow = $ppnRow['items'] ?? [];
                    $rowId = 'ppn-detail-' . $pembayaran->id_pembayaran;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $pembayaran->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-800">{{ $pembayaran->vendor->nama_vendor ?? '-' }}</div>
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
                            Rp {{ number_format($pembayaran->nominal_bayar, 2, ',', '.') }}
                        </div>
                        @php
                            $persenNominal = $totalModalVendor > 0 ? ($pembayaran->nominal_bayar / $totalModalVendor) * 100 : 0;
                        @endphp
                        <div class="text-xs text-gray-500">{{ number_format($persenNominal, 1) }}% dari total</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($adaPpnRow)
                            <div class="text-xs font-semibold text-amber-700">
                                Rp {{ number_format($totalPpnRow, 2, ',', '.') }}
                            </div>
                            @php
                                $totalSebelumPpnRow = floatval($ppnRow['total_sebelum_ppn'] ?? 0);
                            @endphp
                            <div class="text-xs text-gray-400">
                                DPP: Rp {{ number_format($totalSebelumPpnRow, 2, ',', '.') }}
                            </div>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
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
                        @php
                            $buktiArray = $pembayaran->bukti_bayar_array;
                        @endphp
                        @if(!empty($buktiArray))
                            @foreach($buktiArray as $idx => $buktiFile)
                            <a href="{{ asset('storage/' . $buktiFile) }}"
                               target="_blank"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs mr-1">
                                <i class="fas fa-file-image mr-1"></i>
                                Bukti{{ count($buktiArray) > 1 ? ' ' . ($idx+1) : '' }}
                            </a>
                            @endforeach
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
                    <td class="px-6 py-4">
                        @if(!empty($ppnItemsRow))
                        <button type="button"
                                onclick="togglePpnDetail('{{ $rowId }}')"
                                class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-md border
                                    {{ $adaPpnRow ? 'border-amber-300 text-amber-700 bg-amber-50 hover:bg-amber-100' : 'border-gray-300 text-gray-500 bg-gray-50 hover:bg-gray-100' }}">
                            <i class="fas fa-list mr-1"></i>
                            {{ count($ppnItemsRow) }} item
                        </button>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
                {{-- Expandable PPN detail row --}}
                @if(!empty($ppnItemsRow))
                <tr id="{{ $rowId }}" class="hidden bg-amber-50/60">
                    <td colspan="10" class="px-6 py-0">
                        <div class="py-3">
                            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-2">
                                <i class="fas fa-percentage mr-1"></i>
                                Detail PPN — Pembayaran #{{ $pembayaran->id_pembayaran }}
                                ({{ $pembayaran->tanggal_bayar->format('d/m/Y') }})
                            </p>
                            <table class="w-full text-xs border border-amber-200 rounded-lg overflow-hidden">
                                <thead class="bg-amber-100">
                                    <tr>
                                        <th class="px-3 py-1.5 text-left font-medium text-amber-800">Nama Barang</th>
                                        <th class="px-3 py-1.5 text-right font-medium text-amber-800">Harga (inc. PPN)</th>
                                        <th class="px-3 py-1.5 text-center font-medium text-amber-800">PPN %</th>
                                        <th class="px-3 py-1.5 text-right font-medium text-amber-800">DPP (Sebelum PPN)</th>
                                        <th class="px-3 py-1.5 text-right font-medium text-amber-800">Nominal PPN</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-amber-100 bg-white">
                                    @foreach($ppnItemsRow as $pItem)
                                    <tr class="{{ $pItem['ada_ppn'] ? '' : 'opacity-60' }}">
                                        <td class="px-3 py-1.5 text-gray-800">{{ $pItem['nama_barang'] ?? '-' }}</td>
                                        <td class="px-3 py-1.5 text-right text-gray-700">
                                            Rp {{ number_format($pItem['harga_total'] ?? 0, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-1.5 text-center">
                                            @if($pItem['ada_ppn'])
                                                <span class="bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-semibold">
                                                    {{ $pItem['persen_ppn'] ?? 11 }}%
                                                </span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-1.5 text-right text-gray-600">
                                            @if($pItem['ada_ppn'])
                                                Rp {{ number_format($pItem['harga_sebelum_ppn'] ?? 0, 2, ',', '.') }}
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-1.5 text-right font-semibold {{ $pItem['ada_ppn'] ? 'text-amber-700' : 'text-gray-400' }}">
                                            @if($pItem['ada_ppn'])
                                                Rp {{ number_format($pItem['nominal_ppn'] ?? 0, 2, ',', '.') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if($adaPpnRow)
                                <tfoot class="bg-amber-100 border-t border-amber-300">
                                    <tr>
                                        <td colspan="3" class="px-3 py-1.5 text-right font-bold text-amber-800">
                                            Total PPN pembayaran ini:
                                        </td>
                                        <td class="px-3 py-1.5 text-right font-bold text-gray-700">
                                            Rp {{ number_format(floatval($ppnRow['total_sebelum_ppn'] ?? 0), 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-1.5 text-right font-bold text-amber-700">
                                            Rp {{ number_format($totalPpnRow, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        
        <!-- Summary Row -->
        <div class="bg-gray-50 px-6 py-3 border-t">
            <div class="flex justify-between items-center flex-wrap gap-2">
                <div class="text-sm font-medium text-gray-700">
                    Total {{ count($riwayatPembayaran) }} transaksi pembayaran
                </div>
                <div class="flex items-center gap-4">
                    @php
                        $grandTotalPpn = $riwayatPembayaran->where('status_verifikasi', 'Approved')->sum(fn($p) => floatval($p->ppn_data['total_ppn'] ?? 0));
                    @endphp
                    @if($grandTotalPpn > 0)
                    <div class="text-sm font-semibold text-amber-700">
                        <i class="fas fa-percentage mr-1 opacity-70"></i>
                        Total PPN (Approved): Rp {{ number_format($grandTotalPpn, 2, ',', '.') }}
                    </div>
                    @endif
                    <div class="text-sm font-bold text-gray-900">
                        Total Dibayar: Rp {{ number_format($totalDibayar, 2, ',', '.') }}
                    </div>
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
                @if($canAccess)
                    <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pembayaran
                    </a>
                @else
                    <button disabled
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                        <i class="fas fa-lock mr-2"></i>
                        Tambah Pembayaran (Terkunci)
                    </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function togglePpnDetail(rowId) {
    const row = document.getElementById(rowId);
    if (!row) return;
    row.classList.toggle('hidden');
}
</script>

<!-- Action Buttons -->
<div class="flex items-center justify-between pt-6">
    <a href="{{ route('purchasing.pembayaran') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar
    </a>
    
    <div class="flex space-x-3">
        @if($sisaBayar > 0)
            @if($canAccess)
                <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}" 
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
