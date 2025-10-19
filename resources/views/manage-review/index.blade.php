@extends('layouts.admin')

@section('content')

<main class="container-fluid mt-4">
   
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="left">
            <h1 class="mb-0">Ulasan & Komentar Produk</h1>
           
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ulasan Produk</li> 
                </ol>
         
        </div>
    </div>
    
    {{-- Kotak Notifikasi AJAX (Placeholder untuk JS) --}}
    <div id="notificationBox" class="alert alert-dismissible fade show d-none" role="alert" style="margin-bottom: 1.5rem;">
        Pesan notifikasi akan muncul di sini.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
    {{-- Pesan Status Sukses dari Controller --}}
    @if(session('status_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    {{-- Pesan Error/Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            
            {{-- HEADER TABEL & FILTER --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h5 class="card-title mb-0 fw-bold">Ulasan Pelanggan (Total: {{ $totalReviews ?? 0 }})</h5> 
                
                {{-- Filter Status --}}
                <form action="{{ route('review.index') }}" method="GET" class="d-inline-flex align-items-center gap-2">
                    <label for="reviewStatusFilter" class="form-label mb-0 text-nowrap">Status:</label>
                    <select id="reviewStatusFilter" name="status" class="form-select form-select-sm" onchange="this.form.submit();">
                        <option value="all" {{ ($statusFilter ?? 'all') == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="approved" {{ ($statusFilter ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ ($statusFilter ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="pending" {{ ($statusFilter ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-center table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Pengulas</th>
                            <th>Peringkat</th>
                            <th>Komentar</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews ?? [] as $review) 
                            @php
                                $reviewId = $review['id'] ?? $review->id ?? 'unknown'; 
                                $reviewStatus = strtolower($review['status'] ?? 'pending');
                            @endphp
                            <tr id="review-row-{{ $reviewId }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        @php
                                            $imagePath = $review['product_image'] ?? 'default.jpg';
                                            $imageUrl = asset('images/' . $imagePath);
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                            alt="Product" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        <span class="fw-bold">{{ e($review['product_name'] ?? 'N/A') }}</span> 
                                    </div>
                                </td>
                                <td class="text-center">{{ e($review['reviewer_name'] ?? 'Anonim') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        @php $rating = $review['rating'] ?? 0; @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bx {{ $i <= $rating ? 'bxs-star text-warning' : 'bx-star text-muted' }} me-1"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td>{{ Str::limit(e($review['komentar'] ?? ''), 50) }}</td>
                                <td class="text-center">
                                    <span id="status-badge-{{ $reviewId }}" class="badge p-2">
                                        @php
                                            $badgeClass = 'bg-secondary text-white'; 
                                            if ($reviewStatus === 'approved') {
                                                $badgeClass = 'bg-success text-white'; 
                                            } elseif ($reviewStatus === 'rejected') {
                                                $badgeClass = 'bg-danger text-white'; 
                                            } elseif ($reviewStatus === 'pending') {
                                                $badgeClass = 'bg-warning text-dark'; 
                                            }
                                        @endphp
                                        <span class="{{ $badgeClass }}">{{ ucfirst($reviewStatus) }}</span>
                                    </span>
                                </td>
                                <td class="text-center">{{ e($review['tanggal'] ?? 'N/A') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        
                                        {{-- Tombol Approve (Sintaks Aman) --}}
                                        <button type="button" class="btn btn-sm btn-success action-btn"
                                                data-review-id="{{ $reviewId }}"
                                                data-action="approve"
                                                title="Approve Review"
                                                id="approve-btn-{{ $reviewId }}"
                                                {{-- Sintaks aman untuk editor --}}
                                                @if(($review['status'] ?? 'pending') == 'approved') 
                                                    style="display: none;"
                                                @else
                                                    style="display: inline-block;"
                                                @endif
                                        > 
                                            <i class='bx bx-check-circle'></i>
                                        </button>
                                        
                                        {{-- Tombol Reject (Sintaks Aman) --}}
                                        <button type="button" class="btn btn-sm btn-danger action-btn"
                                                data-review-id="{{ $reviewId }}"
                                                data-action="reject"
                                                title="Reject Review"
                                                id="reject-btn-{{ $reviewId }}"
                                                {{-- Sintaks aman untuk editor --}}
                                                @if(($review['status'] ?? 'pending') == 'rejected')
                                                    style="display: none;"
                                                @else
                                                    style="display: inline-block;"
                                                @endif
                                        >
                                            <i class='bx bx-x-circle'></i>
                                        </button>
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-4">Tidak ada ulasan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            

        </div>
    </div>
</main>


@endsection

@push('scripts')
{{-- Memuat script aksi (Approve/Reject) --}}
<script src="{{ asset('js/admin/manage-reviews.js') }}"></script> 
@endpush