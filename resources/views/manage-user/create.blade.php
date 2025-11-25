@extends('layouts.admin')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tambah User Baru</h1>
            <ul class="breadcrumb">
                <li><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="/manageUser" class="text-decoration-none">User</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="#" class="text-decoration-none">Tambah Baru</a></li>
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

            {{-- Action disesuaikan dengan route manual di web.php --}}
            <form action="/manageUser-simpan" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ID Pengguna (Wajib diisi manual sesuai controller) --}}
                <div class="form-group mb-4">
                    <label for="id_pengguna" style="font-weight: bold;">ID Pengguna</label>
                    <input type="text" id="id_pengguna" name="id_pengguna" class="form-control" value="{{ old('id_pengguna') }}" placeholder="Contoh: U001" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                <div class="form-group mb-4">
                    <label for="username" style="font-weight: bold;">Nama User</label>
                    <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                <div class="form-group mb-4">
                    <label for="email" style="font-weight: bold;">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>
                
                <div class="form-group mb-4">
                    <label for="password" style="font-weight: bold;">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                {{-- Nomor Telepon --}}
                <div class="form-group mb-4">
                    <label for="nomor_telepon" style="font-weight: bold;">Nomor Telepon</label>
                    <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                {{-- Alamat --}}
                <div class="form-group mb-4">
                    <label for="alamat" style="font-weight: bold;">Alamat</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">{{ old('alamat') }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label for="role" style="font-weight: bold;">Role</label>
                    <select id="role" name="role" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                
                {{-- Foto Profil (Upload File) --}}
                <div class="form-group mb-4">
                    <label for="foto_profil" style="font-weight: bold;">Foto Profil</label>
                    <input type="file" id="foto_profil" name="foto_profil" class="form-control" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                </div>

                <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <a href="/manageUser" class="btn btn-secondary" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">Batal</a>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection