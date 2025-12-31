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


// Produk bisa diakses public (tanpa login)
Route::get('/produk', [ProdukApiController::class, 'index']);
Route::get('/produk/{id}', [ProdukApiController::class, 'show']);
Route::get('/faq', [FaqApiController::class, 'index']);
Route::get('/galeri', [GaleriApiController::class, 'index']);
Route::get('/artikel', [ArtikelApiController::class, 'index']);
Route::get('/artikel/{id}', [ArtikelApiController::class, 'show']);
Route::get('/reviews', [ReviewsApiController::class, 'index']);

// setelah login 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Admin routes 
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
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
    Route::apiResource('reviews', ReviewsApiController::class);
});

// Routes  role pelanggan setelah login
Route::middleware(['auth:sanctum', 'role:pelanggan'])->group(function () {
    Route::get('/dashboard/pelanggan', [AccessController::class, 'pelanggan']);
    Route::post('/checkout/process', [PesananApiController::class, 'submitCheckout']);
    Route::post('/pembayaran/upload', [PesananApiController::class, 'uploadBukti']);
    // Pelanggan bisa lihat dan buat pesanan mereka sendiri
    Route::get('/pesanan-saya', [PesananApiController::class, 'riwayatPesanan']); 
    Route::get('/pesanan-saya/{id}', [PesananApiController::class, 'show']); 

    // Pelanggan bisa lihat pembayaran mereka sendiri
    Route::get('/pembayaran-saya', [PembayaranApiController::class, 'index']);
    // Detail  Pesanan berdasarkan ID
    Route::get('/pesanan/{id_pesanan}/detail', [DetailPesananApiController::class, 'detailByPesanan']);

    // Simpan review baru
    Route::post('/reviews', [ReviewsApiController::class, 'store']);
   

});