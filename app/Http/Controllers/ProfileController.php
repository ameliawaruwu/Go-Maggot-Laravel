<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $pengguna = Pengguna::where('email', $user->email)->first();

        return view('profile.show', [
            'title' => 'My Profile',
            'pengguna' => $pengguna,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat'        => 'nullable|string|max:255',
            'foto_profil'   => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        // ambil pengguna berdasarkan email, kalau belum ada -> buat
        $pengguna = Pengguna::where('email', $user->email)->first();

        if (!$pengguna) {
            $idPengguna = 'PG' . str_pad($user->id, 3, '0', STR_PAD_LEFT);

            $pengguna = Pengguna::create([
                'id_pengguna' => $idPengguna,
                'username'    => $user->name ?? 'user',
                'email'       => $user->email,
                'password'    => $user->password, // hash dari users
                // role biarkan default dari DB (tidak kita tampilkan)
            ]);
        }

        // upload foto jika ada
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profile', 'public');
            $pengguna->foto_profil = $path;
        }

        $pengguna->nomor_telepon = $validated['nomor_telepon'] ?? $pengguna->nomor_telepon;
        $pengguna->alamat        = $validated['alamat'] ?? $pengguna->alamat;

        $pengguna->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
