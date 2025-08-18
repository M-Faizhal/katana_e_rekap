<!-- Modal Detail Potensi -->
<div id="modalDetailPotensi" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Detail Potensi Proyek</h3>
                    <p class="text-red-100 text-sm">Informasi lengkap assignment proyek ke vendor</p>
                </div>
            </div>
            <button onclick="closeModal('modalDetailPotensi')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <!-- Status Badge -->
            <div class="mb-6">
                <span id="detailPotensiStatusBadge" class="inline-flex px-4 py-2 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                    Pending
                </span>
            </div>

            <!-- Informasi Proyek -->
            <div class="bg-blue-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-project-diagram text-blue-600 mr-2"></i>
                    Informasi Proyek
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Proyek</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiKodeProyek">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Proyek</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiNamaProyek">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instansi</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiInstansi">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiKabupatenKota">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiJenisPengadaan">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Proyek</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-green-600">
                            <span id="detailPotensiNilaiProyek">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiDeadline">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Vendor -->
            <div class="bg-green-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-building text-green-600 mr-2"></i>
                    Vendor yang Ditugaskan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID Vendor</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiVendorId">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Vendor</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiVendorNama">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Vendor</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiVendorJenis">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Vendor</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiVendorStatus">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Assignment -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clipboard-list text-gray-600 mr-2"></i>
                    Informasi Assignment
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Potensi</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiStatus">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Assign</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPotensiTanggalAssign">-</span>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800 min-h-[100px]">
                            <span id="detailPotensiCatatan">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline/History -->
            <div class="bg-yellow-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history text-yellow-600 mr-2"></i>
                    Riwayat Perubahan
                </h4>
                <div id="detailPotensiTimeline" class="space-y-4">
                    <!-- Timeline items will be loaded here -->
                </div>
            </div>

            <!-- Progress Summary -->
            <div class="bg-purple-50 rounded-xl p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                    Ringkasan Progress
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">1</div>
                        <div class="text-sm text-gray-600">Proyek Assigned</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">
                            <span id="detailPotensiDaysActive">0</span>
                        </div>
                        <div class="text-sm text-gray-600">Hari Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            <span id="detailPotensiProgressPercentage">0</span>%
                        </div>
                        <div class="text-sm text-gray-600">Progress</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200 flex-shrink-0">
            <div class="flex space-x-3">
                <button type="button" onclick="printPotensiDetail()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button type="button" onclick="exportPotensiPDF()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
            </div>
            <button type="button" onclick="closeModal('modalDetailPotensi')" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    .fixed, button {
        display: none !important;
    }

    .max-h-screen {
        max-height: none !important;
    }

    .overflow-y-auto {
        overflow: visible !important;
    }

    .bg-red-800 {
        background-color: #dc2626 !important;
        -webkit-print-color-adjust: exact;
    }
}
</style>
