<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Cek remember me
        $remember = $request->boolean('remember');

        // cek email & password di tabel users
        if (Auth::attempt($credentials, $remember)) {

            // Regenerasi session ID (keamanan)
            $request->session()->regenerate();

            if ($request->filled('redirect')) {
                return redirect()->to($request->input('redirect'));// URL redirect dikirim oleh user melalui request.
            }

            // Redirect ke halaman yang sebelumnya ingin diakses
            if (session()->has('url.intended')) {
                return redirect()->intended('/home');
            }

            // Jika admin -> ke dashboard admin
            if (Auth::user()->role === 'admin') {
                return redirect('/dashboard');
            }

            // Jika user biasa -> ke home
            return redirect('/home');
        }

        // Jika login gagal
        return back()
            ->withErrors([
                'email' => 'Akun tidak ditemukan. Silakan register terlebih dahulu.',
            ])
            ->onlyInput('email');
    }

    // Tampilkan halaman register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        // Validasi form register
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        // Ambil id_pengguna terakhir dari tabel pengguna
        $lastPengguna = DB::table('pengguna')
                        ->orderBy('id_pengguna', 'desc')
                        ->first();

        // Hitung nomor berikutnya untuk PGxxx
        if ($lastPengguna) {
            $lastNumber = (int) substr($lastPengguna->id_pengguna, 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Format ID pengguna baru, contoh: PG001
        $idPengguna = 'PG' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Simpan ke tabel users (tabel autentikasi)
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => bcrypt($request->password), // hash password
            'role'        => 'user',
            'id_pengguna' => $idPengguna,
        ]);

        // Simpan ke tabel pengguna (tabel profil pelanggan)
        DB::table('pengguna')->insert([
            'id_pengguna'   => $idPengguna,
            'username'      => $user->name,
            'email'         => $user->email,
            'password'      => $user->password,
            'alamat'        => null,
            'nomor_telepon' => null,
            'foto_profil'   => null,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Jika kolom id_pengguna di users belum terisi, update ulang
        if (!$user->id_pengguna) {
            $user->update([
                'id_pengguna' => $idPengguna,
            ]);
        }

        // Redirect ke login dengan pesan sukses
        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil! ID Member Anda: ' . $idPengguna . '. Silakan login.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout(); // hapus status login user

        $request->session()->invalidate(); // hapus session lama
        $request->session()->regenerateToken(); // buat token baru

        return redirect('/'); // kembali ke halaman utama
    }
}
