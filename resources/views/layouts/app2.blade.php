<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- 🔐 CSRF TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GoMaggot | @yield('title', 'Produk')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">

    <link rel="stylesheet" href="{{ asset('css/esa/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/esa/CO.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/esa/keranjang.css') }}">

    @stack('styles')
</head>
<body>

    {{-- 🔐 Info login global untuk JS --}}
    <script>
        window.isLoggedIn = @json(auth()->check());
        window.userRole   = @json(optional(auth()->user())->role);
    </script>

    @include('layouts.navbarproduk')

    <div class="main-content-wrapper">
        @yield('content')
    </div>

    @include('layouts.footer-esa')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/esa/keranjang.js') }}"></script>
    <script src="{{ asset('js/esa/script.js') }}"></script>

    @stack('scripts')
</body>
</html>
