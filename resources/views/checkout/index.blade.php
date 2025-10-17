@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="checkout-page-container">
    {{-- Menampilkan pesan sukses atau error dari Controller (sesi) --}}
    <div id="checkoutMessageArea" style="margin-bottom: 15px; text-align: center;">
        @if(session('success'))
            <div style="color: green; background-color: #e0ffe0; border: 1px solid green; padding: 10px; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="color: red; background-color: #ffe0e0; border: 1px solid red; padding: 10px; border-radius: 5px;">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="left-section">
        <a href="{{ route('keranjang.index') }}" class="back-to-cart">← Kembali ke keranjang</a>
        <h2>Keranjang Saya</h2>

        {{-- Sertakan Komponen Ringkasan Keranjang --}}
        @include('components.cart-summary', ['cartItems' => $cartItems])

        <h1 class="total-summary-checkout">Total Harga: <span id="overallTotalPriceDisplayCheckout">Rp.{{ number_format($totalPrice, 0, ',', '.') }}</span></h1>
    </div>

    <div class="right-section">
        <h3>Detail Pengiriman & Pembayaran</h3>
        {{-- Menggunakan route Laravel untuk POST form --}}
        <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
            @csrf 

            {{-- ... (Field Form lainnya sama seperti jawaban sebelumnya) ... --}}
            <div class="form-group">
                <label for="nama_penerima">Nama Penerima</label>
                <input type="text" id="nama_penerima" name="nama_penerima" value="{{ old('nama_penerima') }}" required>
                @error('nama_penerima') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="nomor_telepon">Nomor Telepon</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required>
                @error('nomor_telepon') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" placeholder="Contoh: Jl. Merdeka No. 12, Kelurahan X, Kecamatan Y" required>{{ old('alamat_lengkap') }}</textarea>
                @error('alamat_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="kota">Kota</label>
                <select id="kota" name="kota" required>
                    <option value="">Pilih Kota</option>
                    <option value="Bandung" {{ old('kota') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                    <option value="Jakarta" {{ old('kota') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                </select>
                @error('kota') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="Qris" {{ old('metode_pembayaran') == 'Qris' ? 'selected' : '' }}>Qris</option>
                    <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai (COD)</option>
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
@endsection