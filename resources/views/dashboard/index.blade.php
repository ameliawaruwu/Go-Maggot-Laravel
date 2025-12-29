@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    {{-- HEADER & FILTER WAKTU --}}
    <div class="head-title d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="left">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
        </div>
        
        <div class="right d-flex gap-2 align-items-center mt-3 mt-md-0">
            <form action="{{ url()->current() }}" method="GET">
                <select name="filter" class="form-select shadow-sm" onchange="this.form.submit()">
                    <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </form>
            <button class="btn btn-primary shadow-sm" onclick="window.print()">
                <i class='bx bxs-printer'></i>
            </button>
        </div>
    </div>

    {{-- ALERT SUKSES --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- 1. KARTU STATISTIK (5 KARTU SEJAJAR) --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 g-3 mb-4">
        
        {{-- Order Hari Ini --}}
        <div class="col">
            <div class="card p-3 shadow-sm border-0 border-start border-primary border-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                        <i class="bx bxs-calendar-check fs-2 text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">{{ $newOrdersToday }}</h4>
                        <p class="text-muted mb-0 small">Order Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Omzet Bulan Ini --}}
        <div class="col">
            <div class="card p-3 shadow-sm border-0 border-start border-success border-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                        <i class="bx bxs-dollar-circle fs-2 text-success"></i>
                    </div>
                    <div class="text-truncate">
                        <h4 class="mb-0 fw-bold">Rp {{ number_format($totalSalesMonth, 0, ',', '.') }}</h4>
                        <p class="text-muted mb-0 small">Omzet Bulan Ini</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total User --}}
        <div class="col">
            <div class="card p-3 shadow-sm border-0 border-start border-warning border-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3">
                        <i class="bx bxs-group fs-2 text-warning"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">{{ $totalRegisteredUsers }}</h4>
                        <p class="text-muted mb-0 small">Total User</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Login --}}
        <div class="col">
            <div class="card p-3 shadow-sm border-0 border-start border-info border-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                        <i class="bx bxs-user-check fs-2 text-info"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">{{ $activeUsersToday }}</h4>
                        <p class="text-muted mb-0 small">User Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stok Menipis (Contoh Data Statis) --}}
        <div class="col">
            <div class="card p-3 shadow-sm border-0 border-start border-danger border-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-3">
                        <i class="bx bxs-package fs-2 text-danger"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">5</h4> 
                        <p class="text-muted mb-0 small">Stok Menipis</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. GRAFIK ANALITIK (LAYOUT 4 KOLOM / GRID 2x2) --}}
    <div class="row g-4 mb-4">
        
        {{-- 1. Grafik Penjualan (Kiri Atas) --}}
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary"><i class='bx bx-line-chart'></i> Tren Pendapatan</h6>
                </div>
                <div class="card-body"> 
                    <div style="height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Grafik Pertumbuhan User (Kanan Atas) --}}
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-success"><i class='bx bx-user-plus'></i> User Baru</h6>
                </div>
                <div class="card-body"> 
                    <div style="height: 300px;">
                        <canvas id="visitorsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Grafik Produk Terlaris (Kiri Bawah) --}}
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-info"><i class='bx bx-trophy'></i> Top 5 Produk</h6>
                </div>
                <div class="card-body"> 
                    <div style="height: 300px;">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Grafik Status Pesanan (Kanan Bawah) --}}
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-warning"><i class='bx bx-pie-chart-alt-2'></i> Status Pesanan</h6>
                </div>
                <div class="card-body"> 
                    <div style="height: 300px; position: relative;">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- 1. SALES CHART (Line) ---
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($salesData) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { 
                y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // --- 2. VISITORS CHART (Line) ---
    const visitorCtx = document.getElementById('visitorsChart').getContext('2d');
    new Chart(visitorCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'User Baru',
                data: {!! json_encode($visitorData) !!},
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { 
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // --- 3. TOP PRODUCTS (Bar Horizontal) ---
    const prodCtx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(prodCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($productNames) !!},
            datasets: [{
                label: 'Terjual',
                data: {!! json_encode($productSales) !!},
                backgroundColor: '#36b9cc',
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });

    // --- 4. ORDER STATUS (Doughnut - Data Dummy) ---
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Diproses', 'Selesai', 'Batal'],
            datasets: [{
                data: [5, 3, 15, 2], // Ganti ini dengan variabel backend nanti
                backgroundColor: ['#f6c23e', '#4e73df', '#1cc88a', '#e74a3b'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
            },
            cutout: '70%',
        }
    });
</script>
@endpush