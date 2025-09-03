@extends('layouts.app')

@section('title', 'Detail Penawaran - ' . ($penawaran->no_penawaran ?? 'Unknown') . ' - Cyber KATANA')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-semibold text-gray-900 truncate">Detail Penawaran</h1>
                <div class="text-sm text-gray-600 mt-1 flex flex-wrap items-center gap-2">
                    <span class="font-medium">No. Penawaran:</span> 
                    <span class="text-blue-600 font-semibold">{{ $penawaran->no_penawaran ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">Status:</span> 
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        @if($penawaran->status === 'ACC') bg-green-100 text-green-800
                        @elseif($penawaran->status === 'Ditolak') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ $penawaran->status }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('purchasing.kalkulasi') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <a href="{{ route('purchasing.kalkulasi.hps.summary', ['id' => $proyek->id_proyek]) }}" target="_blank" rel="noopener" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-table mr-2"></i>
                    Ringkasan HPS
                </a>
            </div>
        </div>
    </div>

    <!-- Informasi Proyek -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-project-diagram text-blue-600 mr-2"></i>
                Informasi Proyek
            </h2>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">ID Proyek</label>
                    <p class="text-gray-900 font-medium">PRJ{{ str_pad($proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Kota/Kab</label>
                    <p class="text-gray-900 font-medium">{{ $proyek->kab_kota }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Instansi</label>
                    <p class="text-gray-900 font-medium">{{ $proyek->instansi }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Marketing</label>
                    <p class="text-gray-900 font-medium">{{ $proyek->adminMarketing->nama ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Purchasing</label>
                    <p class="text-gray-900 font-medium">{{ $proyek->adminPurchasing->nama ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Total Proyek</label>
                    <p class="text-green-600 font-bold">{{ 'Rp ' . number_format($proyek->harga_total ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Penawaran -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-file-contract text-green-600 mr-2"></i>
                Informasi Penawaran
            </h2>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Nomor Penawaran</label>
                    <p class="text-blue-600 font-bold">{{ $penawaran->no_penawaran }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Tanggal Penawaran</label>
                    <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($penawaran->tanggal_penawaran)->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Masa Berlaku</label>
                    <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($penawaran->masa_berlaku)->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Total Penawaran</label>
                    <p class="text-green-600 font-bold text-lg">{{ 'Rp ' . number_format($penawaran->total_penawaran, 0, ',', '.') }}</p>
                </div>
            </div>
            
            @if($penawaran->surat_penawaran || $penawaran->surat_pesanan)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <label class="text-sm font-medium text-gray-500 block mb-2">Dokumen</label>
                <div class="flex flex-wrap gap-2">
                    @if($penawaran->surat_penawaran)
                    <a href="{{ asset('storage/penawaran/' . $penawaran->surat_penawaran) }}" 
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200 transition-colors">
                        @php
                            $extension = strtolower(pathinfo($penawaran->surat_penawaran, PATHINFO_EXTENSION));
                            $icon = in_array($extension, ['pdf']) ? 'fas fa-file-pdf' : 
                                   (in_array($extension, ['doc', 'docx']) ? 'fas fa-file-word' : 
                                   (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'fas fa-file-image' : 'fas fa-file'));
                        @endphp
                        <i class="{{ $icon }} mr-2"></i>
                        Surat Penawaran
                        <i class="fas fa-eye ml-2 text-xs"></i>
                    </a>
                    @endif
                    @if($penawaran->surat_pesanan)
                    <a href="{{ asset('storage/penawaran/' . $penawaran->surat_pesanan) }}" 
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 bg-green-100 text-green-700 rounded-lg text-sm hover:bg-green-200 transition-colors">
                        @php
                            $extension = strtolower(pathinfo($penawaran->surat_pesanan, PATHINFO_EXTENSION));
                            $icon = in_array($extension, ['pdf']) ? 'fas fa-file-pdf' : 
                                   (in_array($extension, ['doc', 'docx']) ? 'fas fa-file-word' : 
                                   (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'fas fa-file-image' : 'fas fa-file'));
                        @endphp
                        <i class="{{ $icon }} mr-2"></i>
                        Surat Pesanan
                        <i class="fas fa-eye ml-2 text-xs"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Detail Item Penawaran -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Detail Item Penawaran
                </h2>
                <div class="text-sm text-gray-600">
                    Total: {{ $penawaran->details->count() }} item(s)
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spesifikasi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penawaran->details as $index => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $detail->nama_barang }}</div>
                            @if($detail->barang)
                            <div class="text-xs text-gray-500">Kode: {{ $detail->barang->id_barang }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900 max-w-xs">
                                {{ Str::limit($detail->spesifikasi, 100) }}
                            </div>
                            @if(strlen($detail->spesifikasi) > 100)
                            <button onclick="showFullSpec('{{ $detail->id_detail }}')" class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                Lihat Selengkapnya
                            </button>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            {{ number_format($detail->qty, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->satuan }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            {{ 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                            {{ 'Rp ' . number_format($detail->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-3 opacity-50"></i>
                            <p>Tidak ada detail item penawaran</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($penawaran->details->count() > 0)
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                            <strong>Total Penawaran:</strong>
                        </td>
                        <td class="px-4 py-4 text-right text-lg font-bold text-green-600">
                            {{ 'Rp ' . number_format($penawaran->details->sum('subtotal'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Data Kalkulasi HPS (Lengkap) -->
    @if($kalkulasiData->count() > 0)
    <!-- Bukti Approval Kalkulasi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-file-check text-green-600 mr-2"></i>
                Bukti Approval Kalkulasi
            </h2>
            <p class="text-sm text-gray-600 mt-1">File bukti persetujuan yang digunakan dalam kalkulasi HPS</p>
        </div>
        <div class="p-4">
            @php
                // Get approval file from kalkulasi data (all records should have same file)
                $approvalFile = $kalkulasiData->where('bukti_file_approval', '!=', null)->first();
                $approvalFileName = $approvalFile ? $approvalFile->bukti_file_approval : null;
                
                // Check if file exists in storage
                $fileExists = false;
                $filePath = null;
                if ($approvalFileName) {
                    // If filename contains path, use as is, otherwise add path
                    if (strpos($approvalFileName, '/') !== false) {
                        $filePath = $approvalFileName;
                    } else {
                        $filePath = 'approval_files/' . $approvalFileName;
                    }
                    $fileExists = file_exists(storage_path('app/public/' . $filePath));
                }
            @endphp
            
            @if($approvalFileName && $fileExists)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @php
                                $extension = strtolower(pathinfo($approvalFileName, PATHINFO_EXTENSION));
                                $iconClass = 'fas fa-file-alt';
                                $iconColor = 'text-blue-500';
                                
                                if (in_array($extension, ['pdf'])) {
                                    $iconClass = 'fas fa-file-pdf';
                                    $iconColor = 'text-red-500';
                                } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $iconClass = 'fas fa-file-image';
                                    $iconColor = 'text-green-500';
                                } elseif (in_array($extension, ['doc', 'docx'])) {
                                    $iconClass = 'fas fa-file-word';
                                    $iconColor = 'text-blue-600';
                                }
                            @endphp
                            <i class="{{ $iconClass }} {{ $iconColor }} text-2xl"></i>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ basename($approvalFileName) }}</div>
                                <div class="text-xs text-gray-500">
                                    Diupload: {{ $approvalFile->updated_at ? $approvalFile->updated_at->format('d/m/Y H:i') : '-' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Ukuran: {{ file_exists(storage_path('app/public/' . $filePath)) ? number_format(filesize(storage_path('app/public/' . $filePath)) / 1024, 1) . ' KB' : 'Unknown' }}
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank" 
                               class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat File
                            </a>
                            <a href="{{ asset('storage/' . $filePath) }}" download 
                               class="inline-flex items-center px-3 py-2 bg-green-100 text-green-700 rounded-lg text-sm hover:bg-green-200 transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Download
                            </a>
                        </div>
                    </div>
                    
                    <!-- Preview for images -->
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="text-sm font-medium text-gray-700 mb-2">Preview:</div>
                        <div class="max-w-md">
                            <img src="{{ asset('storage/' . $filePath) }}" 
                                 alt="Preview approval file" 
                                 class="max-w-full h-auto rounded-lg border border-gray-300 shadow-sm cursor-pointer"
                                 onclick="showImageModal('{{ asset('storage/' . $filePath) }}', '{{ basename($approvalFileName) }}')">
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Klik gambar untuk melihat ukuran penuh</div>
                    </div>
                    @endif
                </div>
            @else
                <div class="text-center text-gray-500 py-6">
                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                    <p class="text-lg font-medium mb-2">Tidak Ada File Bukti Approval</p>
                    <p class="text-sm">File bukti approval tidak tersedia untuk kalkulasi ini.</p>
                    @if($approvalFileName && !$fileExists)
                    <p class="text-sm text-red-600 mt-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        File "{{ $approvalFileName }}" tidak ditemukan di server
                    </p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-calculator text-orange-600 mr-2"></i>
                Data Kalkulasi HPS Lengkap
            </h2>
            <p class="text-sm text-gray-600 mt-1">Semua perhitungan dan analisis yang digunakan dalam penawaran</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-orange-50">
                    <tr>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">No</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Barang</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Vendor</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Jenis</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Qty</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Satuan</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Harga Vendor</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Total Diskon</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Harga Akhir</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Total Harga</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">% Kenaikan</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Proyeksi Kenaikan</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">PPN Dinas</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">PPh Dinas</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r bg-yellow-100">HPS</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">DPP</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Asumsi Cair</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Ongkir</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Omzet Dinas</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Bendera</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Bank Cost</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r">Biaya Ops</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase border-r bg-green-100">Nett Income</th>
                        <th class="px-2 py-2 text-left font-medium text-orange-700 uppercase bg-green-100">Nett %</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($kalkulasiData as $index => $kalkulasi)
                    <tr class="hover:bg-orange-50">
                        <td class="px-2 py-2 text-center border-r">{{ $index + 1 }}</td>
                        <td class="px-2 py-2 border-r">
                            <div class="font-medium text-gray-900">{{ $kalkulasi->barang->nama_barang ?? $kalkulasi->keterangan_1 ?? 'N/A' }}</div>
                        </td>
                        <td class="px-2 py-2 border-r">
                            <div class="text-gray-900">{{ $kalkulasi->vendor->nama_vendor ?? 'N/A' }}</div>
                        </td>
                        <td class="px-2 py-2 border-r">
                            <div class="text-gray-700">{{ $kalkulasi->jenis_vendor ?? '-' }}</div>
                        </td>
                        <td class="px-2 py-2 text-center border-r">{{ $kalkulasi->qty ?? 1 }}</td>
                        <td class="px-2 py-2 text-center border-r">{{ $kalkulasi->barang->satuan ?? 'pcs' }}</td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->harga_vendor ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->total_diskon ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->harga_akhir ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->jumlah_volume ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->kenaikan_percent ?? 0, 1) }}%
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->proyeksi_kenaikan ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->nilai_ppn ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->nilai_pph_badan ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right font-bold bg-yellow-50 border-r">
                            {{ number_format($kalkulasi->hps ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->nilai_dpp ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->nilai_asumsi_cair ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->ongkir ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->omzet_dinas ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->bendera ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->bank_cost ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->biaya_ops ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right font-bold bg-green-50 border-r">
                            {{ number_format($kalkulasi->nett_income ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right font-bold bg-green-50">
                            {{ number_format($kalkulasi->nett_income_percent ?? 0, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-orange-100">
                    <tr class="font-bold">
                        <td colspan="6" class="px-2 py-3 text-right border-r">
                            <strong>TOTAL:</strong>
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('harga_vendor'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('total_diskon'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('harga_akhir'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('jumlah_volume'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">-</td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('proyeksi_kenaikan'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('nilai_ppn'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('nilai_pph_badan'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right text-orange-700 bg-yellow-100 border-r">
                            {{ number_format($kalkulasiData->sum('hps'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('nilai_dpp'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('nilai_asumsi_cair'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('ongkir'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('omzet_dinas'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('bendera'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('bank_cost'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('biaya_ops'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right text-green-700 bg-green-100 border-r">
                            {{ number_format($kalkulasiData->sum('nett_income'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right text-green-700 bg-green-100">
                            @php
                                $avgNett = $kalkulasiData->count() > 0 ? $kalkulasiData->avg('nett_income_percent') : 0;
                            @endphp
                            {{ number_format($avgNett, 2) }}%
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @else
    <!-- Tidak ada data kalkulasi HPS -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-calculator text-orange-600 mr-2"></i>
                Data Kalkulasi HPS Lengkap
            </h2>
            <p class="text-sm text-gray-600 mt-1">Semua perhitungan dan analisis yang digunakan dalam penawaran</p>
        </div>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-calculator text-4xl mb-3 opacity-50"></i>
            <p class="text-lg font-medium mb-2">Belum Ada Data Kalkulasi HPS</p>
            <p class="text-sm">Data kalkulasi HPS belum dibuat untuk penawaran ini.</p>
            <p class="text-sm">Silakan buat kalkulasi HPS terlebih dahulu di menu Purchasing → Kalkulasi HPS.</p>
        </div>
    </div>
    @endif
</div>

<!-- Modal untuk Spesifikasi Lengkap -->
<div id="spec-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Spesifikasi Lengkap</h3>
                <button onclick="closeSpecModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="spec-content" class="text-sm text-gray-700 whitespace-pre-wrap max-h-96 overflow-y-auto"></div>
            <div class="flex justify-end mt-4">
                <button onclick="closeSpecModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Preview Gambar -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative max-w-5xl max-h-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-white" id="image-title">Preview Image</h3>
                <button onclick="closeImageModal()" class="text-white hover:text-gray-300">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <img id="modal-image" src="" alt="Preview" class="max-w-full max-h-screen object-contain rounded-lg">
            <div class="flex justify-center mt-4">
                <button onclick="closeImageModal()" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show full specification
function showFullSpec(detailId) {
    const detail = @json($penawaran->details);
    const selectedDetail = detail.find(d => d.id_detail == detailId);
    
    if (selectedDetail) {
        document.getElementById('spec-content').textContent = selectedDetail.spesifikasi;
        document.getElementById('spec-modal').classList.remove('hidden');
    }
}

// Close specification modal
function closeSpecModal() {
    document.getElementById('spec-modal').classList.add('hidden');
}

// Show image modal
function showImageModal(imageSrc, imageTitle) {
    document.getElementById('modal-image').src = imageSrc;
    document.getElementById('image-title').textContent = imageTitle || 'Preview Image';
    document.getElementById('image-modal').classList.remove('hidden');
}

// Close image modal
function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('spec-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSpecModal();   
    }
});

// Close image modal when clicking outside
document.getElementById('image-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();   
    }
});

// Handle escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSpecModal();
        closeImageModal();
    }
});
</script>
@endpush
