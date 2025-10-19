<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GoMaggot')</title>

    {{-- CSS Libraries (Tidak ada duplikasi) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css"> 
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    {{-- CSS Kustom (Pastikan Orry dimuat setelah Esa agar bisa menimpa/override) --}}
    <link rel="stylesheet" href="{{ asset('css/esa/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/esa/CO.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/esa/keranjang.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/orry/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orry/footer.css') }}">

    @stack('styles')
</head>
<body>
    {{-- HEADER/NAVBAR --}}
    @include('layouts.navbar')
    @include('layouts.navbarproduk')
    
    {{-- KONTEN UTAMA (Duplikasi @yield('content') dihapus) --}}
    <main class="main-content-wrapper">
        @yield('content') 
    </main>

    {{-- FOOTER (Duplikasi @include('layouts.footer') dihapus) --}}
    @include('layouts.footer')

    {{-- JAVASCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- JS Kustom Esa --}}
    <script src="{{ asset('js/esa/keranjang.js') }}"></script>
    <script src="{{ asset('js/esa/script.js') }}"></script>
    
    {{-- JS Kustom Orry --}}
    <script src="{{ asset('js/orry/script.js') }}" defer></script>
    
    {{-- Ionicons (Hanya sekali) --}}
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>