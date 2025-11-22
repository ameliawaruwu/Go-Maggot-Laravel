@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="head-title mb-4">
        <h1>Tambah Produk</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page"class="text-decoration-none">Tambah Baru</li>
        </ol>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
           
            <form action="{{ route('products.store') }}" method="POST"> 
                @csrf
                
                
                <div class="mb-3">
                    <label for="namaproduk" class="form-label">Nama Produk <span class="text-danger"></span></label>
                    <input type="text" class="form-control @error('namaproduk') is-invalid @enderror" id="namaproduk" name="namaproduk" value="{{ old('namaproduk') }}" required>
                    @error('namaproduk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                   
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($fixedCategories as $category)
                                <option value="{{ $category }}" {{ old('kategori') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                           
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                  
                    <div class="col-md-6 mb-3">
                        <label for="merk" class="form-label">Merk (Opsional)</label>
                        <input type="text" class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk" value="{{ old('merk') }}">
                        @error('merk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Harga --}}
                    <div class="col-md-4 mb-3">
                        <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga') }}" min="0" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok') ?? 0 }}" min="0" required>
                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                  
                    <div class="col-md-4 mb-3">
                        <label for="masapenyimpanan" class="form-label">Masa Penyimpanan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('masapenyimpanan') is-invalid @enderror" id="masapenyimpanan" name="masapenyimpanan" value="{{ old('masapenyimpanan') }}" placeholder="Contoh: 7 hari / 6 bulan" required>
                        @error('masapenyimpanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Berat & Satuan --}}
                    <div class="col-md-6 mb-3">
                        <label for="berat" class="form-label">Berat <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control @error('berat') is-invalid @enderror" id="berat" name="berat" value="{{ old('berat') }}" min="0" required>
                            <select class="form-select @error('satuan_berat') is-invalid @enderror" name="satuan_berat" style="max-width: 100px;" required>
                                <option value="gr" {{ old('satuan_berat') == 'gr' ? 'selected' : '' }}>gr</option>
                                <option value="kg" {{ old('satuan_berat') == 'kg' ? 'selected' : '' }}>kg</option>
                            </select>
                            @error('berat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('satuan_berat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                   
                    <div class="col-md-6 mb-3">
                        <label for="pengiriman" class="form-label">Pengiriman <span class="text-danger">*</span></label>
                        <select class="form-select @error('pengiriman') is-invalid @enderror" id="pengiriman" name="pengiriman" required>
                            <option value="">Pilih Jenis Pengiriman</option>
                            <option value="Instant" {{ old('pengiriman') == 'Instant' ? 'selected' : '' }}>Instan</option>
                            <option value="Reguler" {{ old('pengiriman') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="Kargo" {{ old('pengiriman') == 'Kargo' ? 'selected' : '' }}>Kargo</option>
                        </select>
                        @error('pengiriman')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

               
                <div class="mb-3">
                    <label for="gambar" class="form-label">Pilih Gambar dari Folder `images` <span class="text-danger">*</span></label>
                    <select class="form-select @error('gambar') is-invalid @enderror" id="gambar" name="gambar" required>
                        <option value="">-- Pilih Nama File Gambar --</option>
                        {{-- Loop dari data $imageFiles yang dikirim Controller --}}
                        @foreach ($imageFiles ?? [] as $file)
                            <option value="{{ $file }}" {{ old('gambar') == $file ? 'selected' : '' }}>{{ $file }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Daftar ini diambil dari file yang sudah ada di folder **`public/images`** Anda.</small>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="deskripsi_produk" class="form-label">Deskripsi Produk</label>
                    <textarea class="form-control @error('deskripsi_produk') is-invalid @enderror" id="deskripsi_produk" name="deskripsi_produk" rows="3">{{ old('deskripsi_produk') }}</textarea>
                    @error('deskripsi_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection