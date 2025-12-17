<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

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

    function simpan(Request $request)
    {
        $validated = $request->validate([
            'id_pengguna' => 'required|numeric|unique:pengguna,id_pengguna',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|min:6',
            'nomor_telepon' => 'required|numeric',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $namaFile = null;
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $validated['foto_profil'] = $namaFile;

        Pengguna::create($validated);

        return redirect('/manageUser');
    }


    // edit
    function edit($id_pengguna){
        $pengguna = Pengguna::findOrFail($id_pengguna);
        return view('manage-user.edit', compact('pengguna'));
    }

    // simpan edit
    function update(Request $request, $id_pengguna)
    {
        $pengguna = Pengguna::findOrFail($id_pengguna);

        $validated = $request->validate([
            'id_pengguna' => 'required|numeric|unique:pengguna,id_pengguna,' . $id_pengguna . ',id_pengguna',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $id_pengguna . ',id_pengguna',
            'password' => 'required|min:6',
            'nomor_telepon' => 'required|numeric',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $namaFile = $pengguna->foto_profil;
        if ($request->hasFile('foto_profil')) {
            if ($pengguna->foto_profil) {
                $oldPath = public_path('photo/' . $pengguna->foto_profil);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('foto_profil');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $validated['foto_profil'] = $namaFile;

        $pengguna->update($validated);

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