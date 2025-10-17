@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')

<div class="tulisan">
    <h2>Produk Kami</h2>
</div>

<div class="container">
    <header></header>
    {{-- Ubah div listProduct agar bisa menggunakan CSS Grid/Flexbox atau Bootstrap Grid --}}
    <div class="listProduct"> 
        
        {{-- Mulai Perulangan --}}
        @foreach ($products as $product)
            @php
                // Siapkan data produk dalam format JSON untuk JavaScript (Keranjang)
                // Menggunakan array access [] karena data dari Controller adalah array
                $productData = json_encode([
                    'idproduk' => $product['idproduk'],
                    'namaproduk' => $product['namaproduk'],
                    'harga' => (float)$product['harga'],
                    // Pastikan path gambar benar di folder public/
                    'gambar' => asset('Admin-HTML/images/' . $product['gambar']), 
                    'stok' => (int)$product['stok']
                ]);
            @endphp
        
            <div class="item">
                <img src="{{ asset('images/esa/' . $product['gambar']) }}" width="250px" height="150px" alt="{{ $product['namaproduk'] }}">
                <h2>{{ $product['namaproduk'] }}</h2><br><br>
                <div class="harga">Rp.{{ number_format($product['harga'], 0, ',', '.') }}</div>
                <div class="stok">Stok: {{ $product['stok'] }}</div>
                
                {{-- Link Detail Produk menggunakan Route Laravel --}}
                <a href="{{ route('product.show', $product['idproduk']) }}">
                    <button class="Masukan Keranjang">
                        Detail Produk
                    </button>
                </a>
                
                {{-- Tombol Masukan Keranjang (gunakan data hardcoded untuk JS) --}}
                <button 
                    class="Masukan Keranjang add-to-cart-btn" 
                    data-product-data='{{ htmlspecialchars($productData, ENT_QUOTES, 'UTF-8') }}'
                    @if ($product['stok'] < 1) disabled @endif
                >
                    Masukan Keranjang
                </button>
            </div>
        @endforeach
        {{-- Akhir Perulangan --}}

    </div>
</div>

{{-- Cart Tab (Dipindahkan ke sini agar mudah dilihat, atau bisa juga dijadikan Component) --}}
<div class="cartTab">
    <h1>Keranjang Saya</h1>
    <div class="ListCart">
        <p id="loadingCartMessage" style="text-align: center; color: gray;">Memuat keranjang...</p>
    </div>

    <p id="emptyCartMessage" style="display: none; text-align: center;">Keranjang Anda kosong.</p>

    <div class="btn">
        <button class="close">Tutup</button>
        <button class="checkOut">Check Out</button>
    </div>
    <div class="total-price-cart">
        <span>Total Harga:</span>
        <span id="totalPriceDisplay">Rp.0</span>
    </div>
</div>

@endsection