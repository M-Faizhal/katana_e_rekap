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

    Route::get('/marketing', function () {
        return view('pages.marketing');
    })->name('marketing');

    Route::get('/purchasing', function () {
        return view('pages.purchasing');
    })->name('purchasing');

    Route::get('/keuangan', function () {
        return view('pages.keuangan');
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
