<!-- Modal Detail Penawaran -->
<div id="modalDetailPenawaran" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Detail Penawaran</h3>
                    <p class="text-red-100 text-sm">Informasi lengkap penawaran proyek</p>
                </div>
            </div>
            <button onclick="closeModal('modalDetailPenawaran')" class="text-white hover:bg-white hover:text-red-800 p-2">
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
                        <label class="text-sm font-medium text-gray-500">Kode Penawaran</label>
                        <p id="detailKode" class="text-lg font-semibold text-gray-800">-</p>
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
                        <label class="text-sm font-medium text-gray-500">Tanggal Dibuat</label>
                        <p id="detailTanggal" class="text-lg font-semibold text-gray-800">-</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Deadline</label>
                        <p id="detailDeadline" class="text-lg font-semibold text-red-600">-</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Admin -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-users text-red-600 mr-2"></i>
                    Informasi Admin
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-red-600 text-lg"></i>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Admin Marketing</label>
                            <p id="detailAdminMarketing" class="text-lg font-semibold text-gray-800">-</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-red-600 text-lg"></i>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Admin Purchasing</label>
                            <p id="detailAdminPurchasing" class="text-lg font-semibold text-gray-800">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Surat Pesanan -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-file-alt text-red-600 mr-2"></i>
                    Surat Pesanan
                </h4>
                <div id="detailSuratPesanan" class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-file-pdf text-red-600 text-2xl"></i>
                        <div>
                            <p class="font-medium text-gray-800" id="detailNamaFile">Tidak ada file</p>
                            <p class="text-sm text-gray-500" id="detailUkuranFile">-</p>
                        </div>
                    </div>
                    <button onclick="downloadSuratPesanan()" class="text-red-600 hover:bg-red-100 rounded-lg p-2 transition-colors duration-200">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-boxes text-red-600 mr-2"></i>
                    Daftar Barang/Jasa
                </h4>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-medium text-gray-700">No</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-700">Nama Barang/Jasa</th>
                                <th class="text-center py-3 px-4 font-medium text-gray-700">Jumlah</th>
                                <th class="text-center py-3 px-4 font-medium text-gray-700">Satuan</th>
                                <th class="text-right py-3 px-4 font-medium text-gray-700">Harga Satuan</th>
                                <th class="text-right py-3 px-4 font-medium text-gray-700">Total</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                            <!-- Items will be populated here -->
                        </tbody>
                    </table>
                </div>

                <!-- Total Keseluruhan -->
                <div class="mt-6 bg-white border-2 border-red-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-semibold text-gray-800">Total Keseluruhan</span>
                        <span id="detailTotalKeseluruhan" class="text-3xl font-bold text-red-600">Rp 0</span>
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

            <!-- Timeline Status -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history text-red-600 mr-2"></i>
                    Timeline Status
                </h4>
                <div id="detailTimeline" class="space-y-4">
                    <!-- Timeline items will be populated here -->
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200 flex-shrink-0">
            <div class="flex items-center space-x-3">
                <button type="button" onclick="printDetail()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </button>
                <button type="button" onclick="exportPDF()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Export PDF
                </button>
            </div>
            <button type="button" onclick="closeModal('modalDetailPenawaran')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function downloadSuratPesanan() {
    // This would download the current file
    alert('Downloading surat pesanan...');
}

// Load detail data with file information
function loadDetailData(data) {
    // Load basic information
    document.getElementById('detailKode').textContent = data.kode;
    document.getElementById('detailKabupatenKota').textContent = data.kabupaten_kota;
    document.getElementById('detailNamaInstansi').textContent = data.nama_instansi;
    document.getElementById('detailJenisPengadaan').textContent = data.jenis_pengadaan;
    document.getElementById('detailTanggal').textContent = data.tanggal;
    document.getElementById('detailDeadline').textContent = data.deadline;
    document.getElementById('detailAdminMarketing').textContent = data.admin_marketing;
    document.getElementById('detailAdminPurchasing').textContent = data.admin_purchasing;
    
    // Load file information
    if (data.surat_pesanan && data.surat_pesanan.nama) {
        document.getElementById('detailNamaFile').textContent = data.surat_pesanan.nama;
        document.getElementById('detailUkuranFile').textContent = data.surat_pesanan.ukuran || '1.2 MB';
        
        // Update icon based on file type
        const fileIcon = document.querySelector('#detailSuratPesanan i');
        const extension = data.surat_pesanan.nama.split('.').pop().toLowerCase();
        if (['pdf'].includes(extension)) {
            fileIcon.className = 'fas fa-file-pdf text-red-600 text-2xl';
        } else if (['doc', 'docx'].includes(extension)) {
            fileIcon.className = 'fas fa-file-word text-blue-600 text-2xl';
        } else if (['jpg', 'jpeg', 'png'].includes(extension)) {
            fileIcon.className = 'fas fa-file-image text-green-600 text-2xl';
        }
    } else {
        document.getElementById('detailNamaFile').textContent = 'Tidak ada file';
        document.getElementById('detailUkuranFile').textContent = '-';
    }
    
    // Set status badge
    const statusBadge = document.getElementById('detailStatusBadge');
    const statusColors = {
        'Draft': 'bg-gray-100 text-gray-800',
        'Menunggu': 'bg-yellow-100 text-yellow-800',
        'Disetujui': 'bg-green-100 text-green-800',
        'Ditolak': 'bg-red-100 text-red-800'
    };
    statusBadge.className = 'inline-flex px-4 py-2 text-sm font-medium rounded-full ' + (statusColors[data.status] || 'bg-gray-100 text-gray-800');
    statusBadge.textContent = data.status;
    
    // Load items table
    const tableBody = document.getElementById('detailTableBody');
    tableBody.innerHTML = '';
    let total = 0;
    
    if (data.items && data.items.length > 0) {
        data.items.forEach((item, index) => {
            const itemTotal = item.jumlah * item.harga_satuan;
            total += itemTotal;
            
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-100 hover:bg-gray-50';
            row.innerHTML = `
                <td class="py-3 px-4 text-gray-700">${index + 1}</td>
                <td class="py-3 px-4 text-gray-800 font-medium">${item.nama}</td>
                <td class="py-3 px-4 text-center text-gray-700">${item.jumlah}</td>
                <td class="py-3 px-4 text-center text-gray-700">${item.satuan}</td>
                <td class="py-3 px-4 text-right text-gray-700">${formatRupiah(item.harga_satuan)}</td>
                <td class="py-3 px-4 text-right font-medium text-gray-800">${formatRupiah(itemTotal)}</td>
            `;
            tableBody.appendChild(row);
        });
    }
    
    document.getElementById('detailTotalKeseluruhan').textContent = formatRupiah(total);
    
    // Load catatan
    if (data.catatan && data.catatan.trim()) {
        document.getElementById('detailCatatan').textContent = data.catatan;
        document.getElementById('detailCatatanSection').style.display = 'block';
    } else {
        document.getElementById('detailCatatanSection').style.display = 'none';
    }
    
    // Load timeline
    loadTimeline(data.timeline || []);
}

function loadTimeline(timeline) {
    const timelineContainer = document.getElementById('detailTimeline');
    timelineContainer.innerHTML = '';
    
    if (timeline.length === 0) {
        timeline = [
            { status: 'Draft', tanggal: '2024-08-10', keterangan: 'Penawaran dibuat' }
        ];
    }
    
    timeline.forEach((item, index) => {
        const isLast = index === timeline.length - 1;
        const timelineItem = document.createElement('div');
        timelineItem.className = 'flex items-start space-x-4';
        timelineItem.innerHTML = `
            <div class="flex flex-col items-center">
                <div class="w-4 h-4 bg-red-600 rounded-full"></div>
                ${!isLast ? '<div class="w-0.5 h-8 bg-gray-300 mt-2"></div>' : ''}
            </div>
            <div class="flex-1 pb-8">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="font-medium text-gray-800">${item.status}</span>
                    <span class="text-sm text-gray-500">•</span>
                    <span class="text-sm text-gray-500">${item.tanggal}</span>
                </div>
                <p class="text-sm text-gray-600">${item.keterangan}</p>
            </div>
        `;
        timelineContainer.appendChild(timelineItem);
    });
}

function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

function printDetail() {
    window.print();
}

function exportPDF() {
    alert('Exporting to PDF...');
}

// Example function to open detail modal
function openDetailModal(id) {
    const sampleData = {
        id: id,
        kode: 'PNW-20240810-143052',
        kabupaten_kota: 'Jakarta Pusat',
        nama_instansi: 'Dinas Pendidikan DKI',
        jenis_pengadaan: 'Pelelangan Umum',
        tanggal: '10 Agustus 2024',
        deadline: '30 September 2024',
        admin_marketing: 'Admin Marketing',
        admin_purchasing: 'Sari Wijaya',
        status: 'Menunggu',
        surat_pesanan: {
            nama: 'SP-001-Pengadaan-Sistem.pdf',
            ukuran: '1.2 MB'
        },
        items: [
            {
                nama: 'Sistem Informasi Manajemen',
                jumlah: 1,
                satuan: 'Paket',
                harga_satuan: 150000000
            },
            {
                nama: 'Training & Implementation',
                jumlah: 1,
                satuan: 'Layanan',
                harga_satuan: 25000000
            }
        ],
        catatan: 'Sistem informasi manajemen untuk sekolah dengan fitur lengkap termasuk training dan implementasi.',
        timeline: [
            { status: 'Draft', tanggal: '10 Agustus 2024', keterangan: 'Penawaran dibuat' },
            { status: 'Menunggu', tanggal: '10 Agustus 2024', keterangan: 'Menunggu persetujuan admin purchasing' }
        ]
    };
    
    loadDetailData(sampleData);
    openModal('modalDetailPenawaran');
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
