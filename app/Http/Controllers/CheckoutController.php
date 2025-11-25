<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Log; 

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

        // --- ID PENGGUNA: Fallback ke PG138 (ID yang valid) jika tidak login
        $idPengguna = auth()->check()
            ? (auth()->user()->id_pengguna ?? auth()->user()->id)
            : 'PG138'; // Pastikan 'PG138' ada di tabel pengguna Anda
        
        $idPesanan = 'ORD-' . time();

        // Data default (akan diupdate di halaman checkout)
        $namaPenerimaDefault = auth()->check() ? auth()->user()->name : 'Pelanggan Langsung';
        $alamatPengirimanDefault = 'Alamat Default - Harap Konfirmasi Admin';
        $nomorTeleponDefault = '000000000000';
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
            // LOG A: Data Pesanan Utama
            Log::info("Memulai transaksi pesanan instan.", [
                'id_pesanan' => $idPesanan, 
                'id_pengguna' => $idPengguna, 
                'total_harga' => $totalHarga
            ]);

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
            
            Log::info("Pesanan ID {$idPesanan} berhasil dibuat. Melanjutkan ke Detail Pesanan...");

            foreach ($cart as $item) {
                $idProduk = $item['idproduk'] ?? null;
                $jumlah = $item['jumlah'] ?? 0;
                $harga = $item['harga'] ?? 0;

                if (is_null($idProduk)) {
                   Log::error("Item keranjang hilang ID produk untuk pesanan: {$idPesanan}");
                   throw new \Exception("Item keranjang tidak memiliki ID produk yang valid.");
                }

                // LOG B: Data Detail Pesanan sebelum insert
                Log::info("Detail Pesanan Data:", [
                    'id_pesanan' => $idPesanan, 
                    'id_produk' => $idProduk, 
                    'jumlah' => $jumlah,
                    'harga_saat_pembelian' => $harga,
                ]);

                // HANYA MENGIRIM 4 KOLOM YANG SESUAI DENGAN TABEL DETAIL_PESANAN
                DetailPesanan::create([
                    'id_pesanan'              => $idPesanan, 
                    'id_produk'               => $idProduk, 
                    'jumlah'                  => $jumlah,
                    'harga_saat_pembelian'    => $harga,
                ]);
            }

            DB::commit();
            
            // ** session()->forget('cart'); Dihapus/dikomentari agar halaman checkout (index) tetap bisa membaca item keranjang.

            // ** Redirect ke halaman checkout (form), BUKAN halaman success **
            $redirectUrl = route('payment.form'); 

            return response()->json([
                'success' => true, 
                'message' => 'Pesanan instan (draft) berhasil dibuat! Anda akan diarahkan ke halaman checkout.', 
                'redirect_url' => $redirectUrl // Menggunakan route checkout.index
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Logging lengkap untuk debugging server
            Log::error('Gagal memproses pesanan instan (Database/Logic): ' . $e->getMessage() . 
                        ' pada baris ' . $e->getLine() . ' di file ' . $e->getFile(), 
                        ['exception' => $e]);
            
            // Mengubah status code 500 menjadi 200 agar pesan error di JSON bisa dibaca oleh frontend
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan pesanan. Terjadi kesalahan server. Detail Error: ' . $e->getMessage() . 
                              ' (Lihat Log Server untuk detail lebih lanjut)'
            ], 200); 
        }
    }

    // Fungsi untuk mengambil biaya pengiriman (Wajib disesuaikan dengan logika bisnis Anda)
    private function getShippingCost($service) {
        // Asumsi: Hanya ada 'Regular' di UI (seperti di screenshot)
        if (strtolower($service) == 'regular') {
            return 10000; // Contoh biaya pengiriman reguler
        }
        return 0;
    }
    
    // ===========================
    // PROSES DARI FORM REGULER (SETELAH TOMBOL CHECKOUT DIKLIK DI HALAMAN FORM)
    // ===========================
    function process(Request $request)
    {
        // ✅ VALIDASI DISESUAIKAN: Menghapus 'kota' dan menambahkan 'pengiriman'
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

        // --- ID PENGGUNA: Fallback ke PG138 (ID yang valid) jika tidak login
        $idPengguna = auth()->check()
            ? (auth()->user()->id_pengguna ?? auth()->user()->id)
            : 'PG138'; 

        $idPesanan = 'ORD-' . time();

        $totalHargaProduk = 0;
        foreach ($cart as $item){
            $totalHargaProduk += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }

        DB::beginTransaction();
        try {
            Pesanan::create([
                'id_pesanan'        => $idPesanan,
                'id_pengguna'       => $idPengguna,
                'nama_penerima'     => $request->nama_penerima,
                // ✅ Menggunakan alamat_lengkap saja sesuai data form
                'alamat_pengiriman' => $request->alamat_lengkap, 
                'nomor_telepon'     => $request->nomor_telepon,
                'tanggal_pesanan'   => now(),
                'metode_pembayaran' => strtoupper($request->metode_pembayaran),
                // ✅ Menyimpan layanan dan biaya pengiriman
                'total_harga'       => $totalHargaFinal, // Total Harga Akhir
            ]);

            foreach ($cart as $item) {
                // HANYA MENGIRIM 4 KOLOM YANG SESUAI DENGAN TABEL DETAIL_PESANAN
                DetailPesanan::create([
                    'id_pesanan'              => $idPesanan,
                    'id_produk'               => $item['idproduk'] ?? null, 
                    'jumlah'                  => $item['jumlah'] ?? 0,
                    'harga_saat_pembelian'    => $item['harga'] ?? 0,
                ]);
            }

            DB::commit();

            session()->forget('cart');
            $request->session()->flash('clear_cart', true); 

            return redirect()->route('checkout.success')
                ->with('success', 'Pesanan berhasil dibuat!')
                ->with('order_id', $idPesanan);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal memproses pesanan form: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Gagal menyimpan pesanan. Silakan coba lagi. (Error: ' . $e->getMessage() . ')');
        }
    }

    // ===========================
    // SUCCESS PAGE
    // ===========================
    function success(Request $request)
    {
        $orderId = session('order_id') ?? $request->query('order_id');
        $message = session('success') ?? 'Terima kasih atas pesanan Anda.';
        // ** HARUS Mengambil data Pesanan dari Database **
        $lastOrder = null;
        if ($orderId) {
            // Mengambil data pesanan dari DB
            $lastOrder = Pesanan::where('id_pesanan', $orderId)->first();
        }

        return view('checkout.success', [
            'order_id' => $orderId,
            'message' => $message,
            'lastOrder' => $lastOrder // <-- WAJIB DIKIRIM ke view
        ]);
    }

    // ===========================
    // SYNC CART
    // ===========================
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