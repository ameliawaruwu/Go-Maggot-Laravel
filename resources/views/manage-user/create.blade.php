@extends('layouts.admin')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tambah User Baru</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('user.index') }}" class="text-decoration-none">User</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a  href="#" class="text-decoration-none">Tambah Baru</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Form User</h3>
            </div>
            
            @if ($errors->any())
                <div class="form-message error" style="margin-bottom: 20px; padding: 15px; border-radius: 8px; background: #ffe6e6; color: #ff0000;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.store') }}" method="POST">
                @csrf

                <div class="form-group mb-4">
                    <label for="username">Nama User</label>
                    <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required>
                </div>

                <div class="form-group mb-4">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                </div>

                <div class="form-group mb-4">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                
                <div class="form-group mb-4">
                    <label for="foto_profil">Foto Profil (Pilih dari public/images)</label>
                    <select id="foto_profil" name="foto_profil" class="form-control">
                        @foreach ($availableImages as $image)
                            <option value="{{ $image }}" {{ old('foto_profil') == $image ? 'selected' : '' }}>
                                {{ $image ?: '--- Tidak Ada Foto ---' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection