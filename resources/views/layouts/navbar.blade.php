<div class="navbar">
  <div class="logo">
    <a href="{{ route('home') }}"><span>Go</span>Maggot</a>
  </div>

  <ul class="menu">
    <li><a href="{{ route('home') }}" class="{{ request()->is('home') || request()->is('/') ? 'active' : '' }}">Home</a></li>
    <li><a href="{{ route('about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About</a></li>
    <li><a href="{{ route('product.index') }}" class="{{ request()->is('daftar-produk') ? 'active' : '' }}">Products</a></li>
    <li><a href="{{ route('contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>

    {{-- Login tetap di menu hanya saat guest --}}
    @guest
      <li><a href="{{ route('login') }}" class="{{ request()->is('login') ? 'active' : '' }}">Login</a></li>
    @endguest
  </ul>

  <div class="nav-btn">
    {{-- Profile icon hanya saat sudah login --}}
    @auth
      @php
        $pg = \App\Models\Pengguna::where('email', auth()->user()->email)->first();
        $hasPhoto = $pg && $pg->foto_profil;
      @endphp

      <a href="{{ route('profile.show') }}" class="nav-avatar" aria-label="My Profile">
        @if($hasPhoto)
          <img src="{{ asset('storage/' . $pg->foto_profil) }}" alt="Profile">
        @else
          {{-- sebelum isi foto profil: pakai ikon profile lama --}}
          <i class="ri-user-line"></i>
        @endif
      </a>
    @endauth

    <a href="{{ route('help') }}"><i class="ri-customer-service-2-fill"></i></a>
    <button type="button">Get A Quote</button>
    <i class="ri-menu-line"></i>
  </div>
</div>
