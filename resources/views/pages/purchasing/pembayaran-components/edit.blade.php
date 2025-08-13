<!-- Modal Edit Pembayaran -->
<div id="editPaymentModal" class="fixed inset-0 bg-black/20 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
        <div class="bg-white rounded-lg sm:rounded-xl max-w-sm sm:max-w-lg lg:max-w-2xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Edit Pembayaran</h3>
                    <button onclick="closeEditPaymentModal()" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-lg sm:text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="editPaymentForm" class="p-4 sm:p-6">
                <!-- Info Proyek (Read Only) -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3">Informasi Proyek</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div><span class="font-medium">Kode:</span> <span id="editProyekKode">PNW-2024-001</span></div>
                        <div><span class="font-medium">Proyek:</span> <span id="editProyekNama">Sistem Informasi Manajemen</span></div>
                        <div><span class="font-medium">Instansi:</span> <span id="editProyekInstansi">Dinas Pendidikan DKI</span></div>
                        <div><span class="font-medium">Total Nilai:</span> <span id="editProyekTotal">Rp 850.000.000</span></div>
                    </div>
                </div>

                <!-- Hidden ID -->
                <input type="hidden" id="editPaymentId" name="payment_id">

                <!-- Jenis Pembayaran -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pembayaran *</label>
                    <select id="editJenisPembayaran" name="jenis_pembayaran" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="DP">DP (Down Payment)</option>
                        <option value="Lunas">Lunas (Pelunasan)</option>
                        <option value="Cicilan">Cicilan</option>
                    </select>
                </div>

                <!-- Nominal Bayar -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Bayar *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                        <input type="text" id="editNominalBayar" name="nominal" required 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="0">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan nominal tanpa titik atau koma</p>
                </div>

                <!-- Tanggal Bayar -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar *</label>
                    <input type="date" id="editTanggalBayar" name="tanggal_bayar" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <!-- Metode Pembayaran -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
                    <select id="editMetodePembayaran" name="metode_pembayaran" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Giro">Giro</option>
                        <option value="Cek">Cek</option>
                        <option value="Virtual Account">Virtual Account</option>
                        <option value="E-Wallet">E-Wallet</option>
                    </select>
                </div>

                <!-- Status Pembayaran -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran *</label>
                    <select id="editStatusPembayaran" name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                        <option value="terverifikasi">Terverifikasi</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <!-- Upload Bukti Pembayaran Baru (Opsional) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Bukti Pembayaran (Opsional)</label>
                    
                    <!-- Bukti yang sudah ada -->
                    <div id="existingFileArea" class="mb-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file text-blue-600"></i>
                                <span id="existingFileName" class="text-sm font-medium">bukti_dp_pnw2024001.pdf</span>
                                <span class="text-xs text-gray-500">(File saat ini)</span>
                            </div>
                            <button type="button" onclick="downloadExistingFile()" class="text-blue-600 hover:text-blue-700 text-sm w-fit">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                    </div>

                    <!-- Upload file baru -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 text-center hover:border-red-400 transition-colors duration-200">
                        <input type="file" id="editBuktiPembayaran" name="bukti_pembayaran_baru" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                        <div id="editUploadArea" onclick="document.getElementById('editBuktiPembayaran').click()" class="cursor-pointer">
                            <i class="fas fa-upload text-2xl sm:text-3xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 font-medium text-sm sm:text-base">Klik untuk upload file baru</p>
                            <p class="text-xs sm:text-sm text-gray-500">PDF, JPG, JPEG, PNG (Max. 5MB)</p>
                        </div>
                        <div id="editFileInfo" class="hidden mt-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file text-green-600"></i>
                                    <span id="editFileName" class="text-sm font-medium"></span>
                                    <span class="text-xs text-green-600">(File baru)</span>
                                </div>
                                <button type="button" onclick="removeEditFile()" class="text-red-600 hover:text-red-700 w-fit">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Jika tidak mengganti file, biarkan kosong. File lama akan tetap digunakan.</p>
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea id="editCatatan" name="catatan" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                <!-- Alasan Edit (Wajib) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Edit *</label>
                    <textarea name="alasan_edit" rows="2" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Jelaskan alasan melakukan perubahan data pembayaran..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Alasan edit akan dicatat dalam log audit</p>
                </div>

                <!-- Log Perubahan -->
                <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <h5 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        Catatan Penting
                    </h5>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• Perubahan data pembayaran akan dicatat dalam log audit</li>
                        <li>• Admin keuangan akan mendapat notifikasi perubahan</li>
                        <li>• Status "Terverifikasi" memerlukan persetujuan ulang</li>
                    </ul>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="button" onclick="closeEditPaymentModal()" 
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors duration-200 text-sm sm:text-base">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                        Update Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions untuk edit
function closeEditPaymentModal() {
    document.getElementById('editPaymentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editPaymentForm').reset();
    document.getElementById('editFileInfo').classList.add('hidden');
    document.getElementById('editUploadArea').classList.remove('hidden');
}

function showEditPayment(paymentId) {
    // Data dummy pembayaran - dalam implementasi nyata ambil dari server
    const paymentData = {
        1: {
            id: 1,
            proyek_kode: 'PNW-2024-001',
            proyek_nama: 'Sistem Informasi Manajemen',
            proyek_instansi: 'Dinas Pendidikan DKI',
            proyek_total: 850000000,
            jenis_pembayaran: 'DP',
            nominal: 255000000,
            tanggal_bayar: '2024-11-01',
            metode_pembayaran: 'Transfer Bank',
            status: 'terverifikasi',
            catatan: 'Pembayaran DP 30% dari total nilai proyek',
            bukti_pembayaran: 'bukti_dp_pnw2024001.pdf'
        }
    };

    const payment = paymentData[paymentId];
    if (payment) {
        // Set data proyek
        document.getElementById('editProyekKode').textContent = payment.proyek_kode;
        document.getElementById('editProyekNama').textContent = payment.proyek_nama;
        document.getElementById('editProyekInstansi').textContent = payment.proyek_instansi;
        document.getElementById('editProyekTotal').textContent = 'Rp ' + payment.proyek_total.toLocaleString('id-ID');

        // Set data pembayaran
        document.getElementById('editPaymentId').value = payment.id;
        document.getElementById('editJenisPembayaran').value = payment.jenis_pembayaran;
        document.getElementById('editNominalBayar').value = payment.nominal.toLocaleString('id-ID');
        document.getElementById('editTanggalBayar').value = payment.tanggal_bayar;
        document.getElementById('editMetodePembayaran').value = payment.metode_pembayaran;
        document.getElementById('editStatusPembayaran').value = payment.status;
        document.getElementById('editCatatan').value = payment.catatan;
        document.getElementById('existingFileName').textContent = payment.bukti_pembayaran;

        // Tampilkan modal
        document.getElementById('editPaymentModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

// File upload handling untuk edit
document.getElementById('editBuktiPembayaran').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('editFileName').textContent = file.name;
        document.getElementById('editUploadArea').classList.add('hidden');
        document.getElementById('editFileInfo').classList.remove('hidden');
    }
});

function removeEditFile() {
    document.getElementById('editBuktiPembayaran').value = '';
    document.getElementById('editUploadArea').classList.remove('hidden');
    document.getElementById('editFileInfo').classList.add('hidden');
}

function downloadExistingFile() {
    const fileName = document.getElementById('existingFileName').textContent;
    alert('Mengunduh file: ' + fileName);
    // Implementasi download file
}

// Format number input untuk edit
document.getElementById('editNominalBayar').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d]/g, '');
    e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
});

// Form submission untuk edit
document.getElementById('editPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validasi
    const alasanEdit = e.target.alasan_edit.value.trim();
    if (!alasanEdit) {
        alert('Alasan edit harus diisi!');
        return;
    }

    // Konfirmasi perubahan
    const isConfirmed = confirm('Apakah Anda yakin ingin menyimpan perubahan?\n\nPerubahan akan dicatat dalam log audit dan memerlukan verifikasi ulang.');
    
    if (isConfirmed) {
        // Simulate form submission
        alert('Pembayaran berhasil diupdate!\nStatus: Menunggu Verifikasi Ulang');
        closeEditPaymentModal();
        
        // Refresh detail jika modal detail terbuka
        if (!document.getElementById('detailPaymentModal').classList.contains('hidden')) {
            // Refresh detail modal
            location.reload(); // Atau update data secara dinamis
        }
    }
});

// Validasi status pembayaran
document.getElementById('editStatusPembayaran').addEventListener('change', function(e) {
    const status = e.target.value;
    const nominalInput = document.getElementById('editNominalBayar');
    
    if (status === 'ditolak') {
        // Jika ditolak, nominal tidak bisa diubah
        nominalInput.setAttribute('readonly', true);
        nominalInput.classList.add('bg-gray-100');
    } else {
        nominalInput.removeAttribute('readonly');
        nominalInput.classList.remove('bg-gray-100');
    }
});

// Auto-fill nominal berdasarkan jenis pembayaran
document.getElementById('editJenisPembayaran').addEventListener('change', function(e) {
    const jenis = e.target.value;
    const proyekTotal = parseInt(document.getElementById('editProyekTotal').textContent.replace(/[^\d]/g, ''));
    
    if (jenis === 'DP') {
        // Auto-fill 30% untuk DP
        const nominalDP = Math.round(proyekTotal * 0.3);
        document.getElementById('editNominalBayar').value = nominalDP.toLocaleString('id-ID');
    }
});
</script>
