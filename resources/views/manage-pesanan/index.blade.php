@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('styles')
<style>
    /* Styling Tambahan untuk Estetika */
    .table-responsive {
        border-radius: 12px;
    }
    .table thead th {
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #8898aa;
        font-weight: 700;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .table tbody td {
        vertical-align: middle;
        padding-top: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f3f9;
    }
    .avatar-circle {
        width: 38px;
        height: 38px;
        background-color: #e0e7ff; /* Warna soft indigo */
        color: #4338ca;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .form-select-status {
        background-color: #f3f4f6;
        border: none;
        color: #4b5563;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 20px;
        padding: 0.4rem 2rem 0.4rem 1rem;
    }
    .form-select-status:focus {
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }
</style>
@endsection

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Kelola Pesanan</h1>
            <p class="text-muted small mb-0">Pantau semua transaksi masuk secara real-time.</p>
        </div>
        
        {{-- Pencarian Modern --}}
        <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white" style="width: 280px; border: 1px solid #e9ecef;">
            <span class="input-group-text bg-white border-0 ps-3 text-muted"><i class='bx bx-search'></i></span>
            <input type="text" class="form-control border-0 ps-1 form-control-sm" placeholder="Cari ID atau Nama..." style="box-shadow: none;">
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4 rounded-3" role="alert">
            <div class="d-flex align-items-center">
                <i class='bx bxs-check-circle fs-5 me-2'></i>
                <span class="fw-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- TABEL CARD --}}
    <div class="card shadow border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Order</th>
                            <th>Pelanggan</th>
                            <th>Detail Produk</th>
                            <th>Waktu Pesanan</th>
                            <th>Total Tagihan</th>
                            <th class="text-center">Bukti Bayar</th>
                            <th class="pe-4">Status Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                        @php
                            // Ambil inisial nama untuk avatar
                            $name = $order->pengguna->username ?? 'G';
                            $initial = strtoupper(substr($name, 0, 1));
                        @endphp
                        <tr>
                            {{-- ID --}}
                            <td class="ps-4">
                                <span class="font-monospace fw-bold text-black">{{ $order->id_pesanan }}</span>
                            </td>

                            {{-- Pelanggan (Avatar + Nama) --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $order->pengguna->username ?? 'Guest' }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">{{ $order->pengguna->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Produk --}}
                            <td>
                                @if($order->detailPesanan->count() > 0)
                                    <div class="d-flex flex-column gap-1">
                                        @foreach ($order->detailPesanan as $detail)
                                            <div class="d-flex align-items-center bg-light rounded px-2 py-1" style="width: fit-content;">
                                                <i class='bx bx-package text-secondary me-1 small'></i>
                                                <span class="small text-dark fw-semibold me-2">{{ $detail->produk->nama_produk ?? '-' }}</span>
                                                <span class="badge bg-white text-dark shadow-sm border" style="font-size: 0.65rem;">x{{ $detail->jumlah }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Kosong</span>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td>
                                <div class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d M Y') }}</div>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <i class='bx bx-time-five me-1'></i>{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('H:i') }}
                                </div>
                            </td>

                            {{-- Total --}}
                            <td>
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Bukti --}}
                            <td class="text-center">
                                @if($order->pembayaran && !empty($order->pembayaran->bukti_bayar))
                                    <a href="{{ asset('photo/' . $order->pembayaran->bukti_bayar) }}" target="_blank" 
                                       class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                                        <i class='bx bx-image-alt me-1'></i> Cek
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted border fw-normal">Belum Upload</span>
                                @endif
                            </td>
                            
                            {{-- Status (Styled Dropdown) --}}
                            <td class="pe-4">
                                <form action="{{ route('pesanan.updateStatus', $order->id_pesanan) }}" method="POST">
                                    @csrf
                                    <select name="id_status_pesanan" 
                                            class="form-select form-select-status cursor-pointer" 
                                            onchange="this.form.submit()">
                                        @foreach ($statuses as $st)
                                            <option value="{{ $st->id_status_pesanan }}" 
                                                {{ $order->id_status_pesanan == $st->id_status_pesanan ? 'selected' : '' }}>
                                                {{ $st->status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center opacity-50">
                                    <i class='bx bx-clipboard fs-1 mb-2'></i>
                                    <p class="mb-0 fw-bold">Belum ada pesanan masuk.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- PAGINATION --}}
            @if($recentOrders->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-end">
                        {{ $recentOrders->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection