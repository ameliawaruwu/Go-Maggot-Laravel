<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengguna;
use App\Models\StatusPesanan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $newOrdersToday        = Pesanan::whereDate('created_at', $today)->count();
        $totalRegisteredUsers  = Pengguna::count();
        $activeUsersToday      = Pengguna::whereDate('last_login', $today)->count();
        $totalSalesToday = Pesanan::whereDate('created_at', $today)
            ->where('id_status_pesanan', 'SP004')
            ->sum('total_harga');

        $recentOrders = Pesanan::with([
                'pengguna',
                'status',           
                'detailPesanan.produk',
                'pembayaran'
            ])->orderBy('created_at', 'desc')->take(10)->get();
        $statuses = StatusPesanan::orderBy('urutan_tampilan', 'asc')->get();

        return view('dashboard.index', compact(
            'newOrdersToday',
            'totalRegisteredUsers',
            'activeUsersToday',
            'totalSalesToday',
            'recentOrders',
            'statuses'
        ));
    }


    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'id_status_pesanan' => 'required|exists:status_pesanan,id_status_pesanan'
    ]);
    $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();
    $pesanan->id_status_pesanan = $request->id_status_pesanan;
    $pesanan->save();
    $pesanan->refresh(); // refresh agar status berubah
    $namaStatusBaru = $pesanan->statusPesanan->status ?? 'Status Diperbarui';

    return back()->with('success', "Status pesanan berhasil diperbarui menjadi '$namaStatusBaru'.");
}
}
