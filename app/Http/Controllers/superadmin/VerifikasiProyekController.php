<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Proyek;
use App\Models\Pengiriman;
use App\Models\PenagihanDinas;
use App\Models\Pembayaran;
use Carbon\Carbon;

class VerifikasiProyekController extends Controller
{
    public function index()
    {
        // Ambil proyek yang memenuhi kriteria:
        // 1. Semua barang vendor sudah sampai (semua pengiriman verified atau sampai tujuan)
        // 2. Pembayaran dinas sudah lunas
        // 3. Status proyek belum "Selesai" atau "Gagal"
        
        $proyekVerifikasi = Proyek::with([
            'semuaPenawaran' => function($query) {
                $query->where('status', 'ACC')->with(['pengiriman']);
            }, 
            'penagihanDinas.buktiPembayaran',
            'adminMarketing:id_user,nama,email',
            'adminPurchasing:id_user,nama,email'
        ])
        ->whereHas('semuaPenawaran', function($query) {
            $query->where('status', 'ACC');
        })
        // Pastikan ada pengiriman untuk penawaran proyek ini
        ->whereHas('semuaPenawaran.pengiriman')
        // Pastikan semua pengiriman sudah sampai (Verified atau Sampai_Tujuan) - tidak ada yang masih dalam perjalanan
        ->whereDoesntHave('semuaPenawaran.pengiriman', function($query) {
            $query->whereNotIn('status_verifikasi', ['Verified', 'Sampai_Tujuan']);
        })
        // Pastikan pembayaran dinas sudah lunas
        ->whereHas('penagihanDinas', function($query) {
            $query->where('status_pembayaran', 'lunas');
        })
        // Status proyek belum selesai atau gagal
        ->whereNotIn('status', ['Selesai', 'Gagal'])
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('pages.superadmin.verifikasi-proyek', compact('proyekVerifikasi'));
    }

    public function show($id)
    {
        $proyek = Proyek::with([
            'semuaPenawaran' => function($query) {
                $query->where('status', 'ACC')->with([
                    'penawaranDetail.barang.vendor', 
                    'pengiriman.vendor'
                ]);
            }, 
            'penagihanDinas.buktiPembayaran',
            'pembayaran.vendor',
            'adminMarketing:id_user,nama,email',
            'adminPurchasing:id_user,nama,email'
        ])->findOrFail($id);

        // Validasi bahwa proyek memenuhi kriteria verifikasi
        $pengirimanAll = collect();
        if ($proyek->semuaPenawaran) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
                if ($penawaran && $penawaran->pengiriman) {
                    $pengirimanAll = $pengirimanAll->merge($penawaran->pengiriman);
                }
            }
        }

        $allPengirimanSampai = $pengirimanAll->isNotEmpty() && $pengirimanAll->every(function($pengiriman) {
            return in_array($pengiriman->status_verifikasi, ['Verified', 'Sampai_Tujuan']);
        });

        $penagihanDinas = $proyek->penagihanDinas->first();
        $pembayaranDinasLunas = $penagihanDinas && $penagihanDinas->status_pembayaran === 'lunas';

        if (!$allPengirimanSampai || !$pembayaranDinasLunas) {
            return redirect()->route('superadmin.verifikasi-proyek')
                ->with('error', 'Proyek belum memenuhi kriteria untuk diverifikasi.');
        }

        // Ambil data pembayaran vendor
        $pembayaran = $proyek->pembayaran;

        // Ambil semua detail penawaran dari penawaran yang ACC
        $penawaranDetail = collect();
        if ($proyek->semuaPenawaran) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
                if ($penawaran->status === 'ACC' && $penawaran->penawaranDetail) {
                    $penawaranDetail = $penawaranDetail->merge($penawaran->penawaranDetail);
                }
            }
        }

        return view('pages.superadmin.verifikasi-proyek-detail', compact('proyek', 'pembayaran', 'penawaranDetail'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:selesai,gagal',
            'catatan_verifikasi' => 'nullable|string|max:1000'
        ]);

        $proyek = Proyek::with(['semuaPenawaran.pengiriman', 'penagihanDinas'])->findOrFail($id);

        // Validasi ulang bahwa proyek memenuhi kriteria
        $pengirimanAll = collect();
        if ($proyek->semuaPenawaran) {
            foreach ($proyek->semuaPenawaran as $penawaran) {
                if ($penawaran && $penawaran->pengiriman) {
                    $pengirimanAll = $pengirimanAll->merge($penawaran->pengiriman);
                }
            }
        }

        $allPengirimanVerified = $pengirimanAll->isNotEmpty() && $pengirimanAll->every(function($pengiriman) {
            return in_array($pengiriman->status_verifikasi, ['Verified', 'Sampai_Tujuan']);
        });

        $penagihanDinas = $proyek->penagihanDinas->first();
        $pembayaranDinasLunas = $penagihanDinas && $penagihanDinas->status_pembayaran === 'lunas';

        if (!$allPengirimanVerified || !$pembayaranDinasLunas) {
            return redirect()->back()
                ->with('error', 'Proyek belum memenuhi kriteria untuk diverifikasi.');
        }

        try {
            DB::beginTransaction();

            // Update status proyek berdasarkan action
            $statusProyek = $request->action === 'selesai' ? 'Selesai' : 'Gagal';
            
            $proyek->update([
                'status' => $statusProyek,
                'catatan' => $request->catatan_verifikasi
            ]);

            DB::commit();

            $message = $request->action === 'selesai' 
                ? 'Proyek berhasil diverifikasi sebagai SELESAI.' 
                : 'Proyek berhasil diverifikasi sebagai GAGAL.';

            return redirect()->route('superadmin.verifikasi-proyek')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memverifikasi proyek: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $historyVerifikasi = Proyek::with([
            'semuaPenawaran' => function($query) {
                $query->where('status', 'ACC')->with(['penawaranDetail']);
            },
            'adminMarketing:id_user,nama,email',
            'adminPurchasing:id_user,nama,email'
        ])
        ->whereIn('status', ['Selesai', 'Gagal'])
        ->orderBy('updated_at', 'desc')
        ->get()
        ->map(function($proyek) {
            // Calculate total penawaran
            $totalPenawaran = 0;
            if ($proyek->semuaPenawaran && $proyek->semuaPenawaran->isNotEmpty()) {
                foreach ($proyek->semuaPenawaran as $penawaran) {
                    if ($penawaran->penawaranDetail) {
                        $totalPenawaran += $penawaran->penawaranDetail->sum('subtotal');
                    }
                }
            }
            $proyek->total_penawaran = $totalPenawaran;
            
            // Set nomor penawaran
            $proyek->no_penawaran = $proyek->semuaPenawaran->first()->no_penawaran ?? 'N/A';
            
            return $proyek;
        });

        return view('pages.superadmin.verifikasi-proyek-history', compact('historyVerifikasi'));
    }
}
