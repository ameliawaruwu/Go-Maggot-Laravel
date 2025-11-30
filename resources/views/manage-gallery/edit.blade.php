@extends('layouts.admin')

@section('title', 'Edit Galeri')

@section('content')
<div class="container mt-4">
    <h4>Edit Galeri</h4>

    <form action="{{ route('gallery.update', $galeri->id_galeri) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="id_galeri" class="form-label">ID Galeri</label>
            <input type="text" name="id_galeri" id="id_galeri"
                   class="form-control @error('id_galeri') is-invalid @enderror"
                   value="{{ old('id_galeri', $galeri->id_galeri) }}">
            @error('id_galeri')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="3"
                      class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $galeri->keterangan) }}</textarea>
            @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Gambar Saat Ini</label>
            @if($galeri->gambar)
                <img src="{{ asset('photo/'.$galeri->gambar) }}" alt="" style="height:100px;">
            @else
                <p class="text-muted">Tidak ada gambar.</p>
            @endif
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Ganti Gambar (opsional)</label>
            <input type="file" name="gambar" id="gambar"
                   class="form-control @error('gambar') is-invalid @enderror">
            @error('gambar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <a href="{{ route('gallery.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
