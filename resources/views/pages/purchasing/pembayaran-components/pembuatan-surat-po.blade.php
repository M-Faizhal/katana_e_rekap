@extends('layouts.app')

@section('title', 'Pembuatan Surat PO - Cyber KATANA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="bg-red-800 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">Pembuatan Surat PO</h1>
                <p class="text-red-100 mt-1 text-sm sm:text-base">Isi data PO, simpan draft, lalu preview dokumen.</p>
                <div class="text-xs text-red-100 mt-1">PO: <span class="font-semibold">{{ $proyek->kode_proyek ?? '-' }}</span> • Vendor: <span class="font-semibold">{{ $vendor->nama_vendor ?? '-' }}</span></div>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <a href="{{ route('purchasing.pembayaran', ['tab' => 'pembuatan-surat-po']) }}"
                   class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <a href="{{ route('purchasing.pembayaran.pembuatan-surat-po.preview', [$proyek->id_proyek, $vendor->id_vendor]) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-white text-red-800 hover:bg-gray-100 rounded-lg text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i>
                    Preview
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mt-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
    @endif

    <!-- Info Proyek / Vendor -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-gray-500">Proyek</div>
                <div class="text-lg font-semibold text-gray-900">{{ $proyek->kode_proyek ?? '-' }} — {{ $proyek->instansi ?? '-' }}</div>
                <div class="text-sm text-gray-600">{{ $proyek->kab_kota ?? '-' }}</div>
            </div>
            <div class="text-sm text-gray-700">
                <div class="text-gray-500">Vendor</div>
                <div class="font-medium text-gray-900">{{ $vendor->nama_vendor ?? '-' }}</div>
                <div class="text-sm text-gray-600">{{ $vendor->alamat ?? '-' }}</div>
            </div>
        </div>
    </div>

    <form id="form-surat-po" method="POST" action="{{ route('purchasing.pembayaran.pembuatan-surat-po.simpan', [$proyek->id_proyek, $vendor->id_vendor]) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf

        <!-- Meta + Vendor -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-file-invoice mr-2 text-red-700"></i>
                        Informasi
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Tanggal surat & nomor PO.</p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', optional($suratPo->tanggal_surat)->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('tanggal_surat')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PO #</label>
                        <input type="text" value="{{ $proyek->kode_proyek ?? '-' }}" disabled
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 text-sm">
                        <p class="text-xs text-gray-500 mt-1">PO # otomatis dari <code>kode_proyek</code>.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-industry mr-2 text-red-700"></i>
                        Vendor
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi vendor tujuan PO.</p>
                </div>
                <div class="p-6 text-sm text-gray-700 space-y-1">
                    <div class="font-semibold text-gray-900">{{ $vendor->nama_vendor ?? '-' }}</div>
                    <div>Alamat: {{ $vendor->alamat ?? '-' }}</div>
                    <div>Kontak: {{ $vendor->kontak ?? ($vendor->no_telp ?? '-') }}</div>
                </div>
            </div>
        </div>

        <!-- Ship To -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-map-marker-alt mr-2 text-red-700"></i>
                    Ship To
                </h2>
                <p class="text-sm text-gray-600 mt-1">Alamat pengiriman barang.</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instansi</label>
                    <input type="text" name="ship_to_instansi" value="{{ old('ship_to_instansi', $suratPo->ship_to_instansi ?? ($proyek->instansi ?? '')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('ship_to_instansi')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="ship_to_alamat" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('ship_to_alamat', $suratPo->ship_to_alamat ?? ($proyek->kab_kota ?? '')) }}</textarea>
                    @error('ship_to_alamat')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Comments + Totals -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-comment-dots mr-2 text-red-700"></i>
                        Comments / Special Instructions
                    </h2>
                </div>
                <div class="p-6">
                    @php
                        $commentsValue = old('comments_html', $suratPo->comments_html ?? '');
                    @endphp

                    <label class="block text-sm font-medium text-gray-700 mb-1">Comments (Rich Text)</label>
                    <div class="border border-gray-300 rounded-lg overflow-hidden bg-white">
                        <div class="flex items-center gap-2 px-2 py-2 bg-gray-50 border-b border-gray-200 flex-wrap">
                            <button type="button" class="po-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="po-rt-comments" data-cmd="bold"><b>B</b></button>
                            <button type="button" class="po-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="po-rt-comments" data-cmd="italic"><i>I</i></button>
                            <button type="button" class="po-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="po-rt-comments" data-cmd="insertUnorderedList">• List</button>
                            <button type="button" class="po-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="po-rt-comments" data-cmd="insertOrderedList">1. List</button>
                        </div>

                        <div id="po-rt-comments" class="po-rt-editor p-3 text-sm min-h-[220px]" contenteditable="true">{!! $commentsValue !!}</div>
                    </div>

                    <input type="hidden" name="comments_html" id="po-rt-hidden-comments" value="">

                    @error('comments_html')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-calculator mr-2 text-red-700"></i>
                        Totals
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Pajak/ongkir/lain-lain.</p>
                </div>
                <div class="p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">DPP (otomatis)</label>
                    <input type="text" value="Rp {{ number_format($dpp ?? 0, 2, ',', '.') }}" disabled
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 text-sm">

                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax</label>
                        <input type="number" step="0.01" name="tax" value="{{ old('tax', $suratPo->tax ?? 0) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                        @error('tax')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shipping</label>
                        <input type="number" step="0.01" name="shipping" value="{{ old('shipping', $suratPo->shipping ?? 0) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                        @error('shipping')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Other</label>
                        <input type="number" step="0.01" name="other" value="{{ old('other', $suratPo->other ?? 0) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                        @error('other')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total (otomatis)</label>
                        <input type="text" value="Rp {{ number_format($total ?? 0, 2, ',', '.') }}" disabled
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment terms -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-money-check-alt mr-2 text-red-700"></i>
                    Pembayaran
                </h2>
                <p class="text-sm text-gray-600 mt-1">Persentase termin pembayaran (total harus 100%).</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">DP (%)</label>
                    <input type="number" step="0.01" name="dp_percent" value="{{ old('dp_percent', $suratPo->dp_percent ?? 30) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <div class="text-xs text-gray-600 mt-1">Nominal: <span class="font-semibold">Rp {{ number_format($dpAmount ?? 0, 2, ',', '.') }}</span></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Termin 2 (%)</label>
                    <input type="number" step="0.01" name="termin2_percent" value="{{ old('termin2_percent', $suratPo->termin2_percent ?? 30) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <div class="text-xs text-gray-600 mt-1">Nominal: <span class="font-semibold">Rp {{ number_format($termin2Amount ?? 0, 2, ',', '.') }}</span></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pelunasan (%)</label>
                    <input type="number" step="0.01" name="pelunasan_percent" value="{{ old('pelunasan_percent', $suratPo->pelunasan_percent ?? 40) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <div class="text-xs text-gray-600 mt-1">Nominal: <span class="font-semibold">Rp {{ number_format($pelunasanAmount ?? 0, 2, ',', '.') }}</span></div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-boxes mr-2 text-red-700"></i>
                    Barang
                </h2>
            </div>

            <div class="p-6 space-y-4">
                @foreach($suratPo->items as $idx => $item)
                    @php
                        $poItemId = $item->id_surat_po_item;
                        $oldSpec = old("items.$idx.spec_html");
                        $specValue = $oldSpec !== null ? $oldSpec : ($item->spec_html ?? '');
                    @endphp
                    <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">
                        <input type="hidden" name="items[{{ $idx }}][id_surat_po_item]" value="{{ $item->id_surat_po_item }}">

                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">{{ $item->barang->nama_barang ?? 'Barang' }}</div>
                                <div class="text-xs text-gray-600 mt-1">
                                    Qty: <span class="font-semibold">{{ $item->qty }}</span> •
                                    Unit Price: <span class="font-semibold">Rp {{ number_format($item->unit_price, 2, ',', '.') }}</span> •
                                    Line Total: <span class="font-semibold">Rp {{ number_format($item->line_total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Specification</label>
                            <div class="border border-gray-300 rounded-lg overflow-hidden bg-white">
                                <div class="flex items-center gap-2 px-2 py-2 bg-gray-50 border-b border-gray-200 flex-wrap">
                                    <button type="button" class="po-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="po-rt-{{ $poItemId }}" data-cmd="bold"><b>B</b></button>
                                    <button type="button" class="po-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="po-rt-{{ $poItemId }}" data-cmd="italic"><i>I</i></button>
                                    <button type="button" class="po-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="po-rt-{{ $poItemId }}" data-cmd="insertUnorderedList">• List</button>
                                    <button type="button" class="po-rt-btn text-xs px-2 py-1 bg-white border rounded" data-target="po-rt-{{ $poItemId }}" data-cmd="insertOrderedList">1. List</button>
                                </div>

                                <div id="po-rt-{{ $poItemId }}" class="po-rt-editor p-3 text-sm min-h-[120px]" contenteditable="true">{!! $specValue !!}</div>
                            </div>
                            <input type="hidden" name="items[{{ $idx }}][spec_html]" id="po-rt-hidden-{{ $poItemId }}" value="">
                            @error("items.$idx.spec_html")
                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @php
            $lampiranListPo = $suratPo?->lampiran_files_list ?? (method_exists($suratPo ?? null, 'getLampiranFilesListAttribute') ? ($suratPo->lampiran_files_list ?? []) : []);
        @endphp

        <!-- Lampiran PO -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-paperclip mr-2 text-red-700"></i>
                            Lampiran PO (opsional)
                        </h2>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran PDF</label>
                    <input type="file" name="lampiran_pdfs[]" multiple accept="application/pdf"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('lampiran_pdfs.*')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-800">Lampiran tersimpan</div>
                        <div class="text-xs text-gray-500">{{ is_array($lampiranListPo) ? count($lampiranListPo) : 0 }} file</div>
                    </div>

                    <div class="mt-3 space-y-2">
                        @if (empty($lampiranListPo))
                            <div class="text-sm text-gray-500">Belum ada lampiran.</div>
                        @else
                            @foreach ($lampiranListPo as $f)
                                <div class="flex items-center justify-between gap-2 bg-white border rounded-lg px-3 py-2" data-path="{{ $f['path'] ?? '' }}">
                                    <div class="min-w-0">
                                        <div class="text-sm text-gray-800 truncate">{{ $f['original_name'] ?? basename($f['path'] ?? '-') }}</div>
                                        <div class="text-xs text-gray-500">{{ $f['uploaded_at'] ?? '' }}</div>
                                    </div>
                                    <button type="button" class="btn-delete-lampiran-po text-xs px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg"
                                            data-path="{{ $f['path'] ?? '' }}">
                                        Hapus
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-red-700 text-white hover:bg-red-800">
                <i class="fas fa-save mr-2"></i>
                Simpan Draft
            </button>
        </div>
    </form>

    <div class="mt-6 sm:hidden">
        <a href="{{ route('purchasing.pembayaran', ['tab' => 'pembuatan-surat-po']) }}"
           class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>
</div>

<script>
(function() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    const form = document.getElementById('form-surat-po');

    function syncPoEditors() {
        document.querySelectorAll('.po-rt-editor').forEach((ed) => {
            const id = ed.getAttribute('id');

            // khusus comments
            if (id === 'po-rt-comments') {
                const hiddenComments = document.getElementById('po-rt-hidden-comments');
                if (hiddenComments) hiddenComments.value = ed.innerHTML;
                return;
            }

            const itemId = id?.replace('po-rt-', '');
            if (!itemId) return;
            const hidden = document.getElementById('po-rt-hidden-' + itemId);
            if (hidden) hidden.value = ed.innerHTML;
        });
    }

    // toolbar
    document.querySelectorAll('.po-rt-btn').forEach((btn) => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const cmd = this.getAttribute('data-cmd');
            const editor = document.getElementById(targetId);
            if (!editor) return;
            editor.focus();
            document.execCommand(cmd, false, null);
        });
    });

    // init hidden values (agar submit tanpa edit tetap terkirim)
    syncPoEditors();

    // submit
    form?.addEventListener('submit', function() {
        syncPoEditors();
    });

     async function deleteLampiran(url, path, btn) {
         if (!path) return;
         if (!confirm('Hapus lampiran ini?')) return;

         btn.disabled = true;
         btn.textContent = 'Menghapus...';

         try {
             const res = await fetch(url, {
                 method: 'DELETE',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': token,
                     'Accept': 'application/json'
                 },
                 body: JSON.stringify({ path })
             });

             const data = await res.json();
             if (!res.ok || !data.success) {
                 throw new Error(data.message || 'Gagal menghapus lampiran');
             }

             const row = btn.closest('[data-path]');
             if (row) row.remove();
         } catch (e) {
             alert(e.message || 'Terjadi kesalahan');
             btn.disabled = false;
             btn.textContent = 'Hapus';
         }
     }

     document.querySelectorAll('.btn-delete-lampiran-po').forEach(btn => {
         btn.addEventListener('click', () => deleteLampiran(
             '{{ route('purchasing.pembayaran.pembuatan-surat-po.lampiran.delete', [$proyek->id_proyek, $vendor->id_vendor]) }}',
             btn.getAttribute('data-path'),
             btn
         ));
     });
 })();
 </script>
@endsection
