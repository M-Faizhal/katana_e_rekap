<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\InvoiceProyek;
use App\Models\InvoiceProyekItem;
use App\Models\Penawaran;
use App\Models\Proyek;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceProyekController extends Controller
{
    public function create($proyekId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->route('keuangan.penagihan')->with('error', 'Akses ditolak.');
        }

        $proyek = Proyek::with([
            'semuaPenawaran' => function ($q) {
                $q->where('status', 'ACC')->latest('id_penawaran');
            },
            'semuaPenawaran.penawaranDetail'
        ])->where('id_proyek', $proyekId)->firstOrFail();

        $penawaran = $proyek->semuaPenawaran->first();
        if (!$penawaran) {
            return redirect()->route('keuangan.penagihan')->with('error', 'Belum ada penawaran ACC untuk proyek ini.');
        }

        $invoice = InvoiceProyek::with('items')
            ->where('id_proyek', $proyek->id_proyek)
            ->where('id_penawaran', $penawaran->id_penawaran)
            ->first();

        $keteranganMap = [];
        if ($invoice) {
            foreach ($invoice->items as $it) {
                $keteranganMap[$it->id_penawaran_detail] = $it->keterangan_html;
            }
        }

        return view('pages.keuangan.pembuatan-invoice-proyek', compact('proyek', 'penawaran', 'invoice', 'keteranganMap'));
    }

    public function store(Request $request, $proyekId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'id_penawaran' => 'nullable|exists:penawaran,id_penawaran',
            'tanggal_surat' => 'nullable|date',
            'nomor_surat' => 'nullable|string|max:120',
            'bill_to_instansi' => 'nullable|string|max:255',
            'bill_to_alamat' => 'nullable|string',
            'ship_to_instansi' => 'nullable|string|max:255',
            'ship_to_alamat' => 'nullable|string',
            'rekening' => 'nullable|string',
            'items' => 'array',
            'items.*.id_penawaran_detail' => 'nullable|exists:penawaran_detail,id_detail',
            'items.*.keterangan_html' => 'nullable|string',
        ]);

        $proyek = Proyek::where('id_proyek', $proyekId)->firstOrFail();

        $penawaran = Penawaran::with('penawaranDetail')
            ->where('id_penawaran', $request->id_penawaran)
            ->where('id_proyek', $proyek->id_proyek)
            ->firstOrFail();

        DB::transaction(function () use ($request, $proyek, $penawaran) {
            $invoice = InvoiceProyek::updateOrCreate(
                [
                    'id_proyek' => $proyek->id_proyek,
                    'id_penawaran' => $penawaran->id_penawaran,
                ],
                [
                    'tanggal_surat' => $request->tanggal_surat,
                    'nomor_surat' => $request->nomor_surat,
                    'bill_to_instansi' => $request->bill_to_instansi,
                    'bill_to_alamat' => $request->bill_to_alamat,
                    'ship_to_instansi' => $request->ship_to_instansi,
                    'ship_to_alamat' => $request->ship_to_alamat,
                    'rekening' => $request->rekening,

                ]
            );

            $allowedDetailIds = $penawaran->penawaranDetail->pluck('id_detail')->flip();

            foreach (($request->items ?? []) as $row) {
                $detailId = (int)($row['id_penawaran_detail'] ?? 0);
                if (!$detailId || !isset($allowedDetailIds[$detailId])) {
                    continue;
                }

                InvoiceProyekItem::updateOrCreate(
                    [
                        'id_invoice' => $invoice->id_invoice,
                        'id_penawaran_detail' => $detailId,
                    ],
                    [
                        'keterangan_html' => $row['keterangan_html'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('keuangan.pembuatan-invoice-proyek', $proyek->id_proyek)
            ->with('success', 'Invoice berhasil disimpan.');
    }

    public function preview($proyekId)
    {
        $data = $this->buildPdfData($proyekId);
        $pdf = Pdf::loadView('pages.files.surat-invoice', $data)->setPaper('a4');
        return $pdf->stream('invoice-preview.pdf');
    }

    public function download($proyekId)
    {
        $data = $this->buildPdfData($proyekId);
        $pdf = Pdf::loadView('pages.files.surat-invoice', $data)->setPaper('a4');
        $no = preg_replace('/[^A-Za-z0-9\-_.]/', '-', (string)($data['invoice']->nomor_surat ?? 'draft'));
        return $pdf->download('invoice-' . $no . '.pdf');
    }

    /**
     * Sanitasi HTML agar aman dirender oleh Dompdf di server yang tidak mendukung WEBP.
     * - Menghapus <img> dengan src webp
     * - Menghapus srcset yang mengandung webp
     * - Menghapus style/background-image url(...) yang mengandung webp
     */
    private function sanitizeHtmlForDompdf(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return $html;
        }

        $out = $html;

        // Hapus tag <img> yang src mengandung .webp (jaga-jaga variasi querystring)
        $out = preg_replace('~<img\b[^>]*\bsrc\s*=\s*(["\"])\s*[^"\']*?\.webp(?:\?[^"\']*)?\1[^>]*>~i', '', $out);

        // Hapus atribut srcset yang mengandung webp (beberapa editor WYSIWYG menambah ini)
        $out = preg_replace('~\ssrcset\s*=\s*(["\"]).*?webp.*?\1~i', '', $out);

        // Hapus background-image / background url(...webp...)
        $out = preg_replace('~url\(([^)]*?\.webp[^)]*)\)~i', 'url()', $out);
        $out = preg_replace('~background(?:-image)?\s*:\s*[^;]*?\.webp[^;]*;?~i', '', $out);

        return $out;
    }

    private function buildPdfData($proyekId): array
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin_keuangan', 'superadmin'])) {
            abort(403, 'Akses ditolak.');
        }

        $proyek = Proyek::with([
            'semuaPenawaran' => function ($q) {
                $q->where('status', 'ACC')->latest('id_penawaran');
            },
            'semuaPenawaran.penawaranDetail'
        ])->where('id_proyek', $proyekId)->firstOrFail();

        $penawaran = $proyek->semuaPenawaran->first();
        if (!$penawaran) {
            abort(404, 'Penawaran ACC tidak ditemukan.');
        }

        $invoice = InvoiceProyek::with(['items', 'items.penawaranDetail'])
            ->where('id_proyek', $proyek->id_proyek)
            ->where('id_penawaran', $penawaran->id_penawaran)
            ->firstOrFail();

        $itemByDetail = $invoice->items->keyBy('id_penawaran_detail');

        $details = $penawaran->penawaranDetail->map(function ($d) use ($itemByDetail) {
            // Hindari undefined index jika item invoice belum dibuat untuk detail ini
            $keteranganHtml = optional($itemByDetail->get($d->id_detail))->keterangan_html;

            // Bersihkan semua image webp yang tidak didukung Dompdf di server
            $keteranganHtml = $this->sanitizeHtmlForDompdf($keteranganHtml);

            return [
                'id_detail'       => $d->id_detail,
                'nama_barang'     => $d->nama_barang,
                'qty'             => $d->qty,
                'satuan'          => $d->satuan,
                'harga_satuan'    => $d->harga_satuan,
                'subtotal'        => $d->subtotal,
                'keterangan_html' => $keteranganHtml,
            ];
        });

        $total = (float)$penawaran->penawaranDetail->sum('subtotal');

        return compact('proyek', 'penawaran', 'invoice', 'details', 'total');
    }
}
