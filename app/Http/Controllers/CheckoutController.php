<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 
use App\Models\Pesanan;
use App\Models\DetailPesanan;
// Perbaikan: Hapus duplikasi use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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
            $totalPrice += ($item['harga'] ?? 0) * $jumlah;
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

        // PERBAIKAN: Hapus duplikasi inisialisasi variabel di sini, gunakan yang di bawah
        
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
        $biayaPengirimanDefault = 0; // Variabel ini tidak digunakan di Pesanan::create saat ini, tapi biarkan saja.

        // Hitung total harga
        $totalHarga = 0;
        foreach ($cart as $item){
            $totalHarga += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }

        DB::beginTransaction();
        try {
            // Membuat pesanan draft (Pastikan nilai ENUM/VARCHAR sesuai)
            Pesanan::create([
                'id_pesanan'        => $idPesanan,
                'id_pengguna'       => $idPengguna,
                'nama_penerima'     => $namaPenerimaDefault,
                'alamat_pengiriman' => $alamatPengirimanDefault,
                'nomor_telepon'     => $nomorTeleponDefault,
                'tanggal_pesanan'   => now(),
                'metode_pembayaran' => $metodePembayaranDefault,
                'layanan_pengiriman'=> $layananPengirimanDefault,
                // PERBAIKAN: Menambahkan 'biaya_pengiriman' jika dibutuhkan di tabel Pesanan
                // 'biaya_pengiriman' => $biayaPengirimanDefault, 
                'total_harga'       => $totalHarga, 
                'status'            => 'Draft', // Tambahkan status Draft agar tidak kosong
            ]);
            
            // Perbaikan: Hapus blok Pesanan::create yang duplikat

            foreach ($cart as $item) {
                $idProduk = $item['idproduk'] ?? null;
                $jumlah = $item['jumlah'] ?? 0;
                $harga = $item['harga'] ?? 0;

                if (is_null($idProduk)) {
                    throw new \Exception("Item keranjang tidak memiliki ID produk yang valid.");
                }

                DetailPesanan::create([
                    'id_pesanan'            => $idPesanan,
                    'id_produk'             => $idProduk,
                    'jumlah'                => $jumlah,
                    'harga_saat_pembelian'  => $harga,
                ]);
            }

            DB::commit();
            
            // Simpan data keranjang dan ID Pesanan Draft ke sesi
            session(['cart' => $cart]);
            session(['draft_order_id' => $idPesanan]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan instan (draft) berhasil dibuat!',
                'redirect_url' => route('checkout.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error instantProcess: " . $e->getMessage() . " on line " . $e->getLine()); 
            
            // PERBAIKAN: Hapus return response()->json yang duplikat
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan pesanan. Terjadi kesalahan server. Detail Error: ' . $e->getMessage() . 
                             ' (Lihat Log Server untuk detail lebih lanjut)'
            ], 200); 
        }
    }

    // ===========================
    // PROSES DARI FORM REGULER
    // ===========================
    function process(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama_penerima'     => 'required|string|max:255',
            'nomor_telepon'     => 'required|string|max:15',
            'alamat_lengkap'    => 'required|string',
            'pengiriman'        => 'required|string|max:50', 
            'metode_pembayaran' => 'required|string|max:50',
            'id_pesanan'        => 'required|string|exists:pesanan,id_pesanan', 
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.')->withInput();
        }
        
        $idPesanan = $request->input('id_pesanan'); // ID dari draft order
        
        // PERBAIKAN: Hapus inisialisasi $idPengguna dan $idPesanan yang duplikat

        // 2. Hitung Total Harga Produk
        $totalHargaProduk = 0;
        foreach ($cart as $item){
            $totalHargaProduk += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }
        
        $layananPengiriman = $request->input('pengiriman');
        $totalHargaFinal = $totalHargaProduk; 
        // PERBAIKAN: Hapus $totalHargaFinal = $totalHargaProduk; yang duplikat

        DB::beginTransaction();
        try {
            // 3. Update data di tabel 'pesanan' berdasarkan ID yang sudah ada
            $pesanan = Pesanan::where('id_pesanan', $idPesanan)->first();
            
            if (!$pesanan) {
                throw new \Exception("Pesanan dengan ID {$idPesanan} tidak ditemukan.");
            }
            
            // PERBAIKAN: Hapus blok Pesanan::create dan foreach DetailPesanan::create di sini
            // Karena ini adalah fungsi update, bukan create.
            
            $pesanan->update([
                // Data penerima diperbarui
                'nama_penerima'     => $request->nama_penerima,
                'alamat_pengiriman' => $request->alamat_lengkap, 
                'nomor_telepon'     => $request->nomor_telepon,
                
                // Data pembayaran & pengiriman (Gunakan nilai sesuai form)
                'metode_pembayaran' => $request->metode_pembayaran, // Diambil langsung dari form (Tunai/Qris)
                'layanan_pengiriman'=> $layananPengiriman,
                'total_harga'       => $totalHargaFinal, 
                'status'            => 'Menunggu Pembayaran', 
            ]);

            DB::commit();

            // 4. Hapus keranjang dan draft ID pesanan
            session()->forget('cart');
            session()->forget('draft_order_id'); 
            $request->session()->flash('clear_cart', true); 

            Log::debug("CHECKOUT SUCCESS. Redirecting to payment. Order ID: " . $idPesanan);

            // 5. Redirect ke halaman pembayaran
            return redirect()->route('payment.form', ['order_id' => $idPesanan])
                ->with('status_message', '✅ Pesanan berhasil divalidasi dan disimpan. Silakan kirim bukti pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memproses pesanan form: ' . $e->getMessage() . ' on line ' . $e->getLine());
            
            return back()->withInput()
                ->with('error', 'Gagal menyimpan pesanan. (Error: ' . $e->getMessage() . ')');
        }
    }

    // ===========================
    // SUCCESS PAGE 
    // ===========================
    function success(Request $request)
    {
        $orderId = session('order_id') ?? $request->query('order_id');
        $message = session('success') ?? 'Terima kasih atas pesanan Anda.';
        
        // Perbaikan: Hapus duplikasi logika pencarian $lastOrder
        $lastOrder = null;
        if ($orderId) {
            $lastOrder = Pesanan::where('id_pesanan', $orderId)->first();
        }
        
        return view('checkout.success', [
            'order_id' => $orderId,
            'message' => $message,
            'lastOrder' => $lastOrder 
        ]);
        // Perbaikan: Hapus return view yang duplikat
    }

    // ... (Fungsi sync tidak berubah)
}