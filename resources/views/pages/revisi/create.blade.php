@extends('layouts.app')

@section('title', 'Buat Revisi - Cyber KATANA')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Revisi</h1>
                <p class="text-gray-600 mt-1">Membuat permintaan revisi untuk {{ \App\Models\Revisi::TIPE_REVISI[$tipeRevisi] ?? $tipeRevisi }}</p>
            </div>
            <div>
                <a href="{{ route('superadmin.verifikasi-proyek.detail', $proyek->id_proyek) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form Revisi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('revisi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_proyek" value="{{ $proyek->id_proyek }}">
            <input type="hidden" name="tipe_revisi" value="{{ $tipeRevisi }}">
            
            <!-- Info Proyek -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Proyek</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-600">Kode Proyek:</span>
                            <p class="font-medium">{{ $proyek->kode_proyek ?? 'PRJ-' . str_pad($proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Instansi:</span>
                            <p class="font-medium">{{ $proyek->instansi }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Jenis Pengadaan:</span>
                            <p class="font-medium">{{ $proyek->jenis_pengadaan }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Tipe Revisi:</span>
                            <p class="font-medium">{{ \App\Models\Revisi::TIPE_REVISI[$tipeRevisi] ?? $tipeRevisi }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Keterangan Revisi -->
            <div class="mb-6">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan Revisi <span class="text-red-500">*</span>
                </label>
                <textarea id="keterangan" 
                          name="keterangan" 
                          rows="6" 
                          required
                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror"
                          placeholder="Jelaskan dengan detail apa yang perlu direvisi dan bagaimana seharusnya...">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <!-- Panduan Keterangan -->
                <div class="mt-2 p-3 bg-blue-50 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Panduan Keterangan Revisi:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        @switch($tipeRevisi)
                            @case('proyek')
                                <li>• Sebutkan data proyek mana yang perlu diubah (nama, tanggal, instansi, dll)</li>
                                <li>• Jelaskan perubahan yang diinginkan</li>
                                <li>• Sertakan alasan mengapa perubahan diperlukan</li>
                                @break
                                
                            @case('hps_penawaran')
                                <li>• Jelaskan item HPS mana yang perlu direvisi</li>
                                <li>• Sebutkan perubahan harga atau spesifikasi yang diperlukan</li>
                                <li>• Jika ada dokumen penawaran yang perlu disesuaikan, sebutkan</li>
                                @break
                                
                            @case('penawaran')
                                <li>• Sebutkan dokumen penawaran mana yang perlu direvisi</li>
                                <li>• Jelaskan perubahan yang diperlukan (harga, spek, vendor, dll)</li>
                                <li>• Sertakan alasan revisi</li>
                                @break
                                
                            @case('penagihan_dinas')
                                <li>• Jelaskan item penagihan mana yang perlu direvisi</li>
                                <li>• Sebutkan perubahan jumlah, tanggal, atau detail lainnya</li>
                                <li>• Sertakan dokumen pendukung jika ada</li>
                                @break
                                
                            @case('pembayaran')
                                <li>• Jelaskan pembayaran mana yang perlu direvisi</li>
                                <li>• Sebutkan perubahan status, jumlah, atau tanggal</li>
                                <li>• Sertakan alasan perubahan</li>
                                @break
                                
                            @case('pengiriman')
                                <li>• Sebutkan pengiriman mana yang perlu direvisi</li>
                                <li>• Jelaskan perubahan status, alamat, atau detail pengiriman</li>
                                <li>• Sertakan informasi terbaru yang dimiliki</li>
                                @break
                        @endswitch
                    </ul>
                </div>
            </div>
            
            <!-- Target ID (Opsional) -->
            <div class="mb-6">
                <label for="target_id" class="block text-sm font-medium text-gray-700 mb-2">
                    ID Target Spesifik (Opsional)
                </label>
                <input type="number" 
                       id="target_id" 
                       name="target_id" 
                       value="{{ old('target_id') }}"
                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('target_id') border-red-500 @enderror"
                       placeholder="Masukkan ID spesifik jika ada (misal: ID penawaran, ID pembayaran)">
                @error('target_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Kosongkan jika revisi berlaku untuk semua item dalam kategori ini
                </p>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('superadmin.verifikasi-proyek.detail', $proyek->id_proyek) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Revisi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
