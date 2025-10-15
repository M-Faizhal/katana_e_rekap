<!-- Modal Edit Pengiriman -->
<div id="modalEditPengiriman" class="fixed inset-0  backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-800 to-red-700 text-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">Edit Pengiriman</h3>
                        <p class="text-red-100 text-sm">Ubah informasi pengiriman</p>
                    </div>
                </div>
                <button onclick="tutupModal('modalEditPengiriman')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                <form id="formEditPengiriman" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" id="editPengirimanId" name="pengiriman_id">

                    <!-- Info Pengiriman Saat Ini -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200 mb-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle text-white text-sm"></i>
                            </div>
                            Informasi Pengiriman Saat Ini
                        </h4>
                        <div id="currentPengirimanInfo" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <!-- Info akan diisi via JavaScript -->
                        </div>
                    </div>

                    <!-- Form Edit Data -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200 mb-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-shipping-fast text-white text-sm"></i>
                            </div>
                            Data Pengiriman
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    No. Surat Jalan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="no_surat_jalan" id="editNoSuratJalan" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                                       placeholder="Masukkan nomor surat jalan" required>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Nomor surat jalan harus unik
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Kirim <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_kirim" id="editTanggalKirim"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                                       required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Saat Ini
                                </label>
                                <div id="currentStatus" class="px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-gray-700">
                                    <!-- Status akan diisi via JavaScript -->
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Alamat Pengiriman <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alamat_kirim" id="editAlamatKirim" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                                          placeholder="Masukkan alamat lengkap pengiriman" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- File Surat Jalan -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200 mb-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-upload text-white text-sm"></i>
                            </div>
                            File Surat Jalan
                        </h4>
                        
                        <!-- Current File Info -->
                        <div id="currentFileInfo" class="mb-4">
                            <!-- Current file info akan diisi via JavaScript -->
                        </div>
                        
                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload File Baru (Opsional)
                            </label>
                            <input type="file" name="file_surat_jalan" id="editFileSuratJalan"
                                   accept=".pdf,.jpg,.jpeg,.png" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                            <div class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, JPG, PNG • Maksimal: 5MB • Kosongkan jika tidak ingin mengubah file
                            </div>
                            
                            <!-- File Preview -->
                            <div id="newFilePreview" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-blue-700">
                                        <i class="fas fa-file mr-2"></i>
                                        <span id="newFileName">File baru dipilih</span>
                                    </div>
                                    <button type="button" onclick="clearNewFile()" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Perubahan akan disimpan secara permanen
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="tutupModal('modalEditPengiriman')" 
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="button" onclick="submitEditPengiriman()" 
                            class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save mr-2"></i>Update Pengiriman
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay untuk Edit -->
<div id="editLoadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-60 hidden">        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600"></div>
            <span class="text-gray-700">Memuat data pengiriman...</span>
        </div>
    </div>

<script>
// Global variables untuk edit pengiriman
let currentEditPengiriman = null;

// Simple toast notification function
function showToast(message, type = 'info') {
    // Create toast element if it doesn't exist
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check' : type === 'error' ? 'fa-times' : 'fa-info'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 5000);
}

// Function untuk membuka modal edit pengiriman
function editPengiriman(pengirimanId) {
    // Check access control
    if (!['admin_purchasing', 'superadmin'].includes(window.currentUserRole)) {
        alert('Tidak memiliki akses untuk mengedit pengiriman. Hanya admin purchasing atau superadmin yang dapat melakukan aksi ini.');
        return;
    }

    // Show loading
    document.getElementById('editLoadingOverlay').classList.remove('hidden');
    
    // Fetch pengiriman data
    fetch(`/purchasing/pengiriman/${pengirimanId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentEditPengiriman = data.data;
            populateEditForm(data.data);
            document.getElementById('editLoadingOverlay').classList.add('hidden');
            document.getElementById('modalEditPengiriman').classList.remove('hidden');
        } else {
            document.getElementById('editLoadingOverlay').classList.add('hidden');
            showToast(data.message || 'Gagal memuat data pengiriman', 'error');
        }
    })
    .catch(error => {
        document.getElementById('editLoadingOverlay').classList.add('hidden');
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memuat data pengiriman', 'error');
    });
}

// Function untuk mengisi form edit
function populateEditForm(pengiriman) {
    // Set pengiriman ID
    document.getElementById('editPengirimanId').value = pengiriman.id_pengiriman;
    
    // Fill current info
    const barangList = pengiriman.barang_list || [];
    let barangHtml = '';
    
    if (barangList.length > 0) {
        if (barangList.length <= 3) {
            barangHtml = `<div><span class="text-gray-500">Barang:</span> <span class="font-medium text-blue-600">
                <i class="fas fa-boxes mr-1"></i>${barangList.join(', ')}
            </span></div>`;
        } else {
            barangHtml = `<div><span class="text-gray-500">Barang:</span> 
                <span class="font-medium text-blue-600">
                    <i class="fas fa-boxes mr-1"></i>${barangList[0]} & ${barangList.length - 1} lainnya
                </span>
                <div class="text-xs text-green-600 mt-1">
                    <i class="fas fa-layer-group mr-1"></i>${barangList.length} jenis barang
                </div>
            </div>`;
        }
    } else {
        barangHtml = `<div><span class="text-gray-500">Barang:</span> <span class="text-gray-400">Data tidak tersedia</span></div>`;
    }
    
    document.getElementById('currentPengirimanInfo').innerHTML = `
        <div><span class="text-gray-500">Proyek:</span> <span class="font-medium">${pengiriman.penawaran.proyek.kode_proyek}</span></div>
        <div><span class="text-gray-500">Instansi:</span> <span class="font-medium">${pengiriman.penawaran.proyek.instansi}</span></div>
        <div><span class="text-gray-500">Vendor:</span> <span class="font-medium">${pengiriman.vendor.nama_vendor}</span></div>
        <div><span class="text-gray-500">Jenis Vendor:</span> <span class="font-medium">${pengiriman.vendor.jenis_perusahaan}</span></div>
        ${barangHtml}
        <div><span class="text-gray-500">Dibuat:</span> <span class="font-medium">${new Date(pengiriman.created_at).toLocaleDateString('id-ID')}</span></div>
    `;
    
    // Fill form fields
    document.getElementById('editNoSuratJalan').value = pengiriman.no_surat_jalan || '';
    document.getElementById('editTanggalKirim').value = pengiriman.tanggal_kirim || '';
    document.getElementById('editAlamatKirim').value = pengiriman.alamat_kirim || '';
    
    // Status
    const statusBadge = getStatusBadge(pengiriman.status_verifikasi);
    document.getElementById('currentStatus').innerHTML = statusBadge;
    
    // Current file info
    displayCurrentFileInfo(pengiriman.file_surat_jalan);
}

// Function untuk menampilkan info file saat ini
function displayCurrentFileInfo(currentFile) {
    const container = document.getElementById('currentFileInfo');
    
    if (currentFile && currentFile.trim() !== '') {
        container.innerHTML = `
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center text-sm font-medium text-blue-800 mb-1">
                            <i class="fas fa-file mr-2"></i>
                            File Surat Jalan Saat Ini
                        </div>
                        <div class="text-xs text-blue-600">${currentFile}</div>
                    </div>
                    <div class="flex space-x-2">
                        <button type="button" onclick="viewFile('${currentFile}')" 
                                class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-xs transition-colors">
                            <i class="fas fa-eye mr-1"></i>Lihat
                        </button>
                        <button type="button" onclick="downloadFile('${currentFile}')" 
                                class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-xs transition-colors">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                    </div>
                </div>
            </div>
        `;
    } else {
        container.innerHTML = `
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-times-circle mr-2"></i>
                    Belum ada file surat jalan
                </div>
            </div>
        `;
    }
}

// Function untuk mendapatkan badge status
function getStatusBadge(status) {
    const statusConfig = {
        'Pending': { class: 'bg-yellow-100 text-yellow-800', icon: 'fas fa-clock' },
        'Dalam_Proses': { class: 'bg-blue-100 text-blue-800', icon: 'fas fa-truck' },
        'Sampai_Tujuan': { class: 'bg-green-100 text-green-800', icon: 'fas fa-map-marker-alt' },
        'Selesai': { class: 'bg-green-100 text-green-800', icon: 'fas fa-check-circle' },
        'Verified': { class: 'bg-purple-100 text-purple-800', icon: 'fas fa-certificate' }
    };
    
    const config = statusConfig[status] || { class: 'bg-gray-100 text-gray-800', icon: 'fas fa-question-circle' };
    const displayStatus = status.replace('_', ' ');
    
    return `
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium ${config.class}">
            <i class="${config.icon} mr-1"></i>
            ${displayStatus}
        </span>
    `;
}

// Function untuk submit edit pengiriman
function submitEditPengiriman() {
    const pengirimanId = document.getElementById('editPengirimanId').value;
    
    if (!pengirimanId) {
        showToast('ID pengiriman tidak valid', 'error');
        return;
    }
    
    // Validate form
    const noSuratJalan = document.getElementById('editNoSuratJalan').value.trim();
    const tanggalKirim = document.getElementById('editTanggalKirim').value;
    const alamatKirim = document.getElementById('editAlamatKirim').value.trim();
    
    if (!noSuratJalan || !tanggalKirim || !alamatKirim) {
        showToast('Semua field wajib harus diisi', 'error');
        return;
    }
    
    // Create FormData
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('no_surat_jalan', noSuratJalan);
    formData.append('tanggal_kirim', tanggalKirim);
    formData.append('alamat_kirim', alamatKirim);
    
    // Add file if selected
    const fileInput = document.getElementById('editFileSuratJalan');
    if (fileInput.files[0]) {
        formData.append('file_surat_jalan', fileInput.files[0]);
    }
    
    // Disable button and show loading
    const submitButton = document.querySelector('button[onclick="submitEditPengiriman()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    
    fetch(`/purchasing/pengiriman/${pengirimanId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Pengiriman berhasil diperbarui', 'success');
            tutupModal('modalEditPengiriman');
            // Reload page to show updated data
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast(data.message || 'Gagal memperbarui pengiriman', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memperbarui pengiriman', 'error');
    })
    .finally(() => {
        // Re-enable button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Function untuk clear new file selection
function clearNewFile() {
    document.getElementById('editFileSuratJalan').value = '';
    document.getElementById('newFilePreview').classList.add('hidden');
}

// Event listeners untuk edit pengiriman
document.addEventListener('DOMContentLoaded', function() {
    // File input change handler
    const fileInput = document.getElementById('editFileSuratJalan');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('newFilePreview');
            const fileName = document.getElementById('newFileName');
            
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showToast('Ukuran file terlalu besar. Maksimal 5MB.', 'error');
                    e.target.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                
                // Show preview
                fileName.textContent = file.name;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });
    }
    
    // Form validation on input change
    const requiredFields = ['editNoSuratJalan', 'editTanggalKirim', 'editAlamatKirim'];
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                // Remove error styling if field is filled
                if (this.value.trim()) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-300');
                } else {
                    this.classList.remove('border-gray-300');
                    this.classList.add('border-red-500');
                }
            });
        }
    });
});

// Function untuk view file (dapat digunakan dari parent juga)
if (typeof viewFile !== 'function') {
    function viewFile(filePath) {
        if (filePath) {
            const fileUrl = `/storage/pengiriman/surat_jalan/${filePath}`;
            window.open(fileUrl, '_blank');
        }
    }
}

// Function untuk download file
function downloadFile(filePath) {
    if (filePath) {
        const fileUrl = `/storage/pengiriman/surat_jalan/${filePath}`;
        const link = document.createElement('a');
        link.href = fileUrl;
        link.download = filePath;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}
</script>
