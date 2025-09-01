<!-- Modal Detail Proyek -->
<div id="modalDetailProyek" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Detail Proyek</h3>
                    <p class="text-red-100 text-sm">Informasi lengkap proyek</p>
                </div>
            </div>
            <button onclick="closeModal('modalDetailProyek')" class="text-white hover:bg-white hover:text-red-800 p-2">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <!-- Status Badge -->
            <div class="mb-6">
                <span id="detailStatusBadge" class="inline-flex px-4 py-2 text-sm font-medium rounded-full">
                    <!-- Status will be set dynamically -->
                </span>
            </div>

            <!-- Informasi Dasar -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-red-600 mr-2"></i>
                    Informasi Dasar
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">ID Proyek</label>
                        <p id="detailIdProyek" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Tanggal</label>
                        <p id="detailTanggal" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Kabupaten/Kota</label>
                        <p id="detailKabupatenKota" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Nama Instansi</label>
                        <p id="detailNamaInstansi" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Jenis Pengadaan</label>
                        <p id="detailJenisPengadaan" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Admin Marketing</label>
                        <p id="detailAdminMarketing" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Admin Purchasing</label>
                        <p id="detailAdminPurchasing" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Potensi</label>
                        <p id="detailPotensi" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Tahun Potensi</label>
                        <p id="detailTahunPotensi" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-boxes text-red-600 mr-2"></i>
                    Daftar Barang
                </h4>
                <div id="detailDaftarBarang" class="space-y-4">
                    <!-- Items will be populated here -->
                </div>

                <!-- Total Keseluruhan -->
                <div class="mt-6 bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-gray-800">Total Keseluruhan:</h5>
                        <div class="text-2xl font-bold text-red-600" id="detailTotalKeseluruhan">Rp 0</div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div id="detailCatatanSection" class="bg-gray-50 rounded-xl p-6 mb-6" style="display: none;">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-red-600 mr-2"></i>
                    Catatan
                </h4>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <p id="detailCatatan" class="text-gray-700 leading-relaxed">-</p>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-file-alt text-red-600 mr-2"></i>
                    Dokumen Proyek
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-2">
                            <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                            <h5 class="font-medium text-gray-800">Surat Penawaran</h5>
                        </div>
                        <div id="detailSuratPenawaran">
                            <p class="text-sm text-gray-500 mb-2">Status: <span id="statusSuratPenawaran" class="text-gray-600 font-medium">Loading...</span></p>
                            <div class="space-y-2">
                                <p class="text-xs text-gray-600 font-mono" id="namaSuratPenawaran">Loading...</p>
                                <button id="downloadSuratPenawaranBtn" onclick="downloadDocument('penawaran')" class="text-red-600 hover:text-red-700 text-sm font-medium hidden">
                                    <i class="fas fa-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-2">
                            <i class="fas fa-file-pdf text-purple-600 text-lg"></i>
                            <h5 class="font-medium text-gray-800">Surat Pesanan</h5>
                        </div>
                        <div id="detailSuratPesanan">
                            <p class="text-sm text-gray-500 mb-2">Status: <span id="statusSuratPesanan" class="text-gray-600 font-medium">Loading...</span></p>
                            <div class="space-y-2">
                                <p class="text-xs text-gray-600 font-mono" id="namaSuratPesanan">Loading...</p>
                                <button id="downloadSuratPesananBtn" onclick="downloadDocument('pesanan')" class="text-purple-600 hover:text-purple-700 text-sm font-medium hidden">
                                    <i class="fas fa-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200 flex-shrink-0">

            <button type="button" onclick="closeModal('modalDetailProyek')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
let currentDetailProyekId = null;
let currentDetailDocuments = null;

function printDetail() {
    window.print();
}

function exportPDF() {
    alert('Exporting to PDF...');
}

function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// Function to load documents for the detail modal
async function loadDetailDocuments(proyekId) {
    try {
        currentDetailProyekId = proyekId;
        console.log('Loading documents for project:', proyekId);
        
        const response = await fetch(`/marketing/penawaran/project/${proyekId}/data`);
        
        if (!response.ok) {
            console.warn('No penawaran data found for project', proyekId);
            setDocumentStatus('tidak_ada');
            return;
        }
        
        const data = await response.json();
        console.log('Document data received:', data);
        
        if (data.success && data.data) {
            currentDetailDocuments = data.data;
            updateDetailDocumentDisplay(data.data);
        } else {
            console.warn('No documents found in response');
            setDocumentStatus('tidak_ada');
        }
    } catch (error) {
        console.error('Error loading documents:', error);
        setDocumentStatus('error');
    }
}// Function to update document display in detail modal
function updateDetailDocumentDisplay(documents) {
    // Update Surat Penawaran
    updateDetailFileDisplay(
        'statusSuratPenawaran',
        'namaSuratPenawaran',
        'downloadSuratPenawaranBtn',
        documents.surat_penawaran,
        'penawaran'
    );

    // Update Surat Pesanan
    updateDetailFileDisplay(
        'statusSuratPesanan',
        'namaSuratPesanan',
        'downloadSuratPesananBtn',
        documents.surat_pesanan,
        'pesanan'
    );
}

// Helper function to update individual file display
function updateDetailFileDisplay(statusElementId, nameElementId, buttonElementId, filename, type) {
    const statusElement = document.getElementById(statusElementId);
    const nameElement = document.getElementById(nameElementId);
    const buttonElement = document.getElementById(buttonElementId);

    console.log(`Updating detail file display: ${type} = ${filename}`);

    if (statusElement && nameElement && buttonElement) {
        if (filename && filename !== 'null' && filename !== '' && filename !== null && filename !== undefined) {
            statusElement.textContent = 'Tersedia';
            statusElement.className = 'text-green-600 font-medium';
            nameElement.textContent = filename;
            nameElement.className = 'text-xs text-gray-600 font-mono';
            buttonElement.classList.remove('hidden');
        } else {
            statusElement.textContent = 'Tidak Ada';
            statusElement.className = 'text-gray-400 font-medium';
            nameElement.textContent = 'Tidak ada file';
            nameElement.className = 'text-xs text-gray-400 font-mono';
            buttonElement.classList.add('hidden');
        }
    }
}

// Function to set document status when no data found
function setDocumentStatus(status) {
    const elements = [
        {status: 'statusSuratPenawaran', name: 'namaSuratPenawaran', button: 'downloadSuratPenawaranBtn'},
        {status: 'statusSuratPesanan', name: 'namaSuratPesanan', button: 'downloadSuratPesananBtn'}
    ];

    elements.forEach(el => {
        const statusEl = document.getElementById(el.status);
        const nameEl = document.getElementById(el.name);
        const buttonEl = document.getElementById(el.button);

        if (statusEl && nameEl && buttonEl) {
            if (status === 'tidak_ada') {
                statusEl.textContent = 'Tidak Ada';
                statusEl.className = 'text-gray-400 font-medium';
                nameEl.textContent = 'Tidak ada file';
                nameEl.className = 'text-xs text-gray-400 font-mono';
            } else if (status === 'error') {
                statusEl.textContent = 'Error';
                statusEl.className = 'text-red-500 font-medium';
                nameEl.textContent = 'Gagal memuat';
                nameEl.className = 'text-xs text-red-400 font-mono';
            }
            buttonEl.classList.add('hidden');
        }
    });
}

// Function to download documents
function downloadDocument(type) {
    if (!currentDetailDocuments || !currentDetailProyekId) {
        alert('Data dokumen tidak tersedia');
        return;
    }

    let filename = null;
    let downloadType = null;

    if (type === 'penawaran') {
        filename = currentDetailDocuments.surat_penawaran;
        downloadType = 'penawaran';
    } else if (type === 'pesanan') {
        filename = currentDetailDocuments.surat_pesanan;
        downloadType = 'pesanan';
    }

    if (!filename || filename === 'null' || filename === '') {
        alert('File tidak tersedia untuk didownload');
        return;
    }

    console.log('Downloading:', downloadType, filename);

    // Create download URL
    const downloadUrl = `/marketing/penawaran/download/${downloadType}/${filename}`;

    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    console.log('Download initiated for:', downloadUrl);
}
</script>

<style>
@media print {
    .fixed, button {
        display: none !important;
    }

    .max-h-\[90vh\] {
        max-height: none !important;
    }

    .overflow-y-auto {
        overflow: visible !important;
    }
}
</style>
