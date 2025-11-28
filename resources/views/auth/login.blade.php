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

      {{-- ERROR MESSAGE --}}
      @if ($errors->any())
        <div class="error-message" style="color:red; margin-bottom:10px;">
          {{ $errors->first() }}
        </div>
      @endif

      {{-- 🚀 FORM LOGIN --}}
      <form action="{{ route('login.post', ['redirect' => request('redirect')]) }}" method="POST" autocomplete="off">
        @csrf
        
        {{-- HIDDEN DUMMY (TRIK AGAR BROWSER TIDAK AUTO-FILL) --}}
        <input type="text" name="fakeusernameremembered" style="display:none">
        <input type="password" name="fakepasswordremembered" style="display:none">

        {{-- INPUT EMAIL --}}
        <div class="input-box">
          <span class="icon"><ion-icon name="mail"></ion-icon></span>
          <input 
              type="email" 
              name="email" 
              required 
              autocomplete="off"
          >
          <label>Email</label>
        </div>

        {{-- INPUT PASSWORD --}}
        <div class="input-box password-box">
          <span class="icon lock-icon"><ion-icon name="lock-closed"></ion-icon></span>
          <input 
              id="password" 
              type="password" 
              name="password" 
              required 
              autocomplete="new-password"
          >
          <label>Password</label>

          {{-- Icon tampil/sembunyikan password --}}
          <span class="toggle-password" id="toggleEye">
            <ion-icon name="eye-off"></ion-icon>
          </span>
        </div>

        {{-- REMEMBER / FORGOT --}}
        <div class="remember-forgot">
          <label><input type="checkbox" name="remember"> Remember me</label>
          <a class="forgot-link" href="#">Forgot Password</a>
        </div>

        {{-- BUTTON LOGIN --}}
        <button type="submit" class="btn">Login</button>

        <div class="login-register">
          <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection
