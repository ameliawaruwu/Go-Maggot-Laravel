<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use App\Models\Galeri; 
use App\Models\Artikel; 

class GalleryController extends Controller
{
    /**
     * 
     */
    public function index()
    {
        // Mengambil semua data dari tabel galeri
        $galeriData = Galeri::all();
        
        $galleryItems = $galeriData->map(function ($item) {
            $linkTarget = $item->id_artikel 
            ? route('article.show', ['id_artikel' => $item->id_artikel])
            : '#';
            
            return [
                'name'        => $item->keterangan, 
                'description' => $item->keterangan, 
                'imageUrl'    => asset('photo/' . $item->gambar), // file di public/photo
                'link'        => $linkTarget,
            ];
        });

        return view('gallery.gallery', compact('galleryItems'));
    }

    /**
     * 
     */
    public function showArtikel($id_artikel) 
    {
        $articleData = Artikel::where('id_artikel', $id_artikel)->first();

        if (!$articleData) {
            abort(404, 'Artikel tidak ditemukan.'); 
        }

        $article = [
            'judul'     => $articleData->judul,
            'penulis'   => $articleData->penulis,
            'tanggal'   => $articleData->tanggal,
            'hak_cipta' => $articleData->hak_cipta ?? 'Hak cipta dilindungi.',
            'konten'    => $articleData->konten, 
        ];

        return view('study.article-detail', compact('article'));
    }
}
