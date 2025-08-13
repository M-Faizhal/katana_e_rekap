<!-- Modal Detail Pembayaran -->
<div id="detailPembayaranModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs hidden z-50 p-4">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Detail Pembayaran</h3>
                        <p class="text-sm text-gray-500">Review dan validasi pembayaran proyek</p>
                    </div>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6 space-y-6">
                <!-- Project Information -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                        <i class="fas fa-project-diagram mr-2"></i>
                        Informasi Proyek
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Kode Proyek</label>
                            <p id="detailKodeProyek" class="text-gray-900 font-semibold">-</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Nama Proyek</label>
                            <p id="detailNamaProyek" class="text-gray-900">-</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Instansi</label>
                            <p id="detailInstansi" class="text-gray-900">-</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Total Nilai Proyek</label>
                            <p id="detailTotalNilai" class="text-gray-900 font-semibold">-</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="font-semibold text-green-900 mb-3 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        Informasi Pembayaran
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Jenis Pembayaran</label>
                            <p id="detailJenisPembayaran" class="text-gray-900">-</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Nominal Pembayaran</label>
                            <p id="detailNominalPembayaran" class="text-gray-900 font-bold text-lg">-</p>
                        </div>
                    </div>
                </div>

                <!-- Rincian Harga Barang -->
                <div class="bg-purple-50 rounded-lg p-4">
                    <h4 class="font-semibold text-purple-900 mb-3 flex items-center">
                        <i class="fas fa-boxes mr-2"></i>
                        Rincian Harga Barang
                    </h4>
                    <div id="detailDaftarBarang" class="space-y-3">
                        <!-- Items will be populated here -->
                    </div>
                    
                    <!-- Total Keseluruhan -->
                    <div class="mt-4 bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <h5 class="text-base font-semibold text-gray-800">Total Nilai Proyek:</h5>
                            <div class="text-xl font-bold text-purple-600" id="detailTotalProyek">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Proof of Payment -->
                <div class="bg-yellow-50 rounded-lg p-4">
                    <h4 class="font-semibold text-yellow-900 mb-3 flex items-center">
                        <i class="fas fa-file-upload mr-2"></i>
                        Bukti Pembayaran
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">File Bukti</label>
                            <div class="flex items-center gap-3 mt-1">
                                <p id="detailFileBukti" class="text-gray-900">-</p>
                                <button id="btnPreviewBukti" onclick="previewBuktiPembayaran()" 
                                        class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg text-sm transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Preview
                                </button>
                                <button id="btnDownloadBukti" onclick="downloadBuktiPembayaran()" 
                                        class="text-green-600 hover:text-green-800 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-lg text-sm transition-colors duration-200">
                                    <i class="fas fa-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Catatan Pembayaran</label>
                            <p id="detailCatatan" class="text-gray-900 bg-white p-3 rounded border">-</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-user-cog mr-2"></i>
                        Informasi Admin Input
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Admin Purchasing</label>
                            <p id="detailAdminInput" class="text-gray-900">-</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Tanggal Input</label>
                            <p id="detailTanggalInput" class="text-gray-900">-</p>
                        </div>
                    </div>
                </div>

                <!-- Validation Checklist -->
                <div class="bg-red-50 rounded-lg p-4">
                    <h4 class="font-semibold text-red-900 mb-3 flex items-center">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Checklist Validasi
                    </h4>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" id="checkNominal" class="rounded border-gray-300 text-red-600 focus:ring-red-500 mr-3">
                            <span class="text-sm text-gray-700">Nominal pembayaran sesuai dengan kontrak/invoice</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="checkBukti" class="rounded border-gray-300 text-red-600 focus:ring-red-500 mr-3">
                            <span class="text-sm text-gray-700">Bukti pembayaran jelas dan valid</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="checkReferensi" class="rounded border-gray-300 text-red-600 focus:ring-red-500 mr-3">
                            <span class="text-sm text-gray-700">Nomor referensi bank dapat diverifikasi</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="checkTanggal" class="rounded border-gray-300 text-red-600 focus:ring-red-500 mr-3">
                            <span class="text-sm text-gray-700">Tanggal pembayaran sesuai dengan bukti</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="checkDokumen" class="rounded border-gray-300 text-red-600 focus:ring-red-500 mr-3">
                            <span class="text-sm text-gray-700">Semua dokumen pendukung lengkap</span>
                        </label>
                    </div>
                </div>

                <!-- Approval Notes -->
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Catatan Approval
                    </h4>
                    <textarea id="approvalNotes" rows="4" 
                              placeholder="Tambahkan catatan atau komentar untuk approval ini..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 rounded-b-xl">
                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <button onclick="closeDetailModal()" 
                            class="w-full sm:w-auto px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                    <button id="btnRejectFromDetail" onclick="rejectFromDetail()" 
                            class="w-full sm:w-auto px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-times-circle mr-2"></i>Reject Pembayaran
                    </button>
                    <button id="btnApproveFromDetail" onclick="approveFromDetail()" 
                            class="w-full sm:w-auto px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-check-circle mr-2"></i>Approve Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Bukti Pembayaran -->
<div id="previewBuktiModal" class="fixed inset-0 bg-black/20 backdrop-blur-sm hidden z-50 p-4">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Preview Bukti Pembayaran</h3>
                        <p class="text-sm text-gray-500" id="previewFileName">-</p>
                    </div>
                    <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600 p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <div class="text-center">
                    <!-- Simulate PDF preview -->
                    <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 min-h-[400px] flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-file-pdf text-6xl text-red-500 mb-4"></i>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Bukti Pembayaran</h4>
                            <p class="text-gray-600 mb-4">File PDF akan ditampilkan di sini</p>
                            <div class="space-y-2 text-sm text-gray-500">
                                <p>📋 Slip transfer bank</p>
                                <p>💰 Nominal: <span id="previewNominal">-</span></p>
                                <p>📅 Tanggal: <span id="previewTanggal">-</span></p>
                                <p>🏦 Bank: <span id="previewBank">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 rounded-b-xl">
                <div class="flex justify-end gap-3">
                    <button onclick="closePreviewModal()" 
                            class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                    <button onclick="downloadBuktiPembayaran()" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Approve -->
<div id="modalKonfirmasiApprove" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-50 p-4">
    <div class="flex items-center justify-center min-h-screen py-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-green-600 text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl mr-3"></i>
                    <div>
                        <h3 class="text-lg font-semibold">Konfirmasi Approval</h3>
                        <p class="text-green-100 text-sm">Setujui pembayaran proyek</p>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Setujui Pembayaran</h4>
                    <p class="text-gray-600 text-sm">Anda akan menyetujui pembayaran dengan detail berikut:</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Proyek:</span>
                        <span class="font-semibold" id="approveProyek">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis:</span>
                        <span class="font-semibold" id="approveJenis">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nominal:</span>
                        <span class="font-semibold text-green-600" id="approveNominal">-</span>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Approval (Opsional)</label>
                    <textarea id="approveModalNotes" rows="3" 
                              placeholder="Tambahkan catatan untuk approval ini..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"></textarea>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mt-0.5 mr-2"></i>
                        <div class="text-sm text-green-800">
                            <p class="font-medium">Setelah disetujui:</p>
                            <ul class="mt-1 list-disc list-inside text-green-700">
                                <li>Status pembayaran akan berubah menjadi "Terverifikasi"</li>
                                <li>Proyek dapat dilanjutkan ke tahap selanjutnya</li>
                                <li>Notifikasi akan dikirim ke tim terkait</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-xl border-t border-gray-200">
                <div class="flex gap-3 justify-end">
                    <button onclick="closeApproveModal()" 
                            class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button onclick="confirmApprove()" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-check-circle mr-2"></i>Ya, Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Reject -->
<div id="modalKonfirmasiReject" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-50 p-4">
    <div class="flex items-center justify-center min-h-screen py-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-red-600 text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-2xl mr-3"></i>
                    <div>
                        <h3 class="text-lg font-semibold">Konfirmasi Penolakan</h3>
                        <p class="text-red-100 text-sm">Tolak pembayaran proyek</p>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-times text-red-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Tolak Pembayaran</h4>
                    <p class="text-gray-600 text-sm">Anda akan menolak pembayaran dengan detail berikut:</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Proyek:</span>
                        <span class="font-semibold" id="rejectProyek">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis:</span>
                        <span class="font-semibold" id="rejectJenis">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nominal:</span>
                        <span class="font-semibold text-red-600" id="rejectNominal">-</span>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="rejectModalNotes" rows="4" 
                              placeholder="Jelaskan alasan penolakan pembayaran ini..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                              required></textarea>
                    <p class="text-xs text-gray-500 mt-1">Alasan penolakan wajib diisi untuk dokumentasi</p>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-600 mt-0.5 mr-2"></i>
                        <div class="text-sm text-red-800">
                            <p class="font-medium">Setelah ditolak:</p>
                            <ul class="mt-1 list-disc list-inside text-red-700">
                                <li>Pembayaran akan dikembalikan ke purchasing</li>
                                <li>Tim purchasing akan memperbaiki data</li>
                                <li>Proyek akan tertunda hingga pembayaran valid</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-xl border-t border-gray-200">
                <div class="flex gap-3 justify-end">
                    <button onclick="closeRejectModal()" 
                            class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button onclick="confirmReject()" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-times-circle mr-2"></i>Ya, Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Sample data untuk demo (dalam implementasi nyata, data ini akan di-fetch dari server)
const pembayaranData = {
    3: {
        id: 3,
        proyek_id: 1,
        kode_proyek: 'PNW-2024-001',
        nama_proyek: 'Sistem Informasi Manajemen',
        nama_instansi: 'Dinas Pendidikan DKI',
        jenis_pembayaran: 'DP',
        nominal: 255000000,
        tanggal_bayar: '2024-11-15',
        metode_pembayaran: 'Transfer Bank',
        status: 'menunggu_approval',
        bukti_pembayaran: 'bukti_dp_pnw2024001.pdf',
        catatan: 'Pembayaran DP 30% dari total nilai proyek',
        admin_input: 'Maya Indah',
        tanggal_input: '2024-11-15 09:30:00',
        total_nilai_proyek: 850000000,
        persentase_dp: 30,
        bank_pengirim: 'BNI',
        rekening_pengirim: '****1234',
        nomor_referensi: 'TRF202411150930001',
        daftar_barang: [
            { nama: 'Server HP ProLiant DL380', jumlah: 2, satuan: 'unit', harga: 45000000, total: 90000000 },
            { nama: 'Software Windows Server 2019', jumlah: 2, satuan: 'license', harga: 15000000, total: 30000000 },
            { nama: 'Database MySQL Enterprise', jumlah: 1, satuan: 'license', harga: 25000000, total: 25000000 },
            { nama: 'Switch Cisco 24 Port', jumlah: 3, satuan: 'unit', harga: 8000000, total: 24000000 },
            { nama: 'UPS APC 3000VA', jumlah: 2, satuan: 'unit', harga: 12000000, total: 24000000 },
            { nama: 'Instalasi & Konfigurasi', jumlah: 1, satuan: 'paket', harga: 50000000, total: 50000000 },
            { nama: 'Training & Support 1 Tahun', jumlah: 1, satuan: 'paket', harga: 35000000, total: 35000000 }
        ]
    },
    4: {
        id: 4,
        proyek_id: 6,
        kode_proyek: 'PNW-2024-006',
        nama_proyek: 'Aplikasi Smart City',
        nama_instansi: 'Pemkot Medan',
        jenis_pembayaran: 'Lunas',
        nominal: 1200000000,
        tanggal_bayar: '2024-11-16',
        metode_pembayaran: 'Transfer Bank',
        status: 'menunggu_approval',
        bukti_pembayaran: 'bukti_lunas_pnw2024006.pdf',
        catatan: 'Pembayaran lunas langsung (tanpa DP)',
        admin_input: 'Sari Wijaya',
        tanggal_input: '2024-11-16 14:15:00',
        total_nilai_proyek: 1200000000,
        persentase_dp: 0,
        bank_pengirim: 'Mandiri',
        rekening_pengirim: '****5678',
        nomor_referensi: 'TRF202411161415002',
        daftar_barang: [
            { nama: 'Mobile App Development (Android)', jumlah: 1, satuan: 'project', harga: 350000000, total: 350000000 },
            { nama: 'Mobile App Development (iOS)', jumlah: 1, satuan: 'project', harga: 350000000, total: 350000000 },
            { nama: 'Web Dashboard Admin', jumlah: 1, satuan: 'project', harga: 200000000, total: 200000000 },
            { nama: 'API & Backend Services', jumlah: 1, satuan: 'project', harga: 150000000, total: 150000000 },
            { nama: 'Cloud Server Setup (1 Year)', jumlah: 1, satuan: 'package', harga: 50000000, total: 50000000 },
            { nama: 'Testing & QA', jumlah: 1, satuan: 'package', harga: 100000000, total: 100000000 }
        ]
    },
    5: {
        id: 5,
        proyek_id: 2,
        kode_proyek: 'PNW-2024-002',
        nama_proyek: 'Portal E-Government',
        nama_instansi: 'Pemda Bandung',
        jenis_pembayaran: 'Lunas',
        nominal: 420000000,
        tanggal_bayar: '2024-11-17',
        metode_pembayaran: 'Transfer Bank',
        status: 'menunggu_approval',
        bukti_pembayaran: 'bukti_pelunasan_pnw2024002.pdf',
        catatan: 'Pelunasan sisa pembayaran (70% dari total)',
        admin_input: 'Andi Prasetyo',
        tanggal_input: '2024-11-17 11:45:00',
        total_nilai_proyek: 600000000,
        persentase_dp: 30,
        bank_pengirim: 'BCA',
        rekening_pengirim: '****9012',
        nomor_referensi: 'TRF202411171145003',
        daftar_barang: [
            { nama: 'Web Portal Development', jumlah: 1, satuan: 'project', harga: 250000000, total: 250000000 },
            { nama: 'Database Design & Setup', jumlah: 1, satuan: 'project', harga: 75000000, total: 75000000 },
            { nama: 'User Management System', jumlah: 1, satuan: 'module', harga: 50000000, total: 50000000 },
            { nama: 'Document Management', jumlah: 1, satuan: 'module', harga: 60000000, total: 60000000 },
            { nama: 'Security & Authentication', jumlah: 1, satuan: 'package', harga: 40000000, total: 40000000 },
            { nama: 'Hosting & Maintenance (1 Year)', jumlah: 1, satuan: 'package', harga: 35000000, total: 35000000 },
            { nama: 'Training & Documentation', jumlah: 1, satuan: 'package', harga: 90000000, total: 90000000 }
        ]
    }
};

let currentPembayaranId = null;

// Function to show detail pembayaran
function showDetailPembayaran(pembayaranId) {
    currentPembayaranId = pembayaranId;
    const data = pembayaranData[pembayaranId];
    
    if (!data) {
        alert('Data pembayaran tidak ditemukan');
        return;
    }
    
    // Populate modal with data
    document.getElementById('detailKodeProyek').textContent = data.kode_proyek;
    document.getElementById('detailNamaProyek').textContent = data.nama_proyek;
    document.getElementById('detailInstansi').textContent = data.nama_instansi;
    document.getElementById('detailTotalNilai').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_nilai_proyek);
    
    const jenisLabel = data.jenis_pembayaran === 'DP' ? 
        `📋 Down Payment (${data.persentase_dp}%)` : 
        (data.persentase_dp > 0 ? '💰 Pelunasan (70%)' : '💰 Pembayaran Lunas');
    document.getElementById('detailJenisPembayaran').textContent = jenisLabel;
    document.getElementById('detailNominalPembayaran').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.nominal);
    
    // Populate rincian barang
    const daftarBarangContainer = document.getElementById('detailDaftarBarang');
    daftarBarangContainer.innerHTML = '';
    
    if (data.daftar_barang && data.daftar_barang.length > 0) {
        data.daftar_barang.forEach(barang => {
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
                        <span class="text-gray-600">Rp ${new Intl.NumberFormat('id-ID').format(barang.harga)}</span>
                    </div>
                    <div class="text-right">
                        <span class="font-semibold text-purple-600">Rp ${new Intl.NumberFormat('id-ID').format(barang.total)}</span>
                    </div>
                </div>
            `;
            daftarBarangContainer.appendChild(barangElement);
        });
    } else {
        daftarBarangContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Tidak ada data rincian barang</p>';
    }
    
    document.getElementById('detailTotalProyek').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_nilai_proyek);
    
    document.getElementById('detailFileBukti').textContent = data.bukti_pembayaran;
    document.getElementById('detailCatatan').textContent = data.catatan;
    
    document.getElementById('detailAdminInput').textContent = data.admin_input;
    document.getElementById('detailTanggalInput').textContent = new Date(data.tanggal_input).toLocaleString('id-ID');
    
    // Clear checklist and notes
    document.querySelectorAll('#detailPembayaranModal input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.getElementById('approvalNotes').value = '';
    
    // Show modal
    document.getElementById('detailPembayaranModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Function to close detail modal
function closeDetailModal() {
    document.getElementById('detailPembayaranModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentPembayaranId = null;
}

// Function to preview bukti pembayaran
function previewBuktiPembayaran() {
    if (!currentPembayaranId) return;
    
    const data = pembayaranData[currentPembayaranId];
    document.getElementById('previewFileName').textContent = data.bukti_pembayaran;
    document.getElementById('previewNominal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.nominal);
    document.getElementById('previewTanggal').textContent = new Date(data.tanggal_bayar).toLocaleDateString('id-ID');
    document.getElementById('previewBank').textContent = data.bank_pengirim;
    
    document.getElementById('previewBuktiModal').classList.remove('hidden');
}

// Function to close preview modal
function closePreviewModal() {
    document.getElementById('previewBuktiModal').classList.add('hidden');
}

// Function to download bukti pembayaran
function downloadBuktiPembayaran() {
    if (!currentPembayaranId) return;
    
    const data = pembayaranData[currentPembayaranId];
    alert('Download dimulai untuk file: ' + data.bukti_pembayaran);
    // Dalam implementasi nyata, ini akan trigger download file
}

// Function to approve from detail modal
function approveFromDetail() {
    if (!currentPembayaranId) return;
    
    // Check if all required validations are checked
    const checkboxes = document.querySelectorAll('#detailPembayaranModal input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    if (!allChecked) {
        alert('Harap centang semua checklist validasi sebelum melakukan approval.');
        return;
    }
    
    // Show approve modal
    const data = pembayaranData[currentPembayaranId];
    document.getElementById('approveProyek').textContent = data.kode_proyek + ' - ' + data.nama_proyek;
    document.getElementById('approveJenis').textContent = data.jenis_pembayaran;
    document.getElementById('approveNominal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.nominal);
    
    // Pre-fill with existing notes from detail modal
    const existingNotes = document.getElementById('approvalNotes').value.trim();
    document.getElementById('approveModalNotes').value = existingNotes;
    
    document.getElementById('modalKonfirmasiApprove').classList.remove('hidden');
}

// Function to reject from detail modal
function rejectFromDetail() {
    if (!currentPembayaranId) return;
    
    // Show reject modal
    const data = pembayaranData[currentPembayaranId];
    document.getElementById('rejectProyek').textContent = data.kode_proyek + ' - ' + data.nama_proyek;
    document.getElementById('rejectJenis').textContent = data.jenis_pembayaran;
    document.getElementById('rejectNominal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.nominal);
    
    // Pre-fill with existing notes from detail modal
    const existingNotes = document.getElementById('approvalNotes').value.trim();
    document.getElementById('rejectModalNotes').value = existingNotes;
    
    document.getElementById('modalKonfirmasiReject').classList.remove('hidden');
}

// Function to close approve modal
function closeApproveModal() {
    document.getElementById('modalKonfirmasiApprove').classList.add('hidden');
}

// Function to close reject modal
function closeRejectModal() {
    document.getElementById('modalKonfirmasiReject').classList.add('hidden');
}

// Function to confirm approve
function confirmApprove() {
    if (!currentPembayaranId) return;
    
    const approvalNotes = document.getElementById('approveModalNotes').value.trim();
    
    // Show loading
    const btn = event.target;
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    btn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        closeApproveModal();
        closeDetailModal();
        
        const data = pembayaranData[currentPembayaranId];
        const successMessage = `✅ Pembayaran Berhasil Disetujui!\n\n` +
                              `Proyek: ${data.kode_proyek}\n` +
                              `Status: Terverifikasi\n` +
                              `${approvalNotes ? 'Catatan: ' + approvalNotes : ''}\n\n` +
                              `Proyek dapat dilanjutkan ke tahap selanjutnya.`;
        
        alert(successMessage);
        location.reload(); // Refresh untuk update data
    }, 2000);
}

// Function to confirm reject
function confirmReject() {
    if (!currentPembayaranId) return;
    
    const rejectNotes = document.getElementById('rejectModalNotes').value.trim();
    
    if (!rejectNotes) {
        alert('Harap berikan alasan penolakan yang jelas.');
        document.getElementById('rejectModalNotes').focus();
        return;
    }
    
    // Show loading
    const btn = event.target;
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    btn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        closeRejectModal();
        closeDetailModal();
        
        const data = pembayaranData[currentPembayaranId];
        const rejectMessage = `❌ Pembayaran Berhasil Ditolak!\n\n` +
                             `Proyek: ${data.kode_proyek}\n` +
                             `Alasan: ${rejectNotes}\n\n` +
                             `Data akan dikembalikan ke purchasing untuk diperbaiki.`;
        
        alert(rejectMessage);
        location.reload(); // Refresh untuk update data
    }, 2000);
}

// Close modal when clicking outside
document.getElementById('detailPembayaranModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});

document.getElementById('previewBuktiModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});

document.getElementById('modalKonfirmasiApprove').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApproveModal();
    }
});

document.getElementById('modalKonfirmasiReject').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// ESC key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closePreviewModal();
        closeApproveModal();
        closeRejectModal();
    }
});

// Function to open approval confirmation modal
function openApproveModal() {
    if (!currentPembayaranId) return;
    
    const data = pembayaranData[currentPembayaranId];
    document.getElementById('approveProyek').textContent = `${data.kode_proyek} - ${data.nama_proyek}`;
    document.getElementById('approveJenis').textContent = data.jenis_pembayaran === 'DP' ? 
        `Down Payment (${data.persentase_dp}%)` : 
        (data.persentase_dp > 0 ? 'Pelunasan (70%)' : 'Pembayaran Lunas');
    document.getElementById('approveNominal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.nominal);
    
    document.getElementById('modalKonfirmasiApprove').classList.remove('hidden');
}

// Function to close approval confirmation modal
function closeApproveModal() {
    document.getElementById('modalKonfirmasiApprove').classList.add('hidden');
}

// Function to confirm approval
function confirmApprove() {
    const notes = document.getElementById('approveModalNotes').value.trim();
    const data = pembayaranData[currentPembayaranId];
    
    // Show loading
    const btn = document.querySelector('#modalKonfirmasiApprove button[onclick="confirmApprove()"]');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    btn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        alert('Pembayaran berhasil disetujui!\n\nStatus: Terverifikasi\nProyek dapat dilanjutkan.');
        closeApproveModal();
        closeDetailModal();
        location.reload(); // Refresh untuk update data
    }, 2000);
}

// Function to open reject confirmation modal
function openRejectModal() {
    if (!currentPembayaranId) return;
    
    const data = pembayaranData[currentPembayaranId];
    document.getElementById('rejectProyek').textContent = `${data.kode_proyek} - ${data.nama_proyek}`;
    document.getElementById('rejectJenis').textContent = data.jenis_pembayaran === 'DP' ? 
        `Down Payment (${data.persentase_dp}%)` : 
        (data.persentase_dp > 0 ? 'Pelunasan (70%)' : 'Pembayaran Lunas');
    document.getElementById('rejectNominal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.nominal);
    
    document.getElementById('modalKonfirmasiReject').classList.remove('hidden');
}

// Function to close reject confirmation modal
function closeRejectModal() {
    document.getElementById('modalKonfirmasiReject').classList.add('hidden');
}

// Function to confirm rejection
function confirmReject() {
    const notes = document.getElementById('rejectModalNotes').value.trim();
    const data = pembayaranData[currentPembayaranId];
    
    if (!notes) {
        alert('Alasan penolakan wajib diisi');
        document.getElementById('rejectModalNotes').focus();
        return;
    }
    
    // Show loading
    const btn = document.querySelector('#modalKonfirmasiReject button[onclick="confirmReject()"]');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    btn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        alert('Pembayaran berhasil ditolak!\n\nAlasan: ' + notes + '\n\nData akan dikembalikan ke purchasing untuk diperbaiki.');
        closeRejectModal();
        closeDetailModal();
        location.reload(); // Refresh untuk update data
    }, 2000);
}
</script>
