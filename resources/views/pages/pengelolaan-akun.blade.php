@extends('layouts.app')

@section('title', 'Pengelolaan Akun')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-red-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Pengelolaan Akun</h1>
                    <p class="mt-1 text-sm text-gray-600">Kelola akun pengguna dan hak akses sistem</p>
                </div>
                <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                    <i class="fas fa-user-plus w-4"></i>
                    <span>Tambah Pengguna</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                        <p class="text-2xl font-bold text-gray-900">42</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pengguna Aktif</p>
                        <p class="text-2xl font-bold text-gray-900">38</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                        <p class="text-2xl font-bold text-gray-900">3</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-times text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pengguna Nonaktif</p>
                        <p class="text-2xl font-bold text-gray-900">4</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Cari pengguna..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="staff">Staff</option>
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                        <option value="pending">Menunggu</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    <button class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Login</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=John+Doe&background=ef4444&color=ffffff" alt="">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2 jam yang lalu</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-900" title="Edit Pengguna">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-yellow-600 hover:text-yellow-900" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900" title="Nonaktifkan">
                                        <i class="fas fa-user-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Jane+Smith&background=ef4444&color=ffffff" alt="">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1 hari yang lalu</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-900" title="Edit Pengguna">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-yellow-600 hover:text-yellow-900" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900" title="Nonaktifkan">
                                        <i class="fas fa-user-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Bob+Johnson&background=ef4444&color=ffffff" alt="">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3 hari yang lalu</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Menunggu
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-900" title="Edit Pengguna">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-blue-600 hover:text-blue-900" title="Aktivasi">
                                        <i class="fas fa-user-check"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- More rows would be added here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">1</span> hingga <span class="font-medium">10</span> dari <span class="font-medium">42</span> hasil
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-sm text-gray-500 bg-white border border-gray-300 rounded hover:bg-gray-50">Previous</button>
                <button class="px-3 py-1 text-sm text-white bg-red-600 border border-red-600 rounded">1</button>
                <button class="px-3 py-1 text-sm text-gray-500 bg-white border border-gray-300 rounded hover:bg-gray-50">2</button>
                <button class="px-3 py-1 text-sm text-gray-500 bg-white border border-gray-300 rounded hover:bg-gray-50">3</button>
                <button class="px-3 py-1 text-sm text-gray-500 bg-white border border-gray-300 rounded hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection
