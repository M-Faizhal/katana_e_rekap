<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\KalkulasiHps;
use App\Models\Barang;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

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
            DB::beginTransaction();

            $proyekId = $request->id_proyek;
            
            // Hapus kalkulasi lama jika ada
            KalkulasiHps::where('id_proyek', $proyekId)->delete();

            // Simpan kalkulasi baru
            foreach ($request->kalkulasi as $item) {
                $kalkulasi = KalkulasiHps::create([
                    'id_proyek' => $proyekId,
                    'id_barang' => $item['id_barang'],
                    'id_vendor' => $item['id_vendor'],
                    'qty' => $item['qty'],
                    'harga_vendor' => $item['harga_vendor'],
                    'diskon_amount' => $item['diskon_amount'] ?? 0,
                    'total_diskon' => $item['total_diskon'] ?? 0,
                    'harga_akhir' => $item['harga_akhir'] ?? 0,
                    'total_harga_hpp' => $item['total_harga_hpp'],
                    'jumlah_volume' => $item['jumlah_volume'] ?? 0,
                    'kenaikan_percent' => $item['kenaikan_percent'] ?? 0,
                    'proyeksi_kenaikan' => $item['proyeksi_kenaikan'] ?? 0,
                    'pph' => $item['pph'] ?? 0,
                    'ppn' => $item['ppn'] ?? 0,
                    'ongkir' => $item['ongkir'] ?? 0,
                    'hps' => $item['hps'],
                    'nilai_tkdn_percent' => $item['nilai_tkdn_percent'] ?? 0,
                    'jenis_vendor' => $item['jenis_vendor'] ?? null,
                    'nilai_pagu_anggaran' => $item['nilai_pagu_anggaran'] ?? 0,
                    'nilai_penawaran_hps' => $item['nilai_penawaran_hps'] ?? 0,
                    'nilai_pesanan' => $item['nilai_pesanan'] ?? 0,
                    'nilai_selisih' => $item['nilai_selisih'] ?? 0,
                    'nilai_dpp' => $item['nilai_dpp'] ?? 0,
                    'ppn_percent' => $item['ppn_percent'] ?? 11,
                    'pph_badan_percent' => $item['pph_badan_percent'] ?? 1.5,
                    'nilai_ppn' => $item['nilai_ppn'] ?? 0,
                    'nilai_pph_badan' => $item['nilai_pph_badan'] ?? 0,
                    'nilai_asumsi_cair' => $item['nilai_asumsi_cair'] ?? 0,
                    'sub_total_langsung' => $item['sub_total_langsung'] ?? 0,
                    'bank_cost' => $item['bank_cost'] ?? 0,
                    'biaya_ops' => $item['biaya_ops'] ?? 0,
                    'bendera' => $item['bendera'] ?? 0,
                    'omzet_dinas_percent' => $item['omzet_dinas_percent'] ?? 0,
                    'omzet_dinas' => $item['omzet_dinas'] ?? 0,
                    'gross_bendera' => $item['gross_bendera'] ?? 0,
                    'gross_bank_cost' => $item['gross_bank_cost'] ?? 0,
                    'gross_biaya_ops' => $item['gross_biaya_ops'] ?? 0,
                    'sub_total_tidak_langsung' => $item['sub_total_tidak_langsung'] ?? 0,
                    'nett' => $item['nett'],
                    'nett_percent' => $item['nett_percent'],
                    'nilai_nett_pcs' => $item['nilai_nett_pcs'] ?? 0,
                    'total_nett_pcs' => $item['total_nett_pcs'] ?? 0,
                    'gross_income' => $item['gross_income'] ?? 0,
                    'gross_income_percent' => $item['gross_income_percent'] ?? 0,
                    'nett_income' => $item['nett_income'] ?? 0,
                    'nett_income_percent' => $item['nett_income_percent'] ?? 0,
                    'catatan' => $item['catatan'] ?? null,
                    'keterangan_1' => $item['keterangan_1'] ?? null,
                    'keterangan_2' => $item['keterangan_2'] ?? null,
                ]);

                // Calculate derived values
                $kalkulasi->calculateValues();
                $kalkulasi->save();
            }

            // Calculate project-level totals
            KalkulasiHps::calculateProjectTotals($proyekId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kalkulasi berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan kalkulasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createPenawaran(Request $request)
    {
        try {
            $proyekId = $request->id_proyek;
            
            // Update status proyek menjadi 'Penawaran'
            $proyek = Proyek::findOrFail($proyekId);
            $proyek->update(['status' => 'Penawaran']);

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil diubah status menjadi Penawaran'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status proyek: ' . $e->getMessage()
            ], 500);
        }
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
            $data = $request->all();
            
            // Create temporary kalkulasi object for calculation
            $kalkulasi = new KalkulasiHps($data);
            $kalkulasi->calculateValues();
            
            return response()->json([
                'success' => true,
                'calculated' => $kalkulasi->toArray()
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
            $proyek = Proyek::with(['adminMarketing', 'adminPurchasing', 'proyekBarang'])->findOrFail($id);
            
            // Get existing kalkulasi data
            $kalkulasiData = KalkulasiHps::with(['barang', 'vendor'])
                                        ->where('id_proyek', $id)
                                        ->get();

            return view('pages.purchasing.hps', compact('proyek', 'kalkulasiData'));
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
}
