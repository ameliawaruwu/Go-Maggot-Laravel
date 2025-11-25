@extends('layouts.payment') 
@section('title', 'Form Pembayaran')

@section('content')
    @if ($message)
        @php
            
            $isError = (strpos($message, 'tidak ditemukan') !== false || strpos($message, 'gagal') !== false);
            $messageClass = $isError ? 'alert-danger' : 'alert-success'; 
        @endphp
        <div class="alert {{ $messageClass }} mb-4" id="messageBox">{!! $message !!}</div>
    @endif

   
    <!--Bagian sukses pembayaran -->
   
    @if ($payment_success)
        <div class="thank-you-container text-center">
            <h2> Terima Kasih!</h2>
            <h2>Checkout dan Pembayaran Berhasil Terkirim</h2>
            <p class="lead">Pesanan Anda telah berhasil diproses.</p>
            
            <!-- Menggunakan variabel total_pembayaran dari Controller -->
            <p>Jumlah Pembayaran: <strong>Rp {{ number_format($total_pembayaran, 0, ',', '.') }}</strong></p>
            <p>ID Pesanan Anda: <strong>{{ $id_pesanan }}</strong></p>

            <p class="text-muted">Kami akan segera memproses pesanan Anda. Silakan cek status pesanan secara berkala.</p>
            
            <div class="actions mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a> 
                
                @if($id_pesanan)
                    <a href="{{ route('orders.status', ['order_id' => $id_pesanan]) }}" class="btn btn-secondary">Cek Status Pesanan</a>
                @endif
            </div>
        </div>
    @else
   

    <!-- Bagian form pembayaran  -->

        <div class="container form-container">
            <h1><center>Form Pembayaran</center></h1>

            <!-- Cek ketersediaan ID pesanan  -->
            @if ($id_pesanan === null)
                <div class="alert alert-warning">
                    <p><strong>Perhatian:</strong> ID Pesanan tidak ditemukan.</p>
                    <p>Silakan kembali ke halaman awal/checkout untuk memulai proses pesanan.</p>
                </div>
                <div class="text-center">
                    <a href="{{ url('checkout') }}" class="btn btn-warning">Kembali ke Checkout</a>
                </div>
            @else
                
                
                <!--Form pembayaran-->
                <form id="paymentForm" method="post" action="{{ route('payment.process') }}" enctype="multipart/form-data" autocomplete="off">
    @csrf {{-- Token CSRF wajib --}}
    </form>

                    <center><h2>Scan QR Code</h2></center>
                    <center><img src="{{ asset('images/Contoh QR.jpeg') }}" alt="QR Code" width="200" height="200"></center>

                    <!-- Data detail pengirim dari variabel Controller  -->
                    <label for="name">Nama Pengirim/Pemilik Rekening</label>
                    <input type="text" id="name" name="name" placeholder="Nama lengkap" value="{{ $name }}" required>
                    
                    <label for="phone">No Telepon Pengirim</label>
                    <input type="tel" id="phone" name="phone" placeholder="081234567890" value="{{ $phone }}" required>

                    <label for="address">Alamat Pengiriman</label>
                    <textarea id="address" name="address" placeholder="Alamat lengkap" required>{{ $address }}</textarea>
                    
                    <label for="payment_proof">Bukti Pembayaran (JPG, PNG, PDF, JPEG)</label>
                    <input type="file" id="payment_proof" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>

                    <input type="hidden" name="id_pesanan" value="{{ $id_pesanan }}">

                    <button type="submit" class="btn btn-success mt-3">Kirim Bukti Pembayaran</button>
                </form>
            @endif
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageBox = document.getElementById('messageBox');
            if (messageBox) {
                messageBox.style.transition = 'opacity 1s ease-out'; 
                setTimeout(() => {
                    messageBox.style.opacity = '0';
                    setTimeout(() => {
                        messageBox.remove(); 
                    }, 1000); 
                }, 5000); 
            }
        });
    </script>
@endsection


