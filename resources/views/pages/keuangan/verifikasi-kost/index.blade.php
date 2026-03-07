@extends('layouts.app')

@section('title', 'Verifikasi Kost - Cyber KATANA')

@section('content')

{{-- ─── Header ─────────────────────────────────────────────────────────────── --}}
<div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Verifikasi Kost</h1>
            <p class="text-red-100 text-sm sm:text-base">Review dan setujui pengajuan biaya kost dari tim marketing</p>
        </div>
        <div class="hidden sm:block">
            <i class="fas fa-house-user text-4xl lg:text-6xl opacity-80"></i>
        </div>
    </div>
</div>

{{-- ─── Alert ──────────────────────────────────────────────────────────────── --}}
<div id="alertBox" class="hidden mb-4 p-4 rounded-xl font-medium text-sm"></div>

{{-- ─── Stats ──────────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-bold text-gray-700">{{ $stats['total'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Total</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-bold text-yellow-600">{{ $stats['menunggu'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Menunggu</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-bold text-green-600">{{ $stats['disetujui'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Disetujui</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <div class="text-2xl font-bold text-red-600">{{ $stats['ditolak'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Ditolak</div>
    </div>
</div>

{{-- ─── Toolbar ─────────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        {{-- Search --}}
        <form method="GET" action="{{ route('keuangan.verifikasi-kost') }}" class="flex gap-2 flex-1">
            <input type="hidden" name="status" value="{{ request('status', 'menunggu') }}">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari kode, lokasi, kota, atau PIC..."
                   class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
            <button type="submit" class="bg-red-800 text-white px-4 py-2 rounded-xl text-sm hover:bg-red-700 transition">
                <i class="fas fa-search"></i>
            </button>
        </form>

        {{-- Filter Status --}}
        <div class="flex gap-2 text-sm">
            @foreach(['menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak',''=>'Semua'] as $val=>$label)
            <a href="{{ route('keuangan.verifikasi-kost', array_merge(request()->except('status'), ['status'=>$val])) }}"
               class="px-3 py-2 rounded-xl border transition
                      {{ $status === $val ? 'bg-red-800 text-white border-red-800' : 'border-gray-200 text-gray-600 hover:border-red-300' }}">
                {{ $label }}
                @if($val === 'menunggu' && $stats['menunggu'] > 0)
                    <span class="ml-1 bg-yellow-400 text-yellow-900 text-xs rounded-full px-1.5">{{ $stats['menunggu'] }}</span>
                @endif
            </a>
            @endforeach
        </div>
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
                    <th class="px-4 py-3 text-left">Tgl Pengajuan</th>
                    <th class="px-4 py-3 text-left">PIC Marketing</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
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
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $item->tanggal_kegiatan?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $item->tanggal_pengajuan?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $item->picMarketing->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-700 max-w-[140px] truncate" title="{{ $item->lokasi }}">
                        {{ $item->lokasi }}@if($item->kota), <span class="text-gray-400">{{ $item->kota }}</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-800 whitespace-nowrap">
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
                            @if($item->status === 'menunggu')
                            <button onclick="openApproveModal({{ $item->id }}, '{{ $item->kode_pengajuan }}')"
                                    class="text-green-600 hover:text-green-800 transition" title="Setujui">
                                <i class="fas fa-check-circle"></i>
                            </button>
                            <button onclick="openRejectModal({{ $item->id }}, '{{ $item->kode_pengajuan }}')"
                                    class="text-red-600 hover:text-red-800 transition" title="Tolak">
                                <i class="fas fa-times-circle"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-3 block opacity-40"></i>
                        Tidak ada data pengajuan kost
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pengajuanList->hasPages())
    <div class="px-4 py-4 border-t border-gray-100">
        {{ $pengajuanList->links() }}
    </div>
    @endif
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DETAIL
════════════════════════════════════════════════════════════════════════════ --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] flex flex-col">
        <div class="bg-red-800 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between shrink-0">
            <h2 class="text-lg font-bold">Detail Pengajuan Kost</h2>
            <button onclick="closeModal('detailModal')" class="text-white hover:text-red-200"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div id="detailContent" class="overflow-y-auto p-6 flex-1 space-y-4 text-sm"></div>
        <div class="px-6 py-4 border-t shrink-0 flex justify-end gap-3" id="detailActions"></div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL SETUJUI
════════════════════════════════════════════════════════════════════════════ --}}
<div id="approveModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6">
        <div class="text-center mb-4">
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Setujui Pengajuan?</h3>
            <p id="approveMessage" class="text-sm text-gray-500 mt-1"></p>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
            <textarea id="approveCatatan" rows="2" placeholder="Tambahkan catatan jika perlu..."
                      class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300 resize-none"></textarea>
        </div>
        <div class="flex gap-3">
            <button onclick="closeModal('approveModal')" class="flex-1 border border-gray-200 rounded-xl text-sm py-2 text-gray-600 hover:bg-gray-50 transition">Batal</button>
            <button onclick="doApprove()" class="flex-1 bg-green-600 text-white rounded-xl text-sm py-2 font-medium hover:bg-green-700 transition">Setujui</button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL TOLAK
════════════════════════════════════════════════════════════════════════════ --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6">
        <div class="text-center mb-4">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-times-circle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Tolak Pengajuan?</h3>
            <p id="rejectMessage" class="text-sm text-gray-500 mt-1"></p>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
            <textarea id="rejectCatatan" rows="3" placeholder="Tulis alasan penolakan..."
                      class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 resize-none" required></textarea>
            <p id="rejectError" class="text-red-500 text-xs mt-1 hidden">Alasan penolakan wajib diisi.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="closeModal('rejectModal')" class="flex-1 border border-gray-200 rounded-xl text-sm py-2 text-gray-600 hover:bg-gray-50 transition">Batal</button>
            <button onclick="doReject()" class="flex-1 bg-red-600 text-white rounded-xl text-sm py-2 font-medium hover:bg-red-700 transition">Tolak</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ROUTES = {
        show:    (id) => `{{ url('keuangan/verifikasi-kost') }}/${id}`,
        approve: (id) => `{{ url('keuangan/verifikasi-kost') }}/${id}/approve`,
        reject:  (id) => `{{ url('keuangan/verifikasi-kost') }}/${id}/reject`,
    };
    const CSRF = "{{ csrf_token() }}";

    let activeId = null;

    // ─── Alert ───────────────────────────────────────────────────────────────
    function showAlert(message, type = 'success') {
        const box = document.getElementById('alertBox');
        box.className = `mb-4 p-4 rounded-xl font-medium text-sm ${type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'}`;
        box.textContent = message;
        box.classList.remove('hidden');
        setTimeout(() => box.classList.add('hidden'), 4000);
    }

    // ─── Modal Helpers ────────────────────────────────────────────────────────
    function openModal(id)  { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
    function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

    // ─── Detail Modal ─────────────────────────────────────────────────────────
    async function openDetailModal(id) {
        const resp = await fetch(ROUTES.show(id));
        const json = await resp.json();
        if (!json.success) return;
        const d = json.data;
        activeId = id;

        const statusMap = {
            menunggu:  '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>',
            disetujui: '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>',
            ditolak:   '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>',
        };

        const buktiHtml = (d.bukti_bayar && d.bukti_bayar.length)
            ? d.bukti_bayar.map(b => `<a href="${b.url}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:underline text-xs"><i class="fas fa-file"></i>${b.file_name}</a>`).join('')
            : '<span class="text-gray-400 text-xs">Tidak ada bukti</span>';

        document.getElementById('detailContent').innerHTML = `
            <div class="grid grid-cols-2 gap-3">
                <div><div class="text-xs text-gray-400">Kode</div><div class="font-semibold text-gray-800">${d.kode_pengajuan}</div></div>
                <div><div class="text-xs text-gray-400">Status</div><div>${statusMap[d.status] ?? d.status}</div></div>
                <div><div class="text-xs text-gray-400">Tgl Kegiatan</div><div class="text-gray-700">${formatDate(d.tanggal_kegiatan)}</div></div>
                <div><div class="text-xs text-gray-400">Tgl Pengajuan</div><div class="text-gray-700">${formatDate(d.tanggal_pengajuan)}</div></div>
                <div><div class="text-xs text-gray-400">PIC Marketing</div><div class="text-gray-700">${d.pic_marketing?.nama ?? '-'}</div></div>
                <div><div class="text-xs text-gray-400">Diinput Oleh</div><div class="text-gray-700">${d.created_by?.nama ?? '-'}</div></div>
                <div class="col-span-2"><div class="text-xs text-gray-400">Lokasi</div><div class="text-gray-700">${d.lokasi}${d.kota ? ', '+d.kota : ''}</div></div>
                <div class="col-span-2"><div class="text-xs text-gray-400">Keterangan</div><div class="text-gray-700">${d.keterangan_kegiatan ?? '-'}</div></div>
                <div class="col-span-2"><div class="text-xs text-gray-400">Catatan</div><div class="text-gray-700">${d.catatan ?? '-'}</div></div>
                <div class="col-span-2 bg-red-50 rounded-xl p-3"><div class="text-xs text-gray-400">Nominal</div><div class="text-xl font-bold text-red-800">Rp ${numFmt(d.nominal)}</div></div>
            </div>
            <hr class="border-gray-100">
            <div>
                <div class="text-xs text-gray-400 mb-2">Bukti Pembayaran</div>
                <div class="space-y-1">${buktiHtml}</div>
            </div>
            ${d.catatan_keuangan ? `
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="text-xs text-gray-400 mb-1">Catatan Keuangan</div>
                <div class="text-gray-700 text-sm">${d.catatan_keuangan}</div>
                <div class="text-xs text-gray-400 mt-1">Oleh: ${d.verified_by?.nama ?? '-'} · ${formatDate(d.tanggal_verifikasi)}</div>
            </div>` : ''}
        `;

        // Tombol aksi di footer jika masih menunggu
        const actionsEl = document.getElementById('detailActions');
        if (d.status === 'menunggu') {
            actionsEl.innerHTML = `
                <button onclick="closeModal('detailModal'); openRejectModal(${d.id}, '${d.kode_pengajuan}')"
                        class="px-4 py-2 bg-red-100 text-red-700 rounded-xl text-sm hover:bg-red-200 transition">
                    <i class="fas fa-times mr-1"></i> Tolak
                </button>
                <button onclick="closeModal('detailModal'); openApproveModal(${d.id}, '${d.kode_pengajuan}')"
                        class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm hover:bg-green-700 transition">
                    <i class="fas fa-check mr-1"></i> Setujui
                </button>`;
        } else {
            actionsEl.innerHTML = `<button onclick="closeModal('detailModal')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm hover:bg-gray-200 transition">Tutup</button>`;
        }

        openModal('detailModal');
    }

    // ─── Approve ──────────────────────────────────────────────────────────────
    function openApproveModal(id, kode) {
        activeId = id;
        document.getElementById('approveMessage').textContent = `Pengajuan ${kode} akan disetujui.`;
        document.getElementById('approveCatatan').value = '';
        openModal('approveModal');
    }

    async function doApprove() {
        const catatan = document.getElementById('approveCatatan').value;
        const fd = new FormData();
        fd.append('catatan_keuangan', catatan);

        const resp = await fetch(ROUTES.approve(activeId), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            body: fd,
        });
        const json = await resp.json();
        closeModal('approveModal');
        if (json.success) {
            showAlert(json.message);
            setTimeout(() => location.reload(), 1200);
        } else {
            showAlert(json.message, 'error');
        }
    }

    // ─── Reject ───────────────────────────────────────────────────────────────
    function openRejectModal(id, kode) {
        activeId = id;
        document.getElementById('rejectMessage').textContent = `Pengajuan ${kode} akan ditolak.`;
        document.getElementById('rejectCatatan').value = '';
        document.getElementById('rejectError').classList.add('hidden');
        openModal('rejectModal');
    }

    async function doReject() {
        const catatan = document.getElementById('rejectCatatan').value.trim();
        if (!catatan) {
            document.getElementById('rejectError').classList.remove('hidden');
            return;
        }
        document.getElementById('rejectError').classList.add('hidden');

        const fd = new FormData();
        fd.append('catatan_keuangan', catatan);

        const resp = await fetch(ROUTES.reject(activeId), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            body: fd,
        });
        const json = await resp.json();
        closeModal('rejectModal');
        if (json.success) {
            showAlert(json.message);
            setTimeout(() => location.reload(), 1200);
        } else {
            showAlert(json.message, 'error');
        }
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────
    function formatDate(str) {
        if (!str) return '-';
        return new Date(str).toLocaleDateString('id-ID', { day:'2-digit', month:'2-digit', year:'numeric' });
    }

    function numFmt(n) {
        return Number(n).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    }
</script>
@endpush
