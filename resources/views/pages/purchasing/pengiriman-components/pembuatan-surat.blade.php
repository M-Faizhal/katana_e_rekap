@extends('layouts.app')

@section('title', 'Pembuatan Surat Pengiriman - Cyber KATANA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="bg-red-800 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">Pembuatan Surat Pengiriman</h1>
                <p class="text-red-100 mt-1 text-sm sm:text-base">Buat Surat Jalan dan Tanda Terima dalam 1 halaman (2 form terpisah)</p>
            </div>
            <div class="hidden sm:block">
                <a href="{{ route('purchasing.pengiriman', ['tab' => 'surat']) }}"
                   class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
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

    <!-- Info Proyek -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-gray-500">Proyek</div>
                <div class="text-lg font-semibold text-gray-900">{{ $proyek->kode_proyek }} — {{ $proyek->instansi }}</div>
                <div class="text-sm text-gray-600">{{ $proyek->kab_kota ?? '-' }}</div>
            </div>
            <div class="text-sm text-gray-700">
                <div class="flex items-center gap-2">
                    <span class="text-gray-500">PIC Purchasing:</span>
                    <span class="font-medium">{{ $proyek->adminPurchasing->name ?? $proyek->adminPurchasing->nama ?? '-' }}</span>
                </div>
                <div class="mt-1">
                    @php
                        $badge = $proyek->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badge }}">{{ $proyek->status }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Forms -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Form Surat Jalan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-truck-loading mr-2 text-red-700"></i>
                            Form Surat Jalan
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Isi data untuk membuat Surat Jalan.</p>
                    </div>
                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">UI saja</span>
                </div>
            </div>

            <form action="#" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id_proyek" value="{{ $proyek->id_proyek }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Jalan</label>
                    <input type="text" name="no_surat_jalan" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kirim</label>
                        <input type="date" name="tanggal_kirim"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Kirim</label>
                    <textarea name="alamat_kirim" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" disabled
                            class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-medium cursor-not-allowed">
                        Preview
                    </button>
                    <button type="submit" disabled
                            class="px-4 py-2 bg-red-700/60 text-white rounded-lg text-sm font-medium cursor-not-allowed">
                        Simpan Surat Jalan
                    </button>
                </div>
            </form>
        </div>

        <!-- Form Tanda Terima -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-clipboard-check mr-2 text-red-700"></i>
                            Form Tanda Terima
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Isi data untuk membuat Tanda Terima.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('purchasing.pengiriman.tanda-terima.preview', $proyek->id_proyek) }}" target="_blank"
                           class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                            Preview PDF
                        </a>
                        <a href="{{ route('purchasing.pengiriman.tanda-terima.download', $proyek->id_proyek) }}"
                           class="text-xs px-3 py-1.5 bg-red-700 hover:bg-red-800 text-white rounded-lg">
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>

            <form id="form-tt" action="{{ route('purchasing.pengiriman.tanda-terima.store', $proyek->id_proyek) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <input type="hidden" name="id_proyek" value="{{ $proyek->id_proyek }}">
                <input type="hidden" name="id_penawaran" value="{{ $suratTandaTerima->id_penawaran ?? $proyek->penawaranAktif->id_penawaran ?? '' }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Tanda Terima</label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $suratTandaTerima->nomor_surat ?? '') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('nomor_surat')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Surat</label>
                        <input type="text" name="tempat_surat" value="{{ old('tempat_surat', $suratTandaTerima->tempat_surat ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('tempat_surat')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', optional($suratTandaTerima->tanggal_surat ?? null)->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('tanggal_surat')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran PDF (opsional)</label>
                    <input type="file" name="lampiran_pdfs[]" multiple accept="application/pdf"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <div class="text-xs text-gray-500 mt-1">Boleh upload lebih dari 1 file PDF. Akan digabungkan ke belakang PDF Tanda Terima saat download/preview.</div>
                    @error('lampiran_pdfs.*')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $lampiranList = $suratTandaTerima?->lampiran_files_list ?? [];
                @endphp

                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-800">Lampiran tersimpan</div>
                        <div class="text-xs text-gray-500">{{ count($lampiranList) }} file</div>
                    </div>

                    <div id="lampiran-list" class="mt-3 space-y-2">
                        @if (empty($lampiranList))
                            <div class="text-sm text-gray-500">Belum ada lampiran.</div>
                        @else
                            @foreach ($lampiranList as $f)
                                <div class="flex items-center justify-between gap-2 bg-white border rounded-lg px-3 py-2" data-path="{{ $f['path'] ?? '' }}">
                                    <div class="min-w-0">
                                        <div class="text-sm text-gray-800 truncate">{{ $f['original_name'] ?? basename($f['path'] ?? '-') }}</div>
                                        <div class="text-xs text-gray-500">{{ $f['uploaded_at'] ?? '' }}</div>
                                    </div>
                                    <button type="button" class="btn-delete-lampiran text-xs px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg"
                                            data-path="{{ $f['path'] ?? '' }}">
                                        Hapus
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-red-700 hover:bg-red-800 text-white rounded-lg text-sm font-medium">
                        Simpan Tanda Terima
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-6 sm:hidden">
        <a href="{{ route('purchasing.pengiriman', ['tab' => 'surat']) }}"
           class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>
</div>

<script>
(function() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    async function deleteLampiran(path, btn) {
        if (!path) return;
        if (!confirm('Hapus lampiran ini?')) return;

        btn.disabled = true;
        btn.textContent = 'Menghapus...';

        try {
            const res = await fetch('{{ route('purchasing.pengiriman.tanda-terima.lampiran.delete', $proyek->id_proyek) }}', {
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

            // remove row
            const row = btn.closest('[data-path]');
            if (row) row.remove();
        } catch (e) {
            alert(e.message || 'Terjadi kesalahan');
            btn.disabled = false;
            btn.textContent = 'Hapus';
        }
    }

    document.querySelectorAll('.btn-delete-lampiran').forEach(btn => {
        btn.addEventListener('click', () => deleteLampiran(btn.getAttribute('data-path'), btn));
    });
})();
</script>
@endsection
