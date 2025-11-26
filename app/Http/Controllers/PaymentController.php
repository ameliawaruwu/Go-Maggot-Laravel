<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use App\Models\Pesanan; // <-- Pastikan model Pesanan di-import!
use App\Models\Pembayaran; // <-- Pastikan ini di-import
use Illuminate\Support\Facades\Log; // Tambahkan Log jika belum ada, untuk debugging

class PaymentController extends Controller
{
    // Hapus atau abaikan properti $dummyOrder karena kita akan menggunakan DB
    // protected $dummyOrder = [...] 

    /**
     * Menampilkan formulir pembayaran atau halaman sukses.
     * Mengambil data pesanan dari DB berdasarkan order_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showPaymentForm(Request $request)
    {
        // --- INISIALISASI VARIABEL DEFAULT ---
        // Variabel ini harus didefinisikan agar view tidak error, terutama jika order_id tidak ada.
        $page_data = [
            'id_pesanan'        => null, // Default
            'payment_success'   => $request->query('status') === 'success',
            'message'           => Session::get('status_message'), // Ambil pesan dari session
            'name'              => old('name', null), // Default
            'phone'             => old('phone', null), // Default
            'address'           => old('address', null), // Default
            'total_pembayaran'  => 0, // Default
            'metode_pembayaran' => null, // Default
        ];
        
        $id_pesanan = $request->query('order_id');
        
        // Cek jika ID Pesanan tidak ada di URL query
        if (!$id_pesanan) {
            $page_data['message'] = "⚠️ ID Pesanan tidak ditemukan. Mohon ulangi proses checkout.";
            return view('payment.form', $page_data); // Mengirim semua variabel default
        } 
        
        // 1. Ambil data pesanan dari database
        $order = Pesanan::where('id_pesanan', $id_pesanan)->first();

        // Cek jika pesanan tidak ditemukan
        if (!$order) {
            $page_data['message'] = "❌ Pesanan dengan ID {$id_pesanan} tidak ditemukan di database.";
            return view('payment.form', $page_data); // Mengirim semua variabel default
        }

        // 2. Jika pesanan ditemukan, timpa nilai default dengan data dari objek $order
        $page_data['id_pesanan'] = $order->id_pesanan;
        $page_data['name'] = old('name', $order->nama_penerima); 
        $page_data['phone'] = old('phone', $order->nomor_telepon);
        $page_data['address'] = old('address', $order->alamat_pengiriman);
        $page_data['total_pembayaran'] = $order->total_harga; 
        $page_data['metode_pembayaran'] = $order->metode_pembayaran;


        // 3. Kembalikan view dengan data lengkap
        return view('payment.form', $page_data);
    }

    /**
     * Memproses data form pembayaran (Simulasi POST).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request)
    {
        // 1. Validasi Input Pembayaran (Wajib karena ada file upload)
        $request->validate([
            'id_pesanan' => 'required|string',
            'name' => 'required|string|max:255',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        $order_id = $request->input('id_pesanan'); 
        $order = Pesanan::where('id_pesanan', $order_id)->first();

        if (!$order) {
            return back()->with('status_message', 'Pesanan tidak valid. Gagal memproses pembayaran.')->withInput();
        }
        
        // 2. Upload Bukti Pembayaran
        $proof_name = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . $file->getClientOriginalName();
            // Simpan file ke storage/app/public/payment_proofs
            $file->storeAs('public/payment_proofs', $fileName);
            $proof_name = $fileName; 
        }

        // 3. Simpan data ke tabel 'pembayaran'
        try {
            Pembayaran::create([
                'id_pembayaran' => 'PAY-' . time(), 
                'id_pengguna'  => $order->id_pengguna,
                'id_pesanan' => $order_id,
                'bukti_bayar'  => $proof_name, // Simpan nama file bukti bayar
                'tanggal_bayar' => now(),
                // Anda mungkin perlu menambahkan kolom lain sesuai skema tabel pembayaran Anda (misal: 'total_bayar' => $order->total_harga)
            ]);
            
            // 4. Update status di tabel 'pesanan'
            $order->update(['status' => 'Menunggu Konfirmasi Pembayaran']);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pembayaran: ' . $e->getMessage());
            return back()->withInput()->with('status_message', '❌ Gagal menyimpan data pembayaran. Error: ' . $e->getMessage());
        }
        
        $success_message = "✅ Bukti Pembayaran untuk Pesanan #{$order_id} berhasil dikirim! Menunggu konfirmasi admin.";

        // 5. Redirect ke halaman form pembayaran dengan status sukses
        return redirect()
            ->route('payment.form', [
                'order_id' => $order_id,
                'status' => 'success'
            ])
            ->with('status_message', $success_message);
    }
}