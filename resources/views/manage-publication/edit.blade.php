@extends('layouts.admin')

@section('title', 'Edit Artikel')

@section('content')

<div class="head-title">
    <div class="left">
        <h1>Edit Artikel</h1>
        <ul class="breadcrumb">
            <li><a href="{{ route('publication.index') }}" class="text-decoration-none">Artikel</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active text-decoration-none" href="#">Edit Artikel</a></li>
        </ul>
    </div>
</div>

<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Form Edit Artikel</h3>
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

        <form action="{{ route('publication.update', $article->id_artikel) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group mb-4">
                <label for="judul">Judul Artikel</label>
                <input type="text" id="judul" name="judul" class="form-control"
                        value="{{ old('judul', $article['judul']) }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="penulis">Penulis</label>
                <input type="text" id="penulis" name="penulis" class="form-control"
                        value="{{ old('penulis', $article['penulis']) }}" required>
            </div>
            
        
            <div class="form-group mb-4">
                <label for="gambar">Gambar Artikel</label>
                
                @if ($article->gambar)
                    <div class="mb-2">
                        <p class="mb-1" style="font-weight: bold;">Gambar Saat Ini:</p>
                       <!-- Menampilkan gambar saat ini -->
                        <img src="{{ asset('photo/' . $article->gambar) }}" alt="Gambar {{ $article->judul }}" style="max-width: 200px; height: auto; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                    </div>
                @else
                    <p class="mb-1" style="color: #666;">Belum ada gambar.</p>
                @endif
                
                <!-- Input untuk unggah gambar baru-->
                <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*">
                <small class="form-text">Unggah file baru</small>
            </div>


            <div class="form-group mb-4">
                <label>Tanggal</label>
                <input type="text" class="form-control"
                        value="{{ \Carbon\Carbon::parse($article['tanggal'])->format('d/m/Y') }}"
                        disabled>
            </div>

            <div class="form-group mb-4">
                <label for="hak_cipta">Hak Cipta</label>
                <input type="text" id="hak_cipta" name="hak_cipta" class="form-control"
                        value="{{ old('hak_cipta', $article['hak_cipta']) }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="konten">Isi Artikel</label>
                <textarea id="konten" name="konten" class="form-control" rows="10" required>{{ old('konten', $article['konten']) }}</textarea>
            </div>

            <div class="form-actions d-flex gap-2">
                <a href="{{ route('publication.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>

        </form>

    </div>
</div>

@endsection