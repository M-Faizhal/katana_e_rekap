@extends('layouts.dashboard')

@section('page-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Marketing</h1>
    <p class="text-gray-600">Kelola kampanye marketing dan analisis performa</p>
</div>

<!-- Marketing Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <i class="fas fa-eye text-blue-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Total Views</h3>
                <p class="text-2xl font-bold text-blue-600">45,234</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <i class="fas fa-mouse-pointer text-green-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Click Rate</h3>
                <p class="text-2xl font-bold text-green-600">12.8%</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 mr-4">
                <i class="fas fa-shopping-cart text-purple-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Conversion</h3>
                <p class="text-2xl font-bold text-purple-600">3.2%</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 mr-4">
                <i class="fas fa-dollar-sign text-red-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">ROI Marketing</h3>
                <p class="text-2xl font-bold text-red-600">324%</p>
            </div>
        </div>
    </div>
</div>

<!-- Campaign Management -->
<div class="bg-white rounded-lg shadow-md mb-8">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Kampanye Aktif</h3>
        <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
            <i class="fas fa-plus mr-2"></i>Buat Kampanye
        </button>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Campaign 1 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-800">Flash Sale Agustus</h4>
                        <p class="text-sm text-gray-500">Email Campaign • Started 5 days ago</p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                <div class="grid grid-cols-3 gap-4 mb-3">
                    <div class="text-center">
                        <p class="text-lg font-bold text-blue-600">12.5K</p>
                        <p class="text-xs text-gray-500">Sent</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-green-600">3.8K</p>
                        <p class="text-xs text-gray-500">Opened</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-purple-600">456</p>
                        <p class="text-xs text-gray-500">Clicked</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 text-blue-600 border border-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-50">
                        Edit
                    </button>
                    <button class="flex-1 text-gray-600 border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-50">
                        Analisis
                    </button>
                </div>
            </div>

            <!-- Campaign 2 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-800">Social Media Ads</h4>
                        <p class="text-sm text-gray-500">Facebook & Instagram • Started 2 days ago</p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                <div class="grid grid-cols-3 gap-4 mb-3">
                    <div class="text-center">
                        <p class="text-lg font-bold text-blue-600">45.2K</p>
                        <p class="text-xs text-gray-500">Impressions</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-green-600">1.8K</p>
                        <p class="text-xs text-gray-500">Clicks</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-purple-600">89</p>
                        <p class="text-xs text-gray-500">Conversions</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 text-blue-600 border border-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-50">
                        Edit
                    </button>
                    <button class="flex-1 text-gray-600 border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-50">
                        Analisis
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Management -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Content Calendar -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Kalender Konten</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">Post Instagram - Produk Baru</p>
                        <p class="text-xs text-gray-500">Hari ini, 14:00</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">Email Newsletter</p>
                        <p class="text-xs text-gray-500">Besok, 09:00</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg">
                    <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">Blog Post - Tips & Tricks</p>
                        <p class="text-xs text-gray-500">13 Agustus, 10:00</p>
                    </div>
                </div>
            </div>
            <button class="w-full mt-4 text-red-600 hover:text-red-800 text-sm font-medium">
                Lihat semua <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
    </div>

    <!-- Customer Insights -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Customer Insights</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Usia 18-25</span>
                        <span class="font-medium">35%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 35%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Usia 26-35</span>
                        <span class="font-medium">45%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Usia 36+</span>
                        <span class="font-medium">20%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 20%"></div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200">
                <h4 class="font-medium text-gray-800 mb-3">Top Channels</h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Social Media</span>
                        <span class="font-medium">42%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Email</span>
                        <span class="font-medium">28%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Search</span>
                        <span class="font-medium">30%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
