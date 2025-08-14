<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index_produk_purchasing(Request $request)
    {
        $query = Barang::with('vendor');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
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
        
        // Filter by category
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        
        // Filter by vendor
        if ($request->has('vendor') && $request->vendor) {
            $query->where('id_vendor', $request->vendor);
        }
        
        // Filter by price range
        if ($request->has('min_harga') && $request->min_harga) {
            $query->where('harga_vendor', '>=', $request->min_harga);
        }
        
        if ($request->has('max_harga') && $request->max_harga) {
            $query->where('harga_vendor', '<=', $request->max_harga);
        }
        
        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Paginate results
        $produk = $query->paginate(12)->withQueryString();
        
        // Get vendors for filter dropdown
        $vendors = Vendor::orderBy('nama_vendor')->get();
        
        // Calculate statistics
        $totalProduk = Barang::count();
        $produkElektronik = Barang::where('kategori', 'Elektronik')->count();
        $produkMeubel = Barang::where('kategori', 'Meubel')->count();
        $produkMesin = Barang::where('kategori', 'Mesin')->count();
        
        // Get all categories for filter
        $categories = ['Elektronik', 'Meubel', 'Mesin', 'Lain-lain'];
        
        return view('pages.purchasing.produk', compact(
            'produk', 
            'vendors', 
            'categories',
            'totalProduk', 
            'produkElektronik', 
            'produkMeubel', 
            'produkMesin'
        ));
    }
    public function index_produk(Request $request)
    {
        $query = Barang::with('vendor');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
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
        
        // Filter by category
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        
        // Filter by vendor
        if ($request->has('vendor') && $request->vendor) {
            $query->where('id_vendor', $request->vendor);
        }
        
        // Filter by price range
        if ($request->has('min_harga') && $request->min_harga) {
            $query->where('harga_vendor', '>=', $request->min_harga);
        }
        
        if ($request->has('max_harga') && $request->max_harga) {
            $query->where('harga_vendor', '<=', $request->max_harga);
        }
        
        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Paginate results
        $produk = $query->paginate(12)->withQueryString();
        
        // Get vendors for filter dropdown
        $vendors = Vendor::orderBy('nama_vendor')->get();
        
        // Calculate statistics
        $totalProduk = Barang::count();
        $produkElektronik = Barang::where('kategori', 'Elektronik')->count();
        $produkMeubel = Barang::where('kategori', 'Meubel')->count();
        $produkMesin = Barang::where('kategori', 'Mesin')->count();
        
        // Get all categories for filter
        $categories = ['Elektronik', 'Meubel', 'Mesin', 'Lain-lain'];
        
        return view('pages.produk', compact(
            'produk', 
            'vendors', 
            'categories',
            'totalProduk', 
            'produkElektronik', 
            'produkMeubel', 
            'produkMesin'
        ));
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
}
