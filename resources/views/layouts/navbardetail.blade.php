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
    </ul>
    <div class="nav-btn">
        <i class="ri-search-line"></i>
        <a href="{{ url('help') }}"><i class="ri-customer-service-2-fill"></i></a> 
         <button>Get A Quote</button>
    </div>
</div>