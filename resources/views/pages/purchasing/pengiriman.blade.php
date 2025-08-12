@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Pengiriman Purchasing</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan pantau pengiriman barang</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-truck text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Content Card -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
    <div class="text-center py-8 sm:py-12 lg:py-16">
        <i class="fas fa-truck text-4xl sm:text-5xl lg:text-6xl text-gray-400 mb-4"></i>
        <h2 class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-600 mb-2">Halaman Pengiriman</h2>
        <p class="text-sm sm:text-base text-gray-500">Fitur pengiriman purchasing akan segera tersedia</p>
    </div>
</div>
@endsection
