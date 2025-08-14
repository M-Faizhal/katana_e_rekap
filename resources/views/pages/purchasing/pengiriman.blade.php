@extends('layouts.app')

@section('content')

@php
// Data proyek yang pembayarannya sudah valid dari marketing (ready untuk pengiriman)
$proyekReady = [
    [
        'id' => 1,
        'kode_proyek' => 'PNW-2024-001',
        'nama_proyek' => 'Sistem Informasi Manajemen',
        'nama_instansi' => 'Dinas Pendidikan DKI',
        'nilai_proyek' => 850000000,
        'status_pembayaran' => 'Valid',
        'tanggal_acc' => '2024-11-15',
        'pic_marketing' => 'Maya Indah',
        'alamat_pengiriman' => 'Jl. Kebon Sirih No. 12, Jakarta Pusat',
        'kontak_penerima' => 'Budi Santoso (021-12345678)',
        'catatan_khusus' => 'Pengiriman ke lantai 3, koordinasi dengan security'
    ],
    [
        'id' => 2,
        'kode_proyek' => 'PNW-2024-002',
        'nama_proyek' => 'Portal E-Government',
        'nama_instansi' => 'Pemda Bandung',
        'nilai_proyek' => 600000000,
        'status_pembayaran' => 'Valid',
        'tanggal_acc' => '2024-11-14',
        'pic_marketing' => 'Andi Prasetyo',
        'alamat_pengiriman' => 'Jl. Asia Afrika No. 146, Bandung',
        'kontak_penerima' => 'Sari Widya (022-87654321)',
        'catatan_khusus' => 'Jam operasional 08:00-16:00'
    ]
];

// Data pengiriman yang sedang dalam proses
$pengirimanBerjalan = [
    [
        'id' => 1,
        'proyek_id' => 3,
        'kode_proyek' => 'PNW-2024-003',
        'nama_proyek' => 'Dashboard Analytics',
        'nama_instansi' => 'Pemkot Surabaya',
        'nomor_surat_jalan' => 'SJ/2024/11/001',
        'tanggal_surat_jalan' => '2024-11-13',
        'status_pengiriman' => 'Dalam Perjalanan',
        'admin_pengiriman' => 'Roni Purchasing',
        'tanggal_berangkat' => '2024-11-13 08:30:00',
        'estimasi_tiba' => '2024-11-15 14:00:00',
        'progress' => [
            'surat_jalan' => true,
            'foto_keberangkatan' => true,
            'foto_perjalanan' => true,
            'foto_diterima' => false,
            'tanda_terima' => false
        ]
    ],
    [
        'id' => 2,
        'proyek_id' => 4,
        'kode_proyek' => 'PNW-2024-004',
        'nama_proyek' => 'Aplikasi Mobile UMKM',
        'nama_instansi' => 'Diskominfo Malang',
        'nomor_surat_jalan' => 'SJ/2024/11/002',
        'tanggal_surat_jalan' => '2024-11-12',
        'status_pengiriman' => 'Menunggu Verifikasi',
        'admin_pengiriman' => 'Siti Purchasing',
        'tanggal_berangkat' => '2024-11-12 09:00:00',
        'tanggal_diterima' => '2024-11-13 15:30:00',
        'progress' => [
            'surat_jalan' => true,
            'foto_keberangkatan' => true,
            'foto_perjalanan' => true,
            'foto_diterima' => true,
            'tanda_terima' => true
        ]
    ]
];

// Data pengiriman yang sudah selesai
$pengirimanSelesai = [
    [
        'id' => 3,
        'proyek_id' => 5,
        'kode_proyek' => 'PNW-2024-005',
        'nama_proyek' => 'Website Company Profile',
        'nama_instansi' => 'PT. Maju Jaya',
        'nomor_surat_jalan' => 'SJ/2024/10/003',
        'status_pengiriman' => 'Selesai',
        'tanggal_selesai' => '2024-10-28',
        'admin_pengiriman' => 'Roni Purchasing',
        'verifikator' => 'Manager Purchasing',
        'tanggal_verifikasi' => '2024-10-30 10:15:00'
    ]
];
@endphp

<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Pengiriman Purchasing</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola dan pantau pengiriman barang ke klien</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-truck text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>

    <!-- Info Flow -->
    <div class="mt-4 p-3 bg-red-700 rounded-lg">
        <div class="flex items-center gap-2 text-sm mb-2">
            <i class="fas fa-info-circle"></i>
            <span class="font-medium">Alur:</span>
            <span class="flex items-center gap-1">
                Marketing ACC →
                <span class="bg-red-600 px-2 py-1 rounded text-xs font-bold">Purchasing Kirim (Anda di sini)</span>
                → Manager Verifikasi → Selesai
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
            <div class="flex items-center gap-2">
                <i class="fas fa-clipboard-list text-blue-300"></i>
                <span><strong>Input:</strong> Surat jalan, foto keberangkatan, perjalanan, diterima, tanda terima</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-300"></i>
                <span><strong>Status:</strong> Lengkapi semua dokumen untuk verifikasi manager</span>
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
                    <p class="text-blue-100 text-sm font-medium">Ready Kirim</p>
                    <p class="text-2xl font-bold">{{ count($proyekReady) }}</p>
                </div>
                <i class="fas fa-box text-2xl opacity-80"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Dalam Proses</p>
                    <p class="text-2xl font-bold">{{ count($pengirimanBerjalan) }}</p>
                </div>
                <i class="fas fa-truck text-2xl opacity-80"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Menunggu Verifikasi</p>
                    <p class="text-2xl font-bold">{{ collect($pengirimanBerjalan)->where('status_pengiriman', 'Menunggu Verifikasi')->count() }}</p>
                </div>
                <i class="fas fa-clock text-2xl opacity-80"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Selesai</p>
                    <p class="text-2xl font-bold">{{ count($pengirimanSelesai) }}</p>
                </div>
                <i class="fas fa-check-circle text-2xl opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex space-x-8">
            <button id="tabReady" onclick="switchTab('ready')" class="py-2 px-1 border-b-2 border-red-500 font-medium text-sm text-red-600 whitespace-nowrap tab-active">
                Ready Kirim ({{ count($proyekReady) }})
            </button>
            <button id="tabProses" onclick="switchTab('proses')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                Dalam Proses ({{ count($pengirimanBerjalan) }})
            </button>
            <button id="tabSelesai" onclick="switchTab('selesai')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                Selesai ({{ count($pengirimanSelesai) }})
            </button>
        </nav>
    </div>

    <!-- Tab Content: Ready Kirim -->
    <div id="contentReady" class="tab-content">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Proyek Siap Dikirim</h3>
            <p class="text-sm text-gray-600">Proyek yang pembayarannya sudah divalidasi marketing dan siap untuk pengiriman</p>
        </div>

        @if(count($proyekReady) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal ACC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC Marketing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($proyekReady as $proyek)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $proyek['kode_proyek'] }}</div>
                                <div class="text-sm text-gray-500">{{ $proyek['nama_proyek'] }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $proyek['nama_instansi'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($proyek['nilai_proyek'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('d/m/Y', strtotime($proyek['tanggal_acc'])) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $proyek['pic_marketing'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="buatPengiriman({{ $proyek['id'] }})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                <span>Buat Pengiriman</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Proyek Ready</h3>
            <p class="text-gray-500">Semua proyek sudah dalam proses pengiriman atau belum di-ACC marketing</p>
        </div>
        @endif
    </div>

    <!-- Tab Content: Dalam Proses -->
    <div id="contentProses" class="tab-content hidden">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Pengiriman Dalam Proses</h3>
            <p class="text-sm text-gray-600">Pantau progress pengiriman dan lengkapi dokumentasi</p>
        </div>

        @if(count($pengirimanBerjalan) > 0)
        <div class="space-y-4">
            @foreach($pengirimanBerjalan as $pengiriman)
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $pengiriman['kode_proyek'] }}</h4>
                        <p class="text-gray-600">{{ $pengiriman['nama_proyek'] }} - {{ $pengiriman['nama_instansi'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Surat Jalan: {{ $pengiriman['nomor_surat_jalan'] }} |
                            Admin: {{ $pengiriman['admin_pengiriman'] }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($pengiriman['status_pengiriman'] == 'Dalam Perjalanan')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-truck mr-1"></i>
                                Dalam Perjalanan
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-clock mr-1"></i>
                                Menunggu Verifikasi
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Progress Tracker -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress Dokumentasi</span>
                        <span class="text-sm text-gray-500">
                            {{ count(array_filter($pengiriman['progress'])) }}/{{ count($pengiriman['progress']) }} Selesai
                        </span>
                    </div>
                    <div class="grid grid-cols-5 gap-2">
                        <div class="text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $pengiriman['progress']['surat_jalan'] ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <p class="text-xs mt-1">Surat Jalan</p>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $pengiriman['progress']['foto_keberangkatan'] ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                <i class="fas fa-camera"></i>
                            </div>
                            <p class="text-xs mt-1">Foto Berangkat</p>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $pengiriman['progress']['foto_perjalanan'] ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                <i class="fas fa-road"></i>
                            </div>
                            <p class="text-xs mt-1">Foto Perjalanan</p>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $pengiriman['progress']['foto_diterima'] ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <p class="text-xs mt-1">Foto Diterima</p>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $pengiriman['progress']['tanda_terima'] ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                <i class="fas fa-signature"></i>
                            </div>
                            <p class="text-xs mt-1">Tanda Terima</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button onclick="lihatDetailPengiriman({{ $pengiriman['id'] }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-eye"></i>
                        <span>Detail</span>
                    </button>
                    @if($pengiriman['status_pengiriman'] == 'Dalam Perjalanan')
                    <button onclick="updateDokumentasi({{ $pengiriman['id'] }})" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-upload"></i>
                        <span>Update Dokumentasi</span>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-truck text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Pengiriman</h3>
            <p class="text-gray-500">Belum ada pengiriman yang sedang dalam proses</p>
        </div>
        @endif
    </div>

    <!-- Tab Content: Selesai -->
    <div id="contentSelesai" class="tab-content hidden">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Pengiriman Selesai</h3>
            <p class="text-sm text-gray-600">Riwayat pengiriman yang sudah selesai dan terverifikasi</p>
        </div>

        @if(count($pengirimanSelesai) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surat Jalan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verifikator</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengirimanSelesai as $selesai)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $selesai['kode_proyek'] }}</div>
                                <div class="text-sm text-gray-500">{{ $selesai['nama_proyek'] }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $selesai['nama_instansi'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $selesai['nomor_surat_jalan'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $selesai['admin_pengiriman'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('d/m/Y', strtotime($selesai['tanggal_selesai'])) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $selesai['verifikator'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="lihatDetailPengiriman({{ $selesai['id'] }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-check-circle text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pengiriman Selesai</h3>
            <p class="text-gray-500">Pengiriman yang sudah selesai akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Buat Pengiriman -->
<div id="modalBuatPengiriman" class="fixed inset-0  bg-black/50 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Buat Pengiriman Baru</h3>
                    <button onclick="tutupModal('modalBuatPengiriman')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="formBuatPengiriman" class="p-6">
                <div class="space-y-4">
                    <!-- Info Proyek -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Informasi Proyek</h4>
                        <div id="infoProyek" class="text-sm text-blue-800">
                            <!-- Will be filled by JavaScript -->
                        </div>
                    </div>

                    <!-- Surat Jalan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat Jalan</label>
                            <input type="text" id="nomorSuratJalan" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                                   placeholder="SJ/2024/11/001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat Jalan</label>
                            <input type="date" id="tanggalSuratJalan" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Surat Jalan (PDF/JPG)</label>
                        <input type="file" id="fileSuratJalan" accept=".pdf,.jpg,.jpeg,.png" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG. Maksimal 5MB</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Keberangkatan</label>
                        <input type="file" id="fotoKeberangkatan" accept=".jpg,.jpeg,.png" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 3MB</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Pengiriman</label>
                        <textarea id="catatanPengiriman" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                                  placeholder="Catatan tambahan untuk pengiriman..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="tutupModal('modalBuatPengiriman')"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan & Mulai Pengiriman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update Dokumentasi -->
<div id="modalUpdateDokumentasi" class="fixed inset-0  bg-black/50 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Update Dokumentasi Pengiriman</h3>
                    <button onclick="tutupModal('modalUpdateDokumentasi')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="formUpdateDokumentasi" class="p-6">
                <div class="space-y-4">
                    <div id="infoPengirimanUpdate" class="bg-blue-50 p-4 rounded-lg">
                        <!-- Will be filled by JavaScript -->
                    </div>

                    <div id="uploadFotoPerjalanan" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Perjalanan</label>
                        <input type="file" id="fotoPerjalanan" accept=".jpg,.jpeg,.png"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-500 mt-1">Foto saat dalam perjalanan menuju lokasi</p>
                    </div>

                    <div id="uploadFotoDiterima" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Barang Diterima</label>
                        <input type="file" id="fotoDiterima" accept=".jpg,.jpeg,.png"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-500 mt-1">Foto saat barang diserahkan dan diterima klien</p>
                    </div>

                    <div id="uploadTandaTerima" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Tanda Terima</label>
                        <input type="file" id="tandaTerima" accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-500 mt-1">Tanda terima yang sudah ditandatangani klien</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Update</label>
                        <textarea id="catatanUpdate" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                                  placeholder="Catatan tambahan untuk update ini..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="tutupModal('modalUpdateDokumentasi')"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-upload mr-2"></i>Update Dokumentasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Pengiriman Selesai -->
<div id="modalDetailSelesai" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-semibold text-white">Detail Pengiriman Selesai</h3>
                        <p class="text-green-100 text-sm mt-1">Informasi lengkap pengiriman yang telah selesai</p>
                    </div>
                    <button onclick="tutupModal('modalDetailSelesai')" class="text-white hover:text-green-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Header Info -->
                <div id="headerDetailSelesai" class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <!-- Will be filled by JavaScript -->
                </div>

                <!-- Tab Navigation untuk Detail -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-8">
                        <button id="tabInfoProyek" onclick="switchDetailTab('infoProyek')" class="py-2 px-1 border-b-2 border-green-500 font-medium text-sm text-green-600 whitespace-nowrap detail-tab-active">
                            Informasi Proyek
                        </button>
                        <button id="tabDokumentasi" onclick="switchDetailTab('dokumentasi')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Dokumentasi
                        </button>
                        <button id="tabTimeline" onclick="switchDetailTab('timeline')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Timeline
                        </button>
                        <button id="tabVerifikasi" onclick="switchDetailTab('verifikasi')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Verifikasi
                        </button>
                    </nav>
                </div>

                <!-- Tab Content: Info Proyek -->
                <div id="contentInfoProyek" class="detail-tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-900 mb-3">Informasi Proyek</h4>
                                <div id="infoProyekDetail" class="space-y-2 text-sm">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-900 mb-3">Informasi Pengiriman</h4>
                                <div id="infoPengirimanDetail" class="space-y-2 text-sm">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Dokumentasi -->
                <div id="contentDokumentasi" class="detail-tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Surat Jalan -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Surat Jalan</h4>
                                <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                            </div>
                            <div class="space-y-2">
                                <div class="bg-white p-3 rounded border">
                                    <div class="flex items-center justify-center h-32 bg-gray-100 rounded mb-2">
                                        <i class="fas fa-file-pdf text-red-500 text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-center">surat_jalan_demo.pdf</p>
                                    <div class="flex gap-2 mt-2">
                                        <button class="flex-1 bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button class="flex-1 bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Keberangkatan -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Foto Keberangkatan</h4>
                                <i class="fas fa-camera text-green-600 text-xl"></i>
                            </div>
                            <div class="space-y-2">
                                <div class="bg-white p-3 rounded border">
                                    <div class="flex items-center justify-center h-32 bg-gray-100 rounded mb-2">
                                        <i class="fas fa-image text-gray-500 text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-center">foto_berangkat.jpg</p>
                                    <button class="w-full bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 mt-2">
                                        <i class="fas fa-eye mr-1"></i>Lihat Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Perjalanan -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Foto Perjalanan</h4>
                                <i class="fas fa-road text-yellow-600 text-xl"></i>
                            </div>
                            <div class="space-y-2">
                                <div class="bg-white p-3 rounded border">
                                    <div class="flex items-center justify-center h-32 bg-gray-100 rounded mb-2">
                                        <i class="fas fa-image text-gray-500 text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-center">foto_perjalanan.jpg</p>
                                    <button class="w-full bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 mt-2">
                                        <i class="fas fa-eye mr-1"></i>Lihat Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Barang Diterima -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Foto Diterima</h4>
                                <i class="fas fa-handshake text-purple-600 text-xl"></i>
                            </div>
                            <div class="space-y-2">
                                <div class="bg-white p-3 rounded border">
                                    <div class="flex items-center justify-center h-32 bg-gray-100 rounded mb-2">
                                        <i class="fas fa-image text-gray-500 text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-center">foto_diterima.jpg</p>
                                    <button class="w-full bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 mt-2">
                                        <i class="fas fa-eye mr-1"></i>Lihat Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Terima -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Tanda Terima</h4>
                                <i class="fas fa-signature text-indigo-600 text-xl"></i>
                            </div>
                            <div class="space-y-2">
                                <div class="bg-white p-3 rounded border">
                                    <div class="flex items-center justify-center h-32 bg-gray-100 rounded mb-2">
                                        <i class="fas fa-file-signature text-indigo-500 text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-center">tanda_terima.pdf</p>
                                    <div class="flex gap-2 mt-2">
                                        <button class="flex-1 bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                        <button class="flex-1 bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Kelengkapan -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Status Kelengkapan</h4>
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Surat Jalan</span>
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Foto Keberangkatan</span>
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Foto Perjalanan</span>
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Foto Diterima</span>
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Tanda Terima</span>
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <div class="flex items-center justify-center bg-green-100 text-green-800 px-3 py-2 rounded-full">
                                        <i class="fas fa-check mr-2"></i>
                                        <span class="text-sm font-medium">Dokumen Lengkap</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Timeline -->
                <div id="contentTimeline" class="detail-tab-content hidden">
                    <div class="max-w-3xl">
                        <div class="relative">
                            <!-- Timeline Line -->
                            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-300"></div>

                            <!-- Timeline Items -->
                            <div class="space-y-6">
                                <!-- Pengiriman Dibuat -->
                                <div class="relative flex items-start">
                                    <div class="absolute left-6 w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow-md"></div>
                                    <div class="ml-16">
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-semibold text-gray-900">Pengiriman Dibuat</h4>
                                                <span class="text-xs text-gray-500">13 Nov 2024, 08:30</span>
                                            </div>
                                            <p class="text-sm text-gray-600">Surat jalan dibuat dan foto keberangkatan diupload</p>
                                            <p class="text-xs text-gray-500 mt-1">Oleh: Roni Purchasing</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dalam Perjalanan -->
                                <div class="relative flex items-start">
                                    <div class="absolute left-6 w-4 h-4 bg-yellow-500 rounded-full border-2 border-white shadow-md"></div>
                                    <div class="ml-16">
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-semibold text-gray-900">Dalam Perjalanan</h4>
                                                <span class="text-xs text-gray-500">13 Nov 2024, 10:15</span>
                                            </div>
                                            <p class="text-sm text-gray-600">Foto perjalanan diupload, pengiriman dalam perjalanan ke lokasi</p>
                                            <p class="text-xs text-gray-500 mt-1">Oleh: Roni Purchasing</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Barang Diterima -->
                                <div class="relative flex items-start">
                                    <div class="absolute left-6 w-4 h-4 bg-purple-500 rounded-full border-2 border-white shadow-md"></div>
                                    <div class="ml-16">
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-semibold text-gray-900">Barang Diterima</h4>
                                                <span class="text-xs text-gray-500">13 Nov 2024, 15:30</span>
                                            </div>
                                            <p class="text-sm text-gray-600">Barang berhasil diserahkan dan diterima oleh klien. Foto dokumentasi dan tanda terima diupload</p>
                                            <p class="text-xs text-gray-500 mt-1">Oleh: Roni Purchasing</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menunggu Verifikasi -->
                                <div class="relative flex items-start">
                                    <div class="absolute left-6 w-4 h-4 bg-orange-500 rounded-full border-2 border-white shadow-md"></div>
                                    <div class="ml-16">
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-semibold text-gray-900">Menunggu Verifikasi</h4>
                                                <span class="text-xs text-gray-500">13 Nov 2024, 15:45</span>
                                            </div>
                                            <p class="text-sm text-gray-600">Semua dokumentasi lengkap, menunggu verifikasi dari manager</p>
                                            <p class="text-xs text-gray-500 mt-1">Status: Otomatis</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Verifikasi Selesai -->
                                <div class="relative flex items-start">
                                    <div class="absolute left-6 w-4 h-4 bg-green-500 rounded-full border-2 border-white shadow-md"></div>
                                    <div class="ml-16">
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-semibold text-gray-900">Pengiriman Selesai</h4>
                                                <span class="text-xs text-gray-500">14 Nov 2024, 10:15</span>
                                            </div>
                                            <p class="text-sm text-gray-600">Dokumentasi telah diverifikasi dan pengiriman dinyatakan selesai</p>
                                            <p class="text-xs text-gray-500 mt-1">Oleh: Manager Purchasing</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Verifikasi -->
                <div id="contentVerifikasi" class="detail-tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 mb-3 flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Status Verifikasi
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-green-800">Kelengkapan Dokumen</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                            <i class="fas fa-check mr-1"></i>Lengkap
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-green-800">Kualitas Foto</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                            <i class="fas fa-check mr-1"></i>Baik
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-green-800">Tanda Terima</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                            <i class="fas fa-check mr-1"></i>Valid
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-green-800">Waktu Pengiriman</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                            <i class="fas fa-check mr-1"></i>Sesuai
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Detail Verifikasi</h4>
                                <div class="space-y-3 text-sm">
                                    <div>
                                        <span class="text-gray-600">Verifikator:</span>
                                        <span class="font-medium text-gray-900 ml-2">Manager Purchasing</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Tanggal Verifikasi:</span>
                                        <span class="font-medium text-gray-900 ml-2">14 November 2024, 10:15</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Waktu Proses:</span>
                                        <span class="font-medium text-gray-900 ml-2">18 jam 30 menit</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Status Akhir:</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium ml-2">
                                            Selesai
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 mb-3">Catatan Verifikasi</h4>
                                <p class="text-sm text-blue-800 italic">
                                    "Semua dokumentasi lengkap dan sesuai standar. Pengiriman berhasil dilakukan dengan baik.
                                    Klien memberikan konfirmasi positif terhadap kualitas dan ketepatan waktu pengiriman."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button onclick="printDetailSelesai()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak Laporan
                    </button>
                    <button onclick="downloadDetailSelesai()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>Download PDF
                    </button>
                    <button onclick="tutupModal('modalDetailSelesai')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pengiriman Dalam Proses -->
<div id="modalDetailProses" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-yellow-600 to-orange-600">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-semibold text-white">Detail Pengiriman Dalam Proses</h3>
                        <p class="text-yellow-100 text-sm mt-1">Pantau progress dan kelola dokumentasi pengiriman</p>
                    </div>
                    <button onclick="tutupModal('modalDetailProses')" class="text-white hover:text-yellow-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Header Info -->
                <div id="headerDetailProses" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <!-- Will be filled by JavaScript -->
                </div>

                <!-- Progress Overview -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Progress Pengiriman</h4>
                    <div id="progressOverview" class="bg-gray-50 rounded-lg p-4">
                        <!-- Will be filled by JavaScript -->
                    </div>
                </div>

                <!-- Tab Navigation untuk Detail Proses -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-8">
                        <button id="tabInfoPengiriman" onclick="switchProsesTab('infoPengiriman')" class="py-2 px-1 border-b-2 border-yellow-500 font-medium text-sm text-yellow-600 whitespace-nowrap proses-tab-active">
                            Info Pengiriman
                        </button>
                        <button id="tabDokumentasiProses" onclick="switchProsesTab('dokumentasiProses')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Dokumentasi
                        </button>
                        <button id="tabTimelineProses" onclick="switchProsesTab('timelineProses')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Timeline Real-time
                        </button>
                        <button id="tabUpdateDokumen" onclick="switchProsesTab('updateDokumen')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
                            Update Dokumen
                        </button>
                    </nav>
                </div>

                <!-- Tab Content: Info Pengiriman -->
                <div id="contentInfoPengiriman" class="proses-tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-900 mb-3">Informasi Proyek</h4>
                                <div id="infoProyekProses" class="space-y-2 text-sm">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-900 mb-3">Status Terkini</h4>
                                <div id="statusTerkini" class="space-y-2 text-sm">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-900 mb-3">Detail Pengiriman</h4>
                                <div id="detailPengirimanProses" class="space-y-2 text-sm">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                                <h4 class="font-semibold text-yellow-900 mb-3">Estimasi & Target</h4>
                                <div id="estimasiTarget" class="space-y-2 text-sm">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Dokumentasi Proses -->
                <div id="contentDokumentasiProses" class="proses-tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Surat Jalan -->
                        <div id="dokumenSuratJalan" class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Surat Jalan</h4>
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    <span class="text-xs text-green-600 font-medium">Tersedia</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="bg-white p-3 rounded border">
                                    <div class="flex items-center justify-center h-24 bg-gray-100 rounded mb-2">
                                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-center">SJ_2024_11_001.pdf</p>
                                    <button class="w-full bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 mt-2">
                                        <i class="fas fa-eye mr-1"></i>Lihat
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Keberangkatan -->
                        <div id="dokumenFotoBerangkat" class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Foto Keberangkatan</h4>
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    <span class="text-xs text-green-600 font-medium">Tersedia</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="bg-white p-3 rounded border">
                                    <div class="flex items-center justify-center h-24 bg-gray-100 rounded mb-2">
                                        <i class="fas fa-image text-gray-500 text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-center">berangkat_08_30.jpg</p>
                                    <button class="w-full bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 mt-2">
                                        <i class="fas fa-eye mr-1"></i>Lihat Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Perjalanan -->
                        <div id="dokumenFotoPerjalanan" class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Foto Perjalanan</h4>
                                <div id="statusFotoPerjalanan" class="flex items-center">
                                    <!-- Will be filled by JavaScript based on progress -->
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div id="containerFotoPerjalanan" class="bg-white p-3 rounded border">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- Foto Barang Diterima -->
                        <div id="dokumenFotoDiterima" class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Foto Diterima</h4>
                                <div id="statusFotoDiterima" class="flex items-center">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div id="containerFotoDiterima" class="bg-white p-3 rounded border">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Terima -->
                        <div id="dokumenTandaTerima" class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">Tanda Terima</h4>
                                <div id="statusTandaTerima" class="flex items-center">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div id="containerTandaTerima" class="bg-white p-3 rounded border">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- Progress Kelengkapan -->
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-yellow-900">Progress Kelengkapan</h4>
                                <i class="fas fa-chart-pie text-yellow-600 text-xl"></i>
                            </div>
                            <div id="progressKelengkapan" class="space-y-3">
                                <!-- Will be filled by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Timeline Real-time -->
                <div id="contentTimelineProses" class="proses-tab-content hidden">
                    <div class="max-w-3xl">
                        <div class="relative">
                            <!-- Timeline Line -->
                            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-300"></div>

                            <!-- Timeline Items -->
                            <div id="timelineItems" class="space-y-6">
                                <!-- Will be filled by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Update Dokumen -->
                <div id="contentUpdateDokumen" class="proses-tab-content hidden">
                    <div class="max-w-2xl">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                <div>
                                    <h4 class="font-semibold text-blue-900">Update Dokumentasi</h4>
                                    <p class="text-sm text-blue-700">Upload dokumen yang belum tersedia untuk melengkapi pengiriman</p>
                                </div>
                            </div>
                        </div>

                        <div id="formUpdateDokumentasi" class="space-y-6">
                            <!-- Will be filled by JavaScript based on missing documents -->
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Last Update: <span id="lastUpdateTime">--</span></span>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="refreshDataProses()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh Data
                        </button>
                        <button onclick="updateDokumentasiModal()" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-upload mr-2"></i>Update Dokumen
                        </button>
                        <button onclick="tutupModal('modalDetailProses')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Data untuk JavaScript
const proyekData = @json($proyekReady);
const pengirimanData = @json($pengirimanBerjalan);

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

// Buat pengiriman
function buatPengiriman(proyekId) {
    const proyek = proyekData.find(p => p.id == proyekId);
    if (!proyek) return;

    // Fill project info
    document.getElementById('infoProyek').innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <strong>Kode Proyek:</strong> ${proyek.kode_proyek}<br>
                <strong>Nama Proyek:</strong> ${proyek.nama_proyek}<br>
                <strong>Instansi:</strong> ${proyek.nama_instansi}
            </div>
            <div>
                <strong>Nilai:</strong> Rp ${new Intl.NumberFormat('id-ID').format(proyek.nilai_proyek)}<br>
                <strong>PIC Marketing:</strong> ${proyek.pic_marketing}<br>
                <strong>Tanggal ACC:</strong> ${new Date(proyek.tanggal_acc).toLocaleDateString('id-ID')}
            </div>
        </div>
        <div class="mt-2">
            <strong>Alamat Pengiriman:</strong> ${proyek.alamat_pengiriman}<br>
            <strong>Kontak Penerima:</strong> ${proyek.kontak_penerima}
            ${proyek.catatan_khusus ? `<br><strong>Catatan Khusus:</strong> ${proyek.catatan_khusus}` : ''}
        </div>
    `;

    // Set default date
    document.getElementById('tanggalSuratJalan').value = new Date().toISOString().split('T')[0];

    // Show modal
    document.getElementById('modalBuatPengiriman').classList.remove('hidden');
}

// Update dokumentasi
function updateDokumentasi(pengirimanId) {
    const pengiriman = pengirimanData.find(p => p.id == pengirimanId);
    if (!pengiriman) return;

    // Fill pengiriman info
    document.getElementById('infoPengirimanUpdate').innerHTML = `
        <h4 class="font-medium text-blue-900 mb-2">Informasi Pengiriman</h4>
        <div class="text-sm text-blue-800">
            <strong>Proyek:</strong> ${pengiriman.kode_proyek} - ${pengiriman.nama_proyek}<br>
            <strong>Instansi:</strong> ${pengiriman.nama_instansi}<br>
            <strong>Surat Jalan:</strong> ${pengiriman.nomor_surat_jalan}<br>
            <strong>Status:</strong> ${pengiriman.status_pengiriman}
        </div>
    `;

    // Show upload sections based on progress
    document.getElementById('uploadFotoPerjalanan').classList.toggle('hidden', pengiriman.progress.foto_perjalanan);
    document.getElementById('uploadFotoDiterima').classList.toggle('hidden', pengiriman.progress.foto_diterima);
    document.getElementById('uploadTandaTerima').classList.toggle('hidden', pengiriman.progress.tanda_terima);

    // Show modal
    document.getElementById('modalUpdateDokumentasi').classList.remove('hidden');
}

// Lihat detail pengiriman
function lihatDetailPengiriman(pengirimanId) {
    // Check if it's from in-progress data first
    const pengirimanInProgress = pengirimanData.find(p => p.id == pengirimanId);

    if (pengirimanInProgress) {
        // Open in-progress modal
        showDetailProses(pengirimanId);
        return;
    }

    // Data lengkap pengiriman selesai untuk demo
    const pengirimanSelesaiData = {
        3: {
            id: 3,
            proyek_id: 5,
            kode_proyek: 'PNW-2024-005',
            nama_proyek: 'Website Company Profile',
            nama_instansi: 'PT. Maju Jaya',
            nilai_proyek: 450000000,
            nomor_surat_jalan: 'SJ/2024/10/003',
            tanggal_surat_jalan: '2024-10-25',
            status_pengiriman: 'Selesai',
            tanggal_selesai: '2024-10-28',
            admin_pengiriman: 'Roni Purchasing',
            verifikator: 'Manager Purchasing',
            tanggal_verifikasi: '2024-10-30 10:15:00',
            alamat_pengiriman: 'Jl. Sudirman No. 45, Jakarta Selatan',
            kontak_penerima: 'Agus Wijaya (021-98765432)',
            pic_marketing: 'Maya Indah',
            tanggal_acc: '2024-10-20',
            estimasi_tiba: '2024-10-26 14:00:00',
            tanggal_berangkat: '2024-10-25 08:00:00',
            tanggal_diterima: '2024-10-26 13:45:00',
            catatan_verifikasi: 'Semua dokumentasi lengkap dan sesuai standar. Pengiriman berhasil dilakukan dengan baik. Klien memberikan konfirmasi positif terhadap kualitas dan ketepatan waktu pengiriman.'
        }
    };

    const data = pengirimanSelesaiData[pengirimanId];
    if (!data) {
        alert(`Detail pengiriman ID: ${pengirimanId}\n\nFitur detail akan menampilkan:\n- Semua dokumentasi yang telah diupload\n- Timeline pengiriman\n- Status verifikasi\n- Catatan dari manager`);
        return;
    }

    // Fill header info
    document.getElementById('headerDetailSelesai').innerHTML = `
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-lg font-bold text-green-900">${data.kode_proyek} - ${data.nama_proyek}</h4>
                <p class="text-green-700 text-sm">${data.nama_instansi}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>
                    Pengiriman Selesai
                </span>
                <p class="text-xs text-green-600 mt-1">Verifikasi: ${new Date(data.tanggal_verifikasi).toLocaleDateString('id-ID')}</p>
            </div>
        </div>
    `;

    // Fill project info
    document.getElementById('infoProyekDetail').innerHTML = `
        <div><span class="text-gray-500">Kode Proyek:</span> <span class="font-medium">${data.kode_proyek}</span></div>
        <div><span class="text-gray-500">Nama Proyek:</span> <span class="font-medium">${data.nama_proyek}</span></div>
        <div><span class="text-gray-500">Instansi:</span> <span class="font-medium">${data.nama_instansi}</span></div>
        <div><span class="text-gray-500">Nilai Proyek:</span> <span class="font-medium">Rp ${new Intl.NumberFormat('id-ID').format(data.nilai_proyek)}</span></div>
        <div><span class="text-gray-500">PIC Marketing:</span> <span class="font-medium">${data.pic_marketing}</span></div>
        <div><span class="text-gray-500">Tanggal ACC:</span> <span class="font-medium">${new Date(data.tanggal_acc).toLocaleDateString('id-ID')}</span></div>
        <div><span class="text-gray-500">Alamat Pengiriman:</span> <span class="font-medium">${data.alamat_pengiriman}</span></div>
        <div><span class="text-gray-500">Kontak Penerima:</span> <span class="font-medium">${data.kontak_penerima}</span></div>
    `;

    // Fill shipping info
    document.getElementById('infoPengirimanDetail').innerHTML = `
        <div><span class="text-gray-500">Nomor Surat Jalan:</span> <span class="font-medium">${data.nomor_surat_jalan}</span></div>
        <div><span class="text-gray-500">Tanggal Surat Jalan:</span> <span class="font-medium">${new Date(data.tanggal_surat_jalan).toLocaleDateString('id-ID')}</span></div>
        <div><span class="text-gray-500">Admin Pengiriman:</span> <span class="font-medium">${data.admin_pengiriman}</span></div>
        <div><span class="text-gray-500">Tanggal Berangkat:</span> <span class="font-medium">${new Date(data.tanggal_berangkat).toLocaleString('id-ID')}</span></div>
        <div><span class="text-gray-500">Tanggal Diterima:</span> <span class="font-medium">${new Date(data.tanggal_diterima).toLocaleString('id-ID')}</span></div>
        <div><span class="text-gray-500">Verifikator:</span> <span class="font-medium">${data.verifikator}</span></div>
        <div><span class="text-gray-500">Tanggal Verifikasi:</span> <span class="font-medium">${new Date(data.tanggal_verifikasi).toLocaleString('id-ID')}</span></div>
        <div><span class="text-gray-500">Status:</span> <span class="font-medium text-green-600">${data.status_pengiriman}</span></div>
    `;

    // Reset to first tab
    switchDetailTab('infoProyek');

    // Show modal
    document.getElementById('modalDetailSelesai').classList.remove('hidden');
}

// Switch detail modal tabs
function switchDetailTab(tabName) {
    // Hide all detail tab contents
    document.querySelectorAll('.detail-tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all detail tabs
    document.querySelectorAll('#modalDetailSelesai nav button').forEach(tab => {
        tab.classList.remove('border-green-500', 'text-green-600', 'detail-tab-active');
        tab.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    document.getElementById('content' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).classList.remove('hidden');

    // Add active class to selected tab
    const activeTab = document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-green-500', 'text-green-600', 'detail-tab-active');
}

// Print detail function
function printDetailSelesai() {
    alert('Fitur cetak laporan akan membuat dokumen PDF lengkap dengan semua dokumentasi pengiriman untuk keperluan arsip dan audit.');
}

// Download detail function
function downloadDetailSelesai() {
    alert('Fitur download PDF akan mengunduh laporan lengkap pengiriman dalam format PDF yang dapat disimpan sebagai dokumentasi.');
}

// Tutup modal
function tutupModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Show detail for in-progress shipment
function showDetailProses(pengirimanId) {
    const pengiriman = pengirimanData.find(p => p.id == pengirimanId);
    if (!pengiriman) {
        alert('Data pengiriman tidak ditemukan');
        return;
    }

    // Use correct status property
    const status = pengiriman.status || pengiriman.status_pengiriman || 'Unknown';

    // Fill header info
    document.getElementById('headerDetailProses').innerHTML = `
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-lg font-bold text-blue-900">${pengiriman.kode_proyek || 'N/A'} - ${pengiriman.nama_proyek || 'N/A'}</h4>
                <p class="text-blue-700 text-sm">${pengiriman.nama_instansi || 'N/A'}</p>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <div class="w-2 h-2 bg-blue-600 rounded-full mr-2"></div>
                    ${status}
                </div>
                <p class="text-blue-600 text-xs mt-1">SJ: ${pengiriman.nomor_surat_jalan || 'N/A'}</p>
            </div>
        </div>
    `;

    // Fill info tab content
    fillInfoPengirimanTab(pengiriman);

    // Fill documentation tab
    fillDokumentasiProsesTab(pengiriman);

    // Fill timeline tab
    fillTimelineRealtimeTab(pengiriman);

    // Fill update document form
    fillUpdateDokumenTab(pengiriman);

    switchProsesTab('info');
    document.getElementById('modalDetailProses').classList.remove('hidden');
}// Fill info pengiriman tab
function fillInfoPengirimanTab(pengiriman) {
    // Safe access to properties with defaults
    const nilaiProyek = pengiriman.nilai_proyek ? pengiriman.nilai_proyek.toLocaleString('id-ID') : 'Data tidak tersedia';
    const picMarketing = pengiriman.pic_marketing || 'Data tidak tersedia';
    const alamatPengiriman = pengiriman.alamat_pengiriman || 'Data tidak tersedia';
    const status = pengiriman.status || pengiriman.status_pengiriman || 'Unknown';

    document.getElementById('infoPengirimanContent').innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Proyek</label>
                    <p class="text-sm text-gray-900">${pengiriman.kode_proyek || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Proyek</label>
                    <p class="text-sm text-gray-900">${pengiriman.nama_proyek || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instansi</label>
                    <p class="text-sm text-gray-900">${pengiriman.nama_instansi || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Proyek</label>
                    <p class="text-sm text-gray-900">Rp ${nilaiProyek}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">PIC Marketing</label>
                    <p class="text-sm text-gray-900">${picMarketing}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Jalan</label>
                    <p class="text-sm text-gray-900">${pengiriman.nomor_surat_jalan || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat Jalan</label>
                    <p class="text-sm text-gray-900">${pengiriman.tanggal_surat_jalan || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ${status}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Pengiriman</label>
                    <p class="text-sm text-gray-900">${pengiriman.admin_pengiriman || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berangkat</label>
                    <p class="text-sm text-gray-900">${pengiriman.tanggal_berangkat || 'Belum berangkat'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi Tiba</label>
                    <p class="text-sm text-gray-900">${pengiriman.estimasi_tiba || 'Belum ditentukan'}</p>
                </div>
            </div>
        </div>
        <div class="mt-6 pt-6 border-t border-gray-200">
            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
            <p class="text-sm text-gray-900">${alamatPengiriman}</p>
        </div>
    `;
}

// Fill dokumentasi proses tab
function fillDokumentasiProsesTab(pengiriman) {
    // Sample documentation status for in-progress shipments
    const dokStatus = [
        { nama: 'Surat Jalan', status: 'Lengkap', file: 'surat_jalan.pdf', uploaded: '2024-10-24 14:30' },
        { nama: 'Invoice', status: 'Lengkap', file: 'invoice.pdf', uploaded: '2024-10-24 14:35' },
        { nama: 'Foto Barang', status: 'Dalam Proses', file: null, uploaded: null },
        { nama: 'Tanda Terima', status: 'Menunggu', file: null, uploaded: null },
        { nama: 'Konfirmasi Penerima', status: 'Menunggu', file: null, uploaded: null }
    ];

    const dokHtml = dokStatus.map(dok => {
        const statusClass = {
            'Lengkap': 'bg-green-100 text-green-800',
            'Dalam Proses': 'bg-yellow-100 text-yellow-800',
            'Menunggu': 'bg-gray-100 text-gray-800'
        };

        return `
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        ${dok.status === 'Lengkap' ?
                            '<div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"><svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>' :
                            '<div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0h8v12H6V4z" clip-rule="evenodd"></path></svg></div>'
                        }
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">${dok.nama}</h4>
                        ${dok.uploaded ? `<p class="text-xs text-gray-500">Diupload: ${dok.uploaded}</p>` : ''}
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass[dok.status]}">
                        ${dok.status}
                    </span>
                    ${dok.file ? `<button class="text-blue-600 hover:text-blue-500 text-sm font-medium">Lihat</button>` : ''}
                </div>
            </div>
        `;
    }).join('');

    document.getElementById('dokumentasiProsesContent').innerHTML = `
        <div class="space-y-4">
            ${dokHtml}
        </div>
    `;
}

// Fill timeline realtime tab
function fillTimelineRealtimeTab(pengiriman) {
    // Safe access to estimasi_tiba
    const estimasiTiba = pengiriman.estimasi_tiba || 'Belum ditentukan';

    // Sample timeline data for in-progress shipments
    const timelineData = [
        {
            waktu: '2024-10-24 14:30',
            status: 'Pengiriman Dibuat',
            detail: 'Pengiriman dibuat oleh admin purchasing',
            selesai: true
        },
        {
            waktu: '2024-10-24 14:35',
            status: 'Dokumentasi Diupload',
            detail: 'Surat jalan dan invoice telah diupload',
            selesai: true
        },
        {
            waktu: '2024-10-24 15:00',
            status: 'Barang Siap Kirim',
            detail: 'Barang telah dikemas dan siap untuk pengiriman',
            selesai: true
        },
        {
            waktu: pengiriman.tanggal_berangkat || '2024-10-25 08:00',
            status: 'Barang Dikirim',
            detail: 'Barang telah berangkat menuju lokasi tujuan',
            selesai: true
        },
        {
            waktu: '',
            status: 'Sedang Dalam Perjalanan',
            detail: 'Tracking pengiriman aktif, estimasi tiba: ' + estimasiTiba,
            selesai: false
        }
    ];

    const timelineHtml = timelineData.map((item, index) => `
        <div class="flex">
            <div class="flex flex-col items-center mr-4">
                <div class="w-4 h-4 ${item.selesai ? 'bg-blue-600' : 'bg-gray-300'} rounded-full"></div>
                ${index < timelineData.length - 1 ? `<div class="w-0.5 h-12 ${item.selesai ? 'bg-blue-600' : 'bg-gray-300'} mt-2"></div>` : ''}
            </div>
            <div class="pb-8">
                <h4 class="text-sm font-semibold ${item.selesai ? 'text-blue-900' : 'text-gray-500'}">${item.status}</h4>
                <p class="text-sm text-gray-600 mt-1">${item.detail}</p>
                ${item.waktu ? `<p class="text-xs text-gray-400 mt-1">${item.waktu}</p>` : ''}
            </div>
        </div>
    `).join('');

    document.getElementById('timelineRealtimeContent').innerHTML = `
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="animate-pulse w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                <h4 class="text-sm font-medium text-blue-900">Status Real-time</h4>
            </div>
            <p class="text-sm text-blue-700 mt-2">Pengiriman sedang dalam perjalanan menuju lokasi tujuan. Update terakhir: ${new Date().toLocaleString('id-ID')}</p>
        </div>
        <div class="space-y-2">
            ${timelineHtml}
        </div>
    `;
}// Fill update dokumen tab
function fillUpdateDokumenTab(pengiriman) {
    document.getElementById('updateDokumenContent').innerHTML = `
        <form class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Dokumen</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih jenis dokumen</option>
                    <option value="foto_barang">Foto Barang</option>
                    <option value="tanda_terima">Tanda Terima</option>
                    <option value="konfirmasi_penerima">Konfirmasi Penerima</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                    <input type="file" class="hidden" id="fileUploadProses" multiple accept=".pdf,.jpg,.jpeg,.png">
                    <label for="fileUploadProses" class="cursor-pointer">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-medium text-blue-600 hover:text-blue-500">Klik untuk upload</span> atau drag and drop
                        </p>
                        <p class="text-xs text-gray-500">PDF, JPG, PNG hingga 10MB</p>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tambahkan catatan untuk update dokumentasi..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalDetailProses').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    Upload Dokumen
                </button>
            </div>
        </form>
    `;
}

// Tab switching untuk modal proses
function switchProsesTab(tab) {
    // Hide all tab contents
    document.querySelectorAll('.tab-proses-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tabs
    document.querySelectorAll('#modalDetailProses .border-b button').forEach(tabBtn => {
        tabBtn.classList.remove('border-blue-500', 'text-blue-600');
        tabBtn.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    const contentMap = {
        'info': 'infoPengirimanContent',
        'dokumentasi': 'dokumentasiProsesContent',
        'timeline': 'timelineRealtimeContent',
        'update': 'updateDokumenContent'
    };

    if (contentMap[tab]) {
        document.getElementById(contentMap[tab]).classList.remove('hidden');
    }

    // Add active class to selected tab
    const tabButton = document.querySelector(`#modalDetailProses button[onclick="switchProsesTab('${tab}')"]`);
    if (tabButton) {
        tabButton.classList.remove('border-transparent', 'text-gray-500');
        tabButton.classList.add('border-blue-500', 'text-blue-600');
    }
}

// Form submissions
document.getElementById('formBuatPengiriman').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const nomorSuratJalan = document.getElementById('nomorSuratJalan').value;

    // Simulate API call
    const btn = e.target.querySelector('button[type="submit"]');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    btn.disabled = true;

    setTimeout(() => {
        alert(`Pengiriman berhasil dibuat!\n\nNomor Surat Jalan: ${nomorSuratJalan}\nStatus: Dalam Perjalanan\n\nAnda dapat melakukan update dokumentasi selama perjalanan.`);
        tutupModal('modalBuatPengiriman');
        location.reload();
    }, 2000);
});

document.getElementById('formUpdateDokumentasi').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = e.target.querySelector('button[type="submit"]');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupload...';
    btn.disabled = true;

    setTimeout(() => {
        alert('Dokumentasi berhasil diupdate!\n\nJika semua dokumen sudah lengkap, status akan berubah menjadi "Menunggu Verifikasi" untuk review manager.');
        tutupModal('modalUpdateDokumentasi');
        location.reload();
    }, 2000);
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        const modal = e.target;
        modal.classList.add('hidden');
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.fixed.inset-0').forEach(modal => {
            modal.classList.add('hidden');
        });
    }
});

// Auto refresh untuk update status real-time
setInterval(() => {
    // Check for status updates
    console.log('Checking for shipping status updates...');
}, 60000); // Every minute

// Show notification for demo
setTimeout(() => {
    showNotification('Sistem pengiriman siap digunakan! Mulai dengan memilih proyek yang ready untuk dikirim.', 'info');
}, 1000);

function showNotification(message, type = 'info') {
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

    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}
</script>
@endsection
