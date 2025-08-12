<!-- Modal Hapus Produk -->
<div id="modalHapusProduk" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Konfirmasi Hapus</h3>
                    <p class="text-red-100 text-sm">Hapus produk dari sistem</p>
                </div>
            </div>
            <button onclick="closeModal('modalHapusProduk')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trash text-red-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Yakin ingin menghapus produk ini?</h4>
                <p class="text-gray-600 text-sm mb-4">Tindakan ini tidak dapat dibatalkan</p>
                
                <!-- Product Info to be deleted -->
                <div class="bg-gray-50 rounded-lg p-4 border">
                    <div class="flex items-center space-x-3">
                        <img id="deleteProductImage" src="https://via.placeholder.com/48" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                        <div class="text-left">
                            <p id="deleteProductName" class="font-semibold text-gray-800">Laptop Dell Latitude 7420</p>
                            <p id="deleteProductNo" class="text-sm text-gray-500">PRD-001</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                    <div>
                        <h5 class="font-medium text-yellow-800">Peringatan!</h5>
                        <p class="text-sm text-yellow-700 mt-1">
                            Menghapus produk akan menghilangkan semua data terkait termasuk riwayat dan referensi dalam sistem lain.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Confirmation Checkbox -->
            <div class="mb-6">
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input type="checkbox" id="confirmDelete" class="mt-1 h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <span class="text-sm text-gray-700">
                        Saya memahami bahwa tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait produk ini.
                    </span>
                </label>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 rounded-b-2xl">
            <button type="button" onclick="closeModal('modalHapusProduk')" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                Batal
            </button>
            <button type="button" id="confirmDeleteBtn" onclick="confirmDeleteProduct()" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center space-x-2 opacity-50 cursor-not-allowed" disabled>
                <i class="fas fa-trash"></i>
                <span>Hapus Produk</span>
            </button>
        </div>
    </div>
</div>

<script>
    // Enable/disable delete button based on checkbox
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('confirmDelete');
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        
        if (checkbox && deleteBtn) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    deleteBtn.disabled = false;
                    deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    deleteBtn.classList.add('hover:bg-red-700');
                } else {
                    deleteBtn.disabled = true;
                    deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    deleteBtn.classList.remove('hover:bg-red-700');
                }
            });
        }
    });

    // Function to populate delete modal (would be called when delete button is clicked)
    function populateDeleteModal(productData) {
        document.getElementById('deleteProductImage').src = productData.gambar || 'https://via.placeholder.com/48';
        document.getElementById('deleteProductName').textContent = productData.nama_barang || '';
        document.getElementById('deleteProductNo').textContent = productData.no_produk || '';
        
        // Reset checkbox
        const checkbox = document.getElementById('confirmDelete');
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        if (checkbox) {
            checkbox.checked = false;
            deleteBtn.disabled = true;
            deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
            deleteBtn.classList.remove('hover:bg-red-700');
        }
    }

    function confirmDeleteProduct() {
        const checkbox = document.getElementById('confirmDelete');
        if (!checkbox.checked) {
            alert('Harap centang kotak konfirmasi terlebih dahulu.');
            return;
        }

        // Here you would implement the actual delete logic
        // For now, just show a success message
        alert('Produk berhasil dihapus!');
        closeModal('modalHapusProduk');
        
        // In a real application, you would:
        // 1. Send AJAX request to delete the product
        // 2. Remove the product card from the UI
        // 3. Update the stats
        // 4. Show success notification
    }
</script>
