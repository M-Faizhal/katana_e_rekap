<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Proyek;
use App\Models\Pengiriman;

class VerifikasiProyekController extends Controller
{
    public function index()
    {
        // Ambil proyek dengan status "Pengiriman" yang menunggu verifikasi
        $proyekVerifikasi = DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('pengiriman', 'penawaran.id_penawaran', '=', 'pengiriman.id_penawaran')
            ->join('users as admin_marketing', 'proyek.id_admin_marketing', '=', 'admin_marketing.id_user')
            ->join('users as admin_purchasing', 'proyek.id_admin_purchasing', '=', 'admin_purchasing.id_user')
            ->leftJoin('users as verifier', 'pengiriman.verified_by', '=', 'verifier.id_user')
            ->select([
                'proyek.*',
                'penawaran.no_penawaran',
                'penawaran.surat_pesanan',
                'penawaran.surat_penawaran', 
                'penawaran.total_penawaran',
                'penawaran.masa_berlaku',
                'pengiriman.*',
                'admin_marketing.nama as admin_marketing_name',
                'admin_purchasing.nama as admin_purchasing_name',
                'verifier.nama as verified_by_name'
            ])
            ->where('proyek.status', 'Pengiriman')
            ->whereIn('pengiriman.status_verifikasi', ['Sampai_Tujuan', 'Pending', 'Dalam_Proses'])
            ->orderBy('pengiriman.updated_at', 'desc')
            ->get();

        return view('pages.superadmin.verifikasi-proyek', compact('proyekVerifikasi'));
    }

    public function show($id)
    {
        // Detail lengkap untuk satu proyek
        $proyek = DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('pengiriman', 'penawaran.id_penawaran', '=', 'pengiriman.id_penawaran')
            ->join('users as admin_marketing', 'proyek.id_admin_marketing', '=', 'admin_marketing.id_user')
            ->join('users as admin_purchasing', 'proyek.id_admin_purchasing', '=', 'admin_purchasing.id_user')
            ->leftJoin('users as verifier', 'pengiriman.verified_by', '=', 'verifier.id_user')
            ->select([
                'proyek.*',
                'penawaran.*',
                'pengiriman.*',
                'admin_marketing.nama as admin_marketing_name',
                'admin_marketing.email as admin_marketing_email',
                'admin_purchasing.nama as admin_purchasing_name', 
                'admin_purchasing.email as admin_purchasing_email',
                'verifier.nama as verified_by_name'
            ])
            ->where('proyek.id_proyek', $id)
            ->first();

        if (!$proyek) {
            return redirect()->route('superadmin.verifikasi-proyek')->with('error', 'Proyek tidak ditemukan');
        }

        // Ambil detail penawaran
        $penawaranDetail = DB::table('penawaran_detail')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->join('vendor', 'barang.id_vendor', '=', 'vendor.id_vendor')
            ->select([
                'penawaran_detail.*',
                'barang.brand',
                'barang.kategori',
                'vendor.nama_vendor'
            ])
            ->where('penawaran_detail.id_penawaran', $proyek->id_penawaran)
            ->get();

        // Ambil riwayat pembayaran
        $pembayaran = DB::table('pembayaran')
            ->where('id_penawaran', $proyek->id_penawaran)
            ->orderBy('tanggal_bayar', 'asc')
            ->get();

        return view('pages.superadmin.verifikasi-proyek-detail', compact('proyek', 'penawaranDetail', 'pembayaran'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:selesai,gagal',
            'catatan_verifikasi' => 'required|string|max:1000'
        ]);

        $proyek = Proyek::findOrFail($id);
        $pengiriman = Pengiriman::where('id_penawaran', $proyek->id_penawaran)->first();

        if (!$pengiriman) {
            return redirect()->back()->with('error', 'Data pengiriman tidak ditemukan');
        }

        if ($request->action === 'selesai') {
            // Update pengiriman
            $pengiriman->update([
                'status_verifikasi' => 'Verified',
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'verified_by' => Auth::user()->id_user,
                'verified_at' => now()
            ]);

            // Update status proyek menjadi "Selesai"
            $proyek->update(['status' => 'Selesai']);

            return redirect()->route('superadmin.verifikasi-proyek')->with('success', 'Proyek berhasil diverifikasi sebagai SELESAI!');
        } else {
            // Update pengiriman sebagai rejected
            $pengiriman->update([
                'status_verifikasi' => 'Rejected',
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'verified_by' => Auth::user()->id_user,
                'verified_at' => now()
            ]);

            // Update status proyek menjadi "Gagal"
            $proyek->update(['status' => 'Gagal']);

            return redirect()->route('superadmin.verifikasi-proyek')->with('error', 'Proyek ditandai sebagai GAGAL! Silakan koordinasi dengan tim terkait.');
        }
    }

    public function history()
    {
        // Ambil semua pengiriman yang sudah diverifikasi (Verified atau Rejected)
        $historyVerifikasi = DB::table('proyek')
            ->join('penawaran', 'proyek.id_penawaran', '=', 'penawaran.id_penawaran')
            ->join('pengiriman', 'penawaran.id_penawaran', '=', 'pengiriman.id_penawaran')
            ->join('users as admin_marketing', 'proyek.id_admin_marketing', '=', 'admin_marketing.id_user')
            ->join('users as admin_purchasing', 'proyek.id_admin_purchasing', '=', 'admin_purchasing.id_user')
            ->join('users as verifier', 'pengiriman.verified_by', '=', 'verifier.id_user')
            ->select([
                'proyek.*',
                'penawaran.no_penawaran',
                'penawaran.total_penawaran',
                'pengiriman.*',
                'admin_marketing.nama as admin_marketing_name',
                'admin_purchasing.nama as admin_purchasing_name',
                'verifier.nama as verified_by_name'
            ])
            ->whereIn('pengiriman.status_verifikasi', ['Verified', 'Rejected'])
            ->orderBy('pengiriman.verified_at', 'desc')
            ->get();

        return view('pages.superadmin.verifikasi-proyek-history', compact('historyVerifikasi'));
    }
}
