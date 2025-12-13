<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    function index(){
        $produk = Produk::where('stok', '>', 0)->get();
        return view('product.index', compact('produk'));
    }

    function show($id_produk){
        $produk = Produk::find($id_produk);

        if (!$produk) {
            return view('product.detail', [
                'data' => null
            ]);
        }

        return view('product.detail', [
            'data' => $produk
        ]);
    }
}
