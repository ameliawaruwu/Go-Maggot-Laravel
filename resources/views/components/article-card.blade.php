@props(['title', 'image', 'articleId']) 

<div class="gallery">
    <img src="{{ asset($image) }}" alt="{{ $title }}">
    <div class="desc">{{ $title }}</div><br>
    <a href="{{ route('article.show', ['id_artikel' => $articleId]) }}" class="button">Pelajari Lebih Lanjut</a>
</div>