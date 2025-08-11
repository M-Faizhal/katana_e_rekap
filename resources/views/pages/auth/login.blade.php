<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KATANA E-Rekap</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-body">
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="login-logo">
                    <i class="fas fa-sword"></i>
                </div>
                <div class="welcome-text">
                    <h2>Masuk ke KATANA E-Rekap</h2>
                    <p>Gunakan akun yang telah terdaftar</p>
                </div>
            </div>

            <!-- Status Message (Show when needed) -->
            @if(session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="status-message" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" id="loginForm" class="space-y-4">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            autofocus
                            autocomplete="username"
                            class="form-input @error('email') border-red-500 @enderror"
                            placeholder="Masukkan email Anda"
                            value="{{ old('email') }}"
                        >
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <div class="error-message" style="display: flex;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="form-input @error('password') border-red-500 @enderror"
                            placeholder="Masukkan password Anda"
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i id="eye" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-message" style="display: flex;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="form-options">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                    <a href="#" class="forgot-password">Lupa password?</a>
                </div>

                <button type="submit" class="login-button">
                    <span>Masuk</span>
                </button>
            </form>

            <p class="footer-text">© 2024 PT. Kamil Trio Niaga. All rights reserved.</p>
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

        // Add form submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Add loading state
            const form = this;
            const button = form.querySelector('.login-button');
            const buttonText = button.querySelector('span');

            form.classList.add('loading');
            buttonText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        });

        // Add input focus effects
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Add some interactive elements
        document.querySelector('.login-logo').addEventListener('click', function() {
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'pulse 2s infinite';
            }, 10);
        });
    </script>
</body>
</html>
