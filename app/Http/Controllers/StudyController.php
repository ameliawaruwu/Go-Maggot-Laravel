<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudyController extends Controller
{
    /**
     * Data Dummy untuk Topik Pelajaran 
     * 
     */
    private $studyTopics = [
        [
            'summary' => 'Mengenal Lebih Jauh Apa itu Maggot BSF',
            'details' => 'Maggot merupakan larva dari jenis lalat Black Soldier Fly (BSF) sehingga sering disebut maggot BSF. Lalat BSF sendiri memiliki nama latin Heremetia illucens. Bentuknya mirip ulat, dengan ukuran larva dewasa 15-22 mm dan berwarna coklat. Siklus hidup lalat BSF kurang lebih selama 40-43 hari. Larva/maggot BSF bertahan selama 14-18 hari sebelum bermetamorfosis menjadi pupa dan lalat dewasa.'
        ],
        [
            'summary' => 'Mengetahui Manfaat Budidaya Maggot BSF',
            'details' => 'Pengelola Sampah Organik, Pakan Ternak, Pupuk Organik.'
        ],
        [
            'summary' => 'Melakukan Pelestarian Maggot BSF dengan Pembudidayaan',
            'details' => 'Proses budidaya maggot dimulai dengan pemilihan telur yang berkualitas. Telur-telur tersebut kemudian ditempatkan di dalam kandang yang telah disiapkan. Setelah menetas, larva maggot diberi pakan berupa limbah organik seperti sisa sayuran dan buah-buahan.'
        ],
    ];

    /**
     * Data Dummy untuk Galeri Artikel
     * 
     */
    private $articleGallery = [
        [
            'title' => 'Mengenal Lebih Dalam Maggot BSF',
            'image' => 'maggot.jpg',
            'link_slug' => 'artikeltiga' 
        ],
        [
            'title' => 'Manfaat Maggot Dalam Segi Pakan Ternak',
            'image' => 'maggot kompos.jpg',
            'link_slug' => 'artikeldua' 
        ],
        [
            'title' => 'Melakukan Budidaya Maggot Sederhana',
            'image' => 'ternak maggot.jpeg',
            'link_slug' => 'artikelsatu' 
        ],
       
    ];

   
    
    public function index()
    {
        // Kirim data dummy ke view
        return view('study.index', [
            'topics' => $this->studyTopics,
            'articles' => $this->articleGallery
        ]);
    }

    public function gallery()
    {
        return redirect()->route('gallery.gallery')->with('message', 'Anda dialihkan ke halaman Ayo Belajar!');
    }

    public function show($slug)
{
    // cari artikel berdasarkan slug
    $article = collect($this->articleGallery)->firstWhere('link_slug', $slug);

    if (!$article) {
        abort(404, 'Artikel tidak ditemukan');
    }

    return view('study.detail', compact('article'));
}

}