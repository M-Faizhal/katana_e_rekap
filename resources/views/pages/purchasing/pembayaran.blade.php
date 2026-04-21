@extends('layouts.app')

@section('title', 'Pembayaran - Cyber KATANA')

@php
    $currentUser = Auth::user();
    $isAdminPurchasing = $currentUser->role === 'admin_purchasing';
@endphp

@push('scripts')
<script>
// Toggle Vendor Details Dropdown - Enhanced Version
function toggleVendorDetails(elementId) {
    const vendorDetails = document.getElementById(`vendor-details-${elementId}`);
    const chevronIcon = document.getElementById(`chevron-${elementId}`);
    
    if (!vendorDetails) return;
    
    if (vendorDetails.classList.contains('hidden')) {
        vendorDetails.classList.remove('hidden');
        vendorDetails.classList.add('animate-fadeIn');
        if (chevronIcon) chevronIcon.style.transform = 'rotate(180deg)';
    } else {
        vendorDetails.classList.add('hidden');
        vendorDetails.classList.remove('animate-fadeIn');
        if (chevronIcon) chevronIcon.style.transform = 'rotate(0deg)';
    }
}

// Tab Navigation Functions
function openTab(evt, tabName) {
    const tabcontent = Array.from(document.getElementsByClassName("tab-content"));
    tabcontent.forEach(tc => tc.style.display = "none");

    const tabbuttons = Array.from(document.getElementsByClassName("tab-button"));
    tabbuttons.forEach(btn => {
        btn.classList.remove("border-red-500", "text-red-600", "bg-white");
        btn.classList.add("border-transparent", "text-gray-500", "bg-gray-50");
        btn.querySelectorAll('span').forEach(badge => {
            if (badge.classList.contains('bg-red-100')) {
                badge.classList.remove('bg-red-100', 'text-red-800');
                badge.classList.add('bg-gray-200', 'text-gray-700');
            }
        });
    });

    const activeContent = document.getElementById(tabName);
    if (activeContent) activeContent.style.display = "block";

    evt.currentTarget.classList.remove("border-transparent", "text-gray-500", "bg-gray-50");
    evt.currentTarget.classList.add("border-red-500", "text-red-600", "bg-white");

    evt.currentTarget.querySelectorAll('span').forEach(badge => {
        if (badge.classList.contains('bg-gray-200')) {
            badge.classList.remove('bg-gray-200', 'text-gray-700');
            badge.classList.add('bg-red-100', 'text-red-800');
        }
    });

    // Update URL parameter - always, sehingga persistent saat reload / pagination
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName.replace('tab-', ''));
    // Reset halaman-halaman pagination yang tidak relevan saat ganti tab manual
    if (evt.isTrusted) {
        url.searchParams.delete('page');
        url.searchParams.delete('proyek_page');
        url.searchParams.delete('pembayaran_page');
        url.searchParams.delete('po_page');
        window.history.pushState({}, '', url);
    }
}

// Initialize tabs on page load - prioritas: PHP $activeTab dari URL
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = '{{ $activeTab ?? "perlu-bayar" }}';
    const tabId = 'tab-' + activeTab;

    // Hide semua tab
    document.querySelectorAll('.tab-content').forEach(tc => tc.style.display = 'none');

    // Reset semua button
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove("border-red-500", "text-red-600", "bg-white");
        btn.classList.add("border-transparent", "text-gray-500", "bg-gray-50");
        btn.querySelectorAll('span').forEach(badge => {
            if (badge.classList.contains('bg-red-100')) {
                badge.classList.remove('bg-red-100', 'text-red-800');
                badge.classList.add('bg-gray-200', 'text-gray-700');
            }
        });
    });

    // Aktifkan tab yang sesuai
    const activeContent = document.getElementById(tabId);
    if (activeContent) activeContent.style.display = 'block';

    const activeBtn = document.querySelector(`button[data-tab="${activeTab}"]`);
    if (activeBtn) {
        activeBtn.classList.remove("border-transparent", "text-gray-500", "bg-gray-50");
        activeBtn.classList.add("border-red-500", "text-red-600", "bg-white");
        activeBtn.querySelectorAll('span').forEach(badge => {
            if (badge.classList.contains('bg-gray-200')) {
                badge.classList.remove('bg-gray-200', 'text-gray-700');
                badge.classList.add('bg-red-100', 'text-red-800');
            }
        });
    } else {
        // Fallback ke tab default
        document.getElementById("defaultOpen").click();
    }

    // Auto-submit filter forms saat select berubah
    const filterForm = document.getElementById('filter-form-semua-proyek');
    if (filterForm) {
        filterForm.querySelectorAll('select').forEach(sel => {
            sel.addEventListener('change', () => filterForm.submit());
        });
    }

    // PO vendor select dynamic link
    document.querySelectorAll('.po-vendor-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const proyekId = this.dataset.proyek;
            const vendorId = this.value;
            const btn = document.querySelector(`.po-create-btn[data-proyek="${proyekId}"]`);
            if (btn && vendorId) {
                btn.href = `/purchasing/pembayaran/pembuatan-surat-po/${proyekId}/${vendorId}`;
            }
        });
    });
});

// Modal for Project Detail
function showProyekDetail(proyek) {
    const modal = document.getElementById('proyekDetailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');

    modalTitle.textContent = `Detail: ${proyek.nama_barang}`;

    const totalPembayaran = proyek.pembayaran ? proyek.pembayaran.length : 0;
    const pendingCount    = proyek.pembayaran ? proyek.pembayaran.filter(p => p.status_verifikasi === 'Pending').length : 0;
    const approvedCount   = proyek.pembayaran ? proyek.pembayaran.filter(p => p.status_verifikasi === 'Approved').length : 0;
    const ditolakCount    = proyek.pembayaran ? proyek.pembayaran.filter(p => p.status_verifikasi === 'Ditolak').length : 0;

    const currentUserRole = '{{ $currentUser->role }}';
    const currentUserId   = {{ $currentUser->id_user }};
    const canAccess       = currentUserRole === 'admin_purchasing' && proyek.id_admin_purchasing == currentUserId;

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <h4 class="font-medium text-gray-900 border-b pb-1">Informasi Proyek</h4>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium text-gray-600">Nama Barang:</span> ${proyek.nama_barang}</div>
                    <div><span class="font-medium text-gray-600">Instansi:</span> ${proyek.instansi}</div>
                    <div><span class="font-medium text-gray-600">Kota/Kab:</span> ${proyek.kab_kota}</div>
                    <div><span class="font-medium text-gray-600">Klien:</span> ${proyek.nama_klien}</div>
                    <div><span class="font-medium text-gray-600">Kontak:</span> ${proyek.kontak_klien || 'Tidak ada'}</div>
                    <div><span class="font-medium text-gray-600">No. Penawaran:</span> ${proyek.penawaran_aktif ? proyek.penawaran_aktif.no_penawaran : 'N/A'}</div>
                    <div><span class="font-medium text-gray-600">Dibuat:</span> ${new Date(proyek.created_at).toLocaleDateString('id-ID')}</div>
                </div>
            </div>
            <div class="space-y-3">
                <h4 class="font-medium text-gray-900 border-b pb-1">Informasi Pembayaran</h4>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium text-gray-600">Total Penawaran:</span>
                        <span class="font-semibold text-green-600">Rp ${proyek.penawaran_aktif ? new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(proyek.penawaran_aktif.total_penawaran) : '0,00'}</span>
                    </div>
                    <div><span class="font-medium text-gray-600">Total Dibayar (Approved):</span>
                        <span class="font-semibold text-blue-600">Rp ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(proyek.total_dibayar_approved || 0)}</span>
                    </div>
                    <div><span class="font-medium text-gray-600">Sisa Bayar:</span>
                        <span class="font-semibold ${proyek.status_lunas ? 'text-green-600' : 'text-orange-600'}">
                            ${proyek.status_lunas ? 'LUNAS' : 'Rp ' + new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(proyek.sisa_bayar || 0)}
                        </span>
                    </div>
                    <div><span class="font-medium text-gray-600">Progress:</span>
                        <span class="font-semibold">${(proyek.persen_bayar || 0).toFixed(2)}%</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: ${Math.min(proyek.persen_bayar || 0, 100)}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <h4 class="font-medium text-gray-900 border-b pb-1 mb-3">Statistik Pembayaran</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg"><div class="text-2xl font-bold text-gray-800">${totalPembayaran}</div><div class="text-xs text-gray-600">Total Transaksi</div></div>
                <div class="text-center p-3 bg-yellow-50 rounded-lg"><div class="text-2xl font-bold text-yellow-600">${pendingCount}</div><div class="text-xs text-yellow-600">Pending</div></div>
                <div class="text-center p-3 bg-green-50 rounded-lg"><div class="text-2xl font-bold text-green-600">${approvedCount}</div><div class="text-xs text-green-600">Approved</div></div>
                <div class="text-center p-3 bg-red-50 rounded-lg"><div class="text-2xl font-bold text-red-600">${ditolakCount}</div><div class="text-xs text-red-600">Ditolak</div></div>
            </div>
        </div>
        <div class="mt-6 flex flex-wrap gap-2">
            ${canAccess && !proyek.status_lunas ? `
                <a href="/purchasing/pembayaran/create/${proyek.id_proyek}"
                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Input Pembayaran Baru
                </a>` : ''}
            ${totalPembayaran > 0 ? `
                <a href="/purchasing/pembayaran/history/${proyek.id_proyek}"
                   class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-history mr-2"></i>Lihat Riwayat Pembayaran
                </a>` : ''}
        </div>
    `;

    modal.classList.remove('hidden');
}

function closeProyekDetail() {
    document.getElementById('proyekDetailModal').classList.add('hidden');
}

// =============================================
// Modal Bukti Pembayaran (Multi-file)
// =============================================
let currentLightboxImages = [];
let currentLightboxIndex = 0;

function openBuktiModal(files, namaBarang) {
    const modal    = document.getElementById('buktiModal');
    const title    = document.getElementById('buktiModalTitle');
    const subtitle = document.getElementById('buktiModalSubtitle');
    const content  = document.getElementById('buktiModalContent');

    title.textContent    = 'Bukti Pembayaran';
    subtitle.textContent = namaBarang || '';

    const imageFiles = [];
    const pdfFiles   = [];
    files.forEach(function(url) {
        const ext = url.split('?')[0].split('.').pop().toLowerCase();
        if (['jpg','jpeg','png','gif','webp','bmp'].includes(ext)) {
            imageFiles.push(url);
        } else {
            pdfFiles.push(url);
        }
    });

    currentLightboxImages = imageFiles;
    currentLightboxIndex  = 0;

    let html = '';

    if (files.length === 0) {
        html = `<div class="text-center py-10 text-gray-400"><i class="fas fa-file-slash text-4xl mb-3"></i><p class="text-sm">Tidak ada file bukti pembayaran.</p></div>`;
    } else {
        html += `<p class="text-xs text-gray-500 mb-4"><i class="fas fa-paperclip mr-1"></i>${files.length} file bukti pembayaran</p>`;

        if (imageFiles.length > 0) {
            html += `<div class="mb-5"><h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2"><i class="fas fa-images text-blue-500"></i> Gambar (${imageFiles.length} file)</h4><div class="grid grid-cols-2 sm:grid-cols-3 gap-3">`;
            imageFiles.forEach(function(url, idx) {
                html += `<div class="relative group rounded-xl overflow-hidden border border-gray-200 bg-gray-50 aspect-square cursor-pointer shadow-sm hover:shadow-md transition-shadow" onclick="openLightbox(${idx})">
                    <img src="${url}" alt="Bukti ${idx+1}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" onerror="this.parentElement.innerHTML='<div class=\\'flex flex-col items-center justify-center h-full text-gray-400\\'><i class=\\'fas fa-image text-2xl mb-1\\'></i><span class=\\'text-xs\\'>Gagal muat</span></div>'">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-200 flex items-center justify-center"><i class="fas fa-search-plus text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 drop-shadow"></i></div>
                    <div class="absolute bottom-1 right-1 bg-black/50 text-white text-xs px-1.5 py-0.5 rounded">${idx+1}/${imageFiles.length}</div>
                </div>`;
            });
            html += `</div><p class="text-xs text-gray-400 mt-2 flex items-center gap-1"><i class="fas fa-info-circle"></i> Klik gambar untuk zoom/preview</p></div>`;
        }

        if (pdfFiles.length > 0) {
            html += `<div><h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2"><i class="fas fa-file-pdf text-red-500"></i> Dokumen PDF (${pdfFiles.length} file)</h4><div class="space-y-2">`;
            pdfFiles.forEach(function(url) {
                const fileName = url.split('/').pop().split('?')[0];
                html += `<div class="flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0"><i class="fas fa-file-pdf text-red-600 text-lg"></i></div>
                    <div class="flex-1 min-w-0"><p class="text-sm font-medium text-gray-800 truncate">${fileName}</p><p class="text-xs text-gray-500">Dokumen PDF</p></div>
                    <div class="flex gap-2">
                        <a href="${url}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors" onclick="event.stopPropagation()"><i class="fas fa-external-link-alt mr-1.5"></i>Buka</a>
                        <a href="${url}" download class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition-colors" onclick="event.stopPropagation()"><i class="fas fa-download mr-1.5"></i>Unduh</a>
                    </div>
                </div>`;
            });
            html += `</div></div>`;
        }
    }

    content.innerHTML = html;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBuktiModal() {
    document.getElementById('buktiModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function openLightbox(idx) {
    if (currentLightboxImages.length === 0) return;
    currentLightboxIndex = idx;
    document.getElementById('lightboxImage').src = currentLightboxImages[idx];
    document.getElementById('lightboxCounter').textContent = `${idx + 1} / ${currentLightboxImages.length}`;
    document.getElementById('imageLightbox').classList.remove('hidden');
    document.getElementById('imageLightbox').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('imageLightbox').classList.add('hidden');
    document.getElementById('imageLightbox').style.display = 'none';
}

function prevLightboxImage(event) {
    event.stopPropagation();
    if (currentLightboxImages.length === 0) return;
    currentLightboxIndex = (currentLightboxIndex - 1 + currentLightboxImages.length) % currentLightboxImages.length;
    openLightbox(currentLightboxIndex);
}

function nextLightboxImage(event) {
    event.stopPropagation();
    if (currentLightboxImages.length === 0) return;
    currentLightboxIndex = (currentLightboxIndex + 1) % currentLightboxImages.length;
    openLightbox(currentLightboxIndex);
}

// Close all dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick*="toggleVendorDetails"]') &&
        !event.target.closest('[id*="vendor-details-"]')) {
        document.querySelectorAll('[id*="vendor-details-"]:not(.hidden)').forEach(dropdown => {
            const elementId  = dropdown.id.replace('vendor-details-', '');
            const chevronIcon = document.getElementById(`chevron-${elementId}`);
            dropdown.classList.add('hidden');
            dropdown.classList.remove('animate-fadeIn');
            if (chevronIcon) chevronIcon.style.transform = 'rotate(0deg)';
        });
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('proyekDetailModal');
    if (modal && e.target === modal) closeProyekDetail();
    const buktiModal = document.getElementById('buktiModal');
    if (buktiModal && e.target === buktiModal) closeBuktiModal();
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProyekDetail();
        closeBuktiModal();
        closeLightbox();
    }
});
</script>

<style>
.animate-fadeIn { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
[id*="chevron-"] { transition: transform 0.3s ease-in-out; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-xl">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="flex-1">
                <h1 class="text-xl sm:text-2xl lg:text-4xl font-bold mb-2">Pembayaran Purchasing</h1>
                <p class="text-red-100 text-sm sm:text-base lg:text-lg opacity-90">Kelola pembayaran proyek yang sudah di-ACC klien (termasuk proyek selesai dan gagal yang belum lunas)</p>
            </div>
            <div class="hidden lg:flex items-center justify-center w-16 h-16 lg:w-20 lg:h-20 bg-red-700 rounded-2xl">
                <i class="fas fa-credit-card text-2xl lg:text-4xl opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-4 sm:mb-6 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-3 sm:p-4 rounded-r-lg shadow-sm">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 text-base sm:text-lg"></i>
            <p class="ml-2 sm:ml-3 text-green-800 font-medium text-sm sm:text-base">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 sm:mb-6 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-3 sm:p-4 rounded-r-lg shadow-sm">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 text-base sm:text-lg"></i>
            <p class="ml-2 sm:ml-3 text-red-800 font-medium text-sm sm:text-base">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
        <!-- Tab Headers -->
        <div class="border-b border-gray-200 bg-gray-50 rounded-t-2xl">
            <nav class="flex space-x-0" aria-label="Tabs">
                <button onclick="openTab(event, 'tab-perlu-bayar')"
                        data-tab="perlu-bayar"
                        class="tab-button flex-1 py-2 sm:py-4 px-2 sm:px-6 border-b-3 font-semibold text-xs sm:text-sm md:text-base focus:outline-none transition-all duration-300 border-red-500 text-red-600 bg-white rounded-tl-2xl"
                        id="defaultOpen">
                    <div class="flex items-center justify-center gap-1 sm:gap-2">
                        <i class="fas fa-exclamation-triangle text-xs sm:text-sm md:text-base"></i>
                        <span class="hidden sm:inline">Proyek Perlu Pembayaran</span>
                        <span class="sm:hidden">Perlu Bayar</span>
                        <span class="bg-red-100 text-red-800 text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.2rem] h-5 flex items-center justify-center">
                            {{ $proyekPerluBayar->total() }}
                        </span>
                    </div>
                </button>

                <button onclick="openTab(event, 'tab-semua-proyek')"
                        data-tab="semua-proyek"
                        class="tab-button flex-1 py-2 sm:py-4 px-2 sm:px-6 border-b-3 font-semibold text-xs sm:text-sm md:text-base focus:outline-none transition-all duration-300 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 bg-gray-50">
                    <div class="flex items-center justify-center gap-1 sm:gap-2">
                        <i class="fas fa-list text-xs sm:text-sm md:text-base"></i>
                        <span class="hidden sm:inline">Semua Proyek</span>
                        <span class="sm:hidden">Proyek</span>
                        <span class="bg-gray-200 text-gray-700 text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.2rem] h-5 flex items-center justify-center">
                            {{ $semuaProyek->total() }}
                        </span>
                    </div>
                </button>

                <button onclick="openTab(event, 'tab-semua-pembayaran')"
                        data-tab="semua-pembayaran"
                        class="tab-button flex-1 py-2 sm:py-4 px-2 sm:px-6 border-b-3 font-semibold text-xs sm:text-sm md:text-base focus:outline-none transition-all duration-300 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 bg-gray-50">
                    <div class="flex items-center justify-center gap-1 sm:gap-2">
                        <i class="fas fa-receipt text-xs sm:text-sm md:text-base"></i>
                        <span class="hidden sm:inline">Semua Pembayaran</span>
                        <span class="sm:hidden">Pembayaran</span>
                        <span class="bg-gray-200 text-gray-700 text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.2rem] h-5 flex items-center justify-center">
                            {{ $semuaPembayaran->total() }}
                        </span>
                    </div>
                </button>

                <button onclick="openTab(event, 'tab-pembuatan-surat-po')"
                        data-tab="pembuatan-surat-po"
                        class="tab-button flex-1 py-2 sm:py-4 px-2 sm:px-6 border-b-3 font-semibold text-xs sm:text-sm md:text-base focus:outline-none transition-all duration-300 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 bg-gray-50 rounded-tr-2xl">
                    <div class="flex items-center justify-center gap-1 sm:gap-2">
                        <i class="fas fa-file-signature text-xs sm:text-sm md:text-base"></i>
                        <span class="hidden sm:inline">Pembuatan Surat PO</span>
                        <span class="sm:hidden">Surat PO</span>
                    </div>
                </button>
            </nav>
        </div>

        {{-- =====================================================
             TAB 1: Proyek Perlu Pembayaran
             ===================================================== --}}
        <div id="tab-perlu-bayar" class="tab-content">
            <div class="p-3 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 sm:mb-6">
                    <div>
                        <h2 class="text-base sm:text-xl md:text-2xl font-semibold text-gray-800">Proyek Perlu Pembayaran</h2>
                        <p class="text-xs sm:text-sm md:text-base text-gray-600 mt-1">Daftar proyek yang sudah di-ACC dan menunggu pembayaran dari klien</p>
                    </div>
                    <form method="GET" class="flex gap-2">
                        <input type="hidden" name="tab" value="perlu-bayar">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('tab') === 'perlu-bayar' ? request('search') : '' }}"
                                   placeholder="Cari proyek..."
                                   class="px-4 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 w-full sm:w-64">
                            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @if(request('tab') === 'perlu-bayar' && request('search'))
                        <a href="{{ route('purchasing.pembayaran') }}?tab=perlu-bayar"
                           class="px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600">
                            <i class="fas fa-times mr-1"></i>Reset
                        </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    @if($proyekPerluBayar->count() > 0)
                    <div class="space-y-4">
                        @foreach($proyekPerluBayar as $proyek)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="p-4 cursor-pointer" onclick="toggleVendorDetails('proyek-{{ $proyek->id_proyek }}')">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <div class="w-3 h-3 rounded-full
                                                @if($proyek->status == 'Pembayaran') bg-blue-500
                                                @elseif($proyek->status == 'Pengiriman') bg-purple-500
                                                @elseif($proyek->status == 'Selesai') bg-green-500
                                                @elseif($proyek->status == 'Gagal') bg-red-500
                                                @else bg-gray-500 @endif">
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $proyek->nama_barang }}</h3>
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                @if($proyek->status == 'Pembayaran') bg-blue-100 text-blue-800
                                                @elseif($proyek->status == 'Pengiriman') bg-purple-100 text-purple-800
                                                @elseif($proyek->status == 'Selesai') bg-green-100 text-green-800
                                                @elseif($proyek->status == 'Gagal') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $proyek->status }}
                                            </span>
                                            @if($proyek->status_lunas)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>LUNAS
                                            </span>
                                            @elseif($proyek->total_dibayar_approved > 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>CICILAN
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>BELUM BAYAR
                                            </span>
                                            @endif
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div class="space-y-1">
                                                <p class="text-gray-600"><i class="fas fa-hashtag text-gray-400 mr-2"></i><span class="font-medium">Kode Proyek:</span> {{ $proyek->kode_proyek }}</p>
                                                <p class="text-gray-600"><i class="fas fa-building text-gray-400 mr-2"></i>{{ $proyek->instansi }} - {{ $proyek->kab_kota }}</p>
                                                <p class="text-gray-600"><i class="fas fa-file-contract text-gray-400 mr-2"></i>No. Penawaran: {{ $proyek->penawaranAktif->no_penawaran }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-gray-600"><span class="font-medium">Total Modal ke Vendor:</span> <span class="text-green-600 font-semibold">Rp {{ number_format($proyek->vendors_data->sum('total_vendor'), 2, ',', '.') }}</span></p>
                                                <p class="text-gray-600"><span class="font-medium">Vendor Belum Lunas:</span> <span class="text-red-600 font-semibold">{{ $proyek->vendors_data->where('status_lunas', false)->count() }} dari {{ $proyek->vendors_data->count() }}</span></p>
                                                <p class="text-gray-600"><span class="font-medium">Progress:</span> <span class="text-blue-600 font-semibold">{{ number_format($proyek->persen_bayar, 1) }}%</span></p>
                                                <p class="text-gray-600"><span class="font-medium">Dibuat:</span> {{ $proyek->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="chevron-proyek-{{ $proyek->id_proyek }}"></i>
                                    </div>
                                </div>
                            </div>

                            <div id="vendor-details-proyek-{{ $proyek->id_proyek }}" class="hidden border-t border-gray-100 bg-gray-50">
                                <div class="p-4">
                                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div class="text-center">
                                                <p class="text-blue-700 font-medium">Harga Penawaran</p>
                                                <p class="text-lg font-bold text-blue-900">Rp {{ number_format($proyek->penawaranAktif->total_penawaran, 2, ',', '.') }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-green-700 font-medium">Total Modal Vendor</p>
                                                <p class="text-lg font-bold text-green-900">Rp {{ number_format($proyek->vendors_data->sum('total_vendor'), 2, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <h4 class="font-medium text-gray-900 mb-3">Detail Pembayaran Vendor</h4>
                                        @foreach($proyek->vendors_data as $vendorData)
                                        <div class="p-3 border rounded-lg @if($vendorData->status_lunas) bg-green-50 border-green-200 @else bg-white border-gray-200 @endif">
                                            <div class="flex items-center justify-between mb-2">
                                                <h5 class="font-medium text-gray-900">{{ $vendorData->vendor->nama_vendor }}</h5>
                                                @if($vendorData->status_lunas)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>LUNAS</span>
                                                @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-exclamation-triangle mr-1"></i>BELUM LUNAS</span>
                                                @endif
                                            </div>
                                            @php
                                                $barangVendor = $proyek->penawaranAktif->penawaranDetail
                                                    ->where('barang.id_vendor', $vendorData->vendor->id_vendor)
                                                    ->pluck('barang.nama_barang')->unique()->values();
                                            @endphp
                                            @if($barangVendor->count() > 0)
                                            <div class="mb-3 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                                <p class="text-xs font-medium text-blue-700 mb-1"><i class="fas fa-cube mr-1"></i>Barang yang Ditangani:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($barangVendor as $nb)
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ $nb }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                                <div>
                                                    <p class="text-sm text-gray-600">{{ $vendorData->vendor->jenis_perusahaan }}</p>
                                                    <p class="text-xs text-gray-500">{{ $vendorData->vendor->email }}</p>
                                                </div>
                                                <div class="text-sm">
                                                    <div class="flex justify-between"><span class="text-gray-600">Total Modal:</span><span class="font-medium">Rp {{ number_format($vendorData->total_vendor, 2, ',', '.') }}</span></div>
                                                    <div class="flex justify-between"><span class="text-gray-600">Dibayar:</span><span class="font-medium text-green-600">Rp {{ number_format($vendorData->total_dibayar_approved, 2, ',', '.') }}</span></div>
                                                    @if(!$vendorData->status_lunas)
                                                    <div class="flex justify-between"><span class="text-gray-600">Sisa:</span><span class="font-medium text-red-600">Rp {{ number_format($vendorData->sisa_bayar, 2, ',', '.') }}</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-xs text-gray-600">Progress Pembayaran</span>
                                                    <span class="text-xs font-medium text-gray-700">{{ number_format($vendorData->persen_bayar, 1) }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="@if($vendorData->status_lunas) bg-green-600 @else bg-blue-600 @endif h-2 rounded-full transition-all duration-300" style="width: {{ min($vendorData->persen_bayar, 100) }}%"></div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @php
                                                    $canAccess = ($currentUser->role === 'admin_purchasing' || $currentUser->role === 'superadmin') && ($proyek->id_admin_purchasing == $currentUser->id_user || $currentUser->role === 'superadmin');
                                                @endphp
                                                @if(!$vendorData->status_lunas && $canAccess)
                                                <a href="{{ route('purchasing.pembayaran.create', [$proyek->id_proyek, $vendorData->vendor->id_vendor]) }}"
                                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                    <i class="fas fa-plus mr-1"></i>Input Pembayaran
                                                </a>
                                                @endif
                                                @if($proyek->pembayaran->where('id_vendor', $vendorData->vendor->id_vendor)->count() > 0)
                                                <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}?vendor={{ $vendorData->vendor->id_vendor }}"
                                                   class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                    <i class="fas fa-history mr-1"></i>History
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-credit-card text-4xl text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada proyek yang perlu pembayaran</h3>
                        <p class="mt-1 text-sm text-gray-500">Semua proyek sudah dalam tahap selanjutnya atau belum ada yang di-ACC.</p>
                    </div>
                    @endif
                </div>

                @if($proyekPerluBayar->hasPages())
                <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-medium">{{ $proyekPerluBayar->firstItem() ?? 0 }} - {{ $proyekPerluBayar->lastItem() ?? 0 }}</span>
                            dari <span class="font-semibold text-gray-800">{{ $proyekPerluBayar->total() }}</span> proyek
                        </div>
                        <div class="flex justify-center">
                            {{-- Pagination harus selalu membawa parameter tab=perlu-bayar --}}
                            {{ $proyekPerluBayar->appends(array_merge(request()->query(), ['tab' => 'perlu-bayar']))->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- =====================================================
             TAB 2: Semua Proyek (dengan filter tahun)
             ===================================================== --}}
        <div id="tab-semua-proyek" class="tab-content" style="display:none;">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Semua Proyek Pembayaran</h2>
                        <p class="text-gray-600 mt-1">Daftar lengkap proyek berdasarkan tahun penawaran yang di-ACC</p>
                    </div>

                    {{-- Filter & Search --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <form method="GET" class="flex flex-col sm:flex-row gap-2 flex-wrap" id="filter-form-semua-proyek">
                            <input type="hidden" name="tab" value="semua-proyek">

                            {{-- Search --}}
                            <div class="relative">
                                <input type="text" name="search"
                                       value="{{ request('tab') === 'semua-proyek' ? request('search') : '' }}"
                                       placeholder="Cari proyek..."
                                       class="block w-full sm:w-52 px-3 py-2 pr-10 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>

                            {{-- Filter Tahun --}}
                            <select name="tahun_filter" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @if(!empty($availableYears))
                                    @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ $tahunFilter == $year ? 'selected' : '' }}>
                                        Tahun {{ $year }}
                                    </option>
                                    @endforeach
                                @else
                                    <option value="{{ date('Y') }}" selected>Tahun {{ date('Y') }}</option>
                                @endif
                            </select>

                            {{-- Status Lunas --}}
                            <select name="proyek_status_filter" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all" {{ request('proyek_status_filter', 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="lunas" {{ request('proyek_status_filter') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="belum_lunas" {{ request('proyek_status_filter') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            </select>

                            {{-- Sort --}}
                            <select name="sort_by" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="desc" {{ request('sort_by', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                <option value="asc" {{ request('sort_by') == 'asc' ? 'selected' : '' }}>Terlama</option>
                            </select>

                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                <i class="fas fa-filter mr-1"></i>Filter
                            </button>

                            @if(request('tab') === 'semua-proyek' && (request('search') || (request('proyek_status_filter') && request('proyek_status_filter') != 'all') || request('sort_by', 'desc') != 'desc' || request('tahun_filter') != date('Y')))
                            <a href="{{ route('purchasing.pembayaran') }}?tab=semua-proyek"
                               class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                                <i class="fas fa-times mr-1"></i>Reset
                            </a>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- Info tahun aktif --}}
                <div class="mb-4 flex items-center gap-2 text-sm text-blue-700 bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Menampilkan proyek pada <strong>Tahun {{ $tahunFilter }}</strong></span>
                </div>

                <div class="overflow-x-auto">
                    @if($semuaProyek->count() > 0)
                    <div class="space-y-4">
                        @foreach($semuaProyek as $proyek)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="p-4 cursor-pointer" onclick="toggleVendorDetails('semua-proyek-{{ $proyek->id_proyek }}')">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <div class="w-3 h-3 rounded-full
                                                @if($proyek->status == 'Pembayaran') bg-blue-500
                                                @elseif($proyek->status == 'Pengiriman') bg-purple-500
                                                @elseif($proyek->status == 'Selesai') bg-green-500
                                                @elseif($proyek->status == 'Gagal') bg-red-500
                                                @else bg-gray-500 @endif">
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $proyek->nama_barang }}</h3>
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                @if($proyek->status == 'Pembayaran') bg-blue-100 text-blue-800
                                                @elseif($proyek->status == 'Pengiriman') bg-purple-100 text-purple-800
                                                @elseif($proyek->status == 'Selesai') bg-green-100 text-green-800
                                                @elseif($proyek->status == 'Gagal') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $proyek->status }}
                                            </span>
                                            @if($proyek->status_lunas)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>LUNAS</span>
                                            @elseif($proyek->total_dibayar_approved > 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>CICILAN</span>
                                            @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>BELUM BAYAR</span>
                                            @endif
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div class="space-y-1">
                                                <p class="text-gray-600"><i class="fas fa-hashtag text-gray-400 mr-2"></i><span class="font-medium">Kode Proyek:</span> {{ $proyek->kode_proyek }}</p>
                                                <p class="text-gray-600"><i class="fas fa-building text-gray-400 mr-2"></i>{{ $proyek->instansi }} - {{ $proyek->kab_kota }}</p>
                                                <p class="text-gray-600"><i class="fas fa-file-contract text-gray-400 mr-2"></i>No. Penawaran: {{ $proyek->penawaranAktif->no_penawaran }}</p>
                                                <p class="text-gray-600"><i class="fas fa-calendar text-gray-400 mr-2"></i>Tgl Penawaran: {{ optional($proyek->penawaranAktif->tanggal_penawaran)->format('d/m/Y') ?? '-' }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-gray-600"><span class="font-medium">Total Modal ke Vendor:</span> <span class="text-green-600 font-semibold">Rp {{ number_format($proyek->vendors_data->sum('total_vendor'), 2, ',', '.') }}</span></p>
                                                <p class="text-gray-600"><span class="font-medium">Vendor Belum Lunas:</span> <span class="text-red-600 font-semibold">{{ $proyek->vendors_data->where('status_lunas', false)->count() }} dari {{ $proyek->vendors_data->count() }}</span></p>
                                                <p class="text-gray-600"><span class="font-medium">Progress:</span> <span class="text-blue-600 font-semibold">{{ number_format($proyek->persen_bayar, 1) }}%</span></p>
                                                <p class="text-gray-600"><span class="font-medium">Dibuat:</span> {{ $proyek->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="chevron-semua-proyek-{{ $proyek->id_proyek }}"></i>
                                    </div>
                                </div>
                            </div>

                            <div id="vendor-details-semua-proyek-{{ $proyek->id_proyek }}" class="hidden border-t border-gray-100 bg-gray-50">
                                <div class="p-4">
                                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div class="text-center">
                                                <p class="text-blue-700 font-medium">Harga Penawaran</p>
                                                <p class="text-lg font-bold text-blue-900">Rp {{ number_format($proyek->penawaranAktif->total_penawaran, 2, ',', '.') }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-green-700 font-medium">Total Modal Vendor</p>
                                                <p class="text-lg font-bold text-green-900">Rp {{ number_format($proyek->vendors_data->sum('total_vendor'), 2, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <h4 class="font-medium text-gray-900 mb-3">Detail Pembayaran Vendor</h4>
                                        @foreach($proyek->vendors_data as $vendorData)
                                        <div class="p-3 border rounded-lg @if($vendorData->status_lunas) bg-green-50 border-green-200 @else bg-white border-gray-200 @endif">
                                            <div class="flex items-center justify-between mb-2">
                                                <h5 class="font-medium text-gray-900">{{ $vendorData->vendor->nama_vendor }}</h5>
                                                @if($vendorData->status_lunas)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>LUNAS</span>
                                                @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-exclamation-triangle mr-1"></i>BELUM LUNAS</span>
                                                @endif
                                            </div>
                                            @php
                                                $barangVendor2 = $proyek->penawaranAktif->penawaranDetail
                                                    ->where('barang.id_vendor', $vendorData->vendor->id_vendor)
                                                    ->pluck('barang.nama_barang')->unique()->values();
                                            @endphp
                                            @if($barangVendor2->count() > 0)
                                            <div class="mb-3 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                                <p class="text-xs font-medium text-blue-700 mb-1"><i class="fas fa-cube mr-1"></i>Barang yang Ditangani:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($barangVendor2 as $nb2)
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ $nb2 }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                                <div>
                                                    <p class="text-sm text-gray-600">{{ $vendorData->vendor->jenis_perusahaan }}</p>
                                                    <p class="text-xs text-gray-500">{{ $vendorData->vendor->email }}</p>
                                                </div>
                                                <div class="text-sm">
                                                    <div class="flex justify-between"><span class="text-gray-600">Total Modal:</span><span class="font-medium">Rp {{ number_format($vendorData->total_vendor, 2, ',', '.') }}</span></div>
                                                    <div class="flex justify-between"><span class="text-gray-600">Dibayar:</span><span class="font-medium text-green-600">Rp {{ number_format($vendorData->total_dibayar_approved, 2, ',', '.') }}</span></div>
                                                    @if(!$vendorData->status_lunas)
                                                    <div class="flex justify-between"><span class="text-gray-600">Sisa:</span><span class="font-medium text-red-600">Rp {{ number_format($vendorData->sisa_bayar, 2, ',', '.') }}</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-xs text-gray-600">Progress Pembayaran</span>
                                                    <span class="text-xs font-medium text-gray-700">{{ number_format($vendorData->persen_bayar, 1) }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="@if($vendorData->status_lunas) bg-green-600 @else bg-blue-600 @endif h-2 rounded-full transition-all duration-300" style="width: {{ min($vendorData->persen_bayar, 100) }}%"></div>
                                                </div>
                                            </div>
                                            @php
                                                $vendorPembayaran = $proyek->pembayaran->where('id_vendor', $vendorData->vendor->id_vendor);
                                                $vendorPending    = $vendorPembayaran->where('status_verifikasi', 'Pending')->count();
                                                $vendorApproved   = $vendorPembayaran->where('status_verifikasi', 'Approved')->count();
                                                $vendorDitolak    = $vendorPembayaran->where('status_verifikasi', 'Ditolak')->count();
                                            @endphp
                                            @if($vendorPembayaran->count() > 0)
                                            <div class="mb-3 p-2 bg-gray-50 rounded text-xs">
                                                <span class="font-medium text-gray-700">Riwayat:</span>
                                                <span class="text-gray-600">{{ $vendorPembayaran->count() }} transaksi</span>
                                                @if($vendorPending > 0)<span class="text-yellow-600 ml-1">({{ $vendorPending }} pending)</span>@endif
                                                @if($vendorApproved > 0)<span class="text-green-600 ml-1">({{ $vendorApproved }} approved)</span>@endif
                                                @if($vendorDitolak > 0)<span class="text-red-600 ml-1">({{ $vendorDitolak }} ditolak)</span>@endif
                                            </div>
                                            @endif
                                            <div class="flex items-center space-x-2">
                                                @php
                                                    $canAccess2 = ($currentUser->role === 'admin_purchasing' || $currentUser->role === 'superadmin') && ($proyek->id_admin_purchasing == $currentUser->id_user || $currentUser->role === 'superadmin');
                                                @endphp
                                                @if(!$vendorData->status_lunas && $canAccess2)
                                                <a href="{{ route('purchasing.pembayaran.create', [$proyek->id_proyek, $vendorData->vendor->id_vendor]) }}"
                                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                    <i class="fas fa-plus mr-1"></i>Input Pembayaran
                                                </a>
                                                @endif
                                                @if($vendorPembayaran->count() > 0)
                                                <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}?vendor={{ $vendorData->vendor->id_vendor }}"
                                                   class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                    <i class="fas fa-history mr-1"></i>History
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach

                                        @php
                                            $pendingCount    = $proyek->pembayaran->where('status_verifikasi', 'Pending')->count();
                                            $approvedCount   = $proyek->pembayaran->where('status_verifikasi', 'Approved')->count();
                                            $ditolakCount    = $proyek->pembayaran->where('status_verifikasi', 'Ditolak')->count();
                                            $totalPembayaran = $proyek->pembayaran->count();
                                        @endphp
                                        @if($totalPembayaran > 0)
                                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                            <h5 class="text-sm font-medium text-gray-700 mb-2">Statistik Pembayaran:</h5>
                                            <div class="flex space-x-4 text-xs">
                                                <span class="text-gray-600"><i class="fas fa-receipt mr-1"></i>Total: {{ $totalPembayaran }}</span>
                                                @if($pendingCount > 0)<span class="text-yellow-600"><i class="fas fa-hourglass-half mr-1"></i>Pending: {{ $pendingCount }}</span>@endif
                                                @if($approvedCount > 0)<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Approved: {{ $approvedCount }}</span>@endif
                                                @if($ditolakCount > 0)<span class="text-red-600"><i class="fas fa-times-circle mr-1"></i>Ditolak: {{ $ditolakCount }}</span>@endif
                                            </div>
                                        </div>
                                        @endif

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @php $canAccess3 = ($currentUser->role === 'admin_purchasing' || $currentUser->role === 'superadmin') && ($proyek->id_admin_purchasing == $currentUser->id_user || $currentUser->role === 'superadmin'); @endphp
                                            @if(!$proyek->status_lunas && $canAccess3)
                                            <a href="{{ route('purchasing.pembayaran.create', $proyek->id_proyek) }}"
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                <i class="fas fa-plus mr-1"></i>Input Pembayaran
                                            </a>
                                            @endif
                                            @if($totalPembayaran > 0)
                                            <a href="{{ route('purchasing.pembayaran.history', $proyek->id_proyek) }}"
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                <i class="fas fa-history mr-1"></i>History Pembayaran
                                            </a>
                                            @endif
                                            <a href="#" onclick="showProyekDetail({{ json_encode($proyek) }})"
                                               class="inline-flex items-center px-3 py-2 border border-indigo-300 text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                                                <i class="fas fa-eye mr-1"></i>Detail Lengkap
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Summary Footer --}}
                    <div class="mt-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                        <div class="flex justify-between items-center text-sm flex-wrap gap-2">
                            <div class="flex space-x-6 flex-wrap gap-2">
                                <span class="text-gray-700"><i class="fas fa-check-circle text-green-600 mr-1"></i>Lunas: {{ $semuaProyek->where('status_lunas', true)->count() }}</span>
                                <span class="text-gray-700"><i class="fas fa-clock text-yellow-600 mr-1"></i>Belum Lunas: {{ $semuaProyek->where('status_lunas', false)->count() }}</span>
                            </div>
                            <div class="font-medium text-gray-900">Total: {{ $semuaProyek->total() }} proyek</div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-project-diagram text-4xl text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            Tidak ada proyek untuk Tahun {{ $tahunFilter }}
                            @if(request('tab') === 'semua-proyek' && request('search'))
                                yang sesuai pencarian
                            @endif
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Coba pilih tahun lain atau ubah filter.</p>
                    </div>
                    @endif
                </div>

                @if($semuaProyek->hasPages())
                <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-medium">{{ $semuaProyek->firstItem() ?? 0 }} - {{ $semuaProyek->lastItem() ?? 0 }}</span>
                            dari <span class="font-semibold text-gray-800">{{ $semuaProyek->total() }}</span> proyek
                        </div>
                        <div class="flex justify-center">
                            {{-- Pagination membawa semua filter aktif termasuk tab --}}
                            {{ $semuaProyek->appends(array_merge(request()->query(), ['tab' => 'semua-proyek']))->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- =====================================================
             TAB 3: Semua Pembayaran
             ===================================================== --}}
        <div id="tab-semua-pembayaran" class="tab-content" style="display:none;">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Semua Pembayaran</h2>
                        <p class="text-gray-600 mt-1">Daftar lengkap pembayaran (Pending, Approved, Ditolak)</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <form method="GET" class="flex gap-2">
                            <input type="hidden" name="tab" value="semua-pembayaran">
                            @if(request('status_filter'))
                            <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                            @endif
                            <div class="relative">
                                <input type="text" name="search"
                                       value="{{ request('tab') === 'semua-pembayaran' ? request('search') : '' }}"
                                       placeholder="Cari pembayaran..."
                                       class="px-4 py-2 pr-10 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        <form method="GET" class="flex gap-2">
                            <input type="hidden" name="tab" value="semua-pembayaran">
                            @if(request('tab') === 'semua-pembayaran' && request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <select name="status_filter" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                                <option value="all" {{ request('status_filter', 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="Pending" {{ request('status_filter') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Approved" {{ request('status_filter') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Ditolak" {{ request('status_filter') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            @if(request('tab') === 'semua-pembayaran' && (request('search') || (request('status_filter') && request('status_filter') !== 'all')))
                            <a href="{{ route('purchasing.pembayaran') }}?tab=semua-pembayaran"
                               class="px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 whitespace-nowrap">
                                <i class="fas fa-times mr-1"></i>Reset
                            </a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    @if($semuaPembayaran->count() > 0)
                    {{-- Desktop Table --}}
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($semuaPembayaran as $pembayaran)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $pembayaran->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $pembayaran->penawaran->proyek->nama_barang }}</div>
                                    <div class="text-xs text-gray-500">Kode: {{ $pembayaran->penawaran->proyek->kode_proyek }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">{{ $pembayaran->penawaran->proyek->instansi }}</div>
                                    <div class="text-xs text-gray-400">No. {{ $pembayaran->penawaran->no_penawaran }}</div>
                                    <div class="text-xs text-gray-400">{{ $pembayaran->penawaran->proyek->kab_kota }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $pembayaran->vendor->nama_vendor }}</div>
                                    <div class="text-sm text-gray-500">{{ $pembayaran->vendor->jenis_perusahaan }}</div>
                                    <div class="text-xs text-gray-400">{{ $pembayaran->vendor->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($pembayaran->jenis_bayar == 'Lunas') bg-green-100 text-green-800
                                        @elseif($pembayaran->jenis_bayar == 'DP') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $pembayaran->jenis_bayar }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($pembayaran->nominal_bayar, 2, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($pembayaran->status_verifikasi == 'Pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-hourglass-half mr-1"></i>Pending</span>
                                    @elseif($pembayaran->status_verifikasi == 'Approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Approved</span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>
                                    @endif
                                    <div class="text-xs text-gray-500 mt-1">{{ $pembayaran->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('purchasing.pembayaran.show', $pembayaran->id_pembayaran) }}"
                                           class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>
                                        @php $buktiBayarArr = array_map(fn($f) => asset('storage/' . $f), $pembayaran->bukti_bayar_array); @endphp
                                        @if(count($buktiBayarArr) > 0)
                                        <button type="button"
                                           onclick="openBuktiModal({{ json_encode(array_values($buktiBayarArr)) }}, '{{ addslashes($pembayaran->penawaran->proyek->nama_barang) }}')"
                                           class="inline-flex items-center px-2 py-1 border border-blue-300 text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100">
                                            <i class="fas fa-images mr-1"></i>Bukti
                                            @if(count($buktiBayarArr) > 1)<span class="ml-1 bg-blue-200 text-blue-800 text-xs rounded-full px-1.5 py-0.5 font-bold">{{ count($buktiBayarArr) }}</span>@endif
                                        </button>
                                        @endif
                                        @if($pembayaran->status_verifikasi == 'Pending')
                                            @php
                                                $canAccessPembayaran = $currentUser->role === 'admin_purchasing' && $pembayaran->penawaran->proyek->id_admin_purchasing == $currentUser->id_user;
                                                $isSuperAdmin = $currentUser->role === 'superadmin';
                                            @endphp
                                            @if($canAccessPembayaran || $isSuperAdmin)
                                            <a href="{{ route('purchasing.pembayaran.edit', $pembayaran->id_pembayaran) }}"
                                               class="inline-flex items-center px-2 py-1 border border-yellow-300 text-xs leading-4 font-medium rounded text-yellow-700 bg-yellow-50 hover:bg-yellow-100">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form action="{{ route('purchasing.pembayaran.destroy', $pembayaran->id_pembayaran) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus pembayaran ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2 py-1 border border-red-300 text-xs leading-4 font-medium rounded text-red-700 bg-red-50 hover:bg-red-100">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                            @else
                                            <span class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-400 bg-gray-100 cursor-not-allowed">
                                                <i class="fas fa-lock mr-1"></i>Terkunci
                                            </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Mobile Card Layout --}}
                    <div class="md:hidden space-y-4">
                        @foreach($semuaPembayaran as $pembayaran)
                        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $pembayaran->penawaran->proyek->nama_barang }}</div>
                                    <div class="text-xs text-gray-500">{{ $pembayaran->penawaran->proyek->instansi }}</div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($pembayaran->jenis_bayar == 'Lunas') bg-green-100 text-green-800
                                    @elseif($pembayaran->jenis_bayar == 'DP') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $pembayaran->jenis_bayar }}
                                </span>
                            </div>
                            <div class="mb-2 text-xs text-gray-500">
                                <div>Tanggal: {{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</div>
                                <div>Vendor: {{ $pembayaran->vendor->nama_vendor }}</div>
                            </div>
                            <div class="text-sm font-medium text-gray-900 mb-2">Rp {{ number_format($pembayaran->nominal_bayar, 2, ',', '.') }}</div>
                            <div class="mb-2">
                                @if($pembayaran->status_verifikasi == 'Pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-hourglass-half mr-1"></i>Pending</span>
                                @elseif($pembayaran->status_verifikasi == 'Approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Approved</span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <a href="{{ route('purchasing.pembayaran.show', $pembayaran->id_pembayaran) }}"
                                   class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                @php $buktiBayarArrMobile = array_map(fn($f) => asset('storage/' . $f), $pembayaran->bukti_bayar_array); @endphp
                                @if(count($buktiBayarArrMobile) > 0)
                                <button type="button"
                                   onclick="openBuktiModal({{ json_encode(array_values($buktiBayarArrMobile)) }}, '{{ addslashes($pembayaran->penawaran->proyek->nama_barang) }}')"
                                   class="inline-flex items-center px-2 py-1 border border-blue-300 text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100">
                                    <i class="fas fa-images mr-1"></i>Bukti
                                </button>
                                @endif
                                @if($pembayaran->status_verifikasi == 'Pending')
                                    @php $canAccessMobile = $currentUser->role === 'admin_purchasing' && $pembayaran->penawaran->proyek->id_admin_purchasing == $currentUser->id_user; @endphp
                                    @if($canAccessMobile || $currentUser->role === 'superadmin')
                                    <a href="{{ route('purchasing.pembayaran.edit', $pembayaran->id_pembayaran) }}"
                                       class="inline-flex items-center px-2 py-1 border border-yellow-300 text-xs leading-4 font-medium rounded text-yellow-700 bg-yellow-50 hover:bg-yellow-100">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('purchasing.pembayaran.destroy', $pembayaran->id_pembayaran) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 border border-red-300 text-xs leading-4 font-medium rounded text-red-700 bg-red-50 hover:bg-red-100">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex space-x-6">
                                <span class="text-gray-700"><i class="fas fa-hourglass-half text-yellow-600 mr-1"></i>Pending: {{ $semuaPembayaran->where('status_verifikasi', 'Pending')->count() }}</span>
                                <span class="text-gray-700"><i class="fas fa-check-circle text-green-600 mr-1"></i>Approved: {{ $semuaPembayaran->where('status_verifikasi', 'Approved')->count() }}</span>
                                <span class="text-gray-700"><i class="fas fa-times-circle text-red-600 mr-1"></i>Ditolak: {{ $semuaPembayaran->where('status_verifikasi', 'Ditolak')->count() }}</span>
                            </div>
                            <div class="font-medium text-gray-900">Total: {{ $semuaPembayaran->total() }} pembayaran</div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-receipt text-4xl text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pembayaran</h3>
                        <p class="mt-1 text-sm text-gray-500">Pembayaran akan muncul setelah admin purchasing menginput data.</p>
                    </div>
                    @endif
                </div>

                @if($semuaPembayaran->hasPages())
                <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-medium">{{ $semuaPembayaran->firstItem() ?? 0 }} - {{ $semuaPembayaran->lastItem() ?? 0 }}</span>
                            dari <span class="font-semibold text-gray-800">{{ $semuaPembayaran->total() }}</span> pembayaran
                        </div>
                        <div class="flex justify-center">
                            {{ $semuaPembayaran->appends(array_merge(request()->query(), ['tab' => 'semua-pembayaran']))->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- =====================================================
             TAB 4: Pembuatan Surat PO
             ===================================================== --}}
        <div id="tab-pembuatan-surat-po" class="tab-content" style="display:none;">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Pembuatan Surat PO</h2>
                        <p class="text-gray-600 mt-1">Pilih proyek, lalu pilih vendor untuk membuat Surat Purchase Order (PO).</p>
                    </div>
                    <div class="w-full sm:w-auto">
                        <form method="GET" action="{{ route('purchasing.pembayaran') }}" class="flex gap-2">
                            <input type="hidden" name="tab" value="pembuatan-surat-po">
                            <input type="text" name="po_search" value="{{ $poSearch ?? '' }}"
                                   placeholder="Cari kode proyek / instansi..."
                                   class="w-full sm:w-80 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i>Cari
                            </button>
                            @if(!empty($poSearch))
                            <a href="{{ route('purchasing.pembayaran', ['tab' => 'pembuatan-surat-po']) }}"
                               class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    @if(isset($proyekPo) && $proyekPo->count() > 0)
                    <div class="space-y-4">
                        @foreach($proyekPo as $proyek)
                        @php $vendorsForPo = $proyek->vendors_data ?? collect(); @endphp
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="p-4 cursor-pointer" onclick="toggleVendorDetails('po-{{ $proyek->id_proyek }}')">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <div class="w-3 h-3 rounded-full
                                                @if($proyek->status == 'Pembayaran') bg-blue-500
                                                @elseif($proyek->status == 'Pengiriman') bg-purple-500
                                                @elseif($proyek->status == 'Selesai') bg-green-500
                                                @elseif($proyek->status == 'Gagal') bg-red-500
                                                @else bg-gray-500 @endif">
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $proyek->nama_barang }}</h3>
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $proyek->kode_proyek }}</span>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div class="space-y-1">
                                                <p class="text-gray-600"><i class="fas fa-building text-gray-400 mr-2"></i>{{ $proyek->instansi }} - {{ $proyek->kab_kota }}</p>
                                                <p class="text-gray-600"><i class="fas fa-file-contract text-gray-400 mr-2"></i>No. Penawaran: {{ $proyek->penawaranAktif->no_penawaran ?? '-' }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-gray-600"><span class="font-medium">Jumlah Vendor:</span> <span class="font-semibold text-blue-700">{{ $vendorsForPo->count() }}</span></p>
                                                <p class="text-gray-600"><span class="font-medium">Dibuat:</span> {{ optional($proyek->created_at)->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="chevron-po-{{ $proyek->id_proyek }}"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="vendor-details-po-{{ $proyek->id_proyek }}" class="hidden border-t border-gray-100 bg-gray-50">
                                <div class="p-4">
                                    <h4 class="font-medium text-gray-900 mb-3">Pilih Vendor</h4>
                                    @if($vendorsForPo->count() === 0)
                                    <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-200 text-sm text-yellow-800">Vendor belum tersedia.</div>
                                    @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($vendorsForPo as $vendorData)
                                        <div class="p-3 border border-gray-200 rounded-lg bg-white flex items-center justify-between gap-3">
                                            <div class="font-semibold text-gray-900">{{ $vendorData->vendor->nama_vendor ?? '-' }}</div>
                                            <a href="{{ route('purchasing.pembayaran.pembuatan-surat-po', ['id_proyek' => $proyek->id_proyek, 'id_vendor' => $vendorData->vendor->id_vendor]) }}"
                                               class="inline-flex items-center px-3 py-2 rounded-lg bg-red-700 text-white text-sm font-medium hover:bg-red-800">
                                                <i class="fas fa-file-alt mr-2"></i>Buat Surat PO
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($proyekPo->hasPages())
                    <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-medium">{{ $proyekPo->firstItem() ?? 0 }} - {{ $proyekPo->lastItem() ?? 0 }}</span>
                                dari <span class="font-semibold text-gray-800">{{ $proyekPo->total() }}</span> proyek
                            </div>
                            <div class="flex justify-center">
                                {{ $proyekPo->appends(array_merge(request()->query(), ['tab' => 'pembuatan-surat-po']))->links() }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-signature text-4xl text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada proyek</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada proyek yang bisa dibuatkan PO.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>{{-- End main card --}}

    {{-- ===================================================
         Modal Detail Proyek
         =================================================== --}}
    <div id="proyekDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between pb-3 border-b">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Detail Proyek</h3>
                    <button onclick="closeProyekDetail()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="mt-4" id="modalContent"></div>
                <div class="flex justify-end pt-4 border-t mt-4">
                    <button onclick="closeProyekDetail()" class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Bukti Pembayaran --}}
    <div id="buktiModal" class="fixed inset-0 bg-black/20 bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-xs">
        <div class="relative top-10 mx-auto p-0 w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-2xl rounded-2xl bg-white">
            <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center"><i class="fas fa-images text-white text-sm"></i></div>
                    <div>
                        <h3 class="text-base font-semibold text-white" id="buktiModalTitle">Bukti Pembayaran</h3>
                        <p class="text-blue-200 text-xs" id="buktiModalSubtitle"></p>
                    </div>
                </div>
                <button onclick="closeBuktiModal()" class="text-white/70 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6" id="buktiModalContent"></div>
            <div class="flex justify-end px-6 py-4 border-t bg-gray-50 rounded-b-2xl">
                <button onclick="closeBuktiModal()" class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Lightbox --}}
    <div id="imageLightbox" class="fixed inset-0 bg-black bg-opacity-90 hidden z-[60] flex items-center justify-center" onclick="closeLightbox()">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10"><i class="fas fa-times"></i></button>
        <button onclick="prevLightboxImage(event)" class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-3xl hover:text-gray-300 z-10 bg-black/30 rounded-full w-12 h-12 flex items-center justify-center"><i class="fas fa-chevron-left"></i></button>
        <div class="max-w-5xl max-h-screen p-4 flex items-center justify-center" onclick="event.stopPropagation()">
            <img id="lightboxImage" src="" alt="Bukti Pembayaran" class="max-h-[85vh] max-w-full object-contain rounded-lg shadow-2xl">
        </div>
        <button onclick="nextLightboxImage(event)" class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-3xl hover:text-gray-300 z-10 bg-black/30 rounded-full w-12 h-12 flex items-center justify-center"><i class="fas fa-chevron-right"></i></button>
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-sm" id="lightboxCounter"></div>
    </div>
</div>
@endsection