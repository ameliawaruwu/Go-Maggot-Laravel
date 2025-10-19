<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'GoMaggot')</title>

  <link rel="stylesheet" href="{{ asset('css/orry/home.css') }}">
  <link rel="stylesheet" href="{{ asset('css/orry/footer.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">

  @stack('styles')
</head>
<body>
  @include('layouts.navbar')

  <main>
    @yield('content')
  </main>

  @include('layouts.footer')

  <script src="{{ asset('js/orry/script.js') }}" defer></script>
  {{-- Ionicons (dipakai beberapa halaman) --}}
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  @stack('scripts')
</body>
</html>
