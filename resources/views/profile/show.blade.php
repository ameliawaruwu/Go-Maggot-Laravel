@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/orry/profile.css') }}">
@endpush

@section('content')
@php
  $user = auth()->user();
  $username = $pengguna->username ?? ($user->name ?? 'User');
  $email = $pengguna->email ?? ($user->email ?? '-');
  $hasPhoto = $pengguna && $pengguna->foto_profil;
@endphp

<div class="profile-page">
  <div class="profile-container">

    {{-- SIDEBAR --}}
    <aside class="profile-sidebar">
      <div class="side-user">
        <div class="side-avatar">
          @if($hasPhoto)
            <img src="{{ asset('storage/' . $pengguna->foto_profil) }}" alt="Avatar">
          @else
            <i class="ri-user-line"></i>
          @endif
        </div>

        <div class="side-meta">
          <div class="side-name">{{ $username }}</div>
          <a class="side-edit" href="{{ route('profile.show', ['edit' => 1]) }}#editProfile">
            <i class="ri-pencil-line"></i>
            <span>Ubah Profil</span>
            </a>
        </div>
      </div>

      <nav class="side-nav">
        <div class="side-group">
          <div class="side-group-title">
            <i class="ri-user-3-line"></i>
            <span>Akun Saya</span>
          </div>
          <a class="side-link active" href="{{ route('profile.show') }}">Profil</a>
          <a class="side-link" href="#editProfile">Alamat</a>
          <a class="side-link" href="javascript:void(0)">Ubah Password</a>
          <a class="side-link" href="javascript:void(0)">Pengaturan Notifikasi</a>
          <a class="side-link" href="javascript:void(0)">Pengaturan Privasi</a>
        </div>

        <div class="side-group">
          <a class="side-group-title link" href="javascript:void(0)">
            <i class="ri-shopping-bag-3-line"></i><span>Pesanan Saya</span>
          </a>
          <a class="side-group-title link" href="javascript:void(0)">
            <i class="ri-notification-3-line"></i><span>Notifikasi</span>
          </a>
          <a class="side-group-title link" href="javascript:void(0)">
            <i class="ri-coupon-3-line"></i><span>Voucher Saya</span>
          </a>
          <a class="side-group-title link" href="javascript:void(0)">
            <i class="ri-coin-line"></i><span>Koin Saya</span>
          </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="side-logout">
          @csrf
          <button type="submit" class="btn-logout">Logout</button>
        </form>
      </nav>
    </aside>

    {{-- MAIN --}}
    <main class="profile-main" id="editProfile">
      <div class="main-card">
        <div class="main-head">
          <div class="main-title">Profil Saya</div>
          <div class="main-subtitle">
            Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun
          </div>

          @if(session('success'))
            <div style="margin-top:10px; padding:10px 12px; background:#e9fff0; border:1px solid #bff2cd; border-radius:8px; color:#176b2c;">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div style="margin-top:10px; padding:10px 12px; background:#ffecec; border:1px solid #ffbdbd; border-radius:8px; color:#9a1c1c;">
              <ul style="margin-left:18px;">
                @foreach($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>

        <form class="main-body" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
          @csrf

          {{-- LEFT FORM --}}
          <div class="form-area">
            <div class="form-row">
              <div class="label">Username</div>
              <div class="value">{{ $username }}</div>
            </div>

            <div class="form-row">
              <div class="label">Email</div>
              <div class="value">{{ $email }}</div>
            </div>

            <div class="form-row">
              <div class="label">Nomor Telepon</div>
              <div class="value">
                                <input
                id="nomor_telepon"
                type="text"
                name="nomor_telepon"
                value="{{ old('nomor_telepon', $pengguna->nomor_telepon ?? '') }}"
                @if(request()->get('edit') == 1) autofocus @endif
                style="width:100%; max-width:420px; padding:10px 12px; border:1px solid #ddd; border-radius:8px;">
              </div>
            </div>

            <div class="form-row">
              <div class="label">Alamat</div>
              <div class="value">
                <textarea name="alamat" rows="3"
                          style="width:100%; max-width:420px; padding:10px 12px; border:1px solid #ddd; border-radius:8px; resize:vertical;">{{ old('alamat', $pengguna->alamat ?? '') }}</textarea>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-save" style="border:none; cursor:pointer;">Simpan</button>
            </div>
          </div>

          {{-- RIGHT PHOTO --}}
          <div class="photo-area">
            <div class="photo-circle">
              @if($hasPhoto)
                <img src="{{ asset('storage/' . $pengguna->foto_profil) }}" alt="Avatar">
              @else
                <i class="ri-user-line"></i>
              @endif
            </div>

            <label class="btn-pick">
              <i class="ri-image-add-line"></i>
              Pilih Gambar
              <input type="file" name="foto_profil" accept="image/png,image/jpeg">
            </label>

            <div class="photo-note">
              Ukuran gambar: maks. 10 MB<br>
              Format gambar: .JPEG, .PNG
            </div>
          </div>
        </form>
      </div>
    </main>

  </div>
</div>
@endsection
