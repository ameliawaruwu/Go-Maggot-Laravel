@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="head-title d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="left">
            <h1>Edit Produk</h1>
             <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/manageProduk" class="text-decoration-none">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Produk</li>
            </ol>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="/manageProduk-update/{{ $produk->id_produk }}" method="POST" enctype="multipart/form-data"> 
                @csrf

                <div class="mb-3">
                    <label for="id_produk" class="form-label">ID Produk</label>
                    <input type="text" class="form-control bg-light" id="id_produk" name="id_produk" value="{{ $produk->id_produk }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="{{ $produk->nama_produk }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="BSF" {{ $produk->kategori == 'BSF' ? 'selected' : '' }}>BSF</option>
                            <option value="Kompos" {{ $produk->kategori == 'Kompos' ? 'selected' : '' }}>Kompos</option>
                            <option value="Kandang Maggot" {{ $produk->kategori == 'Kandang Maggot' ? 'selected' : '' }}>Kandang Maggot</option>
                            <option value="Lainnya" {{ $produk->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="merk" class="form-label">Merk</label>
                        <input type="text" class="form-control" id="merk" name="merk" value="{{ $produk->merk }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="harga" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" id="harga" name="harga" min="0" value="{{ $produk->harga }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" value="{{ $produk->stok }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="masa_penyimpanan" class="form-label">Masa Penyimpanan</label>
                        <input type="text" class="form-control" id="masa_penyimpanan" name="masa_penyimpanan" value="{{ $produk->masa_penyimpanan }}" placeholder="Contoh: 7 hari" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="berat" class="form-label">Berat</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="berat" name="berat" value="{{ $produk->berat }}" min="0" required>
                            <select class="form-select bg-light" name="satuan_berat" style="max-width: 90px;">
                                <option value="gr" {{ ($produk->satuan_berat ?? 'gr') == 'gr' ? 'selected' : '' }}>gr</option>
                                <option value="kg" {{ ($produk->satuan_berat ?? '') == 'kg' ? 'selected' : '' }}>kg</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="pengiriman" class="form-label">Pengiriman</label>
                        <select class="form-select" id="pengiriman" name="pengiriman" required>
                            <option value="">Pilih Jenis Pengiriman</option>
                            <option value="Instant" {{ $produk->pengiriman == 'Instant' ? 'selected' : '' }}>Instan</option>
                            <option value="Reguler" {{ $produk->pengiriman == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="Kargo" {{ $produk->pengiriman == 'Kargo' ? 'selected' : '' }}>Kargo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Gambar Produk Saat Ini</label>
                    @if($produk->gambar)
                        <img src="{{ asset('photo/' . $produk->gambar) }}" alt="Foto Produk" class="img-thumbnail mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                    @endif

                    <label for="gambar" class="form-label d-block mt-2">Ganti Gambar (Opsional)</label>
                    <input type="file" class="form-control" id="gambar" name="gambar">
                </div>
                
                <div class="mb-3">
                    <label for="deskripsi_produk" class="form-label">Deskripsi Produk</label>
                    <textarea class="form-control" id="deskripsi_produk" name="deskripsi_produk" rows="3">{{ $produk->deskripsi_produk }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/manageProduk" class="btn btn-secondary">Kembali</a>
                    <button type="update" class="btn btn-warning text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection