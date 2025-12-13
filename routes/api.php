<?php
use App\Http\Controllers\Api\ProdukApiController;
use App\Http\Controllers\Api\StatusPesananApiController;
use App\Http\Controllers\Api\PesananApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\GaleriApiController;
use App\Http\Controllers\Api\ArtikelApiController;
use App\Http\Controllers\Api\ReviewsApiController;

Route::apiResource('produk', ProdukApiController::class);
// Ini akan membuat rute:
// GET    /api/produk         -> index
// POST   /api/produk         -> store
// GET    /api/produk/{produk} -> show
// PUT    /api/produk/{produk} -> update (Anda mungkin perlu menggunakan POST dengan _method:PUT di form-data)
// DELETE /api/produk/{produk} -> destroy

Route::apiResource('status-pesanan', StatusPesananApiController::class);
// Ini akan membuat rute:
// GET    /api/status-pesanan         -> index
// POST   /api/status-pesanan         -> store
// GET    /api/status-pesanan/{status_pesanan} -> show
// PUT    /api/status-pesanan/{status_pesanan} -> update
// DELETE /api/status-pesanan/{status_pesanan} -> destroy

Route::apiResource('pesanan', PesananApiController::class);
// Ini akan membuat rute:
// GET    /api/pesanan         -> index
// POST   /api/pesanan         -> store
// GET    /api/pesanan/{pesanan} -> show
// PUT    /api/pesanan/{pesanan} -> update
// DELETE /api/pesanan/{pesanan} -> destroy

Route::apiResource('faq', FaqApiController::class);
// Ini akan membuat rute:
// GET    /api/faq         -> index
// POST   /api/faq         -> store
// GET    /api/faq/{faq} -> show
// PUT    /api/faq/{faq} -> update
// DELETE /api/faq/{faq} -> destroy

Route::apiResource('galeri', GaleriApiController::class);
// Ini akan membuat rute:
// GET    /api/galeri         -> index
// POST   /api/galeri         -> store
// GET    /api/galeri/{galeri} -> show
// PUT    /api/galeri/{galeri} -> update
// DELETE /api/galeri/{galeri} -> destroy

Route::apiResource('artikel', ArtikelApiController::class);
// Ini akan membuat rute:
// GET    /api/artikel         -> index
// POST   /api/artikel         -> store
// GET    /api/artikel/{artikel} -> show
// PUT    /api/artikel/{artikel} -> update
// DELETE /api/artikel/{artikel} -> destroy

Route::apiResource('reviews', ReviewsApiController::class);
// Ini akan membuat rute:
// GET    /api/reviews         -> index
// POST   /api/reviews         -> store
// GET    /api/reviews/{reviews} -> show
// PUT    /api/reviews/{reviews} -> update
// DELETE /api/reviews/{reviews} -> destroy