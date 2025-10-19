<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str; // Tambahkan ini untuk Str::limit jika digunakan

class ManageGalleryController extends Controller
{
    // Data galeri awal (Default) - Menggunakan file dari public/images
    private $initialGalleries = [
        ['id_galeri' => 1, 'gambar' => 'maggot-fresh.jpg', 'keterangan' => 'Panen Maggot BSF di kolam.','tanggal' => '2025-10-10'],
        ['id_galeri' => 2, 'gambar' => 'pupuk-kompos.jpg', 'keterangan' => 'Pengolahan Kompos Organik.', 'tanggal' => '2025-10-15'],
        ['id_galeri' => 3, 'gambar' => 'pelet-maggot.jpg', 'keterangan' => 'Proses pengeringan maggot.', 'tanggal' => '2025-09-28'],
    ];

    private function getGalleries()
    {
        if (!Session::has('galleries')) {
            Session::put('galleries', $this->initialGalleries);
        }
        return Session::get('galleries');
    }

    private function saveGalleries($galleries)
    {
        // Menyimpan array yang sudah dimodifikasi kembali ke Session
        Session::put('galleries', $galleries);
    }
    
    private function getNextId($galleries)
    {
        $maxId = 0;
        foreach ($galleries as $galeri) {
            if ($galeri['id_galeri'] > $maxId) {
                $maxId = $galeri['id_galeri'];
            }
        }
        return $maxId + 1;
    }
    
    private function getAvailableImages()
    {
        $imagesPath = public_path('images');
        $imageFiles = [];

        if (File::isDirectory($imagesPath)) {
            $files = File::files($imagesPath); 
            foreach ($files as $file) {
                $imageFiles[] = $file->getFilename(); 
            }
        }
        
        if (!in_array('default-product.jpg', $imageFiles)) {
             $imageFiles[] = 'default-product.jpg';
        }

        sort($imageFiles); 
        return array_unique($imageFiles);
    }


    public function index()
    {
        $galleries = $this->getGalleries();
        return view('manage-gallery.index', compact('galleries'));
    }

    public function create()
    {
        $availableImages = $this->getAvailableImages();
        return view('manage-gallery.create', compact('availableImages'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'keterangan' => 'required|string|max:255',
            'gambar' => 'required|string|max:255', 
        ]);

        $galleries = $this->getGalleries();
        $newId = $this->getNextId($galleries);
        $currentDate = now()->toDateString(); 

        $newGaleri = [
            'id_galeri' => $newId,
            'gambar' => $validated['gambar'],
            'keterangan' => $validated['keterangan'],
            'tanggal' => $currentDate,
        ];

        $galleries[] = $newGaleri;
        $this->saveGalleries($galleries);

        return redirect()->route('gallery.index')->with('status_message', 'Galeri **' . $newId . '** berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $id = (int) $id;
        $galleries = $this->getGalleries();
        
        $galeri = collect($galleries)->firstWhere('id_galeri', $id);

        if (!$galeri) {
            return redirect()->route('gallery.index')->withErrors('Galeri tidak ditemukan.');
        }
        
        $availableImages = $this->getAvailableImages();

        return view('manage-gallery.edit', compact('galeri', 'availableImages'));
    }

    public function update(Request $request, $id)
    {
        $id = (int) $id;
        
        $validated = $request->validate([
            'keterangan' => 'required|string|max:255',
            'gambar' => 'required|string|max:255', 
        ]);

        $galleries = $this->getGalleries();
        $indexToUpdate = -1;

        foreach ($galleries as $index => $g) {
            if ($g['id_galeri'] === $id) {
                $indexToUpdate = $index;
                break;
            }
        }

        if ($indexToUpdate === -1) {
            return redirect()->route('gallery.index')->withErrors('Galeri yang akan diperbarui tidak ditemukan.');
        }

        $galleries[$indexToUpdate]['gambar'] = $validated['gambar'];
        $galleries[$indexToUpdate]['keterangan'] = $validated['keterangan'];

        $this->saveGalleries($galleries);

        return redirect()->route('gallery.index')->with('status_message', 'Galeri ID **' . $id . '** berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $id = (int) $id;
        $galleries = $this->getGalleries();
        
        $initialCount = count($galleries);
        
        // 1. FILTER: Hapus item dari array
        $galleries = array_filter($galleries, fn($g) => $g['id_galeri'] !== $id);
        $finalCount = count($galleries);
        
        // Cek validasi
        if ($initialCount === $finalCount) {
            return redirect()->route('gallery.index')->withErrors('Galeri yang akan dihapus tidak ditemukan.');
        }

        $this->saveGalleries(array_values($galleries)); 
        
        return redirect()->route('gallery.index')->with([
            'status_message' => 'Galeri ID **' . $id . '** berhasil dihapus!', 
            'status_type' => 'success' 
        ]);
    }
}