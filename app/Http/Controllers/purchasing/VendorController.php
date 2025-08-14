<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::with('barang')->get();
        
        // Calculate statistics
        $totalVendors = $vendors->count();
        $vendorPrinciple = $vendors->where('jenis_perusahaan', 'Principle')->count();
        $vendorDistributor = $vendors->where('jenis_perusahaan', 'Distributor')->count();
        $vendorRetail = $vendors->where('jenis_perusahaan', 'Retail')->count();
        
        return view('pages.purchasing.vendor', compact(
            'vendors', 
            'totalVendors', 
            'vendorPrinciple', 
            'vendorDistributor', 
            'vendorRetail'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'email' => 'required|email|unique:vendor,email',
            'jenis_perusahaan' => 'required|in:Principle,Distributor,Retail,Lain-lain',
            'kontak' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'barang' => 'nullable|array',
            'barang.*.nama_barang' => 'required_with:barang|string|max:255',
            'barang.*.brand' => 'required_with:barang|string|max:255',
            'barang.*.kategori' => 'required_with:barang|in:Elektronik,Meubel,Mesin,Lain-lain',
            'barang.*.satuan' => 'required_with:barang|string|max:255',
            'barang.*.spesifikasi' => 'required_with:barang|string',
            'barang.*.harga_vendor' => 'required_with:barang|numeric|min:0',
            'barang.*.foto_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();

        try {
            // Create vendor
            $vendor = Vendor::create([
                'nama_vendor' => $request->nama_vendor,
                'email' => $request->email,
                'jenis_perusahaan' => $request->jenis_perusahaan,
                'kontak' => $request->kontak,
                'alamat' => $request->alamat,
            ]);

            // Create barang if provided
            if ($request->has('barang') && is_array($request->barang)) {
                foreach ($request->barang as $index => $barangData) {
                    $fotoPath = null;
                    
                    // Handle foto upload
                    if ($request->hasFile("barang.{$index}.foto_barang")) {
                        $foto = $request->file("barang.{$index}.foto_barang");
                        $fotoPath = $foto->store('barang', 'public');
                    }

                    Barang::create([
                        'id_vendor' => $vendor->id_vendor,
                        'nama_barang' => $barangData['nama_barang'],
                        'brand' => $barangData['brand'],
                        'kategori' => $barangData['kategori'],
                        'satuan' => $barangData['satuan'],
                        'spesifikasi' => $barangData['spesifikasi'],
                        'harga_vendor' => $barangData['harga_vendor'],
                        'foto_barang' => $fotoPath,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor berhasil ditambahkan!',
                'vendor' => $vendor->load('barang')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vendor = Vendor::with('barang')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'vendor' => $vendor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'email' => 'required|email|unique:vendor,email,' . $id . ',id_vendor',
            'jenis_perusahaan' => 'required|in:Principle,Distributor,Retail,Lain-lain',
            'kontak' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'barang' => 'nullable|array',
            'barang.*.nama_barang' => 'required_with:barang|string|max:255',
            'barang.*.brand' => 'required_with:barang|string|max:255',
            'barang.*.kategori' => 'required_with:barang|in:Elektronik,Meubel,Mesin,Lain-lain',
            'barang.*.satuan' => 'required_with:barang|string|max:255',
            'barang.*.spesifikasi' => 'required_with:barang|string',
            'barang.*.harga_vendor' => 'required_with:barang|numeric|min:0',
            'barang.*.foto_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();

        try {
            // Update vendor
            $vendor->update([
                'nama_vendor' => $request->nama_vendor,
                'email' => $request->email,
                'jenis_perusahaan' => $request->jenis_perusahaan,
                'kontak' => $request->kontak,
                'alamat' => $request->alamat,
            ]);

            // Handle barang updates
            if ($request->has('barang') && is_array($request->barang)) {
                // Get existing barang IDs
                $existingBarangIds = $vendor->barang->pluck('id_barang')->toArray();
                $updatedBarangIds = [];

                foreach ($request->barang as $index => $barangData) {
                    $fotoPath = null;
                    
                    // Handle foto upload
                    if ($request->hasFile("barang.{$index}.foto_barang")) {
                        $foto = $request->file("barang.{$index}.foto_barang");
                        $fotoPath = $foto->store('barang', 'public');
                    }

                    if (isset($barangData['id_barang']) && $barangData['id_barang']) {
                        // Update existing barang
                        $barang = Barang::findOrFail($barangData['id_barang']);
                        
                        $updateData = [
                            'nama_barang' => $barangData['nama_barang'],
                            'brand' => $barangData['brand'],
                            'kategori' => $barangData['kategori'],
                            'satuan' => $barangData['satuan'],
                            'spesifikasi' => $barangData['spesifikasi'],
                            'harga_vendor' => $barangData['harga_vendor'],
                        ];

                        if ($fotoPath) {
                            // Delete old foto if exists
                            if ($barang->foto_barang) {
                                Storage::disk('public')->delete($barang->foto_barang);
                            }
                            $updateData['foto_barang'] = $fotoPath;
                        }

                        $barang->update($updateData);
                        $updatedBarangIds[] = $barang->id_barang;
                    } else {
                        // Create new barang
                        $newBarang = Barang::create([
                            'id_vendor' => $vendor->id_vendor,
                            'nama_barang' => $barangData['nama_barang'],
                            'brand' => $barangData['brand'],
                            'kategori' => $barangData['kategori'],
                            'satuan' => $barangData['satuan'],
                            'spesifikasi' => $barangData['spesifikasi'],
                            'harga_vendor' => $barangData['harga_vendor'],
                            'foto_barang' => $fotoPath,
                        ]);
                        $updatedBarangIds[] = $newBarang->id_barang;
                    }
                }

                // Delete barang that are no longer in the request
                $barangToDelete = array_diff($existingBarangIds, $updatedBarangIds);
                if (!empty($barangToDelete)) {
                    $barangItems = Barang::whereIn('id_barang', $barangToDelete)->get();
                    foreach ($barangItems as $barang) {
                        if ($barang->foto_barang) {
                            Storage::disk('public')->delete($barang->foto_barang);
                        }
                    }
                    Barang::whereIn('id_barang', $barangToDelete)->delete();
                }
            } else {
                // If no barang in request, delete all existing barang
                $barangItems = $vendor->barang;
                foreach ($barangItems as $barang) {
                    if ($barang->foto_barang) {
                        Storage::disk('public')->delete($barang->foto_barang);
                    }
                }
                $vendor->barang()->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor berhasil diupdate!',
                'vendor' => $vendor->fresh()->load('barang')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $vendor = Vendor::with('barang')->findOrFail($id);

            // Delete all barang photos
            foreach ($vendor->barang as $barang) {
                if ($barang->foto_barang) {
                    Storage::disk('public')->delete($barang->foto_barang);
                }
            }

            // Delete vendor (cascade will delete barang)
            $vendor->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus vendor: ' . $e->getMessage()
            ], 500);
        }
    }

}
