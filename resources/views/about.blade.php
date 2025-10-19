@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/orry/about.css') }}">
@endpush

@section('content')
  <div class="about section">
    <div class="about-image">
      <img src="{{ asset('images/about11-removebg-preview.png') }}" alt="About Illustration">
    </div>
    <div class="about-content">
      <h1><span>OUR STORY</span></h1>
      <div class="about-card">
        <div class="about-text">
          <h2>Kenapa Maggot harus di Budidayakan?</h2>
          <p>Budidaya maggot layak dikembangkan karena memberikan manfaat yang signifikan dalam pengelolaan limbah organik,
            penyediaan sumber protein alternatif yang ramah lingkungan, serta peluang ekonomi yang menjanjikan. Selain itu,
            budidaya ini mendukung ketahanan pangan, mengurangi dampak lingkungan, dan mendorong keberlanjutan. Dengan keunggulan
            tersebut, maggot dapat menjadi solusi inovatif untuk berbagai tantangan di sektor lingkungan, peternakan, dan ekonomi masyarakat.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="story section">
    <div class="story-image">
      <img src="{{ asset('images/abt2.png') }}" alt="Story Image">
    </div>
    <div class="story-content">
      <h1><span>OUR STORY</span></h1>
      <div class="story-card">
        <div class="story-text">
          <p>GoMaggot berdiri pada tahun 2024 yang bergerak di bidang inovasi pengolahan sampah dan integrated urban farming
            yang menggunakan cara alami yang dimana menyediakan produk-produk yang memudahkan masyarakat untuk melakukan aktivitas
            pengolahan sampah sisa makanan langsung dari rumah.</p>
        </div>
      </div>
      <hr>
      <button id="aboutMoreBtn">More About<i class="ri-leaf-line"></i></button>
    </div>
  </div>
@endsection
