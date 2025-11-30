@extends('layouts.admin')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Manajemen Produk</h1>
            <ul class="breadcrumb">
                <li><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="/manageProduk" class="text-decoration-none">Produk</a></li>
            </ul>
        </div>
        <a href="/manageProduk-input" class="btn-download" style="padding: 10px 15px; border-radius: 8px; font-weight: 600; text-decoration:none;">
            <i class='bx bxs-plus-circle'></i>
            <span class="text">Tambah Produk</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Daftar Produk</h3>
                <div style="display: flex; gap: 10px;">
                    <div class="search-box" style="position: relative;">
                        <input type="text" placeholder="Cari produk..." style="padding: 8px 30px 8px 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <i class='bx bx-search' style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                    <i class='bx bx-filter' style="font-size: 24px; cursor: pointer;"></i> 
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead> 
                        <tr>
                            <th>ID Produk</th> 
                            <th>Gambar</th>
                            <th>Nama Produk</th> 
                            <th>Kategori</th> 
                            <th>Harga</th> 
                            <th>Stok</th> 
                            <th>Deskripsi (Singkat)</th> 
                            <th>Aksi</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk as $prd)
                        <tr>
                            <td>
                                <span class="fw-bold" style="height: 50px; display: flex; align-items: center; justify-content: center;">{{ $prd->id_produk }}</span>
                            </td>
                            <td>
                                @if($prd->gambar)
                                    <img src="{{ asset('photo/' . $prd->gambar) }}" 
                                         style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover;" 
                                         alt="{{ $prd->nama_produk }}">
                                @else
                                    <span style="color: gray; font-size: 12px;">No Image</span>
                                @endif
                            </td>
                            <td style="font-weight: 600;">{{ $prd->nama_produk }}</td>
                            <td>{{ $prd->kategori }}</td>
                            <td class="text-nowrap">Rp {{ number_format($prd->harga, 0, ',', '.') }}</td> 
                            <td>
                                <span class="badge {{ $prd->stok > 0 ? 'bg-success' : 'bg-danger' }}" style="padding: 5px 10px; border-radius: 5px; color: white;">
                                    {{ $prd->stok }}
                                </span>
                            </td> 
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                    {{ $prd->deskripsi_produk }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="/manageProduk-edit/{{ $prd->id_produk }}" 
                                       class="btn btn-sm btn-warning text-white"
                                       style="padding: 5px 10px; border-radius: 5px; text-decoration: none; background-color: #ffc107;">
                                        <i class='bx bxs-edit-alt'></i>
                                    </a>
                                    <a href="/manageProduk-hapus/{{ $prd->id_produk }}" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin ingin menghapus produk {{ $prd->nama_produk }}?')"
                                       style="padding: 5px 10px; border-radius: 5px; text-decoration: none; background-color: #dc3545; color: white;">
                                        <i class='bx bxs-trash'></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection