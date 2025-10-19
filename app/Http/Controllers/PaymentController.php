<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Import Facade Session

class PaymentController extends Controller
{
    
    protected $dummyOrder = [
        'id_pelanggan' => 123,
        'nama_pelanggan' => 'Budi Santoso',
        'telepon' => '081234567890',
        'alamat' => 'Jalan Jakarta No. 123, Bandung',
        'total_pembayaran' => 310000.00,
        'metode_pembayaran' => 'Qris'
    ];

    /**
     * Menampilkan formulir pembayaran atau halaman sukses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
   public function showPaymentForm(Request $request)
{
    $id_pesanan = $request->query('order_id');
    $payment_success = $request->query('status') === 'success';

    $page_data = [
        'id_pesanan' => $id_pesanan,
        'payment_success' => $payment_success,
        'message' => Session::get('status_message'),
        'name' => old('name', $this->dummyOrder['nama_pelanggan']),
        'phone' => old('phone', $this->dummyOrder['telepon']),
        'address' => old('address', $this->dummyOrder['alamat']),
        'total_pembayaran' => $this->dummyOrder['total_pembayaran'],
        'metode_pembayaran' => $this->dummyOrder['metode_pembayaran'],
    ];

    if (!$id_pesanan) {
        $page_data['message'] = "⚠️ ID Pesanan tidak ditemukan. Mohon ulangi proses checkout.";
    } 

    return view('payment.form', $page_data);
}

    /**
     * Memproses data form pembayaran (Simulasi POST).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
  
   public function processPayment(Request $request)
{
    //Ambil ID Pesanan dari input form
    $order_id = $request->input('id_pesanan') ?? 'ORD-MAGGOT-01'; 

    //Simulasi proses pembayaran 
    $success_message = "Pembayaran untuk Pesanan #{$order_id} berhasil dikirim!";

    //Redirect ke halaman form pembayaran yang sama
    return redirect()
        ->route('payment.form', [
            'order_id' => $order_id,
            'status' => 'success'
        ])
        ->with('status_message', $success_message);
}
}
