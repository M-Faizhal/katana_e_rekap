@extends('layouts.app')

@section('page-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan</h1>
    <p class="text-gray-600">Kelola dan lihat berbagai laporan bisnis</p>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                <option>Semua Laporan</option>
                <option>Laporan Penjualan</option>
                <option>Laporan Keuangan</option>
                <option>Laporan Inventory</option>
                <option>Laporan Customer</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                <option>Bulan Ini</option>
                <option>3 Bulan Terakhir</option>
                <option>6 Bulan Terakhir</option>
                <option>Tahun Ini</option>
                <option>Custom Range</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                <option>PDF</option>
                <option>Excel</option>
                <option>CSV</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors duration-200">
                <i class="fas fa-search mr-2"></i>Generate Report
            </button>
        </div>
    </div>
</div>

<!-- Reports Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Sales Report -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Penjualan</h3>
                    <p class="text-sm text-gray-500">Data penjualan bulanan</p>
                </div>
            </div>
        </div>
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Transaksi:</span>
                <span class="font-medium">1,234</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Penjualan:</span>
                <span class="font-medium text-green-600">Rp 125,000,000</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Rata-rata per Transaksi:</span>
                <span class="font-medium">Rp 101,295</span>
            </div>
        </div>
        <div class="flex space-x-2">
            <button class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-md text-sm hover:bg-blue-700">
                <i class="fas fa-eye mr-1"></i>Lihat
            </button>
            <button class="flex-1 border border-blue-600 text-blue-600 px-3 py-2 rounded-md text-sm hover:bg-blue-50">
                <i class="fas fa-download mr-1"></i>Download
            </button>
        </div>
    </div>

    <!-- Financial Report -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-coins text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Keuangan</h3>
                    <p class="text-sm text-gray-500">Cash flow dan profit</p>
                </div>
            </div>
        </div>
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Pendapatan:</span>
                <span class="font-medium text-green-600">Rp 125,000,000</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Pengeluaran:</span>
                <span class="font-medium text-red-600">Rp 85,000,000</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Profit Bersih:</span>
                <span class="font-medium text-blue-600">Rp 40,000,000</span>
            </div>
        </div>
        <div class="flex space-x-2">
            <button class="flex-1 bg-green-600 text-white px-3 py-2 rounded-md text-sm hover:bg-green-700">
                <i class="fas fa-eye mr-1"></i>Lihat
            </button>
            <button class="flex-1 border border-green-600 text-green-600 px-3 py-2 rounded-md text-sm hover:bg-green-50">
                <i class="fas fa-download mr-1"></i>Download
            </button>
        </div>
    </div>

    <!-- Inventory Report -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-boxes text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Inventory</h3>
                    <p class="text-sm text-gray-500">Stock dan pergerakan barang</p>
                </div>
            </div>
        </div>
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Produk:</span>
                <span class="font-medium">456</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Stock Tersedia:</span>
                <span class="font-medium text-green-600">12,345 unit</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Stock Menipis:</span>
                <span class="font-medium text-red-600">23 produk</span>
            </div>
        </div>
        <div class="flex space-x-2">
            <button class="flex-1 bg-purple-600 text-white px-3 py-2 rounded-md text-sm hover:bg-purple-700">
                <i class="fas fa-eye mr-1"></i>Lihat
            </button>
            <button class="flex-1 border border-purple-600 text-purple-600 px-3 py-2 rounded-md text-sm hover:bg-purple-50">
                <i class="fas fa-download mr-1"></i>Download
            </button>
        </div>
    </div>

    <!-- Customer Report -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-users text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Customer</h3>
                    <p class="text-sm text-gray-500">Data pelanggan dan loyalitas</p>
                </div>
            </div>
        </div>
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Customer:</span>
                <span class="font-medium">1,245</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Customer Aktif:</span>
                <span class="font-medium text-green-600">892</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Customer Baru:</span>
                <span class="font-medium text-blue-600">124</span>
            </div>
        </div>
        <div class="flex space-x-2">
            <button class="flex-1 bg-yellow-600 text-white px-3 py-2 rounded-md text-sm hover:bg-yellow-700">
                <i class="fas fa-eye mr-1"></i>Lihat
            </button>
            <button class="flex-1 border border-yellow-600 text-yellow-600 px-3 py-2 rounded-md text-sm hover:bg-yellow-50">
                <i class="fas fa-download mr-1"></i>Download
            </button>
        </div>
    </div>

    <!-- Performance Report -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-tachometer-alt text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Performa</h3>
                    <p class="text-sm text-gray-500">KPI dan metrik bisnis</p>
                </div>
            </div>
        </div>
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Conversion Rate:</span>
                <span class="font-medium text-green-600">12.5%</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Customer Retention:</span>
                <span class="font-medium text-blue-600">78.5%</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">ROI:</span>
                <span class="font-medium text-green-600">245%</span>
            </div>
        </div>
        <div class="flex space-x-2">
            <button class="flex-1 bg-red-600 text-white px-3 py-2 rounded-md text-sm hover:bg-red-700">
                <i class="fas fa-eye mr-1"></i>Lihat
            </button>
            <button class="flex-1 border border-red-600 text-red-600 px-3 py-2 rounded-md text-sm hover:bg-red-50">
                <i class="fas fa-download mr-1"></i>Download
            </button>
        </div>
    </div>

    <!-- Tax Report -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-indigo-100 rounded-lg">
                    <i class="fas fa-file-invoice text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Pajak</h3>
                    <p class="text-sm text-gray-500">PPH dan PPN bulanan</p>
                </div>
            </div>
        </div>
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">PPH Terutang:</span>
                <span class="font-medium">Rp 12,500,000</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">PPN Masukan:</span>
                <span class="font-medium">Rp 8,500,000</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">PPN Keluaran:</span>
                <span class="font-medium">Rp 13,750,000</span>
            </div>
        </div>
        <div class="flex space-x-2">
            <button class="flex-1 bg-indigo-600 text-white px-3 py-2 rounded-md text-sm hover:bg-indigo-700">
                <i class="fas fa-eye mr-1"></i>Lihat
            </button>
            <button class="flex-1 border border-indigo-600 text-indigo-600 px-3 py-2 rounded-md text-sm hover:bg-indigo-50">
                <i class="fas fa-download mr-1"></i>Download
            </button>
        </div>
    </div>
</div>

<!-- Recent Reports Table -->
<div class="bg-white rounded-lg shadow-md mt-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Laporan Terbaru</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Laporan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Laporan Penjualan Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sales Report</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">11 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Ready
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></button>
                        <button class="text-green-600 hover:text-green-900"><i class="fas fa-download"></i></button>
                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Laporan Keuangan Q3 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Financial Report</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Processing
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-gray-400"><i class="fas fa-eye"></i></button>
                        <button class="text-gray-400"><i class="fas fa-download"></i></button>
                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Laporan Inventory Juli 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Inventory Report</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">9 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Ready
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></button>
                        <button class="text-green-600 hover:text-green-900"><i class="fas fa-download"></i></button>
                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
