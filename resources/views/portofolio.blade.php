@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Portfolio</h1>
            <p class="text-lg text-gray-600">Koleksi pekerjaan dan proyek kami</p>
        </div>

        <!-- Portfolio Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Placeholder items -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <div class="bg-gray-300 h-48"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Proyek 1</h3>
                    <p class="text-gray-600 text-sm">Deskripsi proyek pertama kami</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <div class="bg-gray-300 h-48"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Proyek 2</h3>
                    <p class="text-gray-600 text-sm">Deskripsi proyek kedua kami</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <div class="bg-gray-300 h-48"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Proyek 3</h3>
                    <p class="text-gray-600 text-sm">Deskripsi proyek ketiga kami</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
