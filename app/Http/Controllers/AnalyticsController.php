<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengguna;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // --- 1. SALES & VISITORS (Data 7 Hari Terakhir) ---
        $dates = collect();
        $salesData = collect();
        $visitorData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('d M');

            // Simpan Label Tanggal
            $dates->push($displayDate);

            // Hitung Total Penjualan per Tanggal
            $dailySales = Pesanan::whereDate('created_at', $formattedDate)
                // Opsional: Hanya hitung yang statusnya valid (misal ID status selesai)
                // .where('id_status_pesanan', 'SP004') 
                ->sum('total_harga');
            $salesData->push($dailySales);

            // Hitung User Baru Mendaftar per Tanggal (Visitor Proxy)
            $dailyVisitors = Pengguna::whereDate('created_at', $formattedDate)->count();
            $visitorData->push($dailyVisitors);
        }

        // --- 2. PRODUK TERLARIS (Top Products) ---
        // Karena belum ada tabel 'detail_pesanan', kita ambil 5 produk dengan stok paling sedikit
        // (Asumsi: Stok sedikit = Laku keras) atau ambil random 5 produk untuk demo
        $topProducts = Produk::orderBy('stok', 'asc')->take(5)->get();
        $productNames = $topProducts->pluck('nama_produk');
        $productStock = $topProducts->pluck('stok');

        // --- 3. STATUS ORDER (Pie Chart) ---
        $orderStatus = Pesanan::select('id_status_pesanan', DB::raw('count(*) as total'))
            ->groupBy('id_status_pesanan')
            ->with('status') // Pastikan relasi ini ada di Model Pesanan
            ->get();

        $statusLabels = $orderStatus->map(function($item) {
            return $item->statusPesanan->status ?? $item->id_status_pesanan;
        });
        $statusCounts = $orderStatus->pluck('total');

        return view('analytics.index', compact(
            'dates', 
            'salesData', 
            'visitorData', 
            'productNames', 
            'productStock', 
            'statusLabels', 
            'statusCounts'
        ));
    }
}