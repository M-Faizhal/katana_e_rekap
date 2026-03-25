@extends('layouts.app')

@section('title', 'Pembuatan Invoice - Cyber KATANA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-red-800 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">Pembuatan Invoice</h1>
                <p class="text-red-100 mt-1 text-sm sm:text-base">Isi form untuk generate surat invoice (PDF).</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <a href="{{ route('keuangan.pembuatan-invoice-proyek.preview', $proyek->id_proyek) }}" target="_blank"
                   class="text-xs px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg">
                    Preview PDF
                </a>
                <a href="{{ route('keuangan.pembuatan-invoice-proyek.download', $proyek->id_proyek) }}"
                   class="text-xs px-3 py-1.5 bg-white text-red-800 hover:bg-red-50 rounded-lg font-medium">
                    Download PDF
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mt-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-gray-500">Proyek</div>
                <div class="text-lg font-semibold text-gray-900">{{ $proyek->kode_proyek ?? '-' }} — {{ $proyek->instansi ?? '-' }}</div>
                <div class="text-sm text-gray-600">{{ $proyek->kab_kota ?? '-' }}</div>
            </div>
            <div class="text-sm text-gray-700">
                <div class="flex items-center gap-2">
                    <span class="text-gray-500">Penawaran ACC:</span>
                    <span class="font-medium">{{ $penawaran->no_penawaran ?? ('#' . $penawaran->id_penawaran) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
        <form id="form-invoice" action="{{ route('keuangan.pembuatan-invoice-proyek.store', $proyek->id_proyek) }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="id_penawaran" value="{{ $penawaran->id_penawaran }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat"
                           value="{{ old('tanggal_surat', optional($invoice->tanggal_surat ?? null)->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('tanggal_surat')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat / Invoice</label>
                    <input type="text" name="nomor_surat"
                           value="{{ old('nomor_surat', $invoice->nomor_surat ?? '') }}"
                           placeholder="Contoh: 060/INV/KTN/XII/2025"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('nomor_surat')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="border rounded-xl p-4">
                    <div class="text-sm font-semibold text-gray-900 mb-3">Bill To</div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instansi</label>
                        <input type="text" name="bill_to_instansi"
                               value="{{ old('bill_to_instansi', $invoice->bill_to_instansi ?? ($proyek->instansi ?? '')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('bill_to_instansi')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="bill_to_alamat" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('bill_to_alamat', $invoice->bill_to_alamat ?? '') }}</textarea>
                        @error('bill_to_alamat')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="border rounded-xl p-4">
                    <div class="text-sm font-semibold text-gray-900 mb-3">Ship To</div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instansi</label>
                        <input type="text" name="ship_to_instansi"
                               value="{{ old('ship_to_instansi', $invoice->ship_to_instansi ?? ($proyek->instansi ?? '')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('ship_to_instansi')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="ship_to_alamat" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('ship_to_alamat', $invoice->ship_to_alamat ?? '') }}</textarea>
                        @error('ship_to_alamat')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border rounded-xl p-4">
                <div>
                    <div class="text-sm font-semibold text-gray-900">Data Barang (Penawaran Detail)</div>
                    <div class="text-xs text-gray-500">Isi keterangan per barang (RTE) untuk tampil di invoice.</div>
                </div>

                <div class="mt-4 space-y-4">
                    @foreach ($penawaran->penawaranDetail as $idx => $d)
                        @php
                            $detailId = $d->id_detail;
                            $oldKet = old("items.$idx.keterangan_html");
                            $savedKet = $keteranganMap[$detailId] ?? null;
                            $ketValue = $oldKet !== null ? $oldKet : ($savedKet ?? '');
                        @endphp

                        <div class="border rounded-xl overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $d->nama_barang }}</div>
                                    <div class="text-xs text-gray-600">
                                        Qty: {{ $d->qty }} {{ $d->satuan }}
                                        • Harga: Rp {{ number_format((float)$d->harga_satuan, 0, ',', '.') }}
                                        • Subtotal: Rp {{ number_format((float)$d->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                <input type="hidden" name="items[{{ $idx }}][id_penawaran_detail]" value="{{ $detailId }}">

                                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Rich Text)</label>
                                <div class="border border-gray-300 rounded-lg overflow-hidden">
                                    <div class="flex items-center gap-2 px-2 py-2 bg-gray-50 border-b border-gray-200 flex-wrap">
                                        <button type="button" class="inv-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="inv-rt-{{ $detailId }}" data-cmd="bold"><b>B</b></button>
                                        <button type="button" class="inv-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="inv-rt-{{ $detailId }}" data-cmd="italic"><i>I</i></button>
                                        <button type="button" class="inv-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="inv-rt-{{ $detailId }}" data-cmd="insertUnorderedList">• List</button>
                                        <button type="button" class="inv-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="inv-rt-{{ $detailId }}" data-cmd="insertOrderedList">1. List</button>
                                    </div>

                                    <div id="inv-rt-{{ $detailId }}" class="inv-rt-editor p-3 text-sm min-h-[92px]" contenteditable="true">{!! $ketValue !!}</div>
                                </div>

                                <input type="hidden" name="items[{{ $idx }}][keterangan_html]" id="inv-rt-hidden-{{ $detailId }}" value="">

                                @error("items.$idx.keterangan_html")
                                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-xs text-gray-500">
                    Tip: Klik <b>Preview PDF</b> setelah simpan untuk melihat hasil di template invoice.
                </div>
                <button type="submit" class="px-4 py-2 bg-red-700 hover:bg-red-800 text-white rounded-lg text-sm font-medium">
                    Simpan Invoice
                </button>
            </div>
        </form>

        <div class="mt-4 sm:hidden flex items-center gap-2">
            <a href="{{ route('keuangan.pembuatan-invoice-proyek.preview', $proyek->id_proyek) }}" target="_blank"
               class="w-1/2 text-center text-xs px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                Preview PDF
            </a>
            <a href="{{ route('keuangan.pembuatan-invoice-proyek.download', $proyek->id_proyek) }}"
               class="w-1/2 text-center text-xs px-3 py-2 bg-red-700 hover:bg-red-800 text-white rounded-lg">
                Download PDF
            </a>
        </div>
    </div>
</div>

<script>
(function() {
    const form = document.getElementById('form-invoice');

    function syncEditors() {
        document.querySelectorAll('.inv-rt-editor').forEach((ed) => {
            const id = ed.getAttribute('id');
            const detailId = id?.replace('inv-rt-', '');
            if (!detailId) return;
            const hidden = document.getElementById('inv-rt-hidden-' + detailId);
            if (hidden) hidden.value = ed.innerHTML;
        });
    }

    // toolbar
    document.querySelectorAll('.inv-rt-btn').forEach((btn) => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const cmd = this.getAttribute('data-cmd');
            const editor = document.getElementById(targetId);
            if (!editor) return;
            editor.focus();
            document.execCommand(cmd, false, null);
        });
    });

    // init hidden values
    syncEditors();

    // submit
    form?.addEventListener('submit', function() {
        syncEditors();
    });
})();
</script>
@endsection
