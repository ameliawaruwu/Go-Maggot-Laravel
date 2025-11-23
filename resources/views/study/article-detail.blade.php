@extends('layouts.artikel') 

@section('title', $article->judul ?? 'Detail Artikel') {{-- Akses sebagai properti objek ->judul --}}

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/esa/artikelsatu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/esa/artikeldua.css') }}">
    <link rel="stylesheet" href="{{ asset('css/esa/artikeltiga.css') }}">
@endpush

@section('content')

<div class="main-article-wrapper">
    <div class="artikel-atas">
        <div class="artikel-isi">
            <h1>{{ $article->judul }}</h1>
            
            <p class="artikel-penulis">
                ditulis oleh <a href="#">{{ $article->penulis }}</a> 
                pada {{ date("d F Y", strtotime($article->tanggal)) }}
            </p>
        </div>

        <div class="article-content">
            {!! $article->konten !!}
        </div>

        <div class="artikel-kaki">
            @if (!empty($article->hak_cipta))
                {{ $article->hak_cipta }}
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