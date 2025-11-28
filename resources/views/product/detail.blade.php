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
    <div class="container flex" style="display: flex; gap: 40px; align-items: flex-start;">

        {{-- Foto produk --}}
        <div class="left" style="flex: 1; text-align: center;">
            <div class="main_image">
                <img id="main-product-image" 
                     src="{{ asset('photo/' . ($data->gambar ?? 'placeholder.jpg')) }}"
                     class="slide"
                     width="360" height="300"
                     alt="{{ $data->nama_produk }}"
                     style="object-fit: contain; margin-bottom: 10px;">
            </div>

            {{-- Thumbnail (option flex) --}}
            <div class="option flex product-thumbnails mt-2" style="display: flex; justify-content: center; gap: 10px;">
            
                <img src="{{ asset('photo/' . ($data->gambar ?? 'placeholder.jpg')) }}" 
                     onclick="changeMainImage('{{ asset('photo/' . ($data->gambar ?? 'placeholder.jpg')) }}')"
                     alt="Thumbnail 1" 
                     style="width: 70px; height: 70px; object-fit: cover; border: 1px solid #ccc; cursor: pointer;">

                <img src="{{ asset('images/maggot removebg.png') }}" 
                     onclick="changeMainImage('{{ asset('images/maggot removebg.png') }}')"
                     alt="Thumbnail 2" 
                     style="width: 70px; height: 70px; object-fit: cover; border: 1px solid #ccc; cursor: pointer;">
                
                <img src="{{ asset('images/kompos remove bg.png') }}" 
                     onclick="changeMainImage('{{ asset('images/kompos remove bg.png') }}')"
                     alt="Thumbnail 3" 
                     style="width: 70px; height: 70px; object-fit: cover; border: 1px solid #ccc; cursor: pointer;">
                 
                <img src="{{ asset('images/Bundling Maggot.png') }}" 
                     onclick="changeMainImage('{{ asset('images/Bundling Maggot.png') }}')"
                     alt="Thumbnail 4" 
                     style="width: 70px; height: 70px; object-fit: cover; border: 1px solid #ccc; cursor: pointer;">

                <img src="{{ asset('images/Kandang.png') }}" 
                     onclick="changeMainImage('{{ asset('images/Kandang.png') }}')"
                     alt="Thumbnail 5" 
                     style="width: 70px; height: 70px; object-fit: cover; border: 1px solid #ccc; cursor: pointer;">
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="right" style="flex: 2; padding-left: 20px;">
            <h3>{{ $data->nama_produk ?? 'Nama Produk' }}</h3>

            <div class="product-rating">
                <div class="stars" style="color: gold;">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <span>(5.0) | 1.2k Penilaian | 5k+ Terjual</span>
            </div>

            <h1>
                <small>Rp</small>{{ number_format($data->harga ?? 0, 0, ',', '.') }}
                / pcs
            </h1>
            
            <p>{{ $data->deskripsi_produk ?? 'Deskripsi produk ini belum diisi oleh admin.' }}</p>

            <div class="tombol">
                <a href="{{ url('/daftar-produk') }}">
                    <button style="background-color: #55a630; color: white; border: none; padding: 10px 40px; cursor: pointer; border-radius: 20px; font-weight: bold;">
                        Kembali ke Produk
                    </button>
                </a>
            </div>
        </div>

    </div>
</section>

{{-- Spesifikasi produk --}}
<div class="bagiandesk" style="margin-top: 30px; padding: 15px; border: 1px solid #eee; background-color: #f9f9f9;">
    <h2>Spesifikasi Produk</h2><br>

    <x-product-spec label="Kategori" :value="$data->kategori ?? 'N/A'" />
    <x-product-spec label="Stok" :value="($data->stok ?? 0) . ' pcs'" />
    <x-product-spec label="Merek" :value="$data->merk ?? 'N/A'" />
    <x-product-spec label="Berat" :value="$data->berat ?? 'N/A'" />
    <x-product-spec label="Harga" :value="'Rp ' . number_format($data->harga ?? 0, 0, ',', '.')"/>
    <x-product-spec label="Dikirim Dari" :value="$data->pengiriman ?? 'N/A'" />
    <x-product-spec label="Deskripsi" :value="$data->deskripsi_produk ?? 'N/A'" />

</div>

{{-- Review --}}
<div class="bagianakhir" style="margin-top: 30px; margin-bottom: 50px; padding: 15px; border: 1px solid #eee; background-color: white;">
    <h2>Penilaian Produk</h2><br>

    <div class="rating">
        <div class="stars" style="color: gold;">
            <i class="fas fa-star"></i><i class="fas fa-star"></i>
            <i class="fas fa-star"></i><i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </div>
        <div class="score" style="margin-top: 5px;">5.0 dari 5</div>
    </div>

    <div class="review" style="margin-top: 20px;">
        <div class="user" style="display: flex; align-items: center;">
            <img src="{{ asset('images/billie.jpg') }}" class="avatar" style="width: 40px; height: 40px; border-radius: 50%;">
            <div class="name" style="margin-left: 10px; font-weight: bold;">Billie Eilish</div>
        </div>
        <div class="text" style="margin-left: 50px; margin-top: 5px;">Produk bagus banget!</div>
    </div>

    <div class="review" style="margin-top: 20px;">
        <div class="user" style="display: flex; align-items: center;">
            <img src="{{ asset('images/taylor.jpg') }}" class="avatar" style="width: 40px; height: 40px; border-radius: 50%;">
            <div class="name" style="margin-left: 10px; font-weight: bold;">Taylor</div>
        </div>
        <div class="text" style="margin-left: 50px; margin-top: 5px;">Nenek saya jadi semangat budidaya maggot nih!</div>
    </div>

    <div class="review" style="margin-top: 20px;">
        <div class="user" style="display: flex; align-items: center;">
            <img src="{{ asset('images/selena gomez.jpg') }}" class="avatar" style="width: 40px; height: 40px; border-radius: 50%;">
            <div class="name" style="margin-left: 10px; font-weight: bold;">Selena Gomez</div>
        </div>
        <div class="text" style="margin-left: 50px; margin-top: 5px;">Next beli lagi disini ah!</div>
    </div>

    <div class="review" style="margin-top: 20px;">
        <div class="user" style="display: flex; align-items: center;">
            <img src="{{ asset('images/billie.jpg') }}" class="avatar" style="width: 40px; height: 40px; border-radius: 50%;">
            <div class="name" style="margin-left: 10px; font-weight: bold;">songkang</div>
        </div>
        <div class="text" style="margin-left: 50px; margin-top: 5px;">Maggot nya bersih dan fresh, suka deh!</div>
    </div>

    <div class="review" style="margin-top: 20px;">
        <div class="user" style="display: flex; align-items: center;">
            <img src="{{ asset('images/niki.jpg') }}" class="avatar" style="width: 40px; height: 40px; border-radius: 50%;">
            <div class="name" style="margin-left: 10px; font-weight: bold;">niki zenfanya</div>
        </div>
        <div class="text" style="margin-left: 50px; margin-top: 5px;">Terpercaya sekali toko ini, ayo guys beli disini!</div>
    </div>

    <div class="tombol" style="margin-top: 30px;">
        <button>Lihat Semua Ulasan</button>
    </div>
</div>

@endif

@endsection

@section('scripts')
@parent
<script>
    // Fungsi untuk mengganti gambar utama saat thumbnail diklik
    function changeMainImage(path) {
        document.getElementById('main-product-image').src = path;
    }
</script>
@endsection