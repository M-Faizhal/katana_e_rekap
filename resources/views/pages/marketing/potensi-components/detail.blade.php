<!-- Modal Detail Potensi -->
<div id="modalDetailProyek" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Detail Potensi</h3>
                    <p class="text-red-100 text-sm">Informasi lengkap potensi</p>
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
                        <label class="text-sm font-medium text-gray-500">PIC Marketing</label>
                        <p id="detailAdminMarketing" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">PIC Purchasing</label>
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
    // Convert to number if it's a string
    const number = typeof angka === 'string' ? parseFloat(angka) : angka;

    // Return 'Rp 0' if not a valid number
    if (isNaN(number)) {
        return 'Rp 0';
    }

    // Use Indonesian locale formatting with decimal support
    return 'Rp ' + number.toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 5
    });
}

// Function to load documents for the detail modal
async function loadDetailDocuments(proyekId) {
    try {
        currentDetailProyekId = proyekId;
        console.log('Loading documents for project:', proyekId);

        const response = await fetch(`/marketing/penawaran/project/${proyekId}/data`);

        if (!response.ok) {
            console.warn('No penawaran data found for project', proyekId, 'Status:', response.status);
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
        console.warn('Error loading documents (endpoint may not exist):', error);
        // Don't show error, just set to no documents available
        setDocumentStatus('tidak_ada');
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
                statusEl.textContent = 'Belum Ada';
                statusEl.className = 'text-gray-400 font-medium';
                nameEl.textContent = 'Dokumen belum tersedia';
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

// Function to load and display project detail data
function loadDetailData(data) {
    console.log('Loading detail data:', data);
    console.log('Items data:', data.items || data.daftar_barang || []);

    // Load basic information
    document.getElementById('detailIdProyek').textContent = data.kode || data.id_proyek || '-';
    document.getElementById('detailTanggal').textContent = data.tanggal || '-';
    document.getElementById('detailKabupatenKota').textContent = data.kabupaten_kota || data.kabupaten || '-';
    document.getElementById('detailNamaInstansi').textContent = data.nama_instansi || data.instansi || '-';
    document.getElementById('detailJenisPengadaan').textContent = data.jenis_pengadaan || '-';
    document.getElementById('detailAdminMarketing').textContent = data.admin_marketing_nama || data.pic_marketing || '-';
    document.getElementById('detailAdminPurchasing').textContent = data.admin_purchasing_nama || data.pic_purchasing || '-';
    document.getElementById('detailPotensi').textContent = data.potensi === 'ya' ? 'Ya' : 'Tidak';
    document.getElementById('detailTahunPotensi').textContent = data.tahun_potensi || '-';

    // Load catatan
    const catatanSection = document.getElementById('detailCatatanSection');
    const catatanElement = document.getElementById('detailCatatan');
    if (data.catatan && data.catatan.trim()) {
        catatanElement.textContent = data.catatan;
        catatanSection.style.display = 'block';
    } else {
        catatanSection.style.display = 'none';
    }

    // Load status badge
    updateDetailStatusBadge(data.status || 'Draft');

    // Load barang list
    loadDetailBarangList(data.items || data.daftar_barang || []);

    // Try to load documents if ID is available, but don't fail if endpoint doesn't exist
    if (data.id) {
        loadDetailDocuments(data.id);
    } else {
        // If no ID, just set documents to not available
        setDocumentStatus('tidak_ada');
    }
}

// Function to update status badge
function updateDetailStatusBadge(status) {
    const badge = document.getElementById('detailStatusBadge');
    let badgeClass = '';
    let statusText = status;

    switch(status) {
        case 'Draft':
            badgeClass = 'bg-gray-100 text-gray-800';
            statusText = 'Draft';
            break;
        case 'Penawaran':
            badgeClass = 'bg-blue-100 text-blue-800';
            statusText = 'Penawaran';
            break;
        case 'Negosiasi':
            badgeClass = 'bg-yellow-100 text-yellow-800';
            statusText = 'Negosiasi';
            break;
        case 'ACC':
            badgeClass = 'bg-green-100 text-green-800';
            statusText = 'Disetujui';
            break;
        case 'Selesai':
            badgeClass = 'bg-purple-100 text-purple-800';
            statusText = 'Selesai';
            break;
        default:
            badgeClass = 'bg-gray-100 text-gray-800';
    }

    badge.className = `inline-flex px-4 py-2 text-sm font-medium rounded-full ${badgeClass}`;
    badge.textContent = statusText;
}

// Function to load and display barang list with decimal support
function loadDetailBarangList(items) {
    const container = document.getElementById('detailDaftarBarang');
    container.innerHTML = '';

    console.log('loadDetailBarangList called with items:', items);

    if (!items || items.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-box-open text-4xl mb-4"></i>
                <p>Tidak ada data barang</p>
            </div>
        `;
        document.getElementById('detailTotalKeseluruhan').textContent = 'Rp 0';
        return;
    }

    let totalKeseluruhan = 0;

    items.forEach((item, index) => {
        const nama = item.nama || item.nama_barang || '';
        const qty = item.qty || item.jumlah || 0;
        const satuan = item.satuan || '';
        // Ensure proper decimal parsing - don't use parseInt, use parseFloat
        const hargaSatuan = item.harga_satuan ? parseFloat(item.harga_satuan) : 0;
        const spesifikasi = item.spesifikasi || '';

        // Calculate total for this item with decimal precision
        const totalItem = parseFloat(qty) * hargaSatuan;
        totalKeseluruhan += totalItem;

        // Format prices with decimal support - always use formatRupiah
        const hargaSatuanFormatted = hargaSatuan > 0 ? formatRupiah(hargaSatuan) : 'Tidak diisi';
        const totalItemFormatted = totalItem > 0 ? formatRupiah(totalItem) : 'Rp 0';

        console.log(`Item ${index + 1}:`, {
            nama,
            qty,
            hargaSatuan,
            totalItem,
            hargaSatuanFormatted,
            totalItemFormatted
        });

        const itemHtml = `
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="font-medium text-gray-800 flex items-center">
                        <i class="fas fa-box text-red-600 mr-2"></i>
                        Item ${index + 1}
                    </h5>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Nama Barang</label>
                        <p class="text-sm font-semibold text-gray-800">${nama}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Qty</label>
                        <p class="text-sm font-semibold text-gray-800">${qty}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Satuan</label>
                        <p class="text-sm font-semibold text-gray-800">${satuan}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Harga Satuan</label>
                        <p class="text-sm font-semibold text-gray-800">${hargaSatuanFormatted}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Harga Total</label>
                        <p class="text-sm font-semibold text-red-600">${totalItemFormatted}</p>
                    </div>
                    ${spesifikasi ? `
                    <div>
                        <label class="text-sm font-medium text-gray-500">Spesifikasi</label>
                        <p class="text-sm text-gray-700">${spesifikasi}</p>
                    </div>
                    ` : ''}
                </div>
                ${item.spesifikasi_files && item.spesifikasi_files.length > 0 ? `
                <div class="mt-3">
                    <label class="text-sm font-medium text-gray-500 mb-2 block">File Spesifikasi</label>
                    <div class="space-y-1">
                        ${item.spesifikasi_files.map(file => `
                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded border text-sm">
                                <div class="flex items-center space-x-2">
                                    <i class="fas ${getDetailFileIcon(file.original_name)} text-gray-500"></i>
                                    <span class="font-medium">${file.original_name}</span>
                                    <span class="text-gray-500">(${formatDetailFileSize(file.file_size)})</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    ${file.mime_type && file.mime_type.includes('pdf') ? `
                                        <button type="button" onclick="previewDetailFile('${file.stored_name}')" class="text-blue-600 hover:text-blue-800 p-1">
                                            <i class="fas fa-eye" title="Preview"></i>
                                        </button>
                                    ` : ''}
                                    <button type="button" onclick="downloadDetailFile('${file.stored_name}')" class="text-green-600 hover:text-green-800 p-1">
                                        <i class="fas fa-download" title="Download"></i>
                                    </button>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : ''}
            </div>
        `;

        container.insertAdjacentHTML('beforeend', itemHtml);
    });

    // Update total keseluruhan
    console.log('Total keseluruhan calculated:', totalKeseluruhan);
    document.getElementById('detailTotalKeseluruhan').textContent = formatRupiah(totalKeseluruhan);
}

// Helper functions for file display
function getDetailFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    switch (ext) {
        case 'pdf': return 'fa-file-pdf text-red-500';
        case 'doc':
        case 'docx': return 'fa-file-word text-blue-500';
        case 'xls':
        case 'xlsx': return 'fa-file-excel text-green-500';
        case 'jpg':
        case 'jpeg':
        case 'png': return 'fa-file-image text-purple-500';
        default: return 'fa-file text-gray-500';
    }
}

function formatDetailFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function downloadDetailFile(filename) {
    window.open(`/marketing/potensi/file/${filename}`, '_blank');
}

function previewDetailFile(filename) {
    window.open(`/marketing/potensi/file/${filename}/preview`, '_blank');
}

// Make function available globally
window.loadDetailData = loadDetailData;
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
