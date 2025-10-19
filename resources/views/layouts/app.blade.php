{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ $title ?? 'GoMaggot' }}</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.css">
  <link rel="stylesheet" href="{{ asset('css/orry/home.css') }}">
  <link rel="stylesheet" href="{{ asset('css/orry/footer.css') }}">

 
  @stack('styles')
</head>
<body>

  @include('layouts.navbar')   
  
  <main style="min-height: 60vh;">
    @yield('content')
  </main>

  @include('layouts.footer')   

  
  <script src="{{ asset('js/orry/script.js') }}"></script>
  <script src="{{ asset('js/esa/keranjang.js') }}"></script>

  @stack('scripts')
</body>
</html>
