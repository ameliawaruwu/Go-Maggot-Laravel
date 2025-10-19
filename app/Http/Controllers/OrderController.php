<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan status pesanan berdasarkan ID Pesanan.
     * 
     *
     * @param  string  $order_id
     * @return \Illuminate\View\View
     */
    public function showStatus(string $order_id)
{
    $status_message = session('status_message'); 

    $all_statuses = [
        1 => 'Menunggu Pembayaran',
        2 => 'Pembayaran Dikonfirmasi (Siap Dikemas)',
        3 => 'Sedang Dikemas',
        4 => 'Sedang Dikirim',
        5 => 'Sudah Sampai',
        6 => 'Dibatalkan',
    ];

    $dummy_orders = [
        'ORD-MAGGOT-01' => [
        'id_pesanan' => 'ORD-MAGGOT-01',
            'nama_penerima' => 'Budi Santoso',
            'alamat_pengiriman' => 'Jalan Jakarta No. 123, Bandung',
            'tanggal_pesanan' => '2024-10-18',
            'total_harga' => 310000.00,
            'metode_pembayaran' => 'Qris',
            'status_id' => 3,
        ],
        '2024002' => [
            'id_pesanan' => '2024002',
            'nama_penerima' => 'Ahmad Fauzi',
            'alamat_pengiriman' => 'Jl. Bunga No. 12, Jakarta',
            'tanggal_pesanan' => '2024-10-15',
            'total_harga' => 125000.00,
            'metode_pembayaran' => 'E-Wallet',
            'status_id' => 5,
        ],
    ];

    $pesanan_data = $dummy_orders[$order_id] ?? null;
    $error_message = '';

    if (!$pesanan_data) {
        $error_message = "Pesanan dengan ID {$order_id} tidak ditemukan.";
    }

    $status_id = $pesanan_data['status_id'] ?? 0;
    $status_name = $all_statuses[$status_id] ?? 'Tidak Diketahui';

    return view('orders.status', [
        'pesanan_data' => $pesanan_data,
        'error_message' => $error_message,
        'status_name_from_db' => $status_name,
        'status_message' => $status_message,
        'dikemas_class' => $status_id >= 3 ? 'active' : '',
        'dikirim_class' => $status_id >= 4 ? 'active' : '',
        'sampai_class' => $status_id >= 5 ? 'active' : '',
        'order_id_request' => $order_id,
    ]);
}


    public function process(Request $request)
{
    // Simulasi data dari keranjang 
    $totalPrice = 310000; 
    $orderId = '2024001'; 

    // Redirect ke halaman pembayaran (PaymentController@showPaymentForm)
    return redirect()->route('payment.form', [
        'order_id' => $orderId,
        'total' => $totalPrice
    ]);
}

}
