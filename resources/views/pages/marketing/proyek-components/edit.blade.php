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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                            <input type="date" id="editDeadline" name="deadline" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
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
                                <option value="penawaran">Penawaran</option>
                                <option value="persetujuan">Persetujuan</option>
                                <option value="kontrak">Kontrak</option>
                                <option value="selesai">Selesai</option>
                                <option value="proses">Proses</option>
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
                                <option value="Sari Wijaya">Sari Wijaya</option>
                                <option value="Maya Indah">Maya Indah</option>
                                <option value="Roni Hidayat">Roni Hidayat</option>
                                <option value="Lisa Permata">Lisa Permata</option>
                                <option value="Nina Kartika">Nina Kartika</option>
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
    // Load basic information with null checks
    const setElementValue = (id, value) => {
        const element = document.getElementById(id);
        if (element) {
            element.value = value || '';
        }
    };

    setElementValue('editId', data.id);
    setElementValue('editIdProyek', data.kode);
    setElementValue('editNamaProyek', data.nama_proyek);
    setElementValue('editKabupatenKota', data.kabupaten_kota);
    setElementValue('editNamaInstansi', data.nama_instansi);
    setElementValue('editJenisPengadaan', data.jenis_pengadaan);
    setElementValue('editAdminPurchasing', data.admin_purchasing);
    setElementValue('editTanggal', data.tanggal);
    setElementValue('editDeadline', data.deadline);
    setElementValue('editCatatan', data.catatan);
    setElementValue('editTahunPotensi', data.tahun_potensi);
    setElementValue('editStatus', data.status);

    // Load admin marketing
    setElementValue('editAdminMarketing', data.admin_marketing);

    // Load potensi
    if (data.potensi) {
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
        if (items.length > 0) {
            items.forEach((item, index) => {
                addEditItem(item);
            });
        } else {
            addEditItem();
        }

        updateEditDeleteButtons();
        hitungTotalKeseluruhanEdit();
    }
}
}

// Make function available globally
window.loadEditData = loadEditData;

function addEditItem(itemData = null) {
    const container = document.getElementById('daftarBarangEdit');
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
                    <input type="text" name="barang[${editItemCounter}][nama]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Nama barang" value="${itemData ? itemData.nama : ''}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                    <input type="number" name="barang[${editItemCounter}][qty]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm qty-input-edit" placeholder="0" min="1" value="${itemData ? itemData.qty : ''}" onchange="hitungTotalEdit(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <select name="barang[${editItemCounter}][satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                        <option value="">Pilih satuan</option>
                        <option value="pcs" ${itemData && itemData.satuan === 'pcs' ? 'selected' : ''}>Pcs</option>
                        <option value="unit" ${itemData && itemData.satuan === 'unit' ? 'selected' : ''}>Unit</option>
                        <option value="set" ${itemData && itemData.satuan === 'set' ? 'selected' : ''}>Set</option>
                        <option value="buah" ${itemData && itemData.satuan === 'buah' ? 'selected' : ''}>Buah</option>
                        <option value="kg" ${itemData && itemData.satuan === 'kg' ? 'selected' : ''}>Kg</option>
                        <option value="meter" ${itemData && itemData.satuan === 'meter' ? 'selected' : ''}>Meter</option>
                        <option value="liter" ${itemData && itemData.satuan === 'liter' ? 'selected' : ''}>Liter</option>
                        <option value="paket" ${itemData && itemData.satuan === 'paket' ? 'selected' : ''}>Paket</option>
                        <option value="sistem" ${itemData && itemData.satuan === 'sistem' ? 'selected' : ''}>Sistem</option>
                        <option value="layanan" ${itemData && itemData.satuan === 'layanan' ? 'selected' : ''}>Layanan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan</label>
                    <input type="number" name="barang[${editItemCounter}][harga_satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm harga-satuan-input-edit" placeholder="0" min="0" value="${itemData ? itemData.harga_satuan : ''}" onchange="hitungTotalEdit(this)">
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
    if (itemData && itemData.qty && itemData.harga_satuan) {
        const newItem = container.lastElementChild;
        const total = itemData.qty * itemData.harga_satuan;
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

// Form submission
document.getElementById('formEditProyek').addEventListener('submit', function(e) {
    e.preventDefault();

    // Simulate form submission
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;

    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';
    submitButton.disabled = true;

    setTimeout(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        closeModal('modalEditProyek');

        // Show success message
        alert('Proyek berhasil diupdate!');
    }, 2000);
});
</script>
