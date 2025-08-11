<!-- Modal Edit Penawaran -->
<div id="modalEditPenawaran" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Edit Penawaran</h3>
                    <p class="text-red-100 text-sm">Ubah data penawaran proyek</p>
                </div>
            </div>
            <button onclick="closeModal('modalEditPenawaran')" class="text-white hover:bg-white hover:text-red-800 p-2">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formEditPenawaran" class="space-y-6">
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Penawaran</label>
                            <input type="text" id="editKode" name="kode" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" readonly>
                            <small class="text-gray-500 text-xs mt-1">Kode tidak dapat diubah</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <input type="text" id="editKabupatenKota" name="kabupaten_kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan kabupaten/kota" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi</label>
                            <input type="text" id="editNamaInstansi" name="nama_instansi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama instansi" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <select id="editJenisPengadaan" name="jenis_pengadaan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Pilih jenis pengadaan</option>
                                <option value="Pelelangan Umum">Pelelangan Umum</option>
                                <option value="Pelelangan Terbatas">Pelelangan Terbatas</option>
                                <option value="Pemilihan Langsung">Pemilihan Langsung</option>
                                <option value="Penunjukan Langsung">Penunjukan Langsung</option>
                                <option value="Tender">Tender</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Purchasing</label>
                            <select id="editAdminPurchasing" name="admin_purchasing" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Pilih admin purchasing</option>
                                <option value="Sari Wijaya">Sari Wijaya</option>
                                <option value="Maya Indah">Maya Indah</option>
                                <option value="Roni Hidayat">Roni Hidayat</option>
                                <option value="Lisa Permata">Lisa Permata</option>
                                <option value="Nina Kartika">Nina Kartika</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                            <input type="date" id="editDeadline" name="deadline" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        </div>
                    </div>
                    
                    <!-- Upload Surat Pesanan -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-upload text-red-600 mr-1"></i>
                            Surat Pesanan <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors duration-200">
                            <input type="file" name="surat_pesanan" id="editSuratPesanan" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="handleFileUploadEdit(this)">
                            <label for="editSuratPesanan" class="cursor-pointer">
                                <div id="editUploadArea">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 font-medium">Klik untuk upload surat pesanan baru</p>
                                    <p class="text-sm text-gray-500 mt-1">PDF, Word, atau Image (Max: 2MB)</p>
                                </div>
                                <div id="editFilePreview" class="hidden">
                                    <i class="fas fa-file-alt text-4xl text-green-500 mb-3"></i>
                                    <p class="text-green-600 font-medium" id="editFileName"></p>
                                    <p class="text-sm text-gray-500" id="editFileSize"></p>
                                    <button type="button" onclick="removeFileEdit()" class="mt-2 text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash mr-1"></i>Hapus File
                                    </button>
                                </div>
                            </label>
                        </div>
                        <div id="currentFile" class="mt-3 hidden">
                            <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file-alt text-blue-600"></i>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">File saat ini:</p>
                                        <p class="text-sm text-blue-600" id="currentFileName">-</p>
                                    </div>
                                </div>
                                <button type="button" onclick="viewCurrentFile()" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-boxes text-red-600 mr-2"></i>
                            Daftar Barang/Jasa
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
                    <div class="mt-6 bg-white border-2 border-red-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-gray-800">Total Keseluruhan</span>
                            <span id="totalKeseluruhanEdit" class="text-2xl font-bold text-red-600">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-red-600 mr-2"></i>
                        Catatan
                    </h4>
                    <textarea id="editCatatan" name="catatan" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan catatan tambahan (opsional)"></textarea>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalEditPenawaran')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Batal
            </button>
            <button type="submit" form="formEditPenawaran" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Update Penawaran
            </button>
        </div>
    </div>
</div>

<script>
let editItemCounter = 0;

function loadEditData(data) {
    // Load basic information
    document.getElementById('editId').value = data.id;
    document.getElementById('editKode').value = data.kode;
    document.getElementById('editKabupatenKota').value = data.kabupaten_kota;
    document.getElementById('editNamaInstansi').value = data.nama_instansi;
    document.getElementById('editJenisPengadaan').value = data.jenis_pengadaan;
    document.getElementById('editAdminPurchasing').value = data.admin_purchasing;
    document.getElementById('editDeadline').value = data.deadline;
    document.getElementById('editCatatan').value = data.catatan || '';
    
    // Load items
    const container = document.getElementById('daftarBarangEdit');
    container.innerHTML = '';
    editItemCounter = 0;
    
    if (data.items && data.items.length > 0) {
        data.items.forEach((item, index) => {
            addEditItem(item);
        });
    } else {
        addEditItem();
    }
    
    updateEditDeleteButtons();
    hitungTotalKeseluruhanEdit();
}

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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" name="barang[${editItemCounter}][nama]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm" placeholder="Nama barang/jasa" value="${itemData ? itemData.nama : ''}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="barang[${editItemCounter}][jumlah]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm jumlah-input-edit" placeholder="0" min="1" value="${itemData ? itemData.jumlah : ''}" required onchange="hitungTotalEdit(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <select name="barang[${editItemCounter}][satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm" required>
                        <option value="">Pilih satuan</option>
                        <option value="Unit" ${itemData && itemData.satuan === 'Unit' ? 'selected' : ''}>Unit</option>
                        <option value="Set" ${itemData && itemData.satuan === 'Set' ? 'selected' : ''}>Set</option>
                        <option value="Buah" ${itemData && itemData.satuan === 'Buah' ? 'selected' : ''}>Buah</option>
                        <option value="Paket" ${itemData && itemData.satuan === 'Paket' ? 'selected' : ''}>Paket</option>
                        <option value="License" ${itemData && itemData.satuan === 'License' ? 'selected' : ''}>License</option>
                        <option value="Layanan" ${itemData && itemData.satuan === 'Layanan' ? 'selected' : ''}>Layanan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan</label>
                    <input type="number" name="barang[${editItemCounter}][harga_satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm harga-satuan-input-edit" placeholder="0" min="0" value="${itemData ? itemData.harga_satuan : ''}" required onchange="hitungTotalEdit(this)">
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-sm total-harga-edit" readonly placeholder="Rp 0">
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
    editItemCounter++;
    
    // Calculate total for this item if data provided
    if (itemData && itemData.jumlah && itemData.harga_satuan) {
        const newItem = container.lastElementChild;
        const total = itemData.jumlah * itemData.harga_satuan;
        newItem.querySelector('.total-harga-edit').value = formatRupiah(total);
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
        if (items.length > 1) {
            deleteButton.style.display = 'block';
        } else {
            deleteButton.style.display = 'none';
        }
    });
}

function hitungTotalEdit(input) {
    const row = input.closest('.barang-item-edit');
    const jumlah = parseFloat(row.querySelector('.jumlah-input-edit').value) || 0;
    const hargaSatuan = parseFloat(row.querySelector('.harga-satuan-input-edit').value) || 0;
    const total = jumlah * hargaSatuan;
    
    row.querySelector('.total-harga-edit').value = formatRupiah(total);
    hitungTotalKeseluruhanEdit();
}

function hitungTotalKeseluruhanEdit() {
    let total = 0;
    document.querySelectorAll('.barang-item-edit').forEach(item => {
        const jumlah = parseFloat(item.querySelector('.jumlah-input-edit').value) || 0;
        const hargaSatuan = parseFloat(item.querySelector('.harga-satuan-input-edit').value) || 0;
        total += jumlah * hargaSatuan;
    });
    
    document.getElementById('totalKeseluruhanEdit').textContent = formatRupiah(total);
}

// File upload functions for edit modal
function handleFileUploadEdit(input) {
    const file = input.files[0];
    const uploadArea = document.getElementById('editUploadArea');
    const filePreview = document.getElementById('editFilePreview');
    const fileName = document.getElementById('editFileName');
    const fileSize = document.getElementById('editFileSize');
    const currentFile = document.getElementById('currentFile');
    
    if (file) {
        // Check file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file tidak boleh lebih dari 2MB');
            input.value = '';
            return;
        }
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 
                             'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan format PDF, Word, atau Image');
            input.value = '';
            return;
        }
        
        // Hide current file and upload area, show new file preview
        currentFile.classList.add('hidden');
        uploadArea.classList.add('hidden');
        filePreview.classList.remove('hidden');
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
    }
}

function removeFileEdit() {
    const input = document.getElementById('editSuratPesanan');
    const uploadArea = document.getElementById('editUploadArea');
    const filePreview = document.getElementById('editFilePreview');
    const currentFile = document.getElementById('currentFile');
    
    input.value = '';
    uploadArea.classList.remove('hidden');
    filePreview.classList.add('hidden');
    currentFile.classList.remove('hidden');
}

function viewCurrentFile() {
    // This would open the current file in a new tab/modal
    alert('Membuka file saat ini...');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

// Example function to open edit modal
function openEditModal(id) {
    // This would typically fetch data from server
    const sampleData = {
        id: id,
        kode: 'PNW-001',
        kabupaten_kota: 'Jakarta Pusat',
        nama_instansi: 'Dinas Pendidikan DKI',
        jenis_pengadaan: 'Pelelangan Umum',
        admin_purchasing: 'Sari Wijaya',
        deadline: '2024-09-30',
        catatan: 'Sistem informasi manajemen untuk sekolah',
        items: [
            {
                nama: 'Sistem Informasi Manajemen',
                jumlah: 1,
                satuan: 'Paket',
                harga_satuan: 500000000
            },
            {
                nama: 'Training & Support',
                jumlah: 1,
                satuan: 'Layanan',
                harga_satuan: 50000000
            }
        ]
    };
    
    loadEditData(sampleData);
    document.getElementById('modalEditPenawaran').classList.remove('hidden');
    document.getElementById('modalEditPenawaran').classList.add('flex');
}

// Form submission
document.getElementById('formEditPenawaran').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simulate form submission
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';
    submitButton.disabled = true;
    
    setTimeout(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        closeModal('modalEditPenawaran');
        
        // Show success message
        showSuccessModal('Penawaran berhasil diupdate!');
    }, 2000);
});
</script>
