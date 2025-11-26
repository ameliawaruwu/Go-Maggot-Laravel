<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GalleryController; 
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
use App\Http\Controllers\ManageStatusPesananController;


# Home
Route::get('/home', [HomeController::class, 'index'])->name('home');


# Halaman lain (sementara pakai view placeholder biar link navbar/footer jalan)
Route::view('/about', 'about')->name('about');
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

// Manage Gallery


// Manage Status Pesanan
Route::get('/manageStatus', [ManageStatusPesananController::class, 'index']);
Route::get('/manageStatus-input', [ManageStatusPesananController::class, 'input']);
Route::post('/manageStatus-simpan', [ManageStatusPesananController::class, 'simpan']);
Route::get('/manageStatus-edit/{id_status_pesanan}', [ManageStatusPesananController::class, 'edit']);
Route::post('/manageStatus-update/{id_status_pesanan}', [ManageStatusPesananController::class, 'update']);
Route::get('/manageStatus-hapus/{id_status_pesanan}', [ManageStatusPesananController::class, 'delete']);


// Manage Publication
Route::get('/publication', [ManagePublicationController::class, 'tampil'])
    ->name('publication.index');
Route::get('/publication/input', [ManagePublicationController::class, 'input'])
    ->name('publication.create');
Route::post('/publication/simpan', [ManagePublicationController::class, 'simpan'])
    ->name('publication.store');
Route::get('/publication/edit/{id}', [ManagePublicationController::class, 'edit'])
    ->name('publication.edit');
Route::put('/publication/update/{id}', [ManagePublicationController::class, 'update'])
    ->name('publication.update');
Route::delete('/publication/hapus/{id}', [ManagePublicationController::class, 'hapus'])
    ->name('publication.destroy');


// Manage Setting
Route::get('/managesetting', [ManageSettingController::class, 'index'])->name('settings.index');
Route::post('/managesetting', [ManageSettingController::class, 'update'])->name('settings.update');



// PRODUK
Route::get('/daftar-produk', [ProductController::class, 'index'])->name('product.index');
Route::get('/product-detail/{id_produk}', [ProductController::class, 'show']);

// CHECKOUT
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
// Sinkronisasi AJAX dari JavaScript ke Session
Route::post('/checkout/sync', [CheckoutController::class, 'sync']);
Route::post('/checkout/instant-process', [CheckoutController::class, 'instantProcess'])->name('checkout.instant');
// Route::post('/checkout/to-form', [CheckoutController::class, 'redirectToCheckoutForm'])->name('checkout.redirect');

// PEMBAYARAN
Route::get('/pembayaran/{order_id}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');


// // Halaman detail produk (walaupun belum dibuat, route-nya disiapkan)
// Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

// // Route POST untuk memproses form pembayaran (mengirim data ke tabel pembayaran)
// Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');

// // Tampilan halaman checkout
// Route::get('produk', [ProductController::class, 'index'])->name('product.index');
// Route::get('/produk/detail/{id}', [ProductController::class, 'show'])->name('product.detail');


// STATUS PESANAN 
Route::get('/status-pesanan/{order_id}', [OrderController::class, 'showStatus'])->name('orders.status');

// EDUKASI
Route::get('/belajar', [StudyController::class, 'index'])->name('study.index');
// Route::get('/artikel/{slug}', [GalleryController::class, 'showArtikel'])->name('article.show');
Route::get('/study/artikel/{id_artikel}', [ArticleController::class, 'show'])->name('article.show');
// Route untuk menampilkan daftar artikel
Route::get('/study/artikel', [ArticleController::class, 'index'])->name('article.index');
Route::get('/article/{id_artikel}', [GalleryController::class, 'showArtikel'])->name('article.show');

// FAQ 
Route::get('/qna', [QnaController::class, 'index'])->name('qna');

// GALERI
Route::get('/galeri', [HomeController::class, 'index'])->name('gallery.gallery');

