@extends('layouts.app')

@section('title', 'Wilayah - Cyber KATANA')

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
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">{{ $totalWilayah }}</p>
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
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">{{ $totalInstansi }}</p>
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
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">{{ $totalKontak }}</p>
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
                    <p class="text-2xl font-bold text-yellow-600">{{ $totalAdminMarketing }}</p>
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
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button onclick="tambahWilayah()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl transition-colors duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Wilayah
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-user-tie absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select id="filterPIC" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 appearance-none bg-white" onchange="filterWilayah()">
                            <option value="">Semua PIC</option>
                            @php
                                $allPIC = collect($wilayahData)->map(function($wilayah) {
                                    return collect($wilayah['instansi_list'])->pluck('admin_marketing')->filter(function($admin) {
                                        return !empty($admin) && $admin !== '-';
                                    });
                                })->flatten()->unique()->sort()->values();
                            @endphp
                            @foreach($allPIC as $pic)
                                <option value="{{ $pic }}">{{ $pic }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- List Layout -->
        <div class="p-6">
            <div id="wilayahContainer" class="space-y-6">
                @forelse($wilayahData as $wilayah)
                <!-- Wilayah Card: {{ $wilayah['wilayah'] }} -->
                <div class="wilayah-card bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:border-red-200"
                     data-wilayah="{{ $wilayah['wilayah'] }}"
                     data-provinsi="{{ $wilayah['provinsi'] }}"
                     data-instansi="{{ implode(',', array_column($wilayah['instansi_list'], 'instansi')) }}"
                     data-admin="{{ implode(',', array_filter(array_column($wilayah['instansi_list'], 'admin_marketing'))) }}"
                     data-jumlah-instansi="{{ $wilayah['jumlah_instansi'] }}"
                     data-total-proyek="{{ $wilayah['total_proyek'] }}"
                     data-updated="{{ $wilayah['updated_at'] }}">
                    <!-- Wilayah Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-map-marked-alt text-red-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $wilayah['wilayah'] }}</h3>
                                <p class="text-gray-600">{{ $wilayah['provinsi'] }}</p>
                                <p class="text-sm text-gray-500">Kode: {{ $wilayah['kode_wilayah'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                {{ $wilayah['jumlah_instansi'] }} Instansi
                            </span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                {{ $wilayah['total_proyek'] }} Proyek
                            </span>
                        </div>
                    </div>

                    <!-- PIC Wilayah -->
                    @php
                        // Ambil PIC dari instansi pertama atau admin marketing yang ada
                        $picWilayah = collect($wilayah['instansi_list'])->map(function($inst) {
                            return $inst['admin_marketing'] ?? null;
                        })->filter()->unique()->first();
                    @endphp
                    @if($picWilayah && $picWilayah != '-')
                    <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-tie text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-yellow-600 font-medium">PIC Wilayah</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $picWilayah }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Instansi Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        @foreach($wilayah['instansi_list'] as $instansi)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">{{ $instansi['instansi'] }}</h4>
                                    <p class="text-xs text-gray-600 mb-2">{{ $instansi['jumlah_proyek'] }} proyek aktif</p>
                                </div>
                                <div class="flex space-x-1">
                                    <button onclick="detailInstansi({{ $instansi['id'] }})" class="text-blue-600 hover:bg-blue-100 p-1 rounded transition-colors" title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button onclick="editWilayah({{ $instansi['id'] }})" class="text-green-600 hover:bg-green-100 p-1 rounded transition-colors" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="hapusWilayah({{ $instansi['id'] }})" class="text-red-600 hover:bg-red-100 p-1 rounded transition-colors" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-user-tie w-3 mr-2"></i>
                                    <span class="font-medium">{{ $instansi['nama_pejabat'] }}</span>
                                </div>
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-briefcase w-3 mr-2"></i>
                                    <span>{{ $instansi['jabatan'] }}</span>
                                </div>
                                @if($instansi['no_telp'] && $instansi['no_telp'] != '-')
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-phone w-3 mr-2"></i>
                                    <span>{{ $instansi['no_telp'] }}</span>
                                </div>
                                @endif
                                @if($instansi['email'] && $instansi['email'] != '-')
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-envelope w-3 mr-2"></i>
                                    <span class="truncate">{{ $instansi['email'] }}</span>
                                </div>
                                @endif
                            </div>

                            @if($instansi['admin_marketing'] && $instansi['admin_marketing'] != '-')
                            <div class="mt-3 pt-2 border-t border-gray-200">
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-user-cog w-3 mr-2"></i>
                                    <span>Admin: {{ $instansi['admin_marketing'] }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Wilayah Footer -->
                    <div class="border-t border-gray-200 pt-3 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span>Terakhir diperbarui: {{ $wilayah['updated_at'] }}</span>
                        </div>
                        <button onclick="tambahInstansiKeWilayah('{{ $wilayah['wilayah'] }}', '{{ $wilayah['provinsi'] }}')"
                                class="text-blue-600 hover:bg-blue-50 px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-plus mr-1"></i>Tambah Instansi
                        </button>
                    </div>
                </div>
                @empty
                <!-- No Results Message -->
                <div class="text-center py-12">
                    <i class="fas fa-map text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">Belum ada data wilayah</h3>
                    <p class="text-gray-500">Tambahkan wilayah untuk melihat data instansi dan pejabat</p>
                </div>
                @endforelse
            </div>

            <!-- No Results from Filter -->
            <div id="noResults" class="text-center py-12 hidden">
                <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada hasil yang ditemukan</h3>
                <p class="text-gray-500">Coba gunakan kata kunci pencarian yang berbeda</p>
                <button onclick="resetFilter()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-redo mr-2"></i>Reset Filter
                </button>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-8 pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                <div class="text-sm text-gray-600 text-center sm:text-left">
                    Menampilkan 1-{{ count($wilayahData) }} dari {{ count($wilayahData) }} data wilayah
                </div>

                <!-- Mobile Pagination (Simple) -->
                <div class="flex sm:hidden items-center justify-center space-x-3">
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 min-h-[44px] flex items-center" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="text-sm font-medium text-gray-700 px-3 py-2">1 / 4</span>
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 min-h-[44px] flex items-center">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Desktop Pagination (Full) -->
                <div class="hidden sm:flex items-center space-x-2">
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
    @include('pages.marketing.wilayah-components.tambah')
    @include('pages.marketing.wilayah-components.detail')
    @include('pages.marketing.wilayah-components.edit')
    @include('pages.marketing.wilayah-components.hapus')
    @include('components.success-modal')

    <script>
        // Data from controller - flatten instansi data for easier access
        const wilayahData = @json($wilayahData);
        const instansiData = {};

        // Flatten instansi data for direct access by ID
        wilayahData.forEach(wilayah => {
            if (wilayah.instansi_list) {
                wilayah.instansi_list.forEach(instansi => {
                    instansiData[instansi.id] = {
                        ...instansi,
                        wilayah: wilayah.wilayah,
                        provinsi: wilayah.provinsi,
                        // kode_wilayah sudah ada di level instansi
                        deskripsi: wilayah.deskripsi
                    };
                });
            }
        });

        // Function to add new wilayah
        function tambahWilayah() {
            // Clear form
            document.getElementById('formTambahWilayah').reset();

            // Show modal
            document.getElementById('modalTambahWilayah').classList.remove('hidden');
            document.getElementById('modalTambahWilayah').classList.add('flex');
        }

        // Function to add instansi to existing wilayah
        function tambahInstansiKeWilayah(namaWilayah, provinsi) {
            // Clear form
            document.getElementById('formTambahWilayah').reset();

            // Pre-fill wilayah and provinsi
            document.getElementById('tambahWilayah').value = namaWilayah;
            document.getElementById('tambahProvinsi').value = provinsi;

            // Generate unique kode_wilayah suggestion
            const existingCodes = Object.values(instansiData).map(item => item.kode_wilayah);
            let counter = 1;
            let suggestedCode = namaWilayah.substring(0, 3).toUpperCase() + '-' + counter.toString().padStart(2, '0');
            while (existingCodes.includes(suggestedCode)) {
                counter++;
                suggestedCode = namaWilayah.substring(0, 3).toUpperCase() + '-' + counter.toString().padStart(2, '0');
            }
            document.getElementById('tambahKodeWilayah').value = suggestedCode;

            // Show modal
            document.getElementById('modalTambahWilayah').classList.remove('hidden');
            document.getElementById('modalTambahWilayah').classList.add('flex');
        }

        // Function to view instansi detail (renamed from detailWilayah)
        function detailInstansi(id) {
            const data = instansiData[id];
            if (data) {
                // Populate detail modal
                document.getElementById('detailWilayah').textContent = data.wilayah;
                document.getElementById('detailInstansi').textContent = data.instansi;
                document.getElementById('detailAdminMarketing').textContent = data.admin_marketing || '-';
                document.getElementById('detailNamaPejabat').textContent = data.nama_pejabat;
                document.getElementById('detailJabatan').textContent = data.jabatan;
                document.getElementById('detailNoTelp').textContent = data.no_telp || '-';
                document.getElementById('detailEmail').textContent = data.email || '-';
                document.getElementById('detailUpdatedAt').textContent = data.updated_at;

                // Show modal
                document.getElementById('modalDetailWilayah').classList.remove('hidden');
                document.getElementById('modalDetailWilayah').classList.add('flex');
            }
        }

        // Keep original function for backward compatibility
        function detailWilayah(id) {
            detailInstansi(id);
        }

        // Function to edit wilayah/instansi
        function editWilayah(id) {
            const data = instansiData[id];
            console.log('Edit function called with ID:', id);
            console.log('Data found:', data);
            console.log('All instansiData:', instansiData);

            if (data) {
                // Populate form
                document.getElementById('editId').value = data.id;
                document.getElementById('editWilayah').value = data.wilayah;
                document.getElementById('editProvinsi').value = data.provinsi || '';
                document.getElementById('editInstansi').value = data.instansi;
                document.getElementById('editKodeWilayah').value = data.kode_wilayah || '';
                document.getElementById('editNamaPejabat').value = data.nama_pejabat || '';
                document.getElementById('editJabatan').value = data.jabatan || '';
                document.getElementById('editNoTelp').value = data.no_telp || '';
                document.getElementById('editEmail').value = data.email || '';
                document.getElementById('editAdminMarketingText').value = data.admin_marketing_text || '';
                document.getElementById('editDeskripsi').value = data.deskripsi || '';

                // Show modal
                document.getElementById('modalEditWilayah').classList.remove('hidden');
                document.getElementById('modalEditWilayah').classList.add('flex');
            }
        }

        // Function to delete wilayah
        function hapusWilayah(id) {
            const data = instansiData[id];
            if (data) {
                // Populate delete confirmation
                document.getElementById('hapusId').value = data.id;
                document.getElementById('hapusWilayahName').textContent = data.wilayah;
                document.getElementById('hapusInstansiName').textContent = data.instansi;

                // Show modal
                document.getElementById('modalHapusWilayah').classList.remove('hidden');
                document.getElementById('modalHapusWilayah').classList.add('flex');
            }
        }

        // Function to close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        // Handle tambah form submission
        document.getElementById('formTambahWilayah').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]') || document.querySelector('button[form="formTambahWilayah"]');
            let originalText = '';
            if (submitBtn) {
                originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            }

            // Send data to backend
            const formDataToSend = new FormData();
            Object.keys(data).forEach(key => {
                formDataToSend.append(key, data[key]);
            });

            fetch('{{ url("marketing/wilayah") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formDataToSend
            })
            .then(response => {
                console.log('Response status:', response.status);

                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Response not OK:', response.status, text);
                        alert(`Error ${response.status}: ${text.substring(0, 500)}`);
                        throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
                    });
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Response is not JSON:', text);
                        alert('Server returned non-JSON response: ' + text.substring(0, 500));
                        throw new Error('Response is not JSON: ' + text.substring(0, 200));
                    });
                }

                return response.json().then(data => ({ status: response.status, body: data }));
            })
            .then(result => {
                if (result.status === 200 && result.body.success) {
                    // Close modal
                    closeModal('modalTambahWilayah');

                    // Show success message
                    alert(result.body.message || 'Data wilayah berhasil ditambahkan!');

                    // Refresh page to show new data
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert(result.body.message || 'Gagal menambahkan data wilayah');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menambahkan data wilayah');
            })
            .finally(() => {
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });

        // Handle edit form submission
        document.getElementById('formEditWilayah').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const id = data.id;

            console.log('Edit form data:', data);
            console.log('Update URL will be:', '{{ url("marketing/wilayah") }}' + '/' + id);

            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]') || document.querySelector('button[form="formEditWilayah"]');
            let originalText = '';
            if (submitBtn) {
                originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            }

            // Send data to backend
            const updateUrl = '{{ url("marketing/wilayah") }}' + '/' + id;

            // Create FormData for proper form submission
            const formDataToSend = new FormData();
            Object.keys(data).forEach(key => {
                formDataToSend.append(key, data[key]);
            });
            formDataToSend.append('_method', 'PUT');

            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formDataToSend
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                if (!response.ok) {
                    // If response is not ok, try to get text first (might be HTML error)
                    return response.text().then(text => {
                        console.error('Response not OK:', response.status, text);
                        alert(`Error ${response.status}: ${text.substring(0, 500)}`);
                        throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
                    });
                }

                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Response is not JSON:', text);
                        alert('Server returned non-JSON response: ' + text.substring(0, 500));
                        throw new Error('Response is not JSON: ' + text.substring(0, 200));
                    });
                }

                return response.json().then(data => ({ status: response.status, body: data }));
            })
            .then(result => {
                if (result.status === 200 && result.body.success) {
                    // Close edit modal
                    closeModal('modalEditWilayah');

                    // Show success message
                    alert(result.body.message || 'Data wilayah berhasil diperbarui!');

                    // Refresh page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else if (result.status === 422 && result.body.errors) {
                    // Validation errors
                    const errorMessages = Object.values(result.body.errors).flat();
                    alert('Validation Error:\n' + errorMessages.join('\n'));
                } else {
                    alert(result.body.message || 'Gagal memperbarui data wilayah');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui data wilayah');
            })
            .finally(() => {
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });

        // Handle delete form submission
        document.getElementById('formHapusWilayah').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const id = data.id;

            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]') || document.querySelector('button[form="formHapusWilayah"]');
            let originalText = '';
            if (submitBtn) {
                originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
            }

            // Send delete request to backend
            const deleteUrl = '{{ url("marketing/wilayah") }}' + '/' + id;

            const formDataToSend = new FormData();
            formDataToSend.append('_method', 'DELETE');

            fetch(deleteUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formDataToSend
            })
            .then(response => {
                // Parse JSON response regardless of status
                return response.json().then(data => ({ status: response.status, body: data }));
            })
            .then(result => {
                if (result.status === 200 && result.body.success) {
                    // Close delete modal
                    closeModal('modalHapusWilayah');

                    // Show success message
                    alert(result.body.message || 'Data wilayah berhasil dihapus!');

                    // Refresh page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    // Handle error cases (400, 404, 500)
                    alert(result.body.message || 'Gagal menghapus data wilayah');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data wilayah');
            })
            .finally(() => {
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });

        // Filter and Search Functions
        function filterWilayah() {
            const filterPIC = document.getElementById('filterPIC').value.toLowerCase();
            const cards = document.querySelectorAll('.wilayah-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const admin = card.getAttribute('data-admin').toLowerCase();

                // Check PIC filter (hanya berdasarkan PIC)
                const picMatch = filterPIC === '' || admin.includes(filterPIC);

                // Show/hide card
                if (picMatch) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide no results message
            const noResults = document.getElementById('noResults');
            const container = document.getElementById('wilayahContainer');
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        function resetFilter() {
            document.getElementById('filterPIC').value = '';
            filterWilayah();
        }
    </script>
    @endsection
