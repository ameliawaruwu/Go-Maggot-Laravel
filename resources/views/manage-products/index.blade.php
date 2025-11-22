@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
   <div class="head-title d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="left">
            <h1>Manajemen Produk</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page" >Produk</li>
            </ol>
        </div>
        <a href="/manageProduk-input" class="btn btn-primary shadow-sm">
            <i class='bx bxs-plus-circle me-2'></i> Tambah Produk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-data card shadow-sm mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light text-center text-nowrap "> 
                        <tr>
                            <th>ID Produk</th> 
                            <th>Gambar</th>
                            <th>Nama Produk</th> 
                            <th>Deskripsi</th> 
                            <th>Kategori</th> 
                            <th>Merk</th>
                            <th>Masa Simpan</th>
                            <th>Pengiriman</th>
                            <th>Berat</th>
                            <th>Harga</th> 
                            <th>Stok</th> 
                            <th>Aksi</th> 
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($produk as $prd)
                           <td class="text-center">
                                <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                                    <span class="fw-bold">{{ $prd->id_produk }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <img src="{{ asset('photo/' . $prd->gambar) }}" class="shadow-sm rounded" style="width: 55px; height: 55px; object-fit: cover;" 
                                    alt="Img">
                            </td>
                            <td>{{ $prd->nama_produk }}</td>
                            <td>{{ Str::limit($prd->deskripsi_produk, 20) }}</td>
                            <td>{{ $prd->kategori }}</td>
                            <td>{{ $prd->merk }}</td>
                            <td>{{ $prd->masa_penyimpanan }}</td>
                            <td>{{ $prd->pengiriman }}</td>                          
                            <td>{{ $prd->berat }}</td>
                            <td class="text-end text-nowrap">Rp {{ number_format($prd->harga, 0, ',', '.') }}</td> 
                            <td class="text-center">{{ $prd->stok }}</td> 
                            <td class="text-center text-nowrap"> 
                                <a href="/manageProduk-edit/{{ $prd->id_produk }}" class="btn btn-sm btn-warning">
                                    <i class='bx bxs-edit-alt'></i> Edit
                                </a>
                                <a href="/manageProduk-hapus/{{ $prd->id_produk }}" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Hapus data {{ $prd->nama_produk }}?')">
                                    <i class='bx bxs-trash'></i> Hapus
                                </a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
               

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/delete.js') }}"></script>
@endpush

