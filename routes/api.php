<?php
use App\Http\Controllers\Api\ProdukApiController;

Route::apiResource('produk', ProdukApiController::class);
// Ini akan membuat rute:
// GET    /api/produk         -> index
// POST   /api/produk         -> store
// GET    /api/produk/{produk} -> show
// PUT    /api/produk/{produk} -> update (Anda mungkin perlu menggunakan POST dengan _method:PUT di form-data)
// DELETE /api/produk/{produk} -> destroy