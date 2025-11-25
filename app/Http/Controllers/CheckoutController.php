<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class CheckoutController extends Controller
{
    // Halaman checkout 
    function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect('/daftar-produk')->with('error', 'Keranjang masih kosong.');
        }

        $totalPrice = 0;
        $totalQty = 0;

        foreach ($cart as $item) {
            $harga = $item['harga'] ?? 0;
            $jumlah = $item['jumlah'] ?? 0;

            $totalPrice += $harga * $jumlah;
            $totalQty += $jumlah;
        }

        return view('checkout.index', [
            'cartItems' => $cart,
            'totalPrice' => $totalPrice,
            'totalQuantity' => $totalQty
        ]);
    }

    // Proses pesanan instan (AJAX)
    public function instantProcess(Request $request)
    {
        $cart = $request->json('cart', []);
        
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);
        }

        // --- ID PENGGUNA
        $idPengguna = Auth::check()
            ? (Auth::user()->id_pengguna ?? Auth::user()->id)
            : 'PG138';

        $idPengguna = trim($idPengguna);
        $idPesanan = 'ORD-' . time();

        // Data default
        $namaPenerimaDefault = Auth::check() ? Auth::user()->name : 'Pelanggan Langsung';
        $alamatPengirimanDefault = 'Alamat Default - Harap Konfirmasi Admin';
        $nomorTeleponDefault = '081987654321';
        $metodePembayaranDefault = 'QRIS';
        $layananPengirimanDefault = 'Reguler';
        $biayaPengirimanDefault = 0;

        // Hitung total harga
        $totalHarga = 0;
        foreach ($cart as $item){
            $totalHarga += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }

        DB::beginTransaction();
        try {

            Pesanan::create([
                'id_pesanan'        => $idPesanan,
                'id_pengguna'       => $idPengguna,
                'nama_penerima'     => $namaPenerimaDefault,
                'alamat_pengiriman' => $alamatPengirimanDefault,
                'nomor_telepon'     => $nomorTeleponDefault,
                'tanggal_pesanan'   => now(),
                'metode_pembayaran' => $metodePembayaranDefault,
                'layanan_pengiriman'=> $layananPengirimanDefault,
                'biaya_pengiriman'  => $biayaPengirimanDefault,
                'total_harga'       => $totalHarga,
            ]);

            foreach ($cart as $item) {
                $idProduk = $item['idproduk'] ?? null;
                $jumlah = $item['jumlah'] ?? 0;
                $harga = $item['harga'] ?? 0;

                if (is_null($idProduk)) {
                    throw new \Exception("Item keranjang tidak memiliki ID produk yang valid.");
                }

                DetailPesanan::create([
                    'id_pesanan'              => $idPesanan,
                    'id_produk'               => $idProduk,
                    'jumlah'                  => $jumlah,
                    'harga_saat_pembelian'    => $harga,
                ]);
            }

            DB::commit();

            // Simpan ke session agar tampil di halaman view
            session(['cart' => $cart]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan instan (draft) berhasil dibuat!',
                'redirect_url' => route('checkout.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pesanan. Error: ' . $e->getMessage()
            ], 200);
        }
    }

    // ===========================
    // PROSES DARI FORM REGULER
    // ===========================
    function process(Request $request)
    {
        $request->validate([
            'nama_penerima'     => 'required|string|max:255',
            'nomor_telepon'     => 'required|string|max:15',
            'alamat_lengkap'    => 'required|string',
            'pengiriman'        => 'required|string|max:50',
            'metode_pembayaran' => 'required|string|max:50'
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        $idPengguna = Auth::check()
            ? (Auth::user()->id_pengguna ?? Auth::user()->id)
            : 'PG138';

        $idPengguna = trim($idPengguna);
        $idPesanan = 'ORD-' . time();

        $totalHargaProduk = 0;
        foreach ($cart as $item){
            $totalHargaProduk += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }

        $totalHargaFinal = $totalHargaProduk;

        DB::beginTransaction();
        try {

            Pesanan::create([
                'id_pesanan'        => $idPesanan,
                'id_pengguna'       => $idPengguna,
                'nama_penerima'     => $request->nama_penerima,
                'alamat_pengiriman' => $request->alamat_lengkap,
                'nomor_telepon'     => $request->nomor_telepon,
                'tanggal_pesanan'   => now(),
                'metode_pembayaran' => strtoupper($request->metode_pembayaran),
                'total_harga'       => $totalHargaFinal,
            ]);

            foreach ($cart as $item) {
                DetailPesanan::create([
                    'id_pesanan'              => $idPesanan,
                    'id_produk'               => $item['idproduk'] ?? null,
                    'jumlah'                  => $item['jumlah'] ?? 0,
                    'harga_saat_pembelian'    => $item['harga'] ?? 0,
                ]);
            }

            DB::commit();

            session()->forget('cart');

            return redirect()->route('checkout.success')
                ->with('success', 'Pesanan berhasil dibuat!')
                ->with('order_id', $idPesanan);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memproses pesanan form: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal menyimpan pesanan. (Error: ' . $e->getMessage() . ')');
        }
    }

    function success(Request $request)
    {
        $orderId = session('order_id') ?? $request->query('order_id');
        $message = session('success') ?? 'Terima kasih atas pesanan Anda.';
        
        $lastOrder = $orderId 
            ? Pesanan::where('id_pesanan', $orderId)->first()
            : null;

        return view('checkout.success', [
            'order_id' => $orderId,
            'message' => $message,
            'lastOrder' => $lastOrder
        ]);
    }

    public function sync(Request $request)
    {
        $cart = $request->json('cart', []);

        if (!is_array($cart) || empty($cart)) {
            session()->forget('cart');
            return response()->json(['success' => true]);
        }

        $sanitizedCart = collect($cart)->filter(function ($item) {
            return isset($item['idproduk']) 
                && isset($item['namaproduk']) 
                && (int)($item['jumlah'] ?? 0) > 0;
        })->all();
        
        session(['cart' => $sanitizedCart]);

        return response()->json(['success' => true]); 
    }
}
