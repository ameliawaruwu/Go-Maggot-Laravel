<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukApiController;
use App\Http\Controllers\Api\PenggunaApiController;
use App\Http\Controllers\Api\PembayaranApiController;
use App\Http\Controllers\Api\DetailPesananApiController;

Route::apiResource('produk', ProdukApiController::class);
Route::apiResource('pengguna', PenggunaApiController::class);
Route::apiResource('pembayaran', PembayaranApiController::class);
Route::apiResource('detail-pesanan', DetailPesananApiController::class);

// Ini akan membuat rute untuk produk dan pengguna:
// Produk:  GET/POST/GET(id)/PUT/DELETE -> /api/produk
// Pengguna: GET/POST/GET(id)/PUT/DELETE -> /api/pengguna