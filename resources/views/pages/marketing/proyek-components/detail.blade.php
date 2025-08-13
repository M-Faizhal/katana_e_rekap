<!-- Modal Detail Proyek -->
<div id="modalDetailProyek" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Detail Proyek</h3>
                    <p class="text-red-100 text-sm">Informasi lengkap proyek</p>
                </div>
            </div>
            <button onclick="closeModal('modalDetailProyek')" class="text-white hover:bg-white hover:text-red-800 p-2">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <!-- Status Badge -->
            <div class="mb-6">
                <span id="detailStatusBadge" class="inline-flex px-4 py-2 text-sm font-medium rounded-full">
                    <!-- Status will be set dynamically -->
                </span>
            </div>

            <!-- Informasi Dasar -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-red-600 mr-2"></i>
                    Informasi Dasar
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">ID Proyek</label>
                        <p id="detailIdProyek" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Tanggal</label>
                        <p id="detailTanggal" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Deadline</label>
                        <p id="detailDeadline" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Kabupaten/Kota</label>
                        <p id="detailKabupatenKota" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Nama Instansi</label>
                        <p id="detailNamaInstansi" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Jenis Pengadaan</label>
                        <p id="detailJenisPengadaan" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Admin Marketing</label>
                        <p id="detailAdminMarketing" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Admin Purchasing</label>
                        <p id="detailAdminPurchasing" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Potensi</label>
                        <p id="detailPotensi" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Tahun Potensi</label>
                        <p id="detailTahunPotensi" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-boxes text-red-600 mr-2"></i>
                    Daftar Barang
                </h4>
                <div id="detailDaftarBarang" class="space-y-4">
                    <!-- Items will be populated here -->
                </div>
                
                <!-- Total Keseluruhan -->
                <div class="mt-6 bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-gray-800">Total Keseluruhan:</h5>
                        <div class="text-2xl font-bold text-red-600" id="detailTotalKeseluruhan">Rp 0</div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div id="detailCatatanSection" class="bg-gray-50 rounded-xl p-6 mb-6" style="display: none;">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-red-600 mr-2"></i>
                    Catatan
                </h4>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <p id="detailCatatan" class="text-gray-700 leading-relaxed">-</p>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-file-alt text-red-600 mr-2"></i>
                    Dokumen Proyek
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-2">
                            <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                            <h5 class="font-medium text-gray-800">Surat Penawaran</h5>
                        </div>
                        <div id="detailSuratPenawaran">
                            <p class="text-sm text-gray-500 mb-2">Status: <span class="text-green-600 font-medium">Tersedia</span></p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium">
                                <i class="fas fa-download mr-1"></i>Download
                            </button>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-2">
                            <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                            <h5 class="font-medium text-gray-800">Surat Pesanan</h5>
                        </div>
                        <div id="detailSuratPesanan">
                            <p class="text-sm text-gray-500 mb-2">Status: <span class="text-green-600 font-medium">Tersedia</span></p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium">
                                <i class="fas fa-download mr-1"></i>Download
                            </button>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-2">
                            <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                            <h5 class="font-medium text-gray-800">Surat Jalan</h5>
                        </div>
                        <div id="detailSuratJalan">
                            <p class="text-sm text-gray-500 mb-2">Status: <span class="text-green-600 font-medium">Tersedia</span></p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium">
                                <i class="fas fa-download mr-1"></i>Download
                            </button>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-2">
                            <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                            <h5 class="font-medium text-gray-800">Tanda Terima</h5>
                        </div>
                        <div id="detailTandaTerima">
                            <p class="text-sm text-gray-500 mb-2">Status: <span class="text-green-600 font-medium">Tersedia</span></p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium">
                                <i class="fas fa-download mr-1"></i>Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Status -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history text-red-600 mr-2"></i>
                    Timeline Status
                </h4>
                <div id="detailTimeline" class="space-y-4">
                    <!-- Timeline items will be populated here -->
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200 flex-shrink-0">
            
            <button type="button" onclick="closeModal('modalDetailProyek')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function printDetail() {
    window.print();
}

function exportPDF() {
    alert('Exporting to PDF...');
}

function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}
</script>

<style>
@media print {
    .fixed, button {
        display: none !important;
    }
    
    .max-h-\[90vh\] {
        max-height: none !important;
    }
    
    .overflow-y-auto {
        overflow: visible !important;
    }
}
</style>
