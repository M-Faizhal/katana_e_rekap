<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    /**
     * Display a listing of projects that need payment processing
     */
    public function index()
    {
        // Log untuk debugging
        Log::info('PembayaranController@index called');
        
        // Ambil proyek yang statusnya 'Pembayaran' dan sudah ada penawaran yang di-ACC
        // Tapi belum lunas pembayarannya
        $proyekPerluBayar = Proyek::with(['penawaranAktif', 'adminMarketing', 'pembayaran'])
            ->where('status', 'Pembayaran')
            ->whereHas('penawaranAktif', function ($query) {
                $query->where('status', 'ACC');
            })
            ->get()
            ->filter(function ($proyek) {
                // Hitung total yang sudah dibayar dan disetujui (approved saja)
                $totalDibayarApproved = $proyek->pembayaran()
                    ->where('status_verifikasi', 'Approved')
                    ->sum('nominal_bayar');
                
                // Hitung sisa bayar
                $sisaBayar = $proyek->penawaranAktif->total_penawaran - $totalDibayarApproved;
                
                // Log untuk debugging
                Log::info("Proyek {$proyek->nama_barang}: Total Penawaran = {$proyek->penawaranAktif->total_penawaran}, Total Dibayar Approved = {$totalDibayarApproved}, Sisa = {$sisaBayar}");
                
                // Hanya tampilkan yang masih ada sisa bayar (lebih dari 0)
                return $sisaBayar > 0;
            })
            ->sortBy('nama_barang')
            ->values();

        // Convert collection to paginated result
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $currentPageItems = $proyekPerluBayar->slice(($currentPage - 1) * $perPage, $perPage);
        $proyekPerluBayar = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $proyekPerluBayar->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        Log::info('ProyekPerluBayar count: ' . $proyekPerluBayar->count());

        // Ambil parameter filter dan search
        $search = request()->get('search');
        $statusFilter = request()->get('status_filter'); // untuk pembayaran
        $proyekStatusFilter = request()->get('proyek_status_filter'); // untuk status proyek lunas/belum
        $sortBy = request()->get('sort_by', 'created_at');
        $sortOrder = request()->get('sort_order', 'desc');

        // Ambil semua proyek dengan status Pembayaran untuk history
        $semuaProyekQuery = Proyek::with(['penawaranAktif', 'adminMarketing', 'pembayaran'])
            ->where('status', 'Pembayaran')
            ->whereHas('penawaranAktif', function ($query) {
                $query->where('status', 'ACC');
            });

        // Filter berdasarkan search
        if ($search) {
            $semuaProyekQuery->where(function ($query) use ($search) {
                $query->where('nama_barang', 'like', "%{$search}%")
                      ->orWhere('instansi', 'like', "%{$search}%")
                      ->orWhere('nama_klien', 'like', "%{$search}%")
                      ->orWhere('kota_kab', 'like', "%{$search}%")
                      ->orWhereHas('penawaranAktif', function ($subQuery) use ($search) {
                          $subQuery->where('no_penawaran', 'like', "%{$search}%");
                      });
            });
        }

        // Sorting
        if ($sortBy === 'nama_barang') {
            $semuaProyekQuery->orderBy('nama_barang', $sortOrder);
        } elseif ($sortBy === 'instansi') {
            $semuaProyekQuery->orderBy('instansi', $sortOrder);
        } elseif ($sortBy === 'nama_klien') {
            $semuaProyekQuery->orderBy('nama_klien', $sortOrder);
        } else {
            $semuaProyekQuery->orderBy('created_at', $sortOrder);
        }

        $semuaProyek = $semuaProyekQuery->paginate(15, ['*'], 'proyek_page');

        // Hitung statistik untuk setiap proyek
        $semuaProyek->getCollection()->transform(function ($proyek) {
            $totalDibayarApproved = $proyek->pembayaran()
                ->where('status_verifikasi', 'Approved')
                ->sum('nominal_bayar');
            
            $sisaBayar = $proyek->penawaranAktif->total_penawaran - $totalDibayarApproved;
            $persenBayar = $proyek->penawaranAktif->total_penawaran > 0 ? 
                ($totalDibayarApproved / $proyek->penawaranAktif->total_penawaran) * 100 : 0;

            $proyek->total_dibayar_approved = $totalDibayarApproved;
            $proyek->sisa_bayar = $sisaBayar;
            $proyek->persen_bayar = $persenBayar;
            $proyek->status_lunas = $sisaBayar <= 0;

            return $proyek;
        });

        // Filter berdasarkan status proyek (lunas/belum lunas) setelah perhitungan
        if ($proyekStatusFilter && $proyekStatusFilter !== 'all') {
            $semuaProyek = $semuaProyek->filter(function ($proyek) use ($proyekStatusFilter) {
                if ($proyekStatusFilter === 'lunas') {
                    return $proyek->status_lunas;
                } elseif ($proyekStatusFilter === 'belum_lunas') {
                    return !$proyek->status_lunas;
                }
                return true;
            });

            // Re-paginate setelah filter
            $currentPage = request()->get('proyek_page', 1);
            $perPage = 15;
            $currentPageItems = $semuaProyek->slice(($currentPage - 1) * $perPage, $perPage);
            $semuaProyek = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $semuaProyek->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'proyek_page']
            );
        }

        // Ambil semua pembayaran dengan filter
        $semuaPembayaranQuery = Pembayaran::with(['penawaran.proyek.adminMarketing'])
            ->whereHas('penawaran.proyek', function ($query) {
                $query->where('status', 'Pembayaran')
                      ->whereHas('penawaranAktif', function ($subQuery) {
                          $subQuery->where('status', 'ACC');
                      });
            });

        // Filter pembayaran berdasarkan status
        if ($statusFilter && $statusFilter !== 'all') {
            $semuaPembayaranQuery->where('status_verifikasi', $statusFilter);
        }

        // Filter pembayaran berdasarkan search
        if ($search) {
            $semuaPembayaranQuery->whereHas('penawaran.proyek', function ($query) use ($search) {
                $query->where('nama_barang', 'like', "%{$search}%")
                      ->orWhere('instansi', 'like', "%{$search}%")
                      ->orWhere('nama_klien', 'like', "%{$search}%");
            });
        }

        $semuaPembayaran = $semuaPembayaranQuery->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'pembayaran_page');

        Log::info('SemuaPembayaran count: ' . $semuaPembayaran->count());

        return view('pages.purchasing.pembayaran', compact(
            'proyekPerluBayar', 
            'semuaPembayaran', 
            'semuaProyek',
            'search',
            'statusFilter',
            'proyekStatusFilter',
            'sortBy',
            'sortOrder'
        ));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create($id_proyek)
    {
        $proyek = Proyek::with(['penawaranAktif', 'adminMarketing', 'pembayaran'])
            ->findOrFail($id_proyek);

        // Pastikan proyek memiliki penawaran yang sudah di-ACC
        if (!$proyek->penawaranAktif || $proyek->penawaranAktif->status !== 'ACC') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Proyek ini belum memiliki penawaran yang di-ACC');
        }

        // Hitung total yang sudah dibayar dan disetujui (approved saja)
        $totalDibayar = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        $sisaBayar = $proyek->penawaranAktif->total_penawaran - $totalDibayar;

        return view('pages.purchasing.pembayaran-components.pembayaran-form', compact('proyek', 'totalDibayar', 'sisaBayar'));
    }

    /**
     * Store a newly created payment in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_proyek' => 'required|exists:proyek,id_proyek',
            'jenis_bayar' => 'required|in:Lunas,DP,Cicilan',
            'nominal_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB
            'catatan' => 'nullable|string'
        ]);

        $proyek = Proyek::with('penawaranAktif')->findOrFail($request->id_proyek);

        // Validasi nominal pembayaran
        $totalDibayar = Pembayaran::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
            ->where('status_verifikasi', '!=', 'Ditolak')
            ->sum('nominal_bayar');

        $sisaBayar = $proyek->penawaranAktif->total_penawaran - $totalDibayar;

        if ($request->nominal_bayar > $sisaBayar) {
            return back()->with('error', 'Nominal pembayaran melebihi sisa tagihan');
        }

        DB::beginTransaction();
        try {
            // Upload bukti pembayaran dulu
            $buktiPath = null;
            if ($request->hasFile('bukti_bayar')) {
                $buktiPath = $request->file('bukti_bayar')->store('pembayaran', 'public');
            }

            // Simpan pembayaran
            $pembayaran = Pembayaran::create([
                'id_penawaran' => $proyek->penawaranAktif->id_penawaran,
                'jenis_bayar' => $request->jenis_bayar,
                'nominal_bayar' => $request->nominal_bayar,
                'tanggal_bayar' => now()->toDateString(),
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar' => $buktiPath,
                'catatan' => $request->catatan,
                'status_verifikasi' => 'Pending', // Menunggu verifikasi admin keuangan
            ]);

            // Update status proyek jika pembayaran lunas
            $totalSetelahBayar = $totalDibayar + $request->nominal_bayar;
            if ($totalSetelahBayar >= $proyek->penawaranAktif->total_penawaran) {
                $proyek->update(['status' => 'Pengiriman']);
            }

            DB::commit();
            
            Log::info("Pembayaran berhasil disimpan: ID {$pembayaran->id_pembayaran}, File: {$buktiPath}");

            return redirect()->route('purchasing.pembayaran')
                ->with('success', 'Pembayaran berhasil disimpan dan menunggu verifikasi admin keuangan');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang sudah diupload jika terjadi error
            if ($buktiPath) {
                $this->deleteFileIfExists($buktiPath);
            }
            
            Log::error("Error saving pembayaran: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan pembayaran');
        }
    }

    /**
     * Display the specified payment details
     */
    public function show($id_pembayaran)
    {
        $pembayaran = Pembayaran::with(['penawaran.proyek'])->findOrFail($id_pembayaran);

        return view('pages.purchasing.pembayaran-components.pembayaran-detail', compact('pembayaran'));
    }

    /**
     * Show payment history for a project
     */
    public function history($id_proyek)
    {
        $proyek = Proyek::with(['penawaranAktif', 'adminMarketing'])->findOrFail($id_proyek);
        
        $riwayatPembayaran = Pembayaran::with(['penawaran'])
            ->where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.purchasing.pembayaran-components.pembayaran-history', compact('proyek', 'riwayatPembayaran'));
    }

    /**
     * Calculate payment suggestions for quick input
     */
    public function calculateSuggestion(Request $request)
    {
        $proyek = Proyek::with('penawaranAktif')->findOrFail($request->id_proyek);
        $totalPenawaran = $proyek->penawaranAktif->total_penawaran;

        $suggestions = [
            'lunas' => $totalPenawaran,
            'dp_30' => $totalPenawaran * 0.3,
            'dp_50' => $totalPenawaran * 0.5,
            'dp_70' => $totalPenawaran * 0.7,
        ];

        return response()->json($suggestions);
    }

    /**
     * Show the form for editing payment
     */
    public function edit($id_pembayaran)
    {
        $pembayaran = Pembayaran::with(['penawaran.proyek.adminMarketing'])->findOrFail($id_pembayaran);
        
        // Hanya bisa edit jika status masih pending
        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat diubah');
        }

        $proyek = $pembayaran->penawaran->proyek;
        
        // Hitung total yang sudah dibayar (exclude pembayaran ini dan yang ditolak)
        $totalDibayar = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
            ->where('id_pembayaran', '!=', $id_pembayaran)
            ->where('status_verifikasi', '!=', 'Ditolak')
            ->sum('nominal_bayar');

        $sisaBayar = $pembayaran->penawaran->total_penawaran - $totalDibayar;

        return view('pages.purchasing.pembayaran-components.pembayaran-edit', compact('pembayaran', 'proyek', 'totalDibayar', 'sisaBayar'));
    }

    /**
     * Update payment with proper file management
     */
    public function update(Request $request, $id_pembayaran)
    {
        $pembayaran = Pembayaran::with(['penawaran.proyek'])->findOrFail($id_pembayaran);
        
        // Hanya bisa update jika status masih pending
        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat diubah');
        }

        $request->validate([
            'jenis_bayar' => 'required|in:Lunas,DP,Cicilan',
            'nominal_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // optional untuk update
            'catatan' => 'nullable|string'
        ]);

        // Validasi nominal pembayaran
        $totalDibayar = Pembayaran::where('id_penawaran', $pembayaran->id_penawaran)
            ->where('id_pembayaran', '!=', $id_pembayaran)
            ->where('status_verifikasi', '!=', 'Ditolak')
            ->sum('nominal_bayar');

        $sisaBayar = $pembayaran->penawaran->total_penawaran - $totalDibayar;

        if ($request->nominal_bayar > $sisaBayar) {
            return back()->with('error', 'Nominal pembayaran melebihi sisa tagihan');
        }

        DB::beginTransaction();
        try {
            $oldBuktiPath = $pembayaran->bukti_bayar;
            $newBuktiPath = $oldBuktiPath; // Default keep old file

            // Handle file upload jika ada file baru
            if ($request->hasFile('bukti_bayar')) {
                // Upload file baru
                $newBuktiPath = $request->file('bukti_bayar')->store('pembayaran', 'public');
                
                // Hapus file lama jika ada dan berbeda
                if ($oldBuktiPath && $oldBuktiPath !== $newBuktiPath) {
                    $this->deleteFileIfExists($oldBuktiPath);
                }
            }

            // Update pembayaran
            $pembayaran->update([
                'jenis_bayar' => $request->jenis_bayar,
                'nominal_bayar' => $request->nominal_bayar,
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar' => $newBuktiPath,
                'catatan' => $request->catatan,
            ]);

            DB::commit();

            return redirect()->route('purchasing.pembayaran')
                ->with('success', 'Pembayaran berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file baru jika upload gagal
            if (isset($newBuktiPath) && $newBuktiPath !== $oldBuktiPath) {
                $this->deleteFileIfExists($newBuktiPath);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat mengupdate pembayaran');
        }
    }

    /**
     * Delete payment and clean up files
     */
    public function destroy($id_pembayaran)
    {
        $pembayaran = Pembayaran::findOrFail($id_pembayaran);
        
        // Hanya bisa hapus jika status masih pending
        if ($pembayaran->status_verifikasi !== 'Pending') {
            return redirect()->route('purchasing.pembayaran')
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat dihapus');
        }

        DB::beginTransaction();
        try {
            $buktiPath = $pembayaran->bukti_bayar;
            
            // Hapus record dari database
            $pembayaran->delete();
            
            // Hapus file bukti pembayaran
            if ($buktiPath) {
                $this->deleteFileIfExists($buktiPath);
            }

            DB::commit();

            return redirect()->route('purchasing.pembayaran')
                ->with('success', 'Pembayaran berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus pembayaran');
        }
    }

    /**
     * Helper method to safely delete file from storage
     */
    private function deleteFileIfExists($filePath)
    {
        try {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                Log::info("File deleted: {$filePath}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to delete file: {$filePath}. Error: " . $e->getMessage());
        }
    }

    /**
     * Clean up orphaned files (untuk maintenance)
     */
    public function cleanupOrphanedFiles()
    {
        // Ambil semua file di folder pembayaran
        $allFiles = Storage::disk('public')->files('pembayaran');
        
        // Ambil semua path file yang masih digunakan di database
        $usedFiles = Pembayaran::whereNotNull('bukti_bayar')
            ->pluck('bukti_bayar')
            ->toArray();

        $orphanedFiles = array_diff($allFiles, $usedFiles);
        $deletedCount = 0;

        foreach ($orphanedFiles as $file) {
            try {
                Storage::disk('public')->delete($file);
                $deletedCount++;
                Log::info("Orphaned file deleted: {$file}");
            } catch (\Exception $e) {
                Log::error("Failed to delete orphaned file: {$file}. Error: " . $e->getMessage());
            }
        }

        return response()->json([
            'message' => "Cleanup completed. {$deletedCount} orphaned files deleted.",
            'deleted_count' => $deletedCount
        ]);
    }
}
