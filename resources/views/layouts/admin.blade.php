<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    
    
    @stack('styles')
</head>
 <body>

    <div class="toast-container position-fixed end-0 p-3" 
         style="top: 200px; right: 20px; z-index: 9999;"> 
         {{-- Nilai top ditingkatkan menjadi 100px untuk amannya --}}
        @yield('toast_component') 
    </div>
    @include('layouts.sidebar-admin')

    
    
    <section id="content">
        @include('layouts.navbar-admin')
        
        <main class="py-4 px-md-4">
            @yield('content')
        </main>
        
        
        @include('layouts.footer-admin') 

    </section>
    

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/scriptadmin.js') }}"></script>
    <script src="{{ asset('js/admin/script1.js') }}"></script>
    <script src="{{ asset('js/admin/manage-product.js') }}"></script>
    
    
    
    @stack('scripts') 

    

    </section>

</body>
</html>