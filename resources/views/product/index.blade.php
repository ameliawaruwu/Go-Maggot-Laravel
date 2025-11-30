@extends('layouts.app2')

@section('title', 'Daftar Produk')

@section('content')

<script>
    window.isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    
    @auth
        @php
            $currentUser = auth()->user();
            if (!empty($currentUser->role)) {
                $userRole = $currentUser->role;
            } elseif (method_exists($currentUser, 'pengguna') && $currentUser->pengguna) {
                $userRole = $currentUser->pengguna->role;
            } else {
                try {
                    $pengguna = DB::table('pengguna')
                        ->where('email', $currentUser->email)
                        ->orWhere('id_pengguna', $currentUser->id)
                        ->first();
                    $userRole = $pengguna ? $pengguna->role : 'guest';
                } catch (\Exception $e) {
                    $userRole = 'guest';
                }
            }
        @endphp

        window.userRole = "{{ $userRole ?? 'guest' }}";

        console.log('=== STATUS USER SAAT LOAD ===');
        console.log('Login Status: true');
        console.log('User Role:', window.userRole);
        console.log('==============================');
    @else
        window.userRole = "guest";
        console.log('User logged in as: guest');
    @endauth
</script>

<div class="tulisan">
    <h2>Produk Kami</h2>
</div>

<div class="container">
    <div class="listProduct">
        @foreach ($produk as $p)
            <div class="item">
                <img src="{{ asset('photo/' . $p->gambar) }}" 
                     width="250" height="150"
                     alt="{{ $p->nama_produk }}">

                <h2>{{ $p->nama_produk }}</h2>

                <div class="harga">
                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                </div>

                <div class="stok">
                    Stok: {{ $p->stok }}
                </div>

                <a href="{{ url('/product-detail/' . $p->id_produk) }}">
                    <button class="btn-detail">Detail Produk</button>
                </a>

                <button class="add-to-cart-btn"
                    data-id="{{ $p->id_produk }}"
                    data-nama="{{ $p->nama_produk }}"
                    data-harga="{{ $p->harga }}"
                    data-gambar="{{ asset('photo/' . $p->gambar) }}">
                    Masukkan Keranjang
                </button>
            </div>
        @endforeach
    </div>
</div>

<div class="cartTab">
    <h1>Keranjang Saya</h1>

    <div class="ListCart">
        <p id="loadingCartMessage" style="text-align:center;">Memuat keranjang...</p>
    </div>

    <p id="emptyCartMessage" style="display:none; text-align:center;">Keranjang Anda kosong.</p>

    <div class="btn">
        <button class="close">Tutup</button>
        <button id="checkoutBtn" data-redirect-url="{{ route('checkout.index') }}">
            Checkout
        </button>
    </div>

    <div class="total-price-cart">
        <span>Total Harga:</span>
        <span id="totalPriceDisplay">Rp 0</span>
    </div>
</div>

@endsection
