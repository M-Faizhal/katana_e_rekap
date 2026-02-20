@extends('layouts.app')

@section('title', 'Ringkasan HPS - ' . ($proyek->kode_proyek ?? 'Unknown') . ' - Cyber KATANA')

@section('content')
<style>
    /* CSS ini HANYA untuk print - tampilan web tidak berubah */
    @media print {
        /* Reset margin dan padding */
        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        
        * {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
        
        /* Sembunyikan elemen yang tidak perlu */
        nav, .no-print, button, .sidebar, header, footer {
            display: none !important;
        }
        
        /* Container full width saat print */
        body, .container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* Header compact */
        .page-header {
            margin-bottom: 8px !important;
            padding: 6px 8px !important;
        }
        
        .page-header h1 {
            font-size: 14px !important;
            margin: 0 !important;
        }
        
        .page-header .text-sm {
            font-size: 9px !important;
        }
        
        /* Section headers */
        h2, h3 {
            font-size: 11px !important;
            margin: 4px 0 !important;
            padding: 4px 6px !important;
        }
        
        /* Tabel super compact */
        table {
            width: 100% !important;
            font-size: 7px !important;
            border-collapse: collapse !important;
            page-break-inside: auto;
            margin: 0 !important;
        }
        
        table thead {
            display: table-header-group;
            background-color: #f9fafb !important;
        }
        
        table th {
            padding: 3px 2px !important;
            font-size: 7px !important;
            border: 1px solid #e5e7eb !important;
            background-color: #f9fafb !important;
        }
        
        table td {
            padding: 2px 3px !important;
            font-size: 7px !important;
            border: 1px solid #e5e7eb !important;
        }
        
        table tfoot td {
            font-weight: bold !important;
            background-color: #f9fafb !important;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        /* Summary cards ultra compact */
        .summary-section {
            margin: 6px 0 !important;
            padding: 4px !important;
            page-break-inside: avoid;
        }
        
        .grid {
            display: grid !important;
            gap: 4px !important;
        }
        
        .grid > div {
            padding: 4px 6px !important;
        }
        
        .grid .text-sm {
            font-size: 7px !important;
        }
        
        .grid .text-lg {
            font-size: 9px !important;
        }
        
        .grid .text-xs {
            font-size: 6px !important;
        }
        
        /* Remove shadows and adjust borders for print */
        .rounded-lg, .shadow-sm {
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        
        /* Compact spacing */
        .mb-6 {
            margin-bottom: 4px !important;
        }
        
        .mb-4 {
            margin-bottom: 4px !important;
        }
        
        .mb-2 {
            margin-bottom: 4px !important;
        }
        
        .p-4 {
            padding: 4px !important;
        }
        
        .p-3 {
            padding: 4px !important;
        }
        
        .p-2 {
            padding: 4px !important;
        }
        
        /* Hide overflow scroll */
        .overflow-x-auto {
            overflow: visible !important;
        }
        
        /* Ensure colors print */
        .text-green-600, .text-blue-600, .text-orange-600, 
        .text-purple-600, .text-red-700, .text-yellow-700,
        .bg-gray-50, .bg-white, .bg-red-50 {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 page-header">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-semibold text-gray-900 truncate">Ringkasan Item HPS</h1>
                <div class="text-sm text-gray-600 mt-1 flex flex-wrap items-center gap-2">
                    <span class="font-medium">Proyek:</span> <span class="truncate">{{ $proyek->kode_proyek ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">ID:</span> <span>{{ $proyek->id_proyek ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">Instansi:</span> <span class="truncate">{{ $proyek->instansi ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">Wilayah:</span> <span class="truncate">{{ $proyek->kab_kota ?? '-' }}</span>
                    <span class="hidden sm:inline">|</span>
                    <span class="font-medium">Total:</span> <span class="truncate text-green-600">{{ 'Rp ' . number_format($proyek->harga_total ?? 0, 2, ',', '.') }}</span>
                </div>
            </div>
           
        </div>
    </div>
        <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Tabel Ringkasan HPS Per Item</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">No</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Nama Barang</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Vendor</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Jenis Vendor</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Harga Awal</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Diskon</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Harga Akhir</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Qty</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Satuan</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Jumlah volume yang dikerjakan</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Harga Jual</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Total HPS</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Keterangan</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">TKDN</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Nett Per PCS</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Total Nett Per PCS</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kalkulasiData as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">{{ $index + 1 }}</td>
                            <td class="px-3 py-2">{{ $item->barang->nama_barang ?? ($item->keterangan_1 ?? 'Item') }}</td>
                            <td class="px-3 py-2">{{ $item->vendor->nama_vendor ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->jenis_vendor ?? ($item->vendor->jenis_perusahaan ?? '-') }}</td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format($item->harga_vendor ?? 0, 2, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format($item->diskon_amount ?? 0, 2, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format(($item->harga_akhir ?? $item->harga_diskon ?? 0), 2, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ number_format($item->qty ?? ($item->barang ? ($item->barang->pivot->jumlah ?? 1) : 1), 2, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ $item->barang->satuan ?? 'pcs' }}</td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format($item->total_harga_hpp ?? $item->jumlah_volume ?? 0, 2, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format($item->harga_yang_diharapkan ?? 0, 2, ',', '.') }}</td>
                            <td class="px-3 py-2 font-semibold">{{ 'Rp ' . number_format($item->hps ?? 0, 2, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ $item->keterangan_1 ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->keterangan_2 ?? '-' }}</td>
                           <td class="px-3 py-2">
                                {{ number_format($item->nett_percent ?? 0, 2, ',', '.') }}%
                            </td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format($item->nilai_nett_pcs ?? 0, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16" class="px-3 py-6 text-center text-gray-500">Belum ada data HPS untuk proyek ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($kalkulasiData->count())
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="11" class="px-3 py-3 text-right font-semibold">Total</td>
                        <td class="px-3 py-3 font-semibold">{{ 'Rp ' . number_format($kalkulasiData->sum('hps'), 2, ',', '.') }}</td>
                        <td class="px-3 py-3"></td>
                        <td class="px-3 py-3"></td>
                        <td class="px-3 py-3"></td>
                      
                        <td class="px-3 py-3 font-semibold">{{ 'Rp ' . number_format($kalkulasiData->sum('total_nett_pcs'), 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
            <!-- Biaya Tidak Langsung Section -->
            <div class="bg-gray-50 rounded-lg p-4 mb-2 summary-section">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Biaya Tidak Langsung</h3>
                
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 text-center">
                <!-- Dinas -->
                <div class="bg-white rounded-lg p-3 border">
                    <div class="text-sm text-gray-600">Dinas</div>
                    <div class="text-lg font-bold text-blue-600" id="omzet-dinas">
                        {{ 'Rp ' . number_format($kalkulasiData->sum('omzet_dinas'), 2, ',', '.') }}
                        ({{ number_format($kalkulasiData->avg('omzet_dinas_percent'), 1, ',', '.') }}%)
                    </div>
                    <div class="text-xs text-gray-500">Biaya dinas</div>
                </div>

                <!-- Bendera -->
                <div class="bg-white rounded-lg p-3 border">
                    <div class="text-sm text-gray-600">Bendera</div>
                    <div class="text-lg font-bold text-green-600" id="bendera">
                        {{ 'Rp ' . number_format($kalkulasiData->sum('bendera'), 2, ',', '.') }}
                        ({{ number_format($kalkulasiData->avg('bendera_percent'), 1, ',', '.') }}%)
                    </div>
                    <div class="text-xs text-gray-500">Biaya bendera</div>
                </div>

                <!-- Bank Cost -->
                <div class="bg-white rounded-lg p-3 border">
                    <div class="text-sm text-gray-600">Bank Cost</div>
                    <div class="text-lg font-bold text-orange-600" id="bank-cost">
                        {{ 'Rp ' . number_format($kalkulasiData->sum('bank_cost'), 2, ',', '.') }}
                        ({{ number_format($kalkulasiData->avg('bank_cost_percent'), 1, ',', '.') }}%)
                    </div>
                    <div class="text-xs text-gray-500">Biaya administrasi bank</div>
                </div>

                <!-- Biaya Operasional -->
                <div class="bg-white rounded-lg p-3 border">
                    <div class="text-sm text-gray-600">Biaya Operasional</div>
                    <div class="text-lg font-bold text-purple-600" id="biaya-operasional">
                        {{ 'Rp ' . number_format($kalkulasiData->sum('biaya_ops'), 2, ',', '.') }}
                        ({{ number_format($kalkulasiData->avg('biaya_ops_percent'), 1, ',', '.') }}%)
                    </div>
                    <div class="text-xs text-gray-500">Biaya operasional</div>
                </div>


            <!-- Subtotal Biaya Tidak Langsung -->
            <div class="bg-white rounded-lg p-3 border-2 border-red-300">
                <div class="text-sm text-gray-600 font-semibold">Subtotal Biaya Tidak Langsung</div>
                <div class="text-lg font-bold text-red-700" id="subtotal-tidak-langsung">
                    @php
                        $subtotalTidakLangsung = $kalkulasiData->sum('omzet_dinas') + 
                                                $kalkulasiData->sum('bendera') + 
                                                $kalkulasiData->sum('bank_cost') + 
                                                $kalkulasiData->sum('gross_biaya_ops');
                    @endphp
                    {{ 'Rp ' . number_format($subtotalTidakLangsung, 2, ',', '.') }}
                </div>
                <div class="text-xs text-gray-500">Total keseluruhan</div>
            </div>
        </div>
        
    </div>
    <div class="bg-gray-50 rounded-lg p-4 mb-2 summary-section">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Rincian Proyek</h3>
        <!-- Additional Summary Details -->
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 text-center">
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Items</div>
                <div class="text-sm font-semibold" id="total-items">{{ $kalkulasiData->count() }}</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Diskon</div>
                <div class="text-sm font-semibold" id="total-diskon">{{ 'Rp ' . number_format($kalkulasiData->sum('total_diskon'), 2, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Volume</div>
                <div class="text-sm font-semibold" id="total-volume">{{ 'Rp ' . number_format($kalkulasiData->sum('jumlah_volume'), 2, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total DPP</div>
                <div class="text-sm font-semibold" id="total-dpp">{{ 'Rp ' . number_format($kalkulasiData->sum('nilai_dpp'), 2, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Asumsi Cair</div>
                <div class="text-sm font-semibold" id="total-asumsi-cair">{{ 'Rp ' . number_format($kalkulasiData->sum('nilai_asumsi_cair'), 2, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-lg p-2 border">
                <div class="text-xs text-gray-600">Total Ongkir</div>
                <div class="text-sm font-semibold" id="total-ongkir">{{ 'Rp ' . number_format($kalkulasiData->sum('ongkir'), 2, ',', '.') }}</div>
            </div>
        </div>
        </h3>
        </div>
    <!-- Summary Cards Section -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6 summary-section">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Total Kalkulasi HPS</h3>
        
        <!-- Main Summary Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-center mb-4">
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Total HPP (Modal)</div>
                <div class="text-lg font-bold text-yellow-700" id="grand-total-hpp">{{ 'Rp ' . number_format($kalkulasiData->sum('jumlah_volume'), 2, ',', '.') }}</div>
                <div class="text-xs text-gray-500">Harga beli dari vendor</div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Total HPS</div>
                <div class="text-lg font-bold text-blue-700" id="grand-total-hps">{{ 'Rp ' . number_format($kalkulasiData->sum('hps'), 2, ',', '.') }}</div>
                <div class="text-xs text-gray-500">Harga penawaran ke klien</div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Total Nett</div>
                <div class="text-lg font-bold text-green-700" id="grand-total-nett">{{ 'Rp ' . number_format($kalkulasiData->sum('nett_income'), 2, ',', '.') }}</div>
                <div class="text-xs text-gray-500">Pendapatan bersih</div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Rata-rata % Nett</div>
                <div class="text-lg font-bold text-red-700" id="grand-avg-nett">
                    @php
                        $totalNettIncome = $kalkulasiData->sum('nett_income');
                        $totalAsumsiCair = $kalkulasiData->sum('nilai_asumsi_cair');
                    @endphp
                    @if($totalAsumsiCair > 0)
                        {{ number_format(($totalNettIncome / $totalAsumsiCair) * 100, 2, ',', '.') }}%
                    @else
                        0,00%
                    @endif
                </div>
                <div class="text-xs text-gray-500">Margin bersih</div>
            </div>
        </div>
        
        <!-- Secondary Summary Row - Selisih Pagu -->
        @php
            $totalPermintaanKlien = $proyek->proyekBarang ? $proyek->proyekBarang->sum('harga_total') : 0;
            $totalHps = $kalkulasiData->sum('hps');
            $selisihPaguHps = $totalPermintaanKlien - $totalHps;
            $persenSelisih = $totalPermintaanKlien > 0 ? ($selisihPaguHps / $totalPermintaanKlien * 100) : 0;
        @endphp
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-center mb-4">
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Selisih Pagu dan HPS</div>
                <div class="text-lg font-bold {{ $selisihPaguHps >= 0 ? 'text-green-700' : 'text-red-700' }}">
                    {{ 'Rp ' . number_format($selisihPaguHps, 2, ',', '.') }}
                </div>
            </div>
            <div class="bg-white rounded-lg p-3 border">
                <div class="text-sm text-gray-600">Persentase Selisih</div>
                <div class="text-lg font-bold {{ $persenSelisih >= 0 ? 'text-green-700' : 'text-red-700' }}">
                    {{ number_format($persenSelisih, 2) }}%
                </div>
            </div>
        </div>
        
        
    </div>

   


</div>
@endsection