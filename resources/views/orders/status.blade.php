
@extends('layouts.orders')



@section('title', 'Status Pesanan: ' . $order_id_request)

@section('content')
    <div class="container mx-auto p-4 max-w-4xl bg-white shadow-lg rounded-xl mt-8">
        <h1 class="text-3xl font-bold text-gray-800 border-b pb-2 mb-4 text-center">
            Status Pesanan #{{ $order_id_request }}
        </h1>

       
        @if ($error_message)
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                <p class="font-bold">Status Pesanan</p>
                <p>{{ $error_message }}</p>
            </div>
            <div class="actions text-center mt-6">
                <a href="{{ route('home') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-300">Kembali ke Beranda</a>
            </div>

        @elseif ($pesanan_data)
            <div class="order-summary mb-8 p-4 border rounded-lg bg-gray-50">
                <h2 class="text-xl font-semibold mb-3 text-indigo-700">Ringkasan Pesanan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                    <p><strong>Nama Penerima:</strong> {{ $pesanan_data['nama_penerima'] ?? 'N/A' }}</p>
                    <p><strong>Tanggal Pesanan:</strong> {{ \Carbon\Carbon::parse($pesanan_data['tanggal_pesanan'] ?? now())->isoFormat('D MMMM YYYY') }}</p>
                    <p><strong>Total Harga:</strong> <span class="text-green-600 font-bold">Rp {{ number_format($pesanan_data['total_harga'] ?? 0, 0, ',', '.') }}</span></p>
                    <p><strong>Metode Pembayaran:</strong> {{ $pesanan_data['metode_pembayaran'] ?? 'N/A' }}</p>
                    <p class="md:col-span-2"><strong>Alamat Pengiriman:</strong> {{ $pesanan_data['alamat_pengiriman'] ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div class="status-box mb-8 p-4 bg-indigo-50 border-l-4 border-indigo-500 rounded-lg">
                <p class="text-lg font-bold text-indigo-800">
                    Status Saat Ini: 
                    <span class="status-text ml-2 p-1 px-3 rounded-full bg-indigo-500 text-white text-base shadow-md">{{ $status_name_from_db }}</span>
                </p>
            </div>


            <div class="status-tracker">
                <!-- {{-- Menggunakan Blade Component Status Tracker --}} -->
                <x-status-tracker :class="$dikemas_class" text="Sedang Dikemas" />
                <x-status-tracker :class="$dikirim_class" text="Sedang Dikirim" />
                <x-status-tracker :class="$sampai_class" text="Sudah Sampai" />
            </div>

            <div class="actions mt-10 text-left space-x-5">
                


                 <!-- Tombol "Kembali" -->
                <a href="{{ url()->previous() }}" class="btn bg-green-500 hover:bg-green-600 text-black px-6 py-2 rounded-full transition duration-300 shadow-md font-semibold">
                    ← Kembali
                </a>
                
                <!--Tombol Aksi Utama (Refresh Status, Bentuk Pil-->
                <a href="{{ route('orders.status', ['order_id' => $pesanan_data['id_pesanan']]) }}" class="btn bg-blue-500 hover:bg-blue-600 text-black px-6 py-2 rounded-full transition duration-300 shadow-md">
                    Refresh Status
                </a>
            </div>
        @endif
    </div>
@endsection




