<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Cyber KATANA'))</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased overflow-hidden">
    <div id="app" class="h-screen flex flex-col">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Mobile top bar (hamburger) — hanya tampil di layar kecil --}}
        <div class="lg:hidden flex-shrink-0 flex items-center px-3 py-2 bg-white border-b border-gray-200 shadow-sm z-30">
            <button id="mobileMenuToggle" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <i class="fas fa-bars text-lg"></i>
            </button>
        </div>

        {{-- Content: area di sebelah sidebar, full height sisa --}}
        <main class="lg:ml-64 flex flex-col flex-1 overflow-hidden">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

    <script>
    // Mobile sidebar toggle untuk layout chat (tanpa header global)
    document.addEventListener('DOMContentLoaded', function () {
        const toggle  = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('mobileOverlay');

        if (toggle) {
            toggle.addEventListener('click', function () {
                if (sidebar) sidebar.classList.toggle('-translate-x-full');
                if (overlay) overlay.classList.toggle('hidden');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', function () {
                if (sidebar) sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    });
    </script>
</body>
</html>
