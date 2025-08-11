<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'KATANA E-Rekap') }} • Masuk</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Masuk ke {{ config('app.name', 'KATANA E-Rekap') }} - Sistem Rekapitulasi Digital">
    <meta name="keywords" content="katana, e-rekap, login, pt kamil trio niaga">
    <meta name="author" content="PT. Kamil Trio Niaga">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('head')
</head>
<body class="font-sans antialiased selection:bg-red-100 selection:text-red-800">
    <main class="min-h-screen overflow-hidden">
        @yield('content')
    </main>

    @stack('scripts')
    @stack('modals')
</body>
</html>
</html>
