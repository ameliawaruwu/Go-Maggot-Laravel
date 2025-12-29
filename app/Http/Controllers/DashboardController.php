<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengguna;
use App\Models\DetailPesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $newOrdersToday = Pesanan::whereDate('created_at', $today)->count();
        $totalRegisteredUsers = Pengguna::count();
        
       
        $activeUsersToday = Pengguna::whereDate('last_login', $today)->count();
        
        $totalSalesMonth = Pesanan::whereYear('created_at', $today->year)
            ->whereMonth('created_at', $today->month)
            ->sum('total_harga');


        $filter = $request->get('filter', 'weekly'); 
        
        $dates = collect();
        $salesData = collect();
        $visitorData = collect();

        if ($filter == 'weekly') {
            // Data 7 Hari Terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dates->push($date->format('d M')); // Label Sumbu X (Misal: 25 Nov)
                
                $formattedDate = $date->format('Y-m-d');
                
                // Hitung total penjualan per tanggal
                $salesData->push(Pesanan::whereDate('created_at', $formattedDate)->sum('total_harga'));
                
                // Hitung user baru per tanggal
                $visitorData->push(Pengguna::whereDate('created_at', $formattedDate)->count());
            }
        } elseif ($filter == 'monthly') {
            // Data 12 Bulan Tahun Ini
            for ($i = 1; $i <= 12; $i++) {
                $dates->push(Carbon::create()->month($i)->format('M')); // Label Sumbu X (Jan, Feb, ...)
                
                // Hitung per bulan
                $monthlySales = Pesanan::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', $i)
                    ->sum('total_harga');
                $salesData->push($monthlySales);

                $monthlyVisitor = Pengguna::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', $i)
                    ->count();
                $visitorData->push($monthlyVisitor);
            }
        } elseif ($filter == 'yearly') {
            // Data 5 Tahun Terakhir
            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i)->year;
                $dates->push($year); // Label Sumbu X (2023, 2024, ...)
                
                // Hitung per tahun
                $yearlySales = Pesanan::whereYear('created_at', $year)->sum('total_harga');
                $salesData->push($yearlySales);

                $yearlyVisitor = Pengguna::whereYear('created_at', $year)->count();
                $visitorData->push($yearlyVisitor);
            }
        }

        // ==========================================
        // 3. LOGIKA PRODUK TERLARIS (TOP 5)
        // ==========================================
        // Menghitung jumlah terjual dari tabel detail_pesanan
        // Diurutkan dari jumlah terbanyak (descending)
        $topProducts = DetailPesanan::select('id_produk', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('id_produk')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->with('produk') // Pastikan model DetailPesanan punya relasi ke Produk
            ->get();

        // Memisahkan Nama Produk dan Jumlah Terjual untuk Chart.js
        $productNames = $topProducts->map(function($item) {
            return $item->produk->nama_produk ?? 'Produk Dihapus';
        });
        $productSales = $topProducts->pluck('total_terjual');

      
        return view('dashboard.index', compact(
            'newOrdersToday', 
            'totalRegisteredUsers', 
            'activeUsersToday', 
            'totalSalesMonth', // Mengirim variabel Omzet Bulanan
            'filter', 
            'dates', 
            'salesData', 
            'visitorData',
            'productNames', 
            'productSales'
        ));
    }
}