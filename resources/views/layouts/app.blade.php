<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoMaggot | @yield('title', 'Produk')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">
    
    {{-- CSS Kustom Utama --}}
    <link rel="stylesheet" href="{{ asset('css/esa/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/esa/keranjang.css') }}"> 
    
    {{-- Tambahkan CSS untuk halaman Checkout --}}
    {{-- Pastikan file CO.css Anda diletakkan di public/css/co.css atau sesuaikan path-nya --}}
    <link rel="stylesheet" href="{{ asset('css/esa/checkOut.css') }}"> 

    {{-- Fonts yang dibutuhkan (dari kode lama) --}}
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>
<body>

    @include('layouts.navbarproduk')

    <div class="main-content-wrapper">
        {{-- Konten Halaman akan di-inject di sini --}}
        @yield('content')
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- JS Kustom Utama --}}
    <script src="{{ asset('js/esa/keranjang.js') }}"></script>
    <script src="{{ asset('js/esa/script.js') }}"></script>
    
    {{-- Script khusus untuk halaman tertentu, seperti yang ada di kode checkout sebelumnya --}}
    @yield('scripts')
</body>
</html>
