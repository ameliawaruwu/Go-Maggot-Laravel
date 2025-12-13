<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailPesananApiController extends Controller
{
    /**
     * Menampilkan semua detail pesanan
     */
    public function index()
    {
        $details = DetailPesanan::with(['pesanan', 'produk'])->get();

        return response()->json([
            'message' => 'Daftar detail pesanan',
            'data' => $details
        ]);
    }

    /**
     * Menyimpan detail pesanan baru
     */
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

        $pesanan = Pesanan::find($request->id_pesanan);
        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $produk = Produk::find($request->id_produk);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $detail = DetailPesanan::create([
            'id_detail' => $request->id_detail,
            'id_pesanan' => $request->id_pesanan,
            'id_produk' => $request->id_produk,
            'jumlah' => $request->jumlah,
            'harga_saat_pembelian' => $request->harga_saat_pembelian,
        ]);

        $detail->load(['pesanan', 'produk']);

        return response()->json([
            'message' => 'Detail pesanan berhasil ditambahkan',
            'data' => $detail
        ], 201);
    }

    /**
     * Menampilkan detail pesanan tertentu
     */
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

    /**
     * Memperbarui detail pesanan
     */
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

        $updated = DetailPesanan::with(['pesanan', 'produk'])->find($id_detail);

        return response()->json([
            'message' => 'Detail pesanan berhasil diperbarui',
            'data' => $updated
        ]);
    }

    /**
     * Menghapus detail pesanan
     */
    public function destroy($id_detail)
    {
        $detail = DetailPesanan::find($id_detail);
        if (!$detail) {
            return response()->json(['message' => 'Detail pesanan tidak ditemukan'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'Detail pesanan berhasil dihapus']);
    }
}
