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

                // Notifikasi: Penawaran ACC (ke semua role)
                try {
                    app(\App\Services\NotificationService::class)->penawaranAcc($penawaran->fresh(['proyek']));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Notification penawaranAcc failed: ' . $e->getMessage(), [
                        'id_penawaran' => $penawaran->id_penawaran,
                        'id_proyek' => $penawaran->id_proyek,
                    ]);
                    // Jangan gagalkan proses upload/ACC hanya karena notifikasi gagal
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

                // Notifikasi: Penawaran ACC (ke semua role)
                try {
                    app(\App\Services\NotificationService::class)->penawaranAcc($penawaran->fresh(['proyek']));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Notification penawaranAcc failed: ' . $e->getMessage(), [
                        'id_penawaran' => $penawaran->id_penawaran,
                        'id_proyek' => $penawaran->id_proyek,
                    ]);
                    // Jangan gagalkan proses upload/ACC hanya karena notifikasi gagal
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
            // Baca raw JSON langsung dari DB — hindari masalah getter/accessor
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

                Log::info('Lampiran diupload', [
                    'path'          => $path,
                    'exists_check'  => Storage::disk('public')->exists($path),
                    'original_name' => $file->getClientOriginalName(),
                    'size'          => $file->getSize(),
                ]);

                $existing[] = [
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'uploaded_at'   => now()->toDateTimeString(),
                    'size'          => $file->getSize(),
                ];
            }

            // Simpan langsung ke DB via query builder — bypass getter/setter Eloquent
            DB::table('surat_penawaran')
                ->where('id_surat_penawaran', $surat->id_surat_penawaran)
                ->update(['lampiran_files' => json_encode(array_values($existing))]);
        }

        // Fresh dari DB
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

        // Baca raw dari DB
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

    public function previewSuratPenawaran(Request $request, $proyekId)
    {
        $payload = $this->buildSuratPenawaranPayload($request, $proyekId, true);
        $pdf     = Pdf::loadView('pages.files.surat-penawaran', $payload);
        $content = $this->mergeSuratWithLampiran($pdf->output(), $payload['suratDb'] ?? null);

        return response($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="surat-penawaran-preview.pdf"',
        ]);
    }

    public function downloadSuratPenawaran(Request $request, $proyekId)
    {
        $payload  = $this->buildSuratPenawaranPayload($request, $proyekId, true);
        $pdf      = Pdf::loadView('pages.files.surat-penawaran', $payload);
        $content  = $this->mergeSuratWithLampiran($pdf->output(), $payload['suratDb'] ?? null);
        $filename = 'surat-penawaran-' . ($payload['proyek']->kode_proyek ?? $proyekId) . '.pdf';

        return response($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Merge PDF surat penawaran dengan file lampiran.
     *
     * PENTING: addRaw() dipanggil TANPA parameter Pages kedua.
     * Library iio/libmergepdf versi ini tidak support Pages('all') —
     * memanggil addRaw() tanpa Pages akan merge semua halaman secara default.
     */
    private function mergeSuratWithLampiran(string $suratPdfContent, $suratDb = null): string
    {
        $lampiranList = [];

        try {
            if ($suratDb) {
                // Baca raw JSON langsung dari DB — paling aman, tidak lewat getter Eloquent
                $rawRow = DB::table('surat_penawaran')
                    ->where('id_surat_penawaran', $suratDb->id_surat_penawaran)
                    ->value('lampiran_files');

                if ($rawRow) {
                    $decoded      = json_decode($rawRow, true);
                    $lampiranList = is_array($decoded) ? $decoded : [];
                }
            }

            Log::info('mergeSuratWithLampiran — START', [
                'suratDb_id'          => $suratDb?->id_surat_penawaran,
                'lampiran_list_count' => count($lampiranList),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal baca lampiran_files dari DB: ' . $e->getMessage());
        }

        if (empty($lampiranList)) {
            return $suratPdfContent;
        }

        try {
            $merger = new Merger();

            // Tambah surat utama — TANPA Pages parameter
            $merger->addRaw($suratPdfContent);

            foreach ($lampiranList as $f) {
                $path = $f['path'] ?? null;

                if (!$path) {
                    Log::warning('Skip lampiran: path kosong', ['entry' => $f]);
                    continue;
                }

                // Path di DB relatif terhadap disk 'public'
                // File fisik: storage/app/public/surat-penawaran/lampiran/xxx.pdf
                $absolutePath = storage_path('app/public/' . ltrim($path, '/\\'));

                if (!file_exists($absolutePath)) {
                    Log::warning('Skip lampiran: file tidak ditemukan', [
                        'path'         => $path,
                        'absolutePath' => $absolutePath,
                    ]);
                    continue;
                }

                $raw = file_get_contents($absolutePath);

                if (!$raw || strlen($raw) === 0) {
                    Log::warning('Skip lampiran: file kosong', ['path' => $path]);
                    continue;
                }

                // Tambah lampiran — TANPA Pages parameter
                $merger->addRaw($raw);
                Log::info('Lampiran berhasil di-merge', ['path' => $path]);
            }

            $merged = $merger->merge();
            Log::info('mergeSuratWithLampiran — DONE', ['size' => strlen($merged)]);
            return $merged;

        } catch (\Throwable $e) {
            Log::error('PDF merge gagal, fallback ke surat saja: ' . $e->getMessage());
            return $suratPdfContent;
        }
    }

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

        $barangIds  = $penawaranDetails->pluck('id_barang')->filter()->unique()->values();
        $barangById = $barangIds->isNotEmpty()
            ? Barang::whereIn('id_barang', $barangIds)->get()->keyBy('id_barang')
            : collect();

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
            'items'   => $items,
            'suratDb' => $suratDb,
        ];
    }
}