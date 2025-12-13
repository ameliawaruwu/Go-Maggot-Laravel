<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Produk;

class CheckoutController extends Controller
{
    private function getStatusIdByName(string $statusName): ?string
    {
        $statusId = DB::table('status_pesanan')
            ->where('status', $statusName)
            ->value('id_status_pesanan');

        if (is_null($statusId)) {
            Log::error("Status Pesanan '{$statusName}' tidak ditemukan di database status_pesanan.");
            throw new \Exception("ID Status Pesanan untuk '{$statusName}' tidak ditemukan.");
        }

        return $statusId;
    }

    private function generateNewIdPesanan(): string
    {
        $latestOrder = Pesanan::where('id_pesanan', 'like', 'PSN%')
            ->orderBy('id_pesanan', 'desc')
            ->first();

        $lastNumber = 0;
        if ($latestOrder) {
            $lastNumber = (int) substr($latestOrder->id_pesanan, 3);
        }

        return 'PSN' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    private function generateNewIdDetail(): string
    {
        $latestDetail = DB::table('detail_pesanan')
            ->where('id_detail', 'like', 'DPS%')
            ->orderBy('id_detail', 'desc')
            ->first();

        $lastNumber = 0;
        if ($latestDetail) {
            $lastNumber = (int) substr($latestDetail->id_detail, 3);
        }

        return 'DPS' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    private function generateNewIdPembayaran(): string
    {
        $latest = DB::table('pembayaran')
            ->where('id_pembayaran', 'like', 'PAY%')
            ->orderBy('id_pembayaran', 'desc')
            ->first();

        $lastNumber = 0;
        if ($latest) {
            $numberPart = preg_replace('/[^0-9]/', '', $latest->id_pembayaran);
            $lastNumber = (int) $numberPart;
        }

        return 'PAY' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    //Proses sinkronisasi
    public function sync(Request $request)
    {
        try {
            $validated = $request->validate([
                'cart' => 'required|array',
                'cart.*.idproduk' => 'required',
                'cart.*.namaproduk' => 'required|string',
                'cart.*.harga' => 'required|numeric',
                'cart.*.gambar' => 'required|string',
                'cart.*.jumlah' => 'required|integer|min:1',
            ]);

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login untuk checkout'
                ], 401);
            }

            foreach ($validated['cart'] as $item) {
                $produk = Produk::find($item['idproduk']);
                if (!$produk) {
                    return response()->json([
                        'success' => false,
                        'message' => "Produk {$item['namaproduk']} tidak ditemukan."
                    ], 404);
                }
                if ($produk->stok < $item['jumlah']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok produk {$produk->nama_produk} tidak mencukupi. Tersedia: {$produk->stok}"
                    ], 400);
                }
            }

            session(['cart' => $validated['cart']]);

            Log::info('Cart synced successfully', [
                'user_id' => Auth::id(),
                'cart_items' => count($validated['cart']),
                'cart_data' => $validated['cart']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart berhasil disinkronkan',
                'cart_count' => count($validated['cart'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error on cart sync: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Data cart tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Cart sync error: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyinkronkan cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login')
                ->with('error', 'Silakan login untuk melanjutkan proses checkout.');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect('/daftar-produk')
                ->with('error', 'Keranjang masih kosong.');
        }

        $totalPrice = $totalQty = 0;
        foreach ($cart as $item) {
            $jumlah = $item['jumlah'] ?? 0;
            $totalPrice += ($item['harga'] ?? 0) * $jumlah;
            $totalQty += $jumlah;
        }

        return view('checkout.index', [
            'cartItems' => $cart,
            'totalPrice' => $totalPrice,
            'totalQuantity' => $totalQty,
            'draftOrderId' => null,
        ]);
    }

    // Proses masuk ke db
    public function process(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'pengiriman' => 'required|string|max:50',
            'metode_pembayaran' => 'required|string|max:50',
        ]);

        if (!Auth::check()) {
            return back()->with('error', 'Anda harus login untuk menyelesaikan pesanan ini.')
                         ->withInput();
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.')->withInput();
        }

        $idPengguna = Auth::user()->id_pengguna ?? Auth::user()->id;

        DB::beginTransaction();
        try {
            $idPesanan = $this->generateNewIdPesanan();
            $totalHargaProduk = 0;
            foreach ($cart as $item) {
                $totalHargaProduk += ($item['harga'] ?? 0) * ($item['jumlah'] ?? 0);
            }

            $statusPesananString = 'Menunggu Pembayaran';
            $idStatusMenunggu = $this->getStatusIdByName($statusPesananString);

            $pesananCreated = Pesanan::create([
                'id_pesanan' => $idPesanan,
                'id_pengguna' => $idPengguna,
                'nama_penerima' => $request->nama_penerima,
                'alamat_pengiriman' => $request->alamat_lengkap,
                'nomor_telepon' => $request->nomor_telepon,
                'tanggal_pesanan' => now(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'layanan_pengiriman' => $request->pengiriman,
                'total_harga' => $totalHargaProduk,
                'status' => $statusPesananString,
                'id_status_pesanan' => $idStatusMenunggu,
            ]);

            foreach ($cart as $item) {
                $idProduk = $item['idproduk'] ?? null;
                $jumlah = $item['jumlah'] ?? 0;
                $harga = $item['harga'] ?? 0;

                $produk = Produk::find($idProduk);
                if (!$produk) throw new \Exception("Produk dengan ID {$idProduk} tidak ditemukan.");
                if ($produk->stok < $jumlah) throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi.");

                $idDetail = $this->generateNewIdDetail();

                DetailPesanan::create([
                    'id_detail' => $idDetail,
                    'id_pesanan' => $idPesanan,
                    'id_produk' => $idProduk,
                    'jumlah' => $jumlah,
                    'harga_saat_pembelian' => $harga,
                ]);

                $produk->decrement('stok', $jumlah);
            }

            $idPembayaran = $this->generateNewIdPembayaran();

            Pembayaran::create([
                'id_pembayaran' => $idPembayaran,
                'id_pesanan' => $idPesanan,
                'id_pengguna' => $idPengguna,
                'tanggal_bayar' => now(),
                'bukti_bayar' => '',
                'total_bayar' => $totalHargaProduk,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $statusPesananString,
                'id_status_pesanan' => $idStatusMenunggu,
            ]);

            DB::commit();

            session()->forget(['cart', 'draft_order_id']);
            $request->session()->flash('clear_cart', true);

            return redirect()
                ->route('payment.form', ['order_id' => $idPesanan])
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout process failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Gagal memproses pesanan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
