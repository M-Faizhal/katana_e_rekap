@extends('layouts.app')

@section('title', 'Ringkasan HPS - ' . ($proyek->nama_klien ?? 'Unknown') . ' - Cyber KATANA')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
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
                    <span class="font-medium">Total:</span> <span class="truncate text-green-600">{{ 'Rp ' . number_format($proyek->harga_total ?? 0, 0, ',', '.') }}</span>
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
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Total HPS</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-600 uppercase">Keterangan</th>
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
                            <td class="px-3 py-2">{{ 'Rp ' . number_format($item->harga_vendor ?? 0, 0, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format($item->diskon_amount ?? 0, 0, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format(($item->harga_akhir ?? $item->harga_diskon ?? 0), 0, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ number_format($item->qty ?? ($item->barang ? ($item->barang->pivot->jumlah ?? 1) : 1), 0, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ $item->barang->satuan ?? 'pcs' }}</td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format($item->total_harga_hpp ?? $item->jumlah_volume ?? 0, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 font-semibold">{{ 'Rp ' . number_format($item->hps ?? 0, 0, ',', '.') }}</td>
                            <td class="px-3 py-2">{{ $item->keterangan_1 ?? $item->keterangan_2 ?? '-' }}</td>
                           <td class="px-3 py-2">
                                {{ number_format($item->nett_percent ?? 0, 2, ',', '.') }}%
                            </td>
                            <td class="px-3 py-2">{{ 'Rp ' . number_format($item->nilai_nett_pcs ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="px-3 py-6 text-center text-gray-500">Belum ada data HPS untuk proyek ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($kalkulasiData->count())
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="10" class="px-3 py-3 text-right font-semibold">Total</td>
                        <td class="px-3 py-3 font-semibold">{{ 'Rp ' . number_format($kalkulasiData->sum('hps'), 0, ',', '.') }}</td>
                        <td class="px-3 py-3"></td>
                        <td class="px-3 py-3 font-semibold">{{ 'Rp ' . number_format($kalkulasiData->sum('nilai_nett_pcs'), 0, ',', '.') }}</td>
                        <td class="px-3 py-3 font-semibold">{{ 'Rp ' . number_format($kalkulasiData->sum('total_nett_pcs'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
