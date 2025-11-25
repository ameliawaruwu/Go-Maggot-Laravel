<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        Faq::insert([
            [
                'id_faq' => 'F01',
                'pertanyaan' => 'Apa itu GoMaggot?',
                'jawaban' => 'GoMaggot adalah platform pemesanan dan pengelolaan maggot secara online.'
            ],
            [
                'id_faq' => 'F02',
                'pertanyaan' => 'Bagaimana cara membuat pesanan?',
                'jawaban' => 'Anda dapat membuat pesanan melalui menu pesanan lalu mengisi detail pesanan.'
            ],
            [
                'id_faq' => 'F03',
                'pertanyaan' => 'Bagaimana cara melakukan pembayaran?',
                'jawaban' => 'Setelah membuat pesanan, sistem akan membuat tagihan dan Anda bisa membayar melalui metode yang tersedia.'
            ],
            [
                'id_faq' => 'F04',
                'pertanyaan' => 'Berapa lama proses pemesanan diproses?',
                'jawaban' => 'Pesanan biasanya diproses dalam waktu 1-2 hari kerja.',
            ],
            [
                'id_faq' => 'F05',
                'pertanyaan' => 'Apakah saya bisa membatalkan pesanan?',
                'jawaban' => 'Ya, pembatalan pesanan dapat dilakukan sebelum pembayaran dikonfirmasi.',
            ],
        ]);
    }
}
