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
        // Halaman create biasanya kosong, tidak perlu ambil data
        return view('manage-faq.create');
    }

    function simpan(Request $request){
        // 1. Tambahkan Validasi
        // Simpan data yang lolos validasi ke variabel $validated
        $validated = $request->validate([
            // Pastikan id_faq unik di tabel 'faq' (sesuaikan nama tabel di database)
            'id_faq'     => 'required|unique:faqs,id_faq', 
            'pertanyaan' => 'required|string',
            'jawaban'    => 'required|string',
        ]);

        // 2. Gunakan variabel $validated untuk create
        Faq::create($validated);

        return redirect('/manageFaq')->with('success', 'FAQ berhasil ditambahkan!');
    }

    function edit($id_faq){
        $faq = Faq::findOrFail($id_faq);
        return view('manage-faq.edit', compact('faq'));
    }

    function update(Request $request, $id_faq){
        $faq = Faq::findOrFail($id_faq);

        // 1. Tambahkan Validasi
        $validated = $request->validate([
            // Saat update, kita harus 'ignore' ID milik data ini sendiri agar tidak error "sudah ada"
            // Format: unique:nama_tabel,nama_kolom,id_yang_diabaikan,nama_primary_key
            'id_faq'     => 'required|unique:faqs,id_faq,' . $id_faq . ',id_faq',
            'pertanyaan' => 'required|string',
            'jawaban'    => 'required|string',
        ]);

        // 2. Gunakan variabel $validated untuk update
        // (Langsung pakai $faq->update() lebih efisien daripada Faq::where(...)->update())
        $faq->update($validated);

        return redirect('/manageFaq')->with('success', 'FAQ berhasil diperbarui!');
    }

    function delete($id_faq){
        $faq = Faq::findOrFail($id_faq);
        $faq->delete();
        
        return redirect('/manageFaq')->with('success', 'FAQ berhasil dihapus!');
    }
}