<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PenggunaApiController extends Controller
{
    /**
     * Menampilkan daftar pengguna
     */
    public function index()
    {
        $pengguna = Pengguna::all();

        $data = $pengguna->map(function ($item) {
            $item->foto_profil_url = $item->foto_profil ? asset('photo/' . $item->foto_profil) : null;
            return $item;
        });

        return response()->json([
            'message' => 'Daftar semua pengguna',
            'data' => $data
        ]);
    }

    /**
     * Menyimpan pengguna baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'required|string|max:50|unique:pengguna,id_pengguna',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:pengguna,email',
            'password' => 'required|string|min:6',
            'nomor_telepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'nullable|string|max:50',
            'tanggal_daftar' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $namaFile = null;
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFile);
        }

        $pengguna = Pengguna::create([
            'id_pengguna' => $request->id_pengguna,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'foto_profil' => $namaFile,
            'tanggal_daftar' => $request->tanggal_daftar ?: now(),
            'role' => $request->role,
        ]);

        $pengguna->foto_profil_url = $pengguna->foto_profil ? asset('photo/' . $pengguna->foto_profil) : null;

        return response()->json([
            'message' => 'Pengguna berhasil ditambahkan',
            'data' => $pengguna
        ], 201);
    }

    /**
     * Menampilkan detail pengguna
     */
    public function show($id_pengguna)
    {
        $pengguna = Pengguna::find($id_pengguna);

        if (!$pengguna) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        $pengguna->foto_profil_url = $pengguna->foto_profil ? asset('photo/' . $pengguna->foto_profil) : null;

        return response()->json([
            'message' => 'Detail pengguna',
            'data' => $pengguna
        ]);
    }

    /**
     * Memperbarui data pengguna
     */
    public function update(Request $request, $id_pengguna)
    {
        $pengguna = Pengguna::find($id_pengguna);

        if (!$pengguna) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }
        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'sometimes|required|string|max:50|unique:pengguna,id_pengguna,' . $id_pengguna . ',id_pengguna',
            'username' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:pengguna,email,' . $id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:6',
            'nomor_telepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'nullable|string|max:50',
            'tanggal_daftar' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

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

        $dataUpdate = [];
        $fields = ['id_pengguna','username','email','nomor_telepon','alamat','tanggal_daftar','role'];
        foreach ($fields as $f) {
            if ($request->has($f)) {
                $dataUpdate[$f] = $request->input($f);
            }
        }
        $dataUpdate['foto_profil'] = $namaFile;

        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        $pengguna->update($dataUpdate);

        $updated = Pengguna::find($id_pengguna);
        $updated->foto_profil_url = $updated->foto_profil ? asset('photo/' . $updated->foto_profil) : null;

        return response()->json([
            'message' => 'Pengguna berhasil diperbarui',
            'data' => $updated
        ]);
    }

    /**
     * Menghapus pengguna
     */
    public function destroy($id_pengguna)
    {
        $pengguna = Pengguna::find($id_pengguna);

        if (!$pengguna) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        if ($pengguna->foto_profil) {
            $path = public_path('photo/' . $pengguna->foto_profil);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $pengguna->delete();

        return response()->json(['message' => 'Pengguna berhasil dihapus']);
    }
}
