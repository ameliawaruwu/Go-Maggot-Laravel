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

# Home
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', fn() => redirect()->route('home'));

# Halaman lain (sementara pakai view placeholder biar link navbar/footer jalan)
Route::view('/about', 'about')->name('about');
Route::view('/products', 'products')->name('products'); 
Route::view('/contact', 'contact')->name('contact');
Route::view('/help', 'help')->name('help');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/cart', 'cart')->name('cart');
Route::view('/feedback', 'feedback')->name('feedback');
Route::view('/portfolio', 'portfolio')->name('portfolio');

// bawaan 
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Admin
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

// Analytics
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

// Manage Produk Admin
Route::get('/manageProduk', [ManageProductsController::class, 'index']);



// Manage Gallery


// Manage Publication

// TAMPIL
Route::get('/publication', [ManagePublicationController::class, 'tampil'])
    ->name('publication.index');

// FORM INPUT
Route::get('/publication/input', [ManagePublicationController::class, 'input'])
    ->name('publication.create');

// SIMPAN
Route::post('/publication/simpan', [ManagePublicationController::class, 'simpan'])
    ->name('publication.store');

// EDIT
Route::get('/publication/edit/{id}', [ManagePublicationController::class, 'edit'])
    ->name('publication.edit');

// UPDATE (HANYA INI)
Route::put('/publication/update/{id}', [ManagePublicationController::class, 'update'])
    ->name('publication.update');

// HAPUS
Route::delete('/publication/hapus/{id}', [ManagePublicationController::class, 'hapus'])
    ->name('publication.destroy');

    
// Manage User


// Manage Review

// Manage Faq

// Manage Setting
Route::get('/managesetting', [ManageSettingController::class, 'index'])->name('settings.index');
Route::post('/managesetting', [ManageSettingController::class, 'update'])->name('settings.update');

// Produk
Route::get('/daftar-produk', [ProductController::class, 'index'])->name('product.index');
Route::get('/product-detail/{id_produk}', [ProductController::class, 'show']);

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');



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
Route::get('/galeri', [HomeController::class, 'index'])->name('gallery.gallery');

// Gunakan ArticleController untuk artikel detail
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('article.show'); // SOLUSI WAJIB

// --- FALLBACK ---
Route::fallback(function () {
    return redirect()->route('home.index');
});
