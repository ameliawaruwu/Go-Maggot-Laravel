<div class="navbar">
  <div class="logo">
    <a href="{{ route('home') }}"><span>Go</span>Maggot</a>
  </div>

  <ul class="menu">
    <li><a href="{{ route('home') }}" class="{{ request()->is('home') || request()->is('/') ? 'active' : '' }}">Home</a></li> <!--operator tenary-->
    <li><a href="{{ route('about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About</a></li>
    <li><a href="{{ route('product.index') }}" class="{{ request()->is('products') ? 'active' : '' }}">Products</a></li>
    <li><a href="{{ route('contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>
    <li><a href="{{ route('login') }}" class="{{ request()->is('login') ? 'active' : '' }}">Login</a></li>
  </ul>

  <div class="nav-btn">
    <a href="#"><i class="ri-user-line"></i></a>
    <a href="{{ route('help') }}"><i class="ri-customer-service-2-fill"></i></a>
    <button>Get A Quote</button>
    <i class="ri-menu-line"></i>
  </div>
</div>
