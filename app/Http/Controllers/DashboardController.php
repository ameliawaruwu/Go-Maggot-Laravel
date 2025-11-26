<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengguna;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $newOrdersToday = Pesanan::whereDate('created_at', $today)->count();
        $totalRegisteredUsers = Pengguna::count();
        $activeUsersToday = Pengguna::whereDate('last_login_at', $today)->count();
        $totalSalesToday = Pesanan::whereDate('created_at', $today)->sum('total_harga');
        $recentOrders = Pesanan::orderBy('created_at', 'desc')->take(10)->get();

        return view('dashboard.index', compact(
            'newOrdersToday',
            'totalRegisteredUsers',
            'activeUsersToday',
            'totalSalesToday',
            'recentOrders'
        ));
    }

    public function updateStatus(Request $request, $id_status)
    {
        $request->validate(['status' => 'required']);
        $pesanan = Pesanan::findOrFail($id_status);
        $pesanan->status = $request->status;
        $pesanan->save();
        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
