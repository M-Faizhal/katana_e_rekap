@extends('layouts.app')

@section('title', 'Pengelolaan Akun')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-800 to-red-600 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-6 sm:py-8 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white">Pengelolaan Akun</h1>
                    <p class="mt-2 text-sm sm:text-base text-red-100">Kelola akun pengguna dan hak akses sistem dengan mudah</p>
                </div>
                <button onclick="openAddUserModal()" class="bg-white text-red-600 hover:bg-red-50 px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto">
                    <i class="fas fa-user-plus text-sm sm:text-base"></i>
                    <span class="text-sm sm:text-base">Tambah Pengguna</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Overview -->
        <div class="mb-6 sm:mb-8">
            <!-- Main Stats Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8 mb-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-users text-white text-2xl sm:text-3xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 sm:ml-6">
                            <p class="text-sm sm:text-base font-medium text-gray-600 mb-1">Total Pengguna Terdaftar</p>
                            <p class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900">42</p>
                            <p class="text-sm text-green-600 mt-2 flex items-center">
                                <i class="fas fa-arrow-up mr-2"></i>
                                <span>Bertambah 2 pengguna bulan ini</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0 flex flex-col space-y-2 text-right">
                        <div class="text-sm text-gray-500">Sistem Aktif</div>
                        <div class="flex items-center justify-end">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-sm font-medium text-green-600">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 mb-6 sm:mb-8">
            <div class="flex flex-col space-y-4">
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" placeholder="Cari pengguna..."
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                    <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                </div>

                <!-- Filter Controls -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <select class="px-4 py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="staff">Staff</option>
                    </select>
                    <select class="px-4 py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base">
                        <option value="">Cari berdasarkan nama</option>
                        <option value="john">John Doe</option>
                        <option value="jane">Jane Smith</option>
                        <option value="bob">Bob Johnson</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Mobile Card View -->
            <div class="block lg:hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Pengguna</h3>
                    <p class="text-sm text-gray-600 mt-1">42 pengguna terdaftar</p>
                </div>
                <div class="divide-y divide-gray-200">
                    <!-- User Card 1 -->
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img class="h-12 w-12 rounded-full border-2 border-gray-200" src="https://ui-avatars.com/api/?name=John+Doe&background=ef4444&color=ffffff" alt="">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900">John Doe</h4>
                                    <p class="text-sm text-gray-500">john.doe@katana.com</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Admin</span>
                                    </div>
                                </div>
                            </div>
                            <button onclick="confirmDeleteUser('USR-001', 'John Doe')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-trash text-lg"></i>
                            </button>
                        </div>
                        <div class="mt-3 text-sm">
                            <div>
                                <span class="text-gray-500">Departemen:</span>
                                <span class="font-medium text-gray-900 ml-1">IT</span>
                            </div>
                        </div>
                    </div>

                    <!-- User Card 2 -->
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img class="h-12 w-12 rounded-full border-2 border-gray-200" src="https://ui-avatars.com/api/?name=Jane+Smith&background=ef4444&color=ffffff" alt="">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900">Jane Smith</h4>
                                    <p class="text-sm text-gray-500">jane.smith@katana.com</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Manager</span>
                                    </div>
                                </div>
                            </div>
                            <button onclick="confirmDeleteUser('USR-002', 'Jane Smith')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-trash text-lg"></i>
                            </button>
                        </div>
                        <div class="mt-3 text-sm">
                            <div>
                                <span class="text-gray-500">Departemen:</span>
                                <span class="font-medium text-gray-900 ml-1">Marketing</span>
                            </div>
                        </div>
                    </div>

                    <!-- User Card 3 -->
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img class="h-12 w-12 rounded-full border-2 border-gray-200" src="https://ui-avatars.com/api/?name=Bob+Johnson&background=ef4444&color=ffffff" alt="">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900">Bob Johnson</h4>
                                    <p class="text-sm text-gray-500">bob.johnson@katana.com</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Staff</span>
                                    </div>
                                </div>
                            </div>
                            <button onclick="confirmDeleteUser('USR-003', 'Bob Johnson')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-trash text-lg"></i>
                            </button>
                        </div>
                        <div class="mt-3 text-sm">
                            <div>
                                <span class="text-gray-500">Departemen:</span>
                                <span class="font-medium text-gray-900 ml-1">Purchasing</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full border-2 border-gray-200" src="https://ui-avatars.com/api/?name=John+Doe&background=ef4444&color=ffffff" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">John Doe</div>
                                        <div class="text-sm text-gray-500">ID: USR-001</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">john.doe@katana.com</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Admin
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">IT</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="confirmDeleteUser('USR-001', 'John Doe')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200" title="Hapus Pengguna">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full border-2 border-gray-200" src="https://ui-avatars.com/api/?name=Jane+Smith&background=ef4444&color=ffffff" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Jane Smith</div>
                                        <div class="text-sm text-gray-500">ID: USR-002</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">jane.smith@katana.com</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Manager
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Marketing</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="confirmDeleteUser('USR-002', 'Jane Smith')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200" title="Hapus Pengguna">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full border-2 border-gray-200" src="https://ui-avatars.com/api/?name=Bob+Johnson&background=ef4444&color=ffffff" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Bob Johnson</div>
                                        <div class="text-sm text-gray-500">ID: USR-003</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">bob.johnson@katana.com</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Staff
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Purchasing</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="confirmDeleteUser('USR-003', 'Bob Johnson')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200" title="Hapus Pengguna">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="text-sm text-gray-700 text-center sm:text-left">
                Menampilkan <span class="font-medium text-red-600">1</span> hingga <span class="font-medium text-red-600">10</span> dari <span class="font-medium text-red-600">42</span> hasil
            </div>
            <div class="flex justify-center sm:justify-end">
                <nav class="flex space-x-1" aria-label="Pagination">
                    <button class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 disabled:opacity-50" disabled>
                        <i class="fas fa-chevron-left"></i>
                        <span class="hidden sm:inline ml-1">Previous</span>
                    </button>
                    <button class="px-3 py-2 text-sm text-white bg-red-600 border border-red-600 rounded-lg shadow-sm">1</button>
                    <button class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">2</button>
                    <button class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">3</button>
                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                    <button class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">5</button>
                    <button class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">
                        <span class="hidden sm:inline mr-1">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl sm:rounded-3xl max-w-lg w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 shadow-2xl" id="addUserModalContent">
        <div class="p-4 sm:p-6 border-b border-gray-200 bg-gradient-to-r from-red-600 to-red-700 rounded-t-2xl sm:rounded-t-3xl">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">Tambah Pengguna Baru</h2>
                    <p class="text-red-100 text-sm mt-1">Isi formulir untuk menambah pengguna</p>
                </div>
                <button onclick="closeAddUserModal()" class="text-white hover:text-red-200 transition-colors duration-200 p-2 hover:bg-white/20 rounded-lg">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <form id="addUserForm" class="p-4 sm:p-6">
            <div class="space-y-4 sm:space-y-5">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="fullName" name="fullName" required
                           class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base"
                           placeholder="Masukkan nama lengkap">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base"
                           placeholder="nama@katana.com">
                </div>

                <!-- Role & Departemen -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                        <select id="role" name="role" required
                                class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base">
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen</label>
                        <select id="department" name="department" required
                                class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base">
                            <option value="">Pilih Departemen</option>
                            <option value="IT">IT</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Purchasing">Purchasing</option>
                            <option value="Finance">Finance</option>
                            <option value="HR">HR</option>
                            <option value="Operations">Operations</option>
                        </select>
                    </div>
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base"
                               placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required
                               class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base"
                               placeholder="Ulangi password">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-6 sm:mt-8">
                <button type="button" onclick="closeAddUserModal()"
                        class="flex-1 px-4 py-3 sm:py-3.5 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200 font-semibold">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 sm:py-3.5 text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 font-semibold shadow-lg transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Tambah Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteUserModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl sm:rounded-3xl max-w-md w-full transform transition-all duration-300 scale-95 shadow-2xl" id="deleteUserModalContent">
        <div class="p-6 sm:p-8 text-center">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl sm:text-3xl"></i>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 sm:mb-3">Hapus Pengguna</h2>
            <p class="text-gray-600 mb-6 sm:mb-8 text-sm sm:text-base leading-relaxed">
                Apakah Anda yakin ingin menghapus pengguna <span id="deleteUserName" class="font-semibold text-gray-800"></span>?<br>
                <span class="text-red-600 font-medium">Tindakan ini tidak dapat dibatalkan.</span>
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="closeDeleteUserModal()"
                        class="flex-1 px-4 py-3 sm:py-3.5 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200 font-semibold">
                    Batal
                </button>
                <button onclick="deleteUser()"
                        class="flex-1 px-4 py-3 sm:py-3.5 text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 font-semibold shadow-lg transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentUserIdToDelete = null;

// Add User Modal Functions
function openAddUserModal() {
    const modal = document.getElementById('addUserModal');
    const modalContent = document.getElementById('addUserModalContent');

    modal.style.display = 'flex';
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 50);

    // Reset form
    document.getElementById('addUserForm').reset();
}

function closeAddUserModal() {
    const modal = document.getElementById('addUserModal');
    const modalContent = document.getElementById('addUserModalContent');

    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');

    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Delete User Modal Functions
function confirmDeleteUser(userId, userName) {
    currentUserIdToDelete = userId;
    document.getElementById('deleteUserName').textContent = userName;

    const modal = document.getElementById('deleteUserModal');
    const modalContent = document.getElementById('deleteUserModalContent');

    modal.style.display = 'flex';
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 50);
}

function closeDeleteUserModal() {
    const modal = document.getElementById('deleteUserModal');
    const modalContent = document.getElementById('deleteUserModalContent');

    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');

    setTimeout(() => {
        modal.style.display = 'none';
        currentUserIdToDelete = null;
    }, 300);
}

function deleteUser() {
    if (!currentUserIdToDelete) return;

    // Here you would typically make an AJAX call to delete the user
    console.log('Deleting user:', currentUserIdToDelete);

    // Show success notification
    showNotification('Pengguna berhasil dihapus!', 'success');

    // Close modal
    closeDeleteUserModal();

    // Refresh page or remove row from table
    setTimeout(() => {
        location.reload();
    }, 1500);
}

// Add User Form Submission
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const password = formData.get('password');
    const confirmPassword = formData.get('confirmPassword');

    // Validate password match
    if (password !== confirmPassword) {
        showNotification('Password tidak cocok!', 'error');
        return;
    }

    // Validate password length
    if (password.length < 8) {
        showNotification('Password minimal 8 karakter!', 'error');
        return;
    }

    // Here you would typically make an AJAX call to add the user
    console.log('Adding user:', Object.fromEntries(formData));

    // Show success notification
    showNotification('Pengguna berhasil ditambahkan!', 'success');

    // Close modal
    closeAddUserModal();

    // Refresh page or add new row to table
    setTimeout(() => {
        location.reload();
    }, 1500);
});

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    notification.classList.add(bgColor, 'text-white');

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Close modals when clicking outside
document.getElementById('addUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddUserModal();
    }
});

document.getElementById('deleteUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteUserModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddUserModal();
        closeDeleteUserModal();
    }
});
</script>
@endsection
