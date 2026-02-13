<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WilayahController extends Controller
{
    public function index()
    {
        // Ambil semua data wilayah
        $allWilayah = Wilayah::with(['proyeks.adminMarketing', 'updatedBy'])
            ->active()
            ->get();

        // Buat array untuk menyimpan instansi dengan PIC-nya
        $instansiWithPIC = $allWilayah->map(function ($wilayah) {
            $proyekCount = $wilayah->proyeks->count();
            $adminMarketingFromRelasi = $wilayah->proyeks->pluck('adminMarketing.nama')->filter()->unique();
            $adminMarketing = $wilayah->admin_marketing_text ?: ($adminMarketingFromRelasi->implode(', ') ?: '-');

            // Cek apakah data valid atau kosong
            $noTelp = $wilayah->no_telp && trim($wilayah->no_telp) !== '' ? $wilayah->no_telp : '-';
            $email = $wilayah->email && trim($wilayah->email) !== '' ? $wilayah->email : '-';

            return [
                'id' => $wilayah->id_wilayah,
                'nama_wilayah' => $wilayah->nama_wilayah,
                'provinsi' => $wilayah->provinsi,
                'kode_wilayah' => $wilayah->kode_wilayah,
                'deskripsi' => $wilayah->deskripsi,
                'instansi' => $wilayah->instansi ?: '-',
                'nama_pejabat' => $wilayah->nama_pejabat ?: '-',
                'jabatan' => $wilayah->jabatan ?: '-',
                'no_telp' => $noTelp,
                'email' => $email,
                'admin_marketing_text' => $wilayah->admin_marketing_text,
                'jumlah_proyek' => $proyekCount,
                'admin_marketing' => $adminMarketing,
                'updated_at' => $wilayah->updated_at->format('d M Y'),
                'updated_at_timestamp' => $wilayah->updated_at->timestamp,
                'updated_by_name' => $wilayah->updatedBy ? $wilayah->updatedBy->nama : 'System',
                'created_at' => $wilayah->created_at->format('d M Y')
            ];
        });

        // Group berdasarkan kombinasi nama_wilayah + admin_marketing
        $wilayahData = $instansiWithPIC->groupBy(function($item) {
            return $item['nama_wilayah'] . '|||' . $item['admin_marketing'];
        })->map(function ($group, $groupKey) {
            list($namaWilayah, $adminMarketing) = explode('|||', $groupKey);
            $firstItem = $group->first();

            return [
                'id' => $firstItem['id'],
                'wilayah' => $namaWilayah,
                'provinsi' => $firstItem['provinsi'],
                'kode_wilayah' => $firstItem['kode_wilayah'],
                'deskripsi' => $firstItem['deskripsi'],
                'pic_wilayah' => $adminMarketing, // PIC untuk card ini
                'instansi_list' => $group->toArray(),
                'jumlah_instansi' => $group->count(),
                'total_proyek' => $group->sum('jumlah_proyek'),
                'updated_at' => $firstItem['updated_at'],
                'updated_at_timestamp' => $firstItem['updated_at_timestamp'],
                'updated_by_name' => $firstItem['updated_by_name'],
                'created_at' => $firstItem['created_at']
            ];
        })->values()->toArray();

        // Hitung statistik
        $totalWilayah = count($wilayahData);
        $totalInstansi = Wilayah::active()->count();
        $totalKontak = Wilayah::active()->whereNotNull('no_telp')->count();
        $totalAdminMarketing = Wilayah::with('proyeks')->active()->get()
            ->pluck('proyeks')
            ->flatten()
            ->pluck('id_admin_marketing')
            ->filter()
            ->unique()
            ->count();

        return view('pages.marketing.wilayah', compact(
            'wilayahData',
            'totalWilayah',
            'totalInstansi',
            'totalKontak',
            'totalAdminMarketing'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wilayah' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'kode_wilayah' => 'required|string|max:10|unique:wilayah,kode_wilayah',
            'nama_pejabat' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'admin_marketing_text' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        try {
            // Buat data wilayah baru
            $wilayah = Wilayah::create([
                'nama_wilayah' => $request->wilayah,
                'provinsi' => $request->provinsi,
                'instansi' => $request->instansi,
                'kode_wilayah' => $request->kode_wilayah,
                'nama_pejabat' => $request->nama_pejabat,
                'jabatan' => $request->jabatan,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'admin_marketing_text' => $request->admin_marketing_text,
                'deskripsi' => $request->deskripsi,
                'is_active' => true,
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data wilayah berhasil ditambahkan',
                'data' => $wilayah
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan data wilayah: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Debug logging
        Log::info('Update request received', [
            'id' => $id,
            'data' => $request->all(),
            'method' => $request->method(),
            'route_param' => $id
        ]);

        // Check existing record first
        $existing = Wilayah::find($id);
        Log::info('Existing record', [
            'found' => $existing ? true : false,
            'record' => $existing ? $existing->toArray() : null
        ]);

        try {
            $request->validate([
                'wilayah' => 'required|string|max:255',
                'provinsi' => 'required|string|max:255',
                'instansi' => 'required|string|max:255',
                'kode_wilayah' => 'required|string|max:10|unique:wilayah,kode_wilayah,' . $id . ',id_wilayah',
                'nama_pejabat' => 'required|string|max:255',
                'jabatan' => 'required|string|max:255',
                'no_telp' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'alamat' => 'nullable|string',
                'admin_marketing_text' => 'required|string|max:255',
                'deskripsi' => 'nullable|string'
            ]);

            Log::info('Validation passed for ID: ' . $id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'id' => $id,
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $wilayah = Wilayah::find($id);

            if (!$wilayah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data wilayah tidak ditemukan'
                ], 404);
            }

            // Update data wilayah
            $wilayah->update([
                'nama_wilayah' => $request->wilayah,
                'provinsi' => $request->provinsi,
                'instansi' => $request->instansi,
                'kode_wilayah' => $request->kode_wilayah,
                'nama_pejabat' => $request->nama_pejabat,
                'jabatan' => $request->jabatan,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'admin_marketing_text' => $request->admin_marketing_text,
                'deskripsi' => $request->deskripsi,
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data wilayah berhasil diperbarui',
                'data' => $wilayah
            ]);

        } catch (\Exception $e) {
            Log::error('Update error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data wilayah: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $wilayah = Wilayah::find($id);

            if (!$wilayah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data wilayah tidak ditemukan'
                ], 404);
            }

            // Cek apakah wilayah masih memiliki proyek yang terkait
            $proyekCount = $wilayah->proyeks()->count();

            if ($proyekCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat menghapus wilayah {$wilayah->nama_wilayah} karena masih memiliki {$proyekCount} proyek yang terkait. Harap hapus atau pindahkan proyek terlebih dahulu."
                ], 400);
            }

            // Hapus wilayah jika tidak ada proyek yang terkait
            $namaWilayah = $wilayah->nama_wilayah;
            $wilayah->delete();

            return response()->json([
                'success' => true,
                'message' => "Data wilayah {$namaWilayah} berhasil dihapus"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data wilayah: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUsersForSelect()
    {
        $users = User::select('id_user', 'nama', 'role')
            ->whereIn('role', ['superadmin', 'admin_marketing'])
            ->orderBy('nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    private function generateJabatan($instansi)
    {
        if (strpos(strtolower($instansi), 'dinas') !== false) {
            return 'Kepala Dinas';
        } elseif (strpos(strtolower($instansi), 'rsud') !== false || strpos(strtolower($instansi), 'rumah sakit') !== false) {
            return 'Direktur RSUD';
        } elseif (strpos(strtolower($instansi), 'badan') !== false) {
            return 'Kepala Badan';
        } elseif (strpos(strtolower($instansi), 'universitas') !== false) {
            return 'Rektor';
        } else {
            return 'Kepala Instansi';
        }
    }

    private function generateAlamat($wilayah)
    {
        $alamatMap = [
            'Jakarta' => 'Jl. MH Thamrin No. 1, Jakarta Pusat',
            'Bogor' => 'Jl. Ir. H. Juanda No. 10, Bogor',
            'Depok' => 'Jl. Margonda Raya No. 1, Depok',
            'Tangerang' => 'Jl. Daan Mogot Km. 11, Tangerang',
            'Bekasi' => 'Jl. Ahmad Yani No. 1, Bekasi'
        ];

        foreach ($alamatMap as $kota => $alamat) {
            if (strpos($wilayah, $kota) !== false) {
                return $alamat;
            }
        }

        return 'Jl. Sudirman No. 1, ' . $wilayah;
    }

    private function generateNamaPejabat($instansi)
    {
        $namaPejabat = [
            'Dinas Pendidikan' => 'Dr. Budi Santoso, M.Pd',
            'RSUD' => 'Dr. Sari Dewi, Sp.M',
            'Badan' => 'Ir. Ahmad Fauzi, M.T',
            'Universitas' => 'Prof. Dr. Maria Sari, M.Sc',
            'PT' => 'Ir. Rudi Hartono',
            'CV' => 'Andi Wijaya, S.E'
        ];

        foreach ($namaPejabat as $key => $nama) {
            if (strpos(strtolower($instansi ?: ''), strtolower($key)) !== false) {
                return $nama;
            }
        }

        return 'Bapak/Ibu Pejabat';
    }

    private function generateNoTelp($wilayah)
    {
        $nomorTelepon = [
            'Jakarta' => '021-123-4567',
            'Bandung' => '022-234-5678',
            'Surabaya' => '031-345-6789',
            'Yogyakarta' => '0274-456-7890',
            'Semarang' => '024-567-8901'
        ];

        foreach ($nomorTelepon as $kota => $nomor) {
            if (strpos($wilayah, $kota) !== false) {
                return $nomor;
            }
        }

        return '021-000-0000';
    }

    private function generateEmail($nama, $instansi)
    {
        $namaParts = explode(' ', strtolower($nama));
        $firstName = $namaParts[0] ?? 'admin';
        $lastName = $namaParts[1] ?? '';

        $domain = 'go.id';
        if (strpos(strtolower($instansi), 'universitas') !== false) {
            $domain = 'ac.id';
        }

        return $firstName . ($lastName ? '.' . $lastName : '') . '@' . str_replace(' ', '', strtolower($instansi)) . '.' . $domain;
    }
}
