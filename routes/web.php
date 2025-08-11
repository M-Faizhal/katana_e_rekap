<?php

use Illuminate\Support\Facades\Route;



// Dashboard Routes
Route::get('/dashboard', function () {
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

// Dummy logout route (implement proper authentication later)
Route::post('/logout', function () {
    return redirect('/');
})->name('logout');
