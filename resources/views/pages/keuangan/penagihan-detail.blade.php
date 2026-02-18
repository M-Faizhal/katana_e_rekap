@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Detail Penagihan</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">{{ $penagihanDinas->nomor_invoice }}</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-file-invoice-dollar text-3xl sm:text-4xl lg:text-6xl text-red-200"></i>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="flex flex-wrap items-center justify-between mb-6">
    <a href="{{ route('penagihan-dinas.index') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
    
    <div class="flex space-x-2">
        @if($penagihanDinas->status_pembayaran === 'dp')
        <a href="{{ route('penagihan-dinas.show-pelunasan', $penagihanDinas->id) }}"
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
            <i class="fas fa-money-check-alt mr-2"></i>
            Tambah Pelunasan
        </a>
        @endif
        
        <a href="{{ route('penagihan-dinas.edit', $penagihanDinas->id) }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
            <i class="fas fa-edit mr-2"></i>
            Edit
        </a>
        
        <a href="{{ route('penagihan-dinas.history', $penagihanDinas->id) }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            <i class="fas fa-history mr-2"></i>
            History
        </a>
        

    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Penagihan -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-file-invoice text-red-500 mr-2"></i>
                    Informasi Penagihan
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Invoice</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $penagihanDinas->nomor_invoice }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($penagihanDinas->status_pembayaran === 'belum_bayar') bg-yellow-100 text-yellow-800
                                @elseif($penagihanDinas->status_pembayaran === 'dp') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                @if($penagihanDinas->status_pembayaran === 'belum_bayar')
                                    <i class="fas fa-clock mr-1"></i>
                                @elseif($penagihanDinas->status_pembayaran === 'dp')
                                    <i class="fas fa-hand-holding-usd mr-1"></i>
                                @else
                                    <i class="fas fa-check-circle mr-1"></i>
                                @endif
                                {{ ucfirst(str_replace('_', ' ', $penagihanDinas->status_pembayaran)) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</label>
                            <p class="text-xl font-bold text-green-600">Rp {{ number_format((float)$penagihanDinas->proyek->harga_total ?? 0, 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</label>
                            <p class="text-sm text-gray-900">{{ $penagihanDinas->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Jatuh Tempo</label>
                            <p class="text-lg text-gray-900">{{ \Carbon\Carbon::parse($penagihanDinas->tanggal_jatuh_tempo)->format('d M Y') }}</p>
                        </div>
                        @php
                            $totalBayar = $penagihanDinas->buktiPembayaran->sum('jumlah_bayar');
                            $sisaPembayaran = ($penagihanDinas->proyek->harga_total ?? 0) - $totalBayar;
                        @endphp
                        @if($penagihanDinas->status_pembayaran === 'dp')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah DP ({{ $penagihanDinas->persentase_dp }}%)</label>
                            <p class="text-lg font-semibold text-green-600">Rp {{ number_format((float)$penagihanDinas->jumlah_dp, 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Pembayaran</label>
                            <p class="text-lg font-semibold text-red-600">Rp {{ number_format($sisaPembayaran, 2, ',', '.') }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Total Terbayar</label>
                            <p class="text-lg font-semibold text-blue-600">Rp {{ number_format((float)$totalBayar, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @if($penagihanDinas->keterangan)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-500 mb-2">Keterangan</label>
                    <p class="text-gray-900">{{ $penagihanDinas->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Informasi Proyek -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-project-diagram text-blue-500 mr-2"></i>
                    Informasi Proyek
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Proyek</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $penagihanDinas->proyek->kode_proyek ?? 'PRJ-' . str_pad($penagihanDinas->proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</label>
                            <p class="text-sm font-medium text-gray-900">{{ $penagihanDinas->proyek->instansi }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</label>
                            <p class="text-sm text-gray-900">{{ $penagihanDinas->proyek->nama_barang }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</label>
                            <p class="text-sm text-gray-900">{{ $penagihanDinas->proyek->jumlah }} {{ $penagihanDinas->proyek->satuan }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pengadaan</label>
                            <p class="text-sm text-gray-900">{{ $penagihanDinas->proyek->jenis_pengadaan }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Proyek</label>
                            <p class="text-sm font-medium text-gray-900">{{ $penagihanDinas->proyek->kode_proyek }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak Klien</label>
                            <p class="text-sm text-gray-900">{{ $penagihanDinas->proyek->kontak_klien ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kota/Kabupaten</label>
                            <p class="text-sm text-gray-900">{{ $penagihanDinas->proyek->kab_kota }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Proyek</label>
                            <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($penagihanDinas->proyek->tanggal)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Status Proyek</label>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $penagihanDinas->proyek->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Penawaran -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list-alt text-indigo-600 mr-2"></i>
                    Detail Barang Penawaran
                </h2>
                <p class="text-sm text-gray-500 mt-1">Nomor Penawaran: {{ $penagihanDinas->penawaran->nomor_penawaran }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $grandTotal = 0; @endphp
                        @foreach($penagihanDinas->penawaran->penawaranDetail as $index => $detail)
                            @php $subtotal = $detail->qty * $detail->harga_satuan; $grandTotal += $subtotal; @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-box text-indigo-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $detail->barang->nama_barang ?? 'N/A' }}</div>
                                            @if($detail->barang->merk)
                                                <div class="text-xs text-gray-500">Merk: {{ $detail->barang->merk }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ number_format($detail->qty, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-sm text-gray-900">{{ $detail->satuan }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-sm font-medium text-gray-900">Rp {{ number_format($detail->harga_satuan, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-sm font-semibold text-green-600">Rp {{ number_format($subtotal, 2, ',', '.') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                Total Keseluruhan:
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                    <i class="fas fa-money-bill-wave mr-1"></i>
                                    Rp {{ number_format($grandTotal, 2, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Summary Card -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-info-circle mr-2"></i>
                        Total {{ $penagihanDinas->penawaran->penawaranDetail->count() }} item dalam penawaran
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Status Penawaran</div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            {{ $penagihanDinas->penawaran->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Documents & Payments -->
    <div class="space-y-6">
        <!-- Dokumen -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                    Dokumen
                </h2>
            </div>
            <div class="p-6 space-y-3">
                @php
                $documents = [
                    'berita_acara_serah_terima' => 'Berita Acara Serah Terima',
                    'invoice' => 'Invoice',
                    'pnbp' => 'PNBP',
                    'faktur_pajak' => 'Faktur Pajak',
                    'surat_lainnya' => 'Surat Lainnya'
                ];
                @endphp
                
                @foreach($documents as $field => $label)
                    @if($penagihanDinas->$field)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                            <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                        </div>
                        <a href="{{ asset('storage/penagihan-dinas/dokumen/' . $penagihanDinas->$field) }}" target="_blank"
                           class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            <span class="text-sm">Lihat</span>
                        </a>
                    </div>
                    @else
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg opacity-50">
                        <i class="fas fa-file text-gray-400 mr-2"></i>
                        <span class="text-sm text-gray-500">{{ $label }} - Tidak ada</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- History Pembayaran -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-credit-card text-green-500 mr-2"></i>
                    History Pembayaran
                </h2>
            </div>
            <div class="p-6 space-y-4">
                @forelse($penagihanDinas->buktiPembayaran as $bukti)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($bukti->jenis_pembayaran === 'dp') bg-blue-50 text-blue-700 border border-blue-200
                            @elseif($bukti->jenis_pembayaran === 'lunas') bg-green-50 text-green-700 border border-green-200
                            @else bg-purple-50 text-purple-700 border border-purple-200 @endif">
                            @if($bukti->jenis_pembayaran === 'dp')
                                <i class="fas fa-hand-holding-usd mr-1"></i>
                                Down Payment (DP)
                            @elseif($bukti->jenis_pembayaran === 'lunas')
                                <i class="fas fa-check-circle mr-1"></i>
                                Pelunasan
                            @else
                                <i class="fas fa-money-bill mr-1"></i>
                                {{ ucfirst($bukti->jenis_pembayaran) }}
                            @endif
                        </span>
                        <span class="text-lg font-bold text-green-600">Rp {{ number_format($bukti->jumlah_bayar, 2, ',', '.') }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                            <span>{{ \Carbon\Carbon::parse($bukti->tanggal_bayar)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock mr-2 text-gray-500"></i>
                            <span>{{ $bukti->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                    @if($bukti->keterangan)
                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                        <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">Keterangan</div>
                        <div class="text-sm text-gray-700">{{ $bukti->keterangan }}</div>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <div class="text-xs text-gray-500">
                            ID: #{{ str_pad($bukti->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                        @if($bukti->bukti_pembayaran)
                        <a href="{{ asset('storage/penagihan-dinas/bukti-pembayaran/' . $bukti->bukti_pembayaran) }}" target="_blank"
                           class="inline-flex items-center px-3 py-1 text-xs text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200">
                            <i class="fas fa-eye mr-1"></i>
                            Lihat Bukti
                        </a>
                        @else
                        <span class="inline-flex items-center px-3 py-1 text-xs text-gray-500 bg-gray-100 rounded-lg">
                            <i class="fas fa-file-slash mr-1"></i>
                            Tidak ada bukti
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pembayaran</h3>
                    <p class="text-sm text-gray-500">Pembayaran akan muncul di sini setelah diproses</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div id="pelunasanModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-0 border w-full max-w-md shadow-xl rounded-xl bg-white">
        <div class="relative">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-credit-card mr-2"></i>
                        Tambah Pelunasan
                    </h3>
                    <button type="button" onclick="closePelunasanModal()" class="text-green-100 hover:text-white transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Info Sisa Pembayaran -->
                @if($penagihanDinas->status_pembayaran === 'dp')
                <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-lg">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <span class="text-sm font-medium text-red-800">Sisa Pembayaran</span>
                    </div>
                    <div class="text-2xl font-bold text-red-600">
                        Rp {{ number_format($penagihanDinas->sisa_pembayaran, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-red-600 mt-1">
                        Dari total: Rp {{ number_format($penagihanDinas->proyek->harga_total ?? 0, 2, ',', '.') }}
                    </div>
                </div>
                @endif
                
                <form id="pelunasanForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-upload text-green-600 mr-2"></i>
                                Bukti Pembayaran *
                            </label>
                            <input type="file" name="bukti_pembayaran" accept=".pdf,.jpg,.jpeg,.png" required
                                   class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none hover:bg-gray-100 file:mr-4 file:py-3 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700 file:transition-colors file:duration-200">
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, JPG, JPEG, PNG - Maksimal 2MB
                            </p>
                        </div>
                        
                        <div>
                            <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                                Tanggal Bayar *
                            </label>
                            <input type="date" name="tanggal_bayar" required value="{{ date('Y-m-d') }}"
                                   class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3">
                        </div>
                        
                        <div>
                            <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sticky-note text-green-600 mr-2"></i>
                                Keterangan
                            </label>
                            <textarea name="keterangan" rows="3"
                                      class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3"
                                      placeholder="Masukkan keterangan pelunasan (opsional)..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closePelunasanModal()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Pelunasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
function openPelunasanModal(penagihanId) {
    const modal = document.getElementById('pelunasanModal');
    const form = document.getElementById('pelunasanForm');
    
    form.action = `/penagihan-dinas/${penagihanId}/pelunasan`;
    modal.classList.remove('hidden');
}

function closePelunasanModal() {
    const modal = document.getElementById('pelunasanModal');
    modal.classList.add('hidden');
}


</script>
@endpush

@endsection
