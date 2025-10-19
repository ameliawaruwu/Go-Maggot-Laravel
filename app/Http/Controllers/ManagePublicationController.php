<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Untuk manajemen data
use Illuminate\Support\Str; // Untuk Str::limit
use Carbon\Carbon; // Untuk mendapatkan tanggal saat ini

class ManagePublicationController extends Controller
{
    
    private $initialArticles = [
        ['id_artikel' => 1, 'judul' => 'MELESTARIKAN MAGGOT BSF DENGAN MELAKUKAN PEMBUDIDAYAAN', 'penulis' => 'GoMaggot', 'tanggal' => '2024-11-27', 'konten' => '<div class="artikel-deskripsi artikel-box artikel-clearfix"> <img src="../Admin-HTML/images/maggot-fresh.jpg" /> Isi lengkap artikel 1 tentang pembudidayaan maggot...', 'hak_cipta' => 'Copyright © 2024 GoMaggot'],
        ['id_artikel' => 2, 'judul' => 'MENGENAL LEBIH DALAM MAGGOT BSF', 'penulis' => 'GoMaggot', 'tanggal' => '2024-11-27', 'konten' => '<div class="artikel-deskripsi artikel-box artikel-clearfix"> <img src="../Admin-HTML/images/pupuk-kompos.jpg" /> Konten artikel 2. Maggot BSF adalah larva lalat hitam...', 'hak_cipta' => 'Copyright © 2024 GoMaggot'],
        ['id_artikel' => 3, 'judul' => 'MEMAHAMI LEBIH DALAM MAGGOT BSF', 'penulis' => 'GoMaggot', 'tanggal' => '2024-11-17', 'konten' => '<div class="artikel-deskripsi artikel-box artikel-clearfix"> <img src="../Admin-HTML/images/pelet-maggot.jpg" /> Artikel 3 membahas daur hidup dan manfaat maggot BSF...', 'hak_cipta' => 'Copyright © 2024 GoMaggot'],
    ];

    
    private function getArticles()
    {
        if (!Session::has('articles')) {
            Session::put('articles', $this->initialArticles);
        }
        return Session::get('articles');
    }

    
    private function saveArticles($articles)
    {
        Session::put('articles', array_values($articles));
    }
    
  
    private function getNextId($articles)
    {
        if (empty($articles)) {
            return 1;
        }
        return max(array_column($articles, 'id_artikel')) + 1;
    }

    public function index(Request $request)
    {
        $articles = $this->getArticles();
        $searchQuery = $request->query('search_query');
        
        // Logika Pencarian
        if ($searchQuery) {
            $articles = array_filter($articles, function($article) use ($searchQuery) {
                $searchQueryLower = strtolower($searchQuery);
                return (
                    str_contains(strtolower($article['judul']), $searchQueryLower) ||
                    str_contains(strtolower($article['penulis']), $searchQueryLower) ||
                    str_contains(strtolower($article['konten']), $searchQueryLower) ||
                    str_contains(strtolower($article['hak_cipta']), $searchQueryLower)
                );
            });
            // Pastikan array key tetap berurutan setelah filter
            $articles = array_values($articles);
        }

        // Mengirimkan data ke view manage-publication.index
        return view('manage-publication.index', compact('articles', 'searchQuery'));
    }

   
    public function create()
    {
        return view('manage-publication.create');
    }

    /**
     * Menyimpan artikel baru ke Session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'konten' => 'required',
            'hak_cipta' => 'required|string|max:255',
        ]);

        $articles = $this->getArticles();
        $newId = $this->getNextId($articles);
        
        $newArticle = [
            'id_artikel' => $newId,
            'judul' => $validated['judul'],
            'penulis' => $validated['penulis'],
            'tanggal' => Carbon::now()->toDateString(), 
            // Konten di-sanitize ringan (opsional)
            'konten' => $validated['konten'], 
            'hak_cipta' => $validated['hak_cipta'],
        ];

        $articles[] = $newArticle;
        $this->saveArticles($articles);

        return redirect()->route('publication.index')->with('status_message', 'Artikel **' . $validated['judul'] . '** berhasil ditambahkan!');
    }

   
    public function edit($id)
    {
        $id = (int) $id;
        $articles = $this->getArticles();
        
        // Mencari artikel berdasarkan id_artikel
        $article = collect($articles)->firstWhere('id_artikel', $id);

        if (!$article) {
            return redirect()->route('publication.index')->withErrors('Artikel tidak ditemukan.');
        }

        return view('manage-publication.edit', compact('article'));
    }

    
    public function update(Request $request, $id)
    {
        $id = (int) $id;
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'konten' => 'required',
            'hak_cipta' => 'required|string|max:255',
        ]);

        $articles = $this->getArticles();
        $indexToUpdate = -1;

        // Cari index array yang memiliki id_artikel yang cocok
        foreach ($articles as $index => $a) {
            if ($a['id_artikel'] === $id) {
                $indexToUpdate = $index;
                break;
            }
        }

        if ($indexToUpdate === -1) {
            return redirect()->route('publication.index')->withErrors('Artikel yang akan diperbarui tidak ditemukan.');
        }
        
        // Perbarui data
        $articles[$indexToUpdate]['judul'] = $validated['judul'];
        $articles[$indexToUpdate]['penulis'] = $validated['penulis'];
        // Tanggal dan konten tidak diubah saat update di sini, hanya konten
        $articles[$indexToUpdate]['konten'] = $validated['konten']; 
        $articles[$indexToUpdate]['hak_cipta'] = $validated['hak_cipta'];

        $this->saveArticles($articles); // Simpan kembali ke Session

        return redirect()->route('publication.index')->with('status_message', 'Artikel **' . $validated['judul'] . '** berhasil diperbarui!');
    }

    
    public function destroy($id)
    {
        $id = (int) $id;
        $articles = $this->getArticles();
        
        $initialCount = count($articles);
        
        // Simpan judul artikel sebelum dihapus untuk notifikasi
        $articleToDelete = collect($articles)->firstWhere('id_artikel', $id);
        $articleTitle = $articleToDelete ? $articleToDelete['judul'] : 'ID ' . $id;

        // Filter array untuk menghapus item yang cocok
        $articles = array_filter($articles, fn($a) => $a['id_artikel'] !== $id);
        $finalCount = count($articles);
        
        if ($initialCount === $finalCount) {
            return redirect()->route('publication.index')->withErrors('Artikel yang akan dihapus tidak ditemukan.');
        }

        // Simpan array baru dengan kunci yang sudah direset ke Session
        $this->saveArticles($articles); 
        
        return redirect()->route('publication.index')->with('status_message', 'Artikel **' . $articleTitle . '** berhasil dihapus!');
    }
}