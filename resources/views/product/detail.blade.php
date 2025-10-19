@extends('layouts.detail-produk')

@section('title', $productName ?? 'Produk Tidak Ditemukan')

@section('content')

@if (!isset($productName))
    <section class="error-message">
        <div class="container flex">Produk tidak ditemukan atau ID tidak valid.</div>
    </section>
    <div class="tombol" style="text-align: center; margin: 20px;">
        <a href="{{ url('/daftar-produk') }}">
            <button>Kembali ke Daftar Produk</button>
        </a>
    </div>
@else
    <section>
        <div class="container flex">
            <div class="left">
                <div class="main_image">
                    <img src="{{ asset('images/esa/' . $productImage) }}" class="slide" width="360" height="300" alt="{{ $productName }}">
                </div>
                <div class="option flex">
                    <img src="{{ asset('images/esa/kompos remove bg.png') }}" onclick="img('image/p1.jpg')" alt="Thumbnail 1">
                    <img src="{{ asset('images/esa/Bibit-remove bg.png') }}" onclick="img('image/p2.jpg')" alt="Thumbnail 2">
                    <img src="{{ asset('images/esa/Bundling Maggot.png') }}" onclick="img('image/p3.jpg')" alt="Thumbnail 3">
                    <img src="{{ asset('images/esa/maggot removebg.png') }}" onclick="img('image/p4.jpg')" alt="Thumbnail 4">
                </div>
            </div>
            <div class="right">
                <h3>{{ $productName }}</h3>
                <div class="product-rating">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span>(5.0)</span>
                </div>

                <div class="info">
                    <span>900 Penilaian</span>
                    <span>1RB+ Terjual</span>
                </div>
                <div class="button">
                    <a href="laporan.html">Laporkan</a>
                </div>

                <h1><small>Rp</small>{{ number_format($productPrice, 0, ',', '.') }} / pcs</h1>
                <p>{{ $productDescription }}</p>
                <div class="tombol">
                    <a href="{{ url('/daftar-produk') }}">
                        <button>Kembali ke Produk</button>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="bagianprofil">
        <div class="profile">
            <div class="profile-image">
                <img src="{{ asset('images/esa/SS LOGO.png') }}" alt="GoMaggot Logo" width="100" height="70">
            </div>
            <div class="profile-info">
                <h2>GoMaggot</h2>
            </div>
        </div>
        </div>

    <div class="bagiandesk">
        <h2>Spesifikasi Produk</h2><br>

        <x-product-spec label="Kategori" :value="$productCategory" />
        
        <x-product-spec label="Stok" 
            :value="($productStock > 0) ? $productStock . ' pcs' : 'Habis'" />
        
        <x-product-spec label="Merek" :value="$productBrand" />
        <x-product-spec label="Masa Penyimpanan" :value="$productSave" />
        <x-product-spec label="Berat" :value="$productWeight" />
        <x-product-spec label="Harga" :value="'Rp ' . number_format($productPrice, 0, ',', '.') . ' / pcs'" />
        <x-product-spec label="Dikirim Dari" :value="$productPengiriman" />
        <x-product-spec label="Deskripsi" :value="$productDescription" />
        
    </div>

    <div class="bagianakhir">
    <h2>Penilaian Produk</h2><br>
    <div class="rating">
        <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </div>
        <div class="score">5.0 dari 5</div>
    </div><br><br>
    <div class="review">
        <div class="user">
             <img src="{{ asset('images/esa/billie.jpg') }}" alt="Avatar" class="avatar">
            <div class="name">Billie Eilish</div>
        </div>
        <div class="text">Kandang nya ringan, saya kira akan berat woww!</div>
    </div>
    <div class="review">
        <div class="user">
            <img src="{{ asset('images/esa/jungkook.jpg') }}" alt="Avatar" class="avatar">
            <div class="name">Jeon Jungkook</div>
        </div>
        <div class="text">Ternak ayam saya sangat lahap makanya berkat maggot ini.</div>
    </div>
    <div class="review">
        <div class="user">
            <img src="{{ asset('images/esa/cha eun woo.jpg') }}"alt="Avatar" class="avatar">
            <div class="name">Cha Eun Woo</div>
        </div>
        <div class="text">Toko ini memang tidak pernah mengecewakan.</div>
    </div>
        <div class="review">
        <div class="user">
            <img src="{{ asset('images/esa/taylor.jpg') }}"alt="Avatar" class="avatar">
            <div class="name">Taylor Swift</div>
        </div>
        <div class="text">Tanaman ku tumbuh subur berkat pupuk maggot ini, terimakasih GoMaggot!</div>
    </div>
    <div class="review">
          <div class="user">
            <img src="{{ asset('images/esa/olivia.jpg') }}"alt="Avatar" class="avatar">
            <div class="name">Olivia Rodigro</div>
          </div>
          <div class="text">Wah, harganya terjangkau sekali saya suka! Next beli lagi ah..</div>
        </div>

    <div class="tombol">
        <button>Lihat Semua Ulasan</button>
    </div>
</div>

@endif

@endsection

@push('scripts')
    {{-- <script src="{{ asset('js/nama-file.js') }}"></script> --}}
@endpush