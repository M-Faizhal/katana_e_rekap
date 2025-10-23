<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Revisi;
use App\Models\Proyek;
use App\Models\User;
use App\Models\KalkulasiHps;
use App\Models\Penawaran;
use App\Models\PenagihanDinas;
use App\Models\Pembayaran;
use App\Models\Pengiriman;

class RevisiController extends Controller
{
    /**
     * Menampilkan daftar revisi berdasarkan role user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query dasar - Semua role bisa melihat semua revisi
        $query = Revisi::with(['proyek', 'createdBy', 'handledBy']);
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tipe revisi
        if ($request->filled('tipe_revisi')) {
            $query->where('tipe_revisi', $request->tipe_revisi);
        }
        
        // Filter berdasarkan yang mengerjakan
        if ($request->filled('handled_by')) {
            $query->where('handled_by', $request->handled_by);
        }
        
        // Search berdasarkan ID proyek atau yang mengerjakan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search di kode proyek
                $q->whereHas('proyek', function($subQ) use ($search) {
                    $subQ->where('kode_proyek', 'like', '%' . $search . '%')
                         ->orWhere('id_proyek', 'like', '%' . $search . '%');
                })
                // Search di nama yang mengerjakan
                ->orWhereHas('handledBy', function($subQ) use ($search) {
                    $subQ->where('nama', 'like', '%' . $search . '%');
                });
            });
        }
        
        // Urutan: Menunggu, Sedang Dikerjakan, Selesai, Ditolak
        $query->orderByRaw("FIELD(status, 'pending', 'in_progress', 'completed', 'rejected')")
              ->orderBy('created_at', 'desc');
        
        $revisi = $query->paginate(10)->withQueryString();
        
        return view('pages.revisi.index', compact('revisi'));
    }
    
    /**
     * Menampilkan form untuk membuat revisi baru
     */
    public function create($proyekId, $tipeRevisi)
    {
        $proyek = Proyek::findOrFail($proyekId);
        $user = Auth::user();
        
        // Validasi akses
        if (!$this->canCreateRevision($user, $proyek, $tipeRevisi)) {
            abort(403, 'Akses ditolak untuk membuat revisi jenis ini.');
        }
        
        return view('pages.revisi.create', compact('proyek', 'tipeRevisi'));
    }
    
    /**
     * Menyimpan revisi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_proyek' => 'required|exists:proyek,id_proyek',
            'tipe_revisi' => 'required|in:proyek,hps_penawaran,penawaran,penagihan_dinas,pembayaran,pengiriman',
            'target_id' => 'nullable|integer',
            'keterangan' => 'required|string|max:1000'
        ]);
        
        $user = Auth::user();
        $proyek = Proyek::findOrFail($request->id_proyek);
        
        // Validasi akses
        if (!$this->canCreateRevision($user, $proyek, $request->tipe_revisi)) {
            abort(403, 'Akses ditolak untuk membuat revisi jenis ini.');
        }
        
        try {
            DB::beginTransaction();
            
            Revisi::create([
                'id_proyek' => $request->id_proyek,
                'tipe_revisi' => $request->tipe_revisi,
                'target_id' => $request->target_id,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
                'created_by' => $user->id_user
            ]);
            
            DB::commit();
            
            return redirect()->route('revisi.index')
                ->with('success', 'Revisi berhasil dibuat dan akan segera ditangani.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat revisi: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Menampilkan detail revisi dan form untuk menangani revisi
     */
    public function show($id)
    {
        $revisi = Revisi::with(['proyek', 'createdBy', 'handledBy'])->findOrFail($id);
        $user = Auth::user();
        
        // Validasi akses
        if (!$this->canViewRevision($user, $revisi)) {
            abort(403, 'Akses ditolak untuk melihat revisi ini.');
        }
        
        // Ambil data terkait berdasarkan tipe revisi
        $targetData = $this->getTargetData($revisi);
        
        return view('pages.revisi.show', compact('revisi', 'targetData'));
    }
    
    /**
     * Mengambil alih penanganan revisi
     */
    public function takeRevision($id)
    {
        $revisi = Revisi::findOrFail($id);
        $user = Auth::user();
        
        // Validasi akses
        if (!$this->canHandleRevision($user, $revisi)) {
            abort(403, 'Akses ditolak untuk menangani revisi ini.');
        }
        
        try {
            $revisi->update([
                'status' => 'in_progress',
                'handled_by' => $user->id_user
            ]);
            
            return redirect()->back()
                ->with('success', 'Anda telah mengambil alih penanganan revisi ini.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Menyelesaikan revisi
     */
    public function complete(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'nullable|string|max:1000'
        ]);
        
        $revisi = Revisi::findOrFail($id);
        $user = Auth::user();
        
        // Validasi akses
        if (!$this->canHandleRevision($user, $revisi) || $revisi->handled_by !== $user->id_user) {
            abort(403, 'Akses ditolak untuk menyelesaikan revisi ini.');
        }
        
        try {
            $revisi->update([
                'status' => 'completed',
                'catatan_revisi' => $request->catatan_revisi
            ]);
            
            return redirect()->route('revisi.index')
                ->with('success', 'Revisi berhasil diselesaikan.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Menolak revisi
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|max:1000'
        ]);
        
        $revisi = Revisi::findOrFail($id);
        $user = Auth::user();
        
        // Validasi akses
        if (!$this->canHandleRevision($user, $revisi)) {
            abort(403, 'Akses ditolak untuk menolak revisi ini.');
        }
        
        try {
            $revisi->update([
                'status' => 'rejected',
                'handled_by' => $user->id_user,
                'catatan_revisi' => $request->catatan_revisi
            ]);
            
            return redirect()->route('revisi.index')
                ->with('success', 'Revisi berhasil ditolak.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Validasi apakah user bisa membuat revisi
     */
    private function canCreateRevision($user, $proyek, $tipeRevisi)
    {
        // Hanya superadmin yang bisa membuat revisi
        return $user->role === 'superadmin';
    }
    
    /**
     * Validasi apakah user bisa melihat revisi
     */
    private function canViewRevision($user, $revisi)
    {
        // Semua role bisa melihat revisi
        return true;
    }
    
    /**
     * Validasi apakah user bisa menangani revisi
     */
    private function canHandleRevision($user, $revisi)
    {
        // Semua role bisa menangani revisi
        return true;
    }
    
    /**
     * Mengambil data target berdasarkan tipe revisi
     */
    private function getTargetData($revisi)
    {
        switch ($revisi->tipe_revisi) {
            case 'proyek':
                return $revisi->proyek;
                
            case 'hps_penawaran':
                $hps = KalkulasiHps::where('id_proyek', $revisi->id_proyek)->get();
                $penawaran = Penawaran::where('id_proyek', $revisi->id_proyek)->get();
                return ['hps' => $hps, 'penawaran' => $penawaran];
                
            case 'penawaran':
                return Penawaran::where('id_proyek', $revisi->id_proyek)->get();
                
            case 'penagihan_dinas':
                return PenagihanDinas::where('proyek_id', $revisi->id_proyek)->get();
                
            case 'pembayaran':
                return Pembayaran::whereHas('penawaran', function($q) use ($revisi) {
                    $q->where('id_proyek', $revisi->id_proyek);
                })->get();
                
            case 'pengiriman':
                return Pengiriman::whereHas('penawaran', function($q) use ($revisi) {
                    $q->where('id_proyek', $revisi->id_proyek);
                })->get();
                
            default:
                return null;
        }
    }
}
