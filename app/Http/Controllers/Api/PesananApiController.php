<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
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

// simpan pesanan baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pesanan' => 'required|string|max:50|unique:pesanan,id_pesanan', 
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

   
    public function update(Request $request, $id_pesanan)
    {
        $pesanan = Pesanan::find($id_pesanan);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        
        $validator = Validator::make($request->all(), [
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
        // ambil data pesanan 
        $updatedPesanan = Pesanan::with(['pengguna', 'status', 'detailPesanan', 'pembayaran'])->find($id_pesanan);

        return response()->json([
            'message' => 'Pesanan berhasil diperbarui',
            'data' => $updatedPesanan
        ]);
    }

   // hapus pesanan
    public function destroy($id_pesanan)
    {
        $pesanan = Pesanan::find($id_pesanan);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $pesanan->delete();

        return response()->json(['message' => 'Pesanan berhasil dihapus']);
    }

        public function riwayatPesanan(Request $request)
    {
        $user = $request->user();

        $pesanan = Pesanan::with(['status', 'detailPesanan', 'pembayaran'])
            ->where('id_pengguna', $user->id_pengguna)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Riwayat pesanan Anda',
            'data' => $pesanan
        ]);
    }

public function submitCheckout(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'nama_penerima' => 'required|string|max:255',
        'nomor_telepon' => 'required|string|max:20',
        'alamat_lengkap' => 'required|string',
        'pengiriman' => 'required|string',
        'metode_pembayaran' => 'required|string',
        'total_harga' => 'required|numeric|min:0',
        'items' => 'required|array|min:1',
        'items.*.idproduk' => 'required|string|exists:produk,id_produk',
        'items.*.harga' => 'required|numeric|min:0',
        'items.*.jumlah' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();
    try {
        $id_pesanan = 'PSN' . rand(100, 999);
        $user = $request->user();

        $pesanan = Pesanan::create([
            'id_pesanan' => $id_pesanan,
            'id_pengguna' => $user->id_pengguna,
            'nama_penerima' => $request->nama_penerima,
            'alamat_pengiriman' => $request->alamat_lengkap,
            'nomor_telepon' => $request->nomor_telepon,
            'tanggal_pesanan' => now(),
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_harga' => $request->total_harga,
            'id_status_pesanan' => 'SP001',
        ]);

        foreach ($request->items as $item) {
            DetailPesanan::create([
                'id_detail' => 'DPS' . rand(100, 999),
                'id_pesanan' => $id_pesanan,
                'id_produk' => $item['idproduk'],
                'jumlah' => $item['jumlah'],
                'harga_saat_pembelian' => $item['harga'],
            ]);
        }

        Pembayaran::create([
            'id_pembayaran' => 'PAY' . rand(100, 999),
            'id_pengguna' => $user->id_pengguna,
            'id_pesanan' => $id_pesanan,
            'tanggal_bayar' => now(),
            'bukti_bayar' => 'pending.jpg',
        ]);

        DB::commit();
        return response()->json(['message' => 'Berhasil', 'id_pesanan' => $id_pesanan], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

public function uploadBukti(Request $request) {
    $request->validate([
        'id_pesanan' => 'required',
        'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $pembayaran = Pembayaran::where('id_pesanan', $request->id_pesanan)->first();
    if($request->hasFile('bukti_bayar')) {
        $file = $request->file('bukti_bayar');
        $nama_file = time() . "_" . $file->getClientOriginalName();
        $file->move(public_path('uploads/bukti_bayar'), $nama_file);
        
        $pembayaran->update(['bukti_bayar' => $nama_file]);
        return response()->json(['message' => 'Sukses']);
    }
}