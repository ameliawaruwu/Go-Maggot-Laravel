@extends('layouts.app')
@section('content')

  <div class="header-wrapper">
    <div class="sosial">
      <h3>FOLLOW US</h3>
      <div class="Line"></div>
      <div class="sosial_icons">
        <i class="ri-instagram-line"></i>
        <i class="ri-twitter-x-line"></i>
        <i class="ri-facebook-circle-fill"></i>
        <i class="ri-whatsapp-line"></i>
      </div>
    </div>

    <div class="header-content">
      <h2>GoMaggot</h2>
      <h1>Lets Grow the Maggots! <br> <span>GoMaggot</span></h1>
      <p>Ini adalah budidaya maggot organik yang memiliki banyak manfaat bagi kehidupan</p>
      <div class="header-btns">
        <button id="myButtonn">Let's Talk<i class="ri-leaf-line"></i></button>
        <button id="myButton">Watch Video</button>
      </div>
    </div>
  </div>

  {{-- ABOUT SECTION --}}
  <div class="about section">
    <div class="about-image">
      <img src="{{ asset('images/uid.jpg') }}" alt="">
    </div>
    <div class="about-content">
      <img src="{{ asset('images/about-icon.png') }}" alt=""><span>ABOUT MAGGOT</span>
      <h1>Enviroment Sustainable <br> <span>Forever Green Future</span></h1>

      <div class="about-card">
        <div class="about-text">
          <h2>Apa itu Maggot?</h2>
          <p>Maggot atau lebih sering disebut sebagai belatung, merupakan larva dari jenis lalat Black Soldier Fly (BSF) atau dalam bahasa latin disebut dengan Hermetia Illucens. Bisa dibilang, maggot adalah larva dari jenis lalat yang semula berasal dari telur, kemudian bermetamorfosis menjadi lalat dewasa.</p>
        </div>
      </div>

      <hr>
      <button id="myButtonnn">More About<i class="ri-leaf-line"></i></button>
    </div>
  </div>

  {{-- VIDEO SECTION --}}
  <div class="content-maggot">
    <div class="video-text">
      <h1>Bagaimana Cara Budidaya Maggot?</h1>
      <center>
        <p>Maggot akan siap pakai setelah melalui beberapa proses dengan tahap persiapan kandang, memancing lalat BSF, memlihara larva lalu yang terakhir memanen maggot setelah 10-14 hari dengan memisahkan dari media pakan.</p>
      </center>
    </div>

    <div class="video-section">
      <iframe width="1300" height="560"
              src="https://www.youtube.com/embed/FPALstZU7fI?si=TAW_biOOluFvxfmE"
              title="budidaya maggot from youtube" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
  </div>

  {{-- PRODUCT SECTION --}}
  <section class="products">
    <h1>Our Best Seller Products</h1>
    <div class="all-products">
      <div class="product">
        <img src="{{ asset('images/coba 1.jpg') }}" alt="ini adalah gamabr produk 1">
        <div class="product-info">
          <h4 class="product-title">Maggot Siap Pakai</h4>
          <p class="product-price">Rp. 70.000/150gr</p>
          <a class="product-btn" href="{{ route('product.index') }}">Buy Now</a>
        </div>
      </div>

      <div class="product">
        <img src="{{ asset('images/produk 2.jpg') }}" alt="">
        <div class="product-info">
          <h4 class="product-title">Paket Bundling</h4>
          <p class="product-price">Rp. 170.000</p>
          <a class="product-btn" href="{{ route('product.index') }}">Buy Now</a>
        </div>
      </div>

      <div class="product">
        <img src="{{ asset('images/coba 2.jpg') }}" alt="">
        <div class="product-info">
          <h4 class="product-title">Pupuk</h4>
          <p class="product-price">Rp. 25.000/500gr</p>
          <a class="product-btn" href="{{ route('product.index') }}">Buy Now</a>
        </div>
      </div>

      <div class="product">
        <img src="{{ asset('images/coba.jpg') }}" alt="">
        <div class="product-info">
          <h4 class="product-title">Bibit Maggot</h4>
          <p class="product-price">Rp. 50.000/200gr</p>
          <a class="product-btn" href="{{ route('product.index') }}">Buy Now</a>
        </div>
      </div>
    </div>
  </section>

  {{-- TESTIMONIAL SECTION --}}
  <section id="testimonials">
    <div class="testimonial-heading">
      <h1>Our Testimonials</h1>
    </div>

    <div class="testimonial-box-container">
      {{-- BOX-1 --}}
      <div class="testimonial-box">
        <div class="box-top">
          <div class="profile">
            <div class="profile-img">
              <img src="{{ asset('images/profile 1.jpg') }}" alt="">
            </div>
            <div class="name-user">
              <strong>Sullyoon</strong>
              <span>@Sullyoonadward</span>
            </div>
          </div>
          <div class="reviews">
            <i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="far fa-star">★</i>
          </div>
        </div>
        <div class="client-comment">
          <p>Saya sangat puas dengan produk maggot ini. Maggot yang saya beli sangat berkualitas dan membantu mempercepat proses pengomposan di kebun saya.
            Hasilnya, tanaman saya tumbuh lebih subur dan sehat. Toko ini akan menjadi langganan saya ke depannya. Terima kasih!</p>
        </div>
      </div>

      {{-- BOX-2 --}}
      <div class="testimonial-box">
        <div class="box-top">
          <div class="profile">
            <div class="profile-img">
              <img src="{{ asset('images/profile  2.jpg') }}" alt="">
            </div>
            <div class="name-user">
              <strong>Pharita</strong>
              <span>@KimPharitaa</span>
            </div>
          </div>
          <div class="reviews">
            <i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="far fa-star">★</i>
          </div>
        </div>
        <div class="client-comment">
          <p>Pupuk maggot ini luar biasa! Saya menggunakannya untuk tanaman sayuran di kebun saya, dan hasilnya sangat memuaskan.
            Tanaman tumbuh lebih cepat dan lebih kuat. Saya sangat merekomendasikan pupuk ini kepada siapa saja yang ingin meningkatkan kualitas tanamannya.</p>
        </div>
      </div>

      {{-- BOX-3 --}}
      <div class="testimonial-box">
        <div class="box-top">
          <div class="profile">
            <div class="profile-img">
              <img src="{{ asset('images/profile  3.jpg') }}" alt="">
            </div>
            <div class="name-user">
              <strong>Daniel</strong>
              <span>@DanielRedclief</span>
            </div>
          </div>
          <div class="reviews">
            <i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="far fa-star">★</i>
          </div>
        </div>
        <div class="client-comment">
          <p>Saya baru saja mencoba produk maggot ini dan hasilnya luar biasa! Maggot yang saya terima sangat aktif dan sehat. Mereka membantu mengurangi
            limbah organik di rumah saya dengan cepat. Saya sangat merekomendasikan produk ini kepada siapa saja yang peduli dengan lingkungan.</p>
        </div>
      </div>

      {{-- BOX-4 --}}
      <div class="testimonial-box">
        <div class="box-top">
          <div class="profile">
            <div class="profile-img">
              <img src="{{ asset('images/profile  4.jpg') }}" alt="Jeno">
            </div>
            <div class="name-user">
              <strong>Jeno</strong>
              <span>@Jenowrrl</span>
            </div>
          </div>
          <div class="reviews">
            <i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="fas fa-star">★</i><i class="far fa-star">★</i>
          </div>
        </div>
        <div class="client-comment">
          <p>Kandang maggot yang saya beli sangat praktis dan mudah digunakan. Desainnya memungkinkan sirkulasi udara yang baik, sehingga maggot tumbuh dengan optimal.
            Selain itu, kandang ini juga mudah dibersihkan. Saya sangat senang dengan pembelian ini</p>
        </div>
      </div>
    </div>

    {{-- BUTTON FEEDBACK: beda perilaku kalau login / belum --}}
    @auth
      <a class="testimoni-btn" href="{{ route('feedback') }}">
        Give Us Feedback
      </a>
    @else
      <a class="testimoni-btn"
         href="{{ route('login', ['redirect' => route('feedback')]) }}">
        Give Us Feedback
      </a>
    @endauth
  </section>

  {{-- GALERY --}}
  <div class="portofolio-wrapper"></div>
  <div class="portofolio-btn">
    <a href="{{ route('gallery.gallery') }}"></a>
    <button id="myButonnnn">Let's See<i class="ri-leaf-line"></i></button>
  </div>

@endsection
