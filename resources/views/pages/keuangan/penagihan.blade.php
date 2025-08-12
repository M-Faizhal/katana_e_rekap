@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Penagihan Dinas</h1>
    <p class="text-gray-600">Kelola penagihan dan invoice untuk client</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Outstanding Invoice</p>
                <p class="text-2xl font-bold text-yellow-600">23</p>
                <p class="text-sm text-yellow-500">
                    <i class="fas fa-file-invoice"></i> Belum bayar
                </p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
                <i class="fas fa-file-invoice text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Paid This Month</p>
                <p class="text-2xl font-bold text-green-600">45</p>
                <p class="text-sm text-green-500">
                    <i class="fas fa-arrow-up"></i> +12 dari bulan lalu
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
                <p class="text-sm text-gray-500">Overdue</p>
                <p class="text-2xl font-bold text-red-600">7</p>
                <p class="text-sm text-red-500">
                    <i class="fas fa-exclamation-triangle"></i> Terlambat
                </p>
            </div>
            <div class="p-3 bg-red-100 rounded-full">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Outstanding</p>
                <p class="text-2xl font-bold text-blue-600">Rp 285M</p>
                <p class="text-sm text-blue-500">
                    <i class="fas fa-money-bill-wave"></i> Amount
                </p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Form Input Penagihan Dinas -->
<div class="bg-white rounded-lg shadow-md mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Input Penagihan Dinas</h3>
        <p class="text-sm text-gray-600">Masukkan data invoice, SPJ, faktur pajak, dan uang masuk</p>
    </div>
    <form class="p-6 space-y-6">
        <!-- Invoice/Kwitansi Section -->
        <div class="border-l-4 border-red-500 pl-4">
            <h4 class="text-md font-semibold text-gray-800 mb-4">
                <i class="fas fa-file-invoice text-red-500 mr-2"></i>Invoice/Kwitansi
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Invoice</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="INV-2024-001">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Invoice</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Client</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Pilih Client</option>
                        <option value="PT. ABC Corporation">PT. ABC Corporation</option>
                        <option value="CV. XYZ Trading">CV. XYZ Trading</option>
                        <option value="PT. Global Solutions">PT. Global Solutions</option>
                        <option value="PT. Tech Innovation">PT. Tech Innovation</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Invoice</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="25000000">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Pekerjaan</label>
                    <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Jasa konsultasi marketing..."></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Invoice</label>
                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>

        <!-- Surat Pertanggung Jawaban Section -->
        <div class="border-l-4 border-blue-500 pl-4">
            <h4 class="text-md font-semibold text-gray-800 mb-4">
                <i class="fas fa-file-alt text-blue-500 mr-2"></i>Surat Pertanggung Jawaban (SPJ)
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor SPJ</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="SPJ-2024-001">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal SPJ</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penanggung Jawab</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nama lengkap penanggung jawab">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Pertanggungjawaban</label>
                    <input type="month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan SPJ</label>
                    <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Pertanggungjawaban atas pelaksanaan kegiatan..."></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File SPJ</label>
                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" accept=".pdf,.doc,.docx">
                </div>
            </div>
        </div>

        <!-- Faktur Pajak Section -->
        <div class="border-l-4 border-green-500 pl-4">
            <h4 class="text-md font-semibold text-gray-800 mb-4">
                <i class="fas fa-receipt text-green-500 mr-2"></i>Faktur Pajak
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Faktur Pajak</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="010.000-24.00000001">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Faktur Pajak</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NPWP Penjual</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="00.000.000.0-000.000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NPWP Pembeli</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="00.000.000.0-000.000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">DPP (Dasar Pengenaan Pajak)</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="22727273">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PPN (11%)</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="2500000" readonly>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Faktur Pajak</label>
                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>

        <!-- Uang Masuk Section -->
        <div class="border-l-4 border-purple-500 pl-4">
            <h4 class="text-md font-semibold text-gray-800 mb-4">
                <i class="fas fa-money-bill-wave text-purple-500 mr-2"></i>Uang Masuk
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Transaksi</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="TRX-2024-001">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembayaran</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Diterima</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="25000000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Pilih Metode</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="cash">Tunai</option>
                        <option value="cek">Cek/Giro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Penerima</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Pilih Bank</option>
                        <option value="mandiri">Bank Mandiri</option>
                        <option value="bca">Bank BCA</option>
                        <option value="bni">Bank BNI</option>
                        <option value="bri">Bank BRI</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="1234567890">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Pembayaran</label>
                    <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Pembayaran untuk invoice INV-2024-001..."></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <button type="button" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Batal
            </button>
            <button type="button" class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Simpan Draft
            </button>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                Simpan & Submit
            </button>
        </div>
    </form>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md mb-6 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button class="flex items-center justify-center p-4 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition-all">
            <div class="text-center">
                <i class="fas fa-plus-circle text-red-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-red-800">Buat Invoice Baru</p>
            </div>
        </button>
        <button class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition-all">
            <div class="text-center">
                <i class="fas fa-bell text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-blue-800">Send Reminder</p>
            </div>
        </button>
        <button class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition-all">
            <div class="text-center">
                <i class="fas fa-download text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-green-800">Export Report</p>
            </div>
        </button>
    </div>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <h3 class="text-lg font-semibold text-gray-800">Filter Invoice</h3>
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option>Semua Status</option>
                    <option>Draft</option>
                    <option>Sent</option>
                    <option>Paid</option>
                    <option>Overdue</option>
                    <option>Cancelled</option>
                </select>
                <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option>Semua Client</option>
                    <option>PT. ABC Corporation</option>
                    <option>CV. XYZ Trading</option>
                    <option>PT. Global Solutions</option>
                    <option>PT. Tech Innovation</option>
                </select>
                <input type="date" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <input type="text" placeholder="Cari invoice..." class="px-3 py-2 border border-gray-300 rounded-md text-sm">
            </div>
        </div>
    </div>
</div>

<!-- Invoices Table -->
<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Invoice</h3>
        <div class="flex space-x-2">
            <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md hover:bg-gray-200 text-sm">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                <i class="fas fa-plus mr-2"></i>Buat Invoice
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#INV-2024-001</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PT. ABC Corporation</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jasa konsultasi marketing</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 25,000,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Sent
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-purple-600 hover:text-purple-900" title="Send">
                                <i class="fas fa-paper-plane"></i>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#INV-2024-002</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CV. XYZ Trading</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Supply produk elektronik</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 45,750,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Paid
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-900" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-900" title="Receipt">
                                <i class="fas fa-receipt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 bg-red-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#INV-2024-003</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PT. Global Solutions</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jasa maintenance sistem</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 18,500,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500">5 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Overdue
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-yellow-600 hover:text-yellow-900" title="Send Reminder">
                                <i class="fas fa-bell"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Mark as Bad Debt">
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#INV-2024-004</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PT. Tech Innovation</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Development website</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 35,000,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">25 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Draft
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-purple-600 hover:text-purple-900" title="Send">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300 text-red-600">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#INV-2024-005</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PT. Digital Media</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Campaign advertising</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp 12,800,000</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">18 Agustus 2024</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Sent
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-purple-600 hover:text-purple-900" title="Send Reminder">
                                <i class="fas fa-bell"></i>
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
                Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">23</span> results
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

<!-- Collection Activities -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Payment Reminders -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Payment Reminders</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-bell text-yellow-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">PT. ABC Corporation</h4>
                            <p class="text-sm text-gray-500">#INV-2024-001 - Rp 25,000,000</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-yellow-600 font-medium">Due in 8 days</p>
                        <button class="text-xs text-yellow-700 hover:text-yellow-900">Send Reminder</button>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">PT. Global Solutions</h4>
                            <p class="text-sm text-gray-500">#INV-2024-003 - Rp 18,500,000</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-red-600 font-medium">7 days overdue</p>
                        <button class="text-xs text-red-700 hover:text-red-900">Take Action</button>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">PT. Digital Media</h4>
                            <p class="text-sm text-gray-500">#INV-2024-005 - Rp 12,800,000</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-600 font-medium">Due in 6 days</p>
                        <button class="text-xs text-blue-700 hover:text-blue-900">Schedule Reminder</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection Performance -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Collection Performance</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <!-- Collection Ratio -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Collection Rate</span>
                        <span class="font-medium">92.5%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: 92.5%"></div>
                    </div>
                </div>

                <!-- Average Collection Time -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">28</p>
                        <p class="text-sm text-gray-600">Avg Days to Collect</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">3.2%</p>
                        <p class="text-sm text-gray-600">Bad Debt Rate</p>
                    </div>
                </div>

                <!-- Monthly Performance -->
                <div>
                    <h4 class="font-medium text-gray-800 mb-3">This Month</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Collected:</span>
                            <span class="font-medium text-green-600">Rp 385M</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Outstanding:</span>
                            <span class="font-medium text-yellow-600">Rp 285M</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Overdue:</span>
                            <span class="font-medium text-red-600">Rp 42M</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
