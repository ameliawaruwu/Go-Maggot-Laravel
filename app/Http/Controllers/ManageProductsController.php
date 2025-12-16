<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File; 

class ManageProductsController extends Controller
{
    function index(){
        $produk = Produk::all();
        return view('manage-products.index', compact('produk'));
    }

    function input(){
        $produk = Produk::all();
        return view('manage-products.create', compact('produk'));
    }

    function simpan(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|string|max:50|unique:produk,id_produk',
            'nama_produk' => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'masa_penyimpanan' => 'nullable|string|max:50',
            'pengiriman' => 'nullable|string|max:50',
            'berat' => 'nullable|numeric',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $namaFile = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $validated['gambar'] = $namaFile;

        Produk::create($validated);

        return redirect('/manageProduk');
    }


    // edit
    function edit($id_produk){
        $produk = Produk::find($id_produk);
        return view('manage-products.edit', compact('produk'));
    }

    // simpan edit
    function update(Request $request, $id_produk)
    {
        $produk = Produk::findOrFail($id_produk);

        $validated = $request->validate([
            'id_produk' => 'required|string|max:50|unique:produk,id_produk,' . $id_produk . ',id_produk',
            'nama_produk' => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'masa_penyimpanan' => 'nullable|string|max:50',
            'pengiriman' => 'nullable|string|max:50',
            'berat' => 'nullable|numeric',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $namaFile = $produk->gambar;
        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                $oldPath = public_path('photo/' . $produk->gambar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $validated['gambar'] = $namaFile;

        $produk->update($validated);

        return redirect('/manageProduk');
    }

    // delete 
    function delete($id_produk){
        $produk = Produk::findOrFail($id_produk);
        if ($produk->gambar){
            $path = public_path('photo/' . $produk->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        $produk->delete();
        return redirect('/manageProduk');
    }   
}