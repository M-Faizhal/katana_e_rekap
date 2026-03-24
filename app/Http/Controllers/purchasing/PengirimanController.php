<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\Pengiriman;
use App\Models\PenawaranDetail;
use App\Models\SuratTandaTerima;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;

class PengirimanController extends Controller
{
    /**
     * Display a listing of projects ready for shipping
     *
     * Optimisations vs original:
     * - Removed per-vendor Pengiriman::where() query inside nested map() loop
     *   → replaced with in-memory filtering on the already eager-loaded $proyek->pengiriman
     * - Removed all Log::info() debug calls
     * - Deduplicated the identical kalkulasiHps barang-list transform (extracted to helper)
     * - kalkulasiHps.vendor eager-load removed (vendor not needed there)
     */
    public function index()
    {
        $search    = request()->get('search');
        $activeTab = request()->get('tab', 'ready');

        // ----------------------------------------------------------------
        // TAB: Ready Kirim
        // ----------------------------------------------------------------
        $proyekReadyQuery = Proyek::with([
                'penawaranAktif.penawaranDetail.barang.vendor',
                'adminMarketing',
                'pembayaran',
                'pengiriman',          // hasManyThrough — already defined on Proyek
                'kalkulasiHps.barang', // for barang_vendor list (in-memory)
            ])
            ->whereIn('status', ['Pengiriman', 'Selesai'])
            ->whereHas('penawaranAktif', fn($q) => $q->where('status', 'ACC'));

        if ($search) {
            $proyekReadyQuery->where(function ($q) use ($search) {
                $q->where('instansi', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%")
                  ->orWhereHas('proyekBarang', fn($sq) => $sq->where('nama_barang', 'like', "%{$search}%"));
            });
        }

        $proyekReady = $proyekReadyQuery->get()
            ->map(function ($proyek) {
                // Group existing pengiriman by vendor ID for O(1) lookup — no extra queries
                $pengirimanByVendor = $proyek->pengiriman->groupBy('id_vendor');

                // Group kalkulasi HPS by vendor ID for O(1) barang lookup
                $kalkulasiByVendor = $proyek->kalkulasiHps->groupBy('id_vendor');

                $vendors = $proyek->penawaranAktif->penawaranDetail
                    ->pluck('barang.vendor')
                    ->unique('id_vendor')
                    ->filter();

                $proyek->vendors_ready = $vendors->map(function ($vendor) use ($proyek, $pengirimanByVendor, $kalkulasiByVendor) {
                    $totalVendor = $proyek->penawaranAktif->penawaranDetail
                        ->where('barang.id_vendor', $vendor->id_vendor)
                        ->sum('total_harga_hpp');

                    $totalDibayarApproved = $proyek->pembayaran
                        ->where('id_vendor', $vendor->id_vendor)
                        ->where('status_verifikasi', 'Approved')
                        ->sum('nominal_bayar');

                    $hasPembayaranApproved = $totalDibayarApproved > 0;

                    // In-memory lookup — zero extra queries
                    $pengirimanVendor = $pengirimanByVendor->get($vendor->id_vendor, collect());

                    // Barang list from kalkulasiHps (in-memory)
                    $barangVendor = $kalkulasiByVendor->get($vendor->id_vendor, collect())
                        ->pluck('barang.nama_barang')
                        ->filter()
                        ->unique()
                        ->values()
                        ->all();

                    // Fallback: from penawaran detail
                    if (empty($barangVendor)) {
                        $barangVendor = $proyek->penawaranAktif->penawaranDetail
                            ->filter(fn($d) => $d->barang && $d->barang->id_vendor == $vendor->id_vendor)
                            ->pluck('barang.nama_barang')
                            ->filter()
                            ->unique()
                            ->values()
                            ->all();
                    }

                    return [
                        'vendor'                 => $vendor->toArray(),
                        'total_vendor'           => $totalVendor,
                        'total_dibayar_approved' => $totalDibayarApproved,
                        'status_lunas'           => $totalVendor <= $totalDibayarApproved,
                        'has_approved_payment'   => $hasPembayaranApproved,
                        'pengiriman'             => $pengirimanVendor->toArray(),
                        'ready_to_ship'          => $hasPembayaranApproved && $pengirimanVendor->isEmpty(),
                        'barang_vendor'          => $barangVendor,
                    ];
                })
                ->filter(fn($v) => $v['has_approved_payment'])
                ->values()
                ->toArray();

                return $proyek;
            })
            ->filter(fn($p) => count($p->vendors_ready) > 0)
            ->values();

        // Flatten to per-vendor rows and paginate
        $currentPageReady = (int) request()->get('ready_page', 1);
        $perPageReady     = 10;
        $readyItems = collect($proyekReady)->flatMap(function ($proyek) {
            return collect($proyek->vendors_ready)
                ->filter(fn($v) => $v['ready_to_ship'])
                ->map(fn($v) => (object) array_merge($v, ['proyek' => $proyek]));
        });

        $proyekReadyPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $readyItems->slice(($currentPageReady - 1) * $perPageReady, $perPageReady),
            $readyItems->count(),
            $perPageReady,
            $currentPageReady,
            ['path' => request()->url(), 'pageName' => 'ready_page']
        );

        // ----------------------------------------------------------------
        // TAB: Dalam Proses
        // ----------------------------------------------------------------
        $pengirimanBerjalanQuery = Pengiriman::with([
                'penawaran.proyek.kalkulasiHps.barang',
                'vendor',
            ])
            ->whereIn('status_verifikasi', ['Pending', 'Dalam_Proses']);

        if ($search) {
            $pengirimanBerjalanQuery->where(function ($q) use ($search) {
                $q->whereHas('penawaran.proyek', function ($sq) use ($search) {
                    $sq->where('instansi', 'like', "%{$search}%")
                       ->orWhere('kode_proyek', 'like', "%{$search}%");
                })->orWhereHas('vendor', fn($sq) => $sq->where('nama_vendor', 'like', "%{$search}%"));
            });
        }

        $pengirimanBerjalan = $pengirimanBerjalanQuery
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'proses_page');

        // Attach barang_list in-memory (no extra queries — kalkulasiHps already eager-loaded)
        $pengirimanBerjalan->getCollection()->transform(
            fn($p) => $this->attachBarangList($p)
        );

        // ----------------------------------------------------------------
        // TAB: Selesai
        // ----------------------------------------------------------------
        $pengirimanSelesaiQuery = Pengiriman::with([
                'penawaran.proyek.kalkulasiHps.barang',
                'vendor',
                'verifiedBy',
            ])
            ->where(function ($q) {
                $q->where('status_verifikasi', 'Verified')
                  ->whereHas('penawaran.proyek', fn($sq) => $sq->where('status', 'Selesai'));
            })
            ->orWhere('status_verifikasi', 'Sampai_Tujuan');

        if ($search) {
            $pengirimanSelesaiQuery->where(function ($q) use ($search) {
                $q->whereHas('penawaran.proyek', function ($sq) use ($search) {
                    $sq->where('instansi', 'like', "%{$search}%")
                       ->orWhere('kode_proyek', 'like', "%{$search}%");
                })->orWhereHas('vendor', fn($sq) => $sq->where('nama_vendor', 'like', "%{$search}%"));
            });
        }

        $pengirimanSelesai = $pengirimanSelesaiQuery
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'selesai_page');

        $pengirimanSelesai->getCollection()->transform(
            fn($p) => $this->attachBarangList($p)
        );

        // ----------------------------------------------------------------
        // TAB: Pembuatan Surat Pengiriman (tampilan saja)
        // ----------------------------------------------------------------
        $suratPengirimanQuery = Proyek::with([
                'adminPurchasing',
            ])
            ->whereIn('status', ['Pengiriman', 'Selesai']);

        if ($search) {
            $suratPengirimanQuery->where(function ($q) use ($search) {
                $q->where('instansi', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%")
                  ->orWhere('kab_kota', 'like', "%{$search}%");
            });
        }

        $suratPengirimanList = $suratPengirimanQuery
            ->orderBy('id_proyek', 'asc')
            ->paginate(10, ['*'], 'surat_page');

        return view('pages.purchasing.pengiriman', compact(
            'proyekReady',
            'proyekReadyPaginated',
            'pengirimanBerjalan',
            'pengirimanSelesai',
            'suratPengirimanList',
            'search',
            'activeTab'
        ));
    }

    /**
     * Attach barang_list to a Pengiriman model using already-eager-loaded kalkulasiHps.
     * Zero extra queries.
     */
    private function attachBarangList(Pengiriman $pengiriman): Pengiriman
    {
        if (!empty($pengiriman->barang_list)) {
            return $pengiriman;
        }

        $barangList = [];
        if ($pengiriman->penawaran?->proyek?->kalkulasiHps) {
            $barangList = $pengiriman->penawaran->proyek->kalkulasiHps
                ->filter(fn($k) => $k->id_vendor == $pengiriman->id_vendor && $k->barang)
                ->pluck('barang.nama_barang')
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        $pengiriman->barang_list = $barangList;
        return $pengiriman;
    }

    /**
     * Halaman pembuatan Surat Jalan & Tanda Terima (tampilan saja).
     */
    public function surat($proyekId)
    {
        $proyek = Proyek::with(['adminPurchasing'])->findOrFail($proyekId);

        $suratTandaTerima = SuratTandaTerima::where('id_proyek', $proyek->id_proyek)->first();

        return view('pages.purchasing.pengiriman-components.pembuatan-surat', compact('proyek', 'suratTandaTerima'));
    }

    /**
     * Store a newly created shipping record
     */
    public function store(Request $request)
    {
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk membuat pengiriman. Hanya admin purchasing/superadmin yang dapat melakukan aksi ini.');
        }

        $request->validate([
            'id_penawaran' => 'required|exists:penawaran,id_penawaran',
            'id_vendor' => 'required|exists:vendor,id_vendor',
            'no_surat_jalan' => 'required|string|max:50',
            'tanggal_kirim' => 'required|date',
            'alamat_kirim' => 'nullable|string',
            'file_surat_jalan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $penawaran = Penawaran::with(['proyek.pembayaran'])->findOrFail($request->id_penawaran);
        // Check if current user is assigned to this project, unless superadmin
        if ($user->role === 'admin_purchasing' && $penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk proyek ini. Hanya admin purchasing yang ditugaskan/superadmin yang dapat membuat pengiriman untuk proyek ini.');
        }
        
        $totalVendor = $penawaran->penawaranDetail
            ->where('barang.id_vendor', $request->id_vendor)
            ->sum('total_harga_hpp');

        $totalDibayar = $penawaran->proyek->pembayaran
            ->where('id_vendor', $request->id_vendor)
            ->where('status_verifikasi', 'Approved')
            ->sum('nominal_bayar');

        if ($totalDibayar <= 0) {
            return back()->with('error', 'Vendor belum memiliki pembayaran yang di-approve, tidak bisa membuat pengiriman');
        }

        // Cek apakah sudah ada pengiriman untuk vendor ini
        $existingPengiriman = Pengiriman::where('id_penawaran', $request->id_penawaran)
            ->where('id_vendor', $request->id_vendor)
            ->exists();

        if ($existingPengiriman) {
            return back()->with('error', 'Pengiriman untuk vendor ini sudah dibuat');
        }

        DB::beginTransaction();
        try {
            // Upload file surat jalan jika ada
            $filePath = null;
            if ($request->hasFile('file_surat_jalan')) {
                $file = $request->file('file_surat_jalan');
                $fileName = time() . '_surat_jalan_' . $file->getClientOriginalName();
                $file->storeAs('pengiriman/surat_jalan', $fileName, 'public');
                $filePath = $fileName; // Simpan hanya nama file
            }

            // Buat pengiriman
            $pengiriman = Pengiriman::create([
                'id_penawaran' => $request->id_penawaran,
                'id_vendor' => $request->id_vendor,
                'no_surat_jalan' => $request->no_surat_jalan,
                'tanggal_kirim' => $request->tanggal_kirim,
                'alamat_kirim' => $request->alamat_kirim,
                'file_surat_jalan' => $filePath,
                'status_verifikasi' => 'Pending'
            ]);

            // Update status proyek berdasarkan kondisi vendor
            $this->updateProjectStatusOnShipping($penawaran->proyek);

            DB::commit();

            return redirect()->route('purchasing.pengiriman')
                ->with('success', 'Pengiriman berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($filePath) {
                Storage::disk('public')->delete('pengiriman/surat_jalan/' . $filePath);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat membuat pengiriman');
        }
    }

    /**
     * Show the form for editing pengiriman
     */
    public function edit($id)
    {
        Log::info('PengirimanController::edit called', ['id' => $id, 'user' => Auth::id()]);
        
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            Log::warning('Access denied for pengiriman edit', ['user_role' => $user->role, 'user_id' => $user->id_user]);
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit pengiriman'
            ], 403);
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek', 'vendor'])->findOrFail($id);

        // Additional check: Only admin_purchasing assigned to the project or superadmin can edit
        if ($user->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat mengedit pengiriman untuk proyek yang ditugaskan kepada Anda'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $pengiriman
        ]);
    }

    /**
     * Update pengiriman data
     */
    public function update(Request $request, $id)
    {
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengupdate pengiriman'
            ], 403);
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek', 'vendor'])->findOrFail($id);

        // Additional check: Only admin_purchasing assigned to the project or superadmin can update
        if ($user->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya dapat mengupdate pengiriman untuk proyek yang ditugaskan kepada Anda'
            ], 403);
        }

        // Validate input
        $request->validate([
            'no_surat_jalan' => 'required|string|max:255|unique:pengiriman,no_surat_jalan,' . $id . ',id_pengiriman',
            'tanggal_kirim' => 'required|date',
            'alamat_kirim' => 'required|string',
            'file_surat_jalan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        DB::beginTransaction();

        try {
            $updateData = [
                'no_surat_jalan' => $request->no_surat_jalan,
                'tanggal_kirim' => $request->tanggal_kirim,
                'alamat_kirim' => $request->alamat_kirim
            ];

            // Handle file surat jalan update
            if ($request->hasFile('file_surat_jalan')) {
                // Delete old file if exists
                if ($pengiriman->file_surat_jalan) {
                    Storage::disk('public')->delete('pengiriman/surat_jalan/' . $pengiriman->file_surat_jalan);
                }

                // Upload new file
                $file = $request->file('file_surat_jalan');
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
                $file->storeAs('pengiriman/surat_jalan', $fileName, 'public');
                $updateData['file_surat_jalan'] = $fileName;
            }

            $pengiriman->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data pengiriman berhasil diperbarui',
                'data' => $pengiriman->fresh(['penawaran.proyek', 'vendor'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update dokumentasi pengiriman
     */
    public function updateDokumentasi(Request $request, $id)
    {
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk mengupdate dokumentasi pengiriman. Hanya admin purchasing/superadmin yang dapat melakukan aksi ini.');
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek'])->findOrFail($id);
        // Check if current user is assigned to this project, unless superadmin
        if ($user->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk proyek ini. Hanya admin purchasing yang ditugaskan/superadmin yang dapat mengupdate dokumentasi pengiriman untuk proyek ini.');
        }

        $request->validate([
            'foto_berangkat' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto_perjalanan' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto_sampai' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'tanda_terima' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $updateData = [];

            // Handle file uploads
            $fileFields = ['foto_berangkat', 'foto_perjalanan', 'foto_sampai', 'tanda_terima'];
            
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Hapus file lama jika ada
                    if ($pengiriman->$field) {
                        Storage::disk('public')->delete('pengiriman/dokumentasi/' . $pengiriman->$field);
                    }
                    
                    // Upload file baru
                    $file = $request->file($field);
                    $fileName = time() . '_' . $field . '_' . $file->getClientOriginalName();
                    $file->storeAs('pengiriman/dokumentasi', $fileName, 'public');
                    $updateData[$field] = $fileName;
                }
            }

            // Simpan checklist (tanpa migration — disimpan sebagai JSON prefix di catatan_verifikasi)
            // Hanya update checklist jika status belum Verified (agar catatan superadmin tidak tertimpa)
            if (!in_array($pengiriman->status_verifikasi, ['Verified', 'Rejected'])) {
                // Baca checklist lama jika ada
                $oldChecklist = $pengiriman->checklist_data;

                $newChecklist = [
                    'berangkat'  => $request->boolean('check_berangkat')  || !empty($oldChecklist['berangkat']),
                    'perjalanan' => $request->boolean('check_perjalanan') || !empty($oldChecklist['perjalanan']),
                    // foto_sampai & tanda_terima wajib file — tidak bisa via checkbox
                    'sampai'     => !empty($oldChecklist['sampai']),
                    'terima'     => !empty($oldChecklist['terima']),
                ];

                $updateData['catatan_verifikasi'] = '[CHECKLIST]' . json_encode($newChecklist);
            }

            // Update status berdasarkan kelengkapan dokumentasi
            $pengiriman->update($updateData);
            $pengiriman->refresh();

            // Auto update status berdasarkan file + checklist
            $cl = $pengiriman->checklist_data;
            $adaBerangkat  = $pengiriman->foto_berangkat  || !empty($cl['berangkat']);
            $adaPerjalanan = $pengiriman->foto_perjalanan || !empty($cl['perjalanan']);
            // foto_sampai & tanda_terima wajib file — checklist tidak dihitung
            $adaSampai     = (bool) $pengiriman->foto_sampai;
            $adaTerima     = (bool) $pengiriman->tanda_terima;

            if ($adaBerangkat && $adaPerjalanan && $adaSampai && $adaTerima) {
                $pengiriman->update(['status_verifikasi' => 'Sampai_Tujuan']);
            } elseif ($adaBerangkat) {
                $pengiriman->update(['status_verifikasi' => 'Dalam_Proses']);
            }

            DB::commit();

            return redirect()->route('purchasing.pengiriman')
                ->with('success', 'Dokumentasi pengiriman berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat mengupdate dokumentasi: ' . $e->getMessage());
        }
    }

    /**
     * Verify shipping completion (superadmin only)
     */
    public function verify(Request $request, $id)
    {
        // Role-based access control: Only allow superadmin
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk memverifikasi pengiriman. Hanya superadmin yang dapat melakukan aksi ini.');
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek'])->findOrFail($id);

        // Pastikan dokumentasi lengkap
        if (!$pengiriman->dokumentasi_lengkap) {
            return back()->with('error', 'Dokumentasi pengiriman belum lengkap');
        }

        DB::beginTransaction();
        try {
            // Update status pengiriman
            $pengiriman->update([
                'status_verifikasi' => 'Verified',
                'verified_by' => Auth::user()->id_user,
                'verified_at' => now(),
                'catatan_verifikasi' => $request->catatan_verifikasi
            ]);

            // Cek apakah semua vendor dalam proyek ini sudah selesai pengiriman
            $this->checkAndUpdateProjectStatus($pengiriman->penawaran->proyek);

            DB::commit();

            return redirect()->route('purchasing.pengiriman')
                ->with('success', 'Pengiriman berhasil diverifikasi');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat verifikasi pengiriman');
        }
    }

    /**
     * Check and update project status based on all vendor shipping status
     */
    private function checkAndUpdateProjectStatus($proyek)
    {
        // Ambil semua vendor yang terlibat dalam proyek
        $allVendorIds = $proyek->penawaranAktif->penawaranDetail
            ->pluck('barang.id_vendor')
            ->unique()
            ->filter();

        // Cek berapa vendor yang sudah verified pengirimannya
        $verifiedVendorIds = Pengiriman::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
            ->where('status_verifikasi', 'Verified')
            ->pluck('id_vendor')
            ->unique();

        // Jika semua vendor sudah verified, update status proyek ke Selesai
        if ($allVendorIds->count() === $verifiedVendorIds->count() && 
            $allVendorIds->diff($verifiedVendorIds)->isEmpty()) {
            $proyek->update(['status' => 'Selesai']);
        }
    }

    /**
     * Update project status when vendor starts shipping
     */
    private function updateProjectStatusOnShipping($proyek)
    {
        // Jika status proyek masih Pembayaran, dan ada vendor yang sudah mulai pengiriman
        // maka update status ke Pengiriman
        if ($proyek->status === 'Pembayaran') {
            // Cek apakah ada vendor yang sudah lunas dan sudah mulai pengiriman
            $hasShippingVendor = Pengiriman::where('id_penawaran', $proyek->penawaranAktif->id_penawaran)
                ->exists();
                
            if ($hasShippingVendor) {
                $proyek->update(['status' => 'Pengiriman']);
            }
        }
    }

    /**
     * Get detail with files for modal
     */
    public function getDetailWithFiles($id)
    {
        $pengiriman = Pengiriman::with(['penawaran.proyek', 'vendor', 'verifiedBy'])
            ->findOrFail($id);

        return response()->json([
            'pengiriman' => $pengiriman,
            'files' => [
                'file_surat_jalan' => $pengiriman->file_surat_jalan ? Storage::url('pengiriman/surat_jalan/' . $pengiriman->file_surat_jalan) : null,
                'foto_berangkat' => $pengiriman->foto_berangkat ? Storage::url('pengiriman/dokumentasi/' . $pengiriman->foto_berangkat) : null,
                'foto_perjalanan' => $pengiriman->foto_perjalanan ? Storage::url('pengiriman/dokumentasi/' . $pengiriman->foto_perjalanan) : null,
                'foto_sampai' => $pengiriman->foto_sampai ? Storage::url('pengiriman/dokumentasi/' . $pengiriman->foto_sampai) : null,
                'tanda_terima' => $pengiriman->tanda_terima ? Storage::url('pengiriman/dokumentasi/' . $pengiriman->tanda_terima) : null,
            ]
        ]);
    }

    /**
     * Delete shipping record
     */
    public function destroy($id)
    {
        // Role-based access control: Allow admin_purchasing and superadmin
        $user = Auth::user();
        if (!in_array($user->role, ['admin_purchasing', 'superadmin'])) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk menghapus pengiriman. Hanya admin purchasing/superadmin yang dapat melakukan aksi ini.');
        }

        $pengiriman = Pengiriman::with(['penawaran.proyek'])->findOrFail($id);
        // Check if current user is assigned to this project, unless superadmin
        if ($user->role === 'admin_purchasing' && $pengiriman->penawaran->proyek->id_admin_purchasing != $user->id_user) {
            return redirect()->route('purchasing.pengiriman')
                ->with('error', 'Tidak memiliki akses untuk proyek ini. Hanya admin purchasing yang ditugaskan/superadmin yang dapat menghapus pengiriman untuk proyek ini.');
        }

        // Hanya bisa hapus jika status masih Pending
        if ($pengiriman->status_verifikasi !== 'Pending') {
            return back()->with('error', 'Pengiriman yang sudah berjalan tidak dapat dihapus');
        }

        DB::beginTransaction();
        try {
            // Hapus file-file yang terkait
            $files = [
                ['file' => $pengiriman->file_surat_jalan, 'folder' => 'pengiriman/surat_jalan'],
                ['file' => $pengiriman->foto_berangkat, 'folder' => 'pengiriman/dokumentasi'],
                ['file' => $pengiriman->foto_perjalanan, 'folder' => 'pengiriman/dokumentasi'],
                ['file' => $pengiriman->foto_sampai, 'folder' => 'pengiriman/dokumentasi'],
                ['file' => $pengiriman->tanda_terima, 'folder' => 'pengiriman/dokumentasi']
            ];

            foreach ($files as $fileData) {
                if ($fileData['file']) {
                    $fullPath = $fileData['folder'] . '/' . $fileData['file'];
                    if (Storage::disk('public')->exists($fullPath)) {
                        Storage::disk('public')->delete($fullPath);
                    }
                }
            }

            $pengiriman->delete();

            DB::commit();

            return redirect()->route('purchasing.pengiriman')
                ->with('success', 'Pengiriman berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengiriman');
        }
    }

    /**
     * Clean up orphaned files
     */
    public function cleanupOrphanedFiles()
    {
        // Role-based access control: Only allow superadmin for maintenance
        if (Auth::user()->role !== 'superadmin') {
            return response()->json([
                'error' => 'Tidak memiliki akses untuk maintenance. Hanya superadmin yang dapat melakukan aksi ini.'
            ], 403);
        }

        $folders = ['pengiriman/surat_jalan', 'pengiriman/dokumentasi'];
        $deletedCount = 0;

        foreach ($folders as $folder) {
            $allFiles = Storage::disk('public')->files($folder);
            
            // Ambil nama file saja (tanpa path)
            $allFileNames = array_map(function($file) {
                return basename($file);
            }, $allFiles);

            $usedFiles = Pengiriman::whereNotNull('file_surat_jalan')
                ->orWhereNotNull('foto_berangkat')
                ->orWhereNotNull('foto_perjalanan')
                ->orWhereNotNull('foto_sampai')
                ->orWhereNotNull('tanda_terima')
                ->get()
                ->flatMap(function ($pengiriman) {
                    return collect([
                        $pengiriman->file_surat_jalan,
                        $pengiriman->foto_berangkat,
                        $pengiriman->foto_perjalanan,
                        $pengiriman->foto_sampai,
                        $pengiriman->tanda_terima
                    ])->filter();
                })
                ->toArray();

            $orphanedFileNames = array_diff($allFileNames, $usedFiles);

            foreach ($orphanedFileNames as $fileName) {
                try {
                    Storage::disk('public')->delete($folder . '/' . $fileName);
                    $deletedCount++;
                } catch (\Exception $e) {
                    // Silent fail
                }
            }
        }

        return response()->json([
            'message' => "Cleanup completed. {$deletedCount} orphaned files deleted.",
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Preview PDF Tanda Terima (inline).
     */
    public function previewTandaTerima(Request $request, $proyekId)
    {
        $payload = $this->buildTandaTerimaPayload($request, $proyekId, true);
        $pdf     = Pdf::loadView('pages.files.surat-tandaterima', $payload);
        $content = $this->mergeTandaTerimaWithLampiran($pdf->output(), $payload['suratDb'] ?? null);

        return response($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="tanda-terima-preview.pdf"',
        ]);
    }

    /**
     * Download PDF Tanda Terima (attachment).
     */
    public function downloadTandaTerima(Request $request, $proyekId)
    {
        $payload  = $this->buildTandaTerimaPayload($request, $proyekId, true);
        $pdf      = Pdf::loadView('pages.files.surat-tandaterima', $payload);
        $content  = $this->mergeTandaTerimaWithLampiran($pdf->output(), $payload['suratDb'] ?? null);
        $filename = 'tanda-terima-' . ($payload['proyek']->kode_proyek ?? $proyekId) . '.pdf';

        return response($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Simpan metadata Tanda Terima + upload lampiran PDF.
     * (dipakai oleh halaman pembuatan surat nanti)
     */
    public function storeTandaTerima(Request $request, $proyekId)
    {
        $validated = $request->validate([
            'nomor_surat'     => 'nullable|string|max:255',
            'tempat_surat'    => 'nullable|string|max:255',
            'tanggal_surat'   => 'nullable|date',
            'id_penawaran'    => 'nullable|integer',
            'lampiran_pdfs'   => 'nullable',
            'lampiran_pdfs.*' => 'file|mimes:pdf|max:10240',
        ]);

        $proyek = Proyek::findOrFail($proyekId);

        $dbFields = collect($validated)->except(['lampiran_pdfs'])->toArray();

        $surat = SuratTandaTerima::updateOrCreate(
            ['id_proyek' => $proyek->id_proyek],
            array_merge($dbFields, ['id_proyek' => $proyek->id_proyek])
        );

        if ($request->hasFile('lampiran_pdfs')) {
            // Baca raw JSON langsung dari DB
            $rawRow   = DB::table('surat_tanda_terima')->where('id_surat_tanda_terima', $surat->id_surat_tanda_terima)->value('lampiran_files');
            $existing = [];
            if ($rawRow) {
                $decoded  = json_decode($rawRow, true);
                $existing = is_array($decoded) ? $decoded : [];
            }

            foreach ($request->file('lampiran_pdfs') as $file) {
                if (!$file || !$file->isValid()) continue;

                $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
                $filename = time() . '_' . uniqid() . '_' . $safeName;
                $path     = $file->storeAs('tanda-terima/lampiran', $filename, 'public');

                $existing[] = [
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'uploaded_at'   => now()->toDateTimeString(),
                    'size'          => $file->getSize(),
                ];
            }

            DB::table('surat_tanda_terima')
                ->where('id_surat_tanda_terima', $surat->id_surat_tanda_terima)
                ->update(['lampiran_files' => json_encode(array_values($existing))]);
        }

        $surat = SuratTandaTerima::find($surat->id_surat_tanda_terima);

        // Jika dipanggil via form biasa, lakukan redirect (bukan JSON)
        if (!$request->expectsJson()) {
            return redirect()
                ->route('purchasing.pengiriman.surat', ['proyekId' => $proyek->id_proyek])
                ->with('success', 'Tanda terima berhasil disimpan.');
        }

        return response()->json([
            'success'        => true,
            'message'        => 'Tanda terima berhasil disimpan.',
            'data'           => $surat,
            'lampiran_files' => $surat->lampiran_files_list,
        ]);
    }

    /**
     * Hapus 1 lampiran dari surat tanda terima.
     */
    public function deleteTandaTerimaLampiran(Request $request, $proyekId)
    {
        $request->validate(['path' => 'required|string']);

        $surat = SuratTandaTerima::where('id_proyek', $proyekId)->firstOrFail();
        $path  = $request->input('path');

        $rawRow = DB::table('surat_tanda_terima')->where('id_surat_tanda_terima', $surat->id_surat_tanda_terima)->value('lampiran_files');
        $files  = [];
        if ($rawRow) {
            $decoded = json_decode($rawRow, true);
            $files   = is_array($decoded) ? $decoded : [];
        }

        $newFiles = [];
        foreach ($files as $f) {
            if (($f['path'] ?? null) === $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
                continue;
            }
            $newFiles[] = $f;
        }

        DB::table('surat_tanda_terima')
            ->where('id_surat_tanda_terima', $surat->id_surat_tanda_terima)
            ->update(['lampiran_files' => json_encode(array_values($newFiles))]);

        $surat = SuratTandaTerima::find($surat->id_surat_tanda_terima);

        return response()->json([
            'success'        => true,
            'message'        => 'Lampiran berhasil dihapus.',
            'lampiran_files' => $surat->lampiran_files_list,
        ]);
    }

    private function buildTandaTerimaPayload(Request $request, $proyekId, bool $preferDb = false): array
    {
        $proyek = Proyek::with(['adminPurchasing', 'penawaranAktif.penawaranDetail'])->findOrFail($proyekId);

        $penawaran = $proyek->penawaranAktif;
        if (!$penawaran) {
            $penawaran = Penawaran::where('id_proyek', $proyek->id_proyek)->orderBy('id_penawaran', 'desc')->first();
        }

        $details = $penawaran
            ? PenawaranDetail::with('barang')->where('id_penawaran', $penawaran->id_penawaran)->get()
            : collect();

        $suratDb = $preferDb
            ? SuratTandaTerima::where('id_proyek', $proyek->id_proyek)->first()
            : null;

        $get = fn (string $k, $fallback = null) => $request->query($k, $fallback);

        $nomor   = $get('nomor_surat',   $suratDb?->nomor_surat);
        $tempat  = $get('tempat_surat',  $suratDb?->tempat_surat);

        $tanggalDb = $suratDb?->tanggal_surat;
        $tanggalDbStr = null;
        if ($tanggalDb instanceof \DateTimeInterface) {
            $tanggalDbStr = $tanggalDb->format('Y-m-d');
        } elseif (is_string($tanggalDb) && $tanggalDb !== '') {
            $tanggalDbStr = $tanggalDb;
        }

        $tanggal = $get('tanggal_surat', $tanggalDbStr);

        $items = $details->map(function ($d) {
            return [
                'nama_barang' => $d->barang?->nama_barang ?? $d->nama_barang,
                'satuan'      => $d->satuan,
                'qty'         => (int) $d->qty,
            ];
        })->values();

        return [
            'proyek'  => $proyek,
            'penawaran' => $penawaran,
            'items'   => $items,
            'surat'   => [
                'nomor_surat'   => $nomor,
                'tempat_surat'  => $tempat,
                'tanggal_surat' => $tanggal,
                'penerima'      => $proyek->instansi,
                'pengirim'      => $proyek->adminPurchasing?->name ?? $proyek->adminPurchasing?->nama,
            ],
            'suratDb' => $suratDb,
        ];
    }

    private function mergeTandaTerimaWithLampiran(string $ttPdfContent, $suratDb = null): string
    {
        $lampiranList = [];

        try {
            if ($suratDb) {
                $rawRow = DB::table('surat_tanda_terima')
                    ->where('id_surat_tanda_terima', $suratDb->id_surat_tanda_terima)
                    ->value('lampiran_files');

                if ($rawRow) {
                    $decoded      = json_decode($rawRow, true);
                    $lampiranList = is_array($decoded) ? $decoded : [];
                }
            }
        } catch (\Throwable $e) {
            // silent
        }

        if (empty($lampiranList)) {
            return $ttPdfContent;
        }

        try {
            $merger = new Merger();
            $merger->addRaw($ttPdfContent);

            foreach ($lampiranList as $f) {
                $path = $f['path'] ?? null;
                if (!$path) continue;

                $absolutePath = storage_path('app/public/' . ltrim($path, '/\\'));
                if (!file_exists($absolutePath)) continue;

                $raw = file_get_contents($absolutePath);
                if (!$raw || strlen($raw) === 0) continue;

                $merger->addRaw($raw);
            }

            return $merger->merge();
        } catch (\Throwable $e) {
            return $ttPdfContent;
        }
    }
}
