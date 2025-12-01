<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ManageGalleryController extends Controller
{
    public function index()
    {
        $galleries = Galeri::all();
        return view('manage-gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('manage-gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_galeri'   => 'required|string|max:50|unique:galeri,id_galeri',
            'keterangan'  => 'required|string|max:255',
            'gambar'      => 'required|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $namaFile = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile); // ← DI SINI
        }

        Galeri::create([
            'id_galeri'  => $request->id_galeri,
            'keterangan' => $request->keterangan,
            'gambar'     => $namaFile,
        ]);

        return redirect()->route('gallery.index')->with('status_message', 'Galeri berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $galeri = Galeri::findOrFail($id);
        return view('manage-gallery.edit', compact('galeri'));
    }

    public function update(Request $request, $id)
    {
        $galeri = Galeri::findOrFail($id);

        $request->validate([
            'id_galeri'   => 'required|string|max:50|unique:galeri,id_galeri,' . $galeri->id_galeri . ',id_galeri',
            'keterangan'  => 'required|string|max:255',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $namaFile = $galeri->gambar;

        if ($request->hasFile('gambar')) {

            // Hapus file lama
            if ($galeri->gambar) {
                $oldPath = public_path('photo/' . $galeri->gambar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile); // ← DI SINI
        }

        $galeri->update([
            'id_galeri'  => $request->id_galeri,
            'keterangan' => $request->keterangan,
            'gambar'     => $namaFile,
        ]);

        return redirect()->route('gallery.index')->with('status_message', 'Galeri berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);

        // hapus file di /public/photo
        if ($galeri->gambar) {
            $path = public_path('photo/' . $galeri->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $galeri->delete();

        return redirect()->route('gallery.index')->with('status_message', 'Galeri berhasil dihapus!');
    }
}
