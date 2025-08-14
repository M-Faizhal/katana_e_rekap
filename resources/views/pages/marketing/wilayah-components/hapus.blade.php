<!-- Modal Hapus Wilayah -->
<div id="modalHapusWilayah" class="fixed inset-0 backdrop-blur-xs bg-black/30 modal-backdrop hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-trash text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h3>
                    <p class="text-sm text-gray-600">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <button onclick="closeModal('modalHapusWilayah')" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-red-800 mb-2">Apakah Anda yakin ingin menghapus data ini?</h4>
                        <div class="text-sm text-red-700">
                            <p><span class="font-medium">Wilayah:</span> <span id="hapusWilayahName">-</span></p>
                            <p><span class="font-medium">Instansi:</span> <span id="hapusInstansiName">-</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-yellow-800 mb-1">Peringatan</h4>
                        <ul class="text-xs text-yellow-700 list-disc list-inside space-y-1">
                            <li>Data wilayah akan dihapus secara permanen</li>
                            <li>Kontak pejabat dan informasi terkait akan hilang</li>
                            <li>Tindakan ini tidak dapat dibatalkan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="formHapusWilayah">
                <input type="hidden" id="hapusId" name="id">

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('modalHapusWilayah')"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i>Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
