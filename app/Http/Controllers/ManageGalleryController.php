<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ManageGalleryController extends Controller
{
  
    private function generateNewIdGaleri(): string
    {
        $latestGaleri = Galeri::where('id_galeri', 'like', 'GL%')
            ->orderBy('id_galeri', 'desc')
            ->first();

        $lastNumber = 0;
        if ($latestGaleri) {
            $lastNumber = (int) substr($latestGaleri->id_galeri, 3);
        }

        return 'GL' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

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
            'keterangan' => 'required|string|max:255',
            'gambar'     => 'required|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);


        $idGaleriBaru = $this->generateNewIdGaleri();

        $namaFile = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        Galeri::create([
            'id_galeri'  => $idGaleriBaru,
            'keterangan' => $request->keterangan,
            'gambar'     => $namaFile,
        ]);

        return redirect()->route('gallery.index')
            ->with('status_message', 'Galeri berhasil ditambahkan!');
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
            'keterangan' => 'required|string|max:255',
            'gambar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $namaFile = $galeri->gambar;

        if ($request->hasFile('gambar')) {
            if ($galeri->gambar) {
                $oldPath = public_path('photo/' . $galeri->gambar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $galeri->update([
            'keterangan' => $request->keterangan,
            'gambar'     => $namaFile,
        ]);

        return redirect()->route('gallery.index')
            ->with('status_message', 'Galeri berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);

        if ($galeri->gambar) {
            $path = public_path('photo/' . $galeri->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $galeri->delete();

        return redirect()->route('gallery.index')
            ->with('status_message', 'Galeri berhasil dihapus!');
    }
}
