@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="head-title d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="left">
            <h1>Edit Produk</h1>
             <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/manageProduk" class="text-decoration-none">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="/manageProduk-update/{{ $produk->id_produk }}" method="POST" enctype="multipart/form-data"> 
                @csrf
                <div class="mb-3">
                    <label for="id_produk" class="form-label">ID Produk </label>
                    <input type="text" class="form-control" id="id_produk" name="id_produk" value="{{ old('id_produk', $produk->id_produk) }}" required>
                </div>
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk </label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label">Kategori </label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="BSF" {{ old('kategori', $produk->kategori) == 'BSF' ? 'selected' : '' }}>BSF</option>
                            <option value="Kompos" {{ old('kategori', $produk->kategori) == 'Kompos' ? 'selected' : '' }}>Kompos</option>
                            <option value="Kandang Maggot" {{ old('kategori', $produk->kategori) == 'Kandang Maggot' ? 'selected' : '' }}>Kandang Maggot</option>
                             <option value="Lainnya" {{ old('kategori', $produk->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="merk" class="form-label">Merk</label>
                        <input type="text" class="form-control" id="merk" name="merk" value="{{ old('merk', $produk->merk) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="harga" class="form-label">Harga (Rp) </label>
                        <input type="number" class="form-control" id="harga" name="harga" min="0" value="{{ old('harga', $produk->harga) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" value="{{ old('stok', $produk->stok) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="masa_penyimpanan" class="form-label">Masa Penyimpanan</label>
                        <input type="text" class="form-control" id="masa_penyimpanan" name="masa_penyimpanan" value="{{ old('masa_penyimpanan', $produk->masa_penyimpanan) }}" placeholder="Contoh: 7 hari" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="berat" class="form-label">Berat</label>
                        <input type="text" class="form-control" id="berat" name="berat" value="{{ old('berat', $produk->berat) }}" placeholder="Contoh: 500 gr atau 1 kg">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="pengiriman" class="form-label">Pengiriman</label>
                        <select class="form-select" id="pengiriman" name="pengiriman" required>
                            <option value="">Pilih Jenis Pengiriman</option>
                            <option value="Instant" {{ old('pengiriman', $produk->pengiriman) == 'Instant' ? 'selected' : '' }}>Instan</option>
                            <option value="Reguler" {{ old('pengiriman', $produk->pengiriman) == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="Kargo" {{ old('pengiriman', $produk->pengiriman) == 'Kargo' ? 'selected' : '' }}>Kargo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Upload Gambar Produk </label>
                    @if($produk->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('photo/' . $produk->gambar) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;" alt="Current Image">
                            <small class="d-block text-muted">Gambar saat ini</small>
                        </div>
                    @endif
                    <input type="file" class="form-control" id="gambar" name="gambar">
                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                </div>
                
                <div class="mb-3">
                    <label for="deskripsi_produk" class="form-label">Deskripsi Produk</label>
                    <textarea class="form-control" id="deskripsi_produk" name="deskripsi_produk" rows="3">{{ old('deskripsi_produk', $produk->deskripsi_produk) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/manageProduk" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection