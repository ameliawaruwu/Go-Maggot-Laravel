<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProdukApiController extends Controller
{
   // mengambil semua data produk 
    public function index()
    {
        $produk = Produk::all();

        // Tambahkan URL gambar lengkap
        $data = $produk->map(function($item) {
            if ($item->gambar) {
                $item->gambar_url = asset('photo/' . $item->gambar);
            } else {
                $item->gambar_url = null;
            }
            return $item;
        });

        return response()->json([
            'message' => 'Daftar semua produk',
            'data' => $data
        ]);
    }

   // menampilkan detail produk berdasarkan id_produk
    public function show($id_produk)
    {
        $produk = Produk::find($id_produk);

        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        // Tambahkan URL gambar lengkap
        if ($produk->gambar) {
            $produk->gambar_url = asset('photo/' . $produk->gambar);
        } else {
            $produk->gambar_url = null;
        }

        return response()->json([
            'message' => 'Detail produk',
            'data' => $produk
        ]);
    }

    // menyimpan data produk baru
    public function store(Request $request)
    {
       // validasi input
        $validator = Validator::make($request->all(), [
            'id_produk' => 'required|string|max:50|unique:produk,id_produk', // Wajib diisi dan unik karena non-incrementing
            'nama_produk' => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'masa_penyimpanan' => 'nullable|string|max:50',
            'pengiriman' => 'nullable|string|max:50',
            'berat' => 'nullable|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar' => 'nullable', // Opsional, Maks 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $namaFile = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        // Simpan data
        $produk = Produk::create([
            "id_produk" => $request->id_produk,
            "nama_produk" => $request->nama_produk,
            "deskripsi_produk" => $request->deskripsi_produk,
            "kategori" => $request->kategori,
            "merk" => $request->merk,
            "masa_penyimpanan" => $request->masa_penyimpanan,
            "pengiriman" => $request->pengiriman,
            "berat" => $request->berat,
            "harga" => $request->harga,
            "stok" => $request->stok,
            "gambar" => $namaFile
        ]);
        
        // Tambahkan URL gambar lengkap ke respons
        if ($produk->gambar) {
            $produk->gambar_url = asset('photo/' . $produk->gambar);
        } else {
            $produk->gambar_url = null;
        }


        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $produk
        ], 201);
    }

// memperbarui data produk berdasarkan id_produk
    public function update(Request $request, $id_produk)
    {
        $produk = Produk::find($id_produk);

        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        // Aturan validasi untuk update
        $validator = Validator::make($request->all(), [
            'id_produk' => 'required|string|max:50|unique:produk,id_produk,' . $id_produk . ',id_produk',
            'nama_produk' => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'masa_penyimpanan' => 'nullable|string|max:50',
            'pengiriman' => 'nullable|string|max:50',
            'berat' => 'nullable|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $namaFile = $produk->gambar; //gunakan file gambar lama
        
        // Cek jika ada file gambar baru diupload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama (jika ada)
            if ($produk->gambar) {
                $oldPath = public_path('photo/' . $produk->gambar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            
            // Upload gambar baru
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        // Siapkan data untuk update
        $dataUpdate = [
            'id_produk' => $request->id_produk, 
            'nama_produk' => $request->nama_produk,
            'deskripsi_produk' => $request->deskripsi_produk,
            'kategori' => $request->kategori,
            'merk' => $request->merk,
            'masa_penyimpanan' => $request->masa_penyimpanan,
            'pengiriman' => $request->pengiriman,
            'berat' => $request->berat,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $namaFile 
        ];
        
        $produk->update($dataUpdate);
        
        // Ambil data produk yang sudah diupdate
        $updatedProduk = Produk::find($id_produk);

        // Tambahkan URL gambar lengkap ke respons
        if ($updatedProduk->gambar) {
            $updatedProduk->gambar_url = asset('photo/' . $updatedProduk->gambar);
        } else {
            $updatedProduk->gambar_url = null;
        }

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'data' => $updatedProduk
        ]);
    }

    // hapus data produk berdasarkan id_produk
    public function destroy($id_produk)
    {
        $produk = Produk::find($id_produk);

        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        // Hapus file gambar terkait
        if ($produk->gambar) {
            $path = public_path('photo/' . $produk->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $produk->delete();
        
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}