@extends('layouts.halajakan')

@section('title', 'Daftar Artikel')

@section('content')

<h4 class="text-center mt-5">Artikel Kami</h4>

<div class="container my-4">
    <div class="row justify-content-center">
        {{-- Cart artikel--}}
        @foreach ($articles as $article)
            <div class="col-md-3 mx-2 mb-4">
                <div class="card shadow-sm" style="border-radius: 15px; overflow:hidden;">

                    <img src="{{ asset('photo/' . $article->gambar) }}"
                         class="card-img-top"
                         alt="{{ $article->judul }}"
                         style="height:200px; object-fit:cover;"
                         onerror="this.onerror=null; this.src='https://placehold.co/400x200/cccccc/333333?text=Gambar+Artikel+Rusak';"
                    >

                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $article->judul }}</h5>

                        {{-- Redirect ke masing-masing artikel --}}
                        <a href="{{ route('article.show', $article->id_artikel) }}" class="btn btn-secondary mt-3">
                            Pelajari Lebih Lanjut
                        </a>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection