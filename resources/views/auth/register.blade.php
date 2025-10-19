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

      <div class="input-box">
        <span class="icon"><ion-icon name="person"></ion-icon></span>
        <input type="text" name="username" required>
        <label>Username</label>
      </div>

      <div class="input-box">
        <span class="icon"><ion-icon name="mail"></ion-icon></span>
        <input type="email" name="email" required>
        <label>Email</label>
      </div>

      <div class="input-box password-box">
        <span class="icon lock-icon"><ion-icon name="lock-closed"></ion-icon></span>
        <input id="password" type="password" name="password" required oninput="toggleEye(this)">
        <label>Password</label>
        <span class="toggle-password" id="toggleEye"><ion-icon name="eye-off"></ion-icon></span>
      </div>

      <div class="remember-forgot">
        <label><input type="checkbox" required> I agree to the terms & conditions</label>
        <span></span>
      </div>

      <button type="button" class="btn">Register</button>

      <div class="login-register">
        <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
      </div>
    </div>
  </div>
</div>
@endsection

