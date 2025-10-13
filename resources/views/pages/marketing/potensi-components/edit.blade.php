<!-- Modal Edit Potensi -->
<div id="modalEditPotensi" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl mx-auto my-4 flex flex-col" style="max-height: calc(100vh - 2rem);">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Edit Potensi Proyek</h3>
                    <p class="text-red-100 text-sm">Ubah data potensi proyek</p>
                </div>
            </div>
            <button onclick="closeModal('modalEditPotensi')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1">
            <form id="formEditPotensi" class="space-y-6">
                <!-- Hidden ID -->
                <input type="hidden" id="editPotensiId" name="id">

                <!-- Informasi Dasar -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Proyek</label>
                            <input type="text" id="editPotensiKodeProyek" name="kode_proyek" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" placeholder="Kode proyek" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" id="editPotensiTanggal" name="tanggal" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <input type="text" id="editPotensiKabupatenKota" name="kab_kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan kabupaten/kota" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi <span class="text-red-500">*</span></label>
                            <input type="text" id="editPotensiNamaInstansi" name="instansi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama instansi" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <select id="editPotensiJenisPengadaan" name="jenis_pengadaan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
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
                            <input type="text" id="editPotensiAdminMarketing" name="admin_marketing" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" value="[Nama User Login]" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIC Purchasing</label>
                            <select id="editPotensiAdminPurchasing" name="admin_purchasing" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Pilih PIC purchasing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Potensi</label>
                            <input type="number" id="editPotensiTahunPotensi" name="tahun_potensi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="2024" min="2020" max="2030">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea id="editPotensiCatatan" name="catatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan catatan proyek..."></textarea>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-boxes text-red-600 mr-2"></i>
                            Daftar Barang
                        </h4>
                        <button type="button" onclick="tambahBarangEditPotensi()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Barang
                        </button>
                    </div>

                    <div id="daftarBarangEditPotensi" class="space-y-4">
                        <!-- Items will be populated here -->
                    </div>

                    <!-- Total Keseluruhan -->
                    <div class="mt-6 bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <h5 class="text-lg font-semibold text-gray-800">Total Keseluruhan:</h5>
                            <div class="text-2xl font-bold text-red-600" id="totalKeseluruhanEditPotensi">Rp 0</div>
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
                                <input type="file" id="editPotensiSuratPenawaran" name="surat_penawaran"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx">
                                <button type="button" onclick="clearFile('editPotensiSuratPenawaran')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="editPotensiSuratPenawaranPreview" class="mt-2 text-sm text-gray-600 hidden">
                                <i class="fas fa-file-pdf mr-1"></i>
                                <span class="filename">No file selected</span>
                            </div>
                        </div>

                        <!-- Surat Pesanan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surat Pesanan</label>
                            <div class="flex items-center space-x-2">
                                <input type="file" id="editPotensiSuratPesanan" name="surat_pesanan"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                       accept=".pdf,.doc,.docx">
                                <button type="button" onclick="clearFile('editPotensiSuratPesanan')"
                                        class="px-3 py-3 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="editPotensiSuratPesananPreview" class="mt-2 text-sm text-gray-600 hidden">
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
                                    <span id="currentSuratPesanan" class="text-gray-600 font-mono text-xs">Loading...</span>
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
            <button type="button" onclick="closeModal('modalEditPotensi')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Batal
            </button>
            <button type="button" onclick="submitFormEditPotensi()" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Update Potensi
            </button>
        </div>
    </div>
</div>

<script>
let barangEditPotensiCount = 0;

// Function to tambah barang edit potensi
function tambahBarangEditPotensi() {
    barangEditPotensiCount++;

    const daftarBarang = document.getElementById('daftarBarangEditPotensi');

    const barangItem = document.createElement('div');
    barangItem.className = 'barang-item bg-white border border-gray-200 rounded-lg p-4';
    barangItem.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h5 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-cube text-red-600 mr-2"></i>
                Barang ${barangEditPotensiCount}
            </h5>
            <button type="button" onclick="hapusBarangEditPotensi(this)" class="text-red-600 hover:bg-red-100 p-1 rounded transition-colors">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Barang</label>
                <input type="text" name="nama_barang[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Nama barang">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="0" min="1" onchange="hitungTotalEditPotensi()">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" name="satuan[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="pcs/unit">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Harga Satuan</label>
                <input type="number" name="harga_satuan[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="0" min="0" onchange="hitungTotalEditPotensi()">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Spesifikasi</label>
                <input type="text" name="spesifikasi[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Spesifikasi barang">
            </div>
        </div>
        <div class="mt-2 text-right">
            <span class="text-sm text-gray-600">Subtotal: </span>
            <span class="font-semibold text-red-600 subtotal">Rp 0</span>
        </div>
    `;

    daftarBarang.appendChild(barangItem);
}

// Function to hapus barang edit potensi
function hapusBarangEditPotensi(button) {
    const barangItem = button.closest('.barang-item');
    barangItem.remove();
    hitungTotalEditPotensi();
}

// Function to hitung total edit potensi
function hitungTotalEditPotensi() {
    let totalKeseluruhan = 0;

    const barangItems = document.querySelectorAll('#daftarBarangEditPotensi .barang-item');
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
    const totalElement = document.getElementById('totalKeseluruhanEditPotensi');
    if (totalElement) {
        totalElement.textContent = formatRupiah(totalKeseluruhan);
    }
}

// Function to load edit potensi data
function loadEditPotensiData(data) {
    console.log('Loading edit potensi data:', data);
    console.log('Data id_admin_purchasing:', data.id_admin_purchasing);
    console.log('Data tahun_potensi:', data.tahun_potensi);

    // Load basic information
    document.getElementById('editPotensiId').value = data.id || '';
    document.getElementById('editPotensiKodeProyek').value = data.kode_proyek || data.kode || '';
    document.getElementById('editPotensiTanggal').value = data.tanggal || data.deadline || '';
    document.getElementById('editPotensiKabupatenKota').value = data.kabupaten_kota || data.kabupaten || '';
    document.getElementById('editPotensiNamaInstansi').value = data.instansi || '';
    document.getElementById('editPotensiJenisPengadaan').value = data.jenis_pengadaan || '';
    document.getElementById('editPotensiAdminMarketing').value = data.admin_marketing || '';
    document.getElementById('editPotensiTahunPotensi').value = data.tahun_potensi || data.tahun || '';
    document.getElementById('editPotensiCatatan').value = data.catatan || '';

    // Load PIC purchasing options and set selected
    loadEditAdminPurchasingOptions();
    setTimeout(() => {
        const adminPurchasingSelect = document.getElementById('editPotensiAdminPurchasing');
        if (adminPurchasingSelect && data.id_admin_purchasing) {
            adminPurchasingSelect.value = data.id_admin_purchasing;
            console.log('Set PIC purchasing to:', data.id_admin_purchasing);
            console.log('Current select value:', adminPurchasingSelect.value);
        }
    }, 500);

    // Clear and load barang data
    const daftarBarang = document.getElementById('daftarBarangEditPotensi');
    daftarBarang.innerHTML = '';
    barangEditPotensiCount = 0;

    if (data.daftar_barang && data.daftar_barang.length > 0) {
        data.daftar_barang.forEach(barang => {
            tambahBarangEditPotensi();
            const latestItem = daftarBarang.lastElementChild;
            if (latestItem) {
                latestItem.querySelector('input[name="nama_barang[]"]').value = barang.nama_barang || '';
                latestItem.querySelector('input[name="spesifikasi[]"]').value = barang.spesifikasi || '';
                latestItem.querySelector('input[name="jumlah[]"]').value = barang.jumlah || '';
                latestItem.querySelector('input[name="satuan[]"]').value = barang.satuan || '';
                latestItem.querySelector('input[name="harga_satuan[]"]').value = barang.harga_satuan || '';
            }
        });
    }

    // Calculate total
    hitungTotalEditPotensi();

    // Load current documents
    loadCurrentDocuments(data);
}

// Function to load current documents
function loadCurrentDocuments(data) {
    // Load penawaran data if available
    if (data.penawaran && data.penawaran.id) {
        // Show penawaran status
        const statusInfo = document.getElementById('penawaranStatusInfo');
        const statusElement = document.getElementById('penawaranStatus');
        if (statusInfo && statusElement) {
            statusInfo.classList.remove('hidden');
            statusElement.textContent = data.penawaran.status || 'Belum Ada';
        }

        // Load documents from penawaran
        loadPenawaranDocuments(data.penawaran.id);
    } else {
        // Hide status info if no penawaran
        const statusInfo = document.getElementById('penawaranStatusInfo');
        if (statusInfo) {
            statusInfo.classList.add('hidden');
        }

        // Set default state for document displays
        setDocumentDisplay('currentSuratPenawaran', 'downloadSuratPenawaran', null);
        setDocumentDisplay('currentSuratPesanan', 'downloadSuratPesanan', null);
    }
}

// Function to load penawaran documents
async function loadPenawaranDocuments(penawaranId) {
    try {
        const response = await fetch(`/marketing/penawaran/project/${penawaranId}/data`);

        if (response.ok) {
            const data = await response.json();

            if (data.success && data.data) {
                // Update document displays
                setDocumentDisplay('currentSuratPenawaran', 'downloadSuratPenawaran', data.data.surat_penawaran);
                setDocumentDisplay('currentSuratPesanan', 'downloadSuratPesanan', data.data.surat_pesanan);
            }
        }
    } catch (error) {
        console.error('Error loading penawaran documents:', error);
        setDocumentDisplay('currentSuratPenawaran', 'downloadSuratPenawaran', null);
        setDocumentDisplay('currentSuratPesanan', 'downloadSuratPesanan', null);
    }
}

// Helper function to set document display
function setDocumentDisplay(displayElementId, downloadElementId, filename) {
    const displayElement = document.getElementById(displayElementId);
    const downloadElement = document.getElementById(downloadElementId);

    if (displayElement && downloadElement) {
        if (filename && filename !== 'null' && filename !== '') {
            displayElement.textContent = filename;
            downloadElement.classList.remove('hidden');
            downloadElement.href = '#'; // Will be set by onclick handler
        } else {
            displayElement.textContent = 'Tidak ada file';
            downloadElement.classList.add('hidden');
        }
    }
}

// Function to download file
function downloadFile(fileType) {
    const potensiId = document.getElementById('editPotensiId').value;

    if (fileType === 'surat_penawaran' || fileType === 'surat_pesanan') {
        // For penawaran documents, use penawaran download route
        const url = `/marketing/penawaran/project/${potensiId}/download/${fileType === 'surat_penawaran' ? 'penawaran' : 'pesanan'}`;
        window.open(url, '_blank');
    } else {
        // For other documents, use potensi download route
        const url = `/marketing/potensi/${potensiId}/download/${fileType}`;
        window.open(url, '_blank');
    }
}

// Function to clear file input
function clearFile(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.value = '';

        // Also clear preview if exists
        const previewId = inputId + 'Preview';
        const preview = document.getElementById(previewId);
        if (preview) {
            preview.classList.add('hidden');
        }
    }
}

// Function to submit form edit potensi
function submitFormEditPotensi() {
    const form = document.getElementById('formEditPotensi');
    const formData = new FormData(form);
    const potensiId = document.getElementById('editPotensiId').value;

    // Debug: Log form data sebelum submit
    console.log('Form data sebelum submit:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    // Set potensi to 'ya' since this is potensi page
    formData.append('potensi', 'ya');
    formData.append('_method', 'PUT');

    // Debug: Check specific fields
    const adminPurchasing = document.getElementById('editPotensiAdminPurchasing').value;
    const tahunPotensi = document.getElementById('editPotensiTahunPotensi').value;
    console.log('PIC Purchasing value:', adminPurchasing);
    console.log('Tahun Potensi value:', tahunPotensi);

    // Make sure the values are properly added to formData
    if (adminPurchasing) {
        formData.set('admin_purchasing', adminPurchasing);
    }
    if (tahunPotensi) {
        formData.set('tahun_potensi', tahunPotensi);
    }

    // Validate required fields
    const requiredFields = [
        { name: 'tanggal', element: document.getElementById('editPotensiTanggal') },
        { name: 'instansi', element: document.getElementById('editPotensiNamaInstansi') },
        { name: 'kab_kota', element: document.getElementById('editPotensiKabupatenKota') }
    ];

    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.element || !field.element.value.trim()) {
            isValid = false;
            if (field.element) {
                field.element.classList.add('border-red-500');
                setTimeout(() => {
                    field.element.classList.remove('border-red-500');
                }, 3000);
            }
        }
    });

    if (!isValid) {
        showCustomAlert('error', 'Mohon lengkapi semua field yang wajib diisi');
        return;
    }

    // Submit form
    fetch(`/marketing/potensi/${potensiId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('modalEditPotensi');
            showSuccessModal('Potensi proyek berhasil diperbarui!');

            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showCustomAlert('error', data.message || 'Terjadi kesalahan saat memperbarui data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCustomAlert('error', 'Terjadi kesalahan sistem');
    });
}

// Function to show custom alert (if not already defined)
if (typeof showCustomAlert === 'undefined') {
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
    }
}

// Function to load PIC purchasing options (same as proyek)
async function loadEditAdminPurchasingOptions() {
    try {
        const response = await fetch('/marketing/potensi/users/select');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('editPotensiAdminPurchasing');
            if (select) {
                // Store current value
                const currentValue = select.value;

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

                // Restore current value if it exists
                if (currentValue) {
                    select.value = currentValue;
                }
            }
        }
    } catch (error) {
        console.error('Error loading PIC purchasing options for edit potensi:', error);
    }
}

if (typeof loadAdminPurchasingOptions === 'undefined') {
    function loadAdminPurchasingOptions(selectId) {
        // Legacy fallback function - redirects to new implementation
        loadEditAdminPurchasingOptions();
    }
}

// Format rupiah function (if not already defined)
if (typeof formatRupiah === 'undefined') {
    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }
}

// Add file preview functionality
document.addEventListener('DOMContentLoaded', function() {
    // Load PIC purchasing options on page load
    loadEditAdminPurchasingOptions();

    // File input preview handlers
    const fileInputs = [
        { id: 'editPotensiSuratPenawaran', previewId: 'editPotensiSuratPenawaranPreview' },
        { id: 'editPotensiSuratPesanan', previewId: 'editPotensiSuratPesananPreview' }
    ];

    fileInputs.forEach(({ id, previewId }) => {
        const input = document.getElementById(id);
        const preview = document.getElementById(previewId);

        if (input && preview) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const filenameSpan = preview.querySelector('.filename');

                if (file && filenameSpan) {
                    filenameSpan.textContent = file.name;
                    preview.classList.remove('hidden');
                } else if (filenameSpan) {
                    filenameSpan.textContent = 'No file selected';
                    preview.classList.add('hidden');
                }
            });
        }
    });
});
</script>
