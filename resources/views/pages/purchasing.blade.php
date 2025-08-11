@extends('layouts.dashboard')

@section('page-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Purchasing</h1>
    <p class="text-gray-600">Kelola pembelian dan supplier</p>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <button class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow text-left">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <i class="fas fa-plus text-blue-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Buat PO Baru</h3>
                <p class="text-lg font-bold text-blue-600">Purchase Order</p>
            </div>
        </div>
    </button>

    <button class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow text-left">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <i class="fas fa-truck text-green-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Kelola Supplier</h3>
                <p class="text-lg font-bold text-green-600">Suppliers</p>
            </div>
        </div>
    </button>

    <button class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow text-left">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 mr-4">
                <i class="fas fa-clipboard-list text-purple-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Permintaan</h3>
                <p class="text-lg font-bold text-purple-600">Requests</p>
            </div>
        </div>
    </button>

    <button class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow text-left">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 mr-4">
                <i class="fas fa-file-invoice text-red-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Invoice</h3>
                <p class="text-lg font-bold text-red-600">Billing</p>
            </div>
        </div>
    </button>
</div>

<!-- Purchase Orders Table -->
<div class="bg-white rounded-lg shadow-md mb-8">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Purchase Orders Terbaru</h3>
        <div class="flex space-x-2">
            <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option>Semua Status</option>
                <option>Pending</option>
                <option>Approved</option>
                <option>Delivered</option>
                <option>Cancelled</option>
            </select>
            <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                <i class="fas fa-plus mr-2"></i>Buat PO
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PO-2024-001</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PT. Supplier Utama</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 25,000,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></button>
                        <button class="text-green-600 hover:text-green-900"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PO-2024-002</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CV. Mitra Jaya</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 15,500,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">9 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></button>
                        <button class="text-green-600 hover:text-green-900"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">PO-2024-003</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">UD. Sumber Rezeki</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 8,750,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Delivered
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">8 Agustus 2024</td>
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

<!-- Supplier Management & Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Suppliers -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Top Suppliers</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">PT. Supplier Utama</h4>
                            <p class="text-sm text-gray-500">Rating: 4.8/5</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp 125M</p>
                        <p class="text-sm text-gray-500">Total Order</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">CV. Mitra Jaya</h4>
                            <p class="text-sm text-gray-500">Rating: 4.6/5</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp 98M</p>
                        <p class="text-sm text-gray-500">Total Order</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">UD. Sumber Rezeki</h4>
                            <p class="text-sm text-gray-500">Rating: 4.4/5</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp 67M</p>
                        <p class="text-sm text-gray-500">Total Order</p>
                    </div>
                </div>
            </div>
            <button class="w-full mt-4 text-red-600 hover:text-red-800 text-sm font-medium">
                Lihat semua supplier <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
    </div>

    <!-- Purchase Analytics -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Purchase Analytics</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <!-- Monthly Spending -->
                <div>
                    <h4 class="font-medium text-gray-800 mb-3">Monthly Spending Trend</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Juli 2024</span>
                            <span class="font-medium">Rp 185M</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Agustus 2024</span>
                            <span class="font-medium">Rp 248M</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <!-- Category Breakdown -->
                <div>
                    <h4 class="font-medium text-gray-800 mb-3">Category Breakdown</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Raw Materials</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: 60%"></div>
                                </div>
                                <span class="text-sm font-medium">60%</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Office Supplies</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 25%"></div>
                                </div>
                                <span class="text-sm font-medium">25%</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Equipment</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 15%"></div>
                                </div>
                                <span class="text-sm font-medium">15%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cost Savings -->
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center space-x-2 mb-2">
                        <i class="fas fa-piggy-bank text-green-600"></i>
                        <h4 class="font-medium text-green-800">Cost Savings</h4>
                    </div>
                    <p class="text-2xl font-bold text-green-600">Rp 12.5M</p>
                    <p class="text-sm text-green-600">Penghematan bulan ini</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
