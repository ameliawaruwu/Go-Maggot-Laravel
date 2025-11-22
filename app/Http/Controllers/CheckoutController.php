<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class CheckoutController extends Controller
{
    // ===========================
    // TAMPILKAN HALAMAN CHECKOUT
    // ===========================
    function index()
    {
        // ambil keranjang dari session
        $cart = session('cart', []);

        // jika kosong
        if (empty($cart)) {
            return redirect('/daftar-produk')->with('error', 'Keranjang masih kosong.');
        }

        // Hitung total
        $totalPrice = 0;
        $totalQty = 0;

        foreach ($cart as $item) {
            $totalPrice += $item['harga'] * $item['jumlah'];
            $totalQty += $item['jumlah'];
        }

        return view('checkout.index', [
            'cartItems' => $cart,
            'totalPrice' => $totalPrice,
            'totalQuantity' => $totalQty
        ]);
    }

    // ===========================
    // SIMPAN PESANAN KE DATABASE
    // ===========================
    function process(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_penerima'     => 'required',
            'nomor_telepon'     => 'required',
            'alamat_lengkap'    => 'required',
            'kota'              => 'required',
            'metode_pembayaran' => 'required'
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        // Hitung total harga
        $totalHarga = 0;
        foreach ($cart as $item){
            $totalHarga += $item['harga'] * $item['jumlah'];
        }

        // Generate ID pesanan
        $idPesanan = 'ORD-' . time();

        // Simpan ke database
        Pesanan::create([
            'id_pesanan'       => $idPesanan,
            'id_pengguna'      => auth()->user()->id_pengguna ?? 'GUEST',
            'nama_penerima'    => $request->nama_penerima,
            'alamat_pengiriman'=> $request->alamat_lengkap . ', ' . $request->kota,
            'nomor_telepon'    => $request->nomor_telepon,
            'metode_pembayaran'=> strtoupper($request->metode_pembayaran),
            'total_harga'      => $totalHarga,
        ]);

        // hapus session cart
        session()->forget('cart');

        return redirect()->route('checkout.success')
            ->with('success', 'Pesanan berhasil dibuat!')
            ->with('order_id', $idPesanan);
    }

    // ===========================
    // HALAMAN SUCCESS
    // ===========================
    function success()
    {
        return view('checkout.success', [
            'order_id' => session('order_id'),
            'message' => session('success')
        ]);
    }
}
