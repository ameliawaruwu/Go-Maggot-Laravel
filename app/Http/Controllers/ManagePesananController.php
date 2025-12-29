<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\StatusPesanan;

class ManagePesananController extends Controller
{
    /**
     * Menampilkan daftar pesanan di halaman admin.
     */
    public function index(Request $request)
    {
        // 1. Mulai Query Pesanan
        $query = Pesanan::with([
            'pengguna',             // Untuk ambil nama & email user
            'detailPesanan.produk', // Untuk ambil detail produk yang dibeli
            'pembayaran',           // Untuk ambil bukti bayar
            'status'                // Untuk ambil label status (Menunggu, Dikirim, dll)
        ]);

        // 2. Fitur Pencarian (Opsional, jika input search diisi)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('id_pesanan', 'like', "%$search%")
                  ->orWhereHas('pengguna', function($q) use ($search) {
                      $q->where('username', 'like', "%$search%");
                  });
        }

        // 3. Ambil data dengan Pagination (10 per halaman) & urutkan dari yang terbaru
        $recentOrders = $query->latest()->paginate(10);

        // 4. Ambil semua status untuk dropdown pilihan
        $statuses = StatusPesanan::all();

        // 5. Kirim data ke View
        return view('manage-pesanan.index', compact('recentOrders', 'statuses'));
    }

    /**
     * Mengupdate status pesanan (Dipanggil saat dropdown diganti).
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input pastikan id_status_pesanan valid
        $request->validate([
            'id_status_pesanan' => 'required|exists:status_pesanan,id_status_pesanan',
        ]);

        // Cari pesanan berdasarkan ID
        $pesanan = Pesanan::findOrFail($id);

        // Update statusnya
        $pesanan->id_status_pesanan = $request->id_status_pesanan;
        $pesanan->save();

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}