{{-- resources/views/analytics/index.blade.php --}}
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
            <a href="#" class="btn btn-primary d-inline-flex align-items-center shadow-sm">
                <i class='bx bxs-cloud-download me-2'></i>
                <span class="text">Download Analytics Report</span>
            </a>
        </div>
    </div>
    
    <hr class="mb-4">

    <div class="row g-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark fw-bold">Sales Analytics</h6>
                </div>
                <div class="card-body" style="height: 300px;"> 
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark fw-bold">Visitor Statistics</h6>
                </div>
                <div class="card-body" style="height: 300px;"> 
                    <canvas id="visitorsChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark fw-bold">Produk Paling Laku</h6>
                </div>
                <div class="card-body" style="height: 300px;"> 
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark fw-bold">Status Order User</h6>
                </div>
                <div class="card-body" style="height: 300px;"> 
                    <canvas id="userOrderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/admin/analitik.js') }}"></script>
@endpush