<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;



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

Route::get('/', function () {
    return view('welcome');
});

// Dashboard 
Route::get('/dashboard', [DashboardController::class, 'dashboard']);
// Halaman utama (daftar produk)
Route::get('/', [ProductController::class, 'index'])->name('product.index');

// Halaman detail produk (walaupun belum dibuat, route-nya disiapkan)
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
