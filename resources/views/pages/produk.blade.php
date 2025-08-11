@extends('layouts.app')

@section('page-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Produk</h1>
    <p class="text-gray-600">Kelola inventory dan produk</p>
</div>

<!-- Product Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <i class="fas fa-boxes text-blue-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Total Produk</h3>
                <p class="text-2xl font-bold text-blue-600">1,456</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +5.2%
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Stock Available</h3>
                <p class="text-2xl font-bold text-green-600">12,345</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +8.1%
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 mr-4">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Low Stock</h3>
                <p class="text-2xl font-bold text-yellow-600">23</p>
                <p class="text-sm text-red-500">
                    <i class="fas fa-arrow-up"></i> +3 items
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 mr-4">
                <i class="fas fa-dollar-sign text-purple-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Total Value</h3>
                <p class="text-2xl font-bold text-purple-600">Rp 2.5B</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +12.4%
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Product Management -->
<div class="bg-white rounded-lg shadow-md mb-8">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Produk</h3>
        <div class="flex space-x-2">
            <div class="relative">
                <input type="text" placeholder="Cari produk..." class="px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
            <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option>Semua Kategori</option>
                <option>Electronics</option>
                <option>Clothing</option>
                <option>Books</option>
                <option>Home & Garden</option>
            </select>
            <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                <i class="fas fa-plus mr-2"></i>Tambah Produk
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-mobile-alt text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">iPhone 15 Pro</p>
                                <p class="text-sm text-gray-500">Apple Smartphone</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">IPH15PRO001</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Electronics</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">25</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 18,999,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            In Stock
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></button>
                        <button class="text-green-600 hover:text-green-900"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-laptop text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">MacBook Air M2</p>
                                <p class="text-sm text-gray-500">Apple Laptop</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">MBAM2001</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Electronics</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 16,999,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Low Stock
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></button>
                        <button class="text-green-600 hover:text-green-900"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-tshirt text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">T-Shirt Premium</p>
                                <p class="text-sm text-gray-500">Cotton Apparel</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">TSH001PRE</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Clothing</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">150</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 299,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            In Stock
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></button>
                        <button class="text-green-600 hover:text-green-900"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-book text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Programming Guide</p>
                                <p class="text-sm text-gray-500">Educational Book</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">BK001PRG</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Books</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">45</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 450,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            In Stock
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></button>
                        <button class="text-green-600 hover:text-green-900"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Inventory Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Selling Products -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Produk Terlaris</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">iPhone 15 Pro</h4>
                            <p class="text-sm text-gray-500">Electronics</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">156 sold</p>
                        <p class="text-sm text-green-500">+12% bulan ini</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tshirt text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">T-Shirt Premium</h4>
                            <p class="text-sm text-gray-500">Clothing</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">289 sold</p>
                        <p class="text-sm text-green-500">+8% bulan ini</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-laptop text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">MacBook Air M2</h4>
                            <p class="text-sm text-gray-500">Electronics</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">67 sold</p>
                        <p class="text-sm text-green-500">+15% bulan ini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Alerts -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Inventory Alerts</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center space-x-3 p-3 bg-red-50 rounded-lg border border-red-200">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-red-800">Stock Kritis</p>
                        <p class="text-sm text-red-600">5 produk memerlukan restock segera</p>
                    </div>
                    <button class="text-red-600 hover:text-red-800">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="flex items-center space-x-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-yellow-800">Expiring Soon</p>
                        <p class="text-sm text-yellow-600">8 produk akan expired dalam 30 hari</p>
                    </div>
                    <button class="text-yellow-600 hover:text-yellow-800">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-blue-800">Incoming Shipment</p>
                        <p class="text-sm text-blue-600">12 produk akan tiba besok</p>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-green-800">High Demand</p>
                        <p class="text-sm text-green-600">3 produk mengalami kenaikan permintaan</p>
                    </div>
                    <button class="text-green-600 hover:text-green-800">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
