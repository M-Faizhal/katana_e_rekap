<!-- Modal Tambah Penawaran -->
<div id="modalTambahPenawaran"  class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
        </div>
        <div>
            <h3 class="text-xl font-bold">Tambah Penawaran Baru</h3>
            <p class="text-red-100 text-sm">Buat penawaran proyek baru</p>
        </div>
    </div>
    <button onclick="closeModal('modalTambahPenawaran')" class="text-white hover:bg-white hover:text-red-800 p-2">
        <i class="fas fa-times text-2xl"></i>
    </button>
</div>


        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formTambahPenawaran" class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Penawaran</label>
                            <input type="text" name="kode" id="kodeGenerated" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" placeholder="Auto-generated..." readonly>
                            <small class="text-gray-500 text-xs mt-1">Kode akan digenerate otomatis</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten_kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan kabupaten/kota" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi</label>
                            <input type="text" name="nama_instansi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama instansi" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <select name="jenis_pengadaan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
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
                            <select name="admin_purchasing" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
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
                            <input type="date" name="deadline" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        </div>
                    </div>
                    
                    <!-- Upload Surat Pesanan -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-upload text-red-600 mr-1"></i>
                            Surat Pesanan <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors duration-200">
                            <input type="file" name="surat_pesanan" id="suratPesanan" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required onchange="handleFileUpload(this)">
                            <label for="suratPesanan" class="cursor-pointer">
                                <div id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 font-medium">Klik untuk upload surat pesanan</p>
                                    <p class="text-sm text-gray-500 mt-1">PDF, Word, atau Image (Max: 2MB)</p>
                                </div>
                                <div id="filePreview" class="hidden">
                                    <i class="fas fa-file-alt text-4xl text-green-500 mb-3"></i>
                                    <p class="text-green-600 font-medium" id="fileName"></p>
                                    <p class="text-sm text-gray-500" id="fileSize"></p>
                                    <button type="button" onclick="removeFile()" class="mt-2 text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash mr-1"></i>Hapus File
                                    </button>
                                </div>
                            </label>
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
                        <button type="button" onclick="tambahBarang()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Barang
                        </button>
                    </div>
                    
                    <div id="daftarBarang" class="space-y-4">
                        <!-- Item Barang Template -->
                        <div class="barang-item bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-medium text-gray-800">Item 1</h5>
                                <button type="button" onclick="hapusBarang(this)" class="text-red-600 hover:bg-red-100 rounded-lg p-2 transition-colors duration-200" style="display: none;">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                                    <input type="text" name="barang[0][nama]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Nama barang/jasa" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                    <input type="number" name="barang[0][jumlah]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm jumlah-input" placeholder="0" min="1" required onchange="hitungTotal(this)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                    <select name="barang[0][satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
                                        <option value="">Pilih satuan</option>
                                        <option value="Unit">Unit</option>
                                        <option value="Set">Set</option>
                                        <option value="Buah">Buah</option>
                                        <option value="Paket">Paket</option>
                                        <option value="License">License</option>
                                        <option value="Layanan">Layanan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan</label>
                                    <input type="number" name="barang[0][harga_satuan]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm harga-satuan-input" placeholder="0" min="0" required onchange="hitungTotal(this)">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-sm total-harga" readonly placeholder="Rp 0">
                            </div>
                        </div>
                    </div>

                    <!-- Total Keseluruhan -->
                    <div class="mt-6 bg-white border-2 border-red-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-gray-800">Total Keseluruhan</span>
                            <span id="totalKeseluruhan" class="text-2xl font-bold text-red-600">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-red-600 mr-2"></i>
                        Catatan
                    </h4>
                    <textarea name="catatan" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan catatan tambahan (opsional)"></textarea>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalTambahPenawaran')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Batal
            </button>
            <button type="submit" form="formTambahPenawaran" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Simpan Penawaran
            </button>
        </div>
    </div>
</div>

<script>
let itemCounter = 1;

// Generate kode penawaran otomatis saat modal dibuka
function generateKodePenawaran() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    const kode = `PNW-${year}${month}${day}-${hours}${minutes}${seconds}`;
    document.getElementById('kodeGenerated').value = kode;
}

// File upload handler
function handleFileUpload(input) {
    const file = input.files[0];
    const uploadArea = document.getElementById('uploadArea');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
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
        
        // Show file preview
        uploadArea.classList.add('hidden');
        filePreview.classList.remove('hidden');
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
    }
}

// Remove file
function removeFile() {
    const input = document.getElementById('suratPesanan');
    const uploadArea = document.getElementById('uploadArea');
    const filePreview = document.getElementById('filePreview');
    
    input.value = '';
    uploadArea.classList.remove('hidden');
    filePreview.classList.add('hidden');
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function tambahBarang() {
    const container = document.getElementById('daftarBarang');
    const template = document.querySelector('.barang-item').cloneNode(true);
    
    // Update header dan input names
    template.querySelector('h5').textContent = `Item ${itemCounter + 1}`;
    template.querySelectorAll('input, select').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace('[0]', `[${itemCounter}]`));
        }
        if (input.type !== 'hidden') {
            input.value = '';
        }
    });
    
    // Show delete button for new items
    template.querySelector('button[onclick="hapusBarang(this)"]').style.display = 'block';
    
    container.appendChild(template);
    itemCounter++;
    updateDeleteButtons();
}

function hapusBarang(button) {
    const item = button.closest('.barang-item');
    item.remove();
    updateItemNumbers();
    hitungTotalKeseluruhan();
    updateDeleteButtons();
}

function updateItemNumbers() {
    const items = document.querySelectorAll('.barang-item');
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

function updateDeleteButtons() {
    const items = document.querySelectorAll('.barang-item');
    items.forEach((item, index) => {
        const deleteButton = item.querySelector('button[onclick="hapusBarang(this)"]');
        if (items.length > 1) {
            deleteButton.style.display = 'block';
        } else {
            deleteButton.style.display = 'none';
        }
    });
}

function hitungTotal(input) {
    const row = input.closest('.barang-item');
    const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
    const hargaSatuan = parseFloat(row.querySelector('.harga-satuan-input').value) || 0;
    const total = jumlah * hargaSatuan;
    
    row.querySelector('.total-harga').value = formatRupiah(total);
    hitungTotalKeseluruhan();
}

function hitungTotalKeseluruhan() {
    let total = 0;
    document.querySelectorAll('.barang-item').forEach(item => {
        const jumlah = parseFloat(item.querySelector('.jumlah-input').value) || 0;
        const hargaSatuan = parseFloat(item.querySelector('.harga-satuan-input').value) || 0;
        total += jumlah * hargaSatuan;
    });
    
    document.getElementById('totalKeseluruhan').textContent = formatRupiah(total);
}

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

// Form submission
document.getElementById('formTambahPenawaran').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Generate kode penawaran jika belum ada
    if (!document.getElementById('kodeGenerated').value) {
        generateKodePenawaran();
    }
    
    // Simulate form submission
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitButton.disabled = true;
    
    setTimeout(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        closeModal('modalTambahPenawaran');
        
        // Show success message
        alert('Penawaran berhasil ditambahkan!');
        
        // Reset form
        this.reset();
        removeFile(); // Reset file upload
        
        // Reset items to 1
        const container = document.getElementById('daftarBarang');
        const items = container.querySelectorAll('.barang-item');
        for (let i = 1; i < items.length; i++) {
            items[i].remove();
        }
        itemCounter = 1;
        updateDeleteButtons();
        hitungTotalKeseluruhan();
    }, 2000);
});

// Generate kode saat modal dibuka
document.addEventListener('DOMContentLoaded', function() {
    // Override openModal function untuk modal tambah
    const originalOpenModal = window.openModal;
    window.openModal = function(modalId) {
        if (modalId === 'modalTambahPenawaran') {
            generateKodePenawaran();
        }
        if (originalOpenModal) {
            originalOpenModal(modalId);
        } else {
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementById(modalId).classList.add('flex');
        }
    };
});
</script>
