<!-- Modal Edit Vendor -->
<div id="modalEditVendor" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-800 to-red-700 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Edit Vendor</h3>
                    <p class="text-red-100 text-sm">Ubah informasi vendor dan kelola produknya</p>
                </div>
            </div>
            <button onclick="closeModal('modalEditVendor')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formEditVendor" class="space-y-6">
                <input type="hidden" id="editVendorId" name="vendor_id">

                <!-- Informasi Vendor -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                    <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-building text-white text-sm"></i>
                        </div>
                        Informasi Vendor
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Vendor <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_vendor" id="editNamaVendor" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" placeholder="Masukkan nama vendor" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Perusahaan <span class="text-red-500">*</span></label>
                            <select name="jenis_perusahaan" id="editJenisPerusahaan" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" required>
                                <option value="">Pilih jenis perusahaan</option>
                                <option value="Principle">Principle</option>
                                <option value="Distributor">Distributor</option>
                                <option value="Retail">Retail</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="editEmailVendor" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" placeholder="vendor@email.com" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kontak/No HP <span class="text-red-500">*</span></label>
                            <input type="tel" name="kontak" id="editKontakVendor" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" placeholder="0812-3456-7890" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="alamat" id="editAlamatVendor" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" placeholder="Masukkan alamat lengkap vendor"></textarea>
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
                            <h5 class="text-lg font-semibold text-gray-800 flex items-center" id="productFormTitle">
                                <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                                Tambah Produk Baru
                            </h5>
                            <div class="text-sm text-gray-500" id="productFormHint">
                                <i class="fas fa-info-circle mr-1"></i>
                                Isi semua field untuk menambah produk
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                                <input type="text" id="editNewProductName" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Masukkan nama produk">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Brand/Merk <span class="text-red-500">*</span></label>
                                <input type="text" id="editNewProductBrand" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Brand produk">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select id="editNewProductKategori" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Pilih kategori</option>
                                    <option value="Elektronik">📱 Elektronik</option>
                                    <option value="Meubel">🪑 Meubel</option>
                                    <option value="Mesin">⚙️ Mesin</option>
                                    <option value="Lain-lain">📦 Lain-lain</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                                <input type="text" id="editNewProductSatuan" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="pcs, kg, box, dll">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Vendor <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="number" id="editNewProductHarga" class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="0" min="0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk</label>
                                <input type="file" id="editNewProductFoto" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Max: 2MB)</p>
                            </div>
                            <div class="md:col-span-2 lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi Detail</label>
                                
                                <!-- Toggle untuk memilih jenis input -->
                                <div class="mb-3 flex items-center space-x-4 bg-gray-50 p-3 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700">Jenis Input:</span>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="edit_spesifikasi_type" value="text" checked 
                                               onchange="toggleEditSpesifikasiInput('text')" 
                                               class="mr-2 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">
                                            <i class="fas fa-keyboard mr-1"></i>Input Teks
                                        </span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="edit_spesifikasi_type" value="file" 
                                               onchange="toggleEditSpesifikasiInput('file')" 
                                               class="mr-2 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">
                                            <i class="fas fa-file-upload mr-1"></i>Upload File
                                        </span>
                                    </label>
                                </div>

                                <!-- Input Teks (Default) -->
                                <div id="editSpesifikasiTextInput">
                                    <textarea id="editNewProductSpesifikasi" rows="3" 
                                              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                              placeholder="Deskripsi lengkap produk, spesifikasi teknis, dll..."></textarea>
                                </div>

                                <!-- Input File (Hidden by default) -->
                                <div id="editSpesifikasiFileInput" style="display: none;">
                                    <input type="file" id="editNewProductSpesifikasiFile" 
                                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                           accept=".pdf,.doc,.docx,.txt,.xls,.xlsx">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Format: PDF, DOC, DOCX, TXT, XLS, XLSX (Max: 5MB)
                                    </p>
                                    <div id="editSpesifikasiFilePreview" class="mt-2 hidden">
                                        <div class="flex items-center p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                            <i class="fas fa-file text-blue-600 mr-2"></i>
                                            <span id="editSpesifikasiFileName" class="text-sm text-blue-800"></span>
                                            <button type="button" onclick="removeEditSpesifikasiFile()" 
                                                    class="ml-auto text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" id="cancelEditProductBtn" onclick="cancelEditProduct()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 hidden">
                                <i class="fas fa-times mr-2"></i>Batal Edit
                            </button>
                            <button type="button" onclick="addProductToEditVendor()" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
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
                            <div class="flex items-center space-x-2">
                                <div class="text-sm text-gray-500" id="editProductCount">
                                    0 produk
                                </div>
                                <button type="button" onclick="exportProductList()" class="px-3 py-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-all duration-200" title="Export daftar produk">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button type="button" onclick="clearAllProducts()" class="px-3 py-2 bg-orange-100 text-orange-600 rounded-lg hover:bg-orange-200 transition-all duration-200" title="Hapus semua produk">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Search and Filter for Products -->
                        <div class="mb-4 flex space-x-3" id="productSearchContainer" style="display: none;">
                            <div class="flex-1">
                                <input type="text" id="searchProducts" placeholder="Cari produk berdasarkan nama, brand, atau kategori..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <select id="filterProductCategory" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Semua Kategori</option>
                                    <option value="Elektronik">📱 Elektronik</option>
                                    <option value="Meubel">🪑 Meubel</option>
                                    <option value="Mesin">⚙️ Mesin</option>
                                    <option value="Lain-lain">📦 Lain-lain</option>
                                </select>
                            </div>
                        </div>
                        
                        <div id="editVendorProductList" class="space-y-3">
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
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200 flex-shrink-0">
            <div class="text-sm text-gray-500">
                <i class="fas fa-save mr-1"></i>
                Perubahan akan disimpan secara permanen
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="closeModal('modalEditVendor')" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="button" onclick="submitEditVendor()" class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Update Vendor & Produk
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Fix untuk mencegah form submission yang tidak diinginkan
function editProductInVendor(index) {
    if (event) {
        event.preventDefault(); // Mencegah default behavior
        event.stopPropagation(); // Mencegah event bubbling
    }
    
    const product = editVendorProducts[index];
    if (!product) return;
    
    // Store the index being edited
    editProductIndex = index;
    
    // Fill form with product data
    document.getElementById('editNewProductName').value = product.nama_barang || '';
    document.getElementById('editNewProductBrand').value = product.brand || '';
    document.getElementById('editNewProductKategori').value = product.kategori || '';
    document.getElementById('editNewProductSatuan').value = product.satuan || '';
    document.getElementById('editNewProductSpesifikasi').value = product.spesifikasi || '';
    document.getElementById('editNewProductHarga').value = product.harga_vendor || '';
    
    // Update form title and hint
    const formTitle = document.getElementById('productFormTitle');
    const formHint = document.getElementById('productFormHint');
    if (formTitle) {
        formTitle.innerHTML = '<i class="fas fa-edit text-yellow-600 mr-2"></i>Edit Produk';
    }
    if (formHint) {
        formHint.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Ubah data produk yang diperlukan';
    }
    
    // Change button text and behavior to indicate edit mode
    const addButton = document.querySelector('button[onclick="addProductToEditVendor()"]');
    if (addButton) {
        addButton.innerHTML = '<i class="fas fa-save mr-2"></i>Update Produk';
        addButton.setAttribute('onclick', 'updateProductInVendor()');
        addButton.className = 'px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-lg hover:from-yellow-700 hover:to-yellow-800 transition-all duration-200 transform hover:scale-105 shadow-lg';
    }
    
    // Show cancel button
    const cancelButton = document.getElementById('cancelEditProductBtn');
    if (cancelButton) {
        cancelButton.classList.remove('hidden');
    }
    
    // Scroll to form
    document.querySelector('.bg-gradient-to-br.from-blue-50').scrollIntoView({ behavior: 'smooth' });
}

function removeProductFromEditVendor(index) {
    if (event) {
        event.preventDefault(); // Mencegah default behavior
        event.stopPropagation(); // Mencegah event bubbling
    }
    
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        // Rest of the function implementation
        editVendorProducts.splice(index, 1);
        
        // Reset edit mode if editing the deleted product
        if (editProductIndex === index) {
            resetProductEditMode();
            clearEditProductForm();
        } else if (editProductIndex > index) {
            // Adjust edit index if needed
            editProductIndex--;
        }
        
        updateEditVendorProductList();
        showToast('Produk berhasil dihapus!', 'success');
    }
}

// Function untuk toggle antara input teks dan file untuk spesifikasi di edit modal
function toggleEditSpesifikasiInput(type) {
    const textInput = document.getElementById('editSpesifikasiTextInput');
    const fileInput = document.getElementById('editSpesifikasiFileInput');
    const filePreview = document.getElementById('editSpesifikasiFilePreview');
    
    if (type === 'text') {
        textInput.style.display = 'block';
        fileInput.style.display = 'none';
        filePreview.classList.add('hidden');
        // Reset file input
        document.getElementById('editNewProductSpesifikasiFile').value = '';
    } else if (type === 'file') {
        textInput.style.display = 'none';
        fileInput.style.display = 'block';
        // Reset text input
        document.getElementById('editNewProductSpesifikasi').value = '';
    }
}

// Function untuk remove file spesifikasi di edit modal
function removeEditSpesifikasiFile() {
    document.getElementById('editNewProductSpesifikasiFile').value = '';
    document.getElementById('editSpesifikasiFilePreview').classList.add('hidden');
}

// Tambahan untuk mencegah form submission pada modal
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('formEditVendor');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            return false;
        });
    }
    
    // Handle file upload preview untuk edit modal
    const editFileInput = document.getElementById('editNewProductSpesifikasiFile');
    if (editFileInput) {
        editFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('editSpesifikasiFilePreview');
            const fileName = document.getElementById('editSpesifikasiFileName');
            
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
</script>
