<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageFaqController extends Controller
{
    // Simulasi data FAQ (Dalam implementasi nyata, ini diambil dari Model/Database)
    private function getFaqData()
    {
        return [
            (object)['id' => 1, 'pertanyaan' => 'Bagaimana cara reset password?', 'jawaban' => 'Silakan klik "Lupa Password" dan masukkan email Anda.'],
            (object)['id' => 2, 'pertanyaan' => 'Metode pembayaran apa saja yang diterima?', 'jawaban' => 'Kami menerima transfer bank, kartu kredit, dan e-wallet.'],
            (object)['id' => 3, 'pertanyaan' => 'Berapa lama waktu pengiriman?', 'jawaban' => 'Waktu pengiriman standar adalah 3-5 hari kerja.'],
        ];
    }

    /**
     * Menampilkan halaman Manajemen FAQ.
     */
    public function index()
    {
        // Ambil data simulasi
        $faqs = $this->getFaqData(); 
        
        // Load View manage-faq.index
        return view('manage-faq.index', compact('faqs'));
    }

    /**
     * Menyimpan FAQ baru (Simulasi: hanya redirect sukses).
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);

        // 2. Simpan ke Database (Langkah ini diabaikan/simulasi)
        // Logika nyatanya: Faq::create(...)

        // 3. Redirect dengan pesan sukses
        return redirect()->route('managefaq.index')->with('success', 'FAQ berhasil ditambahkan! (Simulasi: Data tidak benar-benar disimpan).');
    }

    /**
     * Memperbarui FAQ tertentu (Simulasi: hanya redirect sukses).
     */
    public function update(Request $request, $id)
    {
        // Validasi Input
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);
        
        // Perbarui data (Langkah ini diabaikan/simulasi)
        // Logika nyatanya: Faq::findOrFail($id)->update(...)

        // Redirect dengan pesan sukses
        return redirect()->route('managefaq.index')->with('success', "FAQ ID $id berhasil diperbarui! (Simulasi).");
    }


    /**
     * Menghapus FAQ tertentu (Simulasi: hanya redirect sukses).
     */
    public function destroy($id)
    {
        // Hapus data (Langkah ini diabaikan/simulasi)
        // Logika nyatanya: Faq::findOrFail($id)->delete()

        // Redirect kembali ke halaman FAQ dengan pesan sukses
        return redirect()->route('managefaq.index')->with('success', "FAQ ID $id berhasil dihapus! (Simulasi).");
    }
}