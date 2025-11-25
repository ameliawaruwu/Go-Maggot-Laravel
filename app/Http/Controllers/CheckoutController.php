<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPesanan;

class CheckoutController extends Controller
{
    // ===========================
    // TAMPILKAN HALAMAN CHECKOUT (Form Pengiriman)
    // ===========================
    function index()
    {
        // Ambil keranjang dari session (yang disinkronkan dari JS)
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

    // ========================================================
    // FUNGSI BARU: PROSES INSTAN (DIPANGGIL DARI HALAMAN PRODUK)
    // ========================================================
    public function instantProcess(Request $request)
    {
        // Menerima data keranjang (cart) dari AJAX POST
        $cart = $request->json('cart', []);
        
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);
        }

        // --- 1. SET DATA PENGGUNA & DEFAULT ---
        // Handle id_pengguna: Jika tidak login, gunakan ID GUEST yang unik.
        // Asumsi: Jika id_pengguna di tabel 'pesanan' tidak merujuk ke Foreign Key, 
        // atau jika Foreign Key-nya mengizinkan nilai yang tidak ada di tabel 'pengguna'.
        $idPengguna = auth()->check() ? auth()->user()->id_pengguna : 'GUEST-' . time();
        $idPesanan  = 'ORD-' . time();

        // Data Pengiriman Default/Dummy untuk pesanan instan
        $namaPenerimaDefault = auth()->check() ? auth()->user()->name : 'Pelanggan Langsung';
        $alamatPengirimanDefault = 'Alamat Default - Harap Konfirmasi Admin';
        $nomorTeleponDefault = '000000000000'; // Harus diisi, sesuaikan panjangnya dengan kolom di DB
        $metodePembayaranDefault = 'INSTANT_ORDER'; 

        // Hitung total harga
        $totalHarga = 0;
        foreach ($cart as $item){
            $totalHarga += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }

        // --- 2. Transaksi Database ---
        DB::beginTransaction();
        try {
            
            // Simpan ke tabel PESANAN
            Pesanan::create([
                'id_pesanan'        => $idPesanan,
                'id_pengguna'       => $idPengguna,
                'nama_penerima'     => $namaPenerimaDefault,
                'alamat_pengiriman' => $alamatPengirimanDefault,
                'nomor_telepon'     => $nomorTeleponDefault,
                'tanggal_pesanan'   => now(), // Tambahkan ini jika kolomnya tidak auto-filled
                'metode_pembayaran' => $metodePembayaranDefault,
                'total_harga'       => $totalHarga,
            ]);

            // Simpan detail produk ke tabel DETAIL_PESANAN
            foreach ($cart as $item) {
                DetailPesanan::create([
                    'id_pesanan'    => $idPesanan, 
                    'id_produk'     => $item['idproduk'] ?? null, 
                    'nama_produk'   => $item['namaproduk'] ?? 'Unknown', 
                    'jumlah'        => $item['jumlah'] ?? 0,
                    'harga_satuan'  => $item['harga'] ?? 0,
                    'subtotal'      => ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0),
                ]);
            }

            DB::commit(); // Semua query berhasil
            
            // Hapus session cart jika sebelumnya ada data yang tersimpan di session
            session()->forget('cart');

            // Beri respon sukses ke AJAX, termasuk URL redirect
            return response()->json([
                'success' => true, 
                'message' => 'Pesanan instan berhasil dibuat!', 
                'redirect_url' => route('checkout.success') . '?order_id=' . $idPesanan
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua query jika terjadi error
            \Log::error('Gagal memproses pesanan instan: ' . $e->getMessage());
            
            // Respon error ke AJAX
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan pesanan. Detail: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===========================
    // SIMPAN PESANAN KE DATABASE (DIPANGGIL DARI FORM CHECKOUT)
    // ===========================
    function process(Request $request)
    {
        // --- 1. Validasi ---
        $request->validate([
            'nama_penerima'     => 'required|string|max:255',
            'nomor_telepon'     => 'required|string|max:15',
            'alamat_lengkap'    => 'required|string',
            'kota'              => 'required|string|max:100',
            'metode_pembayaran' => 'required|string|max:50'
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        // --- 2. Penanganan ID Pengguna & Total Harga ---
        $idPengguna = auth()->check() ? auth()->user()->id_pengguna : 'GUEST-' . time();
        $idPesanan = 'ORD-' . time();
        $totalHarga = 0;
        
        foreach ($cart as $item){
            $totalHarga += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }

        // --- 3. Transaksi Database ---
        DB::beginTransaction();
        try {
            // Simpan ke tabel PESANAN
            Pesanan::create([
                'id_pesanan'        => $idPesanan,
                'id_pengguna'       => $idPengguna,
                'nama_penerima'     => $request->nama_penerima,
                'alamat_pengiriman' => $request->alamat_lengkap . ', ' . $request->kota,
                'nomor_telepon'     => $request->nomor_telepon,
                'tanggal_pesanan'   => now(),
                'metode_pembayaran' => strtoupper($request->metode_pembayaran),
                'total_harga'       => $totalHarga,
            ]);

            // Simpan detail produk ke tabel DETAIL_PESANAN (Looping Keranjang)
            foreach ($cart as $item) {
                DetailPesanan::create([
                    'id_pesanan'    => $idPesanan,
                    'id_produk'     => $item['idproduk'] ?? null, 
                    'nama_produk'   => $item['namaproduk'] ?? 'Unknown', 
                    'jumlah'        => $item['jumlah'] ?? 0,
                    'harga_satuan'  => $item['harga'] ?? 0,
                    'subtotal'      => ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0),
                ]);
            }

            DB::commit(); // Komit transaksi

            // hapus session cart setelah semua data disimpan
            session()->forget('cart');
            $request->session()->flash('clear_cart', true); 

            return redirect()->route('checkout.success')
                ->with('success', 'Pesanan berhasil dibuat!')
                ->with('order_id', $idPesanan);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error
            \Log::error('Gagal memproses pesanan form: ' . $e->getMessage());
            
            return back()->withInput()
                        ->with('error', 'Gagal menyimpan pesanan. Silakan coba lagi. (Error: ' . $e->getMessage() . ')');
        }
    }

    // ===========================
    // HALAMAN SUCCESS
    // ===========================
    function success(Request $request)
    {
        // Ambil order_id dari session atau query parameter
        $orderId = session('order_id') ?? $request->query('order_id');
        $message = session('success') ?? 'Terima kasih atas pesanan Anda.';

        return view('checkout.success', [
            'order_id' => $orderId,
            'message' => $message
        ]);
    }

   public function sync(Request $request)
{
    // Mengambil data 'cart' dari AJAX POST body
    $cart = $request->json('cart', []);

    // Sanitasi data minimal dan menghapus session jika keranjang kosong
    if (!is_array($cart) || empty($cart)) {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    // Filter array 'cart' untuk mencegah item korup (seperti 'undefined' lama) menyebabkan masalah di blade view.
    $sanitizedCart = collect($cart)->filter(function ($item) {
        return isset($item['idproduk']) && isset($item['namaproduk']) && (int)($item['jumlah'] ?? 0) > 0;
    })->all();
    
    // Simpan keranjang yang sudah bersih ke Session
    session(['cart' => $sanitizedCart]);

    // Mengembalikan respons JSON sukses
    return response()->json(['success' => true]); 
}
}