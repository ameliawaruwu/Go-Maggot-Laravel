{{-- resources/views/dashboard/index.blade.php (Final) --}}
@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')

    {{-- Data Dummy untuk Riwayat Order --}}
    @php
        $recentOrders = [
            ['id' => 101, 'customer' => 'Adi Nugroho', 'date' => '2025-10-18', 'status' => 'Processing', 'total' => 150000],
            ['id' => 102, 'customer' => 'Budi Santoso', 'date' => '2025-10-17', 'status' => 'Completed', 'total' => 25000],
            ['id' => 103, 'customer' => 'Citra Dewi', 'date' => '2025-10-17', 'status' => 'Pending', 'total' => 45000],
            ['id' => 104, 'customer' => 'Dedy Kusuma', 'date' => '2025-10-16', 'status' => 'Shipped', 'total' => 80000],
            ['id' => 105, 'customer' => 'Eka Jaya', 'date' => '2025-10-16', 'status' => 'Completed', 'total' => 215000],
        ];
    @endphp
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        
        <div class="left">
            <h1 class="h3 mb-1 text-gray-800; fw-bold">Dashboard</h1> 
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item;  text-decoration-none"><a href="{{ url('dashboard') }}" class="text-decoration-none" >Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Home</li>
            </ol>
        </div>
        <div class="right mt-3 mt-md-0">
            <a href="#" class="btn btn-primary d-inline-flex align-items-center shadow-sm">
                <i class='bx bxs-cloud-download me-2'></i>
                <span class="text">Download Report</span>
            </a>
        </div>
    </div>

    <hr class="mb-4">

    <div class="row g-4 mb-4"> 
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card p-3 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <i class='bx bxs-calendar-check display-4 text-primary me-3'></i>
                    <div class="text">
                        <h3 class="mb-0">0</h3>
                        <p class="text-muted mb-0">New Orders (Today)</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card p-3 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <i class='bx bxs-group display-4 text-warning me-3'></i>
                    <div class="text">
                        <h3 class="mb-0">23</h3>
                        <p class="text-muted mb-0">Total Registered Users</p>
                    </div>
                </div>
            </div>
        </div>
        
    
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card p-3 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <i class='bx bxs-dollar-circle display-4 text-success me-3'></i>
                    <div class="text">
                        <h3 class="mb-0">Rp 2.5 Jt</h3>
                        <p class="text-muted mb-0">Total Sales (Today)</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <hr class="mt-4">

    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark fw-bold">Riwayat Order Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentOrders as $order)
                                    @php
                                        $badgeClass = match ($order['status']) {
                                            'Completed' => 'bg-success',
                                            'Processing' => 'bg-info',
                                            'Shipped' => 'bg-primary',
                                            'Pending' => 'bg-warning text-dark',
                                            default => 'bg-secondary',
                                        };
                                        $formattedTotal = 'Rp ' . number_format($order['total'], 0, ',', '.');
                                    @endphp
                                    <tr>
                                        <td>ORD{{ $order['id'] }}</td>
                                        <td>{{ $order['customer'] }}</td>
                                        <td>{{ $order['date'] }}</td>
                                        <td class="text-end">{{ $formattedTotal }}</td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ $order['status'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada order terbaru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
