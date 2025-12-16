<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StatusPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatusPesananApiController extends Controller
{
    // mengambil semua data status pesanan
    public function index()
    {
        $statusPesanan = StatusPesanan::all();

        return response()->json([
            'message' => 'Daftar semua status pesanan',
            'data' => $statusPesanan
        ]);
    }

    // menampilkan detail status pesanan berdasarkan id_status_pesanan
    public function show($id_status_pesanan)
    {
        $statusPesanan = StatusPesanan::find($id_status_pesanan);

        if (!$statusPesanan) {
            return response()->json(['message' => 'Status pesanan tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail status pesanan',
            'data' => $statusPesanan
        ]);
    }

    // menyimpan data status pesanan baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_status_pesanan' => 'required|string|max:50|unique:status_pesanan,id_status_pesanan', // Wajib diisi dan unik karena non-incrementing
            'status' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan_tampilan' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan data
        $statusPesanan = StatusPesanan::create([
            "id_status_pesanan" => $request->id_status_pesanan,
            "status" => $request->status,
            "deskripsi" => $request->deskripsi,
            "urutan_tampilan" => $request->urutan_tampilan,
        ]);

        return response()->json([
            'message' => 'Status pesanan berhasil ditambahkan',
            'data' => $statusPesanan
        ], 201);
    }

   // memperbarui data status pesanan berdasarkan id_status_pesanan
    public function update(Request $request, $id_status_pesanan)
    {
        $statusPesanan = StatusPesanan::find($id_status_pesanan);

        if (!$statusPesanan) {
            return response()->json(['message' => 'Status pesanan tidak ditemukan'], 404);
        }

        // Aturan validasi untuk update
        $validator = Validator::make($request->all(), [
            'id_status_pesanan' => 'required|string|max:50|unique:status_pesanan,id_status_pesanan,' . $id_status_pesanan . ',id_status_pesanan',
            'status' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan_tampilan' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Siapkan data untuk update
        $dataUpdate = [
            'id_status_pesanan' => $request->id_status_pesanan,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'urutan_tampilan' => $request->urutan_tampilan,
        ];

        $statusPesanan->update($dataUpdate);

        // Ambil data status pesanan yang sudah diupdate
        $updatedStatusPesanan = StatusPesanan::find($id_status_pesanan);

        return response()->json([
            'message' => 'Status pesanan berhasil diperbarui',
            'data' => $updatedStatusPesanan
        ]);
    }

    // menghapus data status pesanan berdasarkan id_status_pesanan
    public function destroy($id_status_pesanan)
    {
        $statusPesanan = StatusPesanan::find($id_status_pesanan);

        if (!$statusPesanan) {
            return response()->json(['message' => 'Status pesanan tidak ditemukan'], 404);
        }

        $statusPesanan->delete();

        return response()->json(['message' => 'Status pesanan berhasil dihapus']);
    }
}
