@extends('layouts.app')

@section('title', 'GoMaggot')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/orry/feedback.css') }}">
@endpush

@section('content')
<section class="feedback-section">
  <div class="container">
    <h2 class="header">PRODUCT REVIEWS</h2>
    <h3>Product Quality</h3>

    {{-- Pesan sukses --}}
    @if (session('success'))
      <div style="margin-bottom:15px; padding:10px; background:#d4edda; color:#155724; border-radius:4px;">
        {{ session('success') }}
      </div>
    @endif

    {{-- Pesan error validasi --}}
    @if ($errors->any())
      <div style="margin-bottom:15px; padding:10px; background:#f8d7da; color:#721c24; border-radius:4px;">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="stars" id="product-rating">
      <span class="star" data-value="1">★</span>
      <span class="star" data-value="2">★</span>
      <span class="star" data-value="3">★</span>
      <span class="star" data-value="4">★</span>
      <span class="star" data-value="5">★</span>
    </div>

    <form action="{{ route('feedback.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <input type="hidden" name="rating_produk" id="rating_produk">
      <input type="hidden" name="rating_seller" id="rating_seller">

      <div class="input-group">
        <label for="review">Share your rating:</label>
        <textarea id="review" name="review" rows="4" placeholder="Share more thoughts on the product..."></textarea>
      </div>

      <div class="media-buttons">
        <label class="media-button">
          <i class="ri-image-line"></i>
          Add Photo
          <input type="file" name="photo" accept="image/*">
        </label>
        <label class="media-button">
          <i class="ri-video-line"></i>
          Add Video
          <input type="file" name="video" accept="video/*">
        </label>
      </div>

      <div class="additional-info">
        <div class="input-group">
          <label>Condition:</label>
          <input type="text" name="condition" placeholder="e.g. as shown in picture">
        </div>
        <div class="input-group">
          <label>Quality:</label>
          <input type="text" name="quality" placeholder="e.g. very good and satisfying">
        </div>
      </div>

      <div class="toggle-switch">
        <label>Show Username On Your Review:</label>
        <input type="checkbox" name="username-toggle" checked>
      </div>

      <div class="stars-section">
        <label>Seller Service:</label>
        <div class="stars" id="seller-service-rating">
          <span class="star" data-value="1">★</span>
          <span class="star" data-value="2">★</span>
          <span class="star" data-value="3">★</span>
          <span class="star" data-value="4">★</span>
          <span class="star" data-value="5">★</span>
        </div>
      </div>

      <button type="submit" class="submit-button">Submit</button>
    </form>
  </div>
</section>
@endsection

@push('scripts')
<script>
  function handleStarGroup(containerId, hiddenInputId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const stars = container.querySelectorAll(".star");
    const hidden = document.getElementById(hiddenInputId);

    stars.forEach(star => {
      star.addEventListener("click", () => {
        const value = parseInt(star.dataset.value);
        stars.forEach(s => {
          s.classList.toggle("selected", parseInt(s.dataset.value) <= value);
        });
        if (hidden) hidden.value = value;
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    handleStarGroup("product-rating", "rating_produk");
    handleStarGroup("seller-service-rating", "rating_seller");
  });
</script>
@endpush
