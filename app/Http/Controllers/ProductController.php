<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    /**
     * Data Dummy Produk Lengkap 
     * 
     */
    private static $simulatedProducts = [
        [
            'idproduk' => 1,
            'namaproduk' => 'Maggot Siap Pakai',
            'harga' => 70000,
            'gambar' => 'maggot removebg.png',
            'stok' => 15,
            'deskripsi_produk' => 'Maggot BSF segar siap panen, cocok untuk pakan ternak unggas, ikan, dan reptil. Protein tinggi, organik, dan ramah lingkungan.',
            'kategori' => 'Pakan Ternak',
            'Merk' => 'GoMaggot Fresh',
            'masapenyimpanan' => 0.5, 
            'berat' => 500,
            'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 2,
            'namaproduk' => 'Kompos Maggot Premium',
            'harga' => 25000,
            'gambar' => 'kompos remove bg.png',
            'stok' => 0, 
            'deskripsi_produk' => 'Pupuk organik berkualitas tinggi dari sisa media budidaya maggot. Kaya nutrisi, memperbaiki struktur tanah, dan meningkatkan hasil panen.',
            'kategori' => 'Pupuk & Media Tanam',
            'Merk' => 'GoMaggot Soil',
            'masapenyimpanan' => 12, 
            'berat' => 1000, 
            'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 3,
            'namaproduk' => 'Paket Bundling Kandang dan Maggot',
            'harga' => 170000,
            'gambar' => 'Bundling Maggot.png',
            'stok' => 5,
            'deskripsi_produk' => 'Paket lengkap untuk pemula. Termasuk 1 set kandang mini BSF dan starter maggot siap tebar. Hemat dan praktis!',
            'kategori' => 'Paket Budidaya',
            'Merk' => 'GoMaggot Kit',
            'masapenyimpanan' => 0, 
            'berat' => 3500,
            'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 4,
            'namaproduk' => 'Bibit Maggot (Telur BSF)',
            'harga' => 50000,
            'gambar' => 'Bibit-remove bg.png',
            'stok' => 20,
            'deskripsi_produk' => 'Telur BSF pilihan dengan daya tetas tinggi (F1). Mulai budidaya Anda dengan bibit berkualitas dari sumber terpercaya.',
            'kategori' => 'Bibit & Telur',
            'Merk' => 'GoMaggot Seed',
            'masapenyimpanan' => 0.05, 
            'berat' => 10, 
            'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 5,
            'namaproduk' => 'Kandang Maggot Lipat',
            'harga' => 75000,
            'gambar' => 'Kandang.png',
            'stok' => 10,
            'deskripsi_produk' => 'Kandang budidaya BSF berukuran minimalis, dapat dilipat, dan mudah dipindah-pindah. Cocok untuk skala rumah tangga.',
            'kategori' => 'Peralatan Budidaya',
            'Merk' => 'GoMaggot Home',
            'masapenyimpanan' => 0,
            'berat' => 2000,
            'pengiriman' => 'Bandung'
        ],
       
        [
            'idproduk' => 6, 'namaproduk' => 'Bibit Maggot', 'harga' => 50000, 'gambar' => 'Bibit-remove bg.png', 'stok' => 20, 'deskripsi_produk' => 'Telur BSF F1 kualitas unggul.', 'kategori' => 'Bibit & Telur', 'Merk' => 'GoMaggot Seed', 'masapenyimpanan' => 0.05, 'berat' => 10, 'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 7, 'namaproduk' => 'Kandang Maggot XXL', 'harga' => 120000, 'gambar' => 'Kandang.png', 'stok' => 10, 'deskripsi_produk' => 'Kandang besar untuk budidaya komersil.', 'kategori' => 'Peralatan Budidaya', 'Merk' => 'GoMaggot Pro', 'masapenyimpanan' => 0, 'berat' => 5000, 'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 8, 'namaproduk' => 'Paket Bundling Pro', 'harga' => 250000, 'gambar' => 'Bundling Maggot.png', 'stok' => 5, 'deskripsi_produk' => 'Paket Pro untuk hasil panen maksimal.', 'kategori' => 'Paket Budidaya', 'Merk' => 'GoMaggot Kit', 'masapenyimpanan' => 0, 'berat' => 4000, 'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 9, 'namaproduk' => 'Kompos Maggot 5KG', 'harga' => 50000, 'gambar' => 'kompos remove bg.png', 'stok' => 30, 'deskripsi_produk' => 'Pupuk organik dalam kemasan besar 5 KG.', 'kategori' => 'Pupuk & Media Tanam', 'Merk' => 'GoMaggot Soil', 'masapenyimpanan' => 12, 'berat' => 5000, 'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 10, 'namaproduk' => 'Maggot Kering (Protein)', 'harga' => 85000, 'gambar' => 'maggot removebg.png', 'stok' => 25, 'deskripsi_produk' => 'Maggot yang sudah dikeringkan, awet untuk pakan jangka panjang.', 'kategori' => 'Pakan Ternak', 'Merk' => 'GoMaggot Dry', 'masapenyimpanan' => 6, 'berat' => 250, 'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 11, 'namaproduk' => 'Maggot Segar 1KG', 'harga' => 120000, 'gambar' => 'maggot removebg.png', 'stok' => 15, 'deskripsi_produk' => 'Maggot segar kemasan 1KG.', 'kategori' => 'Pakan Ternak', 'Merk' => 'GoMaggot Fresh', 'masapenyimpanan' => 0.5, 'berat' => 1000, 'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 12, 'namaproduk' => 'Kompos Starter', 'harga' => 25000, 'gambar' => 'kompos remove bg.png', 'stok' => 15, 'deskripsi_produk' => 'Kompos Maggot kemasan kecil untuk starter tanaman.', 'kategori' => 'Pupuk & Media Tanam', 'Merk' => 'GoMaggot Soil', 'masapenyimpanan' => 12, 'berat' => 1000, 'pengiriman' => 'Bandung'
        ],
        [
            'idproduk' => 13, 'namaproduk' => 'Kandang Modular', 'harga' => 170000, 'gambar' => 'Bundling Maggot.png', 'stok' => 8, 'deskripsi_produk' => 'Kandang yang bisa disambung (modular).', 'kategori' => 'Peralatan Budidaya', 'Merk' => 'GoMaggot Home', 'masapenyimpanan' => 0, 'berat' => 3000, 'pengiriman' => 'Bandung'
        ]
    ];

    public function index()
    {
        $products = collect(self::$simulatedProducts)->filter(function ($product) {
            return $product['stok'] > 0;
        })->values()->all();
        return view('product.index', compact('products'));
    }

    public function show($id)
    {
        //Cari produk berdasarkan ID
        $product = collect(self::$simulatedProducts)->firstWhere('idproduk', (int)$id);

        if (!$product) {
            //Jika produk tidak ditemukan, kirim notifikasi ke view
            return view('products.detail', ['productFound' => false]);
        }
        
        //Memproses dan memformat data
        $data = [
            'productFound' => true,
            'productName' => htmlspecialchars($product['namaproduk']),
            'productPrice' => $product['harga'],
            'productDescription' => htmlspecialchars($product['deskripsi_produk']),
            'productCategory' => htmlspecialchars($product['kategori']),
            'productStock' => (int)$product['stok'],
            'productBrand' => htmlspecialchars($product['Merk']),
            
            // Logika format masa penyimpanan (dari bulan ke hari jika kurang dari 1 bulan)
            'productSave' => ($product['masapenyimpanan'] >= 1) 
                ? $product['masapenyimpanan'] . ' Bulan' 
                : ($product['masapenyimpanan'] * 30) . ' Hari', 
            
            // Logika konversi berat (dari gram ke Kg jika > 1000g)
            'productWeight' => $product['berat'] > 1000 
                ? number_format($product['berat'] / 1000, 1) . ' Kg' 
                : $product['berat'] . ' g', 

            'productImage' => htmlspecialchars($product['gambar']),
            'productPengiriman' => htmlspecialchars($product['pengiriman']),
        ];

        //Kirim data yang sudah diproses ke View
        return view('product.detail', $data);
    }
    
    /**
     * Helper function untuk mendapatkan semua produk
     * @return array
     */
    public static function getAllProducts()
    {
        return self::$simulatedProducts;
    }
}
