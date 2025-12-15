<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PesananApiController extends Controller
{
   
    public function index()
    {
        $pesanan = Pesanan::with(['pengguna', 'status', 'detailPesanan', 'pembayaran'])->get();

        return response()->json([
            'message' => 'Daftar semua pesanan',
            'data' => $pesanan
        ]);
    }

    /**
     * Mengambil satu data pesanan (GET: /api/pesanan/{id_pesanan})
     */
    public function show($id_pesanan)
    {
        $pesanan = Pesanan::with(['pengguna', 'status', 'detailPesanan', 'pembayaran'])->find($id_pesanan);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail pesanan',
            'data' => $pesanan
        ]);
    }

    /**
     * Menyimpan data pesanan baru (POST: /api/pesanan)
     */
    public function store(Request $request)
    {
        // Aturan validasi disesuaikan dengan semua kolom di $fillable
        // dan karakteristik Model (id_pesanan sebagai primary key non-incrementing)
        $validator = Validator::make($request->all(), [
            'id_pesanan' => 'required|string|max:50|unique:pesanan,id_pesanan', // Wajib diisi dan unik karena non-incrementing
            'id_pengguna' => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:255',
            'alamat_pengiriman' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'tanggal_pesanan' => 'required|date',
            'metode_pembayaran' => 'required|string|max:100',
            'total_harga' => 'required|numeric',
            'id_status_pesanan' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan data
        $pesanan = Pesanan::create([
            "id_pesanan" => $request->id_pesanan,
            "id_pengguna" => $request->id_pengguna,
            "nama_penerima" => $request->nama_penerima,
            "alamat_pengiriman" => $request->alamat_pengiriman,
            "nomor_telepon" => $request->nomor_telepon,
            "tanggal_pesanan" => $request->tanggal_pesanan,
            "metode_pembayaran" => $request->metode_pembayaran,
            "total_harga" => $request->total_harga,
            "id_status_pesanan" => $request->id_status_pesanan,
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil ditambahkan',
            'data' => $pesanan
        ], 201);
    }

    /**
     * Memperbarui data pesanan (PUT/PATCH: /api/pesanan/{id_pesanan})
     */
    public function update(Request $request, $id_pesanan)
    {
        $pesanan = Pesanan::find($id_pesanan);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // Aturan validasi untuk update
        $validator = Validator::make($request->all(), [
            // id_pesanan boleh sama dengan dirinya sendiri, tapi harus unik dari yang lain
            'id_pesanan' => 'required|string|max:50|unique:pesanan,id_pesanan,' . $id_pesanan . ',id_pesanan',
            'id_pengguna' => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:255',
            'alamat_pengiriman' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'tanggal_pesanan' => 'required|date',
            'metode_pembayaran' => 'required|string|max:100',
            'total_harga' => 'required|numeric',
            'id_status_pesanan' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Siapkan data untuk update
        $dataUpdate = [
            'id_pesanan' => $request->id_pesanan,
            'id_pengguna' => $request->id_pengguna,
            'nama_penerima' => $request->nama_penerima,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'nomor_telepon' => $request->nomor_telepon,
            'tanggal_pesanan' => $request->tanggal_pesanan,
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_harga' => $request->total_harga,
            'id_status_pesanan' => $request->id_status_pesanan,
        ];

        $pesanan->update($dataUpdate);

        // Ambil data pesanan yang sudah diupdate
        $updatedPesanan = Pesanan::with(['pengguna', 'status', 'detailPesanan', 'pembayaran'])->find($id_pesanan);

        return response()->json([
            'message' => 'Pesanan berhasil diperbarui',
            'data' => $updatedPesanan
        ]);
    }

    /**
     * Menghapus data pesanan (DELETE: /api/pesanan/{id_pesanan})
     */
    public function destroy($id_pesanan)
    {
        $pesanan = Pesanan::find($id_pesanan);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $pesanan->delete();

        return response()->json(['message' => 'Pesanan berhasil dihapus']);
    }

    // pesanan yang login
    public function riwayatPesanan(Request $request)
    {
        // 1. Ambil data user dari Token (Sanctum)
        $user = $request->user();

        // 2. Filter pesanan berdasarkan id_pengguna milik user tersebut
        $pesanan = Pesanan::with(['status', 'detailPesanan', 'pembayaran'])
            ->where('id_pengguna', $user->id_pengguna)
            ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
            ->get();

        return response()->json([
            'message' => 'Riwayat pesanan Anda',
            'data' => $pesanan
        ]);
    }
}
