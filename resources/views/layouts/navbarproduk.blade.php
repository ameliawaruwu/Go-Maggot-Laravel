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