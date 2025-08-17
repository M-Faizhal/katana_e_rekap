@extends('layouts.app')

@section('content')

<!-- Header Section -->
<div class="bg-blue-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Input Pembayaran</h1>
            <p class="text-blue-100 text-sm sm:text-base lg:text-lg">Input pembayaran dari klien untuk proyek</p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-money-bill text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span>Terdapat kesalahan pada form:</span>
    </div>
    <ul class="mt-2 ml-4">
        @foreach($errors->all() as $error)
        <li class="list-disc">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Project Info -->
<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Informasi Proyek</h2>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Proyek</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Barang:</span>
                        <span class="font-medium">{{ $proyek->nama_barang }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Klien:</span>
                        <span class="font-medium">{{ $proyek->nama_klien }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Instansi:</span>
                        <span class="font-medium">{{ $proyek->instansi }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. Penawaran:</span>
                        <span class="font-medium">{{ $proyek->penawaranAktif->no_penawaran }}</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Penawaran:</span>
                        <span class="font-bold text-lg text-green-600">
                            Rp {{ number_format($proyek->penawaranAktif->total_penawaran, 0, ',', '.') }}
                        </span>
                    </div>
                    @if($totalDibayar > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sudah Dibayar:</span>
                        <span class="font-medium text-blue-600">
                            Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-600">Sisa Tagihan:</span>
                        <span class="font-bold text-lg text-red-600">
                            Rp {{ number_format($sisaBayar, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                @php
                    $persenBayar = $proyek->penawaranAktif->total_penawaran > 0 ? 
                        ($totalDibayar / $proyek->penawaranAktif->total_penawaran) * 100 : 0;
                @endphp
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Progress Pembayaran</span>
                        <span>{{ number_format($persenBayar, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $persenBayar }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Documents Section -->
        @if($proyek->penawaranAktif && ($proyek->penawaranAktif->surat_pesanan || $proyek->penawaranAktif->surat_penawaran))
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Dokumen Terkait</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Surat Pesanan -->
                @if($proyek->penawaranAktif->surat_pesanan)
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Surat Pesanan:</h4>
                    <div class="border border-gray-200 rounded-lg p-4">
                        @php
                            $fileSuratPesanan = pathinfo($proyek->penawaranAktif->surat_pesanan, PATHINFO_EXTENSION);
                        @endphp
                        
                        <div class="flex items-center justify-center h-16 bg-blue-50 rounded-lg mb-3">
                            @if(in_array(strtolower($fileSuratPesanan), ['pdf']))
                                <i class="fas fa-file-pdf text-red-500 text-2xl mr-2"></i>
                            @elseif(in_array(strtolower($fileSuratPesanan), ['jpg', 'jpeg', 'png']))
                                <i class="fas fa-file-image text-blue-500 text-2xl mr-2"></i>
                            @else
                                <i class="fas fa-file-alt text-gray-500 text-2xl mr-2"></i>
                            @endif
                            <span class="text-sm font-medium text-gray-700">Surat Pesanan</span>
                        </div>
                        
                        <a href="{{ asset('storage/' . $proyek->penawaranAktif->surat_pesanan) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 w-full justify-center">
                            <i class="fas fa-download mr-2"></i>
                            Lihat Surat Pesanan
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Surat Penawaran -->
                @if($proyek->penawaranAktif->surat_penawaran)
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Surat Penawaran:</h4>
                    <div class="border border-gray-200 rounded-lg p-4">
                        @php
                            $fileSuratPenawaran = pathinfo($proyek->penawaranAktif->surat_penawaran, PATHINFO_EXTENSION);
                        @endphp
                        
                        <div class="flex items-center justify-center h-16 bg-green-50 rounded-lg mb-3">
                            @if(in_array(strtolower($fileSuratPenawaran), ['pdf']))
                                <i class="fas fa-file-pdf text-red-500 text-2xl mr-2"></i>
                            @elseif(in_array(strtolower($fileSuratPenawaran), ['jpg', 'jpeg', 'png']))
                                <i class="fas fa-file-image text-green-500 text-2xl mr-2"></i>
                            @else
                                <i class="fas fa-file-alt text-gray-500 text-2xl mr-2"></i>
                            @endif
                            <span class="text-sm font-medium text-gray-700">Surat Penawaran</span>
                        </div>
                        
                        <a href="{{ asset('storage/' . $proyek->penawaranAktif->surat_penawaran) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 w-full justify-center">
                            <i class="fas fa-download mr-2"></i>
                            Lihat Surat Penawaran
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Payment Form -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Form Input Pembayaran</h2>
        <p class="text-gray-600 mt-1">Masukkan detail pembayaran dari klien</p>
    </div>
    
    <form action="{{ route('purchasing.pembayaran.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        <input type="hidden" name="id_proyek" value="{{ $proyek->id_proyek }}">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Jenis Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_bayar" id="jenis_bayar" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Jenis Pembayaran --</option>
                        <option value="Lunas" {{ old('jenis_bayar') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="DP" {{ old('jenis_bayar') == 'DP' ? 'selected' : '' }}>DP (Down Payment)</option>
                        <option value="Cicilan" {{ old('jenis_bayar') == 'Cicilan' ? 'selected' : '' }}>Cicilan</option>
                    </select>
                </div>
                
                <!-- Nominal Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nominal Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input type="number" name="nominal_bayar" id="nominal_bayar" required 
                               min="1" max="{{ $sisaBayar }}"
                               value="{{ old('nominal_bayar') }}"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0">
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Maksimal: Rp {{ number_format($sisaBayar, 0, ',', '.') }}</p>
                    
                    <!-- Quick Suggestions -->
                    <div class="mt-2 flex flex-wrap gap-2" id="suggestions">
                        <button type="button" class="suggestion-btn px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" 
                                data-amount="{{ $sisaBayar }}">
                            Lunas (Rp {{ number_format($sisaBayar, 0, ',', '.') }})
                        </button>
                        @if($sisaBayar > 0)
                        <button type="button" class="suggestion-btn px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" 
                                data-amount="{{ $sisaBayar * 0.3 }}">
                            30% (Rp {{ number_format($sisaBayar * 0.3, 0, ',', '.') }})
                        </button>
                        <button type="button" class="suggestion-btn px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" 
                                data-amount="{{ $sisaBayar * 0.5 }}">
                            50% (Rp {{ number_format($sisaBayar * 0.5, 0, ',', '.') }})
                        </button>
                        @endif
                    </div>
                </div>
                
                <!-- Metode Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="metode_bayar" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank" {{ old('metode_bayar') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Cash" {{ old('metode_bayar') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Cek" {{ old('metode_bayar') == 'Cek' ? 'selected' : '' }}>Cek</option>
                        <option value="Giro" {{ old('metode_bayar') == 'Giro' ? 'selected' : '' }}>Giro</option>
                    </select>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Bukti Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                            <i class="fas fa-cloud-upload-alt text-4xl"></i>
                        </div>
                        <input type="file" name="bukti_bayar" id="bukti_bayar" required 
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="hidden">
                        <label for="bukti_bayar" class="cursor-pointer">
                            <span class="text-blue-600 hover:text-blue-500">Upload file</span>
                            <span class="text-gray-500"> atau drag & drop</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-2">JPG, JPEG, PNG, PDF (max 5MB)</p>
                    </div>
                    <div id="file-info" class="mt-2 hidden">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-file mr-2 text-gray-400"></i>
                            <span id="file-name" class="text-sm text-gray-700"></span>
                            <button type="button" id="remove-file" class="ml-auto text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea name="catatan" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                </div>
                
                <!-- Info -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-400 mt-0.5 mr-2"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium mb-1">Informasi Penting:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pembayaran akan berstatus "Pending" menunggu verifikasi admin keuangan</li>
                                <li>Pastikan bukti pembayaran jelas dan valid</li>
                                <li>Admin keuangan akan memverifikasi dalam 1-2 hari kerja</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
            <a href="{{ route('purchasing.pembayaran') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
            
            <button type="submit" 
                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-save mr-2"></i>
                Simpan Pembayaran
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload handling
    const fileInput = document.getElementById('bukti_bayar');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const removeFile = document.getElementById('remove-file');
    
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
            fileInfo.classList.remove('hidden');
        }
    });
    
    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        fileInfo.classList.add('hidden');
    });
    
    // Suggestion buttons
    document.querySelectorAll('.suggestion-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const amount = this.getAttribute('data-amount');
            document.getElementById('nominal_bayar').value = Math.round(amount);
        });
    });
    
    // Auto-select jenis bayar based on nominal
    const nominalInput = document.getElementById('nominal_bayar');
    const jenisSelect = document.getElementById('jenis_bayar');
    const sisaBayar = {{ $sisaBayar }};
    
    nominalInput.addEventListener('input', function() {
        const nominal = parseFloat(this.value) || 0;
        if (nominal >= sisaBayar && jenisSelect.value === '') {
            jenisSelect.value = 'Lunas';
        }
    });
    
    // Format number input
    nominalInput.addEventListener('blur', function() {
        const value = parseInt(this.value);
        if (!isNaN(value)) {
            this.value = value;
        }
    });
});
</script>
@endpush

@endsection
