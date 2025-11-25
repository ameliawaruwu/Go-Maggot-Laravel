<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Untuk manajemen data
use Illuminate\Support\Str; // Untuk Str::limit
use Carbon\Carbon; // Untuk mendapatkan tanggal saat ini
use App\Models\Artikel;

class ManagePublicationController extends Controller
{
    // =========================
    // TAMPIL DATA
    // =========================
    function tampil()
    {
        $articles = Artikel::orderBy('id_artikel', 'DESC')->get();
        return view('manage-publication.index', compact('articles'));
    }

    // =========================
    // KE HALAMAN INPUT
    // =========================
    function input()
    {
        return view('manage-publication.create');
    }

    // =========================
    // SIMPAN ARTIKEL
    // =========================
    function simpan(Request $req)
    {
        $req->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'konten' => 'required',
            'hak_cipta' => 'required'
        ]);

        Artikel::create([
            'id_artikel' => Str::uuid(), // <-- WAJIB karena id tidak auto
            'judul' => $req->judul,
            'penulis' => $req->penulis,
            'tanggal' => Carbon::now()->toDateString(),
            'gambar' => 'default.jpg', // <-- supaya tidak error di migration
            'konten' => $req->konten,
            'hak_cipta' => $req->hak_cipta
        ]);

        return redirect('/publication')->with('status_message', 'Artikel berhasil disimpan!');
    }

    // =========================
    // HAPUS
    // =========================
    function hapus($id)
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->delete();

        return redirect('/publication')->with('status_message', 'Artikel berhasil dihapus!');
    }

    // =========================
    // KE HALAMAN EDIT
    // =========================
    function edit($id)
    {
        $article = Artikel::findOrFail($id);
        return view('manage-publication.edit', compact('article'));
    }

    // =========================
    // UPDATE
    // =========================
 public function update(Request $req, $id)
{
    $artikel = Artikel::findOrFail($id);

    $artikel->update([
        'judul' => $req->judul,
        'penulis' => $req->penulis,
        'konten' => $req->konten,
        'hak_cipta' => $req->hak_cipta,
    ]);

    return redirect()->route('publication.index')
        ->with('success', 'Artikel berhasil diupdate!');
}


}