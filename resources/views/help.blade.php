@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/orry/help.css') }}">
@endpush

@section('content')
  {{-- HERO --}}
  <header class="header">
    <div class="back-button"></div>
    <div class="overlay">
      <h1>Help Center</h1>
      <p>We provide solutions that are easily accessible at any time.</p>
    </div>
  </header>

  {{-- SEARCH --}}
  <div class="search-bar">
    <input type="text" placeholder="Search">
  </div>

  {{-- SECURITY --}}
  <div class="security-alert">
    <span>🔒</span> Keep Your Account Secure
  </div>

  {{-- QUICK ACTIONS --}}
  <section class="reports">
    <h2>What are your constraints?</h2>
    <div class="report-icons">
      <div class="icon-item">
        <span>📄</span>
        <p>Track Order</p>
      </div>
      <div class="icon-item">
        <span>❌</span>
        <p>Cancel Order</p>
      </div>
      <div class="icon-item">
        <span>🔄</span>
        <p>Check Return Status</p>
      </div>
      <div class="icon-item">
        <span>📞</span>
        <p>Change Mobile Number</p>
      </div>
      <div class="icon-item">
        <span>📦</span>
        <p>Apply for Return</p>
      </div>
    </div>
    <div class="see-all">
      <a href="#">View All...</a>
    </div>
  </section>

  {{-- FAQ --}}
  <section class="faq">
    <h2>Is there anything we can do to help?</h2>
    <ul class="faq-list">
      <li>How can I submit a return request? <span class="hot">HOT</span></li>
      <li>How do I cancel an order? <span class="hot">HOT</span></li>
      <li>Who bears the cost of return shipping?</li>
      <li>What should I do if the status shows “Delivered” but I didn’t receive it?</li>
    </ul>
    <div class="more-faq">
      <a href="#">View More...</a>
    </div>
  </section>

  {{-- CONTACT US --}}
  <section class="contact-us">
    <h2>Contact Us</h2>
    <div class="contact-item">
      <span>💬</span>
      <p>Chat GoMaggot Now</p>
    </div>
    <div class="contact-item">
      <span>📞</span>
      <p>Call Us<br><small>Operating Hours: 07:00 AM - 05.00 PM</small></p>
    </div>
  </section>
@endsection
