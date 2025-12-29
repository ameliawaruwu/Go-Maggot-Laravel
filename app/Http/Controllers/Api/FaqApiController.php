<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqApiController extends Controller
{
    // mengambil semua data FAQ 
    public function index()
    {
        $faq = Faq::all();

        return response()->json([
            'message' => 'Daftar semua FAQ',
            'data' => $faq
        ]);
    }

    // menampilkan detail FAQ berdasarkan id_faq
    public function show($id_faq)
    {
        $faq = Faq::find($id_faq);

        if (!$faq) {
            return response()->json(['message' => 'FAQ tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail FAQ',
            'data' => $faq
        ]);
    }

    // menyimpan data FAQ baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_faq' => 'required|string|max:50|unique:faq,id_faq', 
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan data
        $faq = Faq::create([
            "id_faq" => $request->id_faq,
            "pertanyaan" => $request->pertanyaan,
            "jawaban" => $request->jawaban,
        ]);

        return response()->json([
            'message' => 'FAQ berhasil ditambahkan',
            'data' => $faq
        ], 201);
    }

    // memperbarui data FAQ berdasarkan id_faq
    public function update(Request $request, $id_faq)
    {
        $faq = Faq::find($id_faq);

        if (!$faq) {
            return response()->json(['message' => 'FAQ tidak ditemukan'], 404);
        }

        // Aturan validasi untuk update
        $validator = Validator::make($request->all(), [
            'id_faq' => 'required|string|max:50|unique:faq,id_faq,' . $id_faq . ',id_faq',
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Siapkan data untuk update
        $dataUpdate = [
            'id_faq' => $request->id_faq,
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban,
        ];

        $faq->update($dataUpdate);

        // Ambil data FAQ yang sudah diupdate
        $updatedFaq = Faq::find($id_faq);

        return response()->json([
            'message' => 'FAQ berhasil diperbarui',
            'data' => $updatedFaq
        ]);
    }

  // menghapus data FAQ berdasarkan id_faq
    public function destroy($id_faq)
    {
        $faq = Faq::find($id_faq);

        if (!$faq) {
            return response()->json(['message' => 'FAQ tidak ditemukan'], 404);
        }

        $faq->delete();

        return response()->json(['message' => 'FAQ berhasil dihapus']);
    }
}
