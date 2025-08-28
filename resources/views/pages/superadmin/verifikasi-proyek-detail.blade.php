@extends('layouts.app')

@section('title', 'Detail Verifikasi Proyek - Cyber KATANA')

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
                    <span class="text-gray-600">Nama Proyek:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->nama_barang }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kode Proyek:</span>
                    <span class="font-medium text-gray-900">{{ $proyek->kode_proyek ?? 'PRJ-' . str_pad($proyek->id_proyek, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Proyek:</span>
                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($proyek->tanggal)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Penawaran:</span>
                    @php
                        $totalPenawaran = 0;
                        if ($proyek->semuaPenawaran && $proyek->semuaPenawaran->isNotEmpty()) {
                            foreach ($proyek->semuaPenawaran as $penawaran) {
                                if ($penawaran->penawaranDetail) {
                                    $totalPenawaran += $penawaran->penawaranDetail->sum('subtotal');
                                }
                            }
                        }
                    @endphp
                    <span class="font-medium text-green-600">Rp {{ number_format($totalPenawaran, 0, ',', '.') }}</span>
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
                    <span class="font-medium text-gray-900">{{ $proyek->kab_kota }}</span>
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
                @if($proyek->adminMarketing)
                    <p class="text-gray-600">{{ $proyek->adminMarketing->nama }}</p>
                    <p class="text-sm text-gray-500">{{ $proyek->adminMarketing->email }}</p>
                @else
                    <p class="text-gray-500 italic">Tidak ada admin marketing</p>
                @endif
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-2">Admin Purchasing</h4>
                @if($proyek->adminPurchasing)
                    <p class="text-gray-600">{{ $proyek->adminPurchasing->nama }}</p>
                    <p class="text-sm text-gray-500">{{ $proyek->adminPurchasing->email }}</p>
                @else
                    <p class="text-gray-500 italic">Tidak ada admin purchasing</p>
                @endif
            </div>
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
                                @if($detail->barang)
                                <div class="text-sm text-gray-500">{{ $detail->barang->brand }} - {{ $detail->barang->kategori }}</div>
                                <div class="text-xs text-gray-400">{{ $detail->spesifikasi }}</div>
                                @else
                                <div class="text-sm text-gray-500">Brand/Kategori tidak tersedia</div>
                                <div class="text-xs text-gray-400">{{ $detail->spesifikasi }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($detail->barang && $detail->barang->vendor)
                                {{ $detail->barang->vendor->nama_vendor }}
                            @else
                                Vendor tidak tersedia
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $detail->qty }} {{ $detail->satuan }}</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Dokumen Penawaran dari Vendor -->
    @if($proyek->semuaPenawaran && $proyek->semuaPenawaran->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-file-signature mr-2 text-blue-500"></i>
            Dokumen Penawaran Vendor
        </h3>
        <div class="space-y-4">
            @foreach($proyek->semuaPenawaran as $penawaran)
                @if($penawaran->status === 'ACC')
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Penawaran ID: {{ $penawaran->id_penawaran }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if($penawaran->surat_penawaran)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-900 text-sm">Surat Penawaran</h5>
                                    <p class="text-xs text-gray-500">Dokumen penawaran resmi</p>
                                </div>
                                <a href="{{ asset('storage/' . $penawaran->surat_penawaran) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    <span class="text-sm">Lihat</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($penawaran->spesifikasi_teknis)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-900 text-sm">Spesifikasi Teknis</h5>
                                    <p class="text-xs text-gray-500">Detail spesifikasi</p>
                                </div>
                                <a href="{{ asset('storage/' . $penawaran->spesifikasi_teknis) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    <span class="text-sm">Lihat</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($penawaran->rincian_harga)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-900 text-sm">Rincian Harga</h5>
                                    <p class="text-xs text-gray-500">Breakdown harga</p>
                                </div>
                                <a href="{{ asset('storage/' . $penawaran->rincian_harga) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    <span class="text-sm">Lihat</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($penawaran->dokumentasi_produk)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-900 text-sm">Dokumentasi Produk</h5>
                                    <p class="text-xs text-gray-500">Foto/video produk</p>
                                </div>
                                <a href="{{ asset('storage/' . $penawaran->dokumentasi_produk) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    <span class="text-sm">Lihat</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($penawaran->sertifikat_produk)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-900 text-sm">Sertifikat Produk</h5>
                                    <p class="text-xs text-gray-500">Sertifikat kualitas</p>
                                </div>
                                <a href="{{ asset('storage/' . $penawaran->sertifikat_produk) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    <span class="text-sm">Lihat</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($penawaran->katalog_produk)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-900 text-sm">Katalog Produk</h5>
                                    <p class="text-xs text-gray-500">Katalog lengkap</p>
                                </div>
                                <a href="{{ asset('storage/' . $penawaran->katalog_produk) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    <span class="text-sm">Lihat</span>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Penagihan Dinas dan Bukti Pembayaran -->
    @if($proyek->penagihanDinas && $proyek->penagihanDinas->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-file-invoice-dollar mr-2 text-green-500"></i>
            Penagihan Dinas & Bukti Pembayaran
        </h3>
        <div class="space-y-4">
            @foreach($proyek->penagihanDinas as $tagihan)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Info Penagihan -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Informasi Penagihan</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nomor Invoice:</span>
                                <span class="font-medium text-gray-900">{{ $tagihan->nomor_invoice }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Jatuh Tempo:</span>
                                <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Harga:</span>
                                <span class="font-medium text-green-600">Rp {{ number_format($tagihan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            @if($tagihan->persentase_dp && $tagihan->jumlah_dp)
                            <div class="flex justify-between">
                                <span class="text-gray-600">DP ({{ $tagihan->persentase_dp }}%):</span>
                                <span class="font-medium text-blue-600">Rp {{ number_format($tagihan->jumlah_dp, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status Pembayaran:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $tagihan->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : 
                                       ($tagihan->status_pembayaran === 'dp' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    @if($tagihan->status_pembayaran === 'belum_bayar')
                                        Belum Bayar
                                    @elseif($tagihan->status_pembayaran === 'dp')
                                        DP
                                    @elseif($tagihan->status_pembayaran === 'lunas')
                                        Lunas
                                    @else
                                        {{ ucfirst($tagihan->status_pembayaran) }}
                                    @endif
                                </span>
                            </div>
                            @if($tagihan->keterangan)
                            <div class="pt-2">
                                <span class="text-gray-600">Keterangan:</span>
                                <p class="text-sm text-gray-900 mt-1">{{ $tagihan->keterangan }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- File Dokumen Penagihan -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Dokumen Penagihan</h4>
                        <div class="space-y-3">
                            @if($tagihan->berita_acara_serah_terima)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="font-medium text-gray-900 text-sm">Berita Acara Serah Terima</h5>
                                        <p class="text-xs text-gray-500">Dokumen serah terima barang</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $tagihan->berita_acara_serah_terima) }}" target="_blank"
                                       class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        <span class="text-sm">Lihat</span>
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($tagihan->invoice)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="font-medium text-gray-900 text-sm">Invoice</h5>
                                        <p class="text-xs text-gray-500">Dokumen invoice resmi</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $tagihan->invoice) }}" target="_blank"
                                       class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        <span class="text-sm">Lihat</span>
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($tagihan->pnbp)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="font-medium text-gray-900 text-sm">PNBP</h5>
                                        <p class="text-xs text-gray-500">Penerimaan Negara Bukan Pajak</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $tagihan->pnbp) }}" target="_blank"
                                       class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        <span class="text-sm">Lihat</span>
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($tagihan->faktur_pajak)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="font-medium text-gray-900 text-sm">Faktur Pajak</h5>
                                        <p class="text-xs text-gray-500">Dokumen pajak</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $tagihan->faktur_pajak) }}" target="_blank"
                                       class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        <span class="text-sm">Lihat</span>
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($tagihan->surat_lainnya)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="font-medium text-gray-900 text-sm">Surat Lainnya</h5>
                                        <p class="text-xs text-gray-500">Dokumen pendukung lainnya</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $tagihan->surat_lainnya) }}" target="_blank"
                                       class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        <span class="text-sm">Lihat</span>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bukti Pembayaran -->
                @if($tagihan->buktiPembayaran && $tagihan->buktiPembayaran->isNotEmpty())
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="font-medium text-gray-900 mb-3">Bukti Pembayaran</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($tagihan->buktiPembayaran as $bukti)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="space-y-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h5 class="font-medium text-gray-900 text-sm">{{ ucfirst($bukti->jenis_pembayaran) }}</h5>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($bukti->tanggal_bayar)->format('d M Y') }}</p>
                                    </div>
                                    <span class="text-sm font-medium text-green-600">
                                        Rp {{ number_format($bukti->jumlah_bayar, 0, ',', '.') }}
                                    </span>
                                </div>
                                @if($bukti->bukti_pembayaran)
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xs text-gray-600">File Bukti:</span>
                                    <a href="{{ asset('storage/' . $bukti->bukti_pembayaran) }}" target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        Lihat
                                    </a>
                                </div>
                                @endif
                                @if($bukti->keterangan)
                                <p class="text-xs text-gray-600 mt-1">{{ $bukti->keterangan }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Riwayat Pembayaran Vendor -->
    @if($pembayaran->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-credit-card mr-2 text-purple-500"></i>
            Riwayat Pembayaran Vendor
        </h3>
        <div class="space-y-3">
            @foreach($pembayaran as $bayar)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900">{{ $bayar->jenis_bayar }}</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $bayar->metode_bayar }}</p>
                        @if($bayar->vendor)
                        <p class="text-xs text-gray-600">Vendor: {{ $bayar->vendor->nama_vendor }}</p>
                        @endif
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

    <!-- Informasi Pengiriman per Vendor -->
    @if($proyek->semuaPenawaran && $proyek->semuaPenawaran->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-shipping-fast mr-2 text-red-500"></i>
            Informasi Pengiriman per Vendor
        </h3>
        <div class="space-y-6">
            @foreach($proyek->semuaPenawaran as $penawaran)
                @if($penawaran->status === 'ACC' && $penawaran->pengiriman && $penawaran->pengiriman->isNotEmpty())
                    @foreach($penawaran->pengiriman as $pengiriman)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Info Pengiriman -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">
                                    Pengiriman - {{ $pengiriman->vendor->nama_vendor ?? 'Vendor tidak ditemukan' }}
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">No. Surat Jalan:</span>
                                        <span class="font-medium text-gray-900">{{ $pengiriman->no_surat_jalan }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tanggal Kirim:</span>
                                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($pengiriman->tanggal_kirim)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Alamat Kirim:</span>
                                        <span class="font-medium text-gray-900">{{ $pengiriman->alamat_kirim }}</span>
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
                                            ][$pengiriman->status_verifikasi] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ $pengiriman->status_verifikasi }}
                                        </span>
                                    </div>
                                    @if($pengiriman->catatan)
                                    <div class="pt-2">
                                        <span class="text-gray-600">Catatan:</span>
                                        <p class="text-sm text-gray-900 mt-1">{{ $pengiriman->catatan }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- File Surat Jalan -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">Dokumen Pengiriman</h4>
                                @if($pengiriman->file_surat_jalan)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h5 class="font-medium text-gray-900 text-sm">File Surat Jalan</h5>
                                            <p class="text-xs text-gray-500">Dokumen pengiriman resmi</p>
                                        </div>
                                        <a href="{{ asset('storage/' . $pengiriman->file_surat_jalan) }}" target="_blank"
                                           class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Dokumentasi Pengiriman untuk vendor ini -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-3">Dokumentasi Pengiriman</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Foto Berangkat -->
                                <div class="text-center">
                                    <h5 class="font-medium text-gray-900 mb-2 text-sm">Foto Berangkat</h5>
                                    @if($pengiriman->foto_berangkat)
                                        <img src="{{ asset('storage/' . $pengiriman->foto_berangkat) }}" 
                                             alt="Foto Berangkat" 
                                             class="w-full h-24 object-cover rounded-lg border border-gray-300 cursor-pointer"
                                             onclick="openImageModal('{{ asset('storage/' . $pengiriman->foto_berangkat) }}', 'Foto Berangkat - {{ $pengiriman->vendor->nama_vendor ?? 'Vendor' }}')">
                                    @else
                                        <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Foto Perjalanan -->
                                <div class="text-center">
                                    <h5 class="font-medium text-gray-900 mb-2 text-sm">Foto Perjalanan</h5>
                                    @if($pengiriman->foto_perjalanan)
                                        <img src="{{ asset('storage/' . $pengiriman->foto_perjalanan) }}" 
                                             alt="Foto Perjalanan" 
                                             class="w-full h-24 object-cover rounded-lg border border-gray-300 cursor-pointer"
                                             onclick="openImageModal('{{ asset('storage/' . $pengiriman->foto_perjalanan) }}', 'Foto Perjalanan - {{ $pengiriman->vendor->nama_vendor ?? 'Vendor' }}')">
                                    @else
                                        <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Foto Sampai -->
                                <div class="text-center">
                                    <h5 class="font-medium text-gray-900 mb-2 text-sm">Foto Sampai</h5>
                                    @if($pengiriman->foto_sampai)
                                        <img src="{{ asset('storage/' . $pengiriman->foto_sampai) }}" 
                                             alt="Foto Sampai" 
                                             class="w-full h-24 object-cover rounded-lg border border-gray-300 cursor-pointer"
                                             onclick="openImageModal('{{ asset('storage/' . $pengiriman->foto_sampai) }}', 'Foto Sampai - {{ $pengiriman->vendor->nama_vendor ?? 'Vendor' }}')">
                                    @else
                                        <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Tanda Terima -->
                                <div class="text-center">
                                    <h5 class="font-medium text-gray-900 mb-2 text-sm">Tanda Terima</h5>
                                    @if($pengiriman->tanda_terima)
                                        <img src="{{ asset('storage/' . $pengiriman->tanda_terima) }}" 
                                             alt="Tanda Terima" 
                                             class="w-full h-24 object-cover rounded-lg border border-gray-300 cursor-pointer"
                                             onclick="openImageModal('{{ asset('storage/' . $pengiriman->tanda_terima) }}', 'Tanda Terima - {{ $pengiriman->vendor->nama_vendor ?? 'Vendor' }}')">
                                    @else
                                        <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Verifikasi Proyek -->
    @php
        $allPengirimanSampai = true;
        $hasValidPengiriman = false;
        
        if ($proyek->semuaPenawaran && $proyek->semuaPenawaran->isNotEmpty()) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
                if ($penawaran->status === 'ACC' && $penawaran->pengiriman && $penawaran->pengiriman->isNotEmpty()) {
                    $hasValidPengiriman = true;
                    foreach ($penawaran->pengiriman as $pengiriman) {
                        if (!in_array($pengiriman->status_verifikasi, ['Verified', 'Sampai_Tujuan'])) {
                            $allPengirimanSampai = false;
                            break 2;
                        }
                    }
                }
            }
        }
        
        $canVerify = $hasValidPengiriman && $allPengirimanSampai && !in_array($proyek->status, ['Selesai', 'Gagal']);
    @endphp
    
    @if($canVerify)
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
    @elseif($proyek->status === 'Selesai')
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-center mb-4">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <h3 class="text-lg font-semibold text-green-800">Proyek Telah Diverifikasi SELESAI</h3>
        </div>
        @if($proyek->catatan)
        <p class="text-green-700 mb-2"><strong>Catatan:</strong> {{ $proyek->catatan }}</p>
        @endif
        <p class="text-green-700 text-sm">
            Status proyek: <strong>{{ $proyek->status }}</strong>
        </p>
    </div>
    @elseif($proyek->status === 'Gagal')
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-center mb-4">
            <i class="fas fa-times-circle text-red-500 mr-3"></i>
            <h3 class="text-lg font-semibold text-red-800">Proyek Telah Diverifikasi GAGAL</h3>
        </div>
        @if($proyek->catatan)
        <p class="text-red-700 mb-2"><strong>Catatan:</strong> {{ $proyek->catatan }}</p>
        @endif
        <p class="text-red-700 text-sm">
            Status proyek: <strong>{{ $proyek->status }}</strong>
        </p>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
            <h3 class="text-lg font-semibold text-yellow-800">Proyek Belum Siap untuk Diverifikasi</h3>
        </div>
        <div class="text-yellow-700 text-sm space-y-1">
            @if(!$hasValidPengiriman)
            <p>• Belum ada pengiriman yang valid untuk proyek ini</p>
            @elseif(!$allPengirimanSampai)
            <p>• Masih ada pengiriman yang belum selesai atau terverifikasi</p>
            @endif
            @if($proyek->penagihanDinas->isEmpty() || $proyek->penagihanDinas->first()->status_pembayaran !== 'lunas')
            <p>• Pembayaran dinas belum lunas</p>
            @endif
        </div>
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
