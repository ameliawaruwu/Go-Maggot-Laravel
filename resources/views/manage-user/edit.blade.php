@extends('layouts.admin')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit User: {{ $user['username'] }}</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('user.index') }}" class="text-decoration-none">User</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a  href="#" class="text-decoration-none">Edit</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Form Edit User (ID: {{ $user['id_pelanggan'] }})</h3>
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

            <form action="{{ route('user.update', $user['id_pelanggan']) }}" method="POST">
                @csrf
                @method('PUT') 

                <div class="form-group mb-4">
                    <label for="username">Nama User</label>
                    <input type="text" id="username" name="username" class="form-control" 
                           value="{{ old('username', $user['username']) }}" required>
                </div>

                <div class="form-group mb-4">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="{{ old('email', $user['email']) }}" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="password">Password (Kosongkan jika tidak ingin diubah)</label>
                    <input type="password" id="password" name="password" class="form-control" minlength="6">
                    <small class="form-text text-muted">Isi hanya jika Anda ingin mengganti password.</small>
                </div>

                <div class="form-group mb-4">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="customer" {{ old('role', $user['role']) == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="admin" {{ old('role', $user['role']) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                
                <div class="form-group mb-4">
                    <label for="foto_profil">Foto Profil (Pilih dari public/images)</label>
                    <select id="foto_profil" name="foto_profil" class="form-control">
                        @foreach ($availableImages as $image)
                            @php
                                $isSelected = old('foto_profil', $user['foto_profil']) == $image;
                            @endphp
                            <option value="{{ $image }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $image ?: '--- Tidak Ada Foto ---' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                     <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection