{{-- resources/views/dashboard/index.blade.php (Revisi Bootstrap) --}}
@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')

    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
       
        <div class="left">
            <h1 class="h3 mb-1 text-gray-800">Dashboard</h1>
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
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

    <div class="row g-4 mb4 "> 

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

    <div class="row mt-5">
        {{-- Contoh Sales Analytics (6 kolom) --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sales Analytics</h6>
                </div>
                <div class="card-body">
                    {{-- Di sini tempat chart.js canvas --}}
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Contoh Visitor Statistics (6 kolom) --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Visitor Statistics</h6>
                </div>
                <div class="card-body">
                    {{-- Di sini tempat chart.js canvas --}}
                    <canvas id="visitorsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Inisialisasi Chart.js Anda di sini
    // const ctxSales = document.getElementById('salesChart').getContext('2d');
    // new Chart(ctxSales, { ... });
</script>
@endpush