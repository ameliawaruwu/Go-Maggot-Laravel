@extends('layouts.admin')

@section('title', 'Tambah Artikel')

@section('content')

<div class="head-title">
    <div class="left">
        <h1>Tambah Artikel Baru</h1>
        <ul class="breadcrumb">
            <li><a href="{{ route('publication.index') }}" class="text-decoration-none">Artikel</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a href="#" class="text-decoration-none">Tambah Baru</a></li>
        </ul>
    </div>
</div>

<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Form Artikel</h3>
        </div>

        @if ($errors->any())
            <div class="form-message error mb-3 p-3 rounded">
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('publication.simpan') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-4">
                <label for="judul">Judul Artikel</label>
                <input type="text" id="judul" name="judul" class="form-control"
                        value="{{ old('judul') }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="penulis">Penulis</label>
                <input type="text" id="penulis" name="penulis" class="form-control"
                        value="{{ old('penulis') }}" required>
            </div>
            
            <!-- Tambah  Gambar -->
            <div class="form-group mb-4">
                <label for="gambar">Gambar Artikel</label>
                <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*">
                <small class="form-text">Maksimal 2MB (jpeg, png, jpg, dll.).</small>
            </div>

            <div class="form-group mb-4">
                <label>Tanggal</label>
                <input type="text" class="form-control" value="{{ now()->format('d/m/Y') }}" disabled>
            </div>

            <div class="form-group mb-4">
                <label for="hak_cipta">Hak Cipta</label>
                <input type="text" id="hak_cipta" name="hak_cipta" class="form-control"
                        value="{{ old('hak_cipta') }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="konten">Isi Artikel</label>
                <textarea id="konten" name="konten" class="form-control" rows="10" required>{{ old('konten') }}</textarea>
            </div>

            <div class="form-actions d-flex gap-2">
                <a href="{{ route('publication.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

    </div>
</div>

@endsection