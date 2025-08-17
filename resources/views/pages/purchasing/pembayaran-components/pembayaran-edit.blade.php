@extends('layouts.app')

@section('content')
<div class="bg-red-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Edit Pembayaran</h1>
            <p class="text-red-100 text-sm sm:text-base lg:text-lg">
                {{ $proyek->nama_proyek }} - {{ $proyek->nama_klien }}
            </p>
        </div>
        <div class="hidden sm:block lg:block">
            <i class="fas fa-edit text-3xl sm:text-4xl lg:text-6xl"></i>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
</div>
@endif

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
    <div class="flex items-center mb-2">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <span class="font-medium">Terdapat kesalahan pada form:</span>
    </div>
    <ul class="list-disc list-inside ml-4">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white rounded-lg shadow-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Edit Pembayaran</h2>
                <p class="text-gray-600 mt-1">Update informasi pembayaran yang masih pending</p>
            </div>
            <a href="{{ route('purchasing.pembayaran') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Project Info -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Informasi Proyek</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-gray-600">Nama Proyek</div>
                    <div class="font-medium">{{ $proyek->nama_proyek }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Klien</div>
                    <div class="font-medium">{{ $proyek->nama_klien }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Total Penawaran</div>
                    <div class="font-medium text-green-600">
                        Rp {{ number_format($pembayaran->penawaran->total_penawaran, 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Sisa Bayar (setelah update ini)</div>
                    <div class="font-medium text-orange-600">
                        Rp {{ number_format($sisaBayar, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Payment Info -->
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Data Pembayaran Saat Ini</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="text-sm text-gray-600">Jenis Bayar</div>
                    <div class="font-medium">{{ $pembayaran->jenis_bayar }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Nominal</div>
                    <div class="font-medium">Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Status</div>
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ $pembayaran->status_verifikasi }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        @if($pembayaran->penawaran && ($pembayaran->penawaran->surat_pesanan || $pembayaran->penawaran->surat_penawaran))
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Dokumen Terkait</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Surat Pesanan -->
                @if($pembayaran->penawaran->surat_pesanan)
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Surat Pesanan:</h4>
                    <div class="border border-gray-200 rounded-lg p-3">
                        @php
                            $fileSuratPesanan = pathinfo($pembayaran->penawaran->surat_pesanan, PATHINFO_EXTENSION);
                        @endphp
                        
                        <div class="flex items-center justify-center h-12 bg-blue-50 rounded-lg mb-2">
                            @if(in_array(strtolower($fileSuratPesanan), ['pdf']))
                                <i class="fas fa-file-pdf text-red-500 text-lg mr-2"></i>
                            @elseif(in_array(strtolower($fileSuratPesanan), ['jpg', 'jpeg', 'png']))
                                <i class="fas fa-file-image text-blue-500 text-lg mr-2"></i>
                            @else
                                <i class="fas fa-file-alt text-gray-500 text-lg mr-2"></i>
                            @endif
                            <span class="text-xs font-medium text-gray-700">Surat Pesanan</span>
                        </div>
                        
                        <a href="{{ asset('storage/' . $pembayaran->penawaran->surat_pesanan) }}" 
                           target="_blank"
                           class="inline-flex items-center px-2 py-1 border border-blue-300 shadow-sm text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100 w-full justify-center">
                            <i class="fas fa-download mr-1"></i>
                            Lihat
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Surat Penawaran -->
                @if($pembayaran->penawaran->surat_penawaran)
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Surat Penawaran:</h4>
                    <div class="border border-gray-200 rounded-lg p-3">
                        @php
                            $fileSuratPenawaran = pathinfo($pembayaran->penawaran->surat_penawaran, PATHINFO_EXTENSION);
                        @endphp
                        
                        <div class="flex items-center justify-center h-12 bg-green-50 rounded-lg mb-2">
                            @if(in_array(strtolower($fileSuratPenawaran), ['pdf']))
                                <i class="fas fa-file-pdf text-red-500 text-lg mr-2"></i>
                            @elseif(in_array(strtolower($fileSuratPenawaran), ['jpg', 'jpeg', 'png']))
                                <i class="fas fa-file-image text-green-500 text-lg mr-2"></i>
                            @else
                                <i class="fas fa-file-alt text-gray-500 text-lg mr-2"></i>
                            @endif
                            <span class="text-xs font-medium text-gray-700">Surat Penawaran</span>
                        </div>
                        
                        <a href="{{ asset('storage/' . $pembayaran->penawaran->surat_penawaran) }}" 
                           target="_blank"
                           class="inline-flex items-center px-2 py-1 border border-green-300 shadow-sm text-xs leading-4 font-medium rounded text-green-700 bg-green-50 hover:bg-green-100 w-full justify-center">
                            <i class="fas fa-download mr-1"></i>
                            Lihat
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Edit Form -->
        <form action="{{ route('purchasing.pembayaran.update', $pembayaran->id_pembayaran) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Pembayaran -->
                <div>
                    <label for="jenis_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_bayar" id="jenis_bayar" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="DP" {{ old('jenis_bayar', $pembayaran->jenis_bayar) == 'DP' ? 'selected' : '' }}>Down Payment (DP)</option>
                        <option value="Cicilan" {{ old('jenis_bayar', $pembayaran->jenis_bayar) == 'Cicilan' ? 'selected' : '' }}>Cicilan</option>
                        <option value="Lunas" {{ old('jenis_bayar', $pembayaran->jenis_bayar) == 'Lunas' ? 'selected' : '' }}>Pelunasan</option>
                    </select>
                </div>

                <!-- Nominal Pembayaran -->
                <div>
                    <label for="nominal_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                        Nominal Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="nominal_bayar" id="nominal_bayar" required min="1" step="0.01"
                           value="{{ old('nominal_bayar', $pembayaran->nominal_bayar) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Masukkan nominal pembayaran">
                    <p class="mt-1 text-sm text-gray-500">Maksimal: Rp {{ number_format($sisaBayar + $pembayaran->nominal_bayar, 0, ',', '.') }}</p>
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label for="metode_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                        Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="metode_bayar" id="metode_bayar" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="Transfer Bank" {{ old('metode_bayar', $pembayaran->metode_bayar) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Cash" {{ old('metode_bayar', $pembayaran->metode_bayar) == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Cek" {{ old('metode_bayar', $pembayaran->metode_bayar) == 'Cek' ? 'selected' : '' }}>Cek</option>
                        <option value="Giro" {{ old('metode_bayar', $pembayaran->metode_bayar) == 'Giro' ? 'selected' : '' }}>Giro</option>
                        <option value="Kartu Kredit" {{ old('metode_bayar', $pembayaran->metode_bayar) == 'Kartu Kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                    </select>
                </div>

                <!-- Bukti Pembayaran -->
                <div>
                    <label for="bukti_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Pembayaran
                    </label>
                    <input type="file" name="bukti_bayar" id="bukti_bayar" 
                           accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, PDF. Maksimal 5MB. Kosongkan jika tidak ingin mengubah.</p>
                    
                    @if($pembayaran->bukti_bayar)
                    <div class="mt-2 p-2 bg-gray-50 rounded border">
                        <p class="text-sm text-gray-600">File saat ini:</p>
                        <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-file mr-1"></i>
                            Lihat file yang ada
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Catatan -->
            <div class="mt-6">
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan
                </label>
                <textarea name="catatan" id="catatan" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Catatan tambahan (opsional)">{{ old('catatan', $pembayaran->catatan) }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex items-center justify-between">
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Update Pembayaran
                    </button>
                    
                    <a href="{{ route('purchasing.pembayaran') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                </div>

                <!-- Delete Button -->
                <form action="{{ route('purchasing.pembayaran.destroy', $pembayaran->id_pembayaran) }}" 
                      method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini? File bukti pembayaran juga akan dihapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Pembayaran
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nominalInput = document.getElementById('nominal_bayar');
    const jenisSelect = document.getElementById('jenis_bayar');
    
    // Format number input
    nominalInput.addEventListener('input', function() {
        // Remove non-numeric characters except decimal point
        this.value = this.value.replace(/[^\d.]/g, '');
    });
    
    // Auto-fill suggestions based on jenis bayar
    jenisSelect.addEventListener('change', function() {
        const maxAmount = {{ $sisaBayar + $pembayaran->nominal_bayar }};
        
        if (this.value === 'Lunas') {
            nominalInput.value = maxAmount;
        } else if (this.value === 'DP') {
            // Suggest 30% of total
            nominalInput.value = Math.round(maxAmount * 0.3);
        }
        // For 'Cicilan', let user input manually
    });
});
</script>
@endsection
