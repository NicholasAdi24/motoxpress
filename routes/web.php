<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PembayaranController;

// Rute untuk landing page
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Rute untuk halaman login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Hapus atau komentari rute berikut karena menimpa rute landing page
// Route::get('/', function () {
//     return view('welcome');
// });

// Authentication Routes (Login, Register, etc.) - Perbaiki penempatan
Auth::routes();

// Redirect setelah login sesuai role
Route::get('/home', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'kasir') { // Tambahkan elseif untuk role kasir
            return redirect()->route('kasir.dashboard');
        }        
    }
    return redirect('/login'); // Pastikan ada redirect jika tidak ada user
})->name('home');

// Group untuk admin
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('admin/barang', BarangController::class)->names([
        'index'   => 'admin.barang.index',
        'create'  => 'admin.barang.create',
        'store'   => 'admin.barang.store',
        'edit'    => 'admin.barang.edit',
        'update'  => 'admin.barang.update',
        'destroy' => 'admin.barang.destroy',
    ]);
});

// Group untuk kasir
Route::group(['middleware' => ['auth', 'role:kasir']], function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.dashboard');
    Route::get('/riwayat-transaksi', [PembayaranController::class, 'index'])->name('riwayat.transaksi');
});
Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');

// Rute tes (opsional)
Route::get('/test-role', function () {
    return 'Role middleware is working.';
})->middleware('role:admin');
