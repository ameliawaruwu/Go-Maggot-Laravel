<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- 🔐 CSRF TOKEN untuk semua form & fetch() --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? 'GoMaggot' }}</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">
  <link rel="stylesheet" href="{{ asset('css/orry/home.css') }}">
  <link rel="stylesheet" href="{{ asset('css/orry/footer.css') }}">

  @stack('styles')
</head>
<body>

  {{-- 🔐 Info login global untuk JS (dipakai keranjang.js) --}}
  <script>
      window.isLoggedIn = @json(auth()->check());
      window.userRole   = @json(optional(auth()->user())->role);
  </script>

  @include('layouts.navbar')

  <main style="min-height: 60vh;">
    @yield('content')
  </main>

  @include('layouts.footer')

  {{-- Script umum --}}
  <script src="{{ asset('js/orry/script.js') }}"></script>

  {{-- Kalau memang mau, keranjang bisa dibatasi hanya di halaman tertentu pakai @stack
       tapi sementara kita keep seperti punyamu --}}
  <script src="{{ asset('js/esa/keranjang.js') }}"></script>

  @stack('scripts')
</body>
</html>
