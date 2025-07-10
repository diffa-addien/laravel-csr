<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BerandaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route untuk Halaman Beranda
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Route untuk Halaman Semua Berita (dengan pagination)
Route::get('/berita', [BerandaController::class, 'semuaBerita'])->name('berita.index');

// Route untuk Halaman Detail Berita (berdasarkan slug)
Route::get('/berita/{slug}', [BerandaController::class, 'showBerita'])->name('berita.show');

Route::get('/contoh-berita', function () {
  return view('beranda');
});
