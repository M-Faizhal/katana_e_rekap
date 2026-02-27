<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\Pembayaran;
use App\Models\KalkulasiHps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatPembelianController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->get('search');
        $filterStatus = $request->get('status_filter', 'all');   // all | lunas | belum_lunas
        $filterPpn   = $request->get('ppn_filter', 'all');        // all | ada_ppn | non_ppn
        $sortBy      = $request->get('sort_by', 'desc');

        // --- Ambil proyek yang punya penawaran ACC + pembayaran ---
        $query = Proyek::with([
            'penawaranAktif.penawaranDetail.barang.vendor',
            'adminMarketing',
        ])
        ->whereIn('status', ['Pembayaran', 'Pengiriman', 'Selesai', 'Gagal'])
        ->whereHas('penawaranAktif', fn($q) => $q->where('status', 'ACC'));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('instansi', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%");
            });
        }

        $sortBy === 'asc'
            ? $query->orderBy('created_at', 'asc')
            : $query->orderBy('created_at', 'desc');

        $proyekAll = $query->get();

        // --- Batch load KalkulasiHps ---
        $proyekIds   = $proyekAll->pluck('id_proyek')->all();
        $penawaranIds = $proyekAll->pluck('penawaranAktif.id_penawaran')->filter()->all();

        $kalkulasiAll = KalkulasiHps::with('barang.vendor')
            ->whereIn('id_proyek', $proyekIds)
            ->get()
            ->groupBy('id_proyek');    // [ id_proyek => Collection<KalkulasiHps> ]

        // Batch total harga_akhir per (proyek, vendor)
        $hpsMap = [];
        foreach ($kalkulasiAll as $pid => $rows) {
            foreach ($rows->groupBy('id_vendor') as $vid => $vrows) {
                $hpsMap[$pid][$vid] = $vrows->sum('total_harga_hpp');
            }
        }

        // Batch approved pembayaran per (proyek via penawaran, vendor)
        $pembayaranApproved = Pembayaran::whereIn('id_penawaran', $penawaranIds)
            ->where('status_verifikasi', 'Approved')
            ->select('id_penawaran', 'id_vendor', DB::raw('SUM(nominal_bayar) as total'))
            ->groupBy('id_penawaran', 'id_vendor')
            ->get();

        $penawaranToProyek = $proyekAll->pluck('penawaranAktif.id_penawaran', 'id_proyek')
            ->flip()->all();   // [ id_penawaran => id_proyek ]

        $bayarMap = [];
        foreach ($pembayaranApproved as $row) {
            $pid = $penawaranToProyek[$row->id_penawaran] ?? null;
            if ($pid) $bayarMap[$pid][$row->id_vendor] = (float) $row->total;
        }

        // --- Ambil ppn_data terbaru per (penawaran, vendor) ---
        // Satu query: latest per (id_penawaran, id_vendor) yang ada ppn_data
        $latestPpnRows = Pembayaran::whereIn('id_penawaran', $penawaranIds)
            ->whereNotNull('ppn_data')
            ->orderBy('id_pembayaran', 'desc')
            ->get()
            ->groupBy('id_penawaran')
            ->map(fn($rows) => $rows->groupBy('id_vendor')->map(fn($vrows) => $vrows->first()));
        // $latestPpnRows[ id_penawaran ][ id_vendor ] = Pembayaran

        // --- Build hasil per proyek ---
        $hasil = $proyekAll->map(function ($proyek) use ($kalkulasiAll, $hpsMap, $bayarMap, $latestPpnRows, $penawaranToProyek) {
            $idPenawaran = $proyek->penawaranAktif?->id_penawaran;
            $kalkRows    = $kalkulasiAll->get($proyek->id_proyek, collect());

            // Vendor unik dari kalkulasi
            $vendorIds = $kalkRows->pluck('id_vendor')->unique()->values();

            $vendors = $vendorIds->map(function ($vendorId) use ($proyek, $kalkRows, $hpsMap, $bayarMap, $latestPpnRows, $idPenawaran) {
                $vKalk       = $kalkRows->where('id_vendor', $vendorId);
                $vendorModel = $vKalk->first()?->barang?->vendor;
                $vendorNama  = $vendorModel?->nama_vendor ?? "Vendor #{$vendorId}";

                $totalHarga  = $hpsMap[$proyek->id_proyek][$vendorId] ?? 0;
                $totalBayar  = $bayarMap[$proyek->id_proyek][$vendorId] ?? 0;
                $sisaBayar   = $totalHarga - $totalBayar;

                // PPN snapshot terbaru
                $latestPpn = $latestPpnRows[$idPenawaran][$vendorId] ?? null;

                // Build items: merge data KalkulasiHps + ppn_data snapshot
                $ppnMap = [];
                if ($latestPpn && !empty($latestPpn->ppn_data['items'])) {
                    foreach ($latestPpn->ppn_data['items'] as $pItem) {
                        $ppnMap[$pItem['id_kalkulasi_hps']] = $pItem;
                    }
                }

                $items = $vKalk->map(function ($k) use ($ppnMap) {
                    $snap       = $ppnMap[$k->id_kalkulasi] ?? null;
                    $adaPpn     = $snap ? (bool) $snap['ada_ppn'] : null;
                    $nominalPpn = isset($snap['nominal_ppn']) ? (float) $snap['nominal_ppn'] : null;
                    $qty        = (int) $k->qty ?: 1;
                    $hargaAkhir = (float) $k->harga_akhir;

                    // Harga satuan = harga per unit SEBELUM PPN
                    // Jika ada PPN: harga_akhir (sudah include PPN per unit) - (nominal_ppn / qty)
                    // Jika non-PPN atau belum dikonfigurasi: sama dengan harga_akhir
                    if ($adaPpn === true && $nominalPpn !== null) {
                        $hargaSatuan = $hargaAkhir - ($nominalPpn / $qty);
                    } else {
                        $hargaSatuan = $hargaAkhir;
                    }

                    return [
                        'id_kalkulasi'      => $k->id_kalkulasi,
                        'nama_barang'       => $k->barang->nama_barang ?? 'N/A',
                        'satuan'            => $k->barang->satuan ?? '-',
                        'qty'               => $qty,
                        'harga_vendor'      => (float) $k->harga_vendor,
                        'harga_satuan'      => $hargaSatuan,   // harga per unit sebelum PPN
                        'harga_akhir'       => $hargaAkhir,    // harga per unit sudah include PPN
                        'total_harga_hpp'   => (float) $k->total_harga_hpp,
                        // PPN dari snapshot (null = belum dikonfigurasi)
                        'ada_ppn'           => $adaPpn,
                        'persen_ppn'        => $snap['persen_ppn'] ?? null,
                        'harga_sebelum_ppn' => $snap['harga_sebelum_ppn'] ?? null,
                        'nominal_ppn'       => $nominalPpn,
                    ];
                })->values()->all();

                // Agregat PPN vendor ini dari semua pembayaran Approved
                $totalPpnApproved = 0;
                if ($idPenawaran) {
                    // Sudah dalam $latestPpnRows — tapi perlu semua approved, bukan hanya latest
                    // (akan dihitung di blade via $pembayaranVendor)
                }

                return [
                    'id_vendor'          => $vendorId,
                    'vendor_nama'        => $vendorNama,
                    'total_harga'        => $totalHarga,
                    'total_bayar'        => $totalBayar,
                    'sisa_bayar'         => $sisaBayar,
                    'status_lunas'       => $sisaBayar <= 0,
                    'has_ppn_snapshot'   => $latestPpn !== null,
                    'snapshot_id'        => $latestPpn?->id_pembayaran,
                    'snapshot_tanggal'   => $latestPpn?->tanggal_bayar,
                    'ada_ppn'            => $latestPpn ? ($latestPpn->ada_ppn) : false,
                    'total_ppn_snapshot' => $latestPpn ? floatval($latestPpn->ppn_data['total_ppn'] ?? 0) : 0,
                    'items'              => $items,
                ];
            })->values()->all();

            $grandTotal   = collect($vendors)->sum('total_harga');
            $grandBayar   = collect($vendors)->sum('total_bayar');
            $grandSisa    = $grandTotal - $grandBayar;
            $adaPpn       = collect($vendors)->contains('ada_ppn', true);

            return [
                'proyek'        => $proyek,
                'vendors'       => $vendors,
                'grand_total'   => $grandTotal,
                'grand_bayar'   => $grandBayar,
                'grand_sisa'    => $grandSisa,
                'status_lunas'  => $grandSisa <= 0,
                'ada_ppn'       => $adaPpn,
            ];
        });

        // --- Filter status lunas / belum_lunas ---
        if ($filterStatus === 'lunas') {
            $hasil = $hasil->filter(fn($h) => $h['status_lunas']);
        } elseif ($filterStatus === 'belum_lunas') {
            $hasil = $hasil->filter(fn($h) => !$h['status_lunas']);
        }

        // --- Filter ada/tidak ada PPN ---
        if ($filterPpn === 'ada_ppn') {
            $hasil = $hasil->filter(fn($h) => $h['ada_ppn']);
        } elseif ($filterPpn === 'non_ppn') {
            $hasil = $hasil->filter(fn($h) => !$h['ada_ppn']);
        }

        $hasil = $hasil->values();

        // --- Manual paginate ---
        $perPage     = 15;
        $currentPage = (int) $request->get('page', 1);
        $paginated   = new \Illuminate\Pagination\LengthAwarePaginator(
            $hasil->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $hasil->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // --- Summary stats ---
        $stats = [
            'total_proyek'       => $hasil->count(),
            'total_lunas'        => $hasil->filter(fn($h) => $h['status_lunas'])->count(),
            'total_belum_lunas'  => $hasil->filter(fn($h) => !$h['status_lunas'])->count(),
            'total_ada_ppn'      => $hasil->filter(fn($h) => $h['ada_ppn'])->count(),
            'grand_nilai'        => $hasil->sum('grand_total'),
            'grand_dibayar'      => $hasil->sum('grand_bayar'),
            'grand_sisa'         => $hasil->sum('grand_sisa'),
        ];

        return view('pages.keuangan.riwayat-pembelian', compact(
            'paginated', 'stats', 'search', 'filterStatus', 'filterPpn', 'sortBy'
        ));
    }
}
