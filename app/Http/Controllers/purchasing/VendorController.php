<?php

namespace App\Http\Controllers\purchasing;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        try {
            // Debug log request
            Log::info('=== VENDOR STORE REQUEST START ===');
            Log::info('Request method: ' . $request->method());
            Log::info('Request URL: ' . $request->url());
            Log::info('Request headers: ', $request->headers->all());
            Log::info('Form data: ', $request->except(['barang']));
            Log::info('Barang data exists: ' . ($request->has('barang') ? 'YES' : 'NO'));
            if ($request->has('barang')) {
                Log::info('Barang count: ' . count($request->barang));
                Log::info('Barang data: ', $request->barang);
            }
            Log::info('Files: ', $request->allFiles());
            
            // Check if user has permission
            if (Auth::user()->role !== 'admin_purchasing' && Auth::user()->role !== 'superadmin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menambah vendor'
                ], 403);
            }

            // Validate basic vendor data first
            $basicValidation = $request->validate([
                'nama_vendor' => 'required|string|max:255',
                'email' => 'required|email|unique:vendor,email',
                'jenis_perusahaan' => 'required|in:Principle,Distributor,Retail,Lain-lain',
                'kontak' => 'required|string|max:255',
                'alamat' => 'nullable|string',
            ]);
            
            Log::info('Basic validation passed');
            
            // Validate barang data if present
            if ($request->has('barang') && is_array($request->barang)) {
                Log::info('Validating barang data...');
                $request->validate([
                    'barang' => 'array',
                    'barang.*.nama_barang' => 'required|string|max:255',
                    'barang.*.brand' => 'required|string|max:255',
                    'barang.*.kategori' => 'required|in:Elektronik,Meubel,Mesin,Lain-lain',
                    'barang.*.satuan' => 'required|string|max:255',
                    'barang.*.spesifikasi' => 'nullable|string',
                    'barang.*.spesifikasi_file' => 'nullable|file|mimes:pdf,doc,docx,txt,xls,xlsx|max:5120',
                    'barang.*.harga_vendor' => 'required|numeric|min:0',
                    'barang.*.foto_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);
                Log::info('Barang validation passed');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }

        DB::beginTransaction();

        try {
            // Debug log
            Log::info('Vendor store request data:', [
                'vendor_data' => $request->only(['nama_vendor', 'email', 'jenis_perusahaan', 'kontak', 'alamat']),
                'barang_count' => $request->has('barang') ? count($request->barang) : 0,
                'barang_keys' => $request->has('barang') ? array_keys($request->barang) : []
            ]);

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
                    // Skip if required fields are empty
                    if (empty($barangData['nama_barang']) || empty($barangData['brand']) || 
                        empty($barangData['kategori']) || empty($barangData['satuan']) || 
                        empty($barangData['harga_vendor'])) {
                        continue;
                    }

                    $fotoPath = null;
                    $spesifikasiFilePath = null;
                    
                    // Handle foto upload
                    if ($request->hasFile("barang.{$index}.foto_barang")) {
                        try {
                            $foto = $request->file("barang.{$index}.foto_barang");
                            $fotoPath = $foto->store('barang/foto', 'public');
                        } catch (\Exception $e) {
                            // Continue without photo if upload fails
                            Log::error('Failed to upload photo for barang: ' . $e->getMessage());
                        }
                    }

                    // Handle spesifikasi file upload
                    if ($request->hasFile("barang.{$index}.spesifikasi_file")) {
                        try {
                            $spesifikasiFile = $request->file("barang.{$index}.spesifikasi_file");
                            $spesifikasiFilePath = $spesifikasiFile->store('barang/spesifikasi', 'public');
                        } catch (\Exception $e) {
                            // Continue without spesifikasi file if upload fails
                            Log::error('Failed to upload spesifikasi file for barang: ' . $e->getMessage());
                        }
                    }

                    Barang::create([
                        'id_vendor' => $vendor->id_vendor,
                        'nama_barang' => $barangData['nama_barang'],
                        'brand' => $barangData['brand'],
                        'kategori' => $barangData['kategori'],
                        'satuan' => $barangData['satuan'],
                        'spesifikasi' => $barangData['spesifikasi'] ?? '',
                        'spesifikasi_file' => $spesifikasiFilePath,
                        'harga_vendor' => $barangData['harga_vendor'],
                        'foto_barang' => $fotoPath,
                    ]);
                }
            }

            DB::commit();
            
            Log::info('Vendor created successfully with ID: ' . $vendor->id_vendor);

            return response()->json([
                'success' => true,
                'message' => 'Vendor berhasil ditambahkan!',
                'vendor' => $vendor->load('barang')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating vendor: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
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
        // Check if user has permission
        if (Auth::user()->role !== 'admin_purchasing' && Auth::user()->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit vendor'
            ], 403);
        }

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
            'barang.*.spesifikasi' => 'nullable|string',
            'barang.*.spesifikasi_file' => 'nullable|file|mimes:pdf,doc,docx,txt,xls,xlsx|max:5120',
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
                    $spesifikasiFilePath = null;
                    
                    // Handle foto upload
                    if ($request->hasFile("barang.{$index}.foto_barang")) {
                        $foto = $request->file("barang.{$index}.foto_barang");
                        $fotoPath = $foto->store('barang/foto', 'public');
                    }

                    // Handle spesifikasi file upload
                    if ($request->hasFile("barang.{$index}.spesifikasi_file")) {
                        $spesifikasiFile = $request->file("barang.{$index}.spesifikasi_file");
                        $spesifikasiFilePath = $spesifikasiFile->store('barang/spesifikasi', 'public');
                    }

                    if (isset($barangData['id_barang']) && $barangData['id_barang']) {
                        // Update existing barang
                        $barang = Barang::findOrFail($barangData['id_barang']);
                        
                        $updateData = [
                            'nama_barang' => $barangData['nama_barang'],
                            'brand' => $barangData['brand'],
                            'kategori' => $barangData['kategori'],
                            'satuan' => $barangData['satuan'],
                            'spesifikasi' => $barangData['spesifikasi'] ?? '',
                            'harga_vendor' => $barangData['harga_vendor'],
                        ];

                        if ($fotoPath) {
                            // Delete old foto if exists
                            if ($barang->foto_barang) {
                                Storage::disk('public')->delete($barang->foto_barang);
                            }
                            $updateData['foto_barang'] = $fotoPath;
                        }

                        if ($spesifikasiFilePath) {
                            // Delete old spesifikasi file if exists
                            if ($barang->spesifikasi_file) {
                                Storage::disk('public')->delete($barang->spesifikasi_file);
                            }
                            $updateData['spesifikasi_file'] = $spesifikasiFilePath;
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
                            'spesifikasi' => $barangData['spesifikasi'] ?? '',
                            'spesifikasi_file' => $spesifikasiFilePath,
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
                        if ($barang->spesifikasi_file) {
                            Storage::disk('public')->delete($barang->spesifikasi_file);
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
                    if ($barang->spesifikasi_file) {
                        Storage::disk('public')->delete($barang->spesifikasi_file);
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
        // Check if user has permission
        if (Auth::user()->role !== 'admin_purchasing' && Auth::user()->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus vendor'
            ], 403);
        }

        DB::beginTransaction();

        try {
            $vendor = Vendor::with('barang')->findOrFail($id);

            // Delete all barang photos and spesifikasi files
            foreach ($vendor->barang as $barang) {
                if ($barang->foto_barang) {
                    Storage::disk('public')->delete($barang->foto_barang);
                }
                if ($barang->spesifikasi_file) {
                    Storage::disk('public')->delete($barang->spesifikasi_file);
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