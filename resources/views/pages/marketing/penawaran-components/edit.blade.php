<!-- Modal Edit Penawaran -->
<div id="modalEditPenawaran" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen my-2 sm:my-4 mx-auto flex flex-col h-full">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-4 sm:p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-2 sm:space-x-3 min-w-0">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                </div>
                <div class="min-w-0">
                    <h3 class="text-lg sm:text-xl font-bold truncate">Edit Penawaran</h3>
                    <p class="text-red-100 text-xs sm:text-sm truncate">Edit informasi penawaran</p>
                </div>
            </div>
            <button onclick="closeModal('modalEditPenawaran')" class="text-white hover:bg-white hover:text-red-800 p-1 sm:p-2 flex-shrink-0 ml-2">
                <i class="fas fa-times text-lg sm:text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-4 sm:p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 140px);">
            <form id="editPenawaranForm">
                <!-- Status Selection -->
                <div class="mb-4 sm:mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Penawaran</label>
                    <select id="editStatus" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="proses">Proses</option>
                        <option value="berhasil">Berhasil</option>
                        <option value="gagal">Gagal</option>
                    </select>
                </div>

                <!-- Informasi Dasar -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2 text-sm sm:text-base"></i>
                        Informasi Dasar
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div class="space-y-1">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">No. Penawaran</label>
                            <input type="text" id="editNoPenawaran" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" readonly>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Kode Proyek</label>
                            <input type="text" id="editKodeProyek" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" readonly>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Nama Proyek</label>
                            <input type="text" id="editNamaProyek" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100" readonly>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Klien</label>
                            <input type="text" id="editKlien" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100" readonly>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Tanggal Penawaran</label>
                            <input type="date" id="editTanggalPenawaran" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100" readonly>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Nilai Kalkulasi</label>
                            <input type="number" id="editNilaiKalkulasi" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100" readonly>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Harga Penawaran</label>
                            <input type="number" id="editHargaPenawaran" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100" readonly>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Margin (%)</label>
                            <input type="text" id="editMargin" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100" readonly>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Admin Marketing</label>
                            <input type="text" id="editAdminMarketing" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100" readonly>
                        </div>
                    </div>
                </div>

                <!-- Daftar Barang (Read-only) -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-boxes text-red-600 mr-2 text-sm sm:text-base"></i>
                        Daftar Barang
                    </h4>
                    <div id="editDaftarBarang" class="space-y-3 sm:space-y-4">
                        <!-- Items will be populated here (read-only) -->
                    </div>
                    
                    <!-- Total Keseluruhan -->
                    <div class="mt-4 sm:mt-6 bg-white border border-gray-200 rounded-lg p-3 sm:p-4">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <h5 class="text-base sm:text-lg font-semibold text-gray-800">Total Nilai Penawaran:</h5>
                            <div class="text-xl sm:text-2xl font-bold text-red-600" id="editTotalPenawaran">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Catatan (Read-only) -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-red-600 mr-2 text-sm sm:text-base"></i>
                        Catatan Penawaran
                    </h4>
                    <textarea id="editCatatan" rows="4" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100" readonly placeholder="Tidak ada catatan"></textarea>
                </div>

                <!-- Dokumen Upload -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-file-alt text-red-600 mr-2 text-sm sm:text-base"></i>
                        Dokumen Penawaran
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Surat Penawaran -->
                        <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4">
                            <div class="flex items-center space-x-2 sm:space-x-3 mb-3">
                                <i class="fas fa-file-contract text-red-600 text-base sm:text-lg"></i>
                                <h5 class="font-medium text-gray-800 text-sm sm:text-base">Surat Penawaran</h5>
                            </div>
                            <div id="editSuratPenawaranStatus" class="mb-3">
                                <!-- Current file status will be shown here -->
                            </div>
                            <div class="space-y-2">
                                <input type="file" id="editSuratPenawaranFile" accept=".pdf,.doc,.docx" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <p class="text-xs text-gray-500">Format: PDF, DOC, DOCX | Maksimal: 2MB</p>
                            </div>
                        </div>
                        
                        <!-- Surat Pesanan -->
                        <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4">
                            <div class="flex items-center space-x-2 sm:space-x-3 mb-3">
                                <i class="fas fa-file-invoice text-red-600 text-base sm:text-lg"></i>
                                <h5 class="font-medium text-gray-800 text-sm sm:text-base">Surat Pesanan</h5>
                            </div>
                            <div id="editSuratPesananStatus" class="mb-3">
                                <!-- Current file status will be shown here -->
                            </div>
                            <div class="space-y-2">
                                <input type="file" id="editSuratPesananFile" accept=".pdf,.doc,.docx" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <p class="text-xs text-gray-500">Format: PDF, DOC, DOCX | Maksimal: 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200 flex-shrink-0 gap-3 sm:gap-0">
            <button type="button" onclick="closeModal('modalEditPenawaran')" class="w-full sm:w-auto order-2 sm:order-1 px-4 sm:px-6 py-2 sm:py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-sm sm:text-base">
                Batal
            </button>
            <button type="button" onclick="savePenawaran()" class="w-full sm:w-auto order-1 sm:order-2 px-4 sm:px-6 py-2 sm:py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm sm:text-base">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<script>
let editPenawaranData = {};

function loadEditPenawaranData(data) {
    editPenawaranData = {...data};
    
    // Load status
    document.getElementById('editStatus').value = data.status || 'proses';
    
    // Load basic information
    document.getElementById('editNoPenawaran').value = data.no_penawaran || '';
    document.getElementById('editKodeProyek').value = data.kode_proyek || '';
    document.getElementById('editNamaProyek').value = data.nama_proyek || '';
    document.getElementById('editKlien').value = data.klien || '';
    
    // Format date for input
    if (data.tanggal_penawaran) {
        const dateStr = convertToDateFormat(data.tanggal_penawaran);
        document.getElementById('editTanggalPenawaran').value = dateStr;
    }
    
    document.getElementById('editNilaiKalkulasi').value = data.nilai_kalkulasi || '';
    document.getElementById('editHargaPenawaran').value = data.harga_penawaran || '';
    document.getElementById('editMargin').value = data.margin || '';
    document.getElementById('editAdminMarketing').value = data.admin_marketing || '';
    document.getElementById('editCatatan').value = data.catatan || '';
    
    // Load daftar barang
    loadEditDaftarBarang(data.daftar_barang || []);
    
    // Load dokumen status
    loadEditDokumenStatus('editSuratPenawaranStatus', data.dokumen?.surat_penawaran, 'Surat Penawaran');
    loadEditDokumenStatus('editSuratPesananStatus', data.dokumen?.surat_pesanan, 'Surat Pesanan');
    
    // Calculate total
    calculateTotal();
}

function loadEditDaftarBarang(barangList) {
    const container = document.getElementById('editDaftarBarang');
    container.innerHTML = '';
    
    if (barangList && barangList.length > 0) {
        barangList.forEach((barang, index) => {
            addReadOnlyBarangItem(barang);
        });
    } else {
        container.innerHTML = '<p class="text-gray-500 text-center py-4">Tidak ada data barang</p>';
    }
}

function addReadOnlyBarangItem(barang) {
    const container = document.getElementById('editDaftarBarang');
    
    const barangElement = document.createElement('div');
    barangElement.className = 'bg-white border border-gray-200 rounded-lg p-4';
    barangElement.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
            <div class="md:col-span-2">
                <h5 class="font-medium text-gray-800">${barang.nama}</h5>
            </div>
            <div class="text-center">
                <span class="text-gray-600">${barang.jumlah} ${barang.satuan}</span>
            </div>
            <div class="text-center">
                <span class="text-gray-600">${formatRupiah(barang.harga)}</span>
            </div>
            <div class="text-right">
                <span class="font-semibold text-red-600">${formatRupiah(barang.total)}</span>
            </div>
        </div>
    `;
    
    container.appendChild(barangElement);
}

function calculateTotal() {
    // Calculate total from existing data (read-only)
    let total = 0;
    
    if (editPenawaranData.daftar_barang && editPenawaranData.daftar_barang.length > 0) {
        editPenawaranData.daftar_barang.forEach(barang => {
            total += barang.total || 0;
        });
    }
    
    document.getElementById('editTotalPenawaran').textContent = formatRupiah(editPenawaranData.harga_penawaran || total);
}


function loadEditDokumenStatus(containerId, dokumen, namaFile) {
    const container = document.getElementById(containerId);
    
    if (dokumen && dokumen.status === 'ada') {
        container.innerHTML = `
            <div class="flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded">
                <div>
                    <p class="text-sm text-green-600 font-medium">File tersedia</p>
                    <p class="text-xs text-gray-500">Upload: ${dokumen.tanggal_upload}</p>
                </div>
                <button type="button" onclick="removeCurrentFile('${containerId}')" class="text-red-600 hover:text-red-700">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        `;
    } else {
        container.innerHTML = `
            <p class="text-sm text-gray-500">Belum ada file</p>
        `;
    }
}

function removeCurrentFile(containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = `<p class="text-sm text-gray-500">File akan dihapus setelah disimpan</p>`;
}

function convertToDateFormat(dateStr) {
    // Convert "15 Januari 2024" to "2024-01-15"
    const months = {
        'Januari': '01', 'Februari': '02', 'Maret': '03', 'April': '04',
        'Mei': '05', 'Juni': '06', 'Juli': '07', 'Agustus': '08',
        'September': '09', 'Oktober': '10', 'November': '11', 'Desember': '12'
    };
    
    const parts = dateStr.split(' ');
    if (parts.length === 3) {
        const day = parts[0].padStart(2, '0');
        const month = months[parts[1]];
        const year = parts[2];
        return `${year}-${month}-${day}`;
    }
    return '';
}

function validateFileUpload(fileInput) {
    const file = fileInput.files[0];
    if (file) {
        // Check file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            fileInput.value = '';
            return false;
        }
        
        // Check file type
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Hanya PDF, DOC, dan DOCX yang diperbolehkan.');
            fileInput.value = '';
            return false;
        }
    }
    return true;
}

function savePenawaran() {
    // Get the new status
    const newStatus = document.getElementById('editStatus').value;
    
    // Validate file uploads
    const suratPenawaranFile = document.getElementById('editSuratPenawaranFile');
    const suratPesananFile = document.getElementById('editSuratPesananFile');
    
    if (suratPenawaranFile.files.length > 0 && !validateFileUpload(suratPenawaranFile)) {
        return;
    }
    
    if (suratPesananFile.files.length > 0 && !validateFileUpload(suratPesananFile)) {
        return;
    }
    
    // Here you would typically send only the status and files to your backend
    // For now, we'll just show a success message
    const changedItems = [];
    
    if (newStatus !== editPenawaranData.status) {
        changedItems.push(`Status: ${newStatus}`);
    }
    
    if (suratPenawaranFile.files.length > 0) {
        changedItems.push('Surat Penawaran: File baru');
    }
    
    if (suratPesananFile.files.length > 0) {
        changedItems.push('Surat Pesanan: File baru');
    }
    
    if (changedItems.length > 0) {
        alert(`Perubahan disimpan:\n- ${changedItems.join('\n- ')}`);
    } else {
        alert('Tidak ada perubahan yang dilakukan.');
    }
    
    closeModal('modalEditPenawaran');
}

// Add file validation event listeners
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = ['editSuratPenawaranFile', 'editSuratPesananFile'];
    fileInputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('change', function() {
                validateFileUpload(this);
            });
        }
    });
});

function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// Function to edit penawaran (called from main page)
function editPenawaran(id) {
    // Use the same data structure as detail
    const data = penawaranDetailData && penawaranDetailData[id] ? penawaranDetailData[id] : {
        no_penawaran: `PNW-2024-00${id}`,
        kode_proyek: `PRJ-2024-00${id}`,
        nama_proyek: 'Sample Project',
        klien: 'Sample Client',
        tanggal_penawaran: '15 Januari 2024',
        nilai_kalkulasi: 1000000,
        harga_penawaran: 1200000,
        margin: '20%',
        admin_marketing: 'Admin',
        status: 'proses',
        catatan: 'Sample catatan',
        daftar_barang: [
            { nama: 'Sample Item', jumlah: 1, satuan: 'pcs', harga: 1000000, total: 1000000 }
        ],
        dokumen: {
            surat_penawaran: { status: 'belum', tanggal_upload: null },
            surat_pesanan: { status: 'belum', tanggal_upload: null }
        }
    };
    
    loadEditPenawaranData(data);
    openModal('modalEditPenawaran');
}
</script>