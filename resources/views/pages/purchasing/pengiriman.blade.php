@extends('layouts.app')

@section('content')

<!-- Header Section -->
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Manajemen Pengiriman</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">Kelola pengiriman proyek yang sudah diverifikasi pembayarannya</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-shipping-fast text-4xl sm:text-5xl lg:text-6xl text-red-200"></i>
        </div>
    </div>
</div>

<!-- Alert Info -->
<div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700">
                <strong>Info:</strong> Hanya proyek dengan pembayaran yang sudah diverifikasi oleh admin keuangan yang bisa dikirim (baik lunas maupun belum lunas).
            </p>
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
                    <p class="text-blue-100 text-sm">Ready Kirim</p>
                    <p class="text-2xl font-bold">{{ count($proyekReady) }}</p>
                </div>
                <i class="fas fa-box text-2xl text-blue-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Dalam Proses</p>
                    <p class="text-2xl font-bold">{{ count($pengirimanBerjalan) }}</p>
                </div>
                <i class="fas fa-truck text-2xl text-yellow-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Selesai</p>
                    <p class="text-2xl font-bold">{{ count($pengirimanSelesai) }}</p>
                </div>
                <i class="fas fa-check-circle text-2xl text-green-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Kirim</p>
                    <p class="text-2xl font-bold">{{ count($pengirimanBerjalan) + count($pengirimanSelesai) }}</p>
                </div>
                <i class="fas fa-chart-line text-2xl text-purple-200"></i>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button id="tabReady" onclick="switchTab('ready')" 
                    class="border-red-500 text-red-600 tab-active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-box mr-2"></i>Ready Kirim
            </button>
            <button id="tabProses" onclick="switchTab('proses')" 
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-truck mr-2"></i>Dalam Proses
            </button>
            <button id="tabSelesai" onclick="switchTab('selesai')" 
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                <i class="fas fa-check-circle mr-2"></i>Selesai
            </button>
        </nav>
    </div>

    <!-- Tab Content: Ready Kirim -->
    <div id="contentReady" class="tab-content">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Proyek Ready untuk Dikirim</h3>
            <p class="text-sm text-gray-600">Proyek dengan pembayaran yang sudah diverifikasi admin keuangan</p>
        </div>
        
        @if(count($proyekReady) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Penawaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($proyekReady as $proyek)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $proyek->no_penawaran }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $proyek->nama_proyek }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $proyek->instansi }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($proyek->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $proyek->status_pembayaran }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="buatPengiriman({{ $proyek->id_penawaran }})" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                    <i class="fas fa-plus mr-1"></i> Buat Pengiriman
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-box text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Tidak ada proyek yang ready untuk dikirim</p>
                <p class="text-xs text-gray-400 mt-2">Pastikan ada pembayaran yang sudah diverifikasi</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Dalam Proses -->
    <div id="contentProses" class="tab-content hidden">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Pengiriman Dalam Proses</h3>
            <p class="text-sm text-gray-600">Pengiriman yang sedang berlangsung</p>
        </div>
        
        @if(count($pengirimanBerjalan) > 0)
            <div class="grid gap-4">
                @foreach($pengirimanBerjalan as $pengiriman)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $pengiriman->nama_proyek }}</h4>
                            <p class="text-sm text-gray-600">{{ $pengiriman->instansi }}</p>
                            <p class="text-sm text-gray-500 mt-1">Surat Jalan: {{ $pengiriman->no_surat_jalan }}</p>
                            <p class="text-sm text-gray-500">Tanggal Kirim: {{ \Carbon\Carbon::parse($pengiriman->tanggal_kirim)->format('d M Y') }}</p>
                            
                            <!-- Status Dokumentasi -->
                            <div class="mt-3">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        $docs = [
                                            ['field' => 'foto_berangkat', 'label' => 'Berangkat', 'icon' => 'fas fa-camera'],
                                            ['field' => 'foto_perjalanan', 'label' => 'Perjalanan', 'icon' => 'fas fa-road'],
                                            ['field' => 'foto_sampai', 'label' => 'Sampai', 'icon' => 'fas fa-map-marker-alt'],
                                            ['field' => 'tanda_terima', 'label' => 'TTD', 'icon' => 'fas fa-signature']
                                        ];
                                    @endphp
                                    
                                    @foreach($docs as $doc)
                                        @if($pengiriman->{$doc['field']})
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 mr-1 mb-1">
                                                <i class="{{ $doc['icon'] }} mr-1"></i>{{ $doc['label'] }}
                                                <button onclick="viewFile('{{ $pengiriman->{$doc['field']} }}')" 
                                                        class="ml-2 text-green-600 hover:text-green-800">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500 mr-1 mb-1">
                                                <i class="{{ $doc['icon'] }} mr-1"></i>{{ $doc['label'] }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                                
                                @php
                                    $totalDocs = 4;
                                    $completedDocs = collect($docs)->filter(function($doc) use ($pengiriman) {
                                        return $pengiriman->{$doc['field']};
                                    })->count();
                                    $progressPercent = ($completedDocs / $totalDocs) * 100;
                                @endphp
                                
                                <div class="mt-2">
                                    <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                        <span>Dokumentasi: {{ $completedDocs }}/{{ $totalDocs }}</span>
                                        <span>{{ round($progressPercent) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $pengiriman->status_verifikasi == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($pengiriman->status_verifikasi) }}
                            </span>
                            <div class="mt-2">
                                <button onclick="updateDokumentasi({{ $pengiriman->id_pengiriman }})" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                    <i class="fas fa-upload mr-1"></i>Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-truck text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Tidak ada pengiriman dalam proses</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Selesai -->
    <div id="contentSelesai" class="tab-content hidden">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Pengiriman Selesai</h3>
            <p class="text-sm text-gray-600">Riwayat pengiriman yang sudah completed</p>
        </div>
        
        @if(count($pengirimanSelesai) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surat Jalan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pengirimanSelesai as $pengiriman)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $pengiriman->no_surat_jalan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengiriman->nama_proyek }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengiriman->instansi }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($pengiriman->tanggal_kirim)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="lihatDetailSelesai({{ $pengiriman->id_pengiriman }})" 
                                        class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i> Lihat Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-check-circle text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Belum ada pengiriman yang selesai</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Buat Pengiriman -->
<div id="modalBuatPengiriman" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Buat Pengiriman Baru</h3>
                    <button onclick="tutupModal('modalBuatPengiriman')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('pengiriman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id_penawaran" name="id_penawaran">
                    
                    <div id="infoProyek" class="bg-gray-50 p-4 rounded-lg mb-4">
                        <!-- Info proyek akan diisi via JavaScript -->
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Surat Jalan</label>
                            <input type="text" name="no_surat_jalan" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kirim</label>
                            <input type="date" name="tanggal_kirim" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                        <textarea name="alamat_kirim" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Surat Jalan (PDF/Gambar)</label>
                        <input type="file" name="file_surat_jalan" accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 5MB, format: PDF, JPG, PNG</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="tutupModal('modalBuatPengiriman')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-1"></i> Simpan Pengiriman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Dokumentasi -->
<div id="modalUpdateDokumentasi" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Update Dokumentasi Pengiriman</h3>
                    <button onclick="tutupModal('modalUpdateDokumentasi')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="formUpdateDokumentasi" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div id="infoPengirimanUpdate" class="bg-gray-50 p-4 rounded-lg mb-4">
                        <!-- Info pengiriman akan diisi via JavaScript -->
                    </div>
                    
                    <div id="formDokumentasiFields" class="space-y-4">
                        <!-- Field dokumentasi akan diisi via JavaScript -->
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="tutupModal('modalUpdateDokumentasi')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-upload mr-1"></i> Update Dokumentasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pengiriman Selesai -->
<div id="modalDetailSelesai" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Detail Pengiriman Selesai</h3>
                    <button onclick="tutupModal('modalDetailSelesai')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Header Info -->
                <div id="headerDetailSelesai" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg mb-6">
                    <!-- Header akan diisi via JavaScript -->
                </div>
                
                <!-- Tab Navigation untuk Detail -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button id="tabInfoDetail" onclick="switchDetailTab('info')" 
                                class="border-green-500 text-green-600 detail-tab-active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-info-circle mr-2"></i>Info Pengiriman
                        </button>
                        <button id="tabDokumentasiDetail" onclick="switchDetailTab('dokumentasi')" 
                                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-camera mr-2"></i>Dokumentasi
                        </button>
                        <button id="tabTimelineDetail" onclick="switchDetailTab('timeline')" 
                                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-history mr-2"></i>Timeline
                        </button>
                    </nav>
                </div>
                
                <!-- Tab Content Info -->
                <div id="contentInfo" class="detail-tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Proyek</h4>
                            <div id="infoProyekDetail" class="space-y-2 text-sm">
                                <!-- Info proyek akan diisi via JavaScript -->
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-3">Detail Pengiriman</h4>
                            <div id="infoPengirimanDetailSelesai" class="space-y-2 text-sm">
                                <!-- Info pengiriman akan diisi via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Content Dokumentasi -->
                <div id="contentDokumentasi" class="detail-tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div id="dokumentasiSelesaiContent">
                            <!-- Dokumentasi akan diisi via JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Tab Content Timeline -->
                <div id="contentTimeline" class="detail-tab-content hidden">
                    <div id="timelineSelesaiContent">
                        <!-- Timeline akan diisi via JavaScript -->
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <button onclick="tutupModal('modalDetailSelesai')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
function buatPengiriman(penawaranId) {
    // Set ID penawaran
    document.getElementById('id_penawaran').value = penawaranId;
    
    // Find project data (in real app, this would be from the controller data)
    const proyekData = @json($proyekReady);
    const proyek = proyekData.find(p => p.id_penawaran == penawaranId);
    
    if (proyek) {
        document.getElementById('infoProyek').innerHTML = `
            <h4 class="font-semibold text-gray-900 mb-2">Informasi Proyek</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">No. Penawaran:</span> <span class="font-medium">${proyek.no_penawaran}</span></div>
                <div><span class="text-gray-500">Nama Proyek:</span> <span class="font-medium">${proyek.nama_proyek}</span></div>
                <div><span class="text-gray-500">Instansi:</span> <span class="font-medium">${proyek.instansi}</span></div>
                <div><span class="text-gray-500">Nilai:</span> <span class="font-medium">Rp ${new Intl.NumberFormat('id-ID').format(proyek.total_harga)}</span></div>
            </div>
        `;                        // Set default alamat
                        if (proyek.alamat_instansi) {
                            document.querySelector('textarea[name="alamat_kirim"]').value = proyek.alamat_instansi;
                        }
    }

    // Set default date to today
    document.querySelector('input[name="tanggal_kirim"]').value = new Date().toISOString().split('T')[0];

    // Show modal
    document.getElementById('modalBuatPengiriman').classList.remove('hidden');
}

// Update dokumentasi
function updateDokumentasi(pengirimanId) {
    // Set form action - gunakan route Laravel yang benar
    document.getElementById('formUpdateDokumentasi').action = `/purchasing/pengiriman/${pengirimanId}/update-dokumentasi`;
    
    // Find pengiriman data
    const pengirimanData = @json($pengirimanBerjalan);
    const pengiriman = pengirimanData.find(p => p.id_pengiriman == pengirimanId);
    
    if (pengiriman) {
        document.getElementById('infoPengirimanUpdate').innerHTML = `
            <h4 class="font-semibold text-gray-900 mb-2">Informasi Pengiriman</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Surat Jalan:</span> <span class="font-medium">${pengiriman.no_surat_jalan}</span></div>
                <div><span class="text-gray-500">Proyek:</span> <span class="font-medium">${pengiriman.nama_proyek}</span></div>
                <div><span class="text-gray-500">Instansi:</span> <span class="font-medium">${pengiriman.instansi}</span></div>
                <div><span class="text-gray-500">Status:</span> <span class="font-medium">${pengiriman.status_verifikasi}</span></div>
            </div>
        `;

        // Build dokumentasi fields dengan status file yang sudah ada
        const dokumentasiFields = [
            { 
                name: 'foto_berangkat', 
                label: 'Foto Keberangkatan', 
                accept: '.jpg,.jpeg,.png',
                icon: 'fas fa-camera',
                current: pengiriman.foto_berangkat 
            },
            { 
                name: 'foto_perjalanan', 
                label: 'Foto Perjalanan', 
                accept: '.jpg,.jpeg,.png',
                icon: 'fas fa-road',
                current: pengiriman.foto_perjalanan 
            },
            { 
                name: 'foto_sampai', 
                label: 'Foto Sampai Tujuan', 
                accept: '.jpg,.jpeg,.png',
                icon: 'fas fa-map-marker-alt',
                current: pengiriman.foto_sampai 
            },
            { 
                name: 'tanda_terima', 
                label: 'Tanda Terima', 
                accept: '.pdf,.jpg,.jpeg,.png',
                icon: 'fas fa-signature',
                current: pengiriman.tanda_terima 
            }
        ];

        const fieldsHtml = dokumentasiFields.map(field => {
            const hasFile = field.current && field.current.trim() !== '';
            const statusBadge = hasFile ? 
                `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-2">
                    <i class="fas fa-check-circle mr-1"></i> File tersedia
                </span>` :
                `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 mb-2">
                    <i class="fas fa-times-circle mr-1"></i> Belum ada file
                </span>`;
            
            const currentFileInfo = hasFile ? 
                `<div class="mt-2 p-2 bg-blue-50 rounded border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-blue-700">
                            <i class="${field.icon} mr-2"></i>
                            <span>File saat ini: ${field.current.split('/').pop()}</span>
                        </div>
                        <button type="button" onclick="viewFile('${field.current}')" 
                                class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-eye mr-1"></i> Lihat
                        </button>
                    </div>
                </div>` : '';

            return `
                <div class="border border-gray-200 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="${field.icon} mr-2"></i>${field.label}
                    </label>
                    ${statusBadge}
                    <input type="file" name="${field.name}" accept="${field.accept}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">
                        ${hasFile ? 'Upload file baru untuk mengganti file yang ada' : 'Belum ada file, upload untuk menambahkan'}
                    </p>
                    ${currentFileInfo}
                </div>
            `;
        }).join('');

        document.getElementById('formDokumentasiFields').innerHTML = fieldsHtml;
    }

    // Show modal
    document.getElementById('modalUpdateDokumentasi').classList.remove('hidden');
}

// Lihat detail pengiriman selesai
function lihatDetailSelesai(pengirimanId) {
    // Find pengiriman data
    const pengirimanData = @json($pengirimanSelesai);
    const pengiriman = pengirimanData.find(p => p.id_pengiriman == pengirimanId);
    
    if (pengiriman) {
        // Fill header info
        document.getElementById('headerDetailSelesai').innerHTML = `
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="text-xl font-bold">${pengiriman.nama_proyek}</h4>
                    <p class="text-green-100">${pengiriman.instansi}</p>
                </div>
                <div class="text-right">
                    <span class="bg-white text-green-600 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i> Selesai
                    </span>
                    <p class="text-green-100 text-sm mt-1">Surat Jalan: ${pengiriman.no_surat_jalan}</p>
                </div>
            </div>
        `;

        // Fill project info
        document.getElementById('infoProyekDetail').innerHTML = `
            <div><span class="text-gray-500">No. Penawaran:</span> <span class="font-medium">${pengiriman.no_penawaran}</span></div>
            <div><span class="text-gray-500">Nama Proyek:</span> <span class="font-medium">${pengiriman.nama_proyek}</span></div>
            <div><span class="text-gray-500">Instansi:</span> <span class="font-medium">${pengiriman.instansi}</span></div>
            <div><span class="text-gray-500">Tanggal Kirim:</span> <span class="font-medium">${new Date(pengiriman.tanggal_kirim).toLocaleDateString('id-ID')}</span></div>
        `;

        // Fill shipping info
        document.getElementById('infoPengirimanDetailSelesai').innerHTML = `
            <div><span class="text-gray-500">No. Surat Jalan:</span> <span class="font-medium">${pengiriman.no_surat_jalan}</span></div>
            <div><span class="text-gray-500">Status:</span> <span class="font-medium text-green-600">Selesai</span></div>
            <div><span class="text-gray-500">Alamat Kirim:</span> <span class="font-medium">${pengiriman.alamat_kirim || 'Tidak tersedia'}</span></div>
            <div><span class="text-gray-500">Tanggal Update:</span> <span class="font-medium">${new Date(pengiriman.updated_at).toLocaleDateString('id-ID')}</span></div>
        `;

        // Fill dokumentasi
        fillDokumentasiSelesai(pengiriman);

        // Fill timeline
        fillTimelineSelesai(pengiriman);

        // Reset to first tab
        switchDetailTab('info');

        // Show modal
        document.getElementById('modalDetailSelesai').classList.remove('hidden');
    }
}

// Fill dokumentasi selesai
function fillDokumentasiSelesai(pengiriman) {
    const dokumentasiList = [
        { key: 'file_surat_jalan', label: 'File Surat Jalan', icon: 'fas fa-file-pdf' },
        { key: 'foto_berangkat', label: 'Foto Keberangkatan', icon: 'fas fa-camera' },
        { key: 'foto_perjalanan', label: 'Foto Perjalanan', icon: 'fas fa-road' },
        { key: 'foto_sampai', label: 'Foto Sampai Tujuan', icon: 'fas fa-map-marker-alt' },
        { key: 'tanda_terima', label: 'Tanda Terima', icon: 'fas fa-signature' }
    ];

    const dokumentasiHtml = dokumentasiList.map(dok => {
        const isAvailable = pengiriman[dok.key];
        const statusClass = isAvailable ? 'text-green-600' : 'text-gray-400';
        const statusIcon = isAvailable ? 'fas fa-check-circle' : 'fas fa-times-circle';
        const actionButton = isAvailable ? 
            `<button onclick="viewFile('${pengiriman[dok.key]}')" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-eye mr-1"></i> Lihat
            </button>` : 
            '<span class="text-gray-400 text-sm">Tidak ada</span>';

        return `
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="${dok.icon} ${statusClass} mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900">${dok.label}</p>
                            <p class="text-sm ${statusClass}">
                                <i class="${statusIcon} mr-1"></i>
                                ${isAvailable ? 'Tersedia' : 'Tidak Tersedia'}
                            </p>
                        </div>
                    </div>
                    <div>
                        ${actionButton}
                    </div>
                </div>
            </div>
        `;
    }).join('');

    document.getElementById('dokumentasiSelesaiContent').innerHTML = `
        <div class="grid grid-cols-1 gap-4">
            <h4 class="font-semibold text-gray-900 mb-4">Dokumentasi Lengkap</h4>
            ${dokumentasiHtml}
        </div>
    `;
}

// Fill timeline selesai
function fillTimelineSelesai(pengiriman) {
    const timelineData = [
        {
            title: 'Pengiriman Dibuat',
            date: pengiriman.created_at,
            status: 'completed',
            description: `Surat jalan ${pengiriman.no_surat_jalan} dibuat dan pengiriman dimulai`
        },
        {
            title: 'Dokumentasi Keberangkatan',
            date: pengiriman.created_at,
            status: pengiriman.foto_berangkat ? 'completed' : 'pending',
            description: 'Foto keberangkatan diupload'
        },
        {
            title: 'Dalam Perjalanan',
            date: pengiriman.updated_at,
            status: pengiriman.foto_perjalanan ? 'completed' : 'pending',
            description: 'Foto perjalanan diupload'
        },
        {
            title: 'Sampai Tujuan',
            date: pengiriman.updated_at,
            status: pengiriman.foto_sampai ? 'completed' : 'pending',
            description: 'Foto sampai tujuan diupload'
        },
        {
            title: 'Selesai',
            date: pengiriman.updated_at,
            status: pengiriman.tanda_terima ? 'completed' : 'pending',
            description: 'Tanda terima diterima dan pengiriman selesai'
        }
    ];

    const timelineHtml = timelineData.map((item, index) => {
        const isCompleted = item.status === 'completed';
        const statusClass = isCompleted ? 'bg-green-500' : 'bg-gray-300';
        const lineClass = index < timelineData.length - 1 ? (isCompleted ? 'bg-green-500' : 'bg-gray-300') : '';
        
        return `
            <div class="flex">
                <div class="flex flex-col items-center mr-4">
                    <div class="w-4 h-4 ${statusClass} rounded-full flex items-center justify-center">
                        ${isCompleted ? '<i class="fas fa-check text-white text-xs"></i>' : ''}
                    </div>
                    ${index < timelineData.length - 1 ? `<div class="w-0.5 h-16 ${lineClass}"></div>` : ''}
                </div>
                <div class="flex-1 pb-8">
                    <div class="flex items-center justify-between">
                        <h5 class="font-medium text-gray-900">${item.title}</h5>
                        <span class="text-sm text-gray-500">${new Date(item.date).toLocaleDateString('id-ID')}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">${item.description}</p>
                </div>
            </div>
        `;
    }).join('');

    document.getElementById('timelineSelesaiContent').innerHTML = `
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-6">Timeline Pengiriman</h4>
            <div class="space-y-0">
                ${timelineHtml}
            </div>
        </div>
    `;
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
    const contentMap = {
        'info': 'contentInfo',
        'dokumentasi': 'contentDokumentasi', 
        'timeline': 'contentTimeline'
    };

    if (contentMap[tabName]) {
        document.getElementById(contentMap[tabName]).classList.remove('hidden');
    }

    // Add active class to selected tab
    const tabButton = document.querySelector(`#modalDetailSelesai button[onclick="switchDetailTab('${tabName}')"]`);
    if (tabButton) {
        tabButton.classList.remove('border-transparent', 'text-gray-500');
        tabButton.classList.add('border-green-500', 'text-green-600', 'detail-tab-active');
    }
}

// Fungsi untuk melihat file yang sudah diupload
function viewFile(filePath) {
    if (filePath) {
        const fullUrl = `/storage/${filePath}`;
        window.open(fullUrl, '_blank');
    }
}

// Print and download functions
function printDetailSelesai() {
    alert('Fitur cetak laporan akan membuat dokumen PDF lengkap dengan semua dokumentasi pengiriman untuk keperluan arsip dan audit.');
}

function downloadDetailSelesai() {
    alert('Fitur download PDF akan mengunduh laporan lengkap pengiriman dalam format PDF yang dapat disimpan sebagai dokumentasi.');
}

// Tutup modal
function tutupModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        tutupModal(e.target.id);
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(modal => {
            modal.classList.add('hidden');
        });
    }
});
</script>

@endsection
