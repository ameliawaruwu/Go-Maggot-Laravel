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
    
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h5 class="card-title mb-0 fw-bold">Ulasan Pelanggan (Total: {{ $review->count() }})</h5> 
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-center table-light">
                        <tr>
                            <th>ID Review</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Komentar</th>
                            <th>Peringkat</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($review as $rp) 
                            <tr>
                                <td class="text-center fw-bold">{{ $rp->id_review }}</td>
                                <td>
                                    @if($rp->tampilkan_username == 1)
                                        {{ $rp->pengguna->username ?? 'User' }}
                                    @else
                                        <span class="text-muted fst-italic">Anonim</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="fw-bold text-black">
                                        {{ $rp->produk->nama_produk ?? 'Produk Terhapus' }}
                                    </span>
                                </td>
                                
                                <td>
                                    <small class="text-muted">{{ Str::limit($rp->komentar, 50) }}</small>
                                </td>
                                
                                <td class="text-center text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class='bx {{ $i <= $rp->rating_seller ? 'bxs-star' : 'bx-star' }}'></i>
                                    @endfor
                                </td>

                                <td class="text-center">
                                    @if($rp->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($rp->status == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{ $rp->tanggal_review }}
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($rp->status != 'approved')
                                            <form action="/manageReview-approve/{{ $rp->id_review }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success text-white" title="Setujui">
                                                    <i class='bx bx-check'></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($rp->status != 'rejected')
                                            <form action="/manageReview-reject/{{ $rp->id_review }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                                                    <i class='bx bx-x'></i>
                                                </button>
                                            </form>
                                        @endif

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