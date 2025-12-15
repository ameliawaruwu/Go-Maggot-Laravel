<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukApiController;
use App\Http\Controllers\Api\PenggunaApiController;
use App\Http\Controllers\Api\PembayaranApiController;
use App\Http\Controllers\Api\DetailPesananApiController;
use App\Http\Controllers\Api\StatusPesananApiController;
use App\Http\Controllers\Api\PesananApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\GaleriApiController;
use App\Http\Controllers\Api\ArtikelApiController;
use App\Http\Controllers\Api\ReviewsApiController;
use App\Http\Controllers\Api\AccessController;
use App\Http\Controllers\Api\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Produk bisa diakses public untuk katalog (hanya GET)
Route::get('/produk', [ProdukApiController::class, 'index']);
Route::get('/produk/{id}', [ProdukApiController::class, 'show']);
Route::get('/faq', [FaqApiController::class, 'index']);
Route::get('/galeri', [GaleriApiController::class, 'index']);
Route::get('/artikel', [ArtikelApiController::class, 'index']);
Route::get('/artikel/{id}', [ArtikelApiController::class, 'show']);
Route::get('/reviews', [ReviewsApiController::class, 'index']);

// Untuk melalukan login register 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// Admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AccessController::class, 'admin']);
    Route::apiResource('produk', ProdukApiController::class);
    Route::apiResource('artikel', ArtikelApiController::class);
    Route::apiResource('faq', FaqApiController::class);
    Route::apiResource('galeri', GaleriApiController::class);
    Route::apiResource('pengguna', PenggunaApiController::class);
    Route::apiResource('pembayaran', PembayaranApiController::class);
    Route::apiResource('pesanan', PesananApiController::class);
    Route::apiResource('detail-pesanan', DetailPesananApiController::class);
    Route::apiResource('status-pesanan', StatusPesananApiController::class);
    Route::apiResource('reviews', ReviewsApiController::class);//->except(['index', 'show']);
});

// Routes  role pelanggan
Route::middleware(['auth:sanctum', 'role:pelanggan'])->group(function () {
    Route::get('/dashboard/pelanggan', [AccessController::class, 'pelanggan']);
    // Pelanggan bisa lihat dan buat pesanan mereka sendiri
    Route::get('/pesanan-saya', [PesananApiController::class, 'riwayatPesanan']); 
    Route::post('/pesanan', [PesananApiController::class, 'store']);
    // Pelanggan bisa lihat pembayaran mereka sendiri
    Route::get('/pembayaran-saya', [PembayaranApiController::class, 'index']); 
    Route::post('/pembayaran', [PembayaranApiController::class, 'store']);
    // Pelanggan bisa buat review
    Route::post('/reviews-simpan', [ReviewsApiController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewsApiController::class, 'update']); 
});