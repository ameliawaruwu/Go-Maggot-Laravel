<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;

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