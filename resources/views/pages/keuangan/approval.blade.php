@extends('layouts.app')

@section('page-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Approval Pembayaran</h1>
    <p class="text-gray-600">Kelola dan approve permintaan pembayaran</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pending Approval</p>
                <p class="text-2xl font-bold text-yellow-600">15</p>
                <p class="text-sm text-yellow-500">
                    <i class="fas fa-clock"></i> Menunggu
                </p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
                <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Approved Today</p>
                <p class="text-2xl font-bold text-green-600">8</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +2 dari kemarin
                </p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Rejected Today</p>
                <p class="text-2xl font-bold text-red-600">2</p>
                <p class="text-sm text-red-500">
                    <i class="fas fa-times-circle"></i> Ditolak
                </p>
            </div>
            <div class="p-3 bg-red-100 rounded-full">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Amount</p>
                <p class="text-2xl font-bold text-blue-600">Rp 125M</p>
                <p class="text-sm text-blue-500">
                    <i class="fas fa-money-bill-wave"></i> Pending
                </p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <h3 class="text-lg font-semibold text-gray-800">Filter Pembayaran</h3>
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option>Semua Status</option>
                    <option>Pending</option>
                    <option>Approved</option>
                    <option>Rejected</option>
                </select>
                <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option>Semua Departemen</option>
                    <option>Marketing</option>
                    <option>Purchasing</option>
                    <option>Operations</option>
                    <option>HR</option>
                </select>
                <input type="text" placeholder="Cari pembayaran..." class="px-3 py-2 border border-gray-300 rounded-md text-sm">
            </div>
        </div>
    </div>
</div>

<!-- Payment Requests Table -->
<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Permintaan Pembayaran</h3>
        <div class="flex space-x-2">
            <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md hover:bg-gray-200 text-sm">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                <i class="fas fa-plus mr-2"></i>Tambah Request
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Request</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#PAY-001</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Ahmad Rizki</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Marketing</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Pembayaran vendor printing</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 5,500,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-green-600 hover:text-green-900" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                            <button class="text-blue-600 hover:text-blue-900" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#PAY-002</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Siti Nurhaliza</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Purchasing</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Pembayaran supplier bahan baku</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 25,000,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">9 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-900" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#PAY-003</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Budi Santoso</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Operations</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Maintenance equipment</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 12,750,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">8 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-green-600 hover:text-green-900" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                            <button class="text-blue-600 hover:text-blue-900" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#PAY-004</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Linda Wijaya</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">HR</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Reimburse training karyawan</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 3,250,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">7 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Rejected
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-orange-600 hover:text-orange-900" title="Resubmit">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="flex items-center">
            <p class="text-sm text-gray-700">
                Showing <span class="font-medium">1</span> to <span class="font-medium">4</span> of <span class="font-medium">25</span> results
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">Previous</button>
            <button class="px-3 py-1 border border-red-300 rounded-md text-sm bg-red-50 text-red-600">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">3</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">Next</button>
        </div>
    </div>
</div>

<!-- Bulk Actions -->
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Bulk Actions</h3>
    <div class="flex flex-wrap gap-4">
        <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm">
            <i class="fas fa-check mr-2"></i>Approve Selected
        </button>
        <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
            <i class="fas fa-times mr-2"></i>Reject Selected
        </button>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
            <i class="fas fa-download mr-2"></i>Export Selected
        </button>
    </div>
</div>
@endsection
