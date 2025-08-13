@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Keuangan</h1>
    <p class="text-gray-600">Kelola keuangan dan cash flow perusahaan</p>
</div>

<!-- Financial Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Revenue</p>
                <p class="text-2xl font-bold text-green-600">Rp 825M</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +15.2%
                </p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Expenses</p>
                <p class="text-2xl font-bold text-red-600">Rp 582M</p>
                <p class="text-sm text-red-500">
                    <i class="fas fa-arrow-up"></i> +8.1%
                </p>
            </div>
            <div class="p-3 bg-red-100 rounded-full">
                <i class="fas fa-chart-line-down text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Net Profit</p>
                <p class="text-2xl font-bold text-blue-600">Rp 243M</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +24.3%
                </p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="fas fa-coins text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Cash Balance</p>
                <p class="text-2xl font-bold text-purple-600">Rp 156M</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +5.7%
                </p>
            </div>
            <div class="p-3 bg-purple-100 rounded-full">
                <i class="fas fa-wallet text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Financial Charts and Accounts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Cash Flow Chart -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Cash Flow</h3>
                <select class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                    <option>6 Bulan Terakhir</option>
                    <option>3 Bulan Terakhir</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
        </div>
        <div class="p-6">
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-chart-area text-blue-500 text-4xl mb-4"></i>
                    <p class="text-gray-500">Cash Flow Chart</p>
                    <p class="text-sm text-gray-400">Chart akan ditampilkan di sini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Balances -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Account Balances</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-university text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Bank BCA</h4>
                            <p class="text-sm text-gray-500">****1234</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp 85,500,000</p>
                        <p class="text-sm text-green-500">Active</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-university text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Bank Mandiri</h4>
                            <p class="text-sm text-gray-500">****5678</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp 42,750,000</p>
                        <p class="text-sm text-green-500">Active</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Kas Kecil</h4>
                            <p class="text-sm text-gray-500">Petty Cash</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp 5,250,000</p>
                        <p class="text-sm text-green-500">Available</p>
                    </div>
                </div>
            </div>
            <button class="w-full mt-4 text-red-600 hover:text-red-800 text-sm font-medium">
                Kelola akun <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-lg shadow-md mb-8">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
        <div class="flex space-x-2">
            <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option>Semua Transaksi</option>
                <option>Income</option>
                <option>Expense</option>
                <option>Transfer</option>
            </select>
            <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                <i class="fas fa-plus mr-2"></i>Tambah Transaksi
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">11 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Pembayaran dari Customer ABC</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sales Revenue</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bank BCA</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">+Rp 15,500,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Pembayaran ke Supplier XYZ</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Purchase</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bank Mandiri</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">-Rp 8,750,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">9 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Gaji Karyawan</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Payroll</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bank BCA</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">-Rp 25,000,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">8 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Pembayaran Listrik & Air</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Utilities</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Kas Kecil</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">-Rp 2,500,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Budget vs Actual -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Budget Overview -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Budget vs Actual</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Sales Revenue</span>
                        <span class="font-medium">85% of budget</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: 85%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Rp 825M</span>
                        <span>Budget: Rp 970M</span>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Operating Expenses</span>
                        <span class="font-medium">92% of budget</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-yellow-600 h-3 rounded-full" style="width: 92%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Rp 460M</span>
                        <span>Budget: Rp 500M</span>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Marketing Spend</span>
                        <span class="font-medium">76% of budget</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: 76%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Rp 38M</span>
                        <span>Budget: Rp 50M</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Health -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Financial Health</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <!-- Quick Ratios -->
                <div>
                    <h4 class="font-medium text-gray-800 mb-3">Key Ratios</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">2.8</p>
                            <p class="text-sm text-gray-600">Current Ratio</p>
                        </div>
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">29.4%</p>
                            <p class="text-sm text-gray-600">Profit Margin</p>
                        </div>
                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                            <p class="text-2xl font-bold text-purple-600">12.5%</p>
                            <p class="text-sm text-gray-600">ROE</p>
                        </div>
                        <div class="text-center p-3 bg-yellow-50 rounded-lg">
                            <p class="text-2xl font-bold text-yellow-600">18.9%</p>
                            <p class="text-sm text-gray-600">ROA</p>
                        </div>
                    </div>
                </div>

                <!-- Financial Health Score -->
                <div>
                    <h4 class="font-medium text-gray-800 mb-3">Health Score</h4>
                    <div class="relative">
                        <div class="flex items-center space-x-3">
                            <div class="flex-1 bg-gray-200 rounded-full h-4">
                                <div class="bg-green-600 h-4 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-lg font-bold text-green-600">85/100</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Kesehatan keuangan sangat baik</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
