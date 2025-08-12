<!-- Modal Edit Produk -->
<div id="modalEditProduk" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Edit Produk</h3>
                    <p class="text-red-100 text-sm">Ubah informasi produk</p>
                </div>
            </div>
            <button onclick="closeModal('modalEditProduk')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formEditProduk" class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Produk
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No Produk</label>
                            <input type="text" name="no_produk_edit" id="noProdukEdit" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_barang_edit" id="namaBarangEdit" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama barang" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi <span class="text-red-500">*</span></label>
                            <textarea name="spesifikasi_edit" id="spesifikasiEdit" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan spesifikasi lengkap produk" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Barang <span class="text-red-500">*</span></label>
                            <select name="jenis_barang_edit" id="jenisBarangEdit" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Pilih jenis barang</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Mesin">Mesin</option>
                                <option value="Meubel">Meubel</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai TKDN (%)</label>
                            <input type="number" name="nilai_tkdn_edit" id="nilaiTkdnEdit" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="0-100">
                        </div>
                    </div>
                </div>

                <!-- Upload Gambar -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-image text-red-600 mr-2"></i>
                        Gambar Produk
                    </h4>
                    <div class="space-y-4">
                        <!-- Current Image -->
                        <div id="currentImageEdit" class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                            <img id="currentImg" src="https://via.placeholder.com/150" alt="Current Product" class="max-w-32 max-h-32 mx-auto rounded-lg border">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Baru (Opsional)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors duration-200">
                                <input type="file" name="gambar_edit" id="gambarInputEdit" class="hidden" accept="image/*" onchange="previewImageEdit(this)">
                                <div id="uploadAreaEdit" onclick="document.getElementById('gambarInputEdit').click()" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 mb-2">Klik untuk upload gambar baru</p>
                                    <p class="text-sm text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                                </div>
                                <div id="imagePreviewEdit" class="hidden">
                                    <img id="previewImgEdit" src="" alt="Preview" class="max-w-32 max-h-32 mx-auto rounded-lg">
                                    <p class="text-sm text-gray-600 mt-2">Gambar baru berhasil dipilih</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 flex-shrink-0">
            <button type="button" onclick="closeModal('modalEditProduk')" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                Batal
            </button>
            <button type="submit" form="formEditProduk" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fas fa-save"></i>
                <span>Update Produk</span>
            </button>
        </div>
    </div>
</div>

<script>
    function previewImageEdit(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadAreaEdit').classList.add('hidden');
                document.getElementById('imagePreviewEdit').classList.remove('hidden');
                document.getElementById('previewImgEdit').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Function to populate edit form (would be called when edit button is clicked)
    function populateEditForm(productData) {
        document.getElementById('noProdukEdit').value = productData.no_produk || '';
        document.getElementById('namaBarangEdit').value = productData.nama_barang || '';
        document.getElementById('spesifikasiEdit').value = productData.spesifikasi || '';
        document.getElementById('jenisBarangEdit').value = productData.jenis_barang || '';
        document.getElementById('nilaiTkdnEdit').value = productData.nilai_tkdn || '';
        
        if (productData.gambar) {
            document.getElementById('currentImg').src = productData.gambar;
        }
        
        // Reset new image upload area
        document.getElementById('uploadAreaEdit').classList.remove('hidden');
        document.getElementById('imagePreviewEdit').classList.add('hidden');
    }
</script>
