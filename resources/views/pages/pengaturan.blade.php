@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="mb-4 sm:mb-6">
    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Pengaturan Akun</h1>
    <p class="text-sm sm:text-base text-gray-600">Kelola informasi akun dan data pribadi Anda</p>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
    <div class="flex">
        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
    <div class="flex">
        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<!-- Account Settings -->
<div class="bg-white rounded-lg shadow-md">
    <div class="p-4 sm:p-6">
        <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 sm:space-y-8">
            @csrf
            @method('PUT')

            <!-- Profile Photo Section -->
            <div class="text-center">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Foto Profil</h3>
                <div class="flex flex-col items-center space-y-3 sm:space-y-4">
                    <div class="relative">
                        <img id="preview-image" src="{{ $user->profile_photo_url }}"
                             alt="Profile Photo" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-gray-200">
                        <label for="foto" class="absolute bottom-0 right-0 bg-red-600 text-white p-1.5 sm:p-2 rounded-full cursor-pointer hover:bg-red-700 transition-colors">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </label>
                        <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 text-center px-4">Klik ikon kamera untuk mengubah foto profil</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 sm:gap-8">
                <!-- Account Information -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Informasi Akun</h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                                   class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm sm:text-base @error('nama') border-red-500 @enderror" required>
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                   class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm sm:text-base @error('username') border-red-500 @enderror"
                                   placeholder="Masukkan username (opsional)">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}"
                                   class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed text-sm sm:text-base" readonly>
                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Role</label>
                            <input type="text" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}"
                                   class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed text-sm sm:text-base" readonly>
                            <p class="text-xs text-gray-500 mt-1">Role tidak dapat diubah</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">No. Telepon</label>
                            <input type="tel" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"
                                   class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm sm:text-base @error('no_telepon') border-red-500 @enderror"
                                   placeholder="Contoh: +62 812 3456 7890">
                            @error('no_telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Alamat</label>
                            <textarea name="alamat" rows="3"
                                      class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm sm:text-base resize-none @error('alamat') border-red-500 @enderror"
                                      placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Ubah Password</h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Password Saat Ini</label>
                            @error('current_password')
                            <input type="password" name="current_password"
                                   class="w-full px-3 py-2 sm:py-2.5 border-2 border-red-500 rounded-md focus:ring-red-500 focus:border-red-500 text-sm sm:text-base"
                                   placeholder="Masukkan password saat ini">
                            @else
                            <input type="password" name="current_password"
                                   class="w-full px-3 py-2 sm:py-2.5 border-2 border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm sm:text-base"
                                   placeholder="Masukkan password saat ini">
                            @enderror
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Password Baru</label>
                            @error('password')
                            <input type="password" name="password"
                                   class="w-full px-3 py-2 sm:py-2.5 border-2 border-red-500 rounded-md focus:ring-red-500 focus:border-red-500 text-sm sm:text-base"
                                   placeholder="Masukkan password baru">
                            @else
                            <input type="password" name="password"
                                   class="w-full px-3 py-2 sm:py-2.5 border-2 border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm sm:text-base"
                                   placeholder="Masukkan password baru">
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter, kombinasi huruf besar, kecil, dan angka</p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-3 py-2 sm:py-2.5 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm sm:text-base"
                                   placeholder="Ulangi password baru">
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            <div class="flex flex-col sm:flex-row">
                                <svg class="w-5 h-5 text-yellow-400 mr-0 sm:mr-2 mb-2 sm:mb-0 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                <button type="button" onclick="resetForm()" class="w-full sm:w-auto px-4 py-2 sm:py-2.5 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors text-sm sm:text-base min-h-[44px] sm:min-h-[40px]">
                    Reset
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 py-2 sm:py-2.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm sm:text-base min-h-[44px] sm:min-h-[40px]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('foto').addEventListener('change', function(e) {
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
</script>

@endsection
