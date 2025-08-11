<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KATANA E-Rekap') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom styles for KATANA theme */
        .sidebar-gradient {
            background: linear-gradient(180deg, #8B0000 0%, #DC143C 100%);
        }

        .card-placeholder {
            background: rgba(220, 20, 60, 0.1);
            border: 2px solid #DC143C;
            border-radius: 12px;
        }

        .indonesia-map {
            background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 400"%3E%3Cpath d="M100 200 Q200 150 300 200 T500 200 Q600 250 700 200 T900 200" fill="%2300CED1" stroke="%23008B8B" stroke-width="2"/%3E%3C/svg%3E');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div id="app" class="min-h-screen">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
