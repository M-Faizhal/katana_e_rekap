<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenagihanDinas;
use App\Models\BuktiPembayaran;
use App\Models\Proyek;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PenagihanDinasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // --- Belum Bayar tab: batch-optimized, no N+1 ---

        // Step 1: Load all ACC penawaran IDs that already have a penagihan (batch, single query)
        $penawaranSudahDitagihIds = PenagihanDinas::withTrashed(false)
            ->pluck('penawaran_id')
            ->flip(); // use as a set for O(1) lookup

        // Step 2: Load all pengiriman with status "Sampai_Tujuan", keyed by id_penawaran
        //   Result: [ id_penawaran => Collection of vendor_ids that have Sampai_Tujuan ]
        $pengirimanSampaiRaw = \App\Models\Pengiriman::where('status_verifikasi', 'Sampai_Tujuan')
            ->select('id_penawaran', 'id_vendor')
            ->get();
        // Build map: id_penawaran -> set of vendor IDs delivered
        $deliveredVendorsByPenawaran = [];
        foreach ($pengirimanSampaiRaw as $p) {
            $deliveredVendorsByPenawaran[$p->id_penawaran][$p->id_vendor] = true;
        }

        // Step 3: Load all ACC proyek with their penawaran and vendor info (eager load)
        $proyekAccQuery = Proyek::with([
            'semuaPenawaran' => function ($query) {
                $query->where('status', 'ACC');
            },
            'semuaPenawaran.penawaranDetail.barang' => function ($query) {
                $query->select('id_barang', 'id_vendor');
            },
        ])->whereHas('semuaPenawaran', function ($query) {
            $query->where('status', 'ACC');
        });

        if ($search) {
            $proyekAccQuery->where(function ($q) use ($search) {
                $q->where('kode_proyek', 'like', '%' . $search . '%')
                  ->orWhere('instansi', 'like', '%' . $search . '%')
                  ->orWhereHas('proyekBarang', function ($pbq) use ($search) {
                      $pbq->where('nama_barang', 'like', '%' . $search . '%');
                  });
            });
        }

        $proyekAcc = $proyekAccQuery->get();

        // Step 4: In-memory filtering — no extra queries per penawaran/vendor
        $proyekBelumBayarCollection = collect();

        foreach ($proyekAcc as $proyek) {
            $hasUnpaidPenawaran = false;

            foreach ($proyek->semuaPenawaran as $penawaran) {
                $penawaranId = $penawaran->id_penawaran;

                // Skip already-invoiced penawaran (in-memory lookup)
                if (isset($penawaranSudahDitagihIds[$penawaranId])) {
                    continue;
                }

                // Collect vendor IDs from penawaran details (in-memory, already eager-loaded)
                $vendorIds = $penawaran->penawaranDetail
                    ->pluck('barang.id_vendor')
                    ->filter()
                    ->unique()
                    ->values();

                if ($vendorIds->isEmpty()) {
                    continue;
                }

                // Check all vendors delivered (in-memory lookup against pre-loaded map)
                $deliveredForThisPenawaran = $deliveredVendorsByPenawaran[$penawaranId] ?? [];
                $allDelivered = true;
                foreach ($vendorIds as $vendorId) {
                    if (!isset($deliveredForThisPenawaran[$vendorId])) {
                        $allDelivered = false;
                        break;
                    }
                }

                if ($allDelivered) {
                    $hasUnpaidPenawaran = true;
                    break;
                }
            }

            if ($hasUnpaidPenawaran) {
                $proyekBelumBayarCollection->push($proyek);
            }
        }

        // Pagination untuk proyek belum bayar
        $currentPage = request()->get('belum_bayar_page', 1);
        $perPage = 10;
        $currentPageItems = $proyekBelumBayarCollection->slice(($currentPage - 1) * $perPage, $perPage);
        $proyekBelumBayar = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $proyekBelumBayarCollection->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'belum_bayar_page',
            ]
        );
        $proyekBelumBayar->appends(request()->query());

        // Notifikasi jika ada proyek baru yang masuk daftar $proyekBelumBayar
        // (Snapshot disimpan di cache, agar tidak mengirim berulang-ulang)
        try {
            $cacheKey = 'notif:proyekBelumBayar:snapshot:v1';

            $prevIds = Cache::get($cacheKey, []);
            if (!is_array($prevIds)) {
                $prevIds = [];
            }

            $currentIds = $proyekBelumBayarCollection
                ->pluck('id_proyek')
                ->filter()
                ->values()
                ->all();

            $newIds = array_values(array_diff($currentIds, $prevIds));

            if (!empty($newIds)) {
                $notifService = app(NotificationService::class);

                foreach ($proyekBelumBayarCollection->whereIn('id_proyek', $newIds) as $proyek) {
                    $notifService->proyekBelumBayarBaru($proyek);
                }
            }

            // simpan snapshot terbaru (TTL 30 hari)
            Cache::put($cacheKey, $currentIds, now()->addDays(30));
        } catch (\Throwable $e) {
            Log::warning('Gagal membuat notifikasi proyekBelumBayar baru: ' . $e->getMessage());
        }

        /**
         * Pembuatan Invoice tab: semua proyek yang punya penawaran ACC
         * (lebih longgar daripada "Belum Bayar", tidak peduli sudah ditagih atau belum).
         */
        $proyekAccInvoiceQuery = Proyek::with([
            'semuaPenawaran' => function ($query) {
                $query->where('status', 'ACC');
            },
            // Ambil penagihan dinas (jika ada) untuk menampilkan status pembayaran di tab invoice
            'penagihanDinas' => function ($q) {
                $q->latest('id');
            },
        ])->whereHas('semuaPenawaran', function ($query) {
            $query->where('status', 'ACC');
        });

        if ($search) {
            $proyekAccInvoiceQuery->where(function ($q) use ($search) {
                $q->where('kode_proyek', 'like', '%' . $search . '%')
                  ->orWhere('instansi', 'like', '%' . $search . '%');
            });
        }

        $proyekAccInvoice = $proyekAccInvoiceQuery->orderByDesc('id_proyek')->paginate(10, ['*'], 'acc_invoice_page');

        // Pagination untuk DP with search
        $proyekDpQuery = PenagihanDinas::with(['proyek', 'penawaran', 'buktiPembayaran'])
            ->where('status_pembayaran', 'dp');

        if ($search) {
            $proyekDpQuery->where(function($q) use ($search) {
                $q->where('nomor_invoice', 'like', '%' . $search . '%')
                  ->orWhereHas('proyek', function($pq) use ($search) {
                      $pq->where('kode_proyek', 'like', '%' . $search . '%')
                         ->orWhere('instansi', 'like', '%' . $search . '%')
                         // Search by proyekBarang nama_barang via relationship
                         ->orWhereHas('proyekBarang', function($pbq) use ($search) {
                             $pbq->where('nama_barang', 'like', '%' . $search . '%');
                         });
                  });
            });
        }

        $proyekDp = $proyekDpQuery->paginate(10, ['*'], 'dp_page');

        // Pagination untuk Lunas with search
        $proyekLunasQuery = PenagihanDinas::with(['proyek', 'penawaran', 'buktiPembayaran'])
            ->where('status_pembayaran', 'lunas');

        if ($search) {
            $proyekLunasQuery->where(function($q) use ($search) {
                $q->where('nomor_invoice', 'like', '%' . $search . '%')
                  ->orWhereHas('proyek', function($pq) use ($search) {
                      $pq->where('kode_proyek', 'like', '%' . $search . '%')
                         ->orWhere('instansi', 'like', '%' . $search . '%')
                         // Search by proyekBarang nama_barang via relationship
                         ->orWhereHas('proyekBarang', function($pbq) use ($search) {
                             $pbq->where('nama_barang', 'like', '%' . $search . '%');
                         });
                  });
            });
        }

        $proyekLunas = $proyekLunasQuery->paginate(10, ['*'], 'lunas_page');

        return view('pages.keuangan.penagihan', compact(
            'proyekBelumBayar', 
            'proyekDp', 
            'proyekLunas',
            'proyekAccInvoice'
        ));
    }

    public function create($proyekId)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat membuat penagihan.');
        }

        $proyek = Proyek::with(['semuaPenawaran' => function($query) {
            $query->where('status', 'ACC');
        }, 'semuaPenawaran.penawaranDetail'])->where('id_proyek', $proyekId)->firstOrFail();
        
        $penawaranAcc = $proyek->semuaPenawaran;
        
        if ($penawaranAcc->isEmpty()) {
            return redirect()->back()->with('error', 'Proyek belum memiliki penawaran yang di ACC.');
        }

        // Ambil penawaran pertama untuk ditampilkan (atau bisa dibuat pilihan)
        $penawaran = $penawaranAcc->first();

        // Hitung total dari detail penawaran
        $totalHarga = $penawaran->penawaranDetail->sum('subtotal');

        return view('pages.keuangan.penagihan-create', compact('proyek', 'penawaran', 'penawaranAcc', 'totalHarga'));
    }

    public function store(Request $request)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat membuat penagihan.');
        }

        $request->validate([
            'proyek_id' => 'required|exists:proyek,id_proyek',
            'penawaran_id' => 'required|exists:penawaran,id_penawaran',
            'nomor_invoice' => 'required|string|unique:penagihan_dinas,nomor_invoice',
            'total_harga' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:dp,lunas',
            'persentase_dp' => 'required_if:status_pembayaran,dp|nullable|numeric|min:0|max:100',
            'tanggal_jatuh_tempo' => 'required|date',
            'berita_acara_serah_terima' => 'nullable|file|mimes:pdf|max:2048',
            'invoice' => 'nullable|file|mimes:pdf|max:2048',
            'pnbp' => 'nullable|file|mimes:pdf|max:2048',
            'faktur_pajak' => 'nullable|file|mimes:pdf|max:2048',
            'surat_lainnya' => 'nullable|file|mimes:pdf|max:2048',
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Handle file uploads untuk dokumen
            $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
            $uploadedDokumen = [];

            foreach ($dokumenFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('penagihan-dinas/dokumen', $filename, 'public');
                    $uploadedDokumen[$field] = $filename;
                }
            }

            // Hitung nilai DP
            $totalHarga = $request->total_harga;
            $jumlahDp = 0;
            $persentaseDp = $request->persentase_dp;

            if ($request->status_pembayaran === 'dp') {
                $jumlahDp = ($persentaseDp / 100) * $totalHarga;
            }

            // Buat penagihan dinas
            $penagihanDinas = PenagihanDinas::create([
                'proyek_id' => $request->proyek_id,
                'penawaran_id' => $request->penawaran_id,
                'nomor_invoice' => $request->nomor_invoice,
                'total_harga' => $totalHarga,
                'status_pembayaran' => $request->status_pembayaran,
                'persentase_dp' => $persentaseDp,
                'jumlah_dp' => $jumlahDp,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'keterangan' => $request->keterangan,
            ] + $uploadedDokumen);

            // Handle bukti pembayaran
            $buktiPembayaranFile = $request->file('bukti_pembayaran');
            $buktiFilename = time() . '_bukti_pembayaran.' . $buktiPembayaranFile->getClientOriginalExtension();
            $buktiPath = $buktiPembayaranFile->storeAs('penagihan-dinas/bukti-pembayaran', $buktiFilename, 'public');

            // Tentukan jenis pembayaran dan jumlah bayar
            $jenisPembayaran = $request->status_pembayaran;
            $jumlahBayar = ($jenisPembayaran === 'dp') ? $jumlahDp : $totalHarga;

            // Buat bukti pembayaran
            BuktiPembayaran::create([
                'penagihan_dinas_id' => $penagihanDinas->id,
                'jenis_pembayaran' => $jenisPembayaran,
                'jumlah_bayar' => $jumlahBayar,
                'tanggal_bayar' => $request->tanggal_bayar,
                'bukti_pembayaran' => $buktiFilename,
                'keterangan' => $request->keterangan_pembayaran
            ]);

            DB::commit();

            return redirect()->route('keuangan.penagihan')
                ->with('success', 'Penagihan dinas berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang sudah diupload jika terjadi error
            foreach ($uploadedDokumen as $filename) {
                Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $filename);
            }
            
            if (isset($buktiFilename)) {
                Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiFilename);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $penagihanDinas = PenagihanDinas::with(['proyek', 'penawaran.penawaranDetail', 'buktiPembayaran'])
            ->findOrFail($id);

        return view('pages.keuangan.penagihan-detail', compact('penagihanDinas'));
    }

    public function edit($id)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat mengedit penagihan.');
        }

        $penagihanDinas = PenagihanDinas::with([
            'proyek',
            'penawaran.penawaranDetail.barang',
            'buktiPembayaran',
        ])->findOrFail($id);

        return view('pages.keuangan.penagihan-edit', compact('penagihanDinas'));
    }

    public function storeBuktiPembayaran(Request $request, $id)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak.');
        }

        $penagihanDinas = PenagihanDinas::with('buktiPembayaran')->findOrFail($id);

        // Strip thousand-separators
        $rawJumlah = str_replace(['.', ' '], '', $request->input('jumlah_bayar', ''));
        $rawJumlah = str_replace(',', '.', $rawJumlah);
        $request->merge(['jumlah_bayar' => $rawJumlah]);

        $request->validate([
            'jenis_pembayaran'  => 'required|in:dp,lunas,lainnya',
            'jumlah_bayar'      => 'required|numeric|min:0.01',
            'tanggal_bayar'     => 'required|date',
            'keterangan'        => 'nullable|string',
            'bukti_pembayaran'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $buktiFilename = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file          = $request->file('bukti_pembayaran');
                $buktiFilename = time() . '_bukti_baru.' . $file->getClientOriginalExtension();
                $file->storeAs('penagihan-dinas/bukti-pembayaran', $buktiFilename, 'public');
            }

            BuktiPembayaran::create([
                'penagihan_dinas_id' => $penagihanDinas->id,
                'jenis_pembayaran'   => $request->jenis_pembayaran,
                'jumlah_bayar'       => (float) $request->jumlah_bayar,
                'tanggal_bayar'      => $request->tanggal_bayar,
                'bukti_pembayaran'   => $buktiFilename,
                'keterangan'         => $request->keterangan,
            ]);

            // Auto-update status_pembayaran if jenis is 'lunas'
            if ($request->jenis_pembayaran === 'lunas') {
                $penagihanDinas->update(['status_pembayaran' => 'lunas']);
            }

            return redirect()->route('penagihan-dinas.edit', $id)
                ->with('success', 'Pembayaran berhasil ditambahkan.');

        } catch (\Exception $e) {
            if ($buktiFilename) {
                Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiFilename);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat mengedit penagihan.');
        }

        $penagihanDinas = PenagihanDinas::findOrFail($id);

        // Strip thousand-separators so validation accepts formatted numbers
        $rawTotalHarga = str_replace(['.', ' '], '', $request->input('total_harga', ''));
        $rawTotalHarga = str_replace(',', '.', $rawTotalHarga);
        $request->merge(['total_harga' => $rawTotalHarga]);

        $request->validate([
            'nomor_invoice' => 'required|string|unique:penagihan_dinas,nomor_invoice,' . $id,
            'total_harga' => 'required|numeric|min:0',
            'persentase_dp' => 'nullable|numeric|min:0|max:100',
            'tanggal_jatuh_tempo' => 'required|date',
            'berita_acara_serah_terima' => 'nullable|file|mimes:pdf|max:2048',
            'invoice' => 'nullable|file|mimes:pdf|max:2048',
            'pnbp' => 'nullable|file|mimes:pdf|max:2048',
            'faktur_pajak' => 'nullable|file|mimes:pdf|max:2048',
            'surat_lainnya' => 'nullable|file|mimes:pdf|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        try {
            // Handle file uploads untuk dokumen
            $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
            $uploadedDokumen = [];

            foreach ($dokumenFields as $field) {
                if ($request->hasFile($field)) {
                    // Hapus file lama jika ada
                    if ($penagihanDinas->$field) {
                        Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$field);
                    }

                    // Upload file baru
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('penagihan-dinas/dokumen', $filename, 'public');
                    $uploadedDokumen[$field] = $filename;
                }
            }

            // Hitung ulang jumlah_dp jika status DP dan total_harga / persentase berubah
            $totalHarga   = (float) $request->total_harga;
            $updateData   = [
                'nomor_invoice'      => $request->nomor_invoice,
                'total_harga'        => $totalHarga,
                'tanggal_jatuh_tempo'=> $request->tanggal_jatuh_tempo,
                'keterangan'         => $request->keterangan,
            ];

            if ($penagihanDinas->status_pembayaran === 'dp' && $request->filled('persentase_dp')) {
                $persentaseDp = (float) $request->persentase_dp;
                $updateData['persentase_dp'] = $persentaseDp;
                $updateData['jumlah_dp']     = round($persentaseDp / 100 * $totalHarga, 2);
            }

            // Update penagihan dinas
            $penagihanDinas->update($updateData + $uploadedDokumen);

            return redirect()->route('penagihan-dinas.show', $id)
                ->with('success', 'Penagihan dinas berhasil diperbarui.');

        } catch (\Exception $e) {
            // Hapus file yang sudah diupload jika terjadi error
            foreach ($uploadedDokumen as $filename) {
                Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $filename);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat menghapus penagihan.');
        }

        $penagihanDinas = PenagihanDinas::with('buktiPembayaran')->findOrFail($id);

        try {
            DB::beginTransaction();

            // Hapus semua file dokumen penagihan dinas
            $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
            
            foreach ($dokumenFields as $field) {
                if ($penagihanDinas->$field) {
                    Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$field);
                }
            }

            // Hapus semua file bukti pembayaran
            foreach ($penagihanDinas->buktiPembayaran as $bukti) {
                if ($bukti->bukti_pembayaran) {
                    Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $bukti->bukti_pembayaran);
                }
            }

            // Hapus semua bukti pembayaran (cascade delete)
            $penagihanDinas->buktiPembayaran()->delete();

            // Hapus penagihan dinas
            $penagihanDinas->delete();

            DB::commit();

            return redirect()->route('keuangan.penagihan')
                ->with('success', 'Penagihan dinas beserta semua file berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function deleteDokumen($id, $jenis)
    {
        $penagihanDinas = PenagihanDinas::findOrFail($id);

        // Validasi jenis dokumen
        $allowedTypes = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
        if (!in_array($jenis, $allowedTypes)) {
            return redirect()->back()->with('error', 'Jenis dokumen tidak valid.');
        }

        try {
            // Hapus file dari storage jika ada
            if ($penagihanDinas->$jenis) {
                Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$jenis);
                
                // Update database dengan null
                $penagihanDinas->update([$jenis => null]);
            }

            return redirect()->back()
                ->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus dokumen: ' . $e->getMessage());
        }
    }

    public function updateBuktiPembayaran(Request $request, $buktiId)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat mengedit bukti pembayaran.');
        }

        $buktiPembayaran = BuktiPembayaran::with('penagihanDinas')->findOrFail($buktiId);
        $penagihanDinas  = $buktiPembayaran->penagihanDinas;

        // Strip thousand-separators before validation
        $rawJumlah = str_replace(['.', ' '], '', $request->input('jumlah_bayar', ''));
        $rawJumlah = str_replace(',', '.', $rawJumlah); // handle decimal comma
        $request->merge(['jumlah_bayar' => $rawJumlah]);

        $request->validate([
            'jumlah_bayar'    => 'required|numeric|min:0',
            'tanggal_bayar'   => 'required|date',
            'keterangan'      => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $updateData = [
                'jumlah_bayar'  => (float) $request->jumlah_bayar,
                'tanggal_bayar' => $request->tanggal_bayar,
                'keterangan'    => $request->keterangan,
            ];

            // Handle file replacement
            if ($request->hasFile('bukti_pembayaran')) {
                // Delete old file
                if ($buktiPembayaran->bukti_pembayaran) {
                    Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiPembayaran->bukti_pembayaran);
                }
                $file         = $request->file('bukti_pembayaran');
                $filename     = time() . '_bukti_' . $buktiId . '.' . $file->getClientOriginalExtension();
                $file->storeAs('penagihan-dinas/bukti-pembayaran', $filename, 'public');
                $updateData['bukti_pembayaran'] = $filename;
            }

            $buktiPembayaran->update($updateData);

            return redirect()->route('penagihan-dinas.edit', $penagihanDinas->id)
                ->with('success', 'Data pembayaran berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteBuktiPembayaran($buktiId)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat menghapus bukti pembayaran.');
        }

        $buktiPembayaran = BuktiPembayaran::with('penagihanDinas')->findOrFail($buktiId);
        $penagihanDinas = $buktiPembayaran->penagihanDinas;

        // Validasi: tidak boleh menghapus bukti DP jika status sudah lunas
        if ($buktiPembayaran->jenis_pembayaran === 'dp' && $penagihanDinas->status_pembayaran === 'lunas') {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus bukti pembayaran DP karena sudah ada pelunasan.');
        }

        try {
            DB::beginTransaction();

            // Hapus file dari storage
            if ($buktiPembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiPembayaran->bukti_pembayaran);
            }

            // Jika yang dihapus adalah bukti pelunasan, update status kembali ke DP
            if ($buktiPembayaran->jenis_pembayaran === 'lunas') {
                $penagihanDinas->update(['status_pembayaran' => 'dp']);
            }

            // Jika yang dihapus adalah bukti DP dan tidak ada pelunasan, hapus seluruh penagihan
            if ($buktiPembayaran->jenis_pembayaran === 'dp') {
                // Cek apakah ada bukti pelunasan
                $hasLunas = $penagihanDinas->buktiPembayaran()
                    ->where('id', '!=', $buktiId)
                    ->where('jenis_pembayaran', 'lunas')
                    ->exists();

                if (!$hasLunas) {
                    // Hapus semua dokumen penagihan dinas
                    $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
                    
                    foreach ($dokumenFields as $field) {
                        if ($penagihanDinas->$field) {
                            Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$field);
                        }
                    }

                    // Hapus penagihan dinas
                    $penagihanDinas->delete();

                    DB::commit();

                    return redirect()->route('keuangan.penagihan')
                        ->with('success', 'Bukti pembayaran DP dihapus. Penagihan dinas telah dihapus karena tidak ada pembayaran.');
                }
            }

            // Hapus bukti pembayaran
            $buktiPembayaran->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Bukti pembayaran berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus bukti pembayaran: ' . $e->getMessage());
        }
    }

    public function addPelunasan(Request $request, $id)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat menambahkan pelunasan.');
        }

        $penagihanDinas = PenagihanDinas::findOrFail($id);

        // Validasi hanya bisa menambah pelunasan jika status masih DP
        if ($penagihanDinas->status_pembayaran !== 'dp') {
            return redirect()->back()->with('error', 'Pelunasan hanya dapat ditambahkan untuk pembayaran DP.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Handle upload bukti pembayaran
            $buktiPembayaranFile = $request->file('bukti_pembayaran');
            $buktiFilename = time() . '_bukti_pelunasan.' . $buktiPembayaranFile->getClientOriginalExtension();
            $buktiPath = $buktiPembayaranFile->storeAs('penagihan-dinas/bukti-pembayaran', $buktiFilename, 'public');

            // Hitung sisa pembayaran
            $totalBayar = $penagihanDinas->buktiPembayaran->sum('jumlah_bayar');
            $sisaPembayaran = $penagihanDinas->total_harga - $totalBayar;

            // Buat bukti pembayaran pelunasan
            BuktiPembayaran::create([
                'penagihan_dinas_id' => $penagihanDinas->id,
                'jenis_pembayaran' => 'lunas',
                'jumlah_bayar' => $sisaPembayaran,
                'tanggal_bayar' => $request->tanggal_bayar,
                'bukti_pembayaran' => $buktiFilename,
                'keterangan' => $request->keterangan
            ]);

            // Update status menjadi lunas
            $penagihanDinas->update([
                'status_pembayaran' => 'lunas'
            ]);

            DB::commit();

            return redirect()->route('penagihan-dinas.show', $id)
                ->with('success', 'Pelunasan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if (isset($buktiFilename)) {
                Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiFilename);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function history($id)
    {
        $penagihanDinas = PenagihanDinas::with(['proyek', 'penawaran', 'buktiPembayaran'])
            ->findOrFail($id);

        return view('pages.keuangan.penagihan-history', compact('penagihanDinas'));
    }

    public function downloadDokumen($id, $jenis)
    {
        $penagihanDinas = PenagihanDinas::findOrFail($id);
        
        if (!$penagihanDinas->$jenis) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $filePath = 'penagihan-dinas/dokumen/' . $penagihanDinas->$jenis;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function downloadBuktiPembayaran($buktiId)
    {
        $buktiPembayaran = BuktiPembayaran::findOrFail($buktiId);
        
        $filePath = 'penagihan-dinas/bukti-pembayaran/' . $buktiPembayaran->bukti_pembayaran;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function showPelunasan($id)
    {
        // Role-based access control
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')
                ->with('error', 'Akses ditolak. Hanya Admin Keuangan/Superadmin yang dapat mengakses halaman pelunasan.');
        }

        $penagihanDinas = PenagihanDinas::with(['proyek', 'penawaran.penawaranDetail', 'buktiPembayaran'])
            ->findOrFail($id);

        // Validasi hanya bisa mengakses pelunasan jika status masih DP
        if ($penagihanDinas->status_pembayaran !== 'dp') {
            return redirect()->route('penagihan-dinas.show', $id)
                ->with('error', 'Pelunasan hanya dapat diakses untuk pembayaran DP.');
        }

        // Hitung sisa pembayaran
        $totalBayar = $penagihanDinas->buktiPembayaran->sum('jumlah_bayar');
        $sisaPembayaran = $penagihanDinas->total_harga - $totalBayar;

        return view('pages.keuangan.penagihan-pelunasan', compact('penagihanDinas', 'sisaPembayaran'));
    }

    public function previewDokumen($id, $jenis)
    {
        $penagihanDinas = PenagihanDinas::findOrFail($id);
        
        if (!$penagihanDinas->$jenis) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $filePath = 'penagihan-dinas/dokumen/' . $penagihanDinas->$jenis;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $file = Storage::disk('public')->get($filePath);
        $absolutePath = Storage::disk('public')->path($filePath);
        $mimeType = mime_content_type($absolutePath);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    public function previewBuktiPembayaran($buktiId)
    {
        $buktiPembayaran = BuktiPembayaran::findOrFail($buktiId);
        
        $filePath = 'penagihan-dinas/bukti-pembayaran/' . $buktiPembayaran->bukti_pembayaran;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $file = Storage::disk('public')->get($filePath);
        $absolutePath = Storage::disk('public')->path($filePath);
        $mimeType = mime_content_type($absolutePath);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    /* =====================================================================
     * AJAX ENDPOINTS — return JSON so the edit page can update without reload
     * ===================================================================== */

    /**
     * AJAX: Update penagihan info (nomor invoice, total_harga, persentase_dp, etc.)
     */
    public function ajaxUpdate(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $penagihanDinas = PenagihanDinas::findOrFail($id);

        // Strip thousand-separators
        $rawTotalHarga = str_replace(['.', ' '], '', $request->input('total_harga', ''));
        $rawTotalHarga = str_replace(',', '.', $rawTotalHarga);
        $request->merge(['total_harga' => $rawTotalHarga]);

        $validated = $request->validate([
            'nomor_invoice'        => 'required|string|unique:penagihan_dinas,nomor_invoice,' . $id,
            'total_harga'          => 'required|numeric|min:0',
            'persentase_dp'        => 'nullable|numeric|min:0|max:100',
            'tanggal_jatuh_tempo'  => 'required|date',
            'keterangan'           => 'nullable|string',
        ]);

        try {
            // Handle file uploads
            $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
            $uploadedDokumen = [];

            foreach ($dokumenFields as $field) {
                if ($request->hasFile($field)) {
                    if ($penagihanDinas->$field) {
                        Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$field);
                    }
                    $file     = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('penagihan-dinas/dokumen', $filename, 'public');
                    $uploadedDokumen[$field] = $filename;
                }
            }

            $totalHarga = (float) $request->total_harga;
            $updateData = [
                'nomor_invoice'       => $request->nomor_invoice,
                'total_harga'         => $totalHarga,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'keterangan'          => $request->keterangan,
            ];

            if ($penagihanDinas->status_pembayaran === 'dp' && $request->filled('persentase_dp')) {
                $persentaseDp = (float) $request->persentase_dp;
                $updateData['persentase_dp'] = $persentaseDp;
                $updateData['jumlah_dp']     = round($persentaseDp / 100 * $totalHarga, 2);
            }

            $penagihanDinas->update($updateData + $uploadedDokumen);
            $penagihanDinas->refresh();

            // Build updated dokumen state for response
            $dokumenState = [];
            foreach ($dokumenFields as $field) {
                $dokumenState[$field] = $penagihanDinas->$field
                    ? asset('storage/penagihan-dinas/dokumen/' . $penagihanDinas->$field)
                    : null;
            }

            return response()->json([
                'success'       => true,
                'message'       => 'Penagihan berhasil diperbarui.',
                'total_harga'   => (float) $penagihanDinas->total_harga,
                'persentase_dp' => (float) $penagihanDinas->persentase_dp,
                'jumlah_dp'     => (float) $penagihanDinas->jumlah_dp,
                'nomor_invoice' => $penagihanDinas->nomor_invoice,
                'dokumen'       => $dokumenState,
            ]);

        } catch (\Exception $e) {
            foreach ($uploadedDokumen as $fname) {
                Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $fname);
            }
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * AJAX: Add new BuktiPembayaran
     */
    public function ajaxStoreBukti(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $penagihanDinas = PenagihanDinas::with('buktiPembayaran')->findOrFail($id);

        $rawJumlah = str_replace(['.', ' '], '', $request->input('jumlah_bayar', ''));
        $rawJumlah = str_replace(',', '.', $rawJumlah);
        $request->merge(['jumlah_bayar' => $rawJumlah]);

        $request->validate([
            'jenis_pembayaran' => 'required|in:dp,lunas,lainnya',
            'jumlah_bayar'     => 'required|numeric|min:0.01',
            'tanggal_bayar'    => 'required|date',
            'keterangan'       => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $buktiFilename = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file          = $request->file('bukti_pembayaran');
                $buktiFilename = time() . '_bukti_baru.' . $file->getClientOriginalExtension();
                $file->storeAs('penagihan-dinas/bukti-pembayaran', $buktiFilename, 'public');
            }

            $bukti = BuktiPembayaran::create([
                'penagihan_dinas_id' => $penagihanDinas->id,
                'jenis_pembayaran'   => $request->jenis_pembayaran,
                'jumlah_bayar'       => (float) $request->jumlah_bayar,
                'tanggal_bayar'      => $request->tanggal_bayar,
                'bukti_pembayaran'   => $buktiFilename,
                'keterangan'         => $request->keterangan,
            ]);

            if ($request->jenis_pembayaran === 'lunas') {
                $penagihanDinas->update(['status_pembayaran' => 'lunas']);
            }

            $penagihanDinas->refresh()->load('buktiPembayaran');

            return response()->json([
                'success'         => true,
                'message'         => 'Pembayaran berhasil ditambahkan.',
                'bukti'           => $this->buktiToArray($bukti),
                'total_terbayar'  => (float) $penagihanDinas->buktiPembayaran->sum('jumlah_bayar'),
                'status_pembayaran' => $penagihanDinas->status_pembayaran,
            ]);

        } catch (\Exception $e) {
            if ($buktiFilename) {
                Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiFilename);
            }
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * AJAX: Update existing BuktiPembayaran
     */
    public function ajaxUpdateBukti(Request $request, $buktiId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $buktiPembayaran = BuktiPembayaran::with('penagihanDinas')->findOrFail($buktiId);

        $rawJumlah = str_replace(['.', ' '], '', $request->input('jumlah_bayar', ''));
        $rawJumlah = str_replace(',', '.', $rawJumlah);
        $request->merge(['jumlah_bayar' => $rawJumlah]);

        $request->validate([
            'jumlah_bayar'    => 'required|numeric|min:0',
            'tanggal_bayar'   => 'required|date',
            'keterangan'      => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $updateData = [
                'jumlah_bayar'  => (float) $request->jumlah_bayar,
                'tanggal_bayar' => $request->tanggal_bayar,
                'keterangan'    => $request->keterangan,
            ];

            if ($request->hasFile('bukti_pembayaran')) {
                if ($buktiPembayaran->bukti_pembayaran) {
                    Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiPembayaran->bukti_pembayaran);
                }
                $file     = $request->file('bukti_pembayaran');
                $filename = time() . '_bukti_' . $buktiId . '.' . $file->getClientOriginalExtension();
                $file->storeAs('penagihan-dinas/bukti-pembayaran', $filename, 'public');
                $updateData['bukti_pembayaran'] = $filename;
            }

            $buktiPembayaran->update($updateData);
            $buktiPembayaran->refresh();

            $penagihanDinas = $buktiPembayaran->penagihanDinas->load('buktiPembayaran');

            return response()->json([
                'success'        => true,
                'message'        => 'Data pembayaran berhasil diperbarui.',
                'bukti'          => $this->buktiToArray($buktiPembayaran),
                'total_terbayar' => (float) $penagihanDinas->buktiPembayaran->sum('jumlah_bayar'),
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * AJAX: Delete BuktiPembayaran
     */
    public function ajaxDeleteBukti($buktiId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $buktiPembayaran = BuktiPembayaran::with('penagihanDinas')->findOrFail($buktiId);
        $penagihanDinas  = $buktiPembayaran->penagihanDinas;

        if ($buktiPembayaran->jenis_pembayaran === 'dp' && $penagihanDinas->status_pembayaran === 'lunas') {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus bukti DP karena sudah ada pelunasan.'], 422);
        }

        try {
            DB::beginTransaction();

            if ($buktiPembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiPembayaran->bukti_pembayaran);
            }

            $willDeletePenagihan = false;
            $redirectToList      = false;

            if ($buktiPembayaran->jenis_pembayaran === 'lunas') {
                $penagihanDinas->update(['status_pembayaran' => 'dp']);
            }

            if ($buktiPembayaran->jenis_pembayaran === 'dp') {
                $hasLunas = $penagihanDinas->buktiPembayaran()
                    ->where('id', '!=', $buktiId)
                    ->where('jenis_pembayaran', 'lunas')
                    ->exists();

                if (!$hasLunas) {
                    // Delete whole penagihan
                    $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
                    foreach ($dokumenFields as $field) {
                        if ($penagihanDinas->$field) {
                            Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$field);
                        }
                    }
                    $penagihanDinas->buktiPembayaran()->delete();
                    $penagihanDinas->delete();
                    $willDeletePenagihan = true;
                    $redirectToList      = true;
                }
            }

            if (!$willDeletePenagihan) {
                $buktiPembayaran->delete();
            }

            DB::commit();

            if ($redirectToList) {
                return response()->json([
                    'success'     => true,
                    'message'     => 'Bukti DP dihapus. Penagihan dinas telah dihapus karena tidak ada pembayaran tersisa.',
                    'redirect_to' => route('keuangan.penagihan'),
                ]);
            }

            $penagihanDinas->refresh()->load('buktiPembayaran');

            return response()->json([
                'success'           => true,
                'message'           => 'Bukti pembayaran berhasil dihapus.',
                'total_terbayar'    => (float) $penagihanDinas->buktiPembayaran->sum('jumlah_bayar'),
                'status_pembayaran' => $penagihanDinas->status_pembayaran,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * AJAX: Delete a single dokumen field
     */
    public function ajaxDeleteDokumen($id, $jenis)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $allowedTypes = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
        if (!in_array($jenis, $allowedTypes)) {
            return response()->json(['success' => false, 'message' => 'Jenis dokumen tidak valid.'], 422);
        }

        $penagihanDinas = PenagihanDinas::findOrFail($id);

        try {
            if ($penagihanDinas->$jenis) {
                Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$jenis);
                $penagihanDinas->update([$jenis => null]);
            }

            return response()->json(['success' => true, 'message' => 'Dokumen berhasil dihapus.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper: Convert BuktiPembayaran to array for JSON response
     */
    private function buktiToArray(BuktiPembayaran $bukti): array
    {
        return [
            'id'               => $bukti->id,
            'jenis_pembayaran' => $bukti->jenis_pembayaran,
            'jumlah_bayar'     => (float) $bukti->jumlah_bayar,
            'tanggal_bayar'    => $bukti->tanggal_bayar,
            'keterangan'       => $bukti->keterangan,
            'bukti_url'        => $bukti->bukti_pembayaran
                ? asset('storage/penagihan-dinas/bukti-pembayaran/' . $bukti->bukti_pembayaran)
                : null,
        ];
    }
}
