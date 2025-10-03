<!-- Modal Tambah Wilayah -->
<div id="modalTambahWilayah" class="fixed inset-0 backdrop-blur-xs bg-black/30 modal-backdrop hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-lg md:max-w-2xl lg:max-w-4xl max-h-screen overflow-hidden my-2 sm:my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-4 sm:p-6 flex items-center justify-between flex-shrink-0 modal-header">
            <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-base sm:text-xl font-bold">Tambah Data Wilayah</h3>
                    <p class="text-red-100 text-xs sm:text-sm">Tambah wilayah dan informasi kontak pejabat baru</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahWilayah')" class="text-white hover:bg-white hover:text-red-800 p-1 sm:p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-lg sm:text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-3 sm:p-6 overflow-y-auto flex-1 modal-form" style="max-height: calc(100vh - 160px);">
            <form id="formTambahWilayah" class="space-y-4 sm:space-y-6">

                <!-- Informasi Wilayah & Instansi -->
                <div class="bg-red-50 rounded-lg sm:rounded-xl p-3 sm:p-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2 text-sm sm:text-base"></i>
                        Informasi Wilayah & Instansi
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nama Wilayah <span class="text-red-500">*</span></label>
                            <input type="text" id="tambahWilayah" name="wilayah" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Contoh: Jakarta Pusat, Bogor" required>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" id="tambahProvinsi" name="provinsi" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Contoh: DKI Jakarta" required>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Kode Wilayah <span class="text-red-500">*</span></label>
                            <input type="text" id="tambahKodeWilayah" name="kode_wilayah" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Contoh: JKT-01" required maxlength="10">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nama Instansi <span class="text-red-500">*</span></label>
                            <input type="text" id="tambahInstansi" name="instansi" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Contoh: Dinas Pendidikan" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Deskripsi (Opsional)</label>
                            <textarea id="tambahDeskripsi" name="deskripsi" rows="2" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Deskripsi singkat tentang wilayah"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pejabat -->
                <div class="bg-blue-50 rounded-lg sm:rounded-xl p-3 sm:p-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-user-tie text-blue-600 mr-2 text-sm sm:text-base"></i>
                        Informasi Pejabat
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nama Pejabat <span class="text-red-500">*</span></label>
                            <input type="text" id="tambahNamaPejabat" name="nama_pejabat" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Contoh: Dr. Budi Santoso" required>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" id="tambahJabatan" name="jabatan" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Contoh: Kepala Dinas" required>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">No. Telepon</label>
                            <input type="text" id="tambahNoTelp" name="no_telp" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Contoh: 021-123-4567">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Email</label>
                            <input type="email" id="tambahEmail" name="email" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Contoh: pejabat@instansi.go.id">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Alamat Kantor</label>
                            <textarea id="tambahAlamat" name="alamat" rows="2" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base" placeholder="Alamat lengkap kantor instansi"></textarea>
                        </div>
                    </div>
                </div>

                <!-- PIC / Admin Marketing -->
                <div class="bg-yellow-50 rounded-lg sm:rounded-xl p-3 sm:p-6 border-2 border-yellow-200">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-user-shield text-yellow-600 mr-2 text-sm sm:text-base"></i>
                        PIC / Admin Marketing <span class="text-red-500">*</span>
                    </h4>
                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nama PIC <span class="text-red-500">*</span></label>
                            <input type="text" id="tambahAdminMarketingText" name="admin_marketing_text" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm sm:text-base" placeholder="Contoh: Sari Dewi, Ahmad Fauzi" required>
                            <small class="text-yellow-700 text-xs mt-1 block">
                                <i class="fas fa-info-circle mr-1"></i>
                                PIC adalah penanggung jawab wilayah ini yang akan ditampilkan di filter dan badge wilayah
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Panduan -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-blue-600 mr-3 mt-1"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 mb-1">Tips Pengisian:</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <strong>PIC/Admin Marketing</strong> akan ditampilkan sebagai badge di wilayah</li>
                                <li>• Kode wilayah harus unik untuk setiap kombinasi wilayah-instansi</li>
                                <li>• Anda dapat menambah instansi lain di wilayah yang sama nanti</li>
                                <li>• Field bertanda (*) wajib diisi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-3 py-3 sm:px-6 sm:py-4 flex flex-col sm:flex-row items-center justify-end space-y-2 sm:space-y-0 sm:space-x-3 border-t border-gray-200">
            <button type="button" onclick="closeModal('modalTambahWilayah')" class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-sm sm:text-base min-h-[44px] sm:min-h-[40px]">
                <i class="fas fa-times mr-1 sm:mr-2"></i>Batal
            </button>
            <button type="submit" form="formTambahWilayah" class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm sm:text-base min-h-[44px] sm:min-h-[40px]">
                <i class="fas fa-save mr-1 sm:mr-2"></i>Simpan Data Wilayah
            </button>
        </div>
    </div>
</div>
