<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel; 

class ArticleController extends Controller
{
    // Artikel berdasarkan id_artikel
    public function show($id_artikel) 
    {
        $article = Artikel::where('id_artikel', $id_artikel)->firstOrFail();         
        return view('study.article-detail', compact('article'));
    }

    // Fungsi untuk menampilkan daftar artikel 
    public function index()
    {
        $articles = Artikel::orderBy('tanggal', 'DESC')->get();
        return view('study.article-list', compact('articles'));
    }
}