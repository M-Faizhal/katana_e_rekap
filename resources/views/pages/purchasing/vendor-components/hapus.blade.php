<!-- Modal Hapus Vendor -->
<div id="modalHapusVendor" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-600 text-white p-6 flex items-center justify-between flex-shrink-0 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Konfirmasi Hapus</h3>
                    <p class="text-red-100 text-sm">Hapus data vendor</p>
                </div>
            </div>
            <button onclick="closeModal('modalHapusVendor')" class="text-white hover:bg-white hover:text-red-600 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-trash text-red-600 text-2xl"></i>
            </div>
            
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Hapus Vendor</h4>
            <p class="text-gray-600 mb-4">
                Apakah Anda yakin ingin menghapus vendor 
                <span class="font-semibold text-red-600" id="hapusVendorNama">-</span>?
            </p>
            <p class="text-sm text-red-500 mb-6">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Tindakan ini tidak dapat dibatalkan!
            </p>
            
            <input type="hidden" id="hapusVendorId" value="">
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-center space-x-3 border-t border-gray-200 rounded-b-2xl">
            <button type="button" onclick="closeModal('modalHapusVendor')" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="button" onclick="confirmHapusVendor()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i>Hapus Vendor
            </button>
        </div>
    </div>
</div>
