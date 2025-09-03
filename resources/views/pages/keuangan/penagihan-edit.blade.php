@extends('layouts.app')

@section('content')
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

    <form action="{{ route('penagihan-dinas.update', $penagihanDinas->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
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
                            <div class="text-lg font-bold text-green-600">Rp {{ number_format((float)$penagihanDinas->total_harga, 0, ',', '.') }}</div>
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
                                                    {{ number_format($detail->qty, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                                    {{ $detail->satuan }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="text-sm font-bold text-green-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
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
                                        <span class="text-xl font-bold text-green-700">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
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
                            $sisaPembayaran = $penagihanDinas->total_harga - $totalBayar;
                        @endphp
                        @if($penagihanDinas->status_pembayaran === 'dp')
                        <div class="grid grid-cols-2 gap-4 pt-3 border-t border-purple-200">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Jumlah DP ({{ $penagihanDinas->persentase_dp }}%)</span>
                                <div class="text-sm font-semibold text-green-600">Rp {{ number_format((float)$penagihanDinas->jumlah_dp, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Sisa Pembayaran</span>
                                <div class="text-sm font-semibold text-red-600">Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="pt-3 border-t border-purple-200">
                            <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Terbayar</span>
                            <div class="text-lg font-bold text-blue-600">Rp {{ number_format((float)$totalBayar, 0, ',', '.') }}</div>
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
                        <div class="border border-gray-200 rounded-lg hover:border-red-300 transition-colors duration-200">
                            <div class="p-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="{{ $doc['icon'] }} text-red-600 mr-2"></i>
                                    {{ $doc['label'] }}
                                </label>
                                
                                @if($penagihanDinas->$field)
                                <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-file-pdf text-green-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-green-700">File tersedia</div>
                                                <div class="text-xs text-green-600">Klik lihat untuk preview</div>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/penagihan-dinas/dokumen/' . $penagihanDinas->$field) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-1 border border-green-300 rounded-md shadow-sm text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors duration-200">
                                            <i class="fas fa-eye mr-1"></i>
                                            Lihat
                                        </a>
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
                        <p class="text-sm text-gray-600 mt-1">Riwayat transaksi pembayaran</p>
                    </div>
                    
                    <div class="p-6">
                        @forelse($penagihanDinas->buktiPembayaran as $index => $bukti)
                        <div class="border border-gray-200 rounded-lg p-4 mb-4 last:mb-0 hover:shadow-md transition-shadow duration-200">
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
                                        <div class="text-xs text-gray-500 mt-1">
                                            Pembayaran #{{ $index + 1 }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-green-600">Rp {{ number_format((float)$bukti->jumlah_bayar, 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($bukti->tanggal_bayar)->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($bukti->keterangan)
                            <div class="border-t border-gray-100 pt-3">
                                <div class="text-xs text-gray-500 mb-1">Keterangan:</div>
                                <div class="text-sm text-gray-700">{{ $bukti->keterangan }}</div>
                            </div>
                            @endif
                            
                            <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100">
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    Dibuat: {{ $bukti->created_at->format('d M Y H:i') }}
                                </div>
                                @if($bukti->bukti_pembayaran)
                                <a href="{{ asset('storage/penagihan-dinas/bukti-pembayaran/' . $bukti->bukti_pembayaran) }}" target="_blank"
                                   class="inline-flex items-center px-2 py-1 border border-green-300 rounded text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat Bukti
                                </a>
                                @endif
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
            </div>
        </div>

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
</div>

@endsection
