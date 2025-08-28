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
                <!-- Approval Buttons - Show only if proyek status is penawaran and penawaran status is Menunggu -->
                @if($proyek->status === 'Penawaran' && $penawaran->status === 'Menunggu')
                    <button onclick="updateProyekStatus('tidak_setuju', {{ $proyek->id_proyek }}, {{ $penawaran->id_penawaran }})" 
                            class="approval-btn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-times mr-2"></i>
                        Tidak Setuju
                    </button>
                    <button onclick="updateProyekStatus('setuju', {{ $proyek->id_proyek }}, {{ $penawaran->id_penawaran }})" 
                            class="approval-btn bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-check mr-2"></i>
                        Setuju
                    </button>
                @endif
                
                <a href="{{ route('purchasing.kalkulasi') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Status Action Section -->
    @if($proyek->status === 'Penawaran' && $penawaran->status === 'Menunggu')
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800">
                    Penawaran Menunggu Persetujuan
                </h3>
                <p class="text-sm text-yellow-700 mt-1">
                    Penawaran ini membutuhkan persetujuan Anda. Silakan tinjau detail penawaran dan pilih "Setuju" untuk melanjutkan ke tahap pembayaran atau "Tidak Setuju" untuk mengembalikan ke tahap menunggu.
                </p>
            </div>
        </div>
    </div>
    @elseif($penawaran->status === 'ACC')
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">
                    Penawaran Disetujui
                </h3>
                <p class="text-sm text-green-700 mt-1">
                    Penawaran ini telah disetujui dan proyek dapat dilanjutkan ke tahap pembayaran.
                </p>
            </div>
        </div>
    </div>
    @elseif($penawaran->status === 'Ditolak')
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-times-circle text-red-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    Penawaran Ditolak
                </h3>
                <p class="text-sm text-red-700 mt-1">
                    Penawaran ini telah ditolak dan proyek dikembalikan ke tahap menunggu untuk evaluasi ulang.
                </p>
            </div>
        </div>
    </div>
    @endif

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
                    <label class="text-sm font-medium text-gray-500">Nama Klien</label>
                    <p class="text-gray-900 font-medium">{{ $proyek->nama_klien }}</p>
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
                    <a href="#" class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Surat Penawaran
                    </a>
                    @endif
                    @if($penawaran->surat_pesanan)
                    <a href="#" class="inline-flex items-center px-3 py-2 bg-green-100 text-green-700 rounded-lg text-sm hover:bg-green-200">
                        <i class="fas fa-file-contract mr-2"></i>
                        Surat Pesanan
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
                            {{ number_format($kalkulasi->total_harga ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->persen_kenaikan ?? 0, 1) }}%
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->proyeksi_kenaikan ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->ppn_dinas ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->pph_dinas ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right font-bold bg-yellow-50 border-r">
                            {{ number_format($kalkulasi->hps ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->dpp ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->asumsi_nilai_cair ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->ongkir ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->omzet_nilai_dinas ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->gross_nilai_bendera ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->gross_nilai_bank_cost ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right border-r">
                            {{ number_format($kalkulasi->gross_nilai_biaya_ops ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right font-bold bg-green-50 border-r">
                            {{ number_format($kalkulasi->nilai_nett_income ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-2 text-right font-bold bg-green-50">
                            {{ number_format($kalkulasi->nett_income_persentase ?? 0, 2) }}%
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
                            {{ number_format($kalkulasiData->sum('total_harga'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">-</td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('proyeksi_kenaikan'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('ppn_dinas'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('pph_dinas'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right text-orange-700 bg-yellow-100 border-r">
                            {{ number_format($kalkulasiData->sum('hps'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('dpp'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('asumsi_nilai_cair'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('ongkir'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('omzet_nilai_dinas'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('gross_nilai_bendera'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('gross_nilai_bank_cost'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right border-r">
                            {{ number_format($kalkulasiData->sum('gross_nilai_biaya_ops'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right text-green-700 bg-green-100 border-r">
                            {{ number_format($kalkulasiData->sum('nilai_nett_income'), 0, ',', '.') }}
                        </td>
                        <td class="px-2 py-3 text-right text-green-700 bg-green-100">
                            @php
                                $avgNett = $kalkulasiData->count() > 0 ? $kalkulasiData->avg('nett_income_persentase') : 0;
                            @endphp
                            {{ number_format($avgNett, 2) }}%
                        </td>
                    </tr>
                </tfoot>
            </table>
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

<style>
/* Approval buttons styling */
.approval-btn {
    position: relative;
    overflow: hidden;
}

.approval-btn:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.approval-btn:hover:before {
    left: 100%;
}

.approval-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.approval-btn:disabled:hover:before {
    display: none;
}

/* Success message animation */
.status-success {
    animation: statusPulse 2s ease-in-out;
}

@keyframes statusPulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
    }
}

/* Rejected message animation */
.status-rejected {
    animation: statusShake 0.5s ease-in-out;
}

@keyframes statusShake {
    0%, 100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-5px);
    }
    75% {
        transform: translateX(5px);
    }
}
</style>

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

// Close modal when clicking outside
document.getElementById('spec-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSpecModal();
    }
});

// Update Proyek Status based on approval/rejection
function updateProyekStatus(action, proyekId, penawaranId) {
    let confirmMessage, newStatus, penawaranStatus;
    
    if (action === 'setuju') {
        confirmMessage = 'Apakah Anda yakin ingin menyetujui penawaran ini?\n\nSetelah disetujui:\n• Status penawaran akan menjadi "ACC"\n• Status proyek akan berubah menjadi "Pembayaran"\n• Proyek dapat dilanjutkan ke tahap pembayaran';
        newStatus = 'Pembayaran';
        penawaranStatus = 'ACC';
    } else {
        confirmMessage = 'Apakah Anda yakin ingin menolak penawaran ini?\n\nSetelah ditolak:\n• Status penawaran akan menjadi "Ditolak"\n• Status proyek akan berubah kembali ke "Menunggu"\n• Proyek perlu dievaluasi ulang';
        newStatus = 'Menunggu';
        penawaranStatus = 'Ditolak';
    }
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    // Show loading overlay
    showLoadingOverlay(true);
    
    // Disable buttons and show loading
    const buttons = document.querySelectorAll('button[onclick*="updateProyekStatus"]');
    buttons.forEach(btn => {
        btn.disabled = true;
        const originalContent = btn.innerHTML;
        btn.setAttribute('data-original-content', originalContent);
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    });
    
    fetch(`/purchasing/penawaran/${penawaranId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: action,
            proyek_id: proyekId,
            penawaran_status: penawaranStatus,
            proyek_status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        showLoadingOverlay(false);
        
        if (data.success) {
            // Show success animation
            showSuccessMessage(data.message, action === 'setuju' ? 'success' : 'warning');
            
            // Reload page after a short delay to see the animation
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showErrorMessage(data.message);
            resetButtons();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showLoadingOverlay(false);
        showErrorMessage('Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.');
        resetButtons();
    });
}

function showLoadingOverlay(show) {
    let overlay = document.getElementById('loading-overlay');
    
    if (show) {
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            overlay.innerHTML = `
                <div class="bg-white rounded-lg p-6 shadow-xl">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="text-gray-700 font-medium">Memproses permintaan...</span>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
        }
        overlay.classList.remove('hidden');
    } else {
        if (overlay) {
            overlay.classList.add('hidden');
        }
    }
}

function showSuccessMessage(message, type = 'success') {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-orange-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas ${icon} text-xl"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Slide in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Slide out after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

function showErrorMessage(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Slide in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Slide out after 5 seconds (longer for error messages)
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

function resetButtons() {
    const buttons = document.querySelectorAll('button[onclick*="updateProyekStatus"]');
    buttons.forEach(btn => {
        btn.disabled = false;
        const originalContent = btn.getAttribute('data-original-content');
        if (originalContent) {
            btn.innerHTML = originalContent;
        }
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}
</script>
@endpush
