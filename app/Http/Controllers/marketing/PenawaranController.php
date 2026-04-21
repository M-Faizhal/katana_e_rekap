<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\PenawaranDetail;
use App\Models\KalkulasiHps;
use App\Models\SuratPenawaran;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;

class PenawaranController extends Controller
{
    public function index()
    {
        $penawaranData = Penawaran::with(['proyek', 'details'])->get();
        return view('pages.marketing.penawaran.index', compact('penawaranData'));
    }

    public function show($proyekId)
    {
        $proyek = Proyek::with('proyekBarang')->findOrFail($proyekId);

        $penawaran = Penawaran::where('id_proyek', $proyek->id_proyek)
                             ->orderBy('id_penawaran', 'desc')
                             ->first();

        if (!$penawaran) {
            $penawaran = Penawaran::create([
                'id_proyek'         => $proyek->id_proyek,
                'tanggal_penawaran' => now(),
                'status'            => 'Menunggu'
            ]);
        }

        $penawaranDetails = PenawaranDetail::where('id_penawaran', $penawaran->id_penawaran)->get();

        if ($penawaranDetails->count() == 0 && $proyek->proyekBarang && $proyek->proyekBarang->count() > 0) {
            foreach ($proyek->proyekBarang as $barang) {
                $subtotal = $barang->harga_satuan ? $barang->harga_satuan * $barang->jumlah : 0;
                PenawaranDetail::create([
                    'id_penawaran' => $penawaran->id_penawaran,
                    'nama_barang'  => $barang->nama_barang,
                    'qty'          => $barang->jumlah,
                    'satuan'       => $barang->satuan ?? 'Unit',
                    'harga_satuan' => $barang->harga_satuan ?? 0,
                    'subtotal'     => $subtotal,
                    'spesifikasi'  => $barang->spesifikasi ?? ''
                ]);
            }
            $penawaranDetails = PenawaranDetail::where('id_penawaran', $penawaran->id_penawaran)->get();
        }

        if ($penawaranDetails->count() > 0) {
            $calculatedTotal = $penawaranDetails->sum('subtotal');
            if ($penawaran->total_nilai != $calculatedTotal) {
                $penawaran->total_nilai = $calculatedTotal;
                $penawaran->save();
            }
        }

        $kalkulasiHps = KalkulasiHps::where('id_proyek', $proyek->id_proyek)
                                   ->whereNotNull('bukti_file_approval')
                                   ->first();

        $proyekBarangWithFiles = $proyek->proyekBarang()->whereNotNull('spesifikasi_files')->get();

        $suratDb = SuratPenawaran::where('id_proyek', $proyek->id_proyek)->first();

        return view('pages.marketing.penawaran.detail', compact(
            'proyek',
            'penawaran',
            'penawaranDetails',
            'kalkulasiHps',
            'proyekBarangWithFiles',
            'suratDb'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proyek'         => 'required|exists:proyek,id_proyek',
            'tanggal_penawaran' => 'required|date',
            'catatan'           => 'nullable|string',
            'surat_penawaran'   => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'surat_pesanan'     => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $suratPenawaranUploaded = false;

            $penawaran = Penawaran::updateOrCreate(
                ['id_proyek' => $request->id_proyek],
                [
                    'tanggal_penawaran' => $request->tanggal_penawaran,
                    'catatan'           => $request->catatan,
                    'status'            => 'Menunggu'
                ]
            );

            $penawaranDetails = PenawaranDetail::where('id_penawaran', $penawaran->id_penawaran)->get();
            if ($penawaranDetails->count() > 0) {
                $penawaran->total_nilai = $penawaranDetails->sum('subtotal');
            }

            if ($request->hasFile('surat_penawaran')) {
                if ($penawaran->surat_penawaran && Storage::disk('public')->exists('penawaran/' . $penawaran->surat_penawaran)) {
                    Storage::disk('public')->delete('penawaran/' . $penawaran->surat_penawaran);
                }
                $file     = $request->file('surat_penawaran');
                $filename = time() . '_penawaran_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('penawaran', $file, $filename);
                $penawaran->surat_penawaran = $filename;
                $suratPenawaranUploaded     = true;
            }

            if ($request->hasFile('surat_pesanan')) {
                if ($penawaran->surat_pesanan && Storage::disk('public')->exists('penawaran/' . $penawaran->surat_pesanan)) {
                    Storage::disk('public')->delete('penawaran/' . $penawaran->surat_pesanan);
                }
                $file     = $request->file('surat_pesanan');
                $filename = time() . '_pesanan_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('penawaran', $file, $filename);
                $penawaran->surat_pesanan = $filename;
            }

            $penawaran->save();

            if ($suratPenawaranUploaded) {
                $penawaran->status = 'ACC';
                $penawaran->save();

                try {
                    app(\App\Services\NotificationService::class)->penawaranAcc($penawaran->fresh(['proyek']));
                } catch (\Throwable $e) {
                    Log::error('Notification penawaranAcc failed: ' . $e->getMessage(), [
                        'id_penawaran' => $penawaran->id_penawaran,
                        'id_proyek'    => $penawaran->id_proyek,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => $suratPenawaranUploaded
                    ? 'Penawaran berhasil disimpan dan status penawaran diubah menjadi ACC!'
                    : 'Penawaran berhasil disimpan!',
                'data'           => $penawaran,
                'status_changed' => $suratPenawaranUploaded
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Penawaran store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'debug'   => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_penawaran' => 'required|date',
            'catatan'           => 'nullable|string',
            'surat_penawaran'   => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'surat_pesanan'     => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $penawaran = Penawaran::findOrFail($id);
            $penawaran->tanggal_penawaran = $request->tanggal_penawaran;
            $penawaran->catatan           = $request->catatan;

            $penawaranDetails = PenawaranDetail::where('id_penawaran', $penawaran->id_penawaran)->get();
            if ($penawaranDetails->count() > 0) {
                $penawaran->total_nilai = $penawaranDetails->sum('subtotal');
            }

            $suratPenawaranUploaded = false;

            if ($request->hasFile('surat_penawaran')) {
                if ($penawaran->surat_penawaran && Storage::disk('public')->exists('penawaran/' . $penawaran->surat_penawaran)) {
                    Storage::disk('public')->delete('penawaran/' . $penawaran->surat_penawaran);
                }
                $file     = $request->file('surat_penawaran');
                $filename = time() . '_penawaran_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('penawaran', $file, $filename);
                $penawaran->surat_penawaran = $filename;
                $suratPenawaranUploaded     = true;
            }

            if ($request->hasFile('surat_pesanan')) {
                if ($penawaran->surat_pesanan && Storage::disk('public')->exists('penawaran/' . $penawaran->surat_pesanan)) {
                    Storage::disk('public')->delete('penawaran/' . $penawaran->surat_pesanan);
                }
                $file     = $request->file('surat_pesanan');
                $filename = time() . '_pesanan_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('penawaran', $file, $filename);
                $penawaran->surat_pesanan = $filename;
            }

            $penawaran->save();

            if ($suratPenawaranUploaded) {
                $penawaran->status = 'ACC';
                $penawaran->save();

                try {
                    app(\App\Services\NotificationService::class)->penawaranAcc($penawaran->fresh(['proyek']));
                } catch (\Throwable $e) {
                    Log::error('Notification penawaranAcc failed: ' . $e->getMessage(), [
                        'id_penawaran' => $penawaran->id_penawaran,
                        'id_proyek'    => $penawaran->id_proyek,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => $suratPenawaranUploaded
                    ? 'Penawaran berhasil diupdate dan status penawaran diubah menjadi ACC!'
                    : 'Penawaran berhasil diupdate!',
                'data'           => $penawaran,
                'status_changed' => $suratPenawaranUploaded
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Penawaran update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'debug'   => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    public function downloadFile($type, $filename)
    {
        $path = 'public/penawaran/' . $filename;
        if (!Storage::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }
        return Storage::download($path);
    }

    public function getPenawaranByProject($proyekId)
    {
        try {
            $penawaran = Penawaran::where('id_proyek', $proyekId)
                                 ->orderBy('id_penawaran', 'desc')
                                 ->first();

            if (!$penawaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penawaran tidak ditemukan untuk proyek ini'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'id_penawaran'      => $penawaran->id_penawaran,
                    'id_proyek'         => $penawaran->id_proyek,
                    'no_penawaran'      => $penawaran->no_penawaran,
                    'tanggal_penawaran' => $penawaran->tanggal_penawaran,
                    'surat_penawaran'   => $penawaran->surat_penawaran,
                    'surat_pesanan'     => $penawaran->surat_pesanan,
                    'total_nilai'       => $penawaran->total_nilai,
                    'catatan'           => $penawaran->catatan,
                    'status'            => $penawaran->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching penawaran by project: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data penawaran'
            ], 500);
        }
    }

    public function storeSuratPenawaran(Request $request, $proyekId)
    {
        $validated = $request->validate([
            'nomor_surat'             => 'nullable|string|max:255',
            'tempat_surat'            => 'nullable|string|max:255',
            'tanggal_surat'           => 'nullable|date',
            'lampiran'                => 'nullable|string|max:255',
            'kepada'                  => 'nullable|string|max:255',
            'alamat_klien'            => 'nullable|string|max:255',
            'wilayah_klien'           => 'nullable|string|max:255',
            'perihal'                 => 'nullable|string|max:255',
            'jangka_waktu_pengerjaan' => 'nullable|string|max:255',
            'berlaku_sejak'           => 'nullable|date',
            'berlaku_sampai'          => 'nullable|date',
            'id_penawaran'            => 'nullable|integer',
            'lampiran_pdfs'           => 'nullable',
            'lampiran_pdfs.*'         => 'file|mimes:pdf|max:10240',
        ]);

        $proyek = Proyek::findOrFail($proyekId);

        $dbFields = collect($validated)->except(['lampiran_pdfs'])->toArray();

        $surat = SuratPenawaran::updateOrCreate(
            ['id_proyek' => $proyek->id_proyek],
            array_merge($dbFields, ['id_proyek' => $proyek->id_proyek])
        );

        if ($request->hasFile('lampiran_pdfs')) {
            $rawRow   = DB::table('surat_penawaran')->where('id_surat_penawaran', $surat->id_surat_penawaran)->value('lampiran_files');
            $existing = [];
            if ($rawRow) {
                $decoded  = json_decode($rawRow, true);
                $existing = is_array($decoded) ? $decoded : [];
            }

            foreach ($request->file('lampiran_pdfs') as $file) {
                if (!$file || !$file->isValid()) continue;

                $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
                $filename = time() . '_' . uniqid() . '_' . $safeName;
                $path     = $file->storeAs('surat-penawaran/lampiran', $filename, 'public');

                $existing[] = [
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'uploaded_at'   => now()->toDateTimeString(),
                    'size'          => $file->getSize(),
                ];
            }

            DB::table('surat_penawaran')
                ->where('id_surat_penawaran', $surat->id_surat_penawaran)
                ->update(['lampiran_files' => json_encode(array_values($existing))]);
        }

        $surat = SuratPenawaran::find($surat->id_surat_penawaran);

        return response()->json([
            'success'        => true,
            'message'        => 'Surat penawaran berhasil disimpan.',
            'data'           => $surat,
            'lampiran_files' => $surat->lampiran_files_list,
        ]);
    }

    public function deleteSuratPenawaranLampiran(Request $request, $proyekId)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $surat = SuratPenawaran::where('id_proyek', $proyekId)->firstOrFail();
        $path  = $request->input('path');

        $rawRow = DB::table('surat_penawaran')->where('id_surat_penawaran', $surat->id_surat_penawaran)->value('lampiran_files');
        $files  = [];
        if ($rawRow) {
            $decoded = json_decode($rawRow, true);
            $files   = is_array($decoded) ? $decoded : [];
        }

        $newFiles = [];
        foreach ($files as $f) {
            if (($f['path'] ?? null) === $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
                continue;
            }
            $newFiles[] = $f;
        }

        DB::table('surat_penawaran')
            ->where('id_surat_penawaran', $surat->id_surat_penawaran)
            ->update(['lampiran_files' => json_encode(array_values($newFiles))]);

        $surat = SuratPenawaran::find($surat->id_surat_penawaran);

        return response()->json([
            'success'        => true,
            'message'        => 'Lampiran berhasil dihapus.',
            'lampiran_files' => $surat->lampiran_files_list,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PREVIEW — surat penawaran + halaman tabel barang + lampiran PDF
    // ─────────────────────────────────────────────────────────────────────────

    public function previewSuratPenawaran(Request $request, $proyekId)
    {
        $payload = $this->buildSuratPenawaranPayload($request, $proyekId, true);

        // 1. Render surat penawaran utama
        $suratPdf = Pdf::loadView('pages.files.surat-penawaran', $payload)->output();

        // 2. Render halaman tabel penawaran barang (halaman baru)
        $barangPdf = Pdf::loadView('pages.files.penawaran-barang', $payload)->output();

        // 3. Merge: surat + tabel barang + lampiran PDF
        $content = $this->mergeAllPages($suratPdf, $barangPdf, $payload['suratDb'] ?? null);

        return response($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="surat-penawaran-preview.pdf"',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DOWNLOAD — surat penawaran + halaman tabel barang + lampiran PDF
    // ─────────────────────────────────────────────────────────────────────────

    public function downloadSuratPenawaran(Request $request, $proyekId)
    {
        $payload  = $this->buildSuratPenawaranPayload($request, $proyekId, true);

        // 1. Render surat penawaran utama
        $suratPdf = Pdf::loadView('pages.files.surat-penawaran', $payload)->output();

        // 2. Render halaman tabel penawaran barang (halaman baru)
        $barangPdf = Pdf::loadView('pages.files.penawaran-barang', $payload)->output();

        // 3. Merge: surat + tabel barang + lampiran PDF
        $content  = $this->mergeAllPages($suratPdf, $barangPdf, $payload['suratDb'] ?? null);
        $filename = 'surat-penawaran-' . ($payload['proyek']->kode_proyek ?? $proyekId) . '.pdf';

        return response($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MERGE: surat utama + halaman tabel barang + lampiran
    //
    // Urutan halaman PDF akhir:
    //   1. Surat Penawaran  (dari $suratPdfContent)
    //   2. Tabel Penawaran Barang  (dari $barangPdfContent)
    //   3. Lampiran PDF (opsional, dari surat_penawaran.lampiran_files di DB)
    //
    // addRaw() dipanggil TANPA parameter Pages kedua karena iio/libmergepdf
    // versi ini tidak support Pages('all') — default-nya merge semua halaman.
    // ─────────────────────────────────────────────────────────────────────────

    private function mergeAllPages(
    string  $suratPdfContent,
    string  $barangPdfContent,
    $suratDb = null
): string {
    $lampiranList = [];

    try {
        if ($suratDb) {
            $rawRow = DB::table('surat_penawaran')
                ->where('id_surat_penawaran', $suratDb->id_surat_penawaran)
                ->value('lampiran_files');

            if ($rawRow) {
                $decoded      = json_decode($rawRow, true);
                $lampiranList = is_array($decoded) ? $decoded : [];
            }
        }
    } catch (\Throwable $e) {
        Log::warning('mergeAllPages — gagal baca lampiran_files: ' . $e->getMessage());
    }

    try {
        $merger = new Merger();

        // Halaman 1: Surat Penawaran
        $merger->addRaw($suratPdfContent);

        // Halaman 2: Tabel Penawaran Barang — skip jika kosong
        if (!empty($barangPdfContent)) {
            $merger->addRaw($barangPdfContent);
            Log::info('mergeAllPages — barangPdfContent ditambahkan, size: ' . strlen($barangPdfContent));
        } else {
            Log::warning('mergeAllPages — barangPdfContent KOSONG, dilewati');
        }

        // Halaman 3+: Lampiran PDF (opsional)
        foreach ($lampiranList as $f) {
            $path = $f['path'] ?? null;
            if (!$path) continue;

            $absolutePath = storage_path('app/public/' . ltrim($path, '/\\'));
            if (!file_exists($absolutePath)) {
                Log::warning('mergeAllPages — lampiran tidak ditemukan', ['path' => $path]);
                continue;
            }

            $raw = file_get_contents($absolutePath);
            if (!$raw || strlen($raw) === 0) {
                Log::warning('mergeAllPages — lampiran kosong', ['path' => $path]);
                continue;
            }

            $merger->addRaw($raw);
            Log::info('mergeAllPages — lampiran ditambahkan', ['path' => $path]);
        }

        $result = $merger->merge();
        Log::info('mergeAllPages — merge sukses, result size: ' . strlen($result));
        return $result;

    } catch (\Throwable $e) {
        Log::error('mergeAllPages — merge gagal: ' . $e->getMessage() . ' | trace: ' . $e->getTraceAsString());

        // Fallback: minimal merge hanya surat + tabel barang tanpa lampiran
        try {
            $fallbackMerger = new Merger();
            $fallbackMerger->addRaw($suratPdfContent);
            if (!empty($barangPdfContent)) {
                $fallbackMerger->addRaw($barangPdfContent);
            }
            return $fallbackMerger->merge();
        } catch (\Throwable $e2) {
            Log::error('mergeAllPages — fallback merge juga gagal: ' . $e2->getMessage());
            return $suratPdfContent;
        }
    }
}

    /**
     * Tetap ada untuk backward compatibility (dipakai method lama jika ada).
     * Sekarang method ini memanggil mergeAllPages tanpa halaman barang.
     */
    private function mergeSuratWithLampiran(string $suratPdfContent, $suratDb = null): string
    {
        return $this->mergeAllPages($suratPdfContent, '', $suratDb);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // BUILD PAYLOAD
    //
    // Perubahan dari versi sebelumnya:
    //   + Menambahkan $itemsDetail — enriched items dengan data dari tabel barang
    //     (spesifikasi, pdn_tkdn_impor, foto_barang) yang digunakan oleh
    //     view penawaran-barang.blade.php
    // ─────────────────────────────────────────────────────────────────────────

    private function buildSuratPenawaranPayload(Request $request, $proyekId, bool $preferDb = false): array
    {
        $proyek = Proyek::with('proyekBarang')->findOrFail($proyekId);

        $penawaran = Penawaran::where('id_proyek', $proyek->id_proyek)
            ->orderBy('id_penawaran', 'desc')
            ->first();

        if (!$penawaran) {
            $penawaran = Penawaran::create([
                'id_proyek'         => $proyek->id_proyek,
                'tanggal_penawaran' => now(),
                'status'            => 'Menunggu'
            ]);
        }

        $penawaranDetails = PenawaranDetail::where('id_penawaran', $penawaran->id_penawaran)->get();

        // ── Ambil data barang (untuk surat penawaran — link produk) ──────────
        $barangIds  = $penawaranDetails->pluck('id_barang')->filter()->unique()->values();
        $barangById = $barangIds->isNotEmpty()
            ? Barang::whereIn('id_barang', $barangIds)->get()->keyBy('id_barang')
            : collect();

        // ── $items — dipakai oleh surat-penawaran.blade.php (existing) ───────
        $items = $penawaranDetails->map(function ($d) use ($barangById) {
            $barang = null;
            if (!empty($d->id_barang)) {
                $barang = $barangById->get($d->id_barang);
            }
            return [
                'nama_barang'  => $d->nama_barang,
                'qty'          => $d->qty,
                'satuan'       => $d->satuan ?? 'Unit',
                'harga_satuan' => $d->harga_satuan,
                'subtotal'     => $d->subtotal,
                'link'         => $barang->link_produk ?? null,
            ];
        })->values();

        // ── $itemsDetail — dipakai oleh penawaran-barang.blade.php (NEW) ─────
        // Mengambil field tambahan dari tabel barang:
        //   - spesifikasi      → barang.spesifikasi
        //   - pdn_tkdn_impor   → barang.pdn_tkdn_impor
        //   - foto_barang      → barang.foto_barang
        //
        // Jika penawaran_detail punya kolom spesifikasi sendiri, gunakan itu
        // sebagai fallback ketika barang tidak ditemukan.
        $itemsDetail = $penawaranDetails->map(function ($d) use ($barangById) {
            $barang = null;
            if (!empty($d->id_barang)) {
                $barang = $barangById->get($d->id_barang);
            }

            return [
                'nama_barang'    => $d->nama_barang,
                'qty'            => $d->qty,
                'satuan'         => $d->satuan ?? 'Unit',
                'harga_satuan'   => $d->harga_satuan,
                'subtotal'       => $d->subtotal,
                'link'           => $barang->link_produk ?? null,

                // ── Data khusus halaman tabel barang ─────────────────
                // Prioritas: dari tabel barang (relasi id_barang)
                // Fallback : dari kolom di penawaran_detail itu sendiri
                'spesifikasi'    => $barang->spesifikasi
                                    ?? $d->spesifikasi
                                    ?? '',
                'pdn_tkdn_impor' => $barang->pdn_tkdn_impor
                                    ?? $d->pdn_tkdn_impor
                                    ?? '',
                'foto_barang'    => $barang->foto_barang
                                    ?? $d->foto_barang
                                    ?? '',
            ];
        })->values();

        $suratDb = $preferDb
            ? SuratPenawaran::where('id_proyek', $proyek->id_proyek)->first()
            : null;

        $get = fn (string $k, $fallback = null) => $request->query($k, $fallback);

        $optionalCarbonFormat = function ($value, string $format = 'Y-m-d') {
            if (empty($value)) return null;
            try {
                return \Carbon\Carbon::parse($value)->format($format);
            } catch (\Throwable $e) {
                return (string) $value;
            }
        };

        $nomor        = $get('nomor_surat',             $suratDb?->nomor_surat);
        $tempat       = $get('tempat_surat',            $suratDb?->tempat_surat);
        $tanggal      = $get('tanggal_surat',           $optionalCarbonFormat($suratDb?->tanggal_surat));
        $kepada       = $get('kepada',                  $suratDb?->kepada);
        $alamatKlien  = $get('alamat_klien',            $suratDb?->alamat_klien);
        $wilayahKlien = $get('wilayah_klien',           $suratDb?->wilayah_klien);
        $perihal      = $get('perihal',                 $suratDb?->perihal);
        $lampiran     = $get('lampiran',                $suratDb?->lampiran);
        $jangka       = $get('jangka_waktu_pengerjaan', $suratDb?->jangka_waktu_pengerjaan);
        $sejak        = $get('berlaku_sejak',           $optionalCarbonFormat($suratDb?->berlaku_sejak));
        $sampai       = $get('berlaku_sampai',          $optionalCarbonFormat($suratDb?->berlaku_sampai));

        $tempatTanggal = null;
        if (!empty($tempat) && !empty($tanggal)) {
            try {
                $tempatTanggal = $tempat . ', ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');
            } catch (\Throwable $e) {
                $tempatTanggal = $tempat . ', ' . $tanggal;
            }
        } elseif ($suratDb && $suratDb->tanggal_surat && $suratDb->tempat_surat) {
            try {
                $tempatTanggal = $suratDb->tempat_surat . ', ' . \Carbon\Carbon::parse($suratDb->tanggal_surat)->translatedFormat('d F Y');
            } catch (\Throwable $e) {
                $tempatTanggal = $suratDb->tempat_surat . ', ' . (string) $suratDb->tanggal_surat;
            }
        }

        return [
            'proyek'           => $proyek,
            'penawaran'        => $penawaran,
            'penawaranDetails' => $penawaranDetails,
            'surat'            => [
                'nomor'          => $nomor,
                'tempat_tanggal' => $tempatTanggal,
                'lampiran'       => $lampiran,
                'kepada'         => $kepada,
                'alamat_klien'   => $alamatKlien,
                'wilayah_klien'  => $wilayahKlien,
                'perihal'        => $perihal,
                'jangka_waktu'   => $jangka,
                'sejak'          => $sejak,
                'sampai'         => $sampai,
            ],
            'items'       => $items,          // dipakai surat-penawaran.blade.php
            'itemsDetail' => $itemsDetail,    // dipakai penawaran-barang.blade.php (NEW)
            'suratDb'     => $suratDb,
        ];
    }
}