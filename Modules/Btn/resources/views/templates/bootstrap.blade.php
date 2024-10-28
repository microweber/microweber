@props(['id', 'style', 'size', 'url', 'blank', 'text', 'action', 'attributes','hasCustomStyles'])

@php
/*

type: layout

name: Bootstrap

description: Bootstrap button

*/
@endphp


@if(isset($hasCustomStyles) && $hasCustomStyles)
    @include('modules.btn::components.custom-css')
@endif


@if($action == 'submit')
<button type="submit" id="{{ $btnId }}" class="btn {{ $style . ' ' . $size }}" {!! $attributes !!}>
        {{ $text }}
</button>
@elseif($action == 'popup')
    @include('modules.btn::components.popup')
    <a id="{{ $btnId }}" href="javascript:{{ $popupFunctionId }}()" class="btn {{ $style . ' ' . $size }}" {!! $attributes !!}>
        {{ $text }}
    </a>
@else
<a id="{{ $btnId }}" href="{{ $url }}" @if ($blank) target="_blank" @endif class="btn {{ $style . ' ' . $size }}" {!! $attributes !!}>
        {{ $text }}
</a>
@endif
