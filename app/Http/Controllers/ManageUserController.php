<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Pengguna;

class ManageUserController extends Controller
{
    function index(){
        $pengguna = Pengguna::all();
        return view('manage-user.index', compact('pengguna'));
    }

    function input(){
        $pengguna = Pengguna::all();
        return view('manage-user.create', compact('pengguna'));
    }

    function simpan( Request $a){
        $namaFile =  null;
        if($a->hasFile('foto_profil')){
            $file = $a->file('foto_profil'); // nangkap inputan foto
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile); // file di upload ke folder photo
        }

        Pengguna::create(
            [
            "id_pengguna"=> $a->id_pengguna,
            "username"=> $a->username,
            "email"=> $a->email,
            "password"=> $a->password,
            "nomor_telepon"=> $a->nomor_telepon,
            "alamat"=> $a->alamat,
            "foto_profil"=> $namaFile,
            ]
        );
        return redirect('/manageUser');
    }


    // edit
    function edit($id_pengguna){
        $pengguna = Pengguna::find($id_pengguna);
        return view('manage-user.edit', compact('pengguna'));
    }

    // simpan edit
    function update( Request $x, $id_pengguna){
        $pengguna = Pengguna::findOrFail($id_pengguna);
        $namaFile = $pengguna->foto_profil;
        if($x->hasFile('foto_profil')){
            if($pengguna->foto_profil) {
                $oldPath = public_path('photo/' . $pengguna->foto_profil);
                if (File::exists($oldPath)){
                    File::delete($oldPath);
                }
            }
            $file = $x->file('foto_profil');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);

        }
        Pengguna::where("id_pengguna", "$x->id_pengguna")->update(
            [
                'id_pengguna'=> $x->id_pengguna,
                'username'=> $x->username,
                'email'=> $x->email,
                'password'=> $x->password,
                'nomor_telepon'=> $x-> nomor_telepon,
                'alamat'=> $x->alamat,
                'foto_profil'=> $namaFile

            ]
            );
        return redirect('/manageUser');
    }

    // delete 
    function delete($id_pengguna){
        $pengguna = Pengguna::findOrFail($id_pengguna);
        if ($pengguna->foto_profil){
            $path = public_path('photo/' . $pengguna->foto_profil);
            if (File::exists($path)) {
                File::delete($path);
            }
        }


        $pengguna->delete();
        return redirect('/manageUser');
    }
}