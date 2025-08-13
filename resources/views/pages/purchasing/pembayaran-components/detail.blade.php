<!-- Modal Detail Pembayaran -->
<div id="detailPaymentModal" class="fixed inset-0 bg-black/20 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
        <div class="bg-white rounded-lg sm:rounded-xl max-w-sm sm:max-w-2xl lg:max-w-4xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Detail Pembayaran Proyek</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-lg sm:text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-4 sm:p-6">
                <!-- Info Proyek -->
                <div class="bg-gray-50 rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center gap-2">
                        <i class="fas fa-project-diagram text-red-600"></i>
                        Informasi Proyek
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600">Kode Proyek</p>
                            <p class="font-semibold text-sm sm:text-base text-gray-900" id="detailKodeProyek">PNW-2024-001</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600">Nama Proyek</p>
                            <p class="font-semibold text-sm sm:text-base text-gray-900" id="detailNamaProyek">Sistem Informasi Manajemen</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600">Instansi</p>
                            <p class="font-semibold text-sm sm:text-base text-gray-900" id="detailInstansi">Dinas Pendidikan DKI</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600">Lokasi</p>
                            <p class="font-semibold text-sm sm:text-base text-gray-900" id="detailLokasi">Jakarta Pusat</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600">Total Nilai Proyek</p>
                            <p class="font-semibold text-sm sm:text-base text-gray-900" id="detailTotalNilai">Rp 850.000.000</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600">Admin Marketing</p>
                            <p class="font-semibold text-sm sm:text-base text-gray-900" id="detailAdminMarketing">Andi Prasetyo</p>
                        </div>
                    </div>
                </div>

                <!-- Status Pembayaran -->
                <div class="bg-blue-50 rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-blue-600"></i>
                        Status Pembayaran
                    </h4>
                    
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-xs sm:text-sm text-gray-600 mb-2">
                            <span>Progress Pembayaran</span>
                            <span id="detailPersentaseProgress">30% (DP)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 sm:h-3">
                            <div id="detailProgressBar" class="bg-green-600 h-2 sm:h-3 rounded-full transition-all duration-300" style="width: 30%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>Rp 0</span>
                            <span id="detailTotalNilaiProgress">Rp 850.000.000</span>
                        </div>
                    </div>

                    <!-- Ringkasan Pembayaran -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div class="text-center p-3 sm:p-4 bg-white rounded-lg border">
                            <div class="text-lg sm:text-2xl font-bold text-green-600" id="detailJumlahTerbayar">Rp 255M</div>
                            <div class="text-xs sm:text-sm text-gray-600">Jumlah Terbayar</div>
                        </div>
                        <div class="text-center p-3 sm:p-4 bg-white rounded-lg border">
                            <div class="text-lg sm:text-2xl font-bold text-orange-600" id="detailSisaPembayaran">Rp 595M</div>
                            <div class="text-xs sm:text-sm text-gray-600">Sisa Pembayaran</div>
                        </div>
                        <div class="text-center p-3 sm:p-4 bg-white rounded-lg border">
                            <div class="text-lg sm:text-2xl font-bold text-blue-600" id="detailJumlahTransaksi">1</div>
                            <div class="text-xs sm:text-sm text-gray-600">Jumlah Transaksi</div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pembayaran -->
                <div class="bg-white border border-gray-200 rounded-lg">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-history text-purple-600"></i>
                            Riwayat Pembayaran
                        </h4>
                    </div>
                    
                    <div class="p-4 sm:p-6">
                        <div id="riwayatPembayaranList">
                            <!-- Item Pembayaran 1 -->
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 mb-4 last:mb-0">
                                <div class="flex flex-col gap-3 sm:gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2 bg-green-100 text-green-600 rounded-lg flex-shrink-0">
                                                <i class="fas fa-credit-card text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-2">
                                                    <h5 class="font-semibold text-sm sm:text-base text-gray-900">Pembayaran DP</h5>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-fit">
                                                        Terverifikasi
                                                    </span>
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1 sm:gap-2 text-xs sm:text-sm text-gray-600">
                                                    <p><span class="font-medium">Nominal:</span> Rp 255.000.000</p>
                                                    <p><span class="font-medium">Tanggal:</span> 01 Nov 2024</p>
                                                    <p><span class="font-medium">Metode:</span> Transfer Bank</p>
                                                    <p><span class="font-medium">Admin:</span> Sari Wijaya</p>
                                                    <p><span class="font-medium">Input:</span> 01 Nov 2024 10:30</p>
                                                </div>
                                                <div class="mt-2">
                                                    <p class="text-xs sm:text-sm text-gray-600"><span class="font-medium">Catatan:</span> Pembayaran DP 30% dari total nilai proyek</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <button onclick="downloadBukti('bukti_dp_pnw2024001.pdf')" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-1">
                                            <i class="fas fa-download text-xs"></i>
                                            Bukti
                                        </button>
                                        <button onclick="showEditPayment(1)" 
                                                class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-1">
                                            <i class="fas fa-edit text-xs"></i>
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Placeholder untuk pembayaran berikutnya -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 sm:p-8 text-center text-gray-500">
                                <i class="fas fa-plus-circle text-2xl sm:text-3xl mb-3"></i>
                                <p class="font-medium text-sm sm:text-base">Menunggu Pelunasan</p>
                                <p class="text-xs sm:text-sm">Sisa pembayaran: Rp 595.000.000</p>
                                <button onclick="closeDetailModal(); showAddPaymentModal();" 
                                        class="mt-3 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors duration-200">
                                    Input Pelunasan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200">
                    <button onclick="exportPaymentReport()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-file-excel"></i>
                        Export Excel
                    </button>
                    <button onclick="printPaymentReport()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                    <button onclick="closeDetailModal()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function closeDetailModal() {
    document.getElementById('detailPaymentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function downloadBukti(fileName) {
    // Simulate file download
    alert('Mengunduh bukti pembayaran: ' + fileName);
}

function showEditPayment(paymentId) {
    alert('Edit pembayaran ID: ' + paymentId);
    // Implementasi edit pembayaran
}

function exportPaymentReport() {
    alert('Export laporan pembayaran ke Excel');
    // Implementasi export Excel
}

function printPaymentReport() {
    window.print();
}

// Function untuk menampilkan detail berdasarkan proyek ID
function showPaymentDetail(proyekId) {
    // Data dummy - dalam implementasi nyata ambil dari server
    const proyekData = {
        1: {
            kode: 'PNW-2024-001',
            nama: 'Sistem Informasi Manajemen',
            instansi: 'Dinas Pendidikan DKI',
            lokasi: 'Jakarta Pusat',
            total_nilai: 850000000,
            admin_marketing: 'Andi Prasetyo',
            terbayar: 255000000,
            sisa: 595000000,
            persentase: 30,
            jumlah_transaksi: 1
        },
        4: {
            kode: 'PNW-2024-004',
            nama: 'Dashboard Analytics Daerah',
            instansi: 'Pemda DIY',
            lokasi: 'Yogyakarta',
            total_nilai: 920000000,
            admin_marketing: 'Fajar Ramadhan',
            terbayar: 276000000,
            sisa: 644000000,
            persentase: 30,
            jumlah_transaksi: 1
        }
    };

    const proyek = proyekData[proyekId];
    if (proyek) {
        // Update data proyek
        document.getElementById('detailKodeProyek').textContent = proyek.kode;
        document.getElementById('detailNamaProyek').textContent = proyek.nama;
        document.getElementById('detailInstansi').textContent = proyek.instansi;
        document.getElementById('detailLokasi').textContent = proyek.lokasi;
        document.getElementById('detailTotalNilai').textContent = 'Rp ' + proyek.total_nilai.toLocaleString('id-ID');
        document.getElementById('detailAdminMarketing').textContent = proyek.admin_marketing;

        // Update progress
        document.getElementById('detailPersentaseProgress').textContent = proyek.persentase + '% (DP)';
        document.getElementById('detailProgressBar').style.width = proyek.persentase + '%';
        document.getElementById('detailTotalNilaiProgress').textContent = 'Rp ' + proyek.total_nilai.toLocaleString('id-ID');

        // Update ringkasan
        document.getElementById('detailJumlahTerbayar').textContent = 'Rp ' + Math.round(proyek.terbayar / 1000000) + 'M';
        document.getElementById('detailSisaPembayaran').textContent = 'Rp ' + Math.round(proyek.sisa / 1000000) + 'M';
        document.getElementById('detailJumlahTransaksi').textContent = proyek.jumlah_transaksi;

        // Tampilkan modal
        document.getElementById('detailPaymentModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}
</script>
