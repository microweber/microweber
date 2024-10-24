@php
/*

type: layout

name: Default

description: Default

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
