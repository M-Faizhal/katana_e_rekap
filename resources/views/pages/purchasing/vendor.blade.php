@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-2xl p-8 mb-8 text-white shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Manajemen Vendor</h1>
            <p class="text-red-100 text-lg">Kelola dan pantau semua vendor</p>
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-handshake text-6xl "></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-red-100 mr-4">
                <i class="fas fa-handshake text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Total Vendor</h3>
                <p class="text-2xl font-bold text-red-600">28</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-green-100 mr-4">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Vendor Aktif</h3>
                <p class="text-2xl font-bold text-green-600">22</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-yellow-100 mr-4">
                <i class="fas fa-pause-circle text-yellow-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Tidak Aktif</h3>
                <p class="text-2xl font-bold text-yellow-600">6</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-blue-100 mr-4">
                <i class="fas fa-building text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Perusahaan</h3>
                <p class="text-2xl font-bold text-blue-600">18</p>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Table -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-800">Daftar Vendor</h2>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" id="searchVendor" placeholder="Cari vendor..." 
                           class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 w-full sm:w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <select id="filterJenis" class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Jenis</option>
                    <option value="Perusahaan">Perusahaan</option>
                    <option value="Perorangan">Perorangan</option>
                    <option value="Koperasi">Koperasi</option>
                </select>
                <select id="filterStatus" class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Vendor</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No HP</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="vendorTableBody">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">VND001</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-building text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">PT. Teknologi Maju</p>
                                <p class="text-sm text-gray-500">Jakarta Selatan</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Perusahaan</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">info@tekmaju.co.id</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">021-5555-1234</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="detailVendor('VND001')" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editVendor('VND001')" class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="hapusVendor('VND001')" class="text-red-600 hover:text-red-800 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">VND002</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">CV. Mandiri Sejahtera</p>
                                <p class="text-sm text-gray-500">Bandung</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Perorangan</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">mandiri@gmail.com</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">022-8888-5678</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="detailVendor('VND002')" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editVendor('VND002')" class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="hapusVendor('VND002')" class="text-red-600 hover:text-red-800 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">VND003</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Koperasi Sukses Bersama</p>
                                <p class="text-sm text-gray-500">Surabaya</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Koperasi</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">koperasi@sukses.com</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">031-7777-9999</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Tidak Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="detailVendor('VND003')" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editVendor('VND003')" class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="hapusVendor('VND003')" class="text-red-600 hover:text-red-800 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">VND004</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-industry text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">PT. Global Industri</p>
                                <p class="text-sm text-gray-500">Medan</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Perusahaan</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">global@industri.co.id</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">061-4444-7777</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="detailVendor('VND004')" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editVendor('VND004')" class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="hapusVendor('VND004')" class="text-red-600 hover:text-red-800 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">VND005</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-user-tie text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Budi Santoso</p>
                                <p class="text-sm text-gray-500">Yogyakarta</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Perorangan</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">budi.santoso@gmail.com</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">0274-5555-8888</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="detailVendor('VND005')" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editVendor('VND005')" class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="hapusVendor('VND005')" class="text-red-600 hover:text-red-800 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Menampilkan 1-5 dari 28 vendor
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>
                    Previous
                </button>
                <button class="px-3 py-1 text-sm bg-red-600 text-white rounded-lg">1</button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<button onclick="tambahVendor()" class="fixed bottom-6 right-6 bg-red-600 hover:bg-red-700 text-white w-14 h-14 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 z-50">
    <i class="fas fa-plus text-xl"></i>
</button>

@include('pages.purchasing.vendor-components.tambah')
@include('pages.purchasing.vendor-components.edit')
@include('pages.purchasing.vendor-components.detail')
@include('pages.purchasing.vendor-components.hapus')
@include('components.success-modal')

<script>
// Sample vendor data
const vendorData = [
    {
        id: 'VND001',
        nama: 'PT. Teknologi Maju',
        jenis: 'Perusahaan',
        email: 'info@tekmaju.co.id',
        noHp: '021-5555-1234',
        alamat: 'Jl. Sudirman No. 123, Jakarta Selatan',
        status: 'Aktif'
    },
    {
        id: 'VND002',
        nama: 'CV. Mandiri Sejahtera',
        jenis: 'Perorangan',
        email: 'mandiri@gmail.com',
        noHp: '022-8888-5678',
        alamat: 'Jl. Asia Afrika No. 45, Bandung',
        status: 'Aktif'
    },
    {
        id: 'VND003',
        nama: 'Koperasi Sukses Bersama',
        jenis: 'Koperasi',
        email: 'koperasi@sukses.com',
        noHp: '031-7777-9999',
        alamat: 'Jl. Pemuda No. 78, Surabaya',
        status: 'Tidak Aktif'
    },
    {
        id: 'VND004',
        nama: 'PT. Global Industri',
        jenis: 'Perusahaan',
        email: 'global@industri.co.id',
        noHp: '061-4444-7777',
        alamat: 'Jl. Gatot Subroto No. 90, Medan',
        status: 'Aktif'
    },
    {
        id: 'VND005',
        nama: 'Budi Santoso',
        jenis: 'Perorangan',
        email: 'budi.santoso@gmail.com',
        noHp: '0274-5555-8888',
        alamat: 'Jl. Malioboro No. 56, Yogyakarta',
        status: 'Aktif'
    }
];

// Modal functions
function tambahVendor() {
    showModal('modalTambahVendor');
}

function editVendor(id) {
    const vendor = vendorData.find(v => v.id === id);
    if (vendor) {
        document.getElementById('editVendorId').value = vendor.id;
        document.getElementById('editNamaVendor').value = vendor.nama;
        document.getElementById('editJenisVendor').value = vendor.jenis;
        document.getElementById('editEmailVendor').value = vendor.email;
        document.getElementById('editNoHpVendor').value = vendor.noHp;
        document.getElementById('editAlamatVendor').value = vendor.alamat;
        document.getElementById('editStatusVendor').value = vendor.status;
        showModal('modalEditVendor');
    }
}

function detailVendor(id) {
    const vendor = vendorData.find(v => v.id === id);
    if (vendor) {
        document.getElementById('detailVendorId').textContent = vendor.id;
        document.getElementById('detailNamaVendor').textContent = vendor.nama;
        document.getElementById('detailJenisVendor').textContent = vendor.jenis;
        document.getElementById('detailEmailVendor').textContent = vendor.email;
        document.getElementById('detailNoHpVendor').textContent = vendor.noHp;
        document.getElementById('detailAlamatVendor').textContent = vendor.alamat;
        document.getElementById('detailStatusVendor').textContent = vendor.status;
        showModal('modalDetailVendor');
    }
}

function hapusVendor(id) {
    const vendor = vendorData.find(v => v.id === id);
    if (vendor) {
        document.getElementById('hapusVendorNama').textContent = vendor.nama;
        document.getElementById('hapusVendorId').value = id;
        showModal('modalHapusVendor');
    }
}

// Modal utility functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Legacy close functions (for backward compatibility)
function closeTambahVendor() {
    closeModal('modalTambahVendor');
}

function closeEditVendor() {
    closeModal('modalEditVendor');
}

function closeDetailVendor() {
    closeModal('modalDetailVendor');
}

function closeHapusVendor() {
    closeModal('modalHapusVendor');
}

// Form submit functions
function submitTambahVendor() {
    // Simulate API call
    setTimeout(() => {
        closeModal('modalTambahVendor');
        showSuccessModal('Vendor berhasil ditambahkan!');
        // Reset form
        const form = document.getElementById('formTambahVendor');
        if (form) form.reset();
    }, 500);
}

function submitEditVendor() {
    // Simulate API call
    setTimeout(() => {
        closeModal('modalEditVendor');
        showSuccessModal('Vendor berhasil diperbarui!');
    }, 500);
}

function confirmHapusVendor() {
    // Simulate API call
    setTimeout(() => {
        closeModal('modalHapusVendor');
        showSuccessModal('Vendor berhasil dihapus!');
    }, 500);
}

// Success modal function
function showSuccessModal(message) {
    document.getElementById('successMessage').textContent = message;
    document.getElementById('successModal').classList.remove('hidden');
    
    setTimeout(() => {
        document.getElementById('successModal').classList.add('hidden');
    }, 3000);
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
}

// Search and filter functions
document.getElementById('searchVendor').addEventListener('input', function() {
    // Implement search functionality
    console.log('Searching:', this.value);
});

document.getElementById('filterJenis').addEventListener('change', function() {
    // Implement filter functionality
    console.log('Filter by jenis:', this.value);
});

document.getElementById('filterStatus').addEventListener('change', function() {
    // Implement filter functionality
    console.log('Filter by status:', this.value);
});
</script>
@endsection
