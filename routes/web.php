<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



// // Auth routes
// Route::middleware('guest')->group(function () {
    //     Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    //     Route::get('/login', [AuthController::class, 'showLogin']);
//     Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
// });

// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

 Route::get('/', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/laporan', function () {
        return view('pages.laporan');
    })->name('laporan');

// Marketing Routes
Route::prefix('marketing')->group(function () {
    Route::get('/penawaran', function () {
        return view('pages.marketing.penawaran');
    })->name('marketing.penawaran');

    Route::get('/wilayah', function () {
        return view('pages.marketing.wilayah');
    })->name('marketing.wilayah');

    Route::get('/potensi', function () {
        return view('pages.marketing.potensi');
    })->name('marketing.potensi');
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

    Route::get('/keuangan', function () {
        return view('pages.keuangan.keuangan');
    })->name('keuangan');

    Route::get('/produk', function () {
        return view('pages.produk');
    })->name('produk');

    Route::get('/pengaturan', function () {
        return view('pages.pengaturan');
    })->name('pengaturan');

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
