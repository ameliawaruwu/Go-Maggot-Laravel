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
        
      
        <a href="{{ route('products.create') }}" class="btn btn-primary d-inline-flex align-items-center shadow-sm" id="addProductBtn">
            <i class='bx bxs-plus-circle me-2'></i>
            <span class="text">Add New Product</span>
        </a>
    </div>
    
    @if(session('status_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    
    
    {{-- Tabel Produk --}}
    <div class="table-data card shadow-sm mt-4">
        <div class="order card-body">
            <div class="head d-flex justify-content-between align-items-center flex-wrap mb-4">
                <h3 class="m-0 fw-bold">Product List</h3> 
                
                <div class="d-flex flex-wrap gap-2">
                    <div class="search-container input-group">
                        <input type="text" id="searchProduct" class="form-control" placeholder="Search products...">
                        <span class="input-group-text"><i class='bx bx-search'></i></span>
                    </div>
                    
                    <div class="filter-container input-group">
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($fixedCategories as $fixedCategory)
                                <option value="{{ $fixedCategory }}">{{ $fixedCategory }}</option>
                            @endforeach
                            
                            @foreach ($categories as $category)
                                @if (!in_array($category['kategori'], $fixedCategories))
                                    <option value="{{ $category['kategori'] }}">{{ $category['kategori'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="input-group-text"><i class='bx bx-filter'></i></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle"> {{-- Ditambahkan align-middle --}}
                    <thead class="text-center table-light"> {{-- Ditambahkan text-center dan table-light --}}
                        <tr>
                            <th style="width: 80px;">ID</th> 
                            <th>Produk</th> 
                            <th style="width: 120px;">Kategori</th> 
                            <th class="text-end" style="width: 120px;">Harga</th> 
                            <th class="text-end" style="width: 70px;">Stok</th> 
                            <th style="width: 100px;">Merk</th>
                            <th class="text-end" style="width: 90px;">Berat</th> 
                            <th style="width: 120px;">Masa Simpan</th>
                            <th style="width: 120px;">Pengiriman</th>
                            <th style="max-width: 200px; min-width: 150px;">Deskripsi</th> 
                            <th style="width: 110px;">Status</th> 
                            <th style="width: 100px;">Aksi</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataProduk as $row)
                            @php
                                // ... Logika Status dan Formatting ...
                                $status = "Available";
                                $statusClass = "completed"; 
                                
                                if ($row['stok'] <= 0) {
                                    $status = "Out of Stock";
                                    $statusClass = "process";
                                } elseif ($row['stok'] <= 10) {
                                    $status = "Low Stock";
                                    $statusClass = "pending";
                                }
                                
                                $imagePath = !empty($row['gambar']) ? asset('images/' . $row['gambar']) : asset('img/default-product.jpg');
                                $formattedPrice = number_format($row['harga'], 0, ',', '.');
                                $fullDescription = $row['deskripsi_produk'] ?? "No description available";
                                $shortDescription = \Illuminate\Support\Str::limit($fullDescription, 35, '...'); 
                            @endphp
                            <tr>
                                <td class="text-center">PRD{{ str_pad($row['idproduk'], 3, '0', STR_PAD_LEFT) }}</td>
                                
                                {{-- 2. Produk (Gabungan Gambar dan Nama) --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $imagePath }}" alt="{{ $row['namaproduk'] }}" class="flex-shrink-0" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; margin-right: 12px;">
                                        <p class="m-0 text-truncate" title="{{ $row['namaproduk'] }}">{{ $row['namaproduk'] }}</p>
                                    </div>
                                </td>
                                
                                <td class="text-center">{{ $row['kategori'] }}</td>
                                <td class="text-end">Rp {{ $formattedPrice }}</td> 
                                <td class="text-end">{{ $row['stok'] }}</td> 
                                <td class="text-center">{{ $row['merk'] ?? '-' }}</td>
                                <td class="text-end">
                                    {{ $row['berat'] }} {{ $row['satuan_berat'] ?? 'kg' }} 
                                </td> 
                                <td class="text-center">{{ $row['masapenyimpanan'] }}</td>
                                <td class="text-center">{{ $row['pengiriman'] ?? '-' }}</td>
                                <td title="{{ $fullDescription }}" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $shortDescription }}
                                </td>
                                <td class="text-center">
                                    <span class='status {{ $statusClass }}'>{{ $status }}</span>
                                </td>
                                
                               
                                <td class="text-center"> 
                                    <div class="d-flex justify-content-center gap-2"> 
                                        
                                        
                                        <a href="{{ route('products.edit', $row['idproduk']) }}" class='btn btn-sm btn-warning text-dark' title="Edit">
                                            <i class='bx bxs-edit-alt'></i> {{-- Ikon diseragamkan dengan bxs-edit-alt --}}
                                        </a>

                                       
                                        <form action="{{ route('products.destroy', $row['idproduk']) }}" 
                                              method="POST" 
                                              class="d-inline delete-form m-0" {{-- Class delete-form dan hapus margin --}}
                                              data-item-name="Produk: {{ $row['namaproduk'] }}"> {{-- Data untuk konfirmasi JS --}}
                                            @csrf
                                            @method('DELETE')
                                            
                                            <button type="submit" 
                                                            class="btn btn-sm btn-danger" {{-- Tombol Merah Solid --}}
                                                            title="Hapus">
                                                <i class='bx bxs-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan='12' class='text-center py-4'>No products found</td></tr>
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