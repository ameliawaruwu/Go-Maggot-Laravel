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
        if($a->hasFile('foto')){
            $file = $a->file('foto'); // nangkap inputan foto
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile); // file di upload ke folder uploads
        }

        Produk::create(
            [
            ""=> $a->nim,
            "nama"=> $a->nama,
            "email"=> $a->email,
            "id_prodi"=> $a->id_prodi,
            "foto"=> $namaFile
            ]
        );
        return redirect('/mhs');
    }


    // edit
    function edit($nim){
        $prodi = Prodi::all();
        $mahasiswa = Mahasiswa::find($nim);
        return view('mahasiswa.edit', compact('mahasiswa', 'prodi'));
    }

    // simpan edit
    function update( Request $x, $nim){
        $mahasiswa = Mahasiswa::findOrFail($nim);
        $namaFile = $mahasiswa->foto;
        // Jika ada file baru, hapus file lama, dan simpan file baru
        if($x->hasFile('foto')){
            if($mahasiswa->foto) {
                $oldPath = public_path('photo/' . $mahasiswa->foto);
                if (File::exists($oldPath)){
                    File::delete($oldPath);
                }
            }

            $file = $x->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);

        }
        Mahasiswa::where("nim", "$x->nim")->update(
            [
                'nim'=> $x->nim,
                'nama'=> $x->nama,
                'email'=> $x->email,
                'id_prodi'=> $x->id_prodi,
                'foto'=> $namaFile

            ]
            );
        return redirect('/mhs');
    }

    // delete 
    function delete($nim){
        $mahasiswa = Mahasiswa::findOrFail($nim);
        if ($mahasiswa->foto){
            $path = public_path('photo' . $mahasiswa->foto);
            if (File::exists($path)) {
                File::delete($path);
            }
        }


        $mahasiswa->delete();
        return redirect('/mhs');
    }


    
    
}