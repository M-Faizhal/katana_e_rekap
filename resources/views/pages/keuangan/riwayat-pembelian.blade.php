@extends('layouts.app')

@section('title', 'Riwayat Pembelian - Cyber KATANA')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- ── Header ─────────────────────────────────────────────────────────────── --}}
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl lg:text-4xl font-bold mb-2">Riwayat Pembelian</h1>
                <p class="text-white text-base lg:text-lg opacity-90">
                    Rekap pembelian per proyek, vendor, barang beserta status PPN
                </p>
            </div>
            <div class="hidden lg:flex items-center justify-center w-20 h-20  rounded-2xl">
                <i class="fas fa-receipt text-4xl opacity-80"></i>
            </div>
        </div>
    </div>



    {{-- ── Filter & Search ─────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow mb-6 p-5">
        <form method="GET" action="{{ route('keuangan.riwayat-pembelian') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Proyek / Instansi</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Kode proyek, instansi..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
            <div class="min-w-36">
                <label class="block text-xs font-medium text-gray-600 mb-1">Status Bayar</label>
                <select name="status_filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    <option value="all" {{ $filterStatus === 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="lunas" {{ $filterStatus === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum_lunas" {{ $filterStatus === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
            </div>
            <div class="min-w-36">
                <label class="block text-xs font-medium text-gray-600 mb-1">PPN</label>
                <select name="ppn_filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    <option value="all" {{ $filterPpn === 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="ada_ppn" {{ $filterPpn === 'ada_ppn' ? 'selected' : '' }}>Ada PPN</option>
                    <option value="non_ppn" {{ $filterPpn === 'non_ppn' ? 'selected' : '' }}>Non-PPN</option>
                </select>
            </div>
            <div class="min-w-32">
                <label class="block text-xs font-medium text-gray-600 mb-1">Urutan</label>
                <select name="sort_by" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    <option value="desc" {{ $sortBy === 'desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="asc" {{ $sortBy === 'asc' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('keuangan.riwayat-pembelian') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
                <a href="{{ route('keuangan.riwayat-pembelian.export', request()->only(['search','status_filter','ppn_filter','sort_by'])) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
            </div>
        </form>
    </div>

    {{-- ── Data Proyek ──────────────────────────────────────────────────────────── --}}
    @if($paginated->isEmpty())
    <div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
        <i class="fas fa-folder-open text-5xl mb-4 opacity-40"></i>
        <p class="text-lg font-medium">Tidak ada data pembelian ditemukan</p>
        <p class="text-sm">Coba ubah filter atau kata kunci pencarian</p>
    </div>
    @else

    @foreach($paginated as $row)
    @php
        $proyek  = $row['proyek'];
        $vendors = $row['vendors'];
    @endphp
    <div class="bg-white rounded-xl shadow mb-6 overflow-hidden border border-red-600">

        {{-- Proyek Header --}}
        <div class="flex items-center justify-between px-6 py-4  border-b border-red-600">
            <div class="flex items-center gap-4">
                <div class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                    {{ $proyek->kode_proyek }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-base">{{ $proyek->instansi }}</p>
                    <p class="text-xs text-gray-500">{{ $proyek->kab_kota }} &nbsp;|&nbsp; No. Penawaran: {{ $proyek->penawaranAktif->no_penawaran }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                {{-- Badge Status Proyek --}}
                @php
                    $statusColor = match($proyek->status) {
                        'Selesai'   => 'bg-green-100 text-green-800 border-green-300',
                        'Pengiriman'=> 'bg-blue-100 text-blue-800 border-blue-300',
                        'Pembayaran'=> 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'Gagal'     => 'bg-red-100 text-red-800 border-red-300',
                        default     => 'bg-gray-100 text-gray-600 border-gray-300',
                    };
                @endphp
                <span class="text-xs font-medium border px-2.5 py-1 rounded-full {{ $statusColor }}">
                    {{ $proyek->status }}
                </span>
                {{-- Badge Lunas --}}
                @if($row['status_lunas'])
                <span class="text-xs font-semibold bg-green-100 text-green-800 border border-green-300 px-2.5 py-1 rounded-full">
                    <i class="fas fa-check mr-1"></i>Lunas
                </span>
                @else
                <span class="text-xs font-semibold bg-red-100 text-red-700 border border-red-300 px-2.5 py-1 rounded-full">
                    <i class="fas fa-clock mr-1"></i>Belum Lunas
                </span>
                @endif
                {{-- Badge PPN --}}
                @if($row['ada_ppn'])
                <span class="text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-300 px-2.5 py-1 rounded-full">
                    <i class="fas fa-percentage mr-1"></i>Ada PPN
                </span>
                @endif
                <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}"
                   class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                    <i class="fas fa-external-link-alt"></i> Detail
                </a>
            </div>
        </div>

        {{-- Ringkasan Keuangan Proyek --}}
        <div class="grid grid-cols-3 divide-x bg-gray-50 text-sm">
            <div class="px-5 py-3">
                <p class="text-xs text-gray-500">Total Nilai Pembelian</p>
                <p class="font-bold text-gray-800">Rp {{ number_format($row['grand_total'], 2, ',', '.') }}</p>
            </div>
            <div class="px-5 py-3">
                <p class="text-xs text-gray-500">Sudah Dibayar (Approved)</p>
                <p class="font-bold text-green-700">Rp {{ number_format($row['grand_bayar'], 2, ',', '.') }}</p>
            </div>
            <div class="px-5 py-3">
                <p class="text-xs text-gray-500">Sisa</p>
                <p class="font-bold {{ $row['grand_sisa'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                    Rp {{ number_format($row['grand_sisa'], 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Vendor + Barang --}}
        @foreach($vendors as $vendor)
        @php
            $adaPpnVendor = collect($vendor['items'])->contains(fn($i) => $i['ada_ppn'] === true);
        @endphp
        <div class="border-t border-gray-100">

            {{-- Vendor Sub-header --}}
            <div class="flex items-center justify-between px-6 py-2.5 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <i class="fas fa-store text-gray-400 text-sm"></i>
                    <span class="font-semibold text-gray-700 text-sm">{{ $vendor['vendor_nama'] }}</span>
                    @if($adaPpnVendor)
                        <span class="text-xs bg-amber-100 text-amber-700 border border-amber-300 px-2 py-0.5 rounded-full font-medium">
                            <i class="fas fa-percentage mr-0.5"></i>Ada PPN
                        </span>
                    @elseif($vendor['has_ppn_snapshot'])
                        <span class="text-xs bg-green-100 text-green-700 border border-green-300 px-2 py-0.5 rounded-full font-medium">
                            Non-PPN
                        </span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-500 border border-gray-300 px-2 py-0.5 rounded-full">
                            Belum Dikonfigurasi
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span>Total: <strong class="text-gray-700">Rp {{ number_format($vendor['total_harga'], 2, ',', '.') }}</strong></span>
                    <span>Dibayar: <strong class="text-green-700">Rp {{ number_format($vendor['total_bayar'], 2, ',', '.') }}</strong></span>
                    <span>Sisa: <strong class="{{ $vendor['sisa_bayar'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                        Rp {{ number_format($vendor['sisa_bayar'], 2, ',', '.') }}
                    </strong></span>

                </div>
            </div>

            {{-- Tabel Barang --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                            <th class="px-5 py-2 text-center text-xs font-medium text-gray-500 uppercase">Satuan</th>
                            <th class="px-5 py-2 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-5 py-2 text-right text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                            <th class="px-5 py-2 text-right text-xs font-medium text-gray-500 uppercase">Harga Akhir (inc PPN)</th>
                            <th class="px-5 py-2 text-center text-xs font-medium text-gray-500 uppercase">PPN %</th>
                            <th class="px-5 py-2 text-right text-xs font-medium text-gray-500 uppercase">DPP</th>
                            <th class="px-5 py-2 text-right text-xs font-medium text-gray-500 uppercase">Nominal PPN</th>
                            <th class="px-5 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total HPP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($vendor['items'] as $item)
                        @php
                            $ppnKonfigured = $item['ada_ppn'] !== null; // null = belum pernah dikonfigurasi
                        @endphp
                        <tr class="{{ $item['ada_ppn'] ? 'bg-amber-50/30' : '' }} hover:bg-gray-50 transition">
                            <td class="px-5 py-2.5 font-medium text-gray-800">{{ $item['nama_barang'] }}</td>
                            <td class="px-5 py-2.5 text-center text-gray-500">{{ $item['satuan'] }}</td>
                            <td class="px-5 py-2.5 text-right text-gray-700">{{ number_format($item['qty'], 0, ',', '.') }}</td>
                            <td class="px-5 py-2.5 text-right text-gray-700">
                                Rp {{ number_format($item['harga_satuan'], 2, ',', '.') }}

                            </td>
                            <td class="px-5 py-2.5 text-right  text-gray-700">
                                Rp {{ number_format($item['harga_akhir'], 2, ',', '.') }}
                            </td>
                            <td class="px-5 py-2.5 text-center">
                                @if($item['ada_ppn'] === true)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        {{ $item['persen_ppn'] ?? 11 }}%
                                    </span>
                                @elseif($item['ada_ppn'] === false)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400">
                                        Non-PPN
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400 italic">
                                        —
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-right text-gray-600">
                                @if($item['ada_ppn'] === true)
                                    Rp {{ number_format($item['harga_sebelum_ppn'], 2, ',', '.') }}
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-right font-semibold {{ $item['ada_ppn'] === true ? 'text-amber-700' : 'text-gray-400' }}">
                                @if($item['ada_ppn'] === true)
                                    Rp {{ number_format($item['nominal_ppn'], 2, ',', '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-right text-gray-700 font-semibold">Rp {{ number_format($item['total_harga_hpp'], 2, ',', '.') }}</td>

                        </tr>
                        @endforeach
                    </tbody>
                    {{-- Footer subtotal vendor --}}
                    <tfoot class="border-t-2 {{ $adaPpnVendor ? 'border-amber-200 bg-amber-50' : 'border-gray-200 bg-gray-50' }}">
                        <tr>
                            <td colspan="4" class="px-5 py-2.5 text-right text-xs font-bold text-gray-600 uppercase">Subtotal Vendor</td>
                            <td class="px-5 py-2.5 text-right font-bold text-gray-800">
                                Rp {{ number_format(collect($vendor['items'])->sum('total_harga_hpp'), 2, ',', '.') }}
                            </td>
                            <td></td>
                            <td class="px-5 py-2.5 text-right font-bold text-gray-700">
                                @if($adaPpnVendor)
                                Rp {{ number_format(collect($vendor['items'])->sum(fn($i) => floatval($i['harga_sebelum_ppn'] ?? 0)), 2, ',', '.') }}
                                @else —
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-right font-bold {{ $adaPpnVendor ? 'text-amber-700' : 'text-gray-400' }}">
                                @if($adaPpnVendor)
                                Rp {{ number_format(collect($vendor['items'])->sum(fn($i) => floatval($i['nominal_ppn'] ?? 0)), 2, ',', '.') }}
                                @else —
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-right font-bold text-gray-800">
                                Rp {{ number_format($vendor['total_harga'], 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
        @endforeach

    </div>
    @endforeach

    {{-- ── Pagination ──────────────────────────────────────────────────────────── --}}
    <div class="mt-6">
        {{ $paginated->links() }}
    </div>

    @endif

</div>
@endsection
