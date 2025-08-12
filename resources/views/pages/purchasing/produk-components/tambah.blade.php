<!-- Modal Tambah Produk -->
<div id="modalTambahProduk" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Tambah Produk Baru</h3>
                    <p class="text-red-100 text-sm">Tambahkan produk baru ke dalam sistem</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahProduk')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formTambahProduk" class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Produk
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No Produk</label>
                            <input type="text" name="no_produk" id="noProdukGenerated" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" placeholder="Auto-generated..." readonly>
                            <small class="text-gray-500 text-xs mt-1">Nomor akan digenerate otomatis</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_barang" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama barang" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi <span class="text-red-500">*</span></label>
                            <textarea name="spesifikasi" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan spesifikasi lengkap produk" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Barang <span class="text-red-500">*</span></label>
                            <select name="jenis_barang" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Pilih jenis barang</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Mesin">Mesin</option>
                                <option value="Meubel">Meubel</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai TKDN (%)</label>
                            <input type="number" name="nilai_tkdn" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="0-100">
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors duration-200">
                                <input type="file" name="gambar" id="gambarInput" class="hidden" accept="image/*" onchange="previewImage(this)">
                                <div id="uploadArea" onclick="document.getElementById('gambarInput').click()" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 mb-2">Klik untuk upload gambar</p>
                                    <p class="text-sm text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                                </div>
                                <div id="imagePreview" class="hidden">
                                    <img id="previewImg" src="" alt="Preview" class="max-w-32 max-h-32 mx-auto rounded-lg">
                                    <p class="text-sm text-gray-600 mt-2">Gambar berhasil dipilih</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 flex-shrink-0">
            <button type="button" onclick="closeModal('modalTambahProduk')" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                Batal
            </button>
            <button type="submit" form="formTambahProduk" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fas fa-save"></i>
                <span>Simpan Produk</span>
            </button>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadArea').classList.add('hidden');
                document.getElementById('imagePreview').classList.remove('hidden');
                document.getElementById('previewImg').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Generate product number when modal opens
    document.addEventListener('DOMContentLoaded', function() {
        // Generate random product number
        function generateProductNumber() {
            const prefix = 'PRD';
            const number = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            return `${prefix}-${number}`;
        }

        // Set generated number when modal is opened
        const modalTambah = document.getElementById('modalTambahProduk');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (!modalTambah.classList.contains('hidden')) {
                        document.getElementById('noProdukGenerated').value = generateProductNumber();
                    }
                }
            });
        });
        observer.observe(modalTambah, { attributes: true });
    });
</script>
