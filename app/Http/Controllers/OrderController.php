<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pesanan; // Pastikan Anda mengimpor Model Pesanan
use Carbon\Carbon; // Digunakan untuk penanganan tanggal

class OrderController extends Controller
{
    /**
     * Menampilkan status pesanan berdasarkan ID Pesanan.
     * * @param  string  $order_id
     * @return \Illuminate\View\View
     */
    public function showStatus(string $order_id)
    {
        $status_message = session('status_message'); 

        // 1. Ambil data pesanan DENGAN relasi statusnya (table status_pesanan)
        $pesanan = Pesanan::with('status') 
                           ->where('id_pesanan', $order_id)
                           ->first();
        
        $error_message = '';
        $pesanan_data = null;
        $status_name = 'Tidak Diketahui';
        $urutan_status = 0; // Urutan tampilan status (1, 2, 3, 4, dst.)

        if (!$pesanan) {
            // Jika pesanan tidak ditemukan
            $error_message = "Pesanan dengan ID #{$order_id} tidak ditemukan.";
        } else {
            // Pesanan ditemukan
            
            // 2. Ambil Nama Status dan Urutan Tampilan dari relasi
            if ($pesanan->status) {
                $status_name = $pesanan->status->deskripsi;
                $urutan_status = $pesanan->status->urutan_tampilan;
            }
            
            // 3. Siapkan data yang akan dikirim ke view
            $pesanan_data = [
                'id_pesanan' => $pesanan->id_pesanan,
                'nama_penerima' => $pesanan->nama_penerima,
                'alamat_pengiriman' => $pesanan->alamat_pengiriman,
                // Menggunakan tanggal_pesanan yang otomatis menjadi objek Carbon karena diatur di Model
                'tanggal_pesanan' => $pesanan->tanggal_pesanan, 
                'total_harga' => $pesanan->total_harga,
                'metode_pembayaran' => $pesanan->metode_pembayaran,
                
                // Menggunakan key 'status_id' untuk view, nilainya dari kolom 'id_status_pesanan'
                'status_id' => $pesanan->id_status_pesanan, 
            ];
        }

        // Tentukan kelas aktif menggunakan 'urutan_tampilan' ($urutan_status)
        // Berdasarkan tabel status_pesanan Anda:
        // Urutan 2: Diproses (Dikemas)
        // Urutan 3: Dikirim
        // Urutan 4: Selesai (Sampai)

        return view('orders.status', [
            'pesanan_data' => $pesanan_data,
            'error_message' => $error_message,
            'status_name_from_db' => $status_name,
            'status_message' => $status_message,
            
            // Logika Status Tracker:
            // Sedang Dikemas aktif jika urutan status >= 2 (Diproses)
            'dikemas_class' => $urutan_status >= 2 ? 'active' : '',
            
            // Sedang Dikirim aktif jika urutan status >= 3 (Dikirim)
            'dikirim_class' => $urutan_status >= 3 ? 'active' : '',
            
            // Sudah Sampai aktif jika urutan status >= 4 (Selesai)
            'sampai_class' => $urutan_status >= 4 ? 'active' : '',
            
            'order_id_request' => $order_id,
        ]);
    }

}