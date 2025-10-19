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
use App\Http\Controllers\ManageProductsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ManageGalleryController;
use App\Http\Controllers\ManagePublicationController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ManageReviewController;
use App\Http\Controllers\ManageFaqController;
use App\Http\Controllers\ManageSettingController;




// --- ROUTE UMUM ---
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

// bawaan 
Route::get('/', function () {
    return view('welcome');
});

// Dashboard 
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

// Analytics
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

// Manage Publication
Route::prefix('manageprod')->group(function () {
    Route::get('/', [ManageProductsController::class, 'index'])->name('products.index'); 
    Route::get('/tambah', [ManageProductsController::class, 'create'])->name('products.create');
    Route::post('/', [ManageProductsController::class, 'store'])->name('products.store'); 
    Route::get('/{id}/edit', [ManageProductsController::class, 'edit'])->name('products.edit');
    Route::put('/{id}', [ManageProductsController::class, 'update'])->name('products.update');
    Route::delete('/{id}', [ManageProductsController::class, 'destroy'])->name('products.destroy');
});

// Manage Gallery
Route::prefix('managegaleri')->group(function () {
    Route::get('/', [ManageGalleryController::class, 'index'])->name('gallery.index');
    Route::get('/tambah2', [ManageGalleryController::class, 'create'])->name('gallery.create');
    Route::post('/', [ManageGalleryController::class, 'store'])->name('gallery.store');
    Route::get('/{id}/edit', [ManageGalleryController::class, 'edit'])->name('gallery.edit');
    Route::put('/{id}', [ManageGalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/{id}', [ManageGalleryController::class, 'destroy'])->name('gallery.destroy');
});

// Manage Publication
Route::prefix('managepublication')->name('publication.')->group(function () {
    Route::get('/', [ManagePublicationController::class, 'index'])->name('index');
    Route::get('/create', [ManagePublicationController::class, 'create'])->name('create');
    Route::post('/', [ManagePublicationController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ManagePublicationController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ManagePublicationController::class, 'update'])->name('update');
    Route::delete('/{id}', [ManagePublicationController::class, 'destroy'])->name('destroy');
});

// Manage User
Route::prefix('manageuser')->name('user.')->group(function () {
    Route::get('/', [ManageUserController::class, 'index'])->name('index');
    Route::get('/create', [ManageUserController::class, 'create'])->name('create');
    Route::post('/', [ManageUserController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ManageUserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ManageUserController::class, 'update'])->name('update');
    Route::delete('/{id}', [ManageUserController::class, 'destroy'])->name('destroy');
});

// Manage Review

// Menggunakan prefix URL 'managereview'
Route::prefix('managereview')->name('review.')->group(function () {
    Route::get('/', [ManageReviewController::class, 'index'])->name('index');
    Route::put('/{id}/status', [ManageReviewController::class, 'updateStatus'])->name('updateStatus');
    Route::delete('/{id}', [ManageReviewController::class, 'destroy'])->name('destroy');
});

// Manage Faq
Route::resource('managefaq', ManageFaqController::class)->names([
    'index' => 'managefaq.index',    
    'store' => 'managefaq.store',    
    'update' => 'managefaq.update', 
    'destroy' => 'managefaq.destroy',
]);

// Manage Setting
Route::get('/managesetting', [ManageSettingController::class, 'index'])->name('settings.index');
Route::post('/managesetting', [ManageSettingController::class, 'update'])->name('settings.update');




// Halaman utama (daftar produk)
Route::get('/', [ProductController::class, 'index'])->name('product.index');

// Halaman detail produk (walaupun belum dibuat, route-nya disiapkan)
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

// Tampilan halaman checkout
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
