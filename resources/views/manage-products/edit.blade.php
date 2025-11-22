@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="head-title mb-4">
        <h1>Edit Produk:  (ID: PRD{{ str_pad($product['idproduk'], 3, '0', STR_PAD_LEFT) }})</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('products.update', $product['idproduk']) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- Nama Produk --}}
                <div class="mb-3">
                    <label for="namaproduk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('namaproduk') is-invalid @enderror" id="namaproduk" name="namaproduk" 
                        value="{{ old('namaproduk', $product['namaproduk']) }}" required>
                    @error('namaproduk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    {{-- Kategori --}}
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($fixedCategories as $category)
                                <option value="{{ $category }}" {{ old('kategori', $product['kategori']) == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Merk --}}
                    <div class="col-md-6 mb-3">
                        <label for="merk" class="form-label">Merk (Opsional)</label>
                        <input type="text" class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk" 
                            value="{{ old('merk', $product['merk'] ?? '') }}">
                        @error('merk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Harga --}}
                    <div class="col-md-4 mb-3">
                        <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" 
                            value="{{ old('harga', $product['harga']) }}" min="0" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" 
                            value="{{ old('stok', $product['stok']) }}" min="0" required>
                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Masa Penyimpanan --}}
                    <div class="col-md-4 mb-3">
                        <label for="masapenyimpanan" class="form-label">Masa Penyimpanan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('masapenyimpanan') is-invalid @enderror" id="masapenyimpanan" name="masapenyimpanan" 
                            value="{{ old('masapenyimpanan', $product['masapenyimpanan']) }}" placeholder="Contoh: 7 hari / 6 bulan" required>
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
                            <input type="number" step="0.01" class="form-control @error('berat') is-invalid @enderror" id="berat" name="berat" 
                                value="{{ old('berat', $product['berat']) }}" min="0" required>
                            <select class="form-select @error('satuan_berat') is-invalid @enderror" name="satuan_berat" style="max-width: 100px;" required>
                                <option value="gr" {{ old('satuan_berat', $product['satuan_berat'] ?? 'kg') == 'gr' ? 'selected' : '' }}>gr</option>
                                <option value="kg" {{ old('satuan_berat', $product['satuan_berat'] ?? 'kg') == 'kg' ? 'selected' : '' }}>kg</option>
                            </select>
                            @error('berat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('satuan_berat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Pengiriman --}}
                    <div class="col-md-6 mb-3">
                        <label for="pengiriman" class="form-label">Pengiriman <span class="text-danger">*</span></label>
                        <select class="form-select @error('pengiriman') is-invalid @enderror" id="pengiriman" name="pengiriman" required>
                            <option value="">Pilih Jenis Pengiriman</option>
                            <option value="Instant" {{ old('pengiriman', $product['pengiriman']) == 'Instant' ? 'selected' : '' }}>Instant</option>
                            <option value="Reguler" {{ old('pengiriman', $product['pengiriman']) == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="Kargo" {{ old('pengiriman', $product['pengiriman']) == 'Kargo' ? 'selected' : '' }}>Kargo</option>
                        </select>
                        @error('pengiriman')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pratinjau Gambar</label>
                    @php
                        // Tentukan path gambar saat ini
                        $currentImageName = $product['gambar'] ?? 'default-product.jpg';
                        $currentImagePath = asset('images/' . $currentImageName);
                    @endphp

                   
                    <div class="mb-2">
                        <img id="image-preview" src="{{ $currentImagePath }}" alt="Current Product Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                    </div>

                    <label for="gambar" class="form-label">Pilih Gambar dari `public/images`</label>
                    <select class="form-select @error('gambar') is-invalid @enderror" id="gambar" name="gambar">
                        <option value="">-- Pilih File Gambar --</option>
                        
                        
                        @foreach ($imageFiles as $imageName)
                            <option value="{{ $imageName }}" {{ old('gambar', $currentImageName) == $imageName ? 'selected' : '' }}>
                                {{ $imageName }}
                            </option>
                        @endforeach
                        
                    </select>
                    
                    <small class="text-muted">Pilih nama file gambar yang sudah ada di folder public/images.</small>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi Produk --}}
                <div class="mb-3">
                    <label for="deskripsi_produk" class="form-label">Deskripsi Produk (Opsional)</label>
                    <textarea class="form-control @error('deskripsi_produk') is-invalid @enderror" id="deskripsi_produk" name="deskripsi_produk" rows="3">{{ old('deskripsi_produk', $product['deskripsi_produk'] ?? '') }}</textarea>
                    @error('deskripsi_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/manage-product.js') }}"></script>
@endpush