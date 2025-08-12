<!-- Modal Edit Wilayah -->
<div id="modalEditWilayah" class="fixed inset-0 backdrop-blur-xs bg-black/30 modal-backdrop hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-lg md:max-w-2xl lg:max-w-4xl max-h-screen overflow-hidden my-2 sm:my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-4 sm:p-6 flex items-center justify-between flex-shrink-0 modal-header">
            <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-base sm:text-xl font-bold">Edit Data Wilayah</h3>
                    <p class="text-red-100 text-xs sm:text-sm">Ubah informasi kontak pejabat instansi</p>
                </div>
            </div>
            <button onclick="closeModal('modalEditWilayah')" class="text-white hover:bg-white hover:text-red-800 p-1 sm:p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-lg sm:text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-3 sm:p-6 overflow-y-auto flex-1 modal-form" style="max-height: calc(100vh - 160px);">
            <form id="formEditWilayah" class="space-y-4 sm:space-y-6">
                <!-- Hidden ID -->
                <input type="hidden" id="editId" name="id">
                
                <!-- Informasi Wilayah & Instansi (Read Only) -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-3 sm:p-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2 text-sm sm:text-base"></i>
                        Informasi Wilayah & Instansi
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Kabupaten/Kota</label>
                            <input type="text" id="editWilayah" name="wilayah" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 text-sm sm:text-base" readonly>
                            <small class="text-gray-500 text-xs mt-1">Data dari penawaran yang sudah diinput</small>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nama Instansi</label>
                            <input type="text" id="editInstansi" name="instansi" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 text-sm sm:text-base" readonly>
                            <small class="text-gray-500 text-xs mt-1">Data dari penawaran yang sudah diinput</small>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Admin Marketing</label>
                            <input type="text" id="editAdminMarketing" name="admin_marketing" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 text-sm sm:text-base" readonly>
                            <small class="text-gray-500 text-xs mt-1">Admin yang menangani wilayah ini</small>
                        </div>
                    </div>
                </div>

                <!-- Informasi Kontak Pejabat (Editable) -->
                <div class="bg-blue-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                        Informasi Kontak Pejabat
                        <span class="ml-2 text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded-full">Dapat Diedit</span>
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pejabat <span class="text-red-500">*</span></label>
                            <input type="text" id="editNamaPejabat" name="nama_pejabat" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama lengkap pejabat" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" id="editJabatan" name="jabatan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan jabatan" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon <span class="text-red-500">*</span></label>
                            <input type="tel" id="editNoTelp" name="no_telp" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: 021-1234567" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email (Opsional)</label>
                            <input type="email" id="editEmail" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: pejabat@instansi.go.id">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Kantor (Opsional)</label>
                            <textarea id="editAlamat" name="alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan alamat lengkap kantor"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-600 mr-3 mt-1"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-yellow-800 mb-1">Catatan Penting:</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• Data wilayah dan instansi tidak dapat diubah karena berasal dari data penawaran</li>
                                <li>• Hanya informasi kontak pejabat yang dapat diedit</li>
                                <li>• Pastikan nomor telepon dapat dihubungi untuk keperluan koordinasi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-3 py-3 sm:px-6 sm:py-4 flex flex-col sm:flex-row items-center justify-end space-y-2 sm:space-y-0 sm:space-x-3 border-t border-gray-200">
            <button type="button" onclick="closeModal('modalEditWilayah')" class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-sm sm:text-base min-h-[44px] sm:min-h-[40px]">
                <i class="fas fa-times mr-1 sm:mr-2"></i>Batal
            </button>
            <button type="submit" form="formEditWilayah" class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm sm:text-base min-h-[44px] sm:min-h-[40px]">
                <i class="fas fa-save mr-1 sm:mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </div>
</div>
