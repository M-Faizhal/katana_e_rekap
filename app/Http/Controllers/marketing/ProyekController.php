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
use Carbon\Carbon;
use Exception;

class ProyekController extends Controller
{
    public function index()
    {
        // Ambil semua data proyek dengan relasi admin marketing, purchasing, wilayah, dan proyek barang
        $proyekData = Proyek::with(['adminMarketing', 'adminPurchasing', 'wilayah', 'proyekBarang'])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Transform data untuk view
        $proyekData = $proyekData->map(function ($proyek) {
            // Get latest penawaran for this project
            $latestPenawaran = Penawaran::with('details')->where('id_proyek', $proyek->id_proyek)
                                      ->orderBy('id_penawaran', 'desc')
                                      ->first();
            // Prioritas daftar barang: proyekBarang -> penawaran detail -> fallback proyek langsung
            $daftarBarang = [];

            // Prioritas 1: Dari proyek_barang (multiple barang per permintaan klien)
            if ($proyek->proyekBarang && $proyek->proyekBarang->count() > 0) {
                foreach ($proyek->proyekBarang as $barang) {
                    $daftarBarang[] = [
                        'nama' => $barang->nama_barang,
                        'nama_barang' => $barang->nama_barang, // Tambahkan untuk compatibility
                        'jumlah' => $barang->jumlah,
                        'qty' => $barang->jumlah, // Tambahkan untuk compatibility
                        'satuan' => $barang->satuan,
                        'spesifikasi' => $barang->spesifikasi,
                        'harga_satuan' => $barang->harga_satuan ?? 0,
                        'harga_total' => $barang->harga_total ?? 0
                    ];
                }
            }
            // Prioritas 2: Dari penawaran detail jika ada
            elseif ($latestPenawaran && $latestPenawaran->details && $latestPenawaran->details->count() > 0) {
                foreach ($latestPenawaran->details as $detail) {
                    $daftarBarang[] = [
                        'nama' => $detail->nama_barang,
                        'nama_barang' => $detail->nama_barang, // Tambahkan untuk compatibility
                        'jumlah' => $detail->qty,
                        'qty' => $detail->qty, // Tambahkan untuk compatibility
                        'satuan' => 'Unit', // Default satuan
                        'spesifikasi' => $detail->spesifikasi,
                        'harga_satuan' => $detail->harga_satuan,
                        'harga_total' => $detail->subtotal
                    ];
                }
            }
            // Prioritas 3: Fallback ke data proyek langsung (tidak ada lagi karena kolom sudah dihapus)
            else {
                $daftarBarang[] = [
                    'nama' => 'Data barang tidak tersedia',
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
                'nama_proyek' => $proyek->kode_proyek ?: 'PRJ-' . str_pad($proyek->id_proyek, 5, '0', STR_PAD_LEFT), // Gunakan kode_proyek sebagai nama_proyek
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
                'total_nilai' => $latestPenawaran ? $latestPenawaran->total_penawaran : ($proyek->harga_total ?? 0),
                'catatan' => $proyek->catatan,
                'potensi' => $proyek->potensi ?? 'tidak',
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

        // Hitung total proyek
        $totalProyek = count($proyekData);

        return view('pages.marketing.proyek', compact('proyekData', 'totalProyek'));
    }

    public function store(Request $request)
    {
        // Role-based access control: Allow superadmin and admin_marketing
        $user = Auth::user();
        if (!in_array($user->role, ['superadmin', 'admin_marketing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak memiliki akses untuk membuat proyek. Hanya superadmin dan admin marketing yang dapat melakukan aksi ini.'
            ], 403);
        }

        // Debug: Log data yang diterima
        Log::info('Data proyek yang diterima:', $request->all());

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
            'potensi' => 'nullable|in:ya,tidak',
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
            'potensi' => $request->potensi ?? 'tidak',
            'tahun_potensi' => $request->tahun_potensi,
            'status' => 'Menunggu'
        ]);

        // Jika ada daftar barang multiple, simpan ke tabel proyek_barang
        if ($daftarBarang && is_array($daftarBarang)) {
            Log::info('Menyimpan multiple barang:', ['jumlah' => count($daftarBarang), 'data' => $daftarBarang]);

            foreach ($daftarBarang as $index => $barang) {
                $harga_total = isset($barang['harga_satuan']) && $barang['harga_satuan'] ? $barang['harga_satuan'] * $barang['jumlah'] : null;

                $proyekBarang = $proyek->proyekBarang()->create([
                    'nama_barang' => $barang['nama_barang'],
                    'jumlah' => $barang['jumlah'],
                    'satuan' => $barang['satuan'],
                    'spesifikasi' => $barang['spesifikasi'] ?? 'Spesifikasi standar',
                    'harga_satuan' => $barang['harga_satuan'] ?? null,
                    'harga_total' => $harga_total
                ]);

                Log::info('Barang disimpan:', ['index' => $index + 1, 'id' => $proyekBarang->id_proyek_barang, 'nama' => $barang['nama_barang']]);
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

        // Refresh model untuk mendapatkan kode_proyek yang sudah di-generate
        $proyek->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Proyek berhasil ditambahkan dengan kode: ' . $proyek->kode_proyek,
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
                'message' => 'Tidak memiliki akses untuk mengupdate proyek. Hanya superadmin dan admin marketing yang dapat melakukan aksi ini.'
            ], 403);
        }

        $proyek = Proyek::findOrFail($id);

        // Debug: Log data yang diterima untuk update
        Log::info('Data update proyek yang diterima:', $request->all());

        $request->validate([
            'tanggal' => 'required|date',
            'kab_kota' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'jenis_pengadaan' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'id_admin_marketing' => 'required|exists:users,id_user',
            'id_admin_purchasing' => 'required|exists:users,id_user',
            'catatan' => 'nullable|string',
            'potensi' => 'nullable|in:ya,tidak',
            'tahun_potensi' => 'nullable|integer|min:2020|max:2030',
            // Support single atau multiple barang (sama seperti store)
            'nama_barang' => 'required_without:daftar_barang|string|max:255',
            'jumlah' => 'required_without:daftar_barang|integer|min:1',
            'satuan' => 'required_without:daftar_barang|string|max:50',
            'spesifikasi' => 'required_without:daftar_barang|string',
            'harga_satuan' => 'nullable|numeric|min:0',
            // Array barang untuk multiple items
            'daftar_barang' => 'nullable|array',
            'daftar_barang.*.nama_barang' => 'required|string|max:255',
            'daftar_barang.*.jumlah' => 'required|integer|min:1',
            'daftar_barang.*.satuan' => 'required|string|max:50',
            'daftar_barang.*.spesifikasi' => 'required|string',
            'daftar_barang.*.harga_satuan' => 'nullable|numeric|min:0'
        ]);

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
                'potensi' => $request->potensi ?? 'tidak',
                'tahun_potensi' => $request->tahun_potensi
            ]);

            // Hapus semua barang lama
            $proyek->proyekBarang()->delete();
            Log::info('Barang lama proyek dihapus untuk proyek ID: ' . $proyek->id_proyek);

            // Jika ada daftar barang multiple, simpan ke tabel proyek_barang
            if ($request->daftar_barang && is_array($request->daftar_barang)) {
                Log::info('Mengupdate multiple barang:', ['jumlah' => count($request->daftar_barang), 'data' => $request->daftar_barang]);

                foreach ($request->daftar_barang as $index => $barang) {
                    $harga_total = isset($barang['harga_satuan']) && $barang['harga_satuan'] ? $barang['harga_satuan'] * $barang['jumlah'] : null;

                    $proyekBarang = $proyek->proyekBarang()->create([
                        'nama_barang' => $barang['nama_barang'],
                        'jumlah' => $barang['jumlah'],
                        'satuan' => $barang['satuan'],
                        'spesifikasi' => $barang['spesifikasi'] ?? 'Spesifikasi standar',
                        'harga_satuan' => $barang['harga_satuan'] ?? null,
                        'harga_total' => $harga_total
                    ]);

                    Log::info('Barang diupdate:', ['index' => $index + 1, 'id' => $proyekBarang->id_proyek_barang, 'nama' => $barang['nama_barang']]);
                }
            }
            // Jika single barang, simpan juga ke proyek_barang untuk konsistensi
            else {
                $harga_total = $request->harga_satuan ? $request->harga_satuan * $request->jumlah : null;

                $proyekBarang = $proyek->proyekBarang()->create([
                    'nama_barang' => $request->nama_barang,
                    'jumlah' => $request->jumlah,
                    'satuan' => $request->satuan,
                    'spesifikasi' => $request->spesifikasi,
                    'harga_satuan' => $request->harga_satuan,
                    'harga_total' => $harga_total
                ]);

                Log::info('Single barang diupdate:', ['id' => $proyekBarang->id_proyek_barang, 'nama' => $request->nama_barang]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil diperbarui',
                'data' => $proyek
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating proyek: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate proyek: ' . $e->getMessage()
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
                'message' => 'Tidak memiliki akses untuk menghapus proyek. Hanya superadmin dan admin marketing yang dapat melakukan aksi ini.'
            ], 403);
        }

        $proyek = Proyek::findOrFail($id);
        $proyek->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proyek berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        // Role-based access control: Allow superadmin and admin_marketing
        $user = Auth::user();
        if (!in_array($user->role, ['superadmin', 'admin_marketing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak memiliki akses untuk mengubah status proyek. Hanya superadmin dan admin marketing yang dapat melakukan aksi ini.'
            ], 403);
        }

        $proyek = Proyek::findOrFail($id);

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
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id_user,
                    'nama' => $user->nama,
                    'email' => $user->email,
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
}
