@extends('layouts.admin')

@section('title', 'Manajemen Galeri Foto')


@section('content')
    <div class="head-title d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="left">
            <h1 class="h3 mb-1 text-gray-800">Galeri Foto</h1>
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Galeri</li>
            </ol>
        </div>
        
        <a href="{{ route('gallery.create') }}" class="btn btn-primary d-inline-flex align-items-center shadow-sm">
            <i class='bx bxs-plus-circle me-2'></i>
            <span class="text">Tambah Galeri Baru</span>
        </a>
    </div>

    <div class="table-data mt-4"> 
        <div class="galeri-management-container">
            <div class="head d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h3 class="m-0">Daftar Galeri</h3>
                
                <div class="search-container input-group w-auto" style="min-width: 250px;">
                    <input type="text" id="searchGaleri" class="form-control" placeholder="Cari galeri...">
                    <span class="input-group-text"><i class='bx bx-search'></i></span>
                </div>
            </div>

            
            <div class="table-responsive bg-white shadow-sm rounded">
                <table class="table table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5px; vertical-align: middle;">Id</th>
                            <th style="width: 150px; vertical-align: middle;">Gambar</th>
                            <th style="width: 150px; vertical-align: middle;">Keterangan</th>
                            <th class="text-center" style="width: 100px; vertical-align: middle;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($galleries as $galeri)
                            <tr class="align-middle"> 
                                <td data-label="ID" class="align-middle" style="vertical-align: middle;"> {{ $galeri['id_galeri'] }}</td> 
                                <td data-label="Gambar" style="vertical-align: middle;">
                                    <img src="{{ asset('images/' . $galeri['gambar']) }}" 
                                         alt="Gambar Galeri" 
                                         class="d-block" 
                                         style="width: 50px; height: 60px; object-fit: cover;">
                                </td>
                                <td data-label="Keterangan" class="align-middle" style="vertical-align: middle;">{{ $galeri['keterangan'] }}</td> 
                                <td data-label="Aksi" class="text-center align-middle" style="vertical-align: middle;">
                                    
                                    <div class="d-flex justify-content-center"> 
                                        <a href="{{ route('gallery.edit', $galeri['id_galeri']) }}" class='btn btn-sm btn-warning text-white me-2' title="Edit">
                                            <i class='bx bxs-edit-alt'></i>
                                        </a> 
                                        
                                        <form action="{{ route('gallery.destroy', $galeri['id_galeri']) }}" method="POST" class="d-inline delete-form">
                                            @csrf 
                                            @method('DELETE') 
                                            
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm delete-button" 
                                                    data-id="{{ $galeri['id_galeri'] }}" 
                                                    title="Hapus">
                                                        <i class='bx bxs-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan='4' class='text-center py-4'>Tidak ada galeri yang ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/delete.js') }}"></script>
@endpush