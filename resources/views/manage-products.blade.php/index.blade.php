
@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')

    {{-- Panggil Component Toast --}}
    <x-toast-notification />

    <div class="head-title d-flex justify-content-between align-items-center mb-4 flex-wrap">
        {{-- Header Kiri --}}
        <div class="left">
            <h1>Manajemen Produk</h1>
            {{-- Menggunakan Breadcrumb Bootstrap --}}
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Produk</li>
            </ol>
        </div>
        
        {{-- Tombol Tambah Produk --}}
        <a href="{{ url('addproduk') }}" class="btn btn-primary btn-download d-inline-flex align-items-center shadow-sm" id="addProductBtn">
            <i class='bx bxs-plus-circle me-2'></i>
            <span class="text">Add New Product</span>
        </a>
    </div>

    <div class="table-data card shadow-sm">
        <div class="order card-body">
            <div class="head d-flex justify-content-between align-items-center flex-wrap mb-4">
                <h3 class="m-0">Product List</h3>
                
                <div class="d-flex flex-wrap gap-2">
                    {{-- Search Container --}}
                    <div class="search-container input-group">
                        <input type="text" id="searchProduct" class="form-control" placeholder="Search products...">
                        <span class="input-group-text"><i class='bx bx-search'></i></span>
                    </div>
                    
                    {{-- Filter Container --}}
                    <div class="filter-container input-group">
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($fixedCategories as $fixedCategory)
                                <option value="{{ $fixedCategory }}">{{ $fixedCategory }}</option>
                            @endforeach
                            
                            @foreach ($categories as $category)
                                @if (!in_array($category->kategori, $fixedCategories))
                                    <option value="{{ $category->kategori }}">{{ $category->kategori }}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="input-group-text"><i class='bx bx-filter'></i></span>
                    </div>
                </div>
            </div>

            {{-- Tabel Produk --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Merk</th>
                            <th>Berat</th>
                            <th>Masa Simpan</th>
                            <th>Pengiriman</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataProduk as $row)
                            @php
                                $status = "Available";
                                $statusClass = "completed";
                                
                                if ($row->stok <= 0) {
                                    $status = "Out of Stock";
                                    $statusClass = "process";
                                } elseif ($row->stok <= 10) {
                                    $status = "Low Stock";
                                    $statusClass = "pending";
                                }
                                
                                // Gunakan asset() helper karena foto ada di public/photos/
                                $imagePath = !empty($row->gambar) ? asset('photos/' . $row->gambar) : asset('img/default-product.jpg');
                                $formattedPrice = number_format($row->harga, 0, ',', '.');
                                $description = !empty($row->deskripsi_produk) ? $row->deskripsi_produk : "No description available";
                            @endphp
                            <tr>
                                <td>PRD{{ str_pad($row->idproduk, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="d-flex align-items-center">
                                    <img src="{{ $imagePath }}" alt="{{ $row->namaproduk }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; margin-right: 12px;">
                                    <p class="m-0">{{ $row->namaproduk }}</p>
                                </td>
                                <td>{{ $row->kategori }}</td>
                                <td>Rp {{ $formattedPrice }}</td>
                                <td>{{ $row->stok }}</td>
                                <td>{{ $row->merk ?? 'GoMaggot' }}</td>
                                <td>{{ $row->berat }} {{ $row->berat >= 1 ? ' kg' : ' gr' }}</td>
                                <td>{{ $row->masapenyimpanan }}</td>
                                <td>{{ $row->pengiriman ?? 'Tidak diketahui' }}</td>
                                <td class="product-description" title="{{ $description }}">{{ $description }}</td>
                                <td><span class='status {{ $statusClass }}'>{{ $status }}</span></td>
                                <td>
                                    <a href="{{ url('editproduk?id=' . $row->idproduk) }}" class='btn btn-sm btn-warning btn-edit'><i class='bx bxs-edit'></i></a>
                                    <a href="{{ url('deleteproduk?id=' . $row->idproduk) }}" class='btn btn-sm btn-danger btn-delete' onclick="return confirm('Yakin ingin menghapus produk ini?');"><i class='bx bxs-trash'></i></a>
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
</main>
@endsection

@push('scripts')
{{-- Pindahkan semua logika JS (search, filter, toast) ke sini atau ke scriptadmin.js --}}
<script>
// Logic JS Search, Filter, dan Toast Anda harus berada di sini atau di file kustom yang dipanggil di layout master.
// Pastikan tidak ada duplikasi.
</script>
@endpush