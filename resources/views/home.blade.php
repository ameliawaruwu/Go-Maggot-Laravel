@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- HEADER SECTION --}}
<section class="text-center text-white" id="home" 
    style="background-image: url('{{ asset('images/header-bg.jpg') }}'); background-size:cover; background-position:center; padding:120px 0;">
    <div class="container">
        <h2 class="fw-bold text-success">GoMaggot</h2>
        <h1 class="display-4 fw-bold">Let's Grow the Maggots!<br><span class="text-success">GoMaggot</span></h1>
        <p class="lead">Ini adalah budidaya maggot organik yang memiliki banyak manfaat bagi kehidupan</p>
        <div class="mt-4">
            <a href="#" class="btn btn-success me-2">Let's Talk <i class="ri-leaf-line"></i></a>
            <a href="#" class="btn btn-outline-light">Watch Video</a>
        </div>
    </div>
</section>

{{-- ABOUT SECTION --}}
<section id="about" class="py-5">
    <div class="container d-flex flex-wrap align-items-center">
        <div class="col-md-6 mb-4">
            <img src="{{ asset('images/uid.jpg') }}" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h5 class="text-success">ABOUT MAGGOT</h5>
            <h2 class="fw-bold">Environment Sustainable <br><span class="text-success">Forever Green Future</span></h2>
            <p>Maggot atau lebih sering disebut sebagai belatung, merupakan larva dari jenis lalat Black Soldier Fly (BSF)...</p>
            <a href="#" class="btn btn-success">More About <i class="ri-leaf-line"></i></a>
        </div>
    </div>
</section>

{{-- VIDEO SECTION --}}
<section class="bg-light py-5 text-center">
    <div class="container">
        <h2 class="fw-bold mb-4">Bagaimana Cara Budidaya Maggot?</h2>
        <p class="text-muted mb-4">Maggot akan siap pakai setelah melalui beberapa proses...</p>
        <div class="ratio ratio-16x9">
            <iframe src="https://www.youtube.com/embed/FPALstZU7fI?si=TAW_biOOluFvxfmE"
                title="Budidaya Maggot" allowfullscreen></iframe>
        </div>
    </div>
</section>

{{-- PRODUCT SECTION --}}
<section id="products" class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-5">Our Best Seller Products</h2>
        <div class="row g-4">
            @foreach($products as $p)
                <div class="col-md-3">
                    <div class="card shadow h-100">
                        <img src="{{ asset($p['image']) }}" class="card-img-top" alt="{{ $p['title'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $p['title'] }}</h5>
                            <p class="card-text text-success fw-bold">{{ $p['price'] }}</p>
                            <a href="#" class="btn btn-outline-success">Buy Now</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
