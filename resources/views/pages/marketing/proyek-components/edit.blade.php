<!-- Modal Edit Proyek -->
<style>
    /* Harga Satuan Input Styling for Edit Modal */
    .harga-satuan-wrapper {
        position: relative;
    }

    .harga-satuan-wrapper::before {
        content: 'Rp';
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 0.875rem;
        pointer-events: none;
        z-index: 1;
    }

    .harga-satuan-input-edit {
        padding-left: 35px !important;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.5px;
    }

    .harga-satuan-input-edit:focus {
        padding-left: 35px !important;
    }
</style>

<div id="modalEditProyek" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Edit Proyek</h3>
                    <p class="text-red-100 text-sm">Ubah data proyek</p>
                </div>
            </div>
            <button onclick="closeModal('modalEditProyek')" class="text-white hover:bg-white hover:text-red-800 p-2">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formEditProyek" class="space-y-6">
                <!-- Hidden ID -->
                <input type="hidden" id="editId" name="id">

                <!-- Informasi Dasar -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Proyek</label>
                            <input type="text" id="editIdProyek" name="id_proyek" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" placeholder="Masukkan ID proyek" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" id="editTanggal" name="tanggal" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <input type="text" id="editKabupatenKota" name="kab_kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan kabupaten/kota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi</label>
                            <input type="text" id="editNamaInstansi" name="instansi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama instansi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <select id="editJenisPengadaan" name="jenis_pengadaan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih jenis pengadaan</option>
                                <option value="E-Katalog">E-Katalog</option>
                                <option value="Pengadaan Langsung">Pengadaan Langsung</option>
                                <option value="Tender">Tender / Mini Kompetisi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIC Marketing</label>
                            <select id="editAdminMarketing" name="id_admin_marketing" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Pilih PIC marketing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIC Purchasing</label>
                            <select id="editAdminPurchasing" name="id_admin_purchasing" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih PIC purchasing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Potensi</label>
                            <div class="flex gap-2">
                                <button type="button" id="editPotensiYa" onclick="togglePotensiEdit('ya')" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-center hover:bg-white hover:text-green-600 hover:border-green-600 transition-all duration-200 potensi-btn-edit shadow-sm hover:shadow-md">
                                    <i class="fas fa-thumbs-up mr-2"></i>Ya
                                </button>
                                <button type="button" id="editPotensiTidak" onclick="togglePotensiEdit('tidak')" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-center hover:bg-white hover:text-red-600 hover:border-red-600 transition-all duration-200 potensi-btn-edit shadow-sm hover:shadow-md">
                                    <i class="fas fa-thumbs-down mr-2"></i>Tidak
                                </button>
                            </div>
                            <input type="hidden" id="editPotensiValue" name="potensi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Potensi</label>
                            <input type="number" id="editTahunPotensi" name="tahun_potensi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="2024" min="2020" max="2030">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea id="editCatatan" name="catatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan catatan proyek..."></textarea>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-boxes text-red-600 mr-2"></i>
                            Daftar Barang
                        </h4>
                        <button type="button" onclick="tambahBarangEdit()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Barang
                        </button>
                    </div>

                    <div id="daftarBarangEdit" class="space-y-4">
                        <!-- Items will be populated here -->
                    </div>

                    <!-- Total Keseluruhan -->
                    <div class="mt-6 bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <h5 class="text-lg font-semibold text-gray-800">Total Keseluruhan:</h5>
                            <div class="text-2xl font-bold text-red-600" id="totalKeseluruhanEdit">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Surat -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-upload text-red-600 mr-2"></i>
                        Dokumen Surat
                    </h4>

                    <!-- Penawaran Status Info -->
                    <div id="penawaranStatusInfo" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <div>
                                <span class="text-sm font-medium text-blue-800">Status Penawaran: </span>
                                <span id="penawaranStatus" class="text-sm text-blue-700 font-semibold">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Surat Penawaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surat Penawaran</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" id="editSuratPenawaran" name="surat_penawaran"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx">
                                <button type="button" onclick="clearFile('editSuratPenawaran')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="editSuratPenawaranPreview" class="mt-2 text-sm text-gray-600 hidden">
                                <i class="fas fa-file-pdf mr-1"></i>
                                <span class="filename">No file selected</span>
                            </div>
                        </div>

                        <!-- Surat Persetujuan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surat Persetujuan</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" id="editSuratPersetujuan" name="surat_persetujuan"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx">
                                <button type="button" onclick="clearFile('editSuratPersetujuan')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="editSuratPersetujuanPreview" class="mt-2 text-sm text-gray-600 hidden">
                                <i class="fas fa-file-pdf mr-1"></i>
                                <span class="filename">No file selected</span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Files Display -->
                    <div class="mt-4 space-y-3">
                        <h5 class="text-sm font-medium text-gray-700">File Yang Ada Saat Ini:</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                    <span>Surat Penawaran:</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span id="currentSuratPenawaran" class="text-gray-600 font-mono text-xs">Loading...</span>
                                    <a id="downloadSuratPenawaran" href="#" class="text-red-600 hover:text-red-700 hidden" title="Download">
                                        <i class="fas fa-download text-sm"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-purple-500 mr-2"></i>
                                    <span>Surat Pesanan:</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span id="currentSuratPersetujuan" class="text-gray-600 font-mono text-xs">Loading...</span>
                                    <a id="downloadSuratPesanan" href="#" class="text-purple-600 hover:text-purple-700 hidden" title="Download">
                                        <i class="fas fa-download text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalEditProyek')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Batal
            </button>
            <button type="submit" form="formEditProyek" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Update Proyek
            </button>
        </div>
    </div>
</div>

<script>
let editItemCounter = 0;

// Toggle potensi buttons for edit modal
function togglePotensiEdit(value) {
    const yaBtn = document.getElementById('editPotensiYa');
    const tidakBtn = document.getElementById('editPotensiTidak');
    const hiddenInput = document.getElementById('editPotensiValue');

    if (!yaBtn || !tidakBtn || !hiddenInput) {
        console.error('Edit potensi elements not found');
        return;
    }

    // Reset all buttons
    yaBtn.classList.remove('bg-green-500', 'text-white', 'border-green-500');
    tidakBtn.classList.remove('bg-red-500', 'text-white', 'border-red-500');
    yaBtn.classList.add('border-gray-300', 'text-gray-700');
    tidakBtn.classList.add('border-gray-300', 'text-gray-700');

    if (value === 'ya') {
        yaBtn.classList.remove('border-gray-300', 'text-gray-700');
        yaBtn.classList.add('bg-green-500', 'text-white', 'border-green-500');
        hiddenInput.value = 'ya';
    } else if (value === 'tidak') {
        tidakBtn.classList.remove('border-gray-300', 'text-gray-700');
        tidakBtn.classList.add('bg-red-500', 'text-white', 'border-red-500');
        hiddenInput.value = 'tidak';
    }
}

function loadEditData(data) {
    console.log('Loading edit data:', data);
    console.log('Penawaran data in loadEditData:', data.penawaran);

    // Load basic information with null checks
    const setElementValue = (id, value) => {
        const element = document.getElementById(id);
        if (element) {
            element.value = value || '';
            console.log(`Set ${id} to:`, value);
        } else {
            console.warn(`Element ${id} not found`);
        }
    };

    // Set semua data dasar
    setElementValue('editId', data.id);
    setElementValue('editIdProyek', data.kode);
    setElementValue('editKabupatenKota', data.kabupaten_kota || data.kabupaten);
    setElementValue('editNamaInstansi', data.nama_instansi || data.instansi);
    setElementValue('editJenisPengadaan', data.jenis_pengadaan);
    setElementValue('editTanggal', data.tanggal);
    setElementValue('editCatatan', data.catatan);
    setElementValue('editTahunPotensi', data.tahun_potensi);
    setElementValue('editStatus', data.status);

    // Set PIC marketing dengan ID
    const adminMarketingSelect = document.getElementById('editAdminMarketing');
    if (adminMarketingSelect && data.id_admin_marketing) {
        // Wait for options to load then set value
        setTimeout(() => {
            adminMarketingSelect.value = data.id_admin_marketing;
        }, 100);
    }

    // Set PIC purchasing dengan ID
    const adminPurchasingSelect = document.getElementById('editAdminPurchasing');
    if (adminPurchasingSelect && data.id_admin_purchasing) {
        // Wait for options to load then set value
        setTimeout(() => {
            adminPurchasingSelect.value = data.id_admin_purchasing;
            console.log('Set PIC purchasing to:', data.id_admin_purchasing);
        }, 500);
    }

    // Load potensi
    if (data.potensi) {
        console.log('Setting potensi to:', data.potensi);
        togglePotensiEdit(data.potensi);
    }

    // Load current files information
    loadCurrentFiles(data);

    // Load items (check both field names for compatibility)
    const container = document.getElementById('daftarBarangEdit');
    if (container) {
        container.innerHTML = '';
        editItemCounter = 0;

        const items = data.items || data.daftar_barang || [];
        console.log('Loading items for edit:', items);

        if (items.length > 0) {
            items.forEach((item, index) => {
                console.log('Adding item:', item);
                addEditItem(item);
            });
        } else {
            console.log('No items found, adding empty item');
            addEditItem();
        }

        updateEditDeleteButtons();
        hitungTotalKeseluruhanEdit();
    }
}// Make function available globally
window.loadEditData = loadEditData;

function addEditItem(itemData = null) {
    const container = document.getElementById('daftarBarangEdit');

    // Handle different data structure possibilities
    let nama = '';
    let qty = '';
    let satuan = '';
    let hargaSatuan = '';
    let spesifikasi = '';
    let existingFiles = [];

    if (itemData) {
        nama = itemData.nama || itemData.nama_barang || '';
        qty = itemData.qty || itemData.jumlah || '';
        satuan = itemData.satuan || '';
        // Format harga satuan for Indonesian display if it exists
        if (itemData.harga_satuan && itemData.harga_satuan !== null) {
            const price = parseFloat(itemData.harga_satuan);
            if (!isNaN(price)) {
                // Format with Indonesian locale (dots for thousands, comma for decimals)
                hargaSatuan = price.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 5
                });
            }
        }
        spesifikasi = itemData.spesifikasi || '';
        existingFiles = itemData.spesifikasi_files || [];
    }

    const itemHtml = `
        <div class="barang-item-edit bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <h5 class="font-medium text-gray-800">Item ${editItemCounter + 1}</h5>
                <button type="button" onclick="hapusBarangEdit(this)" class="text-red-600 hover:bg-red-100 rounded-lg p-2 transition-colors duration-200" style="${editItemCounter === 0 ? 'display: none;' : ''}">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" name="barang[${editItemCounter}][nama]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Nama barang" value="${nama}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                    <input type="number" name="barang[${editItemCounter}][qty]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm qty-input-edit" placeholder="0" min="1" value="${qty}" onchange="hitungTotalEdit(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <select name="barang[${editItemCounter}][satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                        <option value="">Pilih satuan</option>
                        <option value="pcs" ${satuan === 'pcs' ? 'selected' : ''}>Pcs</option>
                        <option value="unit" ${satuan === 'unit' ? 'selected' : ''}>Unit</option>
                        <option value="set" ${satuan === 'set' ? 'selected' : ''}>Set</option>
                        <option value="buah" ${satuan === 'buah' ? 'selected' : ''}>Buah</option>
                        <option value="kg" ${satuan === 'kg' ? 'selected' : ''}>Kg</option>
                        <option value="meter" ${satuan === 'meter' ? 'selected' : ''}>Meter</option>
                        <option value="liter" ${satuan === 'liter' ? 'selected' : ''}>Liter</option>
                        <option value="paket" ${satuan === 'paket' ? 'selected' : ''}>Paket</option>
                        <option value="sistem" ${satuan === 'sistem' ? 'selected' : ''}>Sistem</option>
                        <option value="layanan" ${satuan === 'layanan' ? 'selected' : ''}>Layanan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp)</label>
                    <div class="harga-satuan-wrapper">
                        <input type="text" name="barang[${editItemCounter}][harga_satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm harga-satuan-input-edit" placeholder="1.500.000,50" value="${hargaSatuan}" oninput="formatHargaSatuanEdit(this)" onchange="hitungTotalEdit(this)">
                    </div>
                    <small class="text-gray-500 text-xs">Opsional - untuk estimasi (contoh: 1.500.000,50)</small>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Total (Rp)</label>
                    <input type="text" name="barang[${editItemCounter}][harga_total]" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 text-sm harga-total-input-edit" placeholder="0" readonly>
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi</label>
                <textarea name="barang[${editItemCounter}][spesifikasi]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Masukkan spesifikasi barang...">${spesifikasi}</textarea>

                <!-- Existing Files Display -->
                ${existingFiles && existingFiles.length > 0 ? `
                <div class="mt-2">
                    <div class="text-sm font-medium text-gray-700 mb-2">📎 File spesifikasi yang ada (${existingFiles.length} file):</div>
                    <div class="space-y-1">
                        ${existingFiles.map(file => `
                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded border text-sm">
                                <div class="flex items-center space-x-2">
                                    <i class="fas ${getFileIcon(file.original_name)} text-gray-500"></i>
                                    <span class="font-medium">${file.original_name}</span>
                                    <span class="text-gray-500">(${formatFileSize(file.file_size)})</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    ${file.mime_type && file.mime_type.includes('pdf') ? `
                                        <button type="button" onclick="previewFile('${file.stored_name}')" class="text-blue-600 hover:text-blue-800 p-1">
                                            <i class="fas fa-eye" title="Preview"></i>
                                        </button>
                                    ` : ''}
                                    <button type="button" onclick="downloadFile('${file.stored_name}')" class="text-green-600 hover:text-green-800 p-1">
                                        <i class="fas fa-download" title="Download"></i>
                                    </button>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : ''}

                <!-- File Upload Option -->
                <div class="mt-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="enableFileUploadEdit_${editItemCounter}" class="file-upload-checkbox-edit mr-2 text-red-600 focus:ring-red-500" onchange="toggleFileUploadEdit(this, ${editItemCounter})">
                        <label for="enableFileUploadEdit_${editItemCounter}" class="text-sm text-gray-600 cursor-pointer flex items-center">
                            <i class="fas fa-paperclip mr-1 text-gray-500"></i>
                            ${existingFiles && existingFiles.length > 0 ? 'Tambah file baru' : 'Tambah lampiran file spesifikasi'}
                        </label>
                    </div>

                    <!-- File Upload Area (Hidden by default) -->
                    <div id="fileUploadAreaEdit_${editItemCounter}" class="mt-3 hidden">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-red-400 transition-colors duration-200">
                            <input type="file" name="barang[${editItemCounter}][files][]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" class="hidden" id="fileInputEdit_${editItemCounter}" onchange="handleFileSelectEdit(this, ${editItemCounter})">
                            <label for="fileInputEdit_${editItemCounter}" class="cursor-pointer">
                                <div class="text-gray-500">
                                    <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                    <p class="text-sm font-medium">Klik untuk browse atau drag & drop files</p>
                                    <p class="text-xs mt-1">PDF, DOC, XLS, JPG, PNG (Max 5MB per file)</p>
                                </div>
                            </label>
                        </div>

                        <!-- Selected Files Preview -->
                        <div id="filePreviewEdit_${editItemCounter}" class="mt-3 space-y-2 hidden">
                            <div class="text-sm font-medium text-gray-700 mb-2">File terpilih:</div>
                            <div id="fileListEdit_${editItemCounter}" class="space-y-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', itemHtml);
    editItemCounter++;

    // Calculate total for this item if data provided
    if (itemData && qty && hargaSatuan) {
        const newItem = container.lastElementChild;
        const qtyValue = parseFloat(qty) || 0;
        const hargaSatuanValue = parseIndonesianNumber(hargaSatuan);
        const total = qtyValue * hargaSatuanValue;
        
        if (total > 0) {
            newItem.querySelector('.harga-total-input-edit').value = formatNumberToIndonesian(total);
        }
    }
}

function tambahBarangEdit() {
    addEditItem();
    updateEditDeleteButtons();
}

function hapusBarangEdit(button) {
    const item = button.closest('.barang-item-edit');
    item.remove();
    updateEditItemNumbers();
    hitungTotalKeseluruhanEdit();
    updateEditDeleteButtons();
}

function updateEditItemNumbers() {
    const items = document.querySelectorAll('.barang-item-edit');
    items.forEach((item, index) => {
        item.querySelector('h5').textContent = `Item ${index + 1}`;

        // Update input names and IDs
        item.querySelectorAll('input, select, textarea').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
            }

            // Update IDs for file upload elements
            const id = input.getAttribute('id');
            if (id && id.includes('Edit_')) {
                const newId = id.replace(/Edit_\d+$/, `Edit_${index}`);
                input.setAttribute('id', newId);
            }

            // Update onchange attributes for file inputs
            const onchange = input.getAttribute('onchange');
            if (onchange && onchange.includes('toggleFileUploadEdit')) {
                input.setAttribute('onchange', onchange.replace(/\d+\)/, `${index})`));
            }
            if (onchange && onchange.includes('handleFileSelectEdit')) {
                input.setAttribute('onchange', onchange.replace(/\d+\)/, `${index})`));
            }
        });

        // Update labels and divs for file upload
        item.querySelectorAll('label, div').forEach(element => {
            const forAttr = element.getAttribute('for');
            if (forAttr && forAttr.includes('Edit_')) {
                element.setAttribute('for', forAttr.replace(/Edit_\d+$/, `Edit_${index}`));
            }

            const id = element.getAttribute('id');
            if (id && id.includes('Edit_')) {
                element.setAttribute('id', id.replace(/Edit_\d+$/, `Edit_${index}`));
            }
        });
    });
}

function updateEditDeleteButtons() {
    const items = document.querySelectorAll('.barang-item-edit');
    items.forEach((item, index) => {
        const deleteButton = item.querySelector('button[onclick="hapusBarangEdit(this)"]');
        if (deleteButton) {
            if (items.length > 1) {
                deleteButton.style.display = 'block';
            } else {
                deleteButton.style.display = 'none';
            }
        }
    });
}

// Helper function to format number to Indonesian format (dots for thousands, comma for decimals)
function formatNumberToIndonesian(number) {
    if (!number || isNaN(number)) return '0';
    
    // Convert to string and handle decimal places
    let numStr = number.toString();
    let [integerPart, decimalPart] = numStr.split('.');
    
    // Format integer part with dots as thousand separators
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    // Reconstruct with comma as decimal separator if there are decimals
    if (decimalPart && decimalPart !== '0') {
        // Remove trailing zeros from decimal part
        decimalPart = decimalPart.replace(/0+$/, '');
        if (decimalPart) {
            return integerPart + ',' + decimalPart;
        }
    }
    
    return integerPart;
}

// Helper function to parse Indonesian formatted number to float
function parseIndonesianNumber(value) {
    if (!value || typeof value !== 'string') return 0;
    
    // Clean the value: remove dots (thousand separators) and replace comma with dot for decimal
    let cleanValue = value
        .trim()
        .replace(/\./g, '')    // Remove thousand separators (dots)
        .replace(/,/g, '.');   // Replace decimal comma with dot for parsing
    
    return parseFloat(cleanValue) || 0;
}

function hitungTotalEdit(input) {
    const row = input.closest('.barang-item-edit');
    const qtyInput = row.querySelector('.qty-input-edit');
    const hargaSatuanInput = row.querySelector('.harga-satuan-input-edit');
    const totalInput = row.querySelector('.harga-total-input-edit');

    if (qtyInput && hargaSatuanInput && totalInput) {
        const qty = parseFloat(qtyInput.value) || 0;
        const hargaSatuan = parseIndonesianNumber(hargaSatuanInput.value);
        
        const total = qty * hargaSatuan;

        // Format total with Indonesian format
        totalInput.value = formatNumberToIndonesian(total);
        
        hitungTotalKeseluruhanEdit();
    }
}

// Function to format number with dots for thousand separator and comma for decimal (Indonesian format) for edit
function formatHargaSatuanEdit(input) {
    // Get the cursor position before formatting
    let cursorPosition = input.selectionStart;
    let oldValue = input.value;

    // Remove all characters except digits and comma (,) for decimal
    let value = input.value.replace(/[^\d,]/g, '');

    // Handle multiple decimal commas - keep only the first one
    let parts = value.split(',');
    if (parts.length > 2) {
        value = parts[0] + ',' + parts.slice(1).join('');
    }

    // Split into integer and decimal parts
    let [integerPart, decimalPart] = value.split(',');

    // Format integer part with thousand separators (dots)
    if (integerPart) {
        // Add dots every 3 digits for thousand separators
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Reconstruct the value
    if (decimalPart !== undefined) {
        // Allow unlimited decimal places
        value = integerPart + ',' + decimalPart;
    } else {
        value = integerPart || '';
    }

    // Update input value
    input.value = value;

    // Adjust cursor position after formatting
    let dotsBeforeCursor = (oldValue.substring(0, cursorPosition).match(/\./g) || []).length;
    let dotsAfterCursor = (value.substring(0, cursorPosition).match(/\./g) || []).length;
    let newCursorPosition = cursorPosition + (dotsAfterCursor - dotsBeforeCursor);

    // Set cursor position
    setTimeout(() => {
        if (newCursorPosition >= 0 && newCursorPosition <= value.length) {
            input.setSelectionRange(newCursorPosition, newCursorPosition);
        }
    }, 0);
}

// Function to format rupiah for display (supports decimals) for edit
function formatRupiahNumberEdit(angka) {
    // Check if number has decimal places
    if (angka % 1 !== 0) {
        // Has decimal places - format with up to 5 decimal places
        return angka.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 5
        });
    } else {
        // Whole number - format without decimal places
        return angka.toLocaleString('id-ID');
    }
}

function hitungTotalKeseluruhanEdit() {
    let total = 0;
    document.querySelectorAll('.barang-item-edit').forEach(item => {
        const qtyInput = item.querySelector('.qty-input-edit');
        const hargaSatuanInput = item.querySelector('.harga-satuan-input-edit');

        if (qtyInput && hargaSatuanInput) {
            const qty = parseFloat(qtyInput.value) || 0;
            const hargaSatuan = parseIndonesianNumber(hargaSatuanInput.value);
            
            total += qty * hargaSatuan;
        }
    });

    const totalElement = document.getElementById('totalKeseluruhanEdit');
    if (totalElement) {
        totalElement.textContent = formatRupiahEdit(total);
    }
}

// Function to format rupiah with decimals for edit
function formatRupiahEdit(angka) {
    // Check if number has decimal places
    if (angka % 1 !== 0) {
        // Has decimal places - format with up to 5 decimal places
        return 'Rp ' + angka.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 5
        });
    } else {
        // Whole number - format without decimal places
        return 'Rp ' + angka.toLocaleString('id-ID');
    }
}

// Function to format rupiah (keep existing for compatibility)
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Function to load current files information
function loadCurrentFiles(data) {
    console.log('Loading current files for project:', data);

    // Initialize loading state
    updateFileDisplay('currentSuratPenawaran', 'downloadSuratPenawaran', null);
    updateFileDisplay('currentSuratPersetujuan', 'downloadSuratPesanan', null);
    document.getElementById('currentSuratPenawaran').textContent = 'Loading...';
    document.getElementById('currentSuratPersetujuan').textContent = 'Loading...';

    // Load documents from penawaran data if available
    if (data.penawaran && data.penawaran.length > 0) {
        const penawaran = data.penawaran[0]; // Get the first/latest penawaran
        
        // Show penawaran status info
        const statusInfo = document.getElementById('penawaranStatusInfo');
        const statusSpan = document.getElementById('penawaranStatus');
        if (statusInfo && statusSpan && penawaran.status) {
            statusSpan.textContent = penawaran.status;
            statusInfo.classList.remove('hidden');
        }

        // Load surat penawaran
        if (penawaran.surat_penawaran) {
            updateFileDisplay('currentSuratPenawaran', 'downloadSuratPenawaran', penawaran.surat_penawaran);
        } else {
            document.getElementById('currentSuratPenawaran').textContent = 'Belum ada file';
        }

        // Load surat persetujuan (surat pesanan)
        if (penawaran.surat_pesanan) {
            updateFileDisplay('currentSuratPersetujuan', 'downloadSuratPesanan', penawaran.surat_pesanan);
        } else {
            document.getElementById('currentSuratPersetujuan').textContent = 'Belum ada file';
        }
    } else {
        // No penawaran data - show no files
        document.getElementById('currentSuratPenawaran').textContent = 'Belum ada file';
        document.getElementById('currentSuratPersetujuan').textContent = 'Belum ada file';
        
        // Hide penawaran status info
        const statusInfo = document.getElementById('penawaranStatusInfo');
        if (statusInfo) {
            statusInfo.classList.add('hidden');
        }
    }
}

// Helper function to update file display
function updateFileDisplay(textElementId, linkElementId, filename) {
    const textElement = document.getElementById(textElementId);
    const linkElement = document.getElementById(linkElementId);
    
    if (filename) {
        textElement.textContent = filename;
        if (linkElement) {
            linkElement.href = `/storage/documents/${filename}`;
            linkElement.classList.remove('hidden');
        }
    } else {
        textElement.textContent = 'Belum ada file';
        if (linkElement) {
            linkElement.classList.add('hidden');
        }
    }
}

// Function to fetch penawaran data from server
async function fetchPenawaranData(proyekId) {
    try {
        const response = await fetch(`/api/proyek/${proyekId}/penawaran`);
        if (response.ok) {
            const data = await response.json();
            return data;
        }
    } catch (error) {
        console.error('Error fetching penawaran data:', error);
    }
    return null;
}

// File upload functions
function toggleFileUploadEdit(checkbox, itemIndex) {
    const fileUploadArea = document.getElementById(`fileUploadAreaEdit_${itemIndex}`);
    if (checkbox.checked) {
        fileUploadArea.classList.remove('hidden');
    } else {
        fileUploadArea.classList.add('hidden');
        // Clear file input
        const fileInput = document.getElementById(`fileInputEdit_${itemIndex}`);
        if (fileInput) {
            fileInput.value = '';
        }
        // Hide file preview
        const filePreview = document.getElementById(`filePreviewEdit_${itemIndex}`);
        if (filePreview) {
            filePreview.classList.add('hidden');
        }
    }
}

function handleFileSelectEdit(input, itemIndex) {
    const filePreview = document.getElementById(`filePreviewEdit_${itemIndex}`);
    const fileList = document.getElementById(`fileListEdit_${itemIndex}`);
    
    if (input.files.length > 0) {
        filePreview.classList.remove('hidden');
        fileList.innerHTML = '';
        
        Array.from(input.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between bg-white p-2 rounded border text-sm';
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-file text-gray-500 mr-2"></i>
                    <span>${file.name}</span>
                    <span class="text-gray-400 ml-2">(${(file.size / 1024).toFixed(1)} KB)</span>
                </div>
                <button type="button" onclick="removeFileEdit(${itemIndex}, ${index})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileList.appendChild(fileItem);
        });
    } else {
        filePreview.classList.add('hidden');
    }
}

function removeFileEdit(itemIndex, fileIndex) {
    const fileInput = document.getElementById(`fileInputEdit_${itemIndex}`);
    if (fileInput) {
        // Create new FileList without the removed file
        const dt = new DataTransfer();
        Array.from(fileInput.files).forEach((file, index) => {
            if (index !== fileIndex) {
                dt.items.add(file);
            }
        });
        fileInput.files = dt.files;
        
        // Refresh file display
        handleFileSelectEdit(fileInput, itemIndex);
    }
}

function clearFile(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.value = '';
        
        // Hide preview if exists
        const previewId = inputId + 'Preview';
        const preview = document.getElementById(previewId);
        if (preview) {
            preview.classList.add('hidden');
        }
    }
}

function downloadFile(filename) {
    if (filename && filename !== 'Belum ada file') {
        window.open(`/storage/documents/${filename}`, '_blank');
    }
}
</script>
