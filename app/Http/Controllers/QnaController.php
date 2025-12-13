<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class QnaController extends Controller
{
    public function index()
    {
        
        $faqs = Faq::all()->map(function ($faq) {
            return [
                'question' => $faq->pertanyaan,
                'answer' => $faq->jawaban,
                'active' => false, 
            ];
        });

        return view('QNA.qna', compact('faqs'));
    }
}
