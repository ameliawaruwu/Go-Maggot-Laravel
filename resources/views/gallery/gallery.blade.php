@extends('layouts.galleryuser') 

@section('title', 'Galeri') 

@section('content')
<div class="container">

    <div class="slide">
        {{-- Loop data galeri yang dikirim dari Controller --}}
        @foreach ($galleryItems as $item)
            @include('components.gallery-item', ['item' => $item]) 
        @endforeach
    </div>

    <div class="button">
        <button class="prev"><i class="fa-solid fa-arrow-left"></i></button>
        <button class="next"><i class="fa-solid fa-arrow-right"></i></button>
    </div>

</div>
@endsection
