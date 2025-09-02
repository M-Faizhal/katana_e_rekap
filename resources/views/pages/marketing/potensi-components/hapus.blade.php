<!-- Modal Hapus Potensi -->
<div id="modalHapusPotensi" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
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
                <p class="text-gray-600 mb-4">Anda akan menghapus potensi proyek berikut:</p>

                <!-- Item Info -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Kode:</span>
                            <span id="hapusPotensiKode" class="text-sm text-gray-800 font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Nama Proyek:</span>
                            <span id="hapusPotensiNamaProyek" class="text-sm text-gray-800 font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Instansi:</span>
                            <span id="hapusPotensiInstansi" class="text-sm text-gray-800 font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Kabupaten/Kota:</span>
                            <span id="hapusPotensiKabupaten" class="text-sm text-gray-800 font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Status:</span>
                            <span id="hapusPotensiStatus" class="text-sm font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Vendor:</span>
                            <span id="hapusPotensiVendor" class="text-sm text-gray-800 font-medium">-</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div class="text-left">
                            <p class="text-sm font-medium text-red-800">Perhatian!</p>
                            <p class="text-sm text-red-700 mt-1">
                                Data potensi yang sudah dihapus tidak dapat dikembalikan. Pastikan Anda benar-benar yakin sebelum melanjutkan.
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
                <select id="alasanHapusPotensi" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
                    <option value="">Pilih alasan penghapusan</option>
                    <option value="Data duplikat">Data duplikat</option>
                    <option value="Input error">Input error/kesalahan data</option>
                    <option value="Dibatalkan klien">Dibatalkan oleh klien</option>
                    <option value="Vendor tidak memenuhi syarat">Vendor tidak memenuhi syarat</option>
                    <option value="Proyek expired">Proyek expired</option>
                    <option value="Tidak relevan">Tidak relevan lagi</option>
                    <option value="Assignment ulang">Assignment ulang ke vendor lain</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- Catatan Tambahan -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan <span class="text-gray-400">(opsional)</span>
                </label>
                <textarea id="catatanHapusPotensi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Berikan keterangan tambahan jika diperlukan..."></textarea>
            </div>

            <!-- Konfirmasi Checkbox -->
            <div class="mb-6">
                <label class="flex items-start space-x-3">
                    <input type="checkbox" id="konfirmasiHapusPotensi" class="mt-1 h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <span class="text-sm text-gray-700">
                        Saya memahami bahwa tindakan ini akan menghapus data potensi secara permanen dan tidak dapat dibatalkan.
                    </span>
                </label>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalHapusPotensi')" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                Batal
            </button>
            <button type="button" onclick="confirmHapusPotensi()" id="btnHapusPotensi" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center" disabled>
                <i class="fas fa-trash mr-2"></i>
                Hapus Potensi
            </button>
        </div>
    </div>
</div>

<script>
// Global variable to store potensi data for deletion
let hapusPotensiData = null;

// Function to load hapus potensi data
function loadHapusPotensiData(data) {
    console.log('Loading hapus potensi data:', data);

    hapusPotensiData = data;

    // Set display data
    document.getElementById('hapusPotensiKode').textContent = data.kode_proyek || '-';
    document.getElementById('hapusPotensiNamaProyek').textContent = data.nama_proyek || '-';
    document.getElementById('hapusPotensiInstansi').textContent = data.instansi || '-';
    document.getElementById('hapusPotensiKabupaten').textContent = data.kabupaten_kota || '-';
    document.getElementById('hapusPotensiVendor').textContent = data.vendor_nama || '-';

    // Set status with proper styling
    const statusElement = document.getElementById('hapusPotensiStatus');
    const statusText = data.status || 'pending';
    statusElement.textContent = ucfirst(statusText);

    // Apply status styling
    statusElement.className = 'text-sm font-medium';
    if (statusText === 'sukses') {
        statusElement.classList.add('text-green-600');
    } else {
        statusElement.classList.add('text-yellow-600');
    }

    // Reset form
    document.getElementById('alasanHapusPotensi').value = '';
    document.getElementById('catatanHapusPotensi').value = '';
    document.getElementById('konfirmasiHapusPotensi').checked = false;

    // Disable delete button initially
    updateDeleteButtonState();
}

// Function to update delete button state
function updateDeleteButtonState() {
    const alasan = document.getElementById('alasanHapusPotensi').value;
    const konfirmasi = document.getElementById('konfirmasiHapusPotensi').checked;
    const button = document.getElementById('btnHapusPotensi');

    if (alasan && konfirmasi) {
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// Event listeners for form validation
document.addEventListener('DOMContentLoaded', function() {
    const alasanSelect = document.getElementById('alasanHapusPotensi');
    const konfirmasiCheck = document.getElementById('konfirmasiHapusPotensi');

    if (alasanSelect) {
        alasanSelect.addEventListener('change', updateDeleteButtonState);
    }

    if (konfirmasiCheck) {
        konfirmasiCheck.addEventListener('change', updateDeleteButtonState);
    }
});

// Function to confirm hapus potensi
function confirmHapusPotensi() {
    if (!hapusPotensiData) {
        showCustomAlert('error', 'Data potensi tidak ditemukan');
        return;
    }

    const alasan = document.getElementById('alasanHapusPotensi').value;
    const catatan = document.getElementById('catatanHapusPotensi').value;
    const konfirmasi = document.getElementById('konfirmasiHapusPotensi').checked;

    if (!alasan) {
        showCustomAlert('error', 'Mohon pilih alasan penghapusan');
        return;
    }

    if (!konfirmasi) {
        showCustomAlert('error', 'Mohon centang konfirmasi untuk melanjutkan');
        return;
    }

    // Show loading state
    const button = document.getElementById('btnHapusPotensi');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
    button.disabled = true;

    // Prepare deletion data
    const deleteData = {
        alasan: alasan,
        catatan: catatan,
        _method: 'DELETE'
    };

    // Send delete request
    fetch(`/marketing/potensi/${hapusPotensiData.id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(deleteData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('modalHapusPotensi');
            showSuccessModal('Potensi proyek berhasil dihapus!');

            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan saat menghapus data');
        }
    })
    .catch(error => {
        console.error('Error deleting potensi:', error);
        showCustomAlert('error', error.message || 'Terjadi kesalahan saat menghapus data');

        // Restore button
        button.innerHTML = originalText;
        button.disabled = false;
        updateDeleteButtonState();
    });
}

// Function to show custom alert (if not already defined)
if (typeof showCustomAlert === 'undefined') {
    function showCustomAlert(type, message, title = null) {
        // Check if custom alert exists, otherwise use standard alert
        if (document.getElementById('customAlertOverlay')) {
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
        } else {
            alert(message);
        }
    }
}

// Function to show success modal (if not already defined)
if (typeof showSuccessModal === 'undefined') {
    function showSuccessModal(message) {
        if (document.getElementById('successModal')) {
            // Use existing success modal
            document.getElementById('successMessage').textContent = message;
            openModal('successModal');
        } else {
            showCustomAlert('success', message);
        }
    }
}

// Helper function untuk capitalize first letter (if not already defined)
if (typeof ucfirst === 'undefined') {
    function ucfirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }
}

// Function to reset hapus modal when closed
function resetHapusPotensiModal() {
    document.getElementById('alasanHapusPotensi').value = '';
    document.getElementById('catatanHapusPotensi').value = '';
    document.getElementById('konfirmasiHapusPotensi').checked = false;
    updateDeleteButtonState();
    hapusPotensiData = null;
}

// Add event listener to reset modal when closed
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalHapusPotensi');
    if (modal) {
        // Reset when modal is closed
        modal.addEventListener('transitionend', function(e) {
            if (e.target === modal && modal.classList.contains('hidden')) {
                resetHapusPotensiModal();
            }
        });
    }
});
</script>
