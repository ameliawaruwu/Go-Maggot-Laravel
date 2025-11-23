@props(['title', 'image', 'articleId']) {{-- Ganti 'slug' dengan 'articleId' --}}

<div class="gallery">
    <img src="{{ asset($image) }}" alt="{{ $title }}">
    <div class="desc">{{ $title }}</div><br>
    
    {{-- Menggunakan 'articleId' dan nama parameter route yang benar: 'id_artikel' --}}
    <a href="{{ route('article.show', ['id_artikel' => $articleId]) }}" class="button">Pelajari Lebih Lanjut</a>
</div>