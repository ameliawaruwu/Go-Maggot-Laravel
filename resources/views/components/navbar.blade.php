<<<<<<< HEAD
<div class="navbar">
    <div class="logo">
        <a href="#"><span>Go</span>Maggot</a>
    </div>
    <ul class="menu">
        <li><a href="{{ url('home') }}">Home</a></li>
        <li><a href="{{ url('about') }}">About</a></li>
        <li><a href="{{ route('product.index') }}">Products</a></li>
        <li><a href="{{ url('contact') }}">Contact</a></li>
        <li><a href="{{ url('login') }}">Login</a> </li>
        <div class="icon-cart">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="300px" height="100px" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
            </svg> 
            <span>1</span> 
        </div>
    </ul>
    <div class="nav-btn">
        <i class="ri-search-line"></i>
        <a href="{{ url('help') }}"><i class="ri-customer-service-2-fill"></i></a> 
         <button>Get A Quote</button>
    </div>
</div>
=======
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-success" href="#">GoMaggot</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#products">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
      </ul>
      <a href="#" class="btn btn-success ms-3">Get A Quote</a>
    </div>
  </div>
</nav>
>>>>>>> 5f0018e435478df33decd6594960e8e9ad3c0d73
