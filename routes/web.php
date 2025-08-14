<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\superadmin\PengelolaanAkun;
use App\Http\Controllers\purchasing\VendorController;
use App\Http\Controllers\purchasing\ProdukController;



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

        Route::get('/kalkulasi', function () {
            return view('pages.purchasing.kalkulasi');
        })->name('purchasing.kalkulasi');

        Route::get('/pembayaran', function () {
            return view('pages.purchasing.pembayaran');
        })->name('purchasing.pembayaran');

        Route::get('/pengiriman', function () {
            return view('pages.purchasing.pengiriman');
        })->name('purchasing.pengiriman');
    });

    // Keuangan Routes
    Route::prefix('keuangan')->group(function () {
        Route::get('/', function () {
            return view('pages.keuangan.keuangan');
        })->name('keuangan');

        Route::get('/approval', function () {
            return view('pages.keuangan.approval');
        })->name('keuangan.approval');

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
    });
});
