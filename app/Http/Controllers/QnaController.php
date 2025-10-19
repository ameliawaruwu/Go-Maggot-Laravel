<?php

namespace App\Http\Controllers;

class QnaController extends Controller
{
    public function index()
    {
        $faqs = [
            [
                'question' => 'Apakah maggot aman digunakan?',
                'answer' => 'Maggot dapat digunakan sebagai alternatif pengurai sampah, dan aman untuk pakan ternak karena bukan termasuk lalat penyebar penyakit.',
                'active' => true,
            ],
            [
                'question' => 'Apakah pembayaran dapat COD?',
                'answer' => 'Pembayaran tidak dapat dilakukan dengan COD.',
                'active' => true,
            ],
            [
                'question' => 'Di manakah masyarakat dapat memelihara maggot?',
                'answer' => 'Untuk pemula bisa lakukan ternak di ruang lingkup kecil terlebih dahulu, salah satunya di belakang rumah.',
                'active' => true,
            ],
            [
                'question' => 'Apakah maggot yang dijual disini terjamin kualitasnya?',
                'answer' => 'Tentu, sudah banyak review positif dari beberapa pelanggan kami.',
                'active' => true,
            ],
            [
                'question' => 'Apakah ada jasa pick up untuk mengantarkan maggot ke alamat tujuan?',
                'answer' => 'Ya, kami menyediakan jasa antar pesanan ke alamat pelanggan yang bekerja sama dengan pihak jasa kirimnya. Agar pelanggan tidak perlu datang jauh-jauh ke sini.',
                'active' => true,
            ],
        ];

        // Mengirim data FAQ ke view 
        return view('QNA.qna', compact('faqs'));
    }
}