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

<!-- Modal Tambah Potensi -->
<div id="modalTambahPotensi" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Tambah Potensi Proyek</h3>
                    <p class="text-red-100 text-sm">Buat data potensi proyek baru</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahPotensi')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formTambahPotensi" class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Proyek</label>
                            <input type="text" id="tambahIdProyek" name="id_proyek" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan ID proyek">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" id="tambahTanggal" name="tanggal" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <input type="text" id="tambahKabupatenKota" name="kabupaten_kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan kabupaten/kota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi</label>
                            <input type="text" id="tambahNamaInstansi" name="nama_instansi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama instansi">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Proyek</label>
                            <input type="text" id="tambahNamaProyek" name="nama_proyek" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama proyek">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <select id="tambahJenisPengadaan" name="jenis_pengadaan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih jenis pengadaan</option>
                                <option value="Pelelangan Umum">Pelelangan Umum</option>
                                <option value="Pelelangan Terbatas">Pelelangan Terbatas</option>
                                <option value="Pemilihan Langsung">Pemilihan Langsung</option>
                                <option value="Penunjukan Langsung">Penunjukan Langsung</option>
                                <option value="Tender">Tender</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIC Marketing</label>
                            <input type="text" id="tambahAdminMarketing" name="admin_marketing" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" value="[Nama User Login]" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIC Purchasing</label>
                            <select id="tambahAdminPurchasing" name="admin_purchasing" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih PIC purchasing</option>
                                <!-- Options will be populated by JS -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Potensi</label>
                            <input type="number" id="tambahTahunPotensi" name="tahun_potensi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="2024" min="2020" max="2030">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea id="tambahCatatan" name="catatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan catatan proyek..."></textarea>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-boxes text-red-600 mr-2"></i>
                            Daftar Barang
                            <span id="barangCounter" class="ml-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full">0</span>
                        </h4>
                        <button type="button" onclick="tambahBarang()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center hover-effect-btn">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Barang
                        </button>
                    </div>

                    <div id="daftarBarang" class="space-y-4">
                        <!-- Placeholder untuk barang -->
                        <div id="placeholderBarang" class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                            <i class="fas fa-cube text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm">Belum ada barang. Klik "Tambah Barang" untuk menambahkan.</p>
                        </div>
                    </div>

                    <!-- Total Keseluruhan -->
                    <div class="mt-6 bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <h5 class="text-lg font-semibold text-gray-800">Total Keseluruhan:</h5>
                            <div class="text-2xl font-bold text-red-600" id="totalKeseluruhan">Rp 0</div>
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
                                <input type="file" id="tambahSuratPenawaran" name="surat_penawaran"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx">
                                <button type="button" onclick="clearFile('tambahSuratPenawaran')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Surat Kontrak -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surat Kontrak</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" id="tambahSuratKontrak" name="surat_kontrak"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx">
                                <button type="button" onclick="clearFile('tambahSuratKontrak')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Surat Persetujuan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surat Persetujuan</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" id="tambahSuratPersetujuan" name="surat_persetujuan"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx">
                                <button type="button" onclick="clearFile('tambahSuratPersetujuan')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Dokumen Lainnya -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Lainnya</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" id="tambahDokumenLainnya" name="dokumen_lainnya"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <button type="button" onclick="clearFile('tambahDokumenLainnya')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalTambahPotensi')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Batal
            </button>
            <button type="button" onclick="submitFormTambahPotensi()" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center hover-effect-btn">
                <i class="fas fa-save mr-2"></i>
                Simpan Potensi
            </button>
        </div>
    </div>
</div>

<script>
let barangCount = 0;

// Function to tambah barang
function tambahBarang() {
    barangCount++;
    updateBarangCounter();

    const daftarBarang = document.getElementById('daftarBarang');
    const placeholder = document.getElementById('placeholderBarang');

    if (placeholder) {
        placeholder.style.display = 'none';
    }

    const barangItem = document.createElement('div');
    barangItem.className = 'barang-item bg-white border border-gray-200 rounded-lg p-4';
    barangItem.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h5 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-cube text-red-600 mr-2"></i>
                Barang ${barangCount}
            </h5>
            <button type="button" onclick="hapusBarang(this)" class="text-red-600 hover:bg-red-100 p-1 rounded transition-colors">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Spesifikasi</label>
                <input type="text" name="spesifikasi[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Spesifikasi barang">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="0" min="1" onchange="hitungTotal()">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" name="satuan[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="pcs/unit">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Harga Satuan</label>
                <input type="number" name="harga_satuan[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="0" min="0" onchange="hitungTotal()">
            </div>
        </div>
        <div class="mt-2 text-right">
            <span class="text-sm text-gray-600">Subtotal: </span>
            <span class="font-semibold text-red-600 subtotal">Rp 0</span>
        </div>
    `;

    daftarBarang.appendChild(barangItem);
}

// Function to hapus barang
function hapusBarang(button) {
    const barangItem = button.closest('.barang-item');
    barangItem.remove();

    barangCount--;
    updateBarangCounter();
    hitungTotal();

    // Show placeholder if no items
    const daftarBarang = document.getElementById('daftarBarang');
    const placeholder = document.getElementById('placeholderBarang');

    if (daftarBarang.querySelectorAll('.barang-item').length === 0 && placeholder) {
        placeholder.style.display = 'block';
    }
}

// Function to update barang counter
function updateBarangCounter() {
    const counter = document.getElementById('barangCounter');
    if (counter) {
        counter.textContent = barangCount;
        counter.classList.add('scale-110');
        setTimeout(() => {
            counter.classList.remove('scale-110');
        }, 200);
    }
}

// Function to hitung total
function hitungTotal() {
    let totalKeseluruhan = 0;

    const barangItems = document.querySelectorAll('.barang-item');
    barangItems.forEach(item => {
        const jumlah = parseInt(item.querySelector('input[name="jumlah[]"]').value) || 0;
        const harga = parseInt(item.querySelector('input[name="harga_satuan[]"]').value) || 0;
        const subtotal = jumlah * harga;

        // Update subtotal display
        const subtotalElement = item.querySelector('.subtotal');
        if (subtotalElement) {
            subtotalElement.textContent = formatRupiah(subtotal);
        }

        totalKeseluruhan += subtotal;
    });

    // Update total keseluruhan
    const totalElement = document.getElementById('totalKeseluruhan');
    if (totalElement) {
        totalElement.textContent = formatRupiah(totalKeseluruhan);
    }
}

// Function to clear file input
function clearFile(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.value = '';
    }
}

// Function to submit form
function submitFormTambahPotensi() {
    const form = document.getElementById('formTambahPotensi');
    const formData = new FormData(form);

    // Set potensi to 'ya' since this is potensi page
    formData.append('potensi', 'ya');

    // Validate required fields
    const requiredFields = ['id_proyek', 'nama_proyek', 'nama_instansi', 'tanggal'];
    let isValid = true;

    requiredFields.forEach(field => {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input || !input.value.trim()) {
            isValid = false;
            if (input) {
                input.classList.add('border-red-500');
                setTimeout(() => {
                    input.classList.remove('border-red-500');
                }, 3000);
            }
        }
    });

    if (!isValid) {
        showCustomAlert('error', 'Mohon lengkapi semua field yang wajib diisi');
        return;
    }

    // Submit form
    fetch('/marketing/potensi', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('modalTambahPotensi');
            showSuccessModal('Potensi proyek berhasil ditambahkan!');

            // Reset form
            form.reset();
            document.getElementById('daftarBarang').innerHTML = '<div id="placeholderBarang" class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg"><i class="fas fa-cube text-3xl text-gray-400 mb-2"></i><p class="text-sm">Belum ada barang. Klik "Tambah Barang" untuk menambahkan.</p></div>';
            barangCount = 0;
            updateBarangCounter();
            hitungTotal();

            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showCustomAlert('error', data.message || 'Terjadi kesalahan saat menyimpan data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCustomAlert('error', 'Terjadi kesalahan sistem');
    });
}

// Function to show custom alert
function showCustomAlert(type, message, title = null) {
    const overlay = document.getElementById('customAlertOverlay');
    const alert = document.getElementById('customAlert');
    const header = document.getElementById('alertHeader');
    const icon = document.getElementById('alertIconClass');
    const titleEl = document.getElementById('alertTitle');
    const messageEl = document.getElementById('alertMessage');

    // Set alert type styling
    header.className = 'px-6 py-4 text-white';

    switch(type) {
        case 'success':
            header.classList.add('alert-success');
            icon.className = 'fas fa-check-circle text-lg';
            titleEl.textContent = title || 'Berhasil';
            break;
        case 'error':
            header.classList.add('alert-error');
            icon.className = 'fas fa-exclamation-circle text-lg';
            titleEl.textContent = title || 'Error';
            break;
        case 'warning':
            header.classList.add('alert-warning');
            icon.className = 'fas fa-exclamation-triangle text-lg';
            titleEl.textContent = title || 'Peringatan';
            break;
        default:
            header.classList.add('alert-info');
            icon.className = 'fas fa-info-circle text-lg';
            titleEl.textContent = title || 'Informasi';
    }

    messageEl.textContent = message;

    // Show alert
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    setTimeout(() => {
        alert.classList.add('show');
    }, 10);

    // Auto hide after 5 seconds for success
    if (type === 'success') {
        setTimeout(() => {
            hideCustomAlert();
        }, 5000);
    }
}

// Function to hide custom alert
function hideCustomAlert() {
    const overlay = document.getElementById('customAlertOverlay');
    const alert = document.getElementById('customAlert');

    alert.classList.remove('show');
    setTimeout(() => {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
    }, 300);
}

// Event listeners for alert buttons
document.getElementById('alertConfirm').addEventListener('click', hideCustomAlert);
document.getElementById('alertCancel').addEventListener('click', hideCustomAlert);

// Format rupiah function
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// Initialize on modal open
function initTambahPotensiModal() {
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tambahTanggal').value = today;

    // Set default tahun potensi
    const currentYear = new Date().getFullYear();
    document.getElementById('tambahTahunPotensi').value = currentYear;

    // Load PIC purchasing options
    loadAdminPurchasingOptions('tambahAdminPurchasing');
}

// Function to load PIC purchasing options
function loadAdminPurchasingOptions(selectId) {
    // This would typically fetch from server
    // For now, add sample options
    const select = document.getElementById(selectId);
    if (select) {
        select.innerHTML = `
            <option value="">Pilih PIC purchasing</option>
            <option value="1">PIC Purchasing 1</option>
            <option value="2">PIC Purchasing 2</option>
            <option value="3">PIC Purchasing 3</option>
        `;
    }
}
</script>
