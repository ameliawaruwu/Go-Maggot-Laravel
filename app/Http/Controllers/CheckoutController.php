<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout dengan data keranjang dari session.
     */
    public function index()
    {
        // Simulasi data keranjang. Dalam aplikasi nyata, ini bisa diisi dari session
        // setelah user menambahkan produk dari halaman keranjang.

        // Jika session 'cart' belum ada, kita inisialisasi dengan data simulasi.
        // Dalam kasus nyata, data ini harusnya sudah ada dari proses "Add to Cart".
        if (!Session::has('cart')) {
            Session::put('cart', [
                [
                    'id' => 1,
                    'namaproduk' => 'Paket Bundling Kandang dan Maggot',
                    'jumlah' => 1,
                    'harga' => 170000,
                    'gambar' => 'path/to/product/image.jpg', // Ganti dengan path gambar Anda
                ],
                // Anda bisa menambahkan item lain di sini jika perlu
            ]);
        }

        $cartItems = Session::get('cart', []);
        
        $totalPrice = array_sum(array_map(function($item) {
            return $item['jumlah'] * $item['harga'];
        }, $cartItems));

        $totalQuantity = array_sum(array_column($cartItems, 'jumlah'));

        // Kirim data ke view
        return view('checkout.index', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'totalQuantity' => $totalQuantity
        ]);
    }

    /**
     * Memproses data form checkout.
     */
    public function process(Request $request)
    {
        // 1. Validasi Data Input
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string',
            'metode_pembayaran' => 'required|string',
            // Kita tidak perlu memvalidasi cartItems karena sudah ada di session/tampilan
        ]);

        // 2. Ambil Data Keranjang dari Session
        $cartItems = Session::get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong. Tidak dapat melanjutkan checkout.');
        }

        // 3. Simulasikan Proses Pemesanan
        // Karena tidak menggunakan database, kita hanya akan menyimpan data pesanan di session 
        // dan kemudian menghapus keranjang.

        $orderData = [
            'penerima' => $request->only(['nama_penerima', 'nomor_telepon', 'alamat_lengkap', 'kota', 'metode_pembayaran']),
            'items' => $cartItems,
            'total_harga' => array_sum(array_map(function($item) {
                return $item['jumlah'] * $item['harga'];
            }, $cartItems)),
            'timestamp' => now()->toDateTimeString(),
            // ID pesanan simulasi
            'order_id_simulasi' => 'ORD-' . time(), 
        ];

        // Simpan pesanan simulasi ke session (opsional, hanya untuk menunjukkan data)
        Session::put('last_order', $orderData);

        // 4. Kosongkan Keranjang
        Session::forget('cart');
        
        // 5. Redirect ke halaman sukses
        return redirect()->route('checkout.success')->with('success', 'Checkout berhasil! Pesanan Anda sedang diproses.');
    }

    /**
     * Menampilkan halaman sukses checkout.
     */
    public function success()
    {
        // Ambil data pesanan terakhir jika ada
        $lastOrder = Session::get('last_order', null);
        
        return view('checkout.success', ['lastOrder' => $lastOrder]);
    }
}