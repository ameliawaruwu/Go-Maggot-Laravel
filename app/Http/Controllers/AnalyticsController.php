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
        $dates = collect();
        $salesData = collect();
        $visitorData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('d M');

            $dates->push($displayDate);

            // Hitung Total Penjualan per Tanggal
            $dailySales = Pesanan::whereDate('created_at', $formattedDate)
                ->sum('total_harga');
            $salesData->push($dailySales);
            $dailyVisitors = Pengguna::whereDate('created_at', $formattedDate)->count();
            $visitorData->push($dailyVisitors);
        }

        $topProducts = Produk::orderBy('stok', 'asc')->take(5)->get();
        $productNames = $topProducts->pluck('nama_produk');
        $productStock = $topProducts->pluck('stok');
        $orderStatus = Pesanan::select('id_status_pesanan', DB::raw('count(*) as total'))
            ->groupBy('id_status_pesanan')
            ->with('status') 
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