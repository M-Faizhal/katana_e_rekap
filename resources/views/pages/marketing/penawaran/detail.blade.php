@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-red-800 rounded-2xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 text-white shadow-lg mt-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <button onclick="window.history.back()" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-2 transition-colors duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </button>
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold">Detail Penawaran</h1>
                <p class="text-red-100 text-sm sm:text-base mt-1">{{ $proyek->kode_proyek ?? 'PRJ-XXXX' }}</p>
            </div>
        </div>
        <div class="hidden sm:block">
            <div class="text-right">
                <p class="text-red-100 text-sm">Status Penawaran</p>
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                    @if($penawaran->status === 'selesai') bg-green-500 text-white
                    @elseif($penawaran->status === 'ACC') bg-blue-500 text-white
                    @elseif($penawaran->status === 'pending') bg-yellow-500 text-gray-900
                    @elseif($penawaran->status === 'Ditolak') bg-red-500 text-white
                    @else bg-gray-600 text-white
                    @endif">
                    {{ $penawaran->status === 'ACC' ? 'Disetujui' : ucfirst($penawaran->status ?? 'draft') }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-20">

    <!-- Left Column - Project Info -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Project Details -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-project-diagram text-red-600 mr-2"></i>
                Informasi Proyek
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">ID Proyek</label>
                    <p class="text-base font-semibold text-gray-800">{{ $proyek->kode_proyek ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Tanggal Proyek</label>
                    <p class="text-base font-semibold text-gray-800">
                        {{ $proyek->tanggal ? \Carbon\Carbon::parse($proyek->tanggal)->format('d M Y') : '-' }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Kabupaten/Kota</label>
                    <p class="text-base font-semibold text-gray-800">{{ $proyek->kab_kota ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Nama Instansi</label>
                    <p class="text-base font-semibold text-gray-800">{{ $proyek->instansi ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Jenis Pengadaan</label>
                    <p class="text-base font-semibold text-gray-800">{{ $proyek->jenis_pengadaan ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Status Proyek</label>
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                        @if($proyek->status === 'selesai') bg-green-100 text-green-800
                        @elseif($proyek->status === 'pengiriman') bg-orange-100 text-orange-800
                        @elseif($proyek->status === 'pembayaran') bg-purple-100 text-purple-800
                        @elseif($proyek->status === 'penawaran') bg-blue-100 text-blue-800
                        @elseif($proyek->status === 'Menunggu') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($proyek->status) }}
                    </span>
                </div>
                @if($proyek->catatan)
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500">Catatan Proyek</label>
                    <p class="text-base text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $proyek->catatan }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Penawaran Details -->
        @if($proyek->status !== 'Menunggu')
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-file-invoice text-red-600 mr-2"></i>
                Detail Penawaran
            </h3>
            </div>

            @if($penawaranDetails && $penawaranDetails->count() > 0)

            <div class="space-y-4">
                @foreach($penawaranDetails as $detail)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                    <label class="text-xs text-gray-500">Nama Barang</label>
                    <p class="font-medium text-gray-800">{{ $detail->nama_barang }}</p>
                    </div>
                    <div>
                    <label class="text-xs text-gray-500">Qty</label>
                    <p class="font-medium text-gray-800">{{ $detail->qty }}</p>
                    </div>
                    <div>
                    <label class="text-xs text-gray-500">Harga Satuan</label>
                    <p class="font-medium text-gray-800">
                        @if($detail->harga_satuan > 0)
                        Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                        @else
                        <span class="text-orange-600 text-sm">Belum diset</span>
                        @endif
                    </p>
                    </div>
                    <div>
                    <label class="text-xs text-gray-500">Subtotal</label>
                    <p class="font-medium text-red-600">
                        @if($detail->subtotal > 0)
                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        @else
                        <span class="text-orange-600 text-sm">Rp 0</span>
                        @endif
                    </p>
                    </div>
                </div>
                @if($detail->spesifikasi)
                <div class="mt-3">
                    <label class="text-xs text-gray-500">Spesifikasi</label>
                    <p class="text-sm text-gray-700">{{ $detail->spesifikasi }}</p>
                </div>
                @endif

                {{-- File Spesifikasi dari Proyek --}}
                @php
                    // Cari proyek barang yang sesuai dengan nama barang di penawaran detail
                    $matchingProyekBarang = null;
                    if(isset($proyekBarangWithFiles)) {
                        $matchingProyekBarang = $proyekBarangWithFiles->where('nama_barang', $detail->nama_barang)->first();
                    }
                @endphp

                @if($matchingProyekBarang && $matchingProyekBarang->spesifikasi_files)
                    @php
                        $files = is_string($matchingProyekBarang->spesifikasi_files)
                            ? json_decode($matchingProyekBarang->spesifikasi_files, true)
                            : $matchingProyekBarang->spesifikasi_files;
                    @endphp
                    @if($files && is_array($files) && count($files) > 0)
                    <div class="mt-3">
                        <label class="text-xs text-gray-500">File Spesifikasi ({{ count($files) }} file)</label>
                        <div class="mt-2 space-y-2">
                            @foreach($files as $file)
                            <div class="flex items-center justify-between bg-white p-2 rounded border text-sm">
                                <div class="flex items-center space-x-2">
                                    @php
                                        $extension = strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION));
                                        $iconClass = 'fas fa-file text-gray-500';
                                        $iconColor = 'text-gray-500';

                                        switch($extension) {
                                            case 'pdf':
                                                $iconClass = 'fas fa-file-pdf';
                                                $iconColor = 'text-red-500';
                                                break;
                                            case 'doc':
                                            case 'docx':
                                                $iconClass = 'fas fa-file-word';
                                                $iconColor = 'text-blue-500';
                                                break;
                                            case 'xls':
                                            case 'xlsx':
                                                $iconClass = 'fas fa-file-excel';
                                                $iconColor = 'text-green-500';
                                                break;
                                            case 'jpg':
                                            case 'jpeg':
                                            case 'png':
                                                $iconClass = 'fas fa-file-image';
                                                $iconColor = 'text-purple-500';
                                                break;
                                        }
                                    @endphp
                                    <i class="{{ $iconClass }} {{ $iconColor }}"></i>
                                    <span class="font-medium text-gray-900">{{ $file['original_name'] }}</span>
                                    <span class="text-gray-500 text-xs">
                                        ({{ isset($file['file_size']) ? number_format($file['file_size'] / 1024, 1) . ' KB' : 'Unknown size' }})
                                    </span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    @if($extension === 'pdf')
                                    <button onclick="previewSpecFile('{{ $file['stored_name'] }}')"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded transition-colors"
                                            title="Preview PDF">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    @endif
                                    <button onclick="downloadSpecFile('{{ $file['stored_name'] }}')"
                                            class="text-green-600 hover:text-green-800 p-1 rounded transition-colors"
                                            title="Download">
                                        <i class="fas fa-download text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif
                </div>
                @endforeach

                <!-- Total -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-lg font-semibold text-gray-800">Total Penawaran:</span>
                    <span class="text-2xl font-bold text-red-600">
                    Rp {{ number_format($penawaranDetails->sum('subtotal'), 0, ',', '.') }}
                    </span>
                </div>
                @if($penawaran->total_nilai != $penawaranDetails->sum('subtotal'))
                <div class="text-xs text-amber-600 bg-amber-50 p-2 rounded">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Total tersimpan (Rp {{ number_format($penawaran->total_nilai ?? 0, 0, ',', '.') }}) berbeda dengan perhitungan detail
                </div>
                @endif
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-box-open text-gray-300 text-4xl mb-4"></i>
                <h4 class="text-lg font-medium text-gray-700 mb-2">Belum Ada Data Barang</h4>
                <p class="text-gray-500 mb-4">Proyek ini belum memiliki data barang. Silakan tambah data barang terlebih dahulu di proyek.</p>
                @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing')
                <div class="space-y-2">
                <button onclick="openEditPenawaranModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm">
                    <i class="fas fa-plus mr-1"></i>
                    Tambah Detail Penawaran Manual
                </button>
                <p class="text-xs text-gray-400">atau edit proyek untuk menambah data barang</p>
                </div>
                @else
                <div class="space-y-2">
                <p class="text-sm text-gray-600 bg-gray-100 px-4 py-2 rounded-lg">
                    <i class="fas fa-lock mr-1"></i>
                    Hanya superadmin dan admin marketing yang dapat menambah detail penawaran
                </p>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Data Barang Proyek (Reference) -->
        @if($proyek->proyekBarang && $proyek->proyekBarang->count() > 0)
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-boxes text-blue-600 mr-2"></i>
                Data Barang Proyek
                <span class="ml-2 bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs font-medium">Referensi</span>
            </h3>

            <div class="space-y-3">
                @foreach($proyek->proyekBarang as $barang)
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="text-xs text-gray-500">Nama Barang</label>
                            <p class="font-medium text-gray-800">{{ $barang->nama_barang }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Qty</label>
                            <p class="font-medium text-gray-800">{{ $barang->jumlah }} {{ $barang->satuan }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Harga Satuan</label>
                            <p class="font-medium text-gray-800">
                                @if($barang->harga_satuan > 0)
                                    Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}
                                @else
                                    <span class="text-gray-400 text-sm">Belum diset</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Total</label>
                            <p class="font-medium text-blue-600">
                                @if($barang->harga_total > 0)
                                    Rp {{ number_format($barang->harga_total, 0, ',', '.') }}
                                @else
                                    <span class="text-gray-400 text-sm">Rp 0</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($barang->spesifikasi)
                    <div class="mt-2">
                        <label class="text-xs text-gray-500">Spesifikasi</label>
                        <p class="text-sm text-gray-700">{{ $barang->spesifikasi }}</p>
                    </div>
                    @endif

                    {{-- File Spesifikasi --}}
                    @if($barang->spesifikasi_files)
                        @php
                            $files = is_string($barang->spesifikasi_files)
                                ? json_decode($barang->spesifikasi_files, true)
                                : $barang->spesifikasi_files;
                        @endphp
                        @if($files && is_array($files) && count($files) > 0)
                        <div class="mt-2">
                            <label class="text-xs text-gray-500">File Spesifikasi ({{ count($files) }} file)</label>
                            <div class="mt-1 space-y-1">
                                @foreach($files as $file)
                                <div class="flex items-center justify-between bg-white p-2 rounded border text-sm">
                                    <div class="flex items-center space-x-2">
                                        @php
                                            $extension = strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION));
                                            $iconClass = 'fas fa-file text-gray-500';
                                            $iconColor = 'text-gray-500';

                                            switch($extension) {
                                                case 'pdf':
                                                    $iconClass = 'fas fa-file-pdf';
                                                    $iconColor = 'text-red-500';
                                                    break;
                                                case 'doc':
                                                case 'docx':
                                                    $iconClass = 'fas fa-file-word';
                                                    $iconColor = 'text-blue-500';
                                                    break;
                                                case 'xls':
                                                case 'xlsx':
                                                    $iconClass = 'fas fa-file-excel';
                                                    $iconColor = 'text-green-500';
                                                    break;
                                                case 'jpg':
                                                case 'jpeg':
                                                case 'png':
                                                    $iconClass = 'fas fa-file-image';
                                                    $iconColor = 'text-purple-500';
                                                    break;
                                            }
                                        @endphp
                                        <i class="{{ $iconClass }} {{ $iconColor }}"></i>
                                        <span class="font-medium text-gray-900">{{ $file['original_name'] }}</span>
                                        <span class="text-gray-500 text-xs">
                                            ({{ isset($file['file_size']) ? number_format($file['file_size'] / 1024, 1) . ' KB' : 'Unknown size' }})
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        @if($extension === 'pdf')
                                        <button onclick="previewSpecFile('{{ $file['stored_name'] }}')"
                                                class="text-blue-600 hover:text-blue-800 p-1 rounded transition-colors"
                                                title="Preview PDF">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                        @endif
                                        <button onclick="downloadSpecFile('{{ $file['stored_name'] }}')"
                                                class="text-green-600 hover:text-green-800 p-1 rounded transition-colors"
                                                title="Download">
                                            <i class="fas fa-download text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <!-- Right Column - Documents -->
    <div class="space-y-6">

        <!-- Document Upload Form -->
        @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin_marketing')
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-upload text-red-600 mr-2"></i>
                Upload Dokumen
            </h3>

            <form id="uploadForm" class="space-y-4">
                @csrf
                <input type="hidden" name="id_proyek" value="{{ $proyek->id_proyek }}">
                <input type="hidden" name="id_penawaran" value="{{ $penawaran->id_penawaran }}">

                <!-- Tanggal Penawaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penawaran</label>
                    <input type="date" name="tanggal_penawaran"
                           value="{{ $penawaran->tanggal_penawaran ? $penawaran->tanggal_penawaran->format('Y-m-d') : now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                </div>

                <!-- Surat Penawaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Surat Penawaran <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="surat_penawaran"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           @if(!$penawaran->surat_penawaran) required @endif>
                    <p class="text-xs text-gray-500 mt-1">File: PDF, DOC, DOCX, JPG, PNG (Max: 5MB) - Wajib diisi</p>
                    @if($penawaran->surat_penawaran)
                    <div class="mt-2 flex items-center text-sm text-green-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span>File saat ini: {{ $penawaran->surat_penawaran }}</span>
                        <a href="{{ route('penawaran.download', ['type' => 'penawaran', 'filename' => $penawaran->surat_penawaran]) }}"
                           class="ml-2 text-red-600 hover:text-red-700">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Surat Pesanan (Optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Surat Pesanan <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <input type="file" name="surat_pesanan"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           accept=".pdf,.doc,.docx">
                    <p class="text-xs text-gray-500 mt-1">File: PDF, DOC, DOCX (Max: 5MB)</p>
                    @if($penawaran->surat_pesanan)
                    <div class="mt-2 flex items-center text-sm text-green-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span>File saat ini: {{ $penawaran->surat_pesanan }}</span>
                        <a href="{{ route('penawaran.download', ['type' => 'pesanan', 'filename' => $penawaran->surat_pesanan]) }}"
                           class="ml-2 text-red-600 hover:text-red-700">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="catatan" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Tambahkan catatan untuk penawaran ini...">{{ $penawaran->catatan ?? '' }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3 rounded-lg transition-colors duration-200 font-medium
                               @if(strtolower($proyek->status) === 'menunggu')
                                   bg-gray-400 text-gray-600 cursor-not-allowed
                               @else
                                   bg-red-600 text-white hover:bg-red-700
                               @endif"
                        @if(strtolower($proyek->status) === 'menunggu') disabled @endif>
                    <i class="fas fa-save mr-2"></i>
                    @if(strtolower($proyek->status) === 'menunggu')
                        Tidak Dapat Disimpan (Status Menunggu)
                    @else
                        Simpan Penawaran
                    @endif
                </button>

                @if(strtolower($proyek->status) === 'menunggu')
                <div class="mt-2 text-center">
                    <p class="text-xs text-amber-600 bg-amber-50 px-3 py-2 rounded-lg">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Penawaran tidak dapat disimpan karena status proyek masih "Menunggu".
                        Ubah status proyek terlebih dahulu untuk melanjutkan.
                    </p>
                </div>
                @endif
            </form>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-lock text-gray-600 mr-2"></i>
                Upload Dokumen
            </h3>
            <div class="text-center py-8">
                <i class="fas fa-lock text-gray-400 text-4xl mb-4"></i>
                <h4 class="text-lg font-medium text-gray-700 mb-2">Akses Terbatas</h4>
                <p class="text-gray-500">Hanya superadmin dan admin marketing yang dapat mengupload dokumen penawaran.</p>
            </div>
        </div>
        @endif

        <!-- Current Documents -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-folder text-red-600 mr-2"></i>
                Dokumen Tersimpan
            </h3>

            <div class="space-y-3">
                <!-- Surat Penawaran -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Surat Penawaran</p>
                            <p class="text-xs text-gray-500">
                                @if($penawaran->surat_penawaran)
                                    {{ $penawaran->surat_penawaran }}
                                @else
                                    Belum ada file
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($penawaran->surat_penawaran)
                    <a href="{{ asset('storage/penawaran/' . $penawaran->surat_penawaran) }}"
                       class="text-red-600 hover:text-red-700 p-2" download>
                        <i class="fas fa-download"></i>
                    </a>
                    @endif
                </div>

                <!-- Surat Pesanan -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-file-pdf text-blue-500 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Surat Pesanan</p>
                            <p class="text-xs text-gray-500">
                                @if($penawaran->surat_pesanan)
                                    {{ $penawaran->surat_pesanan }}
                                @else
                                    Belum ada file
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($penawaran->surat_pesanan)
                    <a href="{{ asset('storage/penawaran/' . $penawaran->surat_pesanan) }}"
                       class="text-red-600 hover:text-red-700 p-2" download>
                        <i class="fas fa-download"></i>
                    </a>
                    @endif
                </div>

                <!-- File Approval -->
                @if(isset($kalkulasiHps) && $kalkulasiHps && $kalkulasiHps->bukti_file_approval)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        @php
                            $fileExtension = strtolower(pathinfo($kalkulasiHps->bukti_file_approval, PATHINFO_EXTENSION));
                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                        @endphp
                        <i class="fas {{ $isImage ? 'fa-image text-green-500' : 'fa-file-pdf text-red-500' }} mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">File Approval</p>
                            <p class="text-xs text-gray-500">{{ $kalkulasiHps->bukti_file_approval }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($isImage)
                        <button onclick="showImagePreview('{{ asset('storage/approval_files/' . $kalkulasiHps->bukti_file_approval) }}', '{{ $kalkulasiHps->bukti_file_approval }}')"
                                class="text-blue-600 hover:text-blue-700 p-2" title="Preview">
                            <i class="fas fa-eye"></i>
                        </button>
                        @endif
                        <a href="{{ asset('storage/approval_files/' . $kalkulasiHps->bukti_file_approval) }}"
                           class="text-red-600 hover:text-red-700 p-2" title="Download" download>
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Success Modal -->
@include('components.success-modal')

<script>
// Form submission
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Check if project status is "menunggu" - prevent submission
    const projectStatus = '{{ strtolower($proyek->status) }}';
    if (projectStatus === 'menunggu') {
        alert('Tidak dapat menyimpan penawaran. Status proyek masih "Menunggu".');
        return false;
    }

    // Check if surat_penawaran file is uploaded (if not already exists)
    const fileInput = this.querySelector('input[name="surat_penawaran"]');
    const hasExistingFile = {{ $penawaran->surat_penawaran ? 'true' : 'false' }};

    if (!hasExistingFile && (!fileInput.files || fileInput.files.length === 0)) {
        alert('Surat Penawaran wajib diupload!');
        fileInput.focus();
        return false;
    }

    // Validate file size (5MB = 5 * 1024 * 1024 bytes)
    if (fileInput.files && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar! Maksimal 5MB.');
            fileInput.focus();
            return false;
        }

        // Validate file type
        const allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        const fileName = file.name.toLowerCase();
        const fileExtension = fileName.split('.').pop();

        if (!allowedTypes.includes(fileExtension)) {
            alert('Tipe file tidak diizinkan! Hanya PDF, DOC, DOCX, JPG, PNG yang diperbolehkan.');
            fileInput.focus();
            return false;
        }
    }

    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;

    // Show loading state
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitButton.disabled = true;

    // Get penawaran ID for the endpoint
    const penawaranId = formData.get('id_penawaran');
    const url = penawaranId && penawaranId !== '' ? `/marketing/penawaran/${penawaranId}` : '/marketing/penawaran';
    const method = penawaranId && penawaranId !== '' ? 'POST' : 'POST';

    // Add method override for PUT if updating
    if (penawaranId && penawaranId !== '') {
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        // Debug: Log response status and headers
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        // Check if response is ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Check content type
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response bukan JSON yang valid. Content-Type: ' + contentType);
        }

        return response.json();
    })
    .then(data => {
        console.log('Response data:', data); // Debug log

        if (data.success) {
            // Show success message
            if (typeof showSuccessModal === 'function') {
                showSuccessModal(data.message);
            } else {
                alert(data.message);
            }

            // Show additional info if status changed
            if (data.status_changed) {
                setTimeout(() => {
                    if (typeof showSuccessModal === 'function') {
                        showSuccessModal('Proyek berhasil dipindahkan ke tahap pembayaran!');
                    } else {
                        alert('Proyek berhasil dipindahkan ke tahap pembayaran!');
                    }
                }, 2000);
            }

            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, data.status_changed ? 3500 : 1500);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan saat menyimpan data');
        }
    })
    .catch(error => {
        console.error('Error detail:', error);

        let errorMessage = 'Terjadi kesalahan saat menyimpan data';

        if (error.message.includes('HTTP error')) {
            errorMessage = 'Server error: ' + error.message;
        } else if (error.message.includes('JSON')) {
            errorMessage = 'Response server tidak valid. Silakan coba lagi atau hubungi administrator.';
        } else if (error.message) {
            errorMessage = error.message;
        }

        alert(errorMessage);
    })
    .finally(() => {
        // Reset button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});

// Calculate total from penawaran details
function calculateTotal() {
    @if($penawaranDetails && $penawaranDetails->count() > 0)
    const calculatedTotal = {{ $penawaranDetails->sum('subtotal') }};
    document.getElementById('total_nilai').value = calculatedTotal;

    // Show notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    notification.innerHTML = '<i class="fas fa-check mr-2"></i>Total berhasil dihitung: Rp ' + new Intl.NumberFormat('id-ID').format(calculatedTotal);
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
    @else
    alert('Belum ada detail penawaran untuk dihitung');
    @endif
}

// Placeholder for edit penawaran modal
function openEditPenawaranModal() {
    alert('Fitur edit detail penawaran akan segera tersedia');
}

// Functions for image preview modal
function showImagePreview(imageUrl, fileName) {
    const modal = document.getElementById('imagePreviewModal');
    document.getElementById('previewImage').src = imageUrl;
    document.getElementById('imageTitle').textContent = 'Preview: ' + fileName;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImagePreview() {
    const modal = document.getElementById('imagePreviewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('previewImage').src = '';
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imagePreviewModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImagePreview();
            }
        });
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImagePreview();
        }
    });

    // Add file input validation and preview
    const fileInput = document.querySelector('input[name="surat_penawaran"]');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar! Maksimal 5MB.');
                    this.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
                const fileName = file.name.toLowerCase();
                const fileExtension = fileName.split('.').pop();

                if (!allowedTypes.includes(fileExtension)) {
                    alert('Tipe file tidak diizinkan! Hanya PDF, DOC, DOCX, JPG, PNG yang diperbolehkan.');
                    this.value = '';
                    return;
                }

                // Show file info
                const fileInfo = document.createElement('div');
                fileInfo.className = 'mt-2 text-sm text-green-600 bg-green-50 p-2 rounded';
                fileInfo.innerHTML = `
                    <i class="fas fa-check-circle mr-1"></i>
                    File dipilih: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)
                `;

                // Remove existing file info
                const existingInfo = this.parentNode.querySelector('.file-info');
                if (existingInfo) {
                    existingInfo.remove();
                }

                fileInfo.classList.add('file-info');
                this.parentNode.appendChild(fileInfo);
            }
        });
    }
});

// Functions for specification file handling
function downloadSpecFile(filename) {
    const url = `{{ url('/marketing/proyek/file') }}/${filename}`;
    window.open(url, '_blank');
}

function previewSpecFile(filename) {
    const url = `{{ url('/marketing/proyek/file') }}/${filename}/preview`;
    window.open(url, '_blank');
}
</script>

<!-- Modal Preview Image untuk File Approval -->
<div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg max-w-4xl max-h-screen overflow-auto m-4">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 id="imageTitle" class="text-lg font-medium text-gray-900">Preview File Approval</h3>
            <button onclick="closeImagePreview()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <img id="previewImage" src="" alt="Preview" class="max-w-full h-auto mx-auto">
        </div>
    </div>
</div>

@endsection
