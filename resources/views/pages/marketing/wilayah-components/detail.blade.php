<!-- Modal Detail Wilayah -->
<div id="modalDetailWilayah" class="fixed inset-0 backdrop-blur-xs bg-black/30 modal-backdrop hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-eye text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Detail Wilayah</h3>
                    <p class="text-sm text-gray-600">Informasi lengkap wilayah dan kontak pejabat</p>
                </div>
            </div>
            <button onclick="closeModal('modalDetailWilayah')" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Wilayah & Instansi -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Wilayah</p>
                        <p id="detailWilayah" class="font-semibold text-gray-800 text-lg">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                        <p id="detailAdminMarketing" class="font-medium text-gray-800">-</p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-500 mb-1">Nama Instansi</p>
                    <p id="detailInstansi" class="font-medium text-gray-800">-</p>
                </div>
            </div>

            <!-- Kontak Pejabat -->
            <div class="border border-gray-200 rounded-xl p-4 mb-6">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                    Informasi Pejabat
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Pejabat</p>
                        <p id="detailNamaPejabat" class="font-medium text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Jabatan</p>
                        <p id="detailJabatan" class="font-medium text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">No. Telepon</p>
                        <p id="detailNoTelp" class="font-medium text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p id="detailEmail" class="font-medium text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                        <p id="detailAdminMarketing" class="font-medium text-gray-800">-</p>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 font-medium mb-1">Status Kontak</p>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Aktif
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Terakhir diupdate</p>
                        <p id="detailUpdatedAt" class="text-sm font-medium text-gray-700">-</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal('modalDetailWilayah')"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Tutup
                </button>
                <button type="button" onclick="closeModal('modalDetailWilayah'); editWilayah(document.querySelector('[data-detail-id]')?.getAttribute('data-detail-id') || 1)"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Data
                </button>
            </div>
        </div>
    </div>
</div>
