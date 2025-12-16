<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel; 

class ArticleController extends Controller
{

    public function show($id_artikel) 
    {
        $article = Artikel::where('id_artikel', $id_artikel)->firstOrFail();         
        return view('study.article-detail', compact('article'));
    }

    public function index()
    {
        $articles = Artikel::orderBy('id_artikel', 'ASC')->get();
        return view('study.article-list', compact('articles'));
    }
}