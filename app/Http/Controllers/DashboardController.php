<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengguna;
use App\Models\StatusPesanan; // Import Model Status
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $newOrdersToday = Pesanan::whereDate('created_at', $today)->count();
        $totalRegisteredUsers = Pengguna::count();
        $activeUsersToday = Pengguna::whereDate('last_login', $today)->count();
        
        // Hitung sales (Hanya yg status 'Selesai' misalnya, ID statusnya SP004)
        $totalSalesToday = Pesanan::whereDate('created_at', $today)
                                  ->where('id_status_pesanan', 'SP004') 
                                  ->sum('total_harga');

        // Ambil Order + Relasi Pengguna + Relasi StatusPesanan
        $recentOrders = Pesanan::with(['pengguna', 'statusPesanan'])
                               ->orderBy('created_at', 'desc')
                               ->take(10)
                               ->get();

        // Ambil semua pilihan status untuk Dropdown
        $statuses = StatusPesanan::orderBy('urutan_tampilan', 'asc')->get();

        return view('dashboard.index', compact(
            'newOrdersToday',
            'totalRegisteredUsers',
            'activeUsersToday',
            'totalSalesToday',
            'recentOrders',
            'statuses' // Kirim data status ke view
        ));
    }

    // Update Status (Sekarang update ID-nya)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'id_status_pesanan' => 'required|exists:status_pesanan,id_status_pesanan'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        
        // Update Foreign Key
        $pesanan->id_status_pesanan = $request->id_status_pesanan;
        $pesanan->save();

        // Ambil nama status baru untuk pesan sukses
        $namaStatusBaru = $pesanan->statusPesanan->status; 

        return back()->with('success', "Status pesanan berhasil diperbarui menjadi '$namaStatusBaru'.");
    }
}