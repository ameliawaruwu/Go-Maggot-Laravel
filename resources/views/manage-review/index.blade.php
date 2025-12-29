@extends('layouts.admin')

@section('content')

<main class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="left">
            <h1 class="mb-0 fw-bold">Komentar Produk</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ulasan Produk</li> 
            </ol>
        </div>
    </div>
    
    {{-- ALERT SUKSES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <div class="d-flex align-items-center">
                <i class='bx bxs-check-circle fs-4 me-2'></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h5 class="card-title mb-0 fw-bold">Ulasan Pelanggan (Total: {{ $review->count() }})</h5> 
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-center table-light">
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th style="width: 25%;">Komentar</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($review as $rp) 
                            <tr>
                                <td class="text-center fw-bold">#{{ $rp->id_review }}</td>
                                
                                {{-- Pelanggan --}}
                                <td>
                                    @if($rp->tampilkan_username == 1)
                                        <span class="fw-bold">{{ $rp->pengguna->username ?? 'User' }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Anonim</span>
                                    @endif
                                </td>

                                {{-- Produk --}}
                                <td>
                                    <span class="text-primary">{{ $rp->produk->nama_produk ?? '-' }}</span>
                                </td>
                                
                                {{-- Komentar --}}
                                <td>
                                    <small class="text-muted">"{{ Str::limit($rp->komentar, 50) }}"</small>
                                </td>
                                
                                {{-- Rating --}}
                                <td class="text-center text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class='bx {{ $i <= $rp->rating_seller ? 'bxs-star' : 'bx-star' }}'></i>
                                    @endfor
                                </td>

                                {{-- KOLOM STATUS --}}
                                <td class="text-center">
                                    @if($rp->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($rp->status == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>

                                <td class="text-center small">
                                    {{ \Carbon\Carbon::parse($rp->tanggal_review)->format('d M Y') }}
                                </td>

                                {{-- KOLOM AKSI (3 TOMBOL) --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        
                                        {{-- 1. TOMBOL APPROVE (HIJAU) --}}
                                        <form action="/manageReview-approve/{{ $rp->id_review }}" method="POST">
                                            @csrf
                                            {{-- Disable tombol jika statusnya sudah approved --}}
                                            <button type="submit" class="btn btn-sm btn-success text-white" title="Setujui" 
                                                {{ $rp->status == 'approved' ? 'disabled' : '' }}>
                                                <i class='bx bx-check'></i>
                                            </button>
                                        </form>

                                        {{-- 2. TOMBOL PENDING (KUNING) --}}
                                        <form action="/manageReview-pending/{{ $rp->id_review }}" method="POST">
                                            @csrf
                                            {{-- Disable tombol jika statusnya sudah pending --}}
                                            <button type="submit" class="btn btn-sm btn-warning text-dark" title="Kembalikan ke Pending"
                                                {{ $rp->status == 'pending' || empty($rp->status) ? 'disabled' : '' }}>
                                                <i class='bx bx-time-five'></i>
                                            </button>
                                        </form>

                                        {{-- 3. TOMBOL REJECT (MERAH) --}}
                                        <form action="/manageReview-reject/{{ $rp->id_review }}" method="POST">
                                            @csrf
                                            {{-- Disable tombol jika statusnya sudah rejected --}}
                                            <button type="submit" class="btn btn-sm btn-danger" title="Tolak"
                                                {{ $rp->status == 'rejected' ? 'disabled' : '' }}>
                                                <i class='bx bx-x'></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center p-4 text-muted">Belum ada ulasan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

@endsection