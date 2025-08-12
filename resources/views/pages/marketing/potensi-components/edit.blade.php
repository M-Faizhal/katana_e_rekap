<!-- Modal Edit Potensi -->
<div id="modalEditPotensi" class="fixed inset-0 backdrop-blur-xs bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-screen overflow-hidden my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-6 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold">Edit Potensi Proyek</h3>
                    <p class="text-red-100 text-sm">Ubah assignment proyek ke vendor</p>
                </div>
            </div>
            <button onclick="closeModal('modalEditPotensi')" class="text-white hover:bg-white hover:text-red-800 p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(100vh - 200px);">
            <form id="formEditPotensi">
                <input type="hidden" id="editPotensiId" name="potensi_id">
                
                <!-- Detail Proyek (Read-only) -->
                <div class="bg-blue-50 rounded-xl p-6 mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-project-diagram text-blue-600 mr-2"></i>
                        Detail Proyek
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Proyek</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="editKodeProyek">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Proyek</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="editNamaProyek">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Instansi</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="editInstansi">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="editKabupatenKota">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="editJenisPengadaan">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Proyek</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="editNilaiProyek">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pilih Vendor -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-building text-red-600 mr-2"></i>
                        Assign ke Vendor
                    </h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Vendor <span class="text-red-500">*</span></label>
                            <select id="editVendorId" name="vendor_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required onchange="loadEditVendorDetails()">
                                <option value="">Pilih Vendor...</option>
                                <option value="VND001">VND001 - PT. Teknologi Maju</option>
                                <option value="VND002">VND002 - CV. Mandiri Sejahtera</option>
                                <option value="VND003">VND003 - Koperasi Sukses Bersama</option>
                                <option value="VND004">VND004 - PT. Global Industri</option>
                                <option value="VND005">VND005 - Budi Santoso</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Detail Vendor (Auto-filled) -->
                <div id="editDetailVendorSection" class="bg-green-50 rounded-xl p-6 mb-6 hidden">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-green-600 mr-2"></i>
                        Detail Vendor Terpilih
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Vendor</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="editDetailNamaVendor">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Vendor</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="editDetailJenisVendor">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Vendor</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="editDetailStatusVendor">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status dan Catatan -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clipboard-list text-red-600 mr-2"></i>
                        Status dan Catatan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Potensi <span class="text-red-500">*</span></label>
                            <select id="editStatus" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                                <option value="">Pilih Status...</option>
                                <option value="pending">Pending</option>
                                <option value="sukses">Sukses</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Assign</label>
                            <input type="date" id="editTanggalAssign" name="tanggal_assign" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea id="editCatatan" name="catatan" rows="4" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                    placeholder="Masukkan catatan atau keterangan tambahan..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Timeline/History -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-history text-gray-600 mr-2"></i>
                        Riwayat Perubahan
                    </h4>
                    <div id="editTimeline" class="space-y-3">
                        <!-- Timeline items will be loaded here -->
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalEditPotensi')" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="button" onclick="submitEditPotensi()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Update Potensi
            </button>
        </div>
    </div>
</div>

<script>
// Load vendor details when vendor is selected (for edit)
function loadEditVendorDetails() {
    const vendorId = document.getElementById('editVendorId').value;
    const detailSection = document.getElementById('editDetailVendorSection');
    
    if (vendorId && vendorOptions[vendorId]) {
        const vendor = vendorOptions[vendorId];
        
        document.getElementById('editDetailNamaVendor').textContent = vendor.nama;
        document.getElementById('editDetailJenisVendor').textContent = vendor.jenis;
        document.getElementById('editDetailStatusVendor').textContent = vendor.status;
        
        // Add status badge styling
        const statusElement = document.getElementById('editDetailStatusVendor');
        if (vendor.status === 'Aktif') {
            statusElement.className = 'inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800';
        } else {
            statusElement.className = 'inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800';
        }
        
        detailSection.classList.remove('hidden');
    } else {
        detailSection.classList.add('hidden');
    }
}

// Load data for editing
function loadPotensiEditData(data) {
    // Load hidden ID
    document.getElementById('editPotensiId').value = data.id;
    
    // Load project details (read-only)
    document.getElementById('editKodeProyek').textContent = data.kode_proyek;
    document.getElementById('editNamaProyek').textContent = data.nama_proyek;
    document.getElementById('editInstansi').textContent = data.instansi;
    document.getElementById('editKabupatenKota').textContent = data.kabupaten_kota;
    document.getElementById('editJenisPengadaan').textContent = data.jenis_pengadaan;
    document.getElementById('editNilaiProyek').textContent = formatRupiah(data.nilai_proyek);
    
    // Load current vendor selection
    document.getElementById('editVendorId').value = data.vendor_id;
    loadEditVendorDetails(); // Show vendor details
    
    // Load status and other fields
    document.getElementById('editStatus').value = data.status;
    document.getElementById('editTanggalAssign').value = data.tanggal_assign;
    document.getElementById('editCatatan').value = data.catatan || '';
    
    // Load timeline
    loadEditTimeline(data.timeline || []);
}

// Load timeline for edit modal
function loadEditTimeline(timeline) {
    const timelineContainer = document.getElementById('editTimeline');
    timelineContainer.innerHTML = '';
    
    if (timeline.length === 0) {
        timeline = [
            {
                tanggal: new Date().toISOString().split('T')[0],
                waktu: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                aksi: 'Potensi dibuat',
                user: 'Admin Marketing',
                status: 'pending'
            }
        ];
    }
    
    timeline.forEach((item, index) => {
        const isLast = index === timeline.length - 1;
        const timelineItem = document.createElement('div');
        timelineItem.className = 'flex items-start space-x-4';
        
        let statusColor = 'bg-gray-400';
        let statusIcon = 'fas fa-circle';
        
        if (item.status === 'sukses') {
            statusColor = 'bg-green-500';
            statusIcon = 'fas fa-check';
        } else if (item.status === 'pending') {
            statusColor = 'bg-yellow-500';
            statusIcon = 'fas fa-clock';
        }
        
        timelineItem.innerHTML = `
            <div class="flex flex-col items-center">
                <div class="w-3 h-3 ${statusColor} rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="${statusIcon} text-white text-xs"></i>
                </div>
                ${!isLast ? '<div class="w-0.5 h-8 bg-gray-300 mt-2"></div>' : ''}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-900">${item.aksi}</p>
                    <p class="text-xs text-gray-500">${item.tanggal} ${item.waktu}</p>
                </div>
                <p class="text-xs text-gray-600">${item.user}</p>
            </div>
        `;
        timelineContainer.appendChild(timelineItem);
    });
}

// Submit edit function
function submitEditPotensi() {
    const form = document.getElementById('formEditPotensi');
    const formData = new FormData(form);
    
    // Validate required fields
    const vendorId = formData.get('vendor_id');
    const status = formData.get('status');
    
    if (!vendorId) {
        alert('Silakan pilih vendor terlebih dahulu');
        return;
    }
    
    if (!status) {
        alert('Silakan pilih status terlebih dahulu');
        return;
    }
    
    // Show loading state
    const submitButton = event.target;
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';
    submitButton.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Close modal
        closeModal('modalEditPotensi');
        
        // Show success message
        showSuccessModal('Potensi proyek berhasil diperbarui!');
        
        // Reset button
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        
        // Refresh page or update data
        // window.location.reload();
    }, 1500);
}
</script>
