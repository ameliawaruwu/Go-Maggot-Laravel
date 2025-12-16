<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class GaleriApiController extends Controller
{
    public function index()
    {
        $galeri = Galeri::all();
        $data = $galeri->map(function($item) {
            if ($item->gambar) {
                $item->gambar_url = asset('photo/' . $item->gambar);
            } else {
                $item->gambar_url = null;
            }
            return $item;
        });

        return response()->json([
            'message' => 'Daftar semua galeri',
            'data' => $data
        ]);
    }

    public function show($id_galeri)
    {
        $galeri = Galeri::find($id_galeri);

        if (!$galeri) {
            return response()->json(['message' => 'Galeri tidak ditemukan'], 404);
        }

        // Tambahkan URL gambar lengkap
        if ($galeri->gambar) {
            $galeri->gambar_url = asset('photo/' . $galeri->gambar);
        } else {
            $galeri->gambar_url = null;
        }

        return response()->json([
            'message' => 'Detail galeri',
            'data' => $galeri
        ]);
    }

    
    public function store(Request $request)
    {
        // Aturan validasi disesuaikan dengan semua kolom di $fillable
        $validator = Validator::make($request->all(), [
            'id_galeri' => 'required|string|max:50|unique:galeri,id_galeri',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
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
        $galeri = Galeri::create([
            'id_galeri' => $request->id_galeri,
            'gambar' => $namaFile,
            'keterangan' => $request->keterangan
        ]);
        
        // Tambahkan URL gambar lengkap ke respons
        if ($galeri->gambar) {
            $galeri->gambar_url = asset('photo/' . $galeri->gambar);
        } else {
            $galeri->gambar_url = null;
        }

        return response()->json([
            'message' => 'Galeri berhasil ditambahkan',
            'data' => $galeri
        ], 201);
    }

 
    public function update(Request $request, $id_galeri)
    {
        $galeri = Galeri::find($id_galeri);

        if (!$galeri) {
            return response()->json(['message' => 'Galeri tidak ditemukan'], 404);
        }

        // Aturan validasi untuk update
        $validator = Validator::make($request->all(), [
            'id_galeri' => 'required|string|max:50|unique:galeri,id_galeri,' . $id_galeri . ',id_galeri',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $namaFile = $galeri->gambar;
        
        // Cek jika ada file gambar baru diupload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama (jika ada)
            if ($galeri->gambar) {
                $oldPath = public_path('photo/' . $galeri->gambar);
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
            'id_galeri' => $request->id_galeri,
            'gambar' => $namaFile,
            'keterangan' => $request->keterangan
        ];
        
        $galeri->update($dataUpdate);
        
        // Ambil data galeri yang sudah diupdate menggunakan id baru
        $newId = $request->id_galeri;
        $updatedGaleri = Galeri::find($newId);

        // Tambahkan URL gambar lengkap ke respons
        if ($updatedGaleri->gambar) {
            $updatedGaleri->gambar_url = asset('photo/' . $updatedGaleri->gambar);
        } else {
            $updatedGaleri->gambar_url = null;
        }

        return response()->json([
            'message' => 'Galeri berhasil diperbarui',
            'data' => $updatedGaleri
        ]);
    }

    
    public function destroy($id_galeri)
    {
        $galeri = Galeri::find($id_galeri);

        if (!$galeri) {
            return response()->json(['message' => 'Galeri tidak ditemukan'], 404);
        }

        // Hapus file gambar terkait
        if ($galeri->gambar) {
            $path = public_path('photo/' . $galeri->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $galeri->delete();
        
        return response()->json(['message' => 'Galeri berhasil dihapus']);
    }
}
