<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel; // Import Model Artikel

class StudyController extends Controller
{
    /**
     * Data Dummy untuk Topik Pelajaran (dibiarkan tetap menggunakan array dummy)
     * Karena data ini terlihat seperti konten statis FAQ/Belajar Sederhana.
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

    
    public function index()
    {
        // 1. Ambil 3 artikel terbaru dari database
        $latestArticles = Artikel::orderBy('tanggal', 'DESC')
                                 ->limit(3) // Batasi hanya 3 artikel
                                 ->get();

        // 2. Kirim data nyata (articles) dan data dummy (topics) ke view
        return view('study.index', [
            'topics' => $this->studyTopics,
            'articles' => $latestArticles // Mengirim data dari database
        ]);
    }

    // Fungsi show yang sekarang TIDAK digunakan karena Anda memiliki ArticleController.
    // Jika Anda ingin menggunakannya untuk menampilkan detail, pastikan route mengarah ke ArticleController.
    public function show($id_artikel) 
    {
        // Peringatan: Fungsi ini seharusnya berada di ArticleController, 
        // tapi kita sesuaikan agar mencari artikel berdasarkan id_artikel jika digunakan.
        $article = Artikel::where('id_artikel', $id_artikel)->firstOrFail();
        return view('study.article-detail', compact('article'));
    }

    // Fungsi gallery() tidak relevan dengan database, jadi kita biarkan saja
    public function gallery()
    {
        return redirect()->route('gallery.gallery')->with('message', 'Anda dialihkan ke halaman Ayo Belajar!');
    }
}