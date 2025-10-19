{{-- resources/views/manage-gallery/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Galeri')

@section('content')
    <div class="head-title mb-4">
        <h1>Edit Galeri ID: GLR{{ str_pad($galeri['id_galeri'], 3, '0', STR_PAD_LEFT) }}</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"  class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gallery.index') }}"  class="text-decoration-none">Galeri</a></li>
            <li class="breadcrumb-item active" aria-current="page"  class="text-decoration-none">Edit</li>
        </ol>
    </div>


    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('gallery.update', $galeri['id_galeri']) }}" method="POST">
                @csrf
                @method('PUT')
               
                <div class="mb-3">
                    <label class="form-label">Gambar Saat Ini</label>
                    <div class="mb-2">
                        <img src="{{ asset('images/' . ($galeri['gambar'] ?? 'default-product.jpg')) }}" 
                             alt="Current Gallery Image" 
                             style="width: 150px; height: 90px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                </div>

            
                <div class="mb-4">
                    <label for="keterangan" class="form-label">Deskripsi:</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" 
                              name="keterangan" 
                              rows="5" 
                              required>{{ old('keterangan', $galeri['keterangan']) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
               
                <div class="mb-5">
                    <label for="gambar" class="form-label">Pilih Gambar Baru (Simulasi dari `public/images`)</label>
                    <select class="form-select @error('gambar') is-invalid @enderror" id="gambar" name="gambar" required>
                        <option value="">-- Pilih File Gambar --</option>
                        @foreach ($availableImages as $imageName)
                            <option value="{{ $imageName }}" {{ old('gambar', $galeri['gambar']) == $imageName ? 'selected' : '' }}>
                                {{ $imageName }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Ini mensimulasikan file yang sudah diunggah di **`public/images`**.</small>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
               
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('gallery.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
@endsection