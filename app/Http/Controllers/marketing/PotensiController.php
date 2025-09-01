<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PotensiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter
        $tahunFilter = $request->get('tahun');
        $adminMarketingFilter = $request->get('admin_marketing');
        $statusFilter = $request->get('status');
        $searchFilter = $request->get('search');

        // Query untuk mengambil proyek dengan status tertentu yang menunjukkan potensi
        $query = Proyek::with(['adminMarketing', 'adminPurchasing', 'penawaranAktif'])
            ->whereIn('status', ['Penawaran', 'Pembayaran', 'Pengiriman', 'Selesai']);

        // Filter berdasarkan tahun
        if ($tahunFilter) {
            $query->whereYear('tanggal', $tahunFilter);
        }

        // Filter berdasarkan admin marketing
        if ($adminMarketingFilter) {
            $query->where('id_admin_marketing', $adminMarketingFilter);
        }

        // Filter berdasarkan search
        if ($searchFilter) {
            $query->where(function($q) use ($searchFilter) {
                $q->where('nama_barang', 'like', '%' . $searchFilter . '%')
                  ->orWhere('instansi', 'like', '%' . $searchFilter . '%')
                  ->orWhere('kota_kab', 'like', '%' . $searchFilter . '%');
            });
        }

        $proyekData = $query->orderBy('tanggal', 'desc')->get();

        // Transform data untuk view
        $potensiData = $proyekData->map(function ($proyek, $index) {
            // Generate vendor dummy berdasarkan index
            $vendors = [
                ['id' => 'VND001', 'nama' => 'PT. Teknologi Maju', 'jenis' => 'Perusahaan'],
                ['id' => 'VND002', 'nama' => 'CV. Mandiri Sejahtera', 'jenis' => 'CV'],
                ['id' => 'VND003', 'nama' => 'PT. Global Industri', 'jenis' => 'Perusahaan'],
                ['id' => 'VND004', 'nama' => 'CV. Sukses Bersama', 'jenis' => 'CV'],
                ['id' => 'VND005', 'nama' => 'PT. Solusi Digital', 'jenis' => 'Perusahaan']
            ];

            $vendorIndex = $index % count($vendors);
            $vendor = $vendors[$vendorIndex];

            return [
                'id' => $proyek->id_proyek,
                'kode_proyek' => 'PNW-' . $proyek->tanggal->format('Ymd') . '-' . str_pad($proyek->id_proyek, 6, '0', STR_PAD_LEFT),
                'nama_proyek' => $proyek->nama_barang,
                'instansi' => $proyek->instansi,
                'kabupaten_kota' => $proyek->kota_kab,
                'jenis_pengadaan' => $proyek->jenis_pengadaan,
                'nilai_proyek' => $proyek->penawaranAktif ? $proyek->penawaranAktif->total_penawaran : ($proyek->harga_total ?? 0),
                'deadline' => $proyek->deadline ? $proyek->deadline->format('d M Y') : '-',
                'vendor_id' => $vendor['id'],
                'vendor_nama' => $vendor['nama'],
                'vendor_jenis' => $vendor['jenis'],
                'status' => $this->mapStatusToPotensi($proyek->status),
                'tahun' => $proyek->tanggal->year,
                'admin_marketing' => $proyek->adminMarketing ? $proyek->adminMarketing->nama : '-',
                'admin_purchasing' => $proyek->adminPurchasing ? $proyek->adminPurchasing->nama : '-',
                'tanggal_assign' => $proyek->created_at->format('d M Y'),
                'catatan' => $proyek->catatan ?? '-'
            ];
        });

        // Filter berdasarkan status setelah transform (karena status di-mapping)
        if ($statusFilter) {
            $potensiData = $potensiData->filter(function($item) use ($statusFilter) {
                return $item['status'] === $statusFilter;
            });
        }

        $potensiData = $potensiData->values()->toArray();

        // Hitung statistik
        $totalPotensi = count($potensiData);
        $pendingCount = collect($potensiData)->where('status', 'pending')->count();
        $suksesCount = collect($potensiData)->where('status', 'sukses')->count();
        $vendorAktifCount = 12; // Dummy count

        // Ambil daftar admin marketing untuk filter
        $adminMarketingList = User::whereIn('role', ['superadmin', 'admin_marketing'])
            ->select('id_user', 'nama')
            ->orderBy('nama')
            ->get();

        // Ambil daftar tahun yang tersedia
        $tahunList = Proyek::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('pages.marketing.potensi', compact(
            'potensiData',
            'totalPotensi',
            'pendingCount',
            'suksesCount',
            'vendorAktifCount',
            'adminMarketingList',
            'tahunList',
            'tahunFilter',
            'adminMarketingFilter',
            'statusFilter',
            'searchFilter'
        ));
    }

    public function detail($id)
    {
        try {
            // Ambil data proyek dengan relasi yang diperlukan
            $proyek = Proyek::with(['user', 'penawaran'])
                ->findOrFail($id);

            // Generate vendor data (dummy data karena belum ada tabel vendor yang sesuai)
            $vendorData = [
                'id' => 'VND-' . str_pad($id, 4, '0', STR_PAD_LEFT),
                'nama' => 'PT ' . ucfirst(strtolower($proyek->instansi)) . ' Solutions',
                'jenis' => collect(['Korporasi', 'UMKM', 'Startup'])->random(),
                'status' => collect(['Aktif', 'Pending', 'Suspended'])->random(),
            ];

            // Format data untuk response
            $detailData = [
                'id' => $proyek->id_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'instansi' => $proyek->instansi,
                'kabupaten_kota' => $proyek->kab_kota,
                'jenis_pengadaan' => $proyek->jenis_pengadaan,
                'nilai_proyek' => 'Rp ' . number_format($proyek->nilai_proyek, 0, ',', '.'),
                'deadline' => \Carbon\Carbon::parse($proyek->deadline)->format('d M Y'),
                'admin_marketing' => $proyek->user ? $proyek->user->nama : 'Tidak ada',
                'status' => $this->mapStatusToPotensi($proyek->status),
                'tanggal_assign' => \Carbon\Carbon::parse($proyek->tanggal)->format('d M Y'),
                'catatan' => $proyek->catatan ?? 'Tidak ada catatan khusus',
                'vendor' => $vendorData,
                'timeline' => [
                    [
                        'tanggal' => \Carbon\Carbon::parse($proyek->tanggal)->format('d M Y'),
                        'aktivitas' => 'Proyek dibuat',
                        'detail' => 'Proyek ' . $proyek->nama_proyek . ' telah dibuat dan ditugaskan ke admin marketing'
                    ],
                    [
                        'tanggal' => \Carbon\Carbon::parse($proyek->tanggal)->addDays(1)->format('d M Y'),
                        'aktivitas' => 'Vendor ditugaskan',
                        'detail' => 'Vendor ' . $vendorData['nama'] . ' telah ditugaskan untuk menangani proyek ini'
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $detailData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    private function mapStatusToPotensi($status)
    {
        switch (strtolower($status)) {
            case 'selesai':
                return 'sukses';
            case 'penawaran':
            case 'pembayaran':
            case 'pengiriman':
                return 'pending';
            default:
                return 'pending';
        }
    }
}
