<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProyekController extends Controller
{
    public function index()
    {
        // Ambil semua data proyek dengan relasi admin marketing, purchasing, dan wilayah
        $proyekData = Proyek::with(['adminMarketing', 'adminPurchasing', 'wilayah', 'penawaranAktif.details'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Transform data untuk view
        $proyekData = $proyekData->map(function ($proyek) {
            // Ambil daftar barang dari penawaran detail jika ada, jika tidak gunakan data proyek
            $daftarBarang = [];
            if ($proyek->penawaranAktif && $proyek->penawaranAktif->details) {
                foreach ($proyek->penawaranAktif->details as $detail) {
                    $daftarBarang[] = [
                        'nama' => $detail->nama_barang,
                        'jumlah' => $detail->qty,
                        'satuan' => 'Unit', // Default satuan
                        'spesifikasi' => $detail->spesifikasi,
                        'harga_satuan' => $detail->harga_satuan,
                        'harga_total' => $detail->subtotal
                    ];
                }
            } else {
                $daftarBarang[] = [
                    'nama' => $proyek->nama_barang,
                    'jumlah' => $proyek->jumlah,
                    'satuan' => $proyek->satuan,
                    'spesifikasi' => $proyek->spesifikasi,
                    'harga_satuan' => $proyek->harga_satuan ?? 0,
                    'harga_total' => $proyek->harga_total ?? 0
                ];
            }

            return [
                'id' => $proyek->id_proyek,
                'kode' => $proyek->kode_proyek ?: 'PRJ-' . str_pad($proyek->id_proyek, 5, '0', STR_PAD_LEFT),
                'nama_proyek' => $proyek->nama_barang,
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
                'total_nilai' => $proyek->penawaranAktif ? $proyek->penawaranAktif->total_penawaran : ($proyek->harga_total ?? 0),
                'catatan' => $proyek->catatan,
                'nama_klien' => $proyek->nama_klien,
                'kontak_klien' => $proyek->kontak_klien,
                'spesifikasi' => $proyek->spesifikasi,
                'jumlah' => $proyek->jumlah,
                'satuan' => $proyek->satuan,
                'harga_satuan' => $proyek->harga_satuan ?? 0,
                'potensi' => $proyek->potensi ?? 'tidak',
                'tahun_potensi' => $proyek->tahun_potensi ?? Carbon::now()->year,
                'daftar_barang' => $daftarBarang,
                'penawaran' => $proyek->penawaranAktif ? [
                    'no_penawaran' => $proyek->penawaranAktif->no_penawaran,
                    'tanggal_penawaran' => $proyek->penawaranAktif->tanggal_penawaran->format('Y-m-d'),
                    'total_penawaran' => $proyek->penawaranAktif->total_penawaran
                ] : null,
                'surat_penawaran' => $proyek->penawaranAktif ? $proyek->penawaranAktif->surat_penawaran : null,
                'surat_kontrak' => $proyek->penawaranAktif ? $proyek->penawaranAktif->surat_pesanan : null
            ];
        })->toArray();

        // Hitung total proyek
        $totalProyek = count($proyekData);

        return view('pages.marketing.proyek', compact('proyekData', 'totalProyek'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kab_kota' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'nama_klien' => 'required|string|max:255',
            'kontak_klien' => 'nullable|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'spesifikasi' => 'required|string',
            'harga_satuan' => 'nullable|numeric|min:0',
            'jenis_pengadaan' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'id_admin_marketing' => 'required|exists:users,id_user',
            'id_admin_purchasing' => 'required|exists:users,id_user',
            'catatan' => 'nullable|string',
            'potensi' => 'nullable|in:ya,tidak',
            'tahun_potensi' => 'nullable|integer|min:2020|max:2030'
        ]);

        $harga_total = null;
        if ($request->harga_satuan) {
            $harga_total = $request->harga_satuan * $request->jumlah;
        }

        $proyek = Proyek::create([
            'tanggal' => $request->tanggal,
            'kab_kota' => $request->kab_kota,
            'instansi' => $request->instansi,
            'nama_klien' => $request->nama_klien,
            'kontak_klien' => $request->kontak_klien,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'spesifikasi' => $request->spesifikasi,
            'harga_satuan' => $request->harga_satuan,
            'harga_total' => $harga_total,
            'jenis_pengadaan' => $request->jenis_pengadaan,
            'deadline' => $request->deadline,
            'id_admin_marketing' => $request->id_admin_marketing,
            'id_admin_purchasing' => $request->id_admin_purchasing,
            'catatan' => $request->catatan,
            'potensi' => $request->potensi ?? 'tidak',
            'tahun_potensi' => $request->tahun_potensi,
            'status' => 'Menunggu'
        ]);

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
        $proyek = Proyek::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'kab_kota' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'nama_klien' => 'required|string|max:255',
            'kontak_klien' => 'nullable|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'spesifikasi' => 'required|string',
            'harga_satuan' => 'nullable|numeric|min:0',
            'jenis_pengadaan' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'id_admin_marketing' => 'required|exists:users,id_user',
            'id_admin_purchasing' => 'required|exists:users,id_user',
            'catatan' => 'nullable|string',
            'potensi' => 'nullable|in:ya,tidak',
            'tahun_potensi' => 'nullable|integer|min:2020|max:2030'
        ]);

        $harga_total = null;
        if ($request->harga_satuan) {
            $harga_total = $request->harga_satuan * $request->jumlah;
        }

        $proyek->update([
            'tanggal' => $request->tanggal,
            'kab_kota' => $request->kab_kota,
            'instansi' => $request->instansi,
            'nama_klien' => $request->nama_klien,
            'kontak_klien' => $request->kontak_klien,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'spesifikasi' => $request->spesifikasi,
            'harga_satuan' => $request->harga_satuan,
            'harga_total' => $harga_total,
            'jenis_pengadaan' => $request->jenis_pengadaan,
            'deadline' => $request->deadline,
            'id_admin_marketing' => $request->id_admin_marketing,
            'id_admin_purchasing' => $request->id_admin_purchasing,
            'catatan' => $request->catatan,
            'potensi' => $request->potensi ?? 'tidak',
            'tahun_potensi' => $request->tahun_potensi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proyek berhasil diperbarui',
            'data' => $proyek
        ]);
    }

    public function destroy($id)
    {
        $proyek = Proyek::findOrFail($id);
        $proyek->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proyek berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
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
