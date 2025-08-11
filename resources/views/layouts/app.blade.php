<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KATANA E-Rekap') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar-gradient {
            background: linear-gradient(180deg, #8B0000 0%, #DC143C 100%);
        }
        .card-placeholder {
            background: rgba(220, 20, 60, 0.1);
            border: 2px solid #DC143C;
            border-radius: 12px;
        }
        .indonesia-map {
            background-image: url('data:image/svg+xml;charset=utf-8,...');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div id="app" class="min-h-screen flex">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex-1 flex flex-col">
            {{-- Header --}}
            @include('components.header')

            {{-- Content --}}
            <main class="ml-64 pt-[64px] p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
