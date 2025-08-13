@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Kalkulasi Purchasing</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Hitung dan analisis biaya pengadaan</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-calculator text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 sm:p-6 mb-6">
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <div class="relative">
                <select class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Status</option>
                    <option value="proses">Proses</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
            </div>
            <div class="relative">
                <input type="text" placeholder="Cari proyek..." class="border border-gray-300 rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>
</div>

<!-- Projects Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Proyek</h2>
        <p class="text-sm text-gray-600 mt-1">Klik proyek untuk melakukan kalkulasi HPS</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Proyek</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Permintaan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Terpilih</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Sample Data - Replace with actual data from database -->
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="openHpsModal('PRJ001', 'Pengadaan Komputer Kantor', 'PT. ABC Corporation')">
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Pengadaan Komputer Kantor</div>
                        <div class="text-sm text-gray-500">PRJ001</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">PT. ABC Corporation</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15</td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Proses
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">15 Item</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">2 Item</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="event.stopPropagation(); openHpsModal('PRJ001', 'Pengadaan Komputer Kantor', 'PT. ABC Corporation')" 
                                class="text-red-600 hover:text-red-900 mr-3">
                            <i class="fas fa-calculator"></i> Kalkulasi
                        </button>
                    </td>
                </tr>
                
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="openHpsModal('PRJ002', 'Renovasi Gedung Utama', 'CV. XYZ Construction')">
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Renovasi Gedung Utama</div>
                        <div class="text-sm text-gray-500">PRJ002</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">CV. XYZ Construction</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-20</td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <!-- Tidak ada status -->
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">8 Item</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">0 Item</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="event.stopPropagation(); openHpsModal('PRJ002', 'Renovasi Gedung Utama', 'CV. XYZ Construction')" 
                                class="text-red-600 hover:text-red-900 mr-3">
                            <i class="fas fa-calculator"></i> Kalkulasi
                        </button>
                    </td>
                </tr>
                
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="openHpsModal('PRJ003', 'Pengadaan Peralatan Laboratorium', 'PT. Science Equipment')">
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">3</td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Pengadaan Peralatan Laboratorium</div>
                        <div class="text-sm text-gray-500">PRJ003</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">PT. Science Equipment</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-25</td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Proses
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">22 Item</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">5 Item</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="event.stopPropagation(); openHpsModal('PRJ003', 'Pengadaan Peralatan Laboratorium', 'PT. Science Equipment')" 
                                class="text-red-600 hover:text-red-900 mr-3">
                            <i class="fas fa-calculator"></i> Kalkulasi
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span class="font-medium">3</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            1
                        </a>
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include HPS Modal -->
@include('pages.purchasing.kalkulasi-components.hps')

@push('scripts')
<script src="{{ asset('js/modal-functions.js') }}"></script>
@endpush
@endsection
