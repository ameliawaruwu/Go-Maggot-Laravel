<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('welcome');
});

// Dashboard 
Route::get('/dashboard', [DashboardController::class, 'dashboard']);
// Halaman utama (daftar produk)
Route::get('/', [ProductController::class, 'index'])->name('product.index');

// Halaman detail produk (walaupun belum dibuat, route-nya disiapkan)
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
