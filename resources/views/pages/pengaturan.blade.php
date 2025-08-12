@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Akun</h1>
    <p class="text-gray-600">Kelola informasi akun dan data pribadi Anda</p>
</div>

<!-- Account Settings -->
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6">
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Profile Photo Section -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Foto Profil</h3>
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        @php
                            // Dummy data untuk testing
                            $user = Auth::user() ?? (object) [
                                'name' => 'John Doe',
                                'email' => 'john.doe@katana.co.id',
                                'role' => 'Administrator',
                                'phone' => '+62 812 3456 7890',
                                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat 10110',
                                'profile_photo' => null
                            ];
                        @endphp
                        <img id="preview-image" src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://via.placeholder.com/120x120/ef4444/ffffff?text=' . substr($user->name, 0, 1) }}" 
                             alt="Profile Photo" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                        <label for="profile_photo" class="absolute bottom-0 right-0 bg-red-600 text-white p-2 rounded-full cursor-pointer hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="hidden">
                    </div>
                    <p class="text-sm text-gray-500">Klik ikon kamera untuk mengubah foto profil</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Account Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" readonly>
                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <input type="text" value="{{ $user->role ?? 'User' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" readonly>
                            <p class="text-xs text-gray-500 mt-1">Role tidak dapat diubah</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                            <input type="tel" name="phone" value="{{ $user->phone ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" 
                                   placeholder="Contoh: +62 812 3456 7890">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea name="address" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" 
                                      placeholder="Masukkan alamat lengkap">{{ $user->address ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" 
                                   placeholder="Masukkan password saat ini">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" 
                                   placeholder="Masukkan password baru">
                            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" 
                                   placeholder="Ulangi password baru">
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-yellow-700">
                                    <p class="font-medium">Catatan Keamanan:</p>
                                    <p>Kosongkan field password jika tidak ingin mengubah password</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <button type="button" onclick="resetForm()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    Reset
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
        location.reload();
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.querySelector('input[name="password"]').value;
    const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
    const currentPassword = document.querySelector('input[name="current_password"]').value;
    
    if (password && password !== confirmPassword) {
        e.preventDefault();
        alert('Password baru dan konfirmasi password tidak cocok!');
        return;
    }
    
    if (password && !currentPassword) {
        e.preventDefault();
        alert('Harap masukkan password saat ini untuk mengubah password!');
        return;
    }
    
    if (password && password.length < 8) {
        e.preventDefault();
        alert('Password baru minimal 8 karakter!');
        return;
    }
});
</script>

@endsection
