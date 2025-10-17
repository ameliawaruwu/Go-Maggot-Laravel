<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Ganti include mysqli/configdb dengan facade DB Laravel
use Illuminate\Support\Facades\DB; 

class ProductController extends Controller
{
    public function index()
    {
        // 1. Ambil data produk (menggunakan Query Builder Laravel sebagai pengganti mysqli)
        $dataProduk = DB::table('produk')->orderBy('idproduk', 'asc')->get();

        // 2. Ambil kategori unik
        $categories = DB::table('produk')
                        ->select('kategori')
                        ->distinct()
                        ->whereNotNull('kategori')
                        ->get();
        
        $fixedCategories = ['BSF', 'Kompos', 'Pupuk', 'Lainnya'];

        // 3. Kirim data ke view
        return view('products.index', [
            'dataProduk' => $dataProduk,
            'categories' => $categories,
            'fixedCategories' => $fixedCategories,
        ]);
    }
}