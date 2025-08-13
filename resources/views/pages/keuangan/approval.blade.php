@extends('layouts.app')

@section('content')

@php
// Data pembayaran yang menunggu approval dari purchasing
$pembayaranMenungguApproval = [
    [
        'id' => 3,
        'proyek_id' => 1,
        'kode_proyek' => 'PNW-2024-001',
        'nama_proyek' => 'Sistem Informasi Manajemen',
        'nama_instansi' => 'Dinas Pendidikan DKI',
        'jenis_pembayaran' => 'DP',
        'nominal' => 255000000,
        'tanggal_bayar' => '2024-11-15',
        'metode_pembayaran' => 'Transfer Bank',
        'status' => 'menunggu_approval',
        'bukti_pembayaran' => 'bukti_dp_pnw2024001.pdf',
        'catatan' => 'Pembayaran DP 30% dari total nilai proyek',
        'admin_input' => 'Maya Indah',
        'tanggal_input' => '2024-11-15 09:30:00',
        'total_nilai_proyek' => 850000000,
        'persentase_dp' => 30,
        'bank_pengirim' => 'BNI',
        'rekening_pengirim' => '****1234',
        'nomor_referensi' => 'TRF202411150930001'
    ],
    [
        'id' => 4,
        'proyek_id' => 6,
        'kode_proyek' => 'PNW-2024-006',
        'nama_proyek' => 'Aplikasi Smart City',
        'nama_instansi' => 'Pemkot Medan',
        'jenis_pembayaran' => 'Lunas',
        'nominal' => 1200000000,
        'tanggal_bayar' => '2024-11-16',
        'metode_pembayaran' => 'Transfer Bank',
        'status' => 'menunggu_approval',
        'bukti_pembayaran' => 'bukti_lunas_pnw2024006.pdf',
        'catatan' => 'Pembayaran lunas langsung (tanpa DP)',
        'admin_input' => 'Sari Wijaya',
        'tanggal_input' => '2024-11-16 14:15:00',
        'total_nilai_proyek' => 1200000000,
        'persentase_dp' => 0,
        'bank_pengirim' => 'Mandiri',
        'rekening_pengirim' => '****5678',
        'nomor_referensi' => 'TRF202411161415002'
    ],
    [
        'id' => 5,
        'proyek_id' => 2,
        'kode_proyek' => 'PNW-2024-002',
        'nama_proyek' => 'Portal E-Government',
        'nama_instansi' => 'Pemda Bandung',
        'jenis_pembayaran' => 'Lunas',
        'nominal' => 420000000,
        'tanggal_bayar' => '2024-11-17',
        'metode_pembayaran' => 'Transfer Bank',
        'status' => 'menunggu_approval',
        'bukti_pembayaran' => 'bukti_pelunasan_pnw2024002.pdf',
        'catatan' => 'Pelunasan sisa pembayaran (70% dari total)',
        'admin_input' => 'Andi Prasetyo',
        'tanggal_input' => '2024-11-17 11:45:00',
        'total_nilai_proyek' => 600000000,
        'persentase_dp' => 30,
        'bank_pengirim' => 'BCA',
        'rekening_pengirim' => '****9012',
        'nomor_referensi' => 'TRF202411171145003'
    ]
];

// Data riwayat approval yang sudah diproses
$riwayatApproval = [
    [
        'id' => 1,
        'proyek_id' => 4,
        'kode_proyek' => 'PNW-2024-004',
        'nama_proyek' => 'Dashboard Analytics Daerah',
        'jenis_pembayaran' => 'DP',
        'nominal' => 276000000,
        'status' => 'approved',
        'admin_approval' => 'Budi Keuangan',
        'tanggal_approval' => '2024-11-01 14:15:00',
        'catatan_approval' => 'Bukti pembayaran valid, nominal sesuai kontrak'
    ],
    [
        'id' => 2,
        'proyek_id' => 7,
        'kode_proyek' => 'PNW-2024-007',
        'nama_proyek' => 'Website E-Commerce UMKM',
        'jenis_pembayaran' => 'Lunas',
        'nominal' => 450000000,
        'status' => 'approved',
        'admin_approval' => 'Siti Keuangan',
        'tanggal_approval' => '2024-11-12 11:30:00',
        'catatan_approval' => 'Pembayaran lunas terverifikasi, proyek dapat dimulai'
    ]
];
@endphp

<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Approval Pembayaran</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan approve permintaan pembayaran</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-clipboard-check text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
    
    <!-- Info Flow -->
    <div class="mt-4 p-3 bg-red-700 rounded-lg">
        <div class="flex items-center gap-2 text-sm mb-2">
            <i class="fas fa-info-circle"></i>
            <span class="font-medium">Alur:</span>
            <span class="flex items-center gap-1">
                Marketing → ACC Klien → Purchasing → 
                <span class="bg-red-600 px-2 py-1 rounded text-xs font-bold">Finance Approval (Anda di sini)</span>
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
            <div class="flex items-center gap-2">
                <i class="fas fa-eye text-blue-300"></i>
                <span><strong>Review:</strong> Klik "Detail" untuk cek bukti pembayaran dan validitas data</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-300"></i>
                <span><strong>Approve:</strong> Centang checklist validasi di modal detail, lalu approve</span>
            </div>
        </div>
    </div>
</div>

<!-- Content Card -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Menunggu Approval</p>
                    <p class="text-2xl font-bold">{{ count($pembayaranMenungguApproval) }}</p>
                </div>
                <i class="fas fa-clock text-2xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Sudah Diapprove</p>
                    <p class="text-2xl font-bold">{{ count($riwayatApproval) }}</p>
                </div>
                <i class="fas fa-check-circle text-2xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Nilai Pending</p>
                    <p class="text-2xl font-bold">Rp {{ number_format(array_sum(array_column($pembayaranMenungguApproval, 'nominal')) / 1000000000, 1) }}M</p>
                </div>
                <i class="fas fa-money-bill text-2xl opacity-80"></i>
            </div>
        </div>
        

    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex space-x-8">
            <button id="tabMenunggu" onclick="switchTab('menunggu')" class="py-2 px-1 border-b-2 border-red-500 font-medium text-sm text-red-600 whitespace-nowrap tab-active">
                Menunggu Approval ({{ count($pembayaranMenungguApproval) }})
            </button>
            <button id="tabRiwayat" onclick="switchTab('riwayat')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                Riwayat Approval ({{ count($riwayatApproval) }})
            </button>
        </nav>
    </div>

    <!-- Tab Content: Menunggu Approval -->
    <div id="contentMenunggu" class="tab-content">
        <!-- Filter dan Search -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchPembayaran" placeholder="Cari kode proyek, nama proyek, atau instansi..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="flex gap-2">
                <select id="filterJenis" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Semua Jenis</option>
                    <option value="DP">DP (Down Payment)</option>
                    <option value="Lunas">Lunas/Pelunasan</option>
                </select>
                
                <select id="sortBy" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="tanggal_input_desc">Terbaru</option>
                    <option value="tanggal_input_asc">Terlama</option>
                    <option value="nominal_desc">Nominal Tertinggi</option>
                    <option value="nominal_asc">Nominal Terendah</option>
                </select>
                
                <!-- Quick Actions -->
                <div class="hidden sm:flex gap-2">
                    <button onclick="refreshData()" 
                            class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200"
                            title="Refresh Data">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    
                    <button onclick="exportData()" 
                            class="px-4 py-3 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors duration-200"
                            title="Export Excel">
                        <i class="fas fa-file-excel"></i>
                    </button>
                    
                    <div class="relative">
                        <button onclick="toggleBulkActions()" 
                                class="px-4 py-3 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-200"
                                title="Bulk Actions">
                            <i class="fas fa-tasks"></i>
                        </button>
                        
                        <!-- Bulk Actions Dropdown -->
                        <div id="bulkActionsDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden">
                            <div class="py-1">
                                <button onclick="bulkApprove()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>Bulk Approve Selected
                                </button>
                                <hr class="my-1">
                                <button onclick="selectAll()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-check-square text-blue-600 mr-2"></i>Select All
                                </button>
                                <button onclick="clearSelection()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-square text-gray-600 mr-2"></i>Clear Selection
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar (hidden by default) -->
        <div id="bulkActionsBar" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 hidden">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <span class="text-blue-800 font-medium">
                        <span id="selectedCount">0</span> pembayaran dipilih
                    </span>
                </div>
                <div class="flex gap-2">
                    <button onclick="bulkApprove()" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition-colors duration-200">
                        <i class="fas fa-check mr-1"></i>Approve Selected
                    </button>
                    <button onclick="clearSelection()" 
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Informasi Workflow Validasi -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <div class="bg-blue-100 rounded-full p-2 flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-blue-900 mb-2">Cara Validasi Pembayaran:</h4>
                    <ol class="text-sm text-blue-800 space-y-1">
                        <li class="flex items-center gap-2">
                            <span class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">1</span>
                            Klik tombol <strong>"Detail & Validasi"</strong> untuk melihat informasi lengkap pembayaran
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">2</span>
                            Review bukti pembayaran, nominal, dan data transaksi
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">3</span>
                            Centang semua <strong>checklist validasi</strong> yang tersedia
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">4</span>
                            Tambahkan catatan approval jika diperlukan
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">5</span>
                            Klik <strong>"Approve"</strong> di modal detail setelah validasi selesai
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Tabel Pembayaran Menunggu Approval -->
        <div class="overflow-x-auto">
            <div class="hidden md:block">
                <!-- Desktop Table -->
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()" 
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis & Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="pembayaranTableBody">
                        @foreach($pembayaranMenungguApproval as $pembayaran)
                        <tr class="hover:bg-gray-50 transition-colors duration-200" data-pembayaran-id="{{ $pembayaran['id'] }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="payment-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500" 
                                       data-payment-id="{{ $pembayaran['id'] }}" onchange="updateBulkActions()">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $pembayaran['kode_proyek'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $pembayaran['nama_proyek'] }}</div>
                                        <div class="text-xs text-gray-400">{{ $pembayaran['nama_instansi'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $pembayaran['jenis_pembayaran'] == 'DP' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $pembayaran['jenis_pembayaran'] == 'DP' ? '📋 DP' : '💰 Lunas' }}
                                    </span>
                                </div>
                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($pembayaran['nominal'], 0, ',', '.') }}</div>
                                @if($pembayaran['jenis_pembayaran'] == 'DP')
                                    <div class="text-xs text-gray-500">{{ $pembayaran['persentase_dp'] }}% dari total proyek</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ date('d/m/Y', strtotime($pembayaran['tanggal_bayar'])) }}</div>
                                <div class="text-xs text-gray-500">Input: {{ date('d/m/Y H:i', strtotime($pembayaran['tanggal_input'])) }}</div>
                                <div class="text-xs text-gray-500">oleh {{ $pembayaran['admin_input'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Menunggu Approval
                                </span>
                                <div class="text-xs text-gray-500 mt-1">{{ $pembayaran['metode_pembayaran'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <button onclick="showDetailPembayaran({{ $pembayaran['id'] }})" 
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition-colors duration-200 relative">
                                        <i class="fas fa-eye mr-1"></i>Detail & Validasi
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-2 h-2 animate-pulse"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4" id="mobileCardContainer">
                @foreach($pembayaranMenungguApproval as $pembayaran)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm" data-pembayaran-id="{{ $pembayaran['id'] }}">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-sm">{{ $pembayaran['kode_proyek'] }}</h3>
                            <p class="text-sm text-gray-600 leading-tight">{{ $pembayaran['nama_proyek'] }}</p>
                            <p class="text-xs text-gray-500">{{ $pembayaran['nama_instansi'] }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 shrink-0">
                            <i class="fas fa-clock mr-1"></i>
                            Pending
                        </span>
                    </div>

                    <!-- Payment Info -->
                    <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs">Jenis Pembayaran</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $pembayaran['jenis_pembayaran'] == 'DP' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $pembayaran['jenis_pembayaran'] == 'DP' ? '📋 DP' : '💰 Lunas' }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 text-xs">Nominal</p>
                            <p class="font-semibold text-gray-900">Rp {{ number_format($pembayaran['nominal'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Date & Admin Info -->
                    <div class="grid grid-cols-2 gap-3 mb-4 text-xs text-gray-500">
                        <div>
                            <p>Tanggal Bayar:</p>
                            <p class="text-gray-900">{{ date('d/m/Y', strtotime($pembayaran['tanggal_bayar'])) }}</p>
                        </div>
                        <div>
                            <p>Input oleh:</p>
                            <p class="text-gray-900">{{ $pembayaran['admin_input'] }}</p>
                            <p>{{ date('d/m H:i', strtotime($pembayaran['tanggal_input'])) }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <button onclick="showDetailPembayaran({{ $pembayaran['id'] }})" 
                                class="w-full text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 text-center relative">
                            <i class="fas fa-eye mr-1"></i>Detail & Validasi
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-2 h-2 animate-pulse"></span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @if(count($pembayaranMenungguApproval) == 0)
        <div class="text-center py-12">
            <i class="fas fa-check-circle text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Pembayaran Pending</h3>
            <p class="text-gray-500">Semua pembayaran sudah diproses</p>
        </div>
        @endif
    </div>

    <!-- Tab Content: Riwayat Approval -->
    <div id="contentRiwayat" class="tab-content hidden">
        <div class="overflow-x-auto">
            <div class="hidden md:block">
                <!-- Desktop Table -->
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis & Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approval</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($riwayatApproval as $approval)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $approval['kode_proyek'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $approval['nama_proyek'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $approval['jenis_pembayaran'] == 'DP' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $approval['jenis_pembayaran'] == 'DP' ? '📋 DP' : '💰 Lunas' }}
                                    </span>
                                </div>
                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($approval['nominal'], 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ date('d/m/Y H:i', strtotime($approval['tanggal_approval'])) }}</div>
                                <div class="text-xs text-gray-500">oleh {{ $approval['admin_approval'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Approved
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="showDetailApproval({{ $approval['id'] }})" 
                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4">
                @foreach($riwayatApproval as $approval)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-sm">{{ $approval['kode_proyek'] }}</h3>
                            <p class="text-sm text-gray-600 leading-tight">{{ $approval['nama_proyek'] }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shrink-0">
                            <i class="fas fa-check-circle mr-1"></i>
                            Approved
                        </span>
                    </div>

                    <!-- Payment Info -->
                    <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs">Jenis Pembayaran</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $approval['jenis_pembayaran'] == 'DP' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $approval['jenis_pembayaran'] == 'DP' ? '📋 DP' : '💰 Lunas' }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 text-xs">Nominal</p>
                            <p class="font-semibold text-gray-900">Rp {{ number_format($approval['nominal'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Approval Info -->
                    <div class="bg-green-50 rounded-lg p-3 mb-3">
                        <div class="text-xs text-gray-600 mb-1">Diapprove oleh:</div>
                        <div class="text-sm font-medium text-gray-900">{{ $approval['admin_approval'] }}</div>
                        <div class="text-xs text-gray-500">{{ date('d/m/Y H:i', strtotime($approval['tanggal_approval'])) }}</div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex">
                        <button onclick="showDetailApproval({{ $approval['id'] }})" 
                                class="w-full text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 text-center">
                            <i class="fas fa-eye mr-1"></i>Lihat Detail
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @if(count($riwayatApproval) == 0)
        <div class="text-center py-12">
            <i class="fas fa-history text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat</h3>
            <p class="text-gray-500">Belum ada pembayaran yang diproses</p>
        </div>
        @endif
    </div>
</div>

<!-- Include Modal Components -->
@include('pages.keuangan.approval-components.detail')

<script>
// Sample data untuk demo dan sorting
const pembayaranData = {
    3: {
        id: 3,
        proyek_id: 1,
        kode_proyek: 'PNW-2024-001',
        nama_proyek: 'Sistem Informasi Manajemen',
        nama_instansi: 'Dinas Pendidikan DKI',
        jenis_pembayaran: 'DP',
        nominal: 255000000,
        tanggal_bayar: '2024-11-15',
        metode_pembayaran: 'Transfer Bank',
        status: 'menunggu_approval',
        bukti_pembayaran: 'bukti_dp_pnw2024001.pdf',
        catatan: 'Pembayaran DP 30% dari total nilai proyek',
        admin_input: 'Maya Indah',
        tanggal_input: '2024-11-15 09:30:00',
        total_nilai_proyek: 850000000,
        persentase_dp: 30,
        bank_pengirim: 'BNI',
        rekening_pengirim: '****1234',
        nomor_referensi: 'TRF202411150930001'
    },
    4: {
        id: 4,
        proyek_id: 6,
        kode_proyek: 'PNW-2024-006',
        nama_proyek: 'Aplikasi Smart City',
        nama_instansi: 'Pemkot Medan',
        jenis_pembayaran: 'Lunas',
        nominal: 1200000000,
        tanggal_bayar: '2024-11-16',
        metode_pembayaran: 'Transfer Bank',
        status: 'menunggu_approval',
        bukti_pembayaran: 'bukti_lunas_pnw2024006.pdf',
        catatan: 'Pembayaran lunas langsung (tanpa DP)',
        admin_input: 'Sari Wijaya',
        tanggal_input: '2024-11-16 14:15:00',
        total_nilai_proyek: 1200000000,
        persentase_dp: 0,
        bank_pengirim: 'Mandiri',
        rekening_pengirim: '****5678',
        nomor_referensi: 'TRF202411161415002'
    },
    5: {
        id: 5,
        proyek_id: 2,
        kode_proyek: 'PNW-2024-002',
        nama_proyek: 'Portal E-Government',
        nama_instansi: 'Pemda Bandung',
        jenis_pembayaran: 'Lunas',
        nominal: 420000000,
        tanggal_bayar: '2024-11-17',
        metode_pembayaran: 'Transfer Bank',
        status: 'menunggu_approval',
        bukti_pembayaran: 'bukti_pelunasan_pnw2024002.pdf',
        catatan: 'Pelunasan sisa pembayaran (70% dari total)',
        admin_input: 'Andi Prasetyo',
        tanggal_input: '2024-11-17 11:45:00',
        total_nilai_proyek: 600000000,
        persentase_dp: 30,
        bank_pengirim: 'BCA',
        rekening_pengirim: '****9012',
        nomor_referensi: 'TRF202411171145003'
    }
};

// Tab switching
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('nav button').forEach(tab => {
        tab.classList.remove('border-red-500', 'text-red-600', 'tab-active');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-red-500', 'text-red-600', 'tab-active');
}

// Detail functions - implementation sudah ada di modal component
function showDetailPembayaran(pembayaranId) {
    // Function ini sudah diimplementasikan di detail.blade.php
    // Akan otomatis terpanggil karena include modal component
}

function showDetailApproval(approvalId) {
    // Show detail approval (riwayat)
    const approvalData = {
        1: {
            id: 1,
            proyek_id: 4,
            kode_proyek: 'PNW-2024-004',
            nama_proyek: 'Dashboard Analytics Daerah',
            nama_instansi: 'Pemda Surabaya',
            jenis_pembayaran: 'DP',
            nominal: 276000000,
            status: 'approved',
            admin_approval: 'Budi Keuangan',
            tanggal_approval: '2024-11-01 14:15:00',
            catatan_approval: 'Bukti pembayaran valid, nominal sesuai kontrak',
            total_nilai_proyek: 920000000,
            admin_input: 'Maya Indah',
            tanggal_input: '2024-10-30 10:15:00'
        },
        2: {
            id: 2,
            proyek_id: 7,
            kode_proyek: 'PNW-2024-007',
            nama_proyek: 'Website E-Commerce UMKM',
            nama_instansi: 'Pemkot Malang',
            jenis_pembayaran: 'Lunas',
            nominal: 450000000,
            status: 'approved',
            admin_approval: 'Siti Keuangan',
            tanggal_approval: '2024-11-12 11:30:00',
            catatan_approval: 'Pembayaran lunas terverifikasi, proyek dapat dimulai',
            total_nilai_proyek: 450000000,
            admin_input: 'Andi Prasetyo',
            tanggal_input: '2024-11-11 16:45:00'
        }
    };
    
    const data = approvalData[approvalId];
    if (data) {
        alert(`Detail Approval\n\n` +
              `Proyek: ${data.kode_proyek} - ${data.nama_proyek}\n` +
              `Instansi: ${data.nama_instansi}\n` +
              `Jenis: ${data.jenis_pembayaran}\n` +
              `Nominal: Rp ${new Intl.NumberFormat('id-ID').format(data.nominal)}\n` +
              `Status: ${data.status}\n` +
              `Diapprove oleh: ${data.admin_approval}\n` +
              `Tanggal Approval: ${new Date(data.tanggal_approval).toLocaleString('id-ID')}\n` +
              `Catatan: ${data.catatan_approval}`);
    }
}

// Search and filter functions - updated untuk support mobile cards
document.getElementById('searchPembayaran').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    
    // Filter desktop table rows
    const rows = document.querySelectorAll('#pembayaranTableBody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
    
    // Filter mobile cards
    const cards = document.querySelectorAll('#mobileCardContainer > div');
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

document.getElementById('filterJenis').addEventListener('change', function(e) {
    const filterValue = e.target.value;
    
    // Filter desktop table rows
    const rows = document.querySelectorAll('#pembayaranTableBody tr');
    rows.forEach(row => {
        if (!filterValue) {
            row.style.display = '';
        } else {
            const jenisElement = row.querySelector('.inline-flex');
            const hasJenis = jenisElement && jenisElement.textContent.toLowerCase().includes(filterValue.toLowerCase());
            row.style.display = hasJenis ? '' : 'none';
        }
    });
    
    // Filter mobile cards
    const cards = document.querySelectorAll('#mobileCardContainer > div');
    cards.forEach(card => {
        if (!filterValue) {
            card.style.display = '';
        } else {
            const jenisElement = card.querySelector('.inline-flex');
            const hasJenis = jenisElement && jenisElement.textContent.toLowerCase().includes(filterValue.toLowerCase());
            card.style.display = hasJenis ? '' : 'none';
        }
    });
});

document.getElementById('sortBy').addEventListener('change', function(e) {
    const sortValue = e.target.value;
    
    // Get all items (both table rows and mobile cards)
    const tableRows = Array.from(document.querySelectorAll('#pembayaranTableBody tr'));
    const mobileCards = Array.from(document.querySelectorAll('#mobileCardContainer > div'));
    
    // Sort function
    const sortItems = (items, isCard = false) => {
        return items.sort((a, b) => {
            const aId = parseInt(a.dataset.pembayaranId);
            const bId = parseInt(b.dataset.pembayaranId);
            const aData = pembayaranData[aId];
            const bData = pembayaranData[bId];
            
            if (!aData || !bData) return 0;
            
            switch(sortValue) {
                case 'tanggal_input_desc':
                    return new Date(bData.tanggal_input) - new Date(aData.tanggal_input);
                case 'tanggal_input_asc':
                    return new Date(aData.tanggal_input) - new Date(bData.tanggal_input);
                case 'nominal_desc':
                    return bData.nominal - aData.nominal;
                case 'nominal_asc':
                    return aData.nominal - bData.nominal;
                default:
                    return 0;
            }
        });
    };
    
    // Sort and re-append desktop table rows
    const sortedRows = sortItems(tableRows);
    const tableBody = document.getElementById('pembayaranTableBody');
    sortedRows.forEach(row => tableBody.appendChild(row));
    
    // Sort and re-append mobile cards
    const sortedCards = sortItems(mobileCards, true);
    const cardContainer = document.getElementById('mobileCardContainer');
    sortedCards.forEach(card => cardContainer.appendChild(card));
});

// Auto refresh setiap 30 detik untuk cek pembayaran baru
let refreshInterval = setInterval(() => {
    // Simulate checking for new payments
    console.log('Checking for new payment approvals...');
    checkForNewPayments();
}, 30000);

// Bulk actions functions
function toggleBulkActions() {
    const dropdown = document.getElementById('bulkActionsDropdown');
    dropdown.classList.toggle('hidden');
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const paymentCheckboxes = document.querySelectorAll('.payment-checkbox');
    
    paymentCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkedBoxes.length > 0) {
        bulkActionsBar.classList.remove('hidden');
        selectedCount.textContent = checkedBoxes.length;
    } else {
        bulkActionsBar.classList.add('hidden');
    }
    
    // Update select all checkbox state
    const allCheckboxes = document.querySelectorAll('.payment-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    
    if (checkedBoxes.length === allCheckboxes.length) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else if (checkedBoxes.length > 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    }
}

function selectAll() {
    document.getElementById('selectAllCheckbox').checked = true;
    toggleSelectAll();
}

function clearSelection() {
    document.getElementById('selectAllCheckbox').checked = false;
    toggleSelectAll();
    document.getElementById('bulkActionsDropdown').classList.add('hidden');
}

function bulkApprove() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    const paymentIds = Array.from(checkedBoxes).map(cb => cb.dataset.paymentId);
    
    if (paymentIds.length === 0) {
        alert('Pilih minimal satu pembayaran untuk di-approve.');
        return;
    }
    
    const confirmMessage = `Apakah Anda yakin ingin MENYETUJUI ${paymentIds.length} pembayaran sekaligus?\n\n` +
                          `Pastikan semua pembayaran telah diverifikasi dengan benar.`;
    
    if (confirm(confirmMessage)) {
        // Show loading
        const btn = event.target;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Processing...';
        btn.disabled = true;
        
        // Simulate bulk API call
        setTimeout(() => {
            alert(`${paymentIds.length} pembayaran berhasil disetujui!\n\nSemua proyek terkait dapat dilanjutkan.`);
            clearSelection();
            location.reload(); // Refresh untuk update data
        }, 3000);
    }
}

// Quick action functions
function refreshData() {
    const btn = event.target;
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    
    // Simulate data refresh
    setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        
        // Show notification
        showNotification('Data berhasil di-refresh!', 'success');
    }, 1500);
}

function exportData() {
    const btn = event.target;
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    
    // Simulate export
    setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        
        alert('Export Excel dimulai!\n\nFile akan didownload dalam beberapa saat.');
        // Dalam implementasi nyata, ini akan trigger download file Excel
    }, 2000);
}

// Real-time notifications
function checkForNewPayments() {
    // Simulate checking server for new payments
    const hasNewPayments = Math.random() < 0.1; // 10% chance
    
    if (hasNewPayments) {
        showNotification('Ada pembayaran baru yang perlu di-approval!', 'info');
        // Optionally play notification sound
        playNotificationSound();
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    switch(type) {
        case 'success':
            notification.classList.add('bg-green-500', 'text-white');
            break;
        case 'error':
            notification.classList.add('bg-red-500', 'text-white');
            break;
        case 'info':
            notification.classList.add('bg-blue-500', 'text-white');
            break;
        default:
            notification.classList.add('bg-gray-500', 'text-white');
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Notification sound for new payments
function playNotificationSound() {
    // Simple beep sound using Web Audio API
    if (typeof(AudioContext) !== "undefined" || typeof(webkitAudioContext) !== "undefined") {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        
        oscillator.start();
        oscillator.stop(audioContext.currentTime + 0.2);
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#bulkActionsDropdown') && !e.target.closest('button[onclick="toggleBulkActions()"]')) {
        document.getElementById('bulkActionsDropdown').classList.add('hidden');
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+A: Select all
    if (e.ctrlKey && e.key === 'a' && document.activeElement.tagName !== 'INPUT') {
        e.preventDefault();
        selectAll();
    }
    
    // Ctrl+R: Refresh
    if (e.ctrlKey && e.key === 'r') {
        e.preventDefault();
        refreshData();
    }
    
    // Escape: Clear selection
    if (e.key === 'Escape') {
        clearSelection();
    }
});

// Add CSS animation for indicators
const style = document.createElement('style');
style.textContent = `
    .validation-indicator {
        animation: pulse-glow 2s infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }
`;
document.head.appendChild(style);

// Initialize highlights when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Highlight Detail buttons untuk menarik perhatian
    const detailButtons = document.querySelectorAll('button[onclick*="showDetailPembayaran"]');
    detailButtons.forEach(btn => {
        btn.style.boxShadow = '0 0 0 2px rgba(59, 130, 246, 0.3)';
        btn.style.fontWeight = '600';
    });
    
    // Add pulse animation to indicators
    const indicators = document.querySelectorAll('.animate-pulse');
    indicators.forEach(indicator => {
        indicator.classList.add('validation-indicator');
    });
});
</script>

@endsection
