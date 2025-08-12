    @extends('layouts.app')

    @section('content')
    <!-- Header Section -->
    <div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Manajemen Wilayah</h1>
                <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola data wilayah dan kontak pejabat instansi</p>
            </div>
            <div class="hidden sm:block lg:block">
                <i class="fas fa-map-marked-alt text-3xl sm:text-4xl lg:text-6xl"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center">
                <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-red-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                    <i class="fas fa-map text-red-600 text-sm sm:text-lg lg:text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Total Wilayah</h3>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">15</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center">
                <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-green-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                    <i class="fas fa-building text-green-600 text-sm sm:text-lg lg:text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Instansi Aktif</h3>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">45</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center">
                <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-blue-100 mb-2 sm:mb-0 sm:mr-4 w-fit">
                    <i class="fas fa-user-tie text-blue-600 text-sm sm:text-lg lg:text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-800 truncate">Kontak Pejabat</h3>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">67</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-yellow-100 mr-4">
                    <i class="fas fa-users text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Admin Marketing</h3>
                    <p class="text-2xl font-bold text-yellow-600">8</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-20">
        <!-- Header -->
        <div class="p-8 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Wilayah & Instansi</h2>
                    <p class="text-gray-600 mt-1">Kelola data wilayah dan informasi kontak pejabat</p>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Cari wilayah, instansi, atau admin marketing..." 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
                <div class="flex gap-3">
                    <select class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option>Semua Wilayah</option>
                        <option>Jakarta</option>
                        <option>Bogor</option>
                        <option>Depok</option>
                        <option>Tangerang</option>
                        <option>Bekasi</option>
                    </select>
                    <select class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option>Urutkan</option>
                        <option>Nama Wilayah</option>
                        <option>Jumlah Instansi</option>
                        <option>Terbaru</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- List Layout -->
        <div class="p-6">
            <div class="space-y-4">
                <!-- Item 1 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-building text-red-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Jakarta Pusat</h3>
                                <p class="text-sm text-gray-600">Dinas Pendidikan DKI Jakarta</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                        
                            <button onclick="editWilayah(1)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit Kontak">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                            <p class="font-medium text-gray-800">Budi Santoso</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nama Pejabat</p>
                            <p class="font-medium text-gray-800">Dr. Ahmad Wijaya</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Jabatan</p>
                            <p class="font-medium text-gray-800">Kepala Dinas</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">No. Telepon</p>
                            <p class="font-medium text-gray-800">021-8765432</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3">
                        <p class="text-xs text-gray-500">Terakhir diupdate: 10 Agustus 2025</p>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-hospital text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Bogor</h3>
                                <p class="text-sm text-gray-600">RSUD Kota Bogor</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                    
                            <button onclick="editWilayah(2)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit Kontak">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                            <p class="font-medium text-gray-800">Sari Indah</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nama Pejabat</p>
                            <p class="font-medium text-gray-800">Dr. Siti Nurhaliza</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Jabatan</p>
                            <p class="font-medium text-gray-800">Direktur RSUD</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">No. Telepon</p>
                            <p class="font-medium text-gray-800">0251-123456</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3">
                        <p class="text-xs text-gray-500">Terakhir diupdate: 8 Agustus 2025</p>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-university text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Depok</h3>
                                <p class="text-sm text-gray-600">Dinas Komunikasi dan Informatika</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                    
                            <button onclick="editWilayah(3)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit Kontak">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                            <p class="font-medium text-gray-800">Andi Pratama</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nama Pejabat</p>
                            <p class="font-medium text-gray-800">Ir. Rahman Hakim</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Jabatan</p>
                            <p class="font-medium text-gray-800">Kepala Dinas</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">No. Telepon</p>
                            <p class="font-medium text-gray-800">021-7654321</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3">
                        <p class="text-xs text-gray-500">Terakhir diupdate: 5 Agustus 2025</p>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-city text-purple-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Tangerang</h3>
                                <p class="text-sm text-gray-600">Badan Perencanaan Pembangunan Daerah</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                    
                            <button onclick="editWilayah(4)" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors duration-200" title="Edit Kontak">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Admin Marketing</p>
                            <p class="font-medium text-gray-800">Maya Sari</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nama Pejabat</p>
                            <p class="font-medium text-gray-800">Drs. Bambang Sutrisno</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Jabatan</p>
                            <p class="font-medium text-gray-800">Kepala Badan</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">No. Telepon</p>
                            <p class="font-medium text-gray-800">021-5551234</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3">
                        <p class="text-xs text-gray-500">Terakhir diupdate: 3 Agustus 2025</p>
                    </div>
                </div>

            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    Menampilkan 1-4 dari 15 data wilayah
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>
                        <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                    </button>
                    <button class="px-3 py-2 text-sm bg-red-600 text-white rounded-lg">1</button>
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                        Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Modal Components -->
    @include('pages.marketing.wilayah-components.edit')
    @include('components.success-modal')

    <script>
        // Function to edit wilayah
        function editWilayah(id) {
            // Sample data - replace with actual data from backend
            const sampleData = {
                1: {
                    id: 1,
                    wilayah: 'Jakarta Pusat',
                    instansi: 'Dinas Pendidikan DKI Jakarta',
                    admin_marketing: 'Budi Santoso',
                    nama_pejabat: 'Dr. Ahmad Wijaya',
                    jabatan: 'Kepala Dinas',
                    no_telp: '021-8765432'
                },
                2: {
                    id: 2,
                    wilayah: 'Bogor',
                    instansi: 'RSUD Kota Bogor',
                    admin_marketing: 'Sari Indah',
                    nama_pejabat: 'Dr. Siti Nurhaliza',
                    jabatan: 'Direktur RSUD',
                    no_telp: '0251-123456'
                },
                3: {
                    id: 3,
                    wilayah: 'Depok',
                    instansi: 'Dinas Komunikasi dan Informatika',
                    admin_marketing: 'Andi Pratama',
                    nama_pejabat: 'Ir. Rahman Hakim',
                    jabatan: 'Kepala Dinas',
                    no_telp: '021-7654321'
                },
                4: {
                    id: 4,
                    wilayah: 'Tangerang',
                    instansi: 'Badan Perencanaan Pembangunan Daerah',
                    admin_marketing: 'Maya Sari',
                    nama_pejabat: 'Drs. Bambang Sutrisno',
                    jabatan: 'Kepala Badan',
                    no_telp: '021-5551234'
                }
            };

            const data = sampleData[id];
            if (data) {
                // Populate form
                document.getElementById('editId').value = data.id;
                document.getElementById('editWilayah').value = data.wilayah;
                document.getElementById('editInstansi').value = data.instansi;
                document.getElementById('editAdminMarketing').value = data.admin_marketing;
                document.getElementById('editNamaPejabat').value = data.nama_pejabat;
                document.getElementById('editJabatan').value = data.jabatan;
                document.getElementById('editNoTelp').value = data.no_telp;
                
                // Show modal
                document.getElementById('modalEditWilayah').classList.remove('hidden');
                document.getElementById('modalEditWilayah').classList.add('flex');
            }
        }

        

        // Function to close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        // Handle form submission
        document.getElementById('formEditWilayah').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Here you would normally send data to backend
            console.log('Updating wilayah data:', data);
            
            // Close edit modal
            closeModal('modalEditWilayah');
            
            // Show success modal
            showSuccessModal('Data wilayah berhasil diperbarui!');
            
            // Optional: Auto refresh after success modal closes
            setTimeout(() => {
                location.reload();
            }, 2000);
        });
    </script>
    @endsection