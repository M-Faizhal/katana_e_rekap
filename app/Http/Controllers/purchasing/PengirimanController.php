<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penawaran;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends Controller
{
    public function index()
    {
        // Tab "Ready Kirim": Proyek dengan status "Pembayaran" yang pembayarannya sudah diverifikasi
        // dan belum ada pengiriman
        $proyekReady = DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('pembayaran', 'penawaran.id_penawaran', '=', 'pembayaran.id_penawaran')
            ->leftJoin('pengiriman', 'penawaran.id_penawaran', '=', 'pengiriman.id_penawaran')
            ->select([
                'penawaran.id_penawaran',
                'penawaran.no_penawaran',
                'proyek.nama_barang as nama_proyek',
                'proyek.instansi',
                'penawaran.total_penawaran as total_harga',
                'proyek.kota_kab as alamat_instansi',
                'proyek.kontak_klien as kontak_person',
                'proyek.status as status_proyek',
                'pembayaran.status_verifikasi as status_pembayaran',
                'pembayaran.tanggal_bayar'
            ])
            ->where('proyek.status', 'Pembayaran') // Status proyek = Pembayaran
            ->where('pembayaran.status_verifikasi', 'Approved') // Pembayaran sudah diverifikasi
            ->whereNull('pengiriman.id_pengiriman') // Belum ada pengiriman
            ->distinct()
            ->get();

        // Tab "Dalam Proses": Proyek dengan status "Pengiriman"
        $pengirimanBerjalan = DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('pengiriman', 'penawaran.id_penawaran', '=', 'pengiriman.id_penawaran')
            ->select([
                'pengiriman.*',
                'penawaran.no_penawaran',
                'proyek.nama_barang as nama_proyek',
                'proyek.instansi',
                'proyek.status as status_proyek'
            ])
            ->where('proyek.status', 'Pengiriman') // Status proyek = Pengiriman
            ->whereNotIn('pengiriman.status_verifikasi', ['Verified', 'Rejected']) // Pengiriman belum diverifikasi final
            ->get();

        // Tab "Selesai": Pengiriman yang sudah diverifikasi oleh superadmin (status proyek = Selesai)
        $pengirimanSelesai = DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('pengiriman', 'penawaran.id_penawaran', '=', 'pengiriman.id_penawaran')
            ->leftJoin('users as verifier', 'pengiriman.verified_by', '=', 'verifier.id_user')
            ->select([
                'pengiriman.*',
                'penawaran.no_penawaran',
                'proyek.nama_barang as nama_proyek',
                'proyek.instansi',
                'proyek.status as status_proyek',
                'verifier.nama as verified_by_name'
            ])
            ->where('pengiriman.status_verifikasi', 'Verified')
            ->orWhere('proyek.status', 'Selesai')
            ->get();

        return view('pages.purchasing.pengiriman', compact(
            'proyekReady',
            'pengirimanBerjalan', 
            'pengirimanSelesai'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penawaran' => 'required|exists:penawaran,id_penawaran',
            'no_surat_jalan' => 'required|string|max:255',
            'tanggal_kirim' => 'required|date',
            'alamat_kirim' => 'required|string',
            'file_surat_jalan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $data = $request->only([
            'id_penawaran',
            'no_surat_jalan', 
            'tanggal_kirim',
            'alamat_kirim'
        ]);

        // Upload file surat jalan jika ada
        if ($request->hasFile('file_surat_jalan')) {
            $file = $request->file('file_surat_jalan');
            $filename = 'surat_jalan_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/pengiriman'), $filename);
            $data['file_surat_jalan'] = 'pengiriman/' . $filename;
            
            Log::info("Surat jalan uploaded: {$filename}");
        }

        $data['status_verifikasi'] = 'Pending';

        // Buat pengiriman
        $pengiriman = Pengiriman::create($data);

        // Update status proyek dari "Pembayaran" menjadi "Pengiriman"
        DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->where('penawaran.id_penawaran', $request->id_penawaran)
            ->update(['proyek.status' => 'Pengiriman']);

        return redirect()->back()->with('success', 'Pengiriman berhasil dibuat! Status proyek telah diupdate menjadi "Pengiriman".');
    }

    public function updateDokumentasi(Request $request, $id)
    {
        $request->validate([
            'foto_berangkat' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto_perjalanan' => 'nullable|file|mimes:jpg,jpeg,png|max:5120', 
            'foto_sampai' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'tanda_terima' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $pengiriman = Pengiriman::findOrFail($id);
        $data = [];

        // Upload dokumentasi foto dan hapus file lama jika ada
        $dokumentasi = ['foto_berangkat', 'foto_perjalanan', 'foto_sampai', 'tanda_terima'];
        
        foreach ($dokumentasi as $dok) {
            if ($request->hasFile($dok)) {
                // Hapus file lama jika ada
                if ($pengiriman->$dok) {
                    $this->deleteFileIfExists($pengiriman->$dok);
                }
                
                // Upload file baru
                $file = $request->file($dok);
                $filename = $dok . '_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/pengiriman'), $filename);
                $data[$dok] = 'pengiriman/' . $filename;
                
                Log::info("File {$dok} updated for pengiriman {$id}: {$filename}");
            }
        }

        // Update status berdasarkan dokumentasi yang diupload
        if ($request->hasFile('tanda_terima')) {
            $data['status_verifikasi'] = 'Sampai_Tujuan'; // Menunggu verifikasi final dari superadmin
        } else if ($request->hasFile('foto_perjalanan')) {
            $data['status_verifikasi'] = 'Dalam_Proses';
        } else if ($request->hasFile('foto_berangkat')) {
            $data['status_verifikasi'] = 'Dalam_Proses';
        }

        $pengiriman->update($data);

        $message = 'Dokumentasi berhasil diupdate!';
        if (isset($data['status_verifikasi']) && $data['status_verifikasi'] === 'Sampai_Tujuan') {
            $message .= ' Pengiriman menunggu verifikasi final dari Superadmin.';
        }

        return redirect()->back()->with('success', $message);
    }

    // Method baru untuk verifikasi oleh superadmin
    public function verify(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_verifikasi' => 'required|string|max:500'
        ]);

        $pengiriman = Pengiriman::findOrFail($id);
        
        if ($request->action === 'approve') {
            $pengiriman->update([
                'status_verifikasi' => 'Verified',
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'verified_by' => Auth::id(),
                'verified_at' => now()
            ]);

            // Update status proyek menjadi "Selesai" ketika pengiriman diverifikasi
            DB::table('proyek')
                ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
                ->where('penawaran.id_penawaran', $pengiriman->id_penawaran)
                ->update(['proyek.status' => 'Selesai']);

            return redirect()->back()->with('success', 'Pengiriman berhasil diverifikasi! Status proyek telah diubah menjadi "Selesai".');
        } else {
            $pengiriman->update([
                'status_verifikasi' => 'Rejected',
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'verified_by' => Auth::id(),
                'verified_at' => now()
            ]);

            return redirect()->back()->with('error', 'Pengiriman ditolak. Silakan perbaiki dokumentasi yang diperlukan.');
        }
    }

    /**
     * Helper method to safely delete file from storage
     */
    private function deleteFileIfExists($filePath)
    {
        try {
            if ($filePath) {
                $fullPath = public_path('storage/' . $filePath);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                    Log::info("File deleted: {$fullPath}");
                    return true;
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to delete file: {$filePath}. Error: " . $e->getMessage());
        }
        return false;
    }

    /**
     * Method untuk menghapus pengiriman beserta file-filenya
     */
    public function destroy($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);
        
        // Hapus semua file terkait pengiriman
        $files = [
            'file_surat_jalan',
            'foto_berangkat', 
            'foto_perjalanan',
            'foto_sampai',
            'tanda_terima'
        ];
        
        foreach ($files as $fileField) {
            if ($pengiriman->$fileField) {
                $this->deleteFileIfExists($pengiriman->$fileField);
            }
        }
        
        // Hapus record pengiriman
        $pengiriman->delete();
        
        return redirect()->back()->with('success', 'Pengiriman dan semua file terkait berhasil dihapus.');
    }

    /**
     * Method untuk update file surat jalan
     */
    public function updateSuratJalan(Request $request, $id)
    {
        $request->validate([
            'file_surat_jalan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $pengiriman = Pengiriman::findOrFail($id);

        // Hapus file surat jalan lama jika ada
        if ($pengiriman->file_surat_jalan) {
            $this->deleteFileIfExists($pengiriman->file_surat_jalan);
        }

        // Upload file surat jalan baru
        if ($request->hasFile('file_surat_jalan')) {
            $file = $request->file('file_surat_jalan');
            $filename = 'surat_jalan_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/pengiriman'), $filename);
            
            $pengiriman->update([
                'file_surat_jalan' => 'pengiriman/' . $filename
            ]);
            
            Log::info("Surat jalan updated for pengiriman {$id}: {$filename}");
        }

        return redirect()->back()->with('success', 'File surat jalan berhasil diperbarui.');
    }

    /**
     * Clean up orphaned files (untuk maintenance)
     */
    public function cleanupOrphanedFiles()
    {
        $pengirimanDir = public_path('storage/pengiriman');
        
        if (!File::exists($pengirimanDir)) {
            return response()->json(['message' => 'Directory tidak ditemukan']);
        }

        // Ambil semua file di folder pengiriman
        $allFiles = File::files($pengirimanDir);
        $allFileNames = array_map(function($file) {
            return 'pengiriman/' . $file->getFilename();
        }, $allFiles);

        // Ambil semua path file yang masih digunakan di database
        $usedFiles = Pengiriman::select([
            'file_surat_jalan',
            'foto_berangkat',
            'foto_perjalanan', 
            'foto_sampai',
            'tanda_terima'
        ])->get()
        ->flatMap(function ($pengiriman) {
            return array_filter([
                $pengiriman->file_surat_jalan,
                $pengiriman->foto_berangkat,
                $pengiriman->foto_perjalanan,
                $pengiriman->foto_sampai,
                $pengiriman->tanda_terima
            ]);
        })->toArray();

        $orphanedFiles = array_diff($allFileNames, $usedFiles);
        $deletedCount = 0;

        foreach ($orphanedFiles as $file) {
            if ($this->deleteFileIfExists($file)) {
                $deletedCount++;
            }
        }

        return response()->json([
            'message' => "Cleanup completed. {$deletedCount} orphaned files deleted.",
            'deleted_count' => $deletedCount,
            'orphaned_files' => array_values($orphanedFiles)
        ]);
    }

    /**
     * Get pengiriman data with file status for modal display
     */
    public function getDetailWithFiles($id)
    {
        $pengiriman = Pengiriman::with(['penawaran.proyek'])->findOrFail($id);
        
        $fileStatus = [
            'file_surat_jalan' => [
                'exists' => !empty($pengiriman->file_surat_jalan),
                'path' => $pengiriman->file_surat_jalan,
                'url' => $pengiriman->file_surat_jalan ? asset('storage/' . $pengiriman->file_surat_jalan) : null,
                'name' => $pengiriman->file_surat_jalan ? basename($pengiriman->file_surat_jalan) : null
            ],
            'foto_berangkat' => [
                'exists' => !empty($pengiriman->foto_berangkat),
                'path' => $pengiriman->foto_berangkat,
                'url' => $pengiriman->foto_berangkat ? asset('storage/' . $pengiriman->foto_berangkat) : null,
                'name' => $pengiriman->foto_berangkat ? basename($pengiriman->foto_berangkat) : null
            ],
            'foto_perjalanan' => [
                'exists' => !empty($pengiriman->foto_perjalanan),
                'path' => $pengiriman->foto_perjalanan,
                'url' => $pengiriman->foto_perjalanan ? asset('storage/' . $pengiriman->foto_perjalanan) : null,
                'name' => $pengiriman->foto_perjalanan ? basename($pengiriman->foto_perjalanan) : null
            ],
            'foto_sampai' => [
                'exists' => !empty($pengiriman->foto_sampai),
                'path' => $pengiriman->foto_sampai,
                'url' => $pengiriman->foto_sampai ? asset('storage/' . $pengiriman->foto_sampai) : null,
                'name' => $pengiriman->foto_sampai ? basename($pengiriman->foto_sampai) : null
            ],
            'tanda_terima' => [
                'exists' => !empty($pengiriman->tanda_terima),
                'path' => $pengiriman->tanda_terima,
                'url' => $pengiriman->tanda_terima ? asset('storage/' . $pengiriman->tanda_terima) : null,
                'name' => $pengiriman->tanda_terima ? basename($pengiriman->tanda_terima) : null
            ]
        ];
        
        return response()->json([
            'pengiriman' => $pengiriman,
            'file_status' => $fileStatus
        ]);
    }
}
