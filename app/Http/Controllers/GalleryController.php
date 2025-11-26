<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
// Import Model yang baru Anda sediakan
use App\Models\Galeri; 
use App\Models\Artikel; 

class GalleryController extends Controller
{
    /**
     * Menampilkan halaman galeri dengan data dari database (tabel 'galeri').
     */
    public function index()
    {
        // Mengambil semua data dari tabel 'galeri'
        $galeriData = Galeri::all();
        
        // Memetakan data dari Model ke format yang digunakan di view (jika perlu)
        // Kita asumsikan kolom 'gambar' di DB menyimpan nama file gambar.
        $galleryItems = $galeriData->map(function ($item) {
            
            // Asumsi: Anda ingin link setiap item galeri mengarah ke artikel detail.
            // Karena tabel 'galeri' tidak memiliki kolom 'slug' atau id_artikel, 
            // kita menggunakan link dummy/default untuk menghindari error.
            // Anda HARUS menyesuaikan 'artikelsatu' dengan ID artikel yang benar 
            // atau menambahkan kolom id_artikel/slug ke tabel galeri.
            $defaultArticleId = 'ART-2024001'; 
            
            return [
                'name' => $item->keterangan, 
                'description' => $item->keterangan, 
                // Asumsi file gambar disimpan di folder 'images/galeri/'
                "imageUrl" => asset('images/galeri/' . $item->gambar), 
                // Menggunakan ID/Slug statis untuk sementara, ganti sesuai kebutuhan
                'link' => route('article.show', ['slug' => $defaultArticleId]),
            ];
        });

        // Mengirim data dari database ke view 'gallery'
        return view('gallery.gallery', compact('galleryItems'));
    }

    /**
     * Fungsi untuk menampilkan detail artikel dari database (tabel 'artikel').
     * Slug yang disalurkan akan dianggap sebagai 'id_artikel'.
     */
    public function showArtikel($id_artikel) // Mengubah nama variabel dari $slug menjadi $id_artikel agar lebih jelas
    {
        // Mencari artikel berdasarkan id_artikel
        $articleData = Artikel::where('id_artikel', $id_artikel)->first();

        // Pengecekan jika artikel tidak ditemukan
        if (!$articleData) {
            abort(404, 'Artikel tidak ditemukan.'); 
        }

        // Memetakan data dari Model ke format yang dibutuhkan view
        $article = [
            'judul' => $articleData->judul,
            'penulis' => $articleData->penulis,
            'tanggal' => $articleData->tanggal,
            'hak_cipta' => $articleData->hak_cipta ?? 'Hak cipta dilindungi.',
            // Konten artikel diambil langsung dari kolom 'konten' di DB
            'konten' => $articleData->konten, 
        ];

        // Mengirim data artikel ke view 'study.article-detail'
        return view('study.article-detail', compact('article'));
    }
}