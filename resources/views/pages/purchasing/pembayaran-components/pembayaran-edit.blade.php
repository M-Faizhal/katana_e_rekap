@extends('layouts.app')

@section('content')

<!-- Header Section Enhanced -->
<div class="bg-gradient-to-r from-red-800 to-red-900 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-xl mt-4">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <div class="flex items-center mb-3">
                <div class="bg-red-700 rounded-lg p-2 mr-3">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold">Edit Pembayaran</h1>
                    <p class="text-red-100 text-sm sm:text-base lg:text-lg">
                        Update informasi pembayaran yang masih pending
                    </p>
                </div>
            </div>
           
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-file-edit text-5xl opacity-20"></i>
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

<!-- Main Edit Form Enhanced -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100">
    <div class="p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 rounded-t-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-orange-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-edit text-orange-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Edit Pembayaran</h2>
                    <p class="text-gray-600 mt-1">Update informasi pembayaran yang masih pending</p>
                </div>
            </div>
            <a href="{{ route('purchasing.pembayaran') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Project Info Enhanced -->
        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                <i class="fas fa-project-diagram mr-2"></i>
                Informasi Proyek
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700 font-medium">Kode Proyek:</span>
                        <span class="font-semibold text-blue-900">{{ $proyek->kode_proyek }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700 font-medium">Kota/Kab:</span>
                        <span class="font-semibold text-blue-900">{{ $proyek->kab_kota }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700 font-medium">Vendor:</span>
                        <span class="font-semibold text-blue-900">{{ $pembayaran->vendor->nama_vendor }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700 font-medium">Total Modal Vendor (Harga Akhir Kalkulasi HPS):</span>
                        <span class="font-medium">Rp {{ number_format($totalModalVendor, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700 font-medium">Total Penawaran Klien:</span>
                        <span class="font-semibold text-blue-600">
                            Rp {{ number_format($pembayaran->penawaran->total_penawaran, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700 font-medium">Sisa Bayar Vendor:</span>
                        <span class="font-bold text-orange-700">
                            Rp {{ number_format($sisaBayar, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Payment Info Enhanced -->
        <div class="mb-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 rounded-lg">
            <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                <i class="fas fa-receipt mr-2"></i>
                Data Pembayaran Saat Ini
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-3 border border-yellow-200">
                    <div class="text-xs text-yellow-700 font-medium">Jenis Bayar</div>
                    <div class="font-semibold text-yellow-900">{{ $pembayaran->jenis_bayar }}</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-yellow-200">
                    <div class="text-xs text-yellow-700 font-medium">Nominal</div>
                    <div class="font-semibold text-yellow-900">Rp {{ number_format($pembayaran->nominal_bayar, 2, ',', '.') }}</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-yellow-200">
                    <div class="text-xs text-yellow-700 font-medium">Status</div>
                    <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                        <i class="fas fa-hourglass-half mr-1"></i>
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
                        
                        <a href="{{ asset('storage/penawaran/' . $pembayaran->penawaran->surat_pesanan) }}" 
                           target="_blank"
                           class="inline-flex items-center px-2 py-1 border border-blue-300 shadow-sm text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100 w-full justify-center">
                            <i class="fas fa-eye mr-1"></i>
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
                        
                        <a href="{{ asset('storage/penawaran/' . $pembayaran->penawaran->surat_penawaran) }}" 
                           target="_blank"
                           class="inline-flex items-center px-2 py-1 border border-green-300 shadow-sm text-xs leading-4 font-medium rounded text-green-700 bg-green-50 hover:bg-green-100 w-full justify-center">
                            <i class="fas fa-eye mr-1"></i>
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
                    <p class="mt-1 text-sm text-gray-500">Maksimal: Rp {{ number_format($sisaBayar + $pembayaran->nominal_bayar, 2, ',', '.') }}</p>
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
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center bg-white hover:bg-gray-50 transition-colors" id="drop-zone-edit">
                        <input type="file" name="bukti_bayar[]" id="bukti_bayar" 
                               accept=".jpg,.jpeg,.png,.pdf"
                               multiple
                               class="hidden">
                        <label for="bukti_bayar" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2 block"></i>
                            <span class="text-indigo-600 hover:text-indigo-500 font-medium text-sm">Upload file baru</span>
                            <span class="text-gray-500 text-sm"> (bisa lebih dari 1)</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG, PDF (max 5MB per file). Kosongkan jika tidak ingin mengubah.</p>
                    </div>
                    <!-- New files preview -->
                    <div id="new-file-list" class="mt-2 space-y-1"></div>
                    
                    @php $existingFiles = $pembayaran->bukti_bayar_array; @endphp
                    @if(count($existingFiles) > 0)
                    <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-paperclip mr-1"></i>
                            File saat ini — centang untuk <span class="text-red-600 font-semibold">menghapus</span>:
                        </p>
                        <div class="space-y-2" id="existing-file-list">
                            @foreach($existingFiles as $file)
                            @php
                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                $iconClass = 'fa-file text-gray-500';
                                if ($ext === 'pdf') $iconClass = 'fa-file-pdf text-red-500';
                                elseif (in_array($ext, ['jpg','jpeg','png'])) $iconClass = 'fa-file-image text-blue-500';
                            @endphp
                            <div class="existing-file-item flex items-center gap-3 p-2 rounded-lg border border-gray-200 bg-white transition-colors" data-file="{{ $file }}">
                                <input type="checkbox" 
                                       name="delete_files[]" 
                                       value="{{ $file }}" 
                                       id="del_{{ $loop->index }}"
                                       class="delete-file-checkbox w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer flex-shrink-0">
                                <i class="fas {{ $iconClass }} text-sm flex-shrink-0"></i>
                                <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm underline truncate flex-1">
                                    {{ basename($file) }}
                                </a>
                                <label for="del_{{ $loop->index }}" class="text-xs text-red-500 font-medium cursor-pointer flex-shrink-0 select-none">
                                    Hapus
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            File yang dicentang akan dihapus permanen saat klik "Update Pembayaran".
                        </p>
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
            </form>
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

        <!-- Breakdown Modal per Barang Section (Optional Display) -->
        @if(isset($breakdownBarang) && $breakdownBarang && $breakdownBarang->count() > 0)
        <div class="mt-6 p-4 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg border border-purple-200">
            <h3 class="text-sm font-semibold text-purple-800 mb-3 flex items-center">
                <i class="fas fa-chart-pie mr-2"></i>
                Modal per Barang ({{ $breakdownBarang->count() }} item)
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($breakdownBarang as $item)
                @php
                    $persentaseModal = $totalModalVendor > 0 ? ($item->total_harga_hpp / $totalModalVendor) * 100 : 0;
                @endphp
                <div class="bg-white rounded-lg p-3 border border-purple-200 shadow-sm">
                    <h4 class="font-medium text-gray-900 text-sm mb-2">{{ $item->nama_barang }}</h4>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Qty:</span>
                            <span class="font-medium">{{ number_format($item->qty, 2, ',', '.') }} {{ $item->satuan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Modal:</span>
                            <span class="font-bold text-green-600">Rp {{ number_format($item->total_harga_hpp, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kontribusi:</span>
                            <span class="font-medium text-purple-600">{{ number_format($persentaseModal, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1 mt-2">
                            <div class="bg-purple-500 h-1 rounded-full" style="width: {{ $persentaseModal }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3 pt-3 border-t border-purple-200 flex justify-between items-center">
                <span class="text-sm font-medium text-purple-700">Total Modal Vendor:</span>
                <span class="text-sm font-bold text-purple-800">Rp {{ number_format($totalModalVendor, 2, ',', '.') }}</span>
            </div>
        </div>
        @endif

      
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

    // --- Multi-file upload for edit form ---
    const fileInput = document.getElementById('bukti_bayar');
    const newFileList = document.getElementById('new-file-list');
    const dropZone = document.getElementById('drop-zone-edit');
    let selectedFiles = new DataTransfer();

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function renderNewFileList() {
        newFileList.innerHTML = '';
        const files = selectedFiles.files;
        if (files.length === 0) return;

        const header = document.createElement('p');
        header.className = 'text-xs font-medium text-gray-600 mt-1';
        header.innerHTML = `<i class="fas fa-plus-circle text-green-500 mr-1"></i>File baru yang akan diupload (${files.length}):`;
        newFileList.appendChild(header);

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const ext = file.name.split('.').pop().toLowerCase();
            let iconClass = 'fa-file text-gray-500';
            if (ext === 'pdf') iconClass = 'fa-file-pdf text-red-500';
            else if (['jpg','jpeg','png'].includes(ext)) iconClass = 'fa-file-image text-blue-500';

            const item = document.createElement('div');
            item.className = 'flex items-center p-2 bg-green-50 rounded border border-green-200 text-sm';
            item.innerHTML = `
                <i class="fas ${iconClass} mr-2 text-xs"></i>
                <span class="flex-1 truncate text-green-800">${file.name} <span class="text-xs text-gray-500">(${formatFileSize(file.size)})</span></span>
                <button type="button" class="remove-new-file ml-2 text-red-500 hover:text-red-700" data-index="${i}">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;
            newFileList.appendChild(item);
        }
    }

    // Sync new files to input before form submit
    const editForm = document.querySelector('form[enctype="multipart/form-data"]');
    if (editForm) {
        editForm.addEventListener('submit', function() {
            if (fileInput && selectedFiles.files.length > 0) {
                fileInput.files = selectedFiles.files;
            }
        });
    }

    // --- Visual feedback untuk checkbox hapus file lama ---
    document.querySelectorAll('.delete-file-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const item = this.closest('.existing-file-item');
            if (this.checked) {
                item.classList.add('bg-red-50', 'border-red-300');
                item.classList.remove('bg-white', 'border-gray-200');
                item.querySelector('a').classList.add('line-through', 'text-red-400');
                item.querySelector('a').classList.remove('text-blue-600');
            } else {
                item.classList.remove('bg-red-50', 'border-red-300');
                item.classList.add('bg-white', 'border-gray-200');
                item.querySelector('a').classList.remove('line-through', 'text-red-400');
                item.querySelector('a').classList.add('text-blue-600');
            }
        });
    });

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File "${file.name}" terlalu besar! Maksimal 5MB.`);
                    this.value = '';
                    return;
                }
                selectedFiles.items.add(file);
            }
            renderNewFileList();
            this.value = '';
        });

        newFileList.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-new-file');
            if (!btn) return;
            const index = parseInt(btn.getAttribute('data-index'));
            const newDT = new DataTransfer();
            for (let i = 0; i < selectedFiles.files.length; i++) {
                if (i !== index) newDT.items.add(selectedFiles.files[i]);
            }
            selectedFiles = newDT;
            renderNewFileList();
        });

        if (dropZone) {
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('bg-indigo-50', 'border-indigo-400');
            });
            dropZone.addEventListener('dragleave', function() {
                this.classList.remove('bg-indigo-50', 'border-indigo-400');
            });
            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('bg-indigo-50', 'border-indigo-400');
                for (let i = 0; i < e.dataTransfer.files.length; i++) {
                    const file = e.dataTransfer.files[i];
                    const ext = file.name.split('.').pop().toLowerCase();
                    if (!['jpg','jpeg','png','pdf'].includes(ext)) {
                        alert(`File "${file.name}" tidak didukung.`);
                        return;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`File "${file.name}" terlalu besar! Maksimal 5MB.`);
                        return;
                    }
                    selectedFiles.items.add(file);
                }
                renderNewFileList();
            });
        }
    }
});
</script>
@endsection
