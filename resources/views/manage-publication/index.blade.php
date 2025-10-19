@extends('layouts.admin') 

@section('content')
   
    <div class="head-title">
        <div class="left">
            <h1>Artikel</h1>
            <ul class="breadcrumb">
                <li><a   class="text-decoration-none">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a  href="{{ route('publication.index') }}"  class="text-decoration-none">Artikel</a></li>
            </ul>
        </div>
        <a href="{{ route('publication.create') }}" class="btn btn-primary">
            <i class='bx bxs-plus-circle me-1'></i>
            <span class="text">Add New Artikel</span>
        </a>
    </div>
    
    @if(session('status_message'))
        <div class="alert alert-success mt-3 alert-dismissible fade show" role="alert">
            {{ session('status_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card shadow-sm mt-4"> 
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 fw-bold">
                <h3 class="card-title mb-0 h5">Daftar Artikel</h3>
                <form action="{{ route('publication.index') }}" method="GET" class="d-flex search-form">
                    <input type="text" name="search_query" placeholder="Cari artikel..." class="form-control me-2" value="{{ old('search_query', $searchQuery ?? '') }}" style="width: 200px;">
                    <button type="submit" class="btn btn-outline-secondary"><i class='bx bx-search'></i></button>
                </form>
            </div>
        
            @if(empty($articles))
                <p class="text-center text-muted">Tidak ada artikel yang ditemukan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Tanggal</th>
                                <th>Konten</th>
                                <th>Hak Cipta</th>
                                <th style="width: 120px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articles as $article)
                                <tr>
                                    <td>{{ $article['id_artikel'] }}</td>
                                    <td>{{ $article['judul'] }}</td>
                                    <td>{{ $article['penulis'] }}</td>
                                    <td>{{ $article['tanggal'] }}</td>
                                    <td>{{ Str::limit(strip_tags($article['konten']), 40) }}</td>
                                    <td>{{ $article['hak_cipta'] }}</td>
                                    
                                    
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center"> 
                                            
                                            
                                            <a href="{{ route('publication.edit', $article['id_artikel']) }}" 
                                               title="Edit Artikel" 
                                               class="btn btn-sm btn-warning text-dark">
                                                <i class='bx bxs-edit-alt'></i>
                                            </a>
                                            
                                            
                                            <form action="{{ route('publication.destroy', $article['id_artikel']) }}" 
                                                  method="POST" 
                                                  class="delete-form m-0"
                                                  data-article-title="artikel: {{ $article['judul'] }}"
                                                  style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                
                                                <button type="submit" title="Hapus Artikel" class="btn btn-sm btn-danger">
                                                    <i class='bx bxs-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</main>

{{-- Pastikan file JavaScript untuk konfirmasi delete terload --}}
<script src="{{ asset('js/delete.js') }}"></script>
@endsection