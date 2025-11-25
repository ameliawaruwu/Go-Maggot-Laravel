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
        $faq = Faq::all();
        return view('manage-faq.create', compact('faq'));
    }

    function simpan(Request $a){
        Faq::create(
            [
                "id_faq" => $a->id_faq,
                "pertanyaan" => $a->pertanyaan,
                "jawaban" => $a->jawaban
            ]
            );
        return redirect('/manageFaq');
    }

    function edit($id_faq){
        $faq = Faq::findOrFail($id_faq);
        return view('manage-faq.edit', compact('faq'));
    }

    function update(Request $x, $id_faq){
        $faq = Faq::findOrFail($id_faq);
        Faq::where("id_faq", "$x->id_faq" )->update(
            [
                'id_faq' => $x->id_faq,
                'pertanyaan' => $x->pertanyaan,
                'jawaban' => $x->jawaban
            ]
            );
        return redirect('/manageFaq');
    }

    function delete($id_faq){
        $faq = Faq::findOrFail($id_faq);
        $faq->delete();
        return redirect('/manageFaq');
    }



}