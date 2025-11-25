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

    function simpan( Request $a){
        // cara upload foto
        $namaFile =  null;
        if($a->hasFile('gambar')){
            $file = $a->file('gambar'); // nangkap inputan foto
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile); // file di upload ke folder uploads
        }

        Produk::create(
            [
            "id_produk"=> $a->id_produk,
            "nama_produk"=> $a->nama_produk,
            "deskripsi_produk"=> $a->deskripsi_produk,
            "kategori"=> $a->kategori,
            "merk"=> $a->merk,
            "masa_penyimpanan"=> $a->masa_penyimpanan,
            "pengiriman"=> $a->pengiriman,
            "berat"=> $a->berat,
            "harga"=> $a->harga,
            "stok"=> $a->stok,
            "gambar"=> $namaFile
            ]
        );
        return redirect('/manageProduk');
    }


    // edit
    function edit($id_produk){
        $produk = Produk::find($id_produk);
        return view('manage-products.edit', compact('produk'));
    }

    // simpan edit
    function update( Request $x, $id_produk){
        $produk = Produk::findOrFail($id_produk);
        $namaFile = $produk->gambar;
        if($x->hasFile('gambar')){
            if($produk->gambar) {
                $oldPath = public_path('photo/' . $produk->gambar);
                if (File::exists($oldPath)){
                    File::delete($oldPath);
                }
            }
            $file = $x->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);

        }
        Produk::where("id_produk", "$x->id_produk")->update(
            [
                'id_produk'=> $x->id_produk,
                'nama_produk'=> $x->nama_produk,
                'deskripsi_produk'=> $x->deskripsi_produk,
                'kategori'=> $x->kategori,
                'merk'=> $x-> merk,
                'masa_penyimpanan'=> $x->masa_penyimpanan,
                'pengiriman'=> $x->pengiriman,
                'berat'=> $x->berat,
                'harga'=> $x->harga,
                'stok'=> $x->stok,
                'gambar'=> $namaFile

            ]
            );
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