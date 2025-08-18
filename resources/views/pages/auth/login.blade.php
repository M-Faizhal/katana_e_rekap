<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - KATANA E-Rekap</title>
<script src="https://cdn.tailwindcss.com"></script>


  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50">

  <div class="min-h-screen flex">
    
    <!-- Left Panel - Welcome Section -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-red-600 via-red-500 to-red-700 relative overflow-hidden">
      
      <!-- Floating Shapes -->
      <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full animate-pulse"></div>
      <div class="absolute top-40 right-20 w-20 h-20 bg-white/5 rounded-full animate-bounce"></div>
      <div class="absolute bottom-32 left-20 w-24 h-24 bg-white/10 rounded-full animate-ping"></div>
      
      <!-- Content -->
      <div class="flex flex-col justify-center items-start p-12 z-10 text-white">
        
       <!-- Logo -->
            <div class="flex items-center mb-8">
            <div class="w-36 h-36 bg-white rounded-full flex items-center justify-center mr-3 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Logo KATANA" class="w-36 h-36 object-contain">
            </div>
            <span class="text-4xl font-bold">KATANA E-Rekap</span>
            </div>

            <h1 class="text-7xl font-bold mb-4 leading-tight">
            Selamat Datang!
            </h1>

        
      
        
        
      </div>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
      
      <div class="w-full max-w-md">
        
        <!-- Mobile Logo -->
        <div class="lg:hidden text-center mb-8">
          <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-white text-white text-3xl shadow-lg mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo KATANA" class="w-16 h-16 object-contain">
          </div>
          <h2 class="text-2xl font-bold text-gray-800">KATANA E-Rekap</h2>
        </div>

        <!-- Welcome Text -->
        <div class="mb-8">
          <h3 class="text-2xl font-bold text-gray-800 mb-2">Masuk ke Akun Anda</h3>
        </div>

        <!-- Status Messages -->
        @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm">
          <i class="fas fa-exclamation-circle mr-2"></i>
          <span>{{ $errors->first() }}</span>
        </div>
        @endif

        @if (session('success'))
        <div class="mb-4 p-4 rounded-xl border border-green-200 bg-green-50 text-green-700 text-sm">
          <i class="fas fa-check-circle mr-2"></i>
          <span>{{ session('success') }}</span>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-4 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm">
          <i class="fas fa-exclamation-circle mr-2"></i>
          <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-6">
          @csrf
          
          <!-- Email/Username -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email / Username
            </label>
            <div class="relative">
              <input
                id="email"
                name="email"
                type="text"
                required
                autofocus
                value="{{ old('email') }}"
                placeholder="Masukkan email atau username"
                class="w-full rounded-xl border border-gray-300 pl-12 pr-4 py-3 text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('email') border-red-500 @enderror"
              >
              <div class="absolute left-4 top-1/2 -translate-y-1/2">
                <i class="fas fa-user text-gray-400"></i>
              </div>
            </div>
            @error('email')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Password
            </label>
            <div class="relative">
              <input
                id="password"
                name="password"
                type="password"
                required
                placeholder="Masukkan password"
                class="w-full rounded-xl border border-gray-300 pl-12 pr-12 py-3 text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('password') border-red-500 @enderror"
              >
              <div class="absolute left-4 top-1/2 -translate-y-1/2">
                <i class="fas fa-lock text-gray-400"></i>
              </div>
              <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                <i id="eye" class="fas fa-eye"></i>
              </button>
            </div>
            @error('password')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>

          <!-- Remember Me -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember"
                name="remember"
                type="checkbox"
                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
              >
              <label for="remember" class="ml-2 block text-sm text-gray-700">
                Ingat saya
              </label>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Masuk
          </button>
        </form>

        <!-- Divider -->
        <div class="my-8 flex items-center">
          <div class="flex-1 border-t border-gray-200"></div>
          <div class="flex-1 border-t border-gray-200"></div>
        </div>

        

        <!-- Footer -->
        <div class="mt-8 text-center">
          <p class="text-xs text-gray-500">
            © 2025 PT. Kamil Tria Niaga. All rights reserved.
          </p>
         
        </div>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const eye = document.getElementById('eye');
      if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
      }
    }

    // Form submission handling
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      const submitButton = form.querySelector('button[type="submit"]');
      
      form.addEventListener('submit', function() {
        // Disable submit button to prevent double submission
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
      });

      // Add interactive feedback for inputs
      const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.classList.add('ring-2', 'ring-red-500');
        });
        
        input.addEventListener('blur', function() {
          this.parentElement.classList.remove('ring-2', 'ring-red-500');
        });
      });
    });
  </script>
</body>
</html>