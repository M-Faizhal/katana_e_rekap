<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\ProyekBarang;
use App\Models\Penawaran;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class PotensiController extends Controller
{
    public function index()
    {
        // Ambil data proyek yang ditandai sebagai potensi dengan status menunggu
        $proyekData = Proyek::with(['adminMarketing', 'adminPurchasing', 'wilayah', 'proyekBarang'])
            ->where('potensi', 'ya')
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform data untuk view
        $proyekData = $proyekData->map(function ($proyek) {
            // Get latest penawaran for this project
            $latestPenawaran = Penawaran::with('details')->where('id_proyek', $proyek->id_proyek)
                                    ->orderBy('id_penawaran', 'desc')
                                    ->first();

            // Hitung total nilai dari proyek_barang
            $totalNilaiProyekBarang = 0;
            $daftarBarang = [];

            // Prioritas 1: Dari proyek_barang (multiple barang per permintaan klien)
            if ($proyek->proyekBarang && $proyek->proyekBarang->count() > 0) {
                foreach ($proyek->proyekBarang as $barang) {
                    $hargaTotal = $barang->harga_total ?? ($barang->harga_satuan ? $barang->harga_satuan * $barang->jumlah : 0);
                    $totalNilaiProyekBarang += $hargaTotal;

                    $daftarBarang[] = [
                        'id' => $barang->id_proyek_barang,
                        'nama_barang' => $barang->nama_barang,
                        'jumlah' => $barang->jumlah,
                        'satuan' => $barang->satuan,
                        'spesifikasi' => $barang->spesifikasi,
                        'harga_satuan' => $barang->harga_satuan,
                        'harga_total' => $hargaTotal,
                        'spesifikasi_files' => $barang->spesifikasi_files ?? []
                    ];
                }
            }
            // Prioritas 2: Dari penawaran detail jika ada
            elseif ($latestPenawaran && $latestPenawaran->details && $latestPenawaran->details->count() > 0) {
                foreach ($latestPenawaran->details as $detail) {
                    $hargaTotal = $detail->harga_total ?? 0;
                    $totalNilaiProyekBarang += $hargaTotal;

                    $daftarBarang[] = [
                        'nama_barang' => $detail->nama_produk ?? '-',
                        'jumlah' => $detail->jumlah ?? 0,
                        'satuan' => $detail->satuan ?? '-',
                        'harga_satuan' => $detail->harga_satuan ?? 0,
                        'harga_total' => $hargaTotal
                    ];
                }
            }
            // Prioritas 3: Fallback
            else {
                $daftarBarang[] = [
                    'nama_barang' => '-',
                    'jumlah' => 0,
                    'satuan' => '-',
                    'harga_satuan' => 0,
                    'harga_total' => 0
                ];
            }

            return [
                'id' => $proyek->id_proyek,
                'kode' => $proyek->kode_proyek ?: 'PRJ-' . str_pad($proyek->id_proyek, 5, '0', STR_PAD_LEFT),
                'nama_proyek' => $proyek->kode_proyek ?: 'PRJ-' . str_pad($proyek->id_proyek, 5, '0', STR_PAD_LEFT),
                'instansi' => $proyek->instansi,
                'kabupaten' => $proyek->kab_kota,
                'wilayah' => $proyek->wilayah ? $proyek->wilayah->nama_lengkap : $proyek->kab_kota,
                'provinsi' => $proyek->wilayah ? $proyek->wilayah->provinsi : '-',
                'jenis_pengadaan' => $proyek->jenis_pengadaan,
                'tanggal' => $proyek->tanggal->format('Y-m-d'),
                'deadline' => $proyek->deadline ? $proyek->deadline->format('Y-m-d') : null,
                'admin_marketing' => $proyek->adminMarketing ? $proyek->adminMarketing->nama : '-',
                'admin_purchasing' => $proyek->adminPurchasing ? $proyek->adminPurchasing->nama : '-',
                'id_admin_marketing' => $proyek->id_admin_marketing,
                'id_admin_purchasing' => $proyek->id_admin_purchasing,
                'status' => strtolower($proyek->status),
                'total_nilai' => $totalNilaiProyekBarang,
                'catatan' => $proyek->catatan,
                'potensi' => $proyek->potensi ?? 'ya',
                'tahun_potensi' => $proyek->tahun_potensi ?? Carbon::now()->year,
                'daftar_barang' => $daftarBarang,
                'penawaran' => $latestPenawaran ? [
                    'no_penawaran' => $latestPenawaran->no_penawaran,
                    'tanggal_penawaran' => $latestPenawaran->tanggal_penawaran ? $latestPenawaran->tanggal_penawaran->format('Y-m-d') : null,
                    'total_penawaran' => $latestPenawaran->total_penawaran,
                    'surat_penawaran' => $latestPenawaran->surat_penawaran,
                    'surat_pesanan' => $latestPenawaran->surat_pesanan,
                    'status' => $latestPenawaran->status
                ] : null
            ];
        })->toArray();

        // Hitung total potensi
        $totalProyek = count($proyekData);

        return view('pages.marketing.potensi', compact('proyekData', 'totalProyek'));
    }

    public function store(Request $request)
    {
        // Role-based access control: Allow superadmin and admin_marketing
        $user = Auth::user();
        if (!in_array($user->role, ['superadmin', 'admin_marketing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak memiliki akses untuk membuat potensi. Hanya superadmin dan admin marketing yang dapat melakukan aksi ini.'
            ], 403);
        }

        // Debug: Log data yang diterima
        Log::info('Data potensi yang diterima:', $request->all());

        // Parse daftar_barang jika berupa JSON string
        $daftarBarang = null;
        if ($request->has('daftar_barang') && is_string($request->daftar_barang)) {
            try {
                $daftarBarang = json_decode($request->daftar_barang, true);
                Log::info('Parsed daftar_barang:', $daftarBarang);
            } catch (Exception $e) {
                Log::error('Error parsing daftar_barang JSON:', ['error' => $e->getMessage()]);
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
                        'message' => "Data barang #{$index} tidak lengkap"
                    ], 400);
                }
            }
        }

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
            'potensi' => 'ya', // Set sebagai potensi
            'tahun_potensi' => $request->tahun_potensi ?? Carbon::now()->year,
            'status' => 'Menunggu'
        ]);

        // Jika ada daftar barang multiple, simpan ke tabel proyek_barang
        if ($daftarBarang && is_array($daftarBarang)) {
            Log::info('Menyimpan multiple barang:', ['jumlah' => count($daftarBarang), 'data' => $daftarBarang]);

            foreach ($daftarBarang as $index => $barang) {
                $harga_total = isset($barang['harga_satuan']) && $barang['harga_satuan'] ? $barang['harga_satuan'] * $barang['jumlah'] : null;

                // Handle file upload untuk item ini
                $specFiles = [];
                if ($request->hasFile("barang.{$index}.files")) {
                    $specFiles = $this->handleSpecificationFiles($request->file("barang.{$index}.files"), $proyek->id_proyek, $index);
                }

                $proyekBarang = $proyek->proyekBarang()->create([
                    'nama_barang' => $barang['nama_barang'],
                    'jumlah' => $barang['jumlah'],
                    'satuan' => $barang['satuan'],
                    'spesifikasi' => $barang['spesifikasi'] ?? '',
                    'spesifikasi_files' => $specFiles,
                    'harga_satuan' => $barang['harga_satuan'] ?? null,
                    'harga_total' => $harga_total
                ]);

                Log::info('Barang disimpan:', ['index' => $index + 1, 'id' => $proyekBarang->id_proyek_barang, 'nama' => $barang['nama_barang'], 'files' => count($specFiles)]);
            }
        }
        // Jika single barang, simpan juga ke proyek_barang untuk konsistensi
        else {
            $harga_total = $request->harga_satuan ? $request->harga_satuan * $request->jumlah : null;

            // Handle file upload untuk single item
            $specFiles = [];
            if ($request->hasFile('barang.0.files')) {
                $files = $request->file('barang.0.files');
                $specFiles = $this->handleSpecificationFiles($files, $proyek->id_proyek, 0);
            }

            $proyek->proyekBarang()->create([
                'nama_barang' => $request->nama_barang,
                'jumlah' => $request->jumlah,
                'satuan' => $request->satuan,
                'spesifikasi' => $request->spesifikasi,
                'spesifikasi_files' => $specFiles,
                'harga_satuan' => $request->harga_satuan,
                'harga_total' => $harga_total
            ]);
        }

        // Refresh model untuk mendapatkan kode_proyek yang sudah di-generate
        $proyek->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Potensi berhasil ditambahkan dengan kode: ' . $proyek->kode_proyek,
            'data' => $proyek
        ]);
    }

    public function show($id)
    {
        $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'wilayah', 'proyekBarang'])
            ->where('potensi', 'ya')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $proyek
        ]);
    }

    public function detail($id)
    {
        $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'wilayah', 'proyekBarang'])
            ->where('potensi', 'ya')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $proyek
        ]);
    }

    public function update(Request $request, $id)
    {
        // Role-based access control: Allow superadmin and admin_marketing
        $user = Auth::user();
        if (!in_array($user->role, ['superadmin', 'admin_marketing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak memiliki akses untuk mengupdate potensi. Hanya superadmin dan admin marketing yang dapat melakukan aksi ini.'
            ], 403);
        }

        $proyek = Proyek::where('potensi', 'ya')->findOrFail($id);

        // Debug: Log semua data yang diterima
        Log::info('=== UPDATE POTENSI DEBUG ===');
        Log::info('Project ID: ' . $id);
        Log::info('Request method: ' . $request->method());
        Log::info('All request data: ', $request->all());
        Log::info('Files in request: ', $request->allFiles());

        // Parse daftar_barang jika berupa JSON string
        $daftarBarang = null;
        if ($request->has('daftar_barang') && is_string($request->daftar_barang)) {
            try {
                $daftarBarang = json_decode($request->daftar_barang, true);
                Log::info('Parsed daftar_barang for update:', $daftarBarang);
                $request->merge(['daftar_barang' => $daftarBarang]);
            } catch (Exception $e) {
                Log::error('Error parsing daftar_barang JSON for update:', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Format data barang tidak valid'
                ], 400);
            }
        } else {
            $daftarBarang = $request->daftar_barang;
        }

        try {
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

            // Validasi daftar_barang secara manual jika sudah di-parse
            if ($daftarBarang && is_array($daftarBarang)) {
                foreach ($daftarBarang as $index => $barang) {
                    if (empty($barang['nama_barang']) || empty($barang['jumlah']) || empty($barang['satuan'])) {
                        return response()->json([
                            'success' => false,
                            'message' => "Data barang #{$index} tidak lengkap"
                        ], 400);
                    }
                }
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating potensi:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', array_map(fn($errors) => implode(', ', $errors), $e->errors()))
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update data proyek
            $proyek->update([
                'tanggal' => $request->tanggal,
                'kab_kota' => $request->kab_kota,
                'instansi' => $request->instansi,
                'jenis_pengadaan' => $request->jenis_pengadaan,
                'deadline' => $request->deadline,
                'id_admin_marketing' => $request->id_admin_marketing,
                'id_admin_purchasing' => $request->id_admin_purchasing,
                'catatan' => $request->catatan,
                'potensi' => 'ya', // Pastikan tetap sebagai potensi
                'tahun_potensi' => $request->tahun_potensi ?? Carbon::now()->year
            ]);

            // Hapus semua barang lama
            $proyek->proyekBarang()->delete();
            Log::info('Barang lama potensi dihapus untuk proyek ID: ' . $proyek->id_proyek);

            // Jika ada daftar barang multiple, simpan ke tabel proyek_barang
            if ($daftarBarang && is_array($daftarBarang)) {
                Log::info('Menyimpan ulang multiple barang:', ['jumlah' => count($daftarBarang)]);

                foreach ($daftarBarang as $index => $barang) {
                    $harga_total = isset($barang['harga_satuan']) && $barang['harga_satuan'] ? $barang['harga_satuan'] * $barang['jumlah'] : null;

                    $specFiles = [];
                    if ($request->hasFile("barang.{$index}.files")) {
                        $specFiles = $this->handleSpecificationFiles($request->file("barang.{$index}.files"), $proyek->id_proyek, $index);
                    }

                    $proyek->proyekBarang()->create([
                        'nama_barang' => $barang['nama_barang'],
                        'jumlah' => $barang['jumlah'],
                        'satuan' => $barang['satuan'],
                        'spesifikasi' => $barang['spesifikasi'] ?? '',
                        'spesifikasi_files' => $specFiles,
                        'harga_satuan' => $barang['harga_satuan'] ?? null,
                        'harga_total' => $harga_total
                    ]);
                }
            }
            // Jika single barang
            else {
                $harga_total = $request->harga_satuan ? $request->harga_satuan * $request->jumlah : null;

                $specFiles = [];
                if ($request->hasFile('barang.0.files')) {
                    $specFiles = $this->handleSpecificationFiles($request->file('barang.0.files'), $proyek->id_proyek, 0);
                }

                $proyek->proyekBarang()->create([
                    'nama_barang' => $request->nama_barang,
                    'jumlah' => $request->jumlah,
                    'satuan' => $request->satuan,
                    'spesifikasi' => $request->spesifikasi,
                    'spesifikasi_files' => $specFiles,
                    'harga_satuan' => $request->harga_satuan,
                    'harga_total' => $harga_total
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Potensi berhasil diupdate',
                'data' => $proyek
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating potensi: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate potensi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        // Role-based access control: Allow superadmin and admin_marketing
        $user = Auth::user();
        if (!in_array($user->role, ['superadmin', 'admin_marketing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak memiliki akses untuk menghapus potensi. Hanya superadmin dan admin marketing yang dapat melakukan aksi ini.'
            ], 403);
        }

        $proyek = Proyek::where('potensi', 'ya')->findOrFail($id);
        $proyek->delete();

        return response()->json([
            'success' => true,
            'message' => 'Potensi berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            Log::info('updateStatus potensi called', [
                'potensi_id' => $id,
                'status' => $request->status,
                'user_id' => Auth::id()
            ]);

            // Role-based access control: Allow superadmin and admin_marketing
            $user = Auth::user();
            if (!in_array($user->role, ['superadmin', 'admin_marketing'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses untuk mengubah status potensi'
                ], 403);
            }

            // Validate potensi exists
            $proyek = Proyek::where('potensi', 'ya')->find($id);
            if (!$proyek) {
                return response()->json([
                    'success' => false,
                    'message' => 'Potensi tidak ditemukan'
                ], 404);
            }

            // Validate request data
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:menunggu,penawaran,pembayaran,pengiriman,selesai,gagal'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid'
                ], 422);
            }

            // Update status
            $proyek->update([
                'status' => $request->status
            ]);

            Log::info('Status potensi berhasil diubah', [
                'potensi_id' => $id,
                'old_status' => $proyek->getOriginal('status'),
                'new_status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status potensi berhasil diubah',
                'data' => $proyek
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating potensi status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status potensi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatusGagal(Request $request, $id)
    {
        try {
            Log::info('updateStatusGagal called', [
                'potensi_id' => $id,
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            // Role-based access control: Allow superadmin and admin_marketing
            $user = Auth::user();
            if (!in_array($user->role, ['superadmin', 'admin_marketing'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses untuk mengubah status potensi. Hanya superadmin dan PIC marketing yang dapat melakukan aksi ini.'
                ], 403);
            }

            // Validate potensi exists
            $proyek = Proyek::where('potensi', 'ya')->find($id);
            if (!$proyek) {
                return response()->json([
                    'success' => false,
                    'message' => 'Potensi tidak ditemukan'
                ], 404);
            }

            // Validate request data
            $validator = Validator::make($request->all(), [
                'catatan' => 'required|string|min:5|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid: ' . $validator->errors()->first()
                ], 422);
            }

            // Update status and catatan
            $oldStatus = $proyek->status;
            $proyek->update([
                'status' => 'Gagal',
                'catatan' => $request->catatan
            ]);

            Log::info('Status potensi berhasil diubah menjadi Gagal', [
                'potensi_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => 'Gagal',
                'catatan' => $request->catatan,
                'user_id' => $user->id_user
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status potensi berhasil diubah menjadi Gagal',
                'data' => $proyek
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating potensi status to Gagal', [
                'potensi_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status potensi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUsersForSelect()
    {
        $currentUserId = Auth::id();

        $users = User::select('id_user', 'nama', 'role')
            ->whereIn('role', ['superadmin', 'admin_marketing', 'admin_purchasing', 'admin_keuangan'])
            ->orderBy('nama')
            ->get();

        // Add current user flag to each user
        $usersWithCurrentFlag = $users->map(function ($user) use ($currentUserId) {
            $user->is_current_user = $user->id_user == $currentUserId;
            return $user;
        });

        return response()->json([
            'success' => true,
            'data' => $usersWithCurrentFlag
        ]);
    }

    public function getWilayahForSelect()
    {
        $wilayahData = Wilayah::with('adminMarketing:id_user,nama')->get();
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
                ], 404);
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

    /**
     * Handle file upload untuk spesifikasi barang
     */
    private function handleSpecificationFiles($files, $proyekId, $itemIndex)
    {
        $fileData = [];

        if (!$files || !is_array($files)) {
            return $fileData;
        }

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                // Generate unique filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = 'proj_' . $proyekId . '_item_' . $itemIndex . '_' . time() . '_' . uniqid() . '.' . $extension;

                // Store file
                $path = $file->storeAs('public/specifications', $filename);

                // Get file size
                $fileSize = $file->getSize();

                // Store file info
                $fileData[] = [
                    'original_name' => $originalName,
                    'stored_name' => $filename,
                    'size' => $fileSize,
                    'mime_type' => $file->getMimeType(),
                    'uploaded_at' => now()->toDateTimeString()
                ];

                Log::info('File spesifikasi diupload:', [
                    'proyek_id' => $proyekId,
                    'item_index' => $itemIndex,
                    'filename' => $filename,
                    'original_name' => $originalName,
                    'size' => $fileSize
                ]);
            }
        }

        return $fileData;
    }

    /**
     * Download specification file
     */
    public function downloadFile($filename)
    {
        $filePath = storage_path('app/public/specifications/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Security check - pastikan filename valid
        if (!preg_match('/^proj_\d+_item_\d+_\d+_.+$/', $filename)) {
            abort(403, 'Akses ditolak');
        }

        return response()->download($filePath);
    }

    /**
     * Preview specification file (untuk PDF dan gambar)
     */
    public function previewFile($filename)
    {
        $filePath = storage_path('app/public/specifications/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Security check - pastikan filename valid
        if (!preg_match('/^proj_\d+_item_\d+_\d+_.+$/', $filename)) {
            abort(403, 'Akses ditolak');
        }

        $mimeType = mime_content_type($filePath);

        // Hanya allow preview untuk PDF dan gambar
        $allowedMimes = [
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            return response()->json(['error' => 'File tidak bisa di-preview'], 400);
        }

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
}
