<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



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
        Route::get('/produk', function () {
            return view('pages.purchasing.produk');
        })->name('purchasing.produk');

        Route::get('/vendor', function () {
            return view('pages.purchasing.vendor');
        })->name('purchasing.vendor');

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

    Route::get('/produk', function () {
        return view('pages.produk');
    })->name('produk');

    Route::get('/pengaturan', function () {
        return view('pages.pengaturan');
    })->name('pengaturan');

    // Admin only routes
    Route::middleware('superadmin')->group(function () {
        Route::get('/pengelolaan-akun', function () {
            return view('pages.pengelolaan-akun');
        })->name('pengelolaan.akun');
    });
});

// // Protected pages
// Route::middleware('auth')->group(function () {
//     // Dashboard Routes
//     Route::get('/dashboard', function () {
//         return view('pages.dashboard');
//     })->name('dashboard');

//     Route::get('/laporan', function () {
//         return view('pages.laporan');
//     })->name('laporan');

//     Route::get('/marketing', function () {
//         return view('pages.marketing');
//     })->name('marketing');

//     Route::get('/purchasing', function () {
//         return view('pages.purchasing');
//     })->name('purchasing');

//     Route::get('/keuangan', function () {
//         return view('pages.keuangan');
//     })->name('keuangan');

//     Route::get('/produk', function () {
//         return view('pages.produk');
//     })->name('produk');

//     Route::get('/pengaturan', function () {
//         return view('pages.pengaturan');
//     })->name('pengaturan');
// });
