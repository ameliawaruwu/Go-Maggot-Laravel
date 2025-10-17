{{-- Menerima variabel $cartItems dari view utama --}}
<div class="list-checkout-items" id="listCheckoutItems">
    @forelse($cartItems as $item)
        @php
            $subtotal = $item['jumlah'] * $item['harga'];
        @endphp
        <div class="checkout-item">
            <img src="{{ asset($item['gambar']) }}" alt="{{ $item['namaproduk'] }}">
            <div class="item-details">
                <div class="item-name">{{ $item['namaproduk'] }}</div>
                <div class="item-quantity">× {{ $item['jumlah'] }}</div>
            </div>
            <div class="item-price">Rp.{{ number_format($subtotal, 0, ',', '.') }}</div>
        </div>
    @empty
        <p id="emptyCheckoutMessage" style="text-align: center; padding: 20px;">
            Keranjang kosong. <a href="{{ route('keranjang.index') }}">Belanja sekarang</a>
        </p>
    @endforelse
</div>