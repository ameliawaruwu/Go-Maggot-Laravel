<div class="footer section">
  <div class="footer_top">
    <h1>Stay With us On Social</h1>
    <div class="footer_follow">
      <h2>FOLLOW US :</h2>
      <div class="footer_social">
        <i class="ri-instagram-line"></i>
        <i class="ri-twitter-x-line"></i>
        <i class="ri-facebook-circle-fill"></i>
        <i class="ri-whatsapp-line"></i>
      </div>
    </div>
  </div>

  <div class="footer_grid">

    <div class="footer_col">
      <div class="logo">
        <a href="{{ route('home') }}"><span>Go</span>Maggot</a>
      </div>
      <p>Proactively restore timely alignments after client enviromentals</p>
      <a href="{{ route('contact') }}"><span> - </span> Contact</a>
      <h3><i class="ri-phone-fill"></i>+087928364735</h3>
      <h3><i class="ri-mail-line"></i>gomaggot@gmail.com</h3>
    </div>

    <div class="footer_col">
      <h2>Company</h2>
      <a href="{{ route('home') }}"><i class="ri-arrow-right-s-line"></i>Home</a>
      <a href="{{ route('about') }}"><i class="ri-arrow-right-s-line"></i>About</a>
      <a href="{{ route('product.index') }}"><i class="ri-arrow-right-s-line"></i>Products</a>
      <a href="{{ route('study.index') }}"><i class="ri-arrow-right-s-line"></i>Blog</a>

      @auth
      <form action="{{ route('logout') }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit">
            <i class="ri-arrow-right-s-line"></i>Logout
          </button>
      </form>
      @else
      <a href="{{ route('login') }}"><i class="ri-arrow-right-s-line"></i>Login</a>
      @endauth

    </div>

    <div class="footer_col">
      <h2>Quick Links</h2>
      <a href="{{ route('contact') }}"><i class="ri-arrow-right-s-line"></i>Contact Us</a>
      <a href="{{ route('qna') }}"><i class="ri-arrow-right-s-line"></i>FAQ's</a>
      <a href="{{ route('help') }}"><i class="ri-arrow-right-s-line"></i>Help Center</a>
      <a href="{{ route('feedback') }}"><i class="ri-arrow-right-s-line"></i>Feedback</a>
      <a href="{{ route('portfolio') }}"><i class="ri-arrow-right-s-line"></i>Portofolios</a>
    </div>

    <div class="footer_col">
      <h2>Newsletter</h2>
      <p>Subscribe Our NewsLetter</p>
      <input type="text" placeholder="Enter Email">
      <button>Subscribe <i class="ri-arrow-drop-right-line"></i></button>
    </div>

  </div>

  <div class="footer_bottom">
    <p>© 2024 GoMaggot. All Right Reserved <a href="#">maggotinfo.com</a></p>
  </div>
</div>
