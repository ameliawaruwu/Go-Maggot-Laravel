<div class="list-checkout-items" id="listCheckoutItems">
    @forelse($cartItems as $item)
@php
 $jumlah = $item['jumlah'] ?? 0;
 $harga = $item['harga'] ?? 0;
 $subtotal = $jumlah * $harga;

 $namaProduk = $item['namaproduk'] ?? 'Produk Tidak Dikenal';
 $gambarProduk = $item['gambar'] ?? 'placeholder.jpg'; 
 @endphp
 <div class="checkout-item">
<img src="{{ $gambarProduk }}" alt="{{ $namaProduk }}">
 <div class="item-details">
<div class="item-name">{{ $namaProduk }}</div>
<div class="item-quantity">x {{ $jumlah }}</div>
 </div>
<div class="item-price">Rp.{{ number_format($subtotal, 0, ',', '.') }}</div>
</div>
@empty
<p id="emptyCheckoutMessage" style="text-align: center; padding: 20px;">
Keranjang kosong. <a href="{{ route('product.index') }}">Belanja sekarang</a>
 </p>
     @endforelse
</div>