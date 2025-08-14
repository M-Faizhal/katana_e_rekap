@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50 flex items-center justify-center">
    <div class="max-w-lg w-full mx-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8 text-center">
            <!-- Error Icon -->
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-lock text-red-600 text-3xl"></i>
            </div>

            <!-- Error Message -->
            <h1 class="text-3xl font-bold text-gray-800 mb-3">Akses Ditolak</h1>
            <p class="text-gray-600 mb-6 leading-relaxed">
                {{ $exception->getMessage() ?: 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.' }}
            </p>

            <!-- User Info -->
            @auth
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Pengguna:</span> {{ auth()->user()->nama }}<br>
                    <span class="font-medium">Role:</span> {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                </p>
            </div>
            @endauth

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('dashboard') }}"
                   class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-200 font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-home"></i>
                    <span>Kembali ke Dashboard</span>
                </a>

                <button onclick="history.back()"
                        class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-all duration-200 font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </button>
            </div>

            <!-- Help Text -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator sistem.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
