<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File; 

class ManageProductsController extends Controller
{
    function index(){
        $produk = Produk::all();
        return view('manage-products.index', compact('produk'));
    }

    
    
}