@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bx bxs-calendar-check display-4 text-primary me-3"></i>
                <div>
                    <h3>{{ $newOrdersToday }}</h3>
                    <p class="text-muted mb-0">New Orders (Today)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bx bxs-group display-4 text-warning me-3"></i>
                <div>
                    <h3>{{ $totalRegisteredUsers }}</h3>
                    <p class="text-muted mb-0">Total Registered Users</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bx bxs-dollar-circle display-4 text-success me-3"></i>
                <div>
                    <h3>Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Sales (Today)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header">
        <h6 class="fw-bold m-0">Riwayat Order Terbaru</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th class="text-end">Total</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentOrders as $order)
                    @php
                        $badgeClass = match ($order->status) {
                            'menunggu pembayaran' => 'bg-warning text-dark',
                            'di proses' => 'bg-info text-dark',
                            'dikirim' => 'bg-primary',
                            'dibatalkan' => 'bg-danger',
                            'selesai' => 'bg-success',
                            default => 'bg-secondary',
                        };
                    @endphp

                    <tr>
                        <td>ORD{{ $order->id }}</td>
                        <td>{{ $order->nama_pelanggan }}</td>
                        <td>{{ date('Y-m-d', strtotime($order->created_at)) }}</td>
                        <td class="text-end">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>

                        <td>
                            <form action="{{ route('pesanan.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="menunggu pembayaran" {{ $order->status == 'menunggu pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                    <option value="di proses" {{ $order->status == 'di proses' ? 'selected' : '' }}>Di Proses</option>
                                    <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>

                            <span class="badge {{ $badgeClass }} mt-1">{{ ucfirst($order->status) }}</span>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>

@endsection
