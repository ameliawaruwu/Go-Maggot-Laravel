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
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
// Manajemen Produk Admin
Route::get('/manageProduk', [ManageProductsController::class, 'index']);
Route::get('/manageProduk-input', [ManageProductsController::class, 'input']);
Route::post('/manageProduk-simpan', [ManageProductsController::class, 'simpan']);
Route::get('/manageProduk-edit/{id_produk}', [ManageProductsController::class, 'edit']);
Route::post('/manageProduk-update/{id_produk}', [ManageProductsController::class, 'update']);
Route::get('/manageProduk-hapus/{id_produk}', [ManageProductsController::class, 'delete']);

// Manajemen Pengguna Admin
Route::get('/manageUser', [ManageUserController::class, 'index']);
Route::get('manageUser-input', [ManageUserController::class, 'input']);
Route::post('/manageUser-simpan', [ManageUserController::class, 'simpan']);
Route::get('/manageUser-edit/{id_pengguna}', [ManageUserController::class, 'edit']);
Route::post('/manageUser-update/{id_pengguna}', [ManageUserController::class, 'update']);
Route::get('/manageUser-hapus/{id_pengguna}', [ManageUserController::class, 'delete']);

// Manage Review Admin
Route::get('/manageReview', [ManageReviewController::class, 'index']);
Route::post('/manageReview-approve/{id}', [ManageReviewController::class, 'approve']);
Route::post('/manageReview-reject/{id}', [ManageReviewController::class, 'reject']);

// Manage Faq Admin
Route::get('/manageFaq', [ManageFaqController::class, 'index']);
Route::get('/manageFaq-input', [ManageFaqController::class, 'input']);
Route::post('/manageFaq-simpan', [ManageFaqController::class, 'simpan']);
Route::get('/manageFaq-edit/{id_faq}', [ManageFaqController::class, 'edit']);
Route::post('/manageFaq-update/{id_faq}', [ManageFaqController::class, 'update'] );
Route::get('/manageFaq-hapus/{id_faq}', [ManageFaqController::class, 'delete']);

// Manage Setting
Route::get('/managesetting', [ManageSettingController::class, 'index'])->name('settings.index');
Route::post('/managesetting', [ManageSettingController::class, 'update'])->name('settings.update');





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
