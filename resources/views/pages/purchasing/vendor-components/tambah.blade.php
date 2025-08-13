<!-- Modal Tambah Vendor -->
<div id="modalTambahVendor" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Tambah Vendor Baru</h3>
                    <p class="text-red-100 text-sm">Tambahkan vendor baru ke dalam sistem</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahVendor')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formTambahVendor" class="space-y-6">
                <!-- Informasi Vendor -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-building text-red-600 mr-2"></i>
                        Informasi Vendor
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Vendor <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_vendor" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama vendor" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Vendor <span class="text-red-500">*</span></label>
                            <select name="jenis_vendor" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Pilih jenis vendor</option>
                                <option value="Perusahaan">Perusahaan</option>
                                <option value="Perorangan">Perorangan</option>
                                <option value="Koperasi">Koperasi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Vendor <span class="text-red-500">*</span></label>
                            <select name="status_vendor" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Pilih status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto/Logo Vendor</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-red-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <div id="imagePreviewContainer" class="mb-4 hidden">
                                        <img id="imagePreview" src="" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg shadow-md">
                                    </div>
                                    <div id="uploadPrompt">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="vendorImage" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                                <span>Upload foto/logo</span>
                                                <input id="vendorImage" name="vendor_image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this, 'imagePreview', 'imagePreviewContainer', 'uploadPrompt')">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 5MB</p>
                                    </div>
                                    <div class="mt-2 hidden" id="removeImageBtn">
                                        <button type="button" onclick="removeImage('imagePreview', 'imagePreviewContainer', 'uploadPrompt', 'vendorImage')" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            <i class="fas fa-trash mr-1"></i>Hapus Gambar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Kontak -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-address-book text-red-600 mr-2"></i>
                        Informasi Kontak
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="vendor@email.com" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No HP <span class="text-red-500">*</span></label>
                            <input type="tel" name="no_hp" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="0812-3456-7890" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                            <textarea name="alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan alamat lengkap vendor" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Manajemen Produk -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-box text-red-600 mr-2"></i>
                        Manajemen Produk Vendor
                    </h4>

                    <!-- Produk yang sudah ditambahkan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Produk yang sudah ditambahkan</label>
                        <div id="vendorProductList" class="space-y-2 max-h-32 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-white">
                            <p class="text-gray-500 text-sm">Belum ada produk yang ditambahkan</p>
                        </div>
                    </div>

                    <!-- Form tambah produk baru -->
                    <div class="border-t pt-4">
                        <h5 class="text-md font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                            Tambah Produk Baru
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                                <input type="text" id="newProductName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama produk">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Produk</label>
                                <select id="newProductCategory" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">Pilih kategori</option>
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="Mesin">Mesin</option>
                                    <option value="Meubel">Meubel</option>
                                    <option value="Alat Tulis">Alat Tulis</option>
                                    <option value="Konsumsi">Konsumsi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                                <input type="number" id="newProductPrice" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="0" min="0">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi Produk</label>
                                <input type="text" id="newProductSpec" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan spesifikasi produk">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Produk</label>
                                <textarea id="newProductDescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan deskripsi produk"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-red-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <div id="productImagePreviewContainer" class="mb-4 hidden">
                                            <img id="productImagePreview" src="" alt="Preview" class="mx-auto h-24 w-24 object-cover rounded-lg shadow-md">
                                        </div>
                                        <div id="productUploadPrompt">
                                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="newProductImage" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                                    <span>Upload gambar</span>
                                                    <input id="newProductImage" type="file" class="sr-only" accept="image/*" onchange="previewImage(this, 'productImagePreview', 'productImagePreviewContainer', 'productUploadPrompt')">
                                                </label>
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 5MB</p>
                                        </div>
                                        <div class="mt-2 hidden" id="removeProductImageBtn">
                                            <button type="button" onclick="removeImage('productImagePreview', 'productImagePreviewContainer', 'productUploadPrompt', 'newProductImage')" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                <i class="fas fa-trash mr-1"></i>Hapus Gambar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <button type="button" onclick="addProductToVendor()" class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Tambah Produk ke Vendor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalTambahVendor')" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="button" onclick="submitTambahVendor()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Vendor
            </button>
        </div>
    </div>
</div>
