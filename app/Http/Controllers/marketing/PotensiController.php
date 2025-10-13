<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\User;
use App\Models\Vendor;
use App\Models\ProyekBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PotensiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter
        $tahunFilter = $request->get('tahun');
        $adminMarketingFilter = $request->get('admin_marketing');
        $statusFilter = $request->get('status');
        $searchFilter = $request->get('search');

        // Query untuk mengambil proyek yang memiliki potensi = 'ya' dengan relasi yang sama seperti ProyekController
        $query = Proyek::with(['adminMarketing', 'adminPurchasing', 'wilayah', 'proyekBarang'])
            ->where('potensi', 'ya');

        // Filter berdasarkan tahun
        if ($tahunFilter) {
            $query->whereYear('tanggal', $tahunFilter);
        }

        // Filter berdasarkan admin marketing
        if ($adminMarketingFilter) {
            $query->where('id_admin_marketing', $adminMarketingFilter);
        }

        // Filter berdasarkan search
        if ($searchFilter) {
            $query->where(function($q) use ($searchFilter) {
                $q->where('nama_proyek', 'like', '%' . $searchFilter . '%')
                  ->orWhere('instansi', 'like', '%' . $searchFilter . '%')
                  ->orWhere('kab_kota', 'like', '%' . $searchFilter . '%');
            });
        }

        $proyekData = $query->orderBy('tanggal', 'desc')->get();

        // Transform data untuk view dengan struktur yang sama seperti ProyekController
        $potensiData = $proyekData->map(function ($proyek, $index) {
            // Get latest penawaran for this project
            $latestPenawaran = \App\Models\Penawaran::with('details')->where('id_proyek', $proyek->id_proyek)
                                      ->orderBy('id_penawaran', 'desc')
                                      ->first();

            // Generate vendor dummy berdasarkan index (for compatibility)
            $vendors = [
                ['id' => 'VND001', 'nama' => 'PT. Teknologi Maju', 'jenis' => 'Perusahaan'],
                ['id' => 'VND002', 'nama' => 'CV. Mandiri Sejahtera', 'jenis' => 'CV'],
                ['id' => 'VND003', 'nama' => 'PT. Global Industri', 'jenis' => 'Perusahaan'],
                ['id' => 'VND004', 'nama' => 'CV. Sukses Bersama', 'jenis' => 'CV'],
                ['id' => 'VND005', 'nama' => 'PT. Solusi Digital', 'jenis' => 'Perusahaan']
            ];

            $vendorIndex = $index % count($vendors);
            $vendor = $vendors[$vendorIndex];

            // Prioritas daftar barang: proyekBarang -> penawaran detail -> fallback proyek langsung
            $daftarBarang = [];
            $totalNilaiProyek = 0;

            // Prioritas 1: Dari proyek_barang (multiple barang per permintaan klien)
            if ($proyek->proyekBarang && $proyek->proyekBarang->count() > 0) {
                foreach ($proyek->proyekBarang as $barang) {
                    $hargaTotal = $barang->harga_total ?? ($barang->harga_satuan * $barang->jumlah);
                    $totalNilaiProyek += $hargaTotal;

                    $daftarBarang[] = [
                        'nama_barang' => $barang->nama_barang ?? $barang->spesifikasi,
                        'jumlah' => $barang->jumlah,
                        'satuan' => $barang->satuan,
                        'spesifikasi' => $barang->spesifikasi,
                        'harga_satuan' => $barang->harga_satuan,
                        'harga_total' => $hargaTotal
                    ];
                }
            }
            // Prioritas 2: Dari penawaran detail jika ada
            elseif ($latestPenawaran && $latestPenawaran->details && $latestPenawaran->details->count() > 0) {
                foreach ($latestPenawaran->details as $detail) {
                    $totalNilaiProyek += $detail->harga_total;

                    $daftarBarang[] = [
                        'nama_barang' => $detail->nama_barang,
                        'jumlah' => $detail->jumlah,
                        'satuan' => $detail->satuan,
                        'spesifikasi' => $detail->spesifikasi,
                        'harga_satuan' => $detail->harga_satuan,
                        'harga_total' => $detail->harga_total
                    ];
                }
            }
            // Prioritas 3: Fallback ke data kosong
            else {
                $daftarBarang[] = [
                    'nama_barang' => 'Belum ada data barang',
                    'jumlah' => 0,
                    'satuan' => '-',
                    'spesifikasi' => '-',
                    'harga_satuan' => 0,
                    'harga_total' => 0
                ];
            }

            return [
                'id' => $proyek->id_proyek,
                'kode' => $proyek->kode_proyek ?: 'PRJ-' . str_pad($proyek->id_proyek, 5, '0', STR_PAD_LEFT),
                'kode_proyek' => $proyek->kode_proyek ?? ('PNW-' . $proyek->tanggal->format('Ymd') . '-' . str_pad($proyek->id_proyek, 6, '0', STR_PAD_LEFT)),
                'nama_proyek' => $proyek->kode_proyek ?: 'PRJ-' . str_pad($proyek->id_proyek, 5, '0', STR_PAD_LEFT), // Gunakan kode_proyek sebagai nama_proyek
                'instansi' => $proyek->instansi,
                'kabupaten' => $proyek->kab_kota,
                'kabupaten_kota' => $proyek->kab_kota,
                'wilayah' => $proyek->wilayah ? $proyek->wilayah->nama_lengkap : $proyek->kab_kota,
                'provinsi' => $proyek->wilayah ? $proyek->wilayah->provinsi : '-',
                'jenis_pengadaan' => $proyek->jenis_pengadaan,
                'tanggal' => $proyek->tanggal->format('Y-m-d'),
                'deadline' => $proyek->deadline ? $proyek->deadline->format('Y-m-d') : null,
                'nilai_proyek' => $totalNilaiProyek > 0 ? $totalNilaiProyek : ($latestPenawaran ? $latestPenawaran->total_penawaran : ($proyek->harga_total ?? 0)),
                'admin_marketing' => $proyek->adminMarketing ? $proyek->adminMarketing->nama : '-',
                'admin_purchasing' => $proyek->adminPurchasing ? $proyek->adminPurchasing->nama : '-',
                'id_admin_marketing' => $proyek->id_admin_marketing,
                'id_admin_purchasing' => $proyek->id_admin_purchasing,
                'status' => $this->mapStatusToPotensi($latestPenawaran ? $latestPenawaran->status : 'menunggu'),
                'status_penawaran' => $latestPenawaran ? $latestPenawaran->status : 'Menunggu',
                'tahun' => $proyek->tanggal->year,
                'total_nilai' => $totalNilaiProyek > 0 ? $totalNilaiProyek : ($latestPenawaran ? $latestPenawaran->total_penawaran : ($proyek->harga_total ?? 0)),
                'catatan' => $proyek->catatan,
                'potensi' => $proyek->potensi ?? 'ya', // Always 'ya' for potensi page
                'tahun_potensi' => $proyek->tahun_potensi ?? Carbon::now()->year,
                'tanggal_assign' => $proyek->created_at->format('d M Y'),
                'daftar_barang' => $daftarBarang,
                'penawaran' => $latestPenawaran ? [
                    'id' => $latestPenawaran->id_penawaran,
                    'status' => $latestPenawaran->status
                ] : null,
                // Add vendor data for compatibility
                'vendor_id' => $vendor['id'],
                'vendor_nama' => $vendor['nama'],
                'vendor_jenis' => $vendor['jenis']
            ];
        });

        // Filter berdasarkan status setelah transform (karena status di-mapping)
        if ($statusFilter) {
            $potensiData = $potensiData->filter(function($item) use ($statusFilter) {
                return $item['status'] === $statusFilter;
            });
        }

        $potensiData = $potensiData->values()->toArray();

        // Hitung statistik berdasarkan status penawaran
        $totalPotensi = count($potensiData);
        
        // Pending = status penawaran "Menunggu"
        $pendingCount = collect($potensiData)->filter(function($item) {
            return isset($item['status_penawaran']) && 
                   strtolower($item['status_penawaran']) === 'menunggu';
        })->count();
        
        // Sukses = status penawaran "ACC" atau "Sukses"
        $suksesCount = collect($potensiData)->filter(function($item) {
            $statusPenawaran = strtolower($item['status_penawaran'] ?? '');
            return $statusPenawaran === 'acc' || $statusPenawaran === 'sukses';
        })->count();
        
        // Total Nilai = sum semua nilai proyek
        $totalNilai = collect($potensiData)->sum('nilai_proyek');

        // Ambil daftar admin marketing untuk filter
        $adminMarketingList = User::whereIn('role', ['superadmin', 'admin_marketing'])
            ->select('id_user', 'nama')
            ->orderBy('nama')
            ->get();

        // Ambil daftar tahun yang tersedia
        $tahunList = Proyek::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('pages.marketing.potensi', compact(
            'potensiData',
            'totalPotensi',
            'pendingCount',
            'suksesCount',
            'totalNilai',
            'adminMarketingList',
            'tahunList',
            'tahunFilter',
            'adminMarketingFilter',
            'statusFilter',
            'searchFilter'
        ));
    }

    public function store(Request $request)
    {
        // Debug: Log data yang diterima
        \Illuminate\Support\Facades\Log::info('Data potensi yang diterima:', $request->all());

        // Parse daftar_barang jika berupa JSON string
        $daftarBarang = null;
        if ($request->has('daftar_barang') && is_string($request->daftar_barang)) {
            try {
                $daftarBarang = json_decode($request->daftar_barang, true);
                \Illuminate\Support\Facades\Log::info('Parsed daftar_barang:', $daftarBarang);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error parsing daftar_barang JSON:', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Format data barang tidak valid'
                ], 400);
            }
        } else {
            $daftarBarang = $request->daftar_barang;
        }

        $request->validate([
            'tanggal' => 'required|date',
            'kab_kota' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'jenis_pengadaan' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'id_admin_marketing' => 'required|exists:users,id_user',
            'id_admin_purchasing' => 'required|exists:users,id_user',
            'catatan' => 'nullable|string',
            'tahun_potensi' => 'nullable|integer|min:2020|max:2030',
            // Support single atau multiple barang
            'nama_barang' => 'required_without:daftar_barang|string|max:255',
            'jumlah' => 'required_without:daftar_barang|integer|min:1',
            'satuan' => 'required_without:daftar_barang|string|max:50',
            'spesifikasi' => 'required_without:daftar_barang|string',
            'harga_satuan' => 'nullable|numeric|min:0'
        ]);

        // Validasi daftar_barang secara manual karena sudah di-parse
        if ($daftarBarang && is_array($daftarBarang)) {
            foreach ($daftarBarang as $index => $barang) {
                if (empty($barang['nama_barang']) || empty($barang['jumlah']) || empty($barang['satuan'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Data barang ke-" . ($index + 1) . " tidak lengkap"
                    ], 400);
                }
            }
        }

        try {
            DB::beginTransaction();

            // Ambil nama proyek dari barang pertama
            $namaProyek = $daftarBarang ? $daftarBarang[0]['nama_barang'] : $request->nama_barang;

            $proyek = Proyek::create([
                'tanggal' => $request->tanggal,
                'kab_kota' => $request->kab_kota,
                'instansi' => $request->instansi,
                'jenis_pengadaan' => $request->jenis_pengadaan,
                'deadline' => $request->deadline,
                'id_admin_marketing' => $request->id_admin_marketing,
                'id_admin_purchasing' => $request->id_admin_purchasing,
                'catatan' => $request->catatan,
                'potensi' => 'ya', // Set potensi ke 'ya' karena ini halaman potensi
                'tahun_potensi' => $request->tahun_potensi,
                'status' => 'Menunggu'
            ]);

            // Jika ada daftar barang multiple, simpan ke tabel proyek_barang
            if ($daftarBarang && is_array($daftarBarang)) {
                \Illuminate\Support\Facades\Log::info('Menyimpan multiple barang:', ['jumlah' => count($daftarBarang), 'data' => $daftarBarang]);

                foreach ($daftarBarang as $index => $barang) {
                    $harga_total = isset($barang['harga_satuan']) && $barang['harga_satuan'] ? $barang['harga_satuan'] * $barang['jumlah'] : null;

                    $proyekBarang = $proyek->proyekBarang()->create([
                        'nama_barang' => $barang['nama_barang'],
                        'jumlah' => $barang['jumlah'],
                        'satuan' => $barang['satuan'],
                        'spesifikasi' => $barang['spesifikasi'],
                        'harga_satuan' => $barang['harga_satuan'] ?? null,
                        'harga_total' => $harga_total
                    ]);

                    \Illuminate\Support\Facades\Log::info('Barang disimpan:', ['index' => $index + 1, 'id' => $proyekBarang->id_proyek_barang, 'nama' => $barang['nama_barang']]);
                }
            }
            // Jika single barang, simpan juga ke proyek_barang untuk konsistensi
            else {
                $harga_total = $request->harga_satuan ? $request->harga_satuan * $request->jumlah : null;

                $proyek->proyekBarang()->create([
                    'nama_barang' => $request->nama_barang,
                    'jumlah' => $request->jumlah,
                    'satuan' => $request->satuan,
                    'spesifikasi' => $request->spesifikasi,
                    'harga_satuan' => $request->harga_satuan,
                    'harga_total' => $harga_total
                ]);
            }

            // Handle file uploads
            $this->handleFileUploads($request, $proyek);

            // Refresh model untuk mendapatkan kode_proyek yang sudah di-generate
            $proyek->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Potensi proyek berhasil ditambahkan dengan kode: ' . $proyek->kode_proyek,
                'data' => $proyek
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('Error creating potensi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'proyekBarang'])
                ->where('potensi', 'ya')
                ->findOrFail($id);

            // Get latest penawaran for status
            $latestPenawaran = \App\Models\Penawaran::where('id_proyek', $proyek->id_proyek)
                                      ->orderBy('id_penawaran', 'desc')
                                      ->first();

            $detailData = [
                'id' => $proyek->id_proyek,
                'kode' => $proyek->kode_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'instansi' => $proyek->instansi,
                'kabupaten' => $proyek->kab_kota,
                'jenis_pengadaan' => $proyek->jenis_pengadaan,
                'tanggal' => $proyek->tanggal,
                'deadline' => $proyek->deadline,
                'admin_marketing' => $proyek->adminMarketing ? $proyek->adminMarketing->nama : '-',
                'admin_purchasing' => $proyek->adminPurchasing ? $proyek->adminPurchasing->nama : '-',
                'status' => $this->mapStatusToPotensi($latestPenawaran ? $latestPenawaran->status : 'menunggu'),
                'status_penawaran' => $latestPenawaran ? $latestPenawaran->status : 'Menunggu',
                'tahun_potensi' => $proyek->tahun_potensi,
                'catatan' => $proyek->catatan,
                'total_nilai' => $proyek->harga_total,
                'daftar_barang' => $proyek->proyekBarang->map(function($barang) {
                    return [
                        'spesifikasi' => $barang->spesifikasi,
                        'jumlah' => $barang->jumlah,
                        'satuan' => $barang->satuan,
                        'harga_satuan' => $barang->harga_satuan
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'data' => $detailData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $proyek = Proyek::where('potensi', 'ya')->findOrFail($id);

            // Debug: Log data yang diterima
            \Illuminate\Support\Facades\Log::info('Data update potensi yang diterima:', $request->all());
            \Illuminate\Support\Facades\Log::info('Admin purchasing value: ' . $request->admin_purchasing);
            \Illuminate\Support\Facades\Log::info('Tahun potensi value: ' . $request->tahun_potensi);

            // Validasi input sesuai dengan ProyekController
            $request->validate([
                'tanggal' => 'required|date',
                'kab_kota' => 'required|string|max:255',
                'instansi' => 'required|string|max:255',
                'jenis_pengadaan' => 'nullable|string|max:255',
                'admin_purchasing' => 'nullable|exists:users,id_user',
                'catatan' => 'nullable|string',
                'tahun_potensi' => 'nullable|integer|min:2020|max:2030',
                'nama_barang' => 'nullable|array',
                'spesifikasi' => 'nullable|array',
                'jumlah' => 'nullable|array',
                'satuan' => 'nullable|array',
                'harga_satuan' => 'nullable|array'
            ]);

            // Update data proyek sesuai dengan struktur ProyekController
            $updateData = [
                'tanggal' => $request->tanggal,
                'kab_kota' => $request->kab_kota,
                'instansi' => $request->instansi,
                'jenis_pengadaan' => $request->jenis_pengadaan,
                'id_admin_purchasing' => $request->admin_purchasing, // Fix mapping
                'catatan' => $request->catatan,
                'potensi' => 'ya', // Always 'ya' for potensi page
                'tahun_potensi' => $request->tahun_potensi ?? null // Ensure null if empty
            ];

            \Illuminate\Support\Facades\Log::info('Update data akan disimpan:', $updateData);

            $proyek->update($updateData);

            // Update data barang - hapus semua barang lama
            ProyekBarang::where('id_proyek', $proyek->id_proyek)->delete();

            // Tambah barang baru jika ada
            if ($request->has('nama_barang') && is_array($request->nama_barang)) {
                foreach ($request->nama_barang as $index => $namaBarang) {
                    if (!empty($namaBarang)) {
                        $jumlah = (int)($request->jumlah[$index] ?? 0);
                        $hargaSatuan = (int)($request->harga_satuan[$index] ?? 0);
                        $hargaTotal = $jumlah * $hargaSatuan;

                        ProyekBarang::create([
                            'id_proyek' => $proyek->id_proyek,
                            'nama_barang' => $namaBarang,
                            'spesifikasi' => $request->spesifikasi[$index] ?? '',
                            'jumlah' => $jumlah,
                            'satuan' => $request->satuan[$index] ?? '',
                            'harga_satuan' => $hargaSatuan,
                            'harga_total' => $hargaTotal
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Potensi proyek berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $proyek = Proyek::where('potensi', 'ya')->findOrFail($id);

            // Validasi alasan hapus
            $request->validate([
                'alasan' => 'required|string'
            ]);

            // Hapus file terkait jika ada
            $this->deleteProjectFiles($proyek);

            // Hapus data barang terkait
            ProyekBarang::where('id_proyek', $proyek->id_proyek)->delete();

            // Hapus proyek
            $proyek->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Potensi proyek berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detail($id)
    {
        try {
            // Ambil data proyek dengan relasi yang diperlukan
            $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'penawaranAktif'])
                ->where('potensi', 'ya')
                ->findOrFail($id);

            // Get latest penawaran for status
            $latestPenawaran = \App\Models\Penawaran::where('id_proyek', $proyek->id_proyek)
                                      ->orderBy('id_penawaran', 'desc')
                                      ->first();

            // Generate vendor data (dummy data karena belum ada tabel vendor yang sesuai)
            $vendorData = [
                'id' => 'VND-' . str_pad($id, 4, '0', STR_PAD_LEFT),
                'nama' => 'PT ' . ucfirst(strtolower($proyek->instansi)) . ' Solutions',
                'jenis' => collect(['Korporasi', 'UMKM', 'Startup'])->random(),
                'status' => collect(['Aktif', 'Pending', 'Suspended'])->random(),
            ];

            // Format data untuk response
            $detailData = [
                'id' => $proyek->id_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'instansi' => $proyek->instansi,
                'kabupaten_kota' => $proyek->kab_kota,
                'jenis_pengadaan' => $proyek->jenis_pengadaan,
                'nilai_proyek' => 'Rp ' . number_format($proyek->harga_total ?? 0, 0, ',', '.'),
                'deadline' => $proyek->deadline ? Carbon::parse($proyek->deadline)->format('d M Y') : '-',
                'admin_marketing' => $proyek->adminMarketing ? $proyek->adminMarketing->nama : 'Tidak ada',
                'status' => $this->mapStatusToPotensi($latestPenawaran ? $latestPenawaran->status : 'menunggu'),
                'status_penawaran' => $latestPenawaran ? $latestPenawaran->status : 'Menunggu',
                'tanggal_assign' => Carbon::parse($proyek->tanggal)->format('d M Y'),
                'catatan' => $proyek->catatan ?? 'Tidak ada catatan khusus',
                'vendor' => $vendorData,
                'timeline' => [
                    [
                        'tanggal' => Carbon::parse($proyek->tanggal)->format('d M Y'),
                        'aktivitas' => 'Proyek dibuat',
                        'detail' => 'Proyek ' . $proyek->nama_proyek . ' telah dibuat dan ditugaskan ke admin marketing'
                    ],
                    [
                        'tanggal' => Carbon::parse($proyek->tanggal)->addDays(1)->format('d M Y'),
                        'aktivitas' => 'Vendor ditugaskan',
                        'detail' => 'Vendor ' . $vendorData['nama'] . ' telah ditugaskan untuk menangani proyek ini'
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $detailData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    private function handleFileUploads(Request $request, $proyek)
    {
        $fileFields = ['surat_penawaran', 'surat_pesanan'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = $proyek->kode_proyek . '_' . $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('proyek_documents', $fileName, 'public');

                // Update database field jika ada
                if (in_array($field, ['surat_penawaran', 'surat_pesanan'])) {
                    $proyek->{$field} = $fileName;
                    $proyek->save();
                }
            }
        }
    }

    private function deleteProjectFiles($proyek)
    {
        $fileFields = ['surat_penawaran', 'surat_kontrak'];

        foreach ($fileFields as $field) {
            if ($proyek->{$field} && Storage::disk('public')->exists('proyek_documents/' . $proyek->{$field})) {
                Storage::disk('public')->delete('proyek_documents/' . $proyek->{$field});
            }
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $proyek = Proyek::where('potensi', 'ya')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu,penawaran,pembayaran,pengiriman,selesai,gagal'
        ]);

        $proyek->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status proyek berhasil diperbarui',
            'data' => $proyek
        ]);
    }

    public function getUsersForSelect()
    {
        $users = User::select('id_user', 'nama', 'role')
            ->whereIn('role', ['superadmin', 'admin_marketing', 'admin_purchasing', 'admin_keuangan'])
            ->orderBy('nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function getWilayahForSelect()
    {
        $wilayahData = \App\Models\Wilayah::with('adminMarketing:id_user,nama')->get();
        return response()->json([
            'success' => true,
            'data' => $wilayahData
        ]);
    }

    public function getNextKodeProyek()
    {
        try {
            $nextKode = Proyek::generateNextKodeProyek();
            return response()->json([
                'success' => true,
                'kode' => $nextKode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan kode proyek: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCurrentUser()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id_user,
                    'nama' => $user->nama,
                    'role' => $user->role
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan data user: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getPotensiValue($harga_total)
    {
        if (!$harga_total) {
            return 'rendah';
        }

        if ($harga_total >= 1000000000) { // 1 miliar
            return 'tinggi';
        } elseif ($harga_total >= 100000000) { // 100 juta
            return 'sedang';
        } else {
            return 'rendah';
        }
    }

    private function mapStatusToPotensi($statusPenawaran)
    {
        // Map berdasarkan status penawaran, bukan status proyek
        $status = strtolower($statusPenawaran ?? 'menunggu');
        
        switch ($status) {
            case 'acc':
            case 'sukses':
                return 'sukses';
            case 'menunggu':
            case 'pending':
                return 'pending';
            default:
                return 'pending';
        }
    }
}
