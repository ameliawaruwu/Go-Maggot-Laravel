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
    {{-- ... (Bagian kartu statistik sama seperti sebelumnya) ... --}}
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
        <h6 class="fw-bold m-0 text-primary">Riwayat Order Terbaru</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Bukti Pembelian</th>
                        <th style="width: 220px;">Status Pesanan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentOrders as $order)
                    @php
                        // Ambil Nama Status dari Relasi
                        // Gunakan null coalescing (??) untuk jaga-jaga jika relasi putus/null
                        $namaStatus = $order->statusPesanan->status ?? 'Tidak Diketahui';

                        // Logika Warna Badge
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
                        <td class="text-center fw-bold">ORD{{ $order->id }}</td>
                        
                        <td>
                            {{ $order->pengguna->username ?? 'Guest' }}
                        </td>
                        
                        <td class="text-center">{{ $order->created_at->format('d M Y') }}</td>
                        
                        <td class="text-end fw-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>

                        <td class="text-center">
                            @if(!empty($order->bukti_pembayaran))
                                <a href="{{ asset('bukti_pembayaran/' . $order->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class='bx bx-image-alt'></i> Lihat
                                </a>
                            @else
                                <span class="text-muted small fst-italic">Belum Upload</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex flex-column gap-1">
                                
                                {{-- FORM UPDATE STATUS (Menggunakan ID Status) --}}
                                <form action="{{ route('pesanan.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    
                                    {{-- Name input sekarang adalah 'id_status_pesanan' --}}
                                    <select name="id_status_pesanan" class="form-select form-select-sm cursor-pointer" onchange="this.form.submit()">
                                        
                                        {{-- Loop Data Status dari Controller --}}
                                        @foreach ($statuses as $st)
                                            <option value="{{ $st->id_status_pesanan }}" 
                                                {{ $order->id_status_pesanan == $st->id_status_pesanan ? 'selected' : '' }}>
                                                {{ $st->status }}
                                            </option>
                                        @endforeach

                                    </select>
                                </form>

                                {{-- Label Badge Status --}}
                                <div class="text-center">
                                    <span class="badge {{ $badgeClass }} rounded-pill font-size-sm">
                                        {{ $namaStatus }}
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada order terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>

@endsection