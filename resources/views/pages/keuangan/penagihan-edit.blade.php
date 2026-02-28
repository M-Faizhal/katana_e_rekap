@extends('layouts.app')

@section('content')

{{-- Flash messages --}}
@if(session('success'))
<div class="mb-4 mt-4 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-300 text-green-800 rounded-lg shadow-sm">
    <i class="fas fa-check-circle text-green-500 text-lg"></i>
    <span class="text-sm font-medium">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div class="mb-4 mt-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-300 text-red-800 rounded-lg shadow-sm">
    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
    <span class="text-sm font-medium">{{ session('error') }}</span>
</div>
@endif
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Edit Penagihan</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">{{ $penagihanDinas->nomor_invoice }}</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-edit text-3xl sm:text-4xl lg:text-6xl text-red-200"></i>
        </div>
    </div>
</div>

<!-- Form Section -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Edit Informasi Penagihan</h2>
            <a href="{{ route('penagihan-dinas.show', $penagihanDinas->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('penagihan-dinas.update', $penagihanDinas->id) }}" method="POST" enctype="multipart/form-data" class="p-6" id="form-penagihan-utama">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Informasi Proyek -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-project-diagram text-white text-sm"></i>
                            </div>
                            Informasi Proyek
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Kode Proyek</span>
                                <div class="text-sm font-semibold text-gray-900">{{ $penagihanDinas->proyek->kode_proyek ?? 'PRJ-' . str_pad($penagihanDinas->proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Tanggal</span>
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($penagihanDinas->proyek->tanggal)->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Instansi</span>
                            <div class="text-sm font-medium text-gray-900">{{ $penagihanDinas->proyek->instansi }}</div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Kode Proyek</span>
                            <div class="text-sm font-medium text-gray-900">{{ $penagihanDinas->proyek->kode_proyek }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Kota/Kabupaten</span>
                                <div class="text-sm text-gray-900">{{ $penagihanDinas->proyek->kab_kota }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Jenis Pengadaan</span>
                                <div class="text-sm text-gray-900">{{ $penagihanDinas->proyek->jenis_pengadaan }}</div>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-200">
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Harga Proyek</span>
                            <div id="display-total-harga" class="text-lg font-bold text-green-600">Rp {{ number_format((float)$penagihanDinas->total_harga, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Detail Penawaran -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-list-alt text-white text-sm"></i>
                            </div>
                            Detail Barang Penawaran
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $penagihanDinas->penawaran->nomor_penawaran }} - {{ $penagihanDinas->penawaran->penawaranDetail->count() }} item</p>
                    </div>
                    
                    <div class="overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Qty</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Satuan</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Harga Satuan</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @php $grandTotal = 0; @endphp
                                    @foreach($penagihanDinas->penawaran->penawaranDetail as $index => $detail)
                                        @php $subtotal = $detail->qty * $detail->harga_satuan; $grandTotal += $subtotal; @endphp
                                        <tr class="hover:bg-blue-50 transition-all duration-200 group">
                                            <td class="px-4 py-3 text-sm text-gray-500 font-medium">
                                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-semibold text-gray-600 group-hover:bg-blue-100">
                                                    {{ $index + 1 }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-blue-100 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow duration-200">
                                                        <i class="fas fa-cube text-indigo-600 text-sm"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="text-sm font-semibold text-gray-900 truncate">{{ $detail->barang->nama_barang ?? 'N/A' }}</div>
                                                        @if($detail->barang && $detail->barang->brand)
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                                    <i class="fas fa-tag mr-1"></i>
                                                                    {{ $detail->barang->brand }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center justify-center w-16 h-8 px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 shadow-sm">
                                                    {{ number_format($detail->qty, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                                    {{ $detail->satuan }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($detail->harga_satuan, 2, ',', '.') }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="text-sm font-bold text-green-600">Rp {{ number_format($subtotal, 2, ',', '.') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Total Section -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-t border-gray-200">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-calculator text-green-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium">Total Keseluruhan Penawaran</span>
                                </div>
                                <div class="text-right">
                                    <div class="inline-flex items-center px-4 py-2 rounded-lg bg-green-100 border border-green-200">
                                        <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                                        <span class="text-xl font-bold text-green-700">Rp {{ number_format($grandTotal, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Pembayaran -->
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-purple-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-purple-200 bg-gradient-to-r from-purple-100 to-indigo-100 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-credit-card text-white text-sm"></i>
                            </div>
                            Status Pembayaran
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Status Saat Ini:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                @if($penagihanDinas->status_pembayaran === 'belum_bayar') bg-yellow-100 text-yellow-800
                                @elseif($penagihanDinas->status_pembayaran === 'dp') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                @if($penagihanDinas->status_pembayaran === 'belum_bayar')
                                    <i class="fas fa-clock mr-1"></i>Belum Bayar
                                @elseif($penagihanDinas->status_pembayaran === 'dp')
                                    <i class="fas fa-hand-holding-usd mr-1"></i>Down Payment
                                @else
                                    <i class="fas fa-check-circle mr-1"></i>Lunas
                                @endif
                            </span>
                        </div>
                        @php
                            $totalBayar = $penagihanDinas->buktiPembayaran->sum('jumlah_bayar');
                            $sisaPembayaran = (float)$penagihanDinas->total_harga - $totalBayar;
                        @endphp
                        @if($penagihanDinas->status_pembayaran === 'dp')
                        <div class="grid grid-cols-2 gap-4 pt-3 border-t border-purple-200">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Jumlah DP (<span id="summary-persentase-dp">{{ $penagihanDinas->persentase_dp }}</span>%)</span>
                                <div id="summary-jumlah-dp" class="text-sm font-semibold text-green-600">Rp {{ number_format((float)$penagihanDinas->jumlah_dp, 2, ',', '.') }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Sisa Pembayaran</span>
                                <div id="summary-sisa-pembayaran" class="text-sm font-semibold text-red-600">Rp {{ number_format($sisaPembayaran, 2, ',', '.') }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="pt-3 border-t border-purple-200">
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Terbayar</span>
                            <div id="summary-total-terbayar" class="text-lg font-bold text-blue-600">Rp {{ number_format((float)$totalBayar, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Form Edit Fields -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-edit text-white text-sm"></i>
                            </div>
                            Edit Informasi Penagihan
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Total Harga -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-money-bill-wave text-orange-600 mr-2"></i>
                                Total Harga Penagihan *
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium text-sm pointer-events-none">Rp</span>
                                <input type="text" name="total_harga" id="total_harga"
                                       value="{{ old('total_harga', number_format((float)$penagihanDinas->total_harga, 2, ',', '.')) }}"
                                       required
                                       data-currency
                                       class="currency-input block w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 @error('total_harga') border-red-300 @enderror pl-10 pr-4 py-3"
                                       placeholder="0,00">
                            </div>
                            @error('total_harga')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Nomor Invoice -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-file-invoice text-orange-600 mr-2"></i>
                                Nomor Invoice *
                            </label>
                            <input type="text" name="nomor_invoice" value="{{ old('nomor_invoice', $penagihanDinas->nomor_invoice) }}" required
                                   class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 @error('nomor_invoice') border-red-300 @enderror px-4 py-3"
                                   placeholder="Masukkan nomor invoice">
                            @error('nomor_invoice')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Tanggal Jatuh Tempo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-calendar-times text-orange-600 mr-2"></i>
                                Tanggal Jatuh Tempo *
                            </label>
                            <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', \Carbon\Carbon::parse($penagihanDinas->tanggal_jatuh_tempo)->format('Y-m-d')) }}" required
                                   class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 @error('tanggal_jatuh_tempo') border-red-300 @enderror px-4 py-3">
                            @error('tanggal_jatuh_tempo')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-sticky-note text-orange-600 mr-2"></i>
                                Keterangan Tambahan
                            </label>
                            <textarea name="keterangan" rows="4"
                                      class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 @error('keterangan') border-red-300 @enderror px-4 py-3"
                                      placeholder="Masukkan keterangan tambahan (opsional)...">{{ old('keterangan', $penagihanDinas->keterangan) }}</textarea>
                            @error('keterangan')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Update Dokumen -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-upload text-white text-sm"></i>
                            </div>
                            Update Dokumen
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Upload file baru untuk mengganti dokumen yang sudah ada (opsional)</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        @php
                        $documents = [
                            'berita_acara_serah_terima' => ['label' => 'Berita Acara Serah Terima', 'icon' => 'fas fa-file-contract'],
                            'invoice' => ['label' => 'Invoice', 'icon' => 'fas fa-file-invoice'],
                            'pnbp' => ['label' => 'PNBP', 'icon' => 'fas fa-file-alt'],
                            'faktur_pajak' => ['label' => 'Faktur Pajak', 'icon' => 'fas fa-receipt'],
                            'surat_lainnya' => ['label' => 'Surat Lainnya', 'icon' => 'fas fa-file']
                        ];
                        @endphp

                        @foreach($documents as $field => $doc)
                        <div class="border border-gray-200 rounded-lg hover:border-red-300 transition-colors duration-200" id="dokumen-card-{{ $field }}" data-field="{{ $field }}">
                            <div class="p-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="{{ $doc['icon'] }} text-red-600 mr-2"></i>
                                    {{ $doc['label'] }}
                                </label>
                                
                                <div id="dokumen-status-{{ $field }}">
                                @if($penagihanDinas->$field)
                                <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-file-pdf text-green-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-green-700">File tersedia</div>
                                                <div class="text-xs text-green-600">Upload file baru untuk mengganti</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ asset('storage/penagihan-dinas/dokumen/' . $penagihanDinas->$field) }}" target="_blank"
                                               class="inline-flex items-center px-3 py-1 border border-green-300 rounded-md shadow-sm text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors duration-200">
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat
                                            </a>
                                            {{-- Tombol hapus pakai form di luar (lihat bawah) --}}
                                            <button type="button"
                                                    onclick="hapusDokumen('{{ $field }}', '{{ $doc['label'] }}')"
                                                    class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                                <i class="fas fa-trash mr-1"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="mb-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-file-times text-gray-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500">Belum ada file</div>
                                            <div class="text-xs text-gray-400">Upload file baru</div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                </div>{{-- /dokumen-status --}}
                                
                                <input type="file" name="{{ $field }}" accept=".pdf"
                                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 file:transition-colors file:duration-200">
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format: PDF, Maksimal 2MB. Upload untuk mengganti file yang ada.
                                </p>
                                @error($field)
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- History Pembayaran -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-history text-white text-sm"></i>
                            </div>
                            History Pembayaran
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Klik "Edit" untuk mengubah data pembayaran</p>
                    </div>
                    
                    <div class="p-6" id="history-container">
                        @forelse($penagihanDinas->buktiPembayaran as $index => $bukti)
                        <div class="border border-gray-200 rounded-lg mb-4 last:mb-0 overflow-hidden" id="card-bukti-{{ $bukti->id }}">
                            {{-- === VIEW MODE === --}}
                            <div id="view-bukti-{{ $bukti->id }}" class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-money-check-alt text-green-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                                @if($bukti->jenis_pembayaran === 'dp') bg-blue-200 text-blue-900
                                                @elseif($bukti->jenis_pembayaran === 'lunas') bg-green-200 text-green-900
                                                @else bg-purple-200 text-purple-900 @endif">
                                                @if($bukti->jenis_pembayaran === 'dp')
                                                    <i class="fas fa-hand-holding-usd mr-1"></i>Down Payment
                                                @elseif($bukti->jenis_pembayaran === 'lunas')
                                                    <i class="fas fa-check-circle mr-1"></i>Pelunasan
                                                @else
                                                    <i class="fas fa-credit-card mr-1"></i>{{ ucfirst($bukti->jenis_pembayaran) }}
                                                @endif
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">Pembayaran #{{ $index + 1 }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div id="view-jumlah-{{ $bukti->id }}" class="text-lg font-bold text-green-600">Rp {{ number_format((float)$bukti->jumlah_bayar, 2, ',', '.') }}</div>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($bukti->tanggal_bayar)->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>

                                @if($bukti->keterangan)
                                <div class="border-t border-gray-100 pt-3 mb-3">
                                    <div class="text-xs text-gray-500 mb-1">Keterangan:</div>
                                    <div class="text-sm text-gray-700">{{ $bukti->keterangan }}</div>
                                </div>
                                @endif

                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <div class="flex items-center gap-2">
                                        @if($bukti->bukti_pembayaran)
                                        <a href="{{ asset('storage/penagihan-dinas/bukti-pembayaran/' . $bukti->bukti_pembayaran) }}" target="_blank"
                                           class="inline-flex items-center px-2 py-1 border border-green-300 rounded text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors duration-200">
                                            <i class="fas fa-eye mr-1"></i>Lihat Bukti
                                        </a>
                                        @else
                                        <span class="text-xs text-gray-400 italic"><i class="fas fa-file-times mr-1"></i>Belum ada file</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                onclick="toggleEditBukti({{ $bukti->id }})"
                                                class="inline-flex items-center px-3 py-1 border border-blue-300 rounded text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button type="button"
                                                onclick="hapusBukti({{ $bukti->id }})"
                                                class="inline-flex items-center px-3 py-1 border border-red-300 rounded text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- === EDIT MODE (inline) — fields dikumpulkan JS dari dalam panel === --}}
                            <div id="edit-bukti-{{ $bukti->id }}" class="hidden bg-blue-50 border-t border-blue-200 p-5">
                                <div class="mb-3 flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-blue-800 flex items-center">
                                        <i class="fas fa-edit mr-2"></i>Edit Pembayaran #{{ $index + 1 }}
                                    </h4>
                                    <button type="button" onclick="toggleEditBukti({{ $bukti->id }})"
                                            class="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1">
                                        <i class="fas fa-times"></i> Batal
                                    </button>
                                </div>

                                {{-- Jumlah Bayar --}}
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                                            <i class="fas fa-money-bill-wave text-blue-600 mr-1"></i>
                                            Jumlah Bayar *
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm pointer-events-none">Rp</span>
                                            <input type="text"
                                                   name="jumlah_bayar"
                                                   value="{{ number_format((float)$bukti->jumlah_bayar, 2, ',', '.') }}"
                                                   required
                                                   class="currency-input block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-10 pr-4 py-2 text-sm"
                                                   placeholder="0,00">
                                        </div>
                                    </div>

                                    {{-- Tanggal Bayar --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                                            <i class="fas fa-calendar text-blue-600 mr-1"></i>
                                            Tanggal Bayar *
                                        </label>
                                        <input type="date"
                                               name="tanggal_bayar"
                                               value="{{ \Carbon\Carbon::parse($bukti->tanggal_bayar)->format('Y-m-d') }}"
                                               required
                                               class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-sm">
                                    </div>

                                    {{-- Keterangan --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                                            <i class="fas fa-sticky-note text-blue-600 mr-1"></i>
                                            Keterangan
                                        </label>
                                        <textarea name="keterangan"
                                                  rows="2"
                                                  class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-sm"
                                                  placeholder="Keterangan (opsional)">{{ $bukti->keterangan }}</textarea>
                                    </div>

                                    {{-- Ganti File Bukti --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                                            <i class="fas fa-upload text-blue-600 mr-1"></i>
                                            Ganti File Bukti Pembayaran
                                        </label>
                                        @if($bukti->bukti_pembayaran)
                                        <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
                                            <div class="text-xs text-green-700 flex items-center">
                                                <i class="fas fa-file-check mr-1"></i>File tersedia — upload baru untuk mengganti
                                            </div>
                                            <a href="{{ asset('storage/penagihan-dinas/bukti-pembayaran/' . $bukti->bukti_pembayaran) }}" target="_blank"
                                               class="text-xs text-green-700 underline hover:no-underline">Lihat</a>
                                        </div>
                                        @endif
                                        <input type="file"
                                               name="bukti_pembayaran"
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <p class="mt-1 text-xs text-gray-400">Format: PDF, JPG, PNG – maks. 2 MB</p>
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <button type="button"
                                                onclick="simpanEditBukti({{ $bukti->id }})"
                                                class="inline-flex items-center px-5 py-2 border border-transparent rounded-lg shadow text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-receipt text-2xl text-gray-400"></i>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Belum ada pembayaran</h4>
                            <p class="text-xs text-gray-500">Riwayat pembayaran akan muncul di sini</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tambah Pembayaran — DI LUAR form utama supaya tidak ikut divalidasi -->
                </div>{{-- end right column space-y-6 --}}
            </div>{{-- end grid --}}

        <!-- Submit Button -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-lg border-t border-gray-200 mt-8">
            <div class="flex justify-end space-x-4">
                <a href="{{ route('penagihan-dinas.show', $penagihanDinas->id) }}"
                   class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center px-8 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    {{-- ================================================================
         PANEL TAMBAH PEMBAYARAN — di luar <form> agar tidak tervalidasi
         oleh browser saat Simpan Perubahan diklik
         ================================================================ --}}
    <div class="px-6 pb-6">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-t-lg">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-plus text-white text-sm"></i>
                    </div>
                    Tambah Pembayaran
                </h3>
                <p class="text-sm text-gray-600 mt-1">Tambahkan data pembayaran baru</p>
            </div>
            <div class="p-6">
                <div class="space-y-4" id="panel-tambah-bukti">

                    {{-- Jenis Pembayaran --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-teal-600 mr-1"></i>
                            Jenis Pembayaran *
                        </label>
                        <select name="jenis_pembayaran"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 px-3 py-2 text-sm">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="dp">Down Payment (DP)</option>
                            <option value="lunas">Pelunasan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    {{-- Jumlah Bayar --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave text-teal-600 mr-1"></i>
                            Jumlah Bayar *
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm pointer-events-none">Rp</span>
                            <input type="text" name="jumlah_bayar"
                                   class="currency-input block w-full border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 pl-10 pr-4 py-2 text-sm"
                                   placeholder="0,00">
                        </div>
                    </div>

                    {{-- Tanggal Bayar --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-teal-600 mr-1"></i>
                            Tanggal Bayar *
                        </label>
                        <input type="date" name="tanggal_bayar"
                               value="{{ date('Y-m-d') }}"
                               class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 px-3 py-2 text-sm">
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note text-teal-600 mr-1"></i>
                            Keterangan
                        </label>
                        <textarea name="keterangan" rows="2"
                                  class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 px-3 py-2 text-sm"
                                  placeholder="Keterangan (opsional)"></textarea>
                    </div>

                    {{-- File Bukti --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-upload text-teal-600 mr-1"></i>
                            File Bukti Pembayaran
                        </label>
                        <input type="file" name="bukti_pembayaran"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        <p class="mt-1 text-xs text-gray-400">Format: PDF, JPG, PNG – maks. 2 MB (opsional)</p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="button" id="btn-tambah-bukti"
                                class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>Tambah Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ===================================================================
   CONFIG — URL endpoints di-render dari Blade supaya bisa dipakai JS
   =================================================================== */
const URLS = {
    ajaxStoreBukti : '{{ route('penagihan-dinas.ajax-store-bukti', $penagihanDinas->id) }}',
    ajaxUpdateBukti: (id) => `/keuangan/penagihan-dinas/bukti-pembayaran/${id}/ajax-update`,
    ajaxDeleteBukti: (id) => `/keuangan/penagihan-dinas/bukti-pembayaran/${id}/ajax-delete`,
    ajaxDeleteDok  : (jenis) => `/keuangan/penagihan-dinas/{{ $penagihanDinas->id }}/dokumen/${jenis}/ajax-delete`,
};

const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

/* ===================================================================
   TOAST NOTIFICATION
   =================================================================== */
function showToast(msg, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = 'position:fixed;top:1.25rem;right:1.25rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;';
        document.body.appendChild(container);
    }
    const colors = type === 'success'
        ? 'bg-green-50 border-green-300 text-green-800'
        : 'bg-red-50 border-red-300 text-red-800';
    const icon = type === 'success'
        ? '<i class="fas fa-check-circle text-green-500 mr-2"></i>'
        : '<i class="fas fa-exclamation-circle text-red-500 mr-2"></i>';
    const toast = document.createElement('div');
    toast.className = `flex items-center gap-2 px-4 py-3 border rounded-lg shadow-md text-sm font-medium ${colors} transition-all duration-300`;
    toast.innerHTML = icon + msg;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3500);
}

/* ===================================================================
   CURRENCY HELPERS
   =================================================================== */
function parseCurrency(val) {
    return parseFloat((val || '').replace(/\./g, '').replace(',', '.')) || 0;
}
function formatCurrency(num) {
    return num.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

/* ===================================================================
   CURRENCY INPUT MASK
   =================================================================== */
function initCurrencyInput(input) {
    if (input.dataset.currencyInited) return;
    input.dataset.currencyInited = '1';

    input.addEventListener('input', function () {
        const el = this;
        const selEnd = el.selectionEnd;
        const oldLen = el.value.length;
        let v = el.value.replace(/[^0-9,]/g, '');
        const commaIdx = v.indexOf(',');
        if (commaIdx !== -1) {
            const intPart = v.slice(0, commaIdx);
            const decPart = v.slice(commaIdx + 1).replace(/,/g, '').slice(0, 2);
            v = intPart + ',' + decPart;
        }
        const parts = v.split(',');
        const intFormatted = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        el.value = parts.length > 1 ? intFormatted + ',' + parts[1] : intFormatted;
        const diff = el.value.length - oldLen;
        el.setSelectionRange(selEnd + diff, selEnd + diff);
    });
    input.addEventListener('blur', function () {
        const raw = parseCurrency(this.value);
        if (raw > 0) this.value = formatCurrency(raw);
    });
}
document.querySelectorAll('.currency-input').forEach(initCurrencyInput);

/* ===================================================================
   LIVE DP PREVIEW
   =================================================================== */
(function () {
    const totalInput = document.getElementById('total_harga');
    const pctInput   = document.getElementById('persentase_dp');
    const preview    = document.getElementById('dp_preview_amount');
    if (!totalInput || !pctInput || !preview) return;
    function updatePreview() {
        const total = parseCurrency(totalInput.value);
        const pct   = parseFloat(pctInput.value.replace(',', '.')) || 0;
        preview.textContent = 'Rp ' + formatCurrency(total * pct / 100);
    }
    totalInput.addEventListener('input', updatePreview);
    totalInput.addEventListener('blur', updatePreview);
    pctInput.addEventListener('input', updatePreview);
    updatePreview();
})();

/* ===================================================================
   TOGGLE INLINE EDIT PANEL
   =================================================================== */
function toggleEditBukti(id) {
    const viewEl = document.getElementById('view-bukti-' + id);
    const editEl = document.getElementById('edit-bukti-' + id);
    const isHidden = editEl.classList.contains('hidden');
    editEl.classList.toggle('hidden', !isHidden);
    viewEl.classList.toggle('opacity-50', isHidden);
}

/* ===================================================================
   AJAX HELPER — supports multipart/form-data
   =================================================================== */
async function ajaxFetch(url, method, formData) {
    const res = await fetch(url, {
        method,
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: formData,
    });
    return res.json();
}

/* ===================================================================
   BUTTON STATE HELPERS
   =================================================================== */
function setLoading(btn, loading, origText) {
    if (loading) {
        btn.disabled = true;
        btn.dataset.origText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    } else {
        btn.disabled = false;
        btn.innerHTML = origText || btn.dataset.origText || btn.innerHTML;
    }
}

/* ===================================================================
   CURRENCY INPUT MASK — sanitise total_harga sebelum form submit
   =================================================================== */
document.getElementById('form-penagihan-utama').addEventListener('submit', function () {
    const totalInput = document.getElementById('total_harga');
    if (totalInput) {
        const raw = parseCurrency(totalInput.value);
        totalInput.value = raw.toFixed(2).replace('.', ',');
    }
});

/* ===================================================================
   2. SIMPAN EDIT BUKTI PEMBAYARAN (AJAX)
   =================================================================== */
async function simpanEditBukti(buktiId) {
    const editEl = document.getElementById('edit-bukti-' + buktiId);
    const btn    = editEl.querySelector('button[onclick*="simpanEditBukti"]');

    const fd = new FormData();
    // Route ajax-update-bukti is POST — no method spoofing needed

    // Collect fields inside the edit panel directly (no form="..." needed since we're building manually)
    editEl.querySelectorAll('input, textarea, select').forEach(el => {
        if (el.type === 'file') {
            if (el.files[0]) fd.append(el.name, el.files[0]);
        } else {
            let val = el.value;
            if (el.classList.contains('currency-input')) {
                val = parseCurrency(val).toFixed(2).replace('.', ',');
            }
            fd.append(el.name, val);
        }
    });

    if (btn) setLoading(btn, true);
    try {
        const data = await ajaxFetch(URLS.ajaxUpdateBukti(buktiId), 'POST', fd);
        if (data.success) {
            showToast(data.message, 'success');
            // Update view mode amounts
            const jumlahEl = document.getElementById('view-jumlah-' + buktiId);
            if (jumlahEl) jumlahEl.textContent = 'Rp ' + formatCurrency(data.bukti.jumlah_bayar);

            // Update total terbayar
            const totalEl = document.getElementById('summary-total-terbayar');
            if (totalEl) totalEl.textContent = 'Rp ' + formatCurrency(data.total_terbayar);

            // Close edit panel
            toggleEditBukti(buktiId);
        } else {
            showToast(data.message || 'Gagal menyimpan.', 'error');
        }
    } catch (e) {
        showToast('Terjadi kesalahan jaringan.', 'error');
    } finally {
        if (btn) setLoading(btn, false);
    }
}

/* ===================================================================
   3. HAPUS BUKTI PEMBAYARAN (AJAX)
   =================================================================== */
async function hapusBukti(buktiId) {
    if (!confirm('Hapus data pembayaran ini? Tindakan ini tidak dapat dibatalkan.')) return;

    const fd = new FormData();
    fd.append('_method', 'DELETE');

    try {
        const data = await ajaxFetch(URLS.ajaxDeleteBukti(buktiId), 'POST', fd);
        if (data.success) {
            if (data.redirect_to) {
                showToast(data.message, 'success');
                setTimeout(() => { window.location.href = data.redirect_to; }, 1200);
                return;
            }
            showToast(data.message, 'success');
            // Remove card from DOM
            const card = document.getElementById('card-bukti-' + buktiId);
            if (card) card.remove();
            // Update total terbayar
            const totalEl = document.getElementById('summary-total-terbayar');
            if (totalEl) totalEl.textContent = 'Rp ' + formatCurrency(data.total_terbayar);
            // Show empty state if no more cards
            const container = document.getElementById('history-container');
            if (container && container.querySelectorAll('[id^="card-bukti-"]').length === 0) {
                container.innerHTML = `
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-2xl text-gray-400"></i>
                    </div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Belum ada pembayaran</h4>
                    <p class="text-xs text-gray-500">Riwayat pembayaran akan muncul di sini</p>
                </div>`;
            }
        } else {
            showToast(data.message || 'Gagal menghapus.', 'error');
        }
    } catch (e) {
        showToast('Terjadi kesalahan jaringan.', 'error');
    }
}

/* ===================================================================
   4. HAPUS DOKUMEN (AJAX)
   =================================================================== */
async function hapusDokumen(field, label) {
    if (!confirm('Hapus file ' + label + '?')) return;

    const fd = new FormData();
    fd.append('_method', 'DELETE');

    try {
        const data = await ajaxFetch(URLS.ajaxDeleteDok(field), 'POST', fd);
        if (data.success) {
            showToast(data.message, 'success');
            const statusDiv = document.getElementById('dokumen-status-' + field);
            if (statusDiv) {
                statusDiv.innerHTML = `
                <div class="mb-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-file-times text-gray-400 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Belum ada file</div>
                            <div class="text-xs text-gray-400">Upload file baru</div>
                        </div>
                    </div>
                </div>`;
            }
        } else {
            showToast(data.message || 'Gagal menghapus dokumen.', 'error');
        }
    } catch (e) {
        showToast('Terjadi kesalahan jaringan.', 'error');
    }
}

/* ===================================================================
   5. TAMBAH PEMBAYARAN (AJAX)
   =================================================================== */
document.getElementById('btn-tambah-bukti').addEventListener('click', async function () {
    const btn = this;

    // Collect fields from the tambah panel directly
    const panel = document.getElementById('panel-tambah-bukti');
    const fd = new FormData();
    panel.querySelectorAll('input, textarea, select').forEach(el => {
        if (el.type === 'file') {
            if (el.files[0]) fd.append(el.name, el.files[0]);
        } else {
            let val = el.value;
            if (el.classList.contains('currency-input')) {
                val = parseCurrency(val).toFixed(2).replace('.', ',');
            }
            fd.append(el.name, val);
        }
    });

    // Basic client-side validation
    if (!fd.get('jenis_pembayaran')) { showToast('Pilih jenis pembayaran terlebih dahulu.', 'error'); return; }
    if (!parseCurrency(fd.get('jumlah_bayar') || '')) { showToast('Masukkan jumlah bayar.', 'error'); return; }
    if (!fd.get('tanggal_bayar')) { showToast('Masukkan tanggal bayar.', 'error'); return; }

    setLoading(btn, true);
    try {
        const data = await ajaxFetch(URLS.ajaxStoreBukti, 'POST', fd);
        if (data.success) {
            showToast(data.message, 'success');

            // Append new card to history container
            const container = document.getElementById('history-container');
            // Remove empty state if present
            const emptyState = container.querySelector('.text-center.py-8');
            if (emptyState) emptyState.remove();

            const b        = data.bukti;
            const index    = container.querySelectorAll('[id^="card-bukti-"]').length + 1;
            const jenisBadge = b.jenis_pembayaran === 'dp'
                ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-200 text-blue-900"><i class="fas fa-hand-holding-usd mr-1"></i>Down Payment</span>'
                : b.jenis_pembayaran === 'lunas'
                    ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-200 text-green-900"><i class="fas fa-check-circle mr-1"></i>Pelunasan</span>'
                    : `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-200 text-purple-900"><i class="fas fa-credit-card mr-1"></i>${b.jenis_pembayaran}</span>`;

            const buktiLink = b.bukti_url
                ? `<a href="${b.bukti_url}" target="_blank" class="inline-flex items-center px-2 py-1 border border-green-300 rounded text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors duration-200"><i class="fas fa-eye mr-1"></i>Lihat Bukti</a>`
                : `<span class="text-xs text-gray-400 italic"><i class="fas fa-file-times mr-1"></i>Belum ada file</span>`;

            const tanggalFormatted = new Date(b.tanggal_bayar).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

            const newCard = document.createElement('div');
            newCard.className = 'border border-gray-200 rounded-lg mb-4 last:mb-0 overflow-hidden';
            newCard.id = 'card-bukti-' + b.id;
            newCard.innerHTML = `
            <div id="view-bukti-${b.id}" class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-check-alt text-green-600 text-sm"></i>
                        </div>
                        <div>
                            ${jenisBadge}
                            <div class="text-xs text-gray-500 mt-1">Pembayaran #${index}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div id="view-jumlah-${b.id}" class="text-lg font-bold text-green-600">Rp ${formatCurrency(b.jumlah_bayar)}</div>
                        <div class="text-xs text-gray-500"><i class="fas fa-calendar mr-1"></i>${tanggalFormatted}</div>
                    </div>
                </div>
                ${b.keterangan ? `<div class="border-t border-gray-100 pt-3 mb-3"><div class="text-xs text-gray-500 mb-1">Keterangan:</div><div class="text-sm text-gray-700">${b.keterangan}</div></div>` : ''}
                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2">${buktiLink}</div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="toggleEditBukti(${b.id})"
                                class="inline-flex items-center px-3 py-1 border border-blue-300 rounded text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        <button type="button" onclick="hapusBukti(${b.id})"
                                class="inline-flex items-center px-3 py-1 border border-red-300 rounded text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>
            <div id="edit-bukti-${b.id}" class="hidden bg-blue-50 border-t border-blue-200 p-5">
                <div class="mb-3 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-blue-800 flex items-center"><i class="fas fa-edit mr-2"></i>Edit Pembayaran #${index}</h4>
                    <button type="button" onclick="toggleEditBukti(${b.id})" class="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1"><i class="fas fa-times"></i> Batal</button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fas fa-money-bill-wave text-blue-600 mr-1"></i>Jumlah Bayar *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm pointer-events-none">Rp</span>
                            <input type="text" name="jumlah_bayar"
                                   value="${formatCurrency(b.jumlah_bayar)}" required
                                   class="currency-input block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-10 pr-4 py-2 text-sm" placeholder="0,00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fas fa-calendar text-blue-600 mr-1"></i>Tanggal Bayar *</label>
                        <input type="date" name="tanggal_bayar"
                               value="${b.tanggal_bayar}" required
                               class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fas fa-sticky-note text-blue-600 mr-1"></i>Keterangan</label>
                        <textarea name="keterangan" rows="2"
                                  class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-sm"
                                  placeholder="Keterangan (opsional)">${b.keterangan || ''}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fas fa-upload text-blue-600 mr-1"></i>Ganti File Bukti</label>
                        ${b.bukti_url ? `<div class="mb-2 p-2 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between"><div class="text-xs text-green-700 flex items-center"><i class="fas fa-file-check mr-1"></i>File tersedia</div><a href="${b.bukti_url}" target="_blank" class="text-xs text-green-700 underline">Lihat</a></div>` : ''}
                        <input type="file" name="bukti_pembayaran" accept=".pdf,.jpg,.jpeg,.png"
                               class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-400">Format: PDF, JPG, PNG – maks. 2 MB</p>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="simpanEditBukti(${b.id})"
                                class="inline-flex items-center px-5 py-2 border border-transparent rounded-lg shadow text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>`;
            container.appendChild(newCard);

            // Init currency mask on new inputs
            newCard.querySelectorAll('.currency-input').forEach(initCurrencyInput);

            // Update total terbayar
            const totalEl = document.getElementById('summary-total-terbayar');
            if (totalEl) totalEl.textContent = 'Rp ' + formatCurrency(data.total_terbayar);

            // Reset tambah form fields
            panel.querySelectorAll('input, textarea, select').forEach(el => {
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
                else if (el.type === 'file') { /* tidak bisa di-reset programatik */ }
                else if (el.type === 'date') el.value = new Date().toISOString().split('T')[0];
                else el.value = '';
            });

        } else {
            // Show validation errors if any
            const msgs = data.errors ? Object.values(data.errors).flat().join(' | ') : (data.message || 'Gagal menyimpan.');
            showToast(msgs, 'error');
        }
    } catch (e) {
        showToast('Terjadi kesalahan jaringan.', 'error');
    } finally {
        setLoading(btn, false);
    }
});
</script>
@endpush
