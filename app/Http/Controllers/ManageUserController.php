<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Pengguna;

class ManageUserController extends Controller
{
    function index(){
        $pengguna = Pengguna::all();
        return view('manage-user.index', compact('pengguna'));
    }

    function input(){
        return view('manage-user.create');
    }

    function simpan(Request $request){
        // --- 1. GENERATE ID OTOMATIS (PG001, PG002...) ---
        $lastUser = Pengguna::orderBy('id_pengguna', 'desc')->first();
        
        if (!$lastUser) {
            $newId = 'PG001'; // Jika belum ada user, mulai dari PG001
        } else {
            $lastId = $lastUser->id_pengguna;
            // Ambil angka setelah 'PG' (index ke-2 sampai akhir)
            $number = (int) substr($lastId, 2); 
            $number++; // Tambah 1
            // Format ulang gabungan 'PG' + angka 3 digit (contoh: 1 jadi 001)
            $newId = 'PG' . sprintf("%03d", $number); 
        }

        // --- 2. UPLOAD FOTO ---
        $namaFile = null;
        if($request->hasFile('foto_profil')){
            $file = $request->file('foto_profil');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        // --- 3. SIMPAN DATA ---
        Pengguna::create([
            "id_pengguna"   => $newId, // Pakai ID Otomatis
            "username"      => $request->username,
            "email"         => $request->email,
            "password"      => $request->password, // Sebaiknya di-hash pakai bcrypt($request->password)
            "nomor_telepon" => $request->nomor_telepon,
            "alamat"        => $request->alamat,
            "role"          => $request->role, // Tambahkan ini agar role tersimpan
            "foto_profil"   => $namaFile,
        ]);

        return redirect('/manageUser')->with('success', 'User berhasil ditambahkan dengan ID: ' . $newId);
    }

    // Edit
    function edit($id_pengguna){
        $pengguna = Pengguna::where('id_pengguna', $id_pengguna)->firstOrFail();
        return view('manage-user.edit', compact('pengguna'));
    }

    // Update
    function update(Request $request, $id_pengguna){
        $pengguna = Pengguna::where('id_pengguna', $id_pengguna)->firstOrFail();
        
        $namaFile = $pengguna->foto_profil;
        
        if($request->hasFile('foto_profil')){
            if($pengguna->foto_profil) {
                $oldPath = public_path('photo/' . $pengguna->foto_profil);
                if (File::exists($oldPath)){
                    File::delete($oldPath);
                }
            }
            $file = $request->file('foto_profil');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        // Update data
        // Catatan: id_pengguna tidak perlu di-update
        $pengguna->update([
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => $request->password, // Jika user ganti password
            'nomor_telepon' => $request->nomor_telepon,
            'alamat'        => $request->alamat,
            'role'          => $request->role,
            'foto_profil'   => $namaFile
        ]);

        return redirect('/manageUser');
    }

    // Delete 
    function delete($id_pengguna){
        $pengguna = Pengguna::where('id_pengguna', $id_pengguna)->firstOrFail();
        
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