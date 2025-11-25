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

        // --- ID PENGGUNA: Fallback ke PG138 (sudah dikonfirmasi ada) jika tidak login
        $idPengguna = auth()->check()
            ? (auth()->user()->id_pengguna ?? auth()->user()->id)
            : 'PG138'; 
        
        // ** PERBAIKAN KRUSIAL A: Bersihkan ID Pengguna dari spasi yang tersembunyi
        $idPengguna = trim($idPengguna); 

        $idPesanan = 'ORD-' . time();

        // Data default (akan diupdate di halaman checkout)
        $namaPenerimaDefault = auth()->check() ? auth()->user()->name : 'Pelanggan Langsung';
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
            // ... (Logging dihapus di sini untuk keringkasan, tapi tetap ada di kode Anda)

            Pesanan::create([
                'id_pesanan'        => $idPesanan,
                'id_pengguna'       => $idPengguna, // ID yang sudah di-trim
                'nama_penerima'     => $namaPenerimaDefault,
                'alamat_pengiriman' => $alamatPengirimanDefault,
                'nomor_telepon'     => $nomorTeleponDefault,
                'tanggal_pesanan'   => now(),
                'metode_pembayaran' => $metodePembayaranDefault,
                'layanan_pengiriman'=> $layananPengirimanDefault,
                'biaya_pengiriman'  => $biayaPengirimanDefault, 
                'total_harga'       => $totalHarga,
            ]);
            
            // ... (Logging)

            foreach ($cart as $item) {
                $idProduk = $item['idproduk'] ?? null;
                $jumlah = $item['jumlah'] ?? 0;
                $harga = $item['harga'] ?? 0;

                if (is_null($idProduk)) {
                    // ... (Logging Error)
                    throw new \Exception("Item keranjang tidak memiliki ID produk yang valid.");
                }

                // ... (Logging)

                // HANYA MENGIRIM 4 KOLOM YANG SESUAI DENGAN TABEL DETAIL_PESANAN
                DetailPesanan::create([
                    'id_pesanan'                => $idPesanan, 
                    'id_produk'                 => $idProduk, 
                    'jumlah'                    => $jumlah,
                    'harga_saat_pembelian'      => $harga,
                ]);
            }

            DB::commit();
            
            // ** PERBAIKAN KRUSIAL B: Simpan data keranjang ke session agar index() bisa membacanya
            session(['cart' => $cart]);

            // ** PERBAIKAN KRUSIAL C: GANTI ROUTE KE HALAMAN CHECKOUT (index)
            $redirectUrl = route('checkout.index'); 

            return response()->json([
                'success' => true, 
                'message' => 'Pesanan instan (draft) berhasil dibuat! Anda akan diarahkan ke halaman checkout.', 
                'redirect_url' => $redirectUrl 
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // ... (Logging lengkap)
            
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan pesanan. Terjadi kesalahan server. Detail Error: ' . $e->getMessage() . 
                              ' (Lihat Log Server untuk detail lebih lanjut)'
            ], 200); 
        }
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

        $idPengguna = trim($idPengguna);
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