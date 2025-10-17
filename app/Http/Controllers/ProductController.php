<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Simulasikan data yang seharusnya diambil dari database
    private $simulatedProducts = [
        // Struktur data harus konsisten dengan yang diharapkan View
        // Menggunakan array asosiatif untuk memudahkan akses di Controller/View
        [
            'idproduk' => 1,
            'namaproduk' => 'Maggot Siap Pakai',
            'harga' => 70000,
            'gambar' => 'maggot removebg.png', // Sesuaikan dengan nama file gambar di public/
            'stok' => 15
        ],
        [
            'idproduk' => 2,
            'namaproduk' => 'Kompos Maggot',
            'harga' => 25000,
            'gambar' => 'kompos remove bg.png',
            'stok' => 0 // Stok 0 untuk simulasi produk yang habis
        ],
        [
            'idproduk' => 3,
            'namaproduk' => 'Paket Bundling Kandang dan Maggot',
            'harga' => 170000,
            'gambar' => 'Bundling Maggot.png',
            'stok' => 5
        ],
        [
            'idproduk' => 4,
            'namaproduk' => 'Bibit Maggot',
            'harga' => 50000,
            'gambar' => 'Bibit-remove bg.png',
            'stok' => 20
        ],
        [
            'idproduk' => 5,
            'namaproduk' => 'Kandang Maggot',
            'harga' => 75000,
            'gambar' => 'Kandang.png',
            'stok' => 10
        ],
        // ... Tambahkan semua produk lainnya di sini jika diperlukan
    ];

    public function index()
    {
        // LOGIC: Filter produk yang stoknya lebih dari 0 (menggantikan klausa WHERE stok > 0)
        $products = collect($this->simulatedProducts)->filter(function ($product) {
            return $product['stok'] > 0;
        })->values()->all(); // Konversi kembali menjadi array

        // VIEW: Mengirim data produk yang sudah difilter ke view product.index
        return view('product.index', compact('products'));
    }
    
    // Logic untuk halaman detail produk
    public function show($id)
    {
        // LOGIC: Mencari produk berdasarkan ID
        $product = collect($this->simulatedProducts)->firstWhere('idproduk', (int)$id);
        
        // Cek jika produk tidak ditemukan
        if (!$product) {
            abort(404); // Tampilkan halaman 404 jika produk tidak ada
        }
        
        // VIEW: Mengirim data produk ke view detail
        return view('product.detail', compact('product'));
    }
}