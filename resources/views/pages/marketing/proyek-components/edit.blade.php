<!-- Modal Edit Proyek -->
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
                            <input type="text" id="editKabupatenKota" name="kabupaten_kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan kabupaten/kota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi</label>
                            <input type="text" id="editNamaInstansi" name="nama_instansi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama instansi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <select id="editJenisPengadaan" name="jenis_pengadaan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih jenis pengadaan</option>
                                <option value="Pelelangan Umum">Pelelangan Umum</option>
                                <option value="Pelelangan Terbatas">Pelelangan Terbatas</option>
                                <option value="Pemilihan Langsung">Pemilihan Langsung</option>
                                <option value="Penunjukan Langsung">Penunjukan Langsung</option>
                                <option value="Tender">Tender</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Marketing</label>
                            <input type="text" id="editAdminMarketing" name="admin_marketing" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" value="[Nama User Login]" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Purchasing</label>
                            <select id="editAdminPurchasing" name="admin_purchasing" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih admin purchasing</option>
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

    // Set admin marketing (readonly field)
    setElementValue('editAdminMarketing', data.admin_marketing);

    // Set admin purchasing dengan ID
    const adminPurchasingSelect = document.getElementById('editAdminPurchasing');
    if (adminPurchasingSelect && data.id_admin_purchasing) {
        // Wait for options to load then set value
        setTimeout(() => {
            adminPurchasingSelect.value = data.id_admin_purchasing;
            console.log('Set admin purchasing to:', data.id_admin_purchasing);
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

    if (itemData) {
        nama = itemData.nama || itemData.nama_barang || '';
        qty = itemData.qty || itemData.jumlah || '';
        satuan = itemData.satuan || '';
        hargaSatuan = itemData.harga_satuan || '';
        spesifikasi = itemData.spesifikasi || '';
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan</label>
                    <input type="number" name="barang[${editItemCounter}][harga_satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm harga-satuan-input-edit" placeholder="0" min="0" value="${hargaSatuan}" onchange="hitungTotalEdit(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Total</label>
                    <input type="number" name="barang[${editItemCounter}][harga_total]" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 text-sm harga-total-input-edit" placeholder="0" readonly>
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi</label>
                <textarea name="barang[${editItemCounter}][spesifikasi]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Masukkan spesifikasi barang...">${spesifikasi}</textarea>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', itemHtml);
    editItemCounter++;

    // Calculate total for this item if data provided
    if (itemData && qty && hargaSatuan) {
        const newItem = container.lastElementChild;
        const total = parseFloat(qty) * parseFloat(hargaSatuan);
        newItem.querySelector('.harga-total-input-edit').value = total;
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
        item.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
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

function hitungTotalEdit(input) {
    const row = input.closest('.barang-item-edit');
    const qtyInput = row.querySelector('.qty-input-edit');
    const hargaSatuanInput = row.querySelector('.harga-satuan-input-edit');
    const totalInput = row.querySelector('.harga-total-input-edit');

    if (qtyInput && hargaSatuanInput && totalInput) {
        const qty = parseFloat(qtyInput.value) || 0;
        const hargaSatuan = parseFloat(hargaSatuanInput.value) || 0;
        const total = qty * hargaSatuan;

        totalInput.value = total;
        hitungTotalKeseluruhanEdit();
    }
}

function hitungTotalKeseluruhanEdit() {
    let total = 0;
    document.querySelectorAll('.barang-item-edit').forEach(item => {
        const qtyInput = item.querySelector('.qty-input-edit');
        const hargaSatuanInput = item.querySelector('.harga-satuan-input-edit');

        if (qtyInput && hargaSatuanInput) {
            const qty = parseFloat(qtyInput.value) || 0;
            const hargaSatuan = parseFloat(hargaSatuanInput.value) || 0;
            total += qty * hargaSatuan;
        }
    });

    const totalElement = document.getElementById('totalKeseluruhanEdit');
    if (totalElement) {
        totalElement.textContent = formatRupiah(total);
    }
}

// Function to format rupiah
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
    if (data.penawaran && data.penawaran.surat_penawaran) {
        console.log('Loading from provided penawaran data:', data.penawaran);
        updateFileDisplay('currentSuratPenawaran', 'downloadSuratPenawaran',
                        data.penawaran.surat_penawaran, 'penawaran');
        updateFileDisplay('currentSuratPersetujuan', 'downloadSuratPesanan',
                        data.penawaran.surat_pesanan, 'pesanan');
        updatePenawaranStatus(data.penawaran.status);
    } else {
        // Fetch penawaran data for this project
        console.log('No penawaran data provided, fetching from API for project ID:', data.id);
        fetchPenawaranData(data.id);
    }
}

// Function to fetch penawaran data from server
async function fetchPenawaranData(proyekId) {
    try {
        console.log('Fetching penawaran data for project:', proyekId);
        const url = `/marketing/penawaran/project/${proyekId}/data`;
        console.log('Fetching from URL:', url);

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        console.log('API Response status:', response.status);
        console.log('API Response headers:', response.headers);

        if (response.ok) {
            const result = await response.json();
            console.log('API Response data:', result);

            if (result.success && result.data) {
                const penawaran = result.data;
                console.log('Penawaran data loaded successfully:', penawaran);

                // Update current files display with download links
                updateFileDisplay('currentSuratPenawaran', 'downloadSuratPenawaran',
                                penawaran.surat_penawaran, 'penawaran');
                updateFileDisplay('currentSuratPersetujuan', 'downloadSuratPesanan',
                                penawaran.surat_pesanan, 'pesanan');

                // Show penawaran status
                updatePenawaranStatus(penawaran.status);
            } else {
                console.log('API returned unsuccessful response:', result);
                // Set default empty values
                updateFileDisplay('currentSuratPenawaran', 'downloadSuratPenawaran', null);
                updateFileDisplay('currentSuratPersetujuan', 'downloadSuratPesanan', null);
                hidePenawaranStatus();
            }
        } else {
            console.log('API request failed with status:', response.status);
            const errorText = await response.text();
            console.log('Error response:', errorText);

            // Set default empty values
            updateFileDisplay('currentSuratPenawaran', 'downloadSuratPenawaran', null);
            updateFileDisplay('currentSuratPersetujuan', 'downloadSuratPesanan', null);
            hidePenawaranStatus();
        }
    } catch (error) {
        console.error('Error fetching penawaran data:', error);
        // Set default empty values on error
        updateFileDisplay('currentSuratPenawaran', 'downloadSuratPenawaran', null);
        updateFileDisplay('currentSuratPersetujuan', 'downloadSuratPesanan', null);
        hidePenawaranStatus();
    }
}

// Function to update penawaran status display
function updatePenawaranStatus(status) {
    const statusInfoDiv = document.getElementById('penawaranStatusInfo');
    const statusSpan = document.getElementById('penawaranStatus');

    if (statusInfoDiv && statusSpan && status) {
        let statusText = status;
        let statusClass = 'text-blue-700';

        switch(status) {
            case 'ACC':
                statusText = 'Disetujui';
                statusClass = 'text-green-700';
                break;
            case 'Menunggu':
                statusText = 'Menunggu';
                statusClass = 'text-yellow-700';
                break;
            case 'Ditolak':
                statusText = 'Ditolak';
                statusClass = 'text-red-700';
                break;
        }

        statusSpan.textContent = statusText;
        statusSpan.className = `text-sm font-semibold ${statusClass}`;
        statusInfoDiv.classList.remove('hidden');
    }
}

// Function to hide penawaran status
function hidePenawaranStatus() {
    const statusInfoDiv = document.getElementById('penawaranStatusInfo');
    if (statusInfoDiv) {
        statusInfoDiv.classList.add('hidden');
    }
}

// Helper function to update file display with download links
function updateFileDisplay(textElementId, linkElementId, filename, type = null) {
    const textElement = document.getElementById(textElementId);
    const linkElement = document.getElementById(linkElementId);

    console.log(`Updating file display: ${textElementId} = ${filename}, type = ${type}`);

    if (textElement) {
        if (filename && filename !== 'null' && filename !== '' && filename !== null && filename !== undefined) {
            textElement.textContent = filename;
            textElement.classList.remove('text-gray-400');
            textElement.classList.add('text-gray-600');

            // Show download link if file exists and type is provided
            if (linkElement && type) {
                const downloadUrl = `/marketing/penawaran/download/${type}/${filename}`;
                linkElement.href = downloadUrl;
                linkElement.classList.remove('hidden');
                console.log(`Download link set: ${downloadUrl}`);
            }
        } else {
            textElement.textContent = 'Tidak ada file';
            textElement.classList.remove('text-gray-600');
            textElement.classList.add('text-gray-400');

            // Hide download link
            if (linkElement) {
                linkElement.classList.add('hidden');
                linkElement.href = '#';
            }
        }
    } else {
        console.warn(`Element ${textElementId} not found`);
    }
}

// Function to clear file input
function clearFile(inputId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(inputId + 'Preview');

    if (input) {
        input.value = '';
    }
    if (preview) {
        preview.classList.add('hidden');
    }
}

// File upload preview handlers
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = ['editSuratPenawaran', 'editSuratPersetujuan'];

    fileInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(inputId + 'Preview');

        if (input && preview) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const filenameSpan = preview.querySelector('.filename');

                if (file) {
                    filenameSpan.textContent = file.name;
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }
            });
        }
    });
});

// Form submission - moved to DOMContentLoaded
function initializeEditFormSubmission() {
    const editForm = document.getElementById('formEditProyek');
    if (!editForm) {
        console.error('Edit form not found');
        return;
    }

    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submit triggered');

        // Validasi form
        if (!validateEditForm()) {
            console.log('Form validation failed');
            return;
        }

        // Ambil ID proyek yang sedang diedit
        const proyekId = document.getElementById('editId').value;
        if (!proyekId) {
            console.error('Proyek ID not found');
            alert('ID Proyek tidak ditemukan!');
            return;
        }

        console.log('Collecting form data...');
        // Kumpulkan data form
        const formData = collectEditFormData();
        console.log('Form data collected:', formData);

        // Submit data
        const submitButton = document.querySelector('button[form="formEditProyek"]') ||
                           e.target.querySelector('button[type="submit"]') ||
                           document.querySelector('#modalEditProyek button[type="submit"]');

        if (!submitButton) {
            console.error('Submit button not found');
            alert('Terjadi kesalahan: Submit button tidak ditemukan');
            return;
        }

        const originalText = submitButton.innerHTML;

        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';
        submitButton.disabled = true;

        // Kirim data ke server
        fetch(`/marketing/proyek/${proyekId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal('modalEditProyek');

                // Show success message
                if (typeof showSuccessModal === 'function') {
                    showSuccessModal('Proyek berhasil diupdate!');
                } else {
                    alert('Proyek berhasil diupdate!');
                }

                // Reload page untuk update data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat mengupdate data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        })
        .finally(() => {
            if (submitButton) {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });
    });
}

// Fungsi validasi form edit
function validateEditForm() {
    const requiredFields = [
        { id: 'editTanggal', label: 'Tanggal' },
        { id: 'editKabupatenKota', label: 'Kabupaten/Kota' },
        { id: 'editNamaInstansi', label: 'Nama Instansi' },
        { id: 'editJenisPengadaan', label: 'Jenis Pengadaan' },
        { id: 'editAdminPurchasing', label: 'Admin Purchasing' }
    ];

    for (let field of requiredFields) {
        const input = document.getElementById(field.id);
        if (!input || !input.value.trim()) {
            alert(`${field.label} harus diisi!`);
            if (input) input.focus();
            return false;
        }
    }

    return true;
}

// Fungsi untuk mengumpulkan data form edit
function collectEditFormData() {
    const data = {};

    // Helper function to safely get element value
    const getElementValue = (id, defaultValue = '') => {
        const element = document.getElementById(id);
        return element ? element.value : defaultValue;
    };

    // Data dasar
    data.tanggal = getElementValue('editTanggal');
    data.kab_kota = getElementValue('editKabupatenKota');
    data.instansi = getElementValue('editNamaInstansi');
    data.jenis_pengadaan = getElementValue('editJenisPengadaan');
    data.catatan = getElementValue('editCatatan');
    data.potensi = getElementValue('editPotensiValue', 'tidak');
    data.tahun_potensi = parseInt(getElementValue('editTahunPotensi')) || new Date().getFullYear();

    // Admin data
    const adminPurchasingSelect = document.getElementById('editAdminPurchasing');
    data.id_admin_purchasing = adminPurchasingSelect ? adminPurchasingSelect.value : null;
    data.id_admin_marketing = 1; // Ambil dari session user yang login

    // Ambil data barang dari form
    const barangItems = document.querySelectorAll('.barang-item-edit');
    console.log('Found barang items for edit:', barangItems.length);

    if (barangItems.length > 0) {
        // Multiple barang - gunakan format daftar_barang array
        data.daftar_barang = [];

        barangItems.forEach((item, index) => {
            const namaInput = item.querySelector('input[name*="[nama]"]');
            const qtyInput = item.querySelector('input[name*="[qty]"]');
            const satuanSelect = item.querySelector('select[name*="[satuan]"]');
            const hargaSatuanInput = item.querySelector('input[name*="[harga_satuan]"]');
            const spesifikasiTextarea = item.querySelector('textarea[name*="[spesifikasi]"]');

            const barangData = {
                nama_barang: namaInput ? namaInput.value : '',
                jumlah: qtyInput ? parseInt(qtyInput.value) || 1 : 1,
                satuan: satuanSelect ? satuanSelect.value || 'Unit' : 'Unit',
                spesifikasi: spesifikasiTextarea ? spesifikasiTextarea.value || 'Spesifikasi standar' : 'Spesifikasi standar',
                harga_satuan: hargaSatuanInput ? parseFloat(hargaSatuanInput.value) || null : null
            };

            if (barangData.nama_barang) {
                data.daftar_barang.push(barangData);
                console.log(`Barang edit ${index + 1}:`, barangData);
            }
        });

        // Jika tidak ada barang valid yang ditemukan, fallback ke single barang
        if (data.daftar_barang.length === 0) {
            console.log('No valid barang found in items, using fallback data');
            data.nama_barang = getElementValue('editNamaProyek') || 'Barang Default';
            data.jumlah = 1;
            data.satuan = 'Unit';
            data.spesifikasi = 'Spesifikasi standar';
            data.harga_satuan = null;
        }
    } else {
        // Single barang - gunakan format lama untuk backward compatibility
        console.log('No barang items found, using single barang format');
        data.nama_barang = getElementValue('editNamaProyek') || 'Barang Default';
        data.jumlah = 1;
        data.satuan = 'Unit';
        data.spesifikasi = getElementValue('editSpesifikasi', 'Spesifikasi standar');
        data.harga_satuan = null;
    }

    console.log('Collected edit form data:', data);
    return data;
}

// Function to load admin purchasing options for edit
async function loadEditAdminPurchasingOptions() {
    try {
        const response = await fetch('/marketing/proyek/users');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('editAdminPurchasing');
            if (select) {
                // Store current value
                const currentValue = select.value;

                // Clear existing options except the first one
                select.innerHTML = '<option value="">Pilih admin purchasing</option>';

                // Add options for purchasing and admin roles
                data.data.forEach(user => {
                    if (user.role === 'admin_purchasing' || user.role === 'superadmin') {
                        const option = document.createElement('option');
                        option.value = user.id_user;
                        option.textContent = user.nama;
                        select.appendChild(option);
                    }
                });

                // Restore current value if it exists
                if (currentValue) {
                    select.value = currentValue;
                }
            }
        }
    } catch (error) {
        console.error('Error loading admin purchasing options for edit:', error);
    }
}

// Initialize edit modal
document.addEventListener('DOMContentLoaded', function() {
    // Load admin purchasing options
    loadEditAdminPurchasingOptions();

    // Initialize form submission
    initializeEditFormSubmission();

    // Ensure form event listener is attached
    const editForm = document.getElementById('formEditProyek');
    if (editForm) {
        console.log('Edit form found and initialized');
    } else {
        console.error('Edit form not found during initialization');
    }

    console.log('Edit modal initialized');
});

// Debug function to test API
window.testPenawaranAPI = function(proyekId) {
    console.log('Testing penawaran API for project:', proyekId);
    fetchPenawaranData(proyekId);
};
</script>
