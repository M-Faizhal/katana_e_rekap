<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\KalkulasiHps;
use App\Models\RiwayatHps;
use App\Models\Barang;
use App\Models\Vendor;
use App\Models\Penawaran;
use App\Models\PenawaranDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\HpsAutoFillService;

class KalkulasiController extends Controller
{
    public function index(Request $request)
    {
        // Tab Menunggu Kalkulasi - Proyek dengan status Menunggu
        $proyekMenunggu = Proyek::with(['adminMarketing:id_user,nama', 'adminPurchasing:id_user,nama', 'proyekBarang'])
                                ->where('status', 'Menunggu');

        // Tab Proses Penawaran - Proyek dengan status Penawaran dan penawaran berstatus Menunggu
        $proyekProses = Proyek::with(['adminMarketing:id_user,nama', 'adminPurchasing:id_user,nama', 'proyekBarang', 'penawaranAktif'])
                              ->where('status', 'Penawaran')
                              ->whereHas('penawaranAktif', function($query) {
                                  $query->where('status', 'Menunggu');
                              });

        // Tab Penawaran Berhasil - Proyek dengan status Penawaran dan penawaran berstatus ACC
        $proyekBerhasil = Proyek::with(['adminMarketing:id_user,nama', 'adminPurchasing:id_user,nama', 'proyekBarang', 'penawaranAktif'])
                                ->whereHas('penawaranAktif', function($query) {
                                    $query->where('status', 'ACC');
                                });

        // Filter berdasarkan pencarian jika ada
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $proyekMenunggu->where(function($q) use ($search) {
                $q->where('nama_klien', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%")
                  ->orWhereHas('proyekBarang', function($subQ) use ($search) {
                      $subQ->where('nama_barang', 'like', "%{$search}%");
                  });
            });

            $proyekProses->where(function($q) use ($search) {
                $q->where('nama_klien', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%")
                  ->orWhereHas('proyekBarang', function($subQ) use ($search) {
                      $subQ->where('nama_barang', 'like', "%{$search}%");
                  });
            });

            $proyekBerhasil->where(function($q) use ($search) {
                $q->where('nama_klien', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%")
                  ->orWhereHas('proyekBarang', function($subQ) use ($search) {
                      $subQ->where('nama_barang', 'like', "%{$search}%");
                  });
            });
        }

        // Paginate hasil
        $proyekMenunggu = $proyekMenunggu->orderBy('created_at', 'desc')->paginate(10, ['*'], 'menunggu');
        $proyekProses = $proyekProses->orderBy('created_at', 'desc')->paginate(10, ['*'], 'proses');
        $proyekBerhasil = $proyekBerhasil->orderBy('created_at', 'desc')->paginate(10, ['*'], 'berhasil');

        // Hitung total HPS dari kalkulasi_hps untuk setiap proyek
        foreach ($proyekMenunggu as $proyek) {
            $proyek->total_hps = KalkulasiHps::where('id_proyek', $proyek->id_proyek)->sum('hps') ?? 0;
        }

        foreach ($proyekProses as $proyek) {
            $proyek->total_hps = KalkulasiHps::where('id_proyek', $proyek->id_proyek)->sum('hps') ?? 0;
        }

        foreach ($proyekBerhasil as $proyek) {
            $proyek->total_hps = KalkulasiHps::where('id_proyek', $proyek->id_proyek)->sum('hps') ?? 0;
        }

        return view('pages.purchasing.kalkulasi', compact('proyekMenunggu', 'proyekProses', 'proyekBerhasil'));
    }

    public function getProyekData($id)
    {
        try {
            $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'proyekBarang'])->findOrFail($id);

            // Get existing kalkulasi data
            $kalkulasiData = KalkulasiHps::with(['barang', 'vendor'])
                                        ->where('id_proyek', $id)
                                        ->get();

            return response()->json([
                'success' => true,
                'proyek' => $proyek,
                'kalkulasi' => $kalkulasiData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Proyek tidak ditemukan'
            ], 404);
        }
    }

    public function getBarangList()
    {
        $barang = Barang::with('vendor:id_vendor,nama_vendor')
                        ->select('id_barang', 'nama_barang', 'satuan', 'harga_vendor', 'id_vendor')
                        ->get();
        return response()->json($barang);
    }

    public function getVendorList()
    {
        $vendor = Vendor::select('id_vendor', 'nama_vendor')->get();
        return response()->json($vendor);
    }

    public function getJenisVendorOptions()
    {
        $jenisVendor = [
            'Principle',
            'Distributor',
            'Lokal',
            'Import',
            'Authorized Dealer',
            'Reseller'
        ];
        return response()->json($jenisVendor);
    }

    public function getKeteranganOptions()
    {
        $keterangan = [
            'Pengadaan Pendidikan',
            'Furniture Kantor',
            'Peralatan Elektronik',
            'Mesin Industri',
            'Standard Quality',
            'Premium Quality',
            'High Quality',
            'Prioritas Tinggi',
            'Prioritas Normal',
            'Urgent'
        ];
        return response()->json($keterangan);
    }

    public function saveKalkulasi(Request $request)
    {
        try {
            $user = Auth::user();
            $proyekId = $request->input('id_proyek');
            $isSuperadmin = $user->role === 'superadmin';

            // Detailed logging untuk debugging
            Log::info('=== SAVE KALKULASI DEBUG START ===');
            Log::info('Received request data', [
                'project_id' => $proyekId,
                'user_id' => $user->id_user,
                'user_role' => $user->role,
                'kalkulasi_count' => count($request->kalkulasi ?? []),
                'raw_request_data' => $request->all(),
                'first_kalkulasi_item' => $request->kalkulasi[0] ?? 'null'
            ]);

            if (!$isSuperadmin && $user->role !== 'admin_purchasing') {
                Log::warning('Access denied - role check failed', [
                    'user_role' => $user->role,
                    'required_roles' => ['superadmin', 'admin_purchasing']
                ]);
                return response()->json([
                    'message' => 'Akses ditolak. Hanya admin purchasing atau superadmin yang dapat melakukan kalkulasi.'
                ], 403);
            }

            $proyek = Proyek::find($proyekId);
            if (!$proyek || (!$isSuperadmin && $proyek->id_admin_purchasing != $user->id_user)) {
                Log::warning('Access denied - project access check failed', [
                    'proyek_found' => $proyek ? 'yes' : 'no',
                    'project_admin_id' => $proyek->id_admin_purchasing ?? 'null',
                    'current_user_id' => $user->id_user,
                    'is_superadmin' => $isSuperadmin
                ]);
                return response()->json([
                    'message' => 'Akses ditolak. Anda tidak memiliki akses untuk melakukan kalkulasi pada proyek ini.'
                ], 403);
            }

            Log::info('Starting database transaction');
            DB::beginTransaction();

            $proyekId = $request->id_proyek;

            // Get existing approval file before deleting records
            $existingApprovalFile = KalkulasiHps::where('id_proyek', $proyekId)
                                               ->whereNotNull('bukti_file_approval')
                                               ->value('bukti_file_approval');

            // Hapus kalkulasi lama jika ada
            KalkulasiHps::where('id_proyek', $proyekId)->delete();

            // Cek apakah ada file approval yang sudah diupload sebelumnya
            $approvalFiles = glob(storage_path('app/public/approval_files/*' . $proyekId . '_approval*'));
            $approvalFileName = null;
            if (!empty($approvalFiles)) {
                // Ambil file terbaru jika ada beberapa
                $latestFile = array_reduce($approvalFiles, function($latest, $file) {
                    return ($latest === null || filemtime($file) > filemtime($latest)) ? $file : $latest;
                });
                if ($latestFile) {
                    $approvalFileName = basename($latestFile);
                }
            }

            // Log untuk debugging approval file
            Log::info('Approval file check during save', [
                'project_id' => $proyekId,
                'storage_path_search' => storage_path('app/public/approval_files/*' . $proyekId . '_approval*'),
                'found_files' => $approvalFiles,
                'found_files_count' => count($approvalFiles),
                'approval_file_name' => $approvalFileName,
                'latest_file_exists' => isset($latestFile) && $latestFile ? file_exists($latestFile) : false,
                'storage_directory_exists' => is_dir(storage_path('app/public/approval_files'))
            ]);

            // Simpan kalkulasi baru
            foreach ($request->kalkulasi as $index => $item) {
                Log::info('Creating kalkulasi item with detailed data', [
                    'index' => $index,
                    'id_barang' => $item['id_barang'] ?? null,
                    'id_vendor' => $item['id_vendor'] ?? null,
                    'harga_vendor' => $item['harga_vendor'] ?? 0,
                    'harga_diskon' => $item['harga_diskon'] ?? 0,
                    'nilai_diskon' => $item['nilai_diskon'] ?? 0,
                    'hps' => $item['hps'] ?? 0,
                    'approval_file_to_assign' => $approvalFileName
                ]);

                $kalkulasi = KalkulasiHps::create([
                    'id_proyek' => $proyekId,
                    'id_barang' => $item['id_barang'] ?? null,
                    'id_vendor' => $item['id_vendor'] ?? null,
                    'qty' => $item['qty'] ?? 1,
                    'harga_vendor' => $item['harga_vendor'] ?? 0,
                    'harga_diskon' => $item['harga_diskon'] ?? 0, // INPUT: Harga setelah diskon  
                    'diskon_amount' => $item['nilai_diskon'] ?? 0, // CALCULATED: nilai diskon per item
                    'total_diskon' => $item['total_diskon'] ?? 0, // CALCULATED: total diskon
                    'harga_akhir' => $item['harga_diskon'] ?? 0, // Untuk kompatibilitas
                    'total_harga_hpp' => $item['total_harga'] ?? 0,
                    'jumlah_volume' => $item['jumlah_volume'] ?? 0,
                    'harga_yang_diharapkan' => $item['harga_yang_diharapkan'] ?? 0,
                    'kenaikan_percent' => $item['persen_kenaikan'] ?? 0,
                    'proyeksi_kenaikan' => $item['proyeksi_kenaikan'] ?? 0,
                    'pph' => $item['pph_dinas'] ?? 0,
                    'ppn' => $item['ppn_dinas'] ?? 0,
                    'ongkir' => $item['ongkir'] ?? 0,
                    'hps' => $item['hps'] ?? 0,
                    'harga_per_pcs' => $item['harga_per_pcs'] ?? 0,
                    'harga_pagu_dinas_per_pcs' => $item['harga_pagu_dinas_per_pcs'] ?? 0,
                    'nilai_sp' => $item['nilai_sp'] ?? 0,
                    'nilai_tkdn_percent' => $item['nilai_tkdn_percent'] ?? 0,
                    'jenis_vendor' => $item['jenis_vendor'] ?? null,
                    'nilai_pagu_anggaran' => $item['pagu_total'] ?? 0,
                    'nilai_penawaran_hps' => $item['nilai_penawaran_hps'] ?? 0,
                    'nilai_pesanan' => $item['nilai_pesanan'] ?? 0,
                    'nilai_selisih' => $item['selisih_pagu_hps'] ?? 0,
                    'nilai_dpp' => $item['dpp'] ?? 0,
                    'ppn_percent' => $item['ppn_percent'] ?? 11,
                    'pph_badan_percent' => $item['pph_badan_percent'] ?? 1.5,
                    'nilai_ppn' => $item['nilai_ppn'] ?? 0,
                    'nilai_pph_badan' => $item['pph_from_dpp'] ?? 0,
                    'nilai_asumsi_cair' => $item['asumsi_nilai_cair'] ?? 0,
                    'sub_total_langsung' => $item['sub_total_langsung'] ?? 0,
                    'bank_cost' => $item['gross_nilai_bank_cost'] ?? 0,
                    'biaya_ops' => $item['gross_nilai_biaya_ops'] ?? 0,
                    'bendera' => $item['gross_nilai_bendera'] ?? 0,
                    'omzet_dinas_percent' => $item['omzet_dinas_percent'] ?? 0,
                    'omzet_dinas' => $item['omzet_nilai_dinas'] ?? 0,
                    'bendera_percent' => $item['bendera_percent'] ?? 0,
                    'bank_cost_percent' => $item['bank_cost_percent'] ?? 0,
                    'biaya_ops_percent' => $item['biaya_ops_percent'] ?? 0,
                    'gross_bendera' => $item['gross_nilai_bendera'] ?? 0,
                    'gross_bank_cost' => $item['gross_nilai_bank_cost'] ?? 0,
                    'gross_biaya_ops' => $item['gross_nilai_biaya_ops'] ?? 0,
                    'sub_total_tidak_langsung' => $item['sub_total_biaya_tidak_langsung'] ?? 0,
                    'nett' => $item['nilai_nett_income'] ?? 0,
                    'nett_percent' => $item['nett_income_persentase'] ?? 0,
                    'nilai_nett_pcs' => $item['nilai_nett_pcs'] ?? 0,
                    'total_nett_pcs' => $item['total_nilai_nett_per_pcs'] ?? 0,
                    'gross_income' => $item['gross_income'] ?? 0,
                    'gross_income_percent' => $item['gross_income_persentase'] ?? 0,
                    'nett_income' => $item['nilai_nett_income'] ?? 0,
                    'nett_income_percent' => $item['nett_income_persentase'] ?? 0,
                    'keterangan_1' => $item['keterangan_1'] ?? null,
                    'keterangan_2' => $item['keterangan_2'] ?? null,
                    'bukti_file_approval' => $approvalFileName, // Assign approval file name only
                    'catatan' => $item['catatan'] ?? null,
                ]);

                Log::info('Kalkulasi item saved successfully', [
                    'id' => $kalkulasi->id_kalkulasi,
                    'approval_file_assigned' => $approvalFileName,
                    'approval_file_in_db' => $kalkulasi->bukti_file_approval,
                    'approval_file_matches' => $kalkulasi->bukti_file_approval === $approvalFileName
                ]);
            }

            // Update total nilai proyek dari kalkulasi HPS
            $totalNilaiProyek = collect($request->kalkulasi)->sum('hps');
            if ($totalNilaiProyek > 0) {
                $proyek->update(['harga_total' => $totalNilaiProyek]);
                Log::info('Project total updated', ['total' => $totalNilaiProyek]);
            }

            Log::info('Database transaction committed successfully');
            
            $responseData = [
                'success' => true,
                'message' => 'Kalkulasi berhasil disimpan',
                'total_nilai_proyek' => $totalNilaiProyek,
                'items_saved' => count($request->kalkulasi)
            ];
            
            Log::info('Sending successful response', $responseData);
            Log::info('=== SAVE KALKULASI DEBUG END ===');

            return response()->json($responseData);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving kalkulasi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan kalkulasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createPenawaran(Request $request)
    {
        try {
            $user = Auth::user();
            $proyekId = $request->input('id_proyek');
            $isSuperadmin = $user->role === 'superadmin';

            if (!$isSuperadmin && $user->role !== 'admin_purchasing') {
                return response()->json([
                    'message' => 'Akses ditolak. Hanya admin purchasing atau superadmin yang dapat membuat penawaran.'
                ], 403);
            }

            $proyek = Proyek::find($proyekId);
            if (!$proyek || (!$isSuperadmin && $proyek->id_admin_purchasing != $user->id_user)) {
                return response()->json([
                    'message' => 'Akses ditolak. Anda tidak memiliki akses untuk membuat penawaran pada proyek ini.'
                ], 403);
            }

            DB::beginTransaction();

            $proyekId = $request->input('id_proyek');

            if (!$proyekId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Proyek tidak ditemukan'
                ], 400);
            }

            // Ambil data proyek dan kalkulasi
            $proyek = Proyek::findOrFail($proyekId);
            $kalkulasiData = KalkulasiHps::with(['barang', 'vendor'])
                                        ->where('id_proyek', $proyekId)
                                        ->get();

            if ($kalkulasiData->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data kalkulasi untuk membuat penawaran'
                ], 400);
            }

            // Generate nomor penawaran
            $lastPenawaran = Penawaran::whereYear('created_at', date('Y'))
                                    ->whereMonth('created_at', date('m'))
                                    ->orderBy('id_penawaran', 'desc')
                                    ->first();

            $counter = $lastPenawaran ? (int)substr($lastPenawaran->no_penawaran, -3) + 1 : 1;
            $noPenawaran = 'PNW/' . date('Y/m') . '/' . str_pad($counter, 3, '0', STR_PAD_LEFT);

            // Hitung total penawaran dari HPS
            $totalPenawaran = $kalkulasiData->sum('hps');

            // Buat data penawaran
            $penawaran = Penawaran::create([
                'id_proyek' => $proyekId,
                'no_penawaran' => $noPenawaran,
                'tanggal_penawaran' => now(),
                'masa_berlaku' => now()->addDays(30), // 30 hari dari sekarang
                'total_penawaran' => $totalPenawaran,
                'status' => 'Menunggu'
            ]);

            // Buat detail penawaran dari setiap item kalkulasi
            foreach ($kalkulasiData as $kalkulasi) {
                // Tentukan nama barang - prioritas dari relasi barang
                $namaBarang = 'Item Kalkulasi';
                if ($kalkulasi->barang) {
                    $namaBarang = $kalkulasi->barang->nama_barang;
                } elseif ($kalkulasi->keterangan_1) {
                    $namaBarang = $kalkulasi->keterangan_1;
                } elseif ($kalkulasi->keterangan_2) {
                    $namaBarang = $kalkulasi->keterangan_2;
                }

                // Cari data proyek barang yang sesuai
                $proyekBarang = null;
                if ($kalkulasi->barang) {
                    $proyekBarang = $proyek->proyekBarang()
                                         ->where('nama_barang', $kalkulasi->barang->nama_barang)
                                         ->first();
                }

                // === LOGIKA QTY ===
                $qty = 1; // Default qty

                // Prioritas 1: Dari kalkulasi qty (field qty langsung)
                if (isset($kalkulasi->qty) && $kalkulasi->qty > 0) {
                    $qty = (int) $kalkulasi->qty;
                }
                // Prioritas 2: Dari proyek barang (data asli permintaan klien)
                elseif ($proyekBarang && $proyekBarang->jumlah > 0) {
                    $qty = (int) $proyekBarang->jumlah;
                }
                // Prioritas 3: Fallback ke 1 jika tidak ada yang valid
                else {
                    $qty = 1;
                }

                // === LOGIKA SATUAN ===
                $satuan = 'pcs'; // Default satuan

                // Prioritas 1: Dari data barang master
                if ($kalkulasi->barang && $kalkulasi->barang->satuan) {
                    $satuan = $kalkulasi->barang->satuan;
                }
                // Prioritas 2: Dari data proyek barang
                elseif ($proyekBarang && $proyekBarang->satuan) {
                    $satuan = $proyekBarang->satuan;
                }

                // === LOGIKA HARGA SATUAN DAN SUBTOTAL ===
                $subtotal = $kalkulasi->hps ?: 0; // Subtotal dari HPS (hasil kalkulasi final)
                $hargaSatuan = 0;

                // Strategi: Gunakan HPS sebagai subtotal dan hitung harga satuan dari situ
                if ($subtotal > 0 && $qty > 0) {
                    // Harga satuan = HPS / qty
                    $hargaSatuan = $subtotal / $qty;
                } else {
                    // Fallback: gunakan harga vendor atau harga dari proyek
                    if ($kalkulasi->harga_vendor && $kalkulasi->harga_vendor > 0) {
                        $hargaSatuan = $kalkulasi->harga_vendor;
                        $subtotal = $hargaSatuan * $qty;
                    } elseif ($proyekBarang && $proyekBarang->harga_satuan > 0) {
                        $hargaSatuan = $proyekBarang->harga_satuan;
                        $subtotal = $hargaSatuan * $qty;
                    } elseif ($kalkulasi->barang && $kalkulasi->barang->harga_vendor > 0) {
                        $hargaSatuan = $kalkulasi->barang->harga_vendor;
                        $subtotal = $hargaSatuan * $qty;
                    } else {
                        // Last resort: set minimal values
                        $hargaSatuan = 1000; // Minimal Rp 1.000
                        $subtotal = $hargaSatuan * $qty;
                    }
                }

                // Validasi final: pastikan nilai positif dan masuk akal
                $hargaSatuan = max($hargaSatuan, 100); // Minimal Rp 100
                $subtotal = max($subtotal, $hargaSatuan); // Minimal sama dengan harga satuan
                $qty = max($qty, 1); // Minimal qty 1

                // Tentukan id_barang
                $idBarang = $kalkulasi->id_barang;
                if (!$idBarang && $kalkulasi->barang) {
                    $idBarang = $kalkulasi->barang->id_barang;
                }

                // Log data untuk debugging
                Log::info('Creating penawaran detail', [
                    'kalkulasi_id' => $kalkulasi->id_kalkulasi ?? 'unknown',
                    'nama_barang' => $namaBarang,
                    'qty' => $qty,
                    'satuan' => $satuan,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'hps_original' => $kalkulasi->hps,
                    'harga_vendor_original' => $kalkulasi->harga_vendor,
                    'proyek_barang_found' => $proyekBarang ? true : false,
                    'proyek_barang_qty' => $proyekBarang ? $proyekBarang->jumlah : null,
                    'proyek_barang_harga' => $proyekBarang ? $proyekBarang->harga_satuan : null
                ]);

                $penawaranDetail = PenawaranDetail::create([
                    'id_penawaran' => $penawaran->id_penawaran,
                    'id_barang' => $idBarang,
                    'nama_barang' => $namaBarang,
                    'spesifikasi' => $this->generateSpesifikasi($kalkulasi),
                    'qty' => $qty,
                    'satuan' => $satuan,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal
                ]);
            }

            // Update status proyek menjadi 'Penawaran' dan set id_penawaran
            $proyek->update([
                'status' => 'Penawaran',
                'id_penawaran' => $penawaran->id_penawaran
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penawaran berhasil dibuat dengan nomor: ' . $noPenawaran,
                'data' => [
                    'id_penawaran' => $penawaran->id_penawaran,
                    'no_penawaran' => $noPenawaran,
                    'total_penawaran' => $totalPenawaran,
                    'total_items' => $kalkulasiData->count()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat penawaran: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateSpesifikasi($kalkulasi)
    {
        $spesifikasi = [];

        // Ambil informasi vendor jika ada
        if ($kalkulasi->vendor) {
            $spesifikasi[] = 'Vendor: ' . $kalkulasi->vendor->nama_vendor;

            // Tambahkan jenis vendor dari relasi jika ada
            if ($kalkulasi->vendor->jenis_perusahaan) {
                $spesifikasi[] = 'Jenis Perusahaan: ' . $kalkulasi->vendor->jenis_perusahaan;
            }
        }

        // Tambahkan jenis vendor dari kalkulasi jika ada
        if ($kalkulasi->jenis_vendor) {
            $spesifikasi[] = 'Kategori Vendor: ' . $kalkulasi->jenis_vendor;
        }

        // Tambahkan informasi harga vendor
        if ($kalkulasi->harga_vendor > 0) {
            $spesifikasi[] = 'Harga Vendor: Rp ' . number_format($kalkulasi->harga_vendor, 0, ',', '.');
        }

        // Tambahkan informasi diskon jika ada
        if ($kalkulasi->nilai_diskon > 0) {
            $spesifikasi[] = 'Diskon: ' . number_format($kalkulasi->nilai_diskon, 2) . '%';
        }

        // Tambahkan informasi pajak
        if ($kalkulasi->ppn_percent > 0) {
            $spesifikasi[] = 'PPN: ' . number_format($kalkulasi->ppn_percent, 1) . '%';
        }

        if ($kalkulasi->pph_badan_percent > 0) {
            $spesifikasi[] = 'PPh: ' . number_format($kalkulasi->pph_badan_percent, 1) . '%';
        }

        // Tambahkan keterangan jika ada
        if ($kalkulasi->keterangan_1) {
            $spesifikasi[] = 'Keterangan: ' . $kalkulasi->keterangan_1;
        }

        if ($kalkulasi->keterangan_2) {
            $spesifikasi[] = $kalkulasi->keterangan_2;
        }

        // Tambahkan catatan jika ada
        if ($kalkulasi->catatan) {
            $spesifikasi[] = 'Catatan: ' . $kalkulasi->catatan;
        }

        // Tambahkan informasi kualitas jika ada
        if (stripos($kalkulasi->keterangan_1 ?? '', 'quality') !== false ||
            stripos($kalkulasi->keterangan_2 ?? '', 'quality') !== false) {
            // Quality info sudah ada di keterangan
        } else {
            // Bisa ditambahkan default quality info jika diperlukan
            if ($kalkulasi->harga_vendor > 1000000) { // Jika harga tinggi, anggap premium
                $spesifikasi[] = 'Kualitas: Premium';
            }
        }

        // Jika tidak ada spesifikasi, berikan default
        if (empty($spesifikasi)) {
            $spesifikasi[] = 'Spesifikasi sesuai dengan permintaan klien';
            $spesifikasi[] = 'Kualitas standar sesuai kebutuhan proyek';
        }

        return implode('; ', $spesifikasi);
    }

    public function getDropdownOptions()
    {
        return response()->json([
            'jenis_vendor' => KalkulasiHps::getJenisVendorOptions(),
            'keterangan' => KalkulasiHps::getKeteranganOptions(),
        ]);
    }

    public function calculateHps(Request $request)
    {
        try {
            // NOTE: This endpoint is deprecated as calculations are now done in JavaScript
            // Kept for backward compatibility only

            return response()->json([
                'success' => true,
                'message' => 'Calculation is now handled by JavaScript frontend',
                'calculated' => $request->all() // Return input as-is
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hps($id)
    {
        try {
            $user = Auth::user();
            $isSuperadmin = $user->role === 'superadmin';

            if (!$isSuperadmin && $user->role !== 'admin_purchasing') {
                return redirect()->route('purchasing.kalkulasi')->with('error', 'Akses ditolak. Hanya admin purchasing atau superadmin yang dapat mengakses halaman ini.');
            }

            $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'proyekBarang'])->findOrFail($id);
            $canEdit = $isSuperadmin || ($proyek->id_admin_purchasing == $user->id_user);

            // Get existing kalkulasi data
            $kalkulasiData = KalkulasiHps::with(['barang', 'vendor'])
                                        ->where('id_proyek', $id)
                                        ->get();

            // Transform data untuk kompatibilitas dengan frontend
            $kalkulasiData = $kalkulasiData->map(function($item) {
                $data = $item->toArray();

                // Map database fields ke frontend fields untuk konsistensi
                $data['nama_barang'] = $item->barang ? $item->barang->nama_barang : ($data['keterangan_1'] ?? '');
                $data['nama_vendor'] = $item->vendor ? $item->vendor->nama_vendor : '';
                $data['satuan'] = $item->barang ? $item->barang->satuan : 'pcs';

                // REVISED: Map field names untuk logika baru
                // LOGIKA BARU: harga_diskon = INPUT, nilai_diskon = CALCULATED
                $data['harga_diskon'] = $data['harga_diskon'] ?? $data['harga_akhir'] ?? 0; // INPUT field
                $data['nilai_diskon'] = $data['diskon_amount'] ?? 0; // CALCULATED field
                $data['total_harga'] = $data['total_harga_hpp'] ?? 0;
                $data['persen_kenaikan'] = $data['kenaikan_percent'] ?? 0;
                $data['pph_dinas'] = $data['pph'] ?? 0;
                $data['ppn_dinas'] = $data['ppn'] ?? 0;
                $data['pagu_total'] = $data['nilai_pagu_anggaran'] ?? 0;
                // Mapping untuk field baru - gunakan nama yang sama dengan JavaScript
                $data['harga_pagu_dinas_per_pcs'] = $data['harga_pagu_dinas_per_pcs'] ?? 0;
                $data['nilai_sp'] = $data['nilai_sp'] ?? 0;
                $data['bendera_percent'] = $data['bendera_percent'] ?? 0;
                $data['bank_cost_percent'] = $data['bank_cost_percent'] ?? 0;
                $data['biaya_ops_percent'] = $data['biaya_ops_percent'] ?? 0;
                $data['selisih_pagu_hps'] = $data['nilai_selisih'] ?? 0;
                $data['dpp'] = $data['nilai_dpp'] ?? 0;
                $data['pph_from_dpp'] = $data['nilai_pph_badan'] ?? 0;
                $data['asumsi_nilai_cair'] = $data['nilai_asumsi_cair'] ?? 0;
                $data['omzet_nilai_dinas'] = $data['omzet_dinas'] ?? 0;
                $data['gross_nilai_bendera'] = $data['gross_bendera'] ?? $data['bendera'] ?? 0;
                $data['gross_nilai_bank_cost'] = $data['gross_bank_cost'] ?? $data['bank_cost'] ?? 0;
                $data['gross_nilai_biaya_ops'] = $data['gross_biaya_ops'] ?? $data['biaya_ops'] ?? 0;
                $data['sub_total_biaya_tidak_langsung'] = $data['sub_total_tidak_langsung'] ?? 0;
                $data['nilai_nett_income'] = $data['nett_income'] ?? $data['nett'] ?? 0;
                $data['nett_income_persentase'] = $data['nett_income_percent'] ?? $data['nett_percent'] ?? 0;
                $data['total_nilai_nett_per_pcs'] = $data['total_nett_pcs'] ?? 0;
                $data['gross_income_persentase'] = $data['gross_income_percent'] ?? 0;

                return $data;
            });

            // Auto-fill data berdasarkan permintaan klien menggunakan service
            $kalkulasiData = HpsAutoFillService::autoFillFromClientRequests($kalkulasiData, $proyek);

            // Get current approval file if exists
            $currentApprovalFile = null;
            $approvalFiles = glob(storage_path('app/public/approval_files/*' . $id . '_approval*'));
            if (!empty($approvalFiles)) {
                // Ambil file terbaru jika ada beberapa
                $latestFile = array_reduce($approvalFiles, function($latest, $file) {
                    return ($latest === null || filemtime($file) > filemtime($latest)) ? $file : $latest;
                });
                if ($latestFile) {
                    $currentApprovalFile = 'approval_files/' . basename($latestFile);
                }
            }
            
            // Jika tidak ada file di storage, cek dari database
            if (!$currentApprovalFile) {
                $approvalFromDb = KalkulasiHps::where('id_proyek', $id)
                                            ->whereNotNull('bukti_file_approval')
                                            ->value('bukti_file_approval');
                if ($approvalFromDb) {
                    // Jika di database hanya nama file, tambahkan path
                    if (strpos($approvalFromDb, '/') === false) {
                        $currentApprovalFile = 'approval_files/' . $approvalFromDb;
                    } else {
                        $currentApprovalFile = $approvalFromDb;
                    }
                }
            }

            return view('pages.purchasing.hps', compact('proyek', 'kalkulasiData', 'canEdit', 'currentApprovalFile'));
        } catch (\Exception $e) {
            return redirect()->route('purchasing.kalkulasi')->with('error', 'Proyek tidak ditemukan');
        }
    }

    public function hpsSummary($id)
    {
        try {
            $user = Auth::user();
            $isSuperadmin = $user->role === 'superadmin';

            if (!$isSuperadmin && $user->role !== 'admin_purchasing') {
                return redirect()->route('purchasing.kalkulasi')->with('error', 'Akses ditolak. Hanya admin purchasing atau superadmin yang dapat mengakses halaman ini.');
            }

            $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'proyekBarang'])->findOrFail($id);

            // Ambil data kalkulasi lengkap dengan relasi barang dan vendor
            $kalkulasiData = KalkulasiHps::with(['barang', 'vendor'])
                ->where('id_proyek', $id)
                ->get();

            return view('pages.purchasing.hps-summary', compact('proyek', 'kalkulasiData'));
        } catch (\Exception $e) {
            return redirect()->route('purchasing.kalkulasi')->with('error', 'Proyek tidak ditemukan');
        }
    }

    public function getProyekItems($id)
    {
        try {
            $proyek = Proyek::with(['proyekBarang'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'proyek' => [
                    'id_proyek' => $proyek->id_proyek,
                    'nama_klien' => $proyek->nama_klien,
                    'instansi' => $proyek->instansi,
                    'harga_total' => $proyek->harga_total,
                ],
                'items' => $proyek->proyekBarang->map(function($item) {
                    return [
                        'nama_barang' => $item->nama_barang,
                        'jumlah' => $item->jumlah,
                        'satuan' => $item->satuan,
                        'harga_satuan' => $item->harga_satuan,
                        'harga_total' => $item->harga_total,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Proyek tidak ditemukan'
            ], 404);
        }
    }

    public function getBarangDetails($id)
    {
        try {
            $barang = Barang::with('vendor:id_vendor,nama_vendor,jenis_perusahaan')
                            ->select('id_barang', 'nama_barang', 'satuan', 'harga_vendor', 'id_vendor')
                            ->findOrFail($id);

            return response()->json([
                'success' => true,
                'barang' => [
                    'id_barang' => $barang->id_barang,
                    'nama_barang' => $barang->nama_barang,
                    'satuan' => $barang->satuan,
                    'harga_vendor' => $barang->harga_vendor,
                    'id_vendor' => $barang->id_vendor,
                    'vendor' => $barang->vendor ? [
                        'id_vendor' => $barang->vendor->id_vendor,
                        'nama_vendor' => $barang->vendor->nama_vendor,
                        'jenis_perusahaan' => $barang->vendor->jenis_perusahaan
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }
    }

    public function previewPenawaran(Request $request)
    {
        try {
            $proyekId = $request->input('id_proyek');

            if (!$proyekId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Proyek tidak ditemukan'
                ], 400);
            }

            // Ambil data proyek dan kalkulasi
            $proyek = Proyek::findOrFail($proyekId);
            $kalkulasiData = KalkulasiHps::with(['barang', 'vendor'])
                                        ->where('id_proyek', $proyekId)
                                        ->get();

            if ($kalkulasiData->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data kalkulasi untuk membuat penawaran'
                ], 400);
            }

            // Simulate penawaran creation untuk preview
            $totalPenawaran = $kalkulasiData->sum('hps');
            $previewDetails = [];

            foreach ($kalkulasiData as $kalkulasi) {
                // Simulasi logika yang sama dengan createPenawaran
                $namaBarang = 'Item Kalkulasi';
                if ($kalkulasi->barang) {
                    $namaBarang = $kalkulasi->barang->nama_barang;
                } elseif ($kalkulasi->keterangan_1) {
                    $namaBarang = $kalkulasi->keterangan_1;
                }

                $proyekBarang = null;
                if ($kalkulasi->barang) {
                    $proyekBarang = $proyek->proyekBarang()
                                         ->where('nama_barang', $kalkulasi->barang->nama_barang)
                                         ->first();
                }

                $qty = 1;
                if (isset($kalkulasi->qty) && $kalkulasi->qty > 0) {
                    $qty = (int) $kalkulasi->qty;
                } elseif ($proyekBarang && $proyekBarang->jumlah > 0) {
                    $qty = (int) $proyekBarang->jumlah;
                }

                $satuan = 'pcs';
                if ($kalkulasi->barang && $kalkulasi->barang->satuan) {
                    $satuan = $kalkulasi->barang->satuan;
                } elseif ($proyekBarang && $proyekBarang->satuan) {
                    $satuan = $proyekBarang->satuan;
                }

                $subtotal = $kalkulasi->hps ?: 0;
                $hargaSatuan = $qty > 0 ? $subtotal / $qty : $subtotal;
                $hargaSatuan = max($hargaSatuan, 100);

                $previewDetails[] = [
                    'nama_barang' => $namaBarang,
                    'qty' => $qty,
                    'satuan' => $satuan,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'spesifikasi' => $this->generateSpesifikasi($kalkulasi)
                ];
            }

            return response()->json([
                'success' => true,
                'preview' => [
                    'proyek' => [
                        'nama_klien' => $proyek->nama_klien,
                        'instansi' => $proyek->instansi
                    ],
                    'total_penawaran' => $totalPenawaran,
                    'total_items' => $kalkulasiData->count(),
                    'details' => $previewDetails
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat preview: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detailPenawaran($proyekId)
    {
        try {
            $proyek = Proyek::with([
                'adminMarketing',
                'adminPurchasing',
                'proyekBarang',
                'penawaranAktif.details.barang'
            ])->findOrFail($proyekId);

            if (!$proyek->penawaranAktif) {
                return redirect()->route('purchasing.kalkulasi')
                    ->with('error', 'Proyek ini belum memiliki penawaran');
            }

            $penawaran = $proyek->penawaranAktif;
            $kalkulasiData = KalkulasiHps::with(['barang', 'vendor'])
                                        ->where('id_proyek', $proyekId)
                                        ->get();

            return view('pages.purchasing.penawaran-detail', compact('proyek', 'penawaran', 'kalkulasiData'));

        } catch (\Exception $e) {
            return redirect()->route('purchasing.kalkulasi')
                ->with('error', 'Penawaran tidak ditemukan');
        }
    }

    public function updatePenawaranStatus(Request $request, $penawaranId)
    {
        try {
            $request->validate([
                'status' => 'required|in:ACC,Ditolak'
            ]);

            $penawaran = Penawaran::findOrFail($penawaranId);

            // Check if status can be updated
            if ($penawaran->status !== 'Menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Status penawaran tidak dapat diubah karena sudah ' . $penawaran->status
                ], 400);
            }

            $penawaran->status = $request->status;
            $penawaran->save();

            Log::info('Penawaran status updated', [
                'penawaran_id' => $penawaranId,
                'new_status' => $request->status,
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status penawaran berhasil diperbarui menjadi ' . $request->status
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating penawaran status', [
                'penawaran_id' => $penawaranId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status penawaran'
            ], 500);
        }
    }

    public function manageApprovalFile(Request $request)
    {
        try {
            $action = $request->input('action', 'upload'); // default to upload
            $idProyek = $request->input('id_proyek');
            
            // Validate basic requirements
            $request->validate([
                'action' => 'required|in:upload,delete',
                'id_proyek' => 'required|exists:proyek,id_proyek'
            ]);

            if ($action === 'upload') {
                return $this->handleUploadApproval($request, $idProyek);
            } else {
                return $this->handleDeleteApproval($request, $idProyek);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = collect($e->errors())->flatten()->implode(', ');
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . $errors
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error managing approval file', [
                'action' => $request->input('action'),
                'id_proyek' => $request->input('id_proyek'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function handleUploadApproval(Request $request, $idProyek)
    {
        // Validate file upload requirements
        $request->validate([
            'bukti_approval' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $file = $request->file('bukti_approval');
        
        // Delete existing approval files for this project
        $existingFiles = glob(storage_path('app/public/approval_files/' . $idProyek . '_approval*'));
        foreach ($existingFiles as $existingFile) {
            if (file_exists($existingFile)) {
                unlink($existingFile);
            }
        }
        
        // Create new filename with timestamp
        $fileName = $idProyek . '_approval_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Store file in storage/app/public/approval_files
        $filePath = $file->storeAs('approval_files', $fileName, 'public');
        
        Log::info('Approval file uploaded and stored temporarily', [
            'id_proyek' => $idProyek,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'uploaded_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File bukti approval berhasil diupload',
            'file_path' => asset('storage/' . $filePath),
            'file_name' => $fileName
        ]);
    }

    private function handleDeleteApproval(Request $request, $idProyek)
    {
        // Delete approval files for this project
        $approvalFiles = glob(storage_path('app/public/approval_files/' . $idProyek . '_approval*'));
        $deletedFiles = [];
        
        foreach ($approvalFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
                $deletedFiles[] = basename($file);
            }
        }
        
        // Also clear from database if exists
        KalkulasiHps::where('id_proyek', $idProyek)
                    ->update(['bukti_file_approval' => null]);

        Log::info('Approval files deleted', [
            'id_proyek' => $idProyek,
            'deleted_files' => $deletedFiles,
            'deleted_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File bukti approval berhasil dihapus'
        ]);
    }

    public function saveKalkulasiWithHistory(Request $request)
    {
        try {
            $user = Auth::user();
            $proyekId = $request->input('id_proyek');
            $isSuperadmin = $user->role === 'superadmin';

            if (!$isSuperadmin && $user->role !== 'admin_purchasing') {
                return response()->json([
                    'message' => 'Akses ditolak. Hanya admin purchasing atau superadmin yang dapat melakukan kalkulasi.'
                ], 403);
            }

            $proyek = Proyek::find($proyekId);
            if (!$proyek || (!$isSuperadmin && $proyek->id_admin_purchasing != $user->id_user)) {
                return response()->json([
                    'message' => 'Akses ditolak. Anda tidak memiliki akses untuk melakukan kalkulasi pada proyek ini.'
                ], 403);
            }

            Log::info('=== SAVE KALKULASI WITH HISTORY START ===', [
                'project_id' => $proyekId,
                'user_id' => $user->id_user,
                'kalkulasi_count' => count($request->kalkulasi ?? [])
            ]);

            DB::beginTransaction();

            // Simpan data lama ke riwayat sebelum mengupdate
            $existingKalkulasi = KalkulasiHps::where('id_proyek', $proyekId)->get();
            
            if ($existingKalkulasi->isNotEmpty()) {
                Log::info('Saving existing kalkulasi to history', ['count' => $existingKalkulasi->count()]);
                
                foreach ($existingKalkulasi as $oldData) {
                    RiwayatHps::create([
                        'id_proyek' => $oldData->id_proyek,
                        'id_barang' => $oldData->id_barang,
                        'id_vendor' => $oldData->id_vendor,
                        'nama_barang' => $oldData->barang ? $oldData->barang->nama_barang : $oldData->keterangan_1,
                        'nama_vendor' => $oldData->vendor ? $oldData->vendor->nama_vendor : null,
                        'jenis_vendor' => $oldData->jenis_vendor,
                        'satuan' => $oldData->barang ? $oldData->barang->satuan : 'pcs',
                        'qty' => $oldData->qty,
                        'harga_vendor' => $oldData->harga_vendor,
                        'harga_diskon' => $oldData->harga_akhir,
                        'nilai_diskon' => $oldData->diskon_amount,
                        'total_diskon' => $oldData->total_diskon,
                        'total_harga' => $oldData->total_harga_hpp,
                        'jumlah_volume' => $oldData->jumlah_volume,
                        'harga_yang_diharapkan' => $oldData->harga_yang_diharapkan ?? 0,
                        'persen_kenaikan' => $oldData->kenaikan_percent,
                        'proyeksi_kenaikan' => $oldData->proyeksi_kenaikan,
                        'ppn_dinas' => $oldData->ppn,
                        'pph_dinas' => $oldData->pph,
                        'hps' => $oldData->hps,
                        'harga_per_pcs' => $oldData->harga_per_pcs ?? 0,
                        'harga_pagu_dinas_per_pcs' => $oldData->harga_pagu_dinas_per_pcs ?? 0,
                        'pagu_total' => $oldData->nilai_pagu_anggaran,
                        'selisih_pagu_hps' => $oldData->nilai_selisih,
                        'nilai_sp' => $oldData->nilai_sp ?? 0,
                        'dpp' => $oldData->nilai_dpp,
                        'asumsi_nilai_cair' => $oldData->nilai_asumsi_cair,
                        'ongkir' => $oldData->ongkir,
                        'omzet_dinas_percent' => $oldData->omzet_dinas_percent,
                        'omzet_nilai_dinas' => $oldData->omzet_dinas,
                        'bendera_percent' => $oldData->bendera_percent ?? 0,
                        'gross_nilai_bendera' => $oldData->gross_bendera,
                        'bank_cost_percent' => $oldData->bank_cost_percent ?? 0,
                        'gross_nilai_bank_cost' => $oldData->gross_bank_cost,
                        'biaya_ops_percent' => $oldData->biaya_ops_percent ?? 0,
                        'gross_nilai_biaya_ops' => $oldData->gross_biaya_ops,
                        'sub_total_biaya_tidak_langsung' => $oldData->sub_total_tidak_langsung,
                        'gross_income' => $oldData->gross_income,
                        'gross_income_persentase' => $oldData->gross_income_percent,
                        'nilai_nett_income' => $oldData->nett_income,
                        'nett_income_persentase' => $oldData->nett_income_percent,
                        'keterangan_1' => $oldData->keterangan_1,
                        'keterangan_2' => $oldData->keterangan_2,
                        'created_by' => $user->id_user,
                        'action_type' => 'edit',
                        'action_description' => 'Data disimpan ke riwayat sebelum kalkulasi ulang'
                    ]);
                }
                Log::info('History saved successfully');
            }

            // Sekarang simpan kalkulasi baru (copy logic dari saveKalkulasi tanpa nested transaction)
            
            // Get existing approval file before deleting records
            $existingApprovalFile = KalkulasiHps::where('id_proyek', $proyekId)
                                               ->whereNotNull('bukti_file_approval')
                                               ->value('bukti_file_approval');

            // Hapus kalkulasi lama
            KalkulasiHps::where('id_proyek', $proyekId)->delete();

            // Cek approval files
            $approvalFiles = glob(storage_path('app/public/approval_files/*' . $proyekId . '_approval*'));
            $approvalFileName = null;
            if (!empty($approvalFiles)) {
                $latestFile = array_reduce($approvalFiles, function($latest, $file) {
                    return ($latest === null || filemtime($file) > filemtime($latest)) ? $file : $latest;
                });
                if ($latestFile) {
                    $approvalFileName = basename($latestFile);
                }
            }

            // Simpan kalkulasi baru
            foreach ($request->kalkulasi as $index => $item) {
                $kalkulasi = KalkulasiHps::create([
                    'id_proyek' => $proyekId,
                    'id_barang' => $item['id_barang'] ?? null,
                    'id_vendor' => $item['id_vendor'] ?? null,
                    'qty' => $item['qty'] ?? 1,
                    'harga_vendor' => $item['harga_vendor'] ?? 0,
                    'harga_diskon' => $item['harga_diskon'] ?? 0,
                    'diskon_amount' => $item['nilai_diskon'] ?? 0,
                    'total_diskon' => $item['total_diskon'] ?? 0,
                    'harga_akhir' => $item['harga_diskon'] ?? 0,
                    'total_harga_hpp' => $item['total_harga'] ?? 0,
                    'jumlah_volume' => $item['jumlah_volume'] ?? 0,
                    'harga_yang_diharapkan' => $item['harga_yang_diharapkan'] ?? 0,
                    'kenaikan_percent' => $item['persen_kenaikan'] ?? 0,
                    'proyeksi_kenaikan' => $item['proyeksi_kenaikan'] ?? 0,
                    'pph' => $item['pph_dinas'] ?? 0,
                    'ppn' => $item['ppn_dinas'] ?? 0,
                    'ongkir' => $item['ongkir'] ?? 0,
                    'hps' => $item['hps'] ?? 0,
                    'harga_per_pcs' => $item['harga_per_pcs'] ?? 0,
                    'harga_pagu_dinas_per_pcs' => $item['harga_pagu_dinas_per_pcs'] ?? 0,
                    'nilai_sp' => $item['nilai_sp'] ?? 0,
                    'nilai_tkdn_percent' => $item['nilai_tkdn_percent'] ?? 0,
                    'jenis_vendor' => $item['jenis_vendor'] ?? null,
                    'nilai_pagu_anggaran' => $item['pagu_total'] ?? 0,
                    'nilai_penawaran_hps' => $item['nilai_penawaran_hps'] ?? 0,
                    'nilai_pesanan' => $item['nilai_pesanan'] ?? 0,
                    'nilai_selisih' => $item['selisih_pagu_hps'] ?? 0,
                    'nilai_dpp' => $item['dpp'] ?? 0,
                    'ppn_percent' => $item['ppn_percent'] ?? 11,
                    'pph_badan_percent' => $item['pph_badan_percent'] ?? 1.5,
                    'nilai_ppn' => $item['nilai_ppn'] ?? 0,
                    'nilai_pph_badan' => $item['pph_from_dpp'] ?? 0,
                    'nilai_asumsi_cair' => $item['asumsi_nilai_cair'] ?? 0,
                    'sub_total_langsung' => $item['sub_total_langsung'] ?? 0,
                    'bank_cost' => $item['gross_nilai_bank_cost'] ?? 0,
                    'biaya_ops' => $item['gross_nilai_biaya_ops'] ?? 0,
                    'bendera' => $item['gross_nilai_bendera'] ?? 0,
                    'omzet_dinas_percent' => $item['omzet_dinas_percent'] ?? 0,
                    'omzet_dinas' => $item['omzet_nilai_dinas'] ?? 0,
                    'bendera_percent' => $item['bendera_percent'] ?? 0,
                    'bank_cost_percent' => $item['bank_cost_percent'] ?? 0,
                    'biaya_ops_percent' => $item['biaya_ops_percent'] ?? 0,
                    'gross_bendera' => $item['gross_nilai_bendera'] ?? 0,
                    'gross_bank_cost' => $item['gross_nilai_bank_cost'] ?? 0,
                    'gross_biaya_ops' => $item['gross_nilai_biaya_ops'] ?? 0,
                    'sub_total_tidak_langsung' => $item['sub_total_biaya_tidak_langsung'] ?? 0,
                    'nett' => $item['nilai_nett_income'] ?? 0,
                    'nett_percent' => $item['nett_income_persentase'] ?? 0,
                    'nilai_nett_pcs' => $item['nilai_nett_pcs'] ?? 0,
                    'total_nett_pcs' => $item['total_nilai_nett_per_pcs'] ?? 0,
                    'gross_income' => $item['gross_income'] ?? 0,
                    'gross_income_percent' => $item['gross_income_persentase'] ?? 0,
                    'nett_income' => $item['nilai_nett_income'] ?? 0,
                    'nett_income_percent' => $item['nett_income_persentase'] ?? 0,
                    'keterangan_1' => $item['keterangan_1'] ?? null,
                    'keterangan_2' => $item['keterangan_2'] ?? null,
                    'bukti_file_approval' => $approvalFileName,
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }

            // Update total nilai proyek
            $totalNilaiProyek = collect($request->kalkulasi)->sum('hps');
            if ($totalNilaiProyek > 0) {
                $proyek->update(['harga_total' => $totalNilaiProyek]);
            }

            // ===== UPDATE PENAWARAN JIKA ADA =====
            // Cek apakah proyek ini sudah memiliki penawaran aktif
            $existingPenawaran = Penawaran::where('id_proyek', $proyekId)->first();
            
            if ($existingPenawaran) {
                Log::info('Updating existing penawaran', [
                    'penawaran_id' => $existingPenawaran->id_penawaran,
                    'old_total' => $existingPenawaran->total_penawaran,
                    'new_total' => $totalNilaiProyek
                ]);

                // Update total penawaran
                $existingPenawaran->update([
                    'total_penawaran' => $totalNilaiProyek,
                    'updated_at' => now()
                ]);

                // Hapus detail penawaran lama
                PenawaranDetail::where('id_penawaran', $existingPenawaran->id_penawaran)->delete();
                
                // Buat detail penawaran baru berdasarkan kalkulasi yang baru disimpan
                $newKalkulasiData = KalkulasiHps::with(['barang', 'vendor'])
                                                ->where('id_proyek', $proyekId)
                                                ->get();

                foreach ($newKalkulasiData as $kalkulasi) {
                    // Logic sama dengan createPenawaran
                    $namaBarang = 'Item Kalkulasi';
                    if ($kalkulasi->barang) {
                        $namaBarang = $kalkulasi->barang->nama_barang;
                    } elseif ($kalkulasi->keterangan_1) {
                        $namaBarang = $kalkulasi->keterangan_1;
                    } elseif ($kalkulasi->keterangan_2) {
                        $namaBarang = $kalkulasi->keterangan_2;
                    }

                    // Cari data proyek barang yang sesuai
                    $proyekBarang = null;
                    if ($kalkulasi->barang) {
                        $proyekBarang = $proyek->proyekBarang()
                                             ->where('nama_barang', $kalkulasi->barang->nama_barang)
                                             ->first();
                    }

                    // Logika qty
                    $qty = 1;
                    if (isset($kalkulasi->qty) && $kalkulasi->qty > 0) {
                        $qty = (int) $kalkulasi->qty;
                    } elseif ($proyekBarang && $proyekBarang->jumlah > 0) {
                        $qty = (int) $proyekBarang->jumlah;
                    }

                    // Logika satuan
                    $satuan = 'pcs';
                    if ($kalkulasi->barang && $kalkulasi->barang->satuan) {
                        $satuan = $kalkulasi->barang->satuan;
                    } elseif ($proyekBarang && $proyekBarang->satuan) {
                        $satuan = $proyekBarang->satuan;
                    }

                    // Logika harga
                    $subtotal = $kalkulasi->hps ?: 0;
                    $hargaSatuan = 0;

                    if ($subtotal > 0 && $qty > 0) {
                        $hargaSatuan = $subtotal / $qty;
                    } else {
                        if ($kalkulasi->harga_vendor && $kalkulasi->harga_vendor > 0) {
                            $hargaSatuan = $kalkulasi->harga_vendor;
                            $subtotal = $hargaSatuan * $qty;
                        } elseif ($proyekBarang && $proyekBarang->harga_satuan > 0) {
                            $hargaSatuan = $proyekBarang->harga_satuan;
                            $subtotal = $hargaSatuan * $qty;
                        } elseif ($kalkulasi->barang && $kalkulasi->barang->harga_vendor > 0) {
                            $hargaSatuan = $kalkulasi->barang->harga_vendor;
                            $subtotal = $hargaSatuan * $qty;
                        } else {
                            $hargaSatuan = 1000;
                            $subtotal = $hargaSatuan * $qty;
                        }
                    }

                    // Validasi final
                    $hargaSatuan = max($hargaSatuan, 100);
                    $subtotal = max($subtotal, $hargaSatuan);
                    $qty = max($qty, 1);

                    // Tentukan id_barang
                    $idBarang = $kalkulasi->id_barang;
                    if (!$idBarang && $kalkulasi->barang) {
                        $idBarang = $kalkulasi->barang->id_barang;
                    }

                    // Buat detail penawaran baru
                    PenawaranDetail::create([
                        'id_penawaran' => $existingPenawaran->id_penawaran,
                        'id_barang' => $idBarang,
                        'nama_barang' => $namaBarang,
                        'spesifikasi' => $this->generateSpesifikasi($kalkulasi),
                        'qty' => $qty,
                        'satuan' => $satuan,
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $subtotal
                    ]);
                }

                Log::info('Penawaran updated successfully', [
                    'penawaran_id' => $existingPenawaran->id_penawaran,
                    'new_total' => $totalNilaiProyek,
                    'detail_count' => $newKalkulasiData->count()
                ]);
            }

            DB::commit();
            Log::info('=== SAVE KALKULASI WITH HISTORY COMPLETED ===', [
                'success' => true,
                'total_items_saved' => count($request->kalkulasi),
                'total_nilai_proyek' => $totalNilaiProyek
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kalkulasi berhasil disimpan dan riwayat telah diperbarui',
                'total_items_proyek' => $totalNilaiProyek,
                'total_items_saved' => count($request->kalkulasi)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving kalkulasi with history', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'project_id' => $proyekId ?? 'unknown'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan kalkulasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ajukanPembayaran(Request $request)
    {
        try {
            $user = Auth::user();
            $proyekId = $request->input('id_proyek');
            $isSuperadmin = $user->role === 'superadmin';

            if (!$isSuperadmin && $user->role !== 'admin_purchasing') {
                return response()->json([
                    'message' => 'Akses ditolak. Hanya admin purchasing atau superadmin yang dapat mengajukan pembayaran.'
                ], 403);
            }

            $proyek = Proyek::find($proyekId);
            if (!$proyek || (!$isSuperadmin && $proyek->id_admin_purchasing != $user->id_user)) {
                return response()->json([
                    'message' => 'Akses ditolak. Anda tidak memiliki akses untuk mengajukan pembayaran pada proyek ini.'
                ], 403);
            }

            // Validasi apakah proyek memiliki kalkulasi
            $kalkulasiData = KalkulasiHps::where('id_proyek', $proyekId)->get();
            if ($kalkulasiData->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data kalkulasi untuk diajukan ke pembayaran'
                ], 400);
            }

            // Validasi status proyek harus dari penawaran berhasil (ACC)
            $penawaranACC = $proyek->penawaranAktif()->where('status', 'ACC')->first();
            if (!$penawaranACC) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyek harus memiliki penawaran yang sudah ACC sebelum bisa diajukan ke pembayaran'
                ], 400);
            }

            DB::beginTransaction();

            // Update status proyek menjadi 'Pembayaran'
            $proyek->update([
                'status' => 'Pembayaran',
                'updated_at' => now()
            ]);

            // Log aktivitas
            Log::info('Proyek diajukan ke pembayaran', [
                'id_proyek' => $proyekId,
                'nama_klien' => $proyek->nama_klien,
                'total_hps' => $kalkulasiData->sum('hps'),
                'diajukan_oleh' => $user->id_user,
                'nama_user' => $user->nama
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil diajukan ke pembayaran',
                'data' => [
                    'id_proyek' => $proyekId,
                    'status_baru' => 'Pembayaran',
                    'total_nilai' => $kalkulasiData->sum('hps'),
                    'tanggal_ajukan' => now()->format('d/m/Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error mengajukan pembayaran', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRiwayatHps($proyekId)
    {
        try {
            $user = Auth::user();
            $isSuperadmin = $user->role === 'superadmin';

            if (!$isSuperadmin && $user->role !== 'admin_purchasing') {
                return response()->json([
                    'message' => 'Akses ditolak. Hanya admin purchasing atau superadmin yang dapat melihat riwayat.'
                ], 403);
            }

            $proyek = Proyek::find($proyekId);
            if (!$proyek || (!$isSuperadmin && $proyek->id_admin_purchasing != $user->id_user)) {
                return response()->json([
                    'message' => 'Akses ditolak. Anda tidak memiliki akses untuk melihat riwayat proyek ini.'
                ], 403);
            }

            $riwayatData = RiwayatHps::with(['creator:id_user,nama'])
                                    ->where('id_proyek', $proyekId)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

            return response()->json([
                'success' => true,
                'data' => $riwayatData->map(function($item) {
                    return [
                        'id_riwayat_hps' => $item->id_riwayat_hps,
                        'nama_barang' => $item->nama_barang,
                        'nama_vendor' => $item->nama_vendor,
                        'qty' => $item->qty,
                        'harga_vendor' => $item->harga_vendor,
                        'hps' => $item->hps,
                        'action_type' => $item->action_type,
                        'action_description' => $item->action_description,
                        'created_by' => $item->creator ? $item->creator->nama : 'Unknown',
                        'created_at' => $item->created_at->format('d/m/Y H:i'),
                        'keterangan_1' => $item->keterangan_1,
                        'keterangan_2' => $item->keterangan_2
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting riwayat HPS', [
                'error' => $e->getMessage(),
                'proyek_id' => $proyekId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showRiwayatDetail($proyekId, Request $request)
    {
        try {
            $user = Auth::user();
            $isSuperadmin = $user->role === 'superadmin';
            
            // Authorization check
            if (!$isSuperadmin && $user->role !== 'admin_purchasing') {
                return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya admin purchasing atau superadmin yang dapat melihat riwayat kalkulasi.');
            }

            $proyek = Proyek::find($proyekId);
            if (!$proyek) {
                return redirect()->route('purchasing.kalkulasi')->with('error', 'Proyek tidak ditemukan.');
            }

            // Additional authorization check for non-superadmin
            if (!$isSuperadmin && $proyek->id_admin_purchasing != $user->id_user) {
                return redirect()->route('purchasing.kalkulasi')->with('error', 'Akses ditolak. Anda tidak memiliki akses untuk melihat riwayat kalkulasi pada proyek ini.');
            }

            // Build query for history
            $query = RiwayatHps::where('id_proyek', $proyekId)
                ->with(['createdBy'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            if ($request->filled('user_id')) {
                $query->where('created_by', $request->user_id);
            }

            // Handle export
            if ($request->has('export') && $request->export === 'excel') {
                return $this->exportRiwayatExcel($query->get(), $proyek);
            }

            // Paginate results
            $riwayatData = $query->paginate(20)->appends($request->query());

            // Get unique users for filter
            $users = User::whereIn('id_user', 
                RiwayatHps::where('id_proyek', $proyekId)
                    ->distinct()
                    ->pluck('created_by')
            )->get();

            // Generate historical snapshots - each timestamp shows complete project state at that time
            $riwayatSummaryData = [];
            
            // Get all distinct timestamps when changes occurred
            $timestamps = RiwayatHps::where('id_proyek', $proyekId)
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as timestamp')
                ->distinct()
                ->orderBy('timestamp', 'desc')
                ->pluck('timestamp');
            
            foreach ($timestamps as $timestamp) {
                // Get all items for this specific timestamp (complete snapshot)
                $snapshotItems = RiwayatHps::where('id_proyek', $proyekId)
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") = ?', [$timestamp])
                    ->get();
                
                $summaryData = [];
                $totalHppModal = 0;
                $totalHps = 0;
                $totalDiskon = 0;
                $totalVolume = 0;
                $totalNett = 0;
                $totalItems = 0;
                
                $totalDpp = 0;
                $totalAsumsiCair = 0;
                $totalAsumsiOngkir = 0;
                
                // Biaya tidak langsung
                $totalOmzetDinas = 0;
                $totalBendera = 0;
                $totalBankCost = 0;
                $totalBiayaOps = 0;
                $subTotalBiayaTidakLangsung = 0;
                
                foreach ($snapshotItems as $item) {
                    $hargaAwal = $item->harga_vendor ?? 0;
                    $diskon = $item->nilai_diskon ?? 0;
                    $hargaAkhir = $item->harga_diskon ?? $hargaAwal;
                    $qty = $item->qty ?? 0;
                    $volume = $item->jumlah_volume ?? 0;
                    $totalHpsItem = $item->hps ?? 0;
                    $nettIncome = $item->nilai_nett_income ?? 0;
                    $nettPercent = $item->nett_income_persentase ?? 0;
                    $totalNettItem = $nettIncome;
                    
                    // Additional fields for template
                    $dpp = $item->dpp ?? 0;
                    $asumsiCair = $item->asumsi_nilai_cair ?? 0;
                    $ongkir = $item->ongkir ?? 0;
                    
                    // Biaya tidak langsung
                    $omzetDinas = $item->omzet_nilai_dinas ?? 0;
                    $bendera = $item->gross_nilai_bendera ?? 0;
                    $bankCost = $item->gross_nilai_bank_cost ?? 0;
                    $biayaOps = $item->gross_nilai_biaya_ops ?? 0;
                    $subBiayaTidakLangsung = $item->sub_total_biaya_tidak_langsung ?? 0;
                    $ongkir = $item->ongkir ?? 0;

                    $summaryData[] = [
                        'nama_barang' => $item->nama_barang ?? 'Unknown',
                        'vendor' => $item->nama_vendor ?? 'Unknown',
                        'jenis_vendor' => $item->jenis_vendor ?? '-',
                        'harga_awal' => $hargaAwal,
                        'diskon' => $diskon,
                        'harga_akhir' => $hargaAkhir,
                        'qty' => $qty,
                        'satuan' => $item->satuan ?? 'pcs',
                        'volume' => $volume,
                        'harga_yang_diharapkan' => $item->harga_yang_diharapkan ?? 0,
                        'total_hps' => $totalHpsItem,
                        'nett_per_pcs' => $nettIncome,
                        'nett_persen' => $nettPercent,
                        'total_nett' => $totalNettItem
                    ];

                    $totalHppModal += $hargaAkhir * $qty;
                    $totalHps += $totalHpsItem;
                    $totalDiskon += $diskon * $qty;
                    $totalVolume += $volume;
                    $totalNett += $totalNettItem;
                    $totalDpp += $dpp;
                    $totalAsumsiCair += $asumsiCair;
                    $totalAsumsiOngkir += $ongkir;
                    
                    // Accumulate biaya tidak langsung
                    $totalOmzetDinas += $omzetDinas;
                    $totalBendera += $bendera;
                    $totalBankCost += $bankCost;
                    $totalBiayaOps += $biayaOps;
                    $subTotalBiayaTidakLangsung += $subBiayaTidakLangsung;
                    
                    $totalItems++;
                }

                // Calculate average nett percentage safely using correct formula
                // Formula: (Total Nett Income / Total Asumsi Cair) * 100
                $avgNettPersen = ($totalAsumsiCair > 0) ? (($totalNett / $totalAsumsiCair) * 100) : 0;

                $riwayatSummaryData[$timestamp] = [
                    'summaryData' => $summaryData,
                    'totalHppModal' => $totalHppModal,
                    'totalHps' => $totalHps,
                    'totalDiskon' => $totalDiskon,
                    'totalVolume' => $totalVolume,
                    'totalNett' => $totalNett,
                    'totalItems' => $totalItems,
                    'avgNettPersen' => $avgNettPersen,
                    'totalDpp' => $totalDpp,
                    'totalAsumsiCair' => $totalAsumsiCair,
                    'totalAsumsiOngkir' => $totalAsumsiOngkir,
                    'totalOmzetDinas' => $totalOmzetDinas,
                    'totalBendera' => $totalBendera,
                    'totalBankCost' => $totalBankCost,
                    'totalBiayaOps' => $totalBiayaOps,
                    'subTotalBiayaTidakLangsung' => $subTotalBiayaTidakLangsung
                ];
            }
            
            // Reverse the order to show newest first (matching the display order)
            $riwayatSummaryData = array_reverse($riwayatSummaryData, true);

            return view('pages.purchasing.riwayat-hps', compact(
                'proyek', 
                'riwayatData', 
                'users', 
                'riwayatSummaryData'
            ));

        } catch (\Exception $e) {
            Log::error('Error showing detailed history: ' . $e->getMessage());
            return redirect()->route('purchasing.kalkulasi')->with('error', 'Terjadi kesalahan saat memuat riwayat kalkulasi.');
        }
    }

    /**
     * Export history to Excel
     */
    private function exportRiwayatExcel($riwayatData, $proyek)
    {
        try {
            // Create a simple CSV export
            $filename = 'riwayat_hps_' . $proyek->kode_proyek . '_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($riwayatData) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");
                
                // Header
                fputcsv($file, [
                    'Tanggal',
                    'User',
                    'Barang',
                    'Vendor',
                    'Action',
                    'Qty Sebelum',
                    'Qty Sesudah',
                    'Harga Vendor Sebelum',
                    'Harga Vendor Sesudah',
                    'Harga Diskon Sebelum',
                    'Harga Diskon Sesudah',
                    'HPS Sebelum',
                    'HPS Sesudah',
                    'Nett % Sebelum',
                    'Nett % Sesudah'
                ]);

                foreach ($riwayatData as $item) {
                    $currentData = json_decode($item->data_current, true);
                    $previousData = json_decode($item->data_previous, true);

                    fputcsv($file, [
                        $item->created_at->format('d/m/Y H:i:s'),
                        $item->createdBy->nama ?? 'Unknown',
                        $item->nama_barang,
                        $item->nama_vendor,
                        ucfirst($item->action_type),
                        $previousData['qty'] ?? '-',
                        $currentData['qty'] ?? '-',
                        $previousData['harga_vendor'] ?? '-',
                        $currentData['harga_vendor'] ?? '-',
                        $previousData['harga_diskon'] ?? '-',
                        $currentData['harga_diskon'] ?? '-',
                        $previousData['hps'] ?? '-',
                        $currentData['hps'] ?? '-',
                        isset($previousData['nett_income_persentase']) ? number_format($previousData['nett_income_persentase'], 2) . '%' : '-',
                        isset($currentData['nett_income_persentase']) ? number_format($currentData['nett_income_persentase'], 2) . '%' : '-'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error exporting history: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengexport data riwayat.');
        }
    }

    public function hpsAjukan($id)
    {
        try {
            $user = Auth::user();
            $isSuperadmin = $user->role === 'superadmin';

            if (!$isSuperadmin && $user->role !== 'admin_purchasing') {
                return redirect()->route('purchasing.kalkulasi')->with('error', 'Akses ditolak. Hanya admin purchasing atau superadmin yang dapat mengakses halaman ini.');
            }

            $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'proyekBarang'])->findOrFail($id);
            $canEdit = $isSuperadmin || ($proyek->id_admin_purchasing == $user->id_user);

            // Get existing kalkulasi data
            $kalkulasiData = KalkulasiHps::with(['barang', 'vendor'])
                                        ->where('id_proyek', $id)
                                        ->get();

            // Transform data untuk kompatibilitas dengan frontend
            $kalkulasiData = $kalkulasiData->map(function($item) {
                $data = $item->toArray();

                // Map database fields ke frontend fields untuk konsistensi
                $data['nama_barang'] = $item->barang ? $item->barang->nama_barang : ($data['keterangan_1'] ?? '');
                $data['nama_vendor'] = $item->vendor ? $item->vendor->nama_vendor : '';
                $data['satuan'] = $item->barang ? $item->barang->satuan : 'pcs';

                // REVISED: Map field names untuk logika baru
                // LOGIKA BARU: harga_diskon = INPUT, nilai_diskon = CALCULATED
                $data['harga_diskon'] = $data['harga_diskon'] ?? $data['harga_akhir'] ?? 0; // INPUT field
                $data['nilai_diskon'] = $data['diskon_amount'] ?? 0; // CALCULATED field
                $data['total_harga'] = $data['total_harga_hpp'] ?? 0;
                $data['persen_kenaikan'] = $data['kenaikan_percent'] ?? 0;
                $data['pph_dinas'] = $data['pph'] ?? 0;
                $data['ppn_dinas'] = $data['ppn'] ?? 0;
                $data['pagu_total'] = $data['nilai_pagu_anggaran'] ?? 0;
                // Mapping untuk field baru - gunakan nama yang sama dengan JavaScript
                $data['harga_pagu_dinas_per_pcs'] = $data['harga_pagu_dinas_per_pcs'] ?? 0;
                $data['nilai_sp'] = $data['nilai_sp'] ?? 0;
                $data['bendera_percent'] = $data['bendera_percent'] ?? 0;
                $data['bank_cost_percent'] = $data['bank_cost_percent'] ?? 0;
                $data['biaya_ops_percent'] = $data['biaya_ops_percent'] ?? 0;
                $data['selisih_pagu_hps'] = $data['nilai_selisih'] ?? 0;
                $data['dpp'] = $data['nilai_dpp'] ?? 0;
                $data['pph_from_dpp'] = $data['nilai_pph_badan'] ?? 0;
                $data['asumsi_nilai_cair'] = $data['nilai_asumsi_cair'] ?? 0;
                $data['omzet_nilai_dinas'] = $data['omzet_dinas'] ?? 0;
                $data['gross_nilai_bendera'] = $data['gross_bendera'] ?? $data['bendera'] ?? 0;
                $data['gross_nilai_bank_cost'] = $data['gross_bank_cost'] ?? $data['bank_cost'] ?? 0;
                $data['gross_nilai_biaya_ops'] = $data['gross_biaya_ops'] ?? $data['biaya_ops'] ?? 0;
                $data['sub_total_biaya_tidak_langsung'] = $data['sub_total_tidak_langsung'] ?? 0;
                $data['nilai_nett_income'] = $data['nett_income'] ?? $data['nett'] ?? 0;
                $data['nett_income_persentase'] = $data['nett_income_percent'] ?? $data['nett_percent'] ?? 0;
                $data['total_nilai_nett_per_pcs'] = $data['total_nett_pcs'] ?? 0;
                $data['gross_income_persentase'] = $data['gross_income_percent'] ?? 0;

                return $data;
            });

            // Get current approval file if exists
            $currentApprovalFile = null;
            $approvalFiles = glob(storage_path('app/public/approval_files/*' . $id . '_approval*'));
            if (!empty($approvalFiles)) {
                // Ambil file terbaru jika ada beberapa
                $latestFile = array_reduce($approvalFiles, function($latest, $file) {
                    return ($latest === null || filemtime($file) > filemtime($latest)) ? $file : $latest;
                });
                if ($latestFile) {
                    $currentApprovalFile = 'approval_files/' . basename($latestFile);
                }
            }
            
            // Jika tidak ada file di storage, cek dari database
            if (!$currentApprovalFile) {
                $approvalFromDb = KalkulasiHps::where('id_proyek', $id)
                                            ->whereNotNull('bukti_file_approval')
                                            ->value('bukti_file_approval');
                if ($approvalFromDb) {
                    // Jika di database hanya nama file, tambahkan path
                    if (strpos($approvalFromDb, '/') === false) {
                        $currentApprovalFile = 'approval_files/' . $approvalFromDb;
                    } else {
                        $currentApprovalFile = $approvalFromDb;
                    }
                }
            }

            return view('pages.purchasing.hps-ajukan', compact('proyek', 'kalkulasiData', 'canEdit', 'currentApprovalFile'));
        } catch (\Exception $e) {
            return redirect()->route('purchasing.kalkulasi')->with('error', 'Proyek tidak ditemukan');
        }
    }
}
