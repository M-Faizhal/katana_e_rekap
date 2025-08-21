<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenagihanDinas;
use App\Models\BuktiPembayaran;
use App\Models\Penawaran;
use App\Models\Proyek;
use App\Models\PenawaranDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenagihanDinasController extends Controller
{
    public function index()
    {
        // Ambil proyek yang sudah di ACC oleh klien
        $proyekAcc = Proyek::with(['penawaran' => function($query) {
            $query->where('status', 'ACC');
        }])
        ->whereHas('penawaran', function($query) {
            $query->where('status', 'ACC');
        })
        ->get();

        // Group berdasarkan status pembayaran
        $proyekBelumBayar = $proyekAcc->filter(function($proyek) {
            $penawaran = $proyek->penawaran->first();
            if (!$penawaran) {
                return false;
            }
            
            // Cek apakah sudah ada penagihan dinas untuk penawaran ini
            $existingPenagihan = PenagihanDinas::where('penawaran_id', $penawaran->id_penawaran)->first();
            return !$existingPenagihan;
        });

        $proyekDp = PenagihanDinas::with(['proyek', 'penawaran', 'buktiPembayaran'])
            ->where('status_pembayaran', 'dp')
            ->get();

        $proyekLunas = PenagihanDinas::with(['proyek', 'penawaran', 'buktiPembayaran'])
            ->where('status_pembayaran', 'lunas')
            ->get();

        return view('pages.keuangan.penagihan', compact(
            'proyekBelumBayar', 
            'proyekDp', 
            'proyekLunas'
        ));
    }

    public function create($proyekId)
    {
        $proyek = Proyek::with(['penawaran' => function($query) {
            $query->where('status', 'ACC');
        }, 'penawaran.penawaranDetail'])->where('id_proyek', $proyekId)->firstOrFail();
        
        $penawaran = $proyek->penawaran->first();
        
        if (!$penawaran) {
            return redirect()->back()->with('error', 'Proyek belum memiliki penawaran yang di ACC.');
        }

        // Hitung total dari detail penawaran
        $totalHarga = $penawaran->penawaranDetail->sum('subtotal');

        return view('pages.keuangan.penagihan-create', compact('proyek', 'penawaran', 'totalHarga'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required|exists:proyek,id_proyek',
            'penawaran_id' => 'required|exists:penawaran,id_penawaran',
            'nomor_invoice' => 'required|string|unique:penagihan_dinas,nomor_invoice',
            'total_harga' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:dp,lunas',
            'persentase_dp' => 'required_if:status_pembayaran,dp|numeric|min:0|max:100',
            'tanggal_jatuh_tempo' => 'required|date',
            'berita_acara_serah_terima' => 'nullable|file|mimes:pdf|max:2048',
            'invoice' => 'nullable|file|mimes:pdf|max:2048',
            'pnbp' => 'nullable|file|mimes:pdf|max:2048',
            'faktur_pajak' => 'nullable|file|mimes:pdf|max:2048',
            'surat_lainnya' => 'nullable|file|mimes:pdf|max:2048',
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Handle file uploads untuk dokumen
            $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
            $uploadedDokumen = [];

            foreach ($dokumenFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('penagihan-dinas/dokumen', $filename, 'public');
                    $uploadedDokumen[$field] = $filename;
                }
            }

            // Hitung nilai DP
            $totalHarga = $request->total_harga;
            $jumlahDp = 0;

            if ($request->status_pembayaran === 'dp') {
                $jumlahDp = ($request->persentase_dp / 100) * $totalHarga;
            }

            // Buat penagihan dinas
            $penagihanDinas = PenagihanDinas::create([
                'proyek_id' => $request->proyek_id,
                'penawaran_id' => $request->penawaran_id,
                'nomor_invoice' => $request->nomor_invoice,
                'total_harga' => $totalHarga,
                'status_pembayaran' => $request->status_pembayaran,
                'persentase_dp' => $request->persentase_dp,
                'jumlah_dp' => $jumlahDp,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'keterangan' => $request->keterangan,
            ] + $uploadedDokumen);

            // Handle bukti pembayaran
            $buktiPembayaranFile = $request->file('bukti_pembayaran');
            $buktiFilename = time() . '_bukti_pembayaran.' . $buktiPembayaranFile->getClientOriginalExtension();
            $buktiPath = $buktiPembayaranFile->storeAs('penagihan-dinas/bukti-pembayaran', $buktiFilename, 'public');

            // Tentukan jenis pembayaran dan jumlah bayar
            $jenisPembayaran = $request->status_pembayaran;
            $jumlahBayar = ($jenisPembayaran === 'dp') ? $jumlahDp : $totalHarga;

            // Buat bukti pembayaran
            BuktiPembayaran::create([
                'penagihan_dinas_id' => $penagihanDinas->id,
                'jenis_pembayaran' => $jenisPembayaran,
                'jumlah_bayar' => $jumlahBayar,
                'tanggal_bayar' => $request->tanggal_bayar,
                'bukti_pembayaran' => $buktiFilename,
                'keterangan' => $request->keterangan_pembayaran
            ]);

            DB::commit();

            return redirect()->route('keuangan.penagihan')
                ->with('success', 'Penagihan dinas berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file yang sudah diupload jika terjadi error
            foreach ($uploadedDokumen as $filename) {
                Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $filename);
            }
            
            if (isset($buktiFilename)) {
                Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiFilename);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $penagihanDinas = PenagihanDinas::with(['proyek', 'penawaran.penawaranDetail', 'buktiPembayaran'])
            ->findOrFail($id);

        return view('pages.keuangan.penagihan-detail', compact('penagihanDinas'));
    }

    public function edit($id)
    {
        $penagihanDinas = PenagihanDinas::with(['proyek', 'penawaran'])->findOrFail($id);

        return view('pages.keuangan.penagihan-edit', compact('penagihanDinas'));
    }

    public function update(Request $request, $id)
    {
        $penagihanDinas = PenagihanDinas::findOrFail($id);

        $request->validate([
            'nomor_invoice' => 'required|string|unique:penagihan_dinas,nomor_invoice,' . $id,
            'tanggal_jatuh_tempo' => 'required|date',
            'berita_acara_serah_terima' => 'nullable|file|mimes:pdf|max:2048',
            'invoice' => 'nullable|file|mimes:pdf|max:2048',
            'pnbp' => 'nullable|file|mimes:pdf|max:2048',
            'faktur_pajak' => 'nullable|file|mimes:pdf|max:2048',
            'surat_lainnya' => 'nullable|file|mimes:pdf|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        try {
            // Handle file uploads untuk dokumen
            $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
            $uploadedDokumen = [];

            foreach ($dokumenFields as $field) {
                if ($request->hasFile($field)) {
                    // Hapus file lama jika ada
                    if ($penagihanDinas->$field) {
                        Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$field);
                    }

                    // Upload file baru
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('penagihan-dinas/dokumen', $filename, 'public');
                    $uploadedDokumen[$field] = $filename;
                }
            }

            // Update penagihan dinas
            $penagihanDinas->update([
                'nomor_invoice' => $request->nomor_invoice,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'keterangan' => $request->keterangan,
            ] + $uploadedDokumen);

            return redirect()->route('penagihan-dinas.show', $id)
                ->with('success', 'Penagihan dinas berhasil diperbarui.');

        } catch (\Exception $e) {
            // Hapus file yang sudah diupload jika terjadi error
            foreach ($uploadedDokumen as $filename) {
                Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $filename);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $penagihanDinas = PenagihanDinas::with('buktiPembayaran')->findOrFail($id);

        try {
            DB::beginTransaction();

            // Hapus semua file dokumen penagihan dinas
            $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
            
            foreach ($dokumenFields as $field) {
                if ($penagihanDinas->$field) {
                    Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$field);
                }
            }

            // Hapus semua file bukti pembayaran
            foreach ($penagihanDinas->buktiPembayaran as $bukti) {
                if ($bukti->bukti_pembayaran) {
                    Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $bukti->bukti_pembayaran);
                }
            }

            // Hapus semua bukti pembayaran (cascade delete)
            $penagihanDinas->buktiPembayaran()->delete();

            // Hapus penagihan dinas
            $penagihanDinas->delete();

            DB::commit();

            return redirect()->route('keuangan.penagihan')
                ->with('success', 'Penagihan dinas beserta semua file berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function deleteDokumen($id, $jenis)
    {
        $penagihanDinas = PenagihanDinas::findOrFail($id);

        // Validasi jenis dokumen
        $allowedTypes = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
        if (!in_array($jenis, $allowedTypes)) {
            return redirect()->back()->with('error', 'Jenis dokumen tidak valid.');
        }

        try {
            // Hapus file dari storage jika ada
            if ($penagihanDinas->$jenis) {
                Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$jenis);
                
                // Update database dengan null
                $penagihanDinas->update([$jenis => null]);
            }

            return redirect()->back()
                ->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus dokumen: ' . $e->getMessage());
        }
    }

    public function deleteBuktiPembayaran($buktiId)
    {
        $buktiPembayaran = BuktiPembayaran::with('penagihanDinas')->findOrFail($buktiId);
        $penagihanDinas = $buktiPembayaran->penagihanDinas;

        // Validasi: tidak boleh menghapus bukti DP jika status sudah lunas
        if ($buktiPembayaran->jenis_pembayaran === 'dp' && $penagihanDinas->status_pembayaran === 'lunas') {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus bukti pembayaran DP karena sudah ada pelunasan.');
        }

        try {
            DB::beginTransaction();

            // Hapus file dari storage
            if ($buktiPembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiPembayaran->bukti_pembayaran);
            }

            // Jika yang dihapus adalah bukti pelunasan, update status kembali ke DP
            if ($buktiPembayaran->jenis_pembayaran === 'lunas') {
                $penagihanDinas->update(['status_pembayaran' => 'dp']);
            }

            // Jika yang dihapus adalah bukti DP dan tidak ada pelunasan, hapus seluruh penagihan
            if ($buktiPembayaran->jenis_pembayaran === 'dp') {
                // Cek apakah ada bukti pelunasan
                $hasLunas = $penagihanDinas->buktiPembayaran()
                    ->where('id', '!=', $buktiId)
                    ->where('jenis_pembayaran', 'lunas')
                    ->exists();

                if (!$hasLunas) {
                    // Hapus semua dokumen penagihan dinas
                    $dokumenFields = ['berita_acara_serah_terima', 'invoice', 'pnbp', 'faktur_pajak', 'surat_lainnya'];
                    
                    foreach ($dokumenFields as $field) {
                        if ($penagihanDinas->$field) {
                            Storage::disk('public')->delete('penagihan-dinas/dokumen/' . $penagihanDinas->$field);
                        }
                    }

                    // Hapus penagihan dinas
                    $penagihanDinas->delete();

                    DB::commit();

                    return redirect()->route('keuangan.penagihan')
                        ->with('success', 'Bukti pembayaran DP dihapus. Penagihan dinas telah dihapus karena tidak ada pembayaran.');
                }
            }

            // Hapus bukti pembayaran
            $buktiPembayaran->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Bukti pembayaran berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus bukti pembayaran: ' . $e->getMessage());
        }
    }

    public function addPelunasan(Request $request, $id)
    {
        $penagihanDinas = PenagihanDinas::findOrFail($id);

        // Validasi hanya bisa menambah pelunasan jika status masih DP
        if ($penagihanDinas->status_pembayaran !== 'dp') {
            return redirect()->back()->with('error', 'Pelunasan hanya dapat ditambahkan untuk pembayaran DP.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Handle upload bukti pembayaran
            $buktiPembayaranFile = $request->file('bukti_pembayaran');
            $buktiFilename = time() . '_bukti_pelunasan.' . $buktiPembayaranFile->getClientOriginalExtension();
            $buktiPath = $buktiPembayaranFile->storeAs('penagihan-dinas/bukti-pembayaran', $buktiFilename, 'public');

            // Hitung sisa pembayaran
            $totalBayar = $penagihanDinas->buktiPembayaran->sum('jumlah_bayar');
            $sisaPembayaran = $penagihanDinas->total_harga - $totalBayar;

            // Buat bukti pembayaran pelunasan
            BuktiPembayaran::create([
                'penagihan_dinas_id' => $penagihanDinas->id,
                'jenis_pembayaran' => 'lunas',
                'jumlah_bayar' => $sisaPembayaran,
                'tanggal_bayar' => $request->tanggal_bayar,
                'bukti_pembayaran' => $buktiFilename,
                'keterangan' => $request->keterangan
            ]);

            // Update status menjadi lunas
            $penagihanDinas->update([
                'status_pembayaran' => 'lunas'
            ]);

            DB::commit();

            return redirect()->route('penagihan-dinas.show', $id)
                ->with('success', 'Pelunasan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if (isset($buktiFilename)) {
                Storage::disk('public')->delete('penagihan-dinas/bukti-pembayaran/' . $buktiFilename);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function history($id)
    {
        $penagihanDinas = PenagihanDinas::with(['proyek', 'penawaran', 'buktiPembayaran'])
            ->findOrFail($id);

        return view('pages.keuangan.penagihan-history', compact('penagihanDinas'));
    }

    public function downloadDokumen($id, $jenis)
    {
        $penagihanDinas = PenagihanDinas::findOrFail($id);
        
        if (!$penagihanDinas->$jenis) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $filePath = 'penagihan-dinas/dokumen/' . $penagihanDinas->$jenis;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function downloadBuktiPembayaran($buktiId)
    {
        $buktiPembayaran = BuktiPembayaran::findOrFail($buktiId);
        
        $filePath = 'penagihan-dinas/bukti-pembayaran/' . $buktiPembayaran->bukti_pembayaran;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function showPelunasan($id)
    {
        $penagihanDinas = PenagihanDinas::with(['proyek', 'penawaran.penawaranDetail', 'buktiPembayaran'])
            ->findOrFail($id);

        // Validasi hanya bisa mengakses pelunasan jika status masih DP
        if ($penagihanDinas->status_pembayaran !== 'dp') {
            return redirect()->route('penagihan-dinas.show', $id)
                ->with('error', 'Pelunasan hanya dapat diakses untuk pembayaran DP.');
        }

        // Hitung sisa pembayaran
        $totalBayar = $penagihanDinas->buktiPembayaran->sum('jumlah_bayar');
        $sisaPembayaran = $penagihanDinas->total_harga - $totalBayar;

        return view('pages.keuangan.penagihan-pelunasan', compact('penagihanDinas', 'sisaPembayaran'));
    }
}
