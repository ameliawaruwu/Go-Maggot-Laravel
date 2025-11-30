<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Models\Pembayaran;

class PaymentController extends Controller
{
    /**
     * 
     */
    private function getStatusIdByName(string $statusName): string
    {
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
     * 
     */
    public function showPaymentForm(Request $request, $order_id_param = null)
    {
        $page_data = [
            'id_pesanan'        => null,
            'payment_success'   => $request->query('status') === 'success',
            'message'           => Session::get('status_message'),
            'name'              => old('name', null),
            'phone'             => old('phone', null),
            'address'           => old('address', null),
            'total_pembayaran'  => 0,
            'metode_pembayaran' => null,
            'current_status'    => null,
        ];

        // Menerima ID Pesanan dari URL parameter atau query string
        $id_pesanan = $order_id_param ?? $request->query('order_id');

        if (!$id_pesanan) {
            $page_data['message'] = "ID Pesanan tidak ditemukan. Mohon ulangi proses checkout.";
            return view('payment.form', $page_data);
        }

        // Mencari Pesanan berdasarkan ID
        $order = Pesanan::where('id_pesanan', $id_pesanan)->first();

        if (!$order) {
            $page_data['message'] = "Pesanan dengan ID {$id_pesanan} tidak ditemukan di database.";
            return view('payment.form', $page_data);
        }

        $page_data['id_pesanan']        = $order->id_pesanan;
        $page_data['name']              = old('name', $order->nama_penerima);
        $page_data['phone']             = old('phone', $order->nomor_telepon);
        $page_data['address']           = old('address', $order->alamat_pengiriman);
        $page_data['total_pembayaran']  = $order->total_harga;
        $page_data['metode_pembayaran'] = $order->metode_pembayaran;
        $page_data['current_status']    = $order->status;

        return view('payment.form', $page_data);
    }

    /**
     * 
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'id_pesanan'     => 'required|string',
            'name'           => 'required|string|max:255',
            'payment_proof'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $order_id = $request->input('id_pesanan');

        DB::beginTransaction();
        try {
            $order = Pesanan::where('id_pesanan', $order_id)->first();

            if (!$order) {
                throw new \Exception("Pesanan ID: {$order_id} tidak ditemukan. Gagal memproses pembayaran.");
            }

            Log::info("Processing payment for Order #{$order_id}");

            $userId = Auth::id();
            if (!$userId) {
                $userId = $order->id_pengguna ?? null;
            }

            $newStatusString = 'Diproses';
            $newStatusId     = $this->getStatusIdByName($newStatusString);

            $proof_name = null;

            // ✅ PERBAIKAN: Simpan ke public/photo (bukan storage)
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                // Simpan langsung ke folder public/photo
                $file->move(public_path('photo'), $fileName);
                $proof_name = $fileName;

                Log::info("Bukti pembayaran diunggah: {$proof_name} ke public/photo");
            }

            $order->update([
                'status'            => $newStatusString,
                'id_status_pesanan' => $newStatusId,
            ]);

            Log::info("Status Pesanan #{$order_id} diupdate menjadi: {$newStatusString} ({$newStatusId})");

            $pembayaran = Pembayaran::where('id_pesanan', $order_id)->first();
            $warning_message = '';

            if ($pembayaran) {
                // ✅ Hapus file lama jika ada
                if (!empty($pembayaran->bukti_bayar)) {
                    $oldFile = public_path('photo/' . $pembayaran->bukti_bayar);
                    if (file_exists($oldFile)) {
                        @unlink($oldFile);
                        Log::info("File lama dihapus: {$pembayaran->bukti_bayar}");
                    }
                }

                $pembayaran->update([
                    'bukti_bayar'       => $proof_name,
                    'tanggal_bayar'     => now(),
                    'status_pembayaran' => $newStatusString,
                    'id_status_pesanan' => $newStatusId,
                    'id_pengguna'       => $userId,
                ]);

                Log::info("Record Pembayaran ID: {$pembayaran->id_pembayaran} berhasil diupdate. ID Pengguna: {$userId}");
            } else {
                $new_payment_id = 'PAY-' . time() . '-' . uniqid(); 

                Pembayaran::create([
                    'id_pembayaran'     => $new_payment_id, 
                    'id_pesanan'        => $order_id,
                    'id_pengguna'       => $userId,
                    'total_bayar'       => $order->total_harga,
                    'metode_pembayaran' => $order->metode_pembayaran,
                    'bukti_bayar'       => $proof_name,
                    'tanggal_bayar'     => now(),
                    'status_pembayaran' => $newStatusString,
                    'id_status_pesanan' => $newStatusId,
                ]);

                Log::info("Record pembayaran baru dibuat untuk pesanan {$order_id} dengan ID: {$new_payment_id}.");
                $warning_message = " Peringatan: Record pembayaran dibuat baru, pastikan alur checkout sudah benar.";
            }

            DB::commit();

            Log::info("Transaksi pembayaran untuk Pesanan #{$order_id} berhasil di-commit.");

            $success_message =
                "Bukti Pembayaran untuk Pesanan #{$order_id} berhasil dikirim! Status pesanan kini {$newStatusString}."
                . $warning_message;

            return redirect()
                ->route('payment.form', [
                    'order_id' => $order_id,
                    'status'   => 'success',
                ])
                ->with('status_message', $success_message);

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error(
                'Gagal memproses pembayaran #' . $order_id . ': '
                . $e->getMessage()
                . "\nTrace: " . $e->getTraceAsString()
            );

            return back()->withInput()->with(
                'status_message',
                'Gagal menyimpan data pembayaran. Error: ' . $e->getMessage()
            );
        }
    }
}