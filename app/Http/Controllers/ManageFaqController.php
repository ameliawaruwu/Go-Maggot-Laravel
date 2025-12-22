<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class ManageFaqController extends Controller
{
    function index(){
        $faq = Faq::all();
        return view('manage-faq.index', compact('faq'));
    }

    function input(){
        return view('manage-faq.create');
    }

    function simpan(Request $request){
        // --- 1. GENERATE ID OTOMATIS (F01, F02, dst) ---
        $lastFaq = Faq::orderBy('id_faq', 'desc')->first();

        if (!$lastFaq) {
            $newId = 'F01'; // Jika belum ada data, mulai dari F01
        } else {
            $lastId = $lastFaq->id_faq;
            // Ambil angka setelah huruf 'F' (index ke-1 sampai akhir)
            $number = (int) substr($lastId, 1); 
            $number++; // Tambah 1
            // Format ulang: 'F' + angka 2 digit (contoh: 9 jadi 09)
            $newId = 'F' . sprintf("%02d", $number);
        }

        // --- 2. VALIDASI (Hapus id_faq dari sini) ---
        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'jawaban'    => 'required|string',
        ]);

        // Masukkan ID otomatis ke data yang akan disimpan
        $validated['id_faq'] = $newId;

        // Simpan
        Faq::create($validated);

        return redirect('/manageFaq')->with('success', 'FAQ berhasil ditambahkan dengan ID: ' . $newId);
    }

    function edit($id_faq){
        $faq = Faq::findOrFail($id_faq);
        return view('manage-faq.edit', compact('faq'));
    }

    function update(Request $request, $id_faq){
        $faq = Faq::findOrFail($id_faq);

        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'jawaban'    => 'required|string',
        ]);

        $faq->update($validated);

        return redirect('/manageFaq')->with('success', 'FAQ berhasil diperbarui!');
    }

    function delete($id_faq){
        $faq = Faq::findOrFail($id_faq);
        $faq->delete();
        
        return redirect('/manageFaq')->with('success', 'FAQ berhasil dihapus!');
    }
}