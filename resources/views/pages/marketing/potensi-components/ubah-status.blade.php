<!-- Modal Ubah Status -->
<div id="modalUbahStatus" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-backdrop">
    <div class="modal-container">
        <div class="modal-content">
            <div class="bg-white rounded-lg sm:rounded-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="p-4 sm:p-6 bg-gradient-to-r from-red-600 to-red-700 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold">Ubah Status Proyek</h3>
                            <p class="text-red-100 text-sm mt-1">Ubah status proyek sesuai perkembangan terkini</p>
                        </div>
                        <button onclick="closeModal('modalUbahStatus')" class="text-white hover:text-red-200 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-4 sm:p-6">
                    <form id="formUbahStatus" class="space-y-6">
                        @csrf

                        <!-- Current Project Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Informasi Proyek</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-500">Kode Proyek:</span>
                                    <span id="statusKodeProyek" class="font-medium text-gray-800 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Nama Proyek:</span>
                                    <span id="statusNamaProyek" class="font-medium text-gray-800 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Status Saat Ini:</span>
                                    <span id="statusSaatIni" class="font-medium ml-2">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Selection -->
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Pilih Status Baru</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <button type="button" onclick="selectStatus('menunggu')"
                                        class="status-option p-3 border-2 border-gray-200 rounded-lg hover:border-gray-500 transition-colors text-center"
                                        data-status="menunggu">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-clock text-gray-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Menunggu</span>
                                </button>

                                <button type="button" onclick="selectStatus('penawaran')"
                                        class="status-option p-3 border-2 border-gray-200 rounded-lg hover:border-blue-500 transition-colors text-center"
                                        data-status="penawaran">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Penawaran</span>
                                </button>

                                <button type="button" onclick="selectStatus('pembayaran')"
                                        class="status-option p-3 border-2 border-gray-200 rounded-lg hover:border-purple-500 transition-colors text-center"
                                        data-status="pembayaran">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-credit-card text-purple-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Pembayaran</span>
                                </button>

                                <button type="button" onclick="selectStatus('pengiriman')"
                                        class="status-option p-3 border-2 border-gray-200 rounded-lg hover:border-orange-500 transition-colors text-center"
                                        data-status="pengiriman">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-shipping-fast text-orange-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Pengiriman</span>
                                </button>

                                <button type="button" onclick="selectStatus('selesai')"
                                        class="status-option p-3 border-2 border-gray-200 rounded-lg hover:border-green-500 transition-colors text-center"
                                        data-status="selesai">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Selesai</span>
                                </button>

                                <button type="button" onclick="selectStatus('gagal')"
                                        class="status-option p-3 border-2 border-gray-200 rounded-lg hover:border-red-500 transition-colors text-center"
                                        data-status="gagal">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-times-circle text-red-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Gagal</span>
                                </button>
                            </div>
                        </div>                        <!-- Hidden input for selected status -->
                        <input type="hidden" id="selectedStatus" name="status" value="">
                        <input type="hidden" id="proyekId" name="proyek_id" value="">

                        <!-- Keterangan -->
                        <div>
                            <label for="keteranganStatus" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan Perubahan Status (Opsional)
                            </label>
                            <textarea id="keteranganStatus" name="keterangan" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                    placeholder="Masukkan keterangan atau alasan perubahan status..."></textarea>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 sm:p-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="closeModal('modalUbahStatus')"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="submitStatusChange()"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            id="btnSubmitStatus" disabled>
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedStatusValue = '';
let currentProyekId = '';

function openStatusModal(proyekId) {
    currentProyekId = proyekId;

    // Find project data
    const proyek = potensiData.find(p => p.id == proyekId);
    if (!proyek) {
        alert('Data proyek tidak ditemukan!');
        return;
    }

    // Populate project info
    document.getElementById('statusKodeProyek').textContent = proyek.kode_proyek;
    document.getElementById('statusNamaProyek').textContent = proyek.nama_proyek;

    // Set current status with proper styling
    const statusElement = document.getElementById('statusSaatIni');
    statusElement.textContent = ucfirst(proyek.status);
    statusElement.className = `font-medium ml-2 px-2 py-1 rounded-full text-xs ${getStatusColor(proyek.status)}`;

    // Reset form
    resetStatusForm();

    // Set hidden input
    document.getElementById('proyekId').value = proyekId;

    // Show modal
    openModal('modalUbahStatus');
}

function selectStatus(status) {
    selectedStatusValue = status;

    // Reset all status options
    document.querySelectorAll('.status-option').forEach(option => {
        option.classList.remove('border-blue-500', 'border-purple-500', 'border-orange-500', 'border-green-500', 'border-yellow-500', 'border-red-500', 'bg-blue-50', 'bg-purple-50', 'bg-orange-50', 'bg-green-50', 'bg-yellow-50', 'bg-red-50');
        option.classList.add('border-gray-200');
    });

    // Highlight selected option
    const selectedOption = document.querySelector(`[data-status="${status}"]`);
    if (selectedOption) {
        selectedOption.classList.remove('border-gray-200');
        selectedOption.classList.add(`border-${getStatusColorClass(status)}-500`, `bg-${getStatusColorClass(status)}-50`);
    }

    // Update hidden input
    document.getElementById('selectedStatus').value = status;

    // Enable submit button
    document.getElementById('btnSubmitStatus').disabled = false;
}

function getStatusColor(status) {
    switch(status) {
        case 'sukses': return 'bg-green-100 text-green-800';
        case 'selesai': return 'bg-green-100 text-green-800';
        case 'kontrak': return 'bg-orange-100 text-orange-800';
        case 'persetujuan': return 'bg-purple-100 text-purple-800';
        case 'penawaran': return 'bg-blue-100 text-blue-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'proses': return 'bg-yellow-100 text-yellow-800';
        case 'gagal': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusColorClass(status) {
    switch(status) {
        case 'sukses': return 'green';
        case 'selesai': return 'green';
        case 'kontrak': return 'orange';
        case 'persetujuan': return 'purple';
        case 'penawaran': return 'blue';
        case 'pending': return 'yellow';
        case 'proses': return 'yellow';
        case 'gagal': return 'red';
        default: return 'gray';
    }
}

function resetStatusForm() {
    selectedStatusValue = '';
    document.getElementById('selectedStatus').value = '';
    document.getElementById('keteranganStatus').value = '';
    document.getElementById('btnSubmitStatus').disabled = true;

    // Reset all status options
    document.querySelectorAll('.status-option').forEach(option => {
        option.classList.remove('border-blue-500', 'border-purple-500', 'border-orange-500', 'border-green-500', 'border-yellow-500', 'border-red-500', 'bg-blue-50', 'bg-purple-50', 'bg-orange-50', 'bg-green-50', 'bg-yellow-50', 'bg-red-50');
        option.classList.add('border-gray-200');
    });
}

function submitStatusChange() {
    if (!selectedStatusValue) {
        alert('Silakan pilih status baru terlebih dahulu!');
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('proyek_id', currentProyekId);
    formData.append('status', selectedStatusValue);
    formData.append('keterangan', document.getElementById('keteranganStatus').value);

    // Disable submit button during request
    const submitBtn = document.getElementById('btnSubmitStatus');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

    // Submit to potensi update endpoint
    fetch(`/marketing/potensi/${currentProyekId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: selectedStatusValue === 'selesai' ? 'sukses' : 'pending'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            closeModal('modalUbahStatus');

            // Show success message
            showSuccessModal('Status proyek berhasil diubah!');

            // Refresh the page to show updated data
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);

        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Perubahan';
    });
}
</script>
