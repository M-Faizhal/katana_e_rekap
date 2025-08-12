<!-- Modal Tambah Potensi -->
<div id="modalTambahPotensi" class="fixed inset-0 backdrop-blur-xs bg-black/30 modal-backdrop hidden items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-lg md:max-w-2xl lg:max-w-4xl max-h-screen overflow-hidden my-2 sm:my-4 mx-auto">
        <!-- Modal Header -->
        <div class="bg-red-800 text-white p-4 sm:p-6 flex items-center justify-between flex-shrink-0 modal-header">
            <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                </div>
                <div>
                    <h3 class="text-base sm:text-xl font-bold">Tambah Potensi Proyek</h3>
                    <p class="text-red-100 text-xs sm:text-sm">Assign proyek ke vendor yang sesuai</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahPotensi')" class="text-white hover:bg-white hover:text-red-800 p-1 sm:p-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-times text-lg sm:text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-3 sm:p-6 overflow-y-auto flex-1 modal-form" style="max-height: calc(100vh - 160px);">
            <form id="formTambahPotensi">
                <!-- Pilih Proyek -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-3 sm:p-6 mb-4 sm:mb-6">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-project-diagram text-red-600 mr-2 text-sm sm:text-base"></i>
                        Pilih Proyek Potensi
                    </h4>
                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Proyek yang Berpotensi <span class="text-red-500">*</span></label>
                            <select id="tambahProyekId" name="proyek_id" class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm sm:text-base" required onchange="loadProyekDetails()">
                                <option value="">Pilih Proyek...</option>
                                <option value="PNW-20240810-143052">PNW-20240810-143052 - Sistem Informasi Sekolah</option>
                                <option value="PNW-20240715-120034">PNW-20240715-120034 - Aplikasi E-Learning</option>
                                <option value="PNW-20240620-095021">PNW-20240620-095021 - Portal Website Pemerintah</option>
                                <option value="PNW-20240525-103045">PNW-20240525-103045 - Aplikasi Mobile Pelayanan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Detail Proyek (Auto-filled) -->
                <div id="detailProyekSection" class="bg-blue-50 rounded-lg sm:rounded-xl p-3 sm:p-6 mb-4 sm:mb-6 hidden">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2 text-sm sm:text-base"></i>
                        Detail Proyek Terpilih
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Proyek</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="detailNamaProyek">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Instansi</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="detailInstansi">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="detailKabupatenKota">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengadaan</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="detailJenisPengadaan">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Proyek</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="detailNilaiProyek">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="detailDeadline">-</span>
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
                            <select id="tambahVendorId" name="vendor_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required onchange="loadVendorDetails()">
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
                <div id="detailVendorSection" class="bg-green-50 rounded-xl p-6 mb-6 hidden">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-green-600 mr-2"></i>
                        Detail Vendor Terpilih
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Vendor</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="detailNamaVendor">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Vendor</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="detailJenisVendor">-</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Vendor</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-800">
                                <span id="detailStatusVendor">-</span>
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
                            <select id="tambahStatus" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                                <option value="">Pilih Status...</option>
                                <option value="pending">Pending</option>
                                <option value="sukses">Sukses</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Assign</label>
                            <input type="date" id="tambahTanggalAssign" name="tanggal_assign" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea id="tambahCatatan" name="catatan" rows="4" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                    placeholder="Masukkan catatan atau keterangan tambahan..."></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-3 py-3 sm:px-6 sm:py-4 flex flex-col sm:flex-row items-center justify-end space-y-2 sm:space-y-0 sm:space-x-3 border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeModal('modalTambahPotensi')" class="w-full sm:w-auto px-4 py-2 sm:px-6 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm sm:text-base min-h-[44px] sm:min-h-[40px]">
                <i class="fas fa-times mr-1 sm:mr-2"></i>Batal
            </button>
            <button type="button" onclick="submitTambahPotensi()" class="w-full sm:w-auto px-4 py-2 sm:px-6 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm sm:text-base min-h-[44px] sm:min-h-[40px]">
                <i class="fas fa-save mr-1 sm:mr-2"></i>Simpan Potensi
            </button>
        </div>
    </div>
</div>

<script>
// Data proyek dengan potensi
const proyekPotensiOptions = {
    'PNW-20240810-143052': {
        nama: 'Sistem Informasi Sekolah',
        instansi: 'Dinas Pendidikan DKI',
        kabupaten_kota: 'Jakarta Pusat',
        jenis_pengadaan: 'Pelelangan Umum',
        nilai_proyek: 850000000,
        deadline: '30 September 2024'
    },
    'PNW-20240715-120034': {
        nama: 'Aplikasi E-Learning',
        instansi: 'Universitas Negeri',
        kabupaten_kota: 'Bandung',
        jenis_pengadaan: 'Penunjukan Langsung',
        nilai_proyek: 650000000,
        deadline: '15 Oktober 2024'
    },
    'PNW-20240620-095021': {
        nama: 'Portal Website Pemerintah',
        instansi: 'Pemkot Surabaya',
        kabupaten_kota: 'Surabaya',
        jenis_pengadaan: 'Pelelangan Umum',
        nilai_proyek: 1200000000,
        deadline: '20 November 2024'
    },
    'PNW-20240525-103045': {
        nama: 'Aplikasi Mobile Pelayanan',
        instansi: 'Dinas Kominfo Medan',
        kabupaten_kota: 'Medan',
        jenis_pengadaan: 'Tender Terbatas',
        nilai_proyek: 750000000,
        deadline: '10 Desember 2024'
    }
};

// Data vendor
const vendorOptions = {
    'VND001': {
        nama: 'PT. Teknologi Maju',
        jenis: 'Perusahaan',
        status: 'Aktif'
    },
    'VND002': {
        nama: 'CV. Mandiri Sejahtera',
        jenis: 'Perorangan',
        status: 'Aktif'
    },
    'VND003': {
        nama: 'Koperasi Sukses Bersama',
        jenis: 'Koperasi',
        status: 'Tidak Aktif'
    },
    'VND004': {
        nama: 'PT. Global Industri',
        jenis: 'Perusahaan',
        status: 'Aktif'
    },
    'VND005': {
        nama: 'Budi Santoso',
        jenis: 'Perorangan',
        status: 'Aktif'
    }
};

// Load project details when project is selected
function loadProyekDetails() {
    const proyekId = document.getElementById('tambahProyekId').value;
    const detailSection = document.getElementById('detailProyekSection');
    
    if (proyekId && proyekPotensiOptions[proyekId]) {
        const proyek = proyekPotensiOptions[proyekId];
        
        document.getElementById('detailNamaProyek').textContent = proyek.nama;
        document.getElementById('detailInstansi').textContent = proyek.instansi;
        document.getElementById('detailKabupatenKota').textContent = proyek.kabupaten_kota;
        document.getElementById('detailJenisPengadaan').textContent = proyek.jenis_pengadaan;
        document.getElementById('detailNilaiProyek').textContent = formatRupiah(proyek.nilai_proyek);
        document.getElementById('detailDeadline').textContent = proyek.deadline;
        
        detailSection.classList.remove('hidden');
    } else {
        detailSection.classList.add('hidden');
    }
}

// Load vendor details when vendor is selected
function loadVendorDetails() {
    const vendorId = document.getElementById('tambahVendorId').value;
    const detailSection = document.getElementById('detailVendorSection');
    
    if (vendorId && vendorOptions[vendorId]) {
        const vendor = vendorOptions[vendorId];
        
        document.getElementById('detailNamaVendor').textContent = vendor.nama;
        document.getElementById('detailJenisVendor').textContent = vendor.jenis;
        document.getElementById('detailStatusVendor').textContent = vendor.status;
        
        // Add status badge styling
        const statusElement = document.getElementById('detailStatusVendor');
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

// Submit function
function submitTambahPotensi() {
    const form = document.getElementById('formTambahPotensi');
    const formData = new FormData(form);
    
    // Validate required fields
    const proyekId = formData.get('proyek_id');
    const vendorId = formData.get('vendor_id');
    const status = formData.get('status');
    
    if (!proyekId) {
        alert('Silakan pilih proyek terlebih dahulu');
        return;
    }
    
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
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitButton.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Reset form
        form.reset();
        document.getElementById('detailProyekSection').classList.add('hidden');
        document.getElementById('detailVendorSection').classList.add('hidden');
        
        // Close modal
        closeModal('modalTambahPotensi');
        
        // Show success message
        showSuccessModal('Potensi proyek berhasil ditambahkan!');
        
        // Reset button
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        
        // Refresh page or update data
        // window.location.reload();
    }, 1500);
}

// Format rupiah function
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}
</script>
