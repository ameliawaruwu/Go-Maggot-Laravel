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
        

        // Tambahkan URL file lengkap
        $data = $reviews->map(function($item) {
            if ($item->foto) {
                $item->foto_url = asset('photo/' . $item->foto);
            } else {
                $item->foto_url = null;
            }
            if ($item->video) {
                $item->video_url = asset('photo/' . $item->video);
            } else {
                $item->video_url = null;
            }
            return $item;
        });

        return response()->json([
            'message' => 'Daftar semua review',
            'data' => $data
        ]);
    }

    // mengambil detail review berdasarkan id_review
    public function show($id_review)
    {
        $review = Review::with('pengguna', 'produk')->find($id_review);

        if (!$review) {
            return response()->json(['message' => 'Review tidak ditemukan'], 404);
        }

        // Tambahkan URL file lengkap
        if ($review->foto) {
            $review->foto_url = asset('photo/' . $review->foto);
        } else {
            $review->foto_url = null;
        }
        if ($review->video) {
            $review->video_url = asset('photo/' . $review->video);
        } else {
            $review->video_url = null;
        }

        return response()->json([
            'message' => 'Detail review',
            'data' => $review
        ]);
    }

   // menyimpan data review baru
    public function store(Request $request)
    {
        // Konversi nilai integer dari string jika diperlukan
        $requestData = $request->all();
        if ($request->has('rating_seller') && $request->rating_seller !== null && $request->rating_seller !== '') {
            $requestData['rating_seller'] = intval($request->rating_seller);
        }

        $validator = Validator::make($requestData, [
            'id_review' => 'required|string|max:50|unique:reviews,id_review',
            'id_pengguna' => 'required|string|max:50',
            'id_produk' => 'required|string|max:50',
            'komentar' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov,mkv|max:10240',
            'kualitas' => 'nullable|string|max:50',
            'kegunaan' => 'nullable|string|max:50',
            'tampilkan_username' => 'nullable|boolean',
            'rating_seller' => 'nullable|integer|min:1|max:5',
            'tanggal_review' => 'nullable|date',
            'status' => 'nullable|string|max:50',
        ]);
           

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

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

        // Simpan data menggunakan data yang sudah dikonversi
        $review = Review::create([
            'id_review' => $requestData['id_review'],
            'id_pengguna' => $requestData['id_pengguna'],
            'id_produk' => $requestData['id_produk'],
            'komentar' => $requestData['komentar'] ?? null,
            'foto' => $namaFoto,
            'video' => $namaVideo,
            'kualitas' => $requestData['kualitas'] ?? null,
            'kegunaan' => $requestData['kegunaan'] ?? null,
            'tampilkan_username' => $requestData['tampilkan_username'] ?? null,
            'rating_seller' => $requestData['rating_seller'] ?? null,
            'tanggal_review' => $requestData['tanggal_review'] ?? null,
            'status' => $requestData['status'] ?? null
        ]);
        
        $review->load('pengguna', 'produk');

        // Tambahkan URL file lengkap ke respons
        if ($review->foto) {
            $review->foto_url = asset('photo/' . $review->foto);
        } else {
            $review->foto_url = null;
        }
        if ($review->video) {
            $review->video_url = asset('photo/' . $review->video);
        } else {
            $review->video_url = null;
        }

        return response()->json([
            'message' => 'Review berhasil ditambahkan',
            'data' => $review
        ], 201);
    }

    // memperbarui data review berdasarkan id_review
    public function update(Request $request, $id_review)
    {
        $review = Review::find($id_review);

        if (!$review) {
            return response()->json(['message' => 'Review tidak ditemukan'], 404);
        }

        // Konversi nilai integer dari string jika diperlukan
        $requestData = $request->all();
        if ($request->has('rating_seller') && $request->rating_seller !== null && $request->rating_seller !== '') {
            $requestData['rating_seller'] = intval($request->rating_seller);
        }

        $validator = Validator::make($requestData, [
            'id_review' => 'required|string|max:50|unique:reviews,id_review,' . $id_review . ',id_review',
            'id_pengguna' => 'required|string|max:50',
            'id_produk' => 'required|string|max:50',
            'komentar' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov,mkv|max:10240',
            'kualitas' => 'nullable|string|max:50',
            'kegunaan' => 'nullable|string|max:50',
            'tampilkan_username' => 'nullable|boolean',
            'rating_seller' => 'nullable|integer|min:1|max:5',
            'tanggal_review' => 'nullable|date',
            'status' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $namaFoto = $review->foto;
        $namaVideo = $review->video;
        
        // Cek jika ada file foto baru diupload
        if ($request->hasFile('foto')) {
            // Hapus foto lama (jika ada)
            if ($review->foto) {
                $oldPath = public_path('photo/' . $review->foto);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            
            // Upload foto baru
            $file = $request->file('foto');
            $namaFoto = time() . '_foto_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaFoto);
        }

        // Cek jika ada file video baru diupload
        if ($request->hasFile('video')) {
            // Hapus video lama (jika ada)
            if ($review->video) {
                $oldPath = public_path('photo/' . $review->video);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            
            // Upload video baru
            $file = $request->file('video');
            $namaVideo = time() . '_video_' . $file->getClientOriginalName();
            $file->move(public_path('photo'), $namaVideo);
        }

        // Siapkan data untuk update menggunakan data yang sudah dikonversi
        $dataUpdate = [
            'id_review' => $requestData['id_review'],
            'id_pengguna' => $requestData['id_pengguna'],
            'id_produk' => $requestData['id_produk'],
            'komentar' => $requestData['komentar'] ?? null,
            'foto' => $namaFoto,
            'video' => $namaVideo,
            'kualitas' => $requestData['kualitas'] ?? null,
            'kegunaan' => $requestData['kegunaan'] ?? null,
            'tampilkan_username' => $requestData['tampilkan_username'] ?? null,
            'rating_seller' => $requestData['rating_seller'] ?? null,
            'tanggal_review' => $requestData['tanggal_review'] ?? null,
            'status' => $requestData['status'] ?? null
        ];
        
        $review->update($dataUpdate);
        
        // Ambil data review yang sudah diupdate
        $updatedReview = Review::with('pengguna', 'produk')->find($id_review);

        // Tambahkan URL file lengkap ke respons
        if ($updatedReview->foto) {
            $updatedReview->foto_url = asset('photo/' . $updatedReview->foto);
        } else {
            $updatedReview->foto_url = null;
        }
        if ($updatedReview->video) {
            $updatedReview->video_url = asset('photo/' . $updatedReview->video);
        } else {
            $updatedReview->video_url = null;
        }

        return response()->json([
            'message' => 'Review berhasil diperbarui',
            'data' => $updatedReview
        ]);
    }

    // menghapus review
    public function destroy($id_review)
    {
        $review = Review::find($id_review);

        if (!$review) {
            return response()->json(['message' => 'Review tidak ditemukan'], 404);
        }

        // Hapus file foto terkait
        if ($review->foto) {
            $path = public_path('photo/' . $review->foto);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        // Hapus file video terkait
        if ($review->video) {
            $path = public_path('photo/' . $review->video);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $review->delete();
        
        return response()->json(['message' => 'Review berhasil dihapus']);
    }
}
