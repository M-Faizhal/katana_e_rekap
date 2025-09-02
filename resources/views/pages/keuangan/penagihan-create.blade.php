@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Buat Penagihan Dinas</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Buat penagihan untuk proyek {{ $proyek->nama_proyek }}</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-file-invoice text-3xl sm:text-4xl lg:text-6xl text-red-200"></i>
        </div>
    </div>
</div>

<!-- Form Section -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Informasi Penagihan</h2>
            <a href="{{ route('penagihan-dinas.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('penagihan-dinas.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <!-- Debug: Show validation errors -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <h4 class="text-red-800 font-semibold mb-2">Terjadi kesalahan validasi:</h4>
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-800 font-semibold">{{ session('error') }}</p>
            </div>
        @endif
        
        <input type="hidden" name="proyek_id" value="{{ $proyek->id_proyek }}">
        <input type="hidden" name="penawaran_id" value="{{ $penawaran->id_penawaran }}">
        <input type="hidden" name="total_harga" value="{{ $totalHarga }}">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Informasi Proyek -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Proyek</h3>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Kode Proyek</span>
                                <div class="text-sm font-semibold text-gray-900">{{ $proyek->kode_proyek ?? 'PRJ-' . str_pad($proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Tanggal</span>
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($proyek->tanggal)->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Instansi</span>
                            <div class="text-sm font-medium text-gray-900">{{ $proyek->instansi }}</div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Kode Proyek</span>
                            <div class="text-sm font-medium text-gray-900">{{ $proyek->kode_proyek }}</div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Kontak Klien</span>
                            <div class="text-sm text-gray-900">{{ $proyek->kontak_klien ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Kota/Kabupaten</span>
                                <div class="text-sm text-gray-900">{{ $proyek->kab_kota }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Jenis Pengadaan</span>
                                <div class="text-sm text-gray-900">{{ $proyek->jenis_pengadaan }}</div>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Nama Barang</span>
                            <div class="text-sm font-medium text-gray-900">{{ $proyek->nama_barang }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Jumlah</span>
                                <div class="text-sm text-gray-900">{{ $proyek->jumlah }} {{ $proyek->satuan }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Total Harga</span>
                                <div class="text-sm font-bold text-green-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Penawaran -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Penawaran</h3>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Nomor Penawaran</span>
                                <div class="text-sm font-semibold text-gray-900">{{ $penawaran->nomor_penawaran }}</div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Status</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    {{ $penawaran->status }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Jumlah Item</span>
                            <div class="text-sm text-gray-900">{{ $penawaran->penawaranDetail->count() }} item</div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider">Tanggal ACC</span>
                            <div class="text-sm text-gray-900">{{ $penawaran->updated_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Detail Barang Penawaran -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-list-alt text-white text-sm"></i>
                            </div>
                            Detail Barang Penawaran
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $penawaran->nomor_penawaran }} - {{ $penawaran->penawaranDetail->count() }} item</p>
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
                                    @foreach($penawaran->penawaranDetail as $index => $detail)
                                        @php $subtotal = $detail->qty * $detail->harga_satuan; $grandTotal += $subtotal; @endphp
                                        <tr class="hover:bg-blue-50 transition-all duration-200 group">
                                            <td class="px-4 py-4 text-sm text-gray-500 font-medium">
                                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-semibold text-gray-600 group-hover:bg-blue-100">
                                                    {{ $index + 1 }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
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
                                            <td class="px-4 py-4 text-center">
                                                <span class="inline-flex items-center justify-center w-16 h-8 px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 shadow-sm">
                                                    {{ number_format($detail->qty, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                                    {{ $detail->satuan }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-4 py-4 text-right">
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

                <!-- Informasi Invoice -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-file-invoice text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Invoice</h3>
                    </div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nomor Invoice *</label>
                    <input type="text" name="nomor_invoice" value="{{ old('nomor_invoice') }}" required
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nomor_invoice') border-red-300 @enderror px-4 py-3"
                           placeholder="Masukkan nomor invoice">
                    @error('nomor_invoice')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Status Pembayaran -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-credit-card text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Status Pembayaran</h3>
                    </div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Status Pembayaran *</label>
                    <select name="status_pembayaran" id="status_pembayaran" required onchange="toggleDpPercentage()"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 @error('status_pembayaran') border-red-300 @enderror px-4 py-3">
                        <option value="">Pilih Status Pembayaran</option>
                        <option value="dp" {{ old('status_pembayaran') == 'dp' ? 'selected' : '' }}>Down Payment (DP)</option>
                        <option value="lunas" {{ old('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    @error('status_pembayaran')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Persentase DP -->
                <div id="dp_percentage_section" class="hidden bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-percentage text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Perhitungan DP</h3>
                    </div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Persentase DP (%) *</label>
                    <input type="number" name="persentase_dp" id="persentase_dp" value="{{ old('persentase_dp') }}" 
                           min="0" max="100" step="0.01" onchange="calculateDp()" onwheel="return false;" disabled
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 @error('persentase_dp') border-red-300 @enderror px-4 py-3"
                           placeholder="Masukkan persentase DP">
                    @error('persentase_dp')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    
                    <div id="dp_calculation" class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 hidden">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calculator text-blue-600 mr-2"></i>
                            Kalkulasi Pembayaran
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Jumlah DP:</span>
                                <span id="jumlah_dp" class="text-sm font-semibold text-blue-600">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Sisa Pembayaran:</span>
                                <span id="sisa_pembayaran" class="text-sm font-semibold text-red-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tanggal Jatuh Tempo -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-times text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Jatuh Tempo</h3>
                    </div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Tanggal Jatuh Tempo *</label>
                    <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}" required
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 @error('tanggal_jatuh_tempo') border-red-300 @enderror px-4 py-3">
                    @error('tanggal_jatuh_tempo')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-gray-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-sticky-note text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Keterangan</h3>
                    </div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="4"
                              class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 @error('keterangan') border-red-300 @enderror px-4 py-3"
                              placeholder="Masukkan keterangan tambahan (opsional)...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Upload Dokumen -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-upload text-white text-sm"></i>
                            </div>
                            Upload Dokumen
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Upload dokumen pendukung penagihan</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Berita Acara Serah Terima -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-red-300 transition-colors duration-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-file-contract text-red-600 mr-2"></i>
                                Berita Acara Serah Terima
                            </label>
                            <input type="file" name="berita_acara_serah_terima" accept=".pdf"
                                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 file:transition-colors file:duration-200">
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, Maksimal 2MB
                            </p>
                            @error('berita_acara_serah_terima')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Invoice -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-red-300 transition-colors duration-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-file-invoice text-red-600 mr-2"></i>
                                Invoice
                            </label>
                            <input type="file" name="invoice" accept=".pdf"
                                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 file:transition-colors file:duration-200">
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, Maksimal 2MB
                            </p>
                            @error('invoice')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- PNBP -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-red-300 transition-colors duration-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-file-alt text-red-600 mr-2"></i>
                                PNBP
                            </label>
                            <input type="file" name="pnbp" accept=".pdf"
                                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 file:transition-colors file:duration-200">
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, Maksimal 2MB
                            </p>
                            @error('pnbp')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Faktur Pajak -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-red-300 transition-colors duration-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-receipt text-red-600 mr-2"></i>
                                Faktur Pajak
                            </label>
                            <input type="file" name="faktur_pajak" accept=".pdf"
                                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 file:transition-colors file:duration-200">
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, Maksimal 2MB
                            </p>
                            @error('faktur_pajak')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Surat Lainnya -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-red-300 transition-colors duration-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-file text-red-600 mr-2"></i>
                                Surat Lainnya
                            </label>
                            <input type="file" name="surat_lainnya" accept=".pdf"
                                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 file:transition-colors file:duration-200">
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, Maksimal 2MB
                            </p>
                            @error('surat_lainnya')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bukti Pembayaran -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-credit-card text-white text-sm"></i>
                            </div>
                            Bukti Pembayaran
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Upload bukti pembayaran dan informasi transaksi</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="border border-green-200 rounded-lg p-4 bg-green-50/30">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-image text-green-600 mr-2"></i>
                                Bukti Pembayaran *
                            </label>
                            <input type="file" name="bukti_pembayaran" accept=".pdf,.jpg,.jpeg,.png" required
                                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 file:transition-colors file:duration-200">
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, JPG, JPEG, PNG - Maksimal 2MB
                            </p>
                            @error('bukti_pembayaran')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                                Tanggal Bayar *
                            </label>
                            <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar') }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 @error('tanggal_bayar') border-red-300 @enderror px-4 py-3">
                            @error('tanggal_bayar')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-comment-alt text-green-600 mr-2"></i>
                                Keterangan Pembayaran
                            </label>
                            <textarea name="keterangan_pembayaran" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 @error('keterangan_pembayaran') border-red-300 @enderror px-4 py-3"
                                      placeholder="Masukkan keterangan pembayaran (opsional)...">{{ old('keterangan_pembayaran') }}</textarea>
                            @error('keterangan_pembayaran')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-lg border-t border-gray-200 mt-8">
            <div class="flex justify-end space-x-4">
                <a href="{{ route('penagihan-dinas.index') }}"
                   class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center px-8 py-3 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Penagihan
                </button>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
/* Disable scroll on number inputs */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
}

/* Prevent scroll wheel from changing number input values */
input[type=number] {
    -webkit-appearance: none;
    -moz-appearance: textfield;
}
</style>
@endpush

@push('scripts')
<script>
const totalHarga = {{ $totalHarga }};

// Prevent scroll wheel from changing number input values
document.addEventListener('DOMContentLoaded', function() {
    // Get all number inputs
    const numberInputs = document.querySelectorAll('input[type="number"]');
    
    numberInputs.forEach(function(input) {
        // Disable scroll wheel on number inputs
        input.addEventListener('wheel', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }, { passive: false });
        
        // Also prevent mousewheel event
        input.addEventListener('mousewheel', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }, { passive: false });
        
        // Prevent DOMMouseScroll for Firefox
        input.addEventListener('DOMMouseScroll', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }, { passive: false });
        
        // Blur the input when mouse enters to prevent accidental scroll
        input.addEventListener('mouseenter', function() {
            if (document.activeElement === this) {
                this.blur();
            }
        });
    });
    
    // Initialize other functions
    toggleDpPercentage();
    if (document.getElementById('persentase_dp').value) {
        calculateDp();
    }
});

function toggleDpPercentage() {
    const statusPembayaran = document.getElementById('status_pembayaran').value;
    const dpSection = document.getElementById('dp_percentage_section');
    const persentaseDp = document.getElementById('persentase_dp');
    
    if (statusPembayaran === 'dp') {
        dpSection.classList.remove('hidden');
        persentaseDp.setAttribute('required', 'required');
        persentaseDp.removeAttribute('disabled');
        persentaseDp.setAttribute('name', 'persentase_dp');
    } else {
        dpSection.classList.add('hidden');
        persentaseDp.removeAttribute('required');
        persentaseDp.setAttribute('disabled', 'disabled');
        persentaseDp.value = '';
        document.getElementById('dp_calculation').classList.add('hidden');
    }
}

function calculateDp() {
    const persentase = parseFloat(document.getElementById('persentase_dp').value) || 0;
    const jumlahDp = (totalHarga * persentase) / 100;
    const sisaPembayaran = totalHarga - jumlahDp;
    
    document.getElementById('jumlah_dp').textContent = 'Rp ' + formatNumber(jumlahDp);
    document.getElementById('sisa_pembayaran').textContent = 'Rp ' + formatNumber(sisaPembayaran);
    
    if (persentase > 0) {
        document.getElementById('dp_calculation').classList.remove('hidden');
    } else {
        document.getElementById('dp_calculation').classList.add('hidden');
    }
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endpush

@endsection
