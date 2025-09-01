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
                                <label class="block text-sm font-medium text-gray-500 mb-1">No Produk</label>
                                <p id="detailNoProduk" class="text-lg font-semibold text-gray-800">PRD-001</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Barang</label>
                                <p id="detailNamaBarang" class="text-lg font-semibold text-gray-800">Laptop Dell Latitude 7420</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Barang</label>
                                <span id="detailJenisBarang" class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                    Elektronik
                                </span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nilai TKDN</label>
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div id="detailTkdnBar" class="bg-green-500 h-2 rounded-full" style="width: 25%"></div>
                                    </div>
                                    <span id="detailNilaiTkdn" class="text-lg font-semibold text-green-600">25%</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Dibuat</label>
                                <p id="detailTanggalDibuat" class="text-lg font-semibold text-gray-800">12 Agustus 2025</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                                <span id="detailStatus" class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </div>
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
                                <i class="fas fa-download mr-2"></i>
                                <span id="detailSpesifikasiFileName">Download File</span>
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
                            <p id="detailKategori" class="font-semibold text-gray-800">Teknologi</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center border">
                            <i class="fas fa-flag text-green-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">TKDN</p>
                            <p id="detailTkdnBadge" class="font-semibold text-green-600">25%</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center border">
                            <i class="fas fa-calendar text-purple-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">Terakhir Update</p>
                            <p id="detailLastUpdate" class="font-semibold text-gray-800">Hari ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 flex-shrink-0">
            <button type="button" onclick="closeModal('modalDetailProduk')" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                Tutup
            </button>
            <button type="button" onclick="editProdukFromDetail()" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fas fa-edit"></i>
                <span>Edit Produk</span>
            </button>
        </div>
    </div>
</div>

<script>
    // Function to populate detail modal (would be called when detail button is clicked)
    function populateDetailModal(productData) {
        document.getElementById('detailProductImage').src = productData.gambar || 'https://via.placeholder.com/300';
        document.getElementById('detailNoProduk').textContent = productData.no_produk || '';
        document.getElementById('detailNamaBarang').textContent = productData.nama_barang || '';
        
        // Handle specifications - check if text or file
        const textContainer = document.getElementById('detailSpesifikasiTextContainer');
        const fileContainer = document.getElementById('detailSpesifikasiFileContainer');
        const noSpecContainer = document.getElementById('detailNoSpesifikasiContainer');
        
        // Hide all containers first
        textContainer.style.display = 'none';
        fileContainer.style.display = 'none';
        noSpecContainer.style.display = 'none';
        
        if (productData.file_spesifikasi) {
            // Show file specification container
            fileContainer.style.display = 'block';
            
            // Set download link and filename
            const fileLink = document.getElementById('detailSpesifikasiFileLink');
            const fileName = document.getElementById('detailSpesifikasiFileName');
            
            fileLink.href = productData.file_spesifikasi;
            
            // Extract filename from path or use default
            const pathParts = productData.file_spesifikasi.split('/');
            const extractedFileName = pathParts[pathParts.length - 1] || 'Download Spesifikasi';
            fileName.textContent = extractedFileName;
            
        } else if (productData.spesifikasi) {
            // Show text specification container
            textContainer.style.display = 'block';
            document.getElementById('detailSpesifikasi').textContent = productData.spesifikasi;
            
        } else {
            // Show no specification container
            noSpecContainer.style.display = 'block';
        }
        
        document.getElementById('detailNilaiTkdn').textContent = (productData.nilai_tkdn || 0) + '%';
        document.getElementById('detailTkdnBadge').textContent = (productData.nilai_tkdn || 0) + '%';
        
        // Update TKDN progress bar
        const tkdnBar = document.getElementById('detailTkdnBar');
        tkdnBar.style.width = (productData.nilai_tkdn || 0) + '%';
        
        // Update jenis barang badge
        const jenisBarangBadge = document.getElementById('detailJenisBarang');
        jenisBarangBadge.textContent = productData.jenis_barang || '';
        
        // Set badge color based on jenis barang
        jenisBarangBadge.className = 'inline-flex px-3 py-1 text-sm font-medium rounded-full';
        switch(productData.jenis_barang) {
            case 'Elektronik':
                jenisBarangBadge.classList.add('bg-blue-100', 'text-blue-800');
                break;
            case 'Mesin':
                jenisBarangBadge.classList.add('bg-green-100', 'text-green-800');
                break;
            case 'Meubel':
                jenisBarangBadge.classList.add('bg-yellow-100', 'text-yellow-800');
                break;
            default:
                jenisBarangBadge.classList.add('bg-gray-100', 'text-gray-800');
        }
        
        // Set dates
        document.getElementById('detailTanggalDibuat').textContent = productData.tanggal_dibuat || new Date().toLocaleDateString('id-ID');
        document.getElementById('detailLastUpdate').textContent = productData.last_update || 'Hari ini';
        document.getElementById('detailKategori').textContent = productData.jenis_barang || '';
    }

    function editProdukFromDetail() {
        closeModal('modalDetailProduk');
        openModal('modalEditProduk');
    }
</script>
