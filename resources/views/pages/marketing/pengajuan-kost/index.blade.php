@extends('layouts.app')

@section('title', 'Pengajuan Kost - Cyber KATANA')

@section('content')

{{-- ─── Header ─────────────────────────────────────────────────────────────── --}}
<div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Pengajuan Kost</h1>
            <p class="text-red-100 text-sm sm:text-base">Kelola pengajuan biaya kost</p>
        </div>
        <div class="hidden sm:block">
            <i class="fas fa-house-user text-4xl lg:text-6xl opacity-80"></i>
        </div>
    </div>
</div>

{{-- ─── Alert ──────────────────────────────────────────────────────────────── --}}
<div id="alertBox" class="hidden mb-4 p-4 rounded-xl font-medium text-sm"></div>

{{-- ─── Stats ──────────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-bold text-yellow-600">{{ $stats['menunggu'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Menunggu</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-bold text-green-600">{{ $stats['disetujui'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Disetujui</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-bold text-orange-500">{{ $stats['revisi'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Perlu Revisi</div>
    </div>
</div>

{{-- ─── Toolbar ─────────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        {{-- Search --}}
        <form method="GET" action="{{ route('marketing.pengajuan-kost') }}" class="flex gap-2 flex-1">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari kode, lokasi, kota/kabupaten, atau PIC..."
                   class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
            <button type="submit" class="bg-red-800 text-white px-4 py-2 rounded-xl text-sm hover:bg-red-700 transition">
                <i class="fas fa-search"></i>
            </button>
        </form>

        {{-- Filter Status --}}
        <div class="flex gap-2 text-sm">
            @foreach([''=>'Semua','menunggu'=>'Menunggu','disetujui'=>'Disetujui','revisi'=>'Perlu Revisi'] as $val=>$label)
            <a href="{{ route('marketing.pengajuan-kost', array_merge(request()->query(), ['status'=>$val])) }}"
               class="px-3 py-2 rounded-xl border transition
                      {{ request('status', '') === $val ? 'bg-red-800 text-white border-red-800' : 'border-gray-200 text-gray-600 hover:border-red-300' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Tambah --}}
        <button onclick="openCreateModal()"
                class="bg-red-800 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-700 transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Pengajuan
        </button>
    </div>
</div>

{{-- ─── Table ───────────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-red-50 text-red-800 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Tgl Kegiatan</th>
                    <th class="px-4 py-3 text-left">PIC Marketing</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-left">Kota/ Kabupaten</th>
                    <th class="px-4 py-3 text-right">Nominal</th>
                    <th class="px-4 py-3 text-center">Bukti</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pengajuanList as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800 whitespace-nowrap">{{ $item->kode_pengajuan }}</td>
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                        {{ $item->tanggal_kegiatan?->format('d/m/Y') ?? '-' }}
                        @if($item->tanggal_kegiatan_sampai && $item->tanggal_kegiatan_sampai != $item->tanggal_kegiatan)
                            <span class="text-gray-400"> – {{ $item->tanggal_kegiatan_sampai->format('d/m/Y') }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $item->picMarketing->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-700 max-w-[150px] truncate" title="{{ $item->lokasi }}">{{ $item->lokasi }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->kota ?? '-' }}</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800 whitespace-nowrap">
                        Rp {{ number_format($item->nominal, 2, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-gray-500 text-xs">{{ $item->buktiBayar->count() }} file</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        {!! $item->status_badge !!}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openDetailModal({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($item->canEdit())
                            <button onclick="openEditModal({{ $item->id }})"
                                    class="text-yellow-600 hover:text-yellow-800 transition" title="{{ $item->status === 'revisi' ? 'Perbaiki & Ajukan Ulang' : 'Edit' }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete({{ $item->id }}, '{{ $item->kode_pengajuan }}')"
                                    class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-3 block opacity-40"></i>
                        Belum ada pengajuan kost
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pengajuanList->hasPages())
    <div class="px-4 py-4 border-t border-gray-100">
        {{ $pengajuanList->links() }}
    </div>
    @endif
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL TAMBAH / EDIT
════════════════════════════════════════════════════════════════════════════ --}}
<div id="formModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col">
        {{-- Header --}}
        <div class="bg-red-800 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between shrink-0">
            <h2 id="formModalTitle" class="text-lg font-bold">Tambah Pengajuan Kost</h2>
            <button onclick="closeFormModal()" class="text-white hover:text-red-200 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto p-6 flex-1">
            <form id="kostForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="kostId" value="">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Tanggal Kegiatan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300" required>
                    </div>

                    {{-- Tanggal Kegiatan Sampai --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan Selesai</label>
                        <input type="date" name="tanggal_kegiatan_sampai" id="tanggal_kegiatan_sampai"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                        <p class="text-xs text-gray-400 mt-0.5">Kosongkan jika hanya 1 hari</p>
                    </div>

                    {{-- Tanggal Pengajuan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300" required>
                    </div>

                    {{-- PIC --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PIC <span class="text-red-500">*</span></label>
                        <select name="pic_marketing_id" id="pic_marketing_id"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300" required>
                            <option value="">-- Pilih PIC --</option>
                            @foreach($allUsers as $user)
                            <option value="{{ $user->id_user }}">{{ $user->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nominal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                            <input type="text" id="nominal_display" inputmode="numeric"
                                   placeholder="0"
                                   class="w-full border border-gray-200 rounded-xl pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300" required>
                        </div>
                        <input type="hidden" name="nominal" id="nominal">
                    </div>

                    {{-- Lokasi --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kegiatan <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" maxlength="255"
                               placeholder="Alamat Kegiatan Berlangsung"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300" required>
                    </div>

                    {{-- Kota --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kota/ Kabupaten</label>
                        <input type="text" name="kota" id="kota" maxlength="100"
                               placeholder="Kota/ Kabupaten tujuan dinas"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                    </div>

                    {{-- Keterangan Kegiatan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Kegiatan</label>
                        <input type="text" name="keterangan_kegiatan" id="keterangan_kegiatan" maxlength="1000"
                               placeholder="Keperluan/tujuan dinas"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                    </div>

                    {{-- Catatan --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="2" maxlength="1000"
                                  placeholder="Catatan tambahan (opsional)"
                                  class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"></textarea>
                    </div>

                    {{-- Bukti Pembayaran --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                        <input type="file" name="bukti_files[]" id="bukti_files" multiple
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, PDF. Maks 5MB per file. Bisa upload lebih dari 1.</p>

                        {{-- Daftar bukti existing (saat edit) --}}
                        <div id="existingBuktiList" class="mt-3 space-y-2 hidden">
                            <p class="text-xs font-medium text-gray-600">Bukti yang sudah ada:</p>
                            <div id="existingBuktiItems" class="space-y-1"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 shrink-0">
            <button onclick="closeFormModal()"
                    class="px-4 py-2 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">
                Batal
            </button>
            <button onclick="submitForm()"
                    class="px-5 py-2 bg-red-800 text-white rounded-xl text-sm font-medium hover:bg-red-700 transition flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span id="submitBtnText">Simpan</span>
            </button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DETAIL
════════════════════════════════════════════════════════════════════════════ --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] flex flex-col">
        <div class="bg-red-800 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between shrink-0">
            <h2 class="text-lg font-bold">Detail Pengajuan Kost</h2>
            <button onclick="closeDetailModal()" class="text-white hover:text-red-200"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div id="detailContent" class="overflow-y-auto p-6 flex-1 space-y-4 text-sm"></div>
        <div class="px-6 py-4 border-t shrink-0 flex justify-end">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm hover:bg-gray-200 transition">Tutup</button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL KONFIRMASI HAPUS
════════════════════════════════════════════════════════════════════════════ --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 text-center">
        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-trash text-red-600 text-xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Pengajuan?</h3>
        <p id="deleteMessage" class="text-sm text-gray-500 mb-6"></p>
        <div class="flex gap-3 justify-center">
            <button onclick="closeDeleteModal()" class="px-5 py-2 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">Batal</button>
            <button onclick="doDelete()" class="px-5 py-2 bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700 transition">Ya, Hapus</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ROUTES = {
        store:   "{{ route('marketing.pengajuan-kost.store') }}",
        show:    (id) => `{{ url('marketing/pengajuan-kost') }}/${id}`,
        update:  (id) => `{{ url('marketing/pengajuan-kost') }}/${id}`,
        destroy: (id) => `{{ url('marketing/pengajuan-kost') }}/${id}`,
        buktiDelete:  (id) => `{{ url('marketing/pengajuan-kost/bukti') }}/${id}`,
        buktiPreview: (id) => `{{ url('marketing/pengajuan-kost/bukti') }}/${id}/preview`,
    };
    const CSRF = "{{ csrf_token() }}";

    let deleteTargetId = null;

    // ─── Alert ──────────────────────────────────────────────────────────────
    function showAlert(message, type = 'success') {
        const box = document.getElementById('alertBox');
        box.className = `mb-4 p-4 rounded-xl font-medium text-sm ${type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'}`;
        box.textContent = message;
        box.classList.remove('hidden');
        setTimeout(() => box.classList.add('hidden'), 4000);
    }

    // ─── Modal Helpers ──────────────────────────────────────────────────────
    function openModal(id)  { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
    function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

    function openCreateModal() {
        document.getElementById('formModalTitle').textContent = 'Tambah Pengajuan Kost';
        document.getElementById('submitBtnText').textContent = 'Simpan';
        document.getElementById('kostId').value = '';
        document.getElementById('kostForm').reset();
        document.getElementById('nominal_display').value = '';
        document.getElementById('nominal').value = '';
        document.getElementById('tanggal_pengajuan').value = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_kegiatan_sampai').value = '';
        document.getElementById('existingBuktiList').classList.add('hidden');
        document.getElementById('existingBuktiItems').innerHTML = '';
        openModal('formModal');
    }

    function closeFormModal() { closeModal('formModal'); }
    function closeDetailModal() { closeModal('detailModal'); }
    function closeDeleteModal() { closeModal('deleteModal'); deleteTargetId = null; }

    // ─── Edit Modal ─────────────────────────────────────────────────────────
    async function openEditModal(id) {
        const resp = await fetch(ROUTES.show(id));
        const json = await resp.json();
        if (!json.success) return;
        const d = json.data;

        const isRevisi = d.status === 'revisi';
        document.getElementById('formModalTitle').textContent = isRevisi ? 'Perbaiki & Ajukan Ulang' : 'Edit Pengajuan Kost';
        document.getElementById('submitBtnText').textContent  = isRevisi ? 'Ajukan Ulang' : 'Perbarui';
        document.getElementById('kostId').value = d.id;
        document.getElementById('tanggal_kegiatan').value        = d.tanggal_kegiatan?.split('T')[0] ?? d.tanggal_kegiatan ?? '';
        document.getElementById('tanggal_kegiatan_sampai').value = d.tanggal_kegiatan_sampai?.split('T')[0] ?? d.tanggal_kegiatan_sampai ?? '';
        document.getElementById('tanggal_pengajuan').value       = d.tanggal_pengajuan?.split('T')[0] ?? d.tanggal_pengajuan ?? '';
        document.getElementById('pic_marketing_id').value        = d.pic_marketing_id;
        document.getElementById('lokasi').value                  = d.lokasi;
        document.getElementById('kota').value                    = d.kota ?? '';
        document.getElementById('keterangan_kegiatan').value     = d.keterangan_kegiatan ?? '';
        setNominal(d.nominal);
        document.getElementById('catatan').value                 = d.catatan ?? '';

        // Tampilkan bukti existing
        const listEl  = document.getElementById('existingBuktiList');
        const itemsEl = document.getElementById('existingBuktiItems');
        itemsEl.innerHTML = '';
        if (d.bukti_bayar && d.bukti_bayar.length > 0) {
            listEl.classList.remove('hidden');
            d.bukti_bayar.forEach(b => {
                itemsEl.innerHTML += `
                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2 text-xs" id="bukti-${b.id}">
                    <a href="${b.url}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                        <i class="fas fa-file"></i> ${b.file_name}
                    </a>
                    <button onclick="deleteBukti(${b.id})" class="text-red-500 hover:text-red-700 ml-2"><i class="fas fa-times"></i></button>
                </div>`;
            });
        } else {
            listEl.classList.add('hidden');
        }

        openModal('formModal');
    }

    // ─── Submit Form ────────────────────────────────────────────────────────
    async function submitForm() {
        const id     = document.getElementById('kostId').value;
        const isEdit = !!id;
        const form   = document.getElementById('kostForm');
        const fd     = new FormData(form);

        if (isEdit) fd.append('_method', 'PUT');

        const url = isEdit ? ROUTES.update(id) : ROUTES.store;

        try {
            const resp = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF },
                body: fd,
            });
            const json = await resp.json();
            if (json.success) {
                closeFormModal();
                showAlert(json.message);
                setTimeout(() => location.reload(), 1200);
            } else {
                showAlert(json.message || 'Terjadi kesalahan.', 'error');
            }
        } catch (e) {
            showAlert('Koneksi gagal.', 'error');
        }
    }

    // ─── Detail Modal ───────────────────────────────────────────────────────
    async function openDetailModal(id) {
        const resp = await fetch(ROUTES.show(id));
        const json = await resp.json();
        if (!json.success) return;
        const d = json.data;

        const statusMap = {
            menunggu:  '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>',
            disetujui: '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>',
            revisi:    '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Perlu Revisi</span>',
        };

        const tglKegiatan = d.tanggal_kegiatan_sampai && d.tanggal_kegiatan_sampai !== d.tanggal_kegiatan
            ? `${formatDate(d.tanggal_kegiatan)} – ${formatDate(d.tanggal_kegiatan_sampai)}`
            : formatDate(d.tanggal_kegiatan);

        const buktiHtml = (d.bukti_bayar && d.bukti_bayar.length)
            ? d.bukti_bayar.map(b => `<a href="${b.url}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:underline text-xs"><i class="fas fa-file"></i>${b.file_name}</a>`).join('')
            : '<span class="text-gray-400">Tidak ada bukti</span>';

        const revisiAlert = d.status === 'revisi' && d.catatan_keuangan ? `
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-3">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-exclamation-circle text-orange-500 text-sm"></i>
                    <span class="text-xs font-semibold text-orange-700">Catatan Revisi dari Keuangan</span>
                </div>
                <div class="text-orange-800 text-sm">${d.catatan_keuangan}</div>
                <div class="text-xs text-orange-500 mt-1">Oleh: ${d.verified_by?.nama ?? '-'} · ${formatDate(d.tanggal_verifikasi)}</div>
            </div>` : (d.catatan_keuangan ? `
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="text-xs text-gray-400 mb-1">Catatan Keuangan</div>
                <div class="text-gray-700 text-sm">${d.catatan_keuangan}</div>
                ${d.verified_by ? `<div class="text-xs text-gray-400 mt-1">Oleh: ${d.verified_by?.nama ?? '-'} · ${formatDate(d.tanggal_verifikasi)}</div>` : ''}
            </div>` : '');

        document.getElementById('detailContent').innerHTML = `
            ${revisiAlert}
            <div class="grid grid-cols-2 gap-3">
                <div><div class="text-xs text-gray-400">Kode</div><div class="font-semibold text-gray-800">${d.kode_pengajuan}</div></div>
                <div><div class="text-xs text-gray-400">Status</div><div>${statusMap[d.status] ?? d.status}</div></div>
                <div class="col-span-2"><div class="text-xs text-gray-400">Tgl Kegiatan</div><div class="text-gray-700">${tglKegiatan}</div></div>
                <div><div class="text-xs text-gray-400">Tgl Pengajuan</div><div class="text-gray-700">${formatDate(d.tanggal_pengajuan)}</div></div>
                <div><div class="text-xs text-gray-400">PIC</div><div class="text-gray-700">${d.pic_marketing?.nama ?? '-'}</div></div>
                <div><div class="text-xs text-gray-400">Nominal</div><div class="font-semibold text-gray-800">Rp ${numFmt(d.nominal)}</div></div>
                <div><div class="text-xs text-gray-400">Lokasi</div><div class="text-gray-700">${d.lokasi}</div></div>
                <div><div class="text-xs text-gray-400">Kota/ Kabupaten</div><div class="text-gray-700">${d.kota ?? '-'}</div></div>
                <div class="col-span-2"><div class="text-xs text-gray-400">Keterangan</div><div class="text-gray-700">${d.keterangan_kegiatan ?? '-'}</div></div>
                <div class="col-span-2"><div class="text-xs text-gray-400">Catatan</div><div class="text-gray-700">${d.catatan ?? '-'}</div></div>
            </div>
            <hr class="border-gray-100">
            <div>
                <div class="text-xs text-gray-400 mb-2">Bukti Pembayaran</div>
                <div class="space-y-1">${buktiHtml}</div>
            </div>
        `;
        openModal('detailModal');
    }

    // ─── Delete ─────────────────────────────────────────────────────────────
    function confirmDelete(id, kode) {
        deleteTargetId = id;
        document.getElementById('deleteMessage').textContent = `Pengajuan ${kode} akan dihapus permanen.`;
        openModal('deleteModal');
    }

    async function doDelete() {
        if (!deleteTargetId) return;
        const resp = await fetch(ROUTES.destroy(deleteTargetId), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/x-www-form-urlencoded' },
            body: '_method=DELETE',
        });
        const json = await resp.json();
        closeDeleteModal();
        if (json.success) {
            showAlert(json.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(json.message, 'error');
        }
    }

    // ─── Delete Bukti ────────────────────────────────────────────────────────
    async function deleteBukti(buktiId) {
        if (!confirm('Hapus file bukti ini?')) return;
        const resp = await fetch(ROUTES.buktiDelete(buktiId), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/x-www-form-urlencoded' },
            body: '_method=DELETE',
        });
        const json = await resp.json();
        if (json.success) {
            document.getElementById(`bukti-${buktiId}`)?.remove();
        } else {
            alert(json.message);
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    function formatDate(str) {
        if (!str) return '-';
        const d = new Date(str);
        return d.toLocaleDateString('id-ID', { day:'2-digit', month:'2-digit', year:'numeric' });
    }

    function numFmt(n) {
        return Number(n).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    }

    // ─── Nominal Formatting (support desimal) ────────────────────────────────
    /**
     * Format tampilan: bagian ribuan pakai titik, desimal pakai koma
     * Contoh: 199999.50 → "199.999,50"
     * Raw value disimpan di hidden input dengan titik sebagai desimal: "199999.50"
     */
    function setNominal(rawValue) {
        if (rawValue === null || rawValue === undefined || rawValue === '') {
            document.getElementById('nominal_display').value = '';
            document.getElementById('nominal').value = '';
            return;
        }
        // rawValue bisa berupa "199999.00" (dari DB) atau angka
        const str = String(rawValue);
        const [intPart, decPart] = str.split('.');
        const intNum = parseInt(intPart.replace(/\D/g, ''), 10) || 0;
        const formatted = intNum.toLocaleString('id-ID') + (decPart !== undefined ? ',' + decPart.replace(/\D/g, '') : '');
        document.getElementById('nominal_display').value = formatted;
        // Simpan ke hidden: gunakan titik sebagai desimal
        const raw = decPart !== undefined ? `${intNum}.${decPart.replace(/\D/g, '')}` : `${intNum}`;
        document.getElementById('nominal').value = raw;
    }

    // Event listener untuk input nominal
    document.getElementById('nominal_display').addEventListener('input', function () {
        const cursorPos = this.selectionStart;
        const prevLen   = this.value.length;
        const raw       = this.value;

        // Pisahkan bagian integer dan desimal (user ketik koma sebagai pemisah desimal)
        const commaIdx = raw.lastIndexOf(',');
        let intRaw, decRaw;
        if (commaIdx !== -1) {
            intRaw = raw.substring(0, commaIdx).replace(/\D/g, '');
            decRaw = raw.substring(commaIdx + 1).replace(/\D/g, '').substring(0, 2); // maks 2 digit
        } else {
            intRaw = raw.replace(/\D/g, '');
            decRaw = null;
        }

        const intNum    = parseInt(intRaw, 10) || 0;
        const intFormatted = intNum.toLocaleString('id-ID');
        const formatted = decRaw !== null ? `${intFormatted},${decRaw}` : intFormatted;

        this.value = formatted;

        // Pertahankan posisi cursor
        const diff   = formatted.length - prevLen;
        const newPos = Math.max(0, cursorPos + diff);
        this.setSelectionRange(newPos, newPos);

        // Simpan raw ke hidden (titik sebagai desimal)
        const hiddenVal = decRaw !== null ? `${intNum}.${decRaw}` : `${intNum}`;
        document.getElementById('nominal').value = intNum ? hiddenVal : '';
    });

    // Izinkan digit, koma (desimal), dan tombol kontrol
    document.getElementById('nominal_display').addEventListener('keydown', function (e) {
        const allowed = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'];
        if (allowed.includes(e.key)) return;
        if (e.ctrlKey || e.metaKey) return; // Ctrl+A, Ctrl+C, dll
        // Izinkan koma sebagai pemisah desimal (hanya satu)
        if (e.key === ',' || e.key === '.') {
            if (this.value.includes(',')) e.preventDefault(); // sudah ada koma, tolak
            return;
        }
        if (!/^\d$/.test(e.key)) e.preventDefault();
    });
</script>
@endpush
