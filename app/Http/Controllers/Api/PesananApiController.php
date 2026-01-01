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
    // ==================== LOGGING ====================
    \Log::info('╔════════════════════════════════════════╗');
    \Log::info('║       CHECKOUT REQUEST RECEIVED        ║');
    \Log::info('╠════════════════════════════════════════╣');
    \Log::info('Headers:', $request->headers->all());
    \Log::info('Body:', $request->all());
    \Log::info('Bearer Token:', [$request->bearerToken()]);
    
    try {
        // ==================== CEK AUTH ====================
        $user = $request->user();
        
        if (!$user) {
            \Log::error('❌ User not authenticated!');
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak terautentikasi. Token mungkin tidak valid atau expired.'
            ], 401);
        }

        \Log::info('✅ User authenticated:', [
            'id' => $user->id_pengguna,
            'email' => $user->email,
            'role' => $user->role
        ]);

        // ==================== VALIDASI INPUT ====================
        $validator = Validator::make($request->all(), [
            'nama_penerima' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'metode_pembayaran' => 'required|string',
            'total_harga' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required|string',
            'items.*.harga' => 'required|numeric',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            \Log::error('❌ Validation Failed:', $validator->errors()->toArray());
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        \Log::info('✅ Validation passed');

        // ==================== PROSES PESANAN ====================
        DB::beginTransaction();

        // ✅ GENERATE ID PESANAN - HANYA 3 ANGKA
        $randomNumber = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $id_pesanan = 'PSN-' . $randomNumber;
        \Log::info("📦 Creating order: $id_pesanan");

        // Simpan Pesanan
        $pesanan = Pesanan::create([
            'id_pesanan' => $id_pesanan,
            'id_pengguna' => $user->id_pengguna,
            'nama_penerima' => $request->nama_penerima,
            'alamat_pengiriman' => $request->alamat_lengkap,
            'nomor_telepon' => $request->nomor_telepon,
            'tanggal_pesanan' => now(),
            'metode_pembayaran' => strtoupper($request->metode_pembayaran),
            'total_harga' => $request->total_harga,
            'id_status_pesanan' => 'SP001',
        ]);

        \Log::info('✅ Pesanan created');

        // Simpan Detail Pesanan
        foreach ($request->items as $index => $item) {
            // ✅ GENERATE ID DETAIL - HANYA 3 ANGKA
            $detailNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            DetailPesanan::create([
                'id_detail' => 'DPS-' . $detailNumber,
                'id_pesanan' => $id_pesanan,
                'id_produk' => $item['id_produk'],
                'jumlah' => $item['jumlah'],
                'harga_saat_pembelian' => $item['harga'],
            ]);
        }

        \Log::info('✅ Detail Pesanan created: ' . count($request->items) . ' items');

        // Simpan Pembayaran
        // ✅ GENERATE ID PEMBAYARAN - HANYA 3 ANGKA
        $paymentNumber = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $pembayaran = Pembayaran::create([
            'id_pembayaran' => 'PAY-' . $paymentNumber,
            'id_pesanan' => $id_pesanan,
            'id_pengguna' => $user->id_pengguna,
            'tanggal_bayar' => now(),
            'total_bayar' => $request->total_harga,
            'metode_pembayaran' => strtoupper($request->metode_pembayaran),
            'status_pembayaran' => 'Belum Dibayar',
            'bukti_bayar' => null,
            'id_status_pesanan' => 'SP001',
        ]);

        \Log::info('✅ Pembayaran created');

        DB::commit();
        
        \Log::info('╔════════════════════════════════════════╗');
        \Log::info('║          CHECKOUT SUCCESS ✅           ║');
        \Log::info('╠════════════════════════════════════════╣');
        \Log::info("Order ID: $id_pesanan");
        \Log::info("Total: Rp. {$request->total_harga}");
        \Log::info('╚════════════════════════════════════════╝');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dibuat',
            'data' => [
                'id_pesanan' => $id_pesanan,
                'total_harga' => $request->total_harga,
                'metode_pembayaran' => strtoupper($request->metode_pembayaran)
            ]
        ], 201);

    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        
        \Log::error('❌ DATABASE ERROR');
        \Log::error('Error Code: ' . $e->getCode());
        \Log::error('Error Message: ' . $e->getMessage());
        
        return response()->json([
            'status' => 'error',
            'message' => 'Kesalahan database: ' . $e->getMessage()
        ], 500);

    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('❌ GENERAL ERROR');
        \Log::error('Message: ' . $e->getMessage());
        \Log::error('File: ' . $e->getFile());
        \Log::error('Line: ' . $e->getLine());
        
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

public function uploadBukti(Request $request) 
{
    \Log::info('╔════════════════════════════════════════╗');
    \Log::info('║       UPLOAD BUKTI BAYAR REQUEST       ║');
    \Log::info('╠════════════════════════════════════════╣');
    
    try {
        // ✅ CEK AUTH
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        \Log::info('User:', ['id' => $user->id_pengguna]);
        \Log::info('ID Pesanan:', [$request->id_pesanan]);
        \Log::info('Has File:', [$request->hasFile('bukti_bayar')]);

        // ✅ VALIDASI TANPA CEK SIZE DULU (untuk hindari stat error)
        $validator = Validator::make($request->all(), [
            'id_pesanan' => 'required|string|exists:pembayaran,id_pesanan',
            'bukti_bayar' => 'required|file', // Validasi minimal dulu
        ]);

        if ($validator->fails()) {
            \Log::error('Validation Failed:', $validator->errors()->toArray());
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // ✅ CEK PEMBAYARAN
        $pembayaran = Pembayaran::where('id_pesanan', $request->id_pesanan)->first();
        
        if (!$pembayaran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        // ✅ PROSES FILE - LANGSUNG TANPA VALIDASI SIZE
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            
            \Log::info('File Info:', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'extension' => $file->getClientOriginalExtension(),
            ]);

            // ✅ VALIDASI MANUAL EXTENSION
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $extension = strtolower($file->getClientOriginalExtension());
            
            if (!in_array($extension, $allowedExtensions)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Format file tidak didukung. Gunakan: jpg, png, gif'
                ], 400);
            }

            // ✅ GENERATE FILENAME - HANYA 3 ANGKA
            $randomNumber = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $fileName = 'BUKTI-' . $randomNumber . '.' . $extension;
            
            // ✅ PASTIKAN FOLDER EXISTS
            $uploadPath = public_path('uploads/bukti_bayar');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // ✅ MOVE FILE - INI YANG PENTING
            try {
                $file->move($uploadPath, $fileName);
                \Log::info('✅ File moved successfully:', ['name' => $fileName]);
            } catch (\Exception $e) {
                \Log::error('❌ Failed to move file:', ['error' => $e->getMessage()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan file: ' . $e->getMessage()
                ], 500);
            }

            // ✅ HAPUS FILE LAMA
            if ($pembayaran->bukti_bayar) {
                $oldFile = $uploadPath . '/' . $pembayaran->bukti_bayar;
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            // ✅ UPDATE DATABASE
            $pembayaran->update([
                'bukti_bayar' => $fileName,
                'status_pembayaran' => 'Menunggu Konfirmasi',
            ]);

            \Log::info('✅ UPLOAD SUCCESS');

            return response()->json([
                'status' => 'success',
                'message' => 'Bukti bayar berhasil diupload',
                'data' => [
                    'file_name' => $fileName,
                    'status_pembayaran' => 'Menunggu Konfirmasi'
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'File tidak ditemukan'
        ], 400);

    } catch (\Exception $e) {
        \Log::error('❌ UPLOAD ERROR:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal upload: ' . $e->getMessage()
        ], 500);
    }
}
}