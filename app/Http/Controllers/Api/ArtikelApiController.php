<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ArtikelApiController extends Controller
{
    public function index()
    {
        $artikel = Artikel::orderBy('id_artikel', 'ASC')->get();
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

   
    public function store(Request $request)
    {

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
        if ($request->hasFile('gambar')) {
            if ($artikel->gambar) {
                $oldPath = public_path('photo/' . $artikel->gambar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

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
        
        $updatedArtikel = Artikel::find($id_artikel);

        
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

    public function destroy($id_artikel)
    {
        $artikel = Artikel::find($id_artikel);

        if (!$artikel) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

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
