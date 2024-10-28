@php
/*

type: layout

name: Bootstrap

description: Bootstrap button

*/
@endphp

@if($action == 'submit')
<button type="submit" id="{{ $id }}" class="btn {{ $style . ' ' . $size }}" {!! $attributes !!}>
        {{ $text }}
</button>
@else
<a id="{{ $id }}" href="{{ $url }}" @if ($blank) target="_blank" @endif class="btn {{ $style . ' ' . $size }}" {!! $attributes !!}>
        {{ $text }}
</a>
@endif
