<!-- Modal Ubah Status ke Gagal -->
<div id="modalUbahStatusGagal" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="modal-container">
        <div class="modal-content">
            <div class="bg-white rounded-lg sm:rounded-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="p-4 sm:p-6 bg-gradient-to-r from-red-600 to-red-700 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold">Ubah Status Potensi ke Gagal</h3>
                            <p class="text-red-100 text-sm mt-1">Tandai potensi sebagai gagal dengan memberikan catatan</p>
                        </div>
                        <button onclick="closeModal('modalUbahStatusGagal')" class="text-white hover:text-red-200 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-4 sm:p-6">
                    <form id="formUbahStatusGagal" class="space-y-6">
                        @csrf

                        <!-- Current Project Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Informasi Potensi</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-500">Kode Potensi:</span>
                                    <span id="gagalKodePotensi" class="font-medium text-gray-800 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Nama Instansi:</span>
                                    <span id="gagalNamaInstansi" class="font-medium text-gray-800 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Status Saat Ini:</span>
                                    <span id="gagalStatusSaatIni" class="font-medium ml-2">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Alert -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-500 text-lg mt-0.5"></i>
                                </div>
                                <div>
                                    <h4 class="text-red-800 font-semibold mb-1">Perhatian!</h4>
                                    <p class="text-red-700 text-sm">
                                        Mengubah status potensi menjadi "Gagal" akan menandai potensi ini sebagai tidak berhasil. 
                                        Pastikan Anda telah mempertimbangkan keputusan ini dengan matang.
                                    </p>
                                </div>
                            </div>
                        </div>

                     

                        <!-- Catatan -->
                        <div>
                            <label for="catatanGagal" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Kegagalan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="catatanGagal" name="catatan" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                    placeholder="Jelaskan alasan mengapa potensi ini dianggap gagal. Minimal 5 karakter."
                                    minlength="5"
                                    maxlength="1000"
                                    required></textarea>
                            <div class="mt-1 text-xs text-gray-500">
                                <span id="charCount">0</span>/1000 karakter
                            </div>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" id="potensiIdGagal" name="potensi_id" value="">
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 sm:p-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="closeModal('modalUbahStatusGagal')"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="submitStatusGagal()"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            id="btnSubmitGagal" disabled>
                        <i class="fas fa-times-circle mr-2"></i>Tandai sebagai Gagal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPotensiIdGagal = '';

function ubahStatusGagal(potensiId) {
    currentPotensiIdGagal = potensiId;

    // Find potensi data
    const potensi = potensiData.find(p => p.id == potensiId);
    if (!potensi) {
        alert('Data potensi tidak ditemukan!');
        return;
    }

    // Populate potensi info
    document.getElementById('gagalKodePotensi').textContent = potensi.kode;
    document.getElementById('gagalNamaInstansi').textContent = potensi.instansi;

    // Set current status with proper styling
    const statusElement = document.getElementById('gagalStatusSaatIni');
    statusElement.textContent = ucfirst(potensi.status);
    statusElement.className = `font-medium ml-2 px-2 py-1 rounded-full text-xs ${getStatusColor(potensi.status)}`;

    // Reset form
    resetGagalForm();

    // Set hidden input
    document.getElementById('potensiIdGagal').value = potensiId;

    // Show modal
    openModal('modalUbahStatusGagal');
}

function resetGagalForm() {
    document.getElementById('catatanGagal').value = '';
    document.getElementById('charCount').textContent = '0';
    document.getElementById('btnSubmitGagal').disabled = true;
}

// Character counter dan validation
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('catatanGagal');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('btnSubmitGagal');

    if (textarea) {
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            // Enable/disable submit button based on minimum character requirement
            if (length >= 5 && length <= 1000) {
                submitBtn.disabled = false;
                this.classList.remove('border-red-300');
                this.classList.add('border-gray-300');
            } else {
                submitBtn.disabled = true;
                if (length > 0 && length < 5) {
                    this.classList.add('border-red-300');
                    this.classList.remove('border-gray-300');
                }
            }
        });
    }
});

function submitStatusGagal() {
    const catatan = document.getElementById('catatanGagal').value.trim();
    
    if (!catatan || catatan.length < 5) {
        alert('Catatan kegagalan harus diisi minimal 5 karakter!');
        return;
    }

    if (catatan.length > 1000) {
        alert('Catatan kegagalan tidak boleh lebih dari 1000 karakter!');
        return;
    }

    // Disable submit button during request
    const submitBtn = document.getElementById('btnSubmitGagal');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

    // Prepare form data
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('_method', 'PUT');
    formData.append('catatan', catatan);

    // Send request
    fetch(`/marketing/potensi/${currentPotensiIdGagal}/status-gagal`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the potensi status in the UI
            const potensi = potensiData.find(p => p.id == currentPotensiIdGagal);
            if (potensi) {
                potensi.status = 'gagal';
            }

            // Close modal
            closeModal('modalUbahStatusGagal');

            // Show success message
            showSuccessModal('Status potensi berhasil diubah menjadi Gagal!');

            // Refresh the page to show updated data
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            alert(data.message || 'Terjadi kesalahan saat mengubah status potensi!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah status potensi!');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-times-circle mr-2"></i>Tandai sebagai Gagal';
    });
}
</script>
