<!-- Modal Tambah Potensi -->
<style>
    .hover-effect-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(0);
    }

    .hover-effect-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .barang-item {
        transition: all 0.3s ease;
    }

    .barang-item:hover {
        transform: translateY(-2px);
    }

    .input-focus:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        border-color: #ef4444;
    }

    .btn-hover-white:hover {
        background-color: white !important;
        border: 2px solid currentColor !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    #barangCounter {
        transition: all 0.3s ease;
    }

    /* Custom Alert Styles */
    .custom-alert-overlay {
        backdrop-filter: blur(4px);
        background: rgba(0, 0, 0, 0.4);
    }

    .custom-alert {
        transform: scale(0.9);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .custom-alert.show {
        transform: scale(1);
        opacity: 1;
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .alert-error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .alert-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .alert-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    /* Harga Satuan Input Styling */
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

    .harga-satuan-input {
        padding-left: 35px !important;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.5px;
    }

    .harga-satuan-input:focus {
        padding-left: 35px !important;
    }
</style>

<!-- Custom Alert Modal -->
<div id="customAlertOverlay" class="fixed inset-0 z-[60] hidden items-center justify-center custom-alert-overlay">
    <div id="customAlert" class="custom-alert bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
        <!-- Alert Header -->
        <div id="alertHeader" class="px-6 py-4 text-white">
            <div class="flex items-center">
                <div id="alertIcon" class="w-8 h-8 mr-3 flex items-center justify-center rounded-full bg-white bg-opacity-20">
                    <i id="alertIconClass" class="text-lg"></i>
                </div>
                <h3 id="alertTitle" class="text-lg font-bold">Alert</h3>
            </div>
        </div>

        <!-- Alert Body -->
        <div class="px-6 py-4">
            <p id="alertMessage" class="text-gray-700 text-base leading-relaxed"></p>
        </div>

        <!-- Alert Footer -->
        <div id="alertFooter" class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
            <button id="alertCancel" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:shadow-md transition-all duration-200 hidden">
                Batal
            </button>
            <button id="alertConfirm" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                OK
            </button>
        </div>
    </div>
</div>

<div id="modalTambahProyek" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Tambah Potensi Baru</h3>
                    <p class="text-red-100 text-sm">Buat potensi baru</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahProyek')" class="text-white hover:bg-white hover:text-red-800 hover:border-2 hover:border-white rounded-lg p-2 transition-all duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>


        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formTambahProyek" class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Potensi</label>
                            <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 flex items-center">
                                <i class="fas fa-magic mr-2 text-blue-500"></i>
                                <span id="previewKodeProyek">Auto Generate (PRJ-XXX)</span>
                            </div>
                            <small class="text-gray-500 text-xs mt-1">Kode potensi akan di-generate otomatis</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="tanggal" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten_kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan kabupaten/kota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi</label>
                            <input type="text" name="nama_instansi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama instansi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <select name="jenis_pengadaan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih jenis pengadaan</option>
                                <option value="E-Katalog">E-Katalog</option>
                                <option value="Pengadaan Langsung">Pengadaan Langsung</option>
                                <option value="Tender">Tender / Mini Kompetisi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIC Marketing</label>
                            <select name="id_admin_marketing" id="adminMarketingSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Pilih PIC marketing</option>
                            </select>
                            <small class="text-gray-500 text-xs mt-1">Pilih PIC marketing yang bertanggung jawab untuk potensi ini</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIC Purchasing</label>
                            <select name="admin_purchasing" id="adminPurchasingSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih PIC purchasing</option>
                            </select>
                        </div>
                        <!-- Hidden input untuk potensi, selalu bernilai 'ya' -->
                        <input type="hidden" name="potensi" value="ya">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Potensi</label>
                            <input type="number" name="tahun_potensi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="{{ date('Y') }}" value="{{ date('Y') }}" min="2020" max="2030">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan catatan potensi..."></textarea>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-boxes text-red-600 mr-2"></i>
                            Daftar Barang
                            <span id="barangCounter" class="ml-2 bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs font-medium">1 item</span>
                        </h4>
                    </div>

                    <div id="daftarBarang" class="space-y-4">
                        <!-- Item Barang Template -->
                        <div class="barang-item bg-white border-2 border-gray-200 rounded-lg p-4 hover:border-red-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-medium text-gray-800 flex items-center">
                                    <i class="fas fa-box text-red-600 mr-2"></i>
                                    Item 1
                                </h5>
                                <button type="button" onclick="hapusBarang(this)" class="text-red-600 hover:bg-red-100 hover:text-red-700 rounded-lg p-2 transition-all duration-200 hover:shadow-md" style="display: none;">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                                    <input type="text" name="barang[0][nama]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Nama barang" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                                    <input type="number" name="barang[0][qty]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm qty-input" placeholder="0" min="1" onchange="hitungTotal(this)" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                    <select name="barang[0][satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
                                        <option value="">Pilih satuan</option>
                                        <option value="pcs">Pcs</option>
                                        <option value="unit">Unit</option>
                                        <option value="set">Set</option>
                                        <option value="buah">Buah</option>
                                        <option value="kg">Kg</option>
                                        <option value="meter">Meter</option>
                                        <option value="liter">Liter</option>
                                        <option value="paket">Paket</option>
                                        <option value="sistem">Sistem</option>
                                        <option value="layanan">Layanan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp)</label>
                                    <div class="harga-satuan-wrapper">
                                        <input type="text" name="barang[0][harga_satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm harga-satuan-input" placeholder="1.500.000,50" oninput="formatHargaSatuan(this)" onchange="hitungTotal(this)">
                                    </div>
                                    <small class="text-gray-500 text-xs">Opsional - untuk estimasi (contoh: 1.500.000,50)</small>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Total (Rp)</label>
                                    <input type="text" name="barang[0][harga_total]" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 text-sm harga-total-input" placeholder="0" readonly>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi</label>
                                <textarea name="barang[0][spesifikasi]" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Deskripsi atau spesifikasi barang (opsional)"></textarea>

                                <!-- File Upload Option -->
                                <div class="mt-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="enableFileUpload_0" class="file-upload-checkbox mr-2 text-red-600 focus:ring-red-500" onchange="toggleFileUpload(this, 0)">
                                        <label for="enableFileUpload_0" class="text-sm text-gray-600 cursor-pointer flex items-center">
                                            <i class="fas fa-paperclip mr-1 text-gray-500"></i>
                                            Tambah lampiran file spesifikasi
                                        </label>
                                    </div>

                                    <!-- File Upload Area (Hidden by default) -->
                                    <div id="fileUploadArea_0" class="mt-3 hidden">
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-red-400 transition-colors duration-200">
                                            <input type="file" name="barang[0][files][]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" class="hidden" id="fileInput_0" onchange="handleFileSelect(this, 0)">
                                            <label for="fileInput_0" class="cursor-pointer">
                                                <div class="text-gray-500">
                                                    <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                                    <p class="text-sm font-medium">Klik untuk browse atau drag & drop files</p>
                                                    <p class="text-xs mt-1">PDF, DOC, XLS, JPG, PNG (Max 5MB per file)</p>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Selected Files Preview -->
                                        <div id="filePreview_0" class="mt-3 space-y-2 hidden">
                                            <div class="text-sm font-medium text-gray-700 mb-2">File terpilih:</div>
                                            <div id="fileList_0" class="space-y-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="button" onclick="tambahBarang()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-white hover:text-red-600 hover:border-2 hover:border-red-600 transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Barang
                        </button>
                    </div>

                    <!-- Total Keseluruhan -->
                    <div class="mt-6 bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-calculator text-red-600 mr-2"></i>
                                Total Estimasi Keseluruhan:
                            </h5>
                            <div class="text-2xl font-bold text-red-600" id="totalKeseluruhan">Rp 0</div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Total ini adalah estimasi berdasarkan harga yang diinput
                        </p>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalTambahProyek')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-700 hover:text-white hover:border-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                Batal
            </button>
            <button type="submit" form="formTambahProyek" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-white hover:text-red-600 hover:border-2 hover:border-red-600 transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>
                Simpan Potensi
            </button>
        </div>
    </div>
</div>

<script>
let itemCounter = 1;

// Fungsi togglePotensi dihapus karena tidak diperlukan lagi
// Potensi selalu bernilai 'ya' untuk form tambah potensi

function tambahBarang() {
    const container = document.getElementById('daftarBarang');
    const template = document.querySelector('.barang-item');

    if (!container || !template) {
        console.error('Container atau template tidak ditemukan');
        return;
    }

    // Clone template
    const clonedTemplate = template.cloneNode(true);

    // Update header dan input names
    const titleElement = clonedTemplate.querySelector('h5');
    if (titleElement) {
        titleElement.textContent = `Item ${itemCounter + 1}`;
    }

    // Update semua input names dan reset values
    clonedTemplate.querySelectorAll('input, select, textarea').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace('[0]', `[${itemCounter}]`));
        }

        // Update IDs for file upload elements
        const id = input.getAttribute('id');
        if (id) {
            input.setAttribute('id', id.replace('_0', `_${itemCounter}`));
        }

        // Update onchange attributes for file inputs
        const onchange = input.getAttribute('onchange');
        if (onchange && onchange.includes('toggleFileUpload')) {
            input.setAttribute('onchange', onchange.replace('0)', `${itemCounter})`));
        }
        if (onchange && onchange.includes('handleFileSelect')) {
            input.setAttribute('onchange', onchange.replace('0)', `${itemCounter})`));
        }

        // Reset values
        if (input.type === 'text' || input.type === 'number' || input.tagName === 'TEXTAREA') {
            input.value = '';
        } else if (input.tagName === 'SELECT') {
            input.selectedIndex = 0;
        } else if (input.type === 'checkbox') {
            input.checked = false;
        }

        // Pastikan input harga satuan menggunakan format yang benar
        if (input.classList.contains('harga-satuan-input')) {
            input.type = 'text';
            input.setAttribute('oninput', 'formatHargaSatuan(this)');
            input.setAttribute('onchange', 'hitungTotal(this)');
            input.placeholder = '1.500.000,50';

            // Pastikan wrapper ada untuk input harga satuan
            if (!input.parentElement.classList.contains('harga-satuan-wrapper')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'harga-satuan-wrapper';
                input.parentElement.insertBefore(wrapper, input);
                wrapper.appendChild(input);
            }
        }

        // Pastikan input harga total menggunakan type text untuk format yang benar
        if (input.classList.contains('harga-total-input')) {
            input.type = 'text';
        }

        // Tambahkan event listener untuk qty input
        if (input.classList.contains('qty-input')) {
            input.setAttribute('onchange', 'hitungTotal(this)');
        }

        // Remove readonly untuk input yang baru
        if (input.hasAttribute('readonly')) {
            input.removeAttribute('readonly');
            // Tambahkan kembali readonly untuk harga total
            if (input.classList.contains('harga-total-input')) {
                input.setAttribute('readonly', true);
            }
        }
    });

    // Update labels dan divs untuk file upload
    clonedTemplate.querySelectorAll('label, div').forEach(element => {
        const forAttr = element.getAttribute('for');
        if (forAttr) {
            element.setAttribute('for', forAttr.replace('_0', `_${itemCounter}`));
        }

        const id = element.getAttribute('id');
        if (id) {
            element.setAttribute('id', id.replace('_0', `_${itemCounter}`));
        }
    });

    // Show delete button untuk item baru
    const deleteButton = clonedTemplate.querySelector('button[onclick="hapusBarang(this)"]');
    if (deleteButton) {
        deleteButton.style.display = 'block';
    }

    // Tambahkan animasi fade in
    clonedTemplate.style.opacity = '0';
    clonedTemplate.style.transform = 'translateY(-10px)';
    container.appendChild(clonedTemplate);

    // Animate in
    setTimeout(() => {
        clonedTemplate.style.transition = 'all 0.3s ease';
        clonedTemplate.style.opacity = '1';
        clonedTemplate.style.transform = 'translateY(0)';
    }, 10);

    itemCounter++;
    updateDeleteButtons();
    updateBarangCounter();

    // Focus pada input nama barang yang baru
    const namaBarangInput = clonedTemplate.querySelector('input[name*="[nama]"]');
    if (namaBarangInput) {
        setTimeout(() => namaBarangInput.focus(), 100);
    }

    console.log(`Item ${itemCounter} berhasil ditambahkan`);

    // Show notification
        // Show notification
    showNotification(`Item ${itemCounter} berhasil ditambahkan`, 'success');
}

async function hapusBarang(button) {
    const item = button.closest('.barang-item');
    const itemName = item.querySelector('h5').textContent;

    // Konfirmasi hapus dengan custom alert
    const confirmed = await showConfirmAlert(`Apakah Anda yakin ingin menghapus ${itemName}?`, 'Konfirmasi Hapus');
    if (!confirmed) {
        return;
    }

    // Animasi fade out
    item.style.transition = 'all 0.3s ease';
    item.style.opacity = '0';
    item.style.transform = 'translateY(-10px)';

    setTimeout(() => {
        item.remove();
        updateItemNumbers();
        hitungTotalKeseluruhan();
        updateDeleteButtons();
        console.log(`${itemName} berhasil dihapus`);
        showNotification(`${itemName} berhasil dihapus`, 'info');
    }, 300);
}

function updateItemNumbers() {
    const items = document.querySelectorAll('.barang-item');
    itemCounter = items.length; // Reset counter berdasarkan jumlah item saat ini

    items.forEach((item, index) => {
        // Update title
        const titleElement = item.querySelector('h5');
        if (titleElement) {
            titleElement.textContent = `Item ${index + 1}`;
        }

        // Update input names and IDs
        item.querySelectorAll('input, select, textarea').forEach(input => {
            const name = input.getAttribute('name');
            if (name && name.includes('[')) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
            }

            // Update IDs for file upload elements
            const id = input.getAttribute('id');
            if (id && id.includes('_')) {
                const newId = id.replace(/_\d+$/, `_${index}`);
                input.setAttribute('id', newId);
            }

            // Update onchange attributes for file inputs
            const onchange = input.getAttribute('onchange');
            if (onchange && onchange.includes('toggleFileUpload')) {
                input.setAttribute('onchange', onchange.replace(/\d+\)/, `${index})`));
            }
            if (onchange && onchange.includes('handleFileSelect')) {
                input.setAttribute('onchange', onchange.replace(/\d+\)/, `${index})`));
            }
        });

        // Update labels and divs for file upload
        item.querySelectorAll('label, div').forEach(element => {
            const forAttr = element.getAttribute('for');
            if (forAttr && forAttr.includes('_')) {
                element.setAttribute('for', forAttr.replace(/_\d+$/, `_${index}`));
            }

            const id = element.getAttribute('id');
            if (id && id.includes('_')) {
                element.setAttribute('id', id.replace(/_\d+$/, `_${index}`));
            }
        });
    });

    // Update counter badge
    updateBarangCounter();
}

function updateBarangCounter() {
    const counter = document.getElementById('barangCounter');
    const items = document.querySelectorAll('.barang-item');
    if (counter) {
        const count = items.length;
        counter.textContent = `${count} item${count > 1 ? 's' : ''}`;

        // Animasi perubahan
        counter.style.transform = 'scale(1.2)';
        setTimeout(() => {
            counter.style.transform = 'scale(1)';
        }, 200);
    }
}function updateDeleteButtons() {
    const items = document.querySelectorAll('.barang-item');
    items.forEach((item, index) => {
        const deleteButton = item.querySelector('button[onclick="hapusBarang(this)"]');
        if (deleteButton) {
            if (items.length > 1) {
                deleteButton.style.display = 'block';
            } else {
                deleteButton.style.display = 'none';
            }
        }
    });
}

function hitungTotal(input) {
    const row = input.closest('.barang-item');
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;

    // Get numeric value from formatted harga satuan (supports decimals in Indonesian format)
    let hargaSatuan = 0;
    const hargaSatuanInput = row.querySelector('.harga-satuan-input');
    if (hargaSatuanInput) {
        // Remove thousand separators (dots) and replace decimal comma with dot, then parse as float
        let cleanValue = hargaSatuanInput.value
            .replace(/\./g, '')    // Remove thousand separators (dots)
            .replace(/,/g, '.');   // Replace decimal comma with dot
        hargaSatuan = parseFloat(cleanValue) || 0;
    }

    const total = qty * hargaSatuan;

    const totalInput = row.querySelector('.harga-total-input');
    if (totalInput) {
        // Format with Indonesian format (dots for thousands, comma for decimals)
        totalInput.value = total > 0 ? formatRupiahNumber(total) : '0';
    }
    hitungTotalKeseluruhan();
}

// Function to format number with dots for thousand separator and comma for decimal (Indonesian format)
function formatHargaSatuan(input) {
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

// Function to format rupiah for display (supports decimals)
function formatRupiahNumber(angka) {
    // Check if number has decimal places
    if (angka % 1 !== 0) {
        // Has decimal places - format with up to 2 decimal places
        return angka.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 5
        });
    } else {
        // Whole number - format without decimal places
        return angka.toLocaleString('id-ID');
    }
}

function hitungTotalKeseluruhan() {
    let total = 0;
    document.querySelectorAll('.barang-item').forEach(item => {
        const qtyInput = item.querySelector('.qty-input');
        const hargaSatuanInput = item.querySelector('.harga-satuan-input');

        if (qtyInput && hargaSatuanInput) {
            const qty = parseFloat(qtyInput.value) || 0;
            // Remove thousand separators (dots) and replace decimal comma with dot for parsing
            let cleanValue = hargaSatuanInput.value
                .replace(/\./g, '')    // Remove thousand separators (dots)
                .replace(/,/g, '.');   // Replace decimal comma with dot
            const hargaSatuan = parseFloat(cleanValue) || 0;
            total += qty * hargaSatuan;
        }
    });

    const totalElement = document.getElementById('totalKeseluruhan');
    if (totalElement) {
        totalElement.textContent = formatRupiah(total);
    }
}

function formatRupiah(angka) {
    // Check if number has decimal places
    if (angka % 1 !== 0) {
        // Has decimal places - format with up to 2 decimal places
        return 'Rp ' + angka.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 5
        });
    } else {
        // Whole number - format without decimal places
        return 'Rp ' + angka.toLocaleString('id-ID');
    }
}

// File Upload Functions for Spesifikasi
function toggleFileUpload(checkbox, index) {
    const fileUploadArea = document.getElementById(`fileUploadArea_${index}`);
    if (fileUploadArea) {
        if (checkbox.checked) {
            fileUploadArea.classList.remove('hidden');
            // Add animation
            fileUploadArea.style.opacity = '0';
            fileUploadArea.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                fileUploadArea.style.transition = 'all 0.3s ease';
                fileUploadArea.style.opacity = '1';
                fileUploadArea.style.transform = 'translateY(0)';
            }, 10);
        } else {
            fileUploadArea.classList.add('hidden');
            // Clear files when hiding
            clearSpecFiles(index);
        }
    }
}

function handleFileSelect(input, index) {
    const files = input.files;
    const filePreview = document.getElementById(`filePreview_${index}`);
    const fileList = document.getElementById(`fileList_${index}`);

    if (files.length > 0) {
        filePreview.classList.remove('hidden');
        fileList.innerHTML = '';

        // Validate and display files
        let validFiles = [];
        Array.from(files).forEach((file, fileIndex) => {
            if (validateFile(file)) {
                validFiles.push(file);
                const fileItem = createFilePreviewItem(file, index, fileIndex);
                fileList.appendChild(fileItem);
            }
        });

        if (validFiles.length === 0) {
            filePreview.classList.add('hidden');
            input.value = ''; // Clear invalid files
        }
    } else {
        filePreview.classList.add('hidden');
    }
}

function validateFile(file) {
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/jpg',
        'image/png'
    ];

    if (!allowedTypes.includes(file.type)) {
        showNotification(`File ${file.name} tidak didukung. Gunakan PDF, DOC, XLS, atau gambar.`, 'error');
        return false;
    }

    if (file.size > maxSize) {
        showNotification(`File ${file.name} terlalu besar. Maksimal 5MB.`, 'error');
        return false;
    }

    return true;
}

function createFilePreviewItem(file, itemIndex, fileIndex) {
    const fileItem = document.createElement('div');
    fileItem.className = 'flex items-center justify-between bg-gray-50 rounded-lg p-2 border border-gray-200';

    const fileSize = (file.size / 1024).toFixed(1);
    const fileIcon = getFileIcon(file.type);

    fileItem.innerHTML = `
        <div class="flex items-center flex-1">
            <i class="${fileIcon} text-gray-500 mr-2"></i>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-700 truncate">${file.name}</p>
                <p class="text-xs text-gray-500">${fileSize} KB</p>
            </div>
        </div>
        <button type="button" onclick="removeSpecFile(${itemIndex}, ${fileIndex})"
                class="text-red-500 hover:text-red-700 hover:bg-red-100 rounded-full p-1 transition-colors duration-200">
            <i class="fas fa-times text-xs"></i>
        </button>
    `;

    return fileItem;
}

function getFileIcon(mimeType) {
    if (mimeType.includes('pdf')) return 'fas fa-file-pdf text-red-500';
    if (mimeType.includes('word')) return 'fas fa-file-word text-blue-500';
    if (mimeType.includes('excel') || mimeType.includes('sheet')) return 'fas fa-file-excel text-green-500';
    if (mimeType.includes('image')) return 'fas fa-file-image text-purple-500';
    return 'fas fa-file text-gray-500';
}

function removeSpecFile(itemIndex, fileIndex) {
    const fileInput = document.getElementById(`fileInput_${itemIndex}`);
    const fileList = document.getElementById(`fileList_${itemIndex}`);
    const filePreview = document.getElementById(`filePreview_${itemIndex}`);

    if (fileInput && fileList) {
        // Remove the file from the input (this is tricky with file inputs)
        // We'll need to create a new FileList without the removed file
        const dt = new DataTransfer();
        const files = fileInput.files;

        for (let i = 0; i < files.length; i++) {
            if (i !== fileIndex) {
                dt.items.add(files[i]);
            }
        }

        fileInput.files = dt.files;

        // Remove the visual element
        const fileItems = fileList.children;
        if (fileItems[fileIndex]) {
            fileItems[fileIndex].remove();
        }

        // Hide preview if no files left
        if (fileInput.files.length === 0) {
            filePreview.classList.add('hidden');
        }

        // Re-index remaining file items
        Array.from(fileList.children).forEach((item, index) => {
            const removeBtn = item.querySelector('button');
            if (removeBtn) {
                removeBtn.setAttribute('onclick', `removeSpecFile(${itemIndex}, ${index})`);
            }
        });
    }
}

function clearSpecFiles(index) {
    const fileInput = document.getElementById(`fileInput_${index}`);
    const filePreview = document.getElementById(`filePreview_${index}`);
    const fileList = document.getElementById(`fileList_${index}`);

    if (fileInput) fileInput.value = '';
    if (filePreview) filePreview.classList.add('hidden');
    if (fileList) fileList.innerHTML = '';
}

// Function to show notification
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notification => notification.remove());

    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;

    let bgColor, textColor, icon;
    switch (type) {
        case 'success':
            bgColor = 'bg-green-500';
            textColor = 'text-white';
            icon = 'fas fa-check-circle';
            break;
        case 'error':
            bgColor = 'bg-red-500';
            textColor = 'text-white';
            icon = 'fas fa-exclamation-circle';
            break;
        case 'info':
            bgColor = 'bg-blue-500';
            textColor = 'text-white';
            icon = 'fas fa-info-circle';
            break;
        default:
            bgColor = 'bg-gray-500';
            textColor = 'text-white';
            icon = 'fas fa-bell';
    }

    notification.className += ` ${bgColor} ${textColor}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="${icon} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Auto hide after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(full)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Function to clear file input in tambah modal
function clearTambahFile(inputId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(inputId + 'Preview');

    if (input) {
        input.value = '';
    }
    if (preview) {
        preview.classList.add('hidden');
    }
}

// File upload preview handlers for tambah modal
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = ['suratPenawaran', 'suratPersetujuan', 'suratKontrak', 'suratSelesai'];

    fileInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(inputId + 'Preview');

        if (input && preview) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const filenameSpan = preview.querySelector('.filename');

                if (file) {
                    // Check file size (5MB limit)
                    if (file.size > 5 * 1024 * 1024) {
                        showErrorAlert('Ukuran file terlalu besar. Maksimal 5MB.', 'File Terlalu Besar');
                        this.value = '';
                        preview.classList.add('hidden');
                        return;
                    }

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
document.getElementById('formTambahProyek').addEventListener('submit', async function(e) {
    e.preventDefault();

    // Validasi form
    const isValid = await validateTambahForm();
    if (!isValid) {
        return;
    }

    // Kumpulkan data form
    const formDataObject = collectTambahFormData();

    // Submit data
    const submitButton = e.target.querySelector('button[type="submit"]') || document.querySelector('button[form="formTambahProyek"]');

    if (!submitButton) {
        console.error('Submit button not found');
        showErrorAlert('Terjadi kesalahan: tombol submit tidak ditemukan', 'Error Sistem');
        return;
    }

    const originalText = submitButton.innerHTML;

    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitButton.disabled = true;

    // Create FormData untuk traditional form submission
    const formData = new FormData();

    // Add basic data
    Object.keys(formDataObject).forEach(key => {
        if (key !== 'daftar_barang') {
            formData.append(key, formDataObject[key]);
        }
    });

    // Add daftar_barang sebagai JSON string
    if (formDataObject.daftar_barang) {
        formData.append('daftar_barang', JSON.stringify(formDataObject.daftar_barang));
    }

    // Add file uploads untuk setiap item barang
    const barangItems = document.querySelectorAll('.barang-item');
    barangItems.forEach((item, index) => {
        const fileInput = item.querySelector(`input[name="barang[${index}][files][]"]`);
        if (fileInput && fileInput.files.length > 0) {
            // Add each file with the same naming convention
            Array.from(fileInput.files).forEach(file => {
                formData.append(`barang[${index}][files][]`, file);
            });
        }
    });

    console.log('Sending FormData with:');
    for (let [key, value] of formData.entries()) {
        if (value instanceof File) {
            console.log(key, `File: ${value.name} (${value.size} bytes)`);
        } else {
            console.log(key, value);
        }
    }

    // Kirim data ke server
    fetch('/marketing/potensi', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // If not JSON, get text to see what we received
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                throw new Error('Server returned non-JSON response');
            });
        }
    })
    .then(data => {
        console.log('Response data:', data);

        if (data.success) {
            // Reset form
            this.reset();
            resetTambahModal();
            closeModal('modalTambahProyek');

            // Show success message
            if (typeof showSuccessModal === 'function') {
                showSuccessModal('Potensi berhasil ditambahkan!');
            } else {
                showSuccessAlert('Potensi berhasil ditambahkan!', 'Berhasil');
            }

            // Reload page untuk update data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan saat menyimpan data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        console.error('Error stack:', error.stack);

        let errorMessage = 'Terjadi kesalahan: ' + error.message;

        // Handle different types of errors
        if (error.message.includes('500')) {
            errorMessage = 'Terjadi kesalahan server (500). Periksa log aplikasi untuk detail.';
        } else if (error.message.includes('404')) {
            errorMessage = 'Endpoint tidak ditemukan (404). Periksa routing aplikasi.';
        } else if (error.message.includes('422')) {
            errorMessage = 'Data yang dikirim tidak valid (422). Periksa validasi form.';
        }

        showErrorAlert(errorMessage, 'Error');
    })
    .finally(() => {
        if (submitButton) {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    });
});

// Fungsi validasi form tambah
async function validateTambahForm() {
    const requiredFields = [
        { name: 'tanggal', label: 'Tanggal' },
        { name: 'kabupaten_kota', label: 'Kabupaten/Kota' },
        { name: 'nama_instansi', label: 'Nama Instansi' },
        { name: 'jenis_pengadaan', label: 'Jenis Pengadaan' },
        { name: 'id_admin_marketing', label: 'PIC Marketing' },
        { name: 'admin_purchasing', label: 'PIC Purchasing' }
    ];

    for (let field of requiredFields) {
        const input = document.querySelector(`[name="${field.name}"]`);
        if (!input || !input.value.trim()) {
            await showWarningAlert(`${field.label} harus diisi!`, 'Data Tidak Lengkap');
            if (input) input.focus();
            return false;
        }
    }

    // Validasi PIC marketing sudah dipilih
    const adminMarketingId = document.querySelector('[name="id_admin_marketing"]')?.value;
    if (!adminMarketingId) {
        await showErrorAlert('PIC Marketing harus dipilih!', 'Data Tidak Lengkap');
        return false;
    }

    // Validasi minimal ada 1 barang yang lengkap
    const barangItems = document.querySelectorAll('.barang-item');
    let hasValidItem = false;
    let invalidItems = [];

    barangItems.forEach((item, index) => {
        const namaBarang = item.querySelector('input[name*="[nama]"]');
        const qty = item.querySelector('input[name*="[qty]"]');
        const satuan = item.querySelector('select[name*="[satuan]"]');

        // Cek apakah item ini lengkap
        const isComplete = namaBarang?.value?.trim() && qty?.value && satuan?.value;

        if (isComplete) {
            hasValidItem = true;
        } else {
            // Jika ada data parsial (ada yang diisi tapi tidak lengkap)
            const hasPartialData = namaBarang?.value?.trim() || qty?.value || satuan?.value;
            if (hasPartialData) {
                invalidItems.push(`Item ${index + 1}`);
            }
        }
    });

    if (!hasValidItem) {
        await showWarningAlert('Minimal harus ada 1 barang yang lengkap datanya (Nama, Qty, dan Satuan)!', 'Data Barang Tidak Lengkap');
        return false;
    }

    // Peringatan untuk item yang tidak lengkap
    if (invalidItems.length > 0) {
        const proceed = await showConfirmAlert(`${invalidItems.join(', ')} memiliki data yang tidak lengkap dan akan diabaikan. Lanjutkan?`, 'Data Tidak Lengkap');
        if (!proceed) {
            return false;
        }
    }

    return true;
}

// Fungsi untuk mengumpulkan data form
function collectTambahFormData() {
    const form = document.getElementById('formTambahProyek');
    const formData = new FormData(form);

    // Convert ke object
    const data = {};

    // Data dasar
    data.tanggal = formData.get('tanggal');
    data.kab_kota = formData.get('kabupaten_kota');
    data.instansi = formData.get('nama_instansi');
    data.jenis_pengadaan = formData.get('jenis_pengadaan');
    data.id_admin_purchasing = formData.get('admin_purchasing');
    data.catatan = formData.get('catatan') || '';
    data.potensi = formData.get('potensi') || 'tidak';
    data.tahun_potensi = parseInt(formData.get('tahun_potensi')) || new Date().getFullYear();

    // Kumpulkan data SEMUA barang, bukan hanya yang pertama
    const daftarBarang = [];
    const barangItems = document.querySelectorAll('.barang-item');

    barangItems.forEach((item, index) => {
        const namaBarang = item.querySelector('input[name*="[nama]"]')?.value?.trim();
        const qty = item.querySelector('input[name*="[qty]"]')?.value;
        const satuan = item.querySelector('select[name*="[satuan]"]')?.value;
        const hargaSatuanRaw = item.querySelector('input[name*="[harga_satuan]"]')?.value;
        const spesifikasi = item.querySelector('textarea[name*="[spesifikasi]"]')?.value?.trim();

        // Convert Indonesian formatted price to number (remove dots, replace comma with dot)
        let hargaSatuan = null;
        if (hargaSatuanRaw) {
            const cleanValue = hargaSatuanRaw
                .replace(/\./g, '')    // Remove thousand separators (dots)
                .replace(/,/g, '.');   // Replace decimal comma with dot
            hargaSatuan = parseFloat(cleanValue) || null;
        }

        // Hanya tambahkan barang yang memiliki data minimal (nama, qty, satuan)
        if (namaBarang && qty && satuan) {
            daftarBarang.push({
                nama_barang: namaBarang,
                jumlah: parseInt(qty) || 1,
                satuan: satuan,
                harga_satuan: hargaSatuan,
                spesifikasi: spesifikasi || 'Spesifikasi standar'
            });
        }
    });

    // Jika ada multiple barang, kirim sebagai daftar_barang
    if (daftarBarang.length > 0) {
        data.daftar_barang = daftarBarang;

        // Ambil data dari barang pertama untuk kompatibilitas (fallback)
        const firstBarang = daftarBarang[0];
        data.nama_barang = firstBarang.nama_barang;
        data.jumlah = firstBarang.jumlah;
        data.satuan = firstBarang.satuan;
        data.harga_satuan = firstBarang.harga_satuan;
        data.spesifikasi = firstBarang.spesifikasi;
    } else {
        // Jika tidak ada barang valid, buat data default
        data.nama_barang = 'Barang Default';
        data.jumlah = 1;
        data.satuan = 'Unit';
        data.harga_satuan = null;
        data.spesifikasi = 'Spesifikasi standar';
    }

    // Ambil ID PIC marketing dari form
    const adminMarketingId = formData.get('id_admin_marketing');
    data.id_admin_marketing = adminMarketingId ? parseInt(adminMarketingId) : null;

    console.log('Data yang akan dikirim:', data);
    console.log('Jumlah barang:', daftarBarang.length);

    // Tampilkan preview data barang di console untuk debugging
    if (daftarBarang.length > 0) {
        console.log('Detail barang yang akan disimpan:');
        daftarBarang.forEach((barang, index) => {
            console.log(`  ${index + 1}. ${barang.nama_barang} - ${barang.jumlah} ${barang.satuan}`);
        });
    }

    return data;
}

// Fungsi reset modal tambah
function resetTambahModal() {
    // Reset file previews
    ['suratPenawaran', 'suratPersetujuan', 'suratKontrak', 'suratSelesai'].forEach(inputId => {
        const preview = document.getElementById(inputId + 'Preview');
        if (preview) {
            preview.classList.add('hidden');
        }
    });

    // Tidak perlu reset potensi buttons karena sudah dihapus
    // Potensi selalu 'ya' untuk form tambah

    // Reset PIC marketing dropdown to current user
    const adminMarketingSelect = document.getElementById('adminMarketingSelect');
    if (adminMarketingSelect) {
        // Find and select the current user option
        const currentUserOption = adminMarketingSelect.querySelector('option[selected]');
        if (currentUserOption) {
            adminMarketingSelect.value = currentUserOption.value;
        } else {
            adminMarketingSelect.selectedIndex = 0;
        }
    }

    // Reset PIC purchasing dropdown
    const adminPurchasingSelect = document.getElementById('adminPurchasingSelect');
    if (adminPurchasingSelect) {
        adminPurchasingSelect.selectedIndex = 0;
    }

    // Reset items to 1
    const container = document.getElementById('daftarBarang');
    const items = container.querySelectorAll('.barang-item');
    for (let i = 1; i < items.length; i++) {
        items[i].remove();
    }
    itemCounter = 1;
    updateDeleteButtons();
    updateBarangCounter();
    hitungTotalKeseluruhan();
}

// Function to load PIC marketing options
async function loadAdminMarketingOptions() {
    try {
        const response = await fetch('/marketing/potensi/users');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('adminMarketingSelect');
            if (select) {
                // Clear existing options except the first one
                select.innerHTML = '<option value="">Pilih PIC marketing</option>';

                // Add options for marketing and PIC roles
                data.data.forEach(user => {
                    if (user.role === 'admin_marketing' || user.role === 'superadmin') {
                        const option = document.createElement('option');
                        option.value = user.id_user;
                        option.textContent = user.nama;

                        // Set current user as default selected
                        if (user.is_current_user) {
                            option.selected = true;
                        }

                        select.appendChild(option);
                    }
                });
            }
        }
    } catch (error) {
        console.error('Error loading PIC marketing options:', error);
    }
}

// Function to load PIC purchasing options
async function loadAdminPurchasingOptions() {
    try {
        const response = await fetch('/marketing/potensi/users');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('adminPurchasingSelect');
            if (select) {
                // Clear existing options except the first one
                select.innerHTML = '<option value="">Pilih PIC purchasing</option>';

                // Add options for purchasing and PIC roles
                data.data.forEach(user => {
                    if (user.role === 'admin_purchasing' || user.role === 'superadmin') {
                        const option = document.createElement('option');
                        option.value = user.id_user;
                        option.textContent = user.nama;
                        select.appendChild(option);
                    }
                });
            }
        }
    } catch (error) {
        console.error('Error loading PIC purchasing options:', error);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load PIC marketing options
    loadAdminMarketingOptions();

    // Load admin purchasing options
    loadAdminPurchasingOptions();

    // Load preview kode proyek
    loadPreviewKodeProyek();

    // Add keypress event listener for harga satuan inputs to allow numbers and comma
    document.addEventListener('keypress', function(e) {
        if (e.target.classList.contains('harga-satuan-input')) {
            // Allow: backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }

            // Allow comma (,) for decimal separator - keyCode 44
            if (e.keyCode === 44) {
                // Check if comma already exists in the input
                const currentValue = e.target.value;
                if (currentValue.includes(',')) {
                    e.preventDefault(); // Prevent multiple commas
                }
                return;
            }

            // Allow numbers (0-9) - keyCode 48-57 for regular numbers, 96-105 for numpad
            if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                return;
            }

            // Block all other characters
            e.preventDefault();
        }
    });

    console.log('Tambah modal initialized');
});

// Function to load preview kode proyek
async function loadPreviewKodeProyek() {
    try {
        const response = await fetch('/marketing/potensi/next-kode');
        const data = await response.json();

        if (data.success) {
            const previewElement = document.getElementById('previewKodeProyek');
            if (previewElement) {
                previewElement.innerHTML = `<i class="fas fa-tag mr-1"></i>${data.kode}`;
            }
        }
    } catch (error) {
        console.error('Error loading preview kode proyek:', error);
    }
}

// ================================
// CUSTOM ALERT SYSTEM
// ================================

// Custom Alert Function
function showCustomAlert(message, type = 'info', title = null, showCancel = false) {
    return new Promise((resolve) => {
        const overlay = document.getElementById('customAlertOverlay');
        const alert = document.getElementById('customAlert');
        const header = document.getElementById('alertHeader');
        const icon = document.getElementById('alertIconClass');
        const titleElement = document.getElementById('alertTitle');
        const messageElement = document.getElementById('alertMessage');
        const confirmBtn = document.getElementById('alertConfirm');
        const cancelBtn = document.getElementById('alertCancel');

        // Set alert type and styling
        const alertConfig = {
            success: {
                headerClass: 'alert-success',
                icon: 'fas fa-check-circle',
                title: title || 'Berhasil',
                confirmText: 'OK',
                confirmClass: 'bg-green-600 hover:bg-green-700 hover:shadow-lg hover:-translate-y-0.5'
            },
            error: {
                headerClass: 'alert-error',
                icon: 'fas fa-exclamation-circle',
                title: title || 'Error',
                confirmText: 'OK',
                confirmClass: 'bg-red-600 hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5'
            },
            warning: {
                headerClass: 'alert-warning',
                icon: 'fas fa-exclamation-triangle',
                title: title || 'Peringatan',
                confirmText: 'OK',
                confirmClass: 'bg-orange-600 hover:bg-orange-700 hover:shadow-lg hover:-translate-y-0.5'
            },
            info: {
                headerClass: 'alert-info',
                icon: 'fas fa-info-circle',
                title: title || 'Informasi',
                confirmText: 'OK',
                confirmClass: 'bg-blue-600 hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5'
            },
            confirm: {
                headerClass: 'alert-warning',
                icon: 'fas fa-question-circle',
                title: title || 'Konfirmasi',
                confirmText: 'Ya',
                confirmClass: 'bg-red-600 hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5'
            }
        };

        const config = alertConfig[type] || alertConfig.info;

        // Apply styling
        header.className = `px-6 py-4 text-white ${config.headerClass}`;
        icon.className = config.icon;
        titleElement.textContent = config.title;
        messageElement.textContent = message;
        confirmBtn.textContent = config.confirmText;
        confirmBtn.className = `px-6 py-2 text-white rounded-lg transition-all duration-200 ${config.confirmClass}`;

        // Show/hide cancel button
        if (showCancel || type === 'confirm') {
            cancelBtn.classList.remove('hidden');
        } else {
            cancelBtn.classList.add('hidden');
        }

        // Show alert with animation
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');

        setTimeout(() => {
            alert.classList.add('show');
        }, 10);

        // Event handlers
        const handleConfirm = () => {
            closeAlert();
            resolve(true);
        };

        const handleCancel = () => {
            closeAlert();
            resolve(false);
        };

        const closeAlert = () => {
            alert.classList.remove('show');
            setTimeout(() => {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }, 300);

            // Remove event listeners
            confirmBtn.removeEventListener('click', handleConfirm);
            cancelBtn.removeEventListener('click', handleCancel);
            overlay.removeEventListener('click', handleOverlayClick);
        };

        const handleOverlayClick = (e) => {
            if (e.target === overlay) {
                closeAlert();
                resolve(false);
            }
        };

        // Add event listeners
        confirmBtn.addEventListener('click', handleConfirm);
        cancelBtn.addEventListener('click', handleCancel);
        overlay.addEventListener('click', handleOverlayClick);

        // Auto close for success messages after 3 seconds
        if (type === 'success' && !showCancel) {
            setTimeout(() => {
                if (overlay.classList.contains('flex')) {
                    closeAlert();
                    resolve(true);
                }
            }, 3000);
        }
    });
}

// Wrapper functions for easier use
function showSuccessAlert(message, title = null) {
    return showCustomAlert(message, 'success', title);
}

function showErrorAlert(message, title = null) {
    return showCustomAlert(message, 'error', title);
}

function showWarningAlert(message, title = null) {
    return showCustomAlert(message, 'warning', title);
}

function showInfoAlert(message, title = null) {
    return showCustomAlert(message, 'info', title);
}

function showConfirmAlert(message, title = null) {
    return showCustomAlert(message, 'confirm', title, true);
}

</script>
