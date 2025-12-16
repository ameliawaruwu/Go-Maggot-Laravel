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
    // menampilkan semua pembayaran milik user yang login
    public function index(Request $request)
    {
        // Ambil user dari Token
        $user = $request->user();

        // Filter pembayaran berdasarkan id_pengguna milik user tersebut
        $pembayaran = Pembayaran::with(['pesanan']) 
            ->where('id_pengguna', $user->id_pengguna)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $pembayaran->map(function ($item) {
            $item->bukti_bayar_url = $item->bukti_bayar ? asset('photo/' . $item->bukti_bayar) : null;
            return $item;
        });

        return response()->json([
            'message' => 'Daftar pembayaran Anda',
            'data' => $data
        ]);
    }

   // menyimpan pembayaran baru
    public function store(Request $request)
    {
        $user = $request->user(); 

        $validator = Validator::make($request->all(), [
            'id_pembayaran' => 'required|string|max:50|unique:pembayaran,id_pembayaran',
            'id_pesanan'    => 'required|string|exists:pesanan,id_pesanan',
            'tanggal_bayar' => 'nullable|date',
            'bukti_bayar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

  // memastikan pesanan milik user yang login
        $pesanan = Pesanan::where('id_pesanan', $request->id_pesanan)
                          ->where('id_pengguna', $user->id_pengguna)
                          ->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan atau bukan milik Anda'], 404);
        }

        $namaFile = null;
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $pembayaran = Pembayaran::create([
            'id_pembayaran' => $request->id_pembayaran,
            'id_pengguna'   => $user->id_pengguna, // Otomatis dari token
            'id_pesanan'    => $request->id_pesanan,
            'tanggal_bayar' => $request->tanggal_bayar ?: now(),
            'bukti_bayar'   => $namaFile,
        ]);

        $pembayaran->bukti_bayar_url = $pembayaran->bukti_bayar ? asset('photo/' . $pembayaran->bukti_bayar) : null;

        return response()->json([
            'message' => 'Pembayaran berhasil disimpan',
            'data' => $pembayaran
        ], 201);
    }

   // menampilkan detail pembayaran berdasarkan id_pembayaran milik user yang login
    public function show(Request $request, $id_pembayaran)
    {
        $user = $request->user();
        $pembayaran = Pembayaran::with(['pesanan'])
            ->where('id_pengguna', $user->id_pengguna)
            ->find($id_pembayaran);

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan atau Anda tidak memiliki akses'], 404);
        }

        $pembayaran->bukti_bayar_url = $pembayaran->bukti_bayar ? asset('photo/' . $pembayaran->bukti_bayar) : null;

        return response()->json([
            'message' => 'Detail pembayaran',
            'data' => $pembayaran
        ]);
    }
// memperbarui pembayaran berdasarkan id_pembayaran milik user yang login
    public function update(Request $request, $id_pembayaran)
    {
        $user = $request->user();
        
        // Cari pembayaran milik user
        $pembayaran = Pembayaran::where('id_pengguna', $user->id_pengguna)->find($id_pembayaran);

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan atau Anda tidak memiliki akses'], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_pembayaran' => 'required|string|max:50|unique:pembayaran,id_pembayaran,' . $id_pembayaran . ',id_pembayaran',
            'tanggal_bayar' => 'nullable|date',
            'bukti_bayar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Logic update foto
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

        $pembayaran->update([
            'id_pembayaran' => $request->id_pembayaran,
            'tanggal_bayar' => $request->tanggal_bayar ?: $pembayaran->tanggal_bayar,
            'bukti_bayar'   => $namaFile,
        ]);

        $pembayaran->bukti_bayar_url = $pembayaran->bukti_bayar ? asset('photo/' . $pembayaran->bukti_bayar) : null;

        return response()->json([
            'message' => 'Pembayaran berhasil diperbarui',
            'data' => $pembayaran
        ]);
    }

   // menghapus pembayaran berdasarkan id_pembayaran milik user yang login
    public function destroy(Request $request, $id_pembayaran)
    {
        $user = $request->user();

        // Cari pembayaran milik user
        $pembayaran = Pembayaran::where('id_pengguna', $user->id_pengguna)->find($id_pembayaran);

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan atau Anda tidak memiliki akses'], 404);
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