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
                        Informasi Umum
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Vendor <span class="text-red-500">*</span></label>
                            <input type="text" id="namaVendor" name="nama_vendor" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama vendor" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Vendor <span class="text-red-500">*</span></label>
                            <select id="jenisPerusahaan" name="jenis_perusahaan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Pilih jenis perusahaan</option>
                                <option value="Principle">Principle</option>
                                <option value="Distributor">Distributor</option>
                                <option value="Retail">Retail</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <span class="mr-3">PKP</span>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="pkpVendor" name="pkp" value="ya" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-600">Ya</span>
                                </label>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="bg-purple-50 rounded-xl p-6 border border-purple-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Keterangan
                    </h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Distributor Resmi Brand / Authorized Seller / Produk Spesifik Yang Dijual</label>
                        <textarea id="keteranganVendor" name="keterangan" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Contoh: Distributor Resmi Epson, Authorized Seller Canon, dll"></textarea>
                    </div>
                </div>

                <!-- Kontak -->
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-phone text-blue-600 mr-2"></i>
                        Kontak
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                            <input type="tel" id="kontakVendor" name="kontak" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0812-3456-7890">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="emailVendor" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="vendor@email.com">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea id="alamatVendor" name="alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan alamat lengkap vendor"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Lainnya -->
                <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-ellipsis-h text-green-600 mr-2"></i>
                        Lainnya
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <span class="mr-3">Online Shop</span>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="onlineShopVendor" name="online_shop" value="ya" class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 focus:ring-2" onchange="toggleOnlineShopInput()">
                                    <span class="ml-2 text-sm text-gray-600">Ya</span>
                                </label>
                            </label>
                        </div>
                        <div id="namaOnlineShopContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Online Shop</label>
                            <input type="text" id="namaOnlineShop" name="nama_online_shop" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="[A.8] Menu Muncul Jika 'Ya'">
                            <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>Masukkan nama platform atau link toko online (Tokopedia, Shopee, Bukalapak, dll)</p>
                        </div>
                    </div>
                </div>

                <!-- Manajemen Produk -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                    <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-boxes text-white text-sm"></i>
                        </div>
                        Manajemen Produk
                    </h4>
                    
                    <!-- Form Tambah Produk -->
                    <div class="bg-white rounded-xl p-6 mb-6 border border-blue-200 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                                Tambah Produk Baru
                            </h5>
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Isi semua field untuk menambah produk
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                                <input type="text" id="newProductName" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Masukkan nama produk">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Brand/Merk <span class="text-red-500">*</span></label>
                                <input type="text" id="newProductBrand" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Brand produk">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select id="newProductKategori" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Pilih kategori</option>
                                    <option value="Elektronik">📱 Elektronik</option>
                                    <option value="Meubel">🪑 Meubel</option>
                                    <option value="Mesin">⚙️ Mesin</option>
                                    <option value="Lain-lain">📦 Lain-lain</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                                <input type="text" id="newProductSatuan" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="pcs, kg, box, dll">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Vendor <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="number" id="newProductHarga" class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="0" min="0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk</label>
                                <input type="file" id="newProductFoto" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Max: 2MB)</p>
                            </div>
                            <div class="md:col-span-2 lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi Detail</label>
                                
                                <!-- Toggle untuk memilih jenis input -->
                                <div class="mb-3 flex items-center space-x-4 bg-gray-50 p-3 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700">Jenis Input:</span>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="spesifikasi_type" value="text" checked 
                                               onchange="toggleSpesifikasiInput('text')" 
                                               class="mr-2 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">
                                            <i class="fas fa-keyboard mr-1"></i>Input Teks
                                        </span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="spesifikasi_type" value="file" 
                                               onchange="toggleSpesifikasiInput('file')" 
                                               class="mr-2 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">
                                            <i class="fas fa-file-upload mr-1"></i>Upload File
                                        </span>
                                    </label>
                                </div>

                                <!-- Input Teks (Default) -->
                                <div id="spesifikasiTextInput">
                                    <textarea id="newProductSpesifikasi" rows="3" 
                                              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                              placeholder="Deskripsi lengkap produk, spesifikasi teknis, dll..."></textarea>
                                </div>

                                <!-- Input File (Hidden by default) -->
                                <div id="spesifikasiFileInput" style="display: none;">
                                    <input type="file" id="newProductSpesifikasiFile" 
                                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                           accept=".pdf,.doc,.docx,.txt,.xls,.xlsx">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Format: PDF, DOC, DOCX, TXT, XLS, XLSX (Max: 5MB)
                                    </p>
                                    <div id="spesifikasiFilePreview" class="mt-2 hidden">
                                        <div class="flex items-center p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                            <i class="fas fa-file text-blue-600 mr-2"></i>
                                            <span id="spesifikasiFileName" class="text-sm text-blue-800"></span>
                                            <button type="button" onclick="removeSpesifikasiFile()" 
                                                    class="ml-auto text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="button" onclick="addProductToVendor()" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-plus mr-2"></i>Tambah ke Daftar Produk
                            </button>
                        </div>
                    </div>
                    
                    <!-- Daftar Produk Vendor -->
                    <div class="bg-white rounded-xl p-6 border border-blue-200 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-list text-blue-600 mr-2"></i>
                                Daftar Produk Vendor
                            </h5>
                            <div class="text-sm text-gray-500" id="productCount">
                                0 produk
                            </div>
                        </div>
                        
                        <div id="vendorProductList" class="space-y-3">
                            <div class="text-center py-12 text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-4 text-gray-400"></i>
                                <p class="text-lg">Belum ada produk ditambahkan</p>
                                <p class="text-sm">Gunakan form di atas untuk menambah produk</p>
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

<script>
// Function untuk toggle online shop input
function toggleOnlineShopInput() {
    const checkbox = document.getElementById('onlineShopVendor');
    const container = document.getElementById('namaOnlineShopContainer');
    const input = document.getElementById('namaOnlineShop');
    
    if (checkbox.checked) {
        container.classList.remove('hidden');
        input.required = true;
    } else {
        container.classList.add('hidden');
        input.required = false;
        input.value = '';
    }
}

// Function untuk toggle antara input teks dan file untuk spesifikasi
function toggleSpesifikasiInput(type) {
    const textInput = document.getElementById('spesifikasiTextInput');
    const fileInput = document.getElementById('spesifikasiFileInput');
    const filePreview = document.getElementById('spesifikasiFilePreview');
    
    if (type === 'text') {
        textInput.style.display = 'block';
        fileInput.style.display = 'none';
        filePreview.classList.add('hidden');
        // Reset file input
        document.getElementById('newProductSpesifikasiFile').value = '';
    } else if (type === 'file') {
        textInput.style.display = 'none';
        fileInput.style.display = 'block';
        // Reset text input
        document.getElementById('newProductSpesifikasi').value = '';
    }
}

// Function untuk handle file upload preview
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('newProductSpesifikasiFile');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('spesifikasiFilePreview');
            const fileName = document.getElementById('spesifikasiFileName');
            
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    e.target.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                
                // Show preview
                fileName.textContent = file.name;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });
    }
});

// Function untuk remove file spesifikasi
function removeSpesifikasiFile() {
    document.getElementById('newProductSpesifikasiFile').value = '';
    document.getElementById('spesifikasiFilePreview').classList.add('hidden');
}
</script>
