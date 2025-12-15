<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\Produk;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Artikel;
use App\Models\Galeri;
use App\Models\Faq;
use App\Models\Review;  // Pastikan nama modelnya benar (Reviews atau Review)
use App\Models\StatusPesanan;

class AccessController extends Controller                                                                           
{
    
    public function admin(Request $request)
    {
        $user = $request->user();

        // --- PERBAIKAN DISINI ---
        // Cek apakah role user ADALAH 'admin'. 
        // Jika bukan (misal 'pelanggan'), tolak aksesnya.
        if ($user->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak! Endpoint ini khusus untuk Admin.'
            ], 403); // 403 Forbidden
        }
        // ------------------------

        return response()->json([
            'status'  => 'success',
            'message' => 'Data Dashboard Admin',
            'data'    => [
                'total_pengguna'  => Pengguna::count(),
                'total_pesanan'   => Pesanan::count(),
                'produk'          => Produk::all(),
                'pengguna'        => Pengguna::all(),
                'pesanan'         => Pesanan::orderBy('created_at', 'desc')->get(),
                'detail_pesanan'  => DetailPesanan::all(),
                'pembayaran'      => Pembayaran::all(),
                'artikel'         => Artikel::all(),
                'galeri'          => Galeri::all(),
                'faq'             => Faq::all(),
                'review'          => Review::all(),
                'status_pesanan'  => StatusPesanan::all(),
            ]
        ]);
    }

    
    public function pelanggan(Request $request)
    {
        $user = $request->user();
        $produk = Produk::all();
        $artikel = Artikel::all();
        $faq = Faq::all();
        $galeri = Galeri::all();    
    
        $pesananSaya = Pesanan::where('id_pengguna', $user->id_pengguna)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data Dashboard Pelanggan',
            'data'    => [
            'profil_saya'   => $user,
            'produk'        => $produk, 
            'artikel'       => $artikel,
            'faq'           => $faq,
            'galeri'        => $galeri,
            'pesanan_saya'  => $pesananSaya,
            ]
        ]);
    }
}