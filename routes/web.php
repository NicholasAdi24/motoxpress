<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PembayaranController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes (Login, Register, etc.)
Auth::routes();

// Redirect setelah login sesuai role
Route::get('/home', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('kasir.dashboard');
    }
    return redirect('/login');
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

Route::group(['middleware' => ['auth', 'role:kasir']], function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.dashboard');
    Route::get('/riwayat-transaksi', [PembayaranController::class, 'index'])->name('riwayat.transaksi');
});
Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');


Route::get('/test-role', function () {
    return 'Role middleware is working.';
})->middleware('role:admin');

// Group untuk kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.dashboard');
});

