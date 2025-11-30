<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            if ($request->filled('redirect')) {
                return redirect()->to($request->input('redirect'));
            }
            if (session()->has('url.intended')) {
                return redirect()->intended('/home');
            }
            if (Auth::user()->role === 'admin') {
                return redirect('/dashboard');
            }
            return redirect('/home');
        }

        return back()
            ->withErrors([
                'email' => 'Akun tidak ditemukan. Silakan register terlebih dahulu.',
            ])
            ->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $lastPengguna = DB::table('pengguna')
                        ->orderBy('id_pengguna', 'desc')
                        ->first();

        if ($lastPengguna) {
            $lastNumber = (int) substr($lastPengguna->id_pengguna, 2); 
            $nextNumber = $lastNumber + 1; 
        } else {
            $nextNumber = 1;
        }

        $idPengguna = 'PG' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => bcrypt($request->password),
            'role'        => 'user',
            'id_pengguna' => $idPengguna, 
        ]);

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

        if (!$user->id_pengguna) {
            $user->update([
                'id_pengguna' => $idPengguna,
            ]);
        }

        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil! ID Member Anda: ' . $idPengguna . '. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}