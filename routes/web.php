<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// CRUD Manajemen Stok & Barang
Route::resource('barang', BarangController::class)->except(['show']);
Route::patch('barang/{barang}/stok', [BarangController::class, 'updateStok'])->name('barang.update-stok');

// Transaksi (Pemotongan Stok Otomatis)
Route::resource('transaksi', TransaksiController::class)->only(['index', 'create', 'store']);

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');