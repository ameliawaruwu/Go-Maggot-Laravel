<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\DB; // Tambahkan
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage; // Tambahkan untuk upload
use App\Models\Pesanan; 
use App\Models\Pembayaran; 

class PaymentController extends Controller
{
    /**
     * Mengambil ID Status Pesanan berdasarkan nama status.
     * @param string $statusName
     * @return string
     * @throws \Exception Jika ID status tidak ditemukan.
     */
    private function getStatusIdByName(string $statusName): string
    {
        // Mencari id_status_pesanan berdasarkan nama status
        // Asumsi: Kolom di tabel status_pesanan adalah 'status' dan 'id_status_pesanan'
        $statusId = DB::table('status_pesanan')
            ->where('status', $statusName) 
            ->value('id_status_pesanan');

        if (is_null($statusId)) {
            Log::error("Status Pesanan '{$statusName}' tidak ditemukan.");
            throw new \Exception("ID Status Pesanan untuk '{$statusName}' tidak ditemukan. Pastikan data status sudah ada.");
        }

        return $statusId;
    }

    /**
     * Menampilkan formulir pembayaran atau halaman sukses.
     * Mengambil data pesanan dari DB berdasarkan order_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showPaymentForm(Request $request, $order_id_param = null)
    {
        // ... (Fungsi showPaymentForm tidak diubah, hanya ditambahkan use statement di atas)
        $page_data = [
            'id_pesanan'        => null, // Default
            'payment_success'   => $request->query('status') === 'success',
            'message'           => Session::get('status_message'), // Ambil pesan dari session
            'name'              => old('name', null), // Default
            'phone'             => old('phone', null), // Default
            'address'           => old('address', null), // Default
            'total_pembayaran'  => 0, // Default
            'metode_pembayaran' => null, // Default
            'current_status'    => null, // Tambahkan status saat ini
        ];
        
        $id_pesanan = $order_id_param ?? $request->query('order_id');
        
        // Cek jika ID Pesanan tidak ada di URL query
        if (!$id_pesanan) {
            $page_data['message'] = "⚠️ ID Pesanan tidak ditemukan. Mohon ulangi proses checkout.";
            return view('payment.form', $page_data);
        } 
        
        // 1. Ambil data pesanan dari database
        $order = Pesanan::where('id_pesanan', $id_pesanan)->first();

        // Cek jika pesanan tidak ditemukan
        if (!$order) {
            $page_data['message'] = "❌ Pesanan dengan ID {$id_pesanan} tidak ditemukan di database.";
            return view('payment.form', $page_data);
        }

        // 2. Jika pesanan ditemukan, timpa nilai default
        $page_data['id_pesanan'] = $order->id_pesanan;
        $page_data['name'] = old('name', $order->nama_penerima); 
        $page_data['phone'] = old('phone', $order->nomor_telepon);
        $page_data['address'] = old('address', $order->alamat_pengiriman);
        $page_data['total_pembayaran'] = $order->total_harga; 
        $page_data['metode_pembayaran'] = $order->metode_pembayaran;
        $page_data['current_status'] = $order->status; // Tampilkan status
        
        // 3. Kembalikan view dengan data lengkap
        return view('payment.form', $page_data);
    }

    /**
     * Memproses data form pembayaran (Upload Bukti Bayar).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request)
    {
        // 1. Validasi Input Pembayaran
        // Jika validasi gagal, Laravel akan otomatis me-redirect kembali (back()) dengan error validation.
        $request->validate([
            'id_pesanan' => 'required|string',
            'name' => 'required|string|max:255',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        $order_id = $request->input('id_pesanan'); 
        
        DB::beginTransaction();
        try {
            // Cek Pesanan
            $order = Pesanan::where('id_pesanan', $order_id)->first();
            if (!$order) {
                // Pesanan tidak ditemukan -> throw exception
                throw new \Exception("Pesanan ID: {$order_id} tidak ditemukan. Gagal memproses pembayaran.");
            }
            Log::info("Processing payment for Order #{$order_id}");


            // Tentukan Status Baru ('Diproses' sesuai dengan data status_pesanan yang ada)
            $newStatusString = 'Diproses';
            $newStatusId = $this->getStatusIdByName($newStatusString); 


            // 2. Upload Bukti Pembayaran
            $proof_name = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $fileName = time() . '_' . $file->getClientOriginalName();
                // Simpan file ke storage/app/public/bukti_bayar 
                $file->storeAs('bukti_bayar', $fileName, 'public'); 
                $proof_name = $fileName; 
                Log::info("Bukti pembayaran diunggah: {$proof_name}");
            }
            
            // 3. Update Status di tabel 'pesanan'
            $order->update([
                'status' => $newStatusString, // Ubah status pesanan
                'id_status_pesanan' => $newStatusId, // Tambahkan kolom ID Status
            ]);
            Log::info("Status Pesanan #{$order_id} diupdate menjadi: {$newStatusString} ({$newStatusId})");


            // 4. Update record pembayaran
            $pembayaran = Pembayaran::where('id_pesanan', $order_id)->first();
            $warning_message = ''; // Inisialisasi pesan peringatan

            if ($pembayaran) {
                // KASUS 1: Ditemukan record pembayaran -> UPDATE bukti_bayar
                $pembayaran->update([
                    'bukti_bayar'       => $proof_name, 
                    'tanggal_bayar'     => now(),
                    'status_pembayaran' => $newStatusString,
                    'id_status_pesanan' => $newStatusId,
                ]);
                Log::info("Record Pembayaran ID: {$pembayaran->id_pembayaran} berhasil diupdate dengan bukti bayar.");
            } else {
                // KASUS 2: Record Pembayaran tidak ditemukan
                Log::warning("❌ TIDAK DITEMUKAN record pembayaran untuk pesanan {$order_id}. Bukti bayar TIDAK TERSIMPAN di tabel Pembayaran.");
                $warning_message = " Peringatan: Bukti bayar tidak tersimpan karena record pembayaran belum ada.";
            }

            DB::commit();
            Log::info("Transaksi pembayaran untuk Pesanan #{$order_id} berhasil di-commit.");

            // 5. Redirect ke halaman form pembayaran dengan status sukses
            $success_message = "✅ Bukti Pembayaran untuk Pesanan #{$order_id} berhasil dikirim! Status pesanan kini {$newStatusString}.{$warning_message}";
            
            return redirect()
                ->route('payment.form', [
                    'order_id' => $order_id,
                    'status' => 'success'
                ])
                ->with('status_message', $success_message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Logging detail error, termasuk trace
            Log::error('Gagal memproses/menyimpan pembayaran untuk Pesanan #' . $order_id . ': ' . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            
            // Tampilkan pesan error ke user
            return back()->withInput()->with('status_message', '❌ Gagal menyimpan data pembayaran. Error: ' . $e->getMessage());
        }
    }
}