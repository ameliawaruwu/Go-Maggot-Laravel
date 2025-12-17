<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DetailPesananApiController extends Controller
{
    // menampilkan semua detail pesanan 
    public function index()
    {
        $details = DetailPesanan::with(['pesanan', 'produk'])->get();

        return response()->json([
            'message' => 'Daftar detail pesanan',
            'data' => $details
        ]);
    }

    // menyimpan detail pesanan baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_detail' => 'required|string|max:50|unique:detail_pesanan,id_detail',
            'id_pesanan' => 'required|string|exists:pesanan,id_pesanan',
            'id_produk' => 'required|string|exists:produk,id_produk',
            'jumlah' => 'required|integer|min:1',
            'harga_saat_pembelian' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil pesanan & produk
        $pesanan = Pesanan::find($request->id_pesanan);
        $produk  = Produk::find($request->id_produk);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        if ($produk->stok < $request->jumlah) {
            return response()->json([
                'message' => 'Stok produk tidak mencukupi. Sisa: ' . $produk->stok
            ], 400);
        }

       
        DB::transaction(function () use ($request, $produk, $pesanan, &$detail) {

            // Simpan detail pesanan
            $detail = DetailPesanan::create([
                'id_detail' => $request->id_detail,
                'id_pesanan' => $request->id_pesanan,
                'id_produk' => $request->id_produk,
                'jumlah' => $request->jumlah,
                'harga_saat_pembelian' => $request->harga_saat_pembelian,
            ]);

          
            $produk->decrement('stok', $request->jumlah);

            $totalBaru = DetailPesanan::where('id_pesanan', $request->id_pesanan)
                ->sum(DB::raw('jumlah * harga_saat_pembelian'));

           
            $pesanan->update([
                'total_harga' => $totalBaru
            ]);
        });

        // Load relasi
        $detail->load(['pesanan', 'produk']);

        return response()->json([
            'message' => 'Detail pesanan berhasil ditambahkan (stok & total harga diperbarui)',
            'data' => $detail
        ], 201);
    }

    
    public function show($id_detail)
    {
        $detail = DetailPesanan::with(['pesanan', 'produk'])->find($id_detail);

        if (!$detail) {
            return response()->json(['message' => 'Detail pesanan tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail pesanan',
            'data' => $detail
        ]);
    }

 
    public function update(Request $request, $id_detail)
    {
        $detail = DetailPesanan::find($id_detail);
        if (!$detail) {
            return response()->json(['message' => 'Detail pesanan tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_detail' => 'required|string|max:50|unique:detail_pesanan,id_detail,' . $id_detail . ',id_detail',
            'id_pesanan' => 'required|string|exists:pesanan,id_pesanan',
            'id_produk' => 'required|string|exists:produk,id_produk',
            'jumlah' => 'required|integer|min:1',
            'harga_saat_pembelian' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $detail->update([
            'id_detail' => $request->id_detail,
            'id_pesanan' => $request->id_pesanan,
            'id_produk' => $request->id_produk,
            'jumlah' => $request->jumlah,
            'harga_saat_pembelian' => $request->harga_saat_pembelian,
        ]);

        $detail->load(['pesanan', 'produk']);

        return response()->json([
            'message' => 'Detail pesanan berhasil diperbarui',
            'data' => $detail
        ]);
    }

    // menghapus detail pesanan
    public function destroy($id_detail)
    {
        $detail = DetailPesanan::find($id_detail);
        if (!$detail) {
            return response()->json(['message' => 'Detail pesanan tidak ditemukan'], 404);
        }

        $detail->delete();

        return response()->json([
            'message' => 'Detail pesanan berhasil dihapus'
        ]);
    }

    // detail pesanan milik user login
    public function detailByPesanan($id_pesanan)
    {
        $user = auth()->user();

        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->where('id_pengguna', $user->id_pengguna)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan atau bukan milik Anda'
            ], 404);
        }

        $detailPesanan = DetailPesanan::with('produk')
            ->where('id_pesanan', $id_pesanan)
            ->get();

        return response()->json([
            'message' => 'Detail pesanan user',
            'pesanan' => [
                'id_pesanan' => $pesanan->id_pesanan,
                'total_harga' => $pesanan->total_harga,
                'status' => $pesanan->status,
                'tanggal' => $pesanan->created_at,
            ],
            'detail' => $detailPesanan
        ]);
    }
}
