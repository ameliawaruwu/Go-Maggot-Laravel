<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel; // Import Model Artikel

class ArticleController extends Controller
{
    // Mengambil artikel berdasarkan id_artikel (atau sesuaikan dengan field yang menjadi slug/identifier di route Anda)
    public function show($id_artikel) // Anggap route menggunakan id_artikel sebagai identifier
    {
        // Cari artikel berdasarkan id_artikel (UUID)
        // Menggunakan firstOrFail() akan otomatis melempar 404 jika tidak ditemukan
        $article = Artikel::where('id_artikel', $id_artikel)->firstOrFail(); 

        // Tidak perlu lagi blok if (!$article) { abort(404, 'Artikel tidak ditemukan.'); }
        
        return view('study.article-detail', compact('article'));
    }

    // Fungsi untuk menampilkan daftar artikel (jika ada halaman indeks artikel)
    public function index()
    {
        $articles = Artikel::orderBy('tanggal', 'DESC')->get();
        return view('study.article-list', compact('articles'));
    }
}