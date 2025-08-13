<!-- Modal Hapus Proyek -->
<div id="modalHapusProyek" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 text-center flex-shrink-0">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 object-contain">
            </div>
            <h3 class="text-xl font-bold mb-2">Konfirmasi Hapus</h3>
            <p class="text-red-100 text-sm">Tindakan ini tidak dapat dibatalkan</p>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 250px);">
            <div class="text-center mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Apakah Anda yakin?</h4>
                <p class="text-gray-600 mb-4">Anda akan menghapus proyek berikut:</p>
                
                <!-- Item Info -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Kode:</span>
                            <span id="hapusKode" class="text-sm text-gray-800 font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Instansi:</span>
                            <span id="hapusInstansi" class="text-sm text-gray-800 font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Kabupaten/Kota:</span>
                            <span id="hapusKabupaten" class="text-sm text-gray-800 font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Status:</span>
                            <span id="hapusStatus" class="text-sm font-medium">-</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div class="text-left">
                            <p class="text-sm font-medium text-red-800">Perhatian!</p>
                            <p class="text-sm text-red-700 mt-1">
                                Data yang sudah dihapus tidak dapat dikembalikan. Pastikan Anda benar-benar yakin sebelum melanjutkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alasan Hapus -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penghapusan <span class="text-red-500">*</span>
                </label>
                <select id="alasanHapus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
                    <option value="">Pilih alasan penghapusan</option>
                    <option value="Data duplikat">Data duplikat</option>
                    <option value="Input error">Input error/kesalahan data</option>
                    <option value="Dibatalkan klien">Dibatalkan oleh klien</option>
                    <option value="Expired">Proyek expired</option>
                    <option value="Tidak relevan">Tidak relevan lagi</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- Catatan Tambahan -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan <span class="text-gray-400">(opsional)</span>
                </label>
                <textarea id="catatanHapus" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Berikan keterangan tambahan jika diperlukan..."></textarea>
            </div>

            <!-- Konfirmasi Checkbox -->
            <div class="mb-6">
                <label class="flex items-start space-x-3">
                    <input type="checkbox" id="konfirmasiHapus" class="mt-1 h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <span class="text-sm text-gray-700">
                        Saya memahami bahwa tindakan ini akan menghapus data secara permanen dan tidak dapat dibatalkan.
                    </span>
                </label>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalHapusProyek')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Batal
            </button>
            <button type="button" onclick="confirmHapus()" id="btnHapus" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center" disabled>
                <i class="fas fa-trash mr-2"></i>
                Hapus Proyek
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Terakhir -->
<div id="modalKonfirmasiAkhir" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-60 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden my-4 mx-auto">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi Terakhir</h3>
            <p class="text-gray-600 mb-6">Ketik <strong>"HAPUS"</strong> untuk mengkonfirmasi penghapusan</p>
            
            <input type="text" id="konfirmasiText" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-center font-medium" placeholder="Ketik HAPUS" maxlength="5">
            
            <div class="flex items-center justify-center space-x-3 mt-6">
                <button type="button" onclick="closeModal('modalKonfirmasiAkhir')" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </button>
                <button type="button" onclick="executeHapus()" id="btnKonfirmasiAkhir" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200" disabled>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let hapusData = null;

function loadHapusData(data) {
    hapusData = data;
    
    // Load item info
    document.getElementById('hapusKode').textContent = data.kode;
    document.getElementById('hapusInstansi').textContent = data.nama_instansi;
    document.getElementById('hapusKabupaten').textContent = data.kabupaten_kota;
    
    // Set status with appropriate styling
    const statusElement = document.getElementById('hapusStatus');
    statusElement.textContent = data.status;
    statusElement.className = 'text-sm font-medium ' + getHapusStatusClass(data.status);
    
    // Reset form
    document.getElementById('alasanHapus').value = '';
    document.getElementById('catatanHapus').value = '';
    document.getElementById('konfirmasiHapus').checked = false;
    document.getElementById('btnHapus').disabled = true;
}

function getHapusStatusClass(status) {
    switch (status.toLowerCase()) {
        case 'diterima':
            return 'text-green-600';
        case 'pending':
            return 'text-yellow-600';
        case 'ditolak':
        case 'expired':
            return 'text-red-600';
        case 'review':
            return 'text-blue-600';
        default:
            return 'text-gray-600';
    }
}

// Example function to open hapus modal
function openHapusModal(id) {
    // This would typically fetch data from server
    const sampleData = {
        id: id,
        kode: 'PNW-001',
        nama_instansi: 'Dinas Pendidikan DKI Jakarta',
        kabupaten_kota: 'Jakarta Pusat',
        status: 'Diterima'
    };
    
    loadHapusData(sampleData);
    document.getElementById('modalHapusProyek').classList.remove('hidden');
    document.getElementById('modalHapusProyek').classList.add('flex');
}

// Enable/disable hapus button based on form validation
function validateHapusForm() {
    const alasan = document.getElementById('alasanHapus').value;
    const konfirmasi = document.getElementById('konfirmasiHapus').checked;
    const btnHapus = document.getElementById('btnHapus');
    
    if (alasan && konfirmasi) {
        btnHapus.disabled = false;
        btnHapus.classList.remove('opacity-50');
    } else {
        btnHapus.disabled = true;
        btnHapus.classList.add('opacity-50');
    }
}

// Event listeners for form validation
document.getElementById('alasanHapus').addEventListener('change', validateHapusForm);
document.getElementById('konfirmasiHapus').addEventListener('change', validateHapusForm);

function confirmHapus() {
    // Close first modal and open confirmation modal
    document.getElementById('modalHapusProyek').classList.add('hidden');
    document.getElementById('modalHapusProyek').classList.remove('flex');
    
    document.getElementById('modalKonfirmasiAkhir').classList.remove('hidden');
    document.getElementById('modalKonfirmasiAkhir').classList.add('flex');
    
    // Reset confirmation input
    document.getElementById('konfirmasiText').value = '';
    document.getElementById('btnKonfirmasiAkhir').disabled = true;
}

// Validate final confirmation text
document.getElementById('konfirmasiText').addEventListener('input', function() {
    const text = this.value.toUpperCase();
    const btn = document.getElementById('btnKonfirmasiAkhir');
    
    if (text === 'HAPUS') {
        btn.disabled = false;
        btn.classList.remove('opacity-50');
    } else {
        btn.disabled = true;
        btn.classList.add('opacity-50');
    }
});

function executeHapus() {
    const alasan = document.getElementById('alasanHapus').value;
    const catatan = document.getElementById('catatanHapus').value;
    
    // Simulate deletion process
    const btn = document.getElementById('btnKonfirmasiAkhir');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
    btn.disabled = true;
    
    setTimeout(() => {
        // Close all modals
        closeModal('modalKonfirmasiAkhir');
        
        // Show success message
        showSuccessMessage();
        
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        // Here you would typically make an API call to delete the data
        const hapusData = window.hapusData || {};
        console.log('Deleting item:', {
            id: hapusData.id || null,
            alasan: alasan,
            catatan: catatan
        });
        
    }, 2000);
}

function showSuccessMessage() {
    // Show success modal
    const hapusData = window.hapusData || {};
    const kode = hapusData.kode ? hapusData.kode : 'Proyek';
    showSuccessModal(`Proyek ${kode} berhasil dihapus dari sistem!`);
}

// Close modal when clicking outside
document.getElementById('modalHapusProyek').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal('modalHapusProyek');
    }
});

document.getElementById('modalKonfirmasiAkhir').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal('modalKonfirmasiAkhir');
    }
});

// Handle ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('modalKonfirmasiAkhir').classList.contains('hidden')) {
            closeModal('modalKonfirmasiAkhir');
        } else if (!document.getElementById('modalHapusProyek').classList.contains('hidden')) {
            closeModal('modalHapusProyek');
        }
    }
});
</script>

<style>
.z-60 {
    z-index: 60;
}

/* Loading state */
.opacity-50 {
    opacity: 0.5;
}

/* Smooth transitions */
#modalHapusProyek,
#modalKonfirmasiAkhir {
    transition: all 0.3s ease;
}

/* Custom scrollbar for modal content */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
