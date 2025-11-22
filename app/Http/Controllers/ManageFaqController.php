<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageFaqController extends Controller
{
    
    private function getFaqData()
    {
        return [
            (object)['id' => 1, 'pertanyaan' => 'Bagaimana cara reset password?', 'jawaban' => 'Silakan klik "Lupa Password" dan masukkan email Anda.'],
            (object)['id' => 2, 'pertanyaan' => 'Metode pembayaran apa saja yang diterima?', 'jawaban' => 'Kami menerima transfer bank, kartu kredit, dan e-wallet.'],
            (object)['id' => 3, 'pertanyaan' => 'Berapa lama waktu pengiriman?', 'jawaban' => 'Waktu pengiriman standar adalah 3-5 hari kerja.'],
        ];
    }

    public function index()
    {
        
        $faqs = $this->getFaqData(); 
        
        
        return view('manage-faq.index', compact('faqs'));
    }

    
    public function store(Request $request)
    {
        
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);
        return redirect()->route('managefaq.index')->with('success', 'FAQ berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
       
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);
        return redirect()->route('managefaq.index')->with('success', "FAQ ID $id berhasil diperbarui!.");
    }

    public function destroy($id)
    {
        return redirect()->route('managefaq.index')->with('success', "FAQ ID $id berhasil dihapus! .");
    }
}