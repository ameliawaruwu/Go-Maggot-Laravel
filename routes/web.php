<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
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




Route::get('/home', [HomeController::class, 'index'])->name('home');

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
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Proses pengiriman form checkout (POST)
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// Halaman sukses setelah checkout
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Tambahkan rute untuk simulasi halaman keranjang agar 'Kembali ke keranjang' berfungsi
Route::get('/keranjang', function() {
    return "Halaman Keranjang (Simulasi)"; 
})->name('keranjang.index');
