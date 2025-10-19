@props(['type', 'message', 'id' => null])
@php
    $class = ($type === 'error') ? 'message error' : 'message';
@endphp

@if ($message)
    <div class="{{ $class }}" @if ($id) id="{{ $id }}" @endif>
        {!! $message !!}
    </div>
@endif