@extends('layouts.app')

@section('title', 'GoMaggot')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/orry/login.css') }}">
@endpush

@section('content')

<div class="auth-hero">
  <div class="wrapper">

    <div class="image-container">
      <img src="{{ asset('images/foto login.jpg') }}" alt="Register Illustration">
    </div>

    <div class="form-box register">

      <h2>Registration</h2>

      {{-- ERROR MESSAGE --}}
      @if ($errors->any())
        <div class="error-message" style="color:red; margin-bottom:10px;">
          {{ $errors->first() }}
        </div>
      @endif

      {{-- 🚀 FORM REGISTER --}}
      <form action="{{ route('register.post') }}" method="POST" autocomplete="off">
        @csrf

        {{-- Dummy anti-autofill --}}
        <input type="text" style="display:none">
        <input type="password" style="display:none">

        {{-- Username --}}
        <div class="input-box">
          <span class="icon"><ion-icon name="person"></ion-icon></span>
          <input type="text" name="name" required autocomplete="off">
          <label>Username</label>
        </div>

        {{-- Email --}}
        <div class="input-box">
          <span class="icon"><ion-icon name="mail"></ion-icon></span>
          <input type="email" name="email" required autocomplete="off">
          <label>Email</label>
        </div>

        {{-- Password --}}
        <div class="input-box password-box">
          <span class="icon lock-icon"><ion-icon name="lock-closed"></ion-icon></span>
          <input type="password" name="password" required autocomplete="new-password">
          <label>Password</label>
          <span class="toggle-password"><ion-icon name="eye-off"></ion-icon></span>
        </div>

        <button type="submit" class="btn">Register</button>

        <div class="login-register">
          <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
