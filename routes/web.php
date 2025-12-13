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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;


//auth 

// LOGIN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// REGISTER
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', fn() => view('welcome'));

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/help', 'help')->name('help');
Route::view('/portfolio', 'portfolio')->name('portfolio');

Route::get('/', function () {
    return view('welcome');
});

// PRODUK
Route::get('/daftar-produk', [ProductController::class, 'index'])->name('product.index');
Route::get('/product-detail/{id_produk}', [ProductController::class, 'show']);

// CHECKOUT
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/sync', [CheckoutController::class, 'sync']);
Route::post('/checkout/instant-process', [CheckoutController::class, 'instantProcess'])->name('checkout.instant');
// Route::post('/checkout/to-form', [CheckoutController::class, 'redirectToCheckoutForm'])->name('checkout.redirect');

// PEMBAYARAN
Route::get('/pembayaran/{order_id}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');

// STATUS PESANAN 
Route::get('/status-pesanan/{order_id}', [OrderController::class, 'showStatus'])->name('orders.status');

// Gallery
Route::get('/study/artikel/{id_artikel}', [GalleryController::class, 'showArtikel'])->name('article.show');



//middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/feedback', function () {
        return view('feedback');
    })->name('feedback');

    // FEEDBACK SUBMIT (WAJIB LOGIN)
    Route::post('/feedback', [HomeController::class, 'storeFeedback'])->name('feedback.store');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/instant-process', [CheckoutController::class, 'instantProcess']);
    Route::post('/checkout/sync', [CheckoutController::class, 'sync']); // tombol checkout
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');


    // sementara
    Route::post('/checkout/sync', [CheckoutController::class, 'sync'])
    ->middleware('auth')
    ->name('checkout.sync');

// Route checkout lainnya
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->middleware('auth')
    ->name('checkout.index');

Route::post('/checkout/process', [CheckoutController::class, 'process'])
    ->middleware('auth')
    ->name('checkout.process');

    // PAYMENT
    Route::get('/payment-form', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/payment-form', [PaymentController::class, 'processPayment'])->name('payment.process');

    // ORDER STATUS
    Route::get('/status-pesanan/{order_id}', [OrderController::class, 'showStatus'])->name('orders.status');
});

    // STUDY, ARTICLE, QNA, GALERI
    Route::get('/belajar', [StudyController::class, 'index'])->name('study.index');
    Route::get('/study/artikel/{id_artikel}', [ArticleController::class, 'show'])->name('article.show');
    Route::get('/study/artikel', [ArticleController::class, 'index'])->name('article.index');
    // FAQ 
    Route::get('/qna', [QnaController::class, 'index'])->name('qna');
    // GALERI
    Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.gallery');



// middleware auth Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/pesanan/status/{id}', [DashboardController::class, 'updateStatus'])
    ->name('pesanan.updateStatus');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    // MANAGE PRODUK
    Route::get('/manageProduk', [ManageProductsController::class, 'index']);
    Route::get('/manageProduk-input', [ManageProductsController::class, 'input']);
    Route::post('/manageProduk-simpan', [ManageProductsController::class, 'simpan']);
    Route::get('/manageProduk-edit/{id_produk}', [ManageProductsController::class, 'edit']);
    Route::post('/manageProduk-update/{id_produk}', [ManageProductsController::class, 'update']);
    Route::get('/manageProduk-hapus/{id_produk}', [ManageProductsController::class, 'delete']);
    // MANAGE PENGGUNA
    Route::get('/manageUser', [ManageUserController::class, 'index']);
    Route::get('/manageUser-input', [ManageUserController::class, 'input']);
    Route::post('/manageUser-simpan', [ManageUserController::class, 'simpan']);
    Route::get('/manageUser-edit/{id_pengguna}', [ManageUserController::class, 'edit']);
    Route::post('/manageUser-update/{id_pengguna}', [ManageUserController::class, 'update']);
    Route::get('/manageUser-hapus/{id_pengguna}', [ManageUserController::class, 'delete']);

    // MANAGE REVIEW
    Route::get('/manageReview', [ManageReviewController::class, 'index']);
    Route::post('/manageReview-approve/{id}', [ManageReviewController::class, 'approve']);
    Route::post('/manageReview-reject/{id}', [ManageReviewController::class, 'reject']);

    // MANAGE FAQ
    Route::get('/manageFaq', [ManageFaqController::class, 'index']);
    Route::get('/manageFaq-input', [ManageFaqController::class, 'input']);
    Route::post('/manageFaq-simpan', [ManageFaqController::class, 'simpan']);
    Route::get('/manageFaq-edit/{id_faq}', [ManageFaqController::class, 'edit']);
    Route::post('/manageFaq-update/{id_faq}', [ManageFaqController::class, 'update']);
    Route::get('/manageFaq-hapus/{id_faq}', [ManageFaqController::class, 'delete']);

    // MANAGE STATUS PESANAN
    Route::get('/manageStatus', [ManageStatusPesananController::class, 'index']);
    Route::get('/manageStatus-input', [ManageStatusPesananController::class, 'input']);
    Route::post('/manageStatus-simpan', [ManageStatusPesananController::class, 'simpan']);
    Route::get('/manageStatus-edit/{id_status_pesanan}', [ManageStatusPesananController::class, 'edit']);
    Route::post('/manageStatus-update/{id_status_pesanan}', [ManageStatusPesananController::class, 'update']);
    Route::get('/manageStatus-hapus/{id_status_pesanan}', [ManageStatusPesananController::class, 'delete']);

    // MANAGE PUBLICATION
    Route::get('/publication', [ManagePublicationController::class, 'tampil'])->name('publication.index');
    Route::get('/publication/input', [ManagePublicationController::class, 'input'])->name('publication.create');
    Route::post('/publication/simpan', [ManagePublicationController::class, 'simpan'])->name('publication.store');
    Route::get('/publication/edit/{id}', [ManagePublicationController::class, 'edit'])->name('publication.edit');
    Route::put('/publication/update/{id}', [ManagePublicationController::class, 'update'])->name('publication.update');
    Route::delete('/publication/hapus/{id}', [ManagePublicationController::class, 'hapus'])->name('publication.destroy');

    // MANAGE SETTING
    Route::get('/managesetting', [ManageSettingController::class, 'index'])->name('settings.index');
    Route::post('/managesetting', [ManageSettingController::class, 'update'])->name('settings.update');

    // MANAGE GALLERI
    Route::get('/gallery', [ManageGalleryController::class, 'index'])->name('gallery.index');
    Route::get('/gallery/create', [ManageGalleryController::class, 'create'])->name('gallery.create');
    Route::post('/gallery', [ManageGalleryController::class, 'store'])->name('gallery.store');
    Route::get('/gallery/{id_galeri}/edit', [ManageGalleryController::class, 'edit'])->name('gallery.edit');
    Route::put('/gallery/{id_galeri}', [ManageGalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{id_galeri}', [ManageGalleryController::class, 'destroy'])->name('gallery.destroy');
});

