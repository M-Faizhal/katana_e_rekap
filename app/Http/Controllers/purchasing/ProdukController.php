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
        
        return response()->json([
            'success' => true,
            'produk' => $produk
        ]);
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