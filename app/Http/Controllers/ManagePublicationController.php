<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Str; 
use Carbon\Carbon; 
use App\Models\Artikel;
use Illuminate\Support\Facades\File;

class ManagePublicationController extends Controller
{

    private function generateNewIdArtikel(): string
    {
        $latestArticle = Artikel::where('id_artikel', 'like', 'ART%')
            ->orderBy('id_artikel', 'desc')
            ->first();

        $lastNumber = 0;
        if ($latestArticle) {
            $lastNumber = (int) substr($latestArticle->id_artikel, 3);
        }

        return 'ART' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    function tampil()
    {
        $articles = Artikel::orderBy('id_artikel', 'ASC')->get();
        return view('manage-publication.index', compact('articles'));
    }

   
    function input()
    {
        return view('manage-publication.create');
    }

   
    function simpan(Request $req)
    { 
        $validated = $req->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'konten' => 'required|string',
            'hak_cipta' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $namaFile =  null;
        if($req->hasFile('gambar')){
            $file = $req->file('gambar'); 
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile); 
        }

        $validated['gambar'] = $namaFile;
        $validated['id_artikel'] = $this->generateNewIdArtikel();
        $validated['tanggal'] = Carbon::now()->toDateString();

        Artikel::create($validated);

        return redirect('/publication')->with('status_message', 'Artikel berhasil disimpan!');
    }

    
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

    
    function edit($id_artikel)
    {
        $article = Artikel::findOrFail($id_artikel);
        return view('manage-publication.edit', compact('article'));
    }


    public function update(Request $req, $id_artikel)
    {
        $artikel = Artikel::findOrFail($id_artikel);
        $namaFile = $artikel->gambar; 
        
        $validated = $req->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'konten' => 'required|string',
            'hak_cipta' => 'required|string|max:255',
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
        
        $validated['gambar'] = $namaFile;

        $artikel->update($validated);

        return redirect('/publication')->with('status_message', 'Artikel berhasil diupdate!');
    }


}