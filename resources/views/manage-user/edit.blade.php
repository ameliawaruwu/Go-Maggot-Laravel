@extends('layouts.admin')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit User</h1>
            <ul class="breadcrumb">
                <li><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="/manageUser" class="text-decoration-none">User</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="#" class="text-decoration-none">Edit</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Form Edit User (ID: {{ $pengguna->id_pengguna }})</h3>
            </div>
            <form action="/manageUser-update/{{ $pengguna->id_pengguna }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-4">
                    <label for="id_pengguna" style="font-weight: bold;">ID Pengguna</label>
                    <input type="text" id="id_pengguna" name="id_pengguna" class="form-control" 
                           value="{{ $pengguna->id_pengguna }}" readonly 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #e9ecef;">
                </div>

                <div class="form-group mb-4">
                    <label for="username" style="font-weight: bold;">Nama User</label>
                    <input type="text" id="username" name="username" class="form-control" 
                           value="{{ old('username', $pengguna->username) }}" required 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                <div class="form-group mb-4">
                    <label for="email" style="font-weight: bold;">Email</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="{{ old('email', $pengguna->email) }}" required 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>
                
                <div class="form-group mb-4">
                    <label for="password" style="font-weight: bold;">Password (Opsional)</label>
                    <input type="password" id="password" name="password" class="form-control" value="{{ old('password', $pengguna->password) }}" minlength="6" 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password.</small>
                </div>
                <div class="form-group mb-4">
                    <label for="nomor_telepon" style="font-weight: bold;">Nomor Telepon</label>
                    <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control" 
                           value="{{ old('nomor_telepon', $pengguna->nomor_telepon) }}" 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>
                <div class="form-group mb-4">
                    <label for="alamat" style="font-weight: bold;">Alamat</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3" 
                              style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">{{ old('alamat', $pengguna->alamat) }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label for="role" style="font-weight: bold;">Role</label>
                    <select id="role" name="role" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="pelanggan" {{ old('role', $pengguna->role) == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                        <option value="admin" {{ old('role', $pengguna->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label style="font-weight: bold;">Foto Profil Saat Ini</label><br>
                    @if($pengguna->foto_profil)
                        <img src="{{ asset('photo/' . $pengguna->foto_profil) }}" alt="Foto Profil" 
                             style="width: 100px; height: 100px; object-fit: cover; margin-bottom: 10px;">
                    @else
                        <p style="color: gray;">Belum ada foto.</p>
                    @endif

                    <label for="foto_profil" style="font-weight: bold; display: block; margin-top: 10px;">Ganti Foto (Opsional)</label>
                    <input type="file" id="foto_profil" name="foto_profil" class="form-control" accept="image/*" 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                </div>

                <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 5px; margin-top: 10px;">
                    <a href="/manageUser" class="btn btn-secondary" style="padding: 5px 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Batal</a>
                    <button type="submit" class="btn btn-primary" style="padding: 5px 10px; background: #ffc107; color: white; border: none; border-radius: 4px; cursor: pointer;">Update</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection