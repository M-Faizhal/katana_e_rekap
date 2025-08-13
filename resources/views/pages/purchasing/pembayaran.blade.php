@extends('layouts.app')

@section('content')

@php
// Data proyek yang sudah di-ACC dari marketing dan turun ke purchasing
$proyekDariMarketing = [
    [
        'id' => 1,
        'kode' => 'PNW-2024-001',
        'nama_proyek' => 'Sistem Informasi Manajemen',
        'kabupaten_kota' => 'Jakarta Pusat',
        'nama_instansi' => 'Dinas Pendidikan DKI',
        'total_nilai' => 850000000,
        'tanggal_acc_klien' => '2024-09-30',
        'tanggal_turun_purchasing' => '2024-10-01',
        'admin_marketing' => 'Andi Prasetyo',
        'status_purchasing' => 'belum_ada_pembayaran', // belum_ada_pembayaran, dp_menunggu_verifikasi, dp_terverifikasi, lunas
        'jenis_pembayaran_rencana' => 'dp_dulu', // dp_dulu, langsung_lunas
        'persentase_dp' => 30,
        'nilai_dp' => 255000000,
        'nilai_sisa' => 595000000,
        'jenis_pengadaan' => 'Pelelangan Umum',
        'deadline_proyek' => '2024-12-30'
    ],
    [
        'id' => 4,
        'kode' => 'PNW-2024-004',
        'nama_proyek' => 'Dashboard Analytics Daerah',
        'kabupaten_kota' => 'Yogyakarta',
        'nama_instansi' => 'Pemda DIY',
        'total_nilai' => 920000000,
        'tanggal_acc_klien' => '2024-10-25',
        'tanggal_turun_purchasing' => '2024-10-26',
        'admin_marketing' => 'Fajar Ramadhan',
        'status_purchasing' => 'dp_terverifikasi',
        'jenis_pembayaran_rencana' => 'dp_dulu',
        'persentase_dp' => 30,
        'nilai_dp' => 276000000,
        'nilai_sisa' => 644000000,
        'jenis_pengadaan' => 'Pelelangan Umum',
        'deadline_proyek' => '2024-12-25'
    ],
    [
        'id' => 6,
        'kode' => 'PNW-2024-006',
        'nama_proyek' => 'Aplikasi Smart City',
        'kabupaten_kota' => 'Medan',
        'nama_instansi' => 'Pemkot Medan',
        'total_nilai' => 1200000000,
        'tanggal_acc_klien' => '2024-11-05',
        'tanggal_turun_purchasing' => '2024-11-06',
        'admin_marketing' => 'Dewi Lestari',
        'status_purchasing' => 'belum_ada_pembayaran',
        'jenis_pembayaran_rencana' => 'langsung_lunas', // Proyek ini rencana langsung lunas
        'persentase_dp' => 0, // Tidak ada DP
        'nilai_dp' => 0,
        'nilai_sisa' => 1200000000,
        'jenis_pengadaan' => 'Penunjukan Langsung',
        'deadline_proyek' => '2025-03-15'
    ],
    [
        'id' => 7,
        'kode' => 'PNW-2024-007',
        'nama_proyek' => 'Website E-Commerce UMKM',
        'kabupaten_kota' => 'Surabaya',
        'nama_instansi' => 'Dinas Koperasi Surabaya',
        'total_nilai' => 450000000,
        'tanggal_acc_klien' => '2024-11-10',
        'tanggal_turun_purchasing' => '2024-11-11',
        'admin_marketing' => 'Budi Santoso',
        'status_purchasing' => 'lunas', // Sudah lunas (pembayaran langsung)
        'jenis_pembayaran_rencana' => 'langsung_lunas',
        'persentase_dp' => 0,
        'nilai_dp' => 0,
        'nilai_sisa' => 0,
        'jenis_pengadaan' => 'Penunjukan Langsung',
        'deadline_proyek' => '2025-02-10'
    ]
];

// Data pembayaran yang sudah ada
$riwayatPembayaran = [
    [
        'id' => 1,
        'proyek_id' => 4,
        'jenis_pembayaran' => 'DP',
        'nominal' => 276000000,
        'tanggal_bayar' => '2024-11-01',
        'metode_pembayaran' => 'Transfer Bank',
        'status' => 'terverifikasi',
        'bukti_pembayaran' => 'bukti_dp_pnw2024004.pdf',
        'catatan' => 'Pembayaran DP 30% dari total nilai proyek',
        'admin_input' => 'Maya Indah',
        'tanggal_input' => '2024-11-01 10:30:00',
        'admin_verifikasi' => 'Keuangan',
        'tanggal_verifikasi' => '2024-11-01 14:15:00'
    ],
    [
        'id' => 2,
        'proyek_id' => 7,
        'jenis_pembayaran' => 'Lunas',
        'nominal' => 450000000,
        'tanggal_bayar' => '2024-11-12',
        'metode_pembayaran' => 'Transfer Bank',
        'status' => 'terverifikasi',
        'bukti_pembayaran' => 'bukti_lunas_pnw2024007.pdf',
        'catatan' => 'Pembayaran lunas langsung (tanpa DP)',
        'admin_input' => 'Sari Wijaya',
        'tanggal_input' => '2024-11-12 09:15:00',
        'admin_verifikasi' => 'Keuangan',
        'tanggal_verifikasi' => '2024-11-12 11:30:00'
    ]
];
@endphp

<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Pembayaran Purchasing</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola pembayaran proyek yang sudah di-ACC klien</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-credit-card text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
    
    <!-- Info Flow -->
    <div class="mt-4 p-3 bg-red-700 rounded-lg">
        <div class="flex items-center gap-2 text-sm mb-2">
            <i class="fas fa-info-circle"></i>
            <span class="font-medium">Alur:</span>
            <span class="flex items-center gap-1">
                Marketing → Penawaran → ACC Klien → 
                <span class="bg-red-600 px-2 py-1 rounded text-xs font-bold">Purchasing (Anda di sini)</span>
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
            <div class="flex items-center gap-2">
                <i class="fas fa-clock text-yellow-300"></i>
                <span><strong>DP Dulu:</strong> Input DP → Verifikasi → Input Pelunasan</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-300"></i>
                <span><strong>Langsung Lunas:</strong> Input Pembayaran Full → Selesai</span>
            </div>
        </div>
    </div>
</div>

<!-- Content Card -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Proyek</p>
                    <p class="text-2xl font-bold">{{ count($proyekDariMarketing) }}</p>
                </div>
                <i class="fas fa-project-diagram text-2xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Sudah Lunas</p>
                    <p class="text-2xl font-bold">{{ collect($proyekDariMarketing)->where('status_purchasing', 'lunas')->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-2xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">DP Verified</p>
                    <p class="text-2xl font-bold">{{ collect($proyekDariMarketing)->where('status_purchasing', 'dp_terverifikasi')->count() }}</p>
                </div>
                <i class="fas fa-hourglass-half text-2xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm">Belum Bayar</p>
                    <p class="text-2xl font-bold">{{ collect($proyekDariMarketing)->where('status_purchasing', 'belum_ada_pembayaran')->count() }}</p>
                </div>
                <i class="fas fa-exclamation-triangle text-2xl opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchProyek" placeholder="Cari proyek, kode, atau instansi..." 
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
        </div>
        
        <div class="flex gap-2">
            <select id="filterStatus" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="belum_ada_pembayaran">Belum Ada Pembayaran</option>
                <option value="dp_menunggu_verifikasi">DP Menunggu Verifikasi</option>
                <option value="dp_terverifikasi">DP Terverifikasi</option>
                <option value="lunas">Lunas</option>
            </select>
            
            <button onclick="showAddPaymentModal()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Input Pembayaran
            </button>
        </div>
    </div>

    <!-- Tabel Proyek -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACC Klien</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="proyekTableBody">
                @foreach($proyekDariMarketing as $proyek)
                <tr class="hover:bg-gray-50 transition-colors duration-200" data-proyek-id="{{ $proyek['id'] }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-project-diagram text-red-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $proyek['kode'] }}</div>
                                <div class="text-sm text-gray-600">{{ $proyek['nama_proyek'] }}</div>
                                <div class="text-xs text-gray-500">Marketing: {{ $proyek['admin_marketing'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $proyek['nama_instansi'] }}</div>
                        <div class="text-sm text-gray-600">{{ $proyek['kabupaten_kota'] }}</div>
                        <div class="text-xs text-gray-500">{{ $proyek['jenis_pengadaan'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Rp {{ number_format($proyek['total_nilai'], 0, ',', '.') }}</div>
                        @if($proyek['jenis_pembayaran_rencana'] == 'dp_dulu')
                            <div class="text-xs text-gray-600">DP: {{ $proyek['persentase_dp'] }}% (Rp {{ number_format($proyek['nilai_dp'], 0, ',', '.') }})</div>
                            <div class="text-xs text-gray-500">Sisa: Rp {{ number_format($proyek['nilai_sisa'], 0, ',', '.') }}</div>
                        @else
                            <div class="text-xs text-blue-600">💰 Langsung Lunas</div>
                            <div class="text-xs text-gray-500">Tidak ada DP</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($proyek['status_purchasing'] == 'belum_ada_pembayaran')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-clock mr-1"></i>
                                Belum Ada Pembayaran
                            </span>
                        @elseif($proyek['status_purchasing'] == 'dp_menunggu_verifikasi')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-hourglass-half mr-1"></i>
                                DP Menunggu Verifikasi
                            </span>
                        @elseif($proyek['status_purchasing'] == 'dp_terverifikasi')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-check mr-1"></i>
                                DP Terverifikasi
                            </span>
                        @elseif($proyek['status_purchasing'] == 'lunas')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Lunas
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div>{{ date('d M Y', strtotime($proyek['tanggal_acc_klien'])) }}</div>
                        <div class="text-xs text-gray-500">{{ date('d M Y', strtotime($proyek['tanggal_turun_purchasing'])) }} (ke Purchasing)</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button onclick="showPaymentDetail({{ $proyek['id'] }})" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors duration-200 flex items-center gap-1">
                            <i class="fas fa-eye text-xs"></i>
                            Detail
                        </button>
                        
                        @if($proyek['status_purchasing'] != 'lunas')
                        <button onclick="inputPembayaran({{ $proyek['id'] }})" 
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors duration-200 flex items-center gap-1">
                            <i class="fas fa-plus text-xs"></i>
                            @if($proyek['jenis_pembayaran_rencana'] == 'langsung_lunas')
                                Input Lunas
                            @elseif($proyek['status_purchasing'] == 'belum_ada_pembayaran')
                                Input DP
                            @else
                                Input Pelunasan
                            @endif
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(count($proyekDariMarketing) == 0)
    <div class="text-center py-12">
        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Proyek</h3>
        <p class="text-gray-500">Tidak ada proyek yang turun dari marketing untuk diproses pembayaran</p>
    </div>
    @endif
</div>

<!-- Include Modal Components -->
@include('pages.purchasing.pembayaran-components.detail')
@include('pages.purchasing.pembayaran-components.edit')

<!-- Modal Input Pembayaran -->
<div id="addPaymentModal" class="fixed inset-0 bg-black/20 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
        <div class="bg-white rounded-lg sm:rounded-xl max-w-sm sm:max-w-lg lg:max-w-2xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Input Pembayaran Proyek</h3>
                    <button onclick="closeAddPaymentModal()" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-lg sm:text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="addPaymentForm" class="p-4 sm:p-6">
                <!-- Pilih Proyek -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Proyek *</label>
                    <select id="selectedProyek" name="proyek_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">-- Pilih Proyek yang Sudah di-ACC Klien --</option>
                        @foreach($proyekDariMarketing as $proyek)
                        <option value="{{ $proyek['id'] }}" 
                                data-kode="{{ $proyek['kode'] }}"
                                data-nama="{{ $proyek['nama_proyek'] }}"
                                data-instansi="{{ $proyek['nama_instansi'] }}"
                                data-total="{{ $proyek['total_nilai'] }}"
                                data-dp="{{ $proyek['nilai_dp'] }}"
                                data-sisa="{{ $proyek['nilai_sisa'] }}"
                                data-status="{{ $proyek['status_purchasing'] }}"
                                data-jenis-rencana="{{ $proyek['jenis_pembayaran_rencana'] }}">
                            {{ $proyek['kode'] }} - {{ $proyek['nama_proyek'] }} 
                            @if($proyek['jenis_pembayaran_rencana'] == 'langsung_lunas')
                                (💰 Langsung Lunas)
                            @else
                                (📋 DP Dulu)
                            @endif
                            - {{ $proyek['nama_instansi'] }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Info Proyek Terpilih -->
                <div id="proyekInfo" class="mb-6 p-4 bg-gray-50 rounded-lg hidden">
                    <h4 class="font-semibold text-gray-900 mb-3">Informasi Proyek</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div><span class="font-medium">Kode:</span> <span id="infoKode">-</span></div>
                        <div><span class="font-medium">Proyek:</span> <span id="infoNama">-</span></div>
                        <div><span class="font-medium">Instansi:</span> <span id="infoInstansi">-</span></div>
                        <div><span class="font-medium">Total Nilai:</span> <span id="infoTotal">-</span></div>
                        <div><span class="font-medium">Jenis Pembayaran:</span> <span id="infoJenisPembayaran">-</span></div>
                    </div>
                </div>

                <!-- Jenis Pembayaran -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pembayaran *</label>
                    <select id="jenisPembayaran" name="jenis_pembayaran" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">-- Pilih Jenis Pembayaran --</option>
                        <option value="DP">DP (Down Payment)</option>
                        <option value="Lunas">Lunas (Pelunasan)</option>
                        <option value="Cicilan">Cicilan</option>
                    </select>
                </div>

                <!-- Nominal Bayar -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Bayar *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                        <input type="text" id="nominalBayar" name="nominal" required 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="0">
                    </div>
                    <div id="nominalSuggestion" class="mt-2 text-sm text-blue-600 hidden"></div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan nominal tanpa titik atau koma</p>
                </div>

                <!-- Tanggal Bayar -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar *</label>
                    <input type="date" id="tanggalBayar" name="tanggal_bayar" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <!-- Metode Pembayaran -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
                    <select id="metodePembayaran" name="metode_pembayaran" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Giro">Giro</option>
                        <option value="Cek">Cek</option>
                        <option value="Virtual Account">Virtual Account</option>
                        <option value="E-Wallet">E-Wallet</option>
                    </select>
                </div>

                <!-- Upload Bukti Pembayaran -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 text-center hover:border-red-400 transition-colors duration-200">
                        <input type="file" id="buktiPembayaran" name="bukti_pembayaran" accept=".pdf,.jpg,.jpeg,.png" required class="hidden">
                        <div id="uploadArea" onclick="document.getElementById('buktiPembayaran').click()" class="cursor-pointer">
                            <i class="fas fa-upload text-2xl sm:text-3xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 font-medium text-sm sm:text-base">Klik untuk upload bukti pembayaran</p>
                            <p class="text-xs sm:text-sm text-gray-500">PDF, JPG, JPEG, PNG (Max. 5MB)</p>
                        </div>
                        <div id="fileInfo" class="hidden mt-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file text-blue-600"></i>
                                    <span id="fileName" class="text-sm font-medium"></span>
                                </div>
                                <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-700 w-fit">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Tambahkan catatan mengenai pembayaran ini..."></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeAddPaymentModal()" 
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors duration-200 text-sm sm:text-base">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions
function showAddPaymentModal() {
    document.getElementById('addPaymentModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddPaymentModal() {
    document.getElementById('addPaymentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('addPaymentForm').reset();
    document.getElementById('proyekInfo').classList.add('hidden');
    document.getElementById('fileInfo').classList.add('hidden');
    document.getElementById('uploadArea').classList.remove('hidden');
}

function inputPembayaran(proyekId) {
    showAddPaymentModal();
    // Auto select proyek
    document.getElementById('selectedProyek').value = proyekId;
    document.getElementById('selectedProyek').dispatchEvent(new Event('change'));
}

// Handle proyek selection
document.getElementById('selectedProyek').addEventListener('change', function(e) {
    const selectedOption = e.target.selectedOptions[0];
    if (selectedOption.value) {
        const proyekInfo = document.getElementById('proyekInfo');
        const kode = selectedOption.dataset.kode;
        const nama = selectedOption.dataset.nama;
        const instansi = selectedOption.dataset.instansi;
        const total = parseInt(selectedOption.dataset.total);
        const dp = parseInt(selectedOption.dataset.dp);
        const sisa = parseInt(selectedOption.dataset.sisa);
        const status = selectedOption.dataset.status;
        const jenisRencana = selectedOption.dataset.jenisRencana;
        
        // Update info proyek
        document.getElementById('infoKode').textContent = kode;
        document.getElementById('infoNama').textContent = nama;
        document.getElementById('infoInstansi').textContent = instansi;
        document.getElementById('infoTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        
        // Update jenis pembayaran info
        const jenisText = jenisRencana === 'langsung_lunas' ? '💰 Langsung Lunas' : '📋 DP Dulu (' + (dp / total * 100) + '%)';
        document.getElementById('infoJenisPembayaran').textContent = jenisText;
        
        proyekInfo.classList.remove('hidden');
        
        // Update jenis pembayaran options based on status and rencana
        const jenisPembayaran = document.getElementById('jenisPembayaran');
        jenisPembayaran.innerHTML = '<option value="">-- Pilih Jenis Pembayaran --</option>';
        
        if (jenisRencana === 'langsung_lunas') {
            // Untuk proyek langsung lunas
            if (status === 'belum_ada_pembayaran') {
                jenisPembayaran.innerHTML += '<option value="Lunas">Lunas (Pembayaran Full)</option>';
            }
        } else {
            // Untuk proyek DP dulu
            if (status === 'belum_ada_pembayaran') {
                jenisPembayaran.innerHTML += '<option value="DP">DP (Down Payment)</option>';
            } else if (status === 'dp_terverifikasi') {
                jenisPembayaran.innerHTML += '<option value="Lunas">Lunas (Pelunasan)</option>';
                jenisPembayaran.innerHTML += '<option value="Cicilan">Cicilan</option>';
            }
        }
    } else {
        document.getElementById('proyekInfo').classList.add('hidden');
    }
});

// Handle jenis pembayaran change
document.getElementById('jenisPembayaran').addEventListener('change', function(e) {
    const selectedProyek = document.getElementById('selectedProyek').selectedOptions[0];
    const suggestion = document.getElementById('nominalSuggestion');
    
    if (selectedProyek.value && e.target.value) {
        const total = parseInt(selectedProyek.dataset.total);
        const dp = parseInt(selectedProyek.dataset.dp);
        const sisa = parseInt(selectedProyek.dataset.sisa);
        const jenisRencana = selectedProyek.dataset.jenisRencana;
        
        if (e.target.value === 'DP') {
            suggestion.textContent = 'Saran: Rp ' + dp.toLocaleString('id-ID') + ' (DP ' + (dp/total*100) + '%)';
            suggestion.classList.remove('hidden');
        } else if (e.target.value === 'Lunas') {
            if (jenisRencana === 'langsung_lunas') {
                suggestion.textContent = 'Saran: Rp ' + total.toLocaleString('id-ID') + ' (Pembayaran Full)';
            } else {
                suggestion.textContent = 'Saran: Rp ' + sisa.toLocaleString('id-ID') + ' (Sisa pembayaran)';
            }
            suggestion.classList.remove('hidden');
        } else {
            suggestion.classList.add('hidden');
        }
    } else {
        suggestion.classList.add('hidden');
    }
});

// File upload handling
document.getElementById('buktiPembayaran').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            e.target.value = '';
            return;
        }
        
        // Show file info
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('uploadArea').classList.add('hidden');
        document.getElementById('fileInfo').classList.remove('hidden');
    }
});

function removeFile() {
    document.getElementById('buktiPembayaran').value = '';
    document.getElementById('uploadArea').classList.remove('hidden');
    document.getElementById('fileInfo').classList.add('hidden');
}

// Format number input
document.getElementById('nominalBayar').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d]/g, '');
    e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
});

// Form submission
document.getElementById('addPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validation
    const proyek = document.getElementById('selectedProyek').value;
    const jenis = document.getElementById('jenisPembayaran').value;
    const nominal = document.getElementById('nominalBayar').value;
    const tanggal = document.getElementById('tanggalBayar').value;
    const metode = document.getElementById('metodePembayaran').value;
    const bukti = document.getElementById('buktiPembayaran').files[0];
    
    if (!proyek || !jenis || !nominal || !tanggal || !metode || !bukti) {
        alert('Harap lengkapi semua field yang wajib diisi');
        return;
    }
    
    // Confirm submission
    const isConfirmed = confirm('Apakah Anda yakin ingin menyimpan data pembayaran?\n\nData akan tersimpan dengan status "Menunggu Verifikasi" dan perlu diverifikasi oleh tim keuangan.');
    
    if (isConfirmed) {
        // Show loading
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Menyimpan...';
        submitBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            alert('Data pembayaran berhasil disimpan!\n\nStatus: Menunggu Verifikasi\nSelanjutnya data akan diverifikasi oleh tim keuangan.');
            closeAddPaymentModal();
            // Refresh table or update status
            location.reload();
        }, 2000);
    }
});

// Search and filter functions
document.getElementById('searchProyek').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#proyekTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

document.getElementById('filterStatus').addEventListener('change', function(e) {
    const filterValue = e.target.value;
    const rows = document.querySelectorAll('#proyekTableBody tr');
    
    rows.forEach(row => {
        if (!filterValue) {
            row.style.display = '';
        } else {
            const statusElement = row.querySelector('.inline-flex');
            const hasStatus = statusElement && statusElement.textContent.toLowerCase().includes(filterValue.replace('_', ' '));
            row.style.display = hasStatus ? '' : 'none';
        }
    });
});

// Set default date to today
document.getElementById('tanggalBayar').valueAsDate = new Date();
</script>

@endsection
