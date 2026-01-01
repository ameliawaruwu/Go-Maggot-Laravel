<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ReviewsApiController extends Controller
{
    /**
     * Ambil semua data ulasan dengan efisiensi memori.
     */
    public function index()
    {
        // PERBAIKAN: Batasi kolom yang diambil untuk hemat RAM
        $reviews = Review::with([
            'pengguna:id_pengguna,username,foto_profil', 
            'produk:id_produk,nama_produk'
        ])
        ->latest()
        ->paginate(15); // Gunakan pagination agar tidak me-load ribuan data sekaligus

        $reviews->getCollection()->transform(function($item) {
            $item->foto_url = $item->foto ? asset('photo/' . $item->foto) : null;
            $item->video_url = $item->video ? asset('photo/' . $item->video) : null;
            return $item;
        });

        return response()->json([
            'message' => 'Daftar semua review',
            'data' => $reviews
        ]);
    }

    /**
     * Menyimpan data review baru dengan proteksi memory leak.
     */
    public function store(Request $request)
    {
        // 1. Naikkan limit memori secara lokal untuk upload file biner
        ini_set('memory_limit', '512M');

        // 2. Persiapan ID Otomatis (Gunakan DB Table agar lebih ringan)
        $lastReview = DB::table('reviews')->select('id_review')->orderBy('id_review', 'desc')->first();
        $lastIdNumber = $lastReview ? intval(substr($lastReview->id_review, 3)) : 0;
        $newIdReview = 'REV' . str_pad($lastIdNumber + 1, 3, '0', STR_PAD_LEFT);

        // 3. Ambil user ID secara ringan (Hanya ID, bukan seluruh objek model)
        $userId = Auth::id(); // Mengambil ID pengguna yang sedang login

        // 4. Validasi awal sebelum memproses file
        $validator = Validator::make($request->all(), [
            'id_produk' => 'required|string',
            'komentar' => 'nullable|string',
            'rating_seller' => 'nullable|integer|min:1|max:5',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi|max:10240',
            'tampilkan_username' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // 5. Penanganan File
            $namaFoto = null;
            $namaVideo = null;

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $namaFoto = time() . '_foto_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('photo'), $namaFoto);
            }

            if ($request->hasFile('video')) {
                $file = $request->file('video');
                $namaVideo = time() . '_video_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('photo'), $namaVideo);
            }

            // 6. Simpan Menggunakan DB::table (Lebih aman dari Memory Exhausted Eloquent)
            DB::table('reviews')->insert([
                'id_review' => $newIdReview,
                'id_pengguna' => $userId,
                'id_produk' => $request->id_produk,
                'komentar' => $request->komentar,
                'foto' => $namaFoto,
                'video' => $namaVideo,
                'kualitas' => $request->kualitas,
                'kegunaan' => $request->kegunaan,
                'tampilkan_username' => $request->tampilkan_username ?? 1,
                'rating_seller' => $request->rating_seller ?? 0,
                'tanggal_review' => now(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Review berhasil ditambahkan',
                'id_review' => $newIdReview
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            // JANGAN log seluruh objek exception untuk menghindari loop memory pada log
            Log::error("Gagal simpan review: " . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    public function destroy($id_review)
    {
        $review = Review::find($id_review);
        if (!$review) return response()->json(['message' => 'Review tidak ditemukan'], 404);

        // Hapus file fisik
        if ($review->foto && File::exists(public_path('photo/' . $review->foto))) {
            File::delete(public_path('photo/' . $review->foto));
        }
        if ($review->video && File::exists(public_path('photo/' . $review->video))) {
            File::delete(public_path('photo/' . $review->video));
        }

        $review->delete();
        return response()->json(['message' => 'Review berhasil dihapus']);
    }
}