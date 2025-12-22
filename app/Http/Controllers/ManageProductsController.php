<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 

class ManageProductsController extends Controller
{
    // Menampilkan daftar produk
    function index(){
        $produk = Produk::all();
        return view('manage-products.index', compact('produk'));
    }

    // Menampilkan form tambah produk
    function input(){
        return view('manage-products.create');
    }

    // Menyimpan produk baru dengan AUTO ID (PR01, PR02...)
    function simpan(Request $request)
    {
        // --- 1. GENERATE ID OTOMATIS ---
        $lastProduct = Produk::orderBy('id_produk', 'desc')->first();
        
        if (!$lastProduct) {
            $newId = 'PR01'; // Jika database kosong, mulai dari PR01
        } else {
            $lastId = $lastProduct->id_produk;
            // Ambil angka setelah 'PR' (index ke-2 sampai akhir)
            $number = (int) substr($lastId, 2); 
            $number++; // Tambah 1
            // Format ulang gabungan 'PR' + angka 2 digit (contoh: 9 jadi 09)
            $newId = 'PR' . sprintf("%02d", $number); 
        }

        // --- 2. VALIDASI (Hapus id_produk dari sini) ---
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'masa_penyimpanan' => 'nullable|string|max:50',
            'pengiriman' => 'nullable|string|max:50',
            'berat' => 'nullable|string|max:50',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Masukkan ID otomatis ke data yang akan disimpan
        $validated['id_produk'] = $newId;

        // --- 3. UPLOAD GAMBAR ---
        $namaFile = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $validated['gambar'] = $namaFile;

        // Simpan ke Database
        Produk::create($validated);

        return redirect('/manageProduk')->with('success', 'Produk berhasil ditambahkan dengan ID: ' . $newId);
    }

    // Menampilkan halaman edit
    function edit($id_produk){
        $produk = Produk::findOrFail($id_produk);
        return view('manage-products.edit', compact('produk'));
    }

    // Update produk
    function update(Request $request, $id_produk)
    {
        $produk = Produk::findOrFail($id_produk);

        $validated = $request->validate([
            // ID Produk tetap divalidasi tapi ignore ID yg sedang diedit
            'id_produk' => 'required|string|max:50|unique:produk,id_produk,' . $id_produk . ',id_produk',
            'nama_produk' => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'kategori' => 'required|string|max:100',
            'merk' => 'nullable|string|max:100',
            'masa_penyimpanan' => 'nullable|string|max:50',
            'pengiriman' => 'nullable|string|max:50',
            'berat' => 'nullable|string|max:50',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $namaFile = $produk->gambar;
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
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

        return redirect('/manageProduk')->with('success', 'Data produk berhasil diperbarui');
    }

    // Hapus produk
    function delete($id_produk){
        $produk = Produk::findOrFail($id_produk);
        if ($produk->gambar){
            $path = public_path('photo/' . $produk->gambar);
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        $produk->delete();
        return redirect('/manageProduk')->with('success', 'Produk berhasil dihapus');
    }   
}