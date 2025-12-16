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
        $validated = $request->validate([
            'id_faq'     => 'required|unique:faq,id_faq', 
            'pertanyaan' => 'required|string',
            'jawaban'    => 'required|string',
        ]);

        
        Faq::create($validated);

        return redirect('/manageFaq')->with('success', 'FAQ berhasil ditambahkan!');
    }

    function edit($id_faq){
        $faq = Faq::findOrFail($id_faq);
        return view('manage-faq.edit', compact('faq'));
    }

    function update(Request $request, $id_faq){
        $faq = Faq::findOrFail($id_faq);

        
        $validated = $request->validate([
            // 'id_faq'     => 'required|unique:faq,id_faq,' . $id_faq . ',id_faq',
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