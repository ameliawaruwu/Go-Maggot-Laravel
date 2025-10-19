@extends('layouts.app')

@section('title', 'GoMaggot')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/orry/login.css') }}">
@endpush

@section('content')
<div class="auth-hero">
  <div class="wrapper">
    <div class="image-container">
      <img src="{{ asset('images/foto login.jpg') }}" alt="Login Illustration">
    </div>

    <div class="form-box login">
      <h2>Login</h2>

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
        <label><input type="checkbox"> Remember me</label>
        <a class="forgot-link" href="#">Forgot Password</a>
      </div>

      <button type="button" class="btn">Login</button>

      <div class="login-register">
        <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
      </div>
    </div>
  </div>
</div>
@endsection

