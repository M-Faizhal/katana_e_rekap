<!-- Modal Detail Penawaran -->
<div id="modalDetailPenawaran" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-2 sm:my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-4 sm:p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-2 sm:space-x-3 min-w-0">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                </div>
                <div class="min-w-0">
                    <h3 class="text-lg sm:text-xl font-bold truncate">Detail Penawaran</h3>
                    <p class="text-red-100 text-xs sm:text-sm truncate">Informasi lengkap penawaran</p>
                </div>
            </div>
            <button onclick="closeModal('modalDetailPenawaran')" class="text-white hover:bg-white hover:text-red-800 p-1 sm:p-2 flex-shrink-0 ml-2">
                <i class="fas fa-times text-lg sm:text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-4 sm:p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 140px);">
            <!-- Status Badge -->
            <div class="mb-4 sm:mb-6">
                <span id="detailStatusBadge" class="inline-flex px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm font-medium rounded-full">
                    <!-- Status will be set dynamically -->
                </span>
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
                        <p id="detailNoPenawaran" class="text-sm sm:text-lg font-semibold text-gray-800 break-words">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs sm:text-sm font-medium text-gray-500">Kode Proyek</label>
                        <p id="detailKodeProyek" class="text-sm sm:text-lg font-semibold text-gray-800 break-words">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs sm:text-sm font-medium text-gray-500">Nama Proyek</label>
                        <p id="detailNamaProyek" class="text-sm sm:text-lg font-semibold text-gray-800 break-words">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs sm:text-sm font-medium text-gray-500">Klien</label>
                        <p id="detailKlien" class="text-sm sm:text-lg font-semibold text-gray-800 break-words">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs sm:text-sm font-medium text-gray-500">Tanggal Penawaran</label>
                        <p id="detailTanggalPenawaran" class="text-sm sm:text-lg font-semibold text-gray-800 break-words">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs sm:text-sm font-medium text-gray-500">Nilai Kalkulasi</label>
                        <p id="detailNilaiKalkulasi" class="text-sm sm:text-lg font-semibold text-gray-800 break-words">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs sm:text-sm font-medium text-gray-500">Harga Penawaran</label>
                        <p id="detailHargaPenawaran" class="text-sm sm:text-lg font-semibold text-green-600 break-words">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs sm:text-sm font-medium text-gray-500">Margin</label>
                        <p id="detailMargin" class="text-sm sm:text-lg font-semibold text-blue-600 break-words">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs sm:text-sm font-medium text-gray-500">Admin Marketing</label>
                        <p id="detailAdminMarketing" class="text-sm sm:text-lg font-semibold text-gray-800 break-words">-</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-boxes text-red-600 mr-2 text-sm sm:text-base"></i>
                    Daftar Barang
                </h4>
                <div id="detailDaftarBarang" class="space-y-3 sm:space-y-4">
                    <!-- Items will be populated here -->
                </div>
                
                <!-- Total Keseluruhan -->
                <div class="mt-4 sm:mt-6 bg-white border border-gray-200 rounded-lg p-3 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <h5 class="text-base sm:text-lg font-semibold text-gray-800">Total Nilai Penawaran:</h5>
                        <div class="text-xl sm:text-2xl font-bold text-red-600" id="detailTotalPenawaran">Rp 0</div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div id="detailCatatanSection" class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-4 sm:mb-6" style="display: none;">
                <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-red-600 mr-2 text-sm sm:text-base"></i>
                    Catatan Penawaran
                </h4>
                <div class="bg-white rounded-lg p-3 sm:p-4 border border-gray-200">
                    <p id="detailCatatan" class="text-gray-700 leading-relaxed text-sm sm:text-base">-</p>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-file-alt text-red-600 mr-2 text-sm sm:text-base"></i>
                    Dokumen Penawaran
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center space-x-2 sm:space-x-3 mb-2">
                            <i class="fas fa-file-contract text-red-600 text-base sm:text-lg"></i>
                            <h5 class="font-medium text-gray-800 text-sm sm:text-base">Surat Penawaran</h5>
                        </div>
                        <div id="detailSuratPenawaranStatus">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center space-x-2 sm:space-x-3 mb-2">
                            <i class="fas fa-file-invoice text-red-600 text-base sm:text-lg"></i>
                            <h5 class="font-medium text-gray-800 text-sm sm:text-base">Surat Pesanan</h5>
                        </div>
                        <div id="detailSuratPesananStatus">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-center border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalDetailPenawaran')" class="w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-sm sm:text-base">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Sample data for penawaran detail
const penawaranDetailData = {
    1: {
        no_penawaran: 'PNW-2024-001',
        kode_proyek: 'PRJ-2024-001',
        nama_proyek: 'Pembangunan Gedung Kantor',
        klien: 'PT. ABC Company',
        tanggal_penawaran: '15 Januari 2024',
        nilai_kalkulasi: 2350000000,
        harga_penawaran: 2500000000,
        margin: '6.38%',
        admin_marketing: 'John Doe',
        status: 'proses',
        catatan: 'Penawaran telah disesuaikan dengan kebutuhan klien. Termasuk garansi 2 tahun.',
        daftar_barang: [
            { nama: 'Besi Beton 12mm', jumlah: 100, satuan: 'batang', harga: 95000, total: 9500000 },
            { nama: 'Semen Portland', jumlah: 500, satuan: 'sak', harga: 65000, total: 32500000 },
            { nama: 'Pasir Halus', jumlah: 50, satuan: 'm³', harga: 280000, total: 14000000 }
        ],
        dokumen: {
            surat_penawaran: { status: 'ada', tanggal_upload: '15 Jan 2024' },
            surat_pesanan: { status: 'belum', tanggal_upload: null }
        }
    },
    2: {
        no_penawaran: 'PNW-2024-002',
        kode_proyek: 'PRJ-2024-002',
        nama_proyek: 'Renovasi Pabrik',
        klien: 'PT. XYZ Manufacturing',
        tanggal_penawaran: '20 Januari 2024',
        nilai_kalkulasi: 1200000000,
        harga_penawaran: 1350000000,
        margin: '12.5%',
        admin_marketing: 'Jane Smith',
        status: 'berhasil',
        catatan: 'Penawaran diterima dengan revisi minor. Proyek akan dimulai bulan depan.',
        daftar_barang: [
            { nama: 'Cat Tembok Premium', jumlah: 200, satuan: 'kaleng', harga: 150000, total: 30000000 },
            { nama: 'Keramik 60x60', jumlah: 500, satuan: 'm²', harga: 75000, total: 37500000 },
            { nama: 'Pipa PVC 4 inch', jumlah: 100, satuan: 'batang', harga: 125000, total: 12500000 }
        ],
        dokumen: {
            surat_penawaran: { status: 'ada', tanggal_upload: '20 Jan 2024' },
            surat_pesanan: { status: 'ada', tanggal_upload: '25 Jan 2024' }
        }
    }
};

function loadPenawaranDetailData(data) {
    // Status badge
    const statusBadge = document.getElementById('detailStatusBadge');
    let statusClass = '';
    let statusText = '';
    
    switch(data.status) {
        case 'proses':
            statusClass = 'bg-yellow-100 text-yellow-800';
            statusText = 'Proses';
            break;
        case 'berhasil':
            statusClass = 'bg-green-100 text-green-800';
            statusText = 'Berhasil';
            break;
        case 'gagal':
            statusClass = 'bg-red-100 text-red-800';
            statusText = 'Gagal';
            break;
        default:
            statusClass = 'bg-gray-100 text-gray-800';
            statusText = 'Tidak Ada';
    }
    
    statusBadge.className = `inline-flex px-4 py-2 text-sm font-medium rounded-full ${statusClass}`;
    statusBadge.textContent = statusText;

    // Basic information
    document.getElementById('detailNoPenawaran').textContent = data.no_penawaran || '-';
    document.getElementById('detailKodeProyek').textContent = data.kode_proyek || '-';
    document.getElementById('detailNamaProyek').textContent = data.nama_proyek || '-';
    document.getElementById('detailKlien').textContent = data.klien || '-';
    document.getElementById('detailTanggalPenawaran').textContent = data.tanggal_penawaran || '-';
    document.getElementById('detailNilaiKalkulasi').textContent = data.nilai_kalkulasi ? formatRupiah(data.nilai_kalkulasi) : '-';
    document.getElementById('detailHargaPenawaran').textContent = data.harga_penawaran ? formatRupiah(data.harga_penawaran) : '-';
    document.getElementById('detailMargin').textContent = data.margin || '-';
    document.getElementById('detailAdminMarketing').textContent = data.admin_marketing || '-';

    // Daftar barang
    const daftarBarangContainer = document.getElementById('detailDaftarBarang');
    daftarBarangContainer.innerHTML = '';
    
    let totalPenawaran = 0;
    
    if (data.daftar_barang && data.daftar_barang.length > 0) {
        data.daftar_barang.forEach(barang => {
            totalPenawaran += barang.total;
            
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
            daftarBarangContainer.appendChild(barangElement);
        });
    } else {
        daftarBarangContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Tidak ada data barang</p>';
    }
    
    document.getElementById('detailTotalPenawaran').textContent = formatRupiah(data.harga_penawaran || totalPenawaran);

    // Catatan
    const catatanSection = document.getElementById('detailCatatanSection');
    const catatanText = document.getElementById('detailCatatan');
    
    if (data.catatan && data.catatan.trim() !== '') {
        catatanSection.style.display = 'block';
        catatanText.textContent = data.catatan;
    } else {
        catatanSection.style.display = 'none';
    }

    // Dokumen
    loadDokumenStatus('detailSuratPenawaranStatus', data.dokumen.surat_penawaran, 'Surat Penawaran');
    loadDokumenStatus('detailSuratPesananStatus', data.dokumen.surat_pesanan, 'Surat Pesanan');
}

function loadDokumenStatus(containerId, dokumen, namaFile) {
    const container = document.getElementById(containerId);
    
    if (dokumen.status === 'ada') {
        // Special handling for Surat Penawaran - no download button
        if (namaFile === 'Surat Penawaran') {
            container.innerHTML = `
                <p class="text-sm text-gray-500 mb-2">Status: <span class="text-green-600 font-medium">Tersedia</span></p>
                <p class="text-xs text-gray-400">Upload: ${dokumen.tanggal_upload}</p>
            `;
        } else {
            container.innerHTML = `
                <p class="text-sm text-gray-500 mb-2">Status: <span class="text-green-600 font-medium">Tersedia</span></p>
                <p class="text-xs text-gray-400 mb-2">Upload: ${dokumen.tanggal_upload}</p>
                <button class="text-red-600 hover:text-red-700 text-sm font-medium">
                    <i class="fas fa-download mr-1"></i>Download ${namaFile}
                </button>
            `;
        }
    } else {
        container.innerHTML = `
            <p class="text-sm text-gray-500 mb-2">Status: <span class="text-gray-400 font-medium">Belum Ada</span></p>
            <p class="text-xs text-gray-400">Dokumen belum diupload</p>
        `;
    }
}

function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// Function to view detail penawaran (called from main page)
function viewDetailPenawaran(id) {
    const data = penawaranDetailData[id];
    if (data) {
        loadPenawaranDetailData(data);
        openModal('modalDetailPenawaran');
    }
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