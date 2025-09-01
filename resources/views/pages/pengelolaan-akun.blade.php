@extends('layouts.app')

@section('title', 'Pengelolaan Akun - Cyber KATANA')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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
                            <p class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                            <p class="text-sm text-green-600 mt-2 flex items-center">
                                <i class="fas fa-users mr-2"></i>
                                <span>{{ $stats['superadmin'] }} Super Admin, {{ $stats['admin_marketing'] }} Marketing, {{ $stats['admin_purchasing'] }} Purchasing, {{ $stats['admin_keuangan'] }} Keuangan</span>
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
        <form method="GET" action="{{ route('pengelolaan.akun') }}" class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 mb-6 sm:mb-8">
            <div class="flex flex-col space-y-4">
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengguna berdasarkan nama, email, atau username..."
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                    <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                </div>

                <!-- Filter Controls -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    <select name="role" class="px-4 py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base">
                        <option value="">Semua Role</option>
                        <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin_marketing" {{ request('role') == 'admin_marketing' ? 'selected' : '' }}>Admin Marketing</option>
                        <option value="admin_purchasing" {{ request('role') == 'admin_purchasing' ? 'selected' : '' }}>Admin Purchasing</option>
                        <option value="admin_keuangan" {{ request('role') == 'admin_keuangan' ? 'selected' : '' }}>Admin Keuangan</option>
                    </select>
                    
                    <button type="submit" class="px-4 py-3 bg-red-600 text-white rounded-lg sm:rounded-xl hover:bg-red-700 transition-all duration-200 font-semibold">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    
                    <a href="{{ route('pengelolaan.akun') }}" class="px-4 py-3 bg-gray-100 text-gray-700 rounded-lg sm:rounded-xl hover:bg-gray-200 transition-all duration-200 font-semibold text-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Mobile Card View -->
            <div class="block lg:hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Pengguna</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $users->total() }} pengguna terdaftar</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                @if($user->profile_photo_url)
                                    <img class="h-12 w-12 rounded-full border-2 border-gray-200" 
                                         src="{{ $user->profile_photo_url }}" 
                                         alt="{{ $user->nama }}">
                                @else
                                    <div class="h-12 w-12 rounded-full border-2 border-gray-200 bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">
                                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900">{{ $user->nama }}</h4>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        @php
                                            $roleColors = [
                                                'superadmin' => 'bg-red-100 text-red-800',
                                                'admin_marketing' => 'bg-blue-100 text-blue-800',
                                                'admin_purchasing' => 'bg-green-100 text-green-800',
                                                'admin_keuangan' => 'bg-yellow-100 text-yellow-800'
                                            ];
                                            $roleNames = [
                                                'superadmin' => 'Super Admin',
                                                'admin_marketing' => 'Admin Marketing',
                                                'admin_purchasing' => 'Admin Purchasing',
                                                'admin_keuangan' => 'Admin Keuangan'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $roleNames[$user->role] ?? $user->role }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="openEditUserModal({{ $user->id_user }})" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200" title="Edit Pengguna">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                @if($user->id_user !== auth()->user()->id_user)
                                <button onclick="confirmDeleteUser({{ $user->id_user }}, '{{ $user->nama }}')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200" title="Hapus Pengguna">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada pengguna terdaftar.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->profile_photo_url)
                                            <img class="h-10 w-10 rounded-full border-2 border-gray-200"
                                                src="{{ $user->profile_photo_url }}"
                                                alt="{{ $user->nama }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full border-2 border-gray-200 bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ strtoupper(substr($user->nama, 0, 2)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->nama }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $user->id_user }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $roleColors = [
                                        'superadmin' => 'bg-red-100 text-red-800',
                                        'admin_marketing' => 'bg-blue-100 text-blue-800',
                                        'admin_purchasing' => 'bg-green-100 text-green-800',
                                        'admin_keuangan' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                    $roleNames = [
                                        'superadmin' => 'Super Admin',
                                        'admin_marketing' => 'Admin Marketing',
                                        'admin_purchasing' => 'Admin Purchasing',
                                        'admin_keuangan' => 'Admin Keuangan'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $roleNames[$user->role] ?? $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="openEditUserModal({{ $user->id_user }})" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200" title="Edit Pengguna">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($user->id_user !== auth()->user()->id_user)
                                    <button onclick="confirmDeleteUser({{ $user->id_user }}, '{{ $user->nama }}')" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200" title="Hapus Pengguna">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Belum ada pengguna terdaftar.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="text-sm text-gray-700 text-center sm:text-left">
                Menampilkan <span class="font-medium text-red-600">{{ $users->firstItem() ?? 0 }}</span> hingga <span class="font-medium text-red-600">{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-medium text-red-600">{{ $users->total() }}</span> hasil
            </div>
            <div class="flex justify-center sm:justify-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 items-center justify-center p-4" style="display: none;">
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
            @csrf
            <div class="space-y-4 sm:space-y-5">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" required
                           class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base"
                           placeholder="Masukkan nama lengkap">
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" required
                           class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base"
                           placeholder="Masukkan username">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base"
                           placeholder="nama@katana.com">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm sm:text-base">
                        <option value="">Pilih Role</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin_marketing">Admin Marketing</option>
                        <option value="admin_purchasing">Admin Purchasing</option>
                        <option value="admin_keuangan">Admin Keuangan</option>
                    </select>
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
                        <input type="password" id="password_confirmation" name="password_confirmation" required
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
<div id="deleteUserModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 items-center justify-center p-4" style="display: none;">
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

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl sm:rounded-3xl max-w-lg w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 shadow-2xl" id="editUserModalContent">
        <div class="p-4 sm:p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-2xl sm:rounded-t-3xl">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">Edit Pengguna</h2>
                    <p class="text-blue-100 text-sm mt-1">Ubah informasi pengguna</p>
                </div>
                <button onclick="closeEditUserModal()" class="text-white hover:text-blue-200 transition-colors duration-200 p-2 hover:bg-white/20 rounded-lg">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <form id="editUserForm" class="p-4 sm:p-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_user_id" name="user_id">
            <div class="space-y-4 sm:space-y-5">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="edit_nama" name="nama" required
                           class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base"
                           placeholder="Masukkan nama lengkap">
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <input type="text" id="edit_username" name="username" required
                           class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base"
                           placeholder="Masukkan username">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" id="edit_email" name="email" required
                           class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base"
                           placeholder="nama@katana.com">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                    <select id="edit_role" name="role" required
                            class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base">
                        <option value="">Pilih Role</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin_marketing">Admin Marketing</option>
                        <option value="admin_purchasing">Admin Purchasing</option>
                        <option value="admin_keuangan">Admin Keuangan</option>
                    </select>
                </div>

                <!-- Password Fields (Optional) -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                        <span class="text-sm font-medium text-yellow-800">Reset Password (Opsional)</span>
                    </div>
                    <p class="text-xs text-yellow-700 mb-3">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                            <input type="password" id="edit_password" name="password"
                                   class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base"
                                   placeholder="Minimal 8 karakter">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                   class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base"
                                   placeholder="Ulangi password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-6 sm:mt-8">
                <button type="button" onclick="closeEditUserModal()"
                        class="flex-1 px-4 py-3 sm:py-3.5 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200 font-semibold">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 sm:py-3.5 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-semibold shadow-lg transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
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

    fetch(`/pengelolaan-akun/${currentUserIdToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeDeleteUserModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Terjadi kesalahan saat menghapus pengguna!', 'error');
        console.error('Error:', error);
    });
}

// Edit User Modal Functions
function openEditUserModal(userId) {
    const modal = document.getElementById('editUserModal');
    const modalContent = document.getElementById('editUserModalContent');

    modal.style.display = 'flex';
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 50);

    // Fetch user data and populate the form
    fetch(`/pengelolaan-akun/${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                document.getElementById('edit_user_id').value = user.id_user;
                document.getElementById('edit_nama').value = user.nama;
                document.getElementById('edit_username').value = user.username;
                document.getElementById('edit_email').value = user.email;
                document.getElementById('edit_role').value = user.role;

                // Reset password fields
                document.getElementById('edit_password').value = '';
                document.getElementById('edit_password_confirmation').value = '';
            } else {
                showNotification(data.message || 'Terjadi kesalahan saat memuat data pengguna!', 'error');
            }
        })
        .catch(error => {
            showNotification('Terjadi kesalahan saat memuat data pengguna!', 'error');
            console.error('Error:', error);
        });
}

function closeEditUserModal() {
    const modal = document.getElementById('editUserModal');
    const modalContent = document.getElementById('editUserModalContent');

    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');

    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Add User Form Submission
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    
    fetch('{{ route("pengelolaan.akun.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.status === 422) {
            return response.json().then(data => {
                // Handle validation errors
                let errorMessage = 'Validasi gagal:\n';
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        errorMessage += `• ${data.errors[field][0]}\n`;
                    });
                }
                showNotification(errorMessage, 'error');
                throw new Error('Validation failed');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeAddUserModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Terjadi kesalahan saat menambah pengguna!', 'error');
        }
    })
    .catch(error => {
        if (error.message !== 'Validation failed') {
            showNotification('Terjadi kesalahan saat menambah pengguna!', 'error');
            console.error('Error:', error);
        }
    });
});

// Edit User Form Submission
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const userId = document.getElementById('edit_user_id').value;
    
    // Add method spoofing for PUT
    formData.append('_method', 'PUT');
    
    fetch(`/pengelolaan-akun/${userId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.status === 422) {
            return response.json().then(data => {
                // Handle validation errors
                let errorMessage = 'Validasi gagal:\n';
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        errorMessage += `• ${data.errors[field][0]}\n`;
                    });
                }
                showNotification(errorMessage, 'error');
                throw new Error('Validation failed');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeEditUserModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Terjadi kesalahan saat mengedit pengguna!', 'error');
        }
    })
    .catch(error => {
        if (error.message !== 'Validation failed') {
            showNotification('Terjadi kesalahan saat mengedit pengguna!', 'error');
            console.error('Error:', error);
        }
    });
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
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
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

document.getElementById('editUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditUserModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddUserModal();
        closeDeleteUserModal();
        closeEditUserModal();
    }
});
</script>
@endsection
