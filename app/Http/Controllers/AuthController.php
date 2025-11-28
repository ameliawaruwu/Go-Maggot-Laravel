<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // show login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // 1️⃣ Kalau ada query ?redirect=... (dari JS keranjang)
            if ($request->filled('redirect')) {
                return redirect()->to($request->input('redirect'));
            }

            // 2️⃣ Kalau datang dari middleware auth (url.intended)
            if (session()->has('url.intended')) {
                return redirect()->intended('/home');
            }

            // 3️⃣ Kalau admin -> dashboard
            if (Auth::user()->role === 'admin') {
                return redirect('/dashboard');
            }

            // 4️⃣ User biasa -> home
            return redirect('/home');
        }

        return back()
            ->withErrors([
                'email' => 'Akun tidak ditemukan. Silakan register terlebih dahulu.',
            ])
            ->onlyInput('email');
    }
    // ======================
    //     SHOW REGISTER
    // ======================
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // ======================
    //     REGISTER PROSES
    // ======================

public function register(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6'
    ]);

    // 1️⃣ Buat user Laravel dulu (tabel users)
    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => bcrypt($request->password),
        'role'     => 'user',
    ]);

    // 2️⃣ Buat ID Pengguna PGxxx
    $idPengguna = 'PG' . str_pad($user->id, 3, '0', STR_PAD_LEFT);

    // 3️⃣ Simpan ke tabel pengguna (PAKAI username & password, BUKAN 'nama')
    DB::table('pengguna')->insert([
        'id_pengguna'   => $idPengguna,
        'username'      => $user->name,     // ✅ kolomnya 'username'
        'email'         => $user->email,
        'password'      => $user->password, // ✅ wajib diisi karena NOT NULL
        // kolom lain (nomor_telepon, alamat, dll) boleh NULL jadi tidak wajib diisi
    ]);

    // 4️⃣ Simpan id_pengguna juga ke tabel users
    $user->update([
        'id_pengguna' => $idPengguna,
    ]);

    return redirect()
        ->route('login')
        ->with('success', 'Registrasi berhasil! Silakan login dengan akun yang baru kamu buat.');
}

    // ======================
    //        LOGOUT
    // ======================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
