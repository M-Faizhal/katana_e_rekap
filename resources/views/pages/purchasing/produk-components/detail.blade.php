<!-- Modal Detail Produk -->
<div id="modalDetailProduk" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Detail Produk</h3>
                    <p class="text-red-100 text-sm">Informasi lengkap produk</p>
                </div>
            </div>
            <button onclick="closeModal('modalDetailProduk')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <div class="space-y-6">
                <!-- Product Image -->
                <div class="text-center">
                    <div class="inline-block bg-gray-100 rounded-2xl p-4">
                        <img id="detailProductImage" src="https://via.placeholder.com/300" alt="Product Image" class="max-w-xs max-h-64 rounded-xl object-cover">
                    </div>
                </div>

                <!-- Product Information -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Produk
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Kode Produk</label>
                                <p id="detailNoProduk" class="text-lg font-semibold text-gray-800">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Barang</label>
                                <p id="detailNamaBarang" class="text-lg font-semibold text-gray-800">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Kategori</label>
                                <span id="detailJenisBarang" class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                    -
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Brand</label>
                                <p id="detailBrand" class="text-lg font-semibold text-gray-800">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Spesifikasi Kunci</label>
                                <p id="detailSpesifikasiKunci" class="text-base text-gray-700">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Garansi</label>
                                <p id="detailGaransi" class="text-base text-gray-700">-</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Vendor</label>
                                <p id="detailVendor" class="text-lg font-semibold text-gray-800">-</p>
                            </div>
                            <div id="detailHargaMarketingContainer" style="display: none;">
                                <label class="block text-sm font-medium text-gray-500 mb-1">
                                    Harga Jual
                                </label>
                                <p id="detailHargaMarketing" class="text-xl font-bold text-orange-600">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Harga Pasaran Inaproc</label>
                                <p id="detailHargaInaproc" class="text-lg font-bold text-red-600">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Harga Vendor</label>
                                <p id="detailHarga" class="text-lg font-bold text-red-600">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">PDN/TKDN/Impor</label>
                                <span id="detailPdnTkdn" class="inline-flex px-3 py-1 text-sm font-semibold rounded">-</span>
                            </div>
                            <div id="detailSkorTkdnContainer" style="display: none;">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Skor TKDN</label>
                                <p id="detailSkorTkdn" class="text-base font-semibold text-green-700">-</p>
                            </div>
                            <div id="detailLinkTkdnContainer" style="display: none;">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Link TKDN</label>
                                <a id="detailLinkTkdn" href="#" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    <span id="detailLinkTkdnText">Buka Link TKDN</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Product Details -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clipboard-list text-red-600 mr-2"></i>
                        Detail Tambahan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Estimasi Ketersediaan</label>
                            <p id="detailEstimasiKetersediaan" class="text-base text-gray-700">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Link Produk</label>
                            <div class="flex items-center gap-2">
                                <input type="url" id="inputLinkProduk"
                                    placeholder="https://..."
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                                <button type="button" onclick="saveLinkProduk()"
                                    class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm flex items-center gap-1 whitespace-nowrap">
                                    <i class="fas fa-save"></i>
                                    <span>Simpan</span>
                                </button>
                                <a id="detailLinkProduk" href="#" target="_blank" rel="noopener noreferrer"
                                    class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm hidden items-center gap-1 whitespace-nowrap">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Buka</span>
                                </a>
                            </div>
                            <p id="linkProdukSaveMsg" class="text-xs mt-1 hidden"></p>
                        </div>
                    </div>
                </div>

                <!-- Product Specifications -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-cogs text-red-600 mr-2"></i>
                        Spesifikasi
                    </h4>
                    
                    <!-- Text Specification -->
                    <div id="detailSpesifikasiTextContainer" class="bg-white rounded-lg p-4 border mb-3" style="display: none;">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-file-text text-gray-500 mr-2"></i>
                            <span class="text-sm font-medium text-gray-600">Spesifikasi Teks:</span>
                        </div>
                        <p id="detailSpesifikasi" class="text-gray-700 leading-relaxed">
                            -
                        </p>
                    </div>
                    
                    <!-- File Specification -->
                    <div id="detailSpesifikasiFileContainer" class="bg-white rounded-lg p-4 border" style="display: none;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-blue-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-600">File Spesifikasi:</span>
                            </div>
                            <a id="detailSpesifikasiFileLink" href="#" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                <span id="detailSpesifikasiFileName">Lihat File</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- No Specification -->
                    <div id="detailNoSpesifikasiContainer" class="bg-white rounded-lg p-4 border text-center">
                        <i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500">Tidak ada spesifikasi tersedia</p>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-red-600 mr-2"></i>
                        Informasi Tambahan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-4 text-center border">
                            <i class="fas fa-box-open text-blue-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">Kategori</p>
                            <p id="detailKategori" class="font-semibold text-gray-800">-</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center border">
                            <i class="fas fa-calendar text-green-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">Tanggal Dibuat</p>
                            <p id="detailTanggalDibuat" class="font-semibold text-gray-800">-</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center border">
                            <i class="fas fa-calendar-check text-purple-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">Terakhir Update</p>
                            <p id="detailLastUpdate" class="font-semibold text-gray-800">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 flex-shrink-0">
            <button type="button" onclick="closeModal('modalDetailProduk')" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>
