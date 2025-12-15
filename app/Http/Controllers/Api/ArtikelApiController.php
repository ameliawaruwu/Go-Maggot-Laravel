<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ArtikelApiController extends Controller
{
    /**
     * Mengambil semua data artikel (GET: /api/artikel)
     */
    public function index()
    {
        $artikel = Artikel::all();

        // Tambahkan URL gambar lengkap
        $data = $artikel->map(function($item) {
            if ($item->gambar) {
                $item->gambar_url = asset('photo/' . $item->gambar);
            } else {
                $item->gambar_url = null;
            }
            return $item;
        });

        return response()->json([
            'message' => 'Daftar semua artikel',
            'data' => $data
        ]);
    }

    /**
     * Mengambil satu data artikel (GET: /api/artikel/{id_artikel})
     */
    public function show($id_artikel)
    {
        $artikel = Artikel::find($id_artikel);

        if (!$artikel) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        // Tambahkan URL gambar lengkap
        if ($artikel->gambar) {
            $artikel->gambar_url = asset('photo/' . $artikel->gambar);
        } else {
            $artikel->gambar_url = null;
        }

        return response()->json([
            'message' => 'Detail artikel',
            'data' => $artikel
        ]);
    }

    /**
     * Menyimpan data artikel baru (POST: /api/artikel)
     */
    public function store(Request $request)
    {
        // Aturan validasi disesuaikan dengan semua kolom di $fillable
        $validator = Validator::make($request->all(), [
            'id_artikel' => 'required|string|max:50|unique:artikel,id_artikel',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'konten' => 'required|string',
            'hak_cipta' => 'nullable|string|max:255',
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
        $artikel = Artikel::create([
            'id_artikel' => $request->id_artikel,
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'tanggal' => $request->tanggal,
            'gambar' => $namaFile,
            'konten' => $request->konten,
            'hak_cipta' => $request->hak_cipta
        ]);
        
        // Tambahkan URL gambar lengkap ke respons
        if ($artikel->gambar) {
            $artikel->gambar_url = asset('photo/' . $artikel->gambar);
        } else {
            $artikel->gambar_url = null;
        }

        return response()->json([
            'message' => 'Artikel berhasil ditambahkan',
            'data' => $artikel
        ], 201);
    }

    /**
     * Memperbarui data artikel (PUT/PATCH: /api/artikel/{id_artikel})
     */
    public function update(Request $request, $id_artikel)
    {
        $artikel = Artikel::find($id_artikel);

        if (!$artikel) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        // Aturan validasi untuk update
        $validator = Validator::make($request->all(), [
            'id_artikel' => 'required|string|max:50|unique:artikel,id_artikel,' . $id_artikel . ',id_artikel',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'konten' => 'required|string',
            'hak_cipta' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $namaFile = $artikel->gambar;
        
        // Cek jika ada file gambar baru diupload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama (jika ada)
            if ($artikel->gambar) {
                $oldPath = public_path('photo/' . $artikel->gambar);
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
            'id_artikel' => $request->id_artikel,
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'tanggal' => $request->tanggal,
            'gambar' => $namaFile,
            'konten' => $request->konten,
            'hak_cipta' => $request->hak_cipta
        ];
        
        $artikel->update($dataUpdate);
        
        // Ambil data artikel yang sudah diupdate
        $updatedArtikel = Artikel::find($id_artikel);

        // Tambahkan URL gambar lengkap ke respons
        if ($updatedArtikel->gambar) {
            $updatedArtikel->gambar_url = asset('photo/' . $updatedArtikel->gambar);
        } else {
            $updatedArtikel->gambar_url = null;
        }

        return response()->json([
            'message' => 'Artikel berhasil diperbarui',
            'data' => $updatedArtikel
        ]);
    }

    /**
     * Menghapus data artikel (DELETE: /api/artikel/{id_artikel})
     */
    public function destroy($id_artikel)
    {
        $artikel = Artikel::find($id_artikel);

        if (!$artikel) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        // Hapus file gambar terkait
        if ($artikel->gambar) {
            $path = public_path('photo/' . $artikel->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $artikel->delete();
        
        return response()->json(['message' => 'Artikel berhasil dihapus']);
    }
}
