<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GalleryController; // Pastikan ini tidak mengganggu
use App\Http\Controllers\QnaController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;


// --- ROUTE UMUM ---
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

// --- ROUTE E-COMMERCE ---
Route::get('produk', [ProductController::class, 'index'])->name('product.index');
Route::get('/produk/detail/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

// ROUTE PEMBAYARAN
// 1. GET: Untuk menampilkan form pembayaran
Route::get('/payment-form', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
// 2. POST: Untuk menerima dan memproses data yang disubmit dari form
Route::post('/payment-form', [PaymentController::class, 'processPayment'])->name('payment.process');

// ROUTE STATUS PESANAN 
Route::get('/status-pesanan/{order_id}', [OrderController::class, 'showStatus'])->name('orders.status');

// --- ROUTE BELAJAR & KONTEN ---
Route::get('/belajar', [StudyController::class, 'index'])->name('study.index');
// Route::get('/artikel/{slug}', [GalleryController::class, 'showArtikel'])->name('article.show');

// Halaman FAQ
Route::get('/qna', [QnaController::class, 'index'])->name('qna');

// ROUTE GALERI
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.gallery');

// Gunakan ArticleController untuk artikel detail
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('article.show'); // SOLUSI WAJIB

// --- FALLBACK ---
Route::fallback(function () {
    return redirect()->route('product.index');
});
