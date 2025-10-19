@extends('layouts.halajakan') 

@section('title', 'Ayo Belajar Budidaya Maggot')

@section('content')

<div id="image">
    <img src="{{ asset('images/esa/Logo Artikel Fix.png') }}" alt="Logo Artikel" width="500" height="400">
</div>

<div class="Sub-topik">
    <h2>Ayo Belajar!</h2>
    <ul>
        @foreach ($topics as $topic)
            <li>
                <summary>{{ $topic['summary'] }}</summary>
                <details>
                    <p>{{ $topic['details'] }}</p>
                </details>
            </li>
            <br>
        @endforeach
        
        <br>
        <a href="{{ route('gallery.gallery') }}" class="button">
            <span>Kunjungi Gallery kami</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                 viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                <path d="M20 2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.806 5 19s.55-.988 1.012-1H21V3a1 1 0 0 0-1-1zM9.503 5a1.503 1.503 0 1 1 0 3.006 1.503 1.503 0 0 1 0-3.006zM12 13H7l3-3 1.5 1.399L14.5 8l3.5 5h-6z"></path>
            </svg>
        </a>
    </ul>
</div>

<h2 class="text-center mt-5">Artikel Kami</h2>

<div class="container my-4">
    <div class="row justify-content-center">
        @foreach ($articles as $article)
            <div class="col-md-3 mx-2 mb-4">
                <div class="card shadow-sm" style="border-radius: 15px; overflow:hidden;">
                    <img src="{{ asset('images/esa/' . $article['image']) }}" 
                         class="card-img-top" 
                         alt="{{ $article['title'] }}" 
                         style="height:200px; object-fit:cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $article['title'] }}</h5>

                       <a href="{{ url('/artikel/' . $article['link_slug']) }}" class="btn btn-secondary mt-3">
                            Pelajari Lebih Lanjut
                        </a>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="gallery-container d-none">
    @foreach ($articles as $article)
        <x-article-card 
            :title="$article['title']" 
            :image="asset('images/esa/' . $article['image'])" 
            :slug="$article['link_slug']" 
        />
    @endforeach
</div>

@push('scripts')
<script src="{{ asset('Admin-HTML/js/script.js') }}"></script>
@endpush

@endsection
