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
                            <input type="text" id="editIdProyek" name="id_proyek" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan ID proyek">
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Proyek</label>
                            <input type="text" id="editNamaProyek" name="nama_proyek" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama proyek">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="editStatus" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih status</option>
                                <option value="menunggu">Menunggu</option>
                                <option value="penawaran">Penawaran</option>
                                <option value="pembayaran">Pembayaran</option>
                                <option value="pengiriman">Pengiriman</option>
                                <option value="selesai">Selesai</option>
                                <option value="gagal">Gagal</option>
                            </select>
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
                            <small class="text-gray-500 text-xs mt-1">Otomatis diisi dengan nama user yang login</small>
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
                                <button type="button" id="editPotensiYa" onclick="togglePotensiEdit('ya')" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-center hover:bg-gray-50 transition-colors duration-200 potensi-btn-edit">
                                    <i class="fas fa-thumbs-up mr-2"></i>Ya
                                </button>
                                <button type="button" id="editPotensiTidak" onclick="togglePotensiEdit('tidak')" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-center hover:bg-gray-50 transition-colors duration-200 potensi-btn-edit">
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

                        <!-- Surat Kontrak -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surat Kontrak</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" id="editSuratKontrak" name="surat_kontrak"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx">
                                <button type="button" onclick="clearFile('editSuratKontrak')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="editSuratKontrakPreview" class="mt-2 text-sm text-gray-600 hidden">
                                <i class="fas fa-file-pdf mr-1"></i>
                                <span class="filename">No file selected</span>
                            </div>
                        </div>

                        <!-- Surat Selesai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surat Selesai</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" id="editSuratSelesai" name="surat_selesai"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx">
                                <button type="button" onclick="clearFile('editSuratSelesai')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="editSuratSelesaiPreview" class="mt-2 text-sm text-gray-600 hidden">
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
                                <span id="currentSuratPenawaran" class="text-gray-600 font-mono text-xs">-</span>
                            </div>
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-purple-500 mr-2"></i>
                                    <span>Surat Persetujuan:</span>
                                </div>
                                <span id="currentSuratPersetujuan" class="text-gray-600 font-mono text-xs">-</span>
                            </div>
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-orange-500 mr-2"></i>
                                    <span>Surat Kontrak:</span>
                                </div>
                                <span id="currentSuratKontrak" class="text-gray-600 font-mono text-xs">-</span>
                            </div>
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-green-500 mr-2"></i>
                                    <span>Surat Selesai:</span>
                                </div>
                                <span id="currentSuratSelesai" class="text-gray-600 font-mono text-xs">-</span>
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
    setElementValue('editNamaProyek', data.nama_proyek);
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

    if (itemData) {
        nama = itemData.nama || itemData.nama_barang || '';
        qty = itemData.qty || itemData.jumlah || '';
        satuan = itemData.satuan || '';
        hargaSatuan = itemData.harga_satuan || '';
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
    const setCurrentFile = (elementId, filename) => {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = filename || 'Tidak ada file';
        }
    };

    setCurrentFile('currentSuratPenawaran', data.surat_penawaran);
    setCurrentFile('currentSuratPersetujuan', data.surat_persetujuan);
    setCurrentFile('currentSuratKontrak', data.surat_kontrak);
    setCurrentFile('currentSuratSelesai', data.surat_selesai);
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
    const fileInputs = ['editSuratPenawaran', 'editSuratPersetujuan', 'editSuratKontrak', 'editSuratSelesai'];

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
    data.status = getElementValue('editStatus', 'menunggu');
    data.potensi = getElementValue('editPotensiValue', 'tidak');
    data.tahun_potensi = parseInt(getElementValue('editTahunPotensi')) || new Date().getFullYear();

    // Data nama proyek dan klien
    data.nama_barang = getElementValue('editNamaProyek');
    data.nama_klien = getElementValue('editNamaKlien', 'Klien');
    data.kontak_klien = getElementValue('editKontakKlien');

    // Admin data
    const adminPurchasingSelect = document.getElementById('editAdminPurchasing');
    data.id_admin_purchasing = adminPurchasingSelect ? adminPurchasingSelect.value : null;
    data.id_admin_marketing = 1; // Ambil dari session user yang login

    // Ambil data barang dari form
    const barangItems = document.querySelectorAll('.barang-item-edit');
    if (barangItems.length > 0) {
        const firstItem = barangItems[0];
        const namaInput = firstItem.querySelector('input[name*="[nama]"]');
        const qtyInput = firstItem.querySelector('input[name*="[qty]"]');
        const satuanSelect = firstItem.querySelector('select[name*="[satuan]"]');
        const hargaSatuanInput = firstItem.querySelector('input[name*="[harga_satuan]"]');

        if (namaInput) data.nama_barang = namaInput.value || data.nama_barang;
        if (qtyInput) data.jumlah = parseInt(qtyInput.value) || 1;
        if (satuanSelect) data.satuan = satuanSelect.value || 'Unit';
        if (hargaSatuanInput) {
            data.harga_satuan = parseFloat(hargaSatuanInput.value) || null;
            if (data.harga_satuan && data.jumlah) {
                data.harga_total = data.harga_satuan * data.jumlah;
            }
        }
    } else {
        // Default values jika tidak ada barang
        data.jumlah = 1;
        data.satuan = 'Unit';
        data.spesifikasi = 'Spesifikasi standar';
        data.harga_satuan = null;
    }

    // Spesifikasi default
    data.spesifikasi = getElementValue('editSpesifikasi', 'Spesifikasi standar');

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
</script>
