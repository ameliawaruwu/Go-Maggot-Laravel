@extends('layouts.app')

@section('title', 'Checkout Berhasil')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    <i class="ri-checkbox-circle-fill text-success" style="font-size: 4rem;"></i>
                    <h2 class="card-title mt-3 text-success">Checkout Berhasil!</h2>
                    
                    @if(session('success'))
                        <p class="lead">{{ session('success') }}</p>
                    @endif

                    @if($lastOrder)
                        <hr>
                        <h4 class="mb-3">Detail Pesanan Anda (Simulasi)</h4>
                        
                        <ul class="list-group list-group-flush mb-4 text-start">
                            <li class="list-group-item"><strong>ID Pesanan:</strong> {{ $lastOrder['order_id_simulasi'] }}</li>
                            <li class="list-group-item"><strong>Total Pembayaran:</strong> Rp.{{ number_format($lastOrder['total_harga'], 0, ',', '.') }}</li>
                            <li class="list-group-item"><strong>Metode Pembayaran:</strong> {{ $lastOrder['penerima']['metode_pembayaran'] }}</li>
                            <li class="list-group-item"><strong>Penerima:</strong> {{ $lastOrder['penerima']['nama_penerima'] }} ({{ $lastOrder['penerima']['nomor_telepon'] }})</li>
                            <li class="list-group-item"><strong>Alamat:</strong> {{ $lastOrder['penerima']['alamat_lengkap'] }}, {{ $lastOrder['penerima']['kota'] }}</li>
                        </ul>

                        <a href="/" class="btn btn-success mt-3">Lanjutkan Belanja</a>
                    @else
                        <p class="text-muted">Terima kasih atas pesanan Anda. Kami tidak dapat menampilkan detail pesanan terakhir saat ini.</p>
                        <a href="/" class="btn btn-primary mt-3">Kembali ke Beranda</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection