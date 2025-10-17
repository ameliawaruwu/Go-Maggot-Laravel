<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;

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

// Tampilan halaman checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Proses pengiriman form checkout (POST)
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// Halaman sukses setelah checkout
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Tambahkan rute untuk simulasi halaman keranjang agar 'Kembali ke keranjang' berfungsi
Route::get('/keranjang', function() {
    return "Halaman Keranjang (Simulasi)"; 
})->name('keranjang.index');
