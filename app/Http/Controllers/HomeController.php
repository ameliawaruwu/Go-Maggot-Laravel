<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Review;
use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'title' => 'GoMaggot',
        ]);
    }

    // dipakai oleh route POST /feedback
    public function storeFeedback(Request $request)
    {
        // 1️⃣ Validasi input form
        $request->validate([
            'review'        => 'nullable|string',
            'condition'     => 'nullable|string',
            'quality'       => 'nullable|string',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'video'         => 'nullable|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:20000',
            'rating_produk' => 'nullable|integer|min:1|max:5',
            'rating_seller' => 'nullable|integer|min:1|max:5',
        ]);

        // 2️⃣ Upload file (opsional)
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reviews/photos', 'public');
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('reviews/videos', 'public');
        }

        // 3️⃣ Ambil user yang lagi login
        $user = $request->user(); // sama dengan auth()->user()

        // 4️⃣ Pastikan user punya id_pengguna (PGxxx).
        //    Kalau belum ada -> kita buatkan OTOMATIS dan simpan ke 2 tabel:
        //    - users.id_pengguna
        //    - pengguna (id_pengguna, username, email, password)
        $idPengguna = $user->id_pengguna;

        if (!$idPengguna) {
            // buat kode PGxxx dari id user
            $idPengguna = 'PG' . str_pad($user->id, 3, '0', STR_PAD_LEFT);

            // simpan / update ke tabel pengguna
            DB::table('pengguna')->updateOrInsert(
                ['id_pengguna' => $idPengguna],
                [
                    'username' => $user->name,
                    'email'    => $user->email,
                    'password' => $user->password, // pakai hash yang sama
                ]
            );

            // simpan juga ke tabel users
            $user->id_pengguna = $idPengguna;
            $user->save();
        }

        // 5️⃣ Ambil id_produk yang valid (supaya lolos foreign key)
        $produk = Produk::first();
        if (!$produk) {
            return back()->withErrors('Belum ada data produk di sistem. Tambahkan produk dulu sebelum mengirim review.');
        }

        // kalau form kirim id_produk, pakai itu; kalau tidak, pakai produk pertama
        $idProduk = $request->input('id_produk', $produk->id_produk);

        // 6️⃣ Checkbox tampilkan username
        $tampilkanUsername = $request->has('username-toggle') ? '1' : '0';

        // 7️⃣ Generate id_review unik
        $idReview = 'REV' . str_pad($i, 3, '0', STR_PAD_LEFT);

        // 8️⃣ Simpan ke tabel reviews
        Review::create([
            'id_review'           => $idReview,
            'id_pengguna'         => $idPengguna,
            'id_produk'           => $idProduk,
            'komentar'            => $request->input('review'),
            'foto'                => $photoPath,
            'video'               => $videoPath,
            'kualitas'            => $request->input('quality'),
            'kegunaan'            => $request->input('condition'),
            'tampilkan_username'  => $tampilkanUsername,
            'rating_seller'       => $request->input('rating_seller', 0),
            'tanggal_review'      => now()->toDateString(),
            'status'              => 'pending',
        ]);

        // 9️⃣ Kembali dengan pesan sukses
        return back()->with('success', 'Terima kasih! Feedback kamu sudah terkirim 🙌');
    }
}
