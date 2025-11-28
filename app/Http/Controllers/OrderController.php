<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan; 
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * 
     * @param  string  $order_id
     * @return \Illuminate\View\View
     */
    public function showStatus(string $order_id)
    {
        $status_message = session('status_message'); 

        $pesanan = Pesanan::with('status') 
                            ->where('id_pesanan', $order_id)
                            ->first();
        
        $error_message = '';
        $pesanan_data = null;
        $status_name = 'Tidak Diketahui';
        $urutan_status = 0; 

        if (!$pesanan) {
            $error_message = "Pesanan dengan ID #{$order_id} tidak ditemukan.";
        } else {
            if ($pesanan->status) {
                $status_name = $pesanan->status->deskripsi;
                $urutan_status = $pesanan->status->urutan_tampilan;
            }
            $pesanan_data = [
                'id_pesanan' => $pesanan->id_pesanan,
                'nama_penerima' => $pesanan->nama_penerima,
                'alamat_pengiriman' => $pesanan->alamat_pengiriman,
                'tanggal_pesanan' => $pesanan->tanggal_pesanan, 
                'total_harga' => $pesanan->total_harga,
                'metode_pembayaran' => $pesanan->metode_pembayaran,
                'status_id' => $pesanan->id_status_pesanan, 
            ];
        }

        return view('orders.status', [
            'pesanan_data' => $pesanan_data,
            'error_message' => $error_message,
            'status_name_from_db' => $status_name,
            'status_message' => $status_message,
            
            'dikemas_class' => $urutan_status >= 2 ? 'active' : '',
        
            'dikirim_class' => $urutan_status >= 3 ? 'active' : '',
            
            'sampai_class' => $urutan_status >= 4 ? 'active' : '',
            
            'order_id_request' => $order_id,
        ]);
    }

}