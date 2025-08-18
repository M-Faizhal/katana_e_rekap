<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\superadmin\PengelolaanAkun;
use App\Http\Controllers\superadmin\VerifikasiProyekController;
use App\Http\Controllers\purchasing\VendorController;
use App\Http\Controllers\purchasing\ProdukController;
use App\Http\Controllers\purchasing\KalkulasiController;
use App\Http\Controllers\purchasing\PembayaranController;
use App\Http\Controllers\purchasing\PengirimanController;
use App\Http\Controllers\keuangan\ApprovalController;



// Auth routes
Route::middleware('guest')->group(function () {
        Route::get('/', [AuthController::class, 'showLogin'])->name('login');
        Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes with authentication
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/laporan', function () {
        return view('pages.laporan');
    })->name('laporan');

    // Marketing Routes
    Route::prefix('marketing')->group(function () {
        Route::get('/proyek', function () {
            return view('pages.marketing.proyek');
        })->name('marketing.proyek');

        Route::get('/wilayah', function () {
            return view('pages.marketing.wilayah');
        })->name('marketing.wilayah');

        Route::get('/potensi', function () {
            return view('pages.marketing.potensi');
        })->name('marketing.potensi');

        Route::get('/penawaran', function () {
            return view('pages.marketing.penawaran');
        })->name('marketing.penawaran');
    });

    // Purchasing Routes
    Route::prefix('purchasing')->group(function () {
        // Produk Routes
        Route::get('/produk', [ProdukController::class, 'index_produk_purchasing'])->name('purchasing.produk');
        Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

        // Vendor Routes
        Route::get('/vendor', [VendorController::class, 'index'])->name('purchasing.vendor');
        Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
        Route::get('/vendor/{id}', [VendorController::class, 'show'])->name('vendor.show');
        Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
        Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');

        // Kalkulasi Routes
        Route::get('/kalkulasi', [KalkulasiController::class, 'index'])->name('purchasing.kalkulasi');
        Route::get('/kalkulasi/proyek/{id}', [KalkulasiController::class, 'getProyekData'])->name('kalkulasi.proyek');
        Route::get('/kalkulasi/barang', [KalkulasiController::class, 'getBarangList'])->name('kalkulasi.barang');
        Route::get('/kalkulasi/vendor', [KalkulasiController::class, 'getVendorList'])->name('kalkulasi.vendor');
        Route::post('/kalkulasi/save', [KalkulasiController::class, 'saveKalkulasi'])->name('kalkulasi.save');
        Route::post('/kalkulasi/penawaran', [KalkulasiController::class, 'createPenawaran'])->name('kalkulasi.penawaran');

        // Pembayaran Routes
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('purchasing.pembayaran');
        Route::get('/pembayaran/create/{id_proyek}', [PembayaranController::class, 'create'])->name('purchasing.pembayaran.create');
        Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('purchasing.pembayaran.store');
        Route::get('/pembayaran/{id_pembayaran}', [PembayaranController::class, 'show'])->name('purchasing.pembayaran.show');
        Route::get('/pembayaran/{id_pembayaran}/edit', [PembayaranController::class, 'edit'])->name('purchasing.pembayaran.edit');
        Route::put('/pembayaran/{id_pembayaran}', [PembayaranController::class, 'update'])->name('purchasing.pembayaran.update');
        Route::delete('/pembayaran/{id_pembayaran}', [PembayaranController::class, 'destroy'])->name('purchasing.pembayaran.destroy');
        Route::get('/pembayaran/history/{id_proyek}', [PembayaranController::class, 'history'])->name('purchasing.pembayaran.history');
        Route::get('/pembayaran/suggestion/{id_proyek}', [PembayaranController::class, 'calculateSuggestion'])->name('purchasing.pembayaran.suggestion');
        Route::post('/pembayaran/cleanup-files', [PembayaranController::class, 'cleanupOrphanedFiles'])->name('purchasing.pembayaran.cleanup');

        // Pengiriman Routes
        Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('purchasing.pengiriman');
        Route::get('/pengiriman/{id}/detail', [PengirimanController::class, 'getDetailWithFiles'])->name('pengiriman.detail');
        Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('pengiriman.store');
        Route::put('/pengiriman/{id}/update-dokumentasi', [PengirimanController::class, 'updateDokumentasi'])->name('pengiriman.update-dokumentasi');
        Route::put('/pengiriman/{id}/update-surat-jalan', [PengirimanController::class, 'updateSuratJalan'])->name('pengiriman.update-surat-jalan');
        Route::put('/pengiriman/{id}/verify', [PengirimanController::class, 'verify'])->name('pengiriman.verify');
        Route::delete('/pengiriman/{id}', [PengirimanController::class, 'destroy'])->name('pengiriman.destroy');
        Route::post('/pengiriman/cleanup-files', [PengirimanController::class, 'cleanupOrphanedFiles'])->name('pengiriman.cleanup');
    });

    // Keuangan Routes
    Route::prefix('keuangan')->group(function () {
        // Approval routes
        Route::get('/approval', [ApprovalController::class, 'index'])->name('keuangan.approval');
        Route::get('/approval/{id}', [ApprovalController::class, 'show'])->name('keuangan.approval.detail');
        Route::post('/approval/{id}/approve', [ApprovalController::class, 'approve'])->name('keuangan.approval.approve');
        Route::post('/approval/{id}/reject', [ApprovalController::class, 'reject'])->name('keuangan.approval.reject');
        Route::get('/approval-approved', [ApprovalController::class, 'approved'])->name('keuangan.approval.approved');
        Route::get('/approval-rejected', [ApprovalController::class, 'rejected'])->name('keuangan.approval.rejected');

        Route::get('/penagihan', function () {
            return view('pages.keuangan.penagihan');
        })->name('keuangan.penagihan');
    });

    Route::get('/produk', [ProdukController::class, 'index_produk'])->name('produk');


    Route::get('/pengaturan', [App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan');
    Route::put('/pengaturan', [App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.update');

    // Admin only routes
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/pengelolaan-akun', [PengelolaanAkun::class, 'index'])->name('pengelolaan.akun');
        Route::post('/pengelolaan-akun', [PengelolaanAkun::class, 'store'])->name('pengelolaan.akun.store');
        Route::get('/pengelolaan-akun/{user}', [PengelolaanAkun::class, 'show'])->name('pengelolaan.akun.show');
        Route::put('/pengelolaan-akun/{user}', [PengelolaanAkun::class, 'update'])->name('pengelolaan.akun.update');
        Route::delete('/pengelolaan-akun/{user}', [PengelolaanAkun::class, 'destroy'])->name('pengelolaan.akun.destroy');
        
        // Verifikasi Proyek Routes
        Route::get('/verifikasi-proyek', [VerifikasiProyekController::class, 'index'])->name('superadmin.verifikasi-proyek');
        Route::get('/verifikasi-proyek/{id}', [VerifikasiProyekController::class, 'show'])->name('superadmin.verifikasi-proyek.detail');
        Route::put('/verifikasi-proyek/{id}/verify', [VerifikasiProyekController::class, 'verify'])->name('superadmin.verifikasi-proyek.verify');
        Route::get('/verifikasi-proyek-history', [VerifikasiProyekController::class, 'history'])->name('superadmin.verifikasi-proyek.history');
    });
});
