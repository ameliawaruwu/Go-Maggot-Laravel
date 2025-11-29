@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- KARTU STATISTIK --}}
<div class="row g-4 mb-4">

    <div class="col-lg-4">
        <div class="card p-3 shadow-sm border-0">
            <div class="d-flex align-items-center">
                <i class="bx bxs-calendar-check display-4 text-primary me-3"></i>
                <div>
                    <h3 class="mb-0">{{ $newOrdersToday }}</h3>
                    <p class="text-muted mb-0">New Orders (Today)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-3 shadow-sm border-0">
            <div class="d-flex align-items-center">
                <i class="bx bxs-group display-4 text-warning me-3"></i>
                <div>
                    <h3 class="mb-0">{{ $totalRegisteredUsers }}</h3>
                    <p class="text-muted mb-0">Total Registered Users</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-3 shadow-sm border-0">
            <div class="d-flex align-items-center">
                <i class="bx bxs-dollar-circle display-4 text-success me-3"></i>
                <div>
                    <h3 class="mb-0">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Sales (Today)</p>
                </div>
            </div>
        </div>
    </div>

</div>


{{-- TABEL RIWAYAT ORDER --}}
<div class="card shadow mt-4 border-0">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold m-0 text-black">Riwayat Order Terbaru</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Tanggal Pesan</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Bukti Pembelian</th>
                        <th style="width: 220px;">Status Pesanan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentOrders as $order)

                    @php
                        $namaStatus = $order->statusPesanan;

                        $badgeClass = match ($namaStatus) {
                            'Menunggu Pembayaran' => 'bg-warning text-dark',
                            'Diproses'            => 'bg-info text-dark',
                            'Dikirim'             => 'bg-primary',
                            'Selesai'             => 'bg-success',
                            'Dibatalkan'          => 'bg-danger',
                            default               => 'bg-secondary',
                        };
                    @endphp

                    <tr>

                        <td class="text-center fw-bold">{{ $order->id_pesanan }}</td>

                        {{-- PELANGGAN --}}
                        <td>{{ $order->pengguna->username ?? 'Guest' }}</td>

                        <td>
                            @if ($order->detailPesanan->count() > 0)
                                <div style="font-size: 0.85rem;">
                                    @foreach ($order->detailPesanan as $detail)
                                        <div class="d-flex justify-content-between align-items-center border-bottom mb-1 pb-1">
                                            <div>
                                                {{-- Nama Produk --}}
                                                <span class="fw-bold text-dark">
                                                    {{ $detail->produk->nama_produk ?? 'Produk dihapus' }}
                                                </span>
                                                <br>
                                                {{-- Jumlah x Harga Satuan --}}
                                                <span class="text-muted" style="font-size: 0.75rem;">
                                                    {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_saat_pembelian, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            
                                            
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small fst-italic">Tidak ada detail produk</span>
                            @endif
                        </td>


                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d M Y') }}
                        </td>

                        <td class="text-end fw-bold">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>

                        <td class="text-center">
                            @if(!empty($order->bukti_pembayaran))
                                <a href="{{ asset('photos/' . $order->bukti_pembayaran) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class='bx bx-image-alt'></i> Lihat
                                </a>
                            @else
                                <span class="text-muted small fst-italic">Belum Upload</span>
                            @endif
                        </td>

                       
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <form action="{{ route('pesanan.updateStatus', $order->id_pesanan) }}" method="POST">
                                    @csrf
                                    <select name="id_status_pesanan" 
                                            class="form-select form-select-sm cursor-pointer"
                                            onchange="this.form.submit()">

                                        @foreach ($statuses as $st)
                                            <option value="{{ $st->id_status_pesanan }}"
                                                {{ $order->id_status_pesanan == $st->id_status_pesanan ? 'selected' : '' }}>
                                                {{ $st->status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>

                            </div>
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada order terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>

@endsection
