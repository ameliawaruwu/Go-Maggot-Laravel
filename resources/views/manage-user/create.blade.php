@extends('layouts.admin')

@section('content')
<main>
    <div class="head-title d-flex justify-content-between align-items-center mb-4">
        <div class="left">
            <h1 class="h3 mb-0 text-gray-800">Tambah User Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/manageUser">User</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form User Baru</h6>
        </div>
        <div class="card-body">
            <form action="/manageUser-simpan" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- ID PENGGUNA DIHAPUS KARENA OTOMATIS (PG001...) --}}

                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Nama User</label>
                    <input type="text" id="username" name="username" class="form-control" required placeholder="Masukkan nama lengkap">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="email@contoh.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="6">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nomor_telepon" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label fw-bold">Role</label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="pelanggan">Pelanggan</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label fw-bold">Alamat</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-4">
                    <label for="foto_profil" class="form-label fw-bold">Foto Profil</label>
                    <input type="file" id="foto_profil" name="foto_profil" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="/manageUser" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection