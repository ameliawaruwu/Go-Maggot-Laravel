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
                        <h4 class="mb-3">Detail Pesanan Anda</h4>
                        
                        <ul class="list-group list-group-flush mb-4 text-start">
                            <li class="list-group-item"><strong>ID Pesanan:</strong> {{ $lastOrder->id_pesanan }}</li>
                            <li class="list-group-item"><strong>Total Pembayaran:</strong> Rp.{{ number_format($lastOrder->total_harga, 0, ',', '.') }}</li>
                            <li class="list-group-item"><strong>Metode Pembayaran:</strong> {{ $lastOrder->metode_pembayaran }}</li>
                            <li class="list-group-item"><strong>Penerima:</strong> {{ $lastOrder->nama_penerima }} ({{ $lastOrder->nomor_telepon }})</li>
                            <li class="list-group-item"><strong>Alamat Pengiriman:</strong> {{ $lastOrder->alamat_pengiriman }}</li> 
                        </ul>
                        <a href="{{ route('payment.form', ['order_id' => $lastOrder->id_pesanan]) }}" 
                           class="btn btn-primary btn-lg mt-3 me-2">
                            Lanjut ke Pembayaran
                        </a>

                        <a href="/" class="btn btn-success mt-3">Lanjutkan Belanja</a>
                    @else
                        <p class="text-muted">Terima kasih atas pesanan Anda. Detail tidak ditemukan untuk ID Pesanan: {{ $order_id ?? 'Tidak Ditemukan' }}</p>
                        <a href="/" class="btn btn-primary mt-3">Kembali ke Beranda</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
@parent 
<script>
    // skrip hanya berjalan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // menghapus kunci shoppingCart dari LocalStorage
        localStorage.removeItem('shoppingCart');
        console.log('Keranjang Lokal Dihapus: Pesanan sukses disimpan.');
    });
</script>
@endsection