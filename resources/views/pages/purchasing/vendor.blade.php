@extends('layouts.app')

@section('title', 'Manajemen Vendor - Cyber KATANA')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Manajemen Vendor</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan pantau semua vendor</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-handshake text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-handshake text-red-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Vendor</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">{{ $totalVendors }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-building text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Principle</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">{{ $vendorPrinciple }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-truck text-green-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Distributor</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">{{ $vendorDistributor }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-yellow-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                <i class="fas fa-store text-yellow-600 text-sm sm:text-lg lg:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Retail</h3>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">{{ $vendorRetail }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Role Information Alert -->
@auth
    @if(auth()->user()->role !== 'admin_purchasing' && auth()->user()->role !== 'superadmin')
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-800">
                        <span class="font-medium">Info:</span> 
                        Anda hanya memiliki akses untuk melihat detail vendor. Hanya admin purchasing dan superadmin yang dapat menambah, mengedit, atau menghapus data vendor.
                    </p>
                </div>
            </div>
        </div>
    @endif
@endauth

<!-- Vendor Table -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 mb-20">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Daftar Vendor</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola semua data vendor dan informasinya</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchVendor" placeholder="Cari vendor..."
                           class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <select id="filterJenis" class="px-3 py-2.5 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                    <option value="">Semua Jenis</option>
                    <option value="Principle">Principle</option>
                    <option value="Distributor">Distributor</option>
                    <option value="Retail">Retail</option>
                    <option value="Lain-lain">Lain-lain</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">ID Vendor</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">Nama Vendor</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">No HP</th>
                    <th class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs lg:text-sm font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="vendorTableBody">
                @foreach($vendors as $vendor)
                <tr>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-900">{{ $vendor->id_vendor }}</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-building text-red-600 text-sm lg:text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm lg:text-base font-semibold text-gray-900">{{ $vendor->nama_vendor }}</p>
                                <p class="text-xs lg:text-sm text-gray-500">{{ Str::limit($vendor->alamat, 30) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">{{ $vendor->jenis_perusahaan }}</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">{{ $vendor->email }}</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-gray-600">{{ $vendor->kontak }}</td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-1 lg:space-x-2">
                            <button onclick="detailVendor({{ $vendor->id_vendor }})" class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 lg:p-2" title="Lihat Detail">
                                <i class="fas fa-eye text-sm lg:text-base"></i>
                            </button>
                            @auth
                                @if(auth()->user()->role === 'admin_purchasing' || auth()->user()->role === 'superadmin')
                                    <button onclick="editVendor({{ $vendor->id_vendor }})" class="text-yellow-600 hover:text-yellow-800 transition-colors p-1.5 lg:p-2" title="Edit Vendor">
                                        <i class="fas fa-edit text-sm lg:text-base"></i>
                                    </button>
                                    <button onclick="hapusVendor({{ $vendor->id_vendor }})" class="text-red-600 hover:text-red-800 transition-colors p-1.5 lg:p-2" title="Hapus Vendor">
                                        <i class="fas fa-trash text-sm lg:text-base"></i>
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="block md:hidden">
        <div class="p-4 space-y-4" id="vendorMobileList">
            @foreach($vendors as $vendor)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm vendor-card">
                <div class="p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-building text-red-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $vendor->nama_vendor }}</h3>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $vendor->id_vendor }} • {{ Str::limit($vendor->alamat, 20) }}</p>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700">{{ $vendor->email }}</p>
                                <p class="text-sm text-gray-700">{{ $vendor->kontak }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                    {{ $vendor->jenis_perusahaan }}
                                </span>
                            </div>
                            <div class="flex items-center justify-end mt-3 space-x-3">
                                <button onclick="detailVendor({{ $vendor->id_vendor }})" class="text-blue-600 hover:text-blue-900 transition-colors p-2" title="Lihat Detail">
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                                @auth
                                    @if(auth()->user()->role === 'admin_purchasing' || auth()->user()->role === 'superadmin')
                                        <button onclick="editVendor({{ $vendor->id_vendor }})" class="text-yellow-600 hover:text-yellow-900 transition-colors p-2" title="Edit Vendor">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>
                                        <button onclick="hapusVendor({{ $vendor->id_vendor }})" class="text-red-600 hover:text-red-900 transition-colors p-2" title="Hapus Vendor">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    <div class="p-4 sm:p-6">
        <!-- Mobile Pagination -->
        <div class="flex md:hidden items-center justify-between pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                Halaman 1 dari 1
            </div>
            <div class="flex space-x-2">
                <button class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Desktop Pagination -->
        <div class="hidden md:flex items-center justify-between pt-6 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $vendors->count() > 0 ? '1-' . $vendors->count() : '0' }} dari {{ $vendors->count() }} vendor
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>
                    Previous
                </button>
                <button class="px-3 py-1 text-sm bg-red-600 text-white rounded-lg">1</button>
                <button class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
@auth
    @if(auth()->user()->role === 'admin_purchasing' || auth()->user()->role === 'superadmin')
        <button onclick="tambahVendor()" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 lg:bottom-16 lg:right-16 bg-red-600 hover:bg-red-700 text-white w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 z-50" title="Tambah Vendor Baru">
            <i class="fas fa-plus text-lg sm:text-xl"></i>
        </button>
    @endif
@endauth

@include('pages.purchasing.vendor-components.tambah')
@include('pages.purchasing.vendor-components.edit')
@include('pages.purchasing.vendor-components.detail')
@include('pages.purchasing.vendor-components.hapus')
@include('components.success-modal')

<script>
// Global variables
let vendorProducts = [];
let editVendorProducts = [];
let productIdCounter = 1;
let allVendors = @json($vendors);
let userRole = @json(auth()->user()->role ?? 'guest');

// Modal functions
function tambahVendor() {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk menambah vendor', 'error');
        return;
    }
    
    // Reset form
    document.getElementById('formTambahVendor').reset();
    vendorProducts = [];
    updateVendorProductList();
    showModal('modalTambahVendor');
}

function editVendor(id) {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk mengedit vendor', 'error');
        return;
    }
    
    // Fetch vendor data
    fetch(`/purchasing/vendor/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const vendor = data.vendor;
                
                // Populate form
                document.getElementById('editVendorId').value = vendor.id_vendor;
                document.getElementById('editNamaVendor').value = vendor.nama_vendor;
                document.getElementById('editEmailVendor').value = vendor.email;
                document.getElementById('editJenisPerusahaan').value = vendor.jenis_perusahaan;
                document.getElementById('editKontakVendor').value = vendor.kontak;
                document.getElementById('editAlamatVendor').value = vendor.alamat || '';
                
                // Load existing barang
                editVendorProducts = vendor.barang.map(barang => ({
                    id_barang: barang.id_barang,
                    nama_barang: barang.nama_barang,
                    brand: barang.brand,
                    kategori: barang.kategori,
                    satuan: barang.satuan,
                    spesifikasi: barang.spesifikasi,
                    harga_vendor: barang.harga_vendor,
                    foto_barang: barang.foto_barang
                }));
                
                updateEditVendorProductList();
                showModal('modalEditVendor');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data vendor');
        });
}

function detailVendor(id) {
    // Fetch vendor data
    fetch(`/purchasing/vendor/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const vendor = data.vendor;
                
                // Populate detail modal
                document.getElementById('detailNamaVendor').textContent = vendor.nama_vendor;
                document.getElementById('detailJenisPerusahaan').textContent = vendor.jenis_perusahaan;
                document.getElementById('detailEmailVendor').textContent = vendor.email;
                document.getElementById('detailKontakVendor').textContent = vendor.kontak;
                document.getElementById('detailAlamatVendor').textContent = vendor.alamat || '-';
                
                // Show products
                const productListHtml = vendor.barang.map(barang => `
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                        <div>
                            <p class="font-medium">${barang.nama_barang}</p>
                            <p class="text-sm text-gray-500">${barang.brand} • ${barang.kategori}</p>
                            <p class="text-sm text-blue-600">Rp ${Number(barang.harga_vendor).toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                `).join('');
                
                document.getElementById('detailProductList').innerHTML = productListHtml || '<p class="text-gray-500">Tidak ada produk</p>';
                
                showModal('modalDetailVendor');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data vendor');
        });
}

function hapusVendor(id) {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk menghapus vendor', 'error');
        return;
    }
    
    const vendor = allVendors.find(v => v.id_vendor == id);
    if (vendor) {
        document.getElementById('hapusVendorId').value = id;
        document.getElementById('hapusVendorNama').textContent = vendor.nama_vendor;
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
        
        // Reset edit mode when closing edit vendor modal
        if (modalId === 'modalEditVendor') {
            resetProductEditMode();
            clearEditProductForm();
        }
    }
}

// Legacy close functions
function closeTambahVendor() { closeModal('modalTambahVendor'); }
function closeEditVendor() { closeModal('modalEditVendor'); }
function closeDetailVendor() { closeModal('modalDetailVendor'); }
function closeHapusVendor() { closeModal('modalHapusVendor'); }

// Form submit functions
function submitTambahVendor() {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk menambah vendor', 'error');
        return;
    }
    
    console.log('=== SUBMITTING VENDOR ===');
    console.log('User role:', userRole);
    console.log('Vendor products:', vendorProducts);
    
    const form = document.getElementById('formTambahVendor');
    const formData = new FormData(form);
    
    // Add barang data
    vendorProducts.forEach((product, index) => {
        console.log(`Adding product ${index}:`, product);
        formData.append(`barang[${index}][nama_barang]`, product.nama_barang);
        formData.append(`barang[${index}][brand]`, product.brand);
        formData.append(`barang[${index}][kategori]`, product.kategori);
        formData.append(`barang[${index}][satuan]`, product.satuan);
        formData.append(`barang[${index}][spesifikasi]`, product.spesifikasi);
        formData.append(`barang[${index}][harga_vendor]`, product.harga_vendor);
        
        if (product.foto_barang && product.foto_barang instanceof File) {
            console.log(`Adding photo for product ${index}:`, product.foto_barang.name);
            formData.append(`barang[${index}][foto_barang]`, product.foto_barang);
        }
        
        if (product.spesifikasi_file && product.spesifikasi_file instanceof File) {
            console.log(`Adding spesifikasi file for product ${index}:`, product.spesifikasi_file.name);
            formData.append(`barang[${index}][spesifikasi_file]`, product.spesifikasi_file);
        }
    });
    
    fetch('/purchasing/vendor', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json', // Ensure JSON response
        },
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // For 422 errors, we still want to parse the JSON to see validation errors
        if (response.status === 422) {
            return response.json().then(data => {
                console.error('Validation failed:', data);
                throw new Error(`Validation Error: ${data.message}`);
            });
        }
        
        // Check content type for other errors
        const contentType = response.headers.get('content-type');
        console.log('Content-Type:', contentType);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        // Check if response is JSON
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Expected JSON but received:', text.substring(0, 500));
                throw new Error('Server returned non-JSON response. Check server logs.');
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showSuccessModal(data.message);
            closeModal('modalTambahVendor');
            location.reload();
        } else {
            showToast(data.message || 'Gagal menambahkan vendor', 'error');
            if (data.errors) {
                console.error('Validation errors:', data.errors);
                // Show first validation error
                const firstError = Object.values(data.errors)[0][0];
                showToast(firstError, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Full error object:', error);
        showToast(error.message || 'Terjadi kesalahan saat menambahkan vendor', 'error');
    });
}

function submitEditVendor() {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk mengedit vendor', 'error');
        return;
    }
    
    const vendorId = document.getElementById('editVendorId').value;
    
    // Create FormData manually to ensure all fields are included
    const formData = new FormData();
    
    // Add vendor data manually
    formData.append('_method', 'PUT');
    formData.append('nama_vendor', document.getElementById('editNamaVendor').value || '');
    formData.append('email', document.getElementById('editEmailVendor').value || '');
    formData.append('jenis_perusahaan', document.getElementById('editJenisPerusahaan').value || '');
    formData.append('kontak', document.getElementById('editKontakVendor').value || '');
    formData.append('alamat', document.getElementById('editAlamatVendor').value || '');
    
    // Validate required fields before sending
    const requiredFields = {
        'nama_vendor': document.getElementById('editNamaVendor').value,
        'email': document.getElementById('editEmailVendor').value,
        'jenis_perusahaan': document.getElementById('editJenisPerusahaan').value,
        'kontak': document.getElementById('editKontakVendor').value
    };
    
    // Check for empty required fields
    for (const [field, value] of Object.entries(requiredFields)) {
        if (!value || value.trim() === '') {
            showToast(`Field ${field.replace('_', ' ')} wajib diisi!`, 'error');
            return;
        }
    }
    
    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(requiredFields.email)) {
        showToast('Format email tidak valid!', 'error');
        return;
    }
    
    console.log('Vendor data being sent:', requiredFields);
    
    // Add barang data with validation
    let validProductCount = 0;
    editVendorProducts.forEach((product, index) => {
        // Validate each product before adding
        const requiredProductFields = {
            'nama_barang': product.nama_barang,
            'brand': product.brand,
            'kategori': product.kategori,
            'satuan': product.satuan,
            'harga_vendor': product.harga_vendor
        };
        
        // Check if all required fields are filled
        let isValidProduct = true;
        for (const [field, value] of Object.entries(requiredProductFields)) {
            if (!value || (typeof value === 'string' && value.trim() === '')) {
                console.warn(`Product ${index}: Missing ${field}`);
                isValidProduct = false;
                break;
            }
        }
        
        // Check if harga_vendor is a valid number
        if (isValidProduct && (isNaN(product.harga_vendor) || parseFloat(product.harga_vendor) < 0)) {
            console.warn(`Product ${index}: Invalid harga_vendor`);
            isValidProduct = false;
        }
        
        if (isValidProduct) {
            if (product.id_barang) {
                formData.append(`barang[${validProductCount}][id_barang]`, product.id_barang);
            }
            formData.append(`barang[${validProductCount}][nama_barang]`, product.nama_barang);
            formData.append(`barang[${validProductCount}][brand]`, product.brand);
            formData.append(`barang[${validProductCount}][kategori]`, product.kategori);
            formData.append(`barang[${validProductCount}][satuan]`, product.satuan);
            formData.append(`barang[${validProductCount}][spesifikasi]`, product.spesifikasi || '');
            formData.append(`barang[${validProductCount}][harga_vendor]`, product.harga_vendor);
            
            if (product.foto_barang && product.foto_barang instanceof File) {
                formData.append(`barang[${validProductCount}][foto_barang]`, product.foto_barang);
            }
            
            if (product.spesifikasi_file && product.spesifikasi_file instanceof File) {
                formData.append(`barang[${validProductCount}][spesifikasi_file]`, product.spesifikasi_file);
            }
            
            validProductCount++;
        } else {
            console.error(`Product ${index} validation failed:`, product);
        }
    });
    
    console.log(`Valid products to send: ${validProductCount} out of ${editVendorProducts.length}`);
    
    // Debug: Log all formData entries
    console.log('Complete FormData being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(key, ':', value);
    }
    
    fetch(`/purchasing/vendor/${vendorId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // For 422 errors, we want to parse the JSON to see validation errors
        if (response.status === 422) {
            return response.json().then(data => {
                console.error('Validation failed:', data);
                
                // Show detailed validation errors
                if (data.errors) {
                    const errorMessages = [];
                    for (const [field, messages] of Object.entries(data.errors)) {
                        errorMessages.push(`${field}: ${messages.join(', ')}`);
                    }
                    console.error('Detailed validation errors:', errorMessages);
                    showToast(`Validation Error: ${errorMessages[0]}`, 'error');
                } else {
                    showToast(`Validation Error: ${data.message}`, 'error');
                }
                
                throw new Error(`Validation Error: ${data.message}`);
            });
        }
        
        const contentType = response.headers.get('content-type');
        console.log('Content-Type:', contentType);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Expected JSON but received:', text.substring(0, 500));
                throw new Error('Server returned non-JSON response. Check server logs.');
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showSuccessModal(data.message);
            closeModal('modalEditVendor');
            location.reload();
        } else {
            showToast(data.message || 'Gagal mengupdate vendor', 'error');
            if (data.errors) {
                console.error('Validation errors:', data.errors);
                const firstError = Object.values(data.errors)[0][0];
                showToast(firstError, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Full error object:', error);
        showToast(error.message || 'Terjadi kesalahan saat mengupdate vendor', 'error');
    });
}

function confirmHapusVendor() {
    if (userRole !== 'admin_purchasing' && userRole !== 'superadmin') {
        showToast('Anda tidak memiliki izin untuk menghapus vendor', 'error');
        return;
    }
    
    const vendorId = document.getElementById('hapusVendorId').value;
    
    fetch(`/purchasing/vendor/${vendorId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },

    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal(data.message);
            closeModal('modalHapusVendor');
            location.reload();
        } else {
            alert(data.message || 'Gagal menghapus vendor');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus vendor');
    });
}

// Success modal functions
function showSuccessModal(message) {
    const modal = document.getElementById('successModal');
    const messageElement = document.getElementById('successMessage');
    
    if (modal && messageElement) {
        messageElement.textContent = message;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            closeSuccessModal();
        }, 3000);
    }
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Search and filter functions
function filterVendors() {
    const searchTerm = document.getElementById('searchVendor').value.toLowerCase();
    const jenisFilter = document.getElementById('filterJenis').value;
    
    const tableRows = document.querySelectorAll('#vendorTableBody tr');
    const mobileCards = document.querySelectorAll('#vendorMobileList .vendor-card');
    
    // Filter table rows
    tableRows.forEach(row => {
        const namaVendor = row.cells[1]?.textContent.toLowerCase() || '';
        const jenis = row.cells[2]?.textContent || '';
        const email = row.cells[3]?.textContent.toLowerCase() || '';
        
        const matchesSearch = namaVendor.includes(searchTerm) || email.includes(searchTerm);
        const matchesJenis = !jenisFilter || jenis === jenisFilter;
        
        if (matchesSearch && matchesJenis) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Filter mobile cards
    mobileCards.forEach(card => {
        const namaVendor = card.querySelector('h3')?.textContent.toLowerCase() || '';
        const jenis = card.querySelector('.bg-blue-100')?.textContent || '';
        const email = card.querySelector('.text-gray-700')?.textContent.toLowerCase() || '';
        
        const matchesSearch = namaVendor.includes(searchTerm) || email.includes(searchTerm);
        const matchesJenis = !jenisFilter || jenis === jenisFilter;
        
        if (matchesSearch && matchesJenis) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Product management functions
function addProductToVendor() {
    const namaBarang = document.getElementById('newProductName')?.value.trim();
    const brand = document.getElementById('newProductBrand')?.value.trim();
    const kategori = document.getElementById('newProductKategori')?.value;
    const satuan = document.getElementById('newProductSatuan')?.value.trim();
    const spesifikasi = document.getElementById('newProductSpesifikasi')?.value.trim();
    const hargaVendor = document.getElementById('newProductHarga')?.value;
    const fotoInput = document.getElementById('newProductFoto');
    const spesifikasiFileInput = document.getElementById('newProductSpesifikasiFile');
    
    if (!namaBarang || !brand || !kategori || !satuan || !hargaVendor) {
        showToast('Semua field produk yang wajib harus diisi!', 'error');
        return;
    }
    
    const product = {
        nama_barang: namaBarang,
        brand: brand,
        kategori: kategori,
        satuan: satuan,
        spesifikasi: spesifikasi || '',
        harga_vendor: parseFloat(hargaVendor),
        foto_barang: fotoInput.files[0] || null,
        spesifikasi_file: spesifikasiFileInput.files[0] || null
    };
    
    vendorProducts.push(product);
    updateVendorProductList();
    clearProductForm();
    
    // Show success message
    showToast('Produk berhasil ditambahkan!', 'success');
}

function addProductToEditVendor() {
    const namaBarang = document.getElementById('editNewProductName')?.value.trim();
    const brand = document.getElementById('editNewProductBrand')?.value.trim();
    const kategori = document.getElementById('editNewProductKategori')?.value;
    const satuan = document.getElementById('editNewProductSatuan')?.value.trim();
    const spesifikasi = document.getElementById('editNewProductSpesifikasi')?.value.trim();
    const hargaVendor = document.getElementById('editNewProductHarga')?.value;
    const fotoInput = document.getElementById('editNewProductFoto');
    const spesifikasiFileInput = document.getElementById('editNewProductSpesifikasiFile');
    
    if (!namaBarang || !brand || !kategori || !satuan || !hargaVendor) {
        showToast('Semua field produk harus diisi!', 'error');
        return;
    }
    
    const product = {
        nama_barang: namaBarang,
        brand: brand,
        kategori: kategori,
        satuan: satuan,
        spesifikasi: spesifikasi || '',
        harga_vendor: parseFloat(hargaVendor),
        foto_barang: fotoInput.files[0] || null,
        spesifikasi_file: spesifikasiFileInput.files[0] || null
    };
    
    editVendorProducts.push(product);
    updateEditVendorProductList();
    clearEditProductForm();
    
    // Show success message
    showToast('Produk berhasil ditambahkan!', 'success');
}

function updateVendorProductList() {
    const container = document.getElementById('vendorProductList');
    const counterElement = document.getElementById('productCount');
    
    if (!container) return;
    
    // Update counter
    if (counterElement) {
        counterElement.textContent = `${vendorProducts.length} produk`;
    }
    
    if (vendorProducts.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-box-open text-4xl mb-4 text-gray-400"></i>
                <p class="text-lg">Belum ada produk ditambahkan</p>
                <p class="text-sm">Gunakan form di atas untuk menambah produk</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = vendorProducts.map((product, index) => `
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-white to-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200">
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">${product.nama_barang}</p>
                        <p class="text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <i class="fas fa-tag mr-1"></i>${product.brand}
                            </span>
                            <span class="mx-2">•</span>
                            <span class="inline-flex items-center">
                                <i class="fas fa-layer-group mr-1"></i>${product.kategori}
                            </span>
                            <span class="mx-2">•</span>
                            <span class="inline-flex items-center">
                                <i class="fas fa-weight mr-1"></i>${product.satuan}
                            </span>
                        </p>
                        <p class="text-lg font-bold text-green-600 mt-1">
                            <i class="fas fa-money-bill-wave mr-1"></i>
                            Rp ${Number(product.harga_vendor).toLocaleString('id-ID')}
                        </p>
                        ${product.spesifikasi ? `<p class="text-xs text-gray-600 mt-1"><i class="fas fa-file-text mr-1"></i>${product.spesifikasi}</p>` : ''}
                        ${product.spesifikasi_file ? `<p class="text-xs text-blue-600 mt-1"><i class="fas fa-file mr-1"></i>File spesifikasi: ${product.spesifikasi_file.name}</p>` : ''}
                    </div>
                </div>
            </div>
            <button onclick="removeProductFromVendor(${index})" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 transform hover:scale-105" title="Hapus produk">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `).join('');
}

function updateEditVendorProductList() {
    const container = document.getElementById('editVendorProductList');
    const counterElement = document.getElementById('editProductCount');
    const searchContainer = document.getElementById('productSearchContainer');
    
    if (!container) return;
    
    // Show/hide search container based on product count
    if (searchContainer) {
        if (editVendorProducts.length > 3) {
            searchContainer.style.display = 'flex';
        } else {
            searchContainer.style.display = 'none';
        }
    }
    
    // Get search and filter values
    const searchTerm = document.getElementById('searchProducts')?.value.toLowerCase() || '';
    const selectedCategory = document.getElementById('filterProductCategory')?.value || '';
    
    // Filter products based on search and category
    let filteredProducts = editVendorProducts;
    if (searchTerm || selectedCategory) {
        filteredProducts = editVendorProducts.filter((product, index) => {
            const matchesSearch = !searchTerm || 
                product.nama_barang.toLowerCase().includes(searchTerm) ||
                product.brand.toLowerCase().includes(searchTerm) ||
                product.kategori.toLowerCase().includes(searchTerm) ||
                product.spesifikasi.toLowerCase().includes(searchTerm);
            
            const matchesCategory = !selectedCategory || product.kategori === selectedCategory;
            
            return matchesSearch && matchesCategory;
        });
    }
    
    // Update counter (show filtered count if filtering)
    if (counterElement) {
        if (filteredProducts.length !== editVendorProducts.length) {
            counterElement.textContent = `${filteredProducts.length} dari ${editVendorProducts.length} produk`;
        } else {
            counterElement.textContent = `${editVendorProducts.length} produk`;
        }
    }
    
    if (filteredProducts.length === 0) {
        if (editVendorProducts.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-4 text-gray-400"></i>
                    <p class="text-lg">Belum ada produk ditambahkan</p>
                    <p class="text-sm">Gunakan form di atas untuk menambah produk</p>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-search text-3xl mb-3 text-gray-400"></i>
                    <p class="text-base">Tidak ada produk yang sesuai dengan pencarian</p>
                    <p class="text-sm">Coba ubah kata kunci atau filter kategori</p>
                </div>
            `;
        }
        return;
    }

    container.innerHTML = filteredProducts.map((product) => {
        // Find original index in the full array
        const originalIndex = editVendorProducts.findIndex(p => 
            p.nama_barang === product.nama_barang && 
            p.brand === product.brand && 
            p.kategori === product.kategori &&
            p.harga_vendor === product.harga_vendor
        );
        
        return `
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-white to-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200 ${editProductIndex === originalIndex ? 'border-yellow-400 bg-yellow-50' : ''}">
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 ${editProductIndex === originalIndex ? 'bg-yellow-100' : 'bg-blue-100'} rounded-lg flex items-center justify-center">
                        <i class="fas fa-box ${editProductIndex === originalIndex ? 'text-yellow-600' : 'text-blue-600'}"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            ${editProductIndex === originalIndex ? '<span class="text-xs font-medium text-yellow-600"><i class="fas fa-edit mr-1"></i>SEDANG DIEDIT</span>' : ''}
                            ${product.id_barang ? '<span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full"><i class="fas fa-database mr-1"></i>DATABASE</span>' : '<span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full"><i class="fas fa-plus mr-1"></i>BARU</span>'}
                        </div>
                        <p class="font-semibold text-gray-800" id="productName_${originalIndex}">${product.nama_barang}</p>
                        <p class="text-sm text-gray-500" id="productInfo_${originalIndex}">
                            <span class="inline-flex items-center">
                                <i class="fas fa-tag mr-1"></i>${product.brand}
                            </span>
                            <span class="mx-2">•</span>
                            <span class="inline-flex items-center">
                                <i class="fas fa-layer-group mr-1"></i>${product.kategori}
                            </span>
                            <span class="mx-2">•</span>
                            <span class="inline-flex items-center">
                                <i class="fas fa-weight mr-1"></i>${product.satuan}
                            </span>
                        </p>
                        <p class="text-lg font-bold text-green-600 mt-1" id="productPrice_${originalIndex}">
                            <i class="fas fa-money-bill-wave mr-1"></i>
                            Rp ${Number(product.harga_vendor).toLocaleString('id-ID')}
                        </p>
                        ${product.spesifikasi ? `<p class="text-xs text-gray-600 mt-1" id="productSpec_${originalIndex}">${product.spesifikasi}</p>` : ''}
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="duplicateProductInVendor(${originalIndex})" class="px-3 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 transform hover:scale-105" title="Duplikat produk">
                    <i class="fas fa-copy"></i>
                </button>
                <button onclick="editProductInVendor(${originalIndex})" class="px-3 py-2 ${editProductIndex === originalIndex ? 'bg-yellow-200 text-yellow-700' : 'bg-yellow-100 text-yellow-600'} rounded-lg hover:bg-yellow-200 transition-all duration-200 transform hover:scale-105" title="${editProductIndex === originalIndex ? 'Sedang diedit' : 'Edit produk'}">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="removeProductFromEditVendor(${originalIndex})" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 transform hover:scale-105" title="${product.id_barang ? 'Hapus dari database' : 'Hapus produk'}" ${editProductIndex === originalIndex ? 'disabled' : ''}>
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    }).join('');
}

function removeProductFromVendor(index) {
    vendorProducts.splice(index, 1);
    updateVendorProductList();
}

function removeProductFromEditVendor(index) {
    const product = editVendorProducts[index];
    
    // If product has ID, it means it exists in database - show confirmation
    if (product.id_barang) {
        if (confirm(`Apakah Anda yakin ingin menghapus produk "${product.nama_barang}"? Data ini akan dihapus secara permanen dari database.`)) {
            editVendorProducts.splice(index, 1);
            updateEditVendorProductList();
            
            // Reset edit mode if the deleted product was being edited
            if (editProductIndex === index) {
                resetProductEditMode();
                clearEditProductForm();
            } else if (editProductIndex > index) {
                // Adjust edit index if a product before it was deleted
                editProductIndex--;
            }
        }
    } else {
        // New product (not in database yet), just remove without confirmation
        editVendorProducts.splice(index, 1);
        updateEditVendorProductList();
        
        // Reset edit mode if the deleted product was being edited
        if (editProductIndex === index) {
            resetProductEditMode();
            clearEditProductForm();
        } else if (editProductIndex > index) {
            // Adjust edit index if a product before it was deleted
            editProductIndex--;
        }
    }
}

// Global variable to track edit mode
let editProductIndex = -1;

function editProductInVendor(index) {
    const product = editVendorProducts[index];
    if (!product) return;
    
    // Store the index being edited
    editProductIndex = index;
    
    // Fill form with product data
    document.getElementById('editNewProductName').value = product.nama_barang || '';
    document.getElementById('editNewProductBrand').value = product.brand || '';
    document.getElementById('editNewProductKategori').value = product.kategori || '';
    document.getElementById('editNewProductSatuan').value = product.satuan || '';
    document.getElementById('editNewProductSpesifikasi').value = product.spesifikasi || '';
    document.getElementById('editNewProductHarga').value = product.harga_vendor || '';
    
    // Update form title and hint
    const formTitle = document.getElementById('productFormTitle');
    const formHint = document.getElementById('productFormHint');
    if (formTitle) {
        formTitle.innerHTML = '<i class="fas fa-edit text-yellow-600 mr-2"></i>Edit Produk';
    }
    if (formHint) {
        formHint.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Ubah data produk yang diperlukan';
    }
    
    // Change button text and behavior to indicate edit mode
    const addButton = document.querySelector('button[onclick="addProductToEditVendor()"]');
    if (addButton) {
        addButton.innerHTML = '<i class="fas fa-save mr-2"></i>Update Produk';
        addButton.setAttribute('onclick', 'updateProductInVendor()');
        addButton.className = 'px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-lg hover:from-yellow-700 hover:to-yellow-800 transition-all duration-200 transform hover:scale-105 shadow-lg';
    }
    
    // Show cancel button
    const cancelButton = document.getElementById('cancelEditProductBtn');
    if (cancelButton) {
        cancelButton.classList.remove('hidden');
    }
    
    // Scroll to form
    document.querySelector('.bg-gradient-to-br.from-blue-50').scrollIntoView({ behavior: 'smooth' });
}

function updateProductInVendor() {
    if (editProductIndex < 0 || editProductIndex >= editVendorProducts.length) {
        showToast('Error: Produk tidak ditemukan!', 'error');
        return;
    }
    
    const namaBarang = document.getElementById('editNewProductName')?.value.trim();
    const brand = document.getElementById('editNewProductBrand')?.value.trim();
    const kategori = document.getElementById('editNewProductKategori')?.value;
    const satuan = document.getElementById('editNewProductSatuan')?.value.trim();
    const spesifikasi = document.getElementById('editNewProductSpesifikasi')?.value.trim();
    const hargaVendor = document.getElementById('editNewProductHarga')?.value;
    const fotoInput = document.getElementById('editNewProductFoto');
    
    if (!namaBarang || !brand || !kategori || !satuan || !hargaVendor) {
        showToast('Semua field produk harus diisi!', 'error');
        return;
    }
    
    // Update product in array
    editVendorProducts[editProductIndex] = {
        ...editVendorProducts[editProductIndex], // Keep existing properties like id if any
        nama_barang: namaBarang,
        brand: brand,
        kategori: kategori,
        satuan: satuan,
        spesifikasi: spesifikasi || '',
        harga_vendor: parseFloat(hargaVendor),
        foto_barang: fotoInput.files[0] || editVendorProducts[editProductIndex].foto_barang || null
    };
    
    // Reset edit mode
    resetProductEditMode();
    
    // Update display
    updateEditVendorProductList();
    clearEditProductForm();
    
    // Show success message
    showToast('Produk berhasil diupdate!', 'success');
}

function resetProductEditMode() {
    editProductIndex = -1;
    
    // Reset form title and hint
    const formTitle = document.getElementById('productFormTitle');
    const formHint = document.getElementById('productFormHint');
    if (formTitle) {
        formTitle.innerHTML = '<i class="fas fa-plus-circle text-green-600 mr-2"></i>Tambah Produk Baru';
    }
    if (formHint) {
        formHint.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Isi semua field untuk menambah produk';
    }
    
    // Reset button to add mode
    const addButton = document.querySelector('button[onclick="updateProductInVendor()"]');
    if (addButton) {
        addButton.innerHTML = '<i class="fas fa-plus mr-2"></i>Tambah ke Daftar Produk';
        addButton.setAttribute('onclick', 'addProductToEditVendor()');
        addButton.className = 'px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 transform hover:scale-105 shadow-lg';
    }
    
    // Hide cancel button
    const cancelButton = document.getElementById('cancelEditProductBtn');
    if (cancelButton) {
        cancelButton.classList.add('hidden');
    }
}

function cancelEditProduct() {
    clearEditProductForm();
    resetProductEditMode();
}

function duplicateProductInVendor(index) {
    const product = editVendorProducts[index];
    if (!product) return;
    
    // Create a copy of the product (without id_barang to mark it as new)
    const duplicatedProduct = {
        nama_barang: product.nama_barang + ' (Copy)',
        brand: product.brand,
        kategori: product.kategori,
        satuan: product.satuan,
        spesifikasi: product.spesifikasi,
        harga_vendor: product.harga_vendor,
        foto_barang: product.foto_barang
    };
    
    // Add to the list
    editVendorProducts.push(duplicatedProduct);
    updateEditVendorProductList();
    
    // Show success message
    showToast('Produk berhasil diduplikat!', 'success');
}

function clearAllProducts() {
    if (editVendorProducts.length === 0) {
        showToast('Tidak ada produk untuk dihapus!', 'info');
        return;
    }
    
    const hasDbProducts = editVendorProducts.some(product => product.id_barang);
    const message = hasDbProducts 
        ? `Apakah Anda yakin ingin menghapus semua ${editVendorProducts.length} produk? Produk yang sudah ada di database akan dihapus secara permanen.`
        : `Apakah Anda yakin ingin menghapus semua ${editVendorProducts.length} produk?`;
    
    if (confirm(message)) {
        editVendorProducts = [];
        resetProductEditMode();
        clearEditProductForm();
        updateEditVendorProductList();
        showToast('Semua produk berhasil dihapus!', 'success');
    }
}

function exportProductList() {
    if (editVendorProducts.length === 0) {
        showToast('Tidak ada produk untuk diekspor!', 'info');
        return;
    }
    
    // Prepare data for export
    const exportData = editVendorProducts.map((product, index) => ({
        'No': index + 1,
        'Nama Produk': product.nama_barang,
        'Brand': product.brand,
        'Kategori': product.kategori,
        'Satuan': product.satuan,
        'Harga Vendor': product.harga_vendor,
        'Spesifikasi': product.spesifikasi || '',
        'Status': product.id_barang ? 'Database' : 'Baru'
    }));
    
    // Convert to CSV
    const headers = Object.keys(exportData[0]);
    const csvContent = [
        headers.join(','),
        ...exportData.map(row => headers.map(header => `"${row[header]}"`).join(','))
    ].join('\n');
    
    // Download CSV file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const vendorName = document.getElementById('editNamaVendor')?.value || 'Vendor';
    link.href = URL.createObjectURL(blob);
    link.download = `Produk_${vendorName}_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
    
    showToast('Data produk berhasil diekspor!', 'success');
}

function clearProductForm() {
    const fields = ['newProductName', 'newProductBrand', 'newProductKategori', 'newProductSatuan', 'newProductSpesifikasi', 'newProductHarga', 'newProductFoto', 'newProductSpesifikasiFile'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) element.value = '';
    });
    
    // Reset spesifikasi input to text mode
    const textRadio = document.querySelector('input[name="spesifikasi_type"][value="text"]');
    if (textRadio) {
        textRadio.checked = true;
        toggleSpesifikasiInput('text');
    }
    
    // Hide file preview
    const filePreview = document.getElementById('spesifikasiFilePreview');
    if (filePreview) filePreview.classList.add('hidden');
}

function clearEditProductForm() {
    const fields = ['editNewProductName', 'editNewProductBrand', 'editNewProductKategori', 'editNewProductSatuan', 'editNewProductSpesifikasi', 'editNewProductHarga', 'editNewProductFoto', 'editNewProductSpesifikasiFile'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) element.value = '';
    });
    
    // Reset spesifikasi input to text mode
    const textRadio = document.querySelector('input[name="edit_spesifikasi_type"][value="text"]');
    if (textRadio) {
        textRadio.checked = true;
        toggleEditSpesifikasiInput('text');
    }
    
    // Hide file preview
    const filePreview = document.getElementById('editSpesifikasiFilePreview');
    if (filePreview) filePreview.classList.add('hidden');
    
    // Reset edit mode if active
    if (editProductIndex >= 0) {
        resetProductEditMode();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize filter functionality
    const searchInput = document.getElementById('searchVendor');
    const filterSelect = document.getElementById('filterJenis');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterVendors();
        });
    }
    
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            filterVendors();
        });
    }
    
    // Initialize product search and filter functionality
    const productSearchInput = document.getElementById('searchProducts');
    const productFilterSelect = document.getElementById('filterProductCategory');
    
    if (productSearchInput) {
        productSearchInput.addEventListener('input', function() {
            updateEditVendorProductList();
        });
    }
    
    if (productFilterSelect) {
        productFilterSelect.addEventListener('change', function() {
            updateEditVendorProductList();
        });
    }
    
    // Initialize modal close buttons
    const closeButtons = document.querySelectorAll('[data-modal-close]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-close');
            closeModal(modalId);
        });
    });
    
    console.log('Vendor management initialized successfully');
});

// Toast notification function
function showToast(message, type = 'success') {
    // Remove existing toast if any
    const existingToast = document.getElementById('toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.id = 'toast-notification';
    toast.className = `fixed top-4 right-4 z-50 transform transition-all duration-300 ease-in-out`;
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    toast.innerHTML = `
        <div class="${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-80">
            <i class="fas ${icon} text-xl"></i>
            <span class="font-medium">${message}</span>
            <button onclick="removeToast()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Add to body
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        removeToast();
    }, 5000);
}

function removeToast() {
    const toast = document.getElementById('toast-notification');
    if (toast) {
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}
</script>
@endsection
