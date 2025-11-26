<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 
use App\Models\Pesanan;
use App\Models\DetailPesanan;
<<<<<<< HEAD
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
=======
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a

class CheckoutController extends Controller
{
    // Halaman checkout 
    function index()
    {
        $cart = session('cart', []);
        $draftOrderId = session('draft_order_id'); 

        if (empty($cart)) {
            return redirect('/daftar-produk')->with('error', 'Keranjang masih kosong.');
        }

        // 1. Hitung Total Harga dan Kuantitas Sekali
        $totalPrice = 0;
        $totalQty = 0;
        foreach ($cart as $item) {
            $jumlah = $item['jumlah'] ?? 0;
            $totalPrice += ($item['harga'] ?? 0) * $jumlah; // Menggunakan $jumlah yang sudah didefinisikan
            $totalQty += $jumlah;
        }

        // 2. Buat Draft Order jika belum ada di sesi
        if (!$draftOrderId) {
            $idPesanan = 'ORD-' . time();
            // Fallback ID pengguna ke PG138
            $idPengguna = auth()->check() ? (auth()->user()->id_pengguna ?? auth()->user()->id) : 'PG138'; 
            $idPengguna = trim($idPengguna);
            
            try {
                // Catatan: Gunakan nilai ENUM/VARCHAR yang valid untuk mencegah Data Truncated
                Pesanan::create([
                    'id_pesanan' => $idPesanan,
                    'id_pengguna' => $idPengguna,
                    'nama_penerima' => 'Pelanggan Draft',
                    'alamat_pengiriman' => 'Alamat Default',
                    'nomor_telepon' => '0000000000',
                    'tanggal_pesanan' => now(),
                    // Menggunakan nilai yang diasumsikan valid di ENUM/VARCHAR
                    'metode_pembayaran' => 'Qris', 
                    'layanan_pengiriman'=> 'Instan',
                    'total_harga' => $totalPrice, // Total harga produk dari perhitungan di atas
                    'status' => 'Draft',
                ]);
                
                // Simpan ID baru ke sesi
                session(['draft_order_id' => $idPesanan]);
                $draftOrderId = $idPesanan;
                
            } catch (\Exception $e) {
                Log::error("Gagal membuat draft order di index: " . $e->getMessage() . " on line " . $e->getLine());
                return redirect('/daftar-produk')->with('error', 'Gagal memulai proses checkout (Gagal membuat draft).');
            }
        }
        
        // Perhitungan total sudah dilakukan di awal

        return view('checkout.index', [
            'cartItems' => $cart,
            'totalPrice' => $totalPrice,
            'totalQuantity' => $totalQty,
            'draftOrderId' => $draftOrderId // Dipastikan memiliki nilai
        ]);
    }

    // Proses pesanan instan (AJAX) - Membuat pesanan draft
    public function instantProcess(Request $request)
    {
        $cart = $request->json('cart', []);
        
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);
        }

<<<<<<< HEAD
        $idPengguna = auth()->check()
            ? (auth()->user()->id_pengguna ?? auth()->user()->id)
            : 'PG138'; 
        
        $idPengguna = trim($idPengguna); 
        $idPesanan = 'ORD-' . time();

        $namaPenerimaDefault = auth()->check() ? auth()->user()->name : 'Pelanggan Langsung';
        $alamatPengirimanDefault = 'Alamat Default - Harap Konfirmasi Admin';
        $nomorTeleponDefault = '081987654321';
        $metodePembayaranDefault = 'Qris'; // Ganti ke Qris/Tunai yang valid di ENUM/VARCHAR
        $layananPengirimanDefault = 'Instan'; // Ganti ke Instan/Reguler/Kargo yang valid di ENUM/VARCHAR

        // Hitung total harga produk
=======
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
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
        $totalHarga = 0;
        foreach ($cart as $item){
            $totalHarga += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }

        DB::beginTransaction();
        try {
<<<<<<< HEAD
            // Membuat pesanan draft (Pastikan nilai ENUM/VARCHAR sesuai)
            Pesanan::create([
                'id_pesanan' => $idPesanan,
                'id_pengguna' => $idPengguna, 
                'nama_penerima'  => $namaPenerimaDefault,
=======

            Pesanan::create([
                'id_pesanan'        => $idPesanan,
                'id_pengguna'       => $idPengguna,
                'nama_penerima'     => $namaPenerimaDefault,
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
                'alamat_pengiriman' => $alamatPengirimanDefault,
                'nomor_telepon'  => $nomorTeleponDefault,
                'tanggal_pesanan'  => now(),
                'metode_pembayaran' => $metodePembayaranDefault,
                'layanan_pengiriman'=> $layananPengirimanDefault,
<<<<<<< HEAD
                'total_harga'  => $totalHarga, 
                'status' => 'Draft', // Tambahkan status Draft agar tidak kosong
            ]);
            
            // Membuat detail pesanan
=======
                'biaya_pengiriman'  => $biayaPengirimanDefault,
                'total_harga'       => $totalHarga,
            ]);

>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
            foreach ($cart as $item) {
                $idProduk = $item['idproduk'] ?? null;
                $jumlah = $item['jumlah'] ?? 0;
                $harga = $item['harga'] ?? 0;

                if (is_null($idProduk)) {
                    throw new \Exception("Item keranjang tidak memiliki ID produk yang valid.");
                }

<<<<<<< HEAD
                // HANYA MENGIRIM 4 KOLOM YANG SESUAI DENGAN TABEL DETAIL_PESANAN
                DetailPesanan::create([
                    'id_pesanan' => $idPesanan, 
                    'id_produk' => $idProduk, 
                    'jumlah'=> $jumlah,
                    'harga_saat_pembelian' => $harga,
=======
                DetailPesanan::create([
                    'id_pesanan'              => $idPesanan,
                    'id_produk'               => $idProduk,
                    'jumlah'                  => $jumlah,
                    'harga_saat_pembelian'    => $harga,
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
                ]);
            }

            DB::commit();
<<<<<<< HEAD
            
            // Simpan data keranjang dan ID Pesanan Draft ke sesi
=======

            // Simpan ke session agar tampil di halaman view
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
            session(['cart' => $cart]);
            session(['draft_order_id' => $idPesanan]);

<<<<<<< HEAD
            $redirectUrl = route('checkout.index'); 

=======
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
            return response()->json([
                'success' => true,
                'message' => 'Pesanan instan (draft) berhasil dibuat!',
                'redirect_url' => route('checkout.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
<<<<<<< HEAD
            Log::error("Error instantProcess: " . $e->getMessage() . " on line " . $e->getLine()); 
            
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan pesanan. Terjadi kesalahan server. Detail Error: ' . $e->getMessage() . 
                             ' (Lihat Log Server untuk detail lebih lanjut)'
            ], 200); 
=======
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pesanan. Error: ' . $e->getMessage()
            ], 200);
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
        }
    }

    // ===========================
    // PROSES DARI FORM REGULER
    // ===========================
    function process(Request $request)
    {
<<<<<<< HEAD
        // 1. Validasi
        $request->validate([
            'nama_penerima'  => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'pengiriman' => 'required|string|max:50', 
            'metode_pembayaran' => 'required|string|max:50',
            // Memastikan ID Pesanan ada dan ada di DB
            'id_pesanan' => 'required|string|exists:pesanan,id_pesanan', 
=======
        $request->validate([
            'nama_penerima'     => 'required|string|max:255',
            'nomor_telepon'     => 'required|string|max:15',
            'alamat_lengkap'    => 'required|string',
            'pengiriman'        => 'required|string|max:50',
            'metode_pembayaran' => 'required|string|max:50'
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.')->withInput();
        }
        
        $idPesanan = $request->input('id_pesanan');

<<<<<<< HEAD
        // 2. Hitung Total Harga Produk
=======
        $idPengguna = Auth::check()
            ? (Auth::user()->id_pengguna ?? Auth::user()->id)
            : 'PG138';

        $idPengguna = trim($idPengguna);
        $idPesanan = 'ORD-' . time();

>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
        $totalHargaProduk = 0;
        foreach ($cart as $item){
            $totalHargaProduk += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }
        
        $layananPengiriman = $request->input('pengiriman');
        $totalHargaFinal = $totalHargaProduk; 

        $totalHargaFinal = $totalHargaProduk;

        DB::beginTransaction();
        try {
            // 3. Update data di tabel 'pesanan' berdasarkan ID yang sudah ada
            $pesanan = Pesanan::where('id_pesanan', $idPesanan)->first();
            
            if (!$pesanan) {
                throw new \Exception("Pesanan dengan ID {$idPesanan} tidak ditemukan.");

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
            
            $pesanan->update([
                // Data penerima diperbarui
                'nama_penerima'  => $request->nama_penerima,
                'alamat_pengiriman' => $request->alamat_lengkap, 
                'nomor_telepon'  => $request->nomor_telepon,
                
                // Data pembayaran & pengiriman (Gunakan nilai sesuai form)
                'metode_pembayaran' => $request->metode_pembayaran, // Diambil langsung dari form (Tunai/Qris)
                'layanan_pengiriman'=> $layananPengiriman,
                'total_harga' => $totalHargaFinal, 
                'status' => 'Menunggu Pembayaran', 
            ]);

            DB::commit();

            // 4. Hapus keranjang dan draft ID pesanan
            session()->forget('cart');
<<<<<<< HEAD
            session()->forget('draft_order_id'); 
            $request->session()->flash('clear_cart', true); 
=======
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a

            Log::debug("CHECKOUT SUCCESS. Redirecting to payment. Order ID: " . $idPesanan);

            // 5. Redirect ke halaman pembayaran
            return redirect()->route('payment.form', ['order_id' => $idPesanan])
                ->with('status_message', '✅ Pesanan berhasil divalidasi dan disimpan. Silakan kirim bukti pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
<<<<<<< HEAD
            Log::error('Gagal memproses pesanan form: ' . $e->getMessage() . ' on line ' . $e->getLine());
            
=======
            Log::error('Gagal memproses pesanan form: ' . $e->getMessage());
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
            return back()->withInput()
                ->with('error', 'Gagal menyimpan pesanan. (Error: ' . $e->getMessage() . ')');
        }
    }

<<<<<<< HEAD
    // ===========================
    // SUCCESS PAGE - Tidak berubah, namun disarankan untuk dihapus jika menggunakan payment.form
    // ===========================
=======
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
    function success(Request $request)
    {
        $orderId = session('order_id') ?? $request->query('order_id');
        $message = session('success') ?? 'Terima kasih atas pesanan Anda.';
<<<<<<< HEAD
        $lastOrder = null;
        if ($orderId) {
            $lastOrder = Pesanan::where('id_pesanan', $orderId)->first();
        }
        
        return view('checkout.success', [
            'order_id' => $orderId,
            'message' => $message,
            'lastOrder' => $lastOrder 
=======
        
        $lastOrder = $orderId 
            ? Pesanan::where('id_pesanan', $orderId)->first()
            : null;

        return view('checkout.success', [
            'order_id' => $orderId,
            'message' => $message,
            'lastOrder' => $lastOrder
>>>>>>> 7d9dc6ff6f93eaf1fd8d2ee9cc2de8f536e83e2a
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
