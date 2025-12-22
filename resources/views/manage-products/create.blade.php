@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="head-title d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="left">
            <h1 class="h3 mb-0 text-gray-800">Tambah Produk</h1>
             <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/manageProduk" class="text-decoration-none">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
            </ol>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="/manageProduk-simpan" method="POST" enctype="multipart/form-data"> 
                @csrf
                
                {{-- INFO: ID PRODUK DIBUAT OTOMATIS OLEH SISTEM (PR01, dst) --}}
                
                <div class="mb-3">
                    <label for="nama_produk" class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" required placeholder="Masukkan nama produk...">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="BSF">BSF</option>
                            <option value="Kompos">Kompos</option>
                            <option value="Kandang Maggot">Kandang Maggot</option>
                             <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="merk" class="form-label fw-bold">Merk</label>
                        <input type="text" class="form-control" id="merk" name="merk" placeholder="Nama merk (opsional)">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="harga" class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="harga" name="harga" min="0" required placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label fw-bold">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" required placeholder="Jumlah stok awal">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="masa_penyimpanan" class="form-label fw-bold">Masa Penyimpanan</label>
                        <input type="text" class="form-control" id="masa_penyimpanan" name="masa_penyimpanan" placeholder="Contoh: 7 hari / 6 bulan">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="berat" class="form-label fw-bold">Berat</label>
                        <input type="text" class="form-control" id="berat" name="berat" value="{{ old('berat') }}" placeholder="Contoh: 500 gr atau 1 kg">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="pengiriman" class="form-label fw-bold">Pengiriman</label>
                        <select class="form-select" id="pengiriman" name="pengiriman">
                            <option value="">Pilih Jenis Pengiriman</option>
                            <option value="Instant">Instan</option>
                            <option value="Reguler">Reguler</option>
                            <option value="Kargo">Kargo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label fw-bold">Upload Gambar Produk</label>
                    <input type="file" class="form-control" id="gambar" name="gambar">
                    <small class="text-muted">Format yang didukung: JPG, JPEG, PNG. Maksimal ukuran 2MB.</small>
                </div>
                
                <div class="mb-3">
                    <label for="deskripsi_produk" class="form-label fw-bold">Deskripsi Produk</label>
                    <textarea class="form-control" id="deskripsi_produk" name="deskripsi_produk" rows="4" placeholder="Jelaskan detail produk..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="/manageProduk" class="btn btn-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
@endsection