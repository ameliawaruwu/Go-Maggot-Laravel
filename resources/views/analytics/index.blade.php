@extends('layouts.admin')

@section('title', 'Analytics & Statistik')

@section('content')

    <div class="head-title d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="left">
            <h1 class="h3 mb-1 text-gray-800">Sales & Visitor Analytics</h1>
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Analytics</li>
            </ol>
        </div>
        <div class="right mt-3 mt-md-0">
            <a href="#" class="btn btn-primary d-inline-flex align-items-center shadow-sm" onclick="window.print()">
                <i class='bx bxs-cloud-download me-2'></i>
                <span class="text">Print Report</span>
            </a>
        </div>
    </div>
    
    <hr class="mb-4">

    <div class="row g-4">
        <!-- Sales Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary fw-bold">
                        <i class='bx bx-line-chart me-1'></i> Sales Analytics (7 Hari Terakhir)
                    </h6>
                </div>
                <div class="card-body" style="height: 300px;"> 
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Visitor Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-success fw-bold">
                        <i class='bx bx-user-plus me-1'></i> User Registration (Visitor)
                    </h6>
                </div>
                <div class="card-body" style="height: 300px;"> 
                    <canvas id="visitorsChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Top Products Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-info fw-bold">
                        <i class='bx bx-package me-1'></i> Stok Produk (Top 5 Terendah)
                    </h6>
                </div>
                <div class="card-body" style="height: 300px;"> 
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-warning fw-bold">
                        <i class='bx bx-pie-chart-alt-2 me-1'></i> Status Order User
                    </h6>
                </div>
                <div class="card-body" style="height: 300px;"> 
                    <canvas id="userOrderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Passing Data PHP ke JS -->
<script>
    // Membuat variabel global agar bisa dibaca oleh file external js
    window.analyticsData = {
        dates: {!! json_encode($dates) !!},
        sales: {!! json_encode($salesData) !!},
        visitors: {!! json_encode($visitorData) !!},
        products: {!! json_encode($productNames) !!},
        stocks: {!! json_encode($productStock) !!},
        statusLabels: {!! json_encode($statusLabels) !!},
        statusCounts: {!! json_encode($statusCounts) !!}
    };
</script>

<!-- Load Script Custom -->
<script src="{{ asset('js/admin/analitik.js') }}"></script>
@endpush