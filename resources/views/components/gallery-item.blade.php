{{-- Menerima variabel $item dari foreach loop --}}
<div class="item" style="background-image: url('{{ $item['imageUrl'] }}');">
    <div class="content">
        <div class="name">{{ $item['name'] }}</div>
        <div class="des">{{ $item['description'] }}</div>
        <a href="{{ $item['link'] }}"><button>See More</button></a>
    </div>
</div>