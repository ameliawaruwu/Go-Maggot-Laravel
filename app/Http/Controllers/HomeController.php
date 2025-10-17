<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Logika data dapat ditambahkan di sini
        $products = [
            ['image' => 'images/coba1.jpg', 'title' => 'Maggot Siap Pakai', 'price' => 'Rp. 70.000/150gr'],
            ['image' => 'images/produk2.jpg', 'title' => 'Paket Bundling', 'price' => 'Rp. 170.000'],
            ['image' => 'images/coba2.jpg', 'title' => 'Pupuk', 'price' => 'Rp. 25.000/500gr'],
            ['image' => 'images/coba.jpg', 'title' => 'Bibit Maggot', 'price' => 'Rp. 50.000/200gr'],
        ];

        return view('home', compact('products'));
    }
}
