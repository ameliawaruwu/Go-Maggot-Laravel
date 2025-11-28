<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Untuk manajemen data
use Illuminate\Support\Str; // Untuk Str::limit
use Carbon\Carbon; // Untuk mendapatkan tanggal saat ini
use App\Models\Artikel;
use Illuminate\Support\Facades\File; // Untuk mengelola file

class ManagePublicationController extends Controller
{
   // Menampilkan semua artikel
    function tampil()
    {
        $articles = Artikel::orderBy('id_artikel', 'DESC')->get();
        return view('manage-publication.index', compact('articles'));
    }

    // Ke halaman input artikel
    function input()
    {
        $articles = Artikel::all();
        return view('manage-publication.create');
    }

    //Simpan artikel baru
    function simpan(Request $req)
    {
        $req->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'konten' => 'required',
            'hak_cipta' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' 
        ]);

        // cara upload foto
        $namaFile =  null;
        if($req->hasFile('gambar')){
            $file = $req->file('gambar'); // nangkap inputan foto
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile); // file di upload ke folder public/photo
        }

        Artikel::create([
            'id_artikel' => Str::uuid(), 
            'judul' => $req->judul,
            'penulis' => $req->penulis,
            'tanggal' => Carbon::now()->toDateString(),
            'gambar' => $namaFile, 
            'konten' => $req->konten,
            'hak_cipta' => $req->hak_cipta
        ]);

        return redirect('/publication')->with('status_message', 'Artikel berhasil disimpan!');
    }

    // Hapus artikel
    function hapus($id_artikel)
    {
        $artikel = Artikel::findOrFail($id_artikel);
        if ($artikel->gambar){
            $path = public_path('photo/' . $artikel->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $artikel->delete();
        return redirect('/publication')->with('status_message', 'Artikel berhasil dihapus!');
    }

    // Ke halaman edit
    function edit($id_artikel)
    {
        $article = Artikel::findOrFail($id_artikel);
        return view('manage-publication.edit', compact('article'));
    }

    // Melakukan update artikel
    public function update(Request $req, $id_artikel)
    {
        $artikel = Artikel::findOrFail($id_artikel);
        $namaFile = $artikel->gambar; // Mengambil nama file gambar lama
        
        $req->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'konten' => 'required',
            'hak_cipta' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($req->hasFile('gambar')){
            // Hapus gambar lama jika ada
            if($artikel->gambar) {
                $oldPath = public_path('photo/' . $artikel->gambar);
                if (File::exists($oldPath)){
                    File::delete($oldPath);
                }
            }
            // Upload gambar baru
            $file = $req->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }
        
        $artikel->update([
            'judul' => $req->judul,
            'penulis' => $req->penulis,
            'konten' => $req->konten,
            'hak_cipta' => $req->hak_cipta,
            'gambar' => $namaFile,
        ]);

        // Redirect ke halaman index publikasi
        return redirect('/publication')->with('status_message', 'Artikel berhasil diupdate!');
    }


}