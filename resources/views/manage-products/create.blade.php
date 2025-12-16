@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="head-title d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="left">
            <h1>Tambah Produk</h1>
             <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/manageProduk" class="text-decoration-none">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
        </ol>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="/manageProduk-simpan" method="POST" enctype="multipart/form-data"> 
                @csrf
                <div class="mb-3">
                    <label for="id_produk" class="form-label">ID Produk </label>
                    <input type="text" class="form-control" id="id_produk" name="id_produk" required>
                </div>
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk </label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label">Kategori </label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="BSF">BSF</option>
                            <option value="Kompos">Kompos</option>
                            <option value="Kandang Maggot">Kandang Maggot</option>
                             <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="merk" class="form-label">Merk</label>
                        <input type="text" class="form-control" id="merk" name="merk">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="harga" class="form-label">Harga (Rp) </label>
                        <input type="number" class="form-control" id="harga" name="harga" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="masa_penyimpanan" class="form-label">Masa Penyimpanan</label>
                        <input type="text" class="form-control" id="masa_penyimpanan" name="masa_penyimpanan" placeholder="Contoh: 7 hari" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="berat" class="form-label">Berat</label>
                        <input type="text" class="form-control" id="berat" name="berat" value="{{ old('berat') }}" placeholder="Contoh: 500 gr atau 1 kg">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="pengiriman" class="form-label">Pengiriman</label>
                        <select class="form-select" id="pengiriman" name="pengiriman" required>
                            <option value="">Pilih Jenis Pengiriman</option>
                            <option value="Instant">Instan</option>
                            <option value="Reguler">Reguler</option>
                            <option value="Kargo">Kargo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Upload Gambar Produk </label>
                    <input type="file" class="form-control" id="gambar" name="gambar" required>
                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                </div>
                
                <div class="mb-3">
                    <label for="deskripsi_produk" class="form-label">Deskripsi Produk</label>
                    <textarea class="form-control" id="deskripsi_produk" name="deskripsi_produk" rows="3"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/manageProduk" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection