<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoMaggot | @yield('title', 'Produk')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">
    
    {{-- CSS Kustom: Sesuaikan Path-nya --}}
    <link rel="stylesheet" href="{{ asset('css/esa/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/esa/keranjang.css') }}"> 
</head>
<body>

    @include('components.navbar')

    <div class="main-content-wrapper">
        {{-- Konten Halaman akan di-inject di sini --}}
        @yield('content')
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- JS Kustom: Sesuaikan Path-nya --}}
    <script src="{{ asset('js/esa/keranjang.js') }}"></script>
    <script src="{{ asset('js/esa/script.js') }}"></script>
    @yield('scripts')
</body>
</html>