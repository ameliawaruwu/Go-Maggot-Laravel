<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PembayaranApiController extends Controller
{
    /**
     * Daftar semua pembayaran (GET: /api/pembayaran)
     */
    public function index()
    {
        $pembayaran = Pembayaran::with(['pesanan', 'pengguna'])->get();

        $data = $pembayaran->map(function ($item) {
            $item->bukti_bayar_url = $item->bukti_bayar ? asset('photo/' . $item->bukti_bayar) : null;
            return $item;
        });

        return response()->json([
            'message' => 'Daftar pembayaran',
            'data' => $data
        ]);
    }

    /**
     * Menyimpan pembayaran baru (POST: /api/pembayaran)
     * Pembayaran biasanya berasal dari proses pesanan, membutuhkan id_pesanan dan id_pengguna
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pembayaran' => 'required|string|max:50|unique:pembayaran,id_pembayaran',
            'id_pesanan' => 'required|string|exists:pesanan,id_pesanan',
            'id_pengguna' => 'required|string|exists:pengguna,id_pengguna',
            'tanggal_bayar' => 'nullable|date',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Pastikan pesanan dan pengguna sesuai
        $pesanan = Pesanan::find($request->id_pesanan);
        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $pengguna = Pengguna::find($request->id_pengguna);
        if (!$pengguna) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        $namaFile = null;
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $pembayaran = Pembayaran::create([
            'id_pembayaran' => $request->id_pembayaran,
            'id_pengguna' => $request->id_pengguna,
            'id_pesanan' => $request->id_pesanan,
            'tanggal_bayar' => $request->tanggal_bayar ?: now(),
            'bukti_bayar' => $namaFile,
        ]);

        $pembayaran->bukti_bayar_url = $pembayaran->bukti_bayar ? asset('photo/' . $pembayaran->bukti_bayar) : null;

        return response()->json([
            'message' => 'Pembayaran berhasil disimpan',
            'data' => $pembayaran
        ], 201);
    }

    /**
     * Menampilkan detail pembayaran (GET: /api/pembayaran/{id_pembayaran})
     */
    public function show($id_pembayaran)
    {
        $pembayaran = Pembayaran::with(['pesanan', 'pengguna'])->find($id_pembayaran);

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $pembayaran->bukti_bayar_url = $pembayaran->bukti_bayar ? asset('photo/' . $pembayaran->bukti_bayar) : null;

        return response()->json([
            'message' => 'Detail pembayaran',
            'data' => $pembayaran
        ]);
    }

    /**
     * Memperbarui pembayaran (PUT/PATCH: /api/pembayaran/{id_pembayaran})
     */
    public function update(Request $request, $id_pembayaran)
    {
        $pembayaran = Pembayaran::find($id_pembayaran);

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_pembayaran' => 'required|string|max:50|unique:pembayaran,id_pembayaran,' . $id_pembayaran . ',id_pembayaran',
            'id_pesanan' => 'required|string|exists:pesanan,id_pesanan',
            'id_pengguna' => 'required|string|exists:pengguna,id_pengguna',
            'tanggal_bayar' => 'nullable|date',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validasi eksistensi pesanan/pengguna
        $pesanan = Pesanan::find($request->id_pesanan);
        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $pengguna = Pengguna::find($request->id_pengguna);
        if (!$pengguna) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        $namaFile = $pembayaran->bukti_bayar;
        if ($request->hasFile('bukti_bayar')) {
            if ($pembayaran->bukti_bayar) {
                $oldPath = public_path('photo/' . $pembayaran->bukti_bayar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('bukti_bayar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $dataUpdate = [
            'id_pembayaran' => $request->id_pembayaran,
            'id_pengguna' => $request->id_pengguna,
            'id_pesanan' => $request->id_pesanan,
            'tanggal_bayar' => $request->tanggal_bayar ?: $pembayaran->tanggal_bayar,
            'bukti_bayar' => $namaFile,
        ];

        $pembayaran->update($dataUpdate);

        $updated = Pembayaran::with(['pesanan', 'pengguna'])->find($id_pembayaran);
        $updated->bukti_bayar_url = $updated->bukti_bayar ? asset('photo/' . $updated->bukti_bayar) : null;

        return response()->json([
            'message' => 'Pembayaran berhasil diperbarui',
            'data' => $updated
        ]);
    }

    /**
     * Menghapus pembayaran (DELETE: /api/pembayaran/{id_pembayaran})
     */
    public function destroy($id_pembayaran)
    {
        $pembayaran = Pembayaran::find($id_pembayaran);

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        if ($pembayaran->bukti_bayar) {
            $path = public_path('photo/' . $pembayaran->bukti_bayar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $pembayaran->delete();

        return response()->json(['message' => 'Pembayaran berhasil dihapus']);
    }
}
