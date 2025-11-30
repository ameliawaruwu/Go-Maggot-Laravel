<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * * @param string 
     * @return string
     * @throws \Exception 
     */
    private function getStatusIdByName(string $statusName): ?string
    {
        $statusId = DB::table('status_pesanan')
            ->where('status', $statusName)
            ->value('id_status_pesanan');

        if (is_null($statusId)) {
            Log::error("Status Pesanan '{$statusName}' tidak ditemukan di database status_pesanan.");
            throw new \Exception("ID Status Pesanan untuk '{$statusName}' tidak ditemukan. Pastikan data status sudah ada.");
        }

        return $statusId;
    }

    /**
     * 
     * * @return string
     */
    private function generateNewIdPesanan(): string
    {
        $latestOrder = Pesanan::where('id_pesanan', 'like', 'PSN%')
                                ->orderBy('id_pesanan', 'desc')
                                ->first();

        $lastNumber = 0;
        if ($latestOrder) {
            $lastId = $latestOrder->id_pesanan;
            $numberPart = substr($lastId, 3); 
            $lastNumber = (int) $numberPart;
        }

        $newNumber = $lastNumber + 1;
        $newIdPesanan = 'PSN' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $newIdPesanan;
    }

    // Menampilkan halaman checkout
    function index()
    {
        $cart = session('cart', []);
        $draftOrderId = session('draft_order_id');

        if (empty($cart)) {
            return redirect('/daftar-produk')->with('error', 'Keranjang masih kosong.');
        }

        // Memastikan pengguna harus sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login untuk melanjutkan proses checkout.');
        }

        // Menghitung total harga dan kuantitas
        $totalPrice = 0;
        $totalQty = 0;

        foreach ($cart as $item) {
            $jumlah = $item['jumlah'] ?? 0;
            $totalPrice += ($item['harga'] ?? 0) * $jumlah;
            $totalQty += $jumlah;
        }

        // Membuat draft order jika belum ada di sesi
        if (!$draftOrderId) {
            $idPesanan = $this->generateNewIdPesanan();

            // Mengambil id pengguna
            $idPengguna = Auth::user()->id_pengguna ?? Auth::user()->id;
            $idPengguna = trim($idPengguna);

            try {
                // Menetapkan status awal 'Menunggu Pembayaran'
                $targetStatus = 'Menunggu Pembayaran';
                $idStatusMenunggu = $this->getStatusIdByName($targetStatus);

                Pesanan::create([
                    'id_pesanan'        => $idPesanan,
                    'id_pengguna'        => $idPengguna,
                    'nama_penerima'      => Auth::user()->name ?? 'Pelanggan',
                    'alamat_pengiriman'  => 'Alamat Default',
                    'nomor_telepon'      => '0000000000',
                    'tanggal_pesanan'    => now(),
                    'metode_pembayaran'  => 'Qris',
                    'layanan_pengiriman' => 'Instan',
                    'total_harga'        => $totalPrice,
                    'status'             => $targetStatus,
                    'id_status_pesanan'  => $idStatusMenunggu,
                ]);

                // Menyimpan ID pesanan baru ke sesi
                session(['draft_order_id' => $idPesanan]);
                $draftOrderId = $idPesanan;

            } catch (\Exception $e) {
                Log::error("Gagal membuat draft order di index: " . $e->getMessage() . " on line " . $e->getLine());
                return redirect('/daftar-produk')
                    ->with('error', 'Gagal memulai proses checkout (Gagal membuat draft). Detail: ' . $e->getMessage());
            }
        }

        return view('checkout.index', [
            'cartItems'    => $cart,
            'totalPrice'   => $totalPrice,
            'totalQuantity'=> $totalQty,
            'draftOrderId' => $draftOrderId
        ]);
    }

    // Membuat pesanan draft
    public function instantProcess(Request $request)
    {
        $cart = $request->json('cart', []);

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);
        }

        // Memastikan pengguna sudah login
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login untuk memproses pesanan.'
            ], 401);
        }

        // Mengambil id pengguna
        $idPengguna = Auth::user()->id_pengguna ?? Auth::user()->id;
        $idPengguna = trim($idPengguna);
        $idPesanan = $this->generateNewIdPesanan();

        // Data default pengguna
        $namaPenerimaDefault = Auth::user()->name;
        $alamatPengirimanDefault = 'Alamat Default - Harap Konfirmasi Admin';
        $nomorTeleponDefault = '081987654321';
        $metodePembayaranDefault = 'QRIS';
        $layananPengirimanDefault = 'Reguler';
        $biayaPengirimanDefault = 0;

        // Menghitung total harga
        $totalHarga = 0;
        foreach ($cart as $item) {
            $totalHarga += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }

        DB::beginTransaction();
        try {
            $targetStatus = 'Menunggu Pembayaran';
            $idStatusMenunggu = $this->getStatusIdByName($targetStatus);

            Pesanan::create([
                'id_pesanan'         => $idPesanan,
                'id_pengguna'        => $idPengguna,
                'nama_penerima'      => $namaPenerimaDefault,
                'alamat_pengiriman'  => $alamatPengirimanDefault,
                'nomor_telepon'      => $nomorTeleponDefault,
                'tanggal_pesanan'    => now(),
                'metode_pembayaran'  => $metodePembayaranDefault,
                'layanan_pengiriman' => $layananPengirimanDefault,
                'total_harga'        => $totalHarga,
                'status'             => $targetStatus,
                'id_status_pesanan'  => $idStatusMenunggu,
            ]);

            foreach ($cart as $item) {
                $idProduk = $item['idproduk'] ?? null;
                $jumlah   = $item['jumlah'] ?? 0;
                $harga    = $item['harga'] ?? 0;

                if (is_null($idProduk)) {
                    throw new \Exception("Item keranjang tidak memiliki ID produk yang valid.");
                }

                DetailPesanan::create([
                    'id_pesanan'           => $idPesanan,
                    'id_produk'            => $idProduk,
                    'jumlah'               => $jumlah,
                    'harga_saat_pembelian' => $harga,
                ]);
            }
            // untuk mengurangi stok
            $produk = \App\Models\Produk::find($idProduk);
                if ($produk) {
                     if($produk->stok < $jumlah) {
                         throw new \Exception("Stok produk {$produk->nama_produk} habis.");
                    }
                    $produk->decrement('stok', $jumlah);
                }

            DB::commit();

            session(['cart' => $cart]);
            session(['draft_order_id' => $idPesanan]);

            return response()->json([
                'success'      => true,
                'message'      => 'Pesanan instan (draft) berhasil dibuat!',
                'redirect_url' => route('checkout.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Gagal menyimpan pesanan. Detail Error: ' . $e->getMessage();

            Log::error("Error instantProcess: " . $errorMessage . " on line " . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 200);
        }
    }

    // Proses dari form
    function process(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_penerima'     => 'required|string|max:255',
            'nomor_telepon'     => 'required|string|max:15',
            'alamat_lengkap'    => 'required|string',
            'pengiriman'        => 'required|string|max:50',
            'metode_pembayaran' => 'required|string|max:50',
            'id_pesanan'        => 'required|string|exists:pesanan,id_pesanan',
        ]);

        // Memastikan sudah login
        if (!Auth::check()) {
            return back()->with('error', 'Anda harus login untuk menyelesaikan pesanan ini.')->withInput();
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.')->withInput();
        }

        $idPesanan = $request->input('id_pesanan');

        // Menghitung total harga produk
        $totalHargaProduk = 0;
        foreach ($cart as $item) {
            $totalHargaProduk += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
        }

        $layananPengiriman = $request->input('pengiriman');
        $totalHargaFinal = $totalHargaProduk;

        DB::beginTransaction();
        try {
            $statusPesananString = 'Menunggu Pembayaran';
            $idStatusMenunggu = $this->getStatusIdByName($statusPesananString);

            $pesanan = Pesanan::where('id_pesanan', $idPesanan)->first();

            if (!$pesanan) {
                throw new \Exception("Pesanan dengan ID {$idPesanan} tidak ditemukan.");
            }

            $pesanan->update([
                'nama_penerima'      => $request->nama_penerima,
                'alamat_pengiriman'  => $request->alamat_lengkap,
                'nomor_telepon'      => $request->nomor_telepon,
                'metode_pembayaran'  => $request->metode_pembayaran,
                'layanan_pengiriman' => $layananPengiriman,
                'total_harga'        => $totalHargaFinal,
                'status'             => $statusPesananString,
                'id_status_pesanan'  => $idStatusMenunggu,
            ]);

            Pembayaran::create([
                'id_pembayaran'     => 'PAY-' . time(),
                'id_pesanan'        => $idPesanan,
                'id_pengguna'       => $pesanan->id_pengguna,
                'tanggal_bayar'     => now(),
                'bukti_bayar'       => '',
                'total_bayar'       => $totalHargaFinal,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $statusPesananString,
                'id_status_pesanan' => $idStatusMenunggu,
            ]);

            DB::commit();

            session()->forget('cart');
            session()->forget('draft_order_id');
            $request->session()->flash('clear_cart', true);

            Log::debug("CHECKOUT SUCCESS. Redirecting to payment. Order ID: " . $idPesanan);

            return redirect()->route('payment.form', ['order_id' => $idPesanan]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
}