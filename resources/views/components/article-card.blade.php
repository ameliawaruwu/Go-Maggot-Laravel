@props(['title', 'image', 'slug'])

<div class="gallery">
    <img src="{{ asset($image) }}" alt="{{ $title }}">
    <div class="desc">{{ $title }}</div><br>
    
    <a href="{{ route('article.show', ['slug' => $slug]) }}" class="button">Pelajari Lebih Lanjut</a>
</div>