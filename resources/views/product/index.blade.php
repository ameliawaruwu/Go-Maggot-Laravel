@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')

<div class="tulisan">
    <h2>Produk Kami</h2>
</div>

<div class="container">
    <header></header>
    <div class="listProduct"> 
        @foreach ($products as $product)
            @php
                $productData = json_encode([
                    'idproduk' => $product['idproduk'],
                    'namaproduk' => $product['namaproduk'],
                    'harga' => (float)$product['harga'],
                    'gambar' => asset('images/' . $product['gambar']), 
                    'stok' => (int)$product['stok']
                ]);
            @endphp
        
            <div class="item">
                <img src="{{ asset('images/' . $product['gambar']) }}" width="250px" height="150px" alt="{{ $product['namaproduk'] }}">
                <h2>{{ $product['namaproduk'] }}</h2><br><br>
                <div class="harga">Rp.{{ number_format($product['harga'], 0, ',', '.') }}</div>
                <div class="stok">Stok: {{ $product['stok'] }}</div>
                
                <a href="{{ route('product.detail', $product['idproduk']) }}">
                    <button class="Masukan Keranjang">
                        Detail Produk
                    </button>
                </a>
                
    <button 
    class="add-to-cart-btn"
    data-product-data="{{ json_encode([
        'idproduk' => $product['idproduk'],
        'namaproduk' => $product['namaproduk'],
        'harga' => (float) $product['harga'],
        'gambar' => asset('images/' . $product['gambar'])
    ]) }}">
    Masukkan Keranjang
    </button>
            </div>
        @endforeach
    </div>
</div>

<div class="cartTab">
    <h1>Keranjang Saya</h1>
    <div class="ListCart">
        <p id="loadingCartMessage" style="text-align: center; color: gray;">Memuat keranjang...</p>
    </div>

    <p id="emptyCartMessage" style="display: none; text-align: center;">Keranjang Anda kosong.</p>

    <div class="btn">
        <button class="close">Tutup</button>
      <button id="checkoutBtn" data-url="{{ route('checkout.index') }}">Checkout</button>

<script>
document.getElementById('checkoutBtn').addEventListener('click', function() {
  window.location.href = this.dataset.url;
});
</script>



    </div>
    <div class="total-price-cart">
        <span>Total Harga:</span>
        <span id="totalPriceDisplay">Rp.0</span>
    </div>
</div>

@endsection