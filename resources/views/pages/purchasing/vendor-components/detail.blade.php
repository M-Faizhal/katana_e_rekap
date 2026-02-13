<!-- Modal Detail Vendor -->
<div id="modalDetailVendor" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Detail Vendor</h3>
                    <p class="text-red-100 text-sm">Informasi lengkap vendor</p>
                </div>
            </div>
            <button onclick="closeModal('modalDetailVendor')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <!-- Informasi Umum -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-building text-red-600 mr-2"></i>
                    Informasi Umum
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Vendor</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailNamaVendor">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Vendor</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailJenisPerusahaan">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PKP</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailPkpVendor" class="inline-flex items-center">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="bg-purple-50 rounded-xl p-6 mb-6 border border-purple-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-red-600 mr-2"></i>
                    Keterangan
                </h4>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Distributor Resmi Brand / Authorized Seller / Produk Spesifik Yang Dijual</label>
                    <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800 min-h-[100px]">
                        <span id="detailKeteranganVendor">-</span>
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div class="bg-blue-50 rounded-xl p-6 mb-6 border border-blue-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-phone text-blue-600 mr-2"></i>
                    Kontak
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailKontakVendor">-</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailEmailVendor">-</span>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800 min-h-[100px]">
                            <span id="detailAlamatVendor">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lainnya -->
            <div class="bg-green-50 rounded-xl p-6 mb-6 border border-green-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-ellipsis-h text-green-600 mr-2"></i>
                    Lainnya
                </h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Online Shop</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <span id="detailOnlineShopVendor" class="inline-flex items-center">-</span>
                        </div>
                    </div>
                    <div id="detailNamaOnlineShopContainer" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Online Shop</label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                            <i class="fas fa-store text-blue-600 mr-2"></i>
                            <span id="detailNamaOnlineShop">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-boxes text-red-600 mr-2"></i>
                    Daftar Barang
                </h4>
                <div id="detailProductList" class="space-y-3">
                    <p class="text-gray-500 text-center py-4">Tidak ada produk</p>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalDetailVendor')" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>
