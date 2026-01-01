<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ReviewsApiController extends Controller
{
    // ambil semua data review
    public function index()
    {
        

        $data = $reviews->map(function($item) {
            $item->foto_url = $item->foto ? asset('photo/' . $item->foto) : null;
            $item->video_url = $item->video ? asset('photo/' . $item->video) : null;
            return $item;
        });

        return response()->json([
            'message' => 'Daftar semua review',
            'data' => $data
        ]);
    }

    public function show($id_review)
    {
        $review = Review::with('pengguna', 'produk')->find($id_review);

        if (!$review) {
            return response()->json(['message' => 'Review tidak ditemukan'], 404);
        }

        $review->foto_url = $review->foto ? asset('photo/' . $review->foto) : null;
        $review->video_url = $review->video ? asset('photo/' . $review->video) : null;

        return response()->json([
            'message' => 'Detail review',
            'data' => $review
        ]);
  }

    // menyimpan data review baru
    public function store(Request $request)
    {
        // 1. OTOMATISASI ID REVIEW (REV001, REV002...)
        $lastReview = Review::orderBy('id_review', 'desc')->first();
        if (!$lastReview) {
            $newIdReview = 'REV001';
        } else {
            $lastIdNumber = intval(substr($lastReview->id_review, 3));
            $newIdReview = 'REV' . str_pad($lastIdNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        // 2. OTOMATISASI ID PENGGUNA DARI LOGIN
        $user = auth()->user();

        // 3. PERSIAPAN DATA
        $requestData = $request->all();
        $requestData['id_review'] = $newIdReview;
        $requestData['id_pengguna'] = $user->id_pengguna;
        $requestData['tanggal_review'] = now();
        $requestData['status'] = 'pending';

        if ($request->has('rating_seller')) {
            $requestData['rating_seller'] = intval($request->rating_seller);
        }

        // 4. VALIDASI (Disesuaikan agar tidak bentrok dengan ID otomatis)
        $validator = Validator::make($requestData, [
            'id_review' => 'required|unique:reviews,id_review',
            'id_pengguna' => 'required',
            'id_produk' => 'required|string',
            'komentar' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov,mkv|max:10240',
            'kualitas' => 'nullable|string',
            'kegunaan' => 'nullable|string',
            'tampilkan_username' => 'nullable',
            'rating_seller' => 'nullable|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 5. UPLOAD FILE
        $namaFoto = null;
        $namaVideo = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . '_foto_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFoto);
        }

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $namaVideo = time() . '_video_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaVideo);
        }

        // 6. SIMPAN KE DATABASE
        $review = Review::create([
            'id_review' => $requestData['id_review'],
            'id_pengguna' => $requestData['id_pengguna'],
            'id_produk' => $requestData['id_produk'],
            'komentar' => $requestData['komentar'] ?? null,
            'foto' => $namaFoto,
            'video' => $namaVideo,
            'kualitas' => $requestData['kualitas'] ?? null,
            'kegunaan' => $requestData['kegunaan'] ?? null,
            'tampilkan_username' => $requestData['tampilkan_username'] ?? 1,
            'rating_seller' => $requestData['rating_seller'] ?? 0,
            'tanggal_review' => $requestData['tanggal_review'],
            'status' => $requestData['status']
        ]);
        
        $review->load('pengguna', 'produk');
        $review->foto_url = $review->foto ? asset('photo/' . $review->foto) : null;
        $review->video_url = $review->video ? asset('photo/' . $review->video) : null;

        return response()->json([
            'message' => 'Review berhasil ditambahkan',
            'data' => $review
        ], 201);
    }

    // Fungsi update dan destroy tetap sama, pastikan validasi id_pengguna tidak bentrok
    public function update(Request $request, $id_review)
    {
        $review = Review::find($id_review);
        if (!$review) return response()->json(['message' => 'Review tidak ditemukan'], 404);

        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id_produk' => 'required',
            'komentar' => 'nullable|string',
            'rating_seller' => 'nullable|integer',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $namaFoto = $review->foto;
        if ($request->hasFile('foto')) {
            if ($review->foto) File::delete(public_path('photo/' . $review->foto));
            $file = $request->file('foto');
            $namaFoto = time() . '_foto_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFoto);
        }

        $review->update(array_merge($requestData, ['foto' => $namaFoto]));
        
        return response()->json([
            'message' => 'Review berhasil diperbarui',
            'data' => $review->load('pengguna', 'produk')
        ]);
    }

    public function destroy($id_review)
    {
        $review = Review::find($id_review);
        if (!$review) return response()->json(['message' => 'Review tidak ditemukan'], 404);

        if ($review->foto) File::delete(public_path('photo/' . $review->foto));
        if ($review->video) File::delete(public_path('photo/' . $review->video));

        $review->delete();
        return response()->json(['message' => 'Review berhasil dihapus']);
    }
}