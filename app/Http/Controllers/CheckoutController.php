<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ProductController; 

class CheckoutController extends Controller
{
    /**
     * Fungsi untuk mendapatkan item keranjang uji coba
     *
     * @return array
     */
    private function getTestCartItems()
    {
        // Mengambil data produk lengkap dari ProductController
        $allProducts = collect(ProductController::getAllProducts());

        //Mendefinisikan ID dan Jumlah yang akan dibeli
        $itemsToBuy = [
            1 => 2, // Maggot Siap Pakai (ID 1) sebanyak 2 unit
            3 => 1, // Paket Bundling (ID 3) sebanyak 1 unit
        ];
        
        $cartItems = [];

        //Menggabungkan detail produk (termasuk 'gambar') dengan jumlah pembelian
        foreach ($itemsToBuy as $idproduk => $jumlah) {
            $product = $allProducts->firstWhere('idproduk', $idproduk);

            if ($product) {
                $cartItems[] = [
                    'idproduk' => $product['idproduk'],
                    'namaproduk' => $product['namaproduk'],
                    'harga' => $product['harga'],
                    'gambar' => $product['gambar'], 
                    'jumlah' => $jumlah
                ];
            }
        }
        
        return $cartItems;
    }

    public function index()
    {
         session()->forget('cart');
        $cartItems = session('cart', $this->getTestCartItems());

       
        if (empty(session('cart')) && !empty($cartItems)) {
             session(['cart' => $cartItems]);
        }
        
        // Menghitung total harga dan total jumlah
        $totalPrice = 0;
        $totalQuantity = 0;

        foreach ($cartItems as $item) {
            $totalPrice += $item['harga'] * $item['jumlah'];
            $totalQuantity += $item['jumlah'];
        }

        // Kirim semua data ke view checkout
        return view('checkout.index', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'totalQuantity' => $totalQuantity
        ]);
    }

    public function process(Request $request)
    {
        // Total Harga dari keranjang 
        $totalPrice = 310000; 

        // Melakukan pembuatan ID Pesanan yang konsisten
        $orderId = 'ORD-MAGGOT-01'; 

        // Redirect ke Form Pembayaran (payment.form)
        return redirect()->route('payment.form', [
            'order_id' => $orderId,
            'total' => $totalPrice
        ]);
    }

    public function success()
    {
         // Contoh data pesanan terakhir (bisa diganti dengan data nyata dari database nanti)
         $lastOrder = [
        'id' => 'ORD-MAGGOT-01',
        'total' => 310000,
        'tanggal' => now()->format('d-m-Y H:i:s'),
        'status' => 'Menunggu Pembayaran',
        ];

        // Kirim variabel $lastOrder ke view
        return view('checkout.success', compact('lastOrder'));
        }
}
