@extends('layouts.app')

@section('title', 'Detail Verifikasi Proyek')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Verifikasi Proyek</h1>
                <p class="text-gray-600 mt-1">Verifikasi lengkap untuk proyek {{ $proyek->nama_barang }}</p>
            </div>
            <div>
                <a href="{{ route('superadmin.verifikasi-proyek') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Informasi Proyek -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Data Proyek -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-project-diagram mr-2 text-blue-500"></i>
                Informasi Proyek
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Barang:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->nama_barang }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">No. Penawaran:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->no_penawaran }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Proyek:</span>
                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($proyek->tanggal)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Penawaran:</span>
                    <span class="font-medium text-green-600">Rp {{ number_format($proyek->total_penawaran, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status Proyek:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $proyek->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Data Klien -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user-tie mr-2 text-green-500"></i>
                Data Klien
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Klien:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->nama_klien }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Instansi:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->instansi }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kota/Kabupaten:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->kota_kab }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kontak:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->kontak_klien ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tim Pengelola -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-users mr-2 text-purple-500"></i>
            Tim Pengelola Proyek
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-2">Admin Marketing</h4>
                <p class="text-gray-600">{{ $proyek->admin_marketing_name }}</p>
                <p class="text-sm text-gray-500">{{ $proyek->admin_marketing_email }}</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-2">Admin Purchasing</h4>
                <p class="text-gray-600">{{ $proyek->admin_purchasing_name }}</p>
                <p class="text-sm text-gray-500">{{ $proyek->admin_purchasing_email }}</p>
            </div>
        </div>
    </div>

    <!-- File Penawaran -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-file-contract mr-2 text-orange-500"></i>
            Dokumen Penawaran
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($proyek->surat_pesanan)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Surat Pesanan</h4>
                        <p class="text-sm text-gray-500">File penawaran resmi</p>
                    </div>
                    <a href="{{ asset('storage/' . $proyek->surat_pesanan) }}" target="_blank"
                       class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
            @endif

            @if($proyek->surat_penawaran)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Surat Penawaran</h4>
                        <p class="text-sm text-gray-500">Dokumen proposal</p>
                    </div>
                    <a href="{{ asset('storage/' . $proyek->surat_penawaran) }}" target="_blank"
                       class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Detail Barang -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-boxes mr-2 text-indigo-500"></i>
            Detail Barang yang Dipesan
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($penawaranDetail as $detail)
                    <tr>
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $detail->nama_barang }}</div>
                                <div class="text-sm text-gray-500">{{ $detail->brand }} - {{ $detail->kategori }}</div>
                                <div class="text-xs text-gray-400">{{ $detail->spesifikasi }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $detail->nama_vendor }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $detail->qty }}</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    @if($pembayaran->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-credit-card mr-2 text-green-500"></i>
            Riwayat Pembayaran
        </h3>
        <div class="space-y-3">
            @foreach($pembayaran as $bayar)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900">{{ $bayar->jenis_bayar }}</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $bayar->metode_bayar }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-green-600">Rp {{ number_format($bayar->nominal_bayar, 0, ',', '.') }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $bayar->status_verifikasi == 'Approved' ? 'bg-green-100 text-green-800' : ($bayar->status_verifikasi == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $bayar->status_verifikasi }}
                        </span>
                    </div>
                </div>
                @if($bayar->catatan)
                <p class="text-sm text-gray-600 mt-2">{{ $bayar->catatan }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Informasi Pengiriman -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-shipping-fast mr-2 text-red-500"></i>
            Informasi Pengiriman
        </h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">No. Surat Jalan:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->no_surat_jalan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Kirim:</span>
                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($proyek->tanggal_kirim)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Alamat Kirim:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->alamat_kirim }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status Verifikasi:</span>
                    @php
                        $statusColor = [
                            'Pending' => 'bg-yellow-100 text-yellow-800',
                            'Dalam_Proses' => 'bg-blue-100 text-blue-800',
                            'Sampai_Tujuan' => 'bg-green-100 text-green-800',
                            'Verified' => 'bg-gray-100 text-gray-800',
                            'Rejected' => 'bg-red-100 text-red-800'
                        ][$proyek->status_verifikasi] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                        {{ $proyek->status_verifikasi }}
                    </span>
                </div>
            </div>
            
            <!-- File Surat Jalan -->
            @if($proyek->file_surat_jalan)
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-2">File Surat Jalan</h4>
                <a href="{{ asset('storage/' . $proyek->file_surat_jalan) }}" target="_blank"
                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Download Surat Jalan
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Dokumentasi Pengiriman -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-camera mr-2 text-purple-500"></i>
            Dokumentasi Pengiriman
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Foto Berangkat -->
            <div class="text-center">
                <h4 class="font-medium text-gray-900 mb-2">Foto Berangkat</h4>
                @if($proyek->foto_berangkat)
                    <img src="{{ asset('storage/' . $proyek->foto_berangkat) }}" 
                         alt="Foto Berangkat" 
                         class="w-full h-32 object-cover rounded-lg border border-gray-300 cursor-pointer"
                         onclick="openImageModal('{{ asset('storage/' . $proyek->foto_berangkat) }}', 'Foto Berangkat')">
                @else
                    <div class="w-full h-32 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                @endif
            </div>

            <!-- Foto Perjalanan -->
            <div class="text-center">
                <h4 class="font-medium text-gray-900 mb-2">Foto Perjalanan</h4>
                @if($proyek->foto_perjalanan)
                    <img src="{{ asset('storage/' . $proyek->foto_perjalanan) }}" 
                         alt="Foto Perjalanan" 
                         class="w-full h-32 object-cover rounded-lg border border-gray-300 cursor-pointer"
                         onclick="openImageModal('{{ asset('storage/' . $proyek->foto_perjalanan) }}', 'Foto Perjalanan')">
                @else
                    <div class="w-full h-32 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                @endif
            </div>

            <!-- Foto Sampai -->
            <div class="text-center">
                <h4 class="font-medium text-gray-900 mb-2">Foto Sampai</h4>
                @if($proyek->foto_sampai)
                    <img src="{{ asset('storage/' . $proyek->foto_sampai) }}" 
                         alt="Foto Sampai" 
                         class="w-full h-32 object-cover rounded-lg border border-gray-300 cursor-pointer"
                         onclick="openImageModal('{{ asset('storage/' . $proyek->foto_sampai) }}', 'Foto Sampai')">
                @else
                    <div class="w-full h-32 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                @endif
            </div>

            <!-- Tanda Terima -->
            <div class="text-center">
                <h4 class="font-medium text-gray-900 mb-2">Tanda Terima</h4>
                @if($proyek->tanda_terima)
                    <img src="{{ asset('storage/' . $proyek->tanda_terima) }}" 
                         alt="Tanda Terima" 
                         class="w-full h-32 object-cover rounded-lg border border-gray-300 cursor-pointer"
                         onclick="openImageModal('{{ asset('storage/' . $proyek->tanda_terima) }}', 'Tanda Terima')">
                @else
                    <div class="w-full h-32 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Verifikasi Proyek -->
    @if($proyek->status_verifikasi === 'Sampai_Tujuan')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-check-circle mr-2 text-green-500"></i>
            Verifikasi Proyek
        </h3>
        
        <div class="flex space-x-4">
            <button type="button" onclick="openVerificationModal('selesai')"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-check mr-2"></i>
                Verifikasi SELESAI
            </button>
            <button type="button" onclick="openVerificationModal('gagal')"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>
                Verifikasi GAGAL
            </button>
        </div>
    </div>
    @elseif($proyek->status_verifikasi === 'Verified')
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-center mb-4">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <h3 class="text-lg font-semibold text-green-800">Proyek Telah Diverifikasi</h3>
        </div>
        @if($proyek->catatan_verifikasi)
        <p class="text-green-700 mb-2"><strong>Catatan:</strong> {{ $proyek->catatan_verifikasi }}</p>
        @endif
        <p class="text-green-700 text-sm">
            Diverifikasi oleh: {{ $proyek->verified_by_name }} 
            pada {{ \Carbon\Carbon::parse($proyek->verified_at)->format('d M Y H:i') }}
        </p>
    </div>
    @elseif($proyek->status_verifikasi === 'Rejected')
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-center mb-4">
            <i class="fas fa-times-circle text-red-500 mr-3"></i>
            <h3 class="text-lg font-semibold text-red-800">Proyek Telah Ditolak</h3>
        </div>
        @if($proyek->catatan_verifikasi)
        <p class="text-red-700 mb-2"><strong>Catatan:</strong> {{ $proyek->catatan_verifikasi }}</p>
        @endif
        <p class="text-red-700 text-sm">
            Ditolak oleh: {{ $proyek->verified_by_name }} 
            pada {{ \Carbon\Carbon::parse($proyek->verified_at)->format('d M Y H:i') }}
        </p>
    </div>
    @endif
</div>

<!-- Modal untuk preview gambar -->
<div id="imageModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <img id="modalImage" src="" alt="" class="max-w-full h-auto">
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div id="verificationModal" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <form action="{{ route('superadmin.verifikasi-proyek.verify', $proyek->id_proyek) }}" method="POST" id="verificationForm">
                @csrf
                @method('PUT')
                
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div id="modalIcon" class="w-12 h-12 rounded-full flex items-center justify-center mr-4">
                            <i id="modalIconClass" class="text-2xl"></i>
                        </div>
                        <div>
                            <h3 id="modalVerificationTitle" class="text-lg font-semibold text-gray-900"></h3>
                            <p id="modalVerificationSubtitle" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Verifikasi *</label>
                        <textarea name="catatan_verifikasi" rows="4" 
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Berikan catatan untuk verifikasi ini..."
                                  required></textarea>
                        <p class="text-xs text-gray-500 mt-1">Catatan ini akan disimpan dalam sistem dan dapat dilihat oleh tim terkait.</p>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-0.5"></i>
                            <div>
                                <p class="text-sm text-yellow-800 font-medium">Peringatan!</p>
                                <p class="text-xs text-yellow-700 mt-1">Keputusan verifikasi ini bersifat permanen dan tidak dapat diubah.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 flex space-x-3">
                    <button type="button" onclick="closeVerificationModal()" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" id="submitVerification"
                            class="flex-1 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        <i id="submitIcon" class="mr-2"></i>
                        <span id="submitText"></span>
                    </button>
                </div>
                
                <input type="hidden" name="action" id="verificationAction">
            </form>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function openVerificationModal(action) {
    const modal = document.getElementById('verificationModal');
    const form = document.getElementById('verificationForm');
    const actionInput = document.getElementById('verificationAction');
    const modalIcon = document.getElementById('modalIcon');
    const modalIconClass = document.getElementById('modalIconClass');
    const modalTitle = document.getElementById('modalVerificationTitle');
    const modalSubtitle = document.getElementById('modalVerificationSubtitle');
    const submitBtn = document.getElementById('submitVerification');
    const submitIcon = document.getElementById('submitIcon');
    const submitText = document.getElementById('submitText');
    
    actionInput.value = action;
    
    if (action === 'selesai') {
        modalIcon.className = 'w-12 h-12 rounded-full flex items-center justify-center mr-4 bg-green-100';
        modalIconClass.className = 'fas fa-check-circle text-2xl text-green-600';
        modalTitle.textContent = 'Verifikasi Proyek SELESAI';
        modalSubtitle.textContent = 'Proyek akan ditandai sebagai selesai dan berhasil';
        submitBtn.className = 'flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200';
        submitIcon.className = 'fas fa-check mr-2';
        submitText.textContent = 'Konfirmasi Selesai';
    } else {
        modalIcon.className = 'w-12 h-12 rounded-full flex items-center justify-center mr-4 bg-red-100';
        modalIconClass.className = 'fas fa-times-circle text-2xl text-red-600';
        modalTitle.textContent = 'Verifikasi Proyek GAGAL';
        modalSubtitle.textContent = 'Proyek akan ditandai sebagai gagal';
        submitBtn.className = 'flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200';
        submitIcon.className = 'fas fa-times mr-2';
        submitText.textContent = 'Konfirmasi Gagal';
    }
    
    modal.classList.remove('hidden');
}

function closeVerificationModal() {
    document.getElementById('verificationModal').classList.add('hidden');
    document.querySelector('textarea[name="catatan_verifikasi"]').value = '';
}

// Close modals when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

document.getElementById('verificationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVerificationModal();
    }
});

// Prevent form submission without confirmation
document.getElementById('verificationForm').addEventListener('submit', function(e) {
    const action = document.getElementById('verificationAction').value;
    const actionText = action === 'selesai' ? 'SELESAI' : 'GAGAL';
    
    if (!confirm(`Apakah Anda yakin ingin memverifikasi proyek sebagai ${actionText}? Keputusan ini tidak dapat diubah.`)) {
        e.preventDefault();
    }
});
</script>
@endsection
