<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * REGISTER
     */
    public function register(Request $request) 
    { 
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255', // Input dari postman 'name'
            'email'    => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Generate ID Pengguna Otomatis (PG + Angka Acak)
        // Karena di database kamu ID tidak auto-increment
        $randomId = rand(100, 999);
        $idPengguna = 'PG' . str_pad($randomId, 3, '0', STR_PAD_LEFT);

        // 3. Simpan ke Database
        $pengguna = Pengguna::create([ 
            'id_pengguna' => $idPengguna, // <--- WAJIB ADA
            'username'    => $request->name, // Mapping: input 'name' ke kolom 'username'
            'email'       => $request->email, 
            'password'    => Hash::make($request->password), 
            'role'        => $request->role ?? 'pelanggan',
            'tanggal_daftar' => now(),
        ]); 

        return response()->json([ 
            'status' => 'success', 
            'message' => 'User berhasil dibuat', 
            'user' => $pengguna
        ], 201); 
    } 

    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        // Validasi
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        // Cek User & Password
        if (! $pengguna || ! Hash::check($request->password, $pengguna->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Login gagal, email atau password salah'
            ], 401);
        }

        // Buat Token Sanctum
        $token = $pengguna->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => $pengguna,
        ]);
    }

    /**
     * PROFILE (Hanya jika login)
     */
    public function profile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user'   => $request->user(),
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout berhasil',
        ]);
    }
}