@extends('layouts.artikel') 

@section('title', $article['judul'] ?? 'Detail Artikel')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/esa//artikelsatu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/esa/artikeldua.css') }}">
    <link rel="stylesheet" href="{{ asset('css/esa//artikeltiga.css') }}">
@endpush

@section('content')

<div class="main-article-wrapper">
    <!--  $article berisi data dari Controller  -->
    <div class="artikel-atas">
        <div class="artikel-isi">
            <!-- JUDUL ARTIKEL -->
            <h1>{{ htmlspecialchars($article['judul']) }}</h1>
            
            <!-- PENULIS & TANGGAL -->
            <p class="artikel-penulis">
                ditulis oleh <a href="#">{{ htmlspecialchars($article['penulis']) }}</a> 
                pada {{ date("d F Y", strtotime($article['tanggal'])) }}
            </p>
        </div>

         <!-- KONTEN ARTIKEL  -->
        <div class="article-content">
            {!! $article['konten'] !!}
        </div>

        <!-- HAK CIPTA (LOGIC) -->
        <div class="artikel-kaki">
            @if (!empty($article['hak_cipta']))
                {{ htmlspecialchars($article['hak_cipta']) }}
            @else
                Copyright &copy; {{ date("Y") }} GoMaggot
            @endif
        </div>
    </div>
</div>

@push('scripts')
<!-- <script src="{{ asset('Admin-HTM/js/script.js') }}"></script> -->
@endpush

@endsection