<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Vendor;
use App\Models\Barang;
use App\Models\User;
use App\Models\Penawaran;
use App\Models\PenawaranDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display the main laporan page
     */
    public function index(Request $request)
    {
        // Get basic statistics
        $stats = $this->getStatistics();

        // Get projects with filters applied
        $projects = $this->getFilteredProjects($request);

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        return view('pages.laporan', compact('stats', 'projects', 'filterOptions'));
    }

    /**
     * Get basic statistics for the dashboard
     */
    private function getStatistics()
    {
        $stats = [
            'proyek_selesai' => Proyek::where('status', 'selesai')->count(),
            'proyek_selesai_bulan_ini' => Proyek::where('status', 'selesai')
                ->whereMonth('updated_at', Carbon::now()->month)
                ->whereYear('updated_at', Carbon::now()->year)
                ->count(),
            'total_nilai_proyek' => Proyek::where('status', 'selesai')->sum('harga_total'),
            'vendor_aktif' => Vendor::whereHas('barang')->count(),
            'jenis_produk' => Barang::distinct('nama_barang')->count(),
        ];

        return $stats;
    }

    /**
     * Get projects with applied filters
     */
    private function getFilteredProjects(Request $request)
    {
        $query = Proyek::with([
            'adminMarketing',
            'adminPurchasing',
            'penawaran.penawaranDetail.barang.vendor',
            'semuaPenawaran'
        ])->where('status', '!=', 'menunggu'); // Only show projects that have progressed

        // Apply filters
        if ($request->filled('periode')) {
            $this->applyPeriodeFilter($query, $request->periode, $request);
        }

        if ($request->filled('vendor')) {
            $query->whereHas('penawaran.penawaranDetail.barang.vendor', function($q) use ($request) {
                $q->where('nama_vendor', 'like', '%' . $request->vendor . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('penawaran.penawaranDetail.barang', function($q) use ($request) {
                $q->where('kategori', $request->kategori);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereIn('status', ['selesai', 'pengiriman', 'pembayaran']);
            } elseif ($request->status === 'completed') {
                $query->where('status', 'selesai');
            } elseif ($request->status === 'paid') {
                $query->whereIn('status', ['selesai', 'pengiriman']);
            }
        }

        if ($request->filled('produk')) {
            $query->whereHas('penawaran.penawaranDetail.barang', function($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->produk . '%');
            });
        }

        if ($request->filled('departemen')) {
            $query->whereHas('adminMarketing', function($q) use ($request) {
                $q->where('role', 'like', '%' . $request->departemen . '%');
            });
        }

        if ($request->filled('nilai')) {
            $this->applyNilaiFilter($query, $request->nilai);
        }

        return $query->orderBy('tanggal', 'desc')->paginate(10);
    }

    /**
     * Apply periode filter to query
     */
    private function applyPeriodeFilter($query, $periode, $request)
    {
        $now = Carbon::now();

        switch ($periode) {
            case 'bulan-ini':
                $query->whereMonth('tanggal', $now->month)
                      ->whereYear('tanggal', $now->year);
                break;
            case '3-bulan':
                $query->where('tanggal', '>=', $now->subMonths(3));
                break;
            case '6-bulan':
                $query->where('tanggal', '>=', $now->subMonths(6));
                break;
            case 'tahun-ini':
                $query->whereYear('tanggal', $now->year);
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
                }
                break;
        }
    }

    /**
     * Apply nilai (value range) filter
     */
    private function applyNilaiFilter($query, $nilai)
    {
        switch ($nilai) {
            case '0-5jt':
                $query->where('harga_total', '<=', 5000000);
                break;
            case '5-10jt':
                $query->whereBetween('harga_total', [5000001, 10000000]);
                break;
            case '10-25jt':
                $query->whereBetween('harga_total', [10000001, 25000000]);
                break;
            case '25-50jt':
                $query->whereBetween('harga_total', [25000001, 50000000]);
                break;
            case '50jt+':
                $query->where('harga_total', '>', 50000000);
                break;
        }
    }

    /**
     * Get filter options for dropdowns
     */
    private function getFilterOptions()
    {
        return [
            'vendors' => Vendor::select('id_vendor', 'nama_vendor')
                ->whereHas('barang.penawaranDetail')
                ->distinct()
                ->orderBy('nama_vendor')
                ->get(),
            'products' => Barang::select('nama_barang')
                ->whereHas('penawaranDetail')
                ->distinct()
                ->orderBy('nama_barang')
                ->get(),
            'categories' => Barang::select('kategori')
                ->whereNotNull('kategori')
                ->whereHas('penawaranDetail')
                ->distinct()
                ->orderBy('kategori')
                ->get(),
        ];
    }

    /**
     * Export report to Excel
     */
    public function export(Request $request)
    {
        // Get filtered projects for export
        $projects = $this->getFilteredProjects($request);

        // Create CSV response
        $filename = 'laporan-proyek-' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($projects) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Kode Proyek',
                'Nama Proyek',
                'Instansi',
                'Tanggal',
                'Status',
                'Admin Marketing',
                'Admin Purchasing',
                'Total Nilai',
                'Vendor',
                'Produk',
                'Kategori'
            ]);

            // Add data rows
            foreach ($projects as $project) {
                $vendor = $project->penawaran && $project->penawaran->penawaranDetail->first()
                    ? $project->penawaran->penawaranDetail->first()->barang->vendor->nama_vendor
                    : '-';

                $produk = $project->penawaran && $project->penawaran->penawaranDetail->first()
                    ? $project->penawaran->penawaranDetail->first()->barang->nama_barang
                    : '-';

                $kategori = $project->penawaran && $project->penawaran->penawaranDetail->first()
                    ? $project->penawaran->penawaranDetail->first()->barang->kategori
                    : '-';

                fputcsv($file, [
                    $project->kode_proyek,
                    $project->nama_klien . ' - ' . $project->jenis_pengadaan,
                    $project->instansi,
                    $project->tanggal->format('d/m/Y'),
                    ucfirst($project->status),
                    $project->adminMarketing->nama ?? '-',
                    $project->adminPurchasing->nama ?? '-',
                    number_format($project->harga_total, 0, ',', '.'),
                    $vendor,
                    $produk,
                    $kategori
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get project detail for modal
     */
    public function getProjectDetail($id)
    {
        $project = Proyek::with([
            'adminMarketing',
            'adminPurchasing',
            'penawaran.penawaranDetail.barang.vendor',
            'semuaPenawaran'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'kode_proyek' => $project->kode_proyek,
                'nama_klien' => $project->nama_klien,
                'instansi' => $project->instansi,
                'jenis_pengadaan' => $project->jenis_pengadaan,
                'tanggal' => $project->tanggal->format('d M Y'),
                'deadline' => $project->deadline ? $project->deadline->format('d M Y') : '-',
                'status' => ucfirst($project->status),
                'admin_marketing' => $project->adminMarketing->nama ?? '-',
                'admin_purchasing' => $project->adminPurchasing->nama ?? '-',
                'total_nilai' => number_format($project->harga_total, 0, ',', '.'),
                'catatan' => $project->catatan ?? '-',
                'penawaran' => $project->penawaran ? [
                    'no_penawaran' => $project->penawaran->no_penawaran,
                    'tanggal_penawaran' => $project->penawaran->tanggal_penawaran->format('d M Y'),
                    'total_nilai' => number_format($project->penawaran->total_nilai, 0, ',', '.'),
                    'detail_barang' => $project->penawaran->penawaranDetail->map(function($detail) {
                        return [
                            'nama_barang' => $detail->barang->nama_barang,
                            'vendor' => $detail->barang->vendor->nama_vendor,
                            'kategori' => $detail->barang->kategori,
                            'jumlah' => $detail->jumlah,
                            'satuan' => $detail->barang->satuan,
                            'harga_satuan' => number_format($detail->harga_satuan, 0, ',', '.'),
                            'subtotal' => number_format($detail->subtotal, 0, ',', '.')
                        ];
                    })
                ] : null
            ]
        ]);
    }

    /**
     * API endpoint to get filtered data (for AJAX)
     */
    public function getFilteredData(Request $request)
    {
        $projects = $this->getFilteredProjects($request);
        $stats = $this->getStatistics();

        return response()->json([
            'success' => true,
            'data' => [
                'projects' => $projects,
                'stats' => $stats
            ]
        ]);
    }
}
