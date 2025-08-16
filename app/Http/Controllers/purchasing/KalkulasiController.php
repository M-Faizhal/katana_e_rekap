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
        $query = Proyek::with(['adminMarketing', 'adminPurchasing'])
                      ->where('status', 'Menunggu'); // Hanya proyek dengan status Menunggu

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('nama_klien', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%");
            });
        }

        $proyek = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.purchasing.kalkulasi', compact('proyek'));
    }

    public function getProyekData($id)
    {
        try {
            $proyek = Proyek::with(['adminMarketing', 'adminPurchasing'])->findOrFail($id);
            
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

    public function saveKalkulasi(Request $request)
    {
        try {
            DB::beginTransaction();

            $proyekId = $request->id_proyek;
            
            // Hapus kalkulasi lama jika ada
            KalkulasiHps::where('id_proyek', $proyekId)->delete();

            // Simpan kalkulasi baru
            foreach ($request->kalkulasi as $item) {
                KalkulasiHps::create([
                    'id_proyek' => $proyekId,
                    'id_barang' => $item['id_barang'],
                    'id_vendor' => $item['id_vendor'],
                    'qty' => $item['qty'],
                    'harga_vendor' => $item['harga_vendor'],
                    'diskon_amount' => $item['diskon_amount'],
                    'total_diskon' => $item['total_diskon'],
                    'total_harga_hpp' => $item['total_harga_hpp'],
                    'kenaikan_percent' => $item['kenaikan_percent'],
                    'proyeksi_kenaikan' => $item['proyeksi_kenaikan'],
                    'pph' => $item['pph'],
                    'ppn' => $item['ppn'],
                    'ongkir' => $item['ongkir'],
                    'hps' => $item['hps'],
                    'bank_cost' => $item['bank_cost'],
                    'biaya_ops' => $item['biaya_ops'],
                    'bendera' => $item['bendera'],
                    'nett' => $item['nett'],
                    'nett_percent' => $item['nett_percent'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }

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
}
