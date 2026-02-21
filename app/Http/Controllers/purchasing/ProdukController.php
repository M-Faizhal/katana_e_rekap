<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    /**
     * Display a listing of products (Optimized Version)
     */
    public function index_produk_purchasing(Request $request)
    {
        return $this->getProductList($request, 'pages.purchasing.produk');
    }
    
    public function index_produk(Request $request)
    {
        return $this->getProductList($request, 'pages.produk');
    }

    /**
     * Produk Marketing — hanya tampilkan barang yang ada di kalkulasi_hps,
     * dengan harga diambil dari harga_yang_diharapkan terbaru per id_barang.
     */
    public function index_produk_marketing(Request $request)
    {
        // Subquery: ambil harga_yang_diharapkan terbaru per id_barang dari kalkulasi_hps
        $latestHarga = DB::table('kalkulasi_hps as k')
            ->select('k.id_barang', DB::raw('k.harga_yang_diharapkan as harga_marketing'))
            ->join(DB::raw('(SELECT id_barang, MAX(id_kalkulasi) as max_id FROM kalkulasi_hps WHERE id_barang IS NOT NULL GROUP BY id_barang) as latest'), function ($join) {
                $join->on('k.id_barang', '=', 'latest.id_barang')
                     ->on('k.id_kalkulasi', '=', 'latest.max_id');
            });

        // ID barang yang pernah masuk kalkulasi_hps
        $barangIds = DB::table('kalkulasi_hps')
            ->whereNotNull('id_barang')
            ->distinct()
            ->pluck('id_barang');

        // Terapkan filter harga marketing (berdasarkan kalkulasi_hps)
        $filteredIds = $barangIds;
        if ($request->filled('min_harga') || $request->filled('max_harga')) {
            $hargaQuery = DB::table('kalkulasi_hps as k')
                ->join(DB::raw('(SELECT id_barang, MAX(id_kalkulasi) as max_id FROM kalkulasi_hps WHERE id_barang IS NOT NULL GROUP BY id_barang) as latest'), function ($join) {
                    $join->on('k.id_barang', '=', 'latest.id_barang')
                         ->on('k.id_kalkulasi', '=', 'latest.max_id');
                })
                ->whereNotNull('k.harga_yang_diharapkan');
            if ($request->filled('min_harga')) {
                $hargaQuery->where('k.harga_yang_diharapkan', '>=', $request->min_harga);
            }
            if ($request->filled('max_harga')) {
                $hargaQuery->where('k.harga_yang_diharapkan', '<=', $request->max_harga);
            }
            $filteredIds = $hargaQuery->pluck('k.id_barang');
        }

        $query = Barang::with('vendor')
            ->whereIn('id_barang', $filteredIds);

        // Filter non-harga (search, kategori, vendor, pdn_tkdn_impor)
        $this->applyFiltersMarketing($query, $request);

        // Sort
        $sortBy    = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $produk = $query->paginate(12)->withQueryString();

        // Pasangkan harga_marketing ke tiap item
        $hargaMap = $latestHarga->get()->keyBy('id_barang');
        $produk->getCollection()->transform(function ($item) use ($hargaMap) {
            $item->harga_marketing = isset($hargaMap[$item->id_barang])
                ? (float) $hargaMap[$item->id_barang]->harga_marketing
                : null;
            return $item;
        });

        $vendors    = Vendor::orderBy('nama_vendor')->get();
        $categories = ['Elektronik', 'Meubel', 'Mesin', 'Lain-lain'];

        // Statistik khusus: hanya barang yang ada di kalkulasi_hps
        $stats = DB::table('barang')
            ->whereIn('id_barang', $barangIds)
            ->selectRaw('
                COUNT(*) as totalProduk,
                COUNT(CASE WHEN kategori = "Elektronik" THEN 1 END) as produkElektronik,
                COUNT(CASE WHEN kategori = "Meubel"    THEN 1 END) as produkMeubel,
                COUNT(CASE WHEN kategori = "Mesin"     THEN 1 END) as produkMesin,
                COUNT(CASE WHEN kategori = "Lain-lain" THEN 1 END) as produkLainLain
            ')
            ->first();

        return view('pages.produk', [
            'produk'           => $produk,
            'vendors'          => $vendors,
            'categories'       => $categories,
            'totalProduk'      => $stats->totalProduk      ?? 0,
            'produkElektronik' => $stats->produkElektronik ?? 0,
            'produkMeubel'     => $stats->produkMeubel     ?? 0,
            'produkMesin'      => $stats->produkMesin      ?? 0,
            'produkLainLain'   => $stats->produkLainLain   ?? 0,
        ]);
    }
    
    /**
     * Shared method to handle product listing with optimization
     */
    private function getProductList(Request $request, string $viewPath)
    {
        // Single query with eager loading
        $query = Barang::with('vendor');
        
        // Apply filters
        $this->applyFilters($query, $request);
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Paginate
        $produk = $query->paginate(12)->withQueryString();
        
        // Get cached vendors (cache for 1 hour since vendors don't change often)
        $vendors = Cache::remember('vendors_list', 3600, function() {
            return Vendor::orderBy('nama_vendor')->get();
        });
        
        // Get statistics with single query
        $statistics = $this->getProductStatistics();
        
        // Static categories
        $categories = ['Elektronik', 'Meubel', 'Mesin', 'Lain-lain'];
        
        return view($viewPath, array_merge([
            'produk' => $produk,
            'vendors' => $vendors,
            'categories' => $categories,
        ], $statistics));
    }
    
    /**
     * Apply filters for marketing produk page (no harga_vendor filter — use harga_marketing instead)
     */
    private function applyFiltersMarketing($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%")
                  ->orWhere('spesifikasi', 'LIKE', "%{$search}%")
                  ->orWhereHas('vendor', function($vendorQuery) use ($search) {
                      $vendorQuery->where('nama_vendor', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('vendor')) {
            $query->where('id_vendor', $request->vendor);
        }

        if ($request->filled('pdn_tkdn_impor')) {
            $query->where('pdn_tkdn_impor', $request->pdn_tkdn_impor);
        }
    }

    /**
     * Apply search and filter conditions
     */
    private function applyFilters($query, Request $request)
    {
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%")
                  ->orWhere('spesifikasi', 'LIKE', "%{$search}%")
                  ->orWhereHas('vendor', function($vendorQuery) use ($search) {
                      $vendorQuery->where('nama_vendor', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Category filter
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        
        // Vendor filter
        if ($request->filled('vendor')) {
            $query->where('id_vendor', $request->vendor);
        }
        
        // PDN/TKDN/Impor filter
        if ($request->filled('pdn_tkdn_impor')) {
            $query->where('pdn_tkdn_impor', $request->pdn_tkdn_impor);
        }
        
        // Price range filters
        if ($request->filled('min_harga')) {
            $query->where('harga_vendor', '>=', $request->min_harga);
        }
        
        if ($request->filled('max_harga')) {
            $query->where('harga_vendor', '<=', $request->max_harga);
        }
    }
    
    /**
     * Get product statistics with single optimized query
     */
    private function getProductStatistics()
    {
        // Cache statistics for 30 minutes since they don't change frequently
        return Cache::remember('product_statistics', 1800, function() {
            // Single query to get all statistics
            $stats = DB::table('barang')
                ->selectRaw('
                    COUNT(*) as totalProduk,
                    COUNT(CASE WHEN kategori = "Elektronik" THEN 1 END) as produkElektronik,
                    COUNT(CASE WHEN kategori = "Meubel" THEN 1 END) as produkMeubel,
                    COUNT(CASE WHEN kategori = "Mesin" THEN 1 END) as produkMesin,
                    COUNT(CASE WHEN kategori = "Lain-lain" THEN 1 END) as produkLainLain
                ')
                ->first();
                
            return [
                'totalProduk' => $stats->totalProduk,
                'produkElektronik' => $stats->produkElektronik,
                'produkMeubel' => $stats->produkMeubel,
                'produkMesin' => $stats->produkMesin,
                'produkLainLain' => $stats->produkLainLain,
            ];
        });
    }
    
    /**
     * Display the specified product
     */
    public function show($id)
    {
        $produk = Barang::with('vendor')->findOrFail($id);

        // Ambil harga_yang_diharapkan terbaru dari kalkulasi_hps untuk barang ini
        $latestKalkulasi = DB::table('kalkulasi_hps')
            ->where('id_barang', $id)
            ->whereNotNull('harga_yang_diharapkan')
            ->orderBy('id_kalkulasi', 'desc')
            ->value('harga_yang_diharapkan');

        $produk->harga_marketing = $latestKalkulasi ? (float) $latestKalkulasi : null;

        return response()->json([
            'success' => true,
            'produk' => $produk
        ]);
    }

    /**
     * Update only link_produk for a product
     */
    public function updateLink(Request $request, $id)
    {
        $request->validate([
            'link_produk' => 'nullable|string|max:2048',
        ]);

        $produk = Barang::findOrFail($id);
        $produk->link_produk = $request->link_produk ?: null;
        $produk->save();

        return response()->json([
            'success' => true,
            'message' => 'Link produk berhasil diperbarui.',
            'link_produk' => $produk->link_produk,
        ]);
    }
    
    /**
     * Export products to Excel based on filters with images
     */
    public function export(Request $request)
    {
        // Create export instance with filters
        $export = new \App\Exports\ProdukExport($request->all());
        
        // Execute export (akan langsung download)
        return $export->export();
    }
    
    /**
     * Clear product cache (call this when products are updated)
     */
    public function clearCache()
    {
        Cache::forget('vendors_list');
        Cache::forget('product_statistics');
        
        return response()->json(['message' => 'Cache cleared successfully']);
    }
}