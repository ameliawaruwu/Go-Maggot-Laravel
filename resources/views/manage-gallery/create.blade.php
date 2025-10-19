@extends('layouts.admin')

@section('title', 'Tambah Galeri Baru')

@section('content')
    <div class="head-title mb-4">
        <h1 class="h2 mb-1 text-gray-800 fw-bold">Tambah Galeri Baru</h1>
        <ol class="breadcrumb bg-transparent p-0 mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gallery.index') }}"  class="text-decoration-none">Galeri</a></li>
            <li class="breadcrumb-item active" aria-current="page"  class="text-decoration-none">Tambah</li>
        </ol>
    </div>

    {{-- FORM TAMBAH GALERI (Menggunakan Card) --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">Form Tambah Galeri</h3>
            
            <form action="{{ route('gallery.store') }}" method="POST">
                @csrf
                
                {{-- PILIH GAMBAR (Simulasi Dropdown) --}}
                <div class="mb-4">
                    {{-- LABEL SESUAI DESAIN: Pilih Gambar: --}}
                    <label for="gambar" class="form-label">Pilih Gambar:</label>
                    
                    {{-- Dropdown untuk memilih file yang ada di public/images --}}
                    <select class="form-select @error('gambar') is-invalid @enderror" id="gambar" name="gambar" required>
                        <option value="">-- Pilih File Gambar --</option>
                        {{-- Ini adalah opsi dummy yang sesuai dengan desain input file --}}
                        <option value="" disabled>Pilih File...</option> 
                        @foreach ($availableImages as $imageName)
                            <option value="{{ $imageName }}" {{ old('gambar') == $imageName ? 'selected' : '' }}>
                                {{ $imageName }}
                            </option>
                        @endforeach
                    </select>
                    
                    {{-- KETERANGAN --}}
                    <small class="text-muted mt-2 d-block">Ini mensimulasikan file yang sudah diunggah di `public/images`.</small>
                    @error('gambar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KETERANGAN / DESKRIPSI --}}
                <div class="mb-4">
                    <label for="keterangan" class="form-label">Deskripsi:</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="5" required>{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="d-flex justify-content-end gap-2 mt-5">
                    <a href="{{ route('gallery.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection