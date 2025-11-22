@extends('layouts.detail-produk')

@section('title', $data->nama_produk ?? 'Produk Tidak Ditemukan')

@section('content')

@if (!$data)
    <section class="error-message">
        <div class="container flex">Produk tidak ditemukan.</div>
    </section>
    <div style="text-align:center; margin:20px;">
        <a href="{{ url('/daftar-produk') }}"><button>Kembali ke Produk</button></a>
    </div>
@else

<section>
    <div class="container flex">

        {{-- FOTO PRODUK --}}
        <div class="left">
            <div class="main_image">
                <img src="{{ asset('photo/' . $data->gambar) }}"
                     class="slide"
                     width="360" height="300"
                     alt="{{ $data->nama_produk }}">
            </div>

            <div class="option flex">
                <img src="{{ asset('photo/' . $data->gambar) }}" alt="Thumbnail1">
            </div>
        </div>

        {{-- DETAIL PRODUK --}}
        <div class="right">
            <h3>{{ $data->nama_produk }}</h3>

            <div class="product-rating">
                <div class="stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <span>(5.0)</span>
            </div>

            <h1>
                <small>Rp</small>{{ number_format($data->harga, 0, ',', '.') }}
                / pcs
            </h1>

            <p>{{ $data->deskripsi }}</p>

            <div class="tombol">
                <a href="{{ url('/daftar-produk') }}">
                    <button>Kembali ke Produk</button>
                </a>
            </div>
        </div>

    </div>
</section>

{{-- SPESIFIKASI PRODUK --}}
<div class="bagiandesk">
    <h2>Spesifikasi Produk</h2><br>

    <x-product-spec label="Kategori" :value="$data->kategori" />
    <x-product-spec label="Stok" :value="$data->stok . ' pcs'" />
    <x-product-spec label="Merek" :value="$data->merek" />
    <x-product-spec label="Berat" :value="$data->berat" />
    <x-product-spec label="Harga" :value="'Rp ' . number_format($data->harga, 0, ',', '.')"/>
    <x-product-spec label="Dikirim Dari" :value="$data->pengiriman" />
    <x-product-spec label="Deskripsi" :value="$data->deskripsi" />

</div>

{{-- REVIEW --}}
<div class="bagianakhir">
    <h2>Penilaian Produk</h2><br>

    <div class="rating">
        <div class="stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i>
            <i class="fas fa-star"></i><i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </div>
        <div class="score">5.0 dari 5</div>
    </div>

    <div class="review">
        <div class="user">
            <img src="{{ asset('images/billie.jpg') }}" class="avatar">
            <div class="name">Billie Eilish</div>
        </div>
        <div class="text">Produk bagus banget!</div>
    </div>

    <div class="tombol">
        <button>Lihat Semua Ulasan</button>
    </div>
</div>

@endif

@endsection
