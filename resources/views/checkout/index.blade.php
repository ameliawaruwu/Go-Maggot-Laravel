@extends('layouts.checkout')

@section('title', 'Checkout')

@section('content')

<div class="container py-5">
     <!-- Menampilkan pesan sukses atau error -->
    <div id="checkoutMessageArea" class="row justify-content-center mb-4">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success text-center" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger text-center" role="alert">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row checkout-page-container">

        <div class="col-md-6 col-12 left-section order-1 order-md-1">
            <a href="{{ route('product.index') }}" class="back-to-cart">← Kembali ke keranjang</a>
            <h2>Keranjang Saya</h2>

            <!-- Mengambil nilai dari function index -->
            @include('components.cart-summary', ['cartItems' => $cartItems])

            <h1 class="total-summary-checkout">Total Harga: <span id="overallTotalPriceDisplayCheckout">Rp.{{ number_format($totalPrice, 0, ',', '.') }}</span></h1>
        </div>

        <div class="col-md-6 col-12 right-section order-2 order-md-2">
            <h3>Detail Pengiriman & Pembayaran</h3>
            <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
                @csrf 
                {{-- <input type="hidden" name="id_pesanan" value="{{ $draftOrderId }}"> --}}

                <div class="form-group mb-3">
                    <label for="nama_penerima">Nama Penerima</label>
                    <input type="text" id="nama_penerima" name="nama_penerima" value="{{ old('nama_penerima') }}" required class="form-control">
                    @error('nama_penerima') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="nomor_telepon">Nomor Telepon</label>
                    <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required class="form-control">
                    @error('nomor_telepon') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="alamat_lengkap">Alamat Lengkap</label>
                    <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" placeholder="Contoh: Jl. Merdeka No. 12, Kelurahan X, Kecamatan Y" required class="form-control">{{ old('alamat_lengkap') }}</textarea>
                    @error('alamat_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="pengiriman">Pengiriman</label>
                    <select id="pengiriman" name="pengiriman" required class="form-select">
                        <option value="">-- Pilih Layanan Pengiriman --</option>
                        <option value="Instan" {{ old('pengiriman') == 'Instan' ? 'selected' : '' }}>Instan</option>
                        <option value="Reguler" {{ old('pengiriman') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="Kargo" {{ old('pengiriman') == 'Kargo' ? 'selected' : '' }}>Kargo</option>
                    </select>
                    @error('pengiriman') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group mb-4">
                    <label for="metode_pembayaran">Metode Pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" required class="form-select">
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        <option value="QRIS" {{ old('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        <option value="COD" {{ old('metode_pembayaran') == 'COD' ? 'selected' : '' }}>COD</option>
                    </select>
                    @error('metode_pembayaran') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="summary-details">
                    <div class="summary-row">
                        <span>Total Produk:</span>
                        <span id="totalProdukDisplay">{{ $totalQuantity }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Total Harga:</span>
                        <span id="totalHargaFormDisplay">Rp.{{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" class="checkout-submit-button" {{ empty($cartItems) ? 'disabled' : '' }}>
                    CHECKOUT
                </button>
            </form>
        </div>
    </div>
</div>
@endsection